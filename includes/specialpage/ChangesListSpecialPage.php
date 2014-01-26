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
	var $rcSubpage, $rcOptions; // @todo Rename these, make protected
	protected $customFilters;

	/**
	 * The feed format to output as (either 'rss' or 'atom'), or null if no
	 * feed output was requested
	 *
	 * @var string $feedFormat
	 */
	protected $feedFormat;

	/**
	 * Main execution point
	 *
	 * @param string $subpage
	 */
	public function execute( $subpage ) {
		$this->rcSubpage = $subpage;
		$this->feedFormat = $this->including() ? null : $this->getRequest()->getVal( 'feed' );
		if ( $this->feedFormat !== 'atom' && $this->feedFormat !== 'rss' ) {
			$this->feedFormat = null;
		}

		$this->setHeaders();
		$this->outputHeader();
		$this->addModules();

		$opts = $this->getOptions();
		// Fetch results, prepare a batch link existence check query
		$conds = $this->buildMainQueryConds( $opts );
		$rows = $this->doMainQuery( $conds, $opts );
		if ( $rows === false ) {
			if ( !$this->including() ) {
				$this->doHeader( $opts );
			}

			return;
		}

		if ( !$this->feedFormat ) {
			$batch = new LinkBatch;
			foreach ( $rows as $row ) {
				$batch->add( NS_USER, $row->rc_user_text );
				$batch->add( NS_USER_TALK, $row->rc_user_text );
				$batch->add( $row->rc_namespace, $row->rc_title );
			}
			$batch->execute();
		}
		if ( $this->feedFormat ) {
			list( $changesFeed, $formatter ) = $this->getFeedObject( $this->feedFormat );
			/** @var ChangesFeed $changesFeed */
			$changesFeed->execute( $formatter, $rows, $this->checkLastModified( $this->feedFormat ), $opts );
		} else {
			$this->webOutput( $rows, $opts );
		}

		$rows->free();
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
		$opts = new FormOptions();

		$opts->add( 'hideminor', false );
		$opts->add( 'hidebots', false );
		$opts->add( 'hideanons', false );
		$opts->add( 'hideliu', false );
		$opts->add( 'hidepatrolled', false );
		$opts->add( 'hidemyself', false );

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
		// @todo Fire a Special{$this->getName()}Filters hook here
		return array();
	}

	/**
	 * Fetch values for a FormOptions object from the WebRequest associated with this instance.
	 *
	 * Intended for subclassing, e.g. to add a backwards-compatibility layer.
	 *
	 * @param FormOptions $parameters
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
	 * @todo Whyyyy is this mutating $opts…
	 *
	 * @param FormOptions $opts
	 * @return array
	 */
	public function buildMainQueryConds( FormOptions $opts ) {
		$dbr = $this->getDB();
		$user = $this->getUser();
		$conds = array();

		// It makes no sense to hide both anons and logged-in users
		// Where this occurs, force anons to be shown
		$botsOnly = false;
		if ( $opts['hideanons'] && $opts['hideliu'] ) {
			// Check if the user wants to show bots only
			if ( $opts['hidebots'] ) {
				$opts['hideanons'] = false;
			} else {
				$botsOnly = true;
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
		if ( $botsOnly ) {
			$conds['rc_bot'] = 1;
		} else {
			if ( $opts['hideliu'] ) {
				$conds[] = 'rc_user = 0';
			}
			if ( $opts['hideanons'] ) {
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
	 * @todo This should build some basic processing here…
	 *
	 * @param array $conds
	 * @param FormOptions $opts
	 * @return bool|ResultWrapper Result or false (for Recentchangeslinked only)
	 */
	abstract public function doMainQuery( $conds, $opts );

	/**
	 * Return a DatabaseBase object for reading
	 *
	 * @return DatabaseBase
	 */
	protected function getDB() {
		return wfGetDB( DB_SLAVE );
	}

	/**
	 * Send output to the OutputPage object, only called if not used feeds
	 * @todo This should do most, if not all, of the outputting now done by subclasses
	 *
	 * @param ResultWrapper $rows Database rows
	 * @param FormOptions $opts
	 */
	abstract public function webOutput( $rows, $opts );

	/**
	 * Return the text to be displayed above the changes
	 * @todo Not called by anything, should be called by webOutput()
	 *
	 * @param FormOptions $opts
	 * @return string XHTML
	 */
	public function doHeader( $opts ) {
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
	function setTopText( FormOptions $opts ) {
		// nothing by default
	}

	/**
	 * Send the text to be displayed after the options. Should use $this->getOutput()->addWikiText()
	 * or similar methods to print the text.
	 *
	 * @param FormOptions $opts
	 */
	function setBottomText( FormOptions $opts ) {
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
	function getExtraOptions( $opts ) {
		return array();
	}

	/**
	 * Return the legend displayed within the fieldset
	 * @todo This should not be static, then we can drop the parameter
	 * @todo Not called by anything, should be called by doHeader()
	 *
	 * @param $context the object available as $this in non-static functions
	 * @return string
	 */
	public static function makeLegend( IContextSource $context ) {
		global $wgRecentChangesFlags;
		$user = $context->getUser();
		# The legend showing what the letters and stuff mean
		$legend = Xml::openElement( 'dl' ) . "\n";
		# Iterates through them and gets the messages for both letter and tooltip
		$legendItems = $wgRecentChangesFlags;
		if ( !$user->useRCPatrol() ) {
			unset( $legendItems['unpatrolled'] );
		}
		foreach ( $legendItems as $key => $legendInfo ) { # generate items of the legend
			$label = $legendInfo['title'];
			$letter = $legendInfo['letter'];
			$cssClass = isset( $legendInfo['class'] ) ? $legendInfo['class'] : $key;

			$legend .= Xml::element( 'dt',
				array( 'class' => $cssClass ), $context->msg( $letter )->text()
			) . "\n";
			if ( $key === 'newpage' ) {
				$legend .= Xml::openElement( 'dd' );
				$legend .= $context->msg( $label )->escaped();
				$legend .= ' ' . $context->msg( 'recentchanges-legend-newpage' )->parse();
				$legend .= Xml::closeElement( 'dd' ) . "\n";
			} else {
				$legend .= Xml::element( 'dd', array(),
					$context->msg( $label )->text()
				) . "\n";
			}
		}
		# (+-123)
		$legend .= Xml::tags( 'dt',
			array( 'class' => 'mw-plusminus-pos' ),
			$context->msg( 'recentchanges-legend-plusminus' )->parse()
		) . "\n";
		$legend .= Xml::element(
			'dd',
			array( 'class' => 'mw-changeslist-legend-plusminus' ),
			$context->msg( 'recentchanges-label-plusminus' )->text()
		) . "\n";
		$legend .= Xml::closeElement( 'dl' ) . "\n";

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

	/**
	 * Return an array with a ChangesFeed object and ChannelFeed object.
	 *
	 * This is intentionally not abstract not to require subclasses which don't
	 * use feeds functionality to implement it.
	 *
	 * @param string $feedFormat Feed's format (either 'rss' or 'atom')
	 * @return array
	 */
	public function getFeedObject( $feedFormat ) {
		throw new MWException( "Not implemented" );
	}

	/**
	 * Get last-modified date, for client caching. Not implemented by default
	 * (returns current time).
	 *
	 * @param string $feedFormat
	 * @return string|bool
	 */
	public function checkLastModified( $feedFormat ) {
		return wfTimestampNow();
	}

	protected function getGroupName() {
		return 'changes';
	}
}
