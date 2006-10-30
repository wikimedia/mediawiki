<?php
/**
 * Hold things related to displaying and saving user preferences.
 * @package MediaWiki
 * @subpackage SpecialPage
 */

/**
 * Entry point that create the "Preferences" object
 */
function wfSpecialPreferences() {
	global $wgRequest;

	$form = new PreferencesForm( $wgRequest );
	$form->execute();
}

/**
 * Preferences form handling
 * This object will show the preferences form and can save it as well.
 * @package MediaWiki
 * @subpackage SpecialPage
 */
class PreferencesForm {
	var $mQuickbar, $mOldpass, $mNewpass, $mRetypePass, $mStubs;
	var $mRows, $mCols, $mSkin, $mMath, $mDate, $mUserEmail, $mEmailFlag, $mNick;
	var $mUserLanguage, $mUserVariant;
	var $mSearch, $mRecent, $mHourDiff, $mSearchLines, $mSearchChars, $mAction;
	var $mReset, $mPosted, $mToggles, $mSearchNs, $mRealName, $mImageSize;
	var $mUnderline, $mWatchlistEdits;

	/**
	 * Constructor
	 * Load some values
	 */
	function PreferencesForm( &$request ) {
		global $wgLang, $wgContLang, $wgUser, $wgAllowRealName;

		$this->mQuickbar = $request->getVal( 'wpQuickbar' );
		$this->mOldpass = $request->getVal( 'wpOldpass' );
		$this->mNewpass = $request->getVal( 'wpNewpass' );
		$this->mRetypePass =$request->getVal( 'wpRetypePass' );
		$this->mStubs = $request->getVal( 'wpStubs' );
		$this->mRows = $request->getVal( 'wpRows' );
		$this->mCols = $request->getVal( 'wpCols' );
		$this->mSkin = $request->getVal( 'wpSkin' );
		$this->mMath = $request->getVal( 'wpMath' );
		$this->mDate = $request->getVal( 'wpDate' );
		$this->mUserEmail = $request->getVal( 'wpUserEmail' );
		$this->mRealName = $wgAllowRealName ? $request->getVal( 'wpRealName' ) : '';
		$this->mEmailFlag = $request->getCheck( 'wpEmailFlag' ) ? 0 : 1;
		$this->mNick = $request->getVal( 'wpNick' );
		$this->mUserLanguage = $request->getVal( 'wpUserLanguage' );
		$this->mUserVariant = $request->getVal( 'wpUserVariant' );
		$this->mSearch = $request->getVal( 'wpSearch' );
		$this->mRecent = $request->getVal( 'wpRecent' );
		$this->mHourDiff = $request->getVal( 'wpHourDiff' );
		$this->mSearchLines = $request->getVal( 'wpSearchLines' );
		$this->mSearchChars = $request->getVal( 'wpSearchChars' );
		$this->mImageSize = $request->getVal( 'wpImageSize' );
		$this->mThumbSize = $request->getInt( 'wpThumbSize' );
		$this->mUnderline = $request->getInt( 'wpOpunderline' );
		$this->mAction = $request->getVal( 'action' );
		$this->mReset = $request->getCheck( 'wpReset' );
		$this->mPosted = $request->wasPosted();
		$this->mSuccess = $request->getCheck( 'success' );
		$this->mWatchlistDays = $request->getVal( 'wpWatchlistDays' );
		$this->mWatchlistEdits = $request->getVal( 'wpWatchlistEdits' );

		$this->mSaveprefs = $request->getCheck( 'wpSaveprefs' ) &&
			$this->mPosted &&
			$wgUser->matchEditToken( $request->getVal( 'wpEditToken' ) );

		# User toggles  (the big ugly unsorted list of checkboxes)
		$this->mToggles = array();
		if ( $this->mPosted ) {
			$togs = User::getToggles();
			foreach ( $togs as $tname ) {
				$this->mToggles[$tname] = $request->getCheck( "wpOp$tname" ) ? 1 : 0;
			}
		}

		$this->mUsedToggles = array();

		# Search namespace options
		# Note: namespaces don't necessarily have consecutive keys
		$this->mSearchNs = array();
		if ( $this->mPosted ) {
			$namespaces = $wgContLang->getNamespaces();
			foreach ( $namespaces as $i => $namespace ) {
				if ( $i >= 0 ) {
					$this->mSearchNs[$i] = $request->getCheck( "wpNs$i" ) ? 1 : 0;
				}
			}
		}

		# Validate language
		if ( !preg_match( '/^[a-z\-]*$/', $this->mUserLanguage ) ) {
			$this->mUserLanguage = 'nolanguage';
		}
	}

	function execute() {
		global $wgUser, $wgOut;

		if ( $wgUser->isAnon() ) {
			$wgOut->showErrorPage( 'prefsnologin', 'prefsnologintext' );
			return;
		}
		if ( wfReadOnly() ) {
			$wgOut->readOnlyPage();
			return;
		}
		if ( $this->mReset ) {
			$this->resetPrefs();
			$this->mainPrefsForm( 'reset', wfMsg( 'prefsreset' ) );
		} else if ( $this->mSaveprefs ) {
			$this->savePreferences();
		} else {
			$this->resetPrefs();
			$this->mainPrefsForm( '' );
		}
	}
	/**
	 * @access private
	 */
	function validateInt( &$val, $min=0, $max=0x7fffffff ) {
		$val = intval($val);
		$val = min($val, $max);
		$val = max($val, $min);
		return $val;
	}

	/**
	 * @access private
	 */
	function validateFloat( &$val, $min, $max=0x7fffffff ) {
		$val = floatval( $val );
		$val = min( $val, $max );
		$val = max( $val, $min );
		return( $val );
	}

	/**
	 * @access private
	 */
	function validateIntOrNull( &$val, $min=0, $max=0x7fffffff ) {
		$val = trim($val);
		if($val === '') {
			return $val;
		} else {
			return $this->validateInt( $val, $min, $max );
		}
	}

	/**
	 * @access private
	 */
	function validateDate( $val ) {
		global $wgLang, $wgContLang;
		if ( $val !== false && (
			in_array( $val, (array)$wgLang->getDatePreferences() ) ||
			in_array( $val, (array)$wgContLang->getDatePreferences() ) ) ) 
		{
			return $val;
		} else {
			return $wgLang->getDefaultDateFormat();
		}
	}

	/**
	 * Used to validate the user inputed timezone before saving it as
	 * 'timeciorrection', will return '00:00' if fed bogus data.
	 * Note: It's not a 100% correct implementation timezone-wise, it will
	 * accept stuff like '14:30',
	 * @access private
	 * @param string $s the user input
	 * @return string
	 */
	function validateTimeZone( $s ) {
		if ( $s !== '' ) {
			if ( strpos( $s, ':' ) ) {
				# HH:MM
				$array = explode( ':' , $s );
				$hour = intval( $array[0] );
				$minute = intval( $array[1] );
			} else {
				$minute = intval( $s * 60 );
				$hour = intval( $minute / 60 );
				$minute = abs( $minute ) % 60;
			}
			# Max is +14:00 and min is -12:00, see:
			# http://en.wikipedia.org/wiki/Timezone
			$hour = min( $hour, 14 );
			$hour = max( $hour, -12 );
			$minute = min( $minute, 59 );
			$minute = max( $minute, 0 );
			$s = sprintf( "%02d:%02d", $hour, $minute );
		}
		return $s;
	}

	/**
	 * @access private
	 */
	function savePreferences() {
		global $wgUser, $wgOut, $wgParser;
		global $wgEnableUserEmail, $wgEnableEmail;
		global $wgEmailAuthentication, $wgMinimalPasswordLength;
		global $wgAuth;


		if ( '' != $this->mNewpass && $wgAuth->allowPasswordChange() ) {
			if ( $this->mNewpass != $this->mRetypePass ) {
				$this->mainPrefsForm( 'error', wfMsg( 'badretype' ) );
				return;
			}

			if ( strlen( $this->mNewpass ) < $wgMinimalPasswordLength ) {
				$this->mainPrefsForm( 'error', wfMsg( 'passwordtooshort', $wgMinimalPasswordLength ) );
				return;
			}

			if (!$wgUser->checkPassword( $this->mOldpass )) {
				$this->mainPrefsForm( 'error', wfMsg( 'wrongpassword' ) );
				return;
			}
			if (!$wgAuth->setPassword( $wgUser, $this->mNewpass )) {
				$this->mainPrefsForm( 'error', wfMsg( 'externaldberror' ) );
				return;
			}
			$wgUser->setPassword( $this->mNewpass );
			$this->mNewpass = $this->mOldpass = $this->mRetypePass = '';

		}
		$wgUser->setRealName( $this->mRealName );

		if( $wgUser->getOption( 'language' ) !== $this->mUserLanguage ) {
			$needRedirect = true;
		} else {
			$needRedirect = false;
		}

		# Validate the signature and clean it up as needed
		if( $this->mToggles['fancysig'] ) {
			if( Parser::validateSig( $this->mNick ) !== false ) {
				$this->mNick = $wgParser->cleanSig( $this->mNick );
			} else {
				$this->mainPrefsForm( 'error', wfMsg( 'badsig' ) );
			}
		} else {
			// When no fancy sig used, make sure ~{3,5} get removed.
			$this->mNick = $wgParser->cleanSigInSig( $this->mNick );
		}

		$wgUser->setOption( 'language', $this->mUserLanguage );
		$wgUser->setOption( 'variant', $this->mUserVariant );
		$wgUser->setOption( 'nickname', $this->mNick );
		$wgUser->setOption( 'quickbar', $this->mQuickbar );
		$wgUser->setOption( 'skin', $this->mSkin );
		global $wgUseTeX;
		if( $wgUseTeX ) {
			$wgUser->setOption( 'math', $this->mMath );
		}
		$wgUser->setOption( 'date', $this->validateDate( $this->mDate ) );
		$wgUser->setOption( 'searchlimit', $this->validateIntOrNull( $this->mSearch ) );
		$wgUser->setOption( 'contextlines', $this->validateIntOrNull( $this->mSearchLines ) );
		$wgUser->setOption( 'contextchars', $this->validateIntOrNull( $this->mSearchChars ) );
		$wgUser->setOption( 'rclimit', $this->validateIntOrNull( $this->mRecent ) );
		$wgUser->setOption( 'wllimit', $this->validateIntOrNull( $this->mWatchlistEdits, 0, 1000 ) );
		$wgUser->setOption( 'rows', $this->validateInt( $this->mRows, 4, 1000 ) );
		$wgUser->setOption( 'cols', $this->validateInt( $this->mCols, 4, 1000 ) );
		$wgUser->setOption( 'stubthreshold', $this->validateIntOrNull( $this->mStubs ) );
		$wgUser->setOption( 'timecorrection', $this->validateTimeZone( $this->mHourDiff, -12, 14 ) );
		$wgUser->setOption( 'imagesize', $this->mImageSize );
		$wgUser->setOption( 'thumbsize', $this->mThumbSize );
		$wgUser->setOption( 'underline', $this->validateInt($this->mUnderline, 0, 2) );
		$wgUser->setOption( 'watchlistdays', $this->validateFloat( $this->mWatchlistDays, 0, 7 ) );

		# Set search namespace options
		foreach( $this->mSearchNs as $i => $value ) {
			$wgUser->setOption( "searchNs{$i}", $value );
		}

		if( $wgEnableEmail && $wgEnableUserEmail ) {
			$wgUser->setOption( 'disablemail', $this->mEmailFlag );
		}

		# Set user toggles
		foreach ( $this->mToggles as $tname => $tvalue ) {
			$wgUser->setOption( $tname, $tvalue );
		}
		if (!$wgAuth->updateExternalDB($wgUser)) {
			$this->mainPrefsForm( wfMsg( 'externaldberror' ) );
			return;
		}
		$wgUser->setCookies();
		$wgUser->saveSettings();

		$error = false;
		if( $wgEnableEmail ) {
			$newadr = $this->mUserEmail;
			$oldadr = $wgUser->getEmail();
			if( ($newadr != '') && ($newadr != $oldadr) ) {
				# the user has supplied a new email address on the login page
				if( $wgUser->isValidEmailAddr( $newadr ) ) {
					$wgUser->mEmail = $newadr; # new behaviour: set this new emailaddr from login-page into user database record
					$wgUser->mEmailAuthenticated = null; # but flag as "dirty" = unauthenticated
					$wgUser->saveSettings();
					if ($wgEmailAuthentication) {
						# Mail a temporary password to the dirty address.
						# User can come back through the confirmation URL to re-enable email.
						$result = $wgUser->sendConfirmationMail();
						if( WikiError::isError( $result ) ) {
							$error = wfMsg( 'mailerror', htmlspecialchars( $result->getMessage() ) );
						} else {
							$error = wfMsg( 'eauthentsent', $wgUser->getName() );
						}
					}
				} else {
					$error = wfMsg( 'invalidemailaddress' );
				}
			} else {
				$wgUser->setEmail( $this->mUserEmail );
				$wgUser->setCookies();
				$wgUser->saveSettings();
			}
		}

		if( $needRedirect && $error === false ) {
			$title =& Title::makeTitle( NS_SPECIAL, "Preferences" );
			$wgOut->redirect($title->getFullURL('success'));
			return;
		}

		$wgOut->setParserOptions( ParserOptions::newFromUser( $wgUser ) );
		$po = ParserOptions::newFromUser( $wgUser );
		$this->mainPrefsForm( $error === false ? 'success' : 'error', $error);
	}

	/**
	 * @access private
	 */
	function resetPrefs() {
		global $wgUser, $wgLang, $wgContLang, $wgAllowRealName;

		$this->mOldpass = $this->mNewpass = $this->mRetypePass = '';
		$this->mUserEmail = $wgUser->getEmail();
		$this->mUserEmailAuthenticationtimestamp = $wgUser->getEmailAuthenticationtimestamp();
		$this->mRealName = ($wgAllowRealName) ? $wgUser->getRealName() : '';
		$this->mUserLanguage = $wgUser->getOption( 'language' );
		if( empty( $this->mUserLanguage ) ) {
			# Quick hack for conversions, where this value is blank
			global $wgContLanguageCode;
			$this->mUserLanguage = $wgContLanguageCode;
		}
		$this->mUserVariant = $wgUser->getOption( 'variant');
		$this->mEmailFlag = $wgUser->getOption( 'disablemail' ) == 1 ? 1 : 0;
		$this->mNick = $wgUser->getOption( 'nickname' );

		$this->mQuickbar = $wgUser->getOption( 'quickbar' );
		$this->mSkin = Skin::normalizeKey( $wgUser->getOption( 'skin' ) );
		$this->mMath = $wgUser->getOption( 'math' );
		$this->mDate = $wgUser->getDatePreference();
		$this->mRows = $wgUser->getOption( 'rows' );
		$this->mCols = $wgUser->getOption( 'cols' );
		$this->mStubs = $wgUser->getOption( 'stubthreshold' );
		$this->mHourDiff = $wgUser->getOption( 'timecorrection' );
		$this->mSearch = $wgUser->getOption( 'searchlimit' );
		$this->mSearchLines = $wgUser->getOption( 'contextlines' );
		$this->mSearchChars = $wgUser->getOption( 'contextchars' );
		$this->mImageSize = $wgUser->getOption( 'imagesize' );
		$this->mThumbSize = $wgUser->getOption( 'thumbsize' );
		$this->mRecent = $wgUser->getOption( 'rclimit' );
		$this->mWatchlistEdits = $wgUser->getOption( 'wllimit' );
		$this->mUnderline = $wgUser->getOption( 'underline' );
		$this->mWatchlistDays = $wgUser->getOption( 'watchlistdays' );

		$togs = User::getToggles();
		foreach ( $togs as $tname ) {
			$ttext = wfMsg('tog-'.$tname);
			$this->mToggles[$tname] = $wgUser->getOption( $tname );
		}

		$namespaces = $wgContLang->getNamespaces();
		foreach ( $namespaces as $i => $namespace ) {
			if ( $i >= NS_MAIN ) {
				$this->mSearchNs[$i] = $wgUser->getOption( 'searchNs'.$i );
			}
		}
	}

	/**
	 * @access private
	 */
	function namespacesCheckboxes() {
		global $wgContLang;

		# Determine namespace checkboxes
		$namespaces = $wgContLang->getNamespaces();
		$r1 = null;

		foreach ( $namespaces as $i => $name ) {
			if ($i < 0)
				continue;
			$checked = $this->mSearchNs[$i] ? "checked='checked'" : '';
			$name = str_replace( '_', ' ', $namespaces[$i] );

			if ( empty($name) )
				$name = wfMsg( 'blanknamespace' );

			$r1 .= "<input type='checkbox' value='1' name='wpNs$i' id='wpNs$i' {$checked}/> <label for='wpNs$i'>{$name}</label><br />\n";
		}
		return $r1;
	}


	function getToggle( $tname, $trailer = false, $disabled = false ) {
		global $wgUser, $wgLang;

		$this->mUsedToggles[$tname] = true;
		$ttext = $wgLang->getUserToggle( $tname );

		$checked = $wgUser->getOption( $tname ) == 1 ? ' checked="checked"' : '';
		$disabled = $disabled ? ' disabled="disabled"' : '';
		$trailer = $trailer ? $trailer : '';
		return "<div class='toggle'><input type='checkbox' value='1' id=\"$tname\" name=\"wpOp$tname\"$checked$disabled />" .
			" <span class='toggletext'><label for=\"$tname\">$ttext</label>$trailer</span></div>\n";
	}

	function getToggles( $items ) {
		$out = "";
		foreach( $items as $item ) {
			if( $item === false )
				continue;
			if( is_array( $item ) ) {
				list( $key, $trailer ) = $item;
			} else {
				$key = $item;
				$trailer = false;
			}
			$out .= $this->getToggle( $key, $trailer );
		}
		return $out;
	}

	function addRow($td1, $td2) {
		return "<tr><td align='right'>$td1</td><td align='left'>$td2</td></tr>";
	}

	/**
	 * @access private
	 */
	function mainPrefsForm( $status , $message = '' ) {
		global $wgUser, $wgOut, $wgLang, $wgContLang;
		global $wgAllowRealName, $wgImageLimits, $wgThumbLimits;
		global $wgDisableLangConversion;
		global $wgEnotifWatchlist, $wgEnotifUserTalk,$wgEnotifMinorEdits;
		global $wgRCShowWatchingUsers, $wgEnotifRevealEditorAddress;
		global $wgEnableEmail, $wgEnableUserEmail, $wgEmailAuthentication;
		global $wgContLanguageCode, $wgDefaultSkin, $wgSkipSkins, $wgAuth;

		$wgOut->setPageTitle( wfMsg( 'preferences' ) );
		$wgOut->setArticleRelated( false );
		$wgOut->setRobotpolicy( 'noindex,nofollow' );

		if ( $this->mSuccess || 'success' == $status ) {
			$wgOut->addWikitext( '<div class="successbox"><strong>'. wfMsg( 'savedprefs' ) . '</strong></div>' );
		} else	if ( 'error' == $status ) {
			$wgOut->addWikitext( '<div class="errorbox"><strong>' . $message  . '</strong></div>' );
		} else if ( '' != $status ) {
			$wgOut->addWikitext( $message . "\n----" );
		}

		$qbs = $wgLang->getQuickbarSettings();
		$skinNames = $wgLang->getSkinNames();
		$mathopts = $wgLang->getMathNames();
		$dateopts = $wgLang->getDatePreferences();
		$togs = User::getToggles();

		$titleObj = Title::makeTitle( NS_SPECIAL, 'Preferences' );
		$action = $titleObj->escapeLocalURL();

		# Pre-expire some toggles so they won't show if disabled
		$this->mUsedToggles[ 'shownumberswatching' ] = true;
		$this->mUsedToggles[ 'showupdated' ] = true;
		$this->mUsedToggles[ 'enotifwatchlistpages' ] = true;
		$this->mUsedToggles[ 'enotifusertalkpages' ] = true;
		$this->mUsedToggles[ 'enotifminoredits' ] = true;
		$this->mUsedToggles[ 'enotifrevealaddr' ] = true;
		$this->mUsedToggles[ 'uselivepreview' ] = true;

		# Enotif
		# <FIXME>
		$this->mUserEmail = htmlspecialchars( $this->mUserEmail );
		$this->mRealName = htmlspecialchars( $this->mRealName );
		$rawNick = $this->mNick;
		$this->mNick = htmlspecialchars( $this->mNick );
		if ( !$this->mEmailFlag ) { $emfc = 'checked="checked"'; }
		else { $emfc = ''; }


		if ($wgEmailAuthentication && ($this->mUserEmail != '') ) {
			if( $wgUser->getEmailAuthenticationTimestamp() ) {
				$emailauthenticated = wfMsg('emailauthenticated',$wgLang->timeanddate($wgUser->getEmailAuthenticationTimestamp(), true ) ).'<br />';
				$disableEmailPrefs = false;
			} else {
				$disableEmailPrefs = true;
				$skin = $wgUser->getSkin();
				$emailauthenticated = wfMsg('emailnotauthenticated').'<br />' .
					$skin->makeKnownLinkObj( Title::makeTitle( NS_SPECIAL, 'Confirmemail' ),
						wfMsg( 'emailconfirmlink' ) );
			}
		} else {
			$emailauthenticated = '';
			$disableEmailPrefs = false;
		}

		if ($this->mUserEmail == '') {
			$emailauthenticated = wfMsg( 'noemailprefs' );
		}

		$ps = $this->namespacesCheckboxes();

		$enotifwatchlistpages = ($wgEnotifWatchlist) ? $this->getToggle( 'enotifwatchlistpages', false, $disableEmailPrefs ) : '';
		$enotifusertalkpages = ($wgEnotifUserTalk) ? $this->getToggle( 'enotifusertalkpages', false, $disableEmailPrefs ) : '';
		$enotifminoredits = ($wgEnotifWatchlist && $wgEnotifMinorEdits) ? $this->getToggle( 'enotifminoredits', false, $disableEmailPrefs ) : '';
		$enotifrevealaddr = (($wgEnotifWatchlist || $wgEnotifUserTalk) && $wgEnotifRevealEditorAddress) ? $this->getToggle( 'enotifrevealaddr', false, $disableEmailPrefs ) : '';
		$prefs_help_email_enotif = ( $wgEnotifWatchlist || $wgEnotifUserTalk) ? ' ' . wfMsg('prefs-help-email-enotif') : '';
		$prefs_help_realname = '';

		# </FIXME>

		$wgOut->addHTML( "<form action=\"$action\" method='post'>" );
		$wgOut->addHTML( "<div id='preferences'>" );

		# User data
		#

		$wgOut->addHTML( "<fieldset>\n<legend>" . wfMsg('prefs-personal') . "</legend>\n<table>\n");

		$wgOut->addHTML(
			$this->addRow(
				wfMsg( 'username'),
				$wgUser->getName()
			)
		);

		$wgOut->addHTML(
			$this->addRow(
				wfMsg( 'uid' ),
				$wgUser->getID()
			)
		);


		if ($wgAllowRealName) {
			$wgOut->addHTML(
				$this->addRow(
					'<label for="wpRealName">' . wfMsg('yourrealname') . '</label>',
					"<input type='text' name='wpRealName' id='wpRealName' value=\"{$this->mRealName}\" size='25' />"
				)
			);
		}
		if ($wgEnableEmail) {
			$wgOut->addHTML(
				$this->addRow(
					'<label for="wpUserEmail">' . wfMsg( 'youremail' ) . '</label>',
					"<input type='text' name='wpUserEmail' id='wpUserEmail' value=\"{$this->mUserEmail}\" size='25' />"
				)
			);
		}

		global $wgParser;
		if( !empty( $this->mToggles['fancysig'] ) &&
			false === $wgParser->validateSig( $rawNick ) ) {
			$invalidSig = $this->addRow(
				'&nbsp;',
				'<span class="error">' . wfMsgHtml( 'badsig' ) . '<span>'
			);
		} else {
			$invalidSig = '';
		}

		$wgOut->addHTML(
			$this->addRow(
				'<label for="wpNick">' . wfMsg( 'yournick' ) . '</label>',
				"<input type='text' name='wpNick' id='wpNick' value=\"{$this->mNick}\" size='25' />"
			) .
			$invalidSig .
			# FIXME: The <input> part should be where the &nbsp; is, getToggle() needs
			# to be changed to out return its output in two parts. -ævar
			$this->addRow(
				'&nbsp;',
				$this->getToggle( 'fancysig' )
			)
		);

		/**
		 * Make sure the site language is in the list; a custom language code
		 * might not have a defined name...
		 */
		$languages = $wgLang->getLanguageNames( true );
		if( !array_key_exists( $wgContLanguageCode, $languages ) ) {
			$languages[$wgContLanguageCode] = $wgContLanguageCode;
		}
		ksort( $languages );

		/**
		 * If a bogus value is set, default to the content language.
		 * Otherwise, no default is selected and the user ends up
		 * with an Afrikaans interface since it's first in the list.
		 */
		$selectedLang = isset( $languages[$this->mUserLanguage] ) ? $this->mUserLanguage : $wgContLanguageCode;
		$selbox = null;
		foreach($languages as $code => $name) {
			global $IP;
			$sel = ($code == $selectedLang)? ' selected="selected"' : '';
			$selbox .= "<option value=\"$code\"$sel>$code - $name</option>\n";
		}
		$wgOut->addHTML(
			$this->addRow(
				'<label for="wpUserLanguage">' . wfMsg('yourlanguage') . '</label>',
				"<select name='wpUserLanguage' id='wpUserLanguage'>$selbox</select>"
			)
		);

		/* see if there are multiple language variants to choose from*/
		if(!$wgDisableLangConversion) {
			$variants = $wgContLang->getVariants();
			$variantArray = array();

			foreach($variants as $v) {
				$v = str_replace( '_', '-', strtolower($v));
				if( array_key_exists( $v, $languages ) ) {
					// If it doesn't have a name, we'll pretend it doesn't exist
					$variantArray[$v] = $languages[$v];
				}
			}

			$selbox = null;
			foreach($variantArray as $code => $name) {
				$sel = $code == $this->mUserVariant ? 'selected="selected"' : '';
				$selbox .= "<option value=\"$code\" $sel>$code - $name</option>";
			}

			if(count($variantArray) > 1) {
				$wgOut->addHtml(
					$this->addRow( wfMsg( 'yourvariant' ), "<select name='wpUserVariant'>$selbox</select>" )
				);
			}
		}
		$wgOut->addHTML('</table>');

		# Password
		if( $wgAuth->allowPasswordChange() ) {
			$this->mOldpass = htmlspecialchars( $this->mOldpass );
			$this->mNewpass = htmlspecialchars( $this->mNewpass );
			$this->mRetypePass = htmlspecialchars( $this->mRetypePass );
	
			$wgOut->addHTML( '<fieldset><legend>' . wfMsg( 'changepassword' ) . '</legend><table>');
			$wgOut->addHTML(
				$this->addRow(
					'<label for="wpOldpass">' . wfMsg( 'oldpassword' ) . '</label>',
					"<input type='password' name='wpOldpass' id='wpOldpass' value=\"{$this->mOldpass}\" size='20' />"
				) .
				$this->addRow(
					'<label for="wpNewpass">' . wfMsg( 'newpassword' ) . '</label>',
					"<input type='password' name='wpNewpass' id='wpNewpass' value=\"{$this->mNewpass}\" size='20' />"
				) .
				$this->addRow(
					'<label for="wpRetypePass">' . wfMsg( 'retypenew' ) . '</label>',
					"<input type='password' name='wpRetypePass' id='wpRetypePass' value=\"{$this->mRetypePass}\" size='20' />"
				) .
				"</table>\n" .
				$this->getToggle( "rememberpassword" ) . "</fieldset>\n\n" );
		}

		# <FIXME>
		# Enotif
		if ($wgEnableEmail) {
			$wgOut->addHTML( '<fieldset><legend>' . wfMsg( 'email' ) . '</legend>' );
			$wgOut->addHTML(
					$emailauthenticated.
					$enotifrevealaddr.
					$enotifwatchlistpages.
					$enotifusertalkpages.
					$enotifminoredits );
			if ($wgEnableUserEmail) {
			$emf = wfMsg( 'allowemail' );
				$disabled = $disableEmailPrefs ? ' disabled="disabled"' : '';
				$wgOut->addHTML(
				"<div><input type='checkbox' $emfc $disabled value='1' name='wpEmailFlag' id='wpEmailFlag' /> <label for='wpEmailFlag'>$emf</label></div>" );
			}

			$wgOut->addHTML( '</fieldset>' );
		}
		# </FIXME>

		# Show little "help" tips for the real name and email address options
		if( $wgAllowRealName || $wgEnableEmail ) {
			if( $wgAllowRealName )
				$tips[] = wfMsg( 'prefs-help-realname' );
			if( $wgEnableEmail )
				$tips[] = wfMsg( 'prefs-help-email' );
			$wgOut->addHtml( '<div class="prefsectiontip">' . implode( '<br />', $tips ) . '</div>' );
		}		

		$wgOut->addHTML( '</fieldset>' );

		# Quickbar
		#
		if ($this->mSkin == 'cologneblue' || $this->mSkin == 'standard') {
			$wgOut->addHtml( "<fieldset>\n<legend>" . wfMsg( 'qbsettings' ) . "</legend>\n" );
			for ( $i = 0; $i < count( $qbs ); ++$i ) {
				if ( $i == $this->mQuickbar ) { $checked = ' checked="checked"'; }
				else { $checked = ""; }
				$wgOut->addHTML( "<div><label><input type='radio' name='wpQuickbar' value=\"$i\"$checked />{$qbs[$i]}</label></div>\n" );
			}
			$wgOut->addHtml( "</fieldset>\n\n" );
		} else {
			# Need to output a hidden option even if the relevant skin is not in use,
			# otherwise the preference will get reset to 0 on submit
			$wgOut->addHtml( wfHidden( 'wpQuickbar', $this->mQuickbar ) );
		}

		# Skin
		#
		$wgOut->addHTML( "<fieldset>\n<legend>\n" . wfMsg('skin') . "</legend>\n" );
		$mptitle = Title::newMainPage();
		$previewtext = wfMsg('skinpreview');
		# Only show members of Skin::getSkinNames() rather than
		# $skinNames (skins is all skin names from Language.php)
		$validSkinNames = Skin::getSkinNames();
		foreach ($validSkinNames as $skinkey => $skinname ) {
			if ( in_array( $skinkey, $wgSkipSkins ) ) {
				continue;
			}
			$checked = $skinkey == $this->mSkin ? ' checked="checked"' : '';
			$sn = isset( $skinNames[$skinkey] ) ? $skinNames[$skinkey] : $skinname;

			$mplink = htmlspecialchars($mptitle->getLocalURL("useskin=$skinkey"));
			$previewlink = "<a target='_blank' href=\"$mplink\">$previewtext</a>";
			if( $skinkey == $wgDefaultSkin )
				$sn .= ' (' . wfMsg( 'default' ) . ')';
			$wgOut->addHTML( "<input type='radio' name='wpSkin' id=\"wpSkin$skinkey\" value=\"$skinkey\"$checked /> <label for=\"wpSkin$skinkey\">{$sn}</label> $previewlink<br />\n" );
		}
		$wgOut->addHTML( "</fieldset>\n\n" );

		# Math
		#
		global $wgUseTeX;
		if( $wgUseTeX ) {
			$wgOut->addHTML( "<fieldset>\n<legend>" . wfMsg('math') . '</legend>' );
			foreach ( $mathopts as $k => $v ) {
				$checked = $k == $this->mMath ? ' checked="checked"' : '';
				$wgOut->addHTML( "<div><label><input type='radio' name='wpMath' value=\"$k\"$checked /> ".wfMsg($v)."</label></div>\n" );
			}
			$wgOut->addHTML( "</fieldset>\n\n" );
		}

		# Files
		#
		$wgOut->addHTML("<fieldset>
			<legend>" . wfMsg( 'files' ) . "</legend>
			<div><label for='wpImageSize'>" . wfMsg('imagemaxsize') . "</label> <select id='wpImageSize' name='wpImageSize'>");

		$imageLimitOptions = null;
		foreach ( $wgImageLimits as $index => $limits ) {
			$selected = ($index == $this->mImageSize) ? 'selected="selected"' : '';
			$imageLimitOptions .= "<option value=\"{$index}\" {$selected}>{$limits[0]}×{$limits[1]}". wfMsgHtml('unit-pixel') ."</option>\n";
		}

		$imageThumbOptions = null;
		$wgOut->addHTML( "{$imageLimitOptions}</select></div>
			<div><label for='wpThumbSize'>" . wfMsg('thumbsize') . "</label> <select name='wpThumbSize' id='wpThumbSize'>");
		foreach ( $wgThumbLimits as $index => $size ) {
			$selected = ($index == $this->mThumbSize) ? 'selected="selected"' : '';
			$imageThumbOptions .= "<option value=\"{$index}\" {$selected}>{$size}". wfMsgHtml('unit-pixel') ."</option>\n";
		}
		$wgOut->addHTML( "{$imageThumbOptions}</select></div></fieldset>\n\n");

		# Date format
		#
		# Date/Time
		#

		$wgOut->addHTML( "<fieldset>\n<legend>" . wfMsg( 'datetime' ) . "</legend>\n" );

		if ($dateopts) {
			$wgOut->addHTML( "<fieldset>\n<legend>" . wfMsg( 'dateformat' ) . "</legend>\n" );
			$idCnt = 0;
			$epoch = '20010115161234'; # Wikipedia day
			foreach( $dateopts as $key ) {
				if( $key == 'default' ) {
					$formatted = wfMsgHtml( 'datedefault' );
				} else {
					$formatted = htmlspecialchars( $wgLang->timeanddate( $epoch, false, $key ) );
				}
				($key == $this->mDate) ? $checked = ' checked="checked"' : $checked = '';
				$wgOut->addHTML( "<div><input type='radio' name=\"wpDate\" id=\"wpDate$idCnt\" ".
					"value=\"$key\"$checked /> <label for=\"wpDate$idCnt\">$formatted</label></div>\n" );
				$idCnt++;
			}
			$wgOut->addHTML( "</fieldset>\n" );
		}

		$nowlocal = $wgLang->time( $now = wfTimestampNow(), true );
		$nowserver = $wgLang->time( $now, false );

		$wgOut->addHTML( '<fieldset><legend>' . wfMsg( 'timezonelegend' ). '</legend><table>' .
		 	$this->addRow( wfMsg( 'servertime' ), $nowserver ) .
			$this->addRow( wfMsg( 'localtime' ), $nowlocal ) .
			$this->addRow(
				'<label for="wpHourDiff">' . wfMsg( 'timezoneoffset' ) . '</label>',
				"<input type='text' name='wpHourDiff' id='wpHourDiff' value=\"" . htmlspecialchars( $this->mHourDiff ) . "\" size='6' />"
			) . "<tr><td colspan='2'>
				<input type='button' value=\"" . wfMsg( 'guesstimezone' ) ."\"
				onclick='javascript:guessTimezone()' id='guesstimezonebutton' style='display:none;' />
				</td></tr></table><div class='prefsectiontip'>¹" .  wfMsg( 'timezonetext' ) . "</div></fieldset>
		</fieldset>\n\n" );

		# Editing
		#
		global $wgLivePreview, $wgUseRCPatrol;
		$wgOut->addHTML( '<fieldset><legend>' . wfMsg( 'textboxsize' ) . '</legend>
			<div>' .
				wfInputLabel( wfMsg( 'rows' ), 'wpRows', 'wpRows', 3, $this->mRows ) .
				' ' .
				wfInputLabel( wfMsg( 'columns' ), 'wpCols', 'wpCols', 3, $this->mCols ) .
			"</div>" .
			$this->getToggles( array(
				'editsection',
				'editsectiononrightclick',
				'editondblclick',
				'editwidth',
				'showtoolbar',
				'previewonfirst',
				'previewontop',
				'watchcreations',
				'watchdefault',
				'minordefault',
				'externaleditor',
				'externaldiff',
				$wgLivePreview ? 'uselivepreview' : false,
				$wgUser->isAllowed( 'patrol' ) && $wgUseRCPatrol ? 'autopatrol' : false,
				'forceeditsummary',
			) ) . '</fieldset>'
		);
		$this->mUsedToggles['autopatrol'] = true; # Don't show this up for users who can't; the handler below is dumb and doesn't know it

		$wgOut->addHTML( '<fieldset><legend>' . htmlspecialchars(wfMsg('prefs-rc')) . '</legend>' .
					wfInputLabel( wfMsg( 'recentchangescount' ),
						'wpRecent', 'wpRecent', 3, $this->mRecent ) .
			$this->getToggles( array(
				'hideminor',
				$wgRCShowWatchingUsers ? 'shownumberswatching' : false,
				'usenewrc' )
			) . '</fieldset>'
		);

		# Watchlist
		$wgOut->addHTML( '<fieldset><legend>' . wfMsgHtml( 'prefs-watchlist' ) . '</legend>' );

		$wgOut->addHTML( wfInputLabel( wfMsg( 'prefs-watchlist-days' ),
			'wpWatchlistDays', 'wpWatchlistDays', 3, $this->mWatchlistDays ) );
		$wgOut->addHTML( '<br /><br />' ); # Spacing
		$wgOut->addHTML( $this->getToggles( array( 'watchlisthideown', 'watchlisthidebots', 'extendwatchlist' ) ) );
		$wgOut->addHTML( wfInputLabel( wfMsg( 'prefs-watchlist-edits' ),
			'wpWatchlistEdits', 'wpWatchlistEdits', 3, $this->mWatchlistEdits ) );

		$wgOut->addHTML( '</fieldset>' );

		# Search
		$wgOut->addHTML( '<fieldset><legend>' . wfMsg( 'searchresultshead' ) . '</legend><table>' .
			$this->addRow(
				wfLabel( wfMsg( 'resultsperpage' ), 'wpSearch' ),
				wfInput( 'wpSearch', 4, $this->mSearch, array( 'id' => 'wpSearch' ) )
			) .
			$this->addRow(
				wfLabel( wfMsg( 'contextlines' ), 'wpSearchLines' ),
				wfInput( 'wpSearchLines', 4, $this->mSearchLines, array( 'id' => 'wpSearchLines' ) )
			) .
			$this->addRow(
				wfLabel( wfMsg( 'contextchars' ), 'wpSearchChars' ),
				wfInput( 'wpSearchChars', 4, $this->mSearchChars, array( 'id' => 'wpSearchChars' ) )
			) .
		"</table><fieldset><legend>" . wfMsg( 'defaultns' ) . "</legend>$ps</fieldset></fieldset>" );

		# Misc
		#
		$wgOut->addHTML('<fieldset><legend>' . wfMsg('prefs-misc') . '</legend>');
		$wgOut->addHTML( wfInputLabel( wfMsg( 'stubthreshold' ),
			'wpStubs', 'wpStubs', 6, $this->mStubs ) );
		$msgUnderline = htmlspecialchars( wfMsg ( 'tog-underline' ) );
		$msgUnderlinenever = htmlspecialchars( wfMsg ( 'underline-never' ) );
		$msgUnderlinealways = htmlspecialchars( wfMsg ( 'underline-always' ) );
		$msgUnderlinedefault = htmlspecialchars( wfMsg ( 'underline-default' ) );
		$uopt = $wgUser->getOption("underline");
		$s0 = $uopt == 0 ? ' selected="selected"' : '';
		$s1 = $uopt == 1 ? ' selected="selected"' : '';
		$s2 = $uopt == 2 ? ' selected="selected"' : '';
		$wgOut->addHTML("
<div class='toggle'><label for='wpOpunderline'>$msgUnderline</label>
<select name='wpOpunderline' id='wpOpunderline'>
<option value=\"0\"$s0>$msgUnderlinenever</option>
<option value=\"1\"$s1>$msgUnderlinealways</option>
<option value=\"2\"$s2>$msgUnderlinedefault</option>
</select>
</div>
");
		foreach ( $togs as $tname ) {
			if( !array_key_exists( $tname, $this->mUsedToggles ) ) {
				$wgOut->addHTML( $this->getToggle( $tname ) );
			}
		}
		$wgOut->addHTML( '</fieldset>' );

		$token = $wgUser->editToken();
		$wgOut->addHTML( "
	<div id='prefsubmit'>
	<div>
		<input type='submit' name='wpSaveprefs' class='btnSavePrefs' value=\"" . wfMsgHtml( 'saveprefs' ) . '"'.Skin::tooltipAndAccesskey('save')." />
		<input type='submit' name='wpReset' value=\"" . wfMsgHtml( 'resetprefs' ) . "\" />
	</div>

	</div>

	<input type='hidden' name='wpEditToken' value='{$token}' />
	</div></form>\n" );

		$wgOut->addWikiText( '<div class="prefcache">' . wfMsg('clearyourcache') . '</div>' );

	}
}
?>
