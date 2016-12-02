<?php
/**
 * Implements Special:Recentchanges
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

use MediaWiki\MediaWikiServices;

/**
 * A special page that lists last changes made to the wiki
 *
 * @ingroup SpecialPage
 */
class SpecialRecentChanges extends ChangesListSpecialPage {
	// @codingStandardsIgnoreStart Needed "useless" override to change parameters.
	public function __construct( $name = 'Recentchanges', $restriction = '' ) {
		parent::__construct( $name, $restriction );
	}
	// @codingStandardsIgnoreEnd

	/**
	 * The available duration options for maximum changes age selector.
	 *
	 * @var array
	 */
	public static $changesAgeDurations = [
		3600, // 1 hour
		7200, // 2 hours
		21600, // 6 hours,
		43200, // 12 hours,
		86400, // 1 day
		259200, // 3 days
		604800, // 1 week,
		2592000, // 1 month
	];

	/**
	 * Main execution point
	 *
	 * @param string $subpage
	 */
	public function execute( $subpage ) {
		// Backwards-compatibility: redirect to new feed URLs
		$feedFormat = $this->getRequest()->getVal( 'feed' );
		if ( !$this->including() && $feedFormat ) {
			$query = $this->getFeedQuery();
			$query['feedformat'] = $feedFormat === 'atom' ? 'atom' : 'rss';
			$this->getOutput()->redirect( wfAppendQuery( wfScript( 'api' ), $query ) );

			return;
		}

		// 10 seconds server-side caching max
		$this->getOutput()->setCdnMaxage( 10 );
		// Check if the client has a cached version
		$lastmod = $this->checkLastModified();
		if ( $lastmod === false ) {
			return;
		}

		$this->addHelpLink(
			'//meta.wikimedia.org/wiki/Special:MyLanguage/Help:Recent_changes',
			true
		);
		parent::execute( $subpage );
	}

	/**
	 * Get a FormOptions object containing the default options
	 *
	 * @return FormOptions
	 */
	public function getDefaultOptions() {
		$opts = parent::getDefaultOptions();
		$user = $this->getUser();

		$opts->add( 'maxage', $user->getIntOption( 'rcmaxage' ) ); // in seconds
		$opts->add( 'limit', $user->getIntOption( 'rclimit' ) );
		$opts->add( 'from', '' );

		$opts->add( 'hideminor', $user->getBoolOption( 'hideminor' ) );
		$opts->add( 'hidebots', true );
		$opts->add( 'hideanons', false );
		$opts->add( 'hideliu', false );
		$opts->add( 'hidepatrolled', $user->getBoolOption( 'hidepatrolled' ) );
		$opts->add( 'hidemyself', false );
		$opts->add( 'hidecategorization', $user->getBoolOption( 'hidecategorization' ) );

		$opts->add( 'categories', '' );
		$opts->add( 'categories_any', false );
		$opts->add( 'tagfilter', '' );

		$opts->add( 'panel-collapsed', $user->getBoolOption( 'rcpanelcollapsed' ) );

		return $opts;
	}

	/**
	 * Get custom show/hide filters
	 *
	 * @return array Map of filter URL param names to properties (msg/default)
	 */
	protected function getCustomFilters() {
		if ( $this->customFilters === null ) {
			$this->customFilters = parent::getCustomFilters();
			Hooks::run( 'SpecialRecentChangesFilters', [ $this, &$this->customFilters ], '1.23' );
		}

		return $this->customFilters;
	}

	/**
	 * @param \FormOptions $opts
	 *
	 * @return \FormOptions
	 */
	protected function fetchOptionsFromRequest( $opts ) {
		$opts->fetchValuesFromRequest( $this->getRequest(), null, true );

		return $opts;
	}

	/**
	 * Process $par and put options found in $opts. Used when including the page.
	 *
	 * @param string $par
	 * @param FormOptions $opts
	 */
	public function parseParameters( $par, FormOptions $opts ) {
		$bits = preg_split( '/\s*,\s*/', trim( $par ) );
		foreach ( $bits as $bit ) {
			if ( 'hidebots' === $bit ) {
				$opts['hidebots'] = true;
			}
			if ( 'bots' === $bit ) {
				$opts['hidebots'] = false;
			}
			if ( 'hideminor' === $bit ) {
				$opts['hideminor'] = true;
			}
			if ( 'minor' === $bit ) {
				$opts['hideminor'] = false;
			}
			if ( 'hideliu' === $bit ) {
				$opts['hideliu'] = true;
			}
			if ( 'hidepatrolled' === $bit ) {
				$opts['hidepatrolled'] = true;
			}
			if ( 'hideanons' === $bit ) {
				$opts['hideanons'] = true;
			}
			if ( 'hidemyself' === $bit ) {
				$opts['hidemyself'] = true;
			}
			if ( 'hidecategorization' === $bit ) {
				$opts['hidecategorization'] = true;
			}

			if ( is_numeric( $bit ) ) {
				$opts['limit'] = $bit;
			}

			$m = [];
			if ( preg_match( '/^limit=(\d+)$/', $bit, $m ) ) {
				$opts['limit'] = $m[1];
			}
			if ( preg_match( '/^maxage=(\d+)$/', $bit, $m ) ) {
				$opts['maxage'] = $m[1];
			}
			if ( preg_match( '/^namespace=(\d+)$/', $bit, $m ) ) {
				$opts['namespace'] = $m[1];
			}
			if ( preg_match( '/^tagfilter=(.*)$/', $bit, $m ) ) {
				$opts['tagfilter'] = $m[1];
			}
		}
	}

	public function validateOptions( FormOptions $opts ) {
		$opts->validateIntBounds( 'limit', 0, 5000 );
		parent::validateOptions( $opts );
	}

	/**
	 * Return an array of conditions depending of options set in $opts
	 *
	 * @param FormOptions $opts
	 * @return array
	 */
	public function buildMainQueryConds( FormOptions $opts ) {
		$dbr = $this->getDB();
		$conds = parent::buildMainQueryConds( $opts );

		// Calculate cutoff
		$cutoff_unixtime = time() - $opts['maxage'];
		$cutoff = $dbr->timestamp( $cutoff_unixtime );

		$fromValid = preg_match( '/^[0-9]{14}$/', $opts['from'] );
		if ( $fromValid && $opts['from'] > wfTimestamp( TS_MW, $cutoff ) ) {
			$cutoff = $dbr->timestamp( $opts['from'] );
		} else {
			$opts->reset( 'from' );
		}

		$conds[] = 'rc_timestamp >= ' . $dbr->addQuotes( $cutoff );

		return $conds;
	}

	/**
	 * Process the query
	 *
	 * @param array $conds
	 * @param FormOptions $opts
	 * @return bool|ResultWrapper Result or false (for Recentchangeslinked only)
	 */
	public function doMainQuery( $conds, $opts ) {
		$dbr = $this->getDB();
		$user = $this->getUser();

		$tables = [ 'recentchanges' ];
		$fields = RecentChange::selectFields();
		$query_options = [];
		$join_conds = [];

		// JOIN on watchlist for users
		if ( $user->getId() && $user->isAllowed( 'viewmywatchlist' ) ) {
			$tables[] = 'watchlist';
			$fields[] = 'wl_user';
			$fields[] = 'wl_notificationtimestamp';
			$join_conds['watchlist'] = [ 'LEFT JOIN', [
				'wl_user' => $user->getId(),
				'wl_title=rc_title',
				'wl_namespace=rc_namespace'
			] ];
		}

		if ( $user->isAllowed( 'rollback' ) ) {
			$tables[] = 'page';
			$fields[] = 'page_latest';
			$join_conds['page'] = [ 'LEFT JOIN', 'rc_cur_id=page_id' ];
		}

		ChangeTags::modifyDisplayQuery(
			$tables,
			$fields,
			$conds,
			$join_conds,
			$query_options,
			$opts['tagfilter']
		);

		if ( !$this->runMainQueryHook( $tables, $fields, $conds, $query_options, $join_conds,
			$opts )
		) {
			return false;
		}

		// array_merge() is used intentionally here so that hooks can, should
		// they so desire, override the ORDER BY / LIMIT condition(s); prior to
		// MediaWiki 1.26 this used to use the plus operator instead, which meant
		// that extensions weren't able to change these conditions
		$query_options = array_merge( [
			'ORDER BY' => 'rc_timestamp DESC',
			'LIMIT' => $opts['limit'] ], $query_options );
		$rows = $dbr->select(
			$tables,
			$fields,
			// rc_new is not an ENUM, but adding a redundant rc_new IN (0,1) gives mysql enough
			// knowledge to use an index merge if it wants (it may use some other index though).
			$conds + [ 'rc_new' => [ 0, 1 ] ],
			__METHOD__,
			$query_options,
			$join_conds
		);

		// Build the final data
		if ( $this->getConfig()->get( 'AllowCategorizedRecentChanges' ) ) {
			$this->filterByCategories( $rows, $opts );
		}

		return $rows;
	}

	protected function runMainQueryHook( &$tables, &$fields, &$conds,
		&$query_options, &$join_conds, $opts
	) {
		return parent::runMainQueryHook( $tables, $fields, $conds, $query_options, $join_conds, $opts )
			&& Hooks::run(
				'SpecialRecentChangesQuery',
				[ &$conds, &$tables, &$join_conds, $opts, &$query_options, &$fields ],
				'1.23'
			);
	}

	protected function getDB() {
		return wfGetDB( DB_REPLICA, 'recentchanges' );
	}

	public function outputFeedLinks() {
		$this->addFeedLinks( $this->getFeedQuery() );
	}

	/**
	 * Get URL query parameters for action=feedrecentchanges API feed of current recent changes view.
	 *
	 * @return array
	 */
	protected function getFeedQuery() {
		$query = array_filter( $this->getOptions()->getAllValues(), function ( $value ) {
			// API handles empty parameters in a different way
			return $value !== '';
		} );
		$query['action'] = 'feedrecentchanges';
		$feedLimit = $this->getConfig()->get( 'FeedLimit' );
		if ( $query['limit'] > $feedLimit ) {
			$query['limit'] = $feedLimit;
		}

		return $query;
	}

	/**
	 * Build and output the actual changes list.
	 *
	 * @param ResultWrapper $rows Database rows
	 * @param FormOptions $opts
	 */
	public function outputChangesList( $rows, $opts ) {
		$limit = $opts['limit'];

		$showWatcherCount = $this->getConfig()->get( 'RCShowWatchingUsers' )
			&& $this->getUser()->getOption( 'shownumberswatching' );
		$watcherCache = [];

		$dbr = $this->getDB();

		$counter = 1;
		$list = ChangesList::newFromContext( $this->getContext() );
		$list->initChangesListRows( $rows );

		$userShowHiddenCats = $this->getUser()->getBoolOption( 'showhiddencats' );
		$rclistOutput = $list->beginRecentChangesList();
		foreach ( $rows as $obj ) {
			if ( $limit == 0 ) {
				break;
			}
			$rc = RecentChange::newFromRow( $obj );

			# Skip CatWatch entries for hidden cats based on user preference
			if (
				$rc->getAttribute( 'rc_type' ) == RC_CATEGORIZE &&
				!$userShowHiddenCats &&
				$rc->getParam( 'hidden-cat' )
			) {
				continue;
			}

			$rc->counter = $counter++;
			# Check if the page has been updated since the last visit
			if ( $this->getConfig()->get( 'ShowUpdatedMarker' )
				&& !empty( $obj->wl_notificationtimestamp )
			) {
				$rc->notificationtimestamp = ( $obj->rc_timestamp >= $obj->wl_notificationtimestamp );
			} else {
				$rc->notificationtimestamp = false; // Default
			}
			# Check the number of users watching the page
			$rc->numberofWatchingusers = 0; // Default
			if ( $showWatcherCount && $obj->rc_namespace >= 0 ) {
				if ( !isset( $watcherCache[$obj->rc_namespace][$obj->rc_title] ) ) {
					$watcherCache[$obj->rc_namespace][$obj->rc_title] =
						MediaWikiServices::getInstance()->getWatchedItemStore()->countWatchers(
							new TitleValue( (int)$obj->rc_namespace, $obj->rc_title )
						);
				}
				$rc->numberofWatchingusers = $watcherCache[$obj->rc_namespace][$obj->rc_title];
			}

			$changeLine = $list->recentChangesLine( $rc, !empty( $obj->wl_user ), $counter );
			if ( $changeLine !== false ) {
				$rclistOutput .= $changeLine;
				--$limit;
			}
		}
		$rclistOutput .= $list->endRecentChangesList();

		if ( $rows->numRows() === 0 ) {
			$this->getOutput()->addHTML(
				'<div class="mw-changeslist-empty">' .
				$this->msg( 'recentchanges-noresult' )->parse() .
				'</div>'
			);
			if ( !$this->including() ) {
				$this->getOutput()->setStatusCode( 404 );
			}
		} else {
			$this->getOutput()->addHTML( $rclistOutput );
		}
	}

	/**
	 * Set the text to be displayed above the changes
	 *
	 * @param FormOptions $opts
	 * @param int $numRows Number of rows in the result to show after this header
	 */
	public function doHeader( $opts, $numRows ) {
		$this->setTopText( $opts );

		$this->renderForm( $opts );

		$opts->consumeValues( [
			'namespace', 'invert', 'associated', 'tagfilter', 'categories', 'categories_any'
		] );

		if ( $this->getName() === 'Recentchanges' ) {
			Hooks::run( 'SpecialRecentChangesPanel', [ &$extraOpts, $opts ] );
		}
	}

	/**
	 * Send the text to be displayed above the options
	 *
	 * @param FormOptions $opts Unused
	 */
	function setTopText( FormOptions $opts ) {
		global $wgContLang;

		$message = $this->msg( 'recentchangestext' )->inContentLanguage();
		if ( !$message->isDisabled() ) {
			$this->getOutput()->addWikiText(
				Html::rawElement( 'div',
					[ 'lang' => $wgContLang->getHtmlCode(), 'dir' => $wgContLang->getDir() ],
					"\n" . $message->plain() . "\n"
				),
				/* $lineStart */ true,
				/* $interface */ false
			);
		}
	}

	/**
	 * Add page-specific modules.
	 */
	protected function addModules() {
		parent::addModules();
		$out = $this->getOutput();

		$out->addModules( 'mediawiki.special.recentchanges' );
		$out->enableOOUI();
	}

	/**
	 * Get last modified date, for client caching
	 * Don't use this if we are using the patrol feature, patrol changes don't
	 * update the timestamp
	 *
	 * @return string|bool
	 */
	public function checkLastModified() {
		$dbr = $this->getDB();
		$lastmod = $dbr->selectField( 'recentchanges', 'MAX(rc_timestamp)', false, __METHOD__ );

		return $lastmod;
	}

	/**
	 * Filter $rows by categories set in $opts
	 *
	 * @param ResultWrapper $rows Database rows
	 * @param FormOptions $opts
	 */
	function filterByCategories( &$rows, FormOptions $opts ) {
		$categories = array_map( 'trim', explode( '|', $opts['categories'] ) );

		if ( !count( $categories ) ) {
			return;
		}

		# Filter categories
		$cats = [];
		foreach ( $categories as $cat ) {
			$cat = trim( $cat );
			if ( $cat == '' ) {
				continue;
			}
			$cats[] = $cat;
		}

		# Filter articles
		$articles = [];
		$a2r = [];
		$rowsarr = [];
		foreach ( $rows as $k => $r ) {
			$nt = Title::makeTitle( $r->rc_namespace, $r->rc_title );
			$id = $nt->getArticleID();
			if ( $id == 0 ) {
				continue; # Page might have been deleted...
			}
			if ( !in_array( $id, $articles ) ) {
				$articles[] = $id;
			}
			if ( !isset( $a2r[$id] ) ) {
				$a2r[$id] = [];
			}
			$a2r[$id][] = $k;
			$rowsarr[$k] = $r;
		}

		# Shortcut?
		if ( !count( $articles ) || !count( $cats ) ) {
			return;
		}

		# Look up
		$catFind = new CategoryFinder;
		$catFind->seed( $articles, $cats, $opts['categories_any'] ? 'OR' : 'AND' );
		$match = $catFind->run();

		# Filter
		$newrows = [];
		foreach ( $match as $id ) {
			foreach ( $a2r[$id] as $rev ) {
				$k = $rev;
				$newrows[$k] = $rowsarr[$k];
			}
		}
		$rows = $newrows;
	}

	/**
	 * Makes change an option link which carries all the other options
	 *
	 * @param string $title Title
	 * @param array $override Options to override
	 * @param array $options Current options
	 * @param bool $active Whether to show the link in bold
	 * @return string
	 */
	function makeOptionsLink( $title, $override, $options, $active = false ) {
		$params = $override + $options;
		$linkRenderer = $this->getLinkRenderer();

		// Bug 36524: false values have be converted to "0" otherwise
		// wfArrayToCgi() will omit it them.
		foreach ( $params as &$value ) {
			if ( $value === false ) {
				$value = '0';
			}
		}
		unset( $value );

		return $linkRenderer->makeKnownLink( $this->getPageTitle(), $title, [], $params );
	}

	/**
	 * Render the OOUI form for filtering changes.
	 *
	 * @param \FormOptions $opts
	 */
	private function renderForm( FormOptions $opts ) {
		$output = $this->getOutput();
		$title = $this->getPageTitle();
		$form = new OOUI\FormLayout( [
			'method' => 'GET',
			'action' => wfScript(),
		] );

		$this->populateForm( $form, $opts );

		$form->addItems( [
			new OOUI\FieldLayout(
				new OOUI\ButtonInputWidget( [
					'name' => 'submit',
					'label' => $this->msg( 'recentchanges-submit' )->text(),
					'type' => 'submit',
					'flags' => [ 'primary', 'progressive' ],
				] ),
				[
					'label' => null,
					'align' => 'top',
				]
			),
		] );

		// The usage of $form->addItems is recommended instead of this hack,
		// but currently there is no Widget in oojs-ui to render as a hidden
		// input.
		// Phabricator Task: T152321
		// When this becomes available, replacement is advised.
		$form->appendContent( new \OOUI\HtmlSnippet(
			Html::hidden( 'title', $title->getPrefixedText() )
		) );
		$form->appendContent( new \OOUI\HtmlSnippet(
			Html::hidden( 'panel-collapsed', $opts['panel-collapsed'] )
		) );

		$layout = new OOUI\PanelLayout( [
			'expanded' => false,
			'padded' => true,
			'framed' => true,
			'infusable' => true,
			'id' => 'filterform-panel',
		] );
		$layout->appendContent( $form );

		$output->addHTML( $layout );
	}

	/**
	 * Populate the OOUI form.
	 *
	 * @param \OOUI\FormLayout $form
	 * @param \FormOptions $opts
	 */
	private function populateForm( \OOUI\FormLayout $form, FormOptions $opts ) {
		$language = $this->getLanguage();
		$user = $this->getUser();
		$timestamp = wfTimestampNow();

		$now = $language->userTimeAndDate( $timestamp, $user );
		$timenow = $language->userTime( $timestamp, $user );
		$datenow = $language->userDate( $timestamp, $user );

		$namespaceTagFieldset = new OOUI\FieldsetLayout( [
			'label' => $this->msg( 'recentchanges-fieldset-filter' )->text(),
			'items' => [
				new OOUI\FieldLayout(
					new MediaWiki\Widget\NamespaceInputWidget( [
						'includeAllValue' => '',
						'name' => 'namespace',
						'id' => 'namespace',
						'infusable' => true,
						'value' => $opts['namespace'],
					] ),
					[
						'label' => $this->msg( 'namespace' )->text(),
						'align' => 'left',
						'classes' => [ 'oo-ui-fieldLayout-narrow' ],
					]
				),
				new OOUI\HorizontalLayout( [
					'items' => [
						new OOUI\FieldLayout(
							new OOUI\CheckboxInputWidget( [
								'name' => 'invert',
								'id' => 'nsinvert',
								'selected' => $opts['invert'],
								'infusable' => true,
								'value' => '1', // for compatibility
							] ),
							[
								'label' => $this->msg( 'invert' )->text(),
								'align' => 'inline',
							]
						),
						new OOUI\FieldLayout(
							new OOUI\CheckboxInputWidget( [
								'name' => 'associated',
								'id' => 'nsassociated',
								'selected' => $opts['associated'],
								'infusable' => true,
								'title' => $this->msg( 'tooltip-namespace_association' )->text(),
								'value' => '1', // for compatibility
							] ),
							[
								'label' => $this->msg( 'namespace_association' )->text(),
								'align' => 'inline',
								'title' => $this->msg( 'tooltip-namespace_association' )->text(),
							]
						),
					]
				] ),
			],
		] );
		$categoryFilterForm = new OOUI\FieldsetLayout( [
			'label' => $this->msg( 'recentchanges-fieldset-categoryfilter' )->text(),
			'items' => [
				new \OOUI\FieldLayout(
					new \OOUI\TextInputWidget( [
						'name' => 'categories',
						'id' => 'categories',
						'value' => $opts['categories'],
					] ),
					[
						'label' => $this->msg( 'rc_categories' )->text(),
						'align' => 'left',
						'classes' => [ 'oo-ui-fieldLayout-narrow' ],
					]
				),
				new \OOUI\FieldLayout(
					new \OOUI\CheckboxInputWidget( [
						'name' => 'categories_any',
						'id' => 'categories_any',
						'selected' => $opts['categories_any'],
					] ),
					[
						'label' => $this->msg( 'rc_categories_any' )->text(),
						'align' => 'inline',
					]
				),
			],
		] );
		$changesTypeFieldset = new OOUI\FieldsetLayout( [
			'label' => $this->msg( 'recentchanges-fieldset-changestypes' )->text(),
			'items' => [
				new \OOUI\HorizontalLayout( [
					'items' => $this->makeShowHideLinks( $opts ),
					'classes' => [ 'rcshowhideoption-container' ],
				] ),
			],
		] );
		$displayOptionsFieldset = new OOUI\FieldsetLayout( [
			'label' => $this->msg( 'recentchanges-fieldset-displayopts' )->text(),
			'items' => [
				new \OOUI\FieldLayout(
					new \OOUI\TextInputWidget( [
						'name' => 'limit',
						'id' => 'limit',
						'infusable' => true,
						'type' => 'number',
						'value' => $opts['limit'],
					] ),
					[
						'label' => $this->msg( 'recentchanges-limit-label' )->text(),
						'align' => 'left',
						'infusable' => true,
						'id' => 'limit-fieldlayout',
						'classes' => [ 'oo-ui-fieldLayout-narrow' ],
					]
				),
				new \OOUI\FieldLayout(
					new \OOUI\DropdownInputWidget( [
						'name' => 'maxage',
						'id' => 'maxage',
						'infusable' => true,
						'options' => $this->makeDurationOptions(),
						'value' => $opts['maxage'],
					] ),
					[
						'label' => $this->msg( 'recentchanges-maxage-label' )->text(),
						'align' => 'left',
						'infusable' => true,
						'id' => 'maxage-fieldlayout',
						'classes' => [ 'oo-ui-fieldLayout-narrow' ],
					]
				),
				new \OOUI\FieldLayout(
					new \OOUI\CheckboxInputWidget( [
						'name' => 'from',
						'id' => 'from',
						'value' => $timestamp,
						'selected' => $opts['from'] !== '',
						'infusable' => true,
					] ),
					[
						'label' => $this->msg( 'recentchanges-label-showchangesfrom' )
							->rawParams( $now, $timenow, $datenow )
							->parse(),
						'align' => 'inline',
					]
				)
			],
		] );

		$tagFilterInput = $this->makeOOUITagFilterInput();
		if ( $tagFilterInput !== null ) {
			$namespaceTagFieldset->addItems( [ $tagFilterInput ] );
		}

		$leftColumn = [ $namespaceTagFieldset ];
		$rightColumn = [];

		if ( $this->getConfig()->get( 'AllowCategorizedRecentChanges' ) ) {
			$leftColumn[] = $categoryFilterForm;
			$rightColumn[] = $changesTypeFieldset;
		} else {
			$leftColumn[] = $changesTypeFieldset;
		}

		$rightColumn[] = $displayOptionsFieldset;

		$form->addItems( [
			$this->renderFormColumn( $leftColumn ),
			$this->renderFormColumn( $rightColumn ),
		] );
	}

	/**
	 * Render a column of the form.
	 *
	 * @param $content
	 * @return \OOUI\Tag
	 */
	private function renderFormColumn( $content ) {
		$column = new \OOUI\Tag( 'div' );
		$column->addClasses( [ 'rcform-multicolumn-column' ] );

		foreach ( $content as $piece ) {
			$column->appendContent( $piece );
		}

		return $column;
	}

	/**
	 * Specialized variant of ChangeTags::buildTagFilterSelector to create a FieldLayout.
	 *
	 * @param string $selected The currently selected tag, if any.
	 *
	 * @return null|\OOUI\FieldLayout Returns null if there are no tags defined or if the
	 * configuration does not allow the usage of tag filter.
	 * @see ChangeTags::buildTagFilterSelector
	 */
	private function makeOOUITagFilterInput( $selected = '' ) {
		$context = $this->getContext();

		$config = $context->getConfig();
		if ( !$config->get( 'UseTagFilter' ) || !count( ChangeTags::listDefinedTags() ) ) {
			return null;
		}

		return new \OOUI\FieldLayout(
			new OOUI\TextInputWidget( [
				'id' => 'tagfilter',
				'name' => 'tagfilter',
				'value' => $selected,
			] ),
			[
				'label' => new OOUI\HtmlSnippet( $this->msg( 'tag-filter' )->parse() ),
				'align' => 'left',
				'classes' => [ 'oo-ui-fieldLayout-narrow' ],
			]
		);
	}

	/**
	 * Create the show/hide type checkboxes.
	 *
	 * @param \FormOptions $opts
	 *
	 * @return array
	 */
	private function makeShowHideLinks( FormOptions $opts ) {
		$config = $this->getConfig();
		$user = $this->getUser();

		$filters = [
			'hideminor' => 'rcshowhideminor',
			'hidebots' => 'rcshowhidebots',
			'hideanons' => 'rcshowhideanons',
			'hideliu' => 'rcshowhideliu',
			'hidepatrolled' => 'rcshowhidepatr',
			'hidemyself' => 'rcshowhidemine'
		];

		if ( $config->get( 'RCWatchCategoryMembership' ) ) {
			$filters['hidecategorization'] = 'rcshowhidecategorization';
		}

		$showhide = [ 'show', 'hide' ];

		foreach ( $this->getCustomFilters() as $key => $params ) {
			$filters[$key] = $params['msg'];
		}

		// Disable some if needed
		if ( !$user->useRCPatrol() ) {
			unset( $filters['hidepatrolled'] );
		}

		$links = [];
		foreach ( $filters as $key => $msg ) {
			$linkMessage = $this->msg( $msg . '-' . $showhide[1 - $opts[$key]] );

			if ( !$linkMessage->exists() ) {
				$linkMessage = $this->msg( $showhide[1 - $opts[$key]] );
			}

			$link = $this->makeOptionsLink(
				$linkMessage->text(),
				[ $key => 1 - $opts[$key] ],
				$opts->getChangedValues()
			);

			$linkElement = new \OOUI\Tag( 'span' );
			$linkElement->addClasses( [ $msg, 'rcshowhideoption' ] );
			$linkElement->appendContent( new \OOUI\HtmlSnippet(
				$this->msg( $msg )->rawParams( $link )->parse()
			) );

			$links[] = $linkElement;
		}

		return $links;
	}

	/**
	 * Create the list of available "maximum changes ages".
	 *
	 * @return array
	 */
	private function makeDurationOptions() {
		$language = $this->getLanguage();

		$options = [];
		foreach ( self::$changesAgeDurations as $duration ) {
			$options[] = [
				'data' => (string)$duration,
				'label' => $language->formatDuration( $duration ),
			];
		}

		return $options;
	}

	public function isIncludable() {
		return true;
	}

	protected function getCacheTTL() {
		return 60 * 5;
	}

}
