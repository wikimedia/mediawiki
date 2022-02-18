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

use MediaWiki\Block\BlockActionInfo;
use MediaWiki\Block\BlockRestrictionStore;
use MediaWiki\Block\BlockUtils;
use MediaWiki\Block\DatabaseBlock;
use MediaWiki\Cache\LinkBatchFactory;
use MediaWiki\CommentFormatter\RowCommentFormatter;
use Wikimedia\IPUtils;
use Wikimedia\Rdbms\IDatabase;
use Wikimedia\Rdbms\ILoadBalancer;

/**
 * A special page that lists existing blocks
 *
 * @ingroup SpecialPage
 */
class SpecialBlockList extends SpecialPage {
	protected $target;

	protected $options;

	protected $blockType;

	/** @var LinkBatchFactory */
	private $linkBatchFactory;

	/** @var BlockRestrictionStore */
	private $blockRestrictionStore;

	/** @var ILoadBalancer */
	private $loadBalancer;

	/** @var CommentStore */
	private $commentStore;

	/** @var BlockUtils */
	private $blockUtils;

	/** @var BlockActionInfo */
	private $blockActionInfo;

	/** @var RowCommentFormatter */
	private $rowCommentFormatter;

	public function __construct(
		LinkBatchFactory $linkBatchFactory,
		BlockRestrictionStore $blockRestrictionStore,
		ILoadBalancer $loadBalancer,
		CommentStore $commentStore,
		BlockUtils $blockUtils,
		BlockActionInfo $blockActionInfo,
		RowCommentFormatter $rowCommentFormatter
	) {
		parent::__construct( 'BlockList' );

		$this->linkBatchFactory = $linkBatchFactory;
		$this->blockRestrictionStore = $blockRestrictionStore;
		$this->loadBalancer = $loadBalancer;
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
		$this->addHelpLink( 'Help:Blocking_users' );
		$out = $this->getOutput();
		$out->setPageTitle( $this->msg( 'ipblocklist' ) );
		$out->addModuleStyles( [ 'mediawiki.special' ] );

		$request = $this->getRequest();
		$par = $request->getVal( 'ip', $par );
		$this->target = trim( $request->getVal( 'wpTarget', $par ) );

		$this->options = $request->getArray( 'wpOptions', [] );
		$this->blockType = $request->getVal( 'blockType' );

		$action = $request->getText( 'action' );

		if ( $action == 'unblock' || $action == 'submit' && $request->wasPosted() ) {
			# B/C @since 1.18: Unblock interface is now at Special:Unblock
			$title = $this->getSpecialPageFactory()->getTitleForAlias( 'Unblock/' . $this->target );
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
					'blocklist-tempblocks' => 'tempblocks',
					'blocklist-indefblocks' => 'indefblocks',
					'blocklist-autoblocks' => 'autoblocks',
					'blocklist-userblocks' => 'userblocks',
					'blocklist-addressblocks' => 'addressblocks',
					'blocklist-rangeblocks' => 'rangeblocks',
				],
				'flatlist' => true,
			],
		];

		$fields['BlockType'] = [
			'type' => 'select',
			'label-message' => 'blocklist-type',
			'options' => [
				$this->msg( 'blocklist-type-opt-all' )->escaped() => '',
				$this->msg( 'blocklist-type-opt-sitewide' )->escaped() => 'sitewide',
				$this->msg( 'blocklist-type-opt-partial' )->escaped() => 'partial',
			],
			'name' => 'blockType',
			'cssclass' => 'mw-field-block-type',
		];

		$fields['Limit'] = [
			'type' => 'limitselect',
			'label-message' => 'table_pager_limit_label',
			'options' => $pager->getLimitSelectList(),
			'name' => 'limit',
			'default' => $pager->getLimit(),
			'cssclass' => 'mw-field-limit mw-has-field-block-type',
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
		$db = $this->getDB();
		# Is the user allowed to see hidden blocks?
		if ( !$this->getAuthority()->isAllowed( 'hideuser' ) ) {
			$conds['ipb_deleted'] = 0;
		}

		if ( $this->target !== '' ) {
			list( $target, $type ) = $this->blockUtils->parseBlockTarget( $this->target );

			switch ( $type ) {
				case DatabaseBlock::TYPE_ID:
				case DatabaseBlock::TYPE_AUTO:
					$conds['ipb_id'] = $target;
					break;

				case DatabaseBlock::TYPE_IP:
				case DatabaseBlock::TYPE_RANGE:
					list( $start, $end ) = IPUtils::parseRange( $target );
					$conds[] = $db->makeList(
						[
							'ipb_address' => $target,
							DatabaseBlock::getRangeCond( $start, $end )
						],
						LIST_OR
					);
					$conds['ipb_auto'] = 0;
					break;

				case DatabaseBlock::TYPE_USER:
					$conds['ipb_address'] = $target->getName();
					$conds['ipb_auto'] = 0;
					break;
			}
		}

		# Apply filters
		if ( in_array( 'userblocks', $this->options ) ) {
			$conds['ipb_user'] = 0;
		}
		if ( in_array( 'autoblocks', $this->options ) ) {
			// ipb_parent_block_id = 0 because of T282890
			$conds[] = "ipb_parent_block_id IS NULL OR ipb_parent_block_id = 0";
		}
		if ( in_array( 'addressblocks', $this->options ) ) {
			$conds[] = "ipb_user != 0 OR ipb_range_end > ipb_range_start";
		}
		if ( in_array( 'rangeblocks', $this->options ) ) {
			$conds[] = "ipb_range_end = ipb_range_start";
		}

		$hideTemp = in_array( 'tempblocks', $this->options );
		$hideIndef = in_array( 'indefblocks', $this->options );
		if ( $hideTemp && $hideIndef ) {
			// If both types are hidden, ensure query doesn't produce any results
			$conds[] = '1=0';
		} elseif ( $hideTemp ) {
			$conds['ipb_expiry'] = $db->getInfinity();
		} elseif ( $hideIndef ) {
			$conds[] = "ipb_expiry != " . $db->addQuotes( $db->getInfinity() );
		}

		if ( $this->blockType === 'sitewide' ) {
			$conds['ipb_sitewide'] = 1;
		} elseif ( $this->blockType === 'partial' ) {
			$conds['ipb_sitewide'] = 0;
		}

		return new BlockListPager(
			$this->getContext(),
			$this->blockActionInfo,
			$this->blockRestrictionStore,
			$this->blockUtils,
			$this->commentStore,
			$this->linkBatchFactory,
			$this->getLinkRenderer(),
			$this->loadBalancer,
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
		$otherBlockLink = [];
		$this->getHookRunner()->onOtherBlockLogLink( $otherBlockLink, $this->target );

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

	/**
	 * Return a IDatabase object for reading
	 *
	 * @return IDatabase
	 */
	protected function getDB() {
		return $this->loadBalancer->getConnectionRef( ILoadBalancer::DB_REPLICA );
	}
}
