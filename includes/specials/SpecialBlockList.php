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
	protected $target;

	protected $options;

	function __construct() {
		parent::__construct( 'BlockList' );
	}

	/**
	 * Main execution point
	 *
	 * @param string $par Title fragment
	 */
	public function execute( $par ) {
		$this->setHeaders();
		$this->outputHeader();
		$out = $this->getOutput();
		$lang = $this->getLanguage();
		$out->setPageTitle( $this->msg( 'ipblocklist' ) );
		$out->addModuleStyles( 'mediawiki.special' );
		$out->addModules( 'mediawiki.userSuggest' );

		$request = $this->getRequest();
		$par = $request->getVal( 'ip', $par );
		$this->target = trim( $request->getVal( 'wpTarget', $par ) );

		$this->options = $request->getArray( 'wpOptions', array() );

		$action = $request->getText( 'action' );

		if ( $action == 'unblock' || $action == 'submit' && $request->wasPosted() ) {
			# B/C @since 1.18: Unblock interface is now at Special:Unblock
			$title = SpecialPage::getTitleFor( 'Unblock', $this->target );
			$out->redirect( $title->getFullURL() );

			return;
		}

		# Just show the block list
		$fields = array(
			'Target' => array(
				'type' => 'text',
				'label-message' => 'ipaddressorusername',
				'tabindex' => '1',
				'size' => '45',
				'default' => $this->target,
				'cssclass' => 'mw-autocomplete-user', // used by mediawiki.userSuggest
			),
			'Options' => array(
				'type' => 'multiselect',
				'options' => array(
					$this->msg( 'blocklist-userblocks' )->text() => 'userblocks',
					$this->msg( 'blocklist-tempblocks' )->text() => 'tempblocks',
					$this->msg( 'blocklist-addressblocks' )->text() => 'addressblocks',
					$this->msg( 'blocklist-rangeblocks' )->text() => 'rangeblocks',
				),
				'flatlist' => true,
			),
			'Limit' => array(
				'type' => 'limitselect',
				'label-message' => 'table_pager_limit_label',
				'options' => array(
					$lang->formatNum( 20 ) => 20,
					$lang->formatNum( 50 ) => 50,
					$lang->formatNum( 100 ) => 100,
					$lang->formatNum( 250 ) => 250,
					$lang->formatNum( 500 ) => 500,
				),
				'name' => 'limit',
				'default' => 50,
			),
		);
		$context = new DerivativeContext( $this->getContext() );
		$context->setTitle( $this->getPageTitle() ); // Remove subpage
		$form = new HTMLForm( $fields, $context );
		$form->setMethod( 'get' );
		$form->setWrapperLegendMsg( 'ipblocklist-legend' );
		$form->setSubmitTextMsg( 'ipblocklist-submit' );
		$form->setSubmitProgressive();
		$form->prepareForm();

		$form->displayForm( '' );
		$this->showList();
	}

	function showList() {
		$conds = array();
		# Is the user allowed to see hidden blocks?
		if ( !$this->getUser()->isAllowed( 'hideuser' ) ) {
			$conds['ipb_deleted'] = 0;
		}

		if ( $this->target !== '' ) {
			list( $target, $type ) = Block::parseTarget( $this->target );

			switch ( $type ) {
				case Block::TYPE_ID:
				case Block::TYPE_AUTO:
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
					$conds['ipb_address'] = $target->getName();
					$conds['ipb_auto'] = 0;
					break;
			}
		}

		# Apply filters
		if ( in_array( 'userblocks', $this->options ) ) {
			$conds['ipb_user'] = 0;
		}
		if ( in_array( 'tempblocks', $this->options ) ) {
			$conds['ipb_expiry'] = 'infinity';
		}
		if ( in_array( 'addressblocks', $this->options ) ) {
			$conds[] = "ipb_user != 0 OR ipb_range_end > ipb_range_start";
		}
		if ( in_array( 'rangeblocks', $this->options ) ) {
			$conds[] = "ipb_range_end = ipb_range_start";
		}

		# Check for other blocks, i.e. global/tor blocks
		$otherBlockLink = array();
		Hooks::run( 'OtherBlockLogLink', array( &$otherBlockLink, $this->target ) );

		$out = $this->getOutput();

		# Show additional header for the local block only when other blocks exists.
		# Not necessary in a standard installation without such extensions enabled
		if ( count( $otherBlockLink ) ) {
			$out->addHTML(
				Html::element( 'h2', array(), $this->msg( 'ipblocklist-localblock' )->text() ) . "\n"
			);
		}

		$pager = new BlockListPager( $this, $conds );
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
					array(),
					$this->msg( 'ipblocklist-otherblocks', count( $otherBlockLink ) )->parse()
				) . "\n"
			);
			$list = '';
			foreach ( $otherBlockLink as $link ) {
				$list .= Html::rawElement( 'li', array(), $link ) . "\n";
			}
			$out->addHTML( Html::rawElement(
				'ul',
				array( 'class' => 'mw-ipblocklist-otherblocks' ),
				$list
			) . "\n" );
		}
	}

	protected function getGroupName() {
		return 'users';
	}
}

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
			$headers = array(
				'ipb_timestamp' => 'blocklist-timestamp',
				'ipb_target' => 'blocklist-target',
				'ipb_expiry' => 'blocklist-expiry',
				'ipb_by' => 'blocklist-by',
				'ipb_params' => 'blocklist-params',
				'ipb_reason' => 'blocklist-reason',
			);
			foreach ( $headers as $key => $val ) {
				$headers[$key] = $this->msg( $val )->text();
			}
		}

		return $headers;
	}

	function formatValue( $name, $value ) {
		static $msg = null;
		if ( $msg === null ) {
			$msg = array(
				'anononlyblock',
				'createaccountblock',
				'noautoblockblock',
				'emailblock',
				'blocklist-nousertalk',
				'unblocklink',
				'change-blocklink',
			);
			$msg = array_combine( $msg, array_map( array( $this, 'msg' ), $msg ) );
		}

		/** @var $row object */
		$row = $this->mCurrentRow;

		$formatted = '';

		switch ( $name ) {
			case 'ipb_timestamp':
				$formatted = $this->getLanguage()->userTimeAndDate( $value, $this->getUser() );
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
				$formatted = $this->getLanguage()->formatExpiry( $value, /* User preference timezone */true );
				if ( $this->getUser()->isAllowed( 'block' ) ) {
					if ( $row->ipb_auto ) {
						$links[] = Linker::linkKnown(
							SpecialPage::getTitleFor( 'Unblock' ),
							$msg['unblocklink'],
							array(),
							array( 'wpTarget' => "#{$row->ipb_id}" )
						);
					} else {
						$links[] = Linker::linkKnown(
							SpecialPage::getTitleFor( 'Unblock', $row->ipb_address ),
							$msg['unblocklink']
						);
						$links[] = Linker::linkKnown(
							SpecialPage::getTitleFor( 'Block', $row->ipb_address ),
							$msg['change-blocklink']
						);
					}
					$formatted .= ' ' . Html::rawElement(
						'span',
						array( 'class' => 'mw-blocklist-actions' ),
						$this->msg( 'parentheses' )->rawParams(
							$this->getLanguage()->pipeList( $links ) )->escaped()
					);
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
				$properties = array();
				if ( $row->ipb_anon_only ) {
					$properties[] = $msg['anononlyblock'];
				}
				if ( $row->ipb_create_account ) {
					$properties[] = $msg['createaccountblock'];
				}
				if ( $row->ipb_user && !$row->ipb_enable_autoblock ) {
					$properties[] = $msg['noautoblockblock'];
				}

				if ( $row->ipb_block_email ) {
					$properties[] = $msg['emailblock'];
				}

				if ( !$row->ipb_allow_usertalk ) {
					$properties[] = $msg['blocklist-nousertalk'];
				}

				$formatted = $this->getLanguage()->commaList( $properties );
				break;

			default:
				$formatted = "Unable to format $name";
				break;
		}

		return $formatted;
	}

	function getQueryInfo() {
		$info = array(
			'tables' => array( 'ipblocks', 'user' ),
			'fields' => array(
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
			),
			'conds' => $this->conds,
			'join_conds' => array( 'user' => array( 'LEFT JOIN', 'user_id = ipb_by' ) )
		);

		# Filter out any expired blocks
		$db = $this->getDatabase();
		$info['conds'][] = 'ipb_expiry > ' . $db->addQuotes( $db->timestamp() );

		# Is the user allowed to see hidden blocks?
		if ( !$this->getUser()->isAllowed( 'hideuser' ) ) {
			$info['conds']['ipb_deleted'] = 0;
		}

		return $info;
	}

	public function getTableClass() {
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

		$userids = array();

		foreach ( $result as $row ) {
			$userids[] = $row->ipb_by;

			# Usernames and titles are in fact related by a simple substitution of space -> underscore
			# The last few lines of Title::secureAndSplit() tell the story.
			$name = str_replace( ' ', '_', $row->ipb_address );
			$lb->add( NS_USER, $name );
			$lb->add( NS_USER_TALK, $name );
		}

		$ua = UserArray::newFromIDs( $userids );
		foreach ( $ua as $user ) {
			$name = str_replace( ' ', '_', $user->getName() );
			$lb->add( NS_USER, $name );
			$lb->add( NS_USER_TALK, $name );
		}

		$lb->execute();
	}
}
