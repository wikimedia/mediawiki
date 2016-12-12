<?php

/**
 * Efficient paging for SQL queries.
 * IndexPager with a formatted navigation bar.
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
abstract class ChronologicalPager extends IndexPager {
	public $mDefaultDirection = IndexPager::DIR_DESCENDING;

	function getNavigationBar() {
		if ( !$this->isNavigationBarShown() ) {
			return '';
		}

		if ( isset( $this->mNavigationBar ) ) {
			return $this->mNavigationBar;
		}

		$linkTexts = [
			'prev' => $this->msg( 'pager-newer-n' )->numParams( $this->mLimit )->escaped(),
			'next' => $this->msg( 'pager-older-n' )->numParams( $this->mLimit )->escaped(),
			'first' => $this->msg( 'histlast' )->escaped(),
			'last' => $this->msg( 'histfirst' )->escaped()
		];

		$pagingLinks = $this->getPagingLinks( $linkTexts );
		$limitLinks = $this->getLimitLinks();
		$limits = $this->getLanguage()->pipeList( $limitLinks );
		$firstLastLinks = $this->msg( 'parentheses' )->rawParams( "{$pagingLinks['first']}" .
			$this->msg( 'pipe-separator' )->escaped() .
			"{$pagingLinks['last']}" )->escaped();

		$this->mNavigationBar = $firstLastLinks . ' ' .
			$this->msg( 'viewprevnext' )->rawParams(
				$pagingLinks['prev'], $pagingLinks['next'], $limits )->escaped();

		return $this->mNavigationBar;
	}
}
