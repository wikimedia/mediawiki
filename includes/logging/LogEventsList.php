<?php
/**
 * Contain classes to list log entries
 *
 * Copyright © 2004 Brion Vibber <brion@pobox.com>, 2008 Aaron Schulz
 * https://www.mediawiki.org/
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

class LogEventsList extends ContextSource {
	const NO_ACTION_LINK = 1;
	const NO_EXTRA_USER_LINKS = 2;
	const USE_REVDEL_CHECKBOXES = 4;

	public $flags;

	/**
	 * @var array
	 */
	protected $mDefaultQuery;

	/**
	 * Constructor.
	 * The first two parameters used to be $skin and $out, but now only a context
	 * is needed, that's why there's a second unused parameter.
	 *
	 * @param IContextSource|Skin $context Context to use; formerly it was
	 *   a Skin object. Use of Skin is deprecated.
	 * @param null $unused Unused; used to be an OutputPage object.
	 * @param int $flags Can be a combination of self::NO_ACTION_LINK,
	 *   self::NO_EXTRA_USER_LINKS or self::USE_REVDEL_CHECKBOXES.
	 */
	public function __construct( $context, $unused = null, $flags = 0 ) {
		if ( $context instanceof IContextSource ) {
			$this->setContext( $context );
		} else {
			// Old parameters, $context should be a Skin object
			$this->setContext( $context->getContext() );
		}

		$this->flags = $flags;
	}

	/**
	 * Deprecated alias for getTitle(); do not use.
	 *
	 * @deprecated since 1.20; use getTitle() instead.
	 * @return Title
	 */
	public function getDisplayTitle() {
		wfDeprecated( __METHOD__, '1.20' );
		return $this->getTitle();
	}

	/**
	 * Show options for the log list
	 *
	 * @param array|string $types
	 * @param string $user
	 * @param string $page
	 * @param string $pattern
	 * @param int $year Year
	 * @param int $month Month
	 * @param array $filter
	 * @param string $tagFilter Tag to select by default
	 */
	public function showOptions( $types = array(), $user = '', $page = '', $pattern = '', $year = 0,
		$month = 0, $filter = null, $tagFilter = ''
	) {
		global $wgScript, $wgMiserMode;

		$title = SpecialPage::getTitleFor( 'Log' );

		// For B/C, we take strings, but make sure they are converted...
		$types = ( $types === '' ) ? array() : (array)$types;

		$tagSelector = ChangeTags::buildTagFilterSelector( $tagFilter );

		$html = Html::hidden( 'title', $title->getPrefixedDBkey() );

		// Basic selectors
		$html .= $this->getTypeMenu( $types ) . "\n";
		$html .= $this->getUserInput( $user ) . "\n";
		$html .= $this->getTitleInput( $page ) . "\n";
		$html .= $this->getExtraInputs( $types ) . "\n";

		// Title pattern, if allowed
		if ( !$wgMiserMode ) {
			$html .= $this->getTitlePattern( $pattern ) . "\n";
		}

		// date menu
		$html .= Xml::tags( 'p', null, Xml::dateMenu( (int)$year, (int)$month ) );

		// Tag filter
		if ( $tagSelector ) {
			$html .= Xml::tags( 'p', null, implode( '&#160;', $tagSelector ) );
		}

		// Filter links
		if ( $filter ) {
			$html .= Xml::tags( 'p', null, $this->getFilterLinks( $filter ) );
		}

		// Submit button
		$html .= Xml::submitButton( $this->msg( 'allpagessubmit' )->text() );

		// Fieldset
		$html = Xml::fieldset( $this->msg( 'log' )->text(), $html );

		// Form wrapping
		$html = Xml::tags( 'form', array( 'action' => $wgScript, 'method' => 'get' ), $html );

		$this->getOutput()->addHTML( $html );
	}

	/**
	 * @param array $filter
	 * @return string Formatted HTML
	 */
	private function getFilterLinks( $filter ) {
		// show/hide links
		$messages = array( $this->msg( 'show' )->escaped(), $this->msg( 'hide' )->escaped() );
		// Option value -> message mapping
		$links = array();
		$hiddens = ''; // keep track for "go" button
		foreach ( $filter as $type => $val ) {
			// Should the below assignment be outside the foreach?
			// Then it would have to be copied. Not certain what is more expensive.
			$query = $this->getDefaultQuery();
			$queryKey = "hide_{$type}_log";

			$hideVal = 1 - intval( $val );
			$query[$queryKey] = $hideVal;

			$link = Linker::linkKnown(
				$this->getTitle(),
				$messages[$hideVal],
				array(),
				$query
			);

			// Message: log-show-hide-patrol
			$links[$type] = $this->msg( "log-show-hide-{$type}" )->rawParams( $link )->escaped();
			$hiddens .= Html::hidden( "hide_{$type}_log", $val ) . "\n";
		}

		// Build links
		return '<small>' . $this->getLanguage()->pipeList( $links ) . '</small>' . $hiddens;
	}

	private function getDefaultQuery() {
		if ( !isset( $this->mDefaultQuery ) ) {
			$this->mDefaultQuery = $this->getRequest()->getQueryValues();
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
	 * @param array $queryTypes
	 * @return string Formatted HTML
	 */
	private function getTypeMenu( $queryTypes ) {
		$queryType = count( $queryTypes ) == 1 ? $queryTypes[0] : '';
		$selector = $this->getTypeSelector();
		$selector->setDefault( $queryType );

		return $selector->getHtml();
	}

	/**
	 * Returns log page selector.
	 * @return XmlSelect
	 * @since 1.19
	 */
	public function getTypeSelector() {
		$typesByName = array(); // Temporary array
		// First pass to load the log names
		foreach ( LogPage::validTypes() as $type ) {
			$page = new LogPage( $type );
			$restriction = $page->getRestriction();
			if ( $this->getUser()->isAllowed( $restriction ) ) {
				$typesByName[$type] = $page->getName()->text();
			}
		}

		// Second pass to sort by name
		asort( $typesByName );

		// Always put "All public logs" on top
		$public = $typesByName[''];
		unset( $typesByName[''] );
		$typesByName = array( '' => $public ) + $typesByName;

		$select = new XmlSelect( 'type' );
		foreach ( $typesByName as $type => $name ) {
			$select->addOption( $name, $type );
		}

		return $select;
	}

	/**
	 * @param string $user
	 * @return string Formatted HTML
	 */
	private function getUserInput( $user ) {
		$label = Xml::inputLabel(
			$this->msg( 'specialloguserlabel' )->text(),
			'user',
			'mw-log-user',
			15,
			$user
		);

		return '<span style="white-space: nowrap">' . $label . '</span>';
	}

	/**
	 * @param string $title
	 * @return string Formatted HTML
	 */
	private function getTitleInput( $title ) {
		$label = Xml::inputLabel(
			$this->msg( 'speciallogtitlelabel' )->text(),
			'page',
			'mw-log-page',
			20,
			$title
		);

		return '<span style="white-space: nowrap">' . $label .	'</span>';
	}

	/**
	 * @param string $pattern
	 * @return string Checkbox
	 */
	private function getTitlePattern( $pattern ) {
		return '<span style="white-space: nowrap">' .
			Xml::checkLabel( $this->msg( 'log-title-wildcard' )->text(), 'pattern', 'pattern', $pattern ) .
			'</span>';
	}

	/**
	 * @param array $types
	 * @return string
	 */
	private function getExtraInputs( $types ) {
		$offender = $this->getRequest()->getVal( 'offender' );
		$user = User::newFromName( $offender, false );
		if ( !$user || ( $user->getId() == 0 && !IP::isIPAddress( $offender ) ) ) {
			$offender = ''; // Blank field if invalid
		}
		if ( count( $types ) == 1 && $types[0] == 'suppress' ) {
			return Xml::inputLabel( $this->msg( 'revdelete-offender' )->text(), 'offender',
				'mw-log-offender', 20, $offender );
		}

		return '';
	}

	/**
	 * @return string
	 */
	public function beginLogEventsList() {
		return "<ul>\n";
	}

	/**
	 * @return string
	 */
	public function endLogEventsList() {
		return "</ul>\n";
	}

	/**
	 * @param stdClass $row A single row from the result set
	 * @return string Formatted HTML list item
	 */
	public function logLine( $row ) {
		$entry = DatabaseLogEntry::newFromRow( $row );
		$formatter = LogFormatter::newFromEntry( $entry );
		$formatter->setContext( $this->getContext() );
		$formatter->setShowUserToolLinks( !( $this->flags & self::NO_EXTRA_USER_LINKS ) );

		$time = htmlspecialchars( $this->getLanguage()->userTimeAndDate(
			$entry->getTimestamp(), $this->getUser() ) );

		$action = $formatter->getActionText();

		if ( $this->flags & self::NO_ACTION_LINK ) {
			$revert = '';
		} else {
			$revert = $formatter->getActionLinks();
			if ( $revert != '' ) {
				$revert = '<span class="mw-logevent-actionlink">' . $revert . '</span>';
			}
		}

		$comment = $formatter->getComment();

		// Some user can hide log items and have review links
		$del = $this->getShowHideLinks( $row );

		// Any tags...
		list( $tagDisplay, $newClasses ) = ChangeTags::formatSummaryRow( $row->ts_tags, 'logevent' );
		$classes = array_merge(
			array( 'mw-logline-' . $entry->getType() ),
			$newClasses
		);

		return Html::rawElement( 'li', array( 'class' => $classes ),
			"$del $time $action $comment $revert $tagDisplay" ) . "\n";
	}

	/**
	 * @param stdClass $row Row
	 * @return string
	 */
	private function getShowHideLinks( $row ) {
		// We don't want to see the links and
		// no one can hide items from the suppress log.
		if ( ( $this->flags == self::NO_ACTION_LINK )
			|| $row->log_type == 'suppress'
		) {
			return '';
		}
		$del = '';
		$user = $this->getUser();
		// Don't show useless checkbox to people who cannot hide log entries
		if ( $user->isAllowed( 'deletedhistory' ) ) {
			$canHide = $user->isAllowed( 'deletelogentry' );
			$canViewSuppressedOnly = $user->isAllowed( 'viewsuppressed' ) &&
				!$user->isAllowed( 'suppressrevision' );
			$entryIsSuppressed = self::isDeleted( $row, LogPage::DELETED_RESTRICTED );
			$canViewThisSuppressedEntry = $canViewSuppressedOnly && $entryIsSuppressed;
			if ( $row->log_deleted || $canHide ) {
				// Show checkboxes instead of links.
				if ( $canHide && $this->flags & self::USE_REVDEL_CHECKBOXES && !$canViewThisSuppressedEntry ) {
					// If event was hidden from sysops
					if ( !self::userCan( $row, LogPage::DELETED_RESTRICTED, $user ) ) {
						$del = Xml::check( 'deleterevisions', false, array( 'disabled' => 'disabled' ) );
					} else {
						$del = Xml::check(
							'showhiderevisions',
							false,
							array( 'name' => 'ids[' . $row->log_id . ']' )
						);
					}
				} else {
					// If event was hidden from sysops
					if ( !self::userCan( $row, LogPage::DELETED_RESTRICTED, $user ) ) {
						$del = Linker::revDeleteLinkDisabled( $canHide );
					} else {
						$query = array(
							'target' => SpecialPage::getTitleFor( 'Log', $row->log_type )->getPrefixedDBkey(),
							'type' => 'logging',
							'ids' => $row->log_id,
						);
						$del = Linker::revDeleteLink(
							$query,
							$entryIsSuppressed,
							$canHide && !$canViewThisSuppressedEntry
						);
					}
				}
			}
		}

		return $del;
	}

	/**
	 * @param stdClass $row Row
	 * @param string|array $type
	 * @param string|array $action
	 * @param string $right
	 * @return bool
	 */
	public static function typeAction( $row, $type, $action, $right = '' ) {
		$match = is_array( $type ) ?
			in_array( $row->log_type, $type ) : $row->log_type == $type;
		if ( $match ) {
			$match = is_array( $action ) ?
				in_array( $row->log_action, $action ) : $row->log_action == $action;
			if ( $match && $right ) {
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
	 * @param stdClass $row Row
	 * @param int $field
	 * @param User $user User to check, or null to use $wgUser
	 * @return bool
	 */
	public static function userCan( $row, $field, User $user = null ) {
		return self::userCanBitfield( $row->log_deleted, $field, $user );
	}

	/**
	 * Determine if the current user is allowed to view a particular
	 * field of this log row, if it's marked as deleted.
	 *
	 * @param int $bitfield Current field
	 * @param int $field
	 * @param User $user User to check, or null to use $wgUser
	 * @return bool
	 */
	public static function userCanBitfield( $bitfield, $field, User $user = null ) {
		if ( $bitfield & $field ) {
			if ( $user === null ) {
				global $wgUser;
				$user = $wgUser;
			}
			if ( $bitfield & LogPage::DELETED_RESTRICTED ) {
				$permissions = array( 'suppressrevision', 'viewsuppressed' );
			} else {
				$permissions = array( 'deletedhistory' );
			}
			$permissionlist = implode( ', ', $permissions );
			wfDebug( "Checking for $permissionlist due to $field match on $bitfield\n" );
			return call_user_func_array( array( $user, 'isAllowedAny' ), $permissions );
		}
		return true;
	}

	/**
	 * @param stdClass $row Row
	 * @param int $field One of DELETED_* bitfield constants
	 * @return bool
	 */
	public static function isDeleted( $row, $field ) {
		return ( $row->log_deleted & $field ) == $field;
	}

	/**
	 * Show log extract. Either with text and a box (set $msgKey) or without (don't set $msgKey)
	 *
	 * @param OutputPage|string $out By-reference
	 * @param string|array $types Log types to show
	 * @param string|Title $page The page title to show log entries for
	 * @param string $user The user who made the log entries
	 * @param array $param Associative Array with the following additional options:
	 * - lim Integer Limit of items to show, default is 50
	 * - conds Array Extra conditions for the query (e.g. "log_action != 'revision'")
	 * - showIfEmpty boolean Set to false if you don't want any output in case the loglist is empty
	 *   if set to true (default), "No matching items in log" is displayed if loglist is empty
	 * - msgKey Array If you want a nice box with a message, set this to the key of the message.
	 *   First element is the message key, additional optional elements are parameters for the key
	 *   that are processed with wfMessage
	 * - offset Set to overwrite offset parameter in WebRequest
	 *   set to '' to unset offset
	 * - wrap String Wrap the message in html (usually something like "<div ...>$1</div>").
	 * - flags Integer display flags (NO_ACTION_LINK,NO_EXTRA_USER_LINKS)
	 * - useRequestParams boolean Set true to use Pager-related parameters in the WebRequest
	 * - useMaster boolean Use master DB
	 * @return int Number of total log items (not limited by $lim)
	 */
	public static function showLogExtract(
		&$out, $types = array(), $page = '', $user = '', $param = array()
	) {
		$defaultParameters = array(
			'lim' => 25,
			'conds' => array(),
			'showIfEmpty' => true,
			'msgKey' => array( '' ),
			'wrap' => "$1",
			'flags' => 0,
			'useRequestParams' => false,
			'useMaster' => false,
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
		$useRequestParams = $param['useRequestParams'];
		if ( !is_array( $msgKey ) ) {
			$msgKey = array( $msgKey );
		}

		if ( $out instanceof OutputPage ) {
			$context = $out->getContext();
		} else {
			$context = RequestContext::getMain();
		}

		# Insert list of top 50 (or top $lim) items
		$loglist = new LogEventsList( $context, null, $flags );
		$pager = new LogPager( $loglist, $types, $user, $page, '', $conds );
		if ( !$useRequestParams ) {
			# Reset vars that may have been taken from the request
			$pager->mLimit = 50;
			$pager->mDefaultLimit = 50;
			$pager->mOffset = "";
			$pager->mIsBackwards = false;
		}

		if ( $param['useMaster'] ) {
			$pager->mDb = wfGetDB( DB_MASTER );
		}
		if ( isset( $param['offset'] ) ) { # Tell pager to ignore WebRequest offset
			$pager->setOffset( $param['offset'] );
		}

		if ( $lim > 0 ) {
			$pager->mLimit = $lim;
		}

		$logBody = $pager->getBody();
		$s = '';

		if ( $logBody ) {
			if ( $msgKey[0] ) {
				$dir = $context->getLanguage()->getDir();
				$lang = $context->getLanguage()->getCode();

				$s = Xml::openElement( 'div', array(
					'class' => "mw-warning-with-logexcerpt mw-content-$dir",
					'dir' => $dir,
					'lang' => $lang,
				) );

				if ( count( $msgKey ) == 1 ) {
					$s .= $context->msg( $msgKey[0] )->parseAsBlock();
				} else { // Process additional arguments
					$args = $msgKey;
					array_shift( $args );
					$s .= $context->msg( $msgKey[0], $args )->parseAsBlock();
				}
			}
			$s .= $loglist->beginLogEventsList() .
				$logBody .
				$loglist->endLogEventsList();
		} elseif ( $showIfEmpty ) {
			$s = Html::rawElement( 'div', array( 'class' => 'mw-warning-logempty' ),
				$context->msg( 'logempty' )->parse() );
		}

		if ( $pager->getNumRows() > $pager->mLimit ) { # Show "Full log" link
			$urlParam = array();
			if ( $page instanceof Title ) {
				$urlParam['page'] = $page->getPrefixedDBkey();
			} elseif ( $page != '' ) {
				$urlParam['page'] = $page;
			}

			if ( $user != '' ) {
				$urlParam['user'] = $user;
			}

			if ( !is_array( $types ) ) { # Make it an array, if it isn't
				$types = array( $types );
			}

			# If there is exactly one log type, we can link to Special:Log?type=foo
			if ( count( $types ) == 1 ) {
				$urlParam['type'] = $types[0];
			}

			$s .= Linker::link(
				SpecialPage::getTitleFor( 'Log' ),
				$context->msg( 'log-fulllog' )->escaped(),
				array(),
				$urlParam
			);
		}

		if ( $logBody && $msgKey[0] ) {
			$s .= '</div>';
		}

		if ( $wrap != '' ) { // Wrap message in html
			$s = str_replace( '$1', $s, $wrap );
		}

		/* hook can return false, if we don't want the message to be emitted (Wikia BugId:7093) */
		if ( wfRunHooks( 'LogEventsListShowLogExtract', array( &$s, $types, $page, $user, $param ) ) ) {
			// $out can be either an OutputPage object or a String-by-reference
			if ( $out instanceof OutputPage ) {
				$out->addHTML( $s );
			} else {
				$out = $s;
			}
		}

		return $pager->getNumRows();
	}

	/**
	 * SQL clause to skip forbidden log types for this user
	 *
	 * @param DatabaseBase $db
	 * @param string $audience Public/user
	 * @param User $user User to check, or null to use $wgUser
	 * @return string|bool String on success, false on failure.
	 */
	public static function getExcludeClause( $db, $audience = 'public', User $user = null ) {
		global $wgLogRestrictions;

		if ( $audience != 'public' && $user === null ) {
			global $wgUser;
			$user = $wgUser;
		}

		// Reset the array, clears extra "where" clauses when $par is used
		$hiddenLogs = array();

		// Don't show private logs to unprivileged users
		foreach ( $wgLogRestrictions as $logType => $right ) {
			if ( $audience == 'public' || !$user->isAllowed( $right ) ) {
				$hiddenLogs[] = $logType;
			}
		}
		if ( count( $hiddenLogs ) == 1 ) {
			return 'log_type != ' . $db->addQuotes( $hiddenLogs[0] );
		} elseif ( $hiddenLogs ) {
			return 'log_type NOT IN (' . $db->makeList( $hiddenLogs ) . ')';
		}

		return false;
	}
}
