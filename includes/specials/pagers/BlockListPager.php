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
 * @ingroup Pager
 */

use MediaWiki\Block\BlockActionInfo;
use MediaWiki\Block\BlockRestrictionStore;
use MediaWiki\Block\BlockUtils;
use MediaWiki\Block\Restriction\ActionRestriction;
use MediaWiki\Block\Restriction\NamespaceRestriction;
use MediaWiki\Block\Restriction\PageRestriction;
use MediaWiki\Block\Restriction\Restriction;
use MediaWiki\Cache\LinkBatchFactory;
use MediaWiki\CommentFormatter\RowCommentFormatter;
use MediaWiki\Linker\LinkRenderer;
use MediaWiki\SpecialPage\SpecialPageFactory;
use MediaWiki\User\UserIdentity;
use Wikimedia\IPUtils;
use Wikimedia\Rdbms\ILoadBalancer;
use Wikimedia\Rdbms\IResultWrapper;

/**
 * @ingroup Pager
 */
class BlockListPager extends TablePager {

	protected $conds;

	/**
	 * Array of restrictions.
	 *
	 * @var Restriction[]
	 */
	protected $restrictions = [];

	/** @var BlockActionInfo */
	private $blockActionInfo;

	/** @var BlockRestrictionStore */
	private $blockRestrictionStore;

	/** @var BlockUtils */
	private $blockUtils;

	/** @var CommentStore */
	private $commentStore;

	/** @var LinkBatchFactory */
	private $linkBatchFactory;

	/** @var RowCommentFormatter */
	private $rowCommentFormatter;

	/** @var SpecialPageFactory */
	private $specialPageFactory;

	/** @var string[] */
	private $formattedComments = [];

	/**
	 * @param IContextSource $context
	 * @param BlockActionInfo $blockActionInfo
	 * @param BlockRestrictionStore $blockRestrictionStore
	 * @param BlockUtils $blockUtils
	 * @param CommentStore $commentStore
	 * @param LinkBatchFactory $linkBatchFactory
	 * @param LinkRenderer $linkRenderer
	 * @param ILoadBalancer $loadBalancer
	 * @param RowCommentFormatter $rowCommentFormatter
	 * @param SpecialPageFactory $specialPageFactory
	 * @param array $conds
	 */
	public function __construct(
		IContextSource $context,
		BlockActionInfo $blockActionInfo,
		BlockRestrictionStore $blockRestrictionStore,
		BlockUtils $blockUtils,
		CommentStore $commentStore,
		LinkBatchFactory $linkBatchFactory,
		LinkRenderer $linkRenderer,
		ILoadBalancer $loadBalancer,
		RowCommentFormatter $rowCommentFormatter,
		SpecialPageFactory $specialPageFactory,
		$conds
	) {
		// Set database before parent constructor to avoid setting it there with wfGetDB
		$this->mDb = $loadBalancer->getConnectionRef( ILoadBalancer::DB_REPLICA );
		parent::__construct( $context, $linkRenderer );
		$this->blockActionInfo = $blockActionInfo;
		$this->blockRestrictionStore = $blockRestrictionStore;
		$this->blockUtils = $blockUtils;
		$this->commentStore = $commentStore;
		$this->linkBatchFactory = $linkBatchFactory;
		$this->rowCommentFormatter = $rowCommentFormatter;
		$this->specialPageFactory = $specialPageFactory;
		$this->conds = $conds;
		$this->mDefaultDirection = IndexPager::DIR_DESCENDING;
	}

	protected function getFieldNames() {
		static $headers = null;

		if ( $headers === null ) {
			$headers = [
				'ipb_timestamp' => 'blocklist-timestamp',
				'ipb_target' => 'blocklist-target',
				'ipb_expiry' => 'blocklist-expiry',
				'ipb_by' => 'blocklist-by',
				'ipb_params' => 'blocklist-params',
				'ipb_reason' => 'blocklist-reason',
			];
			foreach ( $headers as $key => $val ) {
				$headers[$key] = $this->msg( $val )->text();
			}
		}

		return $headers;
	}

	/**
	 * @param string $name
	 * @param string|null $value
	 * @return string
	 * @suppress PhanTypeArraySuspicious
	 */
	public function formatValue( $name, $value ) {
		static $msg = null;
		if ( $msg === null ) {
			$keys = [
				'anononlyblock',
				'createaccountblock',
				'noautoblockblock',
				'emailblock',
				'blocklist-nousertalk',
				'unblocklink',
				'change-blocklink',
				'blocklist-editing',
				'blocklist-editing-sitewide',
			];

			foreach ( $keys as $key ) {
				$msg[$key] = $this->msg( $key )->text();
			}
		}
		'@phan-var string[] $msg';

		/** @var stdClass $row */
		$row = $this->mCurrentRow;

		$language = $this->getLanguage();

		$formatted = '';

		$linkRenderer = $this->getLinkRenderer();

		switch ( $name ) {
			case 'ipb_timestamp':
				$formatted = htmlspecialchars( $language->userTimeAndDate( $value, $this->getUser() ) );
				break;

			case 'ipb_target':
				if ( $row->ipb_auto ) {
					$formatted = $this->msg( 'autoblockid', $row->ipb_id )->parse();
				} else {
					list( $target, ) = $this->blockUtils->parseBlockTarget( $row->ipb_address );

					if ( is_string( $target ) ) {
						if ( IPUtils::isValidRange( $target ) ) {
							$target = User::newFromName( $target, false );
						} else {
							$formatted = $target;
						}
					}

					if ( $target instanceof UserIdentity ) {
						$formatted = Linker::userLink( $target->getId(), $target->getName() );
						$formatted .= Linker::userToolLinks(
							$target->getId(),
							$target->getName(),
							false,
							Linker::TOOL_LINKS_NOBLOCK
						);
					}
				}
				break;

			case 'ipb_expiry':
				$formatted = htmlspecialchars( $language->formatExpiry(
					$value,
					/* User preference timezone */true,
					'infinity',
					$this->getUser()
				) );
				if ( $this->getAuthority()->isAllowed( 'block' ) ) {
					$links = [];
					if ( $row->ipb_auto ) {
						$links[] = $linkRenderer->makeKnownLink(
							$this->specialPageFactory->getTitleForAlias( 'Unblock' ),
							$msg['unblocklink'],
							[],
							[ 'wpTarget' => "#{$row->ipb_id}" ]
						);
					} else {
						$links[] = $linkRenderer->makeKnownLink(
							$this->specialPageFactory->getTitleForAlias( 'Unblock/' . $row->ipb_address ),
							$msg['unblocklink']
						);
						$links[] = $linkRenderer->makeKnownLink(
							$this->specialPageFactory->getTitleForAlias( 'Block/' . $row->ipb_address ),
							$msg['change-blocklink']
						);
					}
					$formatted .= ' ' . Html::rawElement(
						'span',
						[ 'class' => 'mw-blocklist-actions' ],
						$this->msg( 'parentheses' )->rawParams(
							$language->pipeList( $links ) )->escaped()
					);
				}
				if ( $value !== 'infinity' ) {
					$timestamp = new MWTimestamp( $value );
					$formatted .= '<br />' . $this->msg(
						'ipb-blocklist-duration-left',
						$language->formatDuration(
							(int)$timestamp->getTimestamp( TS_UNIX ) - MWTimestamp::time(),
							// reasonable output
							[
								'minutes',
								'hours',
								'days',
								'years',
							]
						)
					)->escaped();
				}
				break;

			case 'ipb_by':
				$formatted = Linker::userLink( (int)$value, $row->ipb_by_text );
				$formatted .= Linker::userToolLinks( (int)$value, $row->ipb_by_text );
				break;

			case 'ipb_reason':
				$formatted = $this->formattedComments[$this->getResultOffset()];
				break;

			case 'ipb_params':
				$properties = [];

				if ( $row->ipb_sitewide ) {
					$properties[] = htmlspecialchars( $msg['blocklist-editing-sitewide'] );
				}

				if ( !$row->ipb_sitewide && $this->restrictions ) {
					$list = $this->getRestrictionListHTML( $row );
					if ( $list ) {
						$properties[] = htmlspecialchars( $msg['blocklist-editing'] ) . $list;
					}
				}

				if ( $row->ipb_anon_only ) {
					$properties[] = htmlspecialchars( $msg['anononlyblock'] );
				}
				if ( $row->ipb_create_account ) {
					$properties[] = htmlspecialchars( $msg['createaccountblock'] );
				}
				if ( $row->ipb_user && !$row->ipb_enable_autoblock ) {
					$properties[] = htmlspecialchars( $msg['noautoblockblock'] );
				}

				if ( $row->ipb_block_email ) {
					$properties[] = htmlspecialchars( $msg['emailblock'] );
				}

				if ( !$row->ipb_allow_usertalk ) {
					$properties[] = htmlspecialchars( $msg['blocklist-nousertalk'] );
				}

				$formatted = Html::rawElement(
					'ul',
					[],
					implode( '', array_map( static function ( $prop ) {
						return Html::rawElement(
							'li',
							[],
							$prop
						);
					}, $properties ) )
				);
				break;

			default:
				$formatted = "Unable to format $name";
				break;
		}

		return $formatted;
	}

	/**
	 * Get Restriction List HTML
	 *
	 * @param stdClass $row
	 *
	 * @return string
	 */
	private function getRestrictionListHTML( stdClass $row ) {
		$items = [];
		$linkRenderer = $this->getLinkRenderer();

		foreach ( $this->restrictions as $restriction ) {
			if ( $restriction->getBlockId() !== (int)$row->ipb_id ) {
				continue;
			}

			switch ( $restriction->getType() ) {
				case PageRestriction::TYPE:
					'@phan-var PageRestriction $restriction';
					if ( $restriction->getTitle() ) {
						$items[$restriction->getType()][] = Html::rawElement(
							'li',
							[],
							$linkRenderer->makeLink( $restriction->getTitle() )
						);
					}
					break;
				case NamespaceRestriction::TYPE:
					$text = $restriction->getValue() === NS_MAIN
						? $this->msg( 'blanknamespace' )->text()
						: $this->getLanguage()->getFormattedNsText(
							$restriction->getValue()
						);
					if ( $text ) {
						$items[$restriction->getType()][] = Html::rawElement(
							'li',
							[],
							$linkRenderer->makeLink(
								$this->specialPageFactory->getTitleForAlias( 'Allpages' ),
								$text,
								[],
								[
									'namespace' => $restriction->getValue()
								]
							)
						);
					}
					break;
				case ActionRestriction::TYPE:
					$actionName = $this->blockActionInfo->getActionFromId( $restriction->getValue() );
					$enablePartialActionBlocks = $this->getConfig()->get( 'EnablePartialActionBlocks' );
					if ( $actionName && $enablePartialActionBlocks ) {
						$items[$restriction->getType()][] = Html::rawElement(
							'li',
							[],
							$this->msg( 'ipb-action-' .
								$this->blockActionInfo->getActionFromId( $restriction->getValue() ) )->escaped()
						);
					}
					break;
			}
		}

		if ( empty( $items ) ) {
			return '';
		}

		$sets = [];
		foreach ( $items as $key => $value ) {
			$sets[] = Html::rawElement(
				'li',
				[],
				$this->msg( 'blocklist-editing-' . $key ) . Html::rawElement(
					'ul',
					[],
					implode( '', $value )
				)
			);
		}

		return Html::rawElement(
			'ul',
			[],
			implode( '', $sets )
		);
	}

	public function getQueryInfo() {
		$commentQuery = $this->commentStore->getJoin( 'ipb_reason' );

		$info = [
			'tables' => array_merge(
				[ 'ipblocks', 'ipblocks_by_actor' => 'actor' ],
				$commentQuery['tables']
			),
			'fields' => [
				'ipb_id',
				'ipb_address',
				'ipb_user',
				'ipb_by' => 'ipblocks_by_actor.actor_user',
				'ipb_by_text' => 'ipblocks_by_actor.actor_name',
				'ipb_timestamp',
				'ipb_auto',
				'ipb_anon_only',
				'ipb_create_account',
				'ipb_enable_autoblock',
				'ipb_expiry',
				'ipb_range_start',
				'ipb_range_end',
				'ipb_deleted',
				'ipb_block_email',
				'ipb_allow_usertalk',
				'ipb_sitewide',
			] + $commentQuery['fields'],
			'conds' => $this->conds,
			'join_conds' => [
				'ipblocks_by_actor' => [ 'JOIN', 'actor_id=ipb_by_actor' ]
			] + $commentQuery['joins']
		];

		# Filter out any expired blocks
		$db = $this->getDatabase();
		$info['conds'][] = 'ipb_expiry > ' . $db->addQuotes( $db->timestamp() );

		# Is the user allowed to see hidden blocks?
		if ( !$this->getAuthority()->isAllowed( 'hideuser' ) ) {
			$info['conds']['ipb_deleted'] = 0;
		}

		return $info;
	}

	/**
	 * Get total number of autoblocks at any given time
	 *
	 * @return int Total number of unexpired active autoblocks
	 */
	public function getTotalAutoblocks() {
		$dbr = $this->getDatabase();
		return (int)$dbr->selectField( 'ipblocks', 'COUNT(*)',
			[
				'ipb_auto' => '1',
				'ipb_expiry >= ' . $dbr->addQuotes( $dbr->timestamp() ),
			],
			__METHOD__
		);
	}

	protected function getTableClass() {
		return parent::getTableClass() . ' mw-blocklist';
	}

	public function getIndexField() {
		return [ [ 'ipb_timestamp', 'ipb_id' ] ];
	}

	public function getDefaultSort() {
		return '';
	}

	protected function isFieldSortable( $name ) {
		return false;
	}

	/**
	 * Do a LinkBatch query to minimise database load when generating all these links
	 * @param IResultWrapper $result
	 */
	public function preprocessResults( $result ) {
		// Do a link batch query
		$lb = $this->linkBatchFactory->newLinkBatch();
		$lb->setCaller( __METHOD__ );

		$partialBlocks = [];
		foreach ( $result as $row ) {
			$lb->add( NS_USER, $row->ipb_address );
			$lb->add( NS_USER_TALK, $row->ipb_address );

			if ( $row->ipb_by ?? null ) {
				$lb->add( NS_USER, $row->ipb_by_text );
				$lb->add( NS_USER_TALK, $row->ipb_by_text );
			}

			if ( !$row->ipb_sitewide ) {
				$partialBlocks[] = $row->ipb_id;
			}
		}

		if ( $partialBlocks ) {
			// Mutations to the $row object are not persisted. The restrictions will
			// need be stored in a separate store.
			$this->restrictions = $this->blockRestrictionStore->loadByBlockId( $partialBlocks );

			foreach ( $this->restrictions as $restriction ) {
				if ( $restriction->getType() === PageRestriction::TYPE ) {
					'@phan-var PageRestriction $restriction';
					$title = $restriction->getTitle();
					if ( $title ) {
						$lb->addObj( $title );
					}
				}
			}
		}

		$lb->execute();

		// Format comments
		// The keys of formattedComments will be the corresponding offset into $result
		$this->formattedComments = $this->rowCommentFormatter->formatRows( $result, 'ipb_reason' );
	}

}
