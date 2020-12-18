<?php
/**
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along
 * with this program; if not, write to the Free Software Foundation, Inc.,
 * 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA.
 * http://www.gnu.org/copyleft/gpl.html
 *
 * @file
 */

namespace MediaWiki\Block;

use DateTime;
use DateTimeZone;
use LogicException;
use MediaWiki\Config\ServiceOptions;
use MediaWiki\HookContainer\HookContainer;
use MediaWiki\HookContainer\HookRunner;
use MediaWiki\Permissions\PermissionManager;
use MediaWiki\User\UserGroupManager;
use MediaWiki\User\UserIdentity;
use Message;
use MWCryptHash;
use Psr\Log\LoggerInterface;
use User;
use WebRequest;
use WebResponse;
use Wikimedia\IPSet;
use Wikimedia\IPUtils;

/**
 * A service class for checking blocks.
 * To obtain an instance, use MediaWikiServices::getInstance()->getBlockManager().
 *
 * @since 1.34 Refactored from User and Block.
 */
class BlockManager {
	/** @var PermissionManager */
	private $permissionManager;

	/** @var ServiceOptions */
	private $options;

	/**
	 * @var array
	 * @since 1.34
	 */
	public const CONSTRUCTOR_OPTIONS = [
		'ApplyIpBlocksToXff',
		'CookieSetOnAutoblock',
		'CookieSetOnIpBlock',
		'DnsBlacklistUrls',
		'EnableDnsBlacklist',
		'ProxyList',
		'ProxyWhitelist',
		'SecretKey',
		'SoftBlockRanges',
	];

	/** @var LoggerInterface */
	private $logger;

	/** @var HookRunner */
	private $hookRunner;

	/** @var UserGroupManager */
	private $userGroupManager;

	/**
	 * @param ServiceOptions $options
	 * @param PermissionManager $permissionManager
	 * @param LoggerInterface $logger
	 * @param HookContainer $hookContainer
	 * @param UserGroupManager $userGroupManager
	 */
	public function __construct(
		ServiceOptions $options,
		PermissionManager $permissionManager,
		LoggerInterface $logger,
		HookContainer $hookContainer,
		UserGroupManager $userGroupManager
	) {
		$options->assertRequiredOptions( self::CONSTRUCTOR_OPTIONS );
		$this->options = $options;
		$this->permissionManager = $permissionManager;
		$this->logger = $logger;
		$this->hookRunner = new HookRunner( $hookContainer );
		$this->userGroupManager = $userGroupManager;
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
	 * @internal This should only be called by User::getBlockedStatus
	 * @param User $user
	 * @param WebRequest|null $request The global request object if the user is the
	 *  global user (cases #1 and #2), otherwise null (case #3). The IP address and
	 *  information from the request header are needed to find some types of blocks.
	 * @param bool $fromReplica Whether to check the replica DB first.
	 *  To improve performance, non-critical checks are done against replica DBs.
	 *  Check when actually saving should be done against master.
	 * @return AbstractBlock|null The most relevant block, or null if there is no block.
	 */
	public function getUserBlock( User $user, $request, $fromReplica ) {
		$fromMaster = !$fromReplica;
		$ip = null;

		// If this is the global user, they may be affected by IP blocks (case #1),
		// or they may be exempt (case #2). If affected, look for additional blocks
		// against the IP address and referenced in a cookie.
		$checkIpBlocks = $request &&
			// We cannot use userHasRight here because Autopromote
			// checkCondition calls user->getBlock which leads to
			// this function which then leads back to checkCondition
			// and continues the cycle until it runs out of memory.
			// See T270145.
			!in_array(
				'ipblock-exempt',
				$this->permissionManager->getGroupPermissions(
					$this->userGroupManager->getUserGroups( $user )
				)
			);

		if ( $request && $checkIpBlocks ) {

			// Case #1: checking the global user, including IP blocks
			$ip = $request->getIP();
			// TODO: remove dependency on DatabaseBlock (T221075)
			$blocks = DatabaseBlock::newListFromTarget( $user, $ip, $fromMaster );
			$this->getAdditionalIpBlocks( $blocks, $request, !$user->isRegistered(), $fromMaster );
			$this->getCookieBlock( $blocks, $user, $request );

		} else {

			// Case #2: checking the global user, but they are exempt from IP blocks
			// and cookie blocks, so we only check for a user account block.
			// Case #3: checking whether another user's account is blocked.
			// TODO: remove dependency on DatabaseBlock (T221075)
			$blocks = DatabaseBlock::newListFromTarget( $user, null, $fromMaster );

		}

		// Filter out any duplicated blocks, e.g. from the cookie
		$blocks = $this->getUniqueBlocks( $blocks );

		$block = null;
		if ( count( $blocks ) > 0 ) {
			if ( count( $blocks ) === 1 ) {
				$block = $blocks[ 0 ];
			} else {
				$block = new CompositeBlock( [
					'address' => $ip,
					'reason' => new Message( 'blockedtext-composite-reason' ),
					'originalBlocks' => $blocks,
				] );
			}
		}

		$this->hookRunner->onGetUserBlock( clone $user, $ip, $block );

		return $block;
	}

	/**
	 * Get the cookie block, if there is one.
	 *
	 * @param AbstractBlock[] &$blocks
	 * @param UserIdentity $user
	 * @param WebRequest $request
	 * @return void
	 */
	private function getCookieBlock( &$blocks, UserIdentity $user, WebRequest $request ) {
		$cookieBlock = $this->getBlockFromCookieValue( $user, $request );
		if ( $cookieBlock instanceof DatabaseBlock ) {
			$blocks[] = $cookieBlock;
		}
	}

	/**
	 * Check for any additional blocks against the IP address or any IPs in the XFF header.
	 *
	 * @param AbstractBlock[] &$blocks Blocks found so far
	 * @param WebRequest $request
	 * @param bool $isAnon The user is logged out
	 * @param bool $fromMaster
	 * @return void
	 */
	private function getAdditionalIpBlocks( &$blocks, WebRequest $request, $isAnon, $fromMaster ) {
		$ip = $request->getIP();

		// Proxy blocking
		if ( !in_array( $ip, $this->options->get( 'ProxyWhitelist' ) ) ) {
			// Local list
			if ( $this->isLocallyBlockedProxy( $ip ) ) {
				$blocks[] = new SystemBlock( [
					'reason' => new Message( 'proxyblockreason' ),
					'address' => $ip,
					'systemBlock' => 'proxy',
				] );
			} elseif ( $isAnon && $this->isDnsBlacklisted( $ip ) ) {
				$blocks[] = new SystemBlock( [
					'reason' => new Message( 'sorbsreason' ),
					'address' => $ip,
					'systemBlock' => 'dnsbl',
				] );
			}
		}

		// Soft blocking
		if ( $isAnon && IPUtils::isInRanges( $ip, $this->options->get( 'SoftBlockRanges' ) ) ) {
			$blocks[] = new SystemBlock( [
				'address' => $ip,
				'reason' => new Message( 'softblockrangesreason', [ $ip ] ),
				'anonOnly' => true,
				'systemBlock' => 'wgSoftBlockRanges',
			] );
		}

		// (T25343) Apply IP blocks to the contents of XFF headers, if enabled
		if ( $this->options->get( 'ApplyIpBlocksToXff' )
			&& !in_array( $ip, $this->options->get( 'ProxyWhitelist' ) )
		) {
			$xff = $request->getHeader( 'X-Forwarded-For' );
			$xff = array_map( 'trim', explode( ',', $xff ) );
			$xff = array_diff( $xff, [ $ip ] );
			// TODO: remove dependency on DatabaseBlock (T221075)
			$xffblocks = DatabaseBlock::getBlocksForIPList( $xff, $isAnon, $fromMaster );
			$blocks = array_merge( $blocks, $xffblocks );
		}
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
			} elseif ( $block->getType() === DatabaseBlock::TYPE_AUTO ) {
				/** @var DatabaseBlock $block */
				'@phan-var DatabaseBlock $block';
				if ( !isset( $databaseBlocks[$block->getParentBlockId()] ) ) {
					$databaseBlocks[$block->getParentBlockId()] = $block;
				}
			} else {
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
	 * @return DatabaseBlock|bool The block object, or false if none could be loaded.
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
			// TODO: remove dependency on DatabaseBlock (T221075)
			$block = DatabaseBlock::newFromID( $blockCookieId );
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
	 * @return bool The block sould be applied
	 */
	private function shouldApplyCookieBlock( DatabaseBlock $block, $isAnon ) {
		if ( !$block->isExpired() ) {
			switch ( $block->getType() ) {
				case DatabaseBlock::TYPE_IP:
				case DatabaseBlock::TYPE_RANGE:
					// If block is type IP or IP range, load only
					// if user is not logged in (T152462)
					return $isAnon &&
						$this->options->get( 'CookieSetOnIpBlock' );
				case DatabaseBlock::TYPE_USER:
					return $block->isAutoblocking() &&
						$this->options->get( 'CookieSetOnAutoblock' );
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
		$proxyList = $this->options->get( 'ProxyList' );
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
	 * @param bool $checkWhitelist Whether to check the whitelist first
	 * @return bool True if blacklisted.
	 */
	public function isDnsBlacklisted( $ip, $checkWhitelist = false ) {
		if ( !$this->options->get( 'EnableDnsBlacklist' ) ||
			( $checkWhitelist && in_array( $ip, $this->options->get( 'ProxyWhitelist' ) ) )
		) {
			return false;
		}

		return $this->inDnsBlacklist( $ip, $this->options->get( 'DnsBlacklistUrls' ) );
	}

	/**
	 * Whether the given IP is in a given DNS blacklist.
	 *
	 * @param string $ip IP to check
	 * @param array $bases Array of Strings: URL of the DNS blacklist
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
						"Hostname $hostname is {$ipList[0]}, it's a proxy says $basename!"
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
	 * @return string[]|bool IPv4 array, or false if the IP is not blacklisted
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
	 * @since 1.34
	 * @param User $user
	 * @param WebResponse $response The response on which to set the cookie.
	 * @throws LogicException If called before the User object was loaded.
	 * @throws LogicException If not called pre-send.
	 */
	public function trackBlockWithCookie( User $user, WebResponse $response ) {
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
			if ( $block instanceof CompositeBlock ) {
				// TODO: Improve on simply tracking the first trackable block (T225654)
				foreach ( $block->getOriginalBlocks() as $originalBlock ) {
					if ( $this->shouldTrackBlockWithCookie( $originalBlock, $isAnon ) ) {
						'@phan-var DatabaseBlock $originalBlock';
						$this->setBlockCookie( $originalBlock, $response );
						return;
					}
				}
			} else {
				if ( $this->shouldTrackBlockWithCookie( $block, $isAnon ) ) {
					'@phan-var DatabaseBlock $block';
					$this->setBlockCookie( $block, $response );
				}
			}
		}
	}

	/**
	 * Set the 'BlockID' cookie to this block's ID and expiry time. The cookie's expiry will be
	 * the same as the block's, to a maximum of 24 hours.
	 *
	 * @since 1.34
	 * @internal Should be private.
	 *  Left public for backwards compatibility, until DatabaseBlock::setCookie is removed.
	 * @param DatabaseBlock $block
	 * @param WebResponse $response The response on which to set the cookie.
	 */
	public function setBlockCookie( DatabaseBlock $block, WebResponse $response ) {
		// Calculate the default expiry time.
		$maxExpiryTime = wfTimestamp( TS_MW, (int)wfTimestamp() + ( 24 * 60 * 60 ) );

		// Use the block's expiry time only if it's less than the default.
		$expiryTime = $block->getExpiry();
		if ( $expiryTime === 'infinity' || $expiryTime > $maxExpiryTime ) {
			$expiryTime = $maxExpiryTime;
		}

		// Set the cookie. Reformat the MediaWiki datetime as a Unix timestamp for the cookie.
		$expiryValue = DateTime::createFromFormat(
			'YmdHis',
			$expiryTime,
			new DateTimeZone( 'UTC' )
		)->format( 'U' );
		$cookieOptions = [ 'httpOnly' => false ];
		$cookieValue = $this->getCookieValue( $block );
		$response->setCookie( 'BlockID', $cookieValue, $expiryValue, $cookieOptions );
	}

	/**
	 * Check if the block should be tracked with a cookie.
	 *
	 * @param AbstractBlock $block
	 * @param bool $isAnon The user is logged out
	 * @return bool The block sould be tracked with a cookie
	 */
	private function shouldTrackBlockWithCookie( AbstractBlock $block, $isAnon ) {
		if ( $block instanceof DatabaseBlock ) {
			switch ( $block->getType() ) {
				case DatabaseBlock::TYPE_IP:
				case DatabaseBlock::TYPE_RANGE:
					return $isAnon && $this->options->get( 'CookieSetOnIpBlock' );
				case DatabaseBlock::TYPE_USER:
					return !$isAnon &&
						$this->options->get( 'CookieSetOnAutoblock' ) &&
						$block->isAutoblocking();
				default:
					return false;
			}
		}
		return false;
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
	 * the ID and a HMAC (see DatabaseBlock::setCookie), but will sometimes only be the ID.
	 *
	 * @since 1.34
	 * @internal Should be private.
	 *  Left public for backwards compatibility, until DatabaseBlock::getIdFromCookieValue is removed.
	 * @param string $cookieValue The string in which to find the ID.
	 * @return int|null The block ID, or null if the HMAC is present and invalid.
	 */
	public function getIdFromCookieValue( $cookieValue ) {
		// The cookie value must start with a number
		if ( !is_numeric( substr( $cookieValue, 0, 1 ) ) ) {
			return null;
		}

		// Extract the ID prefix from the cookie value (may be the whole value, if no bang found).
		$bangPos = strpos( $cookieValue, '!' );
		$id = ( $bangPos === false ) ? $cookieValue : substr( $cookieValue, 0, $bangPos );
		if ( !$this->options->get( 'SecretKey' ) ) {
			// If there's no secret key, just use the ID as given.
			return (int)$id;
		}
		$storedHmac = substr( $cookieValue, $bangPos + 1 );
		$calculatedHmac = MWCryptHash::hmac( $id, $this->options->get( 'SecretKey' ), false );
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
	 * @internal Should be private.
	 *  Left public for backwards compatibility, until DatabaseBlock::getCookieValue is removed.
	 * @param DatabaseBlock $block
	 * @return string The block ID, probably concatenated with "!" and the HMAC.
	 */
	public function getCookieValue( DatabaseBlock $block ) {
		$id = $block->getId();
		if ( !$this->options->get( 'SecretKey' ) ) {
			// If there's no secret key, don't append a HMAC.
			return $id;
		}
		$hmac = MWCryptHash::hmac( $id, $this->options->get( 'SecretKey' ), false );
		$cookieValue = $id . '!' . $hmac;
		return $cookieValue;
	}

}
