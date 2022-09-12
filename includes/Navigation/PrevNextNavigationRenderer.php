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

namespace MediaWiki\Navigation;

use MediaWiki\Title\Title;
use MessageLocalizer;

/**
 * Helper class for generating prev/next links for paging.
 *
 * @deprecated since 1.39 Use PagerNavigationBuilder instead
 * @since 1.34
 */
class PrevNextNavigationRenderer {

	/**
	 * @var MessageLocalizer
	 */
	private $messageLocalizer;

	/**
	 * @param MessageLocalizer $messageLocalizer
	 */
	public function __construct( MessageLocalizer $messageLocalizer ) {
		wfDeprecated( __CLASS__, '1.39' );
		$this->messageLocalizer = $messageLocalizer;
	}

	/**
	 * Generate (prev x| next x) (20|50|100...) type links for paging (only suitable when paging by
	 * numeric offset, not when paging by index)
	 *
	 * @param Title $title Title object to link
	 * @param int $offset
	 * @param int $limit
	 * @param array $query Optional URL query parameter string
	 * @param bool $atend Optional param for specified if this is the last page
	 * @return string
	 */
	public function buildPrevNextNavigation(
		Title $title,
		$offset,
		$limit,
		array $query = [],
		$atend = false
	) {
		$navBuilder = new PagerNavigationBuilder( $this->messageLocalizer );
		$navBuilder
			->setPage( $title )
			->setLinkQuery( [ 'limit' => $limit, 'offset' => $offset ] + $query )
			->setLimitLinkQueryParam( 'limit' )
			->setCurrentLimit( $limit )
			->setPrevTooltipMsg( 'prevn-title' )
			->setNextTooltipMsg( 'nextn-title' )
			->setLimitTooltipMsg( 'shown-title' );

		if ( $offset > 0 ) {
			$navBuilder->setPrevLinkQuery( [ 'offset' => (string)max( $offset - $limit, 0 ) ] );
		}
		if ( !$atend ) {
			$navBuilder->setNextLinkQuery( [ 'offset' => (string)( $offset + $limit ) ] );
		}

		return $navBuilder->getHtml();
	}
}
