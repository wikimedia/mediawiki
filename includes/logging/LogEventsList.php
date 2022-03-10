<?php
/**
 * Contain classes to list log entries
 *
 * Copyright © 2004 Brion Vibber <brion@pobox.com>
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

use MediaWiki\HookContainer\HookRunner;
use MediaWiki\Linker\LinkRenderer;
use MediaWiki\MediaWikiServices;
use MediaWiki\Page\PageReference;
use MediaWiki\Permissions\Authority;
use Wikimedia\Rdbms\IDatabase;

class LogEventsList extends ContextSource {
	public const NO_ACTION_LINK = 1;
	public const NO_EXTRA_USER_LINKS = 2;
	public const USE_CHECKBOXES = 4;

	public $flags;

	/**
	 * @var bool
	 */
	protected $showTagEditUI;

	/**
	 * @var array
	 */
	protected $allowedActions = null;

	/**
	 * @var LinkRenderer|null
	 */
	private $linkRenderer;

	/** @var HookRunner */
	private $hookRunner;

	/**
	 * @stable to call. As of the 1.36 release, there is no factory for this class, and it is
	 *         instantiated directly by several extensions. The constructor needs to retain
	 *         backwards compatibility until a factory has been introduced.
	 *
	 * @param IContextSource $context
	 * @param LinkRenderer|null $linkRenderer
	 * @param int $flags Can be a combination of self::NO_ACTION_LINK,
	 *   self::NO_EXTRA_USER_LINKS or self::USE_CHECKBOXES.
	 */
	public function __construct( $context, $linkRenderer = null, $flags = 0 ) {
		$this->setContext( $context );
		$this->flags = $flags;
		$this->showTagEditUI = ChangeTags::showTagEditingUI( $this->getAuthority() );
		if ( $linkRenderer instanceof LinkRenderer ) {
			$this->linkRenderer = $linkRenderer;
		}
		$this->hookRunner = Hooks::runner();
	}

	/**
	 * @since 1.30
	 * @return LinkRenderer
	 */
	protected function getLinkRenderer() {
		if ( $this->linkRenderer !== null ) {
			return $this->linkRenderer;
		} else {
			return MediaWikiServices::getInstance()->getLinkRenderer();
		}
	}

	/**
	 * Show options for the log list
	 *
	 * @param array|string $types
	 * @param string $user
	 * @param string|PageReference $page
	 * @param bool $pattern
	 * @param int|string $year Use 0 to start with no year preselected.
	 * @param int|string $month A month in the 1..12 range. Use 0 to start with no month
	 *  preselected.
	 * @param int|string $day A day in the 1..31 range. Use 0 to start with no month
	 *  preselected.
	 * @param array|null $filter
	 * @param string $tagFilter Tag to select by default
	 * @param string|null $action
	 */
	public function showOptions( $types = [], $user = '', $page = '', $pattern = false, $year = 0,
		$month = 0, $day = 0, $filter = null, $tagFilter = '', $action = null
	) {
		// For B/C, we take strings, but make sure they are converted...
		$types = ( $types === '' ) ? [] : (array)$types;

		$formDescriptor = [];

		// Basic selectors
		$formDescriptor['type'] = $this->getTypeMenuDesc( $types );
		$formDescriptor['user'] = $this->getUserInputDesc( $user );
		$formDescriptor['page'] = $this->getTitleInputDesc( $page );

		// Add extra inputs if any
		// This could either be a form descriptor array or a string with raw HTML.
		// We need it to work in both cases and show a deprecation warning if it
		// is a string. See T199495.
		$extraInputsDescriptor = $this->getExtraInputsDesc( $types );
		if (
			is_array( $extraInputsDescriptor ) &&
			!empty( $extraInputsDescriptor )
		) {
			$formDescriptor[ 'extra' ] = $extraInputsDescriptor;
		} elseif (
			is_string( $extraInputsDescriptor ) &&
			$extraInputsDescriptor !== ''
		) {
			// We'll add this to the footer of the form later
			$extraInputsString = $extraInputsDescriptor;
			wfDeprecated( '$input in LogEventsListGetExtraInputs hook', '1.32' );
		}

		// Title pattern, if allowed
		if ( !$this->getConfig()->get( 'MiserMode' ) ) {
			$formDescriptor['pattern'] = $this->getTitlePatternDesc( $pattern );
		}

		// Date menu
		$formDescriptor['date'] = [
			'type' => 'date',
			'label-message' => 'date',
			'default' => $year && $month && $day ? sprintf( "%04d-%02d-%02d", $year, $month, $day ) : '',
		];

		// Tag filter
		$formDescriptor['tagfilter'] = [
			'type' => 'tagfilter',
			'name' => 'tagfilter',
			'label-message' => 'tag-filter',
		];

		// Filter links
		if ( $filter ) {
			$formDescriptor['filters'] = $this->getFiltersDesc( $filter );
		}

		// Action filter
		if (
			$action !== null &&
			$this->allowedActions !== null &&
			count( $this->allowedActions ) > 0
		) {
			$formDescriptor['subtype'] = $this->getActionSelectorDesc( $types, $action );
		}

		$context = new DerivativeContext( $this->getContext() );
		$context->setTitle( SpecialPage::getTitleFor( 'Log' ) ); // Remove subpage
		$htmlForm = HTMLForm::factory( 'ooui', $formDescriptor, $context );
		$htmlForm
			->setSubmitTextMsg( 'logeventslist-submit' )
			->setMethod( 'get' )
			->setWrapperLegendMsg( 'log' );

		// TODO This will should be removed at some point. See T199495.
		if ( isset( $extraInputsString ) ) {
			$htmlForm->addFooterText( Html::rawElement(
				'div',
				[],
				$extraInputsString
			) );
		}

		$htmlForm->prepareForm()->displayForm( false );
	}

	/**
	 * @param array $filter
	 * @return array Form descriptor
	 */
	private function getFiltersDesc( $filter ) {
		$optionsMsg = [];
		$default = [];
		foreach ( $filter as $type => $val ) {
			$optionsMsg["logeventslist-{$type}-log"] = $type;

			if ( $val === false ) {
				$default[] = $type;
			}
		}
		return [
			'class' => HTMLMultiSelectField::class,
			'label-message' => 'logeventslist-more-filters',
			'flatlist' => true,
			'options-messages' => $optionsMsg,
			'default' => $default,
		];
	}

	/**
	 * @param array $queryTypes
	 * @return array Form descriptor
	 */
	private function getTypeMenuDesc( $queryTypes ) {
		$queryType = count( $queryTypes ) == 1 ? $queryTypes[0] : '';

		$typesByName = []; // Temporary array
		// First pass to load the log names
		foreach ( LogPage::validTypes() as $type ) {
			$page = new LogPage( $type );
			$restriction = $page->getRestriction();
			if ( $this->getAuthority()->isAllowed( $restriction ) ) {
				$typesByName[$type] = $page->getName()->text();
			}
		}

		// Second pass to sort by name
		asort( $typesByName );

		// Always put "All public logs" on top
		$public = $typesByName[''];
		unset( $typesByName[''] );
		$typesByName = [ '' => $public ] + $typesByName;

		return [
			'class' => HTMLSelectField::class,
			'name' => 'type',
			'options' => array_flip( $typesByName ),
			'default' => $queryType,
		];
	}

	/**
	 * @param string $user
	 * @return array Form descriptor
	 */
	private function getUserInputDesc( $user ) {
		return [
			'class' => HTMLUserTextField::class,
			'label-message' => 'specialloguserlabel',
			'name' => 'user',
			'default' => $user,
		];
	}

	/**
	 * @param string|PageReference $page
	 * @return array Form descriptor
	 */
	private function getTitleInputDesc( $page ) {
		return [
			'class' => HTMLTitleTextField::class,
			'label-message' => 'speciallogtitlelabel',
			'name' => 'page',
			'required' => false
		];
	}

	/**
	 * @param bool $pattern
	 * @return array Form descriptor
	 */
	private function getTitlePatternDesc( $pattern ) {
		return [
			'type' => 'check',
			'label-message' => 'log-title-wildcard',
			'name' => 'pattern',
		];
	}

	/**
	 * @param array $types
	 * @return array|string Form descriptor or string with HTML
	 */
	private function getExtraInputsDesc( $types ) {
		if ( count( $types ) == 1 ) {
			if ( $types[0] == 'suppress' ) {
				return [
					'type' => 'text',
					'label-message' => 'revdelete-offender',
					'name' => 'offender',
				];
			} else {
				// Allow extensions to add their own extra inputs
				// This could be an array or string. See T199495.
				$input = ''; // Deprecated
				$formDescriptor = [];
				$this->hookRunner->onLogEventsListGetExtraInputs( $types[0], $this, $input, $formDescriptor );

				return empty( $formDescriptor ) ? $input : $formDescriptor;
			}
		}

		return [];
	}

	/**
	 * Drop down menu for selection of actions that can be used to filter the log
	 * @param array $types
	 * @param string $action
	 * @return array Form descriptor
	 */
	private function getActionSelectorDesc( $types, $action ) {
		$actionOptions = [];
		$actionOptions[ 'log-action-filter-all' ] = '';

		foreach ( $this->allowedActions as $value ) {
			$msgKey = 'log-action-filter-' . $types[0] . '-' . $value;
			$actionOptions[ $msgKey ] = $value;
		}

		return [
			'class' => HTMLSelectField::class,
			'name' => 'subtype',
			'options-messages' => $actionOptions,
			'default' => $action,
			'label' => $this->msg( 'log-action-filter-' . $types[0] )->text(),
		];
	}

	/**
	 * Sets the action types allowed for log filtering
	 * To one action type may correspond several log_actions
	 * @param array $actions
	 * @since 1.27
	 */
	public function setAllowedActions( $actions ) {
		$this->allowedActions = $actions;
	}

	/**
	 * @return string
	 */
	public function beginLogEventsList() {
		return "<ul class='mw-logevent-loglines'>\n";
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
		$formatter->setLinkRenderer( $this->getLinkRenderer() );
		$formatter->setShowUserToolLinks( !( $this->flags & self::NO_EXTRA_USER_LINKS ) );

		$time = $this->getLanguage()->userTimeAndDate(
			$entry->getTimestamp(),
			$this->getUser()
		);
		// Link the time text to the specific log entry, see T207562
		$timeLink = $this->getLinkRenderer()->makeKnownLink(
			SpecialPage::getTitleValueFor( 'Log' ),
			$time,
			[],
			[ 'logid' => $entry->getId() ]
		);

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
		list( $tagDisplay, $newClasses ) = ChangeTags::formatSummaryRow(
			$row->ts_tags,
			'logevent',
			$this->getContext()
		);
		$classes = array_merge(
			[ 'mw-logline-' . $entry->getType() ],
			$newClasses
		);
		$attribs = [
			'data-mw-logid' => $entry->getId(),
			'data-mw-logaction' => $entry->getFullType(),
		];
		$ret = "$del $timeLink $action $comment $revert $tagDisplay";

		// Let extensions add data
		$this->hookRunner->onLogEventsListLineEnding( $this, $ret, $entry, $classes, $attribs );
		$attribs = array_filter( $attribs,
			[ Sanitizer::class, 'isReservedDataAttribute' ],
			ARRAY_FILTER_USE_KEY
		);
		$attribs['class'] = $classes;

		return Html::rawElement( 'li', $attribs, $ret ) . "\n";
	}

	/**
	 * @param stdClass $row
	 * @return string
	 */
	private function getShowHideLinks( $row ) {
		// We don't want to see the links and
		if ( $this->flags == self::NO_ACTION_LINK ) {
			return '';
		}

		$user = $this->getUser();

		// If change tag editing is available to this user, return the checkbox
		if ( $this->flags & self::USE_CHECKBOXES && $this->showTagEditUI ) {
			return Xml::check(
				'showhiderevisions',
				false,
				[ 'name' => 'ids[' . $row->log_id . ']' ]
			);
		}

		// no one can hide items from the suppress log.
		if ( $row->log_type == 'suppress' ) {
			return '';
		}

		$del = '';
		// Don't show useless checkbox to people who cannot hide log entries
		if ( $this->getAuthority()->isAllowed( 'deletedhistory' ) ) {
			$canHide = $this->getAuthority()->isAllowed( 'deletelogentry' );
			$canViewSuppressedOnly = $this->getAuthority()->isAllowed( 'viewsuppressed' ) &&
				!$this->getAuthority()->isAllowed( 'suppressrevision' );
			$entryIsSuppressed = self::isDeleted( $row, LogPage::DELETED_RESTRICTED );
			$canViewThisSuppressedEntry = $canViewSuppressedOnly && $entryIsSuppressed;
			if ( $row->log_deleted || $canHide ) {
				// Show checkboxes instead of links.
				if ( $canHide && $this->flags & self::USE_CHECKBOXES && !$canViewThisSuppressedEntry ) {
					// If event was hidden from sysops
					if ( !self::userCan( $row, LogPage::DELETED_RESTRICTED, $user ) ) {
						$del = Xml::check( 'deleterevisions', false, [ 'disabled' => 'disabled' ] );
					} else {
						$del = Xml::check(
							'showhiderevisions',
							false,
							[ 'name' => 'ids[' . $row->log_id . ']' ]
						);
					}
				} else {
					// If event was hidden from sysops
					if ( !self::userCan( $row, LogPage::DELETED_RESTRICTED, $user ) ) {
						$del = Linker::revDeleteLinkDisabled( $canHide );
					} else {
						$query = [
							'target' => SpecialPage::getTitleFor( 'Log', $row->log_type )->getPrefixedDBkey(),
							'type' => 'logging',
							'ids' => $row->log_id,
						];
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
	 * @param stdClass $row
	 * @param string|array $type
	 * @param string|array $action
	 * @return bool
	 */
	public static function typeAction( $row, $type, $action ) {
		$match = is_array( $type ) ?
			in_array( $row->log_type, $type ) : $row->log_type == $type;
		if ( $match ) {
			$match = is_array( $action ) ?
				in_array( $row->log_action, $action ) : $row->log_action == $action;
		}

		return $match;
	}

	/**
	 * Determine if the current user is allowed to view a particular
	 * field of this log row, if it's marked as deleted and/or restricted log type.
	 *
	 * @param stdClass $row
	 * @param int $field
	 * @param Authority $performer User to check
	 * @return bool
	 */
	public static function userCan( $row, $field, Authority $performer ) {
		return self::userCanBitfield( $row->log_deleted, $field, $performer ) &&
			self::userCanViewLogType( $row->log_type, $performer );
	}

	/**
	 * Determine if the current user is allowed to view a particular
	 * field of this log row, if it's marked as deleted.
	 *
	 * @param int $bitfield Current field
	 * @param int $field
	 * @param Authority $performer User to check
	 * @return bool
	 */
	public static function userCanBitfield( $bitfield, $field, Authority $performer ) {
		if ( $bitfield & $field ) {
			if ( $bitfield & LogPage::DELETED_RESTRICTED ) {
				return $performer->isAllowedAny( 'suppressrevision', 'viewsuppressed' );
			} else {
				return $performer->isAllowed( 'deletedhistory' );
			}
		}
		return true;
	}

	/**
	 * Determine if the current user is allowed to view a particular
	 * field of this log row, if it's marked as restricted log type.
	 *
	 * @param string $type
	 * @param Authority $performer User to check
	 * @return bool
	 */
	public static function userCanViewLogType( $type, Authority $performer ) {
		$logRestrictions = MediaWikiServices::getInstance()->getMainConfig()->get( 'LogRestrictions' );
		if ( isset( $logRestrictions[$type] ) && !$performer->isAllowed( $logRestrictions[$type] )
		) {
			return false;
		}
		return true;
	}

	/**
	 * @param stdClass $row
	 * @param int $field One of DELETED_* bitfield constants
	 * @return bool
	 */
	public static function isDeleted( $row, $field ) {
		return ( $row->log_deleted & $field ) == $field;
	}

	/**
	 * Show log extract. Either with text and a box (set $msgKey) or without (don't set $msgKey)
	 *
	 * @param OutputPage|string &$out
	 * @param string|array $types Log types to show
	 * @param string|PageReference $page The page title to show log entries for
	 * @param string $user The user who made the log entries
	 * @param array $param Associative Array with the following additional options:
	 * - lim Integer Limit of items to show, default is 50
	 * - conds Array Extra conditions for the query
	 *   (e.g. 'log_action != ' . $dbr->addQuotes( 'revision' ))
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
	 * - useMaster boolean Use primary DB
	 * - extraUrlParams array|bool Additional url parameters for "full log" link (if it is shown)
	 * @return int Number of total log items (not limited by $lim)
	 */
	public static function showLogExtract(
		&$out, $types = [], $page = '', $user = '', $param = []
	) {
		$defaultParameters = [
			'lim' => 25,
			'conds' => [],
			'showIfEmpty' => true,
			'msgKey' => [ '' ],
			'wrap' => "$1",
			'flags' => 0,
			'useRequestParams' => false,
			'useMaster' => false,
			'extraUrlParams' => false,
		];
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
		$extraUrlParams = $param['extraUrlParams'];

		$useRequestParams = $param['useRequestParams'];
		// @phan-suppress-next-line PhanRedundantCondition
		if ( !is_array( $msgKey ) ) {
			$msgKey = [ $msgKey ];
		}

		if ( $out instanceof OutputPage ) {
			$context = $out->getContext();
		} else {
			$context = RequestContext::getMain();
		}

		$services = MediaWikiServices::getInstance();
		// FIXME: Figure out how to inject this
		$linkRenderer = $services->getLinkRenderer();

		# Insert list of top 50 (or top $lim) items
		$loglist = new LogEventsList( $context, $linkRenderer, $flags );
		$pager = new LogPager(
			$loglist,
			$types,
			$user,
			$page,
			false,
			$conds,
			false,
			false,
			false,
			'',
			'',
			0,
			$services->getLinkBatchFactory(),
			$services->getDBLoadBalancer(),
			$services->getActorNormalization()
		);
		if ( !$useRequestParams ) {
			# Reset vars that may have been taken from the request
			$pager->mLimit = 50;
			$pager->mDefaultLimit = 50;
			$pager->mOffset = "";
			$pager->mIsBackwards = false;
		}

		if ( $param['useMaster'] ) {
			$pager->mDb = wfGetDB( DB_PRIMARY );
		}
		// @phan-suppress-next-line PhanImpossibleCondition
		if ( isset( $param['offset'] ) ) { # Tell pager to ignore WebRequest offset
			$pager->setOffset( $param['offset'] );
		}

		// @phan-suppress-next-line PhanSuspiciousValueComparison
		if ( $lim > 0 ) {
			$pager->mLimit = $lim;
		}
		// Fetch the log rows and build the HTML if needed
		$logBody = $pager->getBody();
		$numRows = $pager->getNumRows();

		$s = '';

		if ( $logBody ) {
			if ( $msgKey[0] ) {
				$msg = $context->msg( ...$msgKey );
				if ( $page instanceof PageReference ) {
					$msg->page( $page );
				}
				$s .= $msg->parseAsBlock();
			}
			$s .= $loglist->beginLogEventsList() .
				$logBody .
				$loglist->endLogEventsList();
			// add styles for change tags
			$context->getOutput()->addModuleStyles( 'mediawiki.interface.helpers.styles' );
		} elseif ( $showIfEmpty ) {
			$s = Html::rawElement( 'div', [ 'class' => 'mw-warning-logempty' ],
				$context->msg( 'logempty' )->parse() );
		}

		if ( $page instanceof PageReference ) {
			$titleFormatter = MediaWikiServices::getInstance()->getTitleFormatter();
			$pageName = $titleFormatter->getPrefixedDBkey( $page );
		} elseif ( $page != '' ) {
			$pageName = $page;
		} else {
			$pageName = null;
		}

		if ( $numRows > $pager->mLimit ) { # Show "Full log" link
			$urlParam = [];
			if ( $pageName ) {
				$urlParam['page'] = $pageName;
			}

			if ( $user != '' ) {
				$urlParam['user'] = $user;
			}

			if ( !is_array( $types ) ) { # Make it an array, if it isn't
				$types = [ $types ];
			}

			# If there is exactly one log type, we can link to Special:Log?type=foo
			if ( count( $types ) == 1 ) {
				$urlParam['type'] = $types[0];
			}

			// @phan-suppress-next-line PhanSuspiciousValueComparison
			if ( $extraUrlParams !== false ) {
				$urlParam = array_merge( $urlParam, $extraUrlParams );
			}

			$s .= $linkRenderer->makeKnownLink(
				SpecialPage::getTitleFor( 'Log' ),
				$context->msg( 'log-fulllog' )->text(),
				[],
				$urlParam
			);
		}

		if ( $logBody && $msgKey[0] ) {
			// TODO: The condition above is weird. Should this be done in any other cases?
			// Or is it always true in practice?

			// Mark as interface language (T60685)
			$dir = $context->getLanguage()->getDir();
			$lang = $context->getLanguage()->getHtmlCode();
			$s = Html::rawElement( 'div', [
				'class' => "mw-content-$dir",
				'dir' => $dir,
				'lang' => $lang,
			], $s );

			// Wrap in warning box
			$s = Html::warningBox(
				$s,
				'mw-warning-with-logexcerpt'
			);
		}

		// @phan-suppress-next-line PhanSuspiciousValueComparison
		if ( $wrap != '' ) { // Wrap message in html
			$s = str_replace( '$1', $s, $wrap );
		}

		/* hook can return false, if we don't want the message to be emitted (Wikia BugId:7093) */
		if ( Hooks::runner()->onLogEventsListShowLogExtract( $s, $types, $pageName, $user, $param ) ) {
			// $out can be either an OutputPage object or a String-by-reference
			if ( $out instanceof OutputPage ) {
				$out->addHTML( $s );
			} else {
				$out = $s;
			}
		}

		return $numRows;
	}

	/**
	 * SQL clause to skip forbidden log types for this user
	 *
	 * @param IDatabase $db
	 * @param string $audience Public/user
	 * @param Authority|null $performer User to check, required when audience isn't public
	 * @return string|bool String on success, false on failure.
	 * @throws InvalidArgumentException
	 */
	public static function getExcludeClause( $db, $audience = 'public', Authority $performer = null ) {
		$logRestrictions = MediaWikiServices::getInstance()->getMainConfig()->get( 'LogRestrictions' );

		if ( $audience != 'public' && $performer === null ) {
			throw new InvalidArgumentException(
				'A User object must be given when checking for a user audience.'
			);
		}

		// Reset the array, clears extra "where" clauses when $par is used
		$hiddenLogs = [];

		// Don't show private logs to unprivileged users
		foreach ( $logRestrictions as $logType => $right ) {
			if ( $audience == 'public' || !$performer->isAllowed( $right )
			) {
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
