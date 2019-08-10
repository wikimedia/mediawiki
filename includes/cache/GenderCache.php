<?php
/**
 * Caches user genders when needed to use correct namespace aliases.
 *
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
 * @author Niklas Laxström
 * @ingroup Cache
 */

use MediaWiki\MediaWikiServices;
use Wikimedia\Rdbms\ILoadBalancer;

/**
 * Caches user genders when needed to use correct namespace aliases.
 *
 * @since 1.18
 */
class GenderCache {
	protected $cache = [];
	protected $default;
	protected $misses = 0;
	protected $missLimit = 1000;

	/** @var NamespaceInfo */
	private $nsInfo;

	/** @var ILoadBalancer|null */
	private $loadBalancer;

	public function __construct( NamespaceInfo $nsInfo = null, ILoadBalancer $loadBalancer = null ) {
		$this->nsInfo = $nsInfo ?? MediaWikiServices::getInstance()->getNamespaceInfo();
		$this->loadBalancer = $loadBalancer;
	}

	/**
	 * @deprecated in 1.28 see MediaWikiServices::getInstance()->getGenderCache()
	 * @return GenderCache
	 */
	public static function singleton() {
		return MediaWikiServices::getInstance()->getGenderCache();
	}

	/**
	 * Returns the default gender option in this wiki.
	 * @return string
	 */
	protected function getDefault() {
		if ( $this->default === null ) {
			$this->default = User::getDefaultOption( 'gender' );
		}

		return $this->default;
	}

	/**
	 * Returns the gender for given username.
	 * @param string|User $username
	 * @param string $caller The calling method
	 * @return string
	 */
	public function getGenderOf( $username, $caller = '' ) {
		global $wgUser;

		if ( $username instanceof User ) {
			$username = $username->getName();
		}

		$username = self::normalizeUsername( $username );
		if ( !isset( $this->cache[$username] ) ) {
			if ( $this->misses >= $this->missLimit && $wgUser->getName() !== $username ) {
				if ( $this->misses === $this->missLimit ) {
					$this->misses++;
					wfDebug( __METHOD__ . ": too many misses, returning default onwards\n" );
				}

				return $this->getDefault();
			} else {
				$this->misses++;
				$this->doQuery( $username, $caller );
			}
		}

		/* Undefined if there is a valid username which for some reason doesn't
		 * exist in the database.
		 */
		return $this->cache[$username] ?? $this->getDefault();
	}

	/**
	 * Wrapper for doQuery that processes raw LinkBatch data.
	 *
	 * @param array $data
	 * @param string $caller
	 */
	public function doLinkBatch( $data, $caller = '' ) {
		$users = [];
		foreach ( $data as $ns => $pagenames ) {
			if ( !$this->nsInfo->hasGenderDistinction( $ns ) ) {
				continue;
			}
			foreach ( array_keys( $pagenames ) as $username ) {
				$users[$username] = true;
			}
		}

		$this->doQuery( array_keys( $users ), $caller );
	}

	/**
	 * Wrapper for doQuery that processes a title or string array.
	 *
	 * @since 1.20
	 * @param array $titles Array of Title objects or strings
	 * @param string $caller The calling method
	 */
	public function doTitlesArray( $titles, $caller = '' ) {
		$users = [];
		foreach ( $titles as $title ) {
			$titleObj = is_string( $title ) ? Title::newFromText( $title ) : $title;
			if ( !$titleObj ) {
				continue;
			}
			if ( !$this->nsInfo->hasGenderDistinction( $titleObj->getNamespace() ) ) {
				continue;
			}
			$users[] = $titleObj->getText();
		}

		$this->doQuery( $users, $caller );
	}

	/**
	 * Preloads genders for given list of users.
	 * @param array|string $users Usernames
	 * @param string $caller The calling method
	 */
	public function doQuery( $users, $caller = '' ) {
		$default = $this->getDefault();

		$usersToCheck = [];
		foreach ( (array)$users as $value ) {
			$name = self::normalizeUsername( $value );
			// Skip users whose gender setting we already know
			if ( !isset( $this->cache[$name] ) ) {
				// For existing users, this value will be overwritten by the correct value
				$this->cache[$name] = $default;
				// query only for valid names, which can be in the database
				if ( User::isValidUserName( $name ) ) {
					$usersToCheck[] = $name;
				}
			}
		}

		if ( count( $usersToCheck ) === 0 ) {
			return;
		}

		// Only query database, when load balancer is provided by service wiring
		// This maybe not happen when running as part of the installer
		if ( $this->loadBalancer === null ) {
			return;
		}

		$dbr = $this->loadBalancer->getConnectionRef( DB_REPLICA );
		$table = [ 'user', 'user_properties' ];
		$fields = [ 'user_name', 'up_value' ];
		$conds = [ 'user_name' => $usersToCheck ];
		$joins = [ 'user_properties' =>
			[ 'LEFT JOIN', [ 'user_id = up_user', 'up_property' => 'gender' ] ] ];

		$comment = __METHOD__;
		if ( strval( $caller ) !== '' ) {
			$comment .= "/$caller";
		}
		$res = $dbr->select( $table, $fields, $conds, $comment, [], $joins );

		foreach ( $res as $row ) {
			$this->cache[$row->user_name] = $row->up_value ?: $default;
		}
	}

	private static function normalizeUsername( $username ) {
		// Strip off subpages
		$indexSlash = strpos( $username, '/' );
		if ( $indexSlash !== false ) {
			$username = substr( $username, 0, $indexSlash );
		}

		// normalize underscore/spaces
		return strtr( $username, '_', ' ' );
	}
}
