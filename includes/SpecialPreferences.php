<?php

function wfSpecialPreferences()
{
	global $wgRequest;

	$form = new PreferencesForm( $wgRequest );
	$form->execute();
}

class PreferencesForm {
	var $mQuickbar, $mOldpass, $mNewpass, $mRetypePass, $mStubs;
	var $mRows, $mCols, $mSkin, $mMath, $mDate, $mUserEmail, $mEmailFlag, $mNick;
	var $mSearch, $mRecent, $mHourDiff, $mSearchLines, $mSearchChars, $mAction;
	var $mReset, $mPosted, $mToggles, $mSearchNs;

	function PreferencesForm( &$request ) {	
		global $wgLang;
		
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
		$this->mEmailFlag = $request->getCheck( 'wpEmailFlag' ) ? 1 : 0;
		$this->mNick = $request->getVal( 'wpNick' );
		$this->mSearch = $request->getVal( 'wpSearch' );
		$this->mRecent = $request->getVal( 'wpRecent' );
		$this->mHourDiff = $request->getVal( 'wpHourDiff' );
		$this->mSearchLines = $request->getVal( 'wpSearchLines' );
		$this->mSearchChars = $request->getVal( 'wpSearchChars' );
		$this->mAction = $request->getVal( 'action' );
		$this->mReset = $request->getCheck( 'wpReset' );
		$this->mPosted = $request->wasPosted();
		$this->mSaveprefs = $request->getCheck( 'wpSaveprefs' ) && $this->mPosted;

		# User toggles  (the big ugly unsorted list of checkboxes)
		$this->mToggles = array();
		if ( $this->mPosted ) {
			$togs = $wgLang->getUserToggles();
			foreach ( $togs as $tname => $ttext ) {
				$this->mToggles[$tname] = $request->getCheck( "wpOp$tname" ) ? 1 : 0;
			}
		}
		
		# Search namespace options
		# Note: namespaces don't necessarily have consecutive keys
		$this->mSearchNs = array();
		if ( $this->mPosted ) {
			$namespaces = $wgLang->getNamespaces();
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
			$wgOut->errorpage( "prefsnologin", "prefsnologintext" );
			return;
		}
		if ( wfReadOnly() ) {
			$wgOut->readOnlyPage();
			return;
		}
		if ( $this->mReset ) {
			$this->resetPrefs();
			$this->mainPrefsForm( wfMsg( "prefsreset" ) );
		} else if ( $this->mSaveprefs ) {
			$this->savePreferences();
		} else {
			$this->resetPrefs();
			$this->mainPrefsForm( "" );
		}
	}

	/* private */ function validateInt( &$val, $min=0, $max=0x7fffffff ) {
		$val = intval($val);
		$val = min($val, $max);
		$val = max($val, $min);
		return $val;
	}

	/* private */ function validateIntOrNull( &$val, $min=0, $max=0x7fffffff ) {
		$val = trim($val);
		if($val === "") {
			return $val;
		} else {
			return $this->validateInt( $val, $min, $max );
		}
	}

	/* private */ function validateTimeZone( $s )
	{
		
		if ( $s !== "" ) {
			if ( strpos( $s, ":" ) ) {
				# HH:MM
				$array = explode( ":" , $s );
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

	/* private */ function savePreferences()
	{
		global $wgUser, $wgLang, $wgDeferredUpdateList;

		if ( "" != $this->mNewpass ) {
			if ( $this->mNewpass != $this->mRetypePass ) {
				$this->mainPrefsForm( wfMsg( "badretype" ) );			
				return;
			}
			$ep = $wgUser->encryptPassword( $this->mOldpass );
			if ( $ep != $wgUser->getPassword() ) {
				if ( $ep != $wgUser->getNewpassword() ) {
					$this->mainPrefsForm( wfMsg( "wrongpassword" ) );
					return;
				}
			}
			$wgUser->setPassword( $this->mNewpass );
		}
		$wgUser->setEmail( $this->mUserEmail );
		$wgUser->setOption( "nickname", $this->mNick );
		$wgUser->setOption( "quickbar", $this->mQuickbar );
		$wgUser->setOption( "skin", $this->mSkin );
		$wgUser->setOption( "math", $this->mMath );
		$wgUser->setOption( "date", $this->mDate );
		$wgUser->setOption( "searchlimit", $this->validateIntOrNull( $this->mSearch ) );
		$wgUser->setOption( "contextlines", $this->validateIntOrNull( $this->mSearchLines ) );
		$wgUser->setOption( "contextchars", $this->validateIntOrNull( $this->mSearchChars ) );
		$wgUser->setOption( "rclimit", $this->validateIntOrNull( $this->mRecent ) );
		$wgUser->setOption( "rows", $this->validateInt( $this->mRows, 4, 1000 ) );
		$wgUser->setOption( "cols", $this->validateInt( $this->mCols, 4, 1000 ) );
		$wgUser->setOption( "stubthreshold", $this->validateIntOrNull( $this->mStubs ) );
		$wgUser->setOption( "timecorrection", $this->validateTimeZone( $this->mHourDiff, -12, 14 ) );

		# Set search namespace options
		foreach( $this->mSearchNs as $i => $value ) {
			$wgUser->setOption( "searchNs{$i}", $value );
		}
		
		$wgUser->setOption( "disablemail", $this->mEmailFlag );

		# Set user toggles
		foreach ( $this->mToggles as $tname => $tvalue ) {
			$wgUser->setOption( $tname, $tvalue );
		}
		$wgUser->setCookies();
		$up = new UserUpdate();
		array_push( $wgDeferredUpdateList, $up );
		$this->mainPrefsForm( wfMsg( "savedprefs" ) );
	}

	/* private */ function resetPrefs()
	{
		global $wgUser, $wgLang;

		$this->mOldpass = $this->mNewpass = $this->mRetypePass = "";
		$this->mUserEmail = $wgUser->getEmail();
		if ( 1 == $wgUser->getOption( "disablemail" ) ) { $this->mEmailFlag = 1; }
		else { $this->mEmailFlag = 0; }
		$this->mNick = $wgUser->getOption( "nickname" );

		$this->mQuickbar = $wgUser->getOption( "quickbar" );
		$this->mSkin = $wgUser->getOption( "skin" );
		$this->mMath = $wgUser->getOption( "math" );
		$this->mDate = $wgUser->getOption( "date" );
		$this->mRows = $wgUser->getOption( "rows" );
		$this->mCols = $wgUser->getOption( "cols" );
		$this->mStubs = $wgUser->getOption( "stubthreshold" );
		$this->mHourDiff = $wgUser->getOption( "timecorrection" );
		$this->mSearch = $wgUser->getOption( "searchlimit" );
		$this->mSearchLines = $wgUser->getOption( "contextlines" );
		$this->mSearchChars = $wgUser->getOption( "contextchars" );
		$this->mRecent = $wgUser->getOption( "rclimit" );

		$togs = $wgLang->getUserToggles();
		foreach ( $togs as $tname => $ttext ) {
			$this->mToggles[$tname] = $wgUser->getOption( $tname );
		}

		$namespaces = $wgLang->getNamespaces();
		foreach ( $namespaces as $i => $namespace ) {
			if ( $i >= 0 ) {
				$this->mSearchNs[$i] = $wgUser->getOption( "searchNs$i" );
			}
		}
	}

	/* private */ function namespacesCheckboxes()
	{
		global $wgLang, $wgUser;
		
		# Determine namespace checkboxes
		$namespaces = $wgLang->getNamespaces();
		$r1 = "";

		foreach ( $namespaces as $i => $name ) {
			# Skip special or anything similar
			if ( $i >= 0 ) {
				$checked = "";
				if ( $this->mSearchNs[$i] ) {
					$checked = ' checked="checked"';
				}
				$name = str_replace( "_", " ", $namespaces[$i] );
				if ( "" == $name ) { 
					$name = wfMsg( "blanknamespace" ); 
				}

				if ( 0 != $i ) { 
					$r1 .= " "; 
				}
				$r1 .= "<label><input type=checkbox value=\"1\" name=\"" .
				  "wpNs$i\"{$checked}>{$name}</label>\n";
			}
		}
		
		return $r1;
	}




	/* private */ function mainPrefsForm( $err )
	{
		global $wgUser, $wgOut, $wgLang, $wgUseDynamicDates, $wgValidSkinNames;

		$wgOut->setPageTitle( wfMsg( "preferences" ) );
		$wgOut->setArticleRelated( false );
		$wgOut->setRobotpolicy( "noindex,nofollow" );

		if ( "" != $err ) {
			$wgOut->addHTML( "<font size='+1' color='red'>$err</font>\n<p>" );
		}
		$uname = $wgUser->getName();
		$uid = $wgUser->getID();

		$wgOut->addWikiText( wfMsg( "prefslogintext", $uname, $uid ) );

		$qbs = $wgLang->getQuickbarSettings();
		$skinNames = $wgLang->getSkinNames();
		$mathopts = $wgLang->getMathNames();
		$dateopts = $wgLang->getDateFormats();
		$togs = $wgLang->getUserToggles();

		$titleObj = Title::makeTitle( NS_SPECIAL, "Preferences" );
		$action = $titleObj->escapeLocalURL();

		$qb = wfMsg( "qbsettings" );
		$cp = wfMsg( "changepassword" );
		$sk = wfMsg( "skin" );
		$math = wfMsg( "math" );
		$dateFormat = wfMsg("dateformat");
		$opw = wfMsg( "oldpassword" );
		$npw = wfMsg( "newpassword" );
		$rpw = wfMsg( "retypenew" );
		$svp = wfMsg( "saveprefs" );
		$rsp = wfMsg( "resetprefs" );
		$tbs = wfMsg( "textboxsize" );
		$tbr = wfMsg( "rows" );
		$tbc = wfMsg( "columns" );
		$ltz = wfMsg( "localtime" );
		$tzt = wfMsg( "timezonetext" );
		$tzo = wfMsg( "timezoneoffset" );
		$tzGuess = wfMsg( "guesstimezone" );
		$tzServerTime = wfMsg( "servertime" );
		$yem = wfMsg( "youremail" );
		$emf = wfMsg( "emailflag" );
		$ynn = wfMsg( "yournick" );
		$stt = wfMsg ( "stubthreshold" ) ;
		$srh = wfMsg( "searchresultshead" );
		$rpp = wfMsg( "resultsperpage" );
		$scl = wfMsg( "contextlines" );
		$scc = wfMsg( "contextchars" );
		$rcc = wfMsg( "recentchangescount" );
		$dsn = wfMsg( "defaultns" );

		$wgOut->addHTML( "<form id=\"preferences\" name=\"preferences\" action=\"$action\"
	method=\"post\"><table border=\"1\"><tr><td valign=top nowrap><b>$qb:</b><br>\n" );

		# Quickbar setting
		#
		for ( $i = 0; $i < count( $qbs ); ++$i ) {
			if ( $i == $this->mQuickbar ) { $checked = ' checked="checked"'; }
			else { $checked = ""; }
			$wgOut->addHTML( "<label><input type=radio name=\"wpQuickbar\"
	value=\"$i\"$checked> {$qbs[$i]}</label><br>\n" );
		}

		# Fields for changing password
		#
		$this->mOldpass = wfEscapeHTML( $this->mOldpass );
		$this->mNewpass = wfEscapeHTML( $this->mNewpass );
		$this->mRetypePass = wfEscapeHTML( $this->mRetypePass );

		$wgOut->addHTML( "</td><td vaign=top nowrap><b>$cp:</b><br>
	<label>$opw: <input type=password name=\"wpOldpass\" value=\"{$this->mOldpass}\" size=20></label><br>
	<label>$npw: <input type=password name=\"wpNewpass\" value=\"{$this->mNewpass}\" size=20></label><br>
	<label>$rpw: <input type=password name=\"wpRetypePass\" value=\"{$this->mRetypePass}\" size=20></label><br>
	</td></tr>\n" );

		# Skin setting
		#
		$wgOut->addHTML( "<tr><td valign=top nowrap><b>$sk:</b><br>\n" );
		# Only show members of $wgValidSkinNames rather than
		# $skinNames (skins is all skin names from Language.php)
		foreach ($wgValidSkinNames as $skinkey => $skinname ) {
			if ( $skinkey == $this->mSkin ) { 
				$checked = ' checked="checked"'; 
			} else { 
				$checked = ""; 
			}
			$wgOut->addHTML( "<label><input type=radio name=\"wpSkin\"
	value=\"$skinkey\"$checked> {$skinNames[$skinkey]}</label><br>\n" );
		}

		# Various checkbox options
		#
		if ( $wgUseDynamicDates ) {
			$wgOut->addHTML( "</td><td rowspan=3 valign=top nowrap>\n" );
		} else {
			$wgOut->addHTML( "</td><td rowspan=2 valign=top nowrap>\n" );
		}
		$wgOut->addHTML("<table border=0>");
		foreach ( $togs as $tname => $ttext ) {
			if ( 1 == $wgUser->getOption( $tname ) ) {
				$checked = ' checked="checked"';
			} else {
				$checked = "";
			}		
			$wgOut->addHTML( "<tr valign=\"top\"><td><input type=checkbox value=\"1\" "
			  . "id=\"$tname\" name=\"wpOp$tname\"$checked></td><td><label for=\"$tname\">$ttext</label></td></tr>\n" );
		}
		$wgOut->addHTML( "</table></td>" );

		# Math setting
		#
		$wgOut->addHTML( "<tr><td valign=top nowrap><b>$math:</b><br>\n" );
		for ( $i = 0; $i < count( $mathopts ); ++$i ) {
			if ( $i == $this->mMath ) { $checked = ' checked="checked"'; }
			else { $checked = ""; }
			$wgOut->addHTML( "<label><input type=radio name=\"wpMath\"
	value=\"$i\"$checked> {$mathopts[$i]}</label><br>\n" );
		}
		$wgOut->addHTML( "</td></tr>" );
		
		# Date format
		#
		if ( $wgUseDynamicDates ) {
			$wgOut->addHTML( "<tr><td valign=top nowrap><b>$dateFormat:</b><br>" );
			for ( $i = 0; $i < count( $dateopts ); ++$i) {
				if ( $i == $this->mDate ) {
					$checked = ' checked="checked"';
				} else {
					$checked = "";
				}
				$wgOut->addHTML( "<label><input type=radio name=\"wpDate\" ".
					"value=\"$i\"$checked> {$dateopts[$i]}</label><br>\n" );
			}
			$wgOut->addHTML( "</td></tr>");
		}
		# Textbox rows, cols
		#
		$nowlocal = $wgLang->time( $now = wfTimestampNow(), true );
		$nowserver = $wgLang->time( $now, false );
		$wgOut->addHTML( "<td valign=top nowrap><b>$tbs:</b><br>
	<label>$tbr: <input type=text name=\"wpRows\" value=\"{$this->mRows}\" size=6></label><br>
	<label>$tbc: <input type=text name=\"wpCols\" value=\"{$this->mCols}\" size=6></label><br><br>
	<b>$tzServerTime:</b> $nowserver<br />
	<b>$ltz:</b> $nowlocal<br />
	<label>$tzo*: <input type=text name=\"wpHourDiff\" value=\"{$this->mHourDiff}\" size=6></label><br />
	<input type=\"button\" value=\"$tzGuess\" onClick=\"javascript:guessTimezone()\" />
	</td>" );

		# Email, etc.
		#
		$this->mUserEmail = wfEscapeHTML( $this->mUserEmail );
		$this->mNick = wfEscapeHTML( $this->mNick );
		if ( $this->mEmailFlag ) { $emfc = 'checked="checked"'; }
		else { $emfc = ""; }

		$ps = $this->namespacesCheckboxes();

		$wgOut->addHTML( "<td valign=top nowrap>
	<label>$yem: <input type=text name=\"wpUserEmail\" value=\"{$this->mUserEmail}\" size=20></label><br>
	<label><input type=checkbox $emfc value=\"1\" name=\"wpEmailFlag\"> $emf</label><br>
	<label>$ynn: <input type=text name=\"wpNick\" value=\"{$this->mNick}\" size=12></label><br>
	<label>$rcc: <input type=text name=\"wpRecent\" value=\"$this->mRecent\" size=6></label><br>
	<label>$stt: <input type=text name=\"wpStubs\" value=\"$this->mStubs\" size=6></label><br>
	<strong>{$srh}:</strong><br>
	<label>$rpp: <input type=text name=\"wpSearch\" value=\"$this->mSearch\" size=6></label><br>
	<label>$scl: <input type=text name=\"wpSearchLines\" value=\"$this->mSearchLines\" size=6></label><br>
	<label>$scc: <input type=text name=\"wpSearchChars\" value=\"$this->mSearchChars\" size=6></label></td>
	</tr><tr>
	<td colspan=2>
	<b>$dsn</b><br>
	$ps
	</td>
	</tr><tr>
	<td align=center><input type=submit name=\"wpSaveprefs\" value=\"$svp\"></td>
	<td align=center><input type=submit name=\"wpReset\" value=\"$rsp\"></td>
	</tr></table>* {$tzt} </form>\n" );
	}
}
?>
