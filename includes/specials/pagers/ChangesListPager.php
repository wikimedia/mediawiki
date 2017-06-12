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
 * Pager for ChangesListSpecialPage
 * @ingroup Pager
 */
use MediaWiki\MediaWikiServices;

class ChangesListPager extends RangeChronologicalPager {

	/**
	 * getQueryInfo() passes this to ChangeTags::modifyDisplayQuery() as the $filter_tag parameter.
	 * Subclasses wishing to filter by tag(s) should set this prior to getQueryInfo() being called.
	 * @var string
	 */
	protected $tagFilter = '';

	/** @var FormOptions */
	protected $opts;

	/** @var array */
	protected $filterGroups;

	/** @var ChangesList */
	protected $changesList;

	/**
	 * Whether category rows for hidden categories should be dropped from the output
	 * @var boolean
	 */
	protected $dropHiddenCats;

	/**
	 * Whether the number of users watching each page should be fetched
	 * @var boolean
	 */
	protected $showWatcherCount;

	/** @var WatchedItemStore */
	protected $watchedItemStore;

	/**
	 * Cache containing the number of watchers for each page.
	 * [ ns => [ dbkey => numWatchers ] ]
	 * @var array
	 */
	protected $watcherCache;

	/** @var integer */
	protected $rowCounter = 1;

	/**
	 * @param IContextSource $context
	 * @param FormOptions $opts
	 * @param array $filterGroups Array of ChangesListFilterGroup objects
	 */
	public function __construct( IContextSource $context, FormOptions $opts, array $filterGroups ) {
		parent::__construct( $context );

		$this->opts = $opts;
		$this->filterGroups = $filterGroups;
		$this->dropHiddenCats = !$this->getUser()->getBoolOption( 'showhiddencats' );
		$this->changesList = ChangesList::newFromContext( $context, $filterGroups );
		$this->showWatcherCount = $this->getConfig()->get( 'RCShowWatchingUsers' ) &&
			$this->getUser()->getOption( 'shownumberswatching' );
		if ( $this->showWatcherCount ) {
			$this->watchedItemStore = MediaWikiServices::getInstance()->getWatchedItemStore();
			$this->watcherCache = [];
		}
	}

	public function getQueryInfo() {
		$dbr = $this->getDatabase();
		$queryInfo = [
			'tables' => [ 'recentchanges' ],
			'fields' => RecentChange::selectFields(),
			'conds' => $this->getNamespaceCond(),
			'options' => [],
			'join_conds' => []
		];

		ChangeTags::modifyDisplayQuery(
			$queryInfo['tables'],
			$queryInfo['fields'],
			$queryInfo['conds'],
			$queryInfo['join_conds'],
			$queryInfo['options'],
			$this->tagFilter
		);

		foreach ( $this->filterGroups as $filterGroup ) {
			// URL parameters can be per-group, like 'userExpLevel',
			// or per-filter, like 'hideminor'.
			if ( $filterGroup->isPerGroupRequestParameter() ) {
				$filterGroup->modifyQuery( $dbr, $this, $queryInfo['tables'], $queryInfo['fields'],
					$queryInfo['conds'], $queryInfo['query_options'], $queryInfo['join_conds'],
					$this->opts[$filterGroup->getName()]
				);
			} else {
				foreach ( $filterGroup->getFilters() as $filter ) {
					if ( $this->opts[$filter->getName()] ) {
						$filter->modifyQuery( $dbr, $this, $queryInfo['tables'], $queryInfo['fields'],
							$queryInfo['conds'], $queryInfo['query_options'], $queryInfo['join_conds']
						);
					}
				}
			}
		}

		$this->runQueryInfoHook( $queryInfo );

		return $queryInfo;
	}

	protected function runQueryInfoHook( &$queryInfo ) {
		return Hooks::run(
			'ChangesListSpecialPageQuery',
			[
				get_class( $this ), // FIXME maybe introduce a specialpagename parameter
				&$queryInfo['tables'],
				&$queryInfo['fields'],
				&$queryInfo['conds'],
				&$queryInfo['options'],
				&$queryInfo['join_conds'],
				$this->opts
			]
		);
	}

	protected function getNamespaceCond() {
		$dbr = $this->getDatabase();
		if ( $this->opts['namespace'] !== '' ) {
			$namespaces = explode( ';', $this->opts['namespace'] );

			if ( $opts[ 'associated' ] ) {
				$associatedNamespaces = array_map(
					function ( $ns ) {
						return MWNamespace::getAssociated( $ns );
					},
					$namespaces
				);
				$namespaces = array_unique( array_merge( $namespaces, $associatedNamespaces ) );
			}

			if ( count( $namespaces ) === 1 ) {
				$operator = $this->opts['invert'] ? '!=' : '=';
				$value = $dbr->addQuotes( reset( $namespaces ) );
			} else {
				$operator = $this->opts['invert'] ? 'NOT IN' : 'IN';
				sort( $namespaces );
				$value = '(' . $dbr->makeList( $namespaces ) . ')';
			}
			return [ "rc_namespace $operator $value" ];
		}

		return [];
	}

	public function getIndexField() {
		return 'rc_timestamp';
	}

	public function doBatchLookups() {
		$this->mResult->seek( 0 );
		$batch = new LinkBatch;
		// Pre-cache each row's title and relevant user / user talk pages, and titles referenced
		// by log entries
		foreach ( $this->mResult as $row ) {
			$batch->add( $row->rc_namespace, $row->rc_title );
			$batch->add( NS_USER, $row->rc_user_text );
			$batch->add( NS_USER_TALK, $row->rc_user_text );
			if ( $row->rc_source === RecentChange::SRC_LOG ) {
				$formatter = LogFormatter::newFromRow( $row );
				foreach ( $formatter->getPreloadTitles() as $title ) {
					$batch->addObj( $title );
				}
			}
		}
		$batch->execute();
		$this->mResult->seek( 0 );

		$this->changesList->initChangesListRows( $this->mResult );
	}

	public function getStartBody() {
		return $this->changesList->beginRecentChangesList();
	}

	public function getEndBody() {
		return $this->changesList->endRecentChangesList();
	}

	public function formatRow( $row ) {
		$rc = RecentChange::newFromRow( $row );

		// If the user has hidden categories disabled, drop RC_CATEGORIZE rows for hidden categories
		if (
			$this->dropHiddenCats &&
			$rc->getAttribute( 'rc_type' ) == RC_CATEGORIZE &&
			$rc->getParam( 'hidden-cat' )
		) {
			return '';
		}

		$rc->counter = $this->rowCounter++;

		// Add watcher count if enabled
		$ns = $rc->getAttribute( 'rc_namespace' );
		$title = $rc->getAttribute( 'rc_title' );
		if ( $this->showWatcherCount && $ns >= 0 ) {
			if ( !isset( $this->watcherCache[ $ns ][ $title ] ) ) {
				$titleValue = new TitleValue( (int)$ns, $title );
				$this->watcherCache[ $ns ][ $title ] = $this->watchedItemStore->countWatchers( $titleValue );
			}
			$rc->numberofWatchingusers = $this->watcherCache[ $ns ][ $title ];
		}

		return $this->changesList->recentChangesLine( $rc, $this->shouldHighlightAsWatched( $row ), $rc->counter );
	}

	/**
	 * Whether this row should be highlighted as watched.
	 *
	 * The base class always returns false, but subclasses can override this to
	 * cause certain rows to be highlighted.
	 *
	 * @param object $row
	 * @return bool
	 */
	public function shouldHighlightAsWatched( $row ) {
		return false;
	}
}
