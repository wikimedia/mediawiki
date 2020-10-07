<?php
/**
 * Core installer web interface.
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
 * @ingroup Installer
 */

use MediaWiki\MediaWikiServices;

/**
 * Class for the core installer web interface.
 *
 * @ingroup Installer
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
	 * @var array[]
	 */
	protected $session;

	/**
	 * Captured PHP error text. Temporary.
	 *
	 * @var string[]
	 */
	protected $phpErrors;

	/**
	 * The main sequence of page names. These will be displayed in turn.
	 *
	 * To add a new installer page:
	 *    * Add it to this WebInstaller::$pageSequence property
	 *    * Add a "config-page-<name>" message
	 *    * Add a "WebInstaller<name>" class
	 *
	 * @var string[]
	 */
	public $pageSequence = [
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
	];

	/**
	 * Out of sequence pages, selectable by the user at any time.
	 *
	 * @var string[]
	 */
	protected $otherPages = [
		'Restart',
		'ReleaseNotes',
		'Copying',
		'UpgradeDoc', // Can't use Upgrade due to Upgrade step
	];

	/**
	 * Array of pages which have declared that they have been submitted, have validated
	 * their input, and need no further processing.
	 *
	 * @var bool[]
	 */
	protected $happyPages;

	/**
	 * List of "skipped" pages. These are pages that will automatically continue
	 * to the next page on any GET request. To avoid breaking the "back" button,
	 * they need to be skipped during a back operation.
	 *
	 * @var bool[]
	 */
	protected $skippedPages;

	/**
	 * Flag indicating that session data may have been lost.
	 *
	 * @var bool
	 */
	public $showSessionWarning = false;

	/**
	 * Numeric index of the page we're on
	 *
	 * @var int
	 */
	protected $tabIndex = 1;

	/**
	 * Numeric index of the help box
	 *
	 * @var int
	 */
	protected $helpBoxId = 1;

	/**
	 * Name of the page we're on
	 *
	 * @var string
	 */
	protected $currentPageName;

	/**
	 * @param WebRequest $request
	 */
	public function __construct( WebRequest $request ) {
		parent::__construct();
		$this->output = new WebInstallerOutput( $this );
		$this->request = $request;
	}

	/**
	 * Main entry point.
	 *
	 * @param array[] $session Initial session array
	 *
	 * @return array[] New session array
	 */
	public function execute( array $session ) {
		$this->session = $session;

		if ( isset( $session['settings'] ) ) {
			$this->settings = $session['settings'] + $this->settings;
			// T187586 MediaWikiServices works with globals
			foreach ( $this->settings as $key => $val ) {
				$GLOBALS[$key] = $val;
			}
		}

		$this->setupLanguage();

		if ( ( $this->getVar( '_InstallDone' ) || $this->getVar( '_UpgradeDone' ) )
			&& $this->request->getVal( 'localsettings' )
		) {
			$this->outputLS();
			return $this->session;
		}

		$isCSS = $this->request->getVal( 'css' );
		if ( $isCSS ) {
			$this->outputCss();
			return $this->session;
		}

		$this->happyPages = $session['happyPages'] ?? [];

		$this->skippedPages = $session['skippedPages'] ?? [];

		$lowestUnhappy = $this->getLowestUnhappy();

		# Special case for Creative Commons partner chooser box.
		if ( $this->request->getVal( 'SubmitCC' ) ) {
			/** @var WebInstallerOptions $page */
			$page = $this->getPageByName( 'Options' );
			'@phan-var WebInstallerOptions $page';
			$this->output->useShortHeader();
			$this->output->allowFrames();
			$page->submitCC();

			return $this->finish();
		}

		if ( $this->request->getVal( 'ShowCC' ) ) {
			/** @var WebInstallerOptions $page */
			$page = $this->getPageByName( 'Options' );
			'@phan-var WebInstallerOptions $page';
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
				} while ( isset( $this->skippedPages[$nextPage] ) );
			} else {
				$nextPage = $this->pageSequence[$lowestUnhappy];
			}

			$this->output->redirect( $this->getUrl( [ 'page' => $nextPage ] ) );

			return $this->finish();
		}

		# Execute the page.
		$this->currentPageName = $page->getName();
		$this->startPageWrapper( $pageName );

		if ( $page->isSlow() ) {
			$this->disableTimeLimit();
		}

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

			$this->output->redirect( $this->getUrl( [ 'page' => $nextPage ] ) );
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
	 *
	 * @throws Exception
	 * @return bool
	 */
	public function startSession() {
		if ( wfIniGetBool( 'session.auto_start' ) || session_id() ) {
			// Done already
			return true;
		}

		// Use secure cookies if we are on HTTPS
		$options = [];
		if ( $this->request->getProtocol() === 'https' ) {
			$options['cookie_secure'] = '1';
		}

		$this->phpErrors = [];
		set_error_handler( [ $this, 'errorHandler' ] );
		try {
			session_name( 'mw_installer_session' );
			session_start( $options );
		} catch ( Exception $e ) {
			restore_error_handler();
			throw $e;
		}
		restore_error_handler();

		if ( $this->phpErrors ) {
			return false;
		}

		return true;
	}

	/**
	 * Get a hash of data identifying this MW installation.
	 *
	 * This is used by mw-config/index.php to prevent multiple installations of MW
	 * on the same cookie domain from interfering with each other.
	 *
	 * @return string
	 */
	public function getFingerprint() {
		// Get the base URL of the installation
		$url = $this->request->getFullRequestURL();
		if ( preg_match( '!^(.*\?)!', $url, $m ) ) {
			// Trim query string
			$url = $m[1];
		}
		if ( preg_match( '!^(.*)/[^/]*/[^/]*$!', $url, $m ) ) {
			// This... seems to try to get the base path from
			// the /mw-config/index.php. Kinda scary though?
			$url = $m[1];
		}

		return md5( serialize( [
			'local path' => dirname( __DIR__ ),
			'url' => $url,
			'version' => MW_VERSION
		] ) );
	}

	/**
	 * Show an error message in a box. Parameters are like wfMessage(), or
	 * alternatively, pass a Message object in.
	 * @param string|Message $msg
	 * @param mixed ...$params
	 */
	public function showError( $msg, ...$params ) {
		if ( !( $msg instanceof Message ) ) {
			$msg = wfMessage(
				$msg,
				array_map( 'htmlspecialchars', $params )
			);
		}
		$text = $msg->useDatabase( false )->parse();
		$box = Html::errorBox( $text, '', 'config-error-box' );
		$this->output->addHTML( $box );
	}

	/**
	 * Temporary error handler for session start debugging.
	 *
	 * @param int $errno Unused
	 * @param string $errstr
	 */
	public function errorHandler( $errno, $errstr ) {
		$this->phpErrors[] = $errstr;
	}

	/**
	 * Clean up from execute()
	 *
	 * @return array[]
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
		$this->session = [];
		$this->happyPages = [];
		$this->settings = [];
	}

	/**
	 * Get a URL for submission back to the same script.
	 *
	 * @param string[] $query
	 *
	 * @return string
	 */
	public function getUrl( $query = [] ) {
		$url = $this->request->getRequestURL();
		# Remove existing query
		$url = preg_replace( '/\?.*$/', '', $url );

		if ( $query ) {
			$url .= '?' . wfArrayToCgi( $query );
		}

		return $url;
	}

	/**
	 * Get a WebInstallerPage by name.
	 *
	 * @param string $pageName
	 * @return WebInstallerPage
	 */
	public function getPageByName( $pageName ) {
		$pageClass = 'WebInstaller' . $pageName;

		return new $pageClass( $this );
	}

	/**
	 * Get a session variable.
	 *
	 * @param string $name
	 * @param array|null $default
	 *
	 * @return array
	 */
	public function getSession( $name, $default = null ) {
		return $this->session[$name] ?? $default;
	}

	/**
	 * Set a session variable.
	 *
	 * @param string $name Key for the variable
	 * @param mixed $value
	 */
	public function setSession( $name, $value ) {
		$this->session[$name] = $value;
	}

	/**
	 * Get the next tabindex attribute value.
	 *
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
			$wgLang = MediaWikiServices::getInstance()->getLanguageFactory()
				->getLanguage( $wgLanguageCode );
			RequestContext::getMain()->setLanguage( $wgLang );
			$this->setVar( 'wgLanguageCode', $wgLanguageCode );
			$this->setVar( '_UserLang', $wgLanguageCode );
		} else {
			$wgLanguageCode = $this->getVar( 'wgLanguageCode' );
		}
		$wgContLang = MediaWikiServices::getInstance()->getContentLanguage();
	}

	/**
	 * Retrieves MediaWiki language from Accept-Language HTTP header.
	 *
	 * @return string
	 */
	public function getAcceptLanguage() {
		global $wgLanguageCode, $wgRequest;

		$mwLanguages = MediaWikiServices::getInstance()
			->getLanguageNameUtils()
			->getLanguageNames( null, 'mwfile' );
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
	 * @param string $currentPageName
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
		// End list pane
		$s .= "</ul></div>\n";

		// Messages:
		// config-page-language, config-page-welcome, config-page-dbconnect, config-page-upgrade,
		// config-page-dbsettings, config-page-name, config-page-options, config-page-install,
		// config-page-complete, config-page-restart, config-page-releasenotes,
		// config-page-copying, config-page-upgradedoc, config-page-existingwiki
		$s .= Html::element( 'h2', [],
			wfMessage( 'config-page-' . strtolower( $currentPageName ) )->text() );

		$this->output->addHTMLNoFlush( $s );
	}

	/**
	 * Get a list item for the page list.
	 *
	 * @param string $pageName
	 * @param bool $enabled
	 * @param string $currentPageName
	 *
	 * @return string
	 */
	private function getPageListItem( $pageName, $enabled, $currentPageName ) {
		$s = "<li class=\"config-page-list-item\">";

		// Messages:
		// config-page-language, config-page-welcome, config-page-dbconnect, config-page-upgrade,
		// config-page-dbsettings, config-page-name, config-page-options, config-page-install,
		// config-page-complete, config-page-restart, config-page-releasenotes,
		// config-page-copying, config-page-upgradedoc, config-page-existingwiki
		$name = wfMessage( 'config-page-' . strtolower( $pageName ) )->text();

		if ( $enabled ) {
			$query = [ 'page' => $pageName ];

			if ( !in_array( $pageName, $this->pageSequence ) ) {
				if ( in_array( $currentPageName, $this->pageSequence ) ) {
					$query['lastPage'] = $currentPageName;
				}

				$link = Html::element( 'a',
					[
						'href' => $this->getUrl( $query )
					],
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
				[
					'class' => 'config-page-disabled'
				],
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
	 * Get HTML for an information message box with an icon.
	 *
	 * @param string|HtmlArmor $text Wikitext to be parsed (from Message::plain) or raw HTML.
	 * @param string|bool $icon Icon name, file in mw-config/images. Default: false
	 * @param string|bool $class Additional class name to add to the wrapper div. Default: false.
	 * @return string HTML
	 */
	public function getInfoBox( $text, $icon = false, $class = false ) {
		$html = ( $text instanceof HtmlArmor ) ?
			HtmlArmor::getHtml( $text ) :
			$this->parse( $text, true );
		$icon = ( $icon == false ) ?
			'images/info-32.png' :
			'images/' . $icon;
		$alt = wfMessage( 'config-information' )->text();

		return Html::infoBox( $html, $icon, $alt, $class );
	}

	/**
	 * Get small text indented help for a preceding form field.
	 * Parameters like wfMessage().
	 *
	 * @param string $msg
	 * @param mixed ...$args
	 * @return string
	 */
	public function getHelpBox( $msg, ...$args ) {
		$args = array_map( 'htmlspecialchars', $args );
		$text = wfMessage( $msg, $args )->useDatabase( false )->plain();
		$html = $this->parse( $text, true );
		$id = 'helpBox-' . $this->helpBoxId++;

		return "<div class=\"config-help-field-container\">\n" .
			"<input type=\"checkbox\" class=\"config-help-field-checkbox\" id=\"$id\" />" .
			"<label class=\"config-help-field-hint\" for=\"$id\" title=\"" .
			wfMessage( 'config-help-tooltip' )->escaped() . "\">" .
			wfMessage( 'config-help' )->escaped() . "</label>\n" .
			"<div class=\"config-help-field-data\">" . $html . "</div>\n" .
			"</div>\n";
	}

	/**
	 * Output a help box.
	 * @param string $msg Key for wfMessage()
	 * @param mixed ...$params
	 */
	public function showHelpBox( $msg, ...$params ) {
		$html = $this->getHelpBox( $msg, ...$params );
		$this->output->addHTML( $html );
	}

	/**
	 * Show a short informational message.
	 * Output looks like a list.
	 *
	 * @param string $msg
	 * @param mixed ...$params
	 */
	public function showMessage( $msg, ...$params ) {
		$html = '<div class="config-message">' .
			$this->parse( wfMessage( $msg, $params )->useDatabase( false )->plain() ) .
			"</div>\n";
		$this->output->addHTML( $html );
	}

	/**
	 * @param Status $status
	 */
	public function showStatusMessage( Status $status ) {
		$errors = array_merge( $status->getErrorsArray(), $status->getWarningsArray() );
		foreach ( $errors as $error ) {
			$this->showMessage( ...$error );
		}
	}

	/**
	 * Label a control by wrapping a config-input div around it and putting a
	 * label before it.
	 *
	 * @param string $msg
	 * @param string $forId
	 * @param string $contents
	 * @param string $helpData
	 * @return string
	 */
	public function label( $msg, $forId, $contents, $helpData = "" ) {
		if ( strval( $msg ) == '' ) {
			$labelText = "\u{00A0}";
		} else {
			$labelText = wfMessage( $msg )->escaped();
		}

		$attributes = [ 'class' => 'config-label' ];

		if ( $forId ) {
			$attributes['for'] = $forId;
		}

		return "<div class=\"config-block\">\n" .
			"  <div class=\"config-block-label\">\n" .
			Xml::tags( 'label',
				$attributes,
				$labelText
			) . "\n" .
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
	 * @param mixed[] $params
	 *    Parameters are:
	 *      var:         The variable to be configured (required)
	 *      label:       The message name for the label (required)
	 *      attribs:     Additional attributes for the input element (optional)
	 *      controlName: The name for the input element (optional)
	 *      value:       The current value of the variable (optional)
	 *      help:        The html for the help text (optional)
	 *
	 * @return string
	 */
	public function getTextBox( $params ) {
		if ( !isset( $params['controlName'] ) ) {
			$params['controlName'] = 'config_' . $params['var'];
		}

		if ( !isset( $params['value'] ) ) {
			$params['value'] = $this->getVar( $params['var'] );
		}

		if ( !isset( $params['attribs'] ) ) {
			$params['attribs'] = [];
		}
		if ( !isset( $params['help'] ) ) {
			$params['help'] = "";
		}

		return $this->label(
			$params['label'],
			$params['controlName'],
			Xml::input(
				$params['controlName'],
				30, // intended to be overridden by CSS
				$params['value'],
				$params['attribs'] + [
					'id' => $params['controlName'],
					'class' => 'config-input-text',
					'tabindex' => $this->nextTabIndex()
				]
			),
			$params['help']
		);
	}

	/**
	 * Get a labelled textarea to configure a variable
	 *
	 * @param mixed[] $params
	 *    Parameters are:
	 *      var:         The variable to be configured (required)
	 *      label:       The message name for the label (required)
	 *      attribs:     Additional attributes for the input element (optional)
	 *      controlName: The name for the input element (optional)
	 *      value:       The current value of the variable (optional)
	 *      help:        The html for the help text (optional)
	 *
	 * @return string
	 */
	public function getTextArea( $params ) {
		if ( !isset( $params['controlName'] ) ) {
			$params['controlName'] = 'config_' . $params['var'];
		}

		if ( !isset( $params['value'] ) ) {
			$params['value'] = $this->getVar( $params['var'] );
		}

		if ( !isset( $params['attribs'] ) ) {
			$params['attribs'] = [];
		}
		if ( !isset( $params['help'] ) ) {
			$params['help'] = "";
		}

		return $this->label(
			$params['label'],
			$params['controlName'],
			Xml::textarea(
				$params['controlName'],
				$params['value'],
				30,
				5,
				$params['attribs'] + [
					'id' => $params['controlName'],
					'class' => 'config-input-text',
					'tabindex' => $this->nextTabIndex()
				]
			),
			$params['help']
		);
	}

	/**
	 * Get a labelled password box to configure a variable.
	 *
	 * Implements password hiding
	 * @param mixed[] $params
	 *    Parameters are:
	 *      var:         The variable to be configured (required)
	 *      label:       The message name for the label (required)
	 *      attribs:     Additional attributes for the input element (optional)
	 *      controlName: The name for the input element (optional)
	 *      value:       The current value of the variable (optional)
	 *      help:        The html for the help text (optional)
	 *
	 * @return string
	 */
	public function getPasswordBox( $params ) {
		if ( !isset( $params['value'] ) ) {
			$params['value'] = $this->getVar( $params['var'] );
		}

		if ( !isset( $params['attribs'] ) ) {
			$params['attribs'] = [];
		}

		$params['value'] = $this->getFakePassword( $params['value'] );
		$params['attribs']['type'] = 'password';

		return $this->getTextBox( $params );
	}

	/**
	 * Get a labelled checkbox to configure a boolean variable.
	 *
	 * @param mixed[] $params
	 *    Parameters are:
	 *      var:         The variable to be configured (required)
	 *      label:       The message name for the label (required)
	 *      labelAttribs:Additional attributes for the label element (optional)
	 *      attribs:     Additional attributes for the input element (optional)
	 *      controlName: The name for the input element (optional)
	 *      value:       The current value of the variable (optional)
	 *      help:        The html for the help text (optional)
	 *
	 * @return string
	 */
	public function getCheckBox( $params ) {
		if ( !isset( $params['controlName'] ) ) {
			$params['controlName'] = 'config_' . $params['var'];
		}

		if ( !isset( $params['value'] ) ) {
			$params['value'] = $this->getVar( $params['var'] );
		}

		if ( !isset( $params['attribs'] ) ) {
			$params['attribs'] = [];
		}
		if ( !isset( $params['help'] ) ) {
			$params['help'] = "";
		}
		if ( !isset( $params['labelAttribs'] ) ) {
			$params['labelAttribs'] = [];
		}
		$labelText = $params['rawtext'] ?? $this->parse( wfMessage( $params['label'] )->plain() );

		return "<div class=\"config-input-check\">\n" .
			$params['help'] .
			Html::rawElement(
				'label',
				$params['labelAttribs'],
				Xml::check(
					$params['controlName'],
					$params['value'],
					$params['attribs'] + [
						'id' => $params['controlName'],
						'tabindex' => $this->nextTabIndex(),
					]
				) .
				$labelText . "\n"
				) .
			"</div>\n";
	}

	/**
	 * Get a set of labelled radio buttons.
	 *
	 * @param mixed[] $params
	 *    Parameters are:
	 *      var:             The variable to be configured (required)
	 *      label:           The message name for the label (required)
	 *      itemLabelPrefix: The message name prefix for the item labels (required)
	 *      itemLabels:      List of message names to use for the item labels instead
	 *                       of itemLabelPrefix, keyed by values
	 *      values:          List of allowed values (required)
	 *      itemAttribs:     Array of attribute arrays, outer key is the value name (optional)
	 *      commonAttribs:   Attribute array applied to all items
	 *      controlName:     The name for the input element (optional)
	 *      value:           The current value of the variable (optional)
	 *      help:            The html for the help text (optional)
	 *
	 * @return string
	 */
	public function getRadioSet( $params ) {
		$items = $this->getRadioElements( $params );

		$label = $params['label'] ?? '';

		if ( !isset( $params['controlName'] ) ) {
			$params['controlName'] = 'config_' . $params['var'];
		}

		if ( !isset( $params['help'] ) ) {
			$params['help'] = "";
		}

		$s = "<ul>\n";
		foreach ( $items as $value => $item ) {
			$s .= "<li>$item</li>\n";
		}
		$s .= "</ul>\n";

		return $this->label( $label, $params['controlName'], $s, $params['help'] );
	}

	/**
	 * Get a set of labelled radio buttons. You probably want to use getRadioSet(), not this.
	 *
	 * @see getRadioSet
	 *
	 * @param mixed[] $params
	 * @return array
	 */
	public function getRadioElements( $params ) {
		if ( !isset( $params['controlName'] ) ) {
			$params['controlName'] = 'config_' . $params['var'];
		}

		if ( !isset( $params['value'] ) ) {
			$params['value'] = $this->getVar( $params['var'] );
		}

		$items = [];

		foreach ( $params['values'] as $value ) {
			$itemAttribs = [];

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

			$items[$value] =
				Xml::radio( $params['controlName'], $value, $checked, $itemAttribs ) .
				"\u{00A0}" .
				Xml::tags( 'label', [ 'for' => $id ], $this->parse(
					isset( $params['itemLabels'] ) ?
						wfMessage( $params['itemLabels'][$value] )->plain() :
						wfMessage( $params['itemLabelPrefix'] . strtolower( $value ) )->plain()
				) );
		}

		return $items;
	}

	/**
	 * Output an error or warning box using a Status object.
	 *
	 * @param Status $status
	 */
	public function showStatusBox( $status ) {
		if ( !$status->isGood() ) {
			$text = $status->getWikiText();

			if ( $status->isOK() ) {
				$box = Html::warningBox( $text, 'config-warning-box' );
			} else {
				$box = Html::errorBox( $text, '', 'config-error-box' );
			}

			$this->output->addHTML( $box );
		}
	}

	/**
	 * Convenience function to set variables based on form data.
	 * Assumes that variables containing "password" in the name are (potentially
	 * fake) passwords.
	 *
	 * @param string[] $varNames
	 * @param string $prefix The prefix added to variables to obtain form names
	 *
	 * @return string[]
	 */
	public function setVarsFromRequest( $varNames, $prefix = 'config_' ) {
		$newValues = [];

		foreach ( $varNames as $name ) {
			$value = $this->request->getVal( $prefix . $name );
			// T32524, do not trim passwords
			if ( stripos( $name, 'password' ) === false ) {
				$value = trim( $value );
			}
			$newValues[$name] = $value;

			if ( $value === null ) {
				// Checkbox?
				$this->setVar( $name, false );
			} elseif ( stripos( $name, 'password' ) !== false ) {
				$this->setPassword( $name, $value );
			} else {
				$this->setVar( $name, $value );
			}
		}

		return $newValues;
	}

	/**
	 * Helper for WebInstallerOutput
	 *
	 * @internal For use by WebInstallerOutput
	 * @param string $page
	 * @return string
	 */
	public function getDocUrl( $page ) {
		$query = [ 'page' => $page ];

		if ( in_array( $this->currentPageName, $this->pageSequence ) ) {
			$query['lastPage'] = $this->currentPageName;
		}

		return $this->getUrl( $query );
	}

	/**
	 * Helper for sidebar links.
	 *
	 * @internal For use in WebInstallerOutput class
	 * @param string $url
	 * @param string $linkText
	 * @return string HTML
	 */
	public function makeLinkItem( $url, $linkText ) {
		return Html::rawElement( 'li', [],
			Html::element( 'a', [ 'href' => $url ], $linkText )
		);
	}

	/**
	 * Helper for "Download LocalSettings" link.
	 *
	 * @internal For use in WebInstallerComplete class
	 * @return string Html for download link
	 */
	public function makeDownloadLinkHtml() {
		$anchor = Html::rawElement( 'a',
			[ 'href' => $this->getUrl( [ 'localsettings' => 1 ] ) ],
			wfMessage( 'config-download-localsettings' )->parse()
		);

		return Html::rawElement( 'div', [ 'class' => 'config-download-link' ], $anchor );
	}

	/**
	 * If the software package wants the LocalSettings.php file
	 * to be placed in a specific location, override this function
	 * (see mw-config/overrides/README) to return the path of
	 * where the file should be saved, or false for a generic
	 * "in the base of your install"
	 *
	 * @since 1.27
	 * @return string|bool
	 */
	public function getLocalSettingsLocation() {
		return false;
	}

	/**
	 * @return bool
	 */
	public function envCheckPath() {
		// PHP_SELF isn't available sometimes, such as when PHP is CGI but
		// cgi.fix_pathinfo is disabled. In that case, fall back to SCRIPT_NAME
		// to get the path to the current script... hopefully it's reliable. SIGH
		$path = false;
		if ( !empty( $_SERVER['PHP_SELF'] ) ) {
			$path = $_SERVER['PHP_SELF'];
		} elseif ( !empty( $_SERVER['SCRIPT_NAME'] ) ) {
			$path = $_SERVER['SCRIPT_NAME'];
		}
		if ( $path === false ) {
			$this->showError( 'config-no-uri' );
			return false;
		}

		return parent::envCheckPath();
	}

	public function envPrepPath() {
		parent::envPrepPath();
		// PHP_SELF isn't available sometimes, such as when PHP is CGI but
		// cgi.fix_pathinfo is disabled. In that case, fall back to SCRIPT_NAME
		// to get the path to the current script... hopefully it's reliable. SIGH
		$path = false;
		if ( !empty( $_SERVER['PHP_SELF'] ) ) {
			$path = $_SERVER['PHP_SELF'];
		} elseif ( !empty( $_SERVER['SCRIPT_NAME'] ) ) {
			$path = $_SERVER['SCRIPT_NAME'];
		}
		if ( $path !== false ) {
			$scriptPath = preg_replace( '{^(.*)/(mw-)?config.*$}', '$1', $path );

			$this->setVar( 'wgScriptPath', "$scriptPath" );
			// Update variables set from Setup.php that are derived from wgScriptPath
			$this->setVar( 'wgScript', "$scriptPath/index.php" );
			$this->setVar( 'wgLoadScript', "$scriptPath/load.php" );
			$this->setVar( 'wgStylePath', "$scriptPath/skins" );
			$this->setVar( 'wgLocalStylePath', "$scriptPath/skins" );
			$this->setVar( 'wgExtensionAssetsPath', "$scriptPath/extensions" );
			$this->setVar( 'wgUploadPath', "$scriptPath/images" );
			$this->setVar( 'wgResourceBasePath', "$scriptPath" );
		}
	}

	/**
	 * @return string
	 */
	protected function envGetDefaultServer() {
		return WebRequest::detectServer();
	}

	/**
	 * Actually output LocalSettings.php for download
	 *
	 * @suppress SecurityCheck-XSS
	 */
	private function outputLS() {
		$this->request->response()->header( 'Content-type: application/x-httpd-php' );
		$this->request->response()->header(
			'Content-Disposition: attachment; filename="LocalSettings.php"'
		);

		$ls = InstallerOverrides::getLocalSettingsGenerator( $this );
		$rightsProfile = $this->rightsProfiles[$this->getVar( '_RightsProfile' )];
		foreach ( $rightsProfile as $group => $rightsArr ) {
			$ls->setGroupRights( $group, $rightsArr );
		}
		echo $ls->getText();
	}

	/**
	 * Output stylesheet for web installer pages
	 */
	public function outputCss() {
		$this->request->response()->header( 'Content-type: text/css' );
		echo $this->output->getCSS();
	}

	/**
	 * @return string[]
	 */
	public function getPhpErrors() {
		return $this->phpErrors;
	}

}
