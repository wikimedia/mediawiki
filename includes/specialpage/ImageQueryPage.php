<?php
/**
 * Variant of QueryPage which uses a gallery to output results.
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

use Wikimedia\Rdbms\IDatabase;
use Wikimedia\Rdbms\IResultWrapper;

/**
 * Variant of QueryPage which uses a gallery to output results, thus
 * suited for reports generating images
 *
 * @stable to extend
 *
 * @ingroup SpecialPage
 * @author Rob Church <robchur@gmail.com>
 */
abstract class ImageQueryPage extends QueryPage {
	/**
	 * Format and output report results using the given information plus
	 * OutputPage
	 *
	 * @stable to override
	 *
	 * @param OutputPage $out OutputPage to print to
	 * @param Skin $skin User skin to use [unused]
	 * @param IDatabase $dbr (read) connection to use
	 * @param IResultWrapper $res Result pointer
	 * @param int $num Number of available result rows
	 * @param int $offset Paging offset
	 */
	protected function outputResults( $out, $skin, $dbr, $res, $num, $offset ) {
		if ( $num > 0 ) {
			$gallery = ImageGalleryBase::factory( false, $this->getContext() );

			# $res might contain the whole 1,000 rows, so we read up to
			# $num [should update this to use a Pager]
			$i = 0;
			foreach ( $res as $row ) {
				$i++;
				$namespace = $row->namespace ?? NS_FILE;
				$title = Title::makeTitleSafe( $namespace, $row->title );
				if ( $title instanceof Title && $title->getNamespace() == NS_FILE ) {
					$gallery->add( $title, $this->getCellHtml( $row ) );
				}
				if ( $i === $num ) {
					break;
				}
			}

			$out->addHTML( $gallery->toHTML() );
		}
	}

	/**
	 * @stable to override
	 *
	 * @param Skin $skin
	 * @param object $result
	 *
	 * @return bool|string
	 */
	protected function formatResult( $skin, $result ) {
		return false;
	}

	/**
	 * Get additional HTML to be shown in a results' cell
	 *
	 * @stable to override
	 *
	 * @param object $row Result row
	 * @return string
	 */
	protected function getCellHtml( $row ) {
		return '';
	}
}
