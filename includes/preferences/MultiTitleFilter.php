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

use Title;
use TitleFactory;

class MultiTitleFilter implements Filter {

	/**
	 * @var TitleFactory
	 */
	private $titleFactory;

	/**
	 * @param TitleFactory|null $titleFactory
	 */
	public function __construct( TitleFactory $titleFactory = null ) {
		$this->titleFactory = $titleFactory;
	}

	/**
	 * @inheritDoc
	 */
	public function filterForForm( $value ) {
		$ids = array_map( 'intval', preg_split( '/\n/', $value, -1, PREG_SPLIT_NO_EMPTY ) );
		$titles = $ids ? $this->getTitleFactory()->newFromIDs( $ids ) : [];
		if ( !$titles ) {
			return '';
		}
		return implode( "\n", array_map( function ( Title $title ) {
			return $title->getPrefixedText();
		}, $titles ) );
	}

	/**
	 * @inheritDoc
	 */
	public function filterFromForm( $titles ) {
		$titles = trim( $titles );
		if ( $titles !== '' ) {
			$titles = preg_split( '/\n/', $titles, -1, PREG_SPLIT_NO_EMPTY );
			$ids = array_map( function ( $text ) {
				$title = $this->getTitleFactory()->newFromText( $text );
				if ( $title instanceof \Title && $title->getArticleID() > 0 ) {
					return $title->getArticleID();
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
	 * @return TitleFactory
	 */
	private function getTitleFactory() :TitleFactory {
		$this->titleFactory = $this->titleFactory ?? new TitleFactory();
		return $this->titleFactory;
	}
}
