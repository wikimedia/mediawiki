<?php
/**
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
 */

namespace MediaWiki\Specials;

use InvalidArgumentException;
use MediaWiki\Block\AutoBlockTarget;
use MediaWiki\Block\BlockActionInfo;
use MediaWiki\Block\BlockRestrictionStore;
use MediaWiki\Block\BlockTarget;
use MediaWiki\Block\BlockTargetFactory;
use MediaWiki\Block\BlockTargetWithIp;
use MediaWiki\Block\DatabaseBlockStore;
use MediaWiki\Block\HideUserUtils;
use MediaWiki\Block\UserBlockTarget;
use MediaWiki\Cache\LinkBatchFactory;
use MediaWiki\CommentFormatter\RowCommentFormatter;
use MediaWiki\CommentStore\CommentStore;
use MediaWiki\Html\Html;
use MediaWiki\HTMLForm\HTMLForm;
use MediaWiki\Pager\BlockListPager;
use MediaWiki\Parser\ParserOptions;
use MediaWiki\SpecialPage\SpecialPage;
use MediaWiki\User\TempUser\TempUserConfig;
use Wikimedia\Rdbms\IConnectionProvider;
use Wikimedia\Rdbms\IExpression;
use Wikimedia\Rdbms\IReadableDatabase;

/**
 * List of existing blocks
 *
 * @see SpecialBlock
 * @see SpecialAutoblockList
 * @ingroup SpecialPage
 */
class SpecialBlockList extends SpecialPage {
	/** @var string */
	protected $target;

	/** @var array */
	protected $options;

	/** @var string|null */
	protected $blockType;

	private LinkBatchFactory $linkBatchFactory;
	private DatabaseBlockStore $blockStore;
	private BlockRestrictionStore $blockRestrictionStore;
	private IConnectionProvider $dbProvider;
	private CommentStore $commentStore;
	private BlockTargetFactory $blockTargetFactory;
	private HideUserUtils $hideUserUtils;
	private BlockActionInfo $blockActionInfo;
	private RowCommentFormatter $rowCommentFormatter;
	private TempUserConfig $tempUserConfig;

	public function __construct(
		LinkBatchFactory $linkBatchFactory,
		DatabaseBlockStore $blockStore,
		BlockRestrictionStore $blockRestrictionStore,
		IConnectionProvider $dbProvider,
		CommentStore $commentStore,
		BlockTargetFactory $blockTargetFactory,
		HideUserUtils $hideUserUtils,
		BlockActionInfo $blockActionInfo,
		RowCommentFormatter $rowCommentFormatter,
		TempUserConfig $tempUserConfig
	) {
		parent::__construct( 'BlockList' );

		$this->linkBatchFactory = $linkBatchFactory;
		$this->blockStore = $blockStore;
		$this->blockRestrictionStore = $blockRestrictionStore;
		$this->dbProvider = $dbProvider;
		$this->commentStore = $commentStore;
		$this->blockTargetFactory = $blockTargetFactory;
		$this->hideUserUtils = $hideUserUtils;
		$this->blockActionInfo = $blockActionInfo;
		$this->rowCommentFormatter = $rowCommentFormatter;
		$this->tempUserConfig = $tempUserConfig;
	}

	/**
	 * @param string|null $par Title fragment
	 */
	public function execute( $par ) {
		$this->setHeaders();
		$this->outputHeader();
		$this->addHelpLink( 'Help:Blocking_users' );
		$out = $this->getOutput();
		$out->setPageTitleMsg( $this->msg( 'ipblocklist' ) );
		$out->addModuleStyles( [ 'mediawiki.special' ] );

		$request = $this->getRequest();
		$par = $request->getVal( 'ip', $par ?? '' );
		$this->target = trim( $request->getVal( 'wpTarget', $par ) );

		$this->options = $request->getArray( 'wpOptions', [] );
		$this->blockType = $request->getVal( 'blockType' );

		$action = $request->getText( 'action' );

		if ( $action == 'unblock' || ( $action == 'submit' && $request->wasPosted() ) ) {
			// B/C @since 1.18: Unblock interface is now at Special:Unblock
			$title = $this->getSpecialPageFactory()->getTitleForAlias( 'Unblock/' . $this->target );
			$out->redirect( $title->getFullURL() );

			return;
		}

		// Setup BlockListPager here to get the actual default Limit
		$pager = $this->getBlockListPager();

		$blockFilterOptions = [
			'blocklist-tempblocks' => 'tempblocks',
			'blocklist-indefblocks' => 'indefblocks',
			'blocklist-autoblocks' => 'autoblocks',
			'blocklist-addressblocks' => 'addressblocks',
			'blocklist-rangeblocks' => 'rangeblocks',
		];

		if ( $this->tempUserConfig->isKnown() ) {
			// Clarify that "userblocks" excludes named users only if temporary accounts are known (T380266)
			$blockFilterOptions['blocklist-nameduserblocks'] = 'userblocks';
			$blockFilterOptions['blocklist-tempuserblocks'] = 'tempuserblocks';
		} else {
			$blockFilterOptions['blocklist-userblocks'] = 'userblocks';
		}

		// Just show the block list
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
				'options-messages' => $blockFilterOptions,
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

		$form = HTMLForm::factory( 'ooui', $fields, $this->getContext() );
		$form
			->setMethod( 'get' )
			->setTitle( $this->getPageTitle() ) // Remove subpage
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

		// Add target conditions
		if ( $this->target !== '' ) {
			$target = $this->blockTargetFactory->newFromString( $this->target );
			if ( $target ) {
				$conds = $this->getTargetConds( $target );
			}
		}

		// Apply filters
		if ( in_array( 'userblocks', $this->options ) ) {
			$namedUserConds = $db->expr( 'bt_user', '=', null );

			// If temporary accounts are a known concept on this wiki,
			// have the "Hide account blocks" filter exclude only named users (T380266).
			if ( $this->tempUserConfig->isKnown() ) {
				$namedUserConds = $namedUserConds->orExpr(
					$this->tempUserConfig->getMatchCondition( $db, 'bt_user_text', IExpression::LIKE )
				);
			}

			$conds[] = $namedUserConds;
		}
		if ( in_array( 'autoblocks', $this->options ) ) {
			$conds['bl_parent_block_id'] = null;
		}
		if ( in_array( 'addressblocks', $this->options )
			&& in_array( 'rangeblocks', $this->options )
		) {
			// Simpler conditions for only user blocks (T360864)
			$conds[] = $db->expr( 'bt_user', '!=', null );
		} elseif ( in_array( 'addressblocks', $this->options ) ) {
			$conds[] = $db->expr( 'bt_user', '!=', null )->or( 'bt_range_start', '!=', null );
		} elseif ( in_array( 'rangeblocks', $this->options ) ) {
			$conds['bt_range_start'] = null;
		}

		if (
			in_array( 'tempuserblocks', $this->options ) &&
			$this->tempUserConfig->isKnown()
		) {
			$conds[] = $db->expr( 'bt_user', '=', null )
				->orExpr(
					$this->tempUserConfig->getMatchCondition( $db, 'bt_user_text', IExpression::NOT_LIKE )
				);
		}

		$hideTemp = in_array( 'tempblocks', $this->options );
		$hideIndef = in_array( 'indefblocks', $this->options );
		if ( $hideTemp && $hideIndef ) {
			// If both types are hidden, ensure query doesn't produce any results
			$conds[] = '1=0';
		} elseif ( $hideTemp ) {
			$conds['bl_expiry'] = $db->getInfinity();
		} elseif ( $hideIndef ) {
			$conds[] = $db->expr( 'bl_expiry', '!=', $db->getInfinity() );
		}

		if ( $this->blockType === 'sitewide' ) {
			$conds['bl_sitewide'] = 1;
		} elseif ( $this->blockType === 'partial' ) {
			$conds['bl_sitewide'] = 0;
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
	 * Get conditions matching a parsed block target.
	 *
	 * The details are different from other similarly named functions elsewhere:
	 *   - If an IP address or range is requested, autoblocks are not shown.
	 *   - Requests for single IP addresses include range blocks covering the
	 *     address. This is like a "vague target" query in DatabaseBlockStore,
	 *     except that autoblocks are excluded.
	 *   - If a named user doesn't exist, it is assumed that there are no blocks.
	 *
	 * @param BlockTarget $target
	 * @return array
	 */
	private function getTargetConds( BlockTarget $target ) {
		if ( $target instanceof AutoBlockTarget ) {
			return [ 'bl_id' => $target->getId() ];
		}
		if ( $target instanceof BlockTargetWithIp ) {
			$range = $target->toHexRange();
			return [
				$this->blockStore->getRangeCond( $range[0], $range[1] ),
				'bt_auto' => 0
			];
		}
		if ( $target instanceof UserBlockTarget ) {
			$user = $target->getUserIdentity();
			if ( $user->getId() ) {
				return [
					'bt_user' => $user->getId(),
					'bt_auto' => 0
				];
			} else {
				// No such user
				return [ '1=0' ];
			}
		}
		throw new InvalidArgumentException( 'Invalid block target type' );
	}

	/**
	 * Show the list of blocked accounts matching the actual filter.
	 * @param BlockListPager $pager The BlockListPager instance for this page
	 */
	protected function showList( BlockListPager $pager ) {
		$out = $this->getOutput();

		// Check for other blocks, i.e. global/tor blocks
		$otherBlockLink = [];
		$this->getHookRunner()->onOtherBlockLogLink( $otherBlockLink, $this->target );

		// Show additional header for the local block only when other blocks exists.
		// Not necessary in a standard installation without such extensions enabled
		if ( count( $otherBlockLink ) ) {
			$out->addHTML(
				Html::element( 'h2', [], $this->msg( 'ipblocklist-localblock' )->text() ) . "\n"
			);
		}

		if ( $pager->getNumRows() ) {
			$out->addParserOutputContent(
				$pager->getFullOutput(),
				ParserOptions::newFromContext( $this->getContext() )
			);
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

	/** @inheritDoc */
	protected function getGroupName() {
		return 'users';
	}

	/**
	 * Return a IDatabase object for reading
	 *
	 * @return IReadableDatabase
	 */
	protected function getDB() {
		return $this->dbProvider->getReplicaDatabase();
	}
}

/** @deprecated class alias since 1.41 */
class_alias( SpecialBlockList::class, 'SpecialBlockList' );
