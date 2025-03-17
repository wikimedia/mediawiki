<?php

namespace MediaWiki\Search;

use MediaWiki\FileRepo\File\File;
use MediaWiki\FileRepo\RepoGroup;
use MediaWiki\HookContainer\HookContainer;
use MediaWiki\HookContainer\HookRunner;
use MediaWiki\MediaWikiServices;
use MediaWiki\Page\PageIdentity;
use MediaWiki\Search\Entity\SearchResultThumbnail;

/**
 * Find thumbnails for search results
 *
 * @since 1.40
 */
class SearchResultThumbnailProvider {

	public const THUMBNAIL_SIZE = 60;

	private RepoGroup $repoGroup;
	private HookRunner $hookRunner;

	public function __construct( RepoGroup $repoGroup, HookContainer $hookContainer ) {
		$this->repoGroup = $repoGroup;
		$this->hookRunner = new HookRunner( $hookContainer );
	}

	/**
	 * Returns a list of fileNames for a given list of PageIdentity objects (within NS_FILE)
	 *
	 * @param PageIdentity[] $identitiesByPageId key-value array of where key
	 *   is pageId, value is PageIdentity
	 * @return array
	 */
	private function getFileNamesByPageId( array $identitiesByPageId ): array {
		$fileIdentitiesByPageId = array_filter(
			$identitiesByPageId,
			static function ( PageIdentity $pageIdentity ) {
				return $pageIdentity->getNamespace() === NS_FILE;
			}
		);

		return array_map(
			static function ( PageIdentity $pageIdentity ) {
				return $pageIdentity->getDBkey();
			},
			$fileIdentitiesByPageId
		);
	}

	/**
	 * Returns a SearchResultThumbnail instance for a given File/size combination.
	 *
	 * @param File $file
	 * @param int|null $size
	 * @return SearchResultThumbnail|null
	 */
	public function buildSearchResultThumbnailFromFile( File $file, ?int $size = null ): ?SearchResultThumbnail {
		$size ??= self::THUMBNAIL_SIZE;

		$thumb = $file->transform( [ 'width' => $size ] );
		if ( !$thumb || $thumb->isError() ) {
			return null;
		}

		$urlUtils = MediaWikiServices::getInstance()->getUrlUtils();
		return new SearchResultThumbnail(
			$thumb->getFile()->getMimeType(),
			null,
			$thumb->getWidth(),
			$thumb->getHeight(),
			null,
			$urlUtils->expand( $thumb->getUrl(), PROTO_RELATIVE ) ?? false,
			$file->getName()
		);
	}

	/**
	 * @param PageIdentity[] $pageIdentities array that contains $pageId => PageIdentity.
	 * @param int|null $size size of thumbnail height and width in points
	 * @return SearchResultThumbnail[] array of $pageId => SearchResultThumbnail
	 */
	public function getThumbnails( array $pageIdentities, ?int $size = 60 ): array {
		// add filenames for NS_FILE pages by default
		$fileNamesByPageId = $this->getFileNamesByPageId( $pageIdentities );
		$results = [];
		foreach ( $fileNamesByPageId as $pageId => $fileName ) {
			$file = $this->repoGroup->findFile( $fileName );
			if ( !$file ) {
				continue;
			}
			$thumbnail = $this->buildSearchResultThumbnailFromFile( $file, $size );
			if ( $thumbnail ) {
				$results[$pageId] = $thumbnail;
			}
		}

		// allow extensions to inject additional thumbnails
		$this->hookRunner->onSearchResultProvideThumbnail( $pageIdentities, $results, $size );

		return $results;
	}
}
