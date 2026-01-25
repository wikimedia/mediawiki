<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\Specials;

use MediaWiki\Block\BlockActionInfo;
use MediaWiki\Block\BlockRestrictionStore;
use MediaWiki\Block\BlockTargetFactory;
use MediaWiki\Block\HideUserUtils;
use MediaWiki\CommentFormatter\RowCommentFormatter;
use MediaWiki\CommentStore\CommentStore;
use MediaWiki\Html\Html;
use MediaWiki\HTMLForm\HTMLForm;
use MediaWiki\Page\LinkBatchFactory;
use MediaWiki\Pager\BlockListPager;
use MediaWiki\Parser\ParserOptions;
use MediaWiki\SpecialPage\SpecialPage;
use Wikimedia\Rdbms\IConnectionProvider;

/**
 * List of autoblocks
 *
 * @since 1.29
 * @ingroup SpecialPage
 */
class SpecialAutoblockList extends SpecialPage {

	public function __construct(
		private readonly LinkBatchFactory $linkBatchFactory,
		private readonly BlockRestrictionStore $blockRestrictionStore,
		private readonly IConnectionProvider $dbProvider,
		private readonly CommentStore $commentStore,
		private readonly BlockTargetFactory $blockTargetFactory,
		private readonly HideUserUtils $hideUserUtils,
		private readonly BlockActionInfo $blockActionInfo,
		private readonly RowCommentFormatter $rowCommentFormatter
	) {
		parent::__construct( 'AutoblockList' );
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

		$this->showList( $pager );
	}

	/**
	 * Setup a new BlockListPager instance.
	 * @return BlockListPager
	 */
	protected function getBlockListPager() {
		$conds = [
			$this->dbProvider->getReplicaDatabase()->expr( 'bl_parent_block_id', '!=', null ),
		];
		# Is the user allowed to see hidden blocks?
		if ( !$this->getAuthority()->isAllowed( 'hideuser' ) ) {
			$conds['bl_deleted'] = 0;
		}

		return new BlockListPager(
			$this->getContext(),
			$this->blockActionInfo,
			$this->blockRestrictionStore,
			$this->blockTargetFactory,
			$this->hideUserUtils,
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
			$out->addParserOutputContent(
				$pager->getFullOutput(),
				ParserOptions::newFromContext( $this->getContext() )
			);
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

	/** @inheritDoc */
	protected function getGroupName() {
		return 'users';
	}
}

/** @deprecated class alias since 1.41 */
class_alias( SpecialAutoblockList::class, 'SpecialAutoblockList' );
