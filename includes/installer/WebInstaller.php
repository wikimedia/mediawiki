<?php
/**
 * Core installer web interface.
 *
 * @file
 * @ingroup Deployment
 */

/**
 * Class for the core installer web interface.
 *
 * @ingroup Deployment
 * @since 1.17
 */
class WebInstaller extends Installer {

	/**
	 * @var WebInstallerOutput
	 */
	public $output;

	/**
	 * WebRequest object.
	 *
	 * @var WebRequest
	 */
	public $request;

	/**
	 * Cached session array.
	 *
	 * @var array
	 */
	protected $session;

	/**
	 * Captured PHP error text. Temporary.
	 * @var array
	 */
	protected $phpErrors;

	/**
	 * The main sequence of page names. These will be displayed in turn.
	 * To add one:
	 *    * Add it here
	 *    * Add a config-page-<name> message
	 *    * Add a WebInstaller_<name> class
	 * @var array
	 */
	public $pageSequence = array(
		'Language',
		'ExistingWiki',
		'Welcome',
		'DBConnect',
		'Upgrade',
		'DBSettings',
		'Name',
		'Options',
		'Install',
		'Complete',
	);

	/**
	 * Out of sequence pages, selectable by the user at any time.
	 * @var array
	 */
	protected $otherPages = array(
		'Restart',
		'Readme',
		'ReleaseNotes',
		'Copying',
		'UpgradeDoc', // Can't use Upgrade due to Upgrade step
	);

	/**
	 * Array of pages which have declared that they have been submitted, have validated
	 * their input, and need no further processing.
	 * @var array
	 */
	protected $happyPages;

	/**
	 * List of "skipped" pages. These are pages that will automatically continue
	 * to the next page on any GET request. To avoid breaking the "back" button,
	 * they need to be skipped during a back operation.
	 * @var array
	 */
	protected $skippedPages;

	/**
	 * Flag indicating that session data may have been lost.
	 * @var bool
	 */
	public $showSessionWarning = false;

	/**
	 * Numeric index of the page we're on
	 * @var int
	 */
	protected $tabIndex = 1;

	/**
	 * Name of the page we're on
	 * @var string
	 */
	protected $currentPageName;

	/**
	 * Constructor.
	 *
	 * @param $request WebRequest
	 */
	public function __construct( WebRequest $request ) {
		parent::__construct();
		$this->output = new WebInstallerOutput( $this );
		$this->request = $request;

		// Add parser hooks
		global $wgParser;
		$wgParser->setHook( 'downloadlink', array( $this, 'downloadLinkHook' ) );
		$wgParser->setHook( 'doclink', array( $this, 'docLink' ) );
	}

	/**
	 * Main entry point.
	 *
	 * @param $session Array: initial session array
	 *
	 * @return Array: new session array
	 */
	public function execute( array $session ) {
		$this->session = $session;

		if ( isset( $session['settings'] ) ) {
			$this->settings = $session['settings'] + $this->settings;
		}

		$this->exportVars();
		$this->setupLanguage();

		if( ( $this->getVar( '_InstallDone' ) || $this->getVar( '_UpgradeDone' ) )
			&& $this->request->getVal( 'localsettings' ) )
		{
			$this->request->response()->header( 'Content-type: application/x-httpd-php' );
			$this->request->response()->header(
				'Content-Disposition: attachment; filename="LocalSettings.php"'
			);

			$ls = new LocalSettingsGenerator( $this );
			$rightsProfile = $this->rightsProfiles[$this->getVar( '_RightsProfile' )];
			foreach( $rightsProfile as $group => $rightsArr ) {
				$ls->setGroupRights( $group, $rightsArr );
			}
			echo $ls->getText();
			return $this->session;
		}

		$cssDir = $this->request->getVal( 'css' );
		if( $cssDir ) {
			$cssDir = ( $cssDir == 'rtl' ? 'rtl' : 'ltr' );
			$this->request->response()->header( 'Content-type: text/css' );
			echo $this->output->getCSS( $cssDir );
			return $this->session;
		}

		if ( isset( $session['happyPages'] ) ) {
			$this->happyPages = $session['happyPages'];
		} else {
			$this->happyPages = array();
		}

		if ( isset( $session['skippedPages'] ) ) {
			$this->skippedPages = $session['skippedPages'];
		} else {
			$this->skippedPages = array();
		}

		$lowestUnhappy = $this->getLowestUnhappy();

		# Special case for Creative Commons partner chooser box.
		if ( $this->request->getVal( 'SubmitCC' ) ) {
			$page = $this->getPageByName( 'Options' );
			$this->output->useShortHeader();
			$this->output->allowFrames();
			$page->submitCC();
			return $this->finish();
		}

		if ( $this->request->getVal( 'ShowCC' ) ) {
			$page = $this->getPageByName( 'Options' );
			$this->output->useShortHeader();
			$this->output->allowFrames();
			$this->output->addHTML( $page->getCCDoneBox() );
			return $this->finish();
		}

		# Get the page name.
		$pageName = $this->request->getVal( 'page' );

		if ( in_array( $pageName, $this->otherPages ) ) {
			# Out of sequence
			$pageId = false;
			$page = $this->getPageByName( $pageName );
		} else {
			# Main sequence
			if ( !$pageName || !in_array( $pageName, $this->pageSequence ) ) {
				$pageId = $lowestUnhappy;
			} else {
				$pageId = array_search( $pageName, $this->pageSequence );
			}

			# If necessary, move back to the lowest-numbered unhappy page
			if ( $pageId > $lowestUnhappy ) {
				$pageId = $lowestUnhappy;
				if ( $lowestUnhappy == 0 ) {
					# Knocked back to start, possible loss of session data.
					$this->showSessionWarning = true;
				}
			}

			$pageName = $this->pageSequence[$pageId];
			$page = $this->getPageByName( $pageName );
		}

		# If a back button was submitted, go back without submitting the form data.
		if ( $this->request->wasPosted() && $this->request->getBool( 'submit-back' ) ) {
			if ( $this->request->getVal( 'lastPage' ) ) {
				$nextPage = $this->request->getVal( 'lastPage' );
			} elseif ( $pageId !== false ) {
				# Main sequence page
				# Skip the skipped pages
				$nextPageId = $pageId;

				do {
					$nextPageId--;
					$nextPage = $this->pageSequence[$nextPageId];
				} while( isset( $this->skippedPages[$nextPage] ) );
			} else {
				$nextPage = $this->pageSequence[$lowestUnhappy];
			}

			$this->output->redirect( $this->getUrl( array( 'page' => $nextPage ) ) );
			return $this->finish();
		}

		# Execute the page.
		$this->currentPageName = $page->getName();
		$this->startPageWrapper( $pageName );

		$result = $page->execute();

		$this->endPageWrapper();

		if ( $result == 'skip' ) {
			# Page skipped without explicit submission.
			# Skip it when we click "back" so that we don't just go forward again.
			$this->skippedPages[$pageName] = true;
			$result = 'continue';
		} else {
			unset( $this->skippedPages[$pageName] );
		}

		# If it was posted, the page can request a continue to the next page.
		if ( $result === 'continue' && !$this->output->headerDone() ) {
			if ( $pageId !== false ) {
				$this->happyPages[$pageId] = true;
			}

			$lowestUnhappy = $this->getLowestUnhappy();

			if ( $this->request->getVal( 'lastPage' ) ) {
				$nextPage = $this->request->getVal( 'lastPage' );
			} elseif ( $pageId !== false ) {
				$nextPage = $this->pageSequence[$pageId + 1];
			} else {
				$nextPage = $this->pageSequence[$lowestUnhappy];
			}

			if ( array_search( $nextPage, $this->pageSequence ) > $lowestUnhappy ) {
				$nextPage = $this->pageSequence[$lowestUnhappy];
			}

			$this->output->redirect( $this->getUrl( array( 'page' => $nextPage ) ) );
		}

		return $this->finish();
	}

	/**
	 * Find the next page in sequence that hasn't been completed
	 * @return int
	 */
	public function getLowestUnhappy() {
		if ( count( $this->happyPages ) == 0 ) {
			return 0;
		} else {
			return max( array_keys( $this->happyPages ) ) + 1;
		}
	}

	/**
	 * Start the PHP session. This may be called before execute() to start the PHP session.
	 */
	public function startSession() {
		if( wfIniGetBool( 'session.auto_start' ) || session_id() ) {
			// Done already
			return true;
		}

		$this->phpErrors = array();
		set_error_handler( array( $this, 'errorHandler' ) );
		session_start();
		restore_error_handler();

		if ( $this->phpErrors ) {
			$this->showError( 'config-session-error', $this->phpErrors[0] );
			return false;
		}

		return true;
	}

	/**
	 * Get a hash of data identifying this MW installation.
	 *
	 * This is used by mw-config/index.php to prevent multiple installations of MW
	 * on the same cookie domain from interfering with each other.
	 */
	public function getFingerprint() {
		// Get the base URL of the installation
		$url = $this->request->getFullRequestURL();
		if ( preg_match( '!^(.*\?)!', $url, $m) ) {
			// Trim query string
			$url = $m[1];
		}
		if ( preg_match( '!^(.*)/[^/]*/[^/]*$!', $url, $m ) ) {
			// This... seems to try to get the base path from
			// the /mw-config/index.php. Kinda scary though?
			$url = $m[1];
		}
		return md5( serialize( array(
			'local path' => dirname( dirname( __FILE__ ) ),
			'url' => $url,
			'version' => $GLOBALS['wgVersion']
		) ) );
	}

	/**
	 * Show an error message in a box. Parameters are like wfMsg().
	 */
	public function showError( $msg /*...*/ ) {
		$args = func_get_args();
		array_shift( $args );
		$args = array_map( 'htmlspecialchars', $args );
		$msg = wfMsgReal( $msg, $args, false, false, false );
		$this->output->addHTML( $this->getErrorBox( $msg ) );
	}

	/**
	 * Temporary error handler for session start debugging.
	 */
	public function errorHandler( $errno, $errstr ) {
		$this->phpErrors[] = $errstr;
	}

	/**
	 * Clean up from execute()
	 *
	 * @return array
	 */
	public function finish() {
		$this->output->output();

		$this->session['happyPages'] = $this->happyPages;
		$this->session['skippedPages'] = $this->skippedPages;
		$this->session['settings'] = $this->settings;

		return $this->session;
	}

	/**
	 * We're restarting the installation, reset the session, happyPages, etc
	 */
	public function reset() {
		$this->session = array();
		$this->happyPages = array();
		$this->settings = array();
	}

	/**
	 * Get a URL for submission back to the same script.
	 *
	 * @param $query: Array
	 * @return string
	 */
	public function getUrl( $query = array() ) {
		$url = $this->request->getRequestURL();
		# Remove existing query
		$url = preg_replace( '/\?.*$/', '', $url );

		if ( $query ) {
			$url .= '?' . wfArrayToCGI( $query );
		}

		return $url;
	}

	/**
	 * Get a WebInstallerPage by name.
	 *
	 * @param $pageName String
	 * @return WebInstallerPage
	 */
	public function getPageByName( $pageName ) {
		// Totally lame way to force autoload of WebInstallerPage.php
		class_exists( 'WebInstallerPage' );

		$pageClass = 'WebInstaller_' . $pageName;

		return new $pageClass( $this );
	}

	/**
	 * Get a session variable.
	 *
	 * @param $name String
	 * @param $default
	 */
	public function getSession( $name, $default = null ) {
		if ( !isset( $this->session[$name] ) ) {
			return $default;
		} else {
			return $this->session[$name];
		}
	}

	/**
	 * Set a session variable.
	 * @param $name String key for the variable
	 * @param $value Mixed
	 */
	public function setSession( $name, $value ) {
		$this->session[$name] = $value;
	}

	/**
	 * Get the next tabindex attribute value.
	 * @return int
	 */
	public function nextTabIndex() {
		return $this->tabIndex++;
	}

	/**
	 * Initializes language-related variables.
	 */
	public function setupLanguage() {
		global $wgLang, $wgContLang, $wgLanguageCode;

		if ( $this->getSession( 'test' ) === null && !$this->request->wasPosted() ) {
			$wgLanguageCode = $this->getAcceptLanguage();
			$wgLang = $wgContLang = Language::factory( $wgLanguageCode );
			$this->setVar( 'wgLanguageCode', $wgLanguageCode );
			$this->setVar( '_UserLang', $wgLanguageCode );
		} else {
			$wgLanguageCode = $this->getVar( 'wgLanguageCode' );
			$wgLang = Language::factory( $this->getVar( '_UserLang' ) );
			$wgContLang = Language::factory( $wgLanguageCode );
		}
	}

	/**
	 * Retrieves MediaWiki language from Accept-Language HTTP header.
	 *
	 * @return string
	 */
	public function getAcceptLanguage() {
		global $wgLanguageCode, $wgRequest;

		$mwLanguages = Language::getLanguageNames();
		$headerLanguages = array_keys( $wgRequest->getAcceptLang() );

		foreach ( $headerLanguages as $lang ) {
			if ( isset( $mwLanguages[$lang] ) ) {
				return $lang;
			}
		}

		return $wgLanguageCode;
	}

	/**
	 * Called by execute() before page output starts, to show a page list.
	 *
	 * @param $currentPageName String
	 */
	private function startPageWrapper( $currentPageName ) {
		$s = "<div class=\"config-page-wrapper\">\n";
		$s .= "<div class=\"config-page\">\n";
		$s .= "<div class=\"config-page-list\"><ul>\n";
		$lastHappy = -1;

		foreach ( $this->pageSequence as $id => $pageName ) {
			$happy = !empty( $this->happyPages[$id] );
			$s .= $this->getPageListItem(
				$pageName,
				$happy || $lastHappy == $id - 1,
				$currentPageName
			);

			if ( $happy ) {
				$lastHappy = $id;
			}
		}

		$s .= "</ul><br/><ul>\n";
		$s .= $this->getPageListItem( 'Restart', true, $currentPageName );
		$s .= "</ul></div>\n"; // end list pane
		$s .= Html::element( 'h2', array(),
				wfMsg( 'config-page-' . strtolower( $currentPageName ) ) );

		$this->output->addHTMLNoFlush( $s );
	}

	/**
	 * Get a list item for the page list.
	 *
	 * @param $pageName String
	 * @param $enabled Boolean
	 * @param $currentPageName String
	 *
	 * @return string
	 */
	private function getPageListItem( $pageName, $enabled, $currentPageName ) {
		$s = "<li class=\"config-page-list-item\">";
		$name = wfMsg( 'config-page-' . strtolower( $pageName ) );

		if ( $enabled ) {
			$query = array( 'page' => $pageName );

			if ( !in_array( $pageName, $this->pageSequence ) ) {
				if ( in_array( $currentPageName, $this->pageSequence ) ) {
					$query['lastPage'] = $currentPageName;
				}

				$link = Html::element( 'a',
					array(
						'href' => $this->getUrl( $query )
					),
					$name
				);
			} else {
				$link = htmlspecialchars( $name );
			}

			if ( $pageName == $currentPageName ) {
				$s .= "<span class=\"config-page-current\">$link</span>";
			} else {
				$s .= $link;
			}
		} else {
			$s .= Html::element( 'span',
				array(
					'class' => 'config-page-disabled'
				),
				$name
			);
		}

		$s .= "</li>\n";

		return $s;
	}

	/**
	 * Output some stuff after a page is finished.
	 */
	private function endPageWrapper() {
		$this->output->addHTMLNoFlush(
					"<div class=\"visualClear\"></div>\n" .
				"</div>\n" .
				"<div class=\"visualClear\"></div>\n" .
			"</div>" );
	}

	/**
	 * Get HTML for an error box with an icon.
	 *
	 * @param $text String: wikitext, get this with wfMsgNoTrans()
	 */
	public function getErrorBox( $text ) {
		return $this->getInfoBox( $text, 'critical-32.png', 'config-error-box' );
	}

	/**
	 * Get HTML for a warning box with an icon.
	 *
	 * @param $text String: wikitext, get this with wfMsgNoTrans()
	 */
	public function getWarningBox( $text ) {
		return $this->getInfoBox( $text, 'warning-32.png', 'config-warning-box' );
	}

	/**
	 * Get HTML for an info box with an icon.
	 *
	 * @param $text String: wikitext, get this with wfMsgNoTrans()
	 * @param $icon String: icon name, file in skins/common/images
	 * @param $class String: additional class name to add to the wrapper div
	 */
	public function getInfoBox( $text, $icon = 'info-32.png', $class = false ) {
		$s =
			"<div class=\"config-info $class\">\n" .
				"<div class=\"config-info-left\">\n" .
				Html::element( 'img',
					array(
						'src' => '../skins/common/images/' . $icon,
						'alt' => wfMsg( 'config-information' ),
					)
				) . "\n" .
				"</div>\n" .
				"<div class=\"config-info-right\">\n" .
					$this->parse( $text, true ) . "\n" .
				"</div>\n" .
				"<div style=\"clear: left;\"></div>\n" .
			"</div>\n";
		return $s;
	}

	/**
	 * Get small text indented help for a preceding form field.
	 * Parameters like wfMsg().
	 */
	public function getHelpBox( $msg /*, ... */ ) {
		$args = func_get_args();
		array_shift( $args );
		$args = array_map( 'htmlspecialchars', $args );
		$text = wfMsgReal( $msg, $args, false, false, false );
		$html = htmlspecialchars( $text );
		$html = $this->parse( $text, true );

		return "<div class=\"mw-help-field-container\">\n" .
			   "<span class=\"mw-help-field-hint\">" . wfMsgHtml( 'config-help' ) . "</span>\n" .
			   "<span class=\"mw-help-field-data\">" . $html . "</span>\n" .
			   "</div>\n";
	}

	/**
	 * Output a help box.
	 * @param $msg String key for wfMsg()
	 */
	public function showHelpBox( $msg /*, ... */ ) {
		$args = func_get_args();
		$html = call_user_func_array( array( $this, 'getHelpBox' ), $args );
		$this->output->addHTML( $html );
	}

	/**
	 * Show a short informational message.
	 * Output looks like a list.
	 *
	 * @param $msg string
	 */
	public function showMessage( $msg /*, ... */ ) {
		$args = func_get_args();
		array_shift( $args );
		$html = '<div class="config-message">' .
			$this->parse( wfMsgReal( $msg, $args, false, false, false ) ) .
			"</div>\n";
		$this->output->addHTML( $html );
	}

	/**
	 * @param $status Status
	 */
	public function showStatusMessage( Status $status ) {
		$text = $status->getWikiText();
		$this->output->addWikiText(
			"<div class=\"config-message\">\n" .
			$text .
			"</div>"
		);
	}

	/**
	 * Label a control by wrapping a config-input div around it and putting a
	 * label before it.
	 */
	public function label( $msg, $forId, $contents, $helpData = "" ) {
		if ( strval( $msg ) == '' ) {
			$labelText = '&#160;';
		} else {
			$labelText = wfMsgHtml( $msg );
		}

		$attributes = array( 'class' => 'config-label' );

		if ( $forId ) {
			$attributes['for'] = $forId;
		}

		return
			"<div class=\"config-block\">\n" .
			"  <div class=\"config-block-label\">\n" .
			Xml::tags( 'label',
				$attributes,
				$labelText ) . "\n" .
				$helpData .
			"  </div>\n" .
			"  <div class=\"config-block-elements\">\n" .
				$contents .
			"  </div>\n" .
			"</div>\n";
	}

	/**
	 * Get a labelled text box to configure a variable.
	 *
	 * @param $params Array
	 *    Parameters are:
	 *      var:        The variable to be configured (required)
	 *      label:      The message name for the label (required)
	 *      attribs:    Additional attributes for the input element (optional)
	 *      controlName: The name for the input element (optional)
	 *      value:      The current value of the variable (optional)
	 *      help:		The html for the help text (optional)
	 */
	public function getTextBox( $params ) {
		if ( !isset( $params['controlName'] ) ) {
			$params['controlName'] = 'config_' . $params['var'];
		}

		if ( !isset( $params['value'] ) ) {
			$params['value'] = $this->getVar( $params['var'] );
		}

		if ( !isset( $params['attribs'] ) ) {
			$params['attribs'] = array();
		}
		if ( !isset( $params['help'] ) ) {
			$params['help'] = "";
		}
		return
			$this->label(
				$params['label'],
				$params['controlName'],
				Xml::input(
					$params['controlName'],
					30, // intended to be overridden by CSS
					$params['value'],
					$params['attribs'] + array(
						'id' => $params['controlName'],
						'class' => 'config-input-text',
						'tabindex' => $this->nextTabIndex()
					)
				),
				$params['help']
			);
	}

	/**
	 * Get a labelled textarea to configure a variable
	 *
	 * @param $params Array
	 *    Parameters are:
	 *      var:        The variable to be configured (required)
	 *      label:      The message name for the label (required)
	 *      attribs:    Additional attributes for the input element (optional)
	 *      controlName: The name for the input element (optional)
	 *      value:      The current value of the variable (optional)
	 *      help:		The html for the help text (optional)
	 */
	public function getTextArea( $params ) {
		if ( !isset( $params['controlName'] ) ) {
			$params['controlName'] = 'config_' . $params['var'];
		}

		if ( !isset( $params['value'] ) ) {
			$params['value'] = $this->getVar( $params['var'] );
		}

		if ( !isset( $params['attribs'] ) ) {
			$params['attribs'] = array();
		}
		if ( !isset( $params['help'] ) ) {
			$params['help'] = "";
		}
		return
			$this->label(
				$params['label'],
				$params['controlName'],
				Xml::textarea(
					$params['controlName'],
					$params['value'],
					30,
					5,
					$params['attribs'] + array(
						'id' => $params['controlName'],
						'class' => 'config-input-text',
						'tabindex' => $this->nextTabIndex()
					)
				),
				$params['help']
			);
	}

	/**
	 * Get a labelled password box to configure a variable.
	 *
	 * Implements password hiding
	 * @param $params Array
	 *    Parameters are:
	 *      var:        The variable to be configured (required)
	 *      label:      The message name for the label (required)
	 *      attribs:    Additional attributes for the input element (optional)
	 *      controlName: The name for the input element (optional)
	 *      value:      The current value of the variable (optional)
	 *      help:		The html for the help text (optional)
	 */
	public function getPasswordBox( $params ) {
		if ( !isset( $params['value'] ) ) {
			$params['value'] = $this->getVar( $params['var'] );
		}

		if ( !isset( $params['attribs'] ) ) {
			$params['attribs'] = array();
		}

		$params['value'] = $this->getFakePassword( $params['value'] );
		$params['attribs']['type'] = 'password';

		return $this->getTextBox( $params );
	}

	/**
	 * Get a labelled checkbox to configure a boolean variable.
	 *
	 * @param $params Array
	 *    Parameters are:
	 *      var:        The variable to be configured (required)
	 *      label:      The message name for the label (required)
	 *      attribs:    Additional attributes for the input element (optional)
	 *      controlName: The name for the input element (optional)
	 *      value:      The current value of the variable (optional)
	 *      help:		The html for the help text (optional)
	 */
	public function getCheckBox( $params ) {
		if ( !isset( $params['controlName'] ) ) {
			$params['controlName'] = 'config_' . $params['var'];
		}

		if ( !isset( $params['value'] ) ) {
			$params['value'] = $this->getVar( $params['var'] );
		}

		if ( !isset( $params['attribs'] ) ) {
			$params['attribs'] = array();
		}
		if ( !isset( $params['help'] ) ) {
			$params['help'] = "";
		}
		if( isset( $params['rawtext'] ) ) {
			$labelText = $params['rawtext'];
		} else {
			$labelText = $this->parse( wfMsg( $params['label'] ) );
		}

		return
			"<div class=\"config-input-check\">\n" .
			$params['help'] .
			"<label>\n" .
			Xml::check(
				$params['controlName'],
				$params['value'],
				$params['attribs'] + array(
					'id' => $params['controlName'],
					'tabindex' => $this->nextTabIndex(),
				)
			) .
			$labelText . "\n" .
			"</label>\n" .
			"</div>\n";
	}

	/**
	 * Get a set of labelled radio buttons.
	 *
	 * @param $params Array
	 *    Parameters are:
	 *      var:            The variable to be configured (required)
	 *      label:          The message name for the label (required)
	 *      itemLabelPrefix: The message name prefix for the item labels (required)
	 *      values:         List of allowed values (required)
	 *      itemAttribs     Array of attribute arrays, outer key is the value name (optional)
	 *      commonAttribs   Attribute array applied to all items
	 *      controlName:    The name for the input element (optional)
	 *      value:          The current value of the variable (optional)
	 *      help:		The html for the help text (optional)
	 */
	public function getRadioSet( $params ) {
		if ( !isset( $params['controlName']  ) ) {
			$params['controlName'] = 'config_' . $params['var'];
		}

		if ( !isset( $params['value'] ) ) {
			$params['value'] = $this->getVar( $params['var'] );
		}

		if ( !isset( $params['label'] ) ) {
			$label = '';
		} else {
			$label = $params['label'];
		}
		if ( !isset( $params['help'] ) ) {
			$params['help'] = "";
		}
		$s = "<ul>\n";
		foreach ( $params['values'] as $value ) {
			$itemAttribs = array();

			if ( isset( $params['commonAttribs'] ) ) {
				$itemAttribs = $params['commonAttribs'];
			}

			if ( isset( $params['itemAttribs'][$value] ) ) {
				$itemAttribs = $params['itemAttribs'][$value] + $itemAttribs;
			}

			$checked = $value == $params['value'];
			$id = $params['controlName'] . '_' . $value;
			$itemAttribs['id'] = $id;
			$itemAttribs['tabindex'] = $this->nextTabIndex();

			$s .=
				'<li>' .
				Xml::radio( $params['controlName'], $value, $checked, $itemAttribs ) .
				'&#160;' .
				Xml::tags( 'label', array( 'for' => $id ), $this->parse(
					wfMsgNoTrans( $params['itemLabelPrefix'] . strtolower( $value ) )
				) ) .
				"</li>\n";
		}

		$s .= "</ul>\n";

		return $this->label( $label, $params['controlName'], $s, $params['help'] );
	}

	/**
	 * Output an error or warning box using a Status object.
	 */
	public function showStatusBox( $status ) {
		if( !$status->isGood() ) {
			$text = $status->getWikiText();

			if( $status->isOk() ) {
				$box = $this->getWarningBox( $text );
			} else {
				$box = $this->getErrorBox( $text );
			}

			$this->output->addHTML( $box );
		}
	}

	/**
	 * Convenience function to set variables based on form data.
	 * Assumes that variables containing "password" in the name are (potentially
	 * fake) passwords.
	 *
	 * @param $varNames Array
	 * @param $prefix String: the prefix added to variables to obtain form names
	 */
	public function setVarsFromRequest( $varNames, $prefix = 'config_' ) {
		$newValues = array();

		foreach ( $varNames as $name ) {
			$value = trim( $this->request->getVal( $prefix . $name ) );
			$newValues[$name] = $value;

			if ( $value === null ) {
				// Checkbox?
				$this->setVar( $name, false );
			} else {
				if ( stripos( $name, 'password' ) !== false ) {
					$this->setPassword( $name, $value );
				} else {
					$this->setVar( $name, $value );
				}
			}
		}

		return $newValues;
	}

	/**
	 * Helper for Installer::docLink()
	 */
	protected function getDocUrl( $page ) {
		$url = "{$_SERVER['PHP_SELF']}?page=" . urlencode( $page );

		if ( in_array( $this->currentPageName, $this->pageSequence ) ) {
			$url .= '&lastPage=' . urlencode( $this->currentPageName );
		}

		return $url;
	}

	/**
	 * Extension tag hook for a documentation link.
	 */
	public function docLink( $linkText, $attribs, $parser ) {
		$url = $this->getDocUrl( $attribs['href'] );
		return '<a href="' . htmlspecialchars( $url ) . '">' .
			htmlspecialchars( $linkText ) .
			'</a>';
	}
	
	/**
	 * Helper for "Download LocalSettings" link on WebInstall_Complete
	 * @return String Html for download link
	 */
	public function downloadLinkHook( $text, $attribs, $parser  ) {
		$img = Html::element( 'img', array(
			'src' => '../skins/common/images/download-32.png',
			'width' => '32',
			'height' => '32',
		) );
		$anchor = Html::rawElement( 'a',
			array( 'href' => $this->getURL( array( 'localsettings' => 1 ) ) ),
			$img . ' ' . wfMsgHtml( 'config-download-localsettings' ) );
		return Html::rawElement( 'div', array( 'class' => 'config-download-link' ), $anchor );
	}
}
