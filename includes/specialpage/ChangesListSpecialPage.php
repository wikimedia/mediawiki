<?php
/**
 * Special page which uses a ChangesList to show query results.
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
 * Special page which uses a ChangesList to show query results.
 * @todo Way too many public functions, most of them should be protected
 *
 * @ingroup SpecialPage
 */
abstract class ChangesListSpecialPage extends SpecialPage {
	/** @var string */
	protected $rcSubpage;

	/** @var FormOptions */
	protected $rcOptions;

	/** @var array */
	protected $customFilters;

	/**
	 * Main execution point
	 *
	 * @param string $subpage
	 */
	public function execute( $subpage ) {
		$this->rcSubpage = $subpage;

		$this->setHeaders();
		$this->outputHeader();
		$this->addModules();

		$rows = $this->getRows();
		$opts = $this->getOptions();
		if ( $rows === false ) {
			if ( !$this->including() ) {
				$this->doHeader( $opts, 0 );
				$this->getOutput()->setStatusCode( 404 );
			}

			return;
		}

		$batch = new LinkBatch;
		foreach ( $rows as $row ) {
			$batch->add( NS_USER, $row->rc_user_text );
			$batch->add( NS_USER_TALK, $row->rc_user_text );
			$batch->add( $row->rc_namespace, $row->rc_title );
			if ( $row->rc_source === RecentChange::SRC_LOG ) {
				$formatter = LogFormatter::newFromRow( $row );
				foreach ( $formatter->getPreloadTitles() as $title ) {
					$batch->addObj( $title );
				}
			}
		}
		$batch->execute();

		$this->webOutput( $rows, $opts );

		$rows->free();
	}

	/**
	 * Get the database result for this special page instance. Used by ApiFeedRecentChanges.
	 *
	 * @return bool|ResultWrapper Result or false
	 */
	public function getRows() {
		$opts = $this->getOptions();
		$conds = $this->buildMainQueryConds( $opts );

		return $this->doMainQuery( $conds, $opts );
	}

	/**
	 * Get the current FormOptions for this request
	 *
	 * @return FormOptions
	 */
	public function getOptions() {
		if ( $this->rcOptions === null ) {
			$this->rcOptions = $this->setup( $this->rcSubpage );
		}

		return $this->rcOptions;
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

		$opts = $this->fetchOptionsFromRequest( $opts );

		// Give precedence to subpage syntax
		if ( $parameters !== null ) {
			$this->parseParameters( $parameters, $opts );
		}

		$this->validateOptions( $opts );

		return $opts;
	}

	/**
	 * Get a FormOptions object containing the default options. By default returns some basic options,
	 * you might want to not call parent method and discard them, or to override default values.
	 *
	 * @return FormOptions
	 */
	public function getDefaultOptions() {
		$config = $this->getConfig();
		$opts = new FormOptions();

		$opts->add( 'hideminor', false );
		$opts->add( 'hidebots', false );
		$opts->add( 'hideanons', false );
		$opts->add( 'hideliu', false );
		$opts->add( 'hidepatrolled', false );
		$opts->add( 'hidemyself', false );

		if ( $config->get( 'RCWatchCategoryMembership' ) ) {
			$opts->add( 'hidecategorization', false );
		}

		$opts->add( 'namespace', '', FormOptions::INTNULL );
		$opts->add( 'invert', false );
		$opts->add( 'associated', false );

		return $opts;
	}

	/**
	 * Get custom show/hide filters
	 *
	 * @return array Map of filter URL param names to properties (msg/default)
	 */
	protected function getCustomFilters() {
		if ( $this->customFilters === null ) {
			$this->customFilters = [];
			Hooks::run( 'ChangesListSpecialPageFilters', [ $this, &$this->customFilters ] );
		}

		return $this->customFilters;
	}

	/**
	 * Fetch values for a FormOptions object from the WebRequest associated with this instance.
	 *
	 * Intended for subclassing, e.g. to add a backwards-compatibility layer.
	 *
	 * @param FormOptions $opts
	 * @return FormOptions
	 */
	protected function fetchOptionsFromRequest( $opts ) {
		$opts->fetchValuesFromRequest( $this->getRequest() );

		return $opts;
	}

	/**
	 * Process $par and put options found in $opts. Used when including the page.
	 *
	 * @param string $par
	 * @param FormOptions $opts
	 */
	public function parseParameters( $par, FormOptions $opts ) {
		// nothing by default
	}

	/**
	 * Validate a FormOptions object generated by getDefaultOptions() with values already populated.
	 *
	 * @param FormOptions $opts
	 */
	public function validateOptions( FormOptions $opts ) {
		// nothing by default
	}

	/**
	 * Return an array of conditions depending of options set in $opts
	 *
	 * @param FormOptions $opts
	 * @return array
	 */
	public function buildMainQueryConds( FormOptions $opts ) {
		$dbr = $this->getDB();
		$user = $this->getUser();
		$conds = [];

		// It makes no sense to hide both anons and logged-in users. When this occurs, try a guess on
		// what the user meant and either show only bots or force anons to be shown.
		$botsonly = false;
		$hideanons = $opts['hideanons'];
		if ( $opts['hideanons'] && $opts['hideliu'] ) {
			if ( $opts['hidebots'] ) {
				$hideanons = false;
			} else {
				$botsonly = true;
			}
		}

		// Toggles
		if ( $opts['hideminor'] ) {
			$conds['rc_minor'] = 0;
		}
		if ( $opts['hidebots'] ) {
			$conds['rc_bot'] = 0;
		}
		if ( $user->useRCPatrol() && $opts['hidepatrolled'] ) {
			$conds['rc_patrolled'] = 0;
		}
		if ( $botsonly ) {
			$conds['rc_bot'] = 1;
		} else {
			if ( $opts['hideliu'] ) {
				$conds[] = 'rc_user = 0';
			}
			if ( $hideanons ) {
				$conds[] = 'rc_user != 0';
			}
		}
		if ( $opts['hidemyself'] ) {
			if ( $user->getId() ) {
				$conds[] = 'rc_user != ' . $dbr->addQuotes( $user->getId() );
			} else {
				$conds[] = 'rc_user_text != ' . $dbr->addQuotes( $user->getName() );
			}
		}
		if ( $this->getConfig()->get( 'RCWatchCategoryMembership' )
			&& $opts['hidecategorization'] === true
		) {
			$conds[] = 'rc_type != ' . $dbr->addQuotes( RC_CATEGORIZE );
		}

		// Namespace filtering
		if ( $opts['namespace'] !== '' ) {
			$selectedNS = $dbr->addQuotes( $opts['namespace'] );
			$operator = $opts['invert'] ? '!=' : '=';
			$boolean = $opts['invert'] ? 'AND' : 'OR';

			// Namespace association (bug 2429)
			if ( !$opts['associated'] ) {
				$condition = "rc_namespace $operator $selectedNS";
			} else {
				// Also add the associated namespace
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
	 * @return bool|ResultWrapper Result or false
	 */
	public function doMainQuery( $conds, $opts ) {
		$tables = [ 'recentchanges' ];
		$fields = RecentChange::selectFields();
		$query_options = [];
		$join_conds = [];

		ChangeTags::modifyDisplayQuery(
			$tables,
			$fields,
			$conds,
			$join_conds,
			$query_options,
			''
		);

		if ( !$this->runMainQueryHook( $tables, $fields, $conds, $query_options, $join_conds,
			$opts )
		) {
			return false;
		}

		$dbr = $this->getDB();

		return $dbr->select(
			$tables,
			$fields,
			$conds,
			__METHOD__,
			$query_options,
			$join_conds
		);
	}

	protected function runMainQueryHook( &$tables, &$fields, &$conds,
		&$query_options, &$join_conds, $opts
	) {
		return Hooks::run(
			'ChangesListSpecialPageQuery',
			[ $this->getName(), &$tables, &$fields, &$conds, &$query_options, &$join_conds, $opts ]
		);
	}

	/**
	 * Return a IDatabase object for reading
	 *
	 * @return IDatabase
	 */
	protected function getDB() {
		return wfGetDB( DB_SLAVE );
	}

	/**
	 * Send output to the OutputPage object, only called if not used feeds
	 *
	 * @param ResultWrapper $rows Database rows
	 * @param FormOptions $opts
	 */
	public function webOutput( $rows, $opts ) {
		if ( !$this->including() ) {
			$this->outputFeedLinks();
			$this->doHeader( $opts, $rows->numRows() );
		}

		$this->outputChangesList( $rows, $opts );
	}

	/**
	 * Output feed links.
	 */
	public function outputFeedLinks() {
		// nothing by default
	}

	/**
	 * Build and output the actual changes list.
	 *
	 * @param array $rows Database rows
	 * @param FormOptions $opts
	 */
	abstract public function outputChangesList( $rows, $opts );

	/**
	 * Set the text to be displayed above the changes
	 *
	 * @param FormOptions $opts
	 * @param int $numRows Number of rows in the result to show after this header
	 */
	public function doHeader( $opts, $numRows ) {
		$this->setTopText( $opts );

		// @todo Lots of stuff should be done here.

		$this->setBottomText( $opts );
	}

	/**
	 * Send the text to be displayed before the options. Should use $this->getOutput()->addWikiText()
	 * or similar methods to print the text.
	 *
	 * @param FormOptions $opts
	 */
	public function setTopText( FormOptions $opts ) {
		// nothing by default
	}

	/**
	 * Send the text to be displayed after the options. Should use $this->getOutput()->addWikiText()
	 * or similar methods to print the text.
	 *
	 * @param FormOptions $opts
	 */
	public function setBottomText( FormOptions $opts ) {
		// nothing by default
	}

	/**
	 * Get options to be displayed in a form
	 * @todo This should handle options returned by getDefaultOptions().
	 * @todo Not called by anything, should be called by something… doHeader() maybe?
	 *
	 * @param FormOptions $opts
	 * @return array
	 */
	public function getExtraOptions( $opts ) {
		return [];
	}

	/**
	 * Return the legend displayed within the fieldset
	 *
	 * @return string
	 */
	public function makeLegend() {
		$context = $this->getContext();
		$user = $context->getUser();
		# The legend showing what the letters and stuff mean
		$legend = Html::openElement( 'dl' ) . "\n";
		# Iterates through them and gets the messages for both letter and tooltip
		$legendItems = $context->getConfig()->get( 'RecentChangesFlags' );
		if ( !( $user->useRCPatrol() || $user->useNPPatrol() ) ) {
			unset( $legendItems['unpatrolled'] );
		}
		foreach ( $legendItems as $key => $item ) { # generate items of the legend
			$label = isset( $item['legend'] ) ? $item['legend'] : $item['title'];
			$letter = $item['letter'];
			$cssClass = isset( $item['class'] ) ? $item['class'] : $key;

			$legend .= Html::element( 'dt',
				[ 'class' => $cssClass ], $context->msg( $letter )->text()
			) . "\n" .
			Html::rawElement( 'dd',
				[ 'class' => Sanitizer::escapeClass( 'mw-changeslist-legend-' . $key ) ],
				$context->msg( $label )->parse()
			) . "\n";
		}
		# (+-123)
		$legend .= Html::rawElement( 'dt',
			[ 'class' => 'mw-plusminus-pos' ],
			$context->msg( 'recentchanges-legend-plusminus' )->parse()
		) . "\n";
		$legend .= Html::element(
			'dd',
			[ 'class' => 'mw-changeslist-legend-plusminus' ],
			$context->msg( 'recentchanges-label-plusminus' )->text()
		) . "\n";
		$legend .= Html::closeElement( 'dl' ) . "\n";

		# Collapsibility
		$legend =
			'<div class="mw-changeslist-legend">' .
				$context->msg( 'recentchanges-legend-heading' )->parse() .
				'<div class="mw-collapsible-content">' . $legend . '</div>' .
			'</div>';

		return $legend;
	}

	/**
	 * Add page-specific modules.
	 */
	protected function addModules() {
		$out = $this->getOutput();
		// Styles and behavior for the legend box (see makeLegend())
		$out->addModuleStyles( 'mediawiki.special.changeslist.legend' );
		$out->addModules( 'mediawiki.special.changeslist.legend.js' );
	}

	protected function getGroupName() {
		return 'changes';
	}
}
