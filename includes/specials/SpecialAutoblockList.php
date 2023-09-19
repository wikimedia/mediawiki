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

namespace MediaWiki\Specials;

use HTMLForm;
use MediaWiki\Block\BlockActionInfo;
use MediaWiki\Block\BlockRestrictionStore;
use MediaWiki\Block\BlockUtils;
use MediaWiki\Cache\LinkBatchFactory;
use MediaWiki\CommentFormatter\RowCommentFormatter;
use MediaWiki\CommentStore\CommentStore;
use MediaWiki\Html\Html;
use MediaWiki\Pager\BlockListPager;
use MediaWiki\SpecialPage\SpecialPage;
use Wikimedia\Rdbms\IConnectionProvider;

/**
 * A special page that lists autoblocks
 *
 * @since 1.29
 * @ingroup SpecialPage
 */
class SpecialAutoblockList extends SpecialPage {

	private LinkBatchFactory $linkBatchFactory;
	private BlockRestrictionStore $blockRestrictionStore;
	private IConnectionProvider $dbProvider;
	private CommentStore $commentStore;
	private BlockUtils $blockUtils;
	private BlockActionInfo $blockActionInfo;
	private RowCommentFormatter $rowCommentFormatter;

	/**
	 * @param LinkBatchFactory $linkBatchFactory
	 * @param BlockRestrictionStore $blockRestrictionStore
	 * @param IConnectionProvider $dbProvider
	 * @param CommentStore $commentStore
	 * @param BlockUtils $blockUtils
	 * @param BlockActionInfo $blockActionInfo
	 * @param RowCommentFormatter $rowCommentFormatter
	 */
	public function __construct(
		LinkBatchFactory $linkBatchFactory,
		BlockRestrictionStore $blockRestrictionStore,
		IConnectionProvider $dbProvider,
		CommentStore $commentStore,
		BlockUtils $blockUtils,
		BlockActionInfo $blockActionInfo,
		RowCommentFormatter $rowCommentFormatter
	) {
		parent::__construct( 'AutoblockList' );

		$this->linkBatchFactory = $linkBatchFactory;
		$this->blockRestrictionStore = $blockRestrictionStore;
		$this->dbProvider = $dbProvider;
		$this->commentStore = $commentStore;
		$this->blockUtils = $blockUtils;
		$this->blockActionInfo = $blockActionInfo;
		$this->rowCommentFormatter = $rowCommentFormatter;
	}

	/**
	 * @param string|null $par Title fragment
	 */
	public function execute( $par ) {
		$this->setHeaders();
		$this->outputHeader();
		$out = $this->getOutput();
		$out->setPageTitleMsg( $this->msg( 'autoblocklist' ) );
		$this->addHelpLink( 'Autoblock' );
		$out->addModuleStyles( [ 'mediawiki.special' ] );

		# setup BlockListPager here to get the actual default Limit
		$pager = $this->getBlockListPager();

		# Just show the block list
		$fields = [
			'Limit' => [
				'type' => 'limitselect',
				'label-message' => 'table_pager_limit_label',
				'options' => $pager->getLimitSelectList(),
				'name' => 'limit',
				'default' => $pager->getLimit(),
			]
		];

		$form = HTMLForm::factory( 'ooui', $fields, $this->getContext() );
		$form->setMethod( 'get' )
			->setTitle( $this->getPageTitle() ) // Remove subpage
			->setFormIdentifier( 'blocklist' )
			->setWrapperLegendMsg( 'autoblocklist-legend' )
			->setSubmitTextMsg( 'autoblocklist-submit' )
			->prepareForm()
			->displayForm( false );

		$this->showTotal( $pager );
		$this->showList( $pager );
	}

	/**
	 * Setup a new BlockListPager instance.
	 * @return BlockListPager
	 */
	protected function getBlockListPager() {
		$conds = [
			'ipb_parent_block_id IS NOT NULL',
			// ipb_parent_block_id <> 0 because of T282890
			'ipb_parent_block_id <> 0',
		];
		# Is the user allowed to see hidden blocks?
		if ( !$this->getAuthority()->isAllowed( 'hideuser' ) ) {
			$conds['ipb_deleted'] = 0;
		}

		return new BlockListPager(
			$this->getContext(),
			$this->blockActionInfo,
			$this->blockRestrictionStore,
			$this->blockUtils,
			$this->commentStore,
			$this->linkBatchFactory,
			$this->getLinkRenderer(),
			$this->dbProvider,
			$this->rowCommentFormatter,
			$this->getSpecialPageFactory(),
			$conds
		);
	}

	/**
	 * Show total number of autoblocks on top of the table
	 *
	 * @param BlockListPager $pager The BlockListPager instance for this page
	 */
	protected function showTotal( BlockListPager $pager ) {
		$out = $this->getOutput();
		$out->addHTML(
			Html::rawElement( 'div', [ 'style' => 'font-weight: bold;' ],
				$this->msg( 'autoblocklist-total-autoblocks', $pager->getTotalAutoblocks() )->parse() )
			. "\n"
		);
	}

	/**
	 * Show the list of blocked accounts matching the actual filter.
	 * @param BlockListPager $pager The BlockListPager instance for this page
	 */
	protected function showList( BlockListPager $pager ) {
		$out = $this->getOutput();

		# Check for other blocks, i.e. global/tor blocks
		$otherAutoblockLink = [];
		$this->getHookRunner()->onOtherAutoblockLogLink( $otherAutoblockLink );

		# Show additional header for the local block only when other blocks exists.
		# Not necessary in a standard installation without such extensions enabled
		if ( count( $otherAutoblockLink ) ) {
			$out->addHTML(
				Html::rawElement( 'h2', [], $this->msg( 'autoblocklist-localblocks',
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

/**
 * @deprecated since 1.41
 */
class_alias( SpecialAutoblockList::class, 'SpecialAutoblockList' );
