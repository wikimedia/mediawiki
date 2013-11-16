<?php
/**
 * Variant of QueryPage which formats the result as a simple link to the page.
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
 * @ingroup SpecialPage
 */

/**
 * Variant of QueryPage which formats the result as a simple link to the page
 *
 * @ingroup SpecialPage
 */
abstract class PageQueryPage extends QueryPage {

	/**
	 * Pre-cache page existence to speed up link generation
	 *
	 * @param $db DatabaseBase connection
	 * @param ResultWrapper $res
	 */
	public function preprocessResults( $db, $res ) {
		// nothing to do, if query is fresh and only select existing pages
		// or there are no data
		if ( !$this->isCached() || !$res->numRows() ) {
			return;
		}

		$batch = new LinkBatch();
		foreach ( $res as $row ) {
			$batch->add( $row->namespace, $row->title );
		}
		$batch->execute();

		$res->seek( 0 );
	}

	/**
	 * Format the result as a simple link to the page
	 *
	 * @param Skin $skin
	 * @param object $row Result row
	 * @return string
	 */
	public function formatResult( $skin, $row ) {
		global $wgContLang;

		$title = Title::makeTitleSafe( $row->namespace, $row->title );

		if ( $title instanceof Title ) {
			$text = $wgContLang->convert( $title->getPrefixedText() );
			if ( $this->isCached() ) {
				return Linker::link( $title, htmlspecialchars( $text ) );
			} else {
				return Linker::linkKnown( $title, htmlspecialchars( $text ) );
			}
		} else {
			return Html::element( 'span', array( 'class' => 'mw-invalidtitle' ),
				Linker::getInvalidTitleDescription( $this->getContext(), $row->namespace, $row->title ) );
		}
	}
}
