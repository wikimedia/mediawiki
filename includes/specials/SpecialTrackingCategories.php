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
		$this->setHeaders();
		$this->outputHeader();
		$this->getOutput()->allowClickjacking();
		$this->getOutput()->addHTML(
			Html::openElement( 'table', [ 'class' => 'mw-datatable sortable',
				'id' => 'mw-trackingcategories-table' ] ) . "\n" .
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

		$trackingCategories = new TrackingCategories( $this->getConfig() );
		$categoryList = $trackingCategories->getTrackingCategories();

		$batch = new LinkBatch();
		foreach ( $categoryList as $catMsg => $data ) {
			$batch->addObj( $data['msg'] );
			foreach ( $data['cats'] as $catTitle ) {
				$batch->addObj( $catTitle );
			}
		}
		$batch->execute();

		Hooks::run( 'SpecialTrackingCategories::preprocess', [ $this, $categoryList ] );

		$linkRenderer = $this->getLinkRenderer();

		foreach ( $categoryList as $catMsg => $data ) {
			$allMsgs = [];
			$catDesc = $catMsg . '-desc';

			$catMsgTitleText = $linkRenderer->makeLink(
				$data['msg'],
				$catMsg
			);

			foreach ( $data['cats'] as $catTitle ) {
				$html = $linkRenderer->makeLink(
					$catTitle,
					$catTitle->getText()
				);

				Hooks::run( 'SpecialTrackingCategories::generateCatLink',
					[ $this, $catTitle, &$html ] );

				$allMsgs[] = $html;
			}

			# Extra message, when no category was found
			if ( !count( $allMsgs ) ) {
				$allMsgs[] = $this->msg( 'trackingcategories-disabled' )->parse();
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
				Html::openElement( 'td', [ 'class' => 'mw-trackingcategories-name' ] ) .
					$this->getLanguage()->commaList( array_unique( $allMsgs ) ) .
				Html::closeElement( 'td' ) .
				Html::openElement( 'td', [ 'class' => 'mw-trackingcategories-msg' ] ) .
					$catMsgTitleText .
				Html::closeElement( 'td' ) .
				Html::openElement( 'td', [ 'class' => 'mw-trackingcategories-desc' ] ) .
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
