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

use Block;
use IP;
use MediaWiki\User\UserIdentity;
use User;
use WebRequest;
use Wikimedia\IPSet;

/**
 * A service class for checking blocks.
 * To obtain an instance, use MediaWikiServices::getInstance()->getBlockManager().
 *
 * @since 1.34 Refactored from User and Block.
 */
class BlockManager {
	// TODO: This should be UserIdentity instead of User
	/** @var User */
	private $currentUser;

	/** @var WebRequest */
	private $currentRequest;

	/** @var bool */
	private $applyIpBlocksToXff;

	/** @var bool */
	private $cookieSetOnAutoblock;

	/** @var bool */
	private $cookieSetOnIpBlock;

	/** @var array */
	private $dnsBlacklistUrls;

	/** @var bool */
	private $enableDnsBlacklist;

	/** @var array */
	private $proxyList;

	/** @var array */
	private $proxyWhitelist;

	/** @var array */
	private $softBlockRanges;

	/**
	 * @param User $currentUser
	 * @param WebRequest $currentRequest
	 * @param bool $applyIpBlocksToXff
	 * @param bool $cookieSetOnAutoblock
	 * @param bool $cookieSetOnIpBlock
	 * @param array $dnsBlacklistUrls
	 * @param bool $enableDnsBlacklist
	 * @param array $proxyList
	 * @param array $proxyWhitelist
	 * @param array $softBlockRanges
	 */
	public function __construct(
		$currentUser,
		$currentRequest,
		$applyIpBlocksToXff,
		$cookieSetOnAutoblock,
		$cookieSetOnIpBlock,
		$dnsBlacklistUrls,
		$enableDnsBlacklist,
		$proxyList,
		$proxyWhitelist,
		$softBlockRanges
	) {
		$this->currentUser = $currentUser;
		$this->currentRequest = $currentRequest;
		$this->applyIpBlocksToXff = $applyIpBlocksToXff;
		$this->cookieSetOnAutoblock = $cookieSetOnAutoblock;
		$this->cookieSetOnIpBlock = $cookieSetOnIpBlock;
		$this->dnsBlacklistUrls = $dnsBlacklistUrls;
		$this->enableDnsBlacklist = $enableDnsBlacklist;
		$this->proxyList = $proxyList;
		$this->proxyWhitelist = $proxyWhitelist;
		$this->softBlockRanges = $softBlockRanges;
	}

	/**
	 * Get the blocks that apply to a user and return the most relevant one.
	 *
	 * TODO: $user should be UserIdentity instead of User
	 *
	 * @internal This should only be called by User::getBlockedStatus
	 * @param User $user
	 * @param bool $fromReplica Whether to check the replica DB first.
	 *  To improve performance, non-critical checks are done against replica DBs.
	 *  Check when actually saving should be done against master.
	 * @return AbstractBlock|null The most relevant block, or null if there is no block.
	 */
	public function getUserBlock( User $user, $fromReplica ) {
		$isAnon = $user->getId() === 0;

		// TODO: If $user is the current user, we should use the current request. Otherwise,
		// we should not look for XFF or cookie blocks.
		$request = $user->getRequest();

		# We only need to worry about passing the IP address to the Block generator if the
		# user is not immune to autoblocks/hardblocks, and they are the current user so we
		# know which IP address they're actually coming from
		$ip = null;
		$sessionUser = $this->currentUser;
		// the session user is set up towards the end of Setup.php. Until then,
		// assume it's a logged-out user.
		$globalUserName = $sessionUser->isSafeToLoad()
			? $sessionUser->getName()
			: IP::sanitizeIP( $this->currentRequest->getIP() );
		if ( $user->getName() === $globalUserName && !$user->isAllowed( 'ipblock-exempt' ) ) {
			$ip = $this->currentRequest->getIP();
		}

		// User/IP blocking
		// TODO: remove dependency on Block
		$block = Block::newFromTarget( $user, $ip, !$fromReplica );

		// Cookie blocking
		if ( !$block instanceof AbstractBlock ) {
			$block = $this->getBlockFromCookieValue( $user, $request );
		}

		// Proxy blocking
		if ( !$block instanceof AbstractBlock
			&& $ip !== null
			&& !in_array( $ip, $this->proxyWhitelist )
		) {
			// Local list
			if ( $this->isLocallyBlockedProxy( $ip ) ) {
				$block = new SystemBlock( [
					'byText' => wfMessage( 'proxyblocker' )->text(),
					'reason' => wfMessage( 'proxyblockreason' )->plain(),
					'address' => $ip,
					'systemBlock' => 'proxy',
				] );
			} elseif ( $isAnon && $this->isDnsBlacklisted( $ip ) ) {
				$block = new SystemBlock( [
					'byText' => wfMessage( 'sorbs' )->text(),
					'reason' => wfMessage( 'sorbsreason' )->plain(),
					'address' => $ip,
					'systemBlock' => 'dnsbl',
				] );
			}
		}

		// (T25343) Apply IP blocks to the contents of XFF headers, if enabled
		if ( !$block instanceof AbstractBlock
			&& $this->applyIpBlocksToXff
			&& $ip !== null
			&& !in_array( $ip, $this->proxyWhitelist )
		) {
			$xff = $request->getHeader( 'X-Forwarded-For' );
			$xff = array_map( 'trim', explode( ',', $xff ) );
			$xff = array_diff( $xff, [ $ip ] );
			// TODO: remove dependency on Block
			$xffblocks = Block::getBlocksForIPList( $xff, $isAnon, !$fromReplica );
			// TODO: remove dependency on Block
			$block = Block::chooseBlock( $xffblocks, $xff );
			if ( $block instanceof AbstractBlock ) {
				# Mangle the reason to alert the user that the block
				# originated from matching the X-Forwarded-For header.
				$block->setReason( wfMessage( 'xffblockreason', $block->getReason() )->plain() );
			}
		}

		if ( !$block instanceof AbstractBlock
			&& $ip !== null
			&& $isAnon
			&& IP::isInRanges( $ip, $this->softBlockRanges )
		) {
			$block = new SystemBlock( [
				'address' => $ip,
				'byText' => 'MediaWiki default',
				'reason' => wfMessage( 'softblockrangesreason', $ip )->plain(),
				'anonOnly' => true,
				'systemBlock' => 'wgSoftBlockRanges',
			] );
		}

		return $block;
	}

	/**
	 * Try to load a Block from an ID given in a cookie value.
	 *
	 * @param UserIdentity $user
	 * @param WebRequest $request
	 * @return Block|bool The Block object, or false if none could be loaded.
	 */
	private function getBlockFromCookieValue(
		UserIdentity $user,
		WebRequest $request
	) {
		$blockCookieVal = $request->getCookie( 'BlockID' );
		$response = $request->response();

		// Make sure there's something to check. The cookie value must start with a number.
		if ( strlen( $blockCookieVal ) < 1 || !is_numeric( substr( $blockCookieVal, 0, 1 ) ) ) {
			return false;
		}
		// Load the Block from the ID in the cookie.
		// TODO: remove dependency on Block
		$blockCookieId = Block::getIdFromCookieValue( $blockCookieVal );
		if ( $blockCookieId !== null ) {
			// An ID was found in the cookie.
			// TODO: remove dependency on Block
			$tmpBlock = Block::newFromID( $blockCookieId );
			if ( $tmpBlock instanceof Block ) {
				switch ( $tmpBlock->getType() ) {
					case Block::TYPE_USER:
						$blockIsValid = !$tmpBlock->isExpired() && $tmpBlock->isAutoblocking();
						$useBlockCookie = ( $this->cookieSetOnAutoblock === true );
						break;
					case Block::TYPE_IP:
					case Block::TYPE_RANGE:
						// If block is type IP or IP range, load only if user is not logged in (T152462)
						$blockIsValid = !$tmpBlock->isExpired() && $user->getId() === 0;
						$useBlockCookie = ( $this->cookieSetOnIpBlock === true );
						break;
					default:
						$blockIsValid = false;
						$useBlockCookie = false;
				}

				if ( $blockIsValid && $useBlockCookie ) {
					// Use the block.
					return $tmpBlock;
				}

				// If the block is not valid, remove the cookie.
				// TODO: remove dependency on Block
				Block::clearCookie( $response );
			} else {
				// If the block doesn't exist, remove the cookie.
				// TODO: remove dependency on Block
				Block::clearCookie( $response );
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
		if ( !$this->proxyList ) {
			return false;
		}

		if ( !is_array( $this->proxyList ) ) {
			// Load values from the specified file
			$this->proxyList = array_map( 'trim', file( $this->proxyList ) );
		}

		$resultProxyList = [];
		$deprecatedIPEntries = [];

		// backward compatibility: move all ip addresses in keys to values
		foreach ( $this->proxyList as $key => $value ) {
			$keyIsIP = IP::isIPAddress( $key );
			$valueIsIP = IP::isIPAddress( $value );
			if ( $keyIsIP && !$valueIsIP ) {
				$deprecatedIPEntries[] = $key;
				$resultProxyList[] = $key;
			} elseif ( $keyIsIP && $valueIsIP ) {
				$deprecatedIPEntries[] = $key;
				$resultProxyList[] = $key;
				$resultProxyList[] = $value;
			} else {
				$resultProxyList[] = $value;
			}
		}

		if ( $deprecatedIPEntries ) {
			wfDeprecated(
				'IP addresses in the keys of $wgProxyList (found the following IP addresses in keys: ' .
				implode( ', ', $deprecatedIPEntries ) . ', please move them to values)', '1.30' );
		}

		$proxyListIPSet = new IPSet( $resultProxyList );
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
		if ( !$this->enableDnsBlacklist ||
			( $checkWhitelist && in_array( $ip, $this->proxyWhitelist ) )
		) {
			return false;
		}

		return $this->inDnsBlacklist( $ip, $this->dnsBlacklistUrls );
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
		if ( IP::isIPv4( $ip ) ) {
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
					wfDebugLog(
						'dnsblacklist',
						"Hostname $hostname is {$ipList[0]}, it's a proxy says $basename!"
					);
					$found = true;
					break;
				}

				wfDebugLog( 'dnsblacklist', "Requested $hostname, not found in $basename." );
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

}
