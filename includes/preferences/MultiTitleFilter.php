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

namespace MediaWiki\Preferences;

use MediaWiki\MediaWikiServices;
use MediaWiki\Page\PageStore;
use MediaWiki\Page\ProperPageIdentity;
use TitleFactory;
use TitleFormatter;

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
		TitleFactory $titleFactory = null, PageStore $pageStore = null, TitleFormatter $titleFormatter = null ) {
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

	/**
	 * @return PageStore
	 */
	private function getPageStore(): PageStore {
		$this->pageStore = $this->pageStore ?? MediaWikiServices::getInstance()->getPageStore();
		return $this->pageStore;
	}

	/**
	 * @return TitleFormatter
	 */
	private function getTitleFormatter(): TitleFormatter {
		$this->titleFormatter = $this->titleFormatter ?? MediaWikiServices::getInstance()->getTitleFormatter();
		return $this->titleFormatter;
	}
}
