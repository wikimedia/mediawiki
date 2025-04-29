<?php
/**
 * Contain classes to list log entries
 *
 * Copyright © 2004 Brooke Vibber <bvibber@wikimedia.org>
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

namespace MediaWiki\Logging;

use InvalidArgumentException;
use MediaWiki\Block\DatabaseBlockStore;
use MediaWiki\ChangeTags\ChangeTags;
use MediaWiki\Context\ContextSource;
use MediaWiki\Context\IContextSource;
use MediaWiki\Context\RequestContext;
use MediaWiki\HookContainer\HookRunner;
use MediaWiki\Html\Html;
use MediaWiki\HTMLForm\Field\HTMLMultiSelectField;
use MediaWiki\HTMLForm\Field\HTMLSelectField;
use MediaWiki\HTMLForm\Field\HTMLTitleTextField;
use MediaWiki\HTMLForm\Field\HTMLUserTextField;
use MediaWiki\HTMLForm\HTMLForm;
use MediaWiki\Linker\Linker;
use MediaWiki\Linker\LinkRenderer;
use MediaWiki\Logger\LoggerFactory;
use MediaWiki\MainConfigNames;
use MediaWiki\MediaWikiServices;
use MediaWiki\Output\OutputPage;
use MediaWiki\Page\PageReference;
use MediaWiki\Pager\LogPager;
use MediaWiki\Parser\Sanitizer;
use MediaWiki\Permissions\Authority;
use MediaWiki\SpecialPage\SpecialPage;
use MediaWiki\Status\Status;
use MediaWiki\Title\NamespaceInfo;
use MediaWiki\Title\Title;
use MediaWiki\User\UserIdentity;
use MessageLocalizer;
use stdClass;
use Wikimedia\MapCacheLRU\MapCacheLRU;

class LogEventsList extends ContextSource {
	public const NO_ACTION_LINK = 1;
	public const NO_EXTRA_USER_LINKS = 2;
	public const USE_CHECKBOXES = 4;

	/** @var int */
	public $flags;

	/**
	 * @var bool
	 */
	protected $showTagEditUI;

	/**
	 * @var LinkRenderer|null
	 */
	private $linkRenderer;

	/** @var HookRunner */
	private $hookRunner;

	private LogFormatterFactory $logFormatterFactory;

	/** @var MapCacheLRU */
	private $tagsCache;

	/**
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
		$services = MediaWikiServices::getInstance();
		$this->hookRunner = new HookRunner( $services->getHookContainer() );
		$this->logFormatterFactory = $services->getLogFormatterFactory();
		$this->tagsCache = new MapCacheLRU( 50 );
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
	 * @param string $type Log type
	 * @param int|string $year Use 0 to start with no year preselected.
	 * @param int|string $month A month in the 1..12 range. Use 0 to start with no month
	 *  preselected.
	 * @param int|string $day A day in the 1..31 range. Use 0 to start with no month
	 *  preselected.
	 * @return bool Whether the options are valid
	 */
	public function showOptions( $type = '', $year = 0, $month = 0, $day = 0 ) {
		$formDescriptor = [];

		// Basic selectors
		$formDescriptor['type'] = $this->getTypeMenuDesc();
		$formDescriptor['user'] = [
			'class' => HTMLUserTextField::class,
			'label-message' => 'specialloguserlabel',
			'name' => 'user',
			'ipallowed' => true,
			'iprange' => true,
			'external' => true,
		];
		$formDescriptor['page'] = [
			'class' => HTMLTitleTextField::class,
			'label-message' => 'speciallogtitlelabel',
			'name' => 'page',
			'required' => false,
		];

		// Title pattern, if allowed
		if ( !$this->getConfig()->get( MainConfigNames::MiserMode ) ) {
			$formDescriptor['pattern'] = [
				'type' => 'check',
				'label-message' => 'log-title-wildcard',
				'name' => 'pattern',
			];
		}

		// Add extra inputs if any
		$extraInputsDescriptor = $this->getExtraInputsDesc( $type );
		if ( $extraInputsDescriptor ) {
			$formDescriptor[ 'extra' ] = $extraInputsDescriptor;
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
		$formDescriptor['tagInvert'] = [
			'type' => 'check',
			'name' => 'tagInvert',
			'label-message' => 'invert',
			'hide-if' => [ '===', 'tagfilter', '' ],
		];

		// Filter checkboxes, when work on all logs
		if ( $type === '' ) {
			$formDescriptor['filters'] = $this->getFiltersDesc();
		}

		// Action filter
		$allowedActions = $this->getConfig()->get( MainConfigNames::ActionFilteredLogs );
		if ( isset( $allowedActions[$type] ) ) {
			$formDescriptor['subtype'] = $this->getActionSelectorDesc( $type, $allowedActions[$type] );
		}

		$htmlForm = HTMLForm::factory( 'ooui', $formDescriptor, $this->getContext() );
		$htmlForm
			->setTitle( SpecialPage::getTitleFor( 'Log' ) ) // Remove subpage
			->setSubmitTextMsg( 'logeventslist-submit' )
			->setMethod( 'GET' )
			->setWrapperLegendMsg( 'log' )
			->setFormIdentifier( 'logeventslist', true ) // T321154
			// Set callback for data validation and log type description.
			->setSubmitCallback( static function ( $formData, $form ) {
				$form->addPreHtml(
					( new LogPage( $formData['type'] ) )->getDescription()
						->setContext( $form->getContext() )->parseAsBlock()
				);
				return true;
			} );

		$result = $htmlForm->prepareForm()->trySubmit();
		$htmlForm->displayForm( $result );
		return $result === true || ( $result instanceof Status && $result->isGood() );
	}

	/**
	 * @return array Form descriptor
	 */
	private function getFiltersDesc() {
		$optionsMsg = [];
		$filters = $this->getConfig()->get( MainConfigNames::FilterLogTypes );
		foreach ( $filters as $type => $val ) {
			$optionsMsg["logeventslist-{$type}-log"] = $type;
		}
		return [
			'class' => HTMLMultiSelectField::class,
			'label-message' => 'logeventslist-more-filters',
			'flatlist' => true,
			'options-messages' => $optionsMsg,
			'default' => array_keys( array_intersect( $filters, [ false ] ) ),
		];
	}

	/**
	 * @return array Form descriptor
	 */
	private function getTypeMenuDesc() {
		$typesByName = [];
		// Load the log names
		foreach ( LogPage::validTypes() as $type ) {
			$page = new LogPage( $type );
			$pageText = $page->getName()->text();
			if ( in_array( $pageText, $typesByName ) ) {
				LoggerFactory::getInstance( 'translation-problem' )->error(
					'The log type {log_type_one} has the same translation as {log_type_two} for {lang}. ' .
					'{log_type_one} will not be displayed in the drop down menu on Special:Log.',
					[
						'log_type_one' => $type,
						'log_type_two' => array_search( $pageText, $typesByName ),
						'lang' => $this->getLanguage()->getCode(),
					]
				);
				continue;
			}
			if ( $this->getAuthority()->isAllowed( $page->getRestriction() ) ) {
				$typesByName[$type] = $pageText;
			}
		}

		asort( $typesByName );

		// Always put "All public logs" on top
		$public = $typesByName[''];
		unset( $typesByName[''] );
		$typesByName = [ '' => $public ] + $typesByName;

		return [
			'class' => HTMLSelectField::class,
			'name' => 'type',
			'options' => array_flip( $typesByName ),
		];
	}

	/**
	 * @param string $type
	 * @return array Form descriptor
	 */
	private function getExtraInputsDesc( $type ) {
		if ( $type === 'suppress' ) {
			return [
				'type' => 'text',
				'label-message' => 'revdelete-offender',
				'name' => 'offender',
			];
		} else {
			// Allow extensions to add an extra input into the descriptor array.
			$unused = ''; // Deprecated since 1.32, removed in 1.41
			$formDescriptor = [];
			$this->hookRunner->onLogEventsListGetExtraInputs( $type, $this, $unused, $formDescriptor );

			return $formDescriptor;
		}
	}

	/**
	 * Drop down menu for selection of actions that can be used to filter the log
	 * @param string $type
	 * @param array $actions
	 * @return array Form descriptor
	 */
	private function getActionSelectorDesc( $type, $actions ) {
		$actionOptions = [ 'log-action-filter-all' => '' ];

		foreach ( $actions as $value => $_ ) {
			$msgKey = "log-action-filter-$type-$value";
			$actionOptions[ $msgKey ] = $value;
		}

		return [
			'class' => HTMLSelectField::class,
			'name' => 'subtype',
			'options-messages' => $actionOptions,
			'label-message' => 'log-action-filter-' . $type,
		];
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
		$formatter = $this->logFormatterFactory->newFromEntry( $entry );
		$formatter->setContext( $this->getContext() );
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
		[ $tagDisplay, $newClasses ] = $this->tagsCache->getWithSetCallback(
			$this->tagsCache->makeKey(
				$row->ts_tags ?? '',
				$this->getUser()->getName(),
				$this->getLanguage()->getCode()
			),
			fn () => ChangeTags::formatSummaryRow(
				$row->ts_tags,
				'logevent',
				$this->getContext()
			)
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
		$ret .= Html::openElement( 'span', [ 'class' => 'mw-logevent-tool' ] );
		// FIXME: this hook assumes that callers will only append to $ret value.
		// In future this hook should be replaced with a new hook: LogTools that has a
		// hook interface consistent with DiffTools and HistoryTools.
		$this->hookRunner->onLogEventsListLineEnding( $this, $ret, $entry, $classes, $attribs );
		$attribs = array_filter( $attribs,
			[ Sanitizer::class, 'isReservedDataAttribute' ],
			ARRAY_FILTER_USE_KEY
		);
		$ret .= Html::closeElement( 'span' );
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

		// If change tag editing is available to this user, return the checkbox
		if ( $this->flags & self::USE_CHECKBOXES && $this->showTagEditUI ) {
			return Html::check( 'ids[' . $row->log_id . ']', false );
		}

		// no one can hide items from the suppress log.
		if ( $row->log_type == 'suppress' ) {
			return '';
		}

		$del = '';
		$authority = $this->getAuthority();
		// Don't show useless checkbox to people who cannot hide log entries
		if ( $authority->isAllowed( 'deletedhistory' ) ) {
			$canHide = $authority->isAllowed( 'deletelogentry' );
			$canViewSuppressedOnly = $authority->isAllowed( 'viewsuppressed' ) &&
				!$authority->isAllowed( 'suppressrevision' );
			$entryIsSuppressed = self::isDeleted( $row, LogPage::DELETED_RESTRICTED );
			$canViewThisSuppressedEntry = $canViewSuppressedOnly && $entryIsSuppressed;
			if ( $row->log_deleted || $canHide ) {
				// Show checkboxes instead of links.
				if ( $canHide && $this->flags & self::USE_CHECKBOXES && !$canViewThisSuppressedEntry ) {
					// If event was hidden from sysops
					if ( !self::userCan( $row, LogPage::DELETED_RESTRICTED, $authority ) ) {
						$del = Html::check( 'deleterevisions', false, [ 'disabled' => 'disabled' ] );
					} else {
						$del = Html::check( 'ids[' . $row->log_id . ']', false );
					}
				} else {
					// If event was hidden from sysops
					if ( !self::userCan( $row, LogPage::DELETED_RESTRICTED, $authority ) ) {
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
	 * @param int $field One of LogPage::DELETED_ACTION, ::DELETED_COMMENT, ::DELETED_USER, ::DELETED_RESTRICTED
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
	 * @param int $field One of LogPage::DELETED_ACTION, ::DELETED_COMMENT, ::DELETED_USER, ::DELETED_RESTRICTED
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
		$logRestrictions = MediaWikiServices::getInstance()->getMainConfig()->get( MainConfigNames::LogRestrictions );
		if ( isset( $logRestrictions[$type] ) && !$performer->isAllowed( $logRestrictions[$type] ) ) {
			return false;
		}
		return true;
	}

	/**
	 * @param stdClass $row
	 * @param int $field One of LogPage::DELETED_ACTION, ::DELETED_COMMENT, ::DELETED_USER, ::DELETED_RESTRICTED
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
	 *   (e.g. $dbr->expr( 'log_action', '!=', 'revision' ))
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
	 * - footerHtmlItems: string[] Extra HTML to add as horizontal list items after the
	 *   end of the log
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
			'footerHtmlItems' => []
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

		// ???
		// @phan-suppress-next-line PhanRedundantCondition
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
			$services->getActorNormalization(),
			$services->getLogFormatterFactory()
		);
		if ( !$useRequestParams ) {
			# Reset vars that may have been taken from the request
			$pager->mLimit = 50;
			$pager->mDefaultLimit = 50;
			$pager->mOffset = "";
			$pager->mIsBackwards = false;
		}

		if ( $param['useMaster'] ) {
			$pager->mDb = $services->getConnectionProvider()->getPrimaryDatabase();
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
		$footerHtmlItems = [];

		if ( $logBody ) {
			if ( $msgKey[0] ) {
				// @phan-suppress-next-line PhanParamTooFewUnpack Non-emptiness checked above
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

			$footerHtmlItems[] = $linkRenderer->makeKnownLink(
				SpecialPage::getTitleFor( 'Log' ),
				$context->msg( 'log-fulllog' )->text(),
				[],
				$urlParam
			);
		}
		if ( $param['footerHtmlItems'] ) {
			$footerHtmlItems = array_merge( $footerHtmlItems, $param['footerHtmlItems'] );
		}
		if ( $logBody && $footerHtmlItems ) {
			$s .= '<ul class="mw-logevent-footer">';
			foreach ( $footerHtmlItems as $item ) {
				$s .= Html::rawElement( 'li', [], $item );
			}
			$s .= '</ul>';
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
			// Add styles for warning box
			$context->getOutput()->addModuleStyles( 'mediawiki.codex.messagebox.styles' );
		}

		// @phan-suppress-next-line PhanSuspiciousValueComparison
		if ( $wrap != '' ) { // Wrap message in html
			$s = str_replace( '$1', $s, $wrap );
		}

		/* hook can return false, if we don't want the message to be emitted (Wikia BugId:7093) */
		$hookRunner = new HookRunner( $services->getHookContainer() );
		if ( $hookRunner->onLogEventsListShowLogExtract( $s, $types, $pageName, $user, $param ) ) {
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
	 * @param \Wikimedia\Rdbms\IReadableDatabase $db
	 * @param string $audience Public/user
	 * @param Authority|null $performer User to check, required when audience isn't public
	 * @return string|false String on success, false on failure.
	 * @throws InvalidArgumentException
	 */
	public static function getExcludeClause( $db, $audience = 'public', ?Authority $performer = null ) {
		$logRestrictions = MediaWikiServices::getInstance()->getMainConfig()->get( MainConfigNames::LogRestrictions );

		if ( $audience != 'public' && $performer === null ) {
			throw new InvalidArgumentException(
				'A User object must be given when checking for a user audience.'
			);
		}

		// Reset the array, clears extra "where" clauses when $par is used
		$hiddenLogs = [];

		// Don't show private logs to unprivileged users
		foreach ( $logRestrictions as $logType => $right ) {
			if ( $audience == 'public' || !$performer->isAllowed( $right ) ) {
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

	/**
	 * @internal -- shared code for IntroMessageBuilder and Article::showMissingArticle
	 *
	 * If the user associated with the current page is blocked, get a warning
	 * box with a block log extract in it. Otherwise, return null.
	 *
	 * @param DatabaseBlockStore $blockStore
	 * @param NamespaceInfo $namespaceInfo
	 * @param MessageLocalizer $localizer
	 * @param LinkRenderer $linkRenderer
	 * @param UserIdentity|false|null $user The user which may be blocked
	 * @param Title $title The title being viewed
	 * @return string|null
	 */
	public static function getBlockLogWarningBox(
		DatabaseBlockStore $blockStore,
		NamespaceInfo $namespaceInfo,
		MessageLocalizer $localizer,
		LinkRenderer $linkRenderer,
		$user,
		Title $title
	) {
		if ( !$user ) {
			return null;
		}
		$appliesToTitle = false;
		$logTargetPage = '';
		$blockTargetName = '';
		$blocks = $blockStore->newListFromTarget( $user, $user, false,
			DatabaseBlockStore::AUTO_NONE );
		foreach ( $blocks as $block ) {
			if ( $block->appliesToTitle( $title ) ) {
				$appliesToTitle = true;
			}
			$blockTargetName = $block->getTargetName();
			$logTargetPage = $namespaceInfo->getCanonicalName( NS_USER ) .
				':' . $blockTargetName;
		}

		// Show log extract if the user is sitewide blocked or is partially
		// blocked and not allowed to edit their user page or user talk page
		if ( !count( $blocks ) || !$appliesToTitle ) {
			return null;
		}
		$msgKey = count( $blocks ) === 1
			? 'blocked-notice-logextract' : 'blocked-notice-logextract-multi';
		$params = [
			'lim' => 1,
			'showIfEmpty' => false,
			'msgKey' => [
				$msgKey,
				$user->getName(), # Support GENDER in notice
				count( $blocks )
			],
		];
		if ( count( $blocks ) > 1 ) {
			$params['footerHtmlItems'] = [
				$linkRenderer->makeKnownLink(
					SpecialPage::getTitleFor( 'BlockList' ),
					$localizer->msg( 'blocked-notice-list-link' )->text(),
					[],
					[ 'wpTarget' => $blockTargetName ]
				),
			];
		}

		$outString = '';
		self::showLogExtract( $outString, 'block', $logTargetPage, '', $params );
		return $outString ?: null;
	}
}

/** @deprecated class alias since 1.44 */
class_alias( LogEventsList::class, 'LogEventsList' );
