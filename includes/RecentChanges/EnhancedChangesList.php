<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\RecentChanges;

use DomainException;
use MediaWiki\Context\IContextSource;
use MediaWiki\Html\Html;
use MediaWiki\Html\TemplateParser;
use MediaWiki\Logging\LogPage;
use MediaWiki\MainConfigNames;
use MediaWiki\Parser\Sanitizer;
use MediaWiki\Revision\RevisionRecord;
use MediaWiki\SpecialPage\SpecialPage;
use MediaWiki\Title\Title;
use Wikimedia\HtmlArmor\HtmlArmor;

/**
 * Generate a list of changes using an Enhanced system (uses javascript).
 *
 * @ingroup RecentChanges
 */
class EnhancedChangesList extends ChangesList {

	/**
	 * @var RCCacheEntryFactory
	 */
	protected $cacheEntryFactory;

	/**
	 * @var RCCacheEntry[][]
	 */
	protected $rc_cache;

	/**
	 * @var TemplateParser
	 */
	protected $templateParser;

	/**
	 * @param IContextSource $context
	 * @param ChangesListFilterGroupContainer|null $filterGroups
	 */
	public function __construct( $context, ?ChangesListFilterGroupContainer $filterGroups = null ) {
		parent::__construct( $context, $filterGroups );

		// message is set by the parent ChangesList class
		$this->cacheEntryFactory = new RCCacheEntryFactory(
			$context,
			$this->message,
			$this->linkRenderer,
			$this->userLinkRenderer
		);
		$this->templateParser = new TemplateParser();
	}

	/**
	 * Add the JavaScript file for enhanced changeslist
	 * @return string
	 */
	public function beginRecentChangesList() {
		$this->getOutput()->addModuleStyles( [
			'mediawiki.special.changeslist.enhanced',
		] );

		parent::beginRecentChangesList();
		return '<div class="mw-changeslist" aria-live="polite">';
	}

	/**
	 * Format a line for enhanced recentchange (aka with javascript and block of lines).
	 *
	 * @param RecentChange &$rc
	 * @param bool $watched
	 * @param int|null $linenumber (default null)
	 *
	 * @return string
	 */
	public function recentChangesLine( &$rc, $watched = false, $linenumber = null ) {
		$date = $this->getLanguage()->userDate(
			$rc->mAttribs['rc_timestamp'],
			$this->getUser()
		);
		if ( $this->lastdate === '' ) {
			$this->lastdate = $date;
		}

		$ret = '';

		# If it's a new day, flush the cache and update $this->lastdate
		if ( $date !== $this->lastdate ) {
			# Process current cache (uses $this->lastdate to generate a heading)
			$ret = $this->recentChangesBlock();
			$this->rc_cache = [];
			$this->lastdate = $date;
		}

		$cacheEntry = $this->cacheEntryFactory->newFromRecentChange( $rc, $watched );
		$this->addCacheEntry( $cacheEntry );

		return $ret;
	}

	/**
	 * Put accumulated information into the cache, for later display.
	 * Page moves go on their own line.
	 */
	protected function addCacheEntry( RCCacheEntry $cacheEntry ) {
		$cacheGroupingKey = $this->makeCacheGroupingKey( $cacheEntry );
		$this->rc_cache[$cacheGroupingKey][] = $cacheEntry;
	}

	/**
	 * @param RCCacheEntry $cacheEntry
	 *
	 * @return string
	 */
	protected function makeCacheGroupingKey( RCCacheEntry $cacheEntry ) {
		$title = $cacheEntry->getTitle();
		$cacheGroupingKey = $title->getPrefixedDBkey();

		$source = $cacheEntry->mAttribs['rc_source'];

		if ( $source == RecentChange::SRC_LOG ) {
			// Group by log type
			$cacheGroupingKey = SpecialPage::getTitleFor(
				'Log',
				$cacheEntry->mAttribs['rc_log_type']
			)->getPrefixedDBkey();
		}

		return $cacheGroupingKey;
	}

	/**
	 * Enhanced RC group
	 * @param RCCacheEntry[] $block
	 * @return string
	 */
	protected function recentChangesBlockGroup( $block ) {
		$recentChangesFlags = $this->getConfig()->get( MainConfigNames::RecentChangesFlags );

		# Add the namespace and title of the block as part of the class
		$tableClasses = [ 'mw-enhanced-rc', 'mw-changeslist-line' ];
		if ( $block[0]->mAttribs['rc_log_type'] ) {
			# Log entry
			$tableClasses[] = 'mw-changeslist-log';
			$tableClasses[] = Sanitizer::escapeClass( 'mw-changeslist-log-'
				. $block[0]->mAttribs['rc_log_type'] );
		} else {
			$tableClasses[] = 'mw-changeslist-edit';
			$tableClasses[] = Sanitizer::escapeClass( 'mw-changeslist-ns'
				. $block[0]->mAttribs['rc_namespace'] . '-' . $block[0]->mAttribs['rc_title'] );
		}
		if ( $block[0]->watched ) {
			$tableClasses[] = 'mw-changeslist-line-watched';
		} else {
			$tableClasses[] = 'mw-changeslist-line-not-watched';
		}

		# Collate list of users
		$usercounts = [];
		$userlinks = [];
		# Some catalyst variables...
		$namehidden = true;
		$allLogs = true;
		$RCShowChangedSize = $this->getConfig()->get( MainConfigNames::RCShowChangedSize );

		# Default values for RC flags
		$collectedRcFlags = [];
		foreach ( $recentChangesFlags as $key => $value ) {
			$flagGrouping = $value['grouping'] ?? 'any';
			switch ( $flagGrouping ) {
				case 'all':
					$collectedRcFlags[$key] = true;
					break;
				case 'any':
					$collectedRcFlags[$key] = false;
					break;
				default:
					throw new DomainException( "Unknown grouping type \"{$flagGrouping}\"" );
			}
		}
		foreach ( $block as $rcObj ) {
			// If all log actions to this page were hidden, then don't
			// give the name of the affected page for this block!
			if ( !static::isDeleted( $rcObj, LogPage::DELETED_ACTION ) ) {
				$namehidden = false;
			}
			$username = $rcObj->getPerformerIdentity()->getName();
			$userlink = $rcObj->userlink;
			if ( !isset( $usercounts[$username] ) ) {
				$usercounts[$username] = 0;
				$userlinks[$username] = $userlink;
			}
			if ( $rcObj->mAttribs['rc_source'] !== RecentChange::SRC_LOG ) {
				$allLogs = false;
			}

			$usercounts[$username]++;
		}

		# Sort the list and convert to text
		krsort( $usercounts );
		asort( $usercounts );
		$users = [];
		foreach ( $usercounts as $username => $count ) {
			$text = (string)$userlinks[$username];
			if ( $count > 1 ) {
				$formattedCount = $this->msg( 'ntimes' )->numParams( $count )->escaped();
				$text .= ' ' . $this->msg( 'parentheses' )->rawParams( $formattedCount )->escaped();
			}
			$users[] = Html::rawElement(
				'span',
				[ 'class' => 'mw-changeslist-user-in-group' ],
				$text
			);
		}

		# Article link
		$articleLink = '';
		$revDeletedMsg = false;
		if ( $namehidden ) {
			$revDeletedMsg = $this->msg( 'rev-deleted-event' )->escaped();
		} elseif ( $allLogs ) {
			$articleLink = $this->maybeWatchedLink( $block[0]->link, $block[0]->watched );
		} else {
			$articleLink = $this->getArticleLink(
				$block[0], $block[0]->unpatrolled, $block[0]->watched );
		}

		# Sub-entries
		$lines = [];
		$filterClasses = [];
		foreach ( $block as $i => $rcObj ) {
			$line = $this->getLineData( $block, $rcObj );
			if ( !$line ) {
				// completely ignore this RC entry if we don't want to render it
				unset( $block[$i] );
				continue;
			}

			// Roll up flags
			foreach ( $line['recentChangesFlagsRaw'] as $key => $value ) {
				$flagGrouping = ( $recentChangesFlags[$key]['grouping'] ?? 'any' );
				switch ( $flagGrouping ) {
					case 'all':
						if ( !$value ) {
							$collectedRcFlags[$key] = false;
						}
						break;
					case 'any':
						if ( $value ) {
							$collectedRcFlags[$key] = true;
						}
						break;
					default:
						throw new DomainException( "Unknown grouping type \"{$flagGrouping}\"" );
				}
			}

			// Roll up filter-based CSS classes
			$filterClasses = array_merge( $filterClasses, $this->getHTMLClassesForFilters( $rcObj ) );
			// Add classes for change tags separately, getHTMLClassesForFilters() doesn't add them
			$this->getTags( $rcObj, $filterClasses );
			$filterClasses = array_unique( $filterClasses );

			$lines[] = $line;
		}

		// Further down are some assumptions that $block is a 0-indexed array
		// with (count-1) as last key. Let's make sure it is.
		$block = array_values( $block );
		$filterClasses = array_values( $filterClasses );

		if ( !$block || !$lines ) {
			// if we can't show anything, don't display this block altogether
			return '';
		}

		$logText = $this->getLogText( $block, [], $allLogs,
			$collectedRcFlags['newpage'], $namehidden
		);

		# Character difference (does not apply if only log items)
		$charDifference = false;
		if ( $RCShowChangedSize && !$allLogs ) {
			$last = 0;
			$first = count( $block ) - 1;
			# Some events (like logs and category changes) have an "empty" size, so we need to skip those...
			while ( $last < $first && $block[$last]->mAttribs['rc_new_len'] === null ) {
				$last++;
			}
			while ( $last < $first && $block[$first]->mAttribs['rc_old_len'] === null ) {
				$first--;
			}
			# Get net change
			$charDifference = $this->formatCharacterDifference( $block[$first], $block[$last] ) ?: false;
		}

		$numberofWatchingusers = $this->numberofWatchingusers( $block[0]->numberofWatchingusers );
		$usersList = $this->msg( 'brackets' )->rawParams(
			implode( $this->message['semicolon-separator'], $users )
		)->escaped();

		$prefix = '';
		if ( is_callable( $this->changeLinePrefixer ) ) {
			$prefix = ( $this->changeLinePrefixer )( $block[0], $this, true );
		}

		$templateParams = [
			'checkboxId' => 'mw-checkbox-' . base64_encode( random_bytes( 3 ) ),
			'articleLink' => $articleLink,
			'charDifference' => $charDifference,
			'collectedRcFlags' => $this->recentChangesFlags( $collectedRcFlags ),
			'filterClasses' => $filterClasses,
			'lines' => $lines,
			'logText' => $logText,
			'numberofWatchingusers' => $numberofWatchingusers,
			'prefix' => $prefix,
			'rev-deleted-event' => $revDeletedMsg,
			'tableClasses' => $tableClasses,
			'timestamp' => $block[0]->timestamp,
			'fullTimestamp' => $block[0]->getAttribute( 'rc_timestamp' ),
			'users' => $usersList,
		];

		$this->rcCacheIndex++;

		return $this->templateParser->processTemplate(
			'EnhancedChangesListGroup',
			$templateParams
		);
	}

	/**
	 * @param RCCacheEntry[] $block
	 * @param RCCacheEntry $rcObj
	 * @param array $queryParams
	 * @return array
	 */
	protected function getLineData( array $block, RCCacheEntry $rcObj, array $queryParams = [] ) {
		$RCShowChangedSize = $this->getConfig()->get( MainConfigNames::RCShowChangedSize );

		$source = $rcObj->mAttribs['rc_source'];
		$data = [];
		$lineParams = [ 'targetTitle' => $rcObj->getTitle() ];

		$classes = [ 'mw-enhanced-rc' ];
		if ( $rcObj->watched ) {
			$classes[] = 'mw-enhanced-watched';
		}
		$classes = array_merge( $classes, $this->getHTMLClasses( $rcObj, $rcObj->watched ) );

		$separator = ' <span class="mw-changeslist-separator"></span> ';

		$data['recentChangesFlags'] = [
			'newpage' => $source == RecentChange::SRC_NEW,
			'minor' => $rcObj->mAttribs['rc_minor'],
			'unpatrolled' => $rcObj->unpatrolled,
			'bot' => $rcObj->mAttribs['rc_bot'],
		];

		# Log timestamp
		if ( $source == RecentChange::SRC_LOG ) {
			$link = htmlspecialchars( $rcObj->timestamp );
			# Revision link
		} elseif ( !ChangesList::userCan( $rcObj, RevisionRecord::DELETED_TEXT, $this->getAuthority() ) ) {
			$link = Html::element( 'span', [ 'class' => 'history-deleted' ], $rcObj->timestamp );
		} else {
			$params = [];
			$params['curid'] = $rcObj->mAttribs['rc_cur_id'];
			if ( $rcObj->mAttribs['rc_this_oldid'] != 0 ) {
				$params['oldid'] = $rcObj->mAttribs['rc_this_oldid'];
			}
			// FIXME: The link has incorrect "title=" when rc_source = RecentChange::SRC_CATEGORIZE.
			// rc_cur_id refers to the page that was categorized
			// whereas RecentChange::getTitle refers to the category.
			$link = $this->linkRenderer->makeKnownLink(
				$rcObj->getTitle(),
				$rcObj->timestamp,
				[],
				$params + $queryParams
			);
			if ( static::isDeleted( $rcObj, RevisionRecord::DELETED_TEXT ) ) {
				$link = '<span class="history-deleted">' . $link . '</span> ';
			}
		}
		$data['timestampLink'] = $link;

		$currentAndLastLinks = '';
		if ( $source == RecentChange::SRC_EDIT || $source == RecentChange::SRC_NEW ) {
			$currentAndLastLinks .= ' ' . $this->msg( 'parentheses' )->rawParams(
					$rcObj->curlink .
					$this->message['pipe-separator'] .
					$rcObj->lastlink
				)->escaped();
		}
		$data['currentAndLastLinks'] = $currentAndLastLinks;
		$data['separatorAfterCurrentAndLastLinks'] = $separator;

		# Character diff
		if ( $RCShowChangedSize ) {
			$cd = $this->formatCharacterDifference( $rcObj );
			if ( $cd !== '' ) {
				$data['characterDiff'] = $cd;
				$data['separatorAfterCharacterDiff'] = $separator;
			}
		}

		if ( $source == RecentChange::SRC_LOG ) {
			$data['logEntry'] = $this->insertLogEntry( $rcObj );
		} elseif ( $this->isCategorizationWithoutRevision( $rcObj ) ) {
			$data['comment'] = $this->insertComment( $rcObj );
		} else {
			# User links
			$data['userLink'] = $rcObj->userlink;
			$data['userTalkLink'] = $rcObj->usertalklink;
			$data['comment'] = $this->insertComment( $rcObj );
			if ( $source == RecentChange::SRC_CATEGORIZE ) {
				$data['historyLink'] = $this->getDiffHistLinks( $rcObj, false );
			}
			# Rollback, thanks etc...
			$data['rollback'] = $this->getRollback( $rcObj );
		}

		# Tags
		$data['tags'] = $this->getTags( $rcObj, $classes );

		$attribs = $this->getDataAttributes( $rcObj );

		// give the hook a chance to modify the data
		$success = $this->getHookRunner()->onEnhancedChangesListModifyLineData(
			$this, $data, $block, $rcObj, $classes, $attribs );
		if ( !$success ) {
			// skip entry if hook aborted it
			return [];
		}
		$attribs = array_filter( $attribs,
			[ Sanitizer::class, 'isReservedDataAttribute' ],
			ARRAY_FILTER_USE_KEY
		);

		$lineParams['recentChangesFlagsRaw'] = [];
		if ( isset( $data['recentChangesFlags'] ) ) {
			$lineParams['recentChangesFlags'] = $this->recentChangesFlags( $data['recentChangesFlags'] );
			# FIXME: This is used by logic, don't return it in the template params.
			$lineParams['recentChangesFlagsRaw'] = $data['recentChangesFlags'];
			unset( $data['recentChangesFlags'] );
		}

		if ( isset( $data['timestampLink'] ) ) {
			$lineParams['timestampLink'] = $data['timestampLink'];
			unset( $data['timestampLink'] );
		}

		$lineParams['classes'] = array_values( $classes );
		$lineParams['attribs'] = Html::expandAttributes( $attribs );

		// everything else: makes it easier for extensions to add or remove data
		$lineParams['data'] = array_values( $data );

		return $lineParams;
	}

	/**
	 * Generates amount of changes (linking to diff ) & link to history.
	 *
	 * @param RCCacheEntry[] $block
	 * @param array $queryParams
	 * @param bool $allLogs
	 * @param bool $isnew
	 * @param bool $namehidden
	 * @return string
	 */
	protected function getLogText( $block, $queryParams, $allLogs, $isnew, $namehidden ) {
		if ( !$block ) {
			return '';
		}

		// Changes message
		static $nchanges = [];
		static $sinceLastVisitMsg = [];

		$n = count( $block );
		if ( !isset( $nchanges[$n] ) ) {
			$nchanges[$n] = $this->msg( 'nchanges' )->numParams( $n )->escaped();
		}

		$sinceLast = 0;
		$unvisitedOldid = null;
		$currentRevision = 0;
		$previousRevision = 0;
		$curId = 0;
		$allCategorization = true;
		/** @var RCCacheEntry $rcObj */
		foreach ( $block as $rcObj ) {
			// Fields of categorization entries refer to the changed page
			// rather than the category for which we are building the log text.
			if ( $rcObj->mAttribs['rc_source'] == RecentChange::SRC_CATEGORIZE ) {
				continue;
			}

			$allCategorization = false;
			$previousRevision = $rcObj->mAttribs['rc_last_oldid'];
			// Same logic as below inside main foreach
			if ( $rcObj->watched ) {
				$sinceLast++;
				$unvisitedOldid = $previousRevision;
			}
			if ( !$currentRevision ) {
				$currentRevision = $rcObj->mAttribs['rc_this_oldid'];
			}
			if ( !$curId ) {
				$curId = $rcObj->mAttribs['rc_cur_id'];
			}
		}

		// Total change link
		$links = [];
		$title = $block[0]->getTitle();
		if ( !$allLogs ) {
			// TODO: Disable the link if the user cannot see it (rc_deleted).
			// Beware of possibly interspersed categorization entries.
			if ( $isnew || $allCategorization ) {
				$links['total-changes'] = Html::rawElement( 'span', [], $nchanges[$n] );
			} else {
				$links['total-changes'] = Html::rawElement( 'span', [],
					$this->linkRenderer->makeKnownLink(
						$title,
						new HtmlArmor( $nchanges[$n] ),
						[ 'class' => 'mw-changeslist-groupdiff' ],
						$queryParams + [
							'curid' => $curId,
							'diff' => $currentRevision,
							'oldid' => $previousRevision,
						]
					)
				);
			}

			if (
				!$allCategorization &&
				$sinceLast > 0 &&
				$sinceLast < $n
			) {
				if ( !isset( $sinceLastVisitMsg[$sinceLast] ) ) {
					$sinceLastVisitMsg[$sinceLast] =
						$this->msg( 'enhancedrc-since-last-visit' )->numParams( $sinceLast )->escaped();
				}
				$links['total-changes-since-last'] = Html::rawElement( 'span', [],
					$this->linkRenderer->makeKnownLink(
						$title,
						new HtmlArmor( $sinceLastVisitMsg[$sinceLast] ),
						[ 'class' => 'mw-changeslist-groupdiff' ],
						$queryParams + [
							'curid' => $curId,
							'diff' => $currentRevision,
							'oldid' => $unvisitedOldid,
						]
					)
				);
			}
		}

		// History
		if ( $allLogs || $allCategorization ) {
			// don't show history link for logs
		} elseif ( $namehidden || !$title->exists() ) {
			$links['history'] = Html::rawElement( 'span', [], $this->message['enhancedrc-history'] );
		} else {
			$links['history'] = Html::rawElement( 'span', [],
				$this->linkRenderer->makeKnownLink(
					$title,
					new HtmlArmor( $this->message['enhancedrc-history'] ),
					[ 'class' => 'mw-changeslist-history' ],
					[
						'curid' => $curId,
						'action' => 'history',
					] + $queryParams
				)
			);
		}

		// Allow others to alter, remove or add to these links
		$this->getHookRunner()->onEnhancedChangesList__getLogText( $this, $links, $block );

		if ( !$links ) {
			return '';
		}

		$logtext = Html::rawElement( 'span', [ 'class' => 'mw-changeslist-links' ],
			implode( ' ', $links ) );
		return ' ' . $logtext;
	}

	/**
	 * Enhanced RC ungrouped line.
	 *
	 * @param RecentChange|RCCacheEntry $rcObj
	 * @return string A HTML formatted line (generated using $r)
	 */
	protected function recentChangesBlockLine( $rcObj ) {
		$data = [];

		$source = $rcObj->mAttribs['rc_source'];
		$logType = $rcObj->mAttribs['rc_log_type'];
		$classes = $this->getHTMLClasses( $rcObj, $rcObj->watched );
		$classes[] = 'mw-enhanced-rc';

		if ( $logType ) {
			# Log entry
			$classes[] = 'mw-changeslist-log';
			$classes[] = Sanitizer::escapeClass( 'mw-changeslist-log-' . $logType );
		} else {
			$classes[] = 'mw-changeslist-edit';
			$classes[] = Sanitizer::escapeClass( 'mw-changeslist-ns' .
				$rcObj->mAttribs['rc_namespace'] . '-' . $rcObj->mAttribs['rc_title'] );
		}

		# Flag and Timestamp
		$data['recentChangesFlags'] = [
			'newpage' => $source == RecentChange::SRC_NEW,
			'minor' => $rcObj->mAttribs['rc_minor'],
			'unpatrolled' => $rcObj->unpatrolled,
			'bot' => $rcObj->mAttribs['rc_bot'],
		];
		// timestamp is not really a link here, but is called timestampLink
		// for consistency with EnhancedChangesListModifyLineData
		$data['timestampLink'] = htmlspecialchars( $rcObj->timestamp );

		# Article or log link
		if ( $logType ) {
			$logPage = new LogPage( $logType );
			$logTitle = SpecialPage::getTitleFor( 'Log', $logType );
			$logName = $logPage->getName()->text();
			$data['logLink'] = Html::rawElement( 'span', [ 'class' => 'mw-changeslist-links' ],
				$this->linkRenderer->makeKnownLink( $logTitle, $logName )
			);
		} else {
			$data['articleLink'] = $this->getArticleLink( $rcObj, $rcObj->unpatrolled, $rcObj->watched );
		}

		# Diff and hist links
		if ( $source != RecentChange::SRC_LOG && $source != RecentChange::SRC_CATEGORIZE ) {
			$data['historyLink'] = $this->getDiffHistLinks( $rcObj, false );
		}
		$data['separatorAfterLinks'] = ' <span class="mw-changeslist-separator"></span> ';

		# Character diff
		if ( $this->getConfig()->get( MainConfigNames::RCShowChangedSize ) ) {
			$cd = $this->formatCharacterDifference( $rcObj );
			if ( $cd !== '' ) {
				$data['characterDiff'] = $cd;
				$data['separatorAftercharacterDiff'] = ' <span class="mw-changeslist-separator"></span> ';
			}
		}

		if ( $source == RecentChange::SRC_LOG ) {
			$data['logEntry'] = $this->insertLogEntry( $rcObj );
		} elseif ( $this->isCategorizationWithoutRevision( $rcObj ) ) {
			$data['comment'] = $this->insertComment( $rcObj );
		} else {
			$data['userLink'] = $rcObj->userlink;
			$data['userTalkLink'] = $rcObj->usertalklink;
			$data['comment'] = $this->insertComment( $rcObj );
			if ( $source == RecentChange::SRC_CATEGORIZE ) {
				$data['historyLink'] = $this->getDiffHistLinks( $rcObj, false );
			}
			$data['rollback'] = $this->getRollback( $rcObj );
		}

		# Tags
		$data['tags'] = $this->getTags( $rcObj, $classes );

		# Show how many people are watching this if enabled
		$data['watchingUsers'] = $this->numberofWatchingusers( $rcObj->numberofWatchingusers );

		$data['attribs'] = array_merge( $this->getDataAttributes( $rcObj ), [ 'class' => $classes ] );

		// give the hook a chance to modify the data
		$success = $this->getHookRunner()->onEnhancedChangesListModifyBlockLineData(
			$this, $data, $rcObj );
		if ( !$success ) {
			// skip entry if hook aborted it
			return '';
		}
		$attribs = $data['attribs'];
		unset( $data['attribs'] );
		$attribs = array_filter( $attribs, static function ( $key ) {
			return $key === 'class' || Sanitizer::isReservedDataAttribute( $key );
		}, ARRAY_FILTER_USE_KEY );

		$prefix = '';
		if ( is_callable( $this->changeLinePrefixer ) ) {
			$prefix = ( $this->changeLinePrefixer )( $rcObj, $this, false );
		}

		$line = Html::openElement( 'table', $attribs ) . Html::openElement( 'tr' );
		// Highlight block
		$line .= Html::rawElement( 'td', [],
			$this->getHighlightsContainerDiv()
		);

		$line .= Html::rawElement( 'td', [], '<span class="mw-enhancedchanges-arrow-space"></span>' );
		$line .= Html::rawElement( 'td', [ 'class' => 'mw-changeslist-line-prefix' ], $prefix );
		$line .= '<td class="mw-enhanced-rc" colspan="2">';

		if ( isset( $data['recentChangesFlags'] ) ) {
			$line .= $this->recentChangesFlags( $data['recentChangesFlags'] );
			unset( $data['recentChangesFlags'] );
		}

		if ( isset( $data['timestampLink'] ) ) {
			$line .= "\u{00A0}" . $data['timestampLink'];
			unset( $data['timestampLink'] );
		}
		$line .= "\u{00A0}</td>";
		$line .= Html::openElement( 'td', [
			'class' => 'mw-changeslist-line-inner',
			// Used for reliable determination of the affiliated page
			'data-target-page' => $rcObj->getTitle(),
		] );

		// everything else: makes it easier for extensions to add or remove data
		foreach ( $data as $key => $dataItem ) {
			$line .= Html::rawElement( 'span', [
				'class' => 'mw-changeslist-line-inner-' . $key,
			], $dataItem );
		}

		$line .= "</td></tr></table>\n";

		return $line;
	}

	/**
	 * Returns value to be used in 'historyLink' element of $data param in
	 * EnhancedChangesListModifyBlockLineData hook.
	 *
	 * @since 1.27
	 *
	 * @param RCCacheEntry $rc
	 * @param bool|array|null $query deprecated
	 * @param bool|null $useParentheses (optional) Wrap comments in parentheses where needed
	 * @return string HTML
	 */
	public function getDiffHistLinks( RCCacheEntry $rc, $query = null, $useParentheses = null ) {
		if ( is_bool( $query ) ) {
			$useParentheses = $query;
		} elseif ( $query !== null ) {
			wfDeprecated( __METHOD__ . ' with $query parameter', '1.36' );
		}
		$pageTitle = $rc->getTitle();
		if ( $rc->getAttribute( 'rc_source' ) == RecentChange::SRC_CATEGORIZE ) {
			// For categorizations we must swap the category title with the page title!
			$pageTitle = Title::newFromID( $rc->getAttribute( 'rc_cur_id' ) );
			if ( !$pageTitle ) {
				// The page has been deleted, but the RC entry
				// deletion job has not run yet. Just skip.
				return '';
			}
		}

		$histLink = $this->linkRenderer->makeKnownLink(
			$pageTitle,
			new HtmlArmor( $this->message['hist'] ),
			[ 'class' => 'mw-changeslist-history' ],
			[
				'curid' => $rc->getAttribute( 'rc_cur_id' ),
				'action' => 'history'
			]
		);
		if ( $useParentheses !== false ) {
			$retVal = $this->msg( 'parentheses' )
			->rawParams( $rc->difflink . $this->message['pipe-separator']
				. $histLink )->escaped();
		} else {
			$retVal = Html::rawElement( 'span', [ 'class' => 'mw-changeslist-links' ],
				Html::rawElement( 'span', [], $rc->difflink ) .
				Html::rawElement( 'span', [], $histLink )
			);
		}
		return ' ' . $retVal;
	}

	/**
	 * If enhanced RC is in use, this function takes the previously cached
	 * RC lines, arranges them, and outputs the HTML
	 *
	 * @return string
	 */
	protected function recentChangesBlock() {
		if ( count( $this->rc_cache ) == 0 ) {
			return '';
		}

		$blockOut = '';
		foreach ( $this->rc_cache as $block ) {
			$visibleBlock = [];
			$hiddenBlock = [];

			// T398706: Filter the block to prevent leaking hidden usernames
			foreach ( $block as $rcObj ) {
				if ( static::isDeleted( $rcObj, RevisionRecord::DELETED_USER ) ) {
					$hiddenBlock[] = $rcObj;
				} else {
					$visibleBlock[] = $rcObj;
				}
			}

			$visibleCount = count( $visibleBlock );

			if ( $visibleCount > 0 ) {
				$blockOut .= $visibleCount > 1 ?
					$this->recentChangesBlockGroup( $visibleBlock ) :
					$this->recentChangesBlockLine( array_shift( $visibleBlock ) );

			}

			$hiddenCount = count( $hiddenBlock );

			if ( $hiddenCount > 0 ) {
				$blockOut .= $hiddenCount > 1 ?
					$this->recentChangesBlockGroup( $hiddenBlock ) :
					$this->recentChangesBlockLine( array_shift( $hiddenBlock ) );
			}
		}

		if ( $blockOut === '' ) {
			return '';
		}
		// $this->lastdate is kept up to date by recentChangesLine()
		return Html::element( 'h4', [], $this->lastdate ) . "\n<div>" . $blockOut . '</div>';
	}

	/**
	 * Returns text for the end of RC
	 * If enhanced RC is in use, returns pretty much all the text
	 * @return string
	 */
	public function endRecentChangesList() {
		return $this->recentChangesBlock() . '</div>';
	}
}

/** @deprecated class alias since 1.44 */
class_alias( EnhancedChangesList::class, 'EnhancedChangesList' );
