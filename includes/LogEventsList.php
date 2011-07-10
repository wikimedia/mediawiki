<?php
/**
 * Contain classes to list log entries
 *
 * Copyright © 2004 Brion Vibber <brion@pobox.com>, 2008 Aaron Schulz
 * http://www.mediawiki.org/
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
 */

class LogEventsList {
	const NO_ACTION_LINK = 1;
	const NO_EXTRA_USER_LINKS = 2;

	/**
	 * @var Skin
	 */
	private $skin;

	/**
	 * @var OutputPage
	 */
	private $out;
	public $flags;

	/**
	 * @var Array
	 */
	protected $message;

	/**
	 * @var Array
	 */
	protected $mDefaultQuery;

	public function __construct( $skin, $out, $flags = 0 ) {
		$this->skin = $skin;
		$this->out = $out;
		$this->flags = $flags;
		$this->preCacheMessages();
	}

	/**
	 * As we use the same small set of messages in various methods and that
	 * they are called often, we call them once and save them in $this->message
	 */
	private function preCacheMessages() {
		// Precache various messages
		if( !isset( $this->message ) ) {
			$messages = array( 'revertmerge', 'protect_change', 'unblocklink', 'change-blocklink',
				'revertmove', 'undeletelink', 'undeleteviewlink', 'revdel-restore', 'hist', 'diff',
				'pipe-separator', 'revdel-restore-deleted', 'revdel-restore-visible' );
			foreach( $messages as $msg ) {
				$this->message[$msg] = wfMsgExt( $msg, array( 'escapenoentities' ) );
			}
		}
	}

	/**
	 * Set page title and show header for this log type
	 * @param $type Array
	 */
	public function showHeader( $type ) {
		// If only one log type is used, then show a special message...
		$headerType = (count($type) == 1) ? $type[0] : '';
		if( LogPage::isLogType( $headerType ) ) {
			$this->out->setPageTitle( LogPage::logName( $headerType ) );
			$this->out->addHTML( LogPage::logHeader( $headerType ) );
		} else {
			$this->out->addHTML( wfMsgExt('alllogstext',array('parseinline')) );
		}
	}

	/**
	 * Show options for the log list
	 *
	 * @param $types string or Array
	 * @param $user String
	 * @param $page String
	 * @param $pattern String
	 * @param $year Integer: year
	 * @param $month Integer: month
	 * @param $filter: array
	 * @param $tagFilter: array?
	 */
	public function showOptions( $types=array(), $user='', $page='', $pattern='', $year='',
		$month = '', $filter = null, $tagFilter='' ) {
		global $wgScript, $wgMiserMode;

		$action = $wgScript;
		$title = SpecialPage::getTitleFor( 'Log' );
		$special = $title->getPrefixedDBkey();

		// For B/C, we take strings, but make sure they are converted...
		$types = ($types === '') ? array() : (array)$types;

		$tagSelector = ChangeTags::buildTagFilterSelector( $tagFilter );

		$html = Html::hidden( 'title', $special );

		// Basic selectors
		$html .= $this->getTypeMenu( $types ) . "\n";
		$html .= $this->getUserInput( $user ) . "\n";
		$html .= $this->getTitleInput( $page ) . "\n";
		$html .= $this->getExtraInputs( $types ) . "\n";

		// Title pattern, if allowed
		if (!$wgMiserMode) {
			$html .= $this->getTitlePattern( $pattern ) . "\n";
		}

		// date menu
		$html .= Xml::tags( 'p', null, Xml::dateMenu( $year, $month ) );

		// Tag filter
		if ($tagSelector) {
			$html .= Xml::tags( 'p', null, implode( '&#160;', $tagSelector ) );
		}

		// Filter links
		if ($filter) {
			$html .= Xml::tags( 'p', null, $this->getFilterLinks( $filter ) );
		}

		// Submit button
		$html .= Xml::submitButton( wfMsg( 'allpagessubmit' ) );

		// Fieldset
		$html = Xml::fieldset( wfMsg( 'log' ), $html );

		// Form wrapping
		$html = Xml::tags( 'form', array( 'action' => $action, 'method' => 'get' ), $html );

		$this->out->addHTML( $html );
	}

	/**
	 * @param $filter Array
	 * @return String: Formatted HTML
	 */
	private function getFilterLinks( $filter ) {
		global $wgLang;
		// show/hide links
		$messages = array( wfMsgHtml( 'show' ), wfMsgHtml( 'hide' ) );
		// Option value -> message mapping
		$links = array();
		$hiddens = ''; // keep track for "go" button
		foreach( $filter as $type => $val ) {
			// Should the below assignment be outside the foreach?
			// Then it would have to be copied. Not certain what is more expensive.
			$query = $this->getDefaultQuery();
			$queryKey = "hide_{$type}_log";

			$hideVal = 1 - intval($val);
			$query[$queryKey] = $hideVal;

			$link = Linker::link(
				$this->getDisplayTitle(),
				$messages[$hideVal],
				array(),
				$query,
				array( 'known', 'noclasses' )
			);

			$links[$type] = wfMsgHtml( "log-show-hide-{$type}", $link );
			$hiddens .= Html::hidden( "hide_{$type}_log", $val ) . "\n";
		}
		// Build links
		return '<small>'.$wgLang->pipeList( $links ) . '</small>' . $hiddens;
	}

	private function getDefaultQuery() {
		global $wgRequest;

		if ( !isset( $this->mDefaultQuery ) ) {
			$this->mDefaultQuery = $wgRequest->getQueryValues();
			unset( $this->mDefaultQuery['title'] );
			unset( $this->mDefaultQuery['dir'] );
			unset( $this->mDefaultQuery['offset'] );
			unset( $this->mDefaultQuery['limit'] );
			unset( $this->mDefaultQuery['order'] );
			unset( $this->mDefaultQuery['month'] );
			unset( $this->mDefaultQuery['year'] );
		}
		return $this->mDefaultQuery;
	}

	/**
	 * Get the Title object of the page the links should point to.
	 * This is NOT the Title of the page the entries should be restricted to.
	 *
	 * @return Title object
	 */
	public function getDisplayTitle() {
		return $this->out->getTitle();
	}

	/**
	 * @param $queryTypes Array
	 * @return String: Formatted HTML
	 */
	private function getTypeMenu( $queryTypes ) {
		global $wgLogRestrictions, $wgUser;

		$html = "<select name='type'>\n";

		$validTypes = LogPage::validTypes();
		$typesByName = array(); // Temporary array

		// First pass to load the log names
		foreach( $validTypes as $type ) {
			$text = LogPage::logName( $type );
			$typesByName[$type] = $text;
		}

		// Second pass to sort by name
		asort($typesByName);

		// Note the query type
		$queryType = count($queryTypes) == 1 ? $queryTypes[0] : '';

		// Always put "All public logs" on top
		if ( isset( $typesByName[''] ) ) {
			$all = $typesByName[''];
			unset( $typesByName[''] );
			$typesByName = array( '' => $all ) + $typesByName;
		}

		// Third pass generates sorted XHTML content
		foreach( $typesByName as $type => $text ) {
			$selected = ($type == $queryType);
			// Restricted types
			if ( isset($wgLogRestrictions[$type]) ) {
				if ( $wgUser->isAllowed( $wgLogRestrictions[$type] ) ) {
					$html .= Xml::option( $text, $type, $selected ) . "\n";
				}
			} else {
				$html .= Xml::option( $text, $type, $selected ) . "\n";
			}
		}

		$html .= '</select>';
		return $html;
	}

	/**
	 * @param $user String
	 * @return String: Formatted HTML
	 */
	private function getUserInput( $user ) {
		return '<span style="white-space: nowrap">' .
			Xml::inputLabel( wfMsg( 'specialloguserlabel' ), 'user', 'mw-log-user', 15, $user ) .
			'</span>';
	}

	/**
	 * @param $title String
	 * @return String: Formatted HTML
	 */
	private function getTitleInput( $title ) {
		return '<span style="white-space: nowrap">' .
			Xml::inputLabel( wfMsg( 'speciallogtitlelabel' ), 'page', 'mw-log-page', 20, $title ) .
			'</span>';
	}

	/**
	 * @return boolean Checkbox
	 */
	private function getTitlePattern( $pattern ) {
		return '<span style="white-space: nowrap">' .
			Xml::checkLabel( wfMsg( 'log-title-wildcard' ), 'pattern', 'pattern', $pattern ) .
			'</span>';
	}

	private function getExtraInputs( $types ) {
		global $wgRequest;
		$offender = $wgRequest->getVal('offender');
		$user = User::newFromName( $offender, false );
		if( !$user || ($user->getId() == 0 && !IP::isIPAddress($offender) ) ) {
			$offender = ''; // Blank field if invalid
		}
		if( count($types) == 1 && $types[0] == 'suppress' ) {
			return Xml::inputLabel( wfMsg('revdelete-offender'), 'offender',
				'mw-log-offender', 20, $offender );
		}
		return '';
	}

	public function beginLogEventsList() {
		return "<ul>\n";
	}

	public function endLogEventsList() {
		return "</ul>\n";
	}

	/**
	 * @param $row Row: a single row from the result set
	 * @return String: Formatted HTML list item
	 */
	public function logLine( $row ) {
		$classes = array( 'mw-logline-' . $row->log_type );
		$title = Title::makeTitle( $row->log_namespace, $row->log_title );
		// Log time
		$time = $this->logTimestamp( $row );
		// User links
		$userLink = $this->logUserLinks( $row );
		// Extract extra parameters
		$paramArray = LogPage::extractParams( $row->log_params );
		// Event description
		$action = $this->logAction( $row, $title, $paramArray );
		// Log comment
		$comment = $this->logComment( $row );
		// Add review/revert links and such...
		$revert = $this->logActionLinks( $row, $title, $paramArray, $comment );

		// Some user can hide log items and have review links
		$del = $this->getShowHideLinks( $row );
		if( $del != '' ) $del .= ' ';

		// Any tags...
		list( $tagDisplay, $newClasses ) = ChangeTags::formatSummaryRow( $row->ts_tags, 'logevent' );
		$classes = array_merge( $classes, $newClasses );

		return Xml::tags( 'li', array( "class" => implode( ' ', $classes ) ),
			$del . "$time $userLink $action $comment $revert $tagDisplay" ) . "\n";
	}

	private function logTimestamp( $row ) {
		global $wgLang;
		$time = $wgLang->timeanddate( wfTimestamp( TS_MW, $row->log_timestamp ), true );
		return htmlspecialchars( $time );
	}

	private function logUserLinks( $row ) {
		if( self::isDeleted( $row, LogPage::DELETED_USER ) ) {
			$userLinks = '<span class="history-deleted">' .
				wfMsgHtml( 'rev-deleted-user' ) . '</span>';
		} else {
			$userLinks = Linker::userLink( $row->log_user, $row->user_name );
			// Talk|Contribs links...
			if( !( $this->flags & self::NO_EXTRA_USER_LINKS ) ) {
				$userLinks .= Linker::userToolLinks(
					$row->log_user, $row->user_name, true, 0, $row->user_editcount );
			}
		}
		return $userLinks;
	}

	private function logAction( $row, $title, $paramArray ) {
		if( self::isDeleted( $row, LogPage::DELETED_ACTION ) ) {
			$action = '<span class="history-deleted">' .
				wfMsgHtml( 'rev-deleted-event' ) . '</span>';
		} else {
			$action = LogPage::actionText(
				$row->log_type, $row->log_action, $title, $this->skin, $paramArray, true );
		}
		return $action;
	}

	private function logComment( $row ) {
		if( self::isDeleted( $row, LogPage::DELETED_COMMENT ) ) {
			$comment = '<span class="history-deleted">' .
				wfMsgHtml( 'rev-deleted-comment' ) . '</span>';
		} else {
			global $wgLang;
			$comment = $wgLang->getDirMark() .
				Linker::commentBlock( $row->log_comment );
		}
		return $comment;
	}

	/**
	 * @TODO: split up!
	 *
	 * @param  $row
	 * @param Title $title
	 * @param Array $paramArray
	 * @param  $comment
	 * @return String
	 */
	private function logActionLinks( $row, $title, $paramArray, &$comment ) {
		global $wgUser;
		if( ( $this->flags & self::NO_ACTION_LINK ) // we don't want to see the action
			|| self::isDeleted( $row, LogPage::DELETED_ACTION ) ) // action is hidden
		{
			return '';
		}
		$revert = '';
		if( self::typeAction( $row, 'move', 'move', 'move' ) && !empty( $paramArray[0] ) ) {
			$destTitle = Title::newFromText( $paramArray[0] );
			if( $destTitle ) {
				$revert = '(' . Linker::link(
					SpecialPage::getTitleFor( 'Movepage' ),
					$this->message['revertmove'],
					array(),
					array(
						'wpOldTitle' => $destTitle->getPrefixedDBkey(),
						'wpNewTitle' => $title->getPrefixedDBkey(),
						'wpReason'   => wfMsgForContent( 'revertmove' ),
						'wpMovetalk' => 0
					),
					array( 'known', 'noclasses' )
				) . ')';
			}
		// Show undelete link
		} elseif( self::typeAction( $row, array( 'delete', 'suppress' ), 'delete', 'deletedhistory' ) ) {
			if( !$wgUser->isAllowed( 'undelete' ) ) {
				$viewdeleted = $this->message['undeleteviewlink'];
			} else {
				$viewdeleted = $this->message['undeletelink'];
			}
			$revert = '(' . Linker::link(
				SpecialPage::getTitleFor( 'Undelete' ),
				$viewdeleted,
				array(),
				array( 'target' => $title->getPrefixedDBkey() ),
				array( 'known', 'noclasses' )
			 ) . ')';
		// Show unblock/change block link
		} elseif( self::typeAction( $row, array( 'block', 'suppress' ), array( 'block', 'reblock' ), 'block' ) ) {
			$revert = '(' .
				Linker::link(
					SpecialPage::getTitleFor( 'Unblock', $row->log_title ),
					$this->message['unblocklink'],
					array(),
					array(),
					'known'
				) .
				$this->message['pipe-separator'] .
				Linker::link(
					SpecialPage::getTitleFor( 'Block', $row->log_title ),
					$this->message['change-blocklink'],
					array(),
					array(),
					'known'
				) .
				')';
		// Show change protection link
		} elseif( self::typeAction( $row, 'protect', array( 'modify', 'protect', 'unprotect' ) ) ) {
			$revert .= ' (' .
				Linker::link( $title,
					$this->message['hist'],
					array(),
					array(
						'action' => 'history',
						'offset' => $row->log_timestamp
					)
				);
			if( $wgUser->isAllowed( 'protect' ) ) {
				$revert .= $this->message['pipe-separator'] .
					Linker::link( $title,
						$this->message['protect_change'],
						array(),
						array( 'action' => 'protect' ),
						'known' );
			}
			$revert .= ')';
		// Show unmerge link
		} elseif( self::typeAction( $row, 'merge', 'merge', 'mergehistory' ) ) {
			$revert = '(' . Linker::link(
				SpecialPage::getTitleFor( 'MergeHistory' ),
				$this->message['revertmerge'],
				array(),
				array(
					'target' => $paramArray[0],
					'dest' => $title->getPrefixedDBkey(),
					'mergepoint' => $paramArray[1]
				),
				array( 'known', 'noclasses' )
			) . ')';
		// If an edit was hidden from a page give a review link to the history
		} elseif( self::typeAction( $row, array( 'delete', 'suppress' ), 'revision', 'deletedhistory' ) ) {
			$revert = RevisionDeleter::getLogLinks( $title, $paramArray,
								$this->skin, $this->message );
		// Hidden log items, give review link
		} elseif( self::typeAction( $row, array( 'delete', 'suppress' ), 'event', 'deletedhistory' ) ) {
			if( count($paramArray) >= 1 ) {
				$revdel = SpecialPage::getTitleFor( 'Revisiondelete' );
				// $paramArray[1] is a CSV of the IDs
				$query = $paramArray[0];
				// Link to each hidden object ID, $paramArray[1] is the url param
				$revert = '(' . Linker::link(
					$revdel,
					$this->message['revdel-restore'],
					array(),
					array(
						'target' => $title->getPrefixedText(),
						'type' => 'logging',
						'ids' => $query
					),
					array( 'known', 'noclasses' )
				) . ')';
			}
		// Self-created users
		} elseif( self::typeAction( $row, 'newusers', 'create2' ) ) {
			if( isset( $paramArray[0] ) ) {
				$revert = Linker::userToolLinks( $paramArray[0], $title->getDBkey(), true );
			} else {
				# Fall back to a blue contributions link
				$revert = Linker::userToolLinks( 1, $title->getDBkey() );
			}
			if( wfTimestamp( TS_MW, $row->log_timestamp ) < '20080129000000' ) {
				# Suppress $comment from old entries (before 2008-01-29),
				# not needed and can contain incorrect links
				$comment = '';
			}
		// Do nothing. The implementation is handled by the hook modifiying the passed-by-ref parameters.
		} else {
			wfRunHooks( 'LogLine', array( $row->log_type, $row->log_action, $title, $paramArray,
				&$comment, &$revert, $row->log_timestamp ) );
		}
		if( $revert != '' ) {
			$revert = '<span class="mw-logevent-actionlink">' . $revert . '</span>';
		}
		return $revert;
	}

	/**
	 * @param $row Row
	 * @return string
	 */
	private function getShowHideLinks( $row ) {
		global $wgUser;
		if( ( $this->flags & self::NO_ACTION_LINK ) // we don't want to see the links
			|| $row->log_type == 'suppress' ) { // no one can hide items from the suppress log
			return '';
		}
		$del = '';
		// Don't show useless link to people who cannot hide revisions
		if( $wgUser->isAllowed( 'deletedhistory' ) ) {
			if( $row->log_deleted || $wgUser->isAllowed( 'deleterevision' ) ) {
				$canHide = $wgUser->isAllowed( 'deleterevision' );
				// If event was hidden from sysops
				if( !self::userCan( $row, LogPage::DELETED_RESTRICTED ) ) {
					$del = Linker::revDeleteLinkDisabled( $canHide );
				} else {
					$target = SpecialPage::getTitleFor( 'Log', $row->log_type );
					$query = array(
						'target' => $target->getPrefixedDBkey(),
						'type'   => 'logging',
						'ids'    => $row->log_id,
					);
					$del = Linker::revDeleteLink( $query,
						self::isDeleted( $row, LogPage::DELETED_RESTRICTED ), $canHide );
				}
			}
		}
		return $del;
	}

	/**
	 * @param $row Row
	 * @param $type Mixed: string/array
	 * @param $action Mixed: string/array
	 * @param $right string
	 * @return Boolean
	 */
	public static function typeAction( $row, $type, $action, $right='' ) {
		$match = is_array($type) ?
			in_array( $row->log_type, $type ) : $row->log_type == $type;
		if( $match ) {
			$match = is_array( $action ) ?
				in_array( $row->log_action, $action ) : $row->log_action == $action;
			if( $match && $right ) {
				global $wgUser;
				$match = $wgUser->isAllowed( $right );
			}
		}
		return $match;
	}

	/**
	 * Determine if the current user is allowed to view a particular
	 * field of this log row, if it's marked as deleted.
	 *
	 * @param $row Row
	 * @param $field Integer
	 * @return Boolean
	 */
	public static function userCan( $row, $field ) {
		return self::userCanBitfield( $row->log_deleted, $field );
	}

	/**
	 * Determine if the current user is allowed to view a particular
	 * field of this log row, if it's marked as deleted.
	 *
	 * @param $bitfield Integer (current field)
	 * @param $field Integer
	 * @return Boolean
	 */
	public static function userCanBitfield( $bitfield, $field ) {
		if( $bitfield & $field ) {
			global $wgUser;

			if ( $bitfield & LogPage::DELETED_RESTRICTED ) {
				$permission = 'suppressrevision';
			} else {
				$permission = 'deletedhistory';
			}
			wfDebug( "Checking for $permission due to $field match on $bitfield\n" );
			return $wgUser->isAllowed( $permission );
		} else {
			return true;
		}
	}

	/**
	 * @param $row Row
	 * @param $field Integer: one of DELETED_* bitfield constants
	 * @return Boolean
	 */
	public static function isDeleted( $row, $field ) {
		return ( $row->log_deleted & $field ) == $field;
	}

	/**
	 * Show log extract. Either with text and a box (set $msgKey) or without (don't set $msgKey)
	 *
	 * @param $out OutputPage|String-by-reference
	 * @param $types String or Array
	 * @param $page String The page title to show log entries for
	 * @param $user String The user who made the log entries
	 * @param $param Associative Array with the following additional options:
	 * - lim Integer Limit of items to show, default is 50
	 * - conds Array Extra conditions for the query (e.g. "log_action != 'revision'")
	 * - showIfEmpty boolean Set to false if you don't want any output in case the loglist is empty
	 *   if set to true (default), "No matching items in log" is displayed if loglist is empty
	 * - msgKey Array If you want a nice box with a message, set this to the key of the message.
	 *   First element is the message key, additional optional elements are parameters for the key
	 *   that are processed with wgMsgExt and option 'parse'
	 * - offset Set to overwrite offset parameter in $wgRequest
	 *   set to '' to unset offset
	 * - wrap String Wrap the message in html (usually something like "<div ...>$1</div>").
	 * - flags Integer display flags (NO_ACTION_LINK,NO_EXTRA_USER_LINKS)
	 * @return Integer Number of total log items (not limited by $lim)
	 */
	public static function showLogExtract(
		&$out, $types=array(), $page='', $user='', $param = array()
	) {
		global $wgUser, $wgOut;
		$defaultParameters = array(
			'lim' => 25,
			'conds' => array(),
			'showIfEmpty' => true,
			'msgKey' => array(''),
			'wrap' => "$1",
			'flags' => 0
		);
		# The + operator appends elements of remaining keys from the right
		# handed array to the left handed, whereas duplicated keys are NOT overwritten.
		$param += $defaultParameters;
		# Convert $param array to individual variables
		$lim = $param['lim'];
		$conds = $param['conds'];
		$showIfEmpty = $param['showIfEmpty'];
		$msgKey = $param['msgKey'];
		$wrap = $param['wrap'];
		$flags = $param['flags'];
		if ( !is_array( $msgKey ) ) {
			$msgKey = array( $msgKey );
		}
		# Insert list of top 50 (or top $lim) items
		$loglist = new LogEventsList( $wgUser->getSkin(), $wgOut, $flags );
		$pager = new LogPager( $loglist, $types, $user, $page, '', $conds );
		if ( isset( $param['offset'] ) ) { # Tell pager to ignore $wgRequest offset
			$pager->setOffset( $param['offset'] );
		}
		if( $lim > 0 ) $pager->mLimit = $lim;
		$logBody = $pager->getBody();
		$s = '';
		if( $logBody ) {
			if ( $msgKey[0] ) {
				$s = '<div class="mw-warning-with-logexcerpt">';

				if ( count( $msgKey ) == 1 ) {
					$s .= wfMsgExt( $msgKey[0], array( 'parse' ) );
				} else { // Process additional arguments
					$args = $msgKey;
					array_shift( $args );
					$s .= wfMsgExt( $msgKey[0], array( 'parse' ), $args );
				}
			}
			$s .= $loglist->beginLogEventsList() .
				 $logBody .
				 $loglist->endLogEventsList();
		} else {
			if ( $showIfEmpty )
				$s = Html::rawElement( 'div', array( 'class' => 'mw-warning-logempty' ),
					wfMsgExt( 'logempty', array( 'parseinline' ) ) );
		}
		if( $pager->getNumRows() > $pager->mLimit ) { # Show "Full log" link
			$urlParam = array();
			if ( $page != '')
				$urlParam['page'] = $page;
			if ( $user != '')
				$urlParam['user'] = $user;
			if ( !is_array( $types ) ) # Make it an array, if it isn't
				$types = array( $types );
			# If there is exactly one log type, we can link to Special:Log?type=foo
			if ( count( $types ) == 1 )
				$urlParam['type'] = $types[0];
			$s .= Linker::link(
				SpecialPage::getTitleFor( 'Log' ),
				wfMsgHtml( 'log-fulllog' ),
				array(),
				$urlParam
			);
		}
		if ( $logBody && $msgKey[0] ) {
			$s .= '</div>';
		}

		if ( $wrap!='' ) { // Wrap message in html
			$s = str_replace( '$1', $s, $wrap );
		}

		// $out can be either an OutputPage object or a String-by-reference
		if( $out instanceof OutputPage ){
			$out->addHTML( $s );
		} else {
			$out = $s;
		}
		return $pager->getNumRows();
	}

	/**
	 * SQL clause to skip forbidden log types for this user
	 *
	 * @param $db Database
	 * @param $audience string, public/user
	 * @return Mixed: string or false
	 */
	public static function getExcludeClause( $db, $audience = 'public' ) {
		global $wgLogRestrictions, $wgUser;
		// Reset the array, clears extra "where" clauses when $par is used
		$hiddenLogs = array();
		// Don't show private logs to unprivileged users
		foreach( $wgLogRestrictions as $logType => $right ) {
			if( $audience == 'public' || !$wgUser->isAllowed($right) ) {
				$safeType = $db->strencode( $logType );
				$hiddenLogs[] = $safeType;
			}
		}
		if( count($hiddenLogs) == 1 ) {
			return 'log_type != ' . $db->addQuotes( $hiddenLogs[0] );
		} elseif( $hiddenLogs ) {
			return 'log_type NOT IN (' . $db->makeList($hiddenLogs) . ')';
		}
		return false;
	}
}

/**
 * @ingroup Pager
 */
class LogPager extends ReverseChronologicalPager {
	private $types = array(), $user = '', $title = '', $pattern = '';
	private $typeCGI = '';
	public $mLogEventsList;

	/**
	 * Constructor
	 *
	 * @param $list LogEventsList
	 * @param $types String or Array: log types to show
	 * @param $user String: the user who made the log entries
	 * @param $title String: the page title the log entries are for
	 * @param $pattern String: do a prefix search rather than an exact title match
	 * @param $conds Array: extra conditions for the query
	 * @param $year Integer: the year to start from
	 * @param $month Integer: the month to start from
	 * @param $tagFilter String: tag
	 */
	public function __construct( $list, $types = array(), $user = '', $title = '', $pattern = '',
		$conds = array(), $year = false, $month = false, $tagFilter = '' ) {
		parent::__construct();
		$this->mConds = $conds;

		$this->mLogEventsList = $list;

		$this->limitType( $types ); // also excludes hidden types
		$this->limitUser( $user );
		$this->limitTitle( $title, $pattern );
		$this->getDateCond( $year, $month );
		$this->mTagFilter = $tagFilter;
	}

	public function getDefaultQuery() {
		$query = parent::getDefaultQuery();
		$query['type'] = $this->typeCGI; // arrays won't work here
		$query['user'] = $this->user;
		$query['month'] = $this->mMonth;
		$query['year'] = $this->mYear;
		return $query;
	}

	function getTitle() {
		return $this->mLogEventsList->getDisplayTitle();
	}

	// Call ONLY after calling $this->limitType() already!
	public function getFilterParams() {
		global $wgFilterLogTypes, $wgUser, $wgRequest;
		$filters = array();
		if( count($this->types) ) {
			return $filters;
		}
		foreach( $wgFilterLogTypes as $type => $default ) {
			// Avoid silly filtering
			if( $type !== 'patrol' || $wgUser->useNPPatrol() ) {
				$hide = $wgRequest->getInt( "hide_{$type}_log", $default );
				$filters[$type] = $hide;
				if( $hide )
					$this->mConds[] = 'log_type != ' . $this->mDb->addQuotes( $type );
			}
		}
		return $filters;
	}

	/**
	 * Set the log reader to return only entries of the given type.
	 * Type restrictions enforced here
	 *
	 * @param $types String or array: Log types ('upload', 'delete', etc);
	 *   empty string means no restriction
	 */
	private function limitType( $types ) {
		global $wgLogRestrictions, $wgUser;
		// If $types is not an array, make it an array
		$types = ($types === '') ? array() : (array)$types;
		// Don't even show header for private logs; don't recognize it...
		foreach ( $types as $type ) {
			if( isset( $wgLogRestrictions[$type] )
				&& !$wgUser->isAllowed($wgLogRestrictions[$type])
			) {
				$types = array_diff( $types, array( $type ) );
			}
		}
		$this->types = $types;
		// Don't show private logs to unprivileged users.
		// Also, only show them upon specific request to avoid suprises.
		$audience = $types ? 'user' : 'public';
		$hideLogs = LogEventsList::getExcludeClause( $this->mDb, $audience );
		if( $hideLogs !== false ) {
			$this->mConds[] = $hideLogs;
		}
		if( count($types) ) {
			$this->mConds['log_type'] = $types;
			// Set typeCGI; used in url param for paging
			if( count($types) == 1 ) $this->typeCGI = $types[0];
		}
	}

	/**
	 * Set the log reader to return only entries by the given user.
	 *
	 * @param $name String: (In)valid user name
	 */
	private function limitUser( $name ) {
		if( $name == '' ) {
			return false;
		}
		$usertitle = Title::makeTitleSafe( NS_USER, $name );
		if( is_null($usertitle) ) {
			return false;
		}
		/* Fetch userid at first, if known, provides awesome query plan afterwards */
		$userid = User::idFromName( $name );
		if( !$userid ) {
			/* It should be nicer to abort query at all,
			   but for now it won't pass anywhere behind the optimizer */
			$this->mConds[] = "NULL";
		} else {
			global $wgUser;
			$this->mConds['log_user'] = $userid;
			// Paranoia: avoid brute force searches (bug 17342)
			if( !$wgUser->isAllowed( 'deletedhistory' ) ) {
				$this->mConds[] = $this->mDb->bitAnd('log_deleted', LogPage::DELETED_USER) . ' = 0';
			} elseif( !$wgUser->isAllowed( 'suppressrevision' ) ) {
				$this->mConds[] = $this->mDb->bitAnd('log_deleted', LogPage::SUPPRESSED_USER) .
					' != ' . LogPage::SUPPRESSED_USER;
			}
			$this->user = $usertitle->getText();
		}
	}

	/**
	 * Set the log reader to return only entries affecting the given page.
	 * (For the block and rights logs, this is a user page.)
	 *
	 * @param $page String: Title name as text
	 * @param $pattern String
	 */
	private function limitTitle( $page, $pattern ) {
		global $wgMiserMode, $wgUser;

		$title = Title::newFromText( $page );
		if( strlen( $page ) == 0 || !$title instanceof Title ) {
			return false;
		}

		$this->title = $title->getPrefixedText();
		$ns = $title->getNamespace();
		$db = $this->mDb;

		# Using the (log_namespace, log_title, log_timestamp) index with a
		# range scan (LIKE) on the first two parts, instead of simple equality,
		# makes it unusable for sorting.  Sorted retrieval using another index
		# would be possible, but then we might have to scan arbitrarily many
		# nodes of that index. Therefore, we need to avoid this if $wgMiserMode
		# is on.
		#
		# This is not a problem with simple title matches, because then we can
		# use the page_time index.  That should have no more than a few hundred
		# log entries for even the busiest pages, so it can be safely scanned
		# in full to satisfy an impossible condition on user or similar.
		if( $pattern && !$wgMiserMode ) {
			$this->mConds['log_namespace'] = $ns;
			$this->mConds[] = 'log_title ' . $db->buildLike( $title->getDBkey(), $db->anyString() );
			$this->pattern = $pattern;
		} else {
			$this->mConds['log_namespace'] = $ns;
			$this->mConds['log_title'] = $title->getDBkey();
		}
		// Paranoia: avoid brute force searches (bug 17342)
		if( !$wgUser->isAllowed( 'deletedhistory' ) ) {
			$this->mConds[] = $db->bitAnd('log_deleted', LogPage::DELETED_ACTION) . ' = 0';
		} elseif( !$wgUser->isAllowed( 'suppressrevision' ) ) {
			$this->mConds[] = $db->bitAnd('log_deleted', LogPage::SUPPRESSED_ACTION) .
				' != ' . LogPage::SUPPRESSED_ACTION;
		}
	}

	public function getQueryInfo() {
		$tables = array( 'logging', 'user' );
		$this->mConds[] = 'user_id = log_user';
		$index = array();
		$options = array();
		# Add log_search table if there are conditions on it.
		# This filters the results to only include log rows that have
		# log_search records with the specified ls_field and ls_value values.
		if( array_key_exists( 'ls_field', $this->mConds ) ) {
			$tables[] = 'log_search';
			$index['log_search'] = 'ls_field_val';
			$index['logging'] = 'PRIMARY';
			if ( !$this->hasEqualsClause( 'ls_field' )
				|| !$this->hasEqualsClause( 'ls_value' ) )
			{
				# Since (ls_field,ls_value,ls_logid) is unique, if the condition is
				# to match a specific (ls_field,ls_value) tuple, then there will be
				# no duplicate log rows. Otherwise, we need to remove the duplicates.
				$options[] = 'DISTINCT';
			}
		# Avoid usage of the wrong index by limiting
		# the choices of available indexes. This mainly
		# avoids site-breaking filesorts.
		} elseif( $this->title || $this->pattern || $this->user ) {
			$index['logging'] = array( 'page_time', 'user_time' );
			if( count($this->types) == 1 ) {
				$index['logging'][] = 'log_user_type_time';
			}
		} elseif( count($this->types) == 1 ) {
			$index['logging'] = 'type_time';
		} else {
			$index['logging'] = 'times';
		}
		$options['USE INDEX'] = $index;
		# Don't show duplicate rows when using log_search
		$info = array(
			'tables'     => $tables,
			'fields'     => array( 'log_type', 'log_action', 'log_user', 'log_namespace',
				'log_title', 'log_params', 'log_comment', 'log_id', 'log_deleted',
				'log_timestamp', 'user_name', 'user_editcount' ),
			'conds'      => $this->mConds,
			'options'    => $options,
			'join_conds' => array(
				'user' 		 => array( 'INNER JOIN', 'user_id=log_user' ),
				'log_search' => array( 'INNER JOIN', 'ls_log_id=log_id' )
			)
		);
		# Add ChangeTags filter query
		ChangeTags::modifyDisplayQuery( $info['tables'], $info['fields'], $info['conds'],
			$info['join_conds'], $info['options'], $this->mTagFilter );
		return $info;
	}

	// Checks if $this->mConds has $field matched to a *single* value
	protected function hasEqualsClause( $field ) {
		return (
			array_key_exists( $field, $this->mConds ) &&
			( !is_array( $this->mConds[$field] ) || count( $this->mConds[$field] ) == 1 )
		);
	}

	function getIndexField() {
		return 'log_timestamp';
	}

	public function getStartBody() {
		wfProfileIn( __METHOD__ );
		# Do a link batch query
		if( $this->getNumRows() > 0 ) {
			$lb = new LinkBatch;
			foreach ( $this->mResult as $row ) {
				$lb->add( $row->log_namespace, $row->log_title );
				$lb->addObj( Title::makeTitleSafe( NS_USER, $row->user_name ) );
				$lb->addObj( Title::makeTitleSafe( NS_USER_TALK, $row->user_name ) );
			}
			$lb->execute();
			$this->mResult->seek( 0 );
		}
		wfProfileOut( __METHOD__ );
		return '';
	}

	public function formatRow( $row ) {
		return $this->mLogEventsList->logLine( $row );
	}

	public function getType() {
		return $this->types;
	}

	public function getUser() {
		return $this->user;
	}

	public function getPage() {
		return $this->title;
	}

	public function getPattern() {
		return $this->pattern;
	}

	public function getYear() {
		return $this->mYear;
	}

	public function getMonth() {
		return $this->mMonth;
	}

	public function getTagFilter() {
		return $this->mTagFilter;
	}

	public function doQuery() {
		// Workaround MySQL optimizer bug
		$this->mDb->setBigSelects();
		parent::doQuery();
		$this->mDb->setBigSelects( 'default' );
	}
}

