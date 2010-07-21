<?php

class WebInstaller extends Installer {
	
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
	public $session;

	/** Captured PHP error text. Temporary.
	 */
	public $phpErrors;

	/**
	 * The main sequence of page names. These will be displayed in turn.
	 * To add one:
	 *    * Add it here
	 *    * Add a config-page-<name> message
	 *    * Add a WebInstaller_<name> class
	 */
	public $pageSequence = array(
		'Language',
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
	 */
	public $otherPages = array(
		'Restart',
		'Readme',
		'ReleaseNotes',
		'Copying',
		'UpgradeDoc', // Can't use Upgrade due to Upgrade step
	);

	/**
	 * Array of pages which have declared that they have been submitted, have validated
	 * their input, and need no further processing.
	 */
	public $happyPages;

	/**
	 * List of "skipped" pages. These are pages that will automatically continue
	 * to the next page on any GET request. To avoid breaking the "back" button,
	 * they need to be skipped during a back operation.
	 */
	public $skippedPages;

	/**
	 * Flag indicating that session data may have been lost.
	 */
	public $showSessionWarning = false;

	public $helpId = 0;
	public $tabIndex = 1;

	public $currentPageName;

	/** 
	 * Constructor.
	 * 
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

		if( $this->getVar( '_InstallDone' ) && $this->request->getVal( 'localsettings' ) )
		{
			$ls = new LocalSettingsGenerator( $this );
			$this->request->response()->header('Content-type: text/plain');
			
			$this->request->response()->header(
				'Content-Disposition: attachment; filename="LocalSettings.php"'
			);
			
			echo $ls->getText();
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
			$page->submitCC();
			return $this->finish();
		}
		
		if ( $this->request->getVal( 'ShowCC' ) ) {
			$page = $this->getPageByName( 'Options' );
			$this->output->useShortHeader();
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
		$localSettings = $this->getLocalSettingsStatus();
		
		if( !$localSettings->isGood() ) {
			$this->showStatusBox( $localSettings );
			$result = 'output';
		} else {
			$result = $page->execute();
		}
		
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
		$sessPath = $this->getSessionSavePath();
		
		if( $sessPath != '' ) {
			if( strval( ini_get( 'open_basedir' ) ) != '' ) {
				// we need to skip the following check when open_basedir is on.
				// The session path probably *wont* be writable by the current
				// user, and telling them to change it is bad. Bug 23021.
			} elseif( !is_dir( $sessPath ) || !is_writeable( $sessPath ) ) {
				$this->showError( 'config-session-path-bad', $sessPath );
				return false;
			}
		} else {
			// If the path is unset it'll default to some system bit, which *probably* is ok...
			// not sure how to actually get what will be used.
		}
		
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
	 * Get the value of session.save_path
	 *
	 * Per http://www.php.net/manual/en/session.configuration.php#ini.session.save-path,
	 * this might have some additional preceding parts which need to be
	 * ditched
	 *
	 * @return String
	 */
	private function getSessionSavePath() {
		$path = ini_get( 'session.save_path' );
		$path = ltrim( substr( $path, strrpos( $path, ';' ) ), ';');

		return $path;
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
	 * Get a URL for submission back to the same script.
	 * 
	 * @param $query: Array
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
	 * Get a WebInstallerPage from the main sequence, by ID.
	 * 
	 * @param $id Integer
	 * 
	 * @return WebInstallerPage
	 */
	public function getPageById( $id ) {
		return $this->getPageByName( $this->pageSequence[$id] );
	}

	/**
	 * Get a WebInstallerPage by name.
	 * 
	 * @param $pageName String
	 * 
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
	 */
	public function setSession( $name, $value ) {
		$this->session[$name] = $value;
	}

	/**
	 * Get the next tabindex attribute value.
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
		global $wgLanguageCode;

		$mwLanguages = Language::getLanguageNames();
		$langs = $_SERVER['HTTP_ACCEPT_LANGUAGE'];
		
		foreach ( explode( ';', $langs ) as $splitted ) {
			foreach ( explode( ',', $splitted ) as $lang ) {
				$lang = trim( strtolower( $lang ) );
				
				if ( $lang == '' || $lang[0] == 'q' ) {
					continue;
				}
				
				if ( isset( $mwLanguages[$lang] ) ) {
					return $lang;
				}
				
				$lang = preg_replace( '/^(.*?)(?=-[^-]*)$/', '\\1', $lang );
				
				if ( $lang != '' && isset( $mwLanguages[$lang] ) ) {
					return $lang;
				}
			}
		}
		
		return $wgLanguageCode;
	}

	/**
	 * Called by execute() before page output starts, to show a page list.
	 * 
	 * @param $currentPageName String
	 */
	public function startPageWrapper( $currentPageName ) {
		$s = "<div class=\"config-page-wrapper\">\n" .
			"<div class=\"config-page-list\"><ul>\n";
		$lastHappy = -1;
		
		foreach ( $this->pageSequence as $id => $pageName ) {
			$happy = !empty( $this->happyPages[$id] );
			$s .= $this->getPageListItem( $pageName,
				$happy || $lastHappy == $id - 1, $currentPageName );
				
			if ( $happy ) {
				$lastHappy = $id;
			}
		}
		
		$s .= "</ul><br/><ul>\n";
		
		foreach ( $this->otherPages as $pageName ) {
			$s .= $this->getPageListItem( $pageName, true, $currentPageName );
		}
		
		$s .= "</ul></div>\n". // end list pane
			"<div class=\"config-page\">\n" .
			Xml::element( 'h2', array(),
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
	public function getPageListItem( $pageName, $enabled, $currentPageName ) {
		$s = "<li class=\"config-page-list-item\">";
		$name = wfMsg( 'config-page-' . strtolower( $pageName ) );
		
		if ( $enabled ) {
			$query = array( 'page' => $pageName );
			
			if ( !in_array( $pageName, $this->pageSequence ) ) {
				if ( in_array( $currentPageName, $this->pageSequence ) ) {
					$query['lastPage'] = $currentPageName;
				}
				
				$link = Xml::element( 'a',
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
			$s .= Xml::element( 'span',
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
	public function endPageWrapper() {
		$this->output->addHTMLNoFlush(
			"</div>\n" .
			"<br style=\"clear:both\"/>\n" .
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
				Xml::element( 'img',
					array(
						'src' => '../skins/common/images/' . $icon,
						'alt' => wfMsg( 'config-information' ),
					)
				) . "\n" .
				"</div>\n" .
				"<div class=\"config-info-right\">\n" .
					$this->parse( $text ) . "\n" .
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
		$html = $this->parse( $text, true );
		$id = $this->helpId++;
		$alt = wfMsg( 'help' );

		return
			"<div class=\"config-help-wrapper\">\n" .
			"<div class=\"config-help-message\">\n" .
			 $html .
			"</div>\n" .
			"<div class=\"config-show-help\">\n" .
			"<a href=\"#\">" .
			wfMsgHtml( 'config-show-help' ) .
			"</a></div>\n" .
			"<div class=\"config-hide-help\">\n" .
			"<a href=\"#\">" .
			wfMsgHtml( 'config-hide-help' ) .
			"</a></div>\n</div>\n";
	}

	/**
	 * Output a help box.
	 */
	public function showHelpBox( $msg /*, ... */ ) {
		$args = func_get_args();
		$html = call_user_func_array( array( $this, 'getHelpBox' ), $args );
		$this->output->addHTML( $html );
	}

	/**
	 * Show a short informational message.
	 * Output looks like a list.
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
	 * Label a control by wrapping a config-input div around it and putting a
	 * label before it.
	 */
	public function label( $msg, $forId, $contents ) {
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
			"<div class=\"config-input\">\n" .
			Xml::tags( 'label',
				$attributes,
				$labelText ) . "\n" .
			$contents .
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
				)
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
		
		if( isset( $params['rawtext'] ) ) {
			$labelText = $params['rawtext'];
		} else {
			$labelText = $this->parse( wfMsg( $params['label'] ) );
		}
		
		return
			"<div class=\"config-input-check\">\n" .
			"<label>\n" .
			Xml::check(
				$params['controlName'],
				$params['value'],
				$params['attribs'] + array(
					'id' => $params['controlName'],
					'class' => 'config-input-text',
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
			$label = $this->parse( wfMsgNoTrans( $params['label'] ) );
		}
		
		$s = "<label class=\"config-label\">\n" .
			$label .
			"</label>\n" .
			"<ul class=\"config-settings-block\">\n";
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
		return $s;
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

	public function showStatusMessage( $status ) {
		$text = $status->getWikiText();
		$this->output->addWikiText(
			"<div class=\"config-message\">\n" .
			$text .
			"</div>"
		);
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
	 * Get the starting tags of a fieldset.
	 *
	 * @param $legend String: message name
	 */
	public function getFieldsetStart( $legend ) {
		return "\n<fieldset><legend>" . wfMsgHtml( $legend ) . "</legend>\n";
	}

	/**
	 * Get the end tag of a fieldset.
	 */
	public function getFieldsetEnd() {
		return "</fieldset>\n";
	}

	/**
	 * Helper for Installer::docLink()
	 */
	public function getDocUrl( $page ) {
		$url = "{$_SERVER['PHP_SELF']}?page=" . urlencode( $page );
		if ( in_array( $this->currentPageName, $this->pageSequence ) ) {
			$url .= '&lastPage=' . urlencode( $this->currentPageName );
		}
		return $url;
	}
	
}