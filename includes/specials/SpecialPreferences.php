<?php
/**
 * Hold things related to displaying and saving user preferences.
 * @file
 * @ingroup SpecialPage
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
 * @ingroup SpecialPage
 */
class PreferencesForm {
	var $mQuickbar, $mStubs;
	var $mRows, $mCols, $mSkin, $mMath, $mDate, $mUserEmail, $mEmailFlag, $mNick;
	var $mUserLanguage, $mUserVariant;
	var $mSearch, $mRecent, $mRecentDays, $mTimeZone, $mHourDiff, $mSearchLines, $mSearchChars, $mAction;
	var $mReset, $mPosted, $mToggles, $mSearchNs, $mRealName, $mImageSize;
	var $mUnderline, $mWatchlistEdits;

	/**
	 * Constructor
	 * Load some values
	 */
	function PreferencesForm( &$request ) {
		global $wgContLang, $wgUser, $wgAllowRealName;

		$this->mQuickbar = $request->getVal( 'wpQuickbar' );
		$this->mStubs = $request->getVal( 'wpStubs' );
		$this->mRows = $request->getVal( 'wpRows' );
		$this->mCols = $request->getVal( 'wpCols' );
		$this->mSkin = Skin::normalizeKey( $request->getVal( 'wpSkin' ) );
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
		$this->mRecentDays = $request->getVal( 'wpRecentDays' );
		$this->mTimeZone = $request->getVal( 'wpTimeZone' );
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
		$this->mDisableMWSuggest = $request->getCheck( 'wpDisableMWSuggest' );

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

		wfRunHooks( 'InitPreferencesForm', array( $this, $request ) );
	}

	function execute() {
		global $wgUser, $wgOut, $wgTitle;

		if ( $wgUser->isAnon() ) {
			$wgOut->showErrorPage( 'prefsnologin', 'prefsnologintext', array($wgTitle->getPrefixedDBkey()) );
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
			return null;
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
	 * 'timecorrection', will return 'System' if fed bogus data.
	 * @access private
	 * @param string $tz the user input Zoneinfo timezone
	 * @param string $s  the user input offset string
	 * @return string
	 */
	function validateTimeZone( $tz, $s ) {
		$data = explode( '|', $tz, 3 );
		switch ( $data[0] ) {
			case 'ZoneInfo':
			case 'System':
				return $tz;
			case 'Offset':
			default:
				$data = explode( ':', $s, 2 );
				$minDiff = 0;
				if( count( $data ) == 2 ) {
					$data[0] = intval( $data[0] );
					$data[1] = intval( $data[1] );
					$minDiff = abs( $data[0] ) * 60 + $data[1];
					if ( $data[0] < 0 ) $minDiff = -$minDiff;
				} else {
					$minDiff = intval( $data[0] ) * 60;
				}

				# Max is +14:00 and min is -12:00, see:
				# http://en.wikipedia.org/wiki/Timezone
				$minDiff = min( $minDiff, 840 );  # 14:00
				$minDiff = max( $minDiff, -720 ); # -12:00
				return 'Offset|'.$minDiff;
		}
	}

	/**
	 * @access private
	 */
	function savePreferences() {
		global $wgUser, $wgOut, $wgParser;
		global $wgEnableUserEmail, $wgEnableEmail;
		global $wgEmailAuthentication, $wgRCMaxAge;
		global $wgAuth, $wgEmailConfirmToEdit;

		$wgUser->setRealName( $this->mRealName );
		$oldOptions = $wgUser->mOptions;

		if( $wgUser->getOption( 'language' ) !== $this->mUserLanguage ) {
			$needRedirect = true;
		} else {
			$needRedirect = false;
		}

		# Validate the signature and clean it up as needed
		global $wgMaxSigChars;
		if( mb_strlen( $this->mNick ) > $wgMaxSigChars ) {
			global $wgLang;
			$this->mainPrefsForm( 'error',
				wfMsgExt( 'badsiglength', 'parsemag', $wgLang->formatNum( $wgMaxSigChars ) ) );
			return;
		} elseif( $this->mToggles['fancysig'] ) {
			if( $wgParser->validateSig( $this->mNick ) !== false ) {
				$this->mNick = $wgParser->cleanSig( $this->mNick );
			} else {
				$this->mainPrefsForm( 'error', wfMsg( 'badsig' ) );
				return;
			}
		} else {
			// When no fancy sig used, make sure ~{3,5} get removed.
			$this->mNick = $wgParser->cleanSigInSig( $this->mNick );
		}

		$wgUser->setOption( 'language', $this->mUserLanguage );
		$wgUser->setOption( 'variant', $this->mUserVariant );
		$wgUser->setOption( 'nickname', $this->mNick );
		$wgUser->setOption( 'quickbar', $this->mQuickbar );
		global $wgAllowUserSkin;
		if( $wgAllowUserSkin ) {
			$wgUser->setOption( 'skin', $this->mSkin );
		}
		global $wgUseTeX;
		if( $wgUseTeX ) {
			$wgUser->setOption( 'math', $this->mMath );
		}
		$wgUser->setOption( 'date', $this->validateDate( $this->mDate ) );
		$wgUser->setOption( 'searchlimit', $this->validateIntOrNull( $this->mSearch ) );
		$wgUser->setOption( 'contextlines', $this->validateIntOrNull( $this->mSearchLines ) );
		$wgUser->setOption( 'contextchars', $this->validateIntOrNull( $this->mSearchChars ) );
		$wgUser->setOption( 'rclimit', $this->validateIntOrNull( $this->mRecent ) );
		$wgUser->setOption( 'rcdays', $this->validateInt($this->mRecentDays, 1, ceil($wgRCMaxAge / (3600*24))));
		$wgUser->setOption( 'wllimit', $this->validateIntOrNull( $this->mWatchlistEdits, 0, 1000 ) );
		$wgUser->setOption( 'rows', $this->validateInt( $this->mRows, 4, 1000 ) );
		$wgUser->setOption( 'cols', $this->validateInt( $this->mCols, 4, 1000 ) );
		$wgUser->setOption( 'stubthreshold', $this->validateIntOrNull( $this->mStubs ) );
		$wgUser->setOption( 'timecorrection', $this->validateTimeZone( $this->mTimeZone, $this->mHourDiff ) );
		$wgUser->setOption( 'imagesize', $this->mImageSize );
		$wgUser->setOption( 'thumbsize', $this->mThumbSize );
		$wgUser->setOption( 'underline', $this->validateInt($this->mUnderline, 0, 2) );
		$wgUser->setOption( 'watchlistdays', $this->validateFloat( $this->mWatchlistDays, 0, 7 ) );
		$wgUser->setOption( 'disablesuggest', $this->mDisableMWSuggest );

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

		$error = false;
		if( $wgEnableEmail ) {
			$newadr = $this->mUserEmail;
			$oldadr = $wgUser->getEmail();
			if( ($newadr != '') && ($newadr != $oldadr) ) {
				# the user has supplied a new email address on the login page
				if( $wgUser->isValidEmailAddr( $newadr ) ) {
					# new behaviour: set this new emailaddr from login-page into user database record
					$wgUser->setEmail( $newadr );
					# but flag as "dirty" = unauthenticated
					$wgUser->invalidateEmail();
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
				if( $wgEmailConfirmToEdit && empty( $newadr ) ) {
					$this->mainPrefsForm( 'error', wfMsg( 'noemailtitle' ) );
					return;
				}
				$wgUser->setEmail( $this->mUserEmail );
			}
			if( $oldadr != $newadr ) {
				wfRunHooks( 'PrefsEmailAudit', array( $wgUser, $oldadr, $newadr ) );
			}
		}

		if( !$wgAuth->updateExternalDB( $wgUser ) ){
			$this->mainPrefsForm( 'error', wfMsg( 'externaldberror' ) );
			return;
		}

		$msg = '';
		if ( !wfRunHooks( 'SavePreferences', array( $this, $wgUser, &$msg, $oldOptions ) ) ) {
			$this->mainPrefsForm( 'error', $msg );
			return;
		}

		$wgUser->setCookies();
		$wgUser->saveSettings();

		if( $needRedirect && $error === false ) {
			$title = SpecialPage::getTitleFor( 'Preferences' );
			$wgOut->redirect( $title->getFullURL( 'success' ) );
			return;
		}

		$wgOut->parserOptions( ParserOptions::newFromUser( $wgUser ) );
		$this->mainPrefsForm( $error === false ? 'success' : 'error', $error);
	}

	/**
	 * @access private
	 */
	function resetPrefs() {
		global $wgUser, $wgLang, $wgContLang, $wgContLanguageCode, $wgAllowRealName, $wgLocalTZoffset;

		$this->mUserEmail = $wgUser->getEmail();
		$this->mUserEmailAuthenticationtimestamp = $wgUser->getEmailAuthenticationtimestamp();
		$this->mRealName = ($wgAllowRealName) ? $wgUser->getRealName() : '';

		# language value might be blank, default to content language
		$this->mUserLanguage = $wgUser->getOption( 'language', $wgContLanguageCode );

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

		$tz = $wgUser->getOption( 'timecorrection' );
		$data = explode( '|', $tz, 3 );
		$minDiff = null;
		switch ( $data[0] ) {
			case 'ZoneInfo':
				$this->mTimeZone = $tz;
				# Check if the specified TZ exists, and change to 'Offset' if 
				# not.
				if ( !function_exists('timezone_open') || @timezone_open( $data[2] ) === false ) {
					$this->mTimeZone = 'Offset';
					$minDiff = intval( $data[1] );
				}
				break;
			case '':
			case 'System':
				$this->mTimeZone = 'System|'.$wgLocalTZoffset;
				break;
			case 'Offset':
				$this->mTimeZone = 'Offset';
				$minDiff = intval( $data[1] );
				break;
			default:
				$this->mTimeZone = 'Offset';
				$data = explode( ':', $tz, 2 );
				if( count( $data ) == 2 ) {
					$data[0] = intval( $data[0] );
					$data[1] = intval( $data[1] );
					$minDiff = abs( $data[0] ) * 60 + $data[1];
					if ( $data[0] < 0 ) $minDiff = -$minDiff;
				} else {
					$minDiff = intval( $data[0] ) * 60;
				}
				break;
		}
		if ( is_null( $minDiff ) ) {
			$this->mHourDiff = '';
		} else {
			$this->mHourDiff = sprintf( '%+03d:%02d', floor($minDiff/60), abs($minDiff)%60 );
		}

		$this->mSearch = $wgUser->getOption( 'searchlimit' );
		$this->mSearchLines = $wgUser->getOption( 'contextlines' );
		$this->mSearchChars = $wgUser->getOption( 'contextchars' );
		$this->mImageSize = $wgUser->getOption( 'imagesize' );
		$this->mThumbSize = $wgUser->getOption( 'thumbsize' );
		$this->mRecent = $wgUser->getOption( 'rclimit' );
		$this->mRecentDays = $wgUser->getOption( 'rcdays' );
		$this->mWatchlistEdits = $wgUser->getOption( 'wllimit' );
		$this->mUnderline = $wgUser->getOption( 'underline' );
		$this->mWatchlistDays = $wgUser->getOption( 'watchlistdays' );
		$this->mDisableMWSuggest = $wgUser->getBoolOption( 'disablesuggest' );

		$togs = User::getToggles();
		foreach ( $togs as $tname ) {
			$this->mToggles[$tname] = $wgUser->getOption( $tname );
		}

		$namespaces = $wgContLang->getNamespaces();
		foreach ( $namespaces as $i => $namespace ) {
			if ( $i >= NS_MAIN ) {
				$this->mSearchNs[$i] = $wgUser->getOption( 'searchNs'.$i );
			}
		}

		wfRunHooks( 'ResetPreferences', array( $this, $wgUser ) );
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
		return "<tr><td class='mw-label'>$td1</td><td class='mw-input'>$td2</td></tr>";
	}

	/**
	 * Helper function for user information panel
	 * @param $td1 label for an item
	 * @param $td2 item or null
	 * @param $td3 optional help or null
	 * @return xhtml block
	 */
	function tableRow( $td1, $td2 = null, $td3 = null ) {

		if ( is_null( $td3 ) ) {
			$td3 = '';
		} else {
			$td3 = Xml::tags( 'tr', null,
				Xml::tags( 'td', array( 'class' => 'pref-label', 'colspan' => '2' ), $td3 )
			);
		}

		if ( is_null( $td2 ) ) {
			$td1 = Xml::tags( 'td', array( 'class' => 'pref-label', 'colspan' => '2' ), $td1 );
			$td2 = '';
		} else {
			$td1 = Xml::tags( 'td', array( 'class' => 'pref-label' ), $td1 );
			$td2 = Xml::tags( 'td', array( 'class' => 'pref-input' ), $td2 );
		}

		return Xml::tags( 'tr', null, $td1 . $td2 ). $td3 . "\n";

	}

	/**
	 * @access private
	 */
	function mainPrefsForm( $status , $message = '' ) {
		global $wgUser, $wgOut, $wgLang, $wgContLang, $wgAuth;
		global $wgAllowRealName, $wgImageLimits, $wgThumbLimits;
		global $wgDisableLangConversion, $wgDisableTitleConversion;
		global $wgEnotifWatchlist, $wgEnotifUserTalk,$wgEnotifMinorEdits;
		global $wgRCShowWatchingUsers, $wgEnotifRevealEditorAddress;
		global $wgEnableEmail, $wgEnableUserEmail, $wgEmailAuthentication;
		global $wgContLanguageCode, $wgDefaultSkin, $wgCookieExpiration;
		global $wgEmailConfirmToEdit, $wgEnableMWSuggest, $wgLocalTZoffset;

		$wgOut->setPageTitle( wfMsg( 'preferences' ) );
		$wgOut->setArticleRelated( false );
		$wgOut->setRobotPolicy( 'noindex,nofollow' );
		$wgOut->addScriptFile( 'prefs.js' );

		$wgOut->disallowUserJs();  # Prevent hijacked user scripts from sniffing passwords etc.

		if ( $this->mSuccess || 'success' == $status ) {
			$wgOut->wrapWikiMsg( '<div class="successbox"><strong>$1</strong></div>', 'savedprefs' );
		} else	if ( 'error' == $status ) {
			$wgOut->addWikiText( '<div class="errorbox"><strong>' . $message  . '</strong></div>' );
		} else if ( '' != $status ) {
			$wgOut->addWikiText( $message . "\n----" );
		}

		$qbs = $wgLang->getQuickbarSettings();
		$mathopts = $wgLang->getMathNames();
		$dateopts = $wgLang->getDatePreferences();
		$togs = User::getToggles();

		$titleObj = SpecialPage::getTitleFor( 'Preferences' );

		# Pre-expire some toggles so they won't show if disabled
		$this->mUsedToggles[ 'shownumberswatching' ] = true;
		$this->mUsedToggles[ 'showupdated' ] = true;
		$this->mUsedToggles[ 'enotifwatchlistpages' ] = true;
		$this->mUsedToggles[ 'enotifusertalkpages' ] = true;
		$this->mUsedToggles[ 'enotifminoredits' ] = true;
		$this->mUsedToggles[ 'enotifrevealaddr' ] = true;
		$this->mUsedToggles[ 'ccmeonemails' ] = true;
		$this->mUsedToggles[ 'uselivepreview' ] = true;
		$this->mUsedToggles[ 'noconvertlink' ] = true;


		if ( !$this->mEmailFlag ) { $emfc = 'checked="checked"'; }
		else { $emfc = ''; }


		if ($wgEmailAuthentication && ($this->mUserEmail != '') ) {
			if( $wgUser->getEmailAuthenticationTimestamp() ) {
				// date and time are separate parameters to facilitate localisation.
				// $time is kept for backward compat reasons.
				// 'emailauthenticated' is also used in SpecialConfirmemail.php
				$time = $wgLang->timeAndDate( $wgUser->getEmailAuthenticationTimestamp(), true );
				$d = $wgLang->date( $wgUser->getEmailAuthenticationTimestamp(), true );
				$t = $wgLang->time( $wgUser->getEmailAuthenticationTimestamp(), true );
				$emailauthenticated = wfMsg('emailauthenticated', $time, $d, $t ).'<br />';
				$disableEmailPrefs = false;
			} else {
				$disableEmailPrefs = true;
				$skin = $wgUser->getSkin();
				$emailauthenticated = wfMsg('emailnotauthenticated').'<br />' .
					$skin->makeKnownLinkObj( SpecialPage::getTitleFor( 'Confirmemail' ),
						wfMsg( 'emailconfirmlink' ) ) . '<br />';
			}
		} else {
			$emailauthenticated = '';
			$disableEmailPrefs = false;
		}

		if ($this->mUserEmail == '') {
			$emailauthenticated = wfMsg( 'noemailprefs' ) . '<br />';
		}

		$ps = $this->namespacesCheckboxes();

		$enotifwatchlistpages = ($wgEnotifWatchlist) ? $this->getToggle( 'enotifwatchlistpages', false, $disableEmailPrefs ) : '';
		$enotifusertalkpages = ($wgEnotifUserTalk) ? $this->getToggle( 'enotifusertalkpages', false, $disableEmailPrefs ) : '';
		$enotifminoredits = ($wgEnotifWatchlist && $wgEnotifMinorEdits) ? $this->getToggle( 'enotifminoredits', false, $disableEmailPrefs ) : '';
		$enotifrevealaddr = (($wgEnotifWatchlist || $wgEnotifUserTalk) && $wgEnotifRevealEditorAddress) ? $this->getToggle( 'enotifrevealaddr', false, $disableEmailPrefs ) : '';

		# </FIXME>

		$wgOut->addHTML(
			Xml::openElement( 'form', array(
				'action' => $titleObj->getLocalUrl(),
				'method' => 'post',
				'id'     => 'mw-preferences-form',
			) ) .
			Xml::openElement( 'div', array( 'id' => 'preferences' ) )
		);

		# User data

		$wgOut->addHTML(
			Xml::fieldset( wfMsg('prefs-personal') ) .
			Xml::openElement( 'table' ) .
			$this->tableRow( Xml::element( 'h2', null, wfMsg( 'prefs-personal' ) ) )
		);

		# Get groups to which the user belongs
		$userEffectiveGroups = $wgUser->getEffectiveGroups();
		$userEffectiveGroupsArray = array();
		foreach( $userEffectiveGroups as $ueg ) {
			if( $ueg == '*' ) {
				// Skip the default * group, seems useless here
				continue;
			}
			$userEffectiveGroupsArray[] = User::makeGroupLinkHTML( $ueg );
		}
		asort( $userEffectiveGroupsArray );

		$sk = $wgUser->getSkin();
		$toolLinks = array();
		$toolLinks[] = $sk->makeKnownLinkObj( SpecialPage::getTitleFor( 'ListGroupRights' ), wfMsg( 'listgrouprights' ) );
		# At the moment one tool link only but be prepared for the future...
		# FIXME: Add a link to Special:Userrights for users who are allowed to use it.
		# $wgUser->isAllowed( 'userrights' ) seems to strict in some cases

		$userInformationHtml =
			$this->tableRow( wfMsgHtml( 'username' ), htmlspecialchars( $wgUser->getName() ) ) .
			$this->tableRow( wfMsgHtml( 'uid' ), $wgLang->formatNum( htmlspecialchars( $wgUser->getId() ) ) ).

			$this->tableRow(
				wfMsgExt( 'prefs-memberingroups', array( 'parseinline' ), count( $userEffectiveGroupsArray ) ),
				$wgLang->commaList( $userEffectiveGroupsArray ) .
				'<br />(' . implode( ' | ', $toolLinks ) . ')'
			) .

			$this->tableRow(
				wfMsgHtml( 'prefs-edits' ),
				$wgLang->formatNum( $wgUser->getEditCount() )
			);

		if( wfRunHooks( 'PreferencesUserInformationPanel', array( $this, &$userInformationHtml ) ) ) {
			$wgOut->addHTML( $userInformationHtml );
		}

		if ( $wgAllowRealName ) {
			$wgOut->addHTML(
				$this->tableRow(
					Xml::label( wfMsg('yourrealname'), 'wpRealName' ),
					Xml::input( 'wpRealName', 25, $this->mRealName, array( 'id' => 'wpRealName' ) ),
					Xml::tags('div', array( 'class' => 'prefsectiontip' ),
						wfMsgExt( 'prefs-help-realname', 'parseinline' )
					)
				)
			);
		}
		if ( $wgEnableEmail ) {
			$wgOut->addHTML(
				$this->tableRow(
					Xml::label( wfMsg('youremail'), 'wpUserEmail' ),
					Xml::input( 'wpUserEmail', 25, $this->mUserEmail, array( 'id' => 'wpUserEmail' ) ),
					Xml::tags('div', array( 'class' => 'prefsectiontip' ),
						wfMsgExt( $wgEmailConfirmToEdit ? 'prefs-help-email-required' : 'prefs-help-email', 'parseinline' )
					)
				)
			);
		}

		global $wgParser, $wgMaxSigChars;
		if( mb_strlen( $this->mNick ) > $wgMaxSigChars ) {
			$invalidSig = $this->tableRow(
				'&nbsp;',
				Xml::element( 'span', array( 'class' => 'error' ),
					wfMsgExt( 'badsiglength', 'parsemag', $wgLang->formatNum( $wgMaxSigChars ) ) )
			);
		} elseif( !empty( $this->mToggles['fancysig'] ) &&
			false === $wgParser->validateSig( $this->mNick ) ) {
			$invalidSig = $this->tableRow(
				'&nbsp;',
				Xml::element( 'span', array( 'class' => 'error' ), wfMsg( 'badsig' ) )
			);
		} else {
			$invalidSig = '';
		}

		$wgOut->addHTML(
			$this->tableRow(
				Xml::label( wfMsg( 'yournick' ), 'wpNick' ),
				Xml::input( 'wpNick', 25, $this->mNick,
					array(
						'id' => 'wpNick',
						// Note: $wgMaxSigChars is enforced in Unicode characters,
						// both on the backend and now in the browser.
						// Badly-behaved requests may still try to submit
						// an overlong string, however.
						'maxlength' => $wgMaxSigChars ) )
			) .
			$invalidSig .
			$this->tableRow( '&nbsp;', $this->getToggle( 'fancysig' ) )
		);

		list( $lsLabel, $lsSelect) = Xml::languageSelector( $this->mUserLanguage );
		$wgOut->addHTML(
			$this->tableRow( $lsLabel, $lsSelect )
		);

		/* see if there are multiple language variants to choose from*/
		if(!$wgDisableLangConversion) {
			$variants = $wgContLang->getVariants();
			$variantArray = array();

			$languages = Language::getLanguageNames( true );
			foreach($variants as $v) {
				$v = str_replace( '_', '-', strtolower($v));
				if( array_key_exists( $v, $languages ) ) {
					// If it doesn't have a name, we'll pretend it doesn't exist
					$variantArray[$v] = $languages[$v];
				}
			}

			$options = "\n";
			foreach( $variantArray as $code => $name ) {
				$selected = ($code == $this->mUserVariant);
				$options .= Xml::option( "$code - $name", $code, $selected ) . "\n";
			}

			if(count($variantArray) > 1) {
				$wgOut->addHTML(
					$this->tableRow(
						Xml::label( wfMsg( 'yourvariant' ), 'wpUserVariant' ),
						Xml::tags( 'select',
							array( 'name' => 'wpUserVariant', 'id' => 'wpUserVariant' ),
							$options
						)
					)
				);
			}

			if(count($variantArray) > 1 && !$wgDisableLangConversion && !$wgDisableTitleConversion) {
				$wgOut->addHTML(
					Xml::tags( 'tr', null,
						Xml::tags( 'td', array( 'colspan' => '2' ),
							$this->getToggle( "noconvertlink" )
						)
					)
				);
			}
		}

		# Password
		if( $wgAuth->allowPasswordChange() ) {
			$link = $wgUser->getSkin()->link( SpecialPage::getTitleFor( 'ResetPass' ), wfMsgHtml( 'prefs-resetpass' ),
				array() , array('returnto' => SpecialPage::getTitleFor( 'Preferences') ) );
			$wgOut->addHTML(
				$this->tableRow( Xml::element( 'h2', null, wfMsg( 'changepassword' ) ) ) .
				$this->tableRow( '<ul><li>' . $link . '</li></ul>' ) );
		}

		# <FIXME>
		# Enotif
		if ( $wgEnableEmail ) {

			$moreEmail = '';
			if ($wgEnableUserEmail) {
				// fixme -- the "allowemail" pseudotoggle is a hacked-together
				// inversion for the "disableemail" preference.
				$emf = wfMsg( 'allowemail' );
				$disabled = $disableEmailPrefs ? ' disabled="disabled"' : '';
				$moreEmail =
					"<input type='checkbox' $emfc $disabled value='1' name='wpEmailFlag' id='wpEmailFlag' /> <label for='wpEmailFlag'>$emf</label>" .
					$this->getToggle( 'ccmeonemails', '', $disableEmailPrefs );
			}


			$wgOut->addHTML(
				$this->tableRow( Xml::element( 'h2', null, wfMsg( 'email' ) ) ) .
				$this->tableRow(
					$emailauthenticated.
					$enotifrevealaddr.
					$enotifwatchlistpages.
					$enotifusertalkpages.
					$enotifminoredits.
					$moreEmail
				)
			);
		}
		# </FIXME>

		$wgOut->addHTML(
			Xml::closeElement( 'table' ) .
			Xml::closeElement( 'fieldset' )
		);


		# Quickbar
		#
		if ($this->mSkin == 'cologneblue' || $this->mSkin == 'standard') {
			$wgOut->addHTML( "<fieldset>\n<legend>" . wfMsg( 'qbsettings' ) . "</legend>\n" );
			for ( $i = 0; $i < count( $qbs ); ++$i ) {
				if ( $i == $this->mQuickbar ) { $checked = ' checked="checked"'; }
				else { $checked = ""; }
				$wgOut->addHTML( "<div><label><input type='radio' name='wpQuickbar' value=\"$i\"$checked />{$qbs[$i]}</label></div>\n" );
			}
			$wgOut->addHTML( "</fieldset>\n\n" );
		} else {
			# Need to output a hidden option even if the relevant skin is not in use,
			# otherwise the preference will get reset to 0 on submit
			$wgOut->addHTML( Xml::hidden( 'wpQuickbar', $this->mQuickbar ) );
		}

		# Skin
		#
		global $wgAllowUserSkin;
		if( $wgAllowUserSkin ) {
			$wgOut->addHTML( "<fieldset>\n<legend>\n" . wfMsg( 'skin' ) . "</legend>\n" );
			$mptitle = Title::newMainPage();
			$previewtext = wfMsg( 'skin-preview' );
			# Only show members of Skin::getSkinNames() rather than
			# $skinNames (skins is all skin names from Language.php)
			$validSkinNames = Skin::getUsableSkins();
			# Sort by UI skin name. First though need to update validSkinNames as sometimes
			# the skinkey & UI skinname differ (e.g. "standard" skinkey is "Classic" in the UI).
			foreach ( $validSkinNames as $skinkey => &$skinname ) {
				$msgName = "skinname-{$skinkey}";
				$localisedSkinName = wfMsg( $msgName );
				if ( !wfEmptyMsg( $msgName, $localisedSkinName ) )  {
					$skinname = $localisedSkinName;
				}
			}
			asort($validSkinNames);
			foreach ($validSkinNames as $skinkey => $sn ) {
				$checked = $skinkey == $this->mSkin ? ' checked="checked"' : '';
				$mplink = htmlspecialchars( $mptitle->getLocalURL( "useskin=$skinkey" ) );
				$previewlink = "(<a target='_blank' href=\"$mplink\">$previewtext</a>)";
				if( $skinkey == $wgDefaultSkin )
					$sn .= ' (' . wfMsg( 'default' ) . ')';
				$wgOut->addHTML( "<input type='radio' name='wpSkin' id=\"wpSkin$skinkey\" value=\"$skinkey\"$checked /> <label for=\"wpSkin$skinkey\">{$sn}</label> $previewlink<br />\n" );
			}
			$wgOut->addHTML( "</fieldset>\n\n" );
		}

		# Math
		#
		global $wgUseTeX;
		if( $wgUseTeX ) {
			$wgOut->addHTML( "<fieldset>\n<legend>" . wfMsg('math') . '</legend>' );
			foreach ( $mathopts as $k => $v ) {
				$checked = ($k == $this->mMath);
				$wgOut->addHTML(
					Xml::openElement( 'div' ) .
					Xml::radioLabel( wfMsg( $v ), 'wpMath', $k, "mw-sp-math-$k", $checked ) .
					Xml::closeElement( 'div' ) . "\n"
				);
			}
			$wgOut->addHTML( "</fieldset>\n\n" );
		}

		# Files
		#
		$imageLimitOptions = null;
		foreach ( $wgImageLimits as $index => $limits ) {
			$selected = ($index == $this->mImageSize);
			$imageLimitOptions .= Xml::option( "{$limits[0]}×{$limits[1]}" .
				wfMsg('unit-pixel'), $index, $selected );
		}

		$imageThumbOptions = null;
		foreach ( $wgThumbLimits as $index => $size ) {
			$selected = ($index == $this->mThumbSize);
			$imageThumbOptions .= Xml::option($size . wfMsg('unit-pixel'), $index,
				$selected);
		}

		$imageSizeId = 'wpImageSize';
		$thumbSizeId = 'wpThumbSize';
		$wgOut->addHTML(
			Xml::fieldset( wfMsg( 'files' ) ) . "\n" .
			Xml::openElement( 'table' ) .
				'<tr>
					<td class="mw-label">' .
						Xml::label( wfMsg( 'imagemaxsize' ), $imageSizeId ) .
					'</td>
					<td class="mw-input">' .
						Xml::openElement( 'select', array( 'name' => $imageSizeId, 'id' => $imageSizeId ) ) .
						$imageLimitOptions .
						Xml::closeElement( 'select' ) .
					'</td>
				</tr><tr>
					<td class="mw-label">' .
						Xml::label( wfMsg( 'thumbsize' ), $thumbSizeId ) .
					'</td>
					<td class="mw-input">' .
						Xml::openElement( 'select', array( 'name' => $thumbSizeId, 'id' => $thumbSizeId ) ) .
						$imageThumbOptions .
						Xml::closeElement( 'select' ) .
					'</td>
				</tr>' .
 			Xml::closeElement( 'table' ) .
 			Xml::closeElement( 'fieldset' )
		);

		# Date format
		#
		# Date/Time
		#

		$wgOut->addHTML(
			Xml::openElement( 'fieldset' ) .
			Xml::element( 'legend', null, wfMsg( 'datetime' ) ) . "\n"
		);

		if ($dateopts) {
			$wgOut->addHTML(
				Xml::openElement( 'fieldset' ) .
				Xml::element( 'legend', null, wfMsg( 'dateformat' ) ) . "\n"
			);
			$idCnt = 0;
			$epoch = '20010115161234'; # Wikipedia day
			foreach( $dateopts as $key ) {
				if( $key == 'default' ) {
					$formatted = wfMsg( 'datedefault' );
				} else {
					$formatted = $wgLang->timeanddate( $epoch, false, $key );
				}
				$wgOut->addHTML(
					Xml::tags( 'div', null,
						Xml::radioLabel( $formatted, 'wpDate', $key, "wpDate$idCnt", $key == $this->mDate )
					) . "\n"
				);
				$idCnt++;
			}
			$wgOut->addHTML( Xml::closeElement( 'fieldset' ) . "\n" );
		}

		$nowlocal = Xml::openElement( 'span', array( 'id' => 'wpLocalTime' ) ) .
			$wgLang->time( $now = wfTimestampNow(), true ) .
			Xml::closeElement( 'span' );
		$nowserver = $wgLang->time( $now, false ) .
			Xml::hidden( 'wpServerTime', substr( $now, 8, 2 ) * 60 + substr( $now, 10, 2 ) );

		$wgOut->addHTML(
			Xml::openElement( 'fieldset' ) .
			Xml::element( 'legend', null, wfMsg( 'timezonelegend' ) ) .
			Xml::openElement( 'table' ) .
		 	$this->addRow( wfMsg( 'servertime' ), $nowserver ) .
			$this->addRow( wfMsg( 'localtime' ), $nowlocal )
		);
		$opt = Xml::openElement( 'select', array(
			'name' => 'wpTimeZone',
			'id' => 'wpTimeZone',
			'onchange' => 'javascript:updateTimezoneSelection(false)' ) );
		$opt .= Xml::option( wfMsg( 'timezoneuseserverdefault' ), "System|$wgLocalTZoffset", $this->mTimeZone === "System|$wgLocalTZoffset" );
		$opt .= Xml::option( wfMsg( 'timezoneuseoffset' ), 'Offset', $this->mTimeZone === 'Offset' );
		if ( function_exists( 'timezone_identifiers_list' ) ) {
			$optgroup = '';
			$tzs = timezone_identifiers_list();
			sort( $tzs );
			$selZone = explode( '|', $this->mTimeZone, 3);
			$selZone = ( $selZone[0] == 'ZoneInfo' ) ? $selZone[2] : null;
			$now = date_create( 'now' );
			foreach ( $tzs as $tz ) {
				$z = explode( '/', $tz, 2 );
				# timezone_identifiers_list() returns a number of
				# backwards-compatibility entries. This filters them out of the 
				# list presented to the user.
				if ( count( $z ) != 2 || !in_array( $z[0], array( 'Africa', 'America', 'Antarctica', 'Arctic', 'Asia', 'Atlantic', 'Australia', 'Europe', 'Indian', 'Pacific' ) ) ) continue;
				if ( $optgroup != $z[0] ) {
					if ( $optgroup !== '' ) $opt .= Xml::closeElement( 'optgroup' );
					$optgroup = $z[0];
					$opt .= Xml::openElement( 'optgroup', array( 'label' => $z[0] ) );
				}
				$minDiff = floor( timezone_offset_get( timezone_open( $tz ), $now ) / 60 );
				$opt .= Xml::option( str_replace( '_', ' ', $tz ), "ZoneInfo|$minDiff|$tz", $selZone === $tz, array( 'label' => $z[1] ) );
			}
			if ( $optgroup !== '' ) $opt .= Xml::closeElement( 'optgroup' );
		}
		$opt .= Xml::closeElement( 'select' );
		$wgOut->addHTML(
			$this->addRow(
				Xml::label( wfMsg( 'timezoneselect' ), 'wpTimeZone' ),
				$opt )
		);
		$wgOut->addHTML(
			$this->addRow(
				Xml::label( wfMsg( 'timezoneoffset' ), 'wpHourDiff'  ),
				Xml::input( 'wpHourDiff', 6, $this->mHourDiff, array(
					'id' => 'wpHourDiff',
					'onfocus' => 'javascript:updateTimezoneSelection(true)',
					'onblur' => 'javascript:updateTimezoneSelection(false)' ) ) ) .
			"<tr>
				<td></td>
				<td class='mw-submit'>" .
					Xml::element( 'input',
						array( 'type' => 'button',
							'value' => wfMsg( 'guesstimezone' ),
							'onclick' => 'javascript:guessTimezone()',
							'id' => 'guesstimezonebutton',
							'style' => 'display:none;' ) ) .
				"</td>
			</tr>" .
			Xml::closeElement( 'table' ) .
			Xml::tags( 'div', array( 'class' => 'prefsectiontip' ), wfMsgExt( 'timezonetext', 'parseinline' ) ).
			Xml::closeElement( 'fieldset' ) .
			Xml::closeElement( 'fieldset' ) . "\n\n"
		);

		# Editing
		#
		global $wgLivePreview;
		$wgOut->addHTML(
			Xml::fieldset( wfMsg( 'textboxsize' ) ) .
			wfMsgHTML( 'prefs-edit-boxsize' ) . ' ' .
			Xml::inputLabel( wfMsg( 'rows' ), 'wpRows', 'wpRows', 3, $this->mRows ) . ' ' .
			Xml::inputLabel( wfMsg( 'columns' ), 'wpCols', 'wpCols', 3, $this->mCols ) .
			$this->getToggles( array(
				'editsection',
				'editsectiononrightclick',
				'editondblclick',
				'editwidth',
				'showtoolbar',
				'previewonfirst',
				'previewontop',
				'minordefault',
				'externaleditor',
				'externaldiff',
				$wgLivePreview ? 'uselivepreview' : false,
				'forceeditsummary',
			) )
		);

		$wgOut->addHTML( Xml::closeElement( 'fieldset' ) );

		# Recent changes
		global $wgRCMaxAge;
		$wgOut->addHTML(
			Xml::fieldset( wfMsg( 'prefs-rc' ) ) .
 			Xml::openElement( 'table' ) .
				'<tr>
					<td class="mw-label">' .
						Xml::label( wfMsg( 'recentchangesdays' ), 'wpRecentDays' ) .
					'</td>
					<td class="mw-input">' .
						Xml::input( 'wpRecentDays', 3, $this->mRecentDays, array( 'id' => 'wpRecentDays' ) ) . ' ' .
						wfMsgExt( 'recentchangesdays-max', 'parsemag',
							$wgLang->formatNum( ceil( $wgRCMaxAge / ( 3600 * 24 ) ) ) ) .
					'</td>
				</tr><tr>
					<td class="mw-label">' .
						Xml::label( wfMsg( 'recentchangescount' ), 'wpRecent' ) .
					'</td>
					<td class="mw-input">' .
						Xml::input( 'wpRecent', 3, $this->mRecent, array( 'id' => 'wpRecent' ) ) .
					'</td>
				</tr>' .
 			Xml::closeElement( 'table' ) .
			'<br />'
		);

		$toggles[] = 'hideminor';
		if( $wgRCShowWatchingUsers )
			$toggles[] = 'shownumberswatching';
		$toggles[] = 'usenewrc';

		$wgOut->addHTML(
			$this->getToggles( $toggles ) .
			Xml::closeElement( 'fieldset' )
		);

		# Watchlist
		$wgOut->addHTML( 
			Xml::fieldset( wfMsg( 'prefs-watchlist' ) ) .
			Xml::inputLabel( wfMsg( 'prefs-watchlist-days' ), 'wpWatchlistDays', 'wpWatchlistDays', 3, $this->mWatchlistDays ) . ' ' .
			wfMsgHTML( 'prefs-watchlist-days-max' ) .
			'<br /><br />' .
			$this->getToggle( 'extendwatchlist' ) .
			Xml::inputLabel( wfMsg( 'prefs-watchlist-edits' ), 'wpWatchlistEdits', 'wpWatchlistEdits', 3, $this->mWatchlistEdits ) . ' ' .
			wfMsgHTML( 'prefs-watchlist-edits-max' ) .
			'<br /><br />' .
			$this->getToggles( array( 'watchlisthideminor', 'watchlisthidebots', 'watchlisthideown', 'watchlisthideanons', 'watchlisthideliu' ) )
		);

		if( $wgUser->isAllowed( 'createpage' ) || $wgUser->isAllowed( 'createtalk' ) ) {
			$wgOut->addHTML( $this->getToggle( 'watchcreations' ) );
		}

		foreach( array( 'edit' => 'watchdefault', 'move' => 'watchmoves', 'delete' => 'watchdeletion' ) as $action => $toggle ) {
			if( $wgUser->isAllowed( $action ) )
				$wgOut->addHTML( $this->getToggle( $toggle ) );
		}
		$this->mUsedToggles['watchcreations'] = true;
		$this->mUsedToggles['watchdefault'] = true;
		$this->mUsedToggles['watchmoves'] = true;
		$this->mUsedToggles['watchdeletion'] = true;

		$wgOut->addHTML( Xml::closeElement( 'fieldset' ) );

		# Search
		$mwsuggest = $wgEnableMWSuggest ?
			$this->addRow(
				Xml::label( wfMsg( 'mwsuggest-disable' ), 'wpDisableMWSuggest' ),
				Xml::check( 'wpDisableMWSuggest', $this->mDisableMWSuggest, array( 'id' => 'wpDisableMWSuggest' ) )
			) : '';
		$wgOut->addHTML(
			// Elements for the search tab itself
			Xml::openElement( 'fieldset' ) .
			Xml::element( 'legend', null, wfMsg( 'searchresultshead' ) ) .
			// Elements for the search options in the search tab
			Xml::openElement( 'fieldset' ) .
			Xml::element( 'legend', null, wfMsg( 'prefs-searchoptions' ) ) .
			Xml::openElement( 'table' ) .
			$this->addRow(
				Xml::label( wfMsg( 'resultsperpage' ), 'wpSearch' ),
				Xml::input( 'wpSearch', 4, $this->mSearch, array( 'id' => 'wpSearch' ) )
			) .
			$this->addRow(
				Xml::label( wfMsg( 'contextlines' ), 'wpSearchLines' ),
				Xml::input( 'wpSearchLines', 4, $this->mSearchLines, array( 'id' => 'wpSearchLines' ) )
			) .
			$this->addRow(
				Xml::label( wfMsg( 'contextchars' ), 'wpSearchChars' ),
				Xml::input( 'wpSearchChars', 4, $this->mSearchChars, array( 'id' => 'wpSearchChars' ) )
			) .
			$mwsuggest .
			Xml::closeElement( 'table' ) .
			Xml::closeElement( 'fieldset' ) .
			// Elements for the namespace options in the search tab
			Xml::openElement( 'fieldset' ) .
			Xml::element( 'legend', null, wfMsg( 'prefs-namespaces' ) ) .
			wfMsgExt( 'defaultns', array( 'parse' ) ) .
			$ps .
			Xml::closeElement( 'fieldset' ) .
			// End of the search tab
			Xml::closeElement( 'fieldset' )
		);

		# Misc
		#
		$wgOut->addHTML('<fieldset><legend>' . wfMsg('prefs-misc') . '</legend>');
		$wgOut->addHTML( '<label for="wpStubs">' . wfMsg( 'stub-threshold' ) . '</label>&nbsp;' );
		$wgOut->addHTML( Xml::input( 'wpStubs', 6, $this->mStubs, array( 'id' => 'wpStubs' ) ) );
		$msgUnderline = htmlspecialchars( wfMsg ( 'tog-underline' ) );
		$msgUnderlinenever = htmlspecialchars( wfMsg ( 'underline-never' ) );
		$msgUnderlinealways = htmlspecialchars( wfMsg ( 'underline-always' ) );
		$msgUnderlinedefault = htmlspecialchars( wfMsg ( 'underline-default' ) );
		$uopt = $wgUser->getOption("underline");
		$s0 = $uopt == 0 ? ' selected="selected"' : '';
		$s1 = $uopt == 1 ? ' selected="selected"' : '';
		$s2 = $uopt == 2 ? ' selected="selected"' : '';
		$wgOut->addHTML("
<div class='toggle'><p><label for='wpOpunderline'>$msgUnderline</label>
<select name='wpOpunderline' id='wpOpunderline'>
<option value=\"0\"$s0>$msgUnderlinenever</option>
<option value=\"1\"$s1>$msgUnderlinealways</option>
<option value=\"2\"$s2>$msgUnderlinedefault</option>
</select></p></div>");

		foreach ( $togs as $tname ) {
			if( !array_key_exists( $tname, $this->mUsedToggles ) ) {
				if( $tname == 'norollbackdiff' && $wgUser->isAllowed( 'rollback' ) )
					$wgOut->addHTML( $this->getToggle( $tname ) );
				else
					$wgOut->addHTML( $this->getToggle( $tname ) );
			}
		}

		$wgOut->addHTML( '</fieldset>' );

		wfRunHooks( 'RenderPreferencesForm', array( $this, $wgOut ) );

		$token = htmlspecialchars( $wgUser->editToken() );
		$skin = $wgUser->getSkin();
		$wgOut->addHTML( "
	<div id='prefsubmit'>
	<div>
		<input type='submit' name='wpSaveprefs' class='btnSavePrefs' value=\"" . wfMsgHtml( 'saveprefs' ) . '"'.$skin->tooltipAndAccesskey('save')." />
		<input type='submit' name='wpReset' value=\"" . wfMsgHtml( 'resetprefs' ) . "\" />
	</div>

	</div>

	<input type='hidden' name='wpEditToken' value=\"{$token}\" />
	</div></form>\n" );

		$wgOut->addHTML( Xml::tags( 'div', array( 'class' => "prefcache" ),
			wfMsgExt( 'clearyourcache', 'parseinline' ) )
		);
	}
}
