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

	protected $target, $options;

	function __construct() {
		parent::__construct( 'BlockList' );
	}

	/**
	 * Main execution point
	 *
	 * @param $par String title fragment
	 */
	public function execute( $par ) {
		global $wgOut, $wgRequest;

		$this->setHeaders();
		$this->outputHeader();
		$wgOut->setPageTitle( wfMsg( 'ipblocklist' ) );
		$wgOut->addModuleStyles( 'mediawiki.special' );

		$par = $wgRequest->getVal( 'ip', $par );
		$this->target = trim( $wgRequest->getVal( 'wpTarget', $par ) );

		$this->options = $wgRequest->getArray( 'wpOptions', array() );

		$action = $wgRequest->getText( 'action' );

		if( $action == 'unblock' || $action == 'submit' && $wgRequest->wasPosted() ) {
			# B/C @since 1.18: Unblock interface is now at Special:Unblock
			$title = SpecialPage::getTitleFor( 'Unblock', $this->target );
			$wgOut->redirect( $title->getFullUrl() );
			return;
		}

		# Just show the block list
		$fields = array(
			'Target' => array(
				'type' => 'text',
				'label-message' => 'ipadressorusername',
				'tabindex' => '1',
				'size' => '45',
			),
			'Options' => array(
				'type' => 'multiselect',
				'options' => array(
					wfMsg( 'blocklist-userblocks' ) => 'userblocks',
					wfMsg( 'blocklist-tempblocks' ) => 'tempblocks',
					wfMsg( 'blocklist-addressblocks' ) => 'addressblocks',
				),
				'cssclass' => 'mw-htmlform-multiselect-flatlist',
			),
		);
		$form = new HTMLForm( $fields );
		$form->setTitle( $this->getTitle() );
		$form->setMethod( 'get' );
		$form->setWrapperLegend( wfMsg( 'ipblocklist-legend' ) );
		$form->setSubmitText( wfMsg( 'ipblocklist-submit' ) );
		$form->prepareForm();

		$form->displayForm( '' );
		$this->showList();
	}

	function showList() {
		global $wgOut, $wgUser;

		# Purge expired entries on one in every 10 queries
		if ( !mt_rand( 0, 10 ) ) {
			Block::purgeExpired();
		}

		$conds = array();
		# Is the user allowed to see hidden blocks?
		if ( !$wgUser->isAllowed( 'hideuser' ) ){
			$conds['ipb_deleted'] = 0;
		}

		if ( $this->target !== '' ){
			list( $target, $type ) = Block::parseTarget( $this->target );

			switch( $type ){
				case Block::TYPE_ID:
					$conds['ipb_id'] = $target;
					break;

				case Block::TYPE_IP:
				case Block::TYPE_RANGE:
					list( $start, $end ) = IP::parseRange( $target );
					$dbr = wfGetDB( DB_SLAVE );
					$conds[] = $dbr->makeList(
						array(
							'ipb_address' => $target,
							Block::getRangeCond( $start, $end )
						),
						LIST_OR
					);
					$conds['ipb_auto'] = 0;
					break;

				case Block::TYPE_USER:
					$conds['ipb_address'] = (string)$this->target;
					$conds['ipb_auto'] = 0;
					break;
			}
		}

		# Apply filters
		if( in_array( 'userblocks', $this->options ) ) {
			$conds['ipb_user'] = 0;
		}
		if( in_array( 'tempblocks', $this->options ) ) {
			$conds['ipb_expiry'] = 'infinity';
		}
		if( in_array( 'addressblocks', $this->options ) ) {
			$conds[] = "ipb_user != 0 OR ipb_range_end > ipb_range_start";
		}

		# Check for other blocks, i.e. global/tor blocks
		$otherBlockLink = array();
		wfRunHooks( 'OtherBlockLogLink', array( &$otherBlockLink, $this->target ) );

		# Show additional header for the local block only when other blocks exists.
		# Not necessary in a standard installation without such extensions enabled
		if( count( $otherBlockLink ) ) {
			$wgOut->addHTML(
				Html::rawElement( 'h2', array(), wfMsg( 'ipblocklist-localblock' ) ) . "\n"
			);
		}

		$pager = new BlockListPager( $this, $conds );
		if ( $pager->getNumRows() ) {
			$wgOut->addHTML(
				$pager->getNavigationBar() .
				$pager->getBody().
				$pager->getNavigationBar()
			);

		} elseif ( $this->target ) {
			$wgOut->addWikiMsg( 'ipblocklist-no-results' );

		} else {
			$wgOut->addWikiMsg( 'ipblocklist-empty' );
		}

		if( count( $otherBlockLink ) ) {
			$wgOut->addHTML(
				Html::rawElement(
					'h2',
					array(),
					wfMsgExt(
						'ipblocklist-otherblocks',
						'parseinline',
						count( $otherBlockLink )
					)
				) . "\n"
			);
			$list = '';
			foreach( $otherBlockLink as $link ) {
				$list .= Html::rawElement( 'li', array(), $link ) . "\n";
			}
			$wgOut->addHTML( Html::rawElement( 'ul', array( 'class' => 'mw-ipblocklist-otherblocks' ), $list ) . "\n" );
		}
	}
}

class BlockListPager extends TablePager {
	protected $conds;
	protected $page;

	function __construct( $page, $conds ) {
		$this->page = $page;
		$this->conds = $conds;
		$this->mDefaultDirection = true;
		parent::__construct();
	}

	function getFieldNames() {
		static $headers = null;

		if ( $headers == array() ) {
			$headers = array(
				'ipb_timestamp' => 'blocklist-timestamp',
				'ipb_target' => 'blocklist-target',
				'ipb_expiry' => 'blocklist-expiry',
				'ipb_by' => 'blocklist-by',
				'ipb_params' => 'blocklist-params',
				'ipb_reason' => 'blocklist-reason',
			);
			$headers = array_map( 'wfMsg', $headers );
		}

		return $headers;
	}

	function formatValue( $name, $value ) {
		global $wgLang, $wgUser;

		static $sk, $msg;
		if ( empty( $sk ) ) {
			$sk = $wgUser->getSkin();
			$msg = array(
				'anononlyblock',
				'createaccountblock',
				'noautoblockblock',
				'emailblock',
				'blocklist-nousertalk',
				'unblocklink',
				'change-blocklink',
				'infiniteblock',
			);
			$msg = array_combine( $msg, array_map( 'wfMessage', $msg ) );
		}

		$row = $this->mCurrentRow;
		$formatted = '';

		switch( $name ) {
			case 'ipb_timestamp':
				$formatted = $wgLang->timeanddate( $value );
				break;

			case 'ipb_target':
				if( $row->ipb_auto ){
					$formatted = wfMessage( 'autoblockid', $row->ipb_id );
				} else {
					list( $target, $type ) = Block::parseTarget( $row->ipb_address );
					switch( $type ){
						case Block::TYPE_USER:
						case Block::TYPE_IP:
							$formatted = $sk->userLink( $target->getId(), $target );
							$formatted .= $sk->userToolLinks(
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
				$formatted = $wgLang->formatExpiry( $value );
				if( $wgUser->isAllowed( 'block' ) ){
					if( $row->ipb_auto ){
						$links[] = $sk->linkKnown(
							SpecialPage::getTitleFor( 'Unblock' ),
							$msg['unblocklink'],
							array(),
							array( 'wpTarget' => "#{$row->ipb_id}" )
						);
					} else {
						$links[] = $sk->linkKnown(
							SpecialPage::getTitleFor( 'Unblock', $row->ipb_address ),
							$msg['unblocklink']
						);
						$links[] = $sk->linkKnown(
							SpecialPage::getTitleFor( 'Block', $row->ipb_address ),
							$msg['change-blocklink']
						);
					}
					$formatted .= ' ' . Html::rawElement(
						'span',
						array( 'class' => 'mw-blocklist-actions' ),
						wfMsg( 'parentheses', $wgLang->pipeList( $links ) )
					);
				}
				break;

			case 'ipb_by':
				$user = User::newFromId( $value );
				if( $user instanceof User ){
					$formatted = $sk->userLink( $user->getId(), $user->getName() );
					$formatted .= $sk->userToolLinks( $user->getId(), $user->getName() );
				}
				break;

			case 'ipb_reason':
				$formatted = $sk->commentBlock( $value );
				break;

			case 'ipb_params':
				$properties = array();
				if ( $row->ipb_anon_only ) {
					$properties[] = $msg['anononlyblock'];
				}
				if ( $row->ipb_create_account ) {
					$properties[] = $msg['createaccountblock'];
				}
				if ( !$row->ipb_enable_autoblock ) {
					$properties[] = $msg['noautoblockblock'];
				}

				if ( $row->ipb_block_email ) {
					$properties[] = $msg['emailblock'];
				}

				if ( !$row->ipb_allow_usertalk ) {
					$properties[] = $msg['blocklist-nousertalk'];
				}

				$formatted = $wgLang->commaList( $properties );
				break;

			default:
				$formatted = "Unable to format $name";
				break;
		}

		return $formatted;
	}

	function getQueryInfo() {
		$info = array(
			'tables' => array( 'ipblocks' ),
			'fields' => array(
				'ipb_id',
				'ipb_address',
				'ipb_by',
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
			),
			'conds' => $this->conds,
		);

		global $wgUser;
		# Is the user allowed to see hidden blocks?
		if ( !$wgUser->isAllowed( 'hideuser' ) ){
			$conds['ipb_deleted'] = 0;
		}

		return $info;
	}

	public function getTableClass(){
		return 'TablePager mw-blocklist';
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

	function getTitle() {
		return $this->page->getTitle();
	}
}
