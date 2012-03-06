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

/**
 * A special page that lists last changes made to the wiki
 *
 * @ingroup SpecialPage
 */
class SpecialRecentChanges extends IncludableSpecialPage {
	var $rcOptions, $rcSubpage;
	protected $customFilters;

	public function __construct( $name = 'Recentchanges' ) {
		parent::__construct( $name );
	}

	/**
	 * Get a FormOptions object containing the default options
	 *
	 * @return FormOptions
	 */
	public function getDefaultOptions() {
		$opts = new FormOptions();

		$opts->add( 'days',  (int)$this->getUser()->getOption( 'rcdays' ) );
		$opts->add( 'limit', (int)$this->getUser()->getOption( 'rclimit' ) );
		$opts->add( 'from', '' );

		$opts->add( 'hideminor',     $this->getUser()->getBoolOption( 'hideminor' ) );
		$opts->add( 'hidebots',      true  );
		$opts->add( 'hideanons',     false );
		$opts->add( 'hideliu',       false );
		$opts->add( 'hidepatrolled', $this->getUser()->getBoolOption( 'hidepatrolled' ) );
		$opts->add( 'hidemyself',    false );

		$opts->add( 'namespace', '', FormOptions::INTNULL );
		$opts->add( 'invert', false );
		$opts->add( 'associated', false );

		$opts->add( 'categories', '' );
		$opts->add( 'categories_any', false );
		$opts->add( 'tagfilter', '' );
		return $opts;
	}

	/**
	 * Create a FormOptions object with options as specified by the user
	 *
	 * @param $parameters array
	 *
	 * @return FormOptions
	 */
	public function setup( $parameters ) {
		$opts = $this->getDefaultOptions();

		foreach( $this->getCustomFilters() as $key => $params ) {
			$opts->add( $key, $params['default'] );
		}

		$opts->fetchValuesFromRequest( $this->getRequest() );

		// Give precedence to subpage syntax
		if( $parameters !== null ) {
			$this->parseParameters( $parameters, $opts );
		}

		$opts->validateIntBounds( 'limit', 0, 5000 );
		return $opts;
	}

	/**
	 * Get custom show/hide filters
	 *
	 * @return Array Map of filter URL param names to properties (msg/default)
	 */
	protected function getCustomFilters() {
		if ( $this->customFilters === null ) {
			$this->customFilters = array();
			wfRunHooks( 'SpecialRecentChangesFilters', array( $this, &$this->customFilters ) );
		}
		return $this->customFilters;
	}

	/**
	 * Create a FormOptions object specific for feed requests and return it
	 *
	 * @return FormOptions
	 */
	public function feedSetup() {
		global $wgFeedLimit;
		$opts = $this->getDefaultOptions();
		# Feed is cached on limit,hideminor,namespace; other params would randomly not work
		$opts->fetchValuesFromRequest( $this->getRequest(), array( 'limit', 'hideminor', 'namespace' ) );
		$opts->validateIntBounds( 'limit', 0, $wgFeedLimit );
		return $opts;
	}

	/**
	 * Get the current FormOptions for this request
	 */
	public function getOptions() {
		if ( $this->rcOptions === null ) {
			if ( $this->including() ) {
				$isFeed = false;
			} else {
				$isFeed = (bool)$this->getRequest()->getVal( 'feed' );
			}
			$this->rcOptions = $isFeed ? $this->feedSetup() : $this->setup( $this->rcSubpage );
		}
		return $this->rcOptions;
	}


	/**
	 * Main execution point
	 *
	 * @param $subpage String
	 */
	public function execute( $subpage ) {
		$this->rcSubpage = $subpage;
		$feedFormat = $this->including() ? null : $this->getRequest()->getVal( 'feed' );

		# 10 seconds server-side caching max
		$this->getOutput()->setSquidMaxage( 10 );
		# Check if the client has a cached version
		$lastmod = $this->checkLastModified( $feedFormat );
		if( $lastmod === false ) {
			return;
		}

		$opts = $this->getOptions();
		$this->setHeaders();
		$this->outputHeader();
		$this->addRecentChangesJS();

		// Fetch results, prepare a batch link existence check query
		$conds = $this->buildMainQueryConds( $opts );
		$rows = $this->doMainQuery( $conds, $opts );
		if( $rows === false ){
			if( !$this->including() ) {
				$this->doHeader( $opts );
			}
			return;
		}

		if( !$feedFormat ) {
			$batch = new LinkBatch;
			foreach( $rows as $row ) {
				$batch->add( NS_USER, $row->rc_user_text );
				$batch->add( NS_USER_TALK, $row->rc_user_text );
				$batch->add( $row->rc_namespace, $row->rc_title );
			}
			$batch->execute();
		}
		if( $feedFormat ) {
			list( $changesFeed, $formatter ) = $this->getFeedObject( $feedFormat );
			$changesFeed->execute( $formatter, $rows, $lastmod, $opts );
		} else {
			$this->webOutput( $rows, $opts );
		}

		$rows->free();
	}

	/**
	 * Return an array with a ChangesFeed object and ChannelFeed object
	 *
	 * @return Array
	 */
	public function getFeedObject( $feedFormat ){
		$changesFeed = new ChangesFeed( $feedFormat, 'rcfeed' );
		$formatter = $changesFeed->getFeedObject(
			wfMsgForContent( 'recentchanges' ),
			wfMsgForContent( 'recentchanges-feed-description' ),
			$this->getTitle()->getFullURL()
		);
		return array( $changesFeed, $formatter );
	}

	/**
	 * Process $par and put options found if $opts
	 * Mainly used when including the page
	 *
	 * @param $par String
	 * @param $opts FormOptions
	 */
	public function parseParameters( $par, FormOptions $opts ) {
		$bits = preg_split( '/\s*,\s*/', trim( $par ) );
		foreach( $bits as $bit ) {
			if( 'hidebots' === $bit ) {
				$opts['hidebots'] = true;
			}
			if( 'bots' === $bit ) {
				$opts['hidebots'] = false;
			}
			if( 'hideminor' === $bit ) {
				$opts['hideminor'] = true;
			}
			if( 'minor' === $bit ) {
				$opts['hideminor'] = false;
			}
			if( 'hideliu' === $bit ) {
				$opts['hideliu'] = true;
			}
			if( 'hidepatrolled' === $bit ) {
				$opts['hidepatrolled'] = true;
			}
			if( 'hideanons' === $bit ) {
				$opts['hideanons'] = true;
			}
			if( 'hidemyself' === $bit ) {
				$opts['hidemyself'] = true;
			}

			if( is_numeric( $bit ) ) {
				$opts['limit'] =  $bit;
			}

			$m = array();
			if( preg_match( '/^limit=(\d+)$/', $bit, $m ) ) {
				$opts['limit'] = $m[1];
			}
			if( preg_match( '/^days=(\d+)$/', $bit, $m ) ) {
				$opts['days'] = $m[1];
			}
			if( preg_match( '/^namespace=(\d+)$/', $bit, $m ) ) {
				$opts['namespace'] = $m[1];
			}
		}
	}

	/**
	 * Get last modified date, for client caching
	 * Don't use this if we are using the patrol feature, patrol changes don't
	 * update the timestamp
	 *
	 * @param $feedFormat String
	 * @return String or false
	 */
	public function checkLastModified( $feedFormat ) {
		$dbr = wfGetDB( DB_SLAVE );
		$lastmod = $dbr->selectField( 'recentchanges', 'MAX(rc_timestamp)', false, __METHOD__ );
		if( $feedFormat || !$this->getUser()->useRCPatrol() ) {
			if( $lastmod && $this->getOutput()->checkLastModified( $lastmod ) ) {
				# Client cache fresh and headers sent, nothing more to do.
				return false;
			}
		}
		return $lastmod;
	}

	/**
	 * Return an array of conditions depending of options set in $opts
	 *
	 * @param $opts FormOptions
	 * @return array
	 */
	public function buildMainQueryConds( FormOptions $opts ) {
		$dbr = wfGetDB( DB_SLAVE );
		$conds = array();

		# It makes no sense to hide both anons and logged-in users
		# Where this occurs, force anons to be shown
		$forcebot = false;
		if( $opts['hideanons'] && $opts['hideliu'] ){
			# Check if the user wants to show bots only
			if( $opts['hidebots'] ){
				$opts['hideanons'] = false;
			} else {
				$forcebot = true;
				$opts['hidebots'] = false;
			}
		}

		// Calculate cutoff
		$cutoff_unixtime = time() - ( $opts['days'] * 86400 );
		$cutoff_unixtime = $cutoff_unixtime - ($cutoff_unixtime % 86400);
		$cutoff = $dbr->timestamp( $cutoff_unixtime );

		$fromValid = preg_match('/^[0-9]{14}$/', $opts['from']);
		if( $fromValid && $opts['from'] > wfTimestamp(TS_MW,$cutoff) ) {
			$cutoff = $dbr->timestamp($opts['from']);
		} else {
			$opts->reset( 'from' );
		}

		$conds[] = 'rc_timestamp >= ' . $dbr->addQuotes( $cutoff );

		$hidePatrol = $this->getUser()->useRCPatrol() && $opts['hidepatrolled'];
		$hideLoggedInUsers = $opts['hideliu'] && !$forcebot;
		$hideAnonymousUsers = $opts['hideanons'] && !$forcebot;

		if( $opts['hideminor'] ) {
			$conds['rc_minor'] = 0;
		}
		if( $opts['hidebots'] ) {
			$conds['rc_bot'] = 0;
		}
		if( $hidePatrol ) {
			$conds['rc_patrolled'] = 0;
		}
		if( $forcebot ) {
			$conds['rc_bot'] = 1;
		}
		if( $hideLoggedInUsers ) {
			$conds[] = 'rc_user = 0';
		}
		if( $hideAnonymousUsers ) {
			$conds[] = 'rc_user != 0';
		}

		if( $opts['hidemyself'] ) {
			if( $this->getUser()->getId() ) {
				$conds[] = 'rc_user != ' . $dbr->addQuotes( $this->getUser()->getId() );
			} else {
				$conds[] = 'rc_user_text != ' . $dbr->addQuotes( $this->getUser()->getName() );
			}
		}

		# Namespace filtering
		if( $opts['namespace'] !== '' ) {
			$selectedNS = $dbr->addQuotes( $opts['namespace'] );
			$operator = $opts['invert'] ? '!='  : '=';
			$boolean  = $opts['invert'] ? 'AND' : 'OR';

			# namespace association (bug 2429)
			if( !$opts['associated'] ) {
				$condition = "rc_namespace $operator $selectedNS";
			} else {
				# Also add the associated namespace
				$associatedNS = $dbr->addQuotes(
					MWNamespace::getAssociated( $opts['namespace'] )
				);
				$condition = "(rc_namespace $operator $selectedNS "
						   . $boolean
						   . " rc_namespace $operator $associatedNS)";
			}

			$conds[] = $condition;
		}
		return $conds;
	}

	/**
	 * Process the query
	 *
	 * @param $conds Array
	 * @param $opts FormOptions
	 * @return database result or false (for Recentchangeslinked only)
	 */
	public function doMainQuery( $conds, $opts ) {
		$tables = array( 'recentchanges' );
		$join_conds = array();
		$query_options = array(
			'USE INDEX' => array( 'recentchanges' => 'rc_timestamp' )
		);

		$uid = $this->getUser()->getId();
		$dbr = wfGetDB( DB_SLAVE );
		$limit = $opts['limit'];
		$namespace = $opts['namespace'];
		$invert = $opts['invert'];
		$associated = $opts['associated'];

		$fields = array( $dbr->tableName( 'recentchanges' ) . '.*' ); // all rc columns
		// JOIN on watchlist for users
		if ( $uid ) {
			$tables[] = 'watchlist';
			$fields[] = 'wl_user';
			$fields[] = 'wl_notificationtimestamp';
			$join_conds['watchlist'] = array('LEFT JOIN',
				"wl_user={$uid} AND wl_title=rc_title AND wl_namespace=rc_namespace");
		}
		if ( $this->getUser()->isAllowed( 'rollback' ) ) {
			$tables[] = 'page';
			$fields[] = 'page_latest';
			$join_conds['page'] = array('LEFT JOIN', 'rc_cur_id=page_id');
		}
		if ( !$this->including() ) {
			// Tag stuff.
			// Doesn't work when transcluding. See bug 23293
			ChangeTags::modifyDisplayQuery(
				$tables, $fields, $conds, $join_conds, $query_options,
				$opts['tagfilter']
			);
		}

		if ( !wfRunHooks( 'SpecialRecentChangesQuery',
			array( &$conds, &$tables, &$join_conds, $opts, &$query_options, &$fields ) ) )
		{
			return false;
		}

		// Don't use the new_namespace_time timestamp index if:
		// (a) "All namespaces" selected
		// (b) We want pages in more than one namespace (inverted/associated)
		// (c) There is a tag to filter on (use tag index instead)
		// (d) UNION + sort/limit is not an option for the DBMS
		if( $namespace === ''
			|| ( $invert || $associated )
			|| $opts['tagfilter'] != ''
			|| !$dbr->unionSupportsOrderAndLimit() )
		{
			$res = $dbr->select( $tables, $fields, $conds, __METHOD__,
				array( 'ORDER BY' => 'rc_timestamp DESC', 'LIMIT' => $limit ) +
				$query_options,
				$join_conds );
		// We have a new_namespace_time index! UNION over new=(0,1) and sort result set!
		} else {
			// New pages
			$sqlNew = $dbr->selectSQLText(
				$tables,
				$fields,
				array( 'rc_new' => 1 ) + $conds,
				__METHOD__,
				array(
					'ORDER BY' => 'rc_timestamp DESC',
					'LIMIT' => $limit,
					'USE INDEX' => array( 'recentchanges' => 'new_name_timestamp' )
				),
				$join_conds
			);
			// Old pages
			$sqlOld = $dbr->selectSQLText(
				$tables,
				$fields,
				array( 'rc_new' => 0 ) + $conds,
				__METHOD__,
				array(
					'ORDER BY' => 'rc_timestamp DESC',
					'LIMIT' => $limit,
					'USE INDEX' => array( 'recentchanges' => 'new_name_timestamp' )
				),
				$join_conds
			);
			# Join the two fast queries, and sort the result set
			$sql = $dbr->unionQueries( array( $sqlNew, $sqlOld ), false ) .
				' ORDER BY rc_timestamp DESC';
			$sql = $dbr->limitResult( $sql, $limit, false );
			$res = $dbr->query( $sql, __METHOD__ );
		}

		return $res;
	}

	/**
	 * Send output to the OutputPage object, only called if not used feeds
	 *
	 * @param $rows Array of database rows
	 * @param $opts FormOptions
	 */
	public function webOutput( $rows, $opts ) {
		global $wgRCShowWatchingUsers, $wgShowUpdatedMarker, $wgAllowCategorizedRecentChanges;

		$limit = $opts['limit'];

		if( !$this->including() ) {
			// Output options box
			$this->doHeader( $opts );
		}

		// And now for the content
		$this->getOutput()->setFeedAppendQuery( $this->getFeedQuery() );

		if( $wgAllowCategorizedRecentChanges ) {
			$this->filterByCategories( $rows, $opts );
		}

		$showWatcherCount = $wgRCShowWatchingUsers && $this->getUser()->getOption( 'shownumberswatching' );
		$watcherCache = array();

		$dbr = wfGetDB( DB_SLAVE );

		$counter = 1;
		$list = ChangesList::newFromContext( $this->getContext() );

		$s = $list->beginRecentChangesList();
		foreach( $rows as $obj ) {
			if( $limit == 0 ) {
				break;
			}
			$rc = RecentChange::newFromRow( $obj );
			$rc->counter = $counter++;
			# Check if the page has been updated since the last visit
			if( $wgShowUpdatedMarker && !empty( $obj->wl_notificationtimestamp ) ) {
				$rc->notificationtimestamp = ( $obj->rc_timestamp >= $obj->wl_notificationtimestamp );
			} else {
				$rc->notificationtimestamp = false; // Default
			}
			# Check the number of users watching the page
			$rc->numberofWatchingusers = 0; // Default
			if( $showWatcherCount && $obj->rc_namespace >= 0 ) {
				if( !isset( $watcherCache[$obj->rc_namespace][$obj->rc_title] ) ) {
					$watcherCache[$obj->rc_namespace][$obj->rc_title] =
						$dbr->selectField(
							'watchlist',
							'COUNT(*)',
							array(
								'wl_namespace' => $obj->rc_namespace,
								'wl_title' => $obj->rc_title,
							),
							__METHOD__ . '-watchers'
						);
				}
				$rc->numberofWatchingusers = $watcherCache[$obj->rc_namespace][$obj->rc_title];
			}
			$s .= $list->recentChangesLine( $rc, !empty( $obj->wl_user ), $counter );
			--$limit;
		}
		$s .= $list->endRecentChangesList();
		$this->getOutput()->addHTML( $s );
	}

	/**
	 * Get the query string to append to feed link URLs.
	 * This is overridden by RCL to add the target parameter
	 */
	public function getFeedQuery() {
		return false;
	}

	/**
	 * Return the text to be displayed above the changes
	 *
	 * @param $opts FormOptions
	 * @return String: XHTML
	 */
	public function doHeader( $opts ) {
		global $wgScript;

		$this->setTopText( $opts );

		$defaults = $opts->getAllValues();
		$nondefaults = $opts->getChangedValues();
		$opts->consumeValues( array(
			'namespace', 'invert', 'associated', 'tagfilter',
			'categories', 'categories_any'
		) );

		$panel = array();
		$panel[] = $this->optionsPanel( $defaults, $nondefaults );
		$panel[] = '<hr />';

		$extraOpts = $this->getExtraOptions( $opts );
		$extraOptsCount = count( $extraOpts );
		$count = 0;
		$submit = ' ' . Xml::submitbutton( wfMsg( 'allpagessubmit' ) );

		$out = Xml::openElement( 'table', array( 'class' => 'mw-recentchanges-table' ) );
		foreach( $extraOpts as $optionRow ) {
			# Add submit button to the last row only
			++$count;
			$addSubmit = $count === $extraOptsCount ? $submit : '';

			$out .= Xml::openElement( 'tr' );
			if( is_array( $optionRow ) ) {
				$out .= Xml::tags( 'td', array( 'class' => 'mw-label' ), $optionRow[0] );
				$out .= Xml::tags( 'td', array( 'class' => 'mw-input' ), $optionRow[1] . $addSubmit );
			} else {
				$out .= Xml::tags( 'td', array( 'class' => 'mw-input', 'colspan' => 2 ), $optionRow . $addSubmit );
			}
			$out .= Xml::closeElement( 'tr' );
		}
		$out .= Xml::closeElement( 'table' );

		$unconsumed = $opts->getUnconsumedValues();
		foreach( $unconsumed as $key => $value ) {
			$out .= Html::hidden( $key, $value );
		}

		$t = $this->getTitle();
		$out .= Html::hidden( 'title', $t->getPrefixedText() );
		$form = Xml::tags( 'form', array( 'action' => $wgScript ), $out );
		$panel[] = $form;
		$panelString = implode( "\n", $panel );

		$this->getOutput()->addHTML(
			Xml::fieldset( wfMsg( 'recentchanges-legend' ), $panelString, array( 'class' => 'rcoptions' ) )
		);

		$this->setBottomText( $opts );
	}

	/**
	 * Get options to be displayed in a form
	 *
	 * @param $opts FormOptions
	 * @return Array
	 */
	function getExtraOptions( $opts ) {
		$extraOpts = array();
		$extraOpts['namespace'] = $this->namespaceFilterForm( $opts );

		global $wgAllowCategorizedRecentChanges;
		if( $wgAllowCategorizedRecentChanges ) {
			$extraOpts['category'] = $this->categoryFilterForm( $opts );
		}

		$tagFilter = ChangeTags::buildTagFilterSelector( $opts['tagfilter'] );
		if ( count( $tagFilter ) ) {
			$extraOpts['tagfilter'] = $tagFilter;
		}

		wfRunHooks( 'SpecialRecentChangesPanel', array( &$extraOpts, $opts ) );
		return $extraOpts;
	}

	/**
	 * Send the text to be displayed above the options
	 *
	 * @param $opts FormOptions
	 */
	function setTopText( FormOptions $opts ) {
		global $wgContLang;
		$this->getOutput()->addWikiText(
			Html::rawElement( 'p',
				array( 'lang' => $wgContLang->getCode(), 'dir' => $wgContLang->getDir() ),
				"\n" . wfMsgForContentNoTrans( 'recentchangestext' ) . "\n"
			), 
			/* $lineStart */ false,
			/* $interface */ false
		);
	}

	/**
	 * Send the text to be displayed after the options, for use in
	 * Recentchangeslinked
	 *
	 * @param $opts FormOptions
	 */
	function setBottomText( FormOptions $opts ) {}

	/**
	 * Creates the choose namespace selection
	 *
	 * @todo Uses radio buttons (HASHAR)
	 * @param $opts FormOptions
	 * @return String
	 */
	protected function namespaceFilterForm( FormOptions $opts ) {
		$nsSelect = Html::namespaceSelector(
			array( 'selected' => $opts['namespace'], 'all' => '' ),
			array( 'name' => 'namespace', 'id' => 'namespace' )
		);
		$nsLabel = Xml::label( wfMsg( 'namespace' ), 'namespace' );
		$invert = Xml::checkLabel(
			wfMsg( 'invert' ), 'invert', 'nsinvert',
			$opts['invert'],
			array( 'title' => wfMsg( 'tooltip-invert' ) )
		);
		$associated = Xml::checkLabel(
			wfMsg( 'namespace_association' ), 'associated', 'nsassociated',
			$opts['associated'],
			array( 'title' => wfMsg( 'tooltip-namespace_association' ) )
		);
		return array( $nsLabel, "$nsSelect $invert $associated" );
	}

	/**
	 * Create a input to filter changes by categories
	 *
	 * @param $opts FormOptions
	 * @return Array
	 */
	protected function categoryFilterForm( FormOptions $opts ) {
		list( $label, $input ) = Xml::inputLabelSep( wfMsg( 'rc_categories' ),
			'categories', 'mw-categories', false, $opts['categories'] );

		$input .= ' ' . Xml::checkLabel( wfMsg( 'rc_categories_any' ),
			'categories_any', 'mw-categories_any', $opts['categories_any'] );

		return array( $label, $input );
	}

	/**
	 * Filter $rows by categories set in $opts
	 *
	 * @param $rows Array of database rows
	 * @param $opts FormOptions
	 */
	function filterByCategories( &$rows, FormOptions $opts ) {
		$categories = array_map( 'trim', explode( '|' , $opts['categories'] ) );

		if( !count( $categories ) ) {
			return;
		}

		# Filter categories
		$cats = array();
		foreach( $categories as $cat ) {
			$cat = trim( $cat );
			if( $cat == '' ) {
				continue;
			}
			$cats[] = $cat;
		}

		# Filter articles
		$articles = array();
		$a2r = array();
		$rowsarr = array();
		foreach( $rows as $k => $r ) {
			$nt = Title::makeTitle( $r->rc_namespace, $r->rc_title );
			$id = $nt->getArticleID();
			if( $id == 0 ) {
				continue; # Page might have been deleted...
			}
			if( !in_array( $id, $articles ) ) {
				$articles[] = $id;
			}
			if( !isset( $a2r[$id] ) ) {
				$a2r[$id] = array();
			}
			$a2r[$id][] = $k;
			$rowsarr[$k] = $r;
		}

		# Shortcut?
		if( !count( $articles ) || !count( $cats ) ) {
			return;
		}

		# Look up
		$c = new Categoryfinder;
		$c->seed( $articles, $cats, $opts['categories_any'] ? 'OR' : 'AND' );
		$match = $c->run();

		# Filter
		$newrows = array();
		foreach( $match as $id ) {
			foreach( $a2r[$id] as $rev ) {
				$k = $rev;
				$newrows[$k] = $rowsarr[$k];
			}
		}
		$rows = $newrows;
	}

	/**
	 * Makes change an option link which carries all the other options
	 *
	 * @param $title Title
	 * @param $override Array: options to override
	 * @param $options Array: current options
	 * @param $active Boolean: whether to show the link in bold
	 */
	function makeOptionsLink( $title, $override, $options, $active = false ) {
		$params = $override + $options;
		$text = htmlspecialchars( $title );
		if ( $active ) {
			$text = '<strong>' . $text . '</strong>';
		}
		return Linker::linkKnown( $this->getTitle(), $text, array(), $params );
	}

	/**
	 * Creates the options panel.
	 *
	 * @param $defaults Array
	 * @param $nondefaults Array
	 */
	function optionsPanel( $defaults, $nondefaults ) {
		global $wgRCLinkLimits, $wgRCLinkDays;

		$options = $nondefaults + $defaults;

		$note = '';
		if( !wfEmptyMsg( 'rclegend' ) ) {
			$note .= '<div class="mw-rclegend">' .
				wfMsgExt( 'rclegend', array( 'parseinline' ) ) . "</div>\n";
		}
		if( $options['from'] ) {
			$note .= wfMsgExt( 'rcnotefrom', array( 'parseinline' ),
				$this->getLanguage()->formatNum( $options['limit'] ),
				$this->getLanguage()->timeanddate( $options['from'], true ),
				$this->getLanguage()->date( $options['from'], true ),
				$this->getLanguage()->time( $options['from'], true ) ) . '<br />';
		}

		# Sort data for display and make sure it's unique after we've added user data.
		$wgRCLinkLimits[] = $options['limit'];
		$wgRCLinkDays[] = $options['days'];
		sort( $wgRCLinkLimits );
		sort( $wgRCLinkDays );
		$wgRCLinkLimits = array_unique( $wgRCLinkLimits );
		$wgRCLinkDays = array_unique( $wgRCLinkDays );

		// limit links
		foreach( $wgRCLinkLimits as $value ) {
			$cl[] = $this->makeOptionsLink( $this->getLanguage()->formatNum( $value ),
				array( 'limit' => $value ), $nondefaults, $value == $options['limit'] );
		}
		$cl = $this->getLanguage()->pipeList( $cl );

		// day links, reset 'from' to none
		foreach( $wgRCLinkDays as $value ) {
			$dl[] = $this->makeOptionsLink( $this->getLanguage()->formatNum( $value ),
				array( 'days' => $value, 'from' => '' ), $nondefaults, $value == $options['days'] );
		}
		$dl = $this->getLanguage()->pipeList( $dl );


		// show/hide links
		$showhide = array( wfMsg( 'show' ), wfMsg( 'hide' ) );
		$filters = array(
			'hideminor'     => 'rcshowhideminor',
			'hidebots'      => 'rcshowhidebots',
			'hideanons'     => 'rcshowhideanons',
			'hideliu'       => 'rcshowhideliu',
			'hidepatrolled' => 'rcshowhidepatr',
			'hidemyself'    => 'rcshowhidemine'
		);
		foreach ( $this->getCustomFilters() as $key => $params ) {
			$filters[$key] = $params['msg'];
		}
		// Disable some if needed
		if ( !$this->getUser()->useRCPatrol() ) {
			unset( $filters['hidepatrolled'] );
		}

		$links = array();
		foreach ( $filters as $key => $msg ) {
			$link = $this->makeOptionsLink( $showhide[1 - $options[$key]],
				array( $key => 1-$options[$key] ), $nondefaults );
			$links[] = wfMsgHtml( $msg, $link );
		}

		// show from this onward link
		$timestamp = wfTimestampNow();
		$now = $this->getLanguage()->timeanddate( $timestamp, true );
		$tl = $this->makeOptionsLink(
			$now, array( 'from' => $timestamp ), $nondefaults
		);

		$rclinks = wfMsgExt( 'rclinks', array( 'parseinline', 'replaceafter' ),
			$cl, $dl, $this->getLanguage()->pipeList( $links ) );
		$rclistfrom = wfMsgExt( 'rclistfrom', array( 'parseinline', 'replaceafter' ), $tl );
		return "{$note}$rclinks<br />$rclistfrom";
	}

	/**
	 * add javascript specific to the [[Special:RecentChanges]] page
	 */
	function addRecentChangesJS() {
		$this->getOutput()->addModules( array(
			'mediawiki.special.recentchanges',
		) );
	}
}
