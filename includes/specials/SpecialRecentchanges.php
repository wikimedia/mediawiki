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
use Wikimedia\Rdbms\ResultWrapper;
use Wikimedia\Rdbms\FakeResultWrapper;

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
		$out = $this->getOutput();
		$out->setCdnMaxage( 10 );
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

		if ( $this->isStructuredFilterUiEnabled() ) {
			$jsData = $this->getStructuredFilterJsData();

			$messages = [];
			foreach ( $jsData['messageKeys'] as $key ){
				$messages[$key] = $this->msg( $key )->plain();
			}

			$out->addHTML(
				ResourceLoader::makeInlineScript(
					ResourceLoader::makeMessageSetScript( $messages )
				)
			);

			$out->addJsConfigVars( 'wgStructuredChangeFilters', $jsData['groups'] );
		}
	}

	/**
	 * @inheritdoc
	 */
	protected function transformFilterDefinition( array $filterDefinition ) {
		if ( isset( $filterDefinition['showHideSuffix'] ) ) {
			$filterDefinition['showHide'] = 'rc' . $filterDefinition['showHideSuffix'];
		}

		return $filterDefinition;
	}

	/**
	 * @inheritdoc
	 */
	protected function registerFilters() {
		parent::registerFilters();

		$user = $this->getUser();

		$significance = $this->getFilterGroup( 'significance' );
		$hideMinor = $significance->getFilter( 'hideminor' );
		$hideMinor->setDefault( $user->getBoolOption( 'hideminor' ) );

		$automated = $this->getFilterGroup( 'automated' );
		$hideBots = $automated->getFilter( 'hidebots' );
		$hideBots->setDefault( true );

		$reviewStatus = $this->getFilterGroup( 'reviewStatus' );
		if ( $reviewStatus !== null ) {
			// Conditional on feature being available and rights
			$hidePatrolled = $reviewStatus->getFilter( 'hidepatrolled' );
			$hidePatrolled->setDefault( $user->getBoolOption( 'hidepatrolled' ) );
		}

		$changeType = $this->getFilterGroup( 'changeType' );
		$hideCategorization = $changeType->getFilter( 'hidecategorization' );
		if ( $hideCategorization !== null ) {
			// Conditional on feature being available
			$hideCategorization->setDefault( $user->getBoolOption( 'hidecategorization' ) );
		}
	}

	/**
	 * Get a FormOptions object containing the default options
	 *
	 * @return FormOptions
	 */
	public function getDefaultOptions() {
		$opts = parent::getDefaultOptions();
		$user = $this->getUser();

		$opts->add( 'days', $user->getIntOption( 'rcdays' ) );
		$opts->add( 'limit', $user->getIntOption( 'rclimit' ) );
		$opts->add( 'from', '' );

		$opts->add( 'categories', '' );
		$opts->add( 'categories_any', false );
		$opts->add( 'tagfilter', '' );

		return $opts;
	}

	/**
	 * Get all custom filters
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
	 * Process $par and put options found in $opts. Used when including the page.
	 *
	 * @param string $par
	 * @param FormOptions $opts
	 */
	public function parseParameters( $par, FormOptions $opts ) {
		parent::parseParameters( $par, $opts );

		$bits = preg_split( '/\s*,\s*/', trim( $par ) );
		foreach ( $bits as $bit ) {
			if ( is_numeric( $bit ) ) {
				$opts['limit'] = $bit;
			}

			$m = [];
			if ( preg_match( '/^limit=(\d+)$/', $bit, $m ) ) {
				$opts['limit'] = $m[1];
			}
			if ( preg_match( '/^days=(\d+)$/', $bit, $m ) ) {
				$opts['days'] = $m[1];
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
	 * @inheritdoc
	 */
	protected function buildQuery( &$tables, &$fields, &$conds,
		&$query_options, &$join_conds, FormOptions $opts ) {

		$dbr = $this->getDB();
		parent::buildQuery( $tables, $fields, $conds,
			$query_options, $join_conds, $opts );

		// Calculate cutoff
		$cutoff_unixtime = time() - ( $opts['days'] * 86400 );
		$cutoff_unixtime = $cutoff_unixtime - ( $cutoff_unixtime % 86400 );
		$cutoff = $dbr->timestamp( $cutoff_unixtime );

		$fromValid = preg_match( '/^[0-9]{14}$/', $opts['from'] );
		if ( $fromValid && $opts['from'] > wfTimestamp( TS_MW, $cutoff ) ) {
			$cutoff = $dbr->timestamp( $opts['from'] );
		} else {
			$opts->reset( 'from' );
		}

		$conds[] = 'rc_timestamp >= ' . $dbr->addQuotes( $cutoff );
	}

	/**
	 * @inheritdoc
	 */
	protected function doMainQuery( $tables, $fields, $conds, $query_options,
		$join_conds, FormOptions $opts ) {

		$dbr = $this->getDB();
		$user = $this->getUser();

		$tables[] = 'recentchanges';
		$fields = array_merge( RecentChange::selectFields(), $fields );

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

		if ( $this->areFiltersInConflict() ) {
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
		$list = ChangesList::newFromContext( $this->getContext(), $this->filterGroups );
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
			$this->outputNoResults();
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

		$defaults = $opts->getAllValues();
		$nondefaults = $opts->getChangedValues();

		$panel = [];
		$panel[] = $this->makeLegend();
		$panel[] = $this->optionsPanel( $defaults, $nondefaults, $numRows );
		$panel[] = '<hr />';

		$extraOpts = $this->getExtraOptions( $opts );
		$extraOptsCount = count( $extraOpts );
		$count = 0;
		$submit = ' ' . Xml::submitButton( $this->msg( 'recentchanges-submit' )->text() );

		$out = Xml::openElement( 'table', [ 'class' => 'mw-recentchanges-table' ] );
		foreach ( $extraOpts as $name => $optionRow ) {
			# Add submit button to the last row only
			++$count;
			$addSubmit = ( $count === $extraOptsCount ) ? $submit : '';

			$out .= Xml::openElement( 'tr' );
			if ( is_array( $optionRow ) ) {
				$out .= Xml::tags(
					'td',
					[ 'class' => 'mw-label mw-' . $name . '-label' ],
					$optionRow[0]
				);
				$out .= Xml::tags(
					'td',
					[ 'class' => 'mw-input' ],
					$optionRow[1] . $addSubmit
				);
			} else {
				$out .= Xml::tags(
					'td',
					[ 'class' => 'mw-input', 'colspan' => 2 ],
					$optionRow . $addSubmit
				);
			}
			$out .= Xml::closeElement( 'tr' );
		}
		$out .= Xml::closeElement( 'table' );

		$unconsumed = $opts->getUnconsumedValues();
		foreach ( $unconsumed as $key => $value ) {
			$out .= Html::hidden( $key, $value );
		}

		$t = $this->getPageTitle();
		$out .= Html::hidden( 'title', $t->getPrefixedText() );
		$form = Xml::tags( 'form', [ 'action' => wfScript() ], $out );
		$panel[] = $form;
		$panelString = implode( "\n", $panel );

		$rcoptions = Xml::fieldset(
			$this->msg( 'recentchanges-legend' )->text(),
			$panelString,
			[ 'class' => 'rcoptions' ]
		);

		// Insert a placeholder for RCFilters
		if ( $this->getUser()->getOption( 'rcenhancedfilters' ) ) {
			$rcfilterContainer = Html::element(
				'div',
				[ 'class' => 'rcfilters-container' ]
			);

			// Wrap both with rcfilters-head
			$this->getOutput()->addHTML(
				Html::rawElement(
					'div',
					[ 'class' => 'rcfilters-head' ],
					$rcfilterContainer . $rcoptions
				)
			);
		} else {
			$this->getOutput()->addHTML( $rcoptions );
		}

		$this->setBottomText( $opts );
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
	 * Get options to be displayed in a form
	 *
	 * @param FormOptions $opts
	 * @return array
	 */
	function getExtraOptions( $opts ) {
		$opts->consumeValues( [
			'namespace', 'invert', 'associated', 'tagfilter', 'categories', 'categories_any'
		] );

		$extraOpts = [];
		$extraOpts['namespace'] = $this->namespaceFilterForm( $opts );

		if ( $this->getConfig()->get( 'AllowCategorizedRecentChanges' ) ) {
			$extraOpts['category'] = $this->categoryFilterForm( $opts );
		}

		$tagFilter = ChangeTags::buildTagFilterSelector(
			$opts['tagfilter'], false, $this->getContext() );
		if ( count( $tagFilter ) ) {
			$extraOpts['tagfilter'] = $tagFilter;
		}

		// Don't fire the hook for subclasses. (Or should we?)
		if ( $this->getName() === 'Recentchanges' ) {
			Hooks::run( 'SpecialRecentChangesPanel', [ &$extraOpts, $opts ] );
		}

		return $extraOpts;
	}

	/**
	 * Check whether the structured filter UI is enabled
	 *
	 * @return bool
	 */
	protected function isStructuredFilterUiEnabled() {
		return $this->getUser()->getOption(
			'rcenhancedfilters'
		);
	}

	/**
	 * Add page-specific modules.
	 */
	protected function addModules() {
		parent::addModules();
		$out = $this->getOutput();
		$out->addModules( 'mediawiki.special.recentchanges' );
		if ( $this->isStructuredFilterUiEnabled() ) {
			$out->addModules( 'mediawiki.rcfilters.filters.ui' );
			$out->addModuleStyles( 'mediawiki.rcfilters.filters.base.styles' );
		}
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
	 * Creates the choose namespace selection
	 *
	 * @param FormOptions $opts
	 * @return string
	 */
	protected function namespaceFilterForm( FormOptions $opts ) {
		$nsSelect = Html::namespaceSelector(
			[ 'selected' => $opts['namespace'], 'all' => '' ],
			[ 'name' => 'namespace', 'id' => 'namespace' ]
		);
		$nsLabel = Xml::label( $this->msg( 'namespace' )->text(), 'namespace' );
		$invert = Xml::checkLabel(
			$this->msg( 'invert' )->text(), 'invert', 'nsinvert',
			$opts['invert'],
			[ 'title' => $this->msg( 'tooltip-invert' )->text() ]
		);
		$associated = Xml::checkLabel(
			$this->msg( 'namespace_association' )->text(), 'associated', 'nsassociated',
			$opts['associated'],
			[ 'title' => $this->msg( 'tooltip-namespace_association' )->text() ]
		);

		return [ $nsLabel, "$nsSelect $invert $associated" ];
	}

	/**
	 * Create an input to filter changes by categories
	 *
	 * @param FormOptions $opts
	 * @return array
	 */
	protected function categoryFilterForm( FormOptions $opts ) {
		list( $label, $input ) = Xml::inputLabelSep( $this->msg( 'rc_categories' )->text(),
			'categories', 'mw-categories', false, $opts['categories'] );

		$input .= ' ' . Xml::checkLabel( $this->msg( 'rc_categories_any' )->text(),
			'categories_any', 'mw-categories_any', $opts['categories_any'] );

		return [ $label, $input ];
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
		$rows = new FakeResultWrapper( array_values( $newrows ) );
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

		// T38524: false values have be converted to "0" otherwise
		// wfArrayToCgi() will omit it them.
		foreach ( $params as &$value ) {
			if ( $value === false ) {
				$value = '0';
			}
		}
		unset( $value );

		if ( $active ) {
			$title = new HtmlArmor( '<strong>' . htmlspecialchars( $title ) . '</strong>' );
		}

		return $this->getLinkRenderer()->makeKnownLink( $this->getPageTitle(), $title, [
			'data-params' => json_encode( $override ),
			'data-keys' => implode( ',', array_keys( $override ) ),
		], $params );
	}

	/**
	 * Creates the options panel.
	 *
	 * @param array $defaults
	 * @param array $nondefaults
	 * @param int $numRows Number of rows in the result to show after this header
	 * @return string
	 */
	function optionsPanel( $defaults, $nondefaults, $numRows ) {
		$options = $nondefaults + $defaults;

		$note = '';
		$msg = $this->msg( 'rclegend' );
		if ( !$msg->isDisabled() ) {
			$note .= '<div class="mw-rclegend">' . $msg->parse() . "</div>\n";
		}

		$lang = $this->getLanguage();
		$user = $this->getUser();
		$config = $this->getConfig();
		if ( $options['from'] ) {
			$resetLink = $this->makeOptionsLink( $this->msg( 'rclistfromreset' ),
				[ 'from' => '' ], $nondefaults );

			$note .= $this->msg( 'rcnotefrom' )
				->numParams( $options['limit'] )
				->params(
					$lang->userTimeAndDate( $options['from'], $user ),
					$lang->userDate( $options['from'], $user ),
					$lang->userTime( $options['from'], $user )
				)
				->numParams( $numRows )
				->parse() . ' ' .
				Html::rawElement(
					'span',
					[ 'class' => 'rcoptions-listfromreset' ],
					$this->msg( 'parentheses' )->rawParams( $resetLink )->parse()
				) .
				'<br />';
		}

		# Sort data for display and make sure it's unique after we've added user data.
		$linkLimits = $config->get( 'RCLinkLimits' );
		$linkLimits[] = $options['limit'];
		sort( $linkLimits );
		$linkLimits = array_unique( $linkLimits );

		$linkDays = $config->get( 'RCLinkDays' );
		$linkDays[] = $options['days'];
		sort( $linkDays );
		$linkDays = array_unique( $linkDays );

		// limit links
		$cl = [];
		foreach ( $linkLimits as $value ) {
			$cl[] = $this->makeOptionsLink( $lang->formatNum( $value ),
				[ 'limit' => $value ], $nondefaults, $value == $options['limit'] );
		}
		$cl = $lang->pipeList( $cl );

		// day links, reset 'from' to none
		$dl = [];
		foreach ( $linkDays as $value ) {
			$dl[] = $this->makeOptionsLink( $lang->formatNum( $value ),
				[ 'days' => $value, 'from' => '' ], $nondefaults, $value == $options['days'] );
		}
		$dl = $lang->pipeList( $dl );

		$showhide = [ 'show', 'hide' ];

		$links = [];

		$filterGroups = $this->getFilterGroups();

		$context = $this->getContext();
		foreach ( $filterGroups as $groupName => $group ) {
			if ( !$group->isPerGroupRequestParameter() ) {
				foreach ( $group->getFilters() as $key => $filter ) {
					if ( $filter->displaysOnUnstructuredUi( $this ) ) {
						$msg = $filter->getShowHide();
						$linkMessage = $this->msg( $msg . '-' . $showhide[1 - $options[$key]] );
						// Extensions can define additional filters, but don't need to define the corresponding
						// messages. If they don't exist, just fall back to 'show' and 'hide'.
						if ( !$linkMessage->exists() ) {
							$linkMessage = $this->msg( $showhide[1 - $options[$key]] );
						}

						$link = $this->makeOptionsLink( $linkMessage->text(),
							[ $key => 1 - $options[$key] ], $nondefaults );

						$attribs = [
							'class' => "$msg rcshowhideoption",
							'data-filter-name' => $filter->getName(),
						];

						if ( $filter->isFeatureAvailableOnStructuredUi( $this ) ) {
							$attribs['data-feature-in-structured-ui'] = true;
						}

						$links[] = Html::rawElement(
							'span',
							$attribs,
							$this->msg( $msg )->rawParams( $link )->escaped()
						);
					}
				}
			}
		}

		// show from this onward link
		$timestamp = wfTimestampNow();
		$now = $lang->userTimeAndDate( $timestamp, $user );
		$timenow = $lang->userTime( $timestamp, $user );
		$datenow = $lang->userDate( $timestamp, $user );
		$pipedLinks = '<span class="rcshowhide">' . $lang->pipeList( $links ) . '</span>';

		$rclinks = '<span class="rclinks">' . $this->msg( 'rclinks' )->rawParams( $cl, $dl, '' )
			->parse() . '</span>';

		$rclistfrom = '<span class="rclistfrom">' . $this->makeOptionsLink(
			$this->msg( 'rclistfrom' )->rawParams( $now, $timenow, $datenow )->parse(),
			[ 'from' => $timestamp ],
			$nondefaults
		) . '</span>';

		return "{$note}$rclinks<br />$pipedLinks<br />$rclistfrom";
	}

	public function isIncludable() {
		return true;
	}

	protected function getCacheTTL() {
		return 60 * 5;
	}
}
