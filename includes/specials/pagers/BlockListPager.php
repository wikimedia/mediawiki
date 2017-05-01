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
use MediaWiki\MediaWikiServices;
use Wikimedia\Rdbms\ResultWrapper;

class BlockListPager extends TablePager {

	protected $conds;
	protected $page;

	/**
	 * @param SpecialPage $page
	 * @param array $conds
	 */
	function __construct( $page, $conds ) {
		$this->page = $page;
		$this->conds = $conds;
		$this->mDefaultDirection = IndexPager::DIR_DESCENDING;
		parent::__construct( $page->getContext() );
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
			];

			foreach ( $keys as $key ) {
				$msg[$key] = $this->msg( $key )->text();
			}
		}

		/** @var $row object */
		$row = $this->mCurrentRow;

		$language = $this->getLanguage();

		$formatted = '';

		$linkRenderer = MediaWikiServices::getInstance()->getLinkRenderer();

		switch ( $name ) {
			case 'ipb_timestamp':
				$formatted = htmlspecialchars( $language->userTimeAndDate( $value, $this->getUser() ) );
				break;

			case 'ipb_target':
				if ( $row->ipb_auto ) {
					$formatted = $this->msg( 'autoblockid', $row->ipb_id )->parse();
				} else {
					list( $target, $type ) = Block::parseTarget( $row->ipb_address );
					switch ( $type ) {
						case Block::TYPE_USER:
						case Block::TYPE_IP:
							$formatted = Linker::userLink( $target->getId(), $target );
							$formatted .= Linker::userToolLinks(
								$target->getId(),
								$target,
								false,
								Linker::TOOL_LINKS_NOBLOCK
							);
							break;
						case Block::TYPE_RANGE:
							$formatted = htmlspecialchars( $target );
					}
				}
				break;

			case 'ipb_expiry':
				$formatted = htmlspecialchars( $language->formatExpiry(
					$value,
					/* User preference timezone */true
				) );
				if ( $this->getUser()->isAllowed( 'block' ) ) {
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
							$timestamp->getTimestamp() - time(),
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
				$formatted = Linker::formatComment( $value );
				break;

			case 'ipb_params':
				$properties = [];
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

				$formatted = $language->commaList( $properties );
				break;

			default:
				$formatted = "Unable to format $name";
				break;
		}

		return $formatted;
	}

	function getQueryInfo() {
		$info = [
			'tables' => [ 'ipblocks', 'user' ],
			'fields' => [
				'ipb_id',
				'ipb_address',
				'ipb_user',
				'ipb_by',
				'ipb_by_text',
				'by_user_name' => 'user_name',
				'ipb_reason',
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
			],
			'conds' => $this->conds,
			'join_conds' => [ 'user' => [ 'LEFT JOIN', 'user_id = ipb_by' ] ]
		];

		# Filter out any expired blocks
		$db = $this->getDatabase();
		$info['conds'][] = 'ipb_expiry > ' . $db->addQuotes( $db->timestamp() );

		# Is the user allowed to see hidden blocks?
		if ( !$this->getUser()->isAllowed( 'hideuser' ) ) {
			$info['conds']['ipb_deleted'] = 0;
		}

		return $info;
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
	 * @param ResultWrapper $result
	 */
	function preprocessResults( $result ) {
		# Do a link batch query
		$lb = new LinkBatch;
		$lb->setCaller( __METHOD__ );

		foreach ( $result as $row ) {
			$lb->add( NS_USER, $row->ipb_address );
			$lb->add( NS_USER_TALK, $row->ipb_address );

			if ( isset( $row->by_user_name ) ) {
				$lb->add( NS_USER, $row->by_user_name );
				$lb->add( NS_USER_TALK, $row->by_user_name );
			}
		}

		$lb->execute();
	}

}
