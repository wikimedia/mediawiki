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
		///Global array containing names of tracking categories and pagenames
		global $wgTrackingCat;

		$this->setHeaders();
		$this->outputHeader();
		$this->getOutput()->allowClickjacking();
		$this->getOutput()->addHTML(
			wfMessage( 'trackingcategories-desc' )->text() .
			'<hr>'
			);

		foreach( $wgTrackingCat as $catname=>$cat_pagename ) {
			/**
			 * Check if the tracking category varies by namespace
			 * Otherwise only pages in the current namespace will be displayed
			 * If it does vary, show pages considering all namespaces
			**/
			if( strstr( $this->msg( $catname )->plain(), '{' ) ) {
				$mw = new MWNamespace();
				$ns = $mw->getValidNamespaces();
				foreach ( $ns as $namesp ) {
					$cat_title = Title::makeTitleSafe( $namesp, $cat_pagename );
					$cat_titleText = Linker::link(
						$cat_title,
						htmlspecialchars( $cat_title->getText() )
						);

					$newContext = new DerivativeContext( $this->getContext() );
					$newContext->setTitle( $cat_title );
					$cat_msg = wfMessage( $catname )->setContext( $newContext )->text();
					$cat_desc = $catname . '-desc';

					$this->getOutput()->addHTML(
						'<li>'.
						$cat_titleText.
						'-----'.
						$cat_msg
						);

					/**
					 * Show category description if it exists as a system message
					 * as category-name-desc
					 */
					if ( !wfMessage( $cat_desc )->inContentLanguage()->isBlank() ) {
					$this->getOutput()->addHTML (
						'-(' .
						wfMessage( $cat_desc )->text() .
						')'
						);
					}
				}
			}
			else {
				$cat_title = Title::makeTitleSafe( NS_CATEGORY, $cat_pagename );
				$cat_titleText = Linker::link(
					$cat_title,
					htmlspecialchars( $cat_title->getText() )
					);
				$cat_msg = wfMessage( $catname )->text();
				$cat_desc = $catname . '-desc';

				$this->getOutput()->addHTML(
					'<li>' .
					$cat_titleText .
					'-----' .
					$cat_msg
					);

				/**
				 * Show category description if it exists as a system message
				 * as category-name-desc
				 */
				if ( !wfMessage( $cat_desc )->inContentLanguage()->isBlank() ) {
					$this->getOutput()->addHTML(
						'-(' .
						wfMessage( $cat_desc )->text() .
						')'
						);
				}
			}
		}
	}
}
