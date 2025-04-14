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

namespace MediaWiki\Pager;

use MediaWiki\Block\BlockActionInfo;
use MediaWiki\Block\BlockRestrictionStore;
use MediaWiki\Block\BlockTargetFactory;
use MediaWiki\Block\BlockTargetWithUserPage;
use MediaWiki\Block\HideUserUtils;
use MediaWiki\Block\RangeBlockTarget;
use MediaWiki\Block\Restriction\ActionRestriction;
use MediaWiki\Block\Restriction\NamespaceRestriction;
use MediaWiki\Block\Restriction\PageRestriction;
use MediaWiki\Block\Restriction\Restriction;
use MediaWiki\Cache\LinkBatchFactory;
use MediaWiki\CommentFormatter\RowCommentFormatter;
use MediaWiki\CommentStore\CommentStore;
use MediaWiki\Context\IContextSource;
use MediaWiki\Html\Html;
use MediaWiki\Linker\Linker;
use MediaWiki\Linker\LinkRenderer;
use MediaWiki\MainConfigNames;
use MediaWiki\SpecialPage\SpecialPageFactory;
use MediaWiki\User\UserIdentityValue;
use MediaWiki\Utils\MWTimestamp;
use stdClass;
use Wikimedia\Rdbms\IConnectionProvider;
use Wikimedia\Rdbms\IResultWrapper;

/**
 * @ingroup Pager
 */
class BlockListPager extends TablePager {

	protected array $conds;

	/**
	 * Array of restrictions.
	 *
	 * @var Restriction[]
	 */
	protected array $restrictions = [];

	private BlockActionInfo $blockActionInfo;
	private BlockRestrictionStore $blockRestrictionStore;
	private BlockTargetFactory $blockTargetFactory;
	private HideUserUtils $hideUserUtils;
	private CommentStore $commentStore;
	private LinkBatchFactory $linkBatchFactory;
	private RowCommentFormatter $rowCommentFormatter;
	private SpecialPageFactory $specialPageFactory;

	/** @var string[] */
	private array $formattedComments = [];

	/** @var string[] Cache of messages to avoid them being recreated for every row of the pager. */
	private array $messages = [];

	public function __construct(
		IContextSource $context,
		BlockActionInfo $blockActionInfo,
		BlockRestrictionStore $blockRestrictionStore,
		BlockTargetFactory $blockTargetFactory,
		HideUserUtils $hideUserUtils,
		CommentStore $commentStore,
		LinkBatchFactory $linkBatchFactory,
		LinkRenderer $linkRenderer,
		IConnectionProvider $dbProvider,
		RowCommentFormatter $rowCommentFormatter,
		SpecialPageFactory $specialPageFactory,
		array $conds
	) {
		// Set database before parent constructor to avoid setting it there
		$this->mDb = $dbProvider->getReplicaDatabase();

		parent::__construct( $context, $linkRenderer );

		$this->blockActionInfo = $blockActionInfo;
		$this->blockRestrictionStore = $blockRestrictionStore;
		$this->blockTargetFactory = $blockTargetFactory;
		$this->hideUserUtils = $hideUserUtils;
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
				'bl_timestamp' => 'blocklist-timestamp',
				'target' => 'blocklist-target',
				'bl_expiry' => 'blocklist-expiry',
				'bl_by' => 'blocklist-by',
				'params' => 'blocklist-params',
				'bl_reason' => 'blocklist-reason',
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
	 */
	public function formatValue( $name, $value ) {
		if ( $this->messages === [] ) {
			$keys = [
				'anononlyblock',
				'blanknamespace',
				'createaccountblock',
				'noautoblockblock',
				'emailblock',
				'blocklist-nousertalk',
				'unblocklink',
				'remove-blocklink',
				'change-blocklink',
				'blocklist-editing',
				'blocklist-editing-sitewide',
				'blocklist-hidden-param',
				'blocklist-hidden-placeholder',
			];

			foreach ( $keys as $key ) {
				$this->messages[$key] = $this->msg( $key )->text();
			}
		}

		/** @var stdClass $row */
		$row = $this->mCurrentRow;

		$language = $this->getLanguage();

		$linkRenderer = $this->getLinkRenderer();

		switch ( $name ) {
			case 'bl_timestamp':
				// Link the timestamp to the block ID. This allows users without permissions to change blocks
				// to be able to generate a link to a specific block.
				$formatted = $linkRenderer->makeKnownLink(
					$this->specialPageFactory->getTitleForAlias( 'BlockList' ),
					$language->userTimeAndDate( $value, $this->getUser() ),
					[],
					[ 'wpTarget' => "#{$row->bl_id}" ],
				);
				break;

			case 'target':
				$formatted = $this->formatTarget( $row );
				break;

			case 'bl_expiry':
				$formatted = htmlspecialchars( $language->formatExpiry(
					$value,
					/* User preference timezone */true,
					'infinity',
					$this->getUser()
				) );
				if ( $this->getAuthority()->isAllowed( 'block' ) ) {
					$links = $this->getBlockChangeLinks( $row );
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
						$language->formatDurationBetweenTimestamps(
							(int)$timestamp->getTimestamp( TS_UNIX ),
							MWTimestamp::time(),
							4
						)
					)->escaped();
				}
				break;

			case 'bl_by':
				$formatted = Linker::userLink( (int)$value, $row->bl_by_text );
				$formatted .= Linker::userToolLinks( (int)$value, $row->bl_by_text );
				break;

			case 'bl_reason':
				$formatted = $this->formattedComments[$this->getResultOffset()];
				break;

			case 'params':
				$properties = [];

				if ( $row->bl_deleted ) {
					$properties[] = htmlspecialchars( $this->messages['blocklist-hidden-param' ] );
				}
				if ( $row->bl_sitewide ) {
					$properties[] = htmlspecialchars( $this->messages['blocklist-editing-sitewide'] );
				}

				if ( !$row->bl_sitewide && $this->restrictions ) {
					$list = $this->getRestrictionListHTML( $row );
					if ( $list ) {
						$properties[] = htmlspecialchars( $this->messages['blocklist-editing'] ) . $list;
					}
				}

				if ( $row->bl_anon_only ) {
					$properties[] = htmlspecialchars( $this->messages['anononlyblock'] );
				}
				if ( $row->bl_create_account ) {
					$properties[] = htmlspecialchars( $this->messages['createaccountblock'] );
				}
				if ( $row->bt_user && !$row->bl_enable_autoblock ) {
					$properties[] = htmlspecialchars( $this->messages['noautoblockblock'] );
				}

				if ( $row->bl_block_email ) {
					$properties[] = htmlspecialchars( $this->messages['emailblock'] );
				}

				if ( !$row->bl_allow_usertalk ) {
					$properties[] = htmlspecialchars( $this->messages['blocklist-nousertalk'] );
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
	 * Format the target field
	 * @param stdClass $row
	 * @return string
	 */
	private function formatTarget( $row ) {
		if ( $row->bt_auto ) {
			return $this->msg( 'autoblockid', $row->bl_id )->parse();
		}

		$target = $this->blockTargetFactory->newFromRowRedacted( $row );

		if ( $target instanceof RangeBlockTarget ) {
			$userId = 0;
			$userName = $target->toString();
		} elseif ( ( $row->hu_deleted ?? null )
			&& !$this->getAuthority()->isAllowed( 'hideuser' )
		) {
			return Html::element(
				'span',
				[ 'class' => 'mw-blocklist-hidden' ],
				$this->messages['blocklist-hidden-placeholder']
			);
		} elseif ( $target instanceof BlockTargetWithUserPage ) {
			$user = $target->getUserIdentity();
			$userId = $user->getId();
			$userName = $user->getName();
		} else {
			return $this->msg( 'empty-username' )->escaped();
		}
		return Linker::userLink( $userId, $userName ) .
			Linker::userToolLinks(
				$userId,
				$userName,
				false,
				Linker::TOOL_LINKS_NOBLOCK
			);
	}

	/**
	 * Get unblock and change-block links.
	 *
	 * @param stdClass $row Block data.
	 * @return string[] Array of HTML links.
	 */
	private function getBlockChangeLinks( $row ): array {
		$linkRenderer = $this->getLinkRenderer();
		$links = [];
		$target = $this->blockTargetFactory->newFromRowRedacted( $row )->toString();
		if ( $this->getConfig()->get( MainConfigNames::UseCodexSpecialBlock ) ) {
			$query = [ 'id' => $row->bl_id ];
			if ( $row->bt_auto ) {
				$links[] = $linkRenderer->makeKnownLink(
					$this->specialPageFactory->getTitleForAlias( 'Unblock' ),
					$this->messages['remove-blocklink'],
					[],
					[ 'wpTarget' => "#{$row->bl_id}" ]
				);
			} else {
				$specialBlock = $this->specialPageFactory->getTitleForAlias( "Block/$target" );
				$links[] = $linkRenderer->makeKnownLink(
					$specialBlock,
					$this->messages['remove-blocklink'],
					[],
					$query + [ 'remove' => '1' ]
				);
				$links[] = $linkRenderer->makeKnownLink(
					$specialBlock,
					$this->messages['change-blocklink'],
					[],
					$query
				);
			}
		} else {
			if ( $row->bt_auto ) {
				$links[] = $linkRenderer->makeKnownLink(
					$this->specialPageFactory->getTitleForAlias( 'Unblock' ),
					$this->messages['unblocklink'],
					[],
					[ 'wpTarget' => "#{$row->bl_id}" ]
				);
			} else {
				$links[] = $linkRenderer->makeKnownLink(
					$this->specialPageFactory->getTitleForAlias( "Unblock/$target" ),
					$this->messages['unblocklink']
				);
				$links[] = $linkRenderer->makeKnownLink(
					$this->specialPageFactory->getTitleForAlias( "Block/$target" ),
					$this->messages['change-blocklink']
				);
			}
		}
		return $links;
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
			if ( $restriction->getBlockId() !== (int)$row->bl_id ) {
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
						? $this->messages['blanknamespace']
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
					$enablePartialActionBlocks =
						$this->getConfig()->get( MainConfigNames::EnablePartialActionBlocks );
					if ( $actionName && $enablePartialActionBlocks ) {
						$items[$restriction->getType()][] = Html::rawElement(
							'li',
							[],
							// The following messages may be used here:
							// * ipb-action-create
							// * ipb-action-move
							// * ipb-action-upload
							$this->msg( 'ipb-action-' .
								$this->blockActionInfo->getActionFromId( $restriction->getValue() ) )->escaped()
						);
					}
					break;
			}
		}

		if ( !$items ) {
			return '';
		}

		$sets = [];
		foreach ( $items as $key => $value ) {
			$sets[] = Html::rawElement(
				'li',
				[],
				// The following messages may be used here:
				// * blocklist-editing-sitewide
				// * blocklist-editing-page
				// * blocklist-editing-ns
				// * blocklist-editing-action
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
		$db = $this->getDatabase();
		$commentQuery = $this->commentStore->getJoin( 'bl_reason' );
		$info = [
			'tables' => array_merge(
				[
					'block',
					'block_by_actor' => 'actor',
					'block_target',
				],
				$commentQuery['tables']
			),
			'fields' => [
				// The target fields should be those accepted by BlockTargetFactory::newFromRowRedacted()
				'bt_address',
				'bt_user_text',
				'bt_user',
				'bt_auto',
				'bt_range_start',
				'bt_range_end',
				// Block fields and aliases
				'bl_id',
				'bl_by' => 'block_by_actor.actor_user',
				'bl_by_text' => 'block_by_actor.actor_name',
				'bl_timestamp',
				'bl_anon_only',
				'bl_create_account',
				'bl_enable_autoblock',
				'bl_expiry',
				'bl_deleted',
				'bl_block_email',
				'bl_allow_usertalk',
				'bl_sitewide',
			] + $commentQuery['fields'],
			'conds' => $this->conds,
			'join_conds' => [
				'block_by_actor' => [ 'JOIN', 'actor_id=bl_by_actor' ],
				'block_target' => [ 'JOIN', 'bt_id=bl_target' ],
			] + $commentQuery['joins']
		];

		# Filter out any expired blocks
		$info['conds'][] = $db->expr( 'bl_expiry', '>', $db->timestamp() );

		# Filter out blocks with the deleted option if the user doesn't
		# have permission to see hidden users
		# TODO: consider removing this -- we could just redact them instead.
		# The mere fact that an admin has deleted a user does not need to
		# be private and could be included in block lists and logs for
		# transparency purposes. Previously, filtering out deleted blocks
		# was a convenient way to avoid showing the target name.
		if ( $this->getAuthority()->isAllowed( 'hideuser' ) ) {
			$info['fields']['hu_deleted'] = $this->hideUserUtils->getExpression(
				$db,
				'block_target.bt_user',
				HideUserUtils::HIDDEN_USERS
			);
		} else {
			$info['fields']['hu_deleted'] = 0;
			$info['conds'][] = $this->hideUserUtils->getExpression(
				$db,
				'block_target.bt_user',
				HideUserUtils::SHOWN_USERS
			);
		}
		return $info;
	}

	protected function getTableClass() {
		return parent::getTableClass() . ' mw-blocklist';
	}

	public function getIndexField() {
		return [ [ 'bl_timestamp', 'bl_id' ] ];
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
			$target = $row->bt_address ?? $row->bt_user_text;
			if ( $target !== null ) {
				$lb->addUser( new UserIdentityValue( (int)$row->bt_user, $target ) );
			}

			if ( isset( $row->bl_by_text ) ) {
				$lb->add( NS_USER, $row->bl_by_text );
				$lb->add( NS_USER_TALK, $row->bl_by_text );
			}

			if ( !$row->bl_sitewide ) {
				$partialBlocks[] = (int)$row->bl_id;
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
		$this->formattedComments = $this->rowCommentFormatter->formatRows( $result, 'bl_reason' );
	}

}

/**
 * Retain the old class name for backwards compatibility.
 * @deprecated since 1.41
 */
class_alias( BlockListPager::class, 'BlockListPager' );
