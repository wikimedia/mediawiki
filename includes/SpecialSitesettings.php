<?php

function wfSpecialSiteSettings()
{
	global $wgRequest;

	$form = new SiteSettingsForm( $wgRequest );
	$form->execute();
}

class SiteSettingsForm {
	var $mPosted, $mRequest, $mReset, $mSaveprefs;
	
	function SiteSettingsForm ( &$request ) {
		$this->mPosted = $request->wasPosted();
		$this->mRequest = $request;
	}

	function execute() {
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

	/* private */ function resetPrefs() {
		return;
	}

	/* private */ function fieldset( $name, $content ) {
		return "<fieldset><legend>".wfMsg($name)."</legend>\n" .
			$content . "\n</fieldset>\n";
	}

	/* private */ function checkbox( $varname, $checked=false ) {
		$checked = isset( $GLOBALS[$varname] ) && $GLOBALS[$varname] ;
		return "<div><input type='checkbox' value=\"1\" id=\"{$varname}\" name=\"wpOp{$varname}\"" .
			( $checked ? ' checked="checked"' : '' ) .
			" /><label for=\"{$varname}\">". wfMsg( "sitesettings-".$varname ) .
			"</label></div>\n";
	}

	/* private */ function textbox( $varname, $value='', $size=20 ) {
		$value = isset( $GLOBALS[$varname] ) ? $GLOBALS[$varname] : '';
		return "<div><label>". wfMsg( "sitesettings-".$varname ) .
			"<input type='text' name=\"wpOp{$varname}\" value=\"{$value}\" size=\"{$size}\" /></label></div>\n";
	}
	/* private */ function radiobox( $varname, $fields ) {
		foreach ( $fields as $value => $checked ) {
			$s .= "<div><label><input type='radio' name=\"wpOp{$varname}\" value=\"{$value}\"" .
				( $checked ? ' checked="checked"' : '' ) . " />" . wfMsg( 'sitesettings-'.$varname.'-'.$value ) .
				"</label></div>\n";
		}
		return $this->fieldset( 'sitesettings-'.$varname, $s );
	}

	/* private */ function arraybox( $varname , $size=20 ) {
		$s = '';
		if ( isset( $GLOBALS[$varname] ) && is_array( $GLOBALS[$varname] ) ) {
			foreach ( $GLOBALS[$varname] as $index=>$element ) {
				$s .= $element."\n";
			}
		}
		return "<div><label>".wfMsg( 'sitesettings-'.$varname ).
			"<textarea name=\"wpOp{$varname}\" rows=\"5\" cols=\"{$size}\">{$s}</textarea>\n";
	}

	/* private */ function mainPrefsForm( $err ) {
		global $wgOut;

		$wgOut->setPageTitle( wfMsg( "sitesettings" ) );
		$wgOut->setArticleRelated( false );
		$wgOut->setRobotpolicy( "noindex,nofollow" );

		if ( "" != $err ) {
			$wgOut->addHTML( "<p class='error'>" . htmlspecialchars( $err ) . "</p>\n" );
		}

		$titleObj = Title::makeTitle( NS_SPECIAL, "SiteSettings" );
		$action = $titleObj->escapeLocalURL();

		$wgOut->addHTML( "<form id=\"preferences\" name=\"preferences\" action=\"$action\"
			method=\"post\">" );
		$wgOut->addHTML( $this->fieldset( "sitesettings-features",
			$this->checkbox( 'wgShowIPinHeader' )  .
			$this->checkbox( 'wgUseDatabaseMessages' ) .
			$this->checkbox( 'wgUseCategoryMagic' ) .
			$this->checkbox( 'wgUseCategoryBrowser' ) .
			$this->textbox( 'wgHitcounterUpdateFreq' ) .
			$this->textbox( 'wgExtraSubtitle' ).
			$this->textbox( 'wgSiteSupportPage' ) .
			$this->textbox( 'wgSiteNotice' ) .
			$this->checkbox( 'wgDisableAnonTalk' ).
			$this->checkbox( 'wgRCSeconds' ) .
			$this->checkbox( 'wgCapitalLinks' ).
			$this->checkbox( 'wgShowCreditsIfMax' ) .
			$this->textbox( 'wgMaxCredits' ).
			$this->checkbox( 'wgGoToEdit' ).
			$this->checkbox( 'wgAllowRealName' ) .
			$this->checkbox( 'wgAllowUserJs' ) .
			$this->checkbox( 'wgAllowUserCss' ).
			$this->checkbox( 'wgAllowPageInfo' ).
			$this->textbox( 'wgMaxTocLevel' ) .
			$this->checkbox( 'wgUseGeoMode' ) .
			$this->checkbox( 'wgUseValidation' ) .
			$this->checkbox( 'wgUseExternalDiffEngine' ) .
			$this->checkbox( 'wgUseRCPatrol' )
		) );
		$wgOut->addHTML( $this->fieldset( "sitesettings-permissions",
			$this->fieldset( 'sitesettings-permissions-readonly' , 
				$this->checkbox( 'wgReadOnly' ) .
				$this->textbox( 'wgReadOnlyFile','',50 ) 
			) .
			$this->fieldset( 'sitesettings-permissions-whitelist' ,
				$this->checkbox( 'wgWhitelistEdit' ) .
				$this->arraybox( 'wgWhitelistRead' ) .
				$this->checkbox( 'wgWhitelistAccount-user' ) .
				$this->checkbox( 'wgWhitelistAccount-sysop' ) .
				$this->checkbox( 'wgWhitelistAccount-developer' ) 
			) .
			$this->fieldset( 'sitesettings-permissions-banning' ,
				$this->checkbox( 'wgSysopUserBans' ) .
				$this->checkbox( 'wgSysopRangeBans' ) .
				$this->textbox( 'wgDefaultBlockExpiry', "24 hours" ) 
			) .
			$this->checkbox( 'wgAllowAnonymousMinor' ).
			$this->checkbox( 'wgPutIPinRC' ) .
			$this->textbox( 'wgSpamRegex' ).
			$this->checkbox( 'wgUserHtml' ).
			$this->checkbox( 'wgRawHtml' )
		) );
		$wgOut->addHTML( $this->fieldset( "sitesettings-images" ,
			$this->checkbox( 'wgAllowExternalImages' ) .
			$this->fieldset( 'sitesettings-images-upload' ,
				$this->checkbox( 'wgDisableUploads' ) .
				$this->checkbox( 'wgRemoteUploads' ) .
				$this->arraybox( 'wgFileExtensions' ) .
				$this->arraybox( 'wgFileBlacklist' ) .
				$this->checkbox( 'wgCheckFileExtensions' ) .
				$this->checkbox( 'wgStrictFileExtensions' ) .
				$this->textbox( 'wgUploadSizeWarning' ) .
				$this->checkbox( 'wgUseCopyrightUpload' ) .
				$this->checkbox( 'wgCheckCopyrightUpload' ) 
			) .
			$this->fieldset( 'sitesettings-images-resize' ,
				$this->checkbox( 'wgUseImageResize' ) .
				$this->checkbox( 'wgUseImageMagick' ) .
				$this->textbox( 'wgImageMagickConvertCommand' )
			)
		) );
		$wgOut->addHTML( $this->fieldset( "sitesettings-performance",
			$this->fieldset( 'sitesettings-permissions-miser' ,
				$this->checkbox( 'wgMiserMode' ) .
				$this->checkbox( 'wgDisableQueryPages' ) .
				$this->checkbox( 'wgUseWatchlistCache' ) .
				$this->textbox( 'wgWLCacheTimeout', '3600' ) 
			) .
			$this->checkbox( 'wgDisableCounters' ) .
			$this->checkbox( 'wgDisableTextSearch' ) .
			$this->checkbox( 'wgDisableFuzzySearch' ) .
			$this->checkbox( 'wgDisableSearchUpdate' ) 
		) );
		$wgOut->addHTML( $this->fieldset( "sitesettings-memcached",
			$this->checkbox( 'wgMemCachedDebug' ) .
			$this->checkbox( 'wgUseMemCached' ) .
			$this->textbox( 'wgMemCachedServers' ) .
			$this->checkbox( 'wgSessionsInMemcached' ).
			$this->checkbox( 'wgLinkCacheMemcached' ) .
			$this->textbox( 'wgAccountCreationThrottle' )
		) );
		$wgOut->addHTML( $this->fieldset( "sitesettings-caching",
			$this->checkbox( 'wgCachePages' ).
			$this->checkbox( 'wgUseFileCache' ).
			$this->textbox( 'wgFileCacheDirectory' ) .
			$this->textbox( 'wgCookieExpiration' ) .
			$this->fieldset( 'sitesettings-caching-squid' ,
				$this->checkbox( 'wgUseSquid' ) .
				$this->checkbox( 'wgUseESI' ) .
				$this->textbox( 'wgInternalServer' ) .
				$this->textbox( 'wgSquidMaxage' ) .
				$this->textbox( 'wgMaxSquidPurgeTitles' ) 
			)
		) );
		$wgOut->addHTML( $this->fieldset( "sitesettings-cookies",
			$this->textbox( 'wgCookieDomain' ) .
			$this->textbox( 'wgCookiePath' ) .
			$this->checkbox( 'wgDisableCookieCheck' ) 
		) );
		$wgOut->addHTML( $this->fieldset( "sitesettings-debugging",
			$this->textbox( 'wgDebugLogFile','',50 ) .
			$this->checkbox( 'wgDebugRedirects' ) .
			$this->checkbox( 'wgDebugRawPage' ) .
			$this->checkbox( 'wgDebugComments' ) .
			$this->checkbox( 'wgLogQueries' ) .
			$this->checkbox( 'wgDebugDumpSql' ) .
			$this->checkbox( 'wgIgnoreSQLErrors' ) .
			$this->fieldset( 'sitesettings-debugging-profiling',
				$this->checkbox( 'wgProfiling' ) .
				$this->textbox( 'wgProfileLimit' ) .
				$this->checkbox( 'wgProfileOnly' ) .
				$this->checkbox( 'wgProfileToDatabase' ) .
				$this->textbox( 'wgProfileSampleRate' ) .
				$this->checkbox( 'wgDebugProfiling' ) .
				$this->checkbox( 'wgDebugFunctionEntry')
			) .
			$this->checkbox( 'wgDebugSquid' )
		) );
		$wgOut->addHTML( "</form>" );
	}

}

?>
