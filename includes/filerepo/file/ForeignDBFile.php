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

use MediaWiki\MediaWikiServices;
use MediaWiki\Title\Title;
use MediaWiki\User\UserIdentity;
use Wikimedia\Rdbms\DBUnexpectedError;

/**
 * Foreign file from a reachable database in the same wiki farm.
 *
 * @ingroup FileAbstraction
 */
class ForeignDBFile extends LocalFile {

	/**
	 * @return ForeignDBRepo|false
	 */
	public function getRepo() {
		return $this->repo;
	}

	/**
	 * @param string $srcPath
	 * @param int $flags
	 * @param array $options
	 * @return Status
	 * @throws MWException
	 */
	public function publish( $srcPath, $flags = 0, array $options = [] ) {
		$this->readOnlyError();
	}

	/**
	 * @param int[] $versions
	 * @param bool $unsuppress
	 * @return Status
	 * @throws MWException
	 */
	public function restore( $versions = [], $unsuppress = false ) {
		$this->readOnlyError();
	}

	/**
	 * @param string $reason
	 * @param UserIdentity $user
	 * @param bool $suppress
	 * @return Status
	 * @throws MWException
	 */
	public function deleteFile( $reason, UserIdentity $user, $suppress = false ) {
		$this->readOnlyError();
	}

	/**
	 * @param Title $target
	 * @return Status
	 * @throws MWException
	 */
	public function move( $target ) {
		$this->readOnlyError();
	}

	/**
	 * @return string
	 */
	public function getDescriptionUrl() {
		// Restore remote behavior
		return File::getDescriptionUrl();
	}

	/**
	 * @param Language|null $lang Optional language to fetch description in.
	 * @return string|false
	 */
	public function getDescriptionText( Language $lang = null ) {
		global $wgLang;

		if ( !$this->repo->fetchDescription ) {
			return false;
		}

		$lang ??= $wgLang;
		$renderUrl = $this->repo->getDescriptionRenderUrl( $this->getName(), $lang->getCode() );
		if ( !$renderUrl ) {
			return false;
		}

		$touched = $this->repo->getReplicaDB()->selectField(
			'page',
			'page_touched',
			[
				'page_namespace' => NS_FILE,
				'page_title' => $this->title->getDBkey()
			],
			__METHOD__
		);
		if ( $touched === false ) {
			return false; // no description page
		}

		$cache = MediaWikiServices::getInstance()->getMainWANObjectCache();
		$fname = __METHOD__;

		return $cache->getWithSetCallback(
			$this->repo->getLocalCacheKey(
				'file-foreign-description',
				$lang->getCode(),
				md5( $this->getName() ),
				$touched
			),
			$this->repo->descriptionCacheExpiry ?: $cache::TTL_UNCACHEABLE,
			static function ( $oldValue, &$ttl, array &$setOpts ) use ( $renderUrl, $fname ) {
				wfDebug( "Fetching shared description from $renderUrl" );
				$res = MediaWikiServices::getInstance()->getHttpRequestFactory()->
					get( $renderUrl, [], $fname );
				if ( !$res ) {
					$ttl = WANObjectCache::TTL_UNCACHEABLE;
				}

				return $res;
			}
		);
	}

	/**
	 * Get short description URL for a file based on the page ID.
	 *
	 * @return string|null
	 * @throws DBUnexpectedError
	 * @since 1.27
	 */
	public function getDescriptionShortUrl() {
		$dbr = $this->repo->getReplicaDB();
		$pageId = $dbr->selectField(
			'page',
			'page_id',
			[
				'page_namespace' => NS_FILE,
				'page_title' => $this->title->getDBkey()
			],
			__METHOD__
		);

		if ( $pageId !== false ) {
			$url = $this->repo->makeUrl( [ 'curid' => $pageId ] );
			if ( $url !== false ) {
				return $url;
			}
		}
		return null;
	}

}
