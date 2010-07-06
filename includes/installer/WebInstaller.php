<?php

class WebInstaller extends Installer {
	/** WebRequest object */
	var $request;

	/** Cached session array */
	var $session;

	/** Captured PHP error text. Temporary.
	 */
	var $phpErrors;

	/**
	 * The main sequence of page names. These will be displayed in turn.
	 * To add one:
	 *    * Add it here
	 *    * Add a config-page-<name> message
	 *    * Add a WebInstaller_<name> class
	 */
	var $pageSequence = array(
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
	 * Out of sequence pages, selectable by the user at any time
	 */
	var $otherPages = array(
		'Restart',
		'Readme',
		'ReleaseNotes',
		'Copying',
		'UpgradeDoc', // Can't use Upgrade due to Upgrade step
	);

	/**
	 * Array of pages which have declared that they have been submitted, have validated
	 * their input, and need no further processing
	 */
	var $happyPages;

	/**
	 * List of "skipped" pages. These are pages that will automatically continue
	 * to the next page on any GET request. To avoid breaking the "back" button,
	 * they need to be skipped during a back operation.
	 */
	var $skippedPages;

	/**
	 * Flag indicating that session data may have been lost
	 */
	var $showSessionWarning = false;

	var $helpId = 0;
	var $tabIndex = 1;

	var $currentPageName;

	/** Constructor */
	function __construct( $request ) {
		parent::__construct();
		$this->output = new WebInstallerOutput( $this );
		$this->request = $request;
	}

	/**
	 * Main entry point.
	 * @param $session Array: initial session array
	 * @return Array: new session array
	 */
	function execute( $session ) {
		$this->session = $session;
		if ( isset( $session['settings'] ) ) {
			$this->settings = $session['settings'] + $this->settings;
		}
		$this->exportVars();
		$this->setupLanguage();

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

		# Special case for Creative Commons partner chooser box
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

		# Get the page name
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
					# Knocked back to start, possible loss of session data
					$this->showSessionWarning = true;
				}
			}
			$pageName = $this->pageSequence[$pageId];
			$page = $this->getPageByName( $pageName );
		}

		# If a back button was submitted, go back without submitting the form data
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

		# Execute the page
		$this->currentPageName = $page->getName();
		$this->startPageWrapper( $pageName );
		$result = $page->execute();
		$this->endPageWrapper();

		if ( $result == 'skip' ) {
			# Page skipped without explicit submission
			# Skip it when we click "back" so that we don't just go forward again
			$this->skippedPages[$pageName] = true;
			$result = 'continue';
		} else {
			unset( $this->skippedPages[$pageName] );
		}

		# If it was posted, the page can request a continue to the next page
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

	function getLowestUnhappy() {
		if ( count( $this->happyPages ) == 0 ) {
			return 0;
		} else {
			return max( array_keys( $this->happyPages ) ) + 1;
		}
	}

	/**
	 * Start the PHP session. This may be called before execute() to start the PHP session.
	 */
	function startSession() {
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
	function showError( $msg /*...*/ ) {
		$args = func_get_args();
		array_shift( $args );
		$args = array_map( 'htmlspecialchars', $args );
		$msg = wfMsgReal( $msg, $args, false, false, false );
		$this->output->addHTML( $this->getErrorBox( $msg ) );
	}

	/**
	 * Temporary error handler for session start debugging
	 */
	function errorHandler( $errno, $errstr ) {
		$this->phpErrors[] = $errstr;
	}

	/**
	 * Clean up from execute()
	 */
	private function finish() {
		$this->output->output();
		$this->session['happyPages'] = $this->happyPages;
		$this->session['skippedPages'] = $this->skippedPages;
		$this->session['settings'] = $this->settings;
		return $this->session;
	}

	/**
	 * Get a URL for submission back to the same script
	 */
	function getUrl( $query = array() ) {
		$url = $this->request->getRequestURL();
		# Remove existing query
		$url = preg_replace( '/\?.*$/', '', $url );
		if ( $query ) {
			$url .= '?' . wfArrayToCGI( $query );
		}
		return $url;
	}

	/**
	 * Get a WebInstallerPage from the main sequence, by ID
	 */
	function getPageById( $id ) {
		$pageName = $this->pageSequence[$id];
		$pageClass = 'WebInstaller_' . $pageName;
		return new $pageClass( $this );
	}

	/**
	 * Get a WebInstallerPage by name
	 */
	function getPageByName( $pageName ) {
		$pageClass = 'WebInstaller_' . $pageName;
		return new $pageClass( $this );
	}

	/**
	 * Get a session variable
	 */
	function getSession( $name, $default = null ) {
		if ( !isset( $this->session[$name] ) ) {
			return $default;
		} else {
			return $this->session[$name];
		}
	}

	/**
	 * Set a session variable
	 */
	function setSession( $name, $value ) {
		$this->session[$name] = $value;
	}

	/**
	 * Get the next tabindex attribute value
	 */
	function nextTabIndex() {
		return $this->tabIndex++;
	}

	/**
	 * Initializes language-related variables
	 */
	function setupLanguage() {
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
	 * Retrieves MediaWiki language from Accept-Language HTTP header
	 */
	function getAcceptLanguage() {
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
	 * Called by execute() before page output starts, to show a page list
	 */
	function startPageWrapper( $currentPageName ) {
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
	 * Get a list item for the page list
	 */
	function getPageListItem( $pageName, $enabled, $currentPageName ) {
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
	 * Output some stuff after a page is finished
	 */
	function endPageWrapper() {
		$this->output->addHTMLNoFlush(
			"</div>\n" .
			"<br style=\"clear:both\"/>\n" .
			"</div>" );
	}

	/**
	 * Get HTML for an error box with an icon
	 *
	 * @param $text String: wikitext, get this with wfMsgNoTrans()
	 */
	function getErrorBox( $text ) {
		return $this->getInfoBox( $text, 'critical-32.png', 'config-error-box' );
	}

	/**
	 * Get HTML for a warning box with an icon
	 *
	 * @param $text String: wikitext, get this with wfMsgNoTrans()
	 */
	function getWarningBox( $text ) {
		return $this->getInfoBox( $text, 'warning-32.png', 'config-warning-box' );
	}

	/**
	 * Get HTML for an info box with an icon
	 *
	 * @param $text String: wikitext, get this with wfMsgNoTrans()
	 * @param $icon String: icon name, file in skins/common/images
	 * @param $class String: additional class name to add to the wrapper div
	 */
	function getInfoBox( $text, $icon = 'info-32.png', $class = false ) {
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
	function getHelpBox( $msg /*, ... */ ) {
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
	 * Output a help box
	 */
	function showHelpBox( $msg /*, ... */ ) {
		$args = func_get_args();
		$html = call_user_func_array( array( $this, 'getHelpBox' ), $args );
		$this->output->addHTML( $html );
	}

	/**
	 * Show a short informational message
	 * Output looks like a list.
	 */
	function showMessage( $msg /*, ... */ ) {
		$args = func_get_args();
		array_shift( $args );
		$html = '<div class="config-message">' .
			$this->parse( wfMsgReal( $msg, $args, false, false, false ) ) .
			"</div>\n";
		$this->output->addHTML( $html );
	}

	/**
	 * Label a control by wrapping a config-input div around it and putting a
	 * label before it
	 */
	function label( $msg, $forId, $contents ) {
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
	 * Get a labelled text box to configure a variable
	 *
	 * @param $params Array
	 *    Parameters are:
	 *      var:        The variable to be configured (required)
	 *      label:      The message name for the label (required)
	 *      attribs:    Additional attributes for the input element (optional)
	 *      controlName: The name for the input element (optional)
	 *      value:      The current value of the variable (optional)
	 */
	function getTextBox( $params ) {
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
	 * Get a labelled password box to configure a variable
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
	function getPasswordBox( $params ) {
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
	 * Get a labelled checkbox to configure a boolean variable
	 *
	 * @param $params Array
	 *    Parameters are:
	 *      var:        The variable to be configured (required)
	 *      label:      The message name for the label (required)
	 *      attribs:    Additional attributes for the input element (optional)
	 *      controlName: The name for the input element (optional)
	 *      value:      The current value of the variable (optional)
	 */
	function getCheckBox( $params ) {
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
	 * Get a set of labelled radio buttons
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
	function getRadioSet( $params ) {
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
	 * Output an error or warning box using a Status object
	 */
	function showStatusBox( $status ) {
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

	function showStatusMessage( $status ) {
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
	function setVarsFromRequest( $varNames, $prefix = 'config_' ) {
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
	 * Get the starting tags of a fieldset
	 *
	 * @param $legend String: message name
	 */
	function getFieldsetStart( $legend ) {
		return "\n<fieldset><legend>" . wfMsgHtml( $legend ) . "</legend>\n";
	}

	/**
	 * Get the end tag of a fieldset
	 */
	function getFieldsetEnd() {
		return "</fieldset>\n";
	}

	/**
	 * Helper for Installer::docLink()
	 */
	function getDocUrl( $page ) {
		$url = "{$_SERVER['PHP_SELF']}?page=" . urlencode( $page );
		if ( in_array( $this->currentPageName, $this->pageSequence ) ) {
			$url .= '&lastPage=' . urlencode( $this->currentPageName );
		}
		return $url;
	}
}

abstract class WebInstallerPage {
	function __construct( $parent ) {
		$this->parent = $parent;
	}

	function addHTML( $html ) {
		$this->parent->output->addHTML( $html );
	}

	function startForm() {
		$this->addHTML(
			"<div class=\"config-section\">\n" .
			Xml::openElement(
				'form',
				array(
					'method' => 'post',
					'action' => $this->parent->getUrl( array( 'page' => $this->getName() ) )
				)
			) . "\n"
		);
	}

	function endForm( $continue = 'continue' ) {
		$this->parent->output->outputWarnings();
		$s = "<div class=\"config-submit\">\n";
		$id = $this->getId();
		if ( $id === false ) {
			$s .= Xml::hidden( 'lastPage', $this->parent->request->getVal( 'lastPage' ) );
		}
		if ( $continue ) {
			// Fake submit button for enter keypress
			$s .= Xml::submitButton( wfMsg( "config-$continue" ),
				array( 'name' => "enter-$continue", 'style' => 'display:none' ) ) . "\n";
		}
		if ( $id !== 0 ) {
			$s .= Xml::submitButton( wfMsg( 'config-back' ),
				array(
					'name' => 'submit-back',
					'tabindex' => $this->parent->nextTabIndex()
				) ) . "\n";
		}
		if ( $continue ) {
			$s .= Xml::submitButton( wfMsg( "config-$continue" ),
				array(
					'name' => "submit-$continue",
					'tabindex' => $this->parent->nextTabIndex(),
				) ) . "\n";
		}
		$s .= "</div></form></div>\n";
		$this->addHTML( $s );
	}

	function getName() {
		return str_replace( 'WebInstaller_', '', get_class( $this ) );
	}

	function getId() {
		return array_search( $this->getName(), $this->parent->pageSequence );
	}

	abstract function execute();

	function getVar( $var ) {
		return $this->parent->getVar( $var );
	}

	function setVar( $name, $value ) {
		$this->parent->setVar( $name, $value );
	}
}

class WebInstaller_Language extends WebInstallerPage {
	function execute() {
		global $wgLang;
		$r = $this->parent->request;
		$userLang = $r->getVal( 'UserLang' );
		$contLang = $r->getVal( 'ContLang' );

		$lifetime = intval( ini_get( 'session.gc_maxlifetime' ) );
		if ( !$lifetime ) {
			$lifetime = 1440; // PHP default
		}

		if ( $r->wasPosted() ) {
			# Do session test
			if ( $this->parent->getSession( 'test' ) === null ) {
				$requestTime = $r->getVal( 'LanguageRequestTime' );
				if ( !$requestTime ) {
					// The most likely explanation is that the user was knocked back
					// from another page on POST due to session expiry
					$msg = 'config-session-expired';
				} elseif ( time() - $requestTime > $lifetime ) {
					$msg = 'config-session-expired';
				} else {
					$msg = 'config-no-session';
				}
				$this->parent->showError( $msg, $wgLang->formatTimePeriod( $lifetime ) );
			} else {
				$languages = Language::getLanguageNames();
				if ( isset( $languages[$userLang] ) ) {
					$this->setVar( '_UserLang', $userLang );
				}
				if ( isset( $languages[$contLang] ) ) {
					$this->setVar( 'wgLanguageCode', $contLang );
					if ( $this->getVar( '_AdminName' ) === null ) {
						// Load localised sysop username in *content* language
						$this->setVar( '_AdminName', wfMsgForContent( 'config-admin-default-username' ) );
					}
				}
				return 'continue';
			}
		} elseif ( $this->parent->showSessionWarning ) {
			# The user was knocked back from another page to the start
			# This probably indicates a session expiry
			$this->parent->showError( 'config-session-expired', $wgLang->formatTimePeriod( $lifetime ) );
		}

		$this->parent->setSession( 'test', true );

		if ( !isset( $languages[$userLang] ) ) {
			$userLang = $this->getVar( '_UserLang', 'en' );
		}
		if ( !isset( $languages[$contLang] ) ) {
			$contLang = $this->getVar( 'wgLanguageCode', 'en' );
		}
		$this->startForm();
		$s =
			Xml::hidden( 'LanguageRequestTime', time() ) .
			$this->getLanguageSelector( 'UserLang', 'config-your-language', $userLang ) .
			$this->parent->getHelpBox( 'config-your-language-help' ) .
			$this->getLanguageSelector( 'ContLang', 'config-wiki-language', $contLang ) .
			$this->parent->getHelpBox( 'config-wiki-language-help' );


		$this->addHTML( $s );
		$this->endForm();
	}

	/**
	 * Get a <select> for selecting languages
	 */
	function getLanguageSelector( $name, $label, $selectedCode ) {
		global $wgDummyLanguageCodes;
		$s = Xml::openElement( 'select', array( 'id' => $name, 'name' => $name ) ) . "\n";

		$languages = Language::getLanguageNames();
		ksort( $languages );
		$dummies = array_flip( $wgDummyLanguageCodes );
		foreach ( $languages as $code => $lang ) {
			if ( isset( $dummies[$code] ) ) continue;
			$s .= "\n" . Xml::option( "$code - $lang", $code, $code == $selectedCode );
		}
		$s .= "\n</select>\n";
		return $this->parent->label( $label, $name, $s );
	}
}

class WebInstaller_Welcome extends WebInstallerPage {
	function execute() {
		if ( $this->parent->request->wasPosted() ) {
			if ( $this->getVar( '_Environment' ) ) {
				return 'continue';
			}
		}
		$this->parent->output->addWikiText( wfMsgNoTrans( 'config-welcome' ) );
		$status = $this->parent->doEnvironmentChecks();
		if ( $status ) {
			$this->parent->output->addWikiText( wfMsgNoTrans( 'config-copyright', wfMsg( 'config-authors' ) ) );
			$this->startForm();
			$this->endForm();
		}
	}
}

class WebInstaller_DBConnect extends WebInstallerPage {
	function execute() {
		$r = $this->parent->request;
		if ( $r->wasPosted() ) {
			$status = $this->submit();
			if ( $status->isGood() ) {
				$this->setVar( '_UpgradeDone', false );
				return 'continue';
			} else {
				$this->parent->showStatusBox( $status );
			}
		}


		$this->startForm();

		$types = "<ul class=\"config-settings-block\">\n";
		$settings = '';
		$defaultType = $this->getVar( 'wgDBtype' );
		foreach ( $this->parent->getVar( '_CompiledDBs' ) as $type ) {
			$installer = $this->parent->getDBInstaller( $type );
			$types .=
				'<li>' .
				Xml::radioLabel(
					$installer->getReadableName(),
					'DBType',
					$type,
					"DBType_$type",
					$type == $defaultType,
					array( 'class' => 'dbRadio', 'rel' => "DB_wrapper_$type" )
				) .
				"</li>\n";

			$settings .=
				Xml::openElement( 'div', array( 'id' => 'DB_wrapper_' . $type, 'class' => 'dbWrapper' ) ) .
				Xml::element( 'h3', array(), wfMsg( 'config-header-' . $type ) ) .
				$installer->getConnectForm() .
				"</div>\n";
		}
		$types .= "</ul><br clear=\"left\"/>\n";

		$this->addHTML(
			$this->parent->label( 'config-db-type', false, $types ) .
			$settings
		);

		$this->endForm();
	}

	function submit() {
		$r = $this->parent->request;
		$type = $r->getVal( 'DBType' );
		$this->setVar( 'wgDBtype', $type );
		$installer = $this->parent->getDBInstaller( $type );
		if ( !$installer ) {
			return Status::newFatal( 'config-invalid-db-type' );
		}
		return $installer->submitConnectForm();
	}
}

class WebInstaller_Upgrade extends WebInstallerPage {
	function execute() {
		if ( $this->getVar( '_UpgradeDone' ) ) {
			if ( $this->parent->request->wasPosted() ) {
				// Done message acknowledged
				return 'continue';
			} else {
				// Back button click
				// Show the done message again
				// Make them click back again if they want to do the upgrade again
				$this->showDoneMessage();
				return 'output';
			}
		}

		// wgDBtype is generally valid here because otherwise the previous page
		// (connect) wouldn't have declared its happiness
		$type = $this->getVar( 'wgDBtype' );
		$installer = $this->parent->getDBInstaller( $type );

		if ( !$installer->needsUpgrade() ) {
			return 'skip';
		}

		if ( $this->parent->request->wasPosted() ) {
			$this->addHTML(
				'<div id="config-spinner" style="display:none;"><img src="../skins/common/images/ajax-loader.gif" /></div>' .
				'<script>jQuery( "#config-spinner" )[0].style.display = "block";</script>' .
				'<textarea id="config-update-log" name="UpdateLog" rows="10" readonly="readonly">'
			);
			$this->parent->output->flush();
			$result = $installer->doUpgrade();
			$this->addHTML( '</textarea>
<script>jQuery( "#config-spinner" )[0].style.display = "none";</script>' );
			$this->parent->output->flush();
			if ( $result ) {
				$this->setVar( '_UpgradeDone', true );
				$this->showDoneMessage();
				return 'output';
			}
		}

		$this->startForm();
		$this->addHTML( $this->parent->getInfoBox(
			wfMsgNoTrans( 'config-can-upgrade', $GLOBALS['wgVersion'] ) ) );
		$this->endForm();
	}

	function showDoneMessage() {
		$this->startForm();
		$this->addHTML(
			$this->parent->getInfoBox(
				wfMsgNoTrans( 'config-upgrade-done',
					$GLOBALS['wgServer'] .
						$this->getVar( 'wgScriptPath' ) . '/index' .
						$this->getVar( 'wgScriptExtension' )
				), 'tick-32.png'
			)
		);
		$this->endForm( 'regenerate' );
	}
}

class WebInstaller_DBSettings extends WebInstallerPage {
	function execute() {
		$installer = $this->parent->getDBInstaller( $this->getVar( 'wgDBtype' ) );

		$r = $this->parent->request;
		if ( $r->wasPosted() ) {
			$status = $installer->submitSettingsForm();
			if ( $status === false ) {
				return 'skip';
			} elseif ( $status->isGood() ) {
				return 'continue';
			} else {
				$this->parent->showStatusBox( $status );
			}
		}

		$form = $installer->getSettingsForm();
		if ( $form === false ) {
			return 'skip';
		}

		$this->startForm();
		$this->addHTML( $form );
		$this->endForm();
	}

}

class WebInstaller_Name extends WebInstallerPage {
	function execute() {
		$r = $this->parent->request;
		if ( $r->wasPosted() ) {
			if ( $this->submit() ) {
				return 'continue';
			}
		}

		$this->startForm();

		if ( $this->getVar( 'wgSitename' ) == $GLOBALS['wgSitename'] ) {
			$this->setVar( 'wgSitename', '' );
		}

		// Set wgMetaNamespace to something valid before we show the form.
		// $wgMetaNamespace defaults to $wgSiteName which is 'MediaWiki'
		$metaNS = $this->getVar( 'wgMetaNamespace' );
		$this->setVar( 'wgMetaNamespace', wfMsgForContent( 'config-ns-other-default' ) );

		$this->addHTML(
			$this->parent->getTextBox( array(
				'var' => 'wgSitename',
				'label' => 'config-site-name',
			) ) .
			$this->parent->getHelpBox( 'config-site-name-help' ) .
			$this->parent->getRadioSet( array(
				'var' => '_NamespaceType',
				'label' => 'config-project-namespace',
				'itemLabelPrefix' => 'config-ns-',
				'values' => array( 'site-name', 'generic', 'other' ),
				'commonAttribs' => array( 'class' => 'enableForOther', 'rel' => 'config_wgMetaNamespace' ),
			) ) .
			$this->parent->getTextBox( array(
				'var' => 'wgMetaNamespace',
				'label' => '',
				'attribs' => array( 'disabled' => '' ),
			) ) .
			$this->parent->getHelpBox( 'config-project-namespace-help' ) .
			$this->parent->getFieldsetStart( 'config-admin-box' ) .
			$this->parent->getTextBox( array(
				'var' => '_AdminName',
				'label' => 'config-admin-name'
			) ) .
			$this->parent->getPasswordBox( array(
				'var' => '_AdminPassword',
				'label' => 'config-admin-password',
			) ) .
			$this->parent->getPasswordBox( array(
				'var' => '_AdminPassword2',
				'label' => 'config-admin-password-confirm'
			) ) .
			$this->parent->getHelpBox( 'config-admin-help' ) .
			$this->parent->getTextBox( array(
				'var' => '_AdminEmail',
				'label' => 'config-admin-email'
			) ) .
			$this->parent->getHelpBox( 'config-admin-email-help' ) .
			$this->parent->getCheckBox( array(
				'var' => '_Subscribe',
				'label' => 'config-subscribe'
			) ) .
			$this->parent->getHelpBox( 'config-subscribe-help' ) .
			$this->parent->getFieldsetEnd() .
			$this->parent->getInfoBox( wfMsg( 'config-almost-done' ) ) .
			$this->parent->getRadioSet( array(
				'var' => '_SkipOptional',
				'itemLabelPrefix' => 'config-optional-',
				'values' => array( 'continue', 'skip' )
			) )
		);

		// Restore the default value
		$this->setVar( 'wgMetaNamespace', $metaNS );

		$this->endForm();
		return 'output';
	}

	function submit() {
		$retVal = true;
		$this->parent->setVarsFromRequest( array( 'wgSitename', '_NamespaceType',
			'_AdminName', '_AdminPassword', '_AdminPassword2', '_AdminEmail',
			'_Subscribe', '_SkipOptional' ) );

		// Validate site name
		if ( strval( $this->getVar( 'wgSitename' ) ) === '' ) {
			$this->parent->showError( 'config-site-name-blank' );
			$retVal = false;
		}

		// Fetch namespace
		$nsType = $this->getVar( '_NamespaceType' );
		if ( $nsType == 'site-name' ) {
			$name = $this->getVar( 'wgSitename' );
			// Sanitize for namespace
			// This algorithm should match the JS one in WebInstallerOutput.php
			$name = preg_replace( '/[\[\]\{\}|#<>%+? ]/', '_', $name );
			$name = str_replace( '&', '&amp;', $name );
			$name = preg_replace( '/__+/', '_', $name );
			$name = ucfirst( trim( $name, '_' ) );
		} elseif ( $nsType == 'generic' ) {
			$name = wfMsg( 'config-ns-generic' );
		} else { // other
			$name = $this->getVar( 'wgMetaNamespace' );
		}

		// Validate namespace
		if ( strpos( $name, ':' ) !== false ) {
			$good = false;
		} else {
			// Title-style validation
			$title = Title::newFromText( $name );
			if ( !$title ) {
				$good = $nsType == 'site-name' ? true : false;
			} else {
				$name = $title->getDBkey();
				$good = true;
			}
		}
		if ( !$good ) {
			$this->parent->showError( 'config-ns-invalid', $name );
			$retVal = false;
		}
		$this->setVar( 'wgMetaNamespace', $name );

		// Validate username for creation
		$name = $this->getVar( '_AdminName' );
		if ( strval( $name ) === '' ) {
			$this->parent->showError( 'config-admin-name-blank' );
			$cname = $name;
			$retVal = false;
		} else {
			$cname = User::getCanonicalName( $name, 'creatable' );
			if ( $cname === false ) {
				$this->parent->showError( 'config-admin-name-invalid', $name );
				$retVal = false;
			} else {
				$this->setVar( '_AdminName', $cname );
			}
		}

		// Validate password
		$msg = false;
		$pwd = $this->getVar( '_AdminPassword' );
		$user = User::newFromName( $cname );
		$valid = $user->getPasswordValidity( $pwd );
		if ( strval( $pwd ) === '' ) {
			# $user->getPasswordValidity just checks for $wgMinimalPasswordLength.
			# This message is more specific and helpful.
			$msg = 'config-admin-password-blank';
		} elseif ( $pwd !== $this->getVar( '_AdminPassword2' ) ) {
			$msg = 'config-admin-password-mismatch';
		} elseif ( $valid !== true ) {
			# As of writing this will only catch the username being e.g. 'FOO' and
			# the password 'foo'
			$msg = $valid;
		}
		if ( $msg !== false ) {
			$this->parent->showError( $msg );
			$this->setVar( '_AdminPassword', '' );
			$this->setVar( '_AdminPassword2', '' );
			$retVal = false;
		}
		return $retVal;
	}
}

class WebInstaller_Options extends WebInstallerPage {
	function execute() {
		if ( $this->getVar( '_SkipOptional' ) == 'skip' ) {
			return 'skip';
		}
		if ( $this->parent->request->wasPosted() ) {
			if ( $this->submit() ) {
				return 'continue';
			}
		}

		$this->startForm();
		$this->addHTML(
			# User Rights
			$this->parent->getRadioSet( array(
				'var' => '_RightsProfile',
				'label' => 'config-profile',
				'itemLabelPrefix' => 'config-profile-',
				'values' => array_keys( $this->parent->rightsProfiles ),
			) ) .
			$this->parent->getHelpBox( 'config-profile-help' ) .

			# Licensing
			$this->parent->getRadioSet( array(
				'var' => '_LicenseCode',
				'label' => 'config-license',
				'itemLabelPrefix' => 'config-license-',
				'values' => array_keys( $this->parent->licenses ),
				'commonAttribs' => array( 'class' => 'licenseRadio' ),
			) ) .
			$this->getCCChooser() .
			$this->parent->getHelpBox( 'config-license-help' ) .

			# E-mail
			$this->parent->getFieldsetStart( 'config-email-settings' ) .
			$this->parent->getCheckBox( array(
				'var' => 'wgEnableEmail',
				'label' => 'config-enable-email',
				'attribs' => array( 'class' => 'showHideRadio', 'rel' => 'emailwrapper' ),
			) ) .
			$this->parent->getHelpBox( 'config-enable-email-help' ) .
			"<div id=\"emailwrapper\">" .
			$this->parent->getTextBox( array(
				'var' => 'wgPasswordSender',
				'label' => 'config-email-sender'
			) ) .
			$this->parent->getHelpBox( 'config-email-sender-help' ) .
			$this->parent->getCheckBox( array(
				'var' => 'wgEnableUserEmail',
				'label' => 'config-email-user',
			) ) .
			$this->parent->getHelpBox( 'config-email-user-help' ) .
			$this->parent->getCheckBox( array(
				'var' => 'wgEnotifUserTalk',
				'label' => 'config-email-usertalk',
			) ) .
			$this->parent->getHelpBox( 'config-email-usertalk-help' ) .
			$this->parent->getCheckBox( array(
				'var' => 'wgEnotifWatchlist',
				'label' => 'config-email-watchlist',
			) ) .
			$this->parent->getHelpBox( 'config-email-watchlist-help' ) .
			$this->parent->getCheckBox( array(
				'var' => 'wgEmailAuthentication',
				'label' => 'config-email-auth',
			) ) .
			$this->parent->getHelpBox( 'config-email-auth-help' ) .
			"</div>" .
			$this->parent->getFieldsetEnd()
		);

		$extensions = $this->parent->findExtensions();
		if( $extensions ) {
			$extHtml = $this->parent->getFieldsetStart( 'config-extensions' );
			foreach( $extensions as $ext ) {
				$extHtml .= $this->parent->getCheckBox( array(
					'var' => "ext-$ext",
					'rawtext' => $ext,
				) );
			}
			$extHtml .= $this->parent->getHelpBox( 'config-extensions-help' ) .
				$this->parent->getFieldsetEnd();
			$this->addHTML( $extHtml );
		}

		$this->addHTML(
			# Uploading
			$this->parent->getFieldsetStart( 'config-upload-settings' ) .
			$this->parent->getCheckBox( array( 
				'var' => 'wgEnableUploads',
				'label' => 'config-upload-enable',
				'attribs' => array( 'class' => 'showHideRadio', 'rel' => 'uploadwrapper' ),
			) ) .
			$this->parent->getHelpBox( 'config-upload-help' ) .
			'<div id="uploadwrapper" style="display: none;">' .
			$this->parent->getTextBox( array( 
				'var' => 'wgDeletedDirectory',
				'label' => 'config-upload-deleted',
			) ) .
			$this->parent->getHelpBox( 'config-upload-deleted-help' ) .
			'</div>' .
			$this->parent->getTextBox( array(
				'var' => 'wgLogo',
				'label' => 'config-logo'
			) ) .
			$this->parent->getHelpBox( 'config-logo-help' ) .
			$this->parent->getFieldsetEnd()
		);

		$caches = array( 'none' );
		if( count( $this->getVar( '_Caches' ) ) ) {
			$caches[] = 'accel';
			$selected = 'accel';
		}
		$caches[] = 'memcached';

		$this->addHTML(
			# Advanced settings
			$this->parent->getFieldsetStart( 'config-advanced-settings' ) .
			# Object cache settings
			$this->parent->getRadioSet( array(
				'var' => 'wgMainCacheType',
				'label' => 'config-cache-options',
				'itemLabelPrefix' => 'config-cache-',
				'values' => $caches,
				'value' => $selected,
			) ) .
			$this->parent->getHelpBox( 'config-cache-help' ) .
			'<div id="config-memcachewrapper">' .
			$this->parent->getTextBox( array(
				'var' => '_MemCachedServers',
				'label' => 'config-memcached-servers',
			) ) .
			$this->parent->getHelpBox( 'config-memcached-help' ) . '</div>' .
			$this->parent->getFieldsetEnd()
		);
		$this->endForm();
	}

	function getCCPartnerUrl() {
		global $wgServer;
		$exitUrl = $wgServer . $this->parent->getUrl( array(
			'page' => 'Options',
			'SubmitCC' => 'indeed',
			'config__LicenseCode' => 'cc',
			'config_wgRightsUrl' => '[license_url]',
			'config_wgRightsText' => '[license_name]',
			'config_wgRightsIcon' => '[license_button]',
		) );
		$styleUrl = $wgServer . dirname( dirname( $this->parent->getUrl() ) ) .
			'/skins/common/config-cc.css';
		$iframeUrl = 'http://creativecommons.org/license/?' .
			wfArrayToCGI( array(
				'partner' => 'MediaWiki',
				'exit_url' => $exitUrl,
				'lang' => $this->getVar( '_UserLang' ),
				'stylesheet' => $styleUrl,
			) );
		return $iframeUrl;
	}

	function getCCChooser() {
		$iframeAttribs = array(
			'class' => 'config-cc-iframe',
			'name' => 'config-cc-iframe',
			'id' => 'config-cc-iframe',
			'frameborder' => 0,
			'width' => '100%',
			'height' => '100%',
		);
		if ( $this->getVar( '_CCDone' ) ) {
			$iframeAttribs['src'] = $this->parent->getUrl( array( 'ShowCC' => 'yes' ) );
		} else {
			$iframeAttribs['src'] = $this->getCCPartnerUrl();
		}

		return
			"<div class=\"config-cc-wrapper\" id=\"config-cc-wrapper\" style=\"display: none;\">\n" .
			Xml::element( 'iframe', $iframeAttribs, '', false /* not short */ ) .
			"</div>\n";
	}

	function getCCDoneBox() {
		$js = "parent.document.getElementById('config-cc-wrapper').style.height = '$1';";
		// If you change this height, also change it in config.css
		$expandJs = str_replace( '$1', '54em', $js );
		$reduceJs = str_replace( '$1', '70px', $js );
		return
			'<p>'.
			Xml::element( 'img', array( 'src' => $this->getVar( 'wgRightsIcon' ) ) ) .
			'&#160;&#160;' .
			htmlspecialchars( $this->getVar( 'wgRightsText' ) ) .
			"</p>\n" .
			"<p style=\"text-align: center\">" .
			Xml::element( 'a',
				array(
					'href' => $this->getCCPartnerUrl(),
					'onclick' => $expandJs,
				),
				wfMsg( 'config-cc-again' )
			) .
			"</p>\n" .
			"<script type=\"text/javascript\">\n" .
			# Reduce the wrapper div height
			htmlspecialchars( $reduceJs ) .
			"\n" .
			"</script>\n";
	}


	function submitCC() {
		$newValues = $this->parent->setVarsFromRequest(
			array( 'wgRightsUrl', 'wgRightsText', 'wgRightsIcon' ) );
		if ( count( $newValues ) != 3 ) {
			$this->parent->showError( 'config-cc-error' );
			return;
		}
		$this->setVar( '_CCDone', true );
		$this->addHTML( $this->getCCDoneBox() );
	}

	function submit() {
		$this->parent->setVarsFromRequest( array( '_RightsProfile', '_LicenseCode',
			'wgEnableEmail', 'wgPasswordSender', 'wgEnableUpload', 'wgLogo',
			'wgEnableUserEmail', 'wgEnotifUserTalk', 'wgEnotifWatchlist',
			'wgEmailAuthentication', 'wgMainCacheType', '_MemCachedServers' ) );

		if ( !in_array( $this->getVar( '_RightsProfile' ),
			array_keys( $this->parent->rightsProfiles ) ) )
		{
			reset( $this->parent->rightsProfiles );
			$this->setVar( '_RightsProfile', key( $this->parent->rightsProfiles ) );
		}

		$code = $this->getVar( '_LicenseCode' );
		if ( $code == 'cc-choose' ) {
			if ( !$this->getVar( '_CCDone' ) ) {
				$this->parent->showError( 'config-cc-not-chosen' );
				return false;
			}
		} elseif ( in_array( $code, array_keys( $this->parent->licenses ) ) ) {
			$entry = $this->parent->licenses[$code];
			if ( isset( $entry['text'] ) ) {
				$this->setVar( 'wgRightsText', $entry['text'] );
			} else {
				$this->setVar( 'wgRightsText', wfMsg( 'config-license-' . $code ) );
			}
			$this->setVar( 'wgRightsUrl', $entry['url'] );
			$this->setVar( 'wgRightsIcon', $entry['icon'] );
		} else {
			$this->setVar( 'wgRightsText', '' );
			$this->setVar( 'wgRightsUrl', '' );
			$this->setVar( 'wgRightsIcon', '' );
		}

		$exts = $this->parent->getVar( '_Extensions' );
		foreach( $exts as $key => $ext ) {
			if( !$this->parent->request->getCheck( 'config_ext-' . $ext ) ) {
				unset( $exts[$key] );
			}
		}
		$this->parent->setVar( '_Extensions', $exts );
		return true;
	}
}

class WebInstaller_Install extends WebInstallerPage {

	function execute() {
		if( $this->parent->request->wasPosted() ) {
			return 'continue';
		}
		$this->startForm();
		$this->addHTML("<ul>");
		foreach( $this->parent->getInstallSteps() as $stepObj ) {
			$step = is_array( $stepObj ) ? $stepObj['name'] : $stepObj;
			$this->startStage( "config-install-$step" );
			$status = null;

			# Call our working function
			if ( is_array( $step ) ) {
				# A custom callaback
				$callback = $stepObj['callback'];
				$status = call_user_func_array( $callback, array() );
			} else {
				# Boring implicitly named callback
				$func = 'install' . ucfirst( $step );
				$status = $this->parent->{$func}();
			}

			$ok = $status->isGood();
			if ( !$ok ) {
				$this->parent->showStatusBox( $status );
			}
			$this->endStage( $ok );
		}
		$this->addHTML("</ul>");
		$this->endForm();
		return true;

	}

	private function startStage( $msg ) {
		$this->addHTML( "<li>" . wfMsgHtml( $msg ) . wfMsg( 'ellipsis') );
	}

	private function endStage( $success = true ) {
		$msg = $success ? 'config-install-step-done' : 'config-install-step-failed';
		$html = wfMsgHtml( 'word-separator' ) . wfMsgHtml( $msg );
		if ( !$success ) {
			$html = "<span class=\"error\">$html</span>";
		}
		$this->addHTML( $html . "</li>\n" );
	}
}

class WebInstaller_Complete extends WebInstallerPage {
	public function execute() {
		global $IP;
		$this->startForm();
		$msg = file_exists( "$IP/LocalSettings.php" ) ? 'config-install-done-moved' : 'config-install-done';
		$this->addHTML(
			$this->parent->getInfoBox(
				wfMsgNoTrans( $msg,
					$GLOBALS['wgServer'] .
						$this->getVar( 'wgScriptPath' ) . '/index' .
						$this->getVar( 'wgScriptExtension' )
				), 'tick-32.png'
			)
		);
		$this->endForm( false );
	}
}

class WebInstaller_Restart extends WebInstallerPage {
	function execute() {
		$r = $this->parent->request;
		if ( $r->wasPosted() ) {
			$really = $r->getVal( 'submit-restart' );
			if ( $really ) {
				$this->parent->session = array();
				$this->parent->happyPages = array();
				$this->parent->settings = array();
			}
			return 'continue';
		}

		$this->startForm();
		$s = $this->parent->getWarningBox( wfMsgNoTrans( 'config-help-restart' ) );
		$this->addHTML( $s );
		$this->endForm( 'restart' );
	}
}

abstract class WebInstaller_Document extends WebInstallerPage {
	abstract function getFileName();

	function execute() {
		$text = $this->getFileContents();
		$this->parent->output->addWikiText( $text );
		$this->startForm();
		$this->endForm( false );
	}

	function getFileContents() {
		return file_get_contents( dirname( __FILE__ ) . '/../../' . $this->getFileName() );
	}

	protected function formatTextFile( $text ) {
		$text = str_replace( array( '<', '{{', '[[' ),
			array( '&lt;', '&#123;&#123;', '&#91;&#91;' ), $text );
		// replace numbering with [1], [2], etc with MW-style numbering
		$text = preg_replace( "/\r?\n(\r?\n)?\\[\\d+\\]/m", "\\1#", $text );
		// join word-wrapped lines into one
		do {
			$prev = $text;
			$text = preg_replace( "/\n([\\*#])([^\r\n]*?)\r?\n([^\r\n#\\*:]+)/", "\n\\1\\2 \\3", $text );
		} while ( $text != $prev );
		// turn (bug nnnn) into links
		$text = preg_replace_callback('/bug (\d+)/', array( $this, 'replaceBugLinks' ), $text );
		// add links to manual to every global variable mentioned
		$text = preg_replace_callback('/(\$wg[a-z0-9_]+)/i', array( $this, 'replaceConfigLinks' ), $text );
		// special case for <pre> - formatted links
		do {
			$prev = $text;
			$text = preg_replace( '/^([^\\s].*?)\r?\n[\\s]+(https?:\/\/)/m', "\\1\n:\\2", $text );
		} while ( $text != $prev );
		return $text;
	}

	private function replaceBugLinks( $matches ) {
		return '<span class="config-plainlink">[https://bugzilla.wikimedia.org/' .
			$matches[1] . ' bug ' . $matches[1] . ']</span>';
	}

	private function replaceConfigLinks( $matches ) {
		return '<span class="config-plainlink">[http://www.mediawiki.org/wiki/Manual:' .
			$matches[1] . ' ' . $matches[1] . ']</span>';
	}
}

class WebInstaller_Readme extends WebInstaller_Document {
	function getFileName() { return 'README'; }

	function getFileContents() {
		return $this->formatTextFile( parent::getFileContents() );
	}
}

class WebInstaller_ReleaseNotes extends WebInstaller_Document {
	function getFileName() { return 'RELEASE-NOTES'; }

	function getFileContents() {
		return $this->formatTextFile( parent::getFileContents() );
	}
}

class WebInstaller_UpgradeDoc extends WebInstaller_Document {
	function getFileName() { return 'UPGRADE'; }

	function getFileContents() {
		return $this->formatTextFile( parent::getFileContents() );
	}
}

class WebInstaller_Copying extends WebInstaller_Document {
	function getFileName() { return 'COPYING'; }

	function getFileContents() {
		$text = parent::getFileContents();
		$text = str_replace( "\x0C", '', $text );
		$text = preg_replace_callback( '/\n[ \t]+/m', array( 'WebInstaller_Copying', 'replaceLeadingSpaces' ), $text );
		$text = '<tt>' . nl2br( $text ) . '</tt>';
		return $text;
	}

	private static function replaceLeadingSpaces( $matches ) {
		return "\n" . str_repeat( '&#160;', strlen( $matches[0] ) );
	}
}
