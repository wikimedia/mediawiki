<?php
/**
 * Implements Special:AutoblockList
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
 * A special page that lists autoblocks
 *
 * @since 1.29
 * @ingroup SpecialPage
 */
class SpecialAutoblockList extends SpecialPage {

	function __construct() {
		parent::__construct( 'AutoblockList' );
	}

	/**
	 * Main execution point
	 *
	 * @param string $par Title fragment
	 */
	public function execute( $par ) {
		$this->setHeaders();
		$this->outputHeader();
		$out = $this->getOutput();
		$lang = $this->getLanguage();
		$out->setPageTitle( $this->msg( 'autoblocklist' ) );
		$this->addHelpLink( 'Autoblock' );
		$out->addModuleStyles( [ 'mediawiki.special' ] );

		# setup BlockListPager here to get the actual default Limit
		$pager = $this->getBlockListPager();

		# Just show the block list
		$fields = [
			'Limit' => [
				'type' => 'limitselect',
				'label-message' => 'table_pager_limit_label',
				'options' => [
					$lang->formatNum( 20 ) => 20,
					$lang->formatNum( 50 ) => 50,
					$lang->formatNum( 100 ) => 100,
					$lang->formatNum( 250 ) => 250,
					$lang->formatNum( 500 ) => 500,
				],
				'name' => 'limit',
				'default' => $pager->getLimit(),
			]
		];

		$context = new DerivativeContext( $this->getContext() );
		$context->setTitle( $this->getPageTitle() ); // Remove subpage
		$form = HTMLForm::factory( 'ooui', $fields, $context );
		$form->setMethod( 'get' )
			->setFormIdentifier( 'blocklist' )
			->setWrapperLegendMsg( 'autoblocklist-legend' )
			->setSubmitTextMsg( 'autoblocklist-submit' )
			->setSubmitProgressive()
			->prepareForm()
			->displayForm( false );

		$this->showList( $pager );
	}

	/**
	 * Setup a new BlockListPager instance.
	 * @return BlockListPager
	 */
	protected function getBlockListPager() {
		$conds = [
			'ipb_parent_block_id IS NOT NULL'
		];
		# Is the user allowed to see hidden blocks?
		if ( !$this->getUser()->isAllowed( 'hideuser' ) ) {
			$conds['ipb_deleted'] = 0;
		}

		return new BlockListPager( $this, $conds );
	}

	/**
	 * Show the list of blocked accounts matching the actual filter.
	 * @param BlockListPager $pager The BlockListPager instance for this page
	 */
	protected function showList( BlockListPager $pager ) {
		$out = $this->getOutput();

		# Check for other blocks, i.e. global/tor blocks
		$otherAutoblockLink = [];
		Hooks::run( 'OtherAutoblockLogLink', [ &$otherAutoblockLink ] );

		# Show additional header for the local block only when other blocks exists.
		# Not necessary in a standard installation without such extensions enabled
		if ( count( $otherAutoblockLink ) ) {
			$out->addHTML(
				Html::element( 'h2', [], $this->msg( 'autoblocklist-localblocks',
					$pager->getNumRows() )->parse() )
				. "\n"
			);
		}

		if ( $pager->getNumRows() ) {
			$out->addParserOutputContent( $pager->getFullOutput() );
		} else {
			$out->addWikiMsg( 'autoblocklist-empty' );
		}

		if ( count( $otherAutoblockLink ) ) {
			$out->addHTML(
				Html::rawElement(
					'h2',
					[],
					$this->msg( 'autoblocklist-otherblocks', count( $otherAutoblockLink ) )->parse()
				) . "\n"
			);
			$list = '';
			foreach ( $otherAutoblockLink as $link ) {
				$list .= Html::rawElement( 'li', [], $link ) . "\n";
			}
			$out->addHTML(
				Html::rawElement(
					'ul',
					[ 'class' => 'mw-autoblocklist-otherblocks' ],
					$list
				) . "\n"
			);
		}
	}

	protected function getGroupName() {
		return 'users';
	}
}
