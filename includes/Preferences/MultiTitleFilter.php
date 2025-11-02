<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\Preferences;

use MediaWiki\MediaWikiServices;
use MediaWiki\Page\PageStore;
use MediaWiki\Page\ProperPageIdentity;
use MediaWiki\Title\TitleFactory;
use MediaWiki\Title\TitleFormatter;

class MultiTitleFilter implements Filter {

	/**
	 * @var PageStore
	 */
	private $pageStore;

	/**
	 * @var TitleFormatter
	 */
	private $titleFormatter;

	/**
	 * @param TitleFactory|null $titleFactory unused
	 * @param PageStore|null $pageStore
	 * @param TitleFormatter|null $titleFormatter
	 */
	public function __construct(
		?TitleFactory $titleFactory = null, ?PageStore $pageStore = null, ?TitleFormatter $titleFormatter = null ) {
		$this->pageStore = $pageStore;
		$this->titleFormatter = $titleFormatter;
	}

	/**
	 * @inheritDoc
	 */
	public function filterForForm( $value ) {
		$ids = array_map( 'intval', preg_split( '/\n/', $value, -1, PREG_SPLIT_NO_EMPTY ) );
		$pageRecords = $this->getPageStore()
			->newSelectQueryBuilder()
			->wherePageIds( $ids )
			->caller( __METHOD__ )
			->fetchPageRecords();
		return implode( "\n", array_map( function ( $pageRecord ) {
			return $this->getTitleFormatter()->getPrefixedText( $pageRecord );
		}, iterator_to_array( $pageRecords ) ) );
	}

	/**
	 * @inheritDoc
	 */
	public function filterFromForm( $titles ) {
		$titles = trim( $titles );
		if ( $titles !== '' ) {
			$titles = preg_split( '/\n/', $titles, -1, PREG_SPLIT_NO_EMPTY );
			$ids = array_map( function ( $text ) {
				$title = $this->getPageStore()->getPageByText( $text );
				if ( $title instanceof ProperPageIdentity && $title->getId() > 0 ) {
					return $title->getId();
				}
				return false;
			}, $titles );
			if ( $ids ) {
				return implode( "\n", $ids );
			}
		}
		// If the titles list is null, it should be null (don't save) rather than an empty string.
		return null;
	}

	private function getPageStore(): PageStore {
		$this->pageStore ??= MediaWikiServices::getInstance()->getPageStore();
		return $this->pageStore;
	}

	private function getTitleFormatter(): TitleFormatter {
		$this->titleFormatter ??= MediaWikiServices::getInstance()->getTitleFormatter();
		return $this->titleFormatter;
	}
}
