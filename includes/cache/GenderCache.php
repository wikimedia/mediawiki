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
 * @author Niklas Laxström
 */

namespace MediaWiki\Cache;

use MediaWiki\Context\RequestContext;
use MediaWiki\Linker\LinkTarget;
use MediaWiki\Title\NamespaceInfo;
use MediaWiki\User\Options\UserOptionsLookup;
use MediaWiki\User\UserIdentity;
use Wikimedia\Rdbms\IResultWrapper;

/**
 * Look up "gender" user preference.
 *
 * This primarily used in MediaWiki\Title\TitleFormatter for title formatting
 * of pages in gendered namespace aliases, and in CoreParserFunctions for the
 * `{{gender:}}` parser function.
 *
 * @since 1.18
 * @ingroup Cache
 */
class GenderCache {
	/** @var string[] */
	protected $cache = [];
	/** @var string|null */
	protected $default = null;
	/** @var int */
	protected $misses = 0;
	/**
	 * @internal Exposed for MediaWiki core unit tests.
	 * @var int
	 */
	protected $missLimit = 1000;

	private NamespaceInfo $nsInfo;
	private UserOptionsLookup $userOptionsLookup;

	public function __construct(
		NamespaceInfo $nsInfo,
		UserOptionsLookup $userOptionsLookup
	) {
		$this->nsInfo = $nsInfo;
		$this->userOptionsLookup = $userOptionsLookup;
	}

	/**
	 * Get the default gender option on this wiki.
	 *
	 * @return string
	 */
	protected function getDefault() {
		$this->default ??= $this->userOptionsLookup->getDefaultOption( 'gender' );
		return $this->default;
	}

	/**
	 * Get the gender option for given username.
	 *
	 * @param string|UserIdentity $username
	 * @param string|null $caller Unused since 1.44
	 * @return string
	 */
	public function getGenderOf( $username, $caller = '' ) {
		if ( $username instanceof UserIdentity ) {
			$username = $username->getName();
		}

		$username = self::normalizeUsername( $username );
		if ( !isset( $this->cache[$username] ) ) {
			if ( $this->misses < $this->missLimit ||
				RequestContext::getMain()->getUser()->getName() === $username
			) {
				$this->misses++;
				$this->doQuery( $username );
			}
			if ( $this->misses === $this->missLimit ) {
				// Log only once and don't bother incrementing beyond limit+1
				$this->misses++;
				wfDebug( __METHOD__ . ': too many misses, returning default onwards' );
			}
		}

		return $this->cache[$username] ?? $this->getDefault();
	}

	/**
	 * Wrapper for doQuery that processes raw LinkBatch data.
	 *
	 * @param array<int,array<string,mixed>> $data
	 * @param string|null $caller Unused since 1.44
	 */
	public function doLinkBatch( array $data, $caller = '' ) {
		$users = [];
		foreach ( $data as $ns => $pagenames ) {
			if ( $this->nsInfo->hasGenderDistinction( $ns ) ) {
				$users += $pagenames;
			}
		}
		$this->doQuery( array_keys( $users ) );
	}

	/**
	 * Wrapper for doQuery that processes a title array.
	 *
	 * @since 1.20
	 * @param LinkTarget[] $titles
	 * @param string|null $caller Unused since 1.44
	 */
	public function doTitlesArray( $titles, $caller = '' ) {
		$users = [];
		foreach ( $titles as $titleObj ) {
			if ( $this->nsInfo->hasGenderDistinction( $titleObj->getNamespace() ) ) {
				$users[] = $titleObj->getText();
			}
		}
		$this->doQuery( $users );
	}

	/**
	 * Process a set of rows from the page table
	 *
	 * @since 1.45
	 * @param iterable<\stdClass>|IResultWrapper $rows
	 */
	public function doPageRows( $rows ) {
		$users = [];
		foreach ( $rows as $row ) {
			if ( $this->nsInfo->hasGenderDistinction( (int)$row->page_namespace ) ) {
				$users[] = $row->page_title;
			}
		}
		$this->doQuery( $users );
	}

	/**
	 * Preload gender option for multiple user names.
	 *
	 * @param string[]|string $users Usernames
	 * @param string|null $caller Unused since 1.44
	 */
	public function doQuery( $users, $caller = '' ) {
		$usersToFetch = [];
		foreach ( (array)$users as $userName ) {
			$userName = self::normalizeUsername( $userName );
			if ( !isset( $this->cache[$userName] ) ) {
				$usersToFetch[] = $userName;
			}
		}
		if ( !$usersToFetch ) {
			return;
		}

		// Limit batch size to 1000 since the usernames need to be put into an
		// IN() expression in SQL. Could be done closer to the backend, but
		// there are multiple backends.
		foreach ( array_chunk( array_unique( $usersToFetch ), 1000 ) as $batch ) {
			$genders = $this->userOptionsLookup->getOptionBatchForUserNames( $batch, 'gender' );
			foreach ( $genders as $userName => $gender ) {
				$this->cache[$userName] = $gender;
			}
		}
	}

	private static function normalizeUsername( string $username ): string {
		// Strip off subpages
		$indexSlash = strpos( $username, '/' );
		if ( $indexSlash !== false ) {
			$username = substr( $username, 0, $indexSlash );
		}

		// normalize underscore/spaces
		return strtr( $username, '_', ' ' );
	}
}

/** @deprecated class alias since 1.42 */
class_alias( GenderCache::class, 'GenderCache' );
