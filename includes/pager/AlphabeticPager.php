<?php
/**
 * Efficient paging for SQL queries.
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
 * @ingroup Pager
 */

/**
 * IndexPager with an alphabetic list and a formatted navigation bar
 * @ingroup Pager
 */
abstract class AlphabeticPager extends IndexPager {

	/**
	 * Shamelessly stolen bits from ReverseChronologicalPager,
	 * didn't want to do class magic as may be still revamped
	 *
	 * @return string HTML
	 */
	function getNavigationBar() {
		if ( !$this->isNavigationBarShown() ) {
			return '';
		}

		if ( isset( $this->mNavigationBar ) ) {
			return $this->mNavigationBar;
		}

		$linkTexts = array(
			'prev' => $this->msg( 'prevn' )->numParams( $this->mLimit )->escaped(),
			'next' => $this->msg( 'nextn' )->numParams( $this->mLimit )->escaped(),
			'first' => $this->msg( 'page_first' )->escaped(),
			'last' => $this->msg( 'page_last' )->escaped()
		);

		$lang = $this->getLanguage();

		$pagingLinks = $this->getPagingLinks( $linkTexts );
		$limitLinks = $this->getLimitLinks();
		$limits = $lang->pipeList( $limitLinks );

		$this->mNavigationBar = $this->msg( 'parentheses' )->rawParams(
			$lang->pipeList( array( $pagingLinks['first'],
			$pagingLinks['last'] ) ) )->escaped() . " " .
			$this->msg( 'viewprevnext' )->rawParams( $pagingLinks['prev'],
				$pagingLinks['next'], $limits )->escaped();

		if ( !is_array( $this->getIndexField() ) ) {
			# Early return to avoid undue nesting
			return $this->mNavigationBar;
		}

		$extra = '';
		$first = true;
		$msgs = $this->getOrderTypeMessages();
		foreach ( array_keys( $msgs ) as $order ) {
			if ( $first ) {
				$first = false;
			} else {
				$extra .= $this->msg( 'pipe-separator' )->escaped();
			}

			if ( $order == $this->mOrderType ) {
				$extra .= $this->msg( $msgs[$order] )->escaped();
			} else {
				$extra .= $this->makeLink(
					$this->msg( $msgs[$order] )->escaped(),
					array( 'order' => $order )
				);
			}
		}

		if ( $extra !== '' ) {
			$extra = ' ' . $this->msg( 'parentheses' )->rawParams( $extra )->escaped();
			$this->mNavigationBar .= $extra;
		}

		return $this->mNavigationBar;
	}

	/**
	 * If this supports multiple order type messages, give the message key for
	 * enabling each one in getNavigationBar.  The return type is an associative
	 * array whose keys must exactly match the keys of the array returned
	 * by getIndexField(), and whose values are message keys.
	 *
	 * @return array
	 */
	protected function getOrderTypeMessages() {
		return null;
	}
}
