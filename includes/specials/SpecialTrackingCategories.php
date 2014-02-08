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

		$this->getOutput()->addHTML(
			Xml::openElement( 'table', array( 'class' => 'mw-datatable TablePager', 'id' => 'mw-trackingcattable' ) ) . "\n" .
			"<thead><tr>
			<th class='trackingcatmsg'>" .
				$this->msg( 'trackingcatmsg' )->escaped() . "
			</th>
			<th>" .
				$this->msg( 'trackingcatname' )->escaped() .
			"</th>
			<th>" .
				$this->msg( 'trackingcatdesc' )->escaped() . "
			</th>
			</tr></thead>"
		);

		foreach( $wgTrackingCategories as $catName ) {
			/*
			 * Check if the tracking category varies by namespace
			 * Otherwise only pages in the current namespace will be displayed
			 * If it does vary, show pages considering all namespaces
			 */
			$msgObj = $this->msg( $catName );
			$allmsgs = array();
			$catDesc = $catName . '-desc';
			$msgObj2 = $this->msg( $catDesc );

			if ( strpos( $msgObj->plain(), '{{NAMESPACE}}' ) ) {
				$ns = MWNamespace::getValidNamespaces();
				foreach ( $ns as $namesp ) {
					$newContext = new DerivativeContext( $this->getContext() );
					$tempTitle = Title::makeTitleSafe( $namesp, $catName );
					$newContext->setTitle( $tempTitle );
					$catMsg = $msgObj->setContext( $newContext )->inContentLanguage()->text();
					if ( !empty( $catMsg ) ) {
						$catTitle = Title::makeTitleSafe( NS_CATEGORY, $catMsg );
						$catTitleText = Linker::link(
							$catTitle,
							htmlspecialchars( $catMsg )
						);
						array_push( $allmsgs, $catTitleText );
					}
				}
			} else {
				$catMsg = $msgObj->text();
				if ( !$msgObj->isBlank() ) {
					$catTitle = Title::makeTitleSafe( NS_CATEGORY, $catMsg );
					$catTitleText = Linker::link(
						$catTitle,
						htmlspecialchars( $catMsg )
					);
				} else {
					$catTitleText = $this->msg( 'trackcat-disabled' );
				}
				array_push( $allmsgs, $catTitleText );
			}

			/*
			 * Show category description if it exists as a system message
			 * as category-name-desc
			 */
			$msgObj2 = $this->msg( $catDesc );
			if ( $msgObj2->isBlank() ) {
				$msgObj2 = $this->msg( 'trackingcat-nodesc' );
			}

			$this->getOutput()->addHTML(
				Xml::openElement( 'tr' ) .
				Xml::openElement( 'td', array( 'class' => 'trackingcatmsg' ) ) .
					$this->getLanguage()->commaList( $allmsgs ) .
				Xml::closeElement( 'td' ) .
				Xml::openElement( 'td', array( 'class' => 'trackingcatname' ) ) .
					$catName .
				Xml::closeElement( 'td' ) .
				Xml::openElement( 'td', array( 'class' => 'trackingcatdesc' ) ) .
					$msgObj2 .
				Xml::closeElement( 'td' ) .
				Xml::closeElement( 'tr' )
			);
		}
		$this->getOutput()->addHTML( Xml::closeElement( 'table' ) );
	}

	protected function getGroupName() {
		return 'pages';
	}
}
