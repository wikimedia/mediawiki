<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\Block;

use LogicException;
use MediaWiki\Config\ServiceOptions;
use MediaWiki\HookContainer\HookContainer;
use MediaWiki\HookContainer\HookRunner;
use MediaWiki\MainConfigNames;
use MediaWiki\MediaWikiServices;
use MediaWiki\Message\Message;
use MediaWiki\Request\ProxyLookup;
use MediaWiki\Request\WebRequest;
use MediaWiki\Request\WebResponse;
use MediaWiki\User\User;
use MediaWiki\User\UserFactory;
use MediaWiki\User\UserIdentity;
use MediaWiki\User\UserIdentityUtils;
use MWCryptHash;
use Psr\Log\LoggerInterface;
use Wikimedia\IPSet;
use Wikimedia\IPUtils;

/**
 * A service class for checking blocks.
 * To obtain an instance, use MediaWikiServices::getInstance()->getBlockManager().
 *
 * @since 1.34 Refactored from User and Block.
 */
class BlockManager {
	/**
	 * @internal For use by ServiceWiring
	 */
	public const CONSTRUCTOR_OPTIONS = [
		MainConfigNames::ApplyIpBlocksToXff,
		MainConfigNames::CookieSetOnAutoblock,
		MainConfigNames::CookieSetOnIpBlock,
		MainConfigNames::DnsBlacklistUrls,
		MainConfigNames::EnableDnsBlacklist,
		MainConfigNames::ProxyList,
		MainConfigNames::ProxyWhitelist,
		MainConfigNames::SecretKey,
		MainConfigNames::SoftBlockRanges,
	];

	private ServiceOptions $options;
	private UserFactory $userFactory;
	private UserIdentityUtils $userIdentityUtils;
	private LoggerInterface $logger;
	private HookRunner $hookRunner;
	private DatabaseBlockStore $blockStore;
	private BlockTargetFactory $blockTargetFactory;
	private ProxyLookup $proxyLookup;

	private BlockCache $userBlockCache;
	private BlockCache $createAccountBlockCache;

	public function __construct(
		ServiceOptions $options,
		UserFactory $userFactory,
		UserIdentityUtils $userIdentityUtils,
		LoggerInterface $logger,
		HookContainer $hookContainer,
		DatabaseBlockStore $blockStore,
		BlockTargetFactory $blockTargetFactory,
		ProxyLookup $proxyLookup
	) {
		$options->assertRequiredOptions( self::CONSTRUCTOR_OPTIONS );
		$this->options = $options;
		$this->userFactory = $userFactory;
		$this->userIdentityUtils = $userIdentityUtils;
		$this->logger = $logger;
		$this->hookRunner = new HookRunner( $hookContainer );
		$this->blockStore = $blockStore;
		$this->blockTargetFactory = $blockTargetFactory;
		$this->proxyLookup = $proxyLookup;

		$this->userBlockCache = new BlockCache;
		$this->createAccountBlockCache = new BlockCache;
	}

	/**
	 * Get the blocks that apply to a user. If there is only one, return that, otherwise
	 * return a composite block that combines the strictest features of the applicable
	 * blocks.
	 *
	 * Different blocks may be sought, depending on the user and their permissions. The
	 * user may be:
	 * (1) The global user (and can be affected by IP blocks). The global request object
	 * is needed for checking the IP address, the XFF header and the cookies.
	 * (2) The global user (and exempt from IP blocks). The global request object is
	 * available.
	 * (3) Another user (not the global user). No request object is available or needed;
	 * just look for a block against the user account.
	 *
	 * Cases #1 and #2 check whether the global user is blocked in practice; the block
	 * may due to their user account being blocked or to an IP address block or cookie
	 * block (or multiple of these). Case #3 simply checks whether a user's account is
	 * blocked, and does not determine whether the person using that account is affected
	 * in practice by any IP address or cookie blocks.
	 *
	 * @deprecated since 1.42 Use getBlock(), which is the same except that it expects
	 *   the caller to do ipblock-exempt permission checking and to set $request to null
	 *   if the user is exempt from IP blocks.
	 *
	 * @param UserIdentity $user
	 * @param WebRequest|null $request The global request object if the user is the
	 *  global user (cases #1 and #2), otherwise null (case #3). The IP address and
	 *  information from the request header are needed to find some types of blocks.
	 * @param bool $fromReplica Whether to check the replica DB first.
	 *  To improve performance, non-critical checks are done against replica DBs.
	 *  Check when actually saving should be done against primary.
	 * @param bool $disableIpBlockExemptChecking This is used internally to prevent
	 *   a infinite recursion with autopromote. See T270145.
	 * @return AbstractBlock|null The most relevant block, or null if there is no block.
	 */
	public function getUserBlock(
		UserIdentity $user,
		$request,
		$fromReplica,
		$disableIpBlockExemptChecking = false
	) {
		wfDeprecated( __METHOD__, '1.42' );
		// If this is the global user, they may be affected by IP blocks (case #1),
		// or they may be exempt (case #2). If affected, look for additional blocks
		// against the IP address and referenced in a cookie.
		$checkIpBlocks = $request &&
			// Because calling getBlock within Autopromote leads back to here,
			// thus causing a infinite recursion. We fix this by not checking for
			// ipblock-exempt when calling getBlock within Autopromote.
			// See T270145.
			!$disableIpBlockExemptChecking &&
			!$this->isIpBlockExempt( $user );

		return $this->getBlock(
			$user,
			$checkIpBlocks ? $request : null,
			$fromReplica
		);
	}

	/**
	 * Get the blocks that apply to a user. If there is only one, return that, otherwise
	 * return a composite block that combines the strictest features of the applicable
	 * blocks.
	 *
	 * If the user is exempt from IP blocks, the request should be null.
	 *
	 * @since 1.42
	 * @param UserIdentity $user The user performing the action
	 * @param WebRequest|null $request The request to use for IP and cookie
	 *   blocks, or null to skip checking for such blocks. If the user has the
	 *   ipblock-exempt right, the request should be null.
	 * @param bool $fromReplica Whether to check the replica DB first.
	 *   To improve performance, non-critical checks are done against replica DBs.
	 *   Check when actually saving should be done against primary.
	 * @return AbstractBlock|null
	 */
	public function getBlock(
		UserIdentity $user,
		?WebRequest $request,
		$fromReplica = true
	): ?AbstractBlock {
		$fromPrimary = !$fromReplica;
		$ip = null;

		// TODO: normalise the fromPrimary parameter when replication is not configured.
		// Maybe DatabaseBlockStore can tell us about the LoadBalancer configuration.
		$cacheKey = new BlockCacheKey(
			$request,
			$user,
			$fromPrimary
		);
		$block = $this->userBlockCache->get( $cacheKey );
		if ( $block !== null ) {
			$this->logger->debug( "Block cache hit with key {$cacheKey}" );
			return $block ?: null;
		}
		$this->logger->debug( "Block cache miss with key {$cacheKey}" );

		if ( $request ) {

			// Case #1: checking the global user, including IP blocks
			$ip = $request->getIP();
			// For soft blocks, i.e. blocks that don't block logged-in users,
			// temporary users are treated as anon users, and are blocked.
			$applySoftBlocks = !$this->userIdentityUtils->isNamed( $user );

			$xff = $request->getHeader( 'X-Forwarded-For' );

			$blocks = array_merge(
				$this->blockStore->newListFromTarget( $user, $ip, $fromPrimary ),
				$this->getSystemIpBlocks( $ip, $applySoftBlocks ),
				$this->getXffBlocks( $ip, $xff, $applySoftBlocks, $fromPrimary ),
				$this->getCookieBlock( $user, $request )
			);
		} else {

			// Case #2: checking the global user, but they are exempt from IP blocks
			// and cookie blocks, so we only check for a user account block.
			// Case #3: checking whether another user's account is blocked.
			$blocks = $this->blockStore->newListFromTarget( $user, null, $fromPrimary );

		}

		$block = $this->createGetBlockResult( $ip, $blocks );

		$legacyUser = $this->userFactory->newFromUserIdentity( $user );
		$this->hookRunner->onGetUserBlock( clone $legacyUser, $ip, $block );

		$this->userBlockCache->set( $cacheKey, $block ?: false );
		return $block;
	}

	/**
	 * Clear the cache of any blocks that refer to the specified user
	 */
	public function clearUserCache( UserIdentity $user ) {
		$this->userBlockCache->clearUser( $user );
		$this->createAccountBlockCache->clearUser( $user );
	}

	/**
	 * Get the block which applies to a create account action, if there is any
	 *
	 * @since 1.42
	 * @param UserIdentity $user
	 * @param WebRequest|null $request The request, or null to omit IP address
	 *   and cookie blocks. If the user has the ipblock-exempt right, null
	 *   should be passed.
	 * @param bool $fromReplica
	 * @return AbstractBlock|null
	 */
	public function getCreateAccountBlock(
		UserIdentity $user,
		?WebRequest $request,
		$fromReplica
	) {
		$key = new BlockCacheKey( $request, $user, $fromReplica );
		$cachedBlock = $this->createAccountBlockCache->get( $key );
		if ( $cachedBlock !== null ) {
			$this->logger->debug( "Create account block cache hit with key {$key}" );
			return $cachedBlock ?: null;
		}
		$this->logger->debug( "Create account block cache miss with key {$key}" );

		$applicableBlocks = [];
		$userBlock = $this->getBlock( $user, $request, $fromReplica );
		if ( $userBlock ) {
			$applicableBlocks = $userBlock->toArray();
		}

		// T15611: if the IP address the user is trying to create an account from is
		// blocked with createaccount disabled, prevent new account creation there even
		// when the user is logged in
		if ( $request ) {
			$ipBlock = $this->blockStore->newFromTarget(
				null, $request->getIP()
			);
			if ( $ipBlock ) {
				$applicableBlocks = array_merge( $applicableBlocks, $ipBlock->toArray() );
			}
		}

		foreach ( $applicableBlocks as $i => $block ) {
			if ( !$block->appliesToRight( 'createaccount' ) ) {
				unset( $applicableBlocks[$i] );
			}
		}
		$result = $this->createGetBlockResult(
			$request ? $request->getIP() : null,
			$applicableBlocks
		);
		$this->createAccountBlockCache->set( $key, $result ?: false );
		return $result;
	}

	/**
	 * Remove elements of a block which fail a callback test.
	 *
	 * @since 1.42
	 * @param Block|null $block The block, or null to pass in zero blocks.
	 * @param callable $callback The callback, which will be called once for
	 *   each non-composite component of the block. The only parameter is the
	 *   non-composite Block. It should return true, to keep that component,
	 *   or false, to remove that component.
	 * @return Block|null
	 *    - If there are zero remaining elements, null will be returned.
	 *    - If there is one remaining element, a DatabaseBlock or some other
	 *      non-composite block will be returned.
	 *    - If there is more than one remaining element, a CompositeBlock will
	 *      be returned.
	 */
	public function filter( ?Block $block, $callback ) {
		if ( !$block ) {
			return null;
		} elseif ( $block instanceof CompositeBlock ) {
			$blocks = $block->getOriginalBlocks();
			$originalCount = count( $blocks );
			foreach ( $blocks as $i => $originalBlock ) {
				if ( !$callback( $originalBlock ) ) {
					unset( $blocks[$i] );
				}
			}
			if ( !$blocks ) {
				return null;
			} elseif ( count( $blocks ) === 1 ) {
				return $blocks[ array_key_first( $blocks ) ];
			} elseif ( count( $blocks ) === $originalCount ) {
				return $block;
			} else {
				return $block->withOriginalBlocks( array_values( $blocks ) );
			}
		} elseif ( !$callback( $block ) ) {
			return null;
		} else {
			return $block;
		}
	}

	/**
	 * Determine if a user is exempt from IP blocks
	 * @param UserIdentity $user
	 * @return bool
	 */
	private function isIpBlockExempt( UserIdentity $user ) {
		return MediaWikiServices::getInstance()->getPermissionManager()
			->userHasRight( $user, 'ipblock-exempt' );
	}

	/**
	 * @param string|null $ip
	 * @param AbstractBlock[] $blocks
	 * @return AbstractBlock|null
	 */
	private function createGetBlockResult( ?string $ip, array $blocks ): ?AbstractBlock {
		// Filter out any duplicated blocks, e.g. from the cookie
		$blocks = $this->getUniqueBlocks( $blocks );

		if ( count( $blocks ) === 0 ) {
			return null;
		} elseif ( count( $blocks ) === 1 ) {
			return $blocks[ 0 ];
		} else {
			$compositeBlock = CompositeBlock::createFromBlocks( ...$blocks );
			$target = $ip === null ? null : $this->blockTargetFactory->newAnonIpBlockTarget( $ip );
			$compositeBlock->setTarget( $target );
			return $compositeBlock;
		}
	}

	/**
	 * Get the blocks that apply to an IP address. If there is only one, return that, otherwise
	 * return a composite block that combines the strictest features of the applicable blocks.
	 *
	 * @since 1.38
	 * @param string $ip
	 * @param bool $fromReplica
	 * @return AbstractBlock|null
	 */
	public function getIpBlock( string $ip, bool $fromReplica ): ?AbstractBlock {
		if ( !IPUtils::isValid( $ip ) ) {
			return null;
		}

		$blocks = array_merge(
			$this->blockStore->newListFromTarget( $ip, $ip, !$fromReplica ),
			$this->getSystemIpBlocks( $ip, true )
		);

		return $this->createGetBlockResult( $ip, $blocks );
	}

	/**
	 * Get the cookie block, if there is one.
	 *
	 * @param UserIdentity $user
	 * @param WebRequest $request
	 * @return AbstractBlock[]
	 */
	private function getCookieBlock( UserIdentity $user, WebRequest $request ): array {
		$cookieBlock = $this->getBlockFromCookieValue( $user, $request );

		return $cookieBlock instanceof DatabaseBlock ? [ $cookieBlock ] : [];
	}

	/**
	 * Get any system blocks against the IP address.
	 *
	 * @param string $ip
	 * @param bool $applySoftBlocks
	 * @return AbstractBlock[]
	 */
	private function getSystemIpBlocks( string $ip, bool $applySoftBlocks ): array {
		$blocks = [];

		// Proxy blocking
		if ( !in_array( $ip, $this->options->get( MainConfigNames::ProxyWhitelist ) ) ) {
			// Local list
			if ( $this->isLocallyBlockedProxy( $ip ) ) {
				$blocks[] = $this->newSystemBlock( $ip, 'proxy', 'proxyblockreason', false );
			} elseif ( $applySoftBlocks && $this->isDnsBlacklisted( $ip ) ) {
				$blocks[] = $this->newSystemBlock( $ip, 'dnsbl', 'sorbsreason', true );
			}
		}

		// Soft blocking
		if ( $applySoftBlocks && IPUtils::isInRanges( $ip, $this->options->get( MainConfigNames::SoftBlockRanges ) ) ) {
			$blocks[] = $this->newSystemBlock(
				$ip, 'wgSoftBlockRanges', 'softblockrangesreason', true );
		}

		return $blocks;
	}

	/**
	 * Create a new system block
	 *
	 * @param string $ip
	 * @param string $type The system block type
	 * @param string $reasonMsg The message key to use as the reason
	 * @param bool $anonOnly Whether the block is a soft block
	 * @return SystemBlock
	 */
	private function newSystemBlock( string $ip, string $type, string $reasonMsg, bool $anonOnly
	): SystemBlock {
		return new SystemBlock( [
			'reason' => new Message( $reasonMsg ),
			'target' => $this->blockTargetFactory->newAnonIpBlockTarget( $ip ),
			'systemBlock' => $type,
			'anonOnly' => $anonOnly
		] );
	}

	/**
	 * If `$wgApplyIpBlocksToXff` is truthy and the IP that the user is accessing the wiki from is not in
	 * `$wgProxyWhitelist`, then get the blocks that apply to the IP(s) in the X-Forwarded-For HTTP
	 * header.
	 *
	 * @param string $ip
	 * @param string $xff
	 * @param bool $applySoftBlocks
	 * @param bool $fromPrimary
	 * @return AbstractBlock[]
	 */
	private function getXffBlocks(
		string $ip,
		string $xff,
		bool $applySoftBlocks,
		bool $fromPrimary
	): array {
		// (T25343) Apply IP blocks to the contents of XFF headers, if enabled
		if ( $this->options->get( MainConfigNames::ApplyIpBlocksToXff )
			&& !in_array( $ip, $this->options->get( MainConfigNames::ProxyWhitelist ) )
		) {
			$xff = array_map( 'trim', explode( ',', $xff ) );
			$xff = array_diff( $xff, [ $ip ] );
			$xffblocks = $this->getBlocksForIPList( $xff, $applySoftBlocks, $fromPrimary );

			// (T285159) Exclude autoblocks from XFF headers to prevent spoofed
			// headers uncovering the IPs of autoblocked users
			$xffblocks = array_filter( $xffblocks, static function ( $block ) {
				return $block->getType() !== Block::TYPE_AUTO;
			} );

			return $xffblocks;
		}

		return [];
	}

	/**
	 * Get all blocks that match any IP from an array of IP addresses
	 *
	 * @internal Public to support deprecated method in DatabaseBlock
	 *
	 * @param array $ipChain List of IPs (strings), usually retrieved from the
	 *     X-Forwarded-For header of the request
	 * @param bool $applySoftBlocks Include soft blocks (anonymous-only blocks). These
	 *     should only block anonymous and temporary users.
	 * @param bool $fromPrimary Whether to query the primary or replica DB
	 * @return DatabaseBlock[]
	 */
	public function getBlocksForIPList( array $ipChain, bool $applySoftBlocks, bool $fromPrimary ) {
		if ( $ipChain === [] ) {
			return [];
		}

		$ips = [];
		foreach ( array_unique( $ipChain ) as $ipaddr ) {
			// Discard invalid IP addresses. Since XFF can be spoofed and we do not
			// necessarily trust the header given to us, make sure that we are only
			// checking for blocks on well-formatted IP addresses (IPv4 and IPv6).
			// Do not treat private IP spaces as special as it may be desirable for wikis
			// to block those IP ranges in order to stop misbehaving proxies that spoof XFF.
			if ( !IPUtils::isValid( $ipaddr ) ) {
				continue;
			}
			// Don't check trusted IPs (includes local CDNs which will be in every request)
			if ( $this->proxyLookup->isTrustedProxy( $ipaddr ) ) {
				continue;
			}
			$ips[] = $ipaddr;
		}
		return $this->blockStore->newListFromIPs( $ips, $applySoftBlocks, $fromPrimary );
	}

	/**
	 * Given a list of blocks, return a list of unique blocks.
	 *
	 * This usually means that each block has a unique ID. For a block with ID null,
	 * if it's an autoblock, it will be filtered out if the parent block is present;
	 * if not, it is assumed to be a unique system block, and kept.
	 *
	 * @param AbstractBlock[] $blocks
	 * @return AbstractBlock[]
	 */
	private function getUniqueBlocks( array $blocks ) {
		$systemBlocks = [];
		$databaseBlocks = [];

		foreach ( $blocks as $block ) {
			if ( $block instanceof SystemBlock ) {
				$systemBlocks[] = $block;
			} elseif ( $block->getType() === DatabaseBlock::TYPE_AUTO && $block instanceof DatabaseBlock ) {
				if ( !isset( $databaseBlocks[$block->getParentBlockId()] ) ) {
					$databaseBlocks[$block->getParentBlockId()] = $block;
				}
			} else {
				// @phan-suppress-next-line PhanTypeMismatchDimAssignment getId is not null here
				$databaseBlocks[$block->getId()] = $block;
			}
		}

		return array_values( array_merge( $systemBlocks, $databaseBlocks ) );
	}

	/**
	 * Try to load a block from an ID given in a cookie value.
	 *
	 * If the block is invalid, doesn't exist, or the cookie value is malformed, no
	 * block will be loaded. In these cases the cookie will either (1) be replaced
	 * with a valid cookie or (2) removed, next time trackBlockWithCookie is called.
	 *
	 * @param UserIdentity $user
	 * @param WebRequest $request
	 * @return DatabaseBlock|false The block object, or false if none could be loaded.
	 */
	private function getBlockFromCookieValue(
		UserIdentity $user,
		WebRequest $request
	) {
		$cookieValue = $request->getCookie( 'BlockID' );
		if ( $cookieValue === null ) {
			return false;
		}

		$blockCookieId = $this->getIdFromCookieValue( $cookieValue );
		if ( $blockCookieId !== null ) {
			$block = $this->blockStore->newFromID( $blockCookieId );
			if (
				$block instanceof DatabaseBlock &&
				$this->shouldApplyCookieBlock( $block, !$user->isRegistered() )
			) {
				return $block;
			}
		}

		return false;
	}

	/**
	 * Check if the block loaded from the cookie should be applied.
	 *
	 * @param DatabaseBlock $block
	 * @param bool $isAnon The user is logged out
	 * @return bool The block should be applied
	 */
	private function shouldApplyCookieBlock( DatabaseBlock $block, $isAnon ) {
		if ( !$block->isExpired() ) {
			switch ( $block->getType() ) {
				case DatabaseBlock::TYPE_IP:
				case DatabaseBlock::TYPE_RANGE:
					// If block is type IP or IP range, load only
					// if user is not logged in (T152462)
					return $isAnon &&
						$this->options->get( MainConfigNames::CookieSetOnIpBlock );
				case DatabaseBlock::TYPE_USER:
					return $block->isAutoblocking() &&
						$this->options->get( MainConfigNames::CookieSetOnAutoblock );
				default:
					return false;
			}
		}
		return false;
	}

	/**
	 * Check if an IP address is in the local proxy list
	 *
	 * @param string $ip
	 * @return bool
	 */
	private function isLocallyBlockedProxy( $ip ) {
		$proxyList = $this->options->get( MainConfigNames::ProxyList );
		if ( !$proxyList ) {
			return false;
		}

		if ( !is_array( $proxyList ) ) {
			// Load values from the specified file
			$proxyList = array_map( 'trim', file( $proxyList ) );
		}

		$proxyListIPSet = new IPSet( $proxyList );
		return $proxyListIPSet->match( $ip );
	}

	/**
	 * Whether the given IP is in a DNS blacklist.
	 *
	 * @param string $ip IP to check
	 * @param bool $checkAllowed Whether to check $wgProxyWhitelist first
	 * @return bool True if blacklisted.
	 */
	public function isDnsBlacklisted( $ip, $checkAllowed = false ) {
		if ( !$this->options->get( MainConfigNames::EnableDnsBlacklist ) ||
			( $checkAllowed && in_array( $ip, $this->options->get( MainConfigNames::ProxyWhitelist ) ) )
		) {
			return false;
		}

		return $this->inDnsBlacklist( $ip, $this->options->get( MainConfigNames::DnsBlacklistUrls ) );
	}

	/**
	 * Whether the given IP is in a given DNS blacklist.
	 *
	 * @param string $ip IP to check
	 * @param string[] $bases URL of the DNS blacklist
	 * @return bool True if blacklisted.
	 */
	private function inDnsBlacklist( $ip, array $bases ) {
		$found = false;
		// @todo FIXME: IPv6 ???  (https://bugs.php.net/bug.php?id=33170)
		if ( IPUtils::isIPv4( $ip ) ) {
			// Reverse IP, T23255
			$ipReversed = implode( '.', array_reverse( explode( '.', $ip ) ) );

			foreach ( $bases as $base ) {
				// Make hostname
				// If we have an access key, use that too (ProjectHoneypot, etc.)
				$basename = $base;
				if ( is_array( $base ) ) {
					if ( count( $base ) >= 2 ) {
						// Access key is 1, base URL is 0
						$hostname = "{$base[1]}.$ipReversed.{$base[0]}";
					} else {
						$hostname = "$ipReversed.{$base[0]}";
					}
					$basename = $base[0];
				} else {
					$hostname = "$ipReversed.$base";
				}

				// Send query
				$ipList = $this->checkHost( $hostname );

				if ( $ipList ) {
					$this->logger->info(
						'Hostname {hostname} is {ipList}, it\'s a proxy says {basename}!',
						[
							'hostname' => $hostname,
							'ipList' => $ipList[0],
							'basename' => $basename,
						]
					);
					$found = true;
					break;
				}

				$this->logger->debug( "Requested $hostname, not found in $basename." );
			}
		}

		return $found;
	}

	/**
	 * Wrapper for mocking in tests.
	 *
	 * @param string $hostname DNSBL query
	 * @return string[]|false IPv4 array, or false if the IP is not blacklisted
	 */
	protected function checkHost( $hostname ) {
		return gethostbynamel( $hostname );
	}

	/**
	 * Set the 'BlockID' cookie depending on block type and user authentication status.
	 *
	 * If a block cookie is already set, this will check the block that the cookie references
	 * and do the following:
	 *  - If the block is a valid block that should be applied, do nothing and return early.
	 *    This ensures that the cookie's expiry time is based on the time of the first page
	 *    load or attempt. (See discussion on T233595.)
	 *  - If the block is invalid (e.g. has expired), clear the cookie and continue to check
	 *    whether there is another block that should be tracked.
	 *  - If the block is a valid block, but should not be tracked by a cookie, clear the
	 *    cookie and continue to check whether there is another block that should be tracked.
	 *
	 * Must be called after the User object is loaded, and before headers are sent.
	 *
	 * @since 1.34
	 * @param User $user
	 * @param WebResponse $response The response on which to set the cookie.
	 */
	public function trackBlockWithCookie( User $user, WebResponse $response ) {
		if ( !$this->options->get( MainConfigNames::CookieSetOnIpBlock ) &&
			!$this->options->get( MainConfigNames::CookieSetOnAutoblock ) ) {
			// Cookie blocks are disabled, return early to prevent executing unnecessary logic.
			return;
		}

		$request = $user->getRequest();

		if ( $request->getCookie( 'BlockID' ) !== null ) {
			$cookieBlock = $this->getBlockFromCookieValue( $user, $request );
			if ( $cookieBlock && $this->shouldApplyCookieBlock( $cookieBlock, $user->isAnon() ) ) {
				return;
			}
			// The block pointed to by the cookie is invalid or should not be tracked.
			$this->clearBlockCookie( $response );
		}

		if ( !$user->isSafeToLoad() ) {
			// Prevent a circular dependency by not allowing this method to be called
			// before or while the user is being loaded.
			// E.g. User > BlockManager > Block > Message > getLanguage > User.
			// See also T180050 and T226777.
			throw new LogicException( __METHOD__ . ' requires a loaded User object' );
		}
		if ( $response->headersSent() ) {
			throw new LogicException( __METHOD__ . ' must be called pre-send' );
		}

		$block = $user->getBlock();
		$isAnon = $user->isAnon();

		if ( $block ) {
			foreach ( $block->toArray() as $originalBlock ) {
				// TODO: Improve on simply tracking the first trackable block (T225654)
				if ( $originalBlock instanceof DatabaseBlock
					&& $this->shouldTrackBlockWithCookie( $originalBlock, $isAnon )
				) {
					$this->setBlockCookie( $originalBlock, $response );
					return;
				}
			}
		}
	}

	/**
	 * Set the 'BlockID' cookie to this block's ID and expiry time. The cookie's expiry will be
	 * the same as the block's, to a maximum of 24 hours.
	 *
	 * @since 1.34
	 * @param DatabaseBlock $block
	 * @param WebResponse $response The response on which to set the cookie.
	 */
	private function setBlockCookie( DatabaseBlock $block, WebResponse $response ) {
		// Calculate the default expiry time.
		$maxExpiryTime = wfTimestamp( TS_MW, (int)wfTimestamp() + ( 24 * 60 * 60 ) );

		// Use the block's expiry time only if it's less than the default.
		$expiryTime = $block->getExpiry();
		if ( $expiryTime === 'infinity' || $expiryTime > $maxExpiryTime ) {
			$expiryTime = $maxExpiryTime;
		}

		// Set the cookie
		$expiryValue = (int)wfTimestamp( TS_UNIX, $expiryTime );
		$cookieOptions = [ 'httpOnly' => false ];
		$cookieValue = $this->getCookieValue( $block );
		$response->setCookie( 'BlockID', $cookieValue, $expiryValue, $cookieOptions );
	}

	/**
	 * Check if the block should be tracked with a cookie.
	 *
	 * @param DatabaseBlock $block
	 * @param bool $isAnon The user is logged out
	 * @return bool The block should be tracked with a cookie
	 */
	private function shouldTrackBlockWithCookie( DatabaseBlock $block, $isAnon ) {
		switch ( $block->getType() ) {
			case DatabaseBlock::TYPE_IP:
			case DatabaseBlock::TYPE_RANGE:
				return $isAnon && $this->options->get( MainConfigNames::CookieSetOnIpBlock );
			case DatabaseBlock::TYPE_USER:
				return !$isAnon &&
					$this->options->get( MainConfigNames::CookieSetOnAutoblock ) &&
					$block->isAutoblocking();
			default:
				return false;
		}
	}

	/**
	 * Unset the 'BlockID' cookie.
	 *
	 * @since 1.34
	 * @param WebResponse $response
	 */
	public static function clearBlockCookie( WebResponse $response ) {
		$response->clearCookie( 'BlockID', [ 'httpOnly' => false ] );
	}

	/**
	 * Get the stored ID from the 'BlockID' cookie. The cookie's value is usually a combination of
	 * the ID and a HMAC (see self::getCookieValue), but will sometimes only be the ID.
	 *
	 * @since 1.34
	 * @param string $cookieValue The string in which to find the ID.
	 * @return int|null The block ID, or null if the HMAC is present and invalid.
	 */
	private function getIdFromCookieValue( $cookieValue ) {
		// The cookie value must start with a number
		if ( !is_numeric( substr( $cookieValue, 0, 1 ) ) ) {
			return null;
		}

		// Extract the ID prefix from the cookie value (may be the whole value, if no bang found).
		$bangPos = strpos( $cookieValue, '!' );
		$id = ( $bangPos === false ) ? $cookieValue : substr( $cookieValue, 0, $bangPos );
		if ( !$this->options->get( MainConfigNames::SecretKey ) ) {
			// If there's no secret key, just use the ID as given.
			return (int)$id;
		}
		$storedHmac = substr( $cookieValue, $bangPos + 1 );
		$calculatedHmac = MWCryptHash::hmac( $id, $this->options->get( MainConfigNames::SecretKey ), false );
		if ( $calculatedHmac === $storedHmac ) {
			return (int)$id;
		} else {
			return null;
		}
	}

	/**
	 * Get the BlockID cookie's value for this block. This is usually the block ID concatenated
	 * with an HMAC in order to avoid spoofing (T152951), but if wgSecretKey is not set will just
	 * be the block ID.
	 *
	 * @since 1.34
	 * @param DatabaseBlock $block
	 * @return string The block ID, probably concatenated with "!" and the HMAC.
	 */
	private function getCookieValue( DatabaseBlock $block ) {
		$id = (string)$block->getId();
		if ( !$this->options->get( MainConfigNames::SecretKey ) ) {
			// If there's no secret key, don't append a HMAC.
			return $id;
		}
		$hmac = MWCryptHash::hmac( $id, $this->options->get( MainConfigNames::SecretKey ), false );
		return $id . '!' . $hmac;
	}
}
