<?php
/**
 * Implements Special:TrackingCategories
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

class SpecialTrackingCategories extends SpecialPage {

	function __construct() {
		parent::__construct( 'TrackingCategories' );
	}

	function execute( $par ) {
		///Global array containing names of tracking categories
		global $wgTrackingCat;

		$this->setHeaders();
		$this->outputHeader();
		$this->getOutput()->allowClickjacking();

		foreach( $wgTrackingCat as $catName ) {
			/**
			 * Check if the tracking category varies by namespace
			 * Otherwise only pages in the current namespace will be displayed
			 * If it does vary, show pages considering all namespaces
			**/
			if( strpos( $this->msg( $catName )->plain(), '{' ) ) {
				$ns = MWNamespace::getValidNamespaces();
				foreach ( $ns as $namesp ) {
					$newContext = new DerivativeContext( $this->getContext() );
					$tempTitle = Title::makeTitleSafe( $namesp, $catName );
						$newContext->setTitle( $tempTitle );
					$catMsg = $this->msg( $catName )->inContentLanguage()->setContext( $newContext )->text();
					$catTitle = Title::makeTitleSafe( $namesp, $catMsg );
					$catTitleText = Linker::link(
						$catTitle,
						htmlspecialchars( $catMsg )
					);
					$catDesc = $catName . '-desc';

					$this->getOutput()->addHTML(
						'<li>' .
						$catTitleText
					);

					/**
					 * Show category description if it exists as a system message
					 * as category-name-desc
					 */
					if ( !$this->msg( $catDesc )->isBlank() ) {
						$this->getOutput()->addHTML (
							'(' .
							$this->msg( $catDesc )->text() .
							')'
						);
					}
				}
			} else {
				$catMsg = $this->msg( $catName )->text();
				$catTitle = Title::makeTitleSafe( NS_CATEGORY, $catMsg );
				$catTitleText = Linker::link(
					$catTitle,
					htmlspecialchars( $catMsg )
				);
				$catDesc = $catName . '-desc';

				$this->getOutput()->addHTML(
					'<li>' .
					$catTitleText
				);

				/**
				 * Show category description if it exists as a system message
				 * as category-name-desc
				 */
				if ( !wfMessage( $catDesc )->inContentLanguage()->isBlank() ) {
					$this->getOutput()->addHTML(
						'(' .
						$this->msg( $catDesc )->text() .
						')'
					);
				}
			}
		}
	}

	protected function getGroupName() {
		return 'pages';
	}
}
