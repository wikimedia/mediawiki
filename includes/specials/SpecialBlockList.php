<?php
/**
 * Implements Special:BlockList
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
 * A special page that lists existing blocks
 *
 * @ingroup SpecialPage
 */
class SpecialBlockList extends SpecialPage {
	protected $target;

	protected $options;

	function __construct() {
		parent::__construct( 'BlockList' );
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
		$out->setPageTitle( $this->msg( 'ipblocklist' ) );
		$out->addModuleStyles( [ 'mediawiki.special' ] );

		$request = $this->getRequest();
		$par = $request->getVal( 'ip', $par );
		$this->target = trim( $request->getVal( 'wpTarget', $par ) );

		$this->options = $request->getArray( 'wpOptions', [] );

		$action = $request->getText( 'action' );

		if ( $action == 'unblock' || $action == 'submit' && $request->wasPosted() ) {
			# B/C @since 1.18: Unblock interface is now at Special:Unblock
			$title = SpecialPage::getTitleFor( 'Unblock', $this->target );
			$out->redirect( $title->getFullURL() );

			return;
		}

		# setup BlockListPager here to get the actual default Limit
		$pager = $this->getBlockListPager();

		# Just show the block list
		$fields = [
			'Target' => [
				'type' => 'user',
				'label-message' => 'ipaddressorusername',
				'tabindex' => '1',
				'size' => '45',
				'default' => $this->target,
			],
			'Options' => [
				'type' => 'multiselect',
				'options-messages' => [
					'blocklist-userblocks' => 'userblocks',
					'blocklist-tempblocks' => 'tempblocks',
					'blocklist-addressblocks' => 'addressblocks',
					'blocklist-rangeblocks' => 'rangeblocks',
				],
				'flatlist' => true,
			],
			'Limit' => [
				'type' => 'limitselect',
				'label-message' => 'table_pager_limit_label',
				'options' => $pager->getLimitSelectList(),
				'name' => 'limit',
				'default' => $pager->getLimit(),
			],
		];
		$context = new DerivativeContext( $this->getContext() );
		$context->setTitle( $this->getPageTitle() ); // Remove subpage
		$form = HTMLForm::factory( 'ooui', $fields, $context );
		$form
			->setMethod( 'get' )
			->setFormIdentifier( 'blocklist' )
			->setWrapperLegendMsg( 'ipblocklist-legend' )
			->setSubmitTextMsg( 'ipblocklist-submit' )
			->prepareForm()
			->displayForm( false );

		$this->showList( $pager );
	}

	/**
	 * Setup a new BlockListPager instance.
	 * @return BlockListPager
	 */
	protected function getBlockListPager() {
		$conds = [];
		# Is the user allowed to see hidden blocks?
		if ( !$this->getUser()->isAllowed( 'hideuser' ) ) {
			$conds['ipb_deleted'] = 0;
		}

		if ( $this->target !== '' ) {
			list( $target, $type ) = Block::parseTarget( $this->target );

			switch ( $type ) {
				case Block::TYPE_ID:
				case Block::TYPE_AUTO:
					$conds['ipb_id'] = $target;
					break;

				case Block::TYPE_IP:
				case Block::TYPE_RANGE:
					list( $start, $end ) = IP::parseRange( $target );
					$conds[] = wfGetDB( DB_REPLICA )->makeList(
						[
							'ipb_address' => $target,
							Block::getRangeCond( $start, $end )
						],
						LIST_OR
					);
					$conds['ipb_auto'] = 0;
					break;

				case Block::TYPE_USER:
					$conds['ipb_address'] = $target->getName();
					$conds['ipb_auto'] = 0;
					break;
			}
		}

		# Apply filters
		if ( in_array( 'userblocks', $this->options ) ) {
			$conds['ipb_user'] = 0;
		}
		if ( in_array( 'tempblocks', $this->options ) ) {
			$conds['ipb_expiry'] = 'infinity';
		}
		if ( in_array( 'addressblocks', $this->options ) ) {
			$conds[] = "ipb_user != 0 OR ipb_range_end > ipb_range_start";
		}
		if ( in_array( 'rangeblocks', $this->options ) ) {
			$conds[] = "ipb_range_end = ipb_range_start";
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
		$otherBlockLink = [];
		Hooks::run( 'OtherBlockLogLink', [ &$otherBlockLink, $this->target ] );

		# Show additional header for the local block only when other blocks exists.
		# Not necessary in a standard installation without such extensions enabled
		if ( count( $otherBlockLink ) ) {
			$out->addHTML(
				Html::element( 'h2', [], $this->msg( 'ipblocklist-localblock' )->text() ) . "\n"
			);
		}

		if ( $pager->getNumRows() ) {
			$out->addParserOutputContent( $pager->getFullOutput() );
		} elseif ( $this->target ) {
			$out->addWikiMsg( 'ipblocklist-no-results' );
		} else {
			$out->addWikiMsg( 'ipblocklist-empty' );
		}

		if ( count( $otherBlockLink ) ) {
			$out->addHTML(
				Html::rawElement(
					'h2',
					[],
					$this->msg( 'ipblocklist-otherblocks', count( $otherBlockLink ) )->parse()
				) . "\n"
			);
			$list = '';
			foreach ( $otherBlockLink as $link ) {
				$list .= Html::rawElement( 'li', [], $link ) . "\n";
			}
			$out->addHTML( Html::rawElement(
				'ul',
				[ 'class' => 'mw-ipblocklist-otherblocks' ],
				$list
			) . "\n" );
		}
	}

	protected function getGroupName() {
		return 'users';
	}
}
