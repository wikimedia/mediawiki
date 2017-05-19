<?php
/**
 * Generates a list of changes using an Enhanced system (uses javascript).
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

class EnhancedChangesList extends ChangesList {

	/**
	 * @var RCCacheEntryFactory
	 */
	protected $cacheEntryFactory;

	/**
	 * @var array Array of array of RCCacheEntry
	 */
	protected $rc_cache;

	/**
	 * @var TemplateParser
	 */
	protected $templateParser;

	/**
	 * @param IContextSource|Skin $obj
	 * @param array $filterGroups Array of ChangesListFilterGroup objects (currently optional)
	 * @throws MWException
	 */
	public function __construct( $obj, array $filterGroups = [] ) {
		if ( $obj instanceof Skin ) {
			// @todo: deprecate constructing with Skin
			$context = $obj->getContext();
		} else {
			if ( !$obj instanceof IContextSource ) {
				throw new MWException( 'EnhancedChangesList must be constructed with a '
					. 'context source or skin.' );
			}

			$context = $obj;
		}

		parent::__construct( $context, $filterGroups );

		// message is set by the parent ChangesList class
		$this->cacheEntryFactory = new RCCacheEntryFactory(
			$context,
			$this->message,
			$this->linkRenderer
		);
		$this->templateParser = new TemplateParser();
	}

	/**
	 * Add the JavaScript file for enhanced changeslist
	 * @return string
	 */
	public function beginRecentChangesList() {
		$this->rc_cache = [];
		$this->rcMoveIndex = 0;
		$this->rcCacheIndex = 0;
		$this->lastdate = '';
		$this->rclistOpen = false;
		$this->getOutput()->addModuleStyles( [
			'mediawiki.special.changeslist',
			'mediawiki.special.changeslist.enhanced',
		] );
		$this->getOutput()->addModules( [
			'jquery.makeCollapsible',
			'mediawiki.icon',
		] );

		return '<div class="mw-changeslist">';
	}

	/**
	 * Format a line for enhanced recentchange (aka with javascript and block of lines).
	 *
	 * @param RecentChange $rc
	 * @param bool $watched
	 * @param int $linenumber (default null)
	 *
	 * @return string
	 */
	public function recentChangesLine( &$rc, $watched = false, $linenumber = null ) {

		$date = $this->getLanguage()->userDate(
			$rc->mAttribs['rc_timestamp'],
			$this->getUser()
		);

		$ret = '';

		# If it's a new day, add the headline and flush the cache
		if ( $date != $this->lastdate ) {
			# Process current cache
			$ret = $this->recentChangesBlock();
			$this->rc_cache = [];
			$ret .= Xml::element( 'h4', null, $date ) . "\n";
			$this->lastdate = $date;
		}

		$cacheEntry = $this->cacheEntryFactory->newFromRecentChange( $rc, $watched );
		$this->addCacheEntry( $cacheEntry );

		return $ret;
	}

	/**
	 * Put accumulated information into the cache, for later display.
	 * Page moves go on their own line.
	 *
	 * @param RCCacheEntry $cacheEntry
	 */
	protected function addCacheEntry( RCCacheEntry $cacheEntry ) {
		$cacheGroupingKey = $this->makeCacheGroupingKey( $cacheEntry );

		if ( !isset( $this->rc_cache[$cacheGroupingKey] ) ) {
			$this->rc_cache[$cacheGroupingKey] = [];
		}

		array_push( $this->rc_cache[$cacheGroupingKey], $cacheEntry );
	}

	/**
	 * @todo use rc_source to group, if set; fallback to rc_type
	 *
	 * @param RCCacheEntry $cacheEntry
	 *
	 * @return string
	 */
	protected function makeCacheGroupingKey( RCCacheEntry $cacheEntry ) {
		$title = $cacheEntry->getTitle();
		$cacheGroupingKey = $title->getPrefixedDBkey();

		$type = $cacheEntry->mAttribs['rc_type'];

		if ( $type == RC_LOG ) {
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
	 * @throws DomainException
	 */
	protected function recentChangesBlockGroup( $block ) {
		$recentChangesFlags = $this->getConfig()->get( 'RecentChangesFlags' );

		# Add the namespace and title of the block as part of the class
		$tableClasses = [ 'mw-collapsible', 'mw-collapsed', 'mw-enhanced-rc' ];
		if ( $block[0]->mAttribs['rc_log_type'] ) {
			# Log entry
			$tableClasses[] = Sanitizer::escapeClass( 'mw-changeslist-log-'
				. $block[0]->mAttribs['rc_log_type'] );
		} else {
			$tableClasses[] = Sanitizer::escapeClass( 'mw-changeslist-ns'
				. $block[0]->mAttribs['rc_namespace'] . '-' . $block[0]->mAttribs['rc_title'] );
		}
		if ( $block[0]->watched
			&& $block[0]->mAttribs['rc_timestamp'] >= $block[0]->watched
		) {
			$tableClasses[] = 'mw-changeslist-line-watched';
		} else {
			$tableClasses[] = 'mw-changeslist-line-not-watched';
		}

		# Collate list of users
		$userlinks = [];
		# Other properties
		$curId = 0;
		# Some catalyst variables...
		$namehidden = true;
		$allLogs = true;
		$RCShowChangedSize = $this->getConfig()->get( 'RCShowChangedSize' );

		# Default values for RC flags
		$collectedRcFlags = [];
		foreach ( $recentChangesFlags as $key => $value ) {
			$flagGrouping = ( isset( $recentChangesFlags[$key]['grouping'] ) ?
					$recentChangesFlags[$key]['grouping'] : 'any' );
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
			if ( !$this->isDeleted( $rcObj, LogPage::DELETED_ACTION ) ) {
				$namehidden = false;
			}
			$u = $rcObj->userlink;
			if ( !isset( $userlinks[$u] ) ) {
				$userlinks[$u] = 0;
			}
			if ( $rcObj->mAttribs['rc_type'] != RC_LOG ) {
				$allLogs = false;
			}
			# Get the latest entry with a page_id and oldid
			# since logs may not have these.
			if ( !$curId && $rcObj->mAttribs['rc_cur_id'] ) {
				$curId = $rcObj->mAttribs['rc_cur_id'];
			}

			$userlinks[$u]++;
		}

		# Sort the list and convert to text
		krsort( $userlinks );
		asort( $userlinks );
		$users = [];
		foreach ( $userlinks as $userlink => $count ) {
			$text = $userlink;
			$text .= $this->getLanguage()->getDirMark();
			if ( $count > 1 ) {
				// @todo FIXME: Hardcoded '×'. Should be a message.
				$formattedCount = $this->getLanguage()->formatNum( $count ) . '×';
				$text .= ' ' . $this->msg( 'parentheses' )->rawParams( $formattedCount )->escaped();
			}
			array_push( $users, $text );
		}

		# Article link
		$articleLink = '';
		$revDeletedMsg = false;
		if ( $namehidden ) {
			$revDeletedMsg = $this->msg( 'rev-deleted-event' )->escaped();
		} elseif ( $allLogs ) {
			$articleLink = $this->maybeWatchedLink( $block[0]->link, $block[0]->watched );
		} else {
			$articleLink = $this->getArticleLink( $block[0], $block[0]->unpatrolled, $block[0]->watched );
		}

		$queryParams['curid'] = $curId;

		# Sub-entries
		$lines = [];
		foreach ( $block as $i => $rcObj ) {
			$line = $this->getLineData( $block, $rcObj, $queryParams );
			if ( !$line ) {
				// completely ignore this RC entry if we don't want to render it
				unset( $block[$i] );
				continue;
			}

			// Roll up flags
			foreach ( $line['recentChangesFlagsRaw'] as $key => $value ) {
				$flagGrouping = ( isset( $recentChangesFlags[$key]['grouping'] ) ?
					$recentChangesFlags[$key]['grouping'] : 'any' );
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

			$lines[] = $line;
		}

		// Further down are some assumptions that $block is a 0-indexed array
		// with (count-1) as last key. Let's make sure it is.
		$block = array_values( $block );

		if ( empty( $block ) || !$lines ) {
			// if we can't show anything, don't display this block altogether
			return '';
		}

		$logText = $this->getLogText( $block, $queryParams, $allLogs,
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
			$charDifference = $this->formatCharacterDifference( $block[$first], $block[$last] );
		}

		$numberofWatchingusers = $this->numberofWatchingusers( $block[0]->numberofWatchingusers );
		$usersList = $this->msg( 'brackets' )->rawParams(
			implode( $this->message['semicolon-separator'], $users )
		)->escaped();

		$templateParams = [
			'articleLink' => $articleLink,
			'charDifference' => $charDifference,
			'collectedRcFlags' => $this->recentChangesFlags( $collectedRcFlags ),
			'languageDirMark' => $this->getLanguage()->getDirMark(),
			'lines' => $lines,
			'logText' => $logText,
			'numberofWatchingusers' => $numberofWatchingusers,
			'rev-deleted-event' => $revDeletedMsg,
			'tableClasses' => $tableClasses,
			'timestamp' => $block[0]->timestamp,
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
	 * @throws Exception
	 * @throws FatalError
	 * @throws MWException
	 */
	protected function getLineData( array $block, RCCacheEntry $rcObj, array $queryParams = [] ) {
		$RCShowChangedSize = $this->getConfig()->get( 'RCShowChangedSize' );

		$type = $rcObj->mAttribs['rc_type'];
		$data = [];
		$lineParams = [];

		$classes = [ 'mw-enhanced-rc' ];
		if ( $rcObj->watched
			&& $rcObj->mAttribs['rc_timestamp'] >= $rcObj->watched
		) {
			$classes[] = 'mw-enhanced-watched';
		}
		$classes = array_merge( $classes, $this->getHTMLClassesForFilters( $rcObj ) );

		$separator = ' <span class="mw-changeslist-separator">. .</span> ';

		$data['recentChangesFlags'] = [
			'newpage' => $type == RC_NEW,
			'minor' => $rcObj->mAttribs['rc_minor'],
			'unpatrolled' => $rcObj->unpatrolled,
			'bot' => $rcObj->mAttribs['rc_bot'],
		];

		$params = $queryParams;

		if ( $rcObj->mAttribs['rc_this_oldid'] != 0 ) {
			$params['oldid'] = $rcObj->mAttribs['rc_this_oldid'];
		}

		# Log timestamp
		if ( $type == RC_LOG ) {
			$link = $rcObj->timestamp;
			# Revision link
		} elseif ( !ChangesList::userCan( $rcObj, Revision::DELETED_TEXT, $this->getUser() ) ) {
			$link = '<span class="history-deleted">' . $rcObj->timestamp . '</span> ';
		} else {
			$link = $this->linkRenderer->makeKnownLink(
				$rcObj->getTitle(),
				new HtmlArmor( $rcObj->timestamp ),
				[],
				$params
			);
			if ( $this->isDeleted( $rcObj, Revision::DELETED_TEXT ) ) {
				$link = '<span class="history-deleted">' . $link . '</span> ';
			}
		}
		$data['timestampLink'] = $link;

		$currentAndLastLinks = '';
		if ( !$type == RC_LOG || $type == RC_NEW ) {
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

		if ( $rcObj->mAttribs['rc_type'] == RC_LOG ) {
			$data['logEntry'] = $this->insertLogEntry( $rcObj );
		} elseif ( $this->isCategorizationWithoutRevision( $rcObj ) ) {
			$data['comment'] = $this->insertComment( $rcObj );
		} else {
			# User links
			$data['userLink'] = $rcObj->userlink;
			$data['userTalkLink'] = $rcObj->usertalklink;
			$data['comment'] = $this->insertComment( $rcObj );
		}

		# Rollback
		$data['rollback'] = $this->getRollback( $rcObj );

		# Tags
		$data['tags'] = $this->getTags( $rcObj, $classes );

		// give the hook a chance to modify the data
		$success = Hooks::run( 'EnhancedChangesListModifyLineData',
			[ $this, &$data, $block, $rcObj, &$classes ] );
		if ( !$success ) {
			// skip entry if hook aborted it
			return [];
		}

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

		// everything else: makes it easier for extensions to add or remove data
		$lineParams['data'] = array_values( $data );

		return $lineParams;
	}

	/**
	 * Generates amount of changes (linking to diff ) & link to history.
	 *
	 * @param array $block
	 * @param array $queryParams
	 * @param bool $allLogs
	 * @param bool $isnew
	 * @param bool $namehidden
	 * @return string
	 */
	protected function getLogText( $block, $queryParams, $allLogs, $isnew, $namehidden ) {
		if ( empty( $block ) ) {
			return '';
		}

		# Changes message
		static $nchanges = [];
		static $sinceLastVisitMsg = [];

		$n = count( $block );
		if ( !isset( $nchanges[$n] ) ) {
			$nchanges[$n] = $this->msg( 'nchanges' )->numParams( $n )->escaped();
		}

		$sinceLast = 0;
		$unvisitedOldid = null;
		/** @var $rcObj RCCacheEntry */
		foreach ( $block as $rcObj ) {
			// Same logic as below inside main foreach
			if ( $rcObj->watched && $rcObj->mAttribs['rc_timestamp'] >= $rcObj->watched ) {
				$sinceLast++;
				$unvisitedOldid = $rcObj->mAttribs['rc_last_oldid'];
			}
		}
		if ( !isset( $sinceLastVisitMsg[$sinceLast] ) ) {
			$sinceLastVisitMsg[$sinceLast] =
				$this->msg( 'enhancedrc-since-last-visit' )->numParams( $sinceLast )->escaped();
		}

		$currentRevision = 0;
		foreach ( $block as $rcObj ) {
			if ( !$currentRevision ) {
				$currentRevision = $rcObj->mAttribs['rc_this_oldid'];
			}
		}

		# Total change link
		$links = [];
		/** @var $block0 RecentChange */
		$block0 = $block[0];
		$last = $block[count( $block ) - 1];
		if ( !$allLogs ) {
			if ( !ChangesList::userCan( $rcObj, Revision::DELETED_TEXT, $this->getUser() ) ||
				$isnew ||
				$rcObj->mAttribs['rc_type'] == RC_CATEGORIZE
			) {
				$links['total-changes'] = $nchanges[$n];
			} else {
				$links['total-changes'] = $this->linkRenderer->makeKnownLink(
					$block0->getTitle(),
					new HtmlArmor( $nchanges[$n] ),
					[ 'class' => 'mw-changeslist-groupdiff' ],
					$queryParams + [
						'diff' => $currentRevision,
						'oldid' => $last->mAttribs['rc_last_oldid'],
					]
				);
				if ( $sinceLast > 0 && $sinceLast < $n ) {
					$links['total-changes-since-last'] = $this->linkRenderer->makeKnownLink(
							$block0->getTitle(),
							new HtmlArmor( $sinceLastVisitMsg[$sinceLast] ),
							[ 'class' => 'mw-changeslist-groupdiff' ],
							$queryParams + [
								'diff' => $currentRevision,
								'oldid' => $unvisitedOldid,
							]
						);
				}
			}
		}

		# History
		if ( $allLogs || $rcObj->mAttribs['rc_type'] == RC_CATEGORIZE ) {
			// don't show history link for logs
		} elseif ( $namehidden || !$block0->getTitle()->exists() ) {
			$links['history'] = $this->message['enhancedrc-history'];
		} else {
			$params = $queryParams;
			$params['action'] = 'history';

			$links['history'] = $this->linkRenderer->makeKnownLink(
					$block0->getTitle(),
					new HtmlArmor( $this->message['enhancedrc-history'] ),
					[ 'class' => 'mw-changeslist-history' ],
					$params
				);
		}

		# Allow others to alter, remove or add to these links
		Hooks::run( 'EnhancedChangesList::getLogText',
			[ $this, &$links, $block ] );

		if ( !$links ) {
			return '';
		}

		$logtext = implode( $this->message['pipe-separator'], $links );
		$logtext = $this->msg( 'parentheses' )->rawParams( $logtext )->escaped();
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

		$query['curid'] = $rcObj->mAttribs['rc_cur_id'];

		$type = $rcObj->mAttribs['rc_type'];
		$logType = $rcObj->mAttribs['rc_log_type'];
		$classes = $this->getHTMLClasses( $rcObj, $rcObj->watched );
		$classes[] = 'mw-enhanced-rc';

		if ( $logType ) {
			# Log entry
			$classes[] = Sanitizer::escapeClass( 'mw-changeslist-log-' . $logType );
		} else {
			$classes[] = Sanitizer::escapeClass( 'mw-changeslist-ns' .
				$rcObj->mAttribs['rc_namespace'] . '-' . $rcObj->mAttribs['rc_title'] );
		}

		# Flag and Timestamp
		$data['recentChangesFlags'] = [
			'newpage' => $type == RC_NEW,
			'minor' => $rcObj->mAttribs['rc_minor'],
			'unpatrolled' => $rcObj->unpatrolled,
			'bot' => $rcObj->mAttribs['rc_bot'],
		];
		// timestamp is not really a link here, but is called timestampLink
		// for consistency with EnhancedChangesListModifyLineData
		$data['timestampLink'] = $rcObj->timestamp;

		# Article or log link
		if ( $logType ) {
			$logPage = new LogPage( $logType );
			$logTitle = SpecialPage::getTitleFor( 'Log', $logType );
			$logName = $logPage->getName()->text();
			$data['logLink'] = $this->msg( 'parentheses' )
				->rawParams(
					$this->linkRenderer->makeKnownLink( $logTitle, $logName )
				)->escaped();
		} else {
			$data['articleLink'] = $this->getArticleLink( $rcObj, $rcObj->unpatrolled, $rcObj->watched );
		}

		# Diff and hist links
		if ( $type != RC_LOG && $type != RC_CATEGORIZE ) {
			$query['action'] = 'history';
			$data['historyLink'] = $this->getDiffHistLinks( $rcObj, $query );
		}
		$data['separatorAfterLinks'] = ' <span class="mw-changeslist-separator">. .</span> ';

		# Character diff
		if ( $this->getConfig()->get( 'RCShowChangedSize' ) ) {
			$cd = $this->formatCharacterDifference( $rcObj );
			if ( $cd !== '' ) {
				$data['characterDiff'] = $cd;
				$data['separatorAftercharacterDiff'] = ' <span class="mw-changeslist-separator">. .</span> ';
			}
		}

		if ( $type == RC_LOG ) {
			$data['logEntry'] = $this->insertLogEntry( $rcObj );
		} elseif ( $this->isCategorizationWithoutRevision( $rcObj ) ) {
			$data['comment'] = $this->insertComment( $rcObj );
		} else {
			$data['userLink'] = $rcObj->userlink;
			$data['userTalkLink'] = $rcObj->usertalklink;
			$data['comment'] = $this->insertComment( $rcObj );
			if ( $type == RC_CATEGORIZE ) {
				$data['historyLink'] = $this->getDiffHistLinks( $rcObj, $query );
			}
			$data['rollback'] = $this->getRollback( $rcObj );
		}

		# Tags
		$data['tags'] = $this->getTags( $rcObj, $classes );

		# Show how many people are watching this if enabled
		$data['watchingUsers'] = $this->numberofWatchingusers( $rcObj->numberofWatchingusers );

		// give the hook a chance to modify the data
		$success = Hooks::run( 'EnhancedChangesListModifyBlockLineData',
			[ $this, &$data, $rcObj ] );
		if ( !$success ) {
			// skip entry if hook aborted it
			return '';
		}

		$line = Html::openElement( 'table', [ 'class' => $classes ] ) .
			Html::openElement( 'tr' );
		$line .= '<td class="mw-enhanced-rc"><span class="mw-enhancedchanges-arrow-space"></span>';

		if ( isset( $data['recentChangesFlags'] ) ) {
			$line .= $this->recentChangesFlags( $data['recentChangesFlags'] );
			unset( $data['recentChangesFlags'] );
		}

		if ( isset( $data['timestampLink'] ) ) {
			$line .= '&#160;' . $data['timestampLink'];
			unset( $data['timestampLink'] );
		}
		$line .= '&#160;</td><td>';

		// everything else: makes it easier for extensions to add or remove data
		$line .= implode( '', $data );

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
	 * @param array $query array of key/value pairs to append as a query string
	 * @return string HTML
	 */
	public function getDiffHistLinks( RCCacheEntry $rc, array $query ) {
		$pageTitle = $rc->getTitle();
		if ( $rc->getAttribute( 'rc_type' ) == RC_CATEGORIZE ) {
			// For categorizations we must swap the category title with the page title!
			$pageTitle = Title::newFromID( $rc->getAttribute( 'rc_cur_id' ) );
		}

		$retVal = ' ' . $this->msg( 'parentheses' )
				->rawParams( $rc->difflink . $this->message['pipe-separator']
					. $this->linkRenderer->makeKnownLink(
						$pageTitle,
						new HtmlArmor( $this->message['hist'] ),
						[ 'class' => 'mw-changeslist-history' ],
						$query
					) )->escaped();
		return $retVal;
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
			if ( count( $block ) < 2 ) {
				$blockOut .= $this->recentChangesBlockLine( array_shift( $block ) );
			} else {
				$blockOut .= $this->recentChangesBlockGroup( $block );
			}
		}

		return '<div>' . $blockOut . '</div>';
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
