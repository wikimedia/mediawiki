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

/**
 * A special page that displays list of tracking categories
 * Tracking categories allow pages with certain characteristics to be tracked. 
 * It works by adding any such page to a category automatically.
 * Category is specified by the tracking category's system message.
 *
 * @ingroup SpecialPage
 * @since 1.23
 */

class SpecialTrackingCategories extends SpecialPage {

	function __construct() {
		parent::__construct( 'TrackingCategories' );
	}

	function execute( $par ) {
		// Global array containing names of tracking categories
		global $wgTrackingCategories;

		$this->setHeaders();
		$this->outputHeader();
		$this->getOutput()->allowClickjacking();

		foreach( $wgTrackingCategories as $catName ) {
			/*
			 * Check if the tracking category varies by namespace
			 * Otherwise only pages in the current namespace will be displayed
			 * If it does vary, show pages considering all namespaces
			 */
			$msgObj = $this->msg( $catName);
			if( strpos( $msgObj->plain(), '{{NAMESPACE}}' ) ) {
				$ns = MWNamespace::getValidNamespaces();
				foreach ( $ns as $namesp ) {
					$newContext = new DerivativeContext( $this->getContext() );
					$tempTitle = Title::makeTitle( $namesp, $catName );
					$newContext->setTitle( $tempTitle );
					$catMsg = $msgObj->inContentLanguage()->setContext( $newContext )->text();
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

					/*
					 * Show category description if it exists as a system message
					 * as category-name-desc
					 */
					$msgObj2 = $this->msg( $catDesc );
					if ( !$msgObj2->isBlank() ) {
						$this->getOutput()->addHTML (
							'(' .
							$msgObj2->text() .
							')'
						);
					}
				}
			} else {
				$catMsg = $msgObj->text();
				$catTitle = Title::makeTitle( NS_CATEGORY, $catMsg );
				$catTitleText = Linker::link(
					$catTitle,
					htmlspecialchars( $catMsg )
				);
				$catDesc = $catName . '-desc';

				$this->getOutput()->addHTML(
					'<li>' .
					$catTitleText
				);

				/*
				 * Show category description if it exists as a system message
				 * as category-name-desc
				 */
				$msgObj2 = $this->msg( $catDesc );
				if ( !$msgObj2->isBlank() ) {
					$this->getOutput()->addHTML(
						'(' .
						$msgObj2->text() .
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
