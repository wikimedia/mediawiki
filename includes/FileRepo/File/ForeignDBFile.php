<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\FileRepo\File;

use MediaWiki\FileRepo\ForeignDBRepo;
use MediaWiki\Language\Language;
use MediaWiki\MediaWikiServices;
use MediaWiki\Status\Status;
use MediaWiki\Title\Title;
use MediaWiki\User\UserIdentity;
use Wikimedia\ObjectCache\WANObjectCache;
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
	 */
	public function publish( $srcPath, $flags = 0, array $options = [] ) {
		$this->readOnlyError();
	}

	/**
	 * @param int[] $versions
	 * @param bool $unsuppress
	 * @return Status
	 */
	public function restore( $versions = [], $unsuppress = false ) {
		$this->readOnlyError();
	}

	/**
	 * @param string $reason
	 * @param UserIdentity $user
	 * @param bool $suppress
	 * @return Status
	 */
	public function deleteFile( $reason, UserIdentity $user, $suppress = false ) {
		$this->readOnlyError();
	}

	/**
	 * @param Title $target
	 * @return Status
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
	public function getDescriptionText( ?Language $lang = null ) {
		global $wgLang;

		if ( !$this->repo->fetchDescription ) {
			return false;
		}

		$lang ??= $wgLang;
		$renderUrl = $this->repo->getDescriptionRenderUrl( $this->getName(), $lang->getCode() );
		if ( !$renderUrl ) {
			return false;
		}

		$touched = $this->repo->getReplicaDB()->newSelectQueryBuilder()
			->select( 'page_touched' )
			->from( 'page' )
			->where( [ 'page_namespace' => NS_FILE, 'page_title' => $this->title->getDBkey() ] )
			->caller( __METHOD__ )->fetchField();
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
		$pageId = $dbr->newSelectQueryBuilder()
			->select( 'page_id' )
			->from( 'page' )
			->where( [ 'page_namespace' => NS_FILE, 'page_title' => $this->title->getDBkey() ] )
			->caller( __METHOD__ )->fetchField();

		if ( $pageId !== false ) {
			$url = $this->repo->makeUrl( [ 'curid' => $pageId ] );
			if ( $url !== false ) {
				return $url;
			}
		}
		return null;
	}

}

/** @deprecated class alias since 1.44 */
class_alias( ForeignDBFile::class, 'ForeignDBFile' );
