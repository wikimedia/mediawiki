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
		$user = $this->getUser();

		$opts->add( 'days', $user->getIntOption( 'rcdays' ) );
		$opts->add( 'limit', $user->getIntOption( 'rclimit' ) );
		$opts->add( 'from', '' );

		$opts->add( 'hideminor', $user->getBoolOption( 'hideminor' ) );
		$opts->add( 'hidebots', true );
		$opts->add( 'hideanons', false );
		$opts->add( 'hideliu', false );
		$opts->add( 'hidepatrolled', $user->getBoolOption( 'hidepatrolled' ) );
		$opts->add( 'hidemyself', false );

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
	 * @param array $parameters
	 *
	 * @return FormOptions
	 */
	public function setup( $parameters ) {
		$opts = $this->getDefaultOptions();

		foreach ( $this->getCustomFilters() as $key => $params ) {
			$opts->add( $key, $params['default'] );
		}

		$opts->fetchValuesFromRequest( $this->getRequest() );

		// Give precedence to subpage syntax
		if ( $parameters !== null ) {
			$this->parseParameters( $parameters, $opts );
		}

		$opts->validateIntBounds( 'limit', 0, 5000 );

		return $opts;
	}

	/**
	 * Get custom show/hide filters
	 *
	 * @return array Map of filter URL param names to properties (msg/default)
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
		$opts->fetchValuesFromRequest( $this->getRequest() );
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
	 * @param string $subpage
	 */
	public function execute( $subpage ) {
		$this->rcSubpage = $subpage;
		$feedFormat = $this->including() ? null : $this->getRequest()->getVal( 'feed' );

		# 10 seconds server-side caching max
		$this->getOutput()->setSquidMaxage( 10 );
		# Check if the client has a cached version
		$lastmod = $this->checkLastModified( $feedFormat );
		if ( $lastmod === false ) {
			return;
		}

		$opts = $this->getOptions();
		$this->setHeaders();
		$this->outputHeader();
		$this->addModules();

		// Fetch results, prepare a batch link existence check query
		$conds = $this->buildMainQueryConds( $opts );
		$rows = $this->doMainQuery( $conds, $opts );
		if ( $rows === false ) {
			if ( !$this->including() ) {
				$this->doHeader( $opts );
			}

			return;
		}

		if ( !$feedFormat ) {
			$batch = new LinkBatch;
			foreach ( $rows as $row ) {
				$batch->add( NS_USER, $row->rc_user_text );
				$batch->add( NS_USER_TALK, $row->rc_user_text );
				$batch->add( $row->rc_namespace, $row->rc_title );
			}
			$batch->execute();
		}
		if ( $feedFormat ) {
			list( $changesFeed, $formatter ) = $this->getFeedObject( $feedFormat );
			/** @var ChangesFeed $changesFeed */
			$changesFeed->execute( $formatter, $rows, $lastmod, $opts );
		} else {
			$this->webOutput( $rows, $opts );
		}

		$rows->free();
	}

	/**
	 * Return an array with a ChangesFeed object and ChannelFeed object
	 *
	 * @param string $feedFormat Feed's format (either 'rss' or 'atom')
	 * @return array
	 */
	public function getFeedObject( $feedFormat ) {
		$changesFeed = new ChangesFeed( $feedFormat, 'rcfeed' );
		$formatter = $changesFeed->getFeedObject(
			$this->msg( 'recentchanges' )->inContentLanguage()->text(),
			$this->msg( 'recentchanges-feed-description' )->inContentLanguage()->text(),
			$this->getTitle()->getFullURL()
		);

		return array( $changesFeed, $formatter );
	}

	/**
	 * Process $par and put options found if $opts
	 * Mainly used when including the page
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

			if ( is_numeric( $bit ) ) {
				$opts['limit'] = $bit;
			}

			$m = array();
			if ( preg_match( '/^limit=(\d+)$/', $bit, $m ) ) {
				$opts['limit'] = $m[1];
			}
			if ( preg_match( '/^days=(\d+)$/', $bit, $m ) ) {
				$opts['days'] = $m[1];
			}
			if ( preg_match( '/^namespace=(\d+)$/', $bit, $m ) ) {
				$opts['namespace'] = $m[1];
			}
		}
	}

	/**
	 * Get last modified date, for client caching
	 * Don't use this if we are using the patrol feature, patrol changes don't
	 * update the timestamp
	 *
	 * @param string $feedFormat
	 * @return string|bool
	 */
	public function checkLastModified( $feedFormat ) {
		$dbr = wfGetDB( DB_SLAVE );
		$lastmod = $dbr->selectField( 'recentchanges', 'MAX(rc_timestamp)', false, __METHOD__ );
		if ( $feedFormat || !$this->getUser()->useRCPatrol() ) {
			if ( $lastmod && $this->getOutput()->checkLastModified( $lastmod ) ) {
				# Client cache fresh and headers sent, nothing more to do.
				return false;
			}
		}

		return $lastmod;
	}

	/**
	 * Return an array of conditions depending of options set in $opts
	 *
	 * @param FormOptions $opts
	 * @return array
	 */
	public function buildMainQueryConds( FormOptions $opts ) {
		$dbr = wfGetDB( DB_SLAVE );
		$conds = array();

		# It makes no sense to hide both anons and logged-in users
		# Where this occurs, force anons to be shown
		$forcebot = false;
		if ( $opts['hideanons'] && $opts['hideliu'] ) {
			# Check if the user wants to show bots only
			if ( $opts['hidebots'] ) {
				$opts['hideanons'] = false;
			} else {
				$forcebot = true;
				$opts['hidebots'] = false;
			}
		}

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

		$hidePatrol = $this->getUser()->useRCPatrol() && $opts['hidepatrolled'];
		$hideLoggedInUsers = $opts['hideliu'] && !$forcebot;
		$hideAnonymousUsers = $opts['hideanons'] && !$forcebot;

		if ( $opts['hideminor'] ) {
			$conds['rc_minor'] = 0;
		}
		if ( $opts['hidebots'] ) {
			$conds['rc_bot'] = 0;
		}
		if ( $hidePatrol ) {
			$conds['rc_patrolled'] = 0;
		}
		if ( $forcebot ) {
			$conds['rc_bot'] = 1;
		}
		if ( $hideLoggedInUsers ) {
			$conds[] = 'rc_user = 0';
		}
		if ( $hideAnonymousUsers ) {
			$conds[] = 'rc_user != 0';
		}

		if ( $opts['hidemyself'] ) {
			if ( $this->getUser()->getId() ) {
				$conds[] = 'rc_user != ' . $dbr->addQuotes( $this->getUser()->getId() );
			} else {
				$conds[] = 'rc_user_text != ' . $dbr->addQuotes( $this->getUser()->getName() );
			}
		}

		# Namespace filtering
		if ( $opts['namespace'] !== '' ) {
			$selectedNS = $dbr->addQuotes( $opts['namespace'] );
			$operator = $opts['invert'] ? '!=' : '=';
			$boolean = $opts['invert'] ? 'AND' : 'OR';

			# namespace association (bug 2429)
			if ( !$opts['associated'] ) {
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
	 * @param array $conds
	 * @param FormOptions $opts
	 * @return bool|ResultWrapper Result or false (for Recentchangeslinked only)
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

		$fields = RecentChange::selectFields();
		// JOIN on watchlist for users
		if ( $uid && $this->getUser()->isAllowed( 'viewmywatchlist' ) ) {
			$tables[] = 'watchlist';
			$fields[] = 'wl_user';
			$fields[] = 'wl_notificationtimestamp';
			$join_conds['watchlist'] = array( 'LEFT JOIN', array(
				'wl_user' => $uid,
				'wl_title=rc_title',
				'wl_namespace=rc_namespace'
			) );
		}
		if ( $this->getUser()->isAllowed( 'rollback' ) ) {
			$tables[] = 'page';
			$fields[] = 'page_latest';
			$join_conds['page'] = array( 'LEFT JOIN', 'rc_cur_id=page_id' );
		}
		// Tag stuff.
		ChangeTags::modifyDisplayQuery(
			$tables,
			$fields,
			$conds,
			$join_conds,
			$query_options,
			$opts['tagfilter']
		);

		if ( !wfRunHooks( 'SpecialRecentChangesQuery',
			array( &$conds, &$tables, &$join_conds, $opts, &$query_options, &$fields ) )
		) {
			return false;
		}

		// rc_new is not an ENUM, but adding a redundant rc_new IN (0,1) gives mysql enough
		// knowledge to use an index merge if it wants (it may use some other index though).
		return $dbr->select(
			$tables,
			$fields,
			$conds + array( 'rc_new' => array( 0, 1 ) ),
			__METHOD__,
			array( 'ORDER BY' => 'rc_timestamp DESC', 'LIMIT' => $limit ) + $query_options,
			$join_conds
		);
	}

	/**
	 * Send output to the OutputPage object, only called if not used feeds
	 *
	 * @param array $rows Database rows
	 * @param FormOptions $opts
	 */
	public function webOutput( $rows, $opts ) {
		global $wgRCShowWatchingUsers, $wgShowUpdatedMarker, $wgAllowCategorizedRecentChanges;

		// Build the final data

		if ( $wgAllowCategorizedRecentChanges ) {
			$this->filterByCategories( $rows, $opts );
		}

		$limit = $opts['limit'];

		$showWatcherCount = $wgRCShowWatchingUsers && $this->getUser()->getOption( 'shownumberswatching' );
		$watcherCache = array();

		$dbr = wfGetDB( DB_SLAVE );

		$counter = 1;
		$list = ChangesList::newFromContext( $this->getContext() );

		$rclistOutput = $list->beginRecentChangesList();
		foreach ( $rows as $obj ) {
			if ( $limit == 0 ) {
				break;
			}
			$rc = RecentChange::newFromRow( $obj );
			$rc->counter = $counter++;
			# Check if the page has been updated since the last visit
			if ( $wgShowUpdatedMarker && !empty( $obj->wl_notificationtimestamp ) ) {
				$rc->notificationtimestamp = ( $obj->rc_timestamp >= $obj->wl_notificationtimestamp );
			} else {
				$rc->notificationtimestamp = false; // Default
			}
			# Check the number of users watching the page
			$rc->numberofWatchingusers = 0; // Default
			if ( $showWatcherCount && $obj->rc_namespace >= 0 ) {
				if ( !isset( $watcherCache[$obj->rc_namespace][$obj->rc_title] ) ) {
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

			$changeLine = $list->recentChangesLine( $rc, !empty( $obj->wl_user ), $counter );
			if ( $changeLine !== false ) {
				$rclistOutput .= $changeLine;
				--$limit;
			}
		}
		$rclistOutput .= $list->endRecentChangesList();

		// Print things out

		if ( !$this->including() ) {
			// Output options box
			$this->doHeader( $opts );
		}

		// And now for the content
		$feedQuery = $this->getFeedQuery();
		if ( $feedQuery !== '' ) {
			$this->getOutput()->setFeedAppendQuery( $feedQuery );
		} else {
			$this->getOutput()->setFeedAppendQuery( false );
		}

		if ( $rows->numRows() === 0 ) {
			$this->getOutput()->wrapWikiMsg(
				"<div class='mw-changeslist-empty'>\n$1\n</div>", 'recentchanges-noresult'
			);
		} else {
			$this->getOutput()->addHTML( $rclistOutput );
		}
	}

	/**
	 * Get the query string to append to feed link URLs.
	 *
	 * @return string
	 */
	public function getFeedQuery() {
		global $wgFeedLimit;

		$this->getOptions()->validateIntBounds( 'limit', 0, $wgFeedLimit );
		$options = $this->getOptions()->getChangedValues();

		// wfArrayToCgi() omits options set to null or false
		foreach ( $options as &$value ) {
			if ( $value === false ) {
				$value = '0';
			}
		}
		unset( $value );

		return wfArrayToCgi( $options );
	}

	/**
	 * Return the text to be displayed above the changes
	 *
	 * @param FormOptions $opts
	 * @return string XHTML
	 */
	public function doHeader( $opts ) {
		global $wgScript;

		$this->setTopText( $opts );

		$defaults = $opts->getAllValues();
		$nondefaults = $opts->getChangedValues();

		$panel = array();
		$panel[] = $this->optionsPanel( $defaults, $nondefaults );
		$panel[] = '<hr />';

		$extraOpts = $this->getExtraOptions( $opts );
		$extraOptsCount = count( $extraOpts );
		$count = 0;
		$submit = ' ' . Xml::submitbutton( $this->msg( 'allpagessubmit' )->text() );

		$out = Xml::openElement( 'table', array( 'class' => 'mw-recentchanges-table' ) );
		foreach ( $extraOpts as $name => $optionRow ) {
			# Add submit button to the last row only
			++$count;
			$addSubmit = ( $count === $extraOptsCount ) ? $submit : '';

			$out .= Xml::openElement( 'tr' );
			if ( is_array( $optionRow ) ) {
				$out .= Xml::tags(
					'td',
					array( 'class' => 'mw-label mw-' . $name . '-label' ),
					$optionRow[0]
				);
				$out .= Xml::tags(
					'td',
					array( 'class' => 'mw-input' ),
					$optionRow[1] . $addSubmit
				);
			} else {
				$out .= Xml::tags(
					'td',
					array( 'class' => 'mw-input', 'colspan' => 2 ),
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

		$t = $this->getTitle();
		$out .= Html::hidden( 'title', $t->getPrefixedText() );
		$form = Xml::tags( 'form', array( 'action' => $wgScript ), $out );
		$panel[] = $form;
		$panelString = implode( "\n", $panel );

		$this->getOutput()->addHTML(
			Xml::fieldset(
				$this->msg( 'recentchanges-legend' )->text(),
				$panelString,
				array( 'class' => 'rcoptions' )
			)
		);

		$this->setBottomText( $opts );
	}

	/**
	 * Get options to be displayed in a form
	 *
	 * @param FormOptions $opts
	 * @return array
	 */
	function getExtraOptions( $opts ) {
		$opts->consumeValues( array(
			'namespace', 'invert', 'associated', 'tagfilter', 'categories', 'categories_any'
		) );

		$extraOpts = array();
		$extraOpts['namespace'] = $this->namespaceFilterForm( $opts );

		global $wgAllowCategorizedRecentChanges;
		if ( $wgAllowCategorizedRecentChanges ) {
			$extraOpts['category'] = $this->categoryFilterForm( $opts );
		}

		$tagFilter = ChangeTags::buildTagFilterSelector( $opts['tagfilter'] );
		if ( count( $tagFilter ) ) {
			$extraOpts['tagfilter'] = $tagFilter;
		}

		// Don't fire the hook for subclasses. (Or should we?)
		if ( $this->getName() === 'Recentchanges' ) {
			wfRunHooks( 'SpecialRecentChangesPanel', array( &$extraOpts, $opts ) );
		}

		return $extraOpts;
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
				Html::rawElement( 'p',
					array( 'lang' => $wgContLang->getCode(), 'dir' => $wgContLang->getDir() ),
					"\n" . $message->plain() . "\n"
				),
				/* $lineStart */ false,
				/* $interface */ false
			);
		}
	}

	/**
	 * Send the text to be displayed after the options, for use in subclasses.
	 *
	 * @param FormOptions $opts
	 */
	function setBottomText( FormOptions $opts ) {
	}

	/**
	 * Creates the choose namespace selection
	 *
	 * @todo Uses radio buttons (HASHAR)
	 * @param FormOptions $opts
	 * @return string
	 */
	protected function namespaceFilterForm( FormOptions $opts ) {
		$nsSelect = Html::namespaceSelector(
			array( 'selected' => $opts['namespace'], 'all' => '' ),
			array( 'name' => 'namespace', 'id' => 'namespace' )
		);
		$nsLabel = Xml::label( $this->msg( 'namespace' )->text(), 'namespace' );
		$invert = Xml::checkLabel(
			$this->msg( 'invert' )->text(), 'invert', 'nsinvert',
			$opts['invert'],
			array( 'title' => $this->msg( 'tooltip-invert' )->text() )
		);
		$associated = Xml::checkLabel(
			$this->msg( 'namespace_association' )->text(), 'associated', 'nsassociated',
			$opts['associated'],
			array( 'title' => $this->msg( 'tooltip-namespace_association' )->text() )
		);

		return array( $nsLabel, "$nsSelect $invert $associated" );
	}

	/**
	 * Create a input to filter changes by categories
	 *
	 * @param FormOptions $opts
	 * @return array
	 */
	protected function categoryFilterForm( FormOptions $opts ) {
		list( $label, $input ) = Xml::inputLabelSep( $this->msg( 'rc_categories' )->text(),
			'categories', 'mw-categories', false, $opts['categories'] );

		$input .= ' ' . Xml::checkLabel( $this->msg( 'rc_categories_any' )->text(),
			'categories_any', 'mw-categories_any', $opts['categories_any'] );

		return array( $label, $input );
	}

	/**
	 * Filter $rows by categories set in $opts
	 *
	 * @param array $rows Database rows
	 * @param FormOptions $opts
	 */
	function filterByCategories( &$rows, FormOptions $opts ) {
		$categories = array_map( 'trim', explode( '|', $opts['categories'] ) );

		if ( !count( $categories ) ) {
			return;
		}

		# Filter categories
		$cats = array();
		foreach ( $categories as $cat ) {
			$cat = trim( $cat );
			if ( $cat == '' ) {
				continue;
			}
			$cats[] = $cat;
		}

		# Filter articles
		$articles = array();
		$a2r = array();
		$rowsarr = array();
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
				$a2r[$id] = array();
			}
			$a2r[$id][] = $k;
			$rowsarr[$k] = $r;
		}

		# Shortcut?
		if ( !count( $articles ) || !count( $cats ) ) {
			return;
		}

		# Look up
		$c = new Categoryfinder;
		$c->seed( $articles, $cats, $opts['categories_any'] ? 'OR' : 'AND' );
		$match = $c->run();

		# Filter
		$newrows = array();
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

		// Bug 36524: false values have be converted to "0" otherwise
		// wfArrayToCgi() will omit it them.
		foreach ( $params as &$value ) {
			if ( $value === false ) {
				$value = '0';
			}
		}
		unset( $value );

		$text = htmlspecialchars( $title );
		if ( $active ) {
			$text = '<strong>' . $text . '</strong>';
		}

		return Linker::linkKnown( $this->getTitle(), $text, array(), $params );
	}

	/**
	 * Creates the options panel.
	 *
	 * @param array $defaults
	 * @param array $nondefaults
	 * @return string
	 */
	function optionsPanel( $defaults, $nondefaults ) {
		global $wgRCLinkLimits, $wgRCLinkDays;

		$options = $nondefaults + $defaults;

		$note = '';
		$msg = $this->msg( 'rclegend' );
		if ( !$msg->isDisabled() ) {
			$note .= '<div class="mw-rclegend">' . $msg->parse() . "</div>\n";
		}

		$lang = $this->getLanguage();
		$user = $this->getUser();
		if ( $options['from'] ) {
			$note .= $this->msg( 'rcnotefrom' )->numParams( $options['limit'] )->params(
				$lang->userTimeAndDate( $options['from'], $user ),
				$lang->userDate( $options['from'], $user ),
				$lang->userTime( $options['from'], $user ) )->parse() . '<br />';
		}

		# Sort data for display and make sure it's unique after we've added user data.
		$linkLimits = $wgRCLinkLimits;
		$linkLimits[] = $options['limit'];
		sort( $linkLimits );
		$linkLimits = array_unique( $linkLimits );

		$linkDays = $wgRCLinkDays;
		$linkDays[] = $options['days'];
		sort( $linkDays );
		$linkDays = array_unique( $linkDays );

		// limit links
		$cl = array();
		foreach ( $linkLimits as $value ) {
			$cl[] = $this->makeOptionsLink( $lang->formatNum( $value ),
				array( 'limit' => $value ), $nondefaults, $value == $options['limit'] );
		}
		$cl = $lang->pipeList( $cl );

		// day links, reset 'from' to none
		$dl = array();
		foreach ( $linkDays as $value ) {
			$dl[] = $this->makeOptionsLink( $lang->formatNum( $value ),
				array( 'days' => $value, 'from' => '' ), $nondefaults, $value == $options['days'] );
		}
		$dl = $lang->pipeList( $dl );

		// show/hide links
		$showhide = array( $this->msg( 'show' )->text(), $this->msg( 'hide' )->text() );
		$filters = array(
			'hideminor' => 'rcshowhideminor',
			'hidebots' => 'rcshowhidebots',
			'hideanons' => 'rcshowhideanons',
			'hideliu' => 'rcshowhideliu',
			'hidepatrolled' => 'rcshowhidepatr',
			'hidemyself' => 'rcshowhidemine'
		);
		foreach ( $this->getCustomFilters() as $key => $params ) {
			$filters[$key] = $params['msg'];
		}
		// Disable some if needed
		if ( !$user->useRCPatrol() ) {
			unset( $filters['hidepatrolled'] );
		}

		$links = array();
		foreach ( $filters as $key => $msg ) {
			$link = $this->makeOptionsLink( $showhide[1 - $options[$key]],
				array( $key => 1 - $options[$key] ), $nondefaults );
			$links[] = $this->msg( $msg )->rawParams( $link )->escaped();
		}

		// show from this onward link
		$timestamp = wfTimestampNow();
		$now = $lang->userTimeAndDate( $timestamp, $user );
		$tl = $this->makeOptionsLink(
			$now, array( 'from' => $timestamp ), $nondefaults
		);

		$rclinks = $this->msg( 'rclinks' )->rawParams( $cl, $dl, $lang->pipeList( $links ) )
			->parse();
		$rclistfrom = $this->msg( 'rclistfrom' )->rawParams( $tl )->parse();

		return "{$note}$rclinks<br />$rclistfrom";
	}

	/**
	 * Add page-specific modules.
	 */
	protected function addModules() {
		$this->getOutput()->addModules( array(
			'mediawiki.special.recentchanges',
		) );
	}

	protected function getGroupName() {
		return 'changes';
	}
}
