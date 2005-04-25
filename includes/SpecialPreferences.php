<?php
/**
 * Hold things related to displaying and saving user preferences.
 * @package MediaWiki
 * @subpackage SpecialPage
 */

if( !defined( 'MEDIAWIKI' ) )
	die();

/** to get a list of languages in setting user's language preference */
require_once('languages/Names.php');

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
		$this->mEmailFlag = $request->getCheck( 'wpEmailFlag' ) ? 1 : 0;
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
		$this->mAction = $request->getVal( 'action' );
		$this->mReset = $request->getCheck( 'wpReset' );
		$this->mPosted = $request->wasPosted();
		$this->mSaveprefs = $request->getCheck( 'wpSaveprefs' ) &&
			$this->mPosted &&
			$wgUser->matchEditToken( $request->getVal( 'wpEditToken' ) );

		# User toggles  (the big ugly unsorted list of checkboxes)
		$this->mToggles = array();
		if ( $this->mPosted ) {
			$togs = $wgLang->getUserToggles();
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
			$wgOut->errorpage( 'prefsnologin', 'prefsnologintext' );
			return;
		}
		if ( wfReadOnly() ) {
			$wgOut->readOnlyPage();
			return;
		}
		if ( $this->mReset ) {
			$this->resetPrefs();
			$this->mainPrefsForm( wfMsg( 'prefsreset' ) );
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
	function validateIntOrNull( &$val, $min=0, $max=0x7fffffff ) {
		$val = trim($val);
		if($val === '') {
			return $val;
		} else {
			return $this->validateInt( $val, $min, $max );
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
		global $wgUser, $wgLang, $wgOut;
		global $wgEnableUserEmail, $wgEnableEmail;
		global $wgEmailAuthentication, $wgMinimalPasswordLength;

		if ( '' != $this->mNewpass ) {
			if ( $this->mNewpass != $this->mRetypePass ) {
				$this->mainPrefsForm( wfMsg( 'badretype' ) );			
				return;
			}

			if ( strlen( $this->mNewpass ) < $wgMinimalPasswordLength ) {
				$this->mainPrefsForm( wfMsg( 'passwordtooshort', $wgMinimalPasswordLength ) );
				return;
			}

			if (!$wgUser->checkPassword( $this->mOldpass )) {
				$this->mainPrefsForm( wfMsg( 'wrongpassword' ) );
				return;
			}
			$wgUser->setPassword( $this->mNewpass );
		}
		$wgUser->setRealName( $this->mRealName );
		$wgUser->setOption( 'language', $this->mUserLanguage );
		$wgUser->setOption( 'variant', $this->mUserVariant );
		$wgUser->setOption( 'nickname', $this->mNick );
		$wgUser->setOption( 'quickbar', $this->mQuickbar );
		$wgUser->setOption( 'skin', $this->mSkin );
		$wgUser->setOption( 'math', $this->mMath );
		$wgUser->setOption( 'date', $this->mDate );
		$wgUser->setOption( 'searchlimit', $this->validateIntOrNull( $this->mSearch ) );
		$wgUser->setOption( 'contextlines', $this->validateIntOrNull( $this->mSearchLines ) );
		$wgUser->setOption( 'contextchars', $this->validateIntOrNull( $this->mSearchChars ) );
		$wgUser->setOption( 'rclimit', $this->validateIntOrNull( $this->mRecent ) );
		$wgUser->setOption( 'rows', $this->validateInt( $this->mRows, 4, 1000 ) );
		$wgUser->setOption( 'cols', $this->validateInt( $this->mCols, 4, 1000 ) );
		$wgUser->setOption( 'stubthreshold', $this->validateIntOrNull( $this->mStubs ) );
		$wgUser->setOption( 'timecorrection', $this->validateTimeZone( $this->mHourDiff, -12, 14 ) );
		$wgUser->setOption( 'imagesize', $this->mImageSize );
		$wgUser->setOption( 'thumbsize', $this->mThumbSize );

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
		$wgUser->setCookies();
		$wgUser->saveSettings();
		
		if( $wgEnableEmail ) {
			$newadr = strtolower( $this->mUserEmail );
			$oldadr = strtolower($wgUser->getEmail());
			if (($newadr <> '') && ($newadr <> $oldadr)) { # the user has supplied a new email address on the login page
				# prepare for authentication and mail a temporary password to newadr
				require_once( 'SpecialUserlogin.php' );
				if ( !$wgUser->isValidEmailAddr( $newadr ) ) {
					$this->mainPrefsForm( wfMsg( 'invalidemailaddress' ) );
					return;
				}
				$wgUser->mEmail = $newadr; # new behaviour: set this new emailaddr from login-page into user database record
				$wgUser->mEmailAuthenticationtimestamp = 0; # but flag as "dirty" = unauthenticated
				$wgUser->saveSettings();
				if ($wgEmailAuthentication) {
					# mail a temporary password to the dirty address
					# on "save options", this user will be logged-out automatically
					$error = LoginForm::mailPasswordInternal( $wgUser, true, $dummy );
					if ($error === '') {
						return LoginForm::mainLoginForm( wfMsg( 'passwordsentforemailauthentication', $wgUser->getName() ) );
					} else {
						return LoginForm::mainLoginForm( wfMsg( 'mailerror', $error ) );
					}
					# if user returns, that new email address gets authenticated in checkpassword()
				}
			} else {
				$wgUser->setEmail( strtolower($this->mUserEmail) );
				$wgUser->setCookies();
				$wgUser->saveSettings();
			}
		}

		$wgOut->setParserOptions( ParserOptions::newFromUser( $wgUser ) );
		$po = ParserOptions::newFromUser( $wgUser );
		$this->mainPrefsForm( wfMsg( 'savedprefs' ) );
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
		$this->mSkin = $wgUser->getOption( 'skin' );
		$this->mMath = $wgUser->getOption( 'math' );
		$this->mDate = $wgUser->getOption( 'date' );
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

		$togs = $wgLang->getUserToggles();
		foreach ( $togs as $tname ) {
			$ttext = wfMsg('tog-'.$tname);
			$this->mToggles[$tname] = $wgUser->getOption( $tname );
		}

		$namespaces = $wgContLang->getNamespaces();
		foreach ( $namespaces as $i => $namespace ) {
			if ( $i >= 0 ) {
				$this->mSearchNs[$i] = $wgUser->getOption( 'searchNs'.$i );
			}
		}
	}

	/**
	 * @access private
	 */
	function namespacesCheckboxes() {
		global $wgContLang, $wgUser;
		
		# Determine namespace checkboxes
		$namespaces = $wgContLang->getNamespaces();
		$r1 = '';

		foreach ( $namespaces as $i => $name ) {
			# Skip special or anything similar
			if ( $i >= 0 ) {
				$checked = '';
				if ( $this->mSearchNs[$i] ) {
					$checked = ' checked="checked"';
				}
				$name = str_replace( '_', ' ', $namespaces[$i] );
				if ( '' == $name ) { 
					$name = wfMsg( 'blanknamespace' ); 
				}

				if ( 0 != $i ) { 
					$r1 .= ' '; 
				}
				$r1 .= "<label><input type='checkbox' value=\"1\" name=\"" .
				  "wpNs$i\"{$checked} />{$name}</label>\n";
			}
		}
		
		return $r1;
	}


	function getToggle( $tname, $trailer = false) {
		global $wgUser, $wgLang;
		
		$this->mUsedToggles[$tname] = true;
		$ttext = $wgLang->getUserToggle( $tname );
		
		$checked = $wgUser->getOption( $tname ) == 1 ? ' checked="checked"' : '';
		$trailer = $trailer ? $trailer : '';
		return "<tr><td align='right'><input type='checkbox' value='1' id=\"$tname\" name=\"wpOp$tname\"$checked />" .
			"</td><td align='left'><label for=\"$tname\">$ttext</label>$trailer</td></tr>";
	}

	function addRow($td1, $td2) {
		return "<tr><td align='right'>$td1</td><td align='left'>$td2</td></tr>";
	}

	/**
	 * @access private
	 */
	function mainPrefsForm( $err ) {
		global $wgUser, $wgOut, $wgLang, $wgContLang, $wgValidSkinNames;
		global $wgAllowRealName, $wgImageLimits, $wgThumbLimits;
		global $wgLanguageNames, $wgDisableLangConversion;
		global $wgEmailNotificationForWatchlistPages, $wgEmailNotificationForUserTalkPages,$wgEmailNotificationForMinorEdits;
		global $wgRCShowWatchingUsers, $wgEmailNotificationRevealPageEditorAddress;
		global $wgEnableEmail, $wgEnableUserEmail, $wgEmailAuthentication;
		global $wgContLanguageCode;

		$wgOut->setPageTitle( wfMsg( 'preferences' ) );
		$wgOut->setArticleRelated( false );
		$wgOut->setRobotpolicy( 'noindex,nofollow' );

		if ( '' != $err ) {
			$wgOut->addHTML( "<p class='error'>" . htmlspecialchars( $err ) . "</p>\n" );
		}
		$uname = $wgUser->getName();
		$uid = $wgUser->getID();

		$wgOut->addWikiText( wfMsg( 'prefslogintext', $uname, $uid ) );
		$wgOut->addWikiText( wfMsg('clearyourcache'));

		$qbs = $wgLang->getQuickbarSettings();
		$skinNames = $wgLang->getSkinNames();
		$mathopts = $wgLang->getMathNames();
		$dateopts = $wgLang->getDateFormats();
		$togs = $wgLang->getUserToggles();

		$titleObj = Title::makeTitle( NS_SPECIAL, 'Preferences' );
		$action = $titleObj->escapeLocalURL();

		$wgOut->addHTML( "<form id='preferences' name='preferences' action=\"$action\" method='post'>" );
	
		# User data
		#

		$wgOut->addHTML( "<fieldset>\n<legend>" . wfMsg('prefs-personal') . "</legend>\n<table>\n");

		if (!empty($this->mRealName)) {
			$wgOut->addHTML(
				$this->addRow(
					wfMsg('yourrealname'),
					"<input type='text' name='wpRealName' value=\"{$this->mRealName}\" size='25' />"
				) . 
				$this->addRow(
					wfMsg( 'youremail' ),
					"<input type='text' name='wpUserEmail' value=\"{$this->mUserEmail}\" size='25' />"
				)
			);
		}
		
		$wgOut->addHTML( $this->addRow( wfMsg( 'yournick' ), "<input type='text' name='wpNick' value=\"{$this->mNick}\" size='25' />") );
		$wgOut->addHTML( $this->getToggle( 'fancysig' ) );

		/**
		 * If a bogus value is set, default to the content language.
		 * Otherwise, no default is selected and the user ends up
		 * with an Afrikaans interface since it's first in the list.
		 */
		$selectedLang = isset( $wgLanguageNames[$this->mUserLanguage] ) ? $this->mUserLanguage : $wgContLanguageCode;
		$selbox = null;
		foreach($wgLanguageNames as $code => $name) {
			global $IP;
			/* only add languages that have a file */
			$langfile="$IP/languages/Language".str_replace('-', '_', ucfirst($code)).".php";
			if(file_exists($langfile) || $code == $wgContLanguageCode) {
				$sel = ($code == $selectedLang)? ' selected="selected"' : '';
				$selbox .= "<option value=\"$code\"$sel>$code - $name</option>\n";
			}
		}
		$wgOut->addHTML( $this->addRow( wfMsg('yourlanguage'), "<select name='wpUserLanguage'>$selbox</select>" ));

		/* see if there are multiple language variants to choose from*/
		if(!$wgDisableLangConversion) {
			$variants = $wgContLang->getVariants();
		
			foreach($variants as $v) {
				$v = str_replace( '_', '-', strtolower($v));
				if($name = $wgLanguageNames[$v]) {
					$variantArray[$v] = $name;
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
		$this->mOldpass = htmlspecialchars( $this->mOldpass );
		$this->mNewpass = htmlspecialchars( $this->mNewpass );
		$this->mRetypePass = htmlspecialchars( $this->mRetypePass );

		$wgOut->addHTML( '<fieldset><legend>' . wfMsg( 'changepassword' ) . '</legend><table>');
		$wgOut->addHTML(
			$this->addRow( wfMsg( 'oldpassword' ), "<input type='password' name='wpOldpass' value=\"{$this->mOldpass}\" size='20' />" ) .
			$this->addRow( wfMsg( 'newpassword' ), "<input type='password' name='wpNewpass' value=\"{$this->mNewpass}\" size='20' />" ) .
			$this->addRow( wfMsg( 'retypenew' ), "<input type='password' name='wpRetypePass' value=\"{$this->mRetypePass}\" size='20' />" ) .
			$this->getToggle( "rememberpassword" ) . "</table>\n</fieldset>\n\n" );

		# Enotif
		$this->mUserEmail = htmlspecialchars( $this->mUserEmail );
		$this->mRealName = htmlspecialchars( $this->mRealName );
		$this->mNick = htmlspecialchars( $this->mNick );
		
		if ($wgEmailAuthentication && ($this->mUserEmail != '') ) {
			if ($wgUser->getEmailAuthenticationtimestamp() != 0) {
				$emailauthenticated = wfMsg('emailauthenticated',
					$wgLang->timeanddate($wgUser->getEmailAuthenticationtimestamp(), true ) ).'<br />';
				$disabled = '';
			} else {
				$emailauthenticated = wfMsg('emailnotauthenticated').'<br />';
				$disabled = ' '.wfMsg('disableduntilauthent');
			}
		} else {
			$emailauthenticated = '';
		}

		if ($this->mUserEmail == '') {
			$disabled = ' '.wfMsg('disablednoemail');
		}

		$ps = $this->namespacesCheckboxes();

		$enotifwatchlistpages = $wgEmailNotificationForWatchlistPages ? $this->getToggle( 'enotifwatchlistpages', $disabled) : '';
		$enotifusertalkpages = $wgEmailNotificationForUserTalkPages ? $this->getToggle( 'enotifusertalkpages', $disabled) : '';
		$enotifminoredits = $wgEmailNotificationForMinorEdits ? $this->getToggle( 'enotifminoredits', $disabled) : '';
		$enotifrevealaddr = $wgEmailNotificationRevealPageEditorAddress ? $this->getToggle( 'enotifrevealaddr', $disabled) : '';
		$prefs_help_email_enotif = ( $wgEmailNotificationForWatchlistPages || $wgEmailNotificationForUserTalkPages) ? ' ' . wfMsg('prefs-help-email-enotif') : '';
		if( $wgEnableEmail ) {
			$wgOut->addHTML( "<fieldset><legend>" . wfMsg('email') . "</legend>");
			$wgOut->addHTML(
				$emailauthenticated.
				$enotifrevealaddr.
				$enotifwatchlistpages.
				$enotifusertalkpages.
				$enotifminoredits );
			if( $wgEnableUserEmail ) {
				$emfc = $this->mEmailFlag ? 'checked="checked"' : '';
				$wgOut->addHTML(
				"<input type='checkbox' $emfc value='1' name='wpEmailFlag' />".  wfMsg( 'emailflag' ) . $disabled );
				$prefs_help_realname = $wgAllowRealName ? wfMsg('prefs-help-realname') : '';
			}
			$wgOut->addHTML( '</fieldset>' );
		}
		
		if ($wgAllowRealName || $wgEnableEmail) {
			$wgOut->addHTML("<div class='prefsectiontip'>");
			$rn = $wgAllowRealName ? wfMsg('prefs-help-realname') : '';
			$em = $wgEnableEmail ? '<br />' .  wfMsg('prefs-help-email') : '';
			$wgOut->addHTML( $rn . $em  . '</div>');
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
		}

		# Skin
		#
		$wgOut->addHTML( "<fieldset>\n<legend>\n" . wfMsg('skin') . "</legend>\n" );
		# Only show members of $wgValidSkinNames rather than
		# $skinNames (skins is all skin names from Language.php)
		foreach ($wgValidSkinNames as $skinkey => $skinname ) {
			global $wgDefaultSkin;
			
			$checked = $skinkey == $this->mSkin ? ' checked="checked"' : '';
			$sn = isset( $skinNames[$skinkey] ) ? $skinNames[$skinkey] : $skinname;
			
			if( $skinkey == $wgDefaultSkin )
				$sn .= ' (' . wfMsg( 'default' ) . ')';
			$wgOut->addHTML( "<input type='radio' name='wpSkin' value=\"$skinkey\"$checked /> {$sn}<br/>\n" );
		}
		$wgOut->addHTML( "</fieldset>\n\n" );

		# Math
		#
		$wgOut->addHTML( "<fieldset>\n<legend>" . wfMsg('math') . '</legend>' );
		foreach ( $mathopts as $k => $v ) {
			$checked = $k == $this->mMath ? ' checked="checked"' : '';
			$wgOut->addHTML( "<div><label><input type='radio' name='wpMath' value=\"$k\"$checked /> ".wfMsg($v)."</label></div>\n" );
		}
		$wgOut->addHTML( "</fieldset>\n\n" );

		# Files
		#
		$wgOut->addHTML("<fieldset>
			<legend>" . wfMsg( 'files' ) . "</legend>
			<div><label>" . wfMsg('imagemaxsize') . "<select name=\"wpImageSize\">");
			
			$imageLimitOptions = null;
			foreach ( $wgImageLimits as $index => $limits ) {
				$selected = ($index == $this->mImageSize) ? 'selected="selected"' : '';
				$imageLimitOptions .= "<option value=\"{$index}\" {$selected}>{$limits[0]}x{$limits[1]}</option>\n";
			}
			
			$imageThumbOptions = null;
			$wgOut->addHTML( "{$imageLimitOptions}</select></label></div>
				<div><label>" . wfMsg('thumbsize') . "<select name=\"wpThumbSize\">");
			foreach ( $wgThumbLimits as $index => $size ) {
				$selected = ($index == $this->mThumbSize) ? 'selected="selected"' : '';
				$imageThumbOptions .= "<option value=\"{$index}\" {$selected}>{$size}px</option>\n";
			}
			$wgOut->addHTML( "{$imageThumbOptions}</select></label></div></fieldset>\n\n");

                # Date format
                #
		if ($dateopts) {
			$wgOut->addHTML( "<fieldset>\n<legend>" . wfMsg('dateformat') . "</legend>\n" );
			foreach($dateopts as $key => $option) {
				($key == $this->mDate) ? $checked = ' checked="checked"' : $checked = '';
				$wgOut->addHTML( "<div><label><input type='radio' name=\"wpDate\" ".
					"value=\"$key\"$checked />$option</label></div>\n" );
			}
			$wgOut->addHTML( "</fieldset>\n\n");
		}

		# Time zone
		#
		
		$nowlocal = $wgLang->time( $now = wfTimestampNow(), true );
		$nowserver = $wgLang->time( $now, false );
		 
		$wgOut->addHTML( '<fieldset><legend>' . wfMsg( 'timezonelegend' ) . '</legend><table>' .
		 	$this->addRow( wfMsg( 'servertime' ), $nowserver ) .
			$this->addRow( wfMsg( 'localtime' ), $nowlocal ) .
			$this->addRow(
				wfMsg( 'timezoneoffset' ),
				"<input type='text' name='wpHourDiff' value=\"" . htmlspecialchars( $this->mHourDiff ) . "\" size='6' />"
			) . "<tr><td colspan='2'>
				<input type='button' value=\"" . wfMsg( 'guesstimezone' ) ."\"
				onclick='javascript:guessTimezone()' id='guesstimezonebutton' style='display:none;' />
				</td></tr></table>
			<div class='prefsectiontip'>*" .  wfMsg( 'timezonetext' ) . "</div>
		</fieldset>\n\n" );		
		
		# Editing
		#
		$wgOut->addHTML( "<fieldset><legend>" . wfMsg( 'textboxsize' ) . " </legend>\n
		<div>
			<label>" . wfMsg( 'rows' ) . ": <input type='text' name='wpRows' value=\"{$this->mRows}\" size='6' /></label>
			<label>" . wfMsg( 'columns' ) . ": <input type='text' name='wpCols' value=\"{$this->mCols}\" size='6' /></label>
		</div> <table>" .
		$this->getToggle( "editsection" ) .
		$this->getToggle( "editsectiononrightclick" ) .
		$this->getToggle( "editondblclick" ) .
		$this->getToggle( "editwidth" ) .
		$this->getToggle( "showtoolbar" ) .
		$this->getToggle( "previewonfirst" ) .
		$this->getToggle( "previewontop" ) .
		$this->getToggle( "watchdefault" ) .
		$this->getToggle( "minordefault" ) . 
		$this->getToggle( "externaleditor" ) .
		$this->getToggle( "externaldiff" ) .
		"
	</table></fieldset>");
	
		$shownumberswatching = ($wgRCShowWatchingUsers) ? $this->getToggle('shownumberswatching') : '';

		$wgOut->addHTML( "
	<fieldset><legend>".htmlspecialchars(wfMsg('prefs-rc'))."</legend>
		<div><label>" . wfMsg( 'recentchangescount' ) . ": <input type='text' name=\"wpRecent\" value=\"$this->mRecent\" size='6' /></label></div>" .
		$this->getToggle( "hideminor" ) . $shownumberswatching .
		$this->getToggle( "usenewrc" ) . 
		$this->getToggle( "rcusemodstyle" ) .
		$this->getToggle('showupdated', wfMsg('updatedmarker')) .
		"<div><label>". wfMsg ( 'stubthreshold' ) . ": <input type='text' name=\"wpStubs\" value=\"$this->mStubs\" size='6' /></label></div>
	</fieldset>");
	
		$wgOut->addHTML( "<fieldset><legend>" . wfMsg( 'searchresultshead' ) . "</legend><table>" .
		$this->addRow( wfMsg( 'resultsperpage' ), "<input type='text' name='wpSearch' value=\"$this->mSearch\" size='4' />" ) .
		$this->addRow( wfMsg( 'contextlines' ), "<input type='text' name='wpSearchLines' value=\"$this->mSearchLines\" size='4' />") .
		$this->addRow( wfMsg( 'contextchars' ), "<input type='text' name='wpSearchChars' value=\"$this->mSearchChars\" size='4' />") .
		"</table><fieldset><legend>" . wfMsg( 'defaultns' ) . "</legend>$ps</fieldset></fieldset>" );
	
		# Misc
		#
		$wgOut->addHTML('<fieldset><legend>' . wfMsg('prefs-misc') . '</legend><table>');

		foreach ( $togs as $tname ) {
			if( !array_key_exists( $tname, $this->mUsedToggles ) ) {
				$wgOut->addHTML( $this->getToggle( $tname ) );
			}
		}
		$wgOut->addHTML( '</table></fieldset>' );

		$token = $wgUser->editToken();
		$wgOut->addHTML( "
	<div id='prefsubmit'>
	<div>
		<input type='submit' name='wpSaveprefs' value=\"" . wfMsg( 'saveprefs' ) . "\" accesskey=\"".
		wfMsg('accesskey-save')."\" title=\"[alt-".wfMsg('accesskey-save')."]\" />
		<input type='submit' name='wpReset' value=\"" . wfMsg( 'resetprefs' ) . "\" />
	</div>
	
	</div>
	
	<input type='hidden' name='wpEditToken' value='{$token}' />
	</form>\n" );
	}
}
?>
