<?php
/**
 * Implements Special:Watchlist
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
 * @ingroup SpecialPage Watchlist
 */
class SpecialWatchlist extends ChangesListSpecialPage {
	/**
	 * Constructor
	 */
	public function __construct( $page = 'Watchlist', $restriction = 'viewmywatchlist' ) {
		parent::__construct( $page, $restriction );
	}

	/**
	 * Execute
	 * @param $par Parameter passed to the page
	 */
	function execute( $par ) {
		global $wgRCShowWatchingUsers, $wgEnotifWatchlist, $wgShowUpdatedMarker;

		$user = $this->getUser();
		$output = $this->getOutput();

		# Anons don't get a watchlist
		$this->requireLogin( 'watchlistanontext' );

		// Check permissions
		$this->checkPermissions();

		$request = $this->getRequest();
		$opts = $this->getOptions();

		$mode = SpecialEditWatchlist::getMode( $request, $par );
		if ( $mode !== false ) {
			if ( $mode === SpecialEditWatchlist::EDIT_RAW ) {
				$title = SpecialPage::getTitleFor( 'EditWatchlist', 'raw' );
			} else {
				$title = SpecialPage::getTitleFor( 'EditWatchlist' );
			}

			$output->redirect( $title->getLocalURL() );
			return;
		}

		if ( ( $wgEnotifWatchlist || $wgShowUpdatedMarker ) && $request->getVal( 'reset' ) &&
			$request->wasPosted() )
		{
			$user->clearAllNotifications();
			$output->redirect( $this->getTitle()->getFullURL( $opts->getChangedValues() ) );
			return;
		}

		$this->setHeaders();
		$this->outputHeader();
		$this->addModules();

		$dbr = wfGetDB( DB_SLAVE, 'watchlist' );

		$this->numItems = $this->countItems( $dbr ); // TODO kill me
		if ( $this->numItems == 0 ) {
			$output->addWikiMsg( 'nowatchlist' );
			return;
		}

		// Fetch results, prepare a batch link existence check query
		$conds = $this->buildMainQueryConds( $opts );
		$rows = $this->doMainQuery( $conds, $opts );
		$this->numRows = $rows->numRows(); // TODO kill me
		if ( $rows === false ) {
			$this->doHeader( $opts );

			return;
		}

		$feedFormat = $this->getRequest()->getVal( 'feed' );
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
	 * Get a FormOptions object containing the default options
	 *
	 * @return FormOptions
	 */
	public function getDefaultOptions() {
		$opts = parent::getDefaultOptions();
		$user = $this->getUser();

		$opts->add( 'days', $user->getOption( 'watchlistdays' ), FormOptions::FLOAT );

		$opts->add( 'hideminor', $user->getBoolOption( 'watchlisthideminor' ) );
		$opts->add( 'hidebots', $user->getBoolOption( 'watchlisthidebots' ) );
		$opts->add( 'hideanons', $user->getBoolOption( 'watchlisthideanons' ) );
		$opts->add( 'hideliu', $user->getBoolOption( 'watchlisthideliu' ) );
		$opts->add( 'hidepatrolled', $user->getBoolOption( 'watchlisthidepatrolled' ) );
		$opts->add( 'hidemyself', $user->getBoolOption( 'watchlisthideown' ) );

		$opts->add( 'extended', $user->getBoolOption( 'extendwatchlist' ) );

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
			wfRunHooks( 'SpecialWatchlistFilters', array( $this, &$this->customFilters ) );
		}

		return $this->customFilters;
	}

	/**
	 * Fetch values for a FormOptions object from the WebRequest associated with this instance.
	 *
	 * Maps old pre-1.23 request parameters Watchlist used to use (different from Recentchanges' ones)
	 * to the current ones.
	 *
	 * @param FormOptions $parameters
	 * @return FormOptions
	 */
	protected function fetchOptionsFromRequest( $opts ) {
		static $compatibilityMap = array(
			'hideMinor' => 'hideminor',
			'hideBots' => 'hidebots',
			'hideAnons' => 'hideanons',
			'hideLiu' => 'hideliu',
			'hidePatrolled' => 'hidepatrolled',
			'hideOwn' => 'hidemyself',
		);

		$params = $this->getRequest()->getValues();
		foreach ( $compatibilityMap as $from => $to ) {
			if ( isset( $params[$from] ) ) {
				$params[$to] = $params[$from];
				unset( $params[$from] );
			}
		}

		// Not the prettiest way to achieve this… FormOptions internally depends on data sanitization
		// methods defined on WebRequest and removing this dependency would cause some code duplication.
		$request = new DerivativeRequest( $this->getRequest(), $params );
		$opts->fetchValuesFromRequest( $request );
		return $opts;
	}

	/**
	 * Return an array of conditions depending of options set in $opts
	 *
	 * @param FormOptions $opts
	 * @return array
	 */
	public function buildMainQueryConds( FormOptions $opts ) {
		$dbr = wfGetDB( DB_SLAVE, 'watchlist' );
		$conds = array();
		$user = $this->getUser();

		// Calculate cutoff
		if ( $opts['days'] > 0 ) {
			$conds[] = 'rc_timestamp > ' . $dbr->addQuotes( $dbr->timestamp( time() - intval( $opts['days'] * 86400 ) ) );
		}

		# Toggles
		if ( $opts['hidemyself'] ) {
			$conds[] = 'rc_user != ' . $user->getId();
		}
		if ( $opts['hidebots'] ) {
			$conds[] = 'rc_bot = 0';
		}
		if ( $opts['hideminor'] ) {
			$conds[] = 'rc_minor = 0';
		}
		if ( $opts['hideliu'] ) {
			$conds[] = 'rc_user = 0';
		}
		if ( $opts['hideanons'] ) {
			$conds[] = 'rc_user != 0';
		}
		if ( $user->useRCPatrol() && $opts['hidepatrolled'] ) {
			$conds[] = 'rc_patrolled != 1';
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
		global $wgShowUpdatedMarker;

		$dbr = wfGetDB( DB_SLAVE, 'watchlist' );
		$user = $this->getUser();
		# Toggle watchlist content (all recent edits or just the latest)
		if ( $opts['extended'] ) {
			$limitWatchlist = $user->getIntOption( 'wllimit' );
			$usePage = false;
		} else {
			# Top log Ids for a page are not stored
			$nonRevisionTypes = array( RC_LOG );
			wfRunHooks( 'SpecialWatchlistGetNonRevisionTypes', array( &$nonRevisionTypes ) );
			if ( $nonRevisionTypes ) {
				$conds[] = $dbr->makeList(
					array(
						'rc_this_oldid=page_latest',
						'rc_type' => $nonRevisionTypes,
					),
					LIST_OR
				);
			}
			$limitWatchlist = 0;
			$usePage = true;
		}

		$tables = array( 'recentchanges', 'watchlist' );
		$fields = RecentChange::selectFields();
		$join_conds = array(
			'watchlist' => array(
				'INNER JOIN',
				array(
					'wl_user' => $user->getId(),
					'wl_namespace=rc_namespace',
					'wl_title=rc_title'
				),
			),
		);
		$options = array( 'ORDER BY' => 'rc_timestamp DESC' );
		if ( $wgShowUpdatedMarker ) {
			$fields[] = 'wl_notificationtimestamp';
		}
		if ( $limitWatchlist ) {
			$options['LIMIT'] = $limitWatchlist;
		}

		$rollbacker = $user->isAllowed( 'rollback' );
		if ( $usePage || $rollbacker ) {
			$tables[] = 'page';
			$join_conds['page'] = array( 'LEFT JOIN', 'rc_cur_id=page_id' );
			if ( $rollbacker ) {
				$fields[] = 'page_latest';
			}
		}

		ChangeTags::modifyDisplayQuery( $tables, $fields, $conds, $join_conds, $options, '' );
		wfRunHooks( 'SpecialWatchlistQuery', array( &$conds, &$tables, &$join_conds, &$fields, $opts ) );

		return $dbr->select( $tables, $fields, $conds, __METHOD__, $options, $join_conds );
	}

	/**
	 * Send output to the OutputPage object, only called if not used feeds
	 *
	 * @param array $rows Database rows
	 * @param FormOptions $opts
	 */
	public function webOutput( $rows, $opts ) {
		global $wgShowUpdatedMarker, $wgRCShowWatchingUsers;

		$dbr = wfGetDB( DB_SLAVE, 'watchlist' );
		$user = $this->getUser();

		$dbr->dataSeek( $rows, 0 );

		$list = ChangesList::newFromContext( $this->getContext() );
		$list->setWatchlistDivs();

		$s = $list->beginRecentChangesList();
		$counter = 1;
		foreach ( $rows as $obj ) {
			# Make RC entry
			$rc = RecentChange::newFromRow( $obj );
			$rc->counter = $counter++;

			if ( $wgShowUpdatedMarker ) {
				$updated = $obj->wl_notificationtimestamp;
			} else {
				$updated = false;
			}

			if ( $wgRCShowWatchingUsers && $user->getOption( 'shownumberswatching' ) ) {
				$rc->numberofWatchingusers = $dbr->selectField( 'watchlist',
					'COUNT(*)',
					array(
						'wl_namespace' => $obj->rc_namespace,
						'wl_title' => $obj->rc_title,
					),
					__METHOD__ );
			} else {
				$rc->numberofWatchingusers = 0;
			}

			$changeLine = $list->recentChangesLine( $rc, $updated, $counter );
			if ( $changeLine !== false ) {
				$s .= $changeLine;
			}
		}
		$s .= $list->endRecentChangesList();

		// Print things out

		$output = $this->getOutput();

		$output->addSubtitle(
			$this->msg( 'watchlistfor2', $user->getName() )
				->rawParams( SpecialEditWatchlist::buildTools( null ) )
		);

		// Output options box
		$this->doHeader( $opts );

		// Add feed links
		$wlToken = $user->getTokenFromOption( 'watchlisttoken' );
		if ( $wlToken ) {
			$this->addFeedLinks( array(
				'action' => 'feedwatchlist',
				'allrev' => 1,
				'wlowner' => $user->getName(),
				'wltoken' => $wlToken,
			) );
		}

		# Show a message about slave lag, if applicable
		$lag = wfGetLB()->safeGetLag( $dbr );
		if ( $lag > 0 ) {
			$output->showLagWarning( $lag );
		}

		if ( $rows->numRows() == 0 ) {
			$output->wrapWikiMsg(
				"<div class='mw-changeslist-empty'>\n$1\n</div>", 'recentchanges-noresult'
			);
		} else {
			$output->addHTML( $s );
		}
	}

	/**
	 * Return the text to be displayed above the changes
	 *
	 * @param FormOptions $opts
	 * @return string XHTML
	 */
	public function doHeader( $opts ) {
		global $wgScript;

		$user = $this->getUser();

		$this->setTopText( $opts );

		$lang = $this->getLanguage();
		$wlInfo = '';
		if ( $opts['days'] > 0 ) {
			$timestamp = wfTimestampNow();
			$wlInfo = $this->msg( 'wlnote' )->numParams( $this->numRows, round( $opts['days'] * 24 ) )->params(
				$lang->userDate( $timestamp, $user ), $lang->userTime( $timestamp, $user ) )->parse() . "<br />\n";
		}

		$nondefaults = $opts->getChangedValues();
		$cutofflinks = $this->cutoffLinks( $opts['days'], $nondefaults ) . "<br />\n";

		# Spit out some control panel links
		$filters = array(
			'hideminor' => 'rcshowhideminor',
			'hidebots' => 'rcshowhidebots',
			'hideanons' => 'rcshowhideanons',
			'hideliu' => 'rcshowhideliu',
			'hidemyself' => 'rcshowhidemine',
			'hidepatrolled' => 'rcshowhidepatr'
		);
		foreach ( $this->getCustomFilters() as $key => $params ) {
			$filters[$key] = $params['msg'];
		}
		// Disable some if needed
		if ( !$user->useNPPatrol() ) {
			unset( $filters['hidepatrolled'] );
		}

		$links = array();
		foreach ( $filters as $name => $msg ) {
			$links[] = $this->showHideLink( $nondefaults, $msg, $name, $opts[$name] );
		}

		$hiddenFields = $nondefaults;
		unset( $hiddenFields['namespace'] );
		unset( $hiddenFields['invert'] );
		unset( $hiddenFields['associated'] );

		# Create output
		$form = '';

		# Namespace filter and put the whole form together.
		$form .= $wlInfo;
		$form .= $cutofflinks;
		$form .= $lang->pipeList( $links ) . "\n";
		$form .= "<hr />\n<p>";
		$form .= Html::namespaceSelector(
			array(
				'selected' => $opts['namespace'],
				'all' => '',
				'label' => $this->msg( 'namespace' )->text()
			), array(
				'name' => 'namespace',
				'id' => 'namespace',
				'class' => 'namespaceselector',
			)
		) . '&#160;';
		$form .= Xml::checkLabel(
			$this->msg( 'invert' )->text(),
			'invert',
			'nsinvert',
			$opts['invert'],
			array( 'title' => $this->msg( 'tooltip-invert' )->text() )
		) . '&#160;';
		$form .= Xml::checkLabel(
			$this->msg( 'namespace_association' )->text(),
			'associated',
			'nsassociated',
			$opts['associated'],
			array( 'title' => $this->msg( 'tooltip-namespace_association' )->text() )
		) . '&#160;';
		$form .= Xml::submitButton( $this->msg( 'allpagessubmit' )->text() ) . "</p>\n";
		foreach ( $hiddenFields as $key => $value ) {
			$form .= Html::hidden( $key, $value ) . "\n";
		}
		$form .= Xml::closeElement( 'fieldset' ) . "\n";
		$form .= Xml::closeElement( 'form' ) . "\n";
		$this->getOutput()->addHTML( $form );

		$this->setBottomText( $opts );
	}

	function setTopText( FormOptions $opts ) {
		global $wgEnotifWatchlist, $wgShowUpdatedMarker;

		$nondefaults = $opts->getChangedValues();
		$form = "";

		# Show watchlist header
		$form .= "<p>";
		$form .= $this->msg( 'watchlist-details' )->numParams( $this->numItems )->parse() . "\n";
		if ( $wgEnotifWatchlist && $user->getOption( 'enotifwatchlistpages' ) ) {
			$form .= $this->msg( 'wlheader-enotif' )->parse() . "\n";
		}
		if ( $wgShowUpdatedMarker ) {
			$form .= $this->msg( 'wlheader-showupdated' )->parse() . "\n";
		}
		$form .= "</p>";

		if ( $wgShowUpdatedMarker ) {
			$form .= Xml::openElement( 'form', array( 'method' => 'post',
				'action' => $this->getTitle()->getLocalURL(),
				'id' => 'mw-watchlist-resetbutton' ) ) . "\n" .
			Xml::submitButton( $this->msg( 'enotif_reset' )->text(), array( 'name' => 'dummy' ) ) . "\n" .
			Html::hidden( 'reset', 'all' ) . "\n";
			foreach ( $nondefaults as $key => $value ) {
				$form .= Html::hidden( $key, $value ) . "\n";
			}
			$form .= Xml::closeElement( 'form' ) . "\n";
		}

		$form .= Xml::openElement( 'form', array(
			'method' => 'post',
			'action' => $this->getTitle()->getLocalURL(),
			'id' => 'mw-watchlist-form'
		) );
		$form .= Xml::fieldset(
			$this->msg( 'watchlist-options' )->text(),
			false,
			array( 'id' => 'mw-watchlist-options' )
		);

		$this->getOutput()->addHTML( $form );
	}

	protected function showHideLink( $options, $message, $name, $value ) {
		$label = $this->msg( $value ? 'show' : 'hide' )->escaped();
		$options[$name] = 1 - (int)$value;

		return $this->msg( $message )->rawParams( Linker::linkKnown( $this->getTitle(), $label, array(), $options ) )->escaped();
	}

	protected function hoursLink( $h, $options = array() ) {
		$options['days'] = ( $h / 24.0 );

		return Linker::linkKnown(
			$this->getTitle(),
			$this->getLanguage()->formatNum( $h ),
			array(),
			$options
		);
	}

	protected function daysLink( $d, $options = array() ) {
		$options['days'] = $d;
		$message = ( $d ? $this->getLanguage()->formatNum( $d ) : $this->msg( 'watchlistall2' )->escaped() );

		return Linker::linkKnown(
			$this->getTitle(),
			$message,
			array(),
			$options
		);
	}

	/**
	 * Returns html
	 *
	 * @param int $days This gets overwritten, so is not used
	 * @param array $options Query parameters for URL
	 * @return string
	 */
	protected function cutoffLinks( $days, $options = array() ) {
		$hours = array( 1, 2, 6, 12 );
		$days = array( 1, 3, 7 );
		$i = 0;
		foreach ( $hours as $h ) {
			$hours[$i++] = $this->hoursLink( $h, $options );
		}
		$i = 0;
		foreach ( $days as $d ) {
			$days[$i++] = $this->daysLink( $d, $options );
		}
		return $this->msg( 'wlshowlast' )->rawParams(
			$this->getLanguage()->pipeList( $hours ),
			$this->getLanguage()->pipeList( $days ),
			$this->daysLink( 0, $options ) )->parse();
	}

	/**
	 * Count the number of items on a user's watchlist
	 *
	 * @param DatabaseBase $dbr A database connection
	 * @return Integer
	 */
	protected function countItems( $dbr ) {
		# Fetch the raw count
		$rows = $dbr->select( 'watchlist', array( 'count' => 'COUNT(*)' ),
			array( 'wl_user' => $this->getUser()->getId() ), __METHOD__ );
		$row = $dbr->fetchObject( $rows );
		$count = $row->count;

		return floor( $count / 2 );
	}
}
