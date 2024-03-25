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

namespace MediaWiki\Specials;

use MediaWiki\Block\BlockActionInfo;
use MediaWiki\Block\BlockRestrictionStore;
use MediaWiki\Block\BlockUtils;
use MediaWiki\Block\DatabaseBlock;
use MediaWiki\Block\DatabaseBlockStore;
use MediaWiki\Block\HideUserUtils;
use MediaWiki\Cache\LinkBatchFactory;
use MediaWiki\CommentFormatter\RowCommentFormatter;
use MediaWiki\CommentStore\CommentStore;
use MediaWiki\Config\ConfigException;
use MediaWiki\Html\Html;
use MediaWiki\HTMLForm\HTMLForm;
use MediaWiki\MainConfigNames;
use MediaWiki\Pager\BlockListPager;
use MediaWiki\SpecialPage\SpecialPage;
use Wikimedia\IPUtils;
use Wikimedia\Rdbms\IConnectionProvider;
use Wikimedia\Rdbms\IReadableDatabase;

/**
 * A special page that lists existing blocks
 *
 * @ingroup SpecialPage
 */
class SpecialBlockList extends SpecialPage {
	protected $target;

	protected $options;

	protected $blockType;

	private LinkBatchFactory $linkBatchFactory;
	private DatabaseBlockStore $blockStore;
	private BlockRestrictionStore $blockRestrictionStore;
	private IConnectionProvider $dbProvider;
	private CommentStore $commentStore;
	private BlockUtils $blockUtils;
	private HideUserUtils $hideUserUtils;
	private BlockActionInfo $blockActionInfo;
	private RowCommentFormatter $rowCommentFormatter;

	public function __construct(
		LinkBatchFactory $linkBatchFactory,
		DatabaseBlockStore $blockStore,
		BlockRestrictionStore $blockRestrictionStore,
		IConnectionProvider $dbProvider,
		CommentStore $commentStore,
		BlockUtils $blockUtils,
		HideUserUtils $hideUserUtils,
		BlockActionInfo $blockActionInfo,
		RowCommentFormatter $rowCommentFormatter
	) {
		parent::__construct( 'BlockList' );

		$this->linkBatchFactory = $linkBatchFactory;
		$this->blockStore = $blockStore;
		$this->blockRestrictionStore = $blockRestrictionStore;
		$this->dbProvider = $dbProvider;
		$this->commentStore = $commentStore;
		$this->blockUtils = $blockUtils;
		$this->hideUserUtils = $hideUserUtils;
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
		$readStage = $this->getConfig()
				->get( MainConfigNames::BlockTargetMigrationStage ) & SCHEMA_COMPAT_READ_MASK;
		if ( $readStage === SCHEMA_COMPAT_READ_OLD ) {
			$bl_deleted = 'ipb_deleted';
			$bl_id = 'ipb_id';
			$bt_auto = 'ipb_auto';
			$bt_user = 'ipb_user';
			$bl_expiry = 'ipb_expiry';
			$bl_sitewide = 'ipb_sitewide';
		} elseif ( $readStage === SCHEMA_COMPAT_READ_NEW ) {
			$bl_deleted = 'bl_deleted';
			$bl_id = 'bl_id';
			$bt_auto = 'bt_auto';
			$bt_user = 'bt_user';
			$bl_expiry = 'bl_expiry';
			$bl_sitewide = 'bl_sitewide';
		} else {
			throw new ConfigException(
				'$wgBlockTargetMigrationStage has an invalid read stage' );
		}

		$conds = [];
		$db = $this->getDB();

		if ( $this->target !== '' ) {
			[ $target, $type ] = $this->blockUtils->parseBlockTarget( $this->target );

			switch ( $type ) {
				case DatabaseBlock::TYPE_ID:
				case DatabaseBlock::TYPE_AUTO:
					$conds[$bl_id] = $target;
					break;

				case DatabaseBlock::TYPE_IP:
				case DatabaseBlock::TYPE_RANGE:
					[ $start, $end ] = IPUtils::parseRange( $target );
					$conds[] = $this->blockStore->getRangeCond( $start, $end,
						DatabaseBlockStore::SCHEMA_CURRENT );
					$conds[$bt_auto] = 0;
					break;

				case DatabaseBlock::TYPE_USER:
					if ( $target->getId() ) {
						$conds[$bt_user] = $target->getId();
						$conds[$bt_auto] = 0;
					} else {
						// No such user
						$conds[] = '1=0';
					}
					break;
			}
		}

		// Apply filters
		if ( in_array( 'userblocks', $this->options ) ) {
			if ( $readStage === SCHEMA_COMPAT_READ_OLD ) {
				$conds['ipb_user'] = 0;
			} else {
				$conds['bt_user'] = null;
			}
		}
		if ( in_array( 'autoblocks', $this->options ) ) {
			if ( $readStage === SCHEMA_COMPAT_READ_OLD ) {
				// ipb_parent_block_id = 0 because of T282890
				$conds['ipb_parent_block_id'] = [ null, 0 ];
			} else {
				$conds['bl_parent_block_id'] = null;
			}
		}
		if ( in_array( 'addressblocks', $this->options )
			&& in_array( 'rangeblocks', $this->options )
		) {
			// Simpler conditions for only user blocks (T360864)
			if ( $readStage === SCHEMA_COMPAT_READ_OLD ) {
				$conds[] = "ipb_user != 0";
			} else {
				$conds[] = "bt_user IS NOT NULL";
			}
		} elseif ( in_array( 'addressblocks', $this->options ) ) {
			if ( $readStage === SCHEMA_COMPAT_READ_OLD ) {
				$conds[] = "ipb_user != 0 OR ipb_range_end > ipb_range_start";
			} else {
				$conds[] = "bt_user IS NOT NULL OR bt_range_start IS NOT NULL";
			}
		} elseif ( in_array( 'rangeblocks', $this->options ) ) {
			if ( $readStage === SCHEMA_COMPAT_READ_OLD ) {
				$conds[] = "ipb_range_end = ipb_range_start";
			} else {
				$conds['bt_range_start'] = null;
			}
		}

		$hideTemp = in_array( 'tempblocks', $this->options );
		$hideIndef = in_array( 'indefblocks', $this->options );
		if ( $hideTemp && $hideIndef ) {
			// If both types are hidden, ensure query doesn't produce any results
			$conds[] = '1=0';
		} elseif ( $hideTemp ) {
			$conds[$bl_expiry] = $db->getInfinity();
		} elseif ( $hideIndef ) {
			$conds[] = $db->expr( $bl_expiry, '!=', $db->getInfinity() );
		}

		if ( $this->blockType === 'sitewide' ) {
			$conds[$bl_sitewide] = 1;
		} elseif ( $this->blockType === 'partial' ) {
			$conds[$bl_sitewide] = 0;
		}

		return new BlockListPager(
			$this->getContext(),
			$this->blockActionInfo,
			$this->blockRestrictionStore,
			$this->blockUtils,
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
	 * @return IReadableDatabase
	 */
	protected function getDB() {
		return $this->dbProvider->getReplicaDatabase();
	}
}

/** @deprecated class alias since 1.41 */
class_alias( SpecialBlockList::class, 'SpecialBlockList' );
