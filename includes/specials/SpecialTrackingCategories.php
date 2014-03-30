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
			Html::openElement( 'table', array( 'class' => 'mw-datatable TablePager',
				'id' => 'mw-trackingcategories-table' ) ) . "\n" .
			"<thead><tr>
			<th>" .
				$this->msg( 'trackingcategories-msg' )->escaped() . "
			</th>
			<th>" .
				$this->msg( 'trackingcategories-name' )->escaped() .
			"</th>
			<th>" .
				$this->msg( 'trackingcategories-desc' )->escaped() . "
			</th>
			</tr></thead>"
		);

		foreach( $wgTrackingCategories as $catMsg ) {
			/*
			 * Check if the tracking category varies by namespace
			 * Otherwise only pages in the current namespace will be displayed
			 * If it does vary, show pages considering all namespaces
			 */
			$msgObj = $this->msg( $catMsg )->inContentLanguage();
			$allMsgs = array();
			$catDesc = $catMsg . '-desc';
			$catMsgTitle = Title::makeTitleSafe( NS_MEDIAWIKI, $catMsg );
			$catMsgTitleText = Linker::link(
				$catMsgTitle,
				htmlspecialchars( $catMsg )
			);

			if ( strpos( $msgObj->plain(), '{{NAMESPACE}}' ) !== false ) {
				$ns = MWNamespace::getValidNamespaces();
				foreach ( $ns as $namesp ) {
					$tempTitle = Title::makeTitleSafe( $namesp, $catMsg );
					$catName = $msgObj->title( $tempTitle )->text();
					if ( !$msgObj->isDisabled() ) {
						$catTitle = Title::makeTitleSafe( NS_CATEGORY, $catName );
						$catTitleText = Linker::link(
							$catTitle,
							htmlspecialchars( $catName )
						);
						$allMsgs[] = $catTitleText;
					}
				}
			} else {
				$catName = $msgObj->text();
				if ( !$msgObj->isDisabled() ) {
					$catTitle = Title::makeTitleSafe( NS_CATEGORY, $catName );
					$catTitleText = Linker::link(
						$catTitle,
						htmlspecialchars( $catName )
					);
					$classes = array();
				} else {
					$catTitleText = $this->msg( 'trackingcategories-disabled' )->parse();
				}
				$allMsgs[] = $catTitleText;
			}

			/*
			 * Show category description if it exists as a system message
			 * as category-name-desc
			 */
			$descMsg = $this->msg( $catDesc );
			if ( $descMsg->isBlank() ) {
				$descMsg = $this->msg( 'trackingcategories-nodesc' );
			}

			$this->getOutput()->addHTML(
				Html::openElement( 'tr' ) .
				Html::openElement( 'td', array( 'class' => 'mw-trackingcategories-name' ) ) .
					$this->getLanguage()->commaList( array_unique( $allMsgs ) ) .
				Html::closeElement( 'td' ) .
				Html::openElement( 'td', array( 'class' => 'mw-trackingcategories-msg' ) ) .
					$catMsgTitleText .
				Html::closeElement( 'td' ) .
				Html::openElement( 'td', array( 'class' => 'mw-trackingcategories-desc' ) ) .
					$descMsg->parse() .
				Html::closeElement( 'td' ) .
				Html::closeElement( 'tr' )
			);
		}
		$this->getOutput()->addHTML( Html::closeElement( 'table' ) );
	}

	protected function getGroupName() {
		return 'pages';
	}
}
