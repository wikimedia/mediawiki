<?php
/**
 * Hold things related to displaying and saving user preferences.
 * @package MediaWiki
 * @subpackage SpecialPage
 */

/* to get a list of languages in setting user's language preference */
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
		global $wgLang, $wgContLang, $wgAllowRealName;
		
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
		$this->mRealName = ($wgAllowRealName) ? $request->getVal( 'wpRealName' ) : '';
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

		$this->mAction = $request->getVal( 'action' );
		$this->mReset = $request->getCheck( 'wpReset' );
		$this->mPosted = $request->wasPosted();
		$this->mSaveprefs = $request->getCheck( 'wpSaveprefs' ) && $this->mPosted;

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
	}

	function execute() {
		global $wgUser, $wgOut, $wgUseDynamicDates;
		
		if ( 0 == $wgUser->getID() ) {
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
	 * @access private
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
			$hour = min( $hour, 15 );
			$hour = max( $hour, -15 );
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
		global $wgUser, $wgLang, $wgDeferredUpdateList, $wgOut;

		if ( '' != $this->mNewpass ) {
			if ( $this->mNewpass != $this->mRetypePass ) {
				$this->mainPrefsForm( wfMsg( 'badretype' ) );			
				return;
			}

			if (!$wgUser->checkPassword( $this->mOldpass )) {
				$this->mainPrefsForm( wfMsg( 'wrongpassword' ) );
				return;
			}
			$wgUser->setPassword( $this->mNewpass );
		}
		$wgUser->setEmail( $this->mUserEmail );
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

		# Set search namespace options
		foreach( $this->mSearchNs as $i => $value ) {
			$wgUser->setOption( "searchNs{$i}", $value );
		}
		
		$wgUser->setOption( 'disablemail', $this->mEmailFlag );

		# Set user toggles
		foreach ( $this->mToggles as $tname => $tvalue ) {
			$wgUser->setOption( $tname, $tvalue );
		}
		$wgUser->setCookies();
		$up = new UserUpdate();
		array_push( $wgDeferredUpdateList, $up );
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
		$this->mRealName = ($wgAllowRealName) ? $wgUser->getRealName() : '';
		$this->mUserLanguage = $wgUser->getOption( 'language');
        $this->mUserVariant = $wgUser->getOption( 'variant');
		if ( 1 == $wgUser->getOption( 'disablemail' ) ) { $this->mEmailFlag = 1; }
		else { $this->mEmailFlag = 0; }
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


	function getToggle( $tname ) {
		global $wgUser, $wgLang;
		
		$this->mUsedToggles[$tname] = true;
		$ttext = $wgLang->getUserToggle( $tname );
		
		if ( 1 == $wgUser->getOption( $tname ) ) {
			$checked = ' checked="checked"';
		} else {
			$checked = '';
		}		
		return "<div><input type='checkbox' value=\"1\" "
		  . "id=\"$tname\" name=\"wpOp$tname\"$checked /><label for=\"$tname\">$ttext</label></div>\n";
	}

	/**
	 * @access private
	 */
	function mainPrefsForm( $err ) {
		global $wgUser, $wgOut, $wgLang, $wgContLang, $wgUseDynamicDates, $wgValidSkinNames;
		global $wgAllowRealName, $wgImageLimits;
		global $wgLanguageNames;

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

		$qb = wfMsg( 'qbsettings' );
		$cp = wfMsg( 'changepassword' );
		$sk = wfMsg( 'skin' );
		$math = wfMsg( 'math' );
		$dateFormat = wfMsg('dateformat');
		$opw = wfMsg( 'oldpassword' );
		$npw = wfMsg( 'newpassword' );
		$rpw = wfMsg( 'retypenew' );
		$svp = wfMsg( 'saveprefs' );
		$rsp = wfMsg( 'resetprefs' );
		$tbs = wfMsg( 'textboxsize' );
		$tbr = wfMsg( 'rows' );
		$tbc = wfMsg( 'columns' );
		$ltz = wfMsg( 'localtime' );
		$timezone = wfMsg( 'timezonelegend' );
		$tzt = wfMsg( 'timezonetext' );
		$tzo = wfMsg( 'timezoneoffset' );
		$tzGuess = wfMsg( 'guesstimezone' );
		$tzServerTime = wfMsg( 'servertime' );
		$yem = wfMsg( 'youremail' );
		$yrn = ($wgAllowRealName) ? wfMsg( 'yourrealname' ) : '';
		$yl  = wfMsg( 'yourlanguage' );
		$yv  = wfMsg( 'yourvariant' );
		$emf = wfMsg( 'emailflag' );
		$ynn = wfMsg( 'yournick' );
		$stt = wfMsg ( 'stubthreshold' ) ;
		$srh = wfMsg( 'searchresultshead' );
		$rpp = wfMsg( 'resultsperpage' );
		$scl = wfMsg( 'contextlines' );
		$scc = wfMsg( 'contextchars' );
		$rcc = wfMsg( 'recentchangescount' );
		$dsn = wfMsg( 'defaultns' );

		$wgOut->addHTML( "<form id=\"preferences\" name=\"preferences\" action=\"$action\"
	method=\"post\">" );
	
		# First section: identity
		# Email, etc.
		#
		$this->mUserEmail = htmlspecialchars( $this->mUserEmail );
		$this->mRealName = htmlspecialchars( $this->mRealName );
		$this->mNick = htmlspecialchars( $this->mNick );
		if ( $this->mEmailFlag ) { $emfc = 'checked="checked"'; }
		else { $emfc = ''; }

		$ps = $this->namespacesCheckboxes();

		$wgOut->addHTML( "<fieldset>
		<legend>".wfMsg('prefs-personal')."</legend>");
			if ($wgAllowRealName) {
			$wgOut->addHTML("<div><label>$yrn: <input type='text' name=\"wpRealName\" value=\"{$this->mRealName}\" size='20' /></label></div>");
		}
		$wgOut->addHTML("
		<div><label>$yem: <input type='text' name=\"wpUserEmail\" value=\"{$this->mUserEmail}\" size='20' /></label></div>
		<div><label><input type='checkbox' $emfc value=\"1\" name=\"wpEmailFlag\" /> $emf</label></div>
		<div><label>$ynn: <input type='text' name=\"wpNick\" value=\"{$this->mNick}\" size='12' /></label></div>
		<div><label>$yl: <select name=\"wpUserLanguage\">\n");

		foreach($wgLanguageNames as $code => $name) {
			global $IP;
			/* only add languages that have a file */
			$langfile="$IP/languages/Language".str_replace('-', '_', ucfirst($code)).".php";
			if(file_exists($langfile)) {
				$sel = ($code == $this->mUserLanguage)? 'selected="selected"' : '';
				$wgOut->addHtml("\t<option value=\"$code\" $sel>$code - $name</option>\n");
			}
		}
		$wgOut->addHtml("</select></label></div>\n" );

		/* see if there are multiple language variants to choose from*/
		$variants = $wgContLang->getVariants();
		$size=sizeof($variants);
		
		$variantArray=array();
		foreach($variants as $v) {
			$v = str_replace( '_', '-', strtolower($v));
			if($name=$wgLanguageNames[$v]) {
				$variantArray[$v] = $name;
			}
		}
		$size=sizeof($variantArray);
		
		if(sizeof($variantArray) > 1) {
			$wgOut->addHtml("
				<div><label>$yv: <select name=\"wpUserVariant\">\n");
			foreach($variantArray as $code => $name) {
				$sel = ($code==$this->mUserVariant)? 'selected="selected"' : '';
				$wgOut->addHtml("\t<option value=\"$code\" $sel>$code - $name</option>\n");
			}
			$wgOut->addHtml("</select></label></div>\n");
		}

		# Fields for changing password
		#
		$this->mOldpass = htmlspecialchars( $this->mOldpass );
		$this->mNewpass = htmlspecialchars( $this->mNewpass );
		$this->mRetypePass = htmlspecialchars( $this->mRetypePass );

		$wgOut->addHTML( "<fieldset>
	<legend>$cp</legend>
	<div><label>$opw: <input type='password' name=\"wpOldpass\" value=\"{$this->mOldpass}\" size='20' /></label></div>
	<div><label>$npw: <input type='password' name=\"wpNewpass\" value=\"{$this->mNewpass}\" size='20' /></label></div>
	<div><label>$rpw: <input type='password' name=\"wpRetypePass\" value=\"{$this->mRetypePass}\" size='20' /></label></div>
	" . $this->getToggle( "rememberpassword" ) . "
	</fieldset>
	<div class='prefsectiontip'>".wfMsg('prefs-help-userdata')."</div>\n</fieldset>\n" );

	
		# Quickbar setting
		#
		$wgOut->addHtml( "<fieldset>\n<legend>$qb</legend>\n" );
		for ( $i = 0; $i < count( $qbs ); ++$i ) {
			if ( $i == $this->mQuickbar ) { $checked = ' checked="checked"'; }
			else { $checked = ""; }
			$wgOut->addHTML( "<div><label><input type='radio' name=\"wpQuickbar\"
	value=\"$i\"$checked /> {$qbs[$i]}</label></div>\n" );
		}
		$wgOut->addHtml('<div class="prefsectiontip">'.wfMsg('qbsettingsnote').'</div>');
		$wgOut->addHtml( "</fieldset>\n\n" );

		# Skin setting
		#
		$wgOut->addHTML( "<fieldset>\n<legend>$sk</legend>\n" );
		# Only show members of $wgValidSkinNames rather than
		# $skinNames (skins is all skin names from Language.php)
		foreach ($wgValidSkinNames as $skinkey => $skinname ) {
			if ( $skinkey == $this->mSkin ) { 
				$checked = ' checked="checked"'; 
			} else { 
				$checked = ''; 
			}
			if ( isset( $skinNames[$skinkey] ) ) {
				$sn = $skinNames[$skinkey];
			} else {
				$sn = $skinname;
			}
			$wgOut->addHTML( "<div><label><input type='radio' name=\"wpSkin\"
	value=\"$skinkey\"$checked /> {$sn}</label></div>\n" );
		}
		$wgOut->addHTML( "</fieldset>\n\n" );

		# Math setting
		#
		$wgOut->addHTML( "<fieldset>\n<legend>$math</legend>\n" );
		for ( $i = 0; $i < count( $mathopts ); ++$i ) {
			if ( $i == $this->mMath ) { $checked = ' checked="checked"'; }
			else { $checked = ""; }
			$wgOut->addHTML( "<div><label><input type='radio' name=\"wpMath\"
	value=\"$i\"$checked /> ".wfMsg($mathopts[$i])."</label></div>\n" );
		}
		$wgOut->addHTML( "</fieldset>\n\n" );
		
		# Date format
		#
		if ( $wgUseDynamicDates ) {
			$wgOut->addHTML( "<fieldset>\n<legend>$dateFormat</legend>\n" );
			for ( $i = 0; $i < count( $dateopts ); ++$i) {
				if ( $i == $this->mDate ) {
					$checked = ' checked="checked"';
				} else {
					$checked = "";
				}
				$wgOut->addHTML( "<div><label><input type='radio' name=\"wpDate\" ".
					"value=\"$i\"$checked /> {$dateopts[$i]}</label></div>\n" );
			}
			$wgOut->addHTML( "</fieldset>\n\n");
		}
		
		# Textbox rows, cols
		#
		$nowlocal = $wgLang->time( $now = wfTimestampNow(), true );
		$nowserver = $wgLang->time( $now, false );
		$wgOut->addHTML( "<fieldset>
	<legend>$tbs</legend>\n
		<div>
			<label>$tbr: <input type='text' name=\"wpRows\" value=\"{$this->mRows}\" size='6' /></label>
			<label>$tbc: <input type='text' name=\"wpCols\" value=\"{$this->mCols}\" size='6' /></label>
		</div> " .
		$this->getToggle( "editwidth" ) .
		$this->getToggle( "showtoolbar" ) .
		$this->getToggle( "previewonfirst" ) .
		$this->getToggle( "previewontop" ) .
		$this->getToggle( "watchdefault" ) .
		$this->getToggle( "minordefault" ) . "
	</fieldset>
	
	<fieldset>
		<legend>$timezone</legend>
		<div><b>$tzServerTime:</b> $nowserver</div>
		<div><b>$ltz:</b> $nowlocal</div>
		<div><label>$tzo*: <input type='text' name=\"wpHourDiff\" value=\"" . htmlspecialchars( $this->mHourDiff ) . "\" size='6' /></label></div>
		<div><input type=\"button\" value=\"$tzGuess\" onclick=\"javascript:guessTimezone()\" id=\"guesstimezonebutton\" style=\"display:none\" /></div>
		<div class='prefsectiontip'>* {$tzt}</div>
	</fieldset>\n\n" );

		$wgOut->addHTML( "
	<fieldset><legend>".wfMsg('prefs-rc')."</legend>
		<div><label>$rcc: <input type='text' name=\"wpRecent\" value=\"$this->mRecent\" size='6' /></label></div>
		" . $this->getToggle( "hideminor" ) .
		$this->getToggle( "usenewrc" ) . "
		<div><label>$stt: <input type='text' name=\"wpStubs\" value=\"$this->mStubs\" size='6' /></label></div>
                <div><label>".wfMsg('imagemaxsize')."<select name=\"wpImageSize\">");
		
		$imageLimitOptions='';
		foreach ( $wgImageLimits as $index => $limits ) {
			$selected = ($index == $this->mImageSize) ? 'selected="selected"' : '';
			$imageLimitOptions .= "<option value=\"{$index}\" {$selected}>{$limits[0]}x{$limits[1]}</option>\n";
		}
		$wgOut->addHTML( "{$imageLimitOptions}</select></label></div>

	</fieldset>
	
	<fieldset>
		<legend>$srh</legend>
		<div><label>$rpp: <input type='text' name=\"wpSearch\" value=\"$this->mSearch\" size='6' /></label></div>
		<div><label>$scl: <input type='text' name=\"wpSearchLines\" value=\"$this->mSearchLines\" size='6' /></label></div>
		<div><label>$scc: <input type='text' name=\"wpSearchChars\" value=\"$this->mSearchChars\" size='6' /></label></div>

		<fieldset>
			<legend>$dsn</legend>
			$ps
		</fieldset>
	</fieldset>
		" );
	
		# Various checkbox options
		#
		$wgOut->addHTML("<fieldset><legend>".wfMsg('prefs-misc')."</legend>");
		foreach ( $togs as $tname ) {
			if( !array_key_exists( $tname, $this->mUsedToggles ) ) {
				$wgOut->addHTML( $this->getToggle( $tname ) );
			}
		}
		$wgOut->addHTML( "</fieldset>\n\n" );

		$wgOut->addHTML( "
	<div id='prefsubmit'>
	<div>
		<input type='submit' name=\"wpSaveprefs\" value=\"$svp\" accesskey=\"".
		wfMsg('accesskey-save')."\" title=\"[alt-".wfMsg('accesskey-save')."]\" />
		<input type='submit' name=\"wpReset\" value=\"$rsp\" />
	</div>
	
	</div>
	
	</form>\n" );
	}
}
?>
