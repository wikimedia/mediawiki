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
 */

namespace MediaWiki\Specials;

use MediaWiki\ChangeTags\ChangeTagsStore;
use MediaWiki\Html\FormOptions;
use MediaWiki\Html\Html;
use MediaWiki\Language\MessageParser;
use MediaWiki\MainConfigNames;
use MediaWiki\RecentChanges\RecentChange;
use MediaWiki\Title\Title;
use MediaWiki\User\Options\UserOptionsLookup;
use MediaWiki\User\TempUser\TempUserConfig;
use MediaWiki\User\UserIdentityUtils;
use MediaWiki\Watchlist\WatchedItemStoreInterface;
use MediaWiki\Xml\Xml;
use SearchEngineFactory;
use Wikimedia\Rdbms\SelectQueryBuilder;
use Wikimedia\Rdbms\Subquery;

/**
 * This is to display changes made to all articles linked in an article.
 *
 * @ingroup RecentChanges
 * @ingroup SpecialPage
 */
class SpecialRecentChangesLinked extends SpecialRecentChanges {
	/** @var bool|Title */
	protected $rclTargetTitle;

	private SearchEngineFactory $searchEngineFactory;
	private ChangeTagsStore $changeTagsStore;

	public function __construct(
		WatchedItemStoreInterface $watchedItemStore,
		MessageParser $messageParser,
		UserOptionsLookup $userOptionsLookup,
		SearchEngineFactory $searchEngineFactory,
		ChangeTagsStore $changeTagsStore,
		UserIdentityUtils $userIdentityUtils,
		TempUserConfig $tempUserConfig
	) {
		parent::__construct(
			$watchedItemStore,
			$messageParser,
			$userOptionsLookup,
			$changeTagsStore,
			$userIdentityUtils,
			$tempUserConfig
		);
		$this->mName = 'Recentchangeslinked';
		$this->searchEngineFactory = $searchEngineFactory;
		$this->changeTagsStore = $changeTagsStore;
	}

	public function getDefaultOptions() {
		$opts = parent::getDefaultOptions();
		$opts->add( 'target', '' );
		$opts->add( 'showlinkedto', false );

		return $opts;
	}

	public function parseParameters( $par, FormOptions $opts ) {
		$opts['target'] = $par;
	}

	/**
	 * FIXME: Port useful changes from SpecialRecentChanges
	 *
	 * @inheritDoc
	 */
	protected function doMainQuery( $tables, $select, $conds, $query_options,
		$join_conds, FormOptions $opts
	) {
		$target = $opts['target'];
		$showlinkedto = $opts['showlinkedto'];
		$limit = $opts['limit'];

		if ( $target === '' ) {
			return false;
		}
		$outputPage = $this->getOutput();
		$title = Title::newFromText( $target );
		if ( !$title || $title->isExternal() ) {
			$outputPage->addModuleStyles( 'mediawiki.codex.messagebox.styles' );
			$outputPage->addHTML(
				Html::errorBox( $this->msg( 'allpagesbadtitle' )->parse(), '', 'mw-recentchangeslinked-errorbox' )
			);
			return false;
		}

		$outputPage->setPageTitleMsg(
			$this->msg( 'recentchangeslinked-title' )->plaintextParams( $title->getPrefixedText() )
		);

		/*
		 * Ordinary links are in the pagelinks table, while transclusions are
		 * in the templatelinks table, categorizations in categorylinks and
		 * image use in imagelinks.  We need to somehow combine all these.
		 * Special:Whatlinkshere does this by firing multiple queries and
		 * merging the results, but the code we inherit from our parent class
		 * expects only one result set so we use UNION instead.
		 */
		$dbr = $this->getDB();
		$id = $title->getArticleID();
		$ns = $title->getNamespace();
		$dbkey = $title->getDBkey();

		$rcQuery = RecentChange::getQueryInfo();
		$tables = array_unique( array_merge( $rcQuery['tables'], $tables ) );
		$select = array_unique( array_merge( $rcQuery['fields'], $select ) );
		$join_conds = array_merge( $rcQuery['joins'], $join_conds );

		// Join with watchlist and watchlist_expiry tables to highlight watched rows.
		$this->addWatchlistJoins( $dbr, $tables, $select, $join_conds, $conds );

		// JOIN on page, used for 'last revision' filter highlight
		$tables[] = 'page';
		$join_conds['page'] = [ 'LEFT JOIN', 'rc_cur_id=page_id' ];
		$select[] = 'page_latest';

		$tagFilter = $opts['tagfilter'] !== '' ? explode( '|', $opts['tagfilter'] ) : [];
		$this->changeTagsStore->modifyDisplayQuery(
			$tables,
			$select,
			$conds,
			$join_conds,
			$query_options,
			$tagFilter,
			$opts['inverttags']
		);

		if ( $dbr->unionSupportsOrderAndLimit() ) {
			if ( in_array( 'DISTINCT', $query_options ) ) {
				// ChangeTagsStore::modifyDisplayQuery() will have added DISTINCT.
				// To prevent this from causing query performance problems, we need to add
				// a GROUP BY, and add rc_id to the ORDER BY.
				$order = [
					'GROUP BY' => [ 'rc_timestamp', 'rc_id' ],
					'ORDER BY' => [ 'rc_timestamp DESC', 'rc_id DESC' ]
				];
			} else {
				$order = [ 'ORDER BY' => 'rc_timestamp DESC' ];
			}
		} else {
			$order = [];
		}

		if ( !$this->runMainQueryHook( $tables, $select, $conds, $query_options, $join_conds,
			$opts )
		) {
			return false;
		}

		if ( $ns === NS_CATEGORY && !$showlinkedto ) {
			// special handling for categories
			// XXX: should try to make this less kludgy
			$link_tables = [ 'categorylinks' ];
			$showlinkedto = true;
		} else {
			// for now, always join on these tables; really should be configurable as in whatlinkshere
			$link_tables = [ 'pagelinks', 'templatelinks' ];
			// imagelinks only contains links to pages in NS_FILE
			if ( $ns === NS_FILE || !$showlinkedto ) {
				$link_tables[] = 'imagelinks';
			}
		}

		if ( $id == 0 && !$showlinkedto ) {
			return false; // nonexistent pages can't link to any pages
		}

		// field name prefixes for all the various tables we might want to join with
		$prefix = [
			'pagelinks' => 'pl',
			'templatelinks' => 'tl',
			'categorylinks' => 'cl',
			'imagelinks' => 'il'
		];

		$subsql = []; // SELECT statements to combine with UNION

		foreach ( $link_tables as $link_table ) {
			$queryBuilder = $dbr->newSelectQueryBuilder();
			$linksMigration = \MediaWiki\MediaWikiServices::getInstance()->getLinksMigration();
			$queryBuilder = $queryBuilder
				->tables( $tables )
				->fields( $select )
				->where( $conds )
				->caller( __METHOD__ )
				->options( $order + $query_options )
				->joinConds( $join_conds );
			$pfx = $prefix[$link_table];

			// imagelinks and categorylinks tables have no xx_namespace field,
			// and have xx_to instead of xx_title
			if ( $link_table == 'imagelinks' ) {
				$link_ns = NS_FILE;
			} elseif ( $link_table == 'categorylinks' ) {
				$link_ns = NS_CATEGORY;
			} else {
				$link_ns = 0;
			}

			if ( $showlinkedto ) {
				// find changes to pages linking to this page
				if ( $link_ns ) {
					if ( $ns != $link_ns ) {
						continue;
					} // should never happen, but check anyway
					$queryBuilder->where( [ "{$pfx}_to" => $dbkey ] );
				} else {
					if ( isset( $linksMigration::$mapping[$link_table] ) ) {
						$queryBuilder->where( $linksMigration->getLinksConditions( $link_table, $title ) );
					} else {
						$queryBuilder->where( [ "{$pfx}_namespace" => $ns, "{$pfx}_title" => $dbkey ] );
					}
				}
				$queryBuilder->join( $link_table, null, "rc_cur_id = {$pfx}_from" );
			} else {
				// find changes to pages linked from this page
				$queryBuilder->where( [ "{$pfx}_from" => $id ] );
				if ( $link_table == 'imagelinks' || $link_table == 'categorylinks' ) {
					$queryBuilder->where( [ "rc_namespace" => $link_ns ] );
					$queryBuilder->join( $link_table, null, "rc_title = {$pfx}_to" );
				} else {
					// TODO: Move this to LinksMigration
					if ( isset( $linksMigration::$mapping[$link_table] ) ) {
						$queryInfo = $linksMigration->getQueryInfo( $link_table, $link_table );
						[ $nsField, $titleField ] = $linksMigration->getTitleFields( $link_table );
						if ( in_array( 'linktarget', $queryInfo['tables'] ) ) {
							$joinTable = 'linktarget';
						} else {
							$joinTable = $link_table;
						}
						$queryBuilder->join(
							$joinTable,
							null,
							[ "rc_namespace = {$nsField}", "rc_title = {$titleField}" ]
						);
						if ( in_array( 'linktarget', $queryInfo['tables'] ) ) {
							$queryBuilder->joinConds( $queryInfo['joins'] );
							$queryBuilder->table( $link_table );
						}
					} else {
						$queryBuilder->join(
							$link_table,
							null,
							[ "rc_namespace = {$pfx}_namespace", "rc_title = {$pfx}_title" ]
						);
					}
				}
			}

			if ( $dbr->unionSupportsOrderAndLimit() ) {
				$queryBuilder->limit( $limit );
			}

			$subsql[] = $queryBuilder;
		}

		if ( count( $subsql ) == 0 ) {
			return false; // should never happen
		}
		if ( count( $subsql ) == 1 && $dbr->unionSupportsOrderAndLimit() ) {
			return $subsql[0]
				->setMaxExecutionTime( $this->getConfig()->get( MainConfigNames::MaxExecutionTimeForExpensiveQueries ) )
				->caller( __METHOD__ )->fetchResultSet();
		} else {
			$unionQueryBuilder = $dbr->newUnionQueryBuilder()->caller( __METHOD__ );
			foreach ( $subsql as $selectQueryBuilder ) {
				$unionQueryBuilder->add( $selectQueryBuilder );
			}
			return $dbr->newSelectQueryBuilder()
				->select( '*' )
				->from(
					new Subquery( $unionQueryBuilder->getSQL() ),
					'main'
				)
				->orderBy( 'rc_timestamp', SelectQueryBuilder::SORT_DESC )
				->setMaxExecutionTime( $this->getConfig()->get( MainConfigNames::MaxExecutionTimeForExpensiveQueries ) )
				->limit( $limit )
				->caller( __METHOD__ )->fetchResultSet();
		}
	}

	public function setTopText( FormOptions $opts ) {
		$target = $this->getTargetTitle();
		if ( $target ) {
			$this->getOutput()->addBacklinkSubtitle( $target );
			$this->getSkin()->setRelevantTitle( $target );
		}
	}

	/**
	 * Get options to be displayed in a form
	 *
	 * @param FormOptions $opts
	 * @return array
	 */
	public function getExtraOptions( $opts ) {
		$extraOpts = parent::getExtraOptions( $opts );

		$opts->consumeValues( [ 'showlinkedto', 'target' ] );

		$extraOpts['target'] = [ $this->msg( 'recentchangeslinked-page' )->escaped(),
			Xml::input( 'target', 40, str_replace( '_', ' ', $opts['target'] ) ) . ' ' .
			Xml::check( 'showlinkedto', $opts['showlinkedto'], [ 'id' => 'showlinkedto' ] ) . ' ' .
			Xml::label( $this->msg( 'recentchangeslinked-to' )->text(), 'showlinkedto' ) ];

		$this->addHelpLink( 'Help:Related changes' );
		return $extraOpts;
	}

	/**
	 * @return Title
	 */
	private function getTargetTitle() {
		if ( $this->rclTargetTitle === null ) {
			$opts = $this->getOptions();
			if ( isset( $opts['target'] ) && $opts['target'] !== '' ) {
				$this->rclTargetTitle = Title::newFromText( $opts['target'] );
			} else {
				$this->rclTargetTitle = false;
			}
		}

		return $this->rclTargetTitle;
	}

	/**
	 * Return an array of subpages beginning with $search that this special page will accept.
	 *
	 * @param string $search Prefix to search for
	 * @param int $limit Maximum number of results to return (usually 10)
	 * @param int $offset Number of results to skip (usually 0)
	 * @return string[] Matching subpages
	 */
	public function prefixSearchSubpages( $search, $limit, $offset ) {
		return $this->prefixSearchString( $search, $limit, $offset, $this->searchEngineFactory );
	}

	protected function outputNoResults() {
		$targetTitle = $this->getTargetTitle();
		if ( $targetTitle === false ) {
			$this->getOutput()->addHTML(
				Html::rawElement(
					'div',
					[ 'class' => [ 'mw-changeslist-empty', 'mw-changeslist-notargetpage' ] ],
					$this->msg( 'recentchanges-notargetpage' )->parse()
				)
			);
		} elseif ( !$targetTitle || $targetTitle->isExternal() ) {
			$this->getOutput()->addHTML(
				Html::rawElement(
					'div',
					[ 'class' => [ 'mw-changeslist-empty', 'mw-changeslist-invalidtargetpage' ] ],
					$this->msg( 'allpagesbadtitle' )->parse()
				)
			);
		} else {
			parent::outputNoResults();
		}
	}
}

/**
 * Retain the old class name for backwards compatibility.
 * @deprecated since 1.41
 */
class_alias( SpecialRecentChangesLinked::class, 'SpecialRecentChangesLinked' );
