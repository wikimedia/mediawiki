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

/**
 * @ingroup Pager
 */
use MediaWiki\Block\DatabaseBlock;
use MediaWiki\Block\Restriction\Restriction;
use MediaWiki\Block\Restriction\PageRestriction;
use MediaWiki\Block\Restriction\NamespaceRestriction;
use MediaWiki\MediaWikiServices;
use Wikimedia\Rdbms\IResultWrapper;

class BlockListPager extends TablePager {

	protected $conds;

	/**
	 * Array of restrictions.
	 *
	 * @var Restriction[]
	 */
	protected $restrictions = [];

	/**
	 * @param SpecialPage $page
	 * @param array $conds
	 */
	public function __construct( $page, $conds ) {
		parent::__construct( $page->getContext(), $page->getLinkRenderer() );
		$this->conds = $conds;
		$this->mDefaultDirection = IndexPager::DIR_DESCENDING;
	}

	function getFieldNames() {
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
	 * @param string $value
	 * @return string
	 * @suppress PhanTypeArraySuspicious
	 */
	function formatValue( $name, $value ) {
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

		/** @var object $row */
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
					list( $target, $type ) = DatabaseBlock::parseTarget( $row->ipb_address );
					switch ( $type ) {
						case DatabaseBlock::TYPE_USER:
						case DatabaseBlock::TYPE_IP:
							$formatted = Linker::userLink( $target->getId(), $target );
							$formatted .= Linker::userToolLinks(
								$target->getId(),
								$target,
								false,
								Linker::TOOL_LINKS_NOBLOCK
							);
							break;
						case DatabaseBlock::TYPE_RANGE:
							$formatted = htmlspecialchars( $target );
					}
				}
				break;

			case 'ipb_expiry':
				$formatted = htmlspecialchars( $language->formatExpiry(
					$value,
					/* User preference timezone */true
				) );
				if ( MediaWikiServices::getInstance()
						->getPermissionManager()
						->userHasRight( $this->getUser(), 'block' )
				) {
					$links = [];
					if ( $row->ipb_auto ) {
						$links[] = $linkRenderer->makeKnownLink(
							SpecialPage::getTitleFor( 'Unblock' ),
							$msg['unblocklink'],
							[],
							[ 'wpTarget' => "#{$row->ipb_id}" ]
						);
					} else {
						$links[] = $linkRenderer->makeKnownLink(
							SpecialPage::getTitleFor( 'Unblock', $row->ipb_address ),
							$msg['unblocklink']
						);
						$links[] = $linkRenderer->makeKnownLink(
							SpecialPage::getTitleFor( 'Block', $row->ipb_address ),
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
							$timestamp->getTimestamp() - MWTimestamp::time(),
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
				if ( isset( $row->by_user_name ) ) {
					$formatted = Linker::userLink( $value, $row->by_user_name );
					$formatted .= Linker::userToolLinks( $value, $row->by_user_name );
				} else {
					$formatted = htmlspecialchars( $row->ipb_by_text ); // foreign user?
				}
				break;

			case 'ipb_reason':
				$value = CommentStore::getStore()->getComment( 'ipb_reason', $row )->text;
				$formatted = Linker::formatComment( $value );
				break;

			case 'ipb_params':
				$properties = [];

				if ( $this->getConfig()->get( 'EnablePartialBlocks' ) && $row->ipb_sitewide ) {
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
						implode( '', array_map( function ( $prop ) {
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
					$items[$restriction->getType()][] = Html::rawElement(
						'li',
						[],
						$linkRenderer->makeLink(
							SpecialPage::getTitleValueFor( 'Allpages' ),
							$text,
							[],
							[
								'namespace' => $restriction->getValue()
							]
						)
					);
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

	function getQueryInfo() {
		$commentQuery = CommentStore::getStore()->getJoin( 'ipb_reason' );
		$actorQuery = ActorMigration::newMigration()->getJoin( 'ipb_by' );

		$info = [
			'tables' => array_merge(
				[ 'ipblocks' ], $commentQuery['tables'], $actorQuery['tables'], [ 'user' ]
			),
			'fields' => [
				'ipb_id',
				'ipb_address',
				'ipb_user',
				'by_user_name' => 'user_name',
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
			] + $commentQuery['fields'] + $actorQuery['fields'],
			'conds' => $this->conds,
			'join_conds' => [
				'user' => [ 'LEFT JOIN', 'user_id = ' . $actorQuery['fields']['ipb_by'] ]
			] + $commentQuery['joins'] + $actorQuery['joins']
		];

		# Filter out any expired blocks
		$db = $this->getDatabase();
		$info['conds'][] = 'ipb_expiry > ' . $db->addQuotes( $db->timestamp() );

		# Is the user allowed to see hidden blocks?
		if ( !MediaWikiServices::getInstance()
				->getPermissionManager()
				->userHasRight( $this->getUser(), 'hideuser' )
		) {
			$info['conds']['ipb_deleted'] = 0;
		}

		return $info;
	}

	/**
	 * Get total number of autoblocks at any given time
	 *
	 * @return int Total number of unexpired active autoblocks
	 */
	function getTotalAutoblocks() {
		$dbr = $this->getDatabase();
		$res = $dbr->selectField( 'ipblocks',
			'COUNT(*)',
			[
				'ipb_auto' => '1',
				'ipb_expiry >= ' . $dbr->addQuotes( $dbr->timestamp() ),
			]
		);
		if ( $res ) {
			return $res;
		}
		return 0; // We found nothing
	}

	protected function getTableClass() {
		return parent::getTableClass() . ' mw-blocklist';
	}

	function getIndexField() {
		return 'ipb_timestamp';
	}

	function getDefaultSort() {
		return 'ipb_timestamp';
	}

	function isFieldSortable( $name ) {
		return false;
	}

	/**
	 * Do a LinkBatch query to minimise database load when generating all these links
	 * @param IResultWrapper $result
	 */
	function preprocessResults( $result ) {
		# Do a link batch query
		$lb = new LinkBatch;
		$lb->setCaller( __METHOD__ );

		$partialBlocks = [];
		foreach ( $result as $row ) {
			$lb->add( NS_USER, $row->ipb_address );
			$lb->add( NS_USER_TALK, $row->ipb_address );

			if ( isset( $row->by_user_name ) ) {
				$lb->add( NS_USER, $row->by_user_name );
				$lb->add( NS_USER_TALK, $row->by_user_name );
			}

			if ( !$row->ipb_sitewide ) {
				$partialBlocks[] = $row->ipb_id;
			}
		}

		if ( $partialBlocks ) {
			// Mutations to the $row object are not persisted. The restrictions will
			// need be stored in a separate store.
			$blockRestrictionStore = MediaWikiServices::getInstance()->getBlockRestrictionStore();
			$this->restrictions = $blockRestrictionStore->loadByBlockId( $partialBlocks );
		}

		$lb->execute();
	}

}
