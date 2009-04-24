<?php

class Preferences {
	static $defaultPreferences = null;
	static $saveFilters =
		array(
			'timecorrection' => array( 'Preferences', 'filterTimezoneInput' ),
		);
	
	static function getPreferences( $user ) {
		if (self::$defaultPreferences)
			return self::$defaultPreferences;
	
		global $wgLang, $wgRCMaxAge;
		
		$defaultPreferences = array();
		
		## User info #####################################
		// Information panel
		$defaultPreferences['username'] =
				array(
					'type' => 'info',
					'label-message' => 'username',
					'default' => $user->getName(),
					'section' => 'personal',
				);
		
		$defaultPreferences['userid'] =
				array(
					'type' => 'info',
					'label-message' => 'uid',
					'default' => $user->getId(),
					'section' => 'personal',
				);
		
		# Get groups to which the user belongs
		$userEffectiveGroups = $user->getEffectiveGroups();
		$userEffectiveGroupsArray = array();
		foreach( $userEffectiveGroups as $ueg ) {
			if( $ueg == '*' ) {
				// Skip the default * group, seems useless here
				continue;
			}
			$userEffectiveGroupsArray[] = User::makeGroupLinkHTML( $ueg );
		}
		asort( $userEffectiveGroupsArray );
		
		$defaultPreferences['usergroups'] =
				array(
					'type' => 'info',
					'label' => wfMsgExt( 'prefs-memberingroups', 'parseinline',
								count($userEffectiveGroupsArray) ),
					'default' => $wgLang->commaList( $userEffectiveGroupsArray ),
					'raw' => true,
					'section' => 'personal',
				);
		
		$defaultPreferences['editcount'] =
				array(
					'type' => 'info',
					'label-message' => 'prefs-edits',
					'default' => $user->getEditCount(),
					'section' => 'personal',
				);
		
		if ($user->getRegistration()) {
			$defaultPreferences['registrationdate'] =
					array(
						'type' => 'info',
						'label-message' => 'prefs-registration',
						'default' => $wgLang->timeanddate( $user->getRegistration() ),
						'section' => 'personal',
					);
		}
				
		// Actually changeable stuff
		global $wgAllowRealName;
		if ($wgAllowRealName) {
			$defaultPreferences['realname'] =
					array(
						'type' => 'text',
						'default' => $user->getRealName(),
						'section' => 'personal',
						'label-message' => 'yourrealname',
						'help-message' => 'prefs-help-realname',
					);
		}
				
		global $wgEmailConfirmToEdit;
		
		$defaultPreferences['emailaddress'] =
				array(
					'type' => 'text',
					'default' => $user->getEmail(),
					'section' => 'personal',
					'label-message' => 'youremail',
					'help-message' => $wgEmailConfirmToEdit
										? 'prefs-help-email-required'
										: 'prefs-help-email',
					'validation-callback' => array( 'Preferences', 'validateEmail' ),
				);
		
		global $wgAuth;
		if ($wgAuth->allowPasswordChange()) {
			global $wgUser; // For skin.
			$link = $wgUser->getSkin()->link( SpecialPage::getTitleFor( 'ResetPass' ),
				wfMsgHtml( 'prefs-resetpass' ), array() ,
				array('returnto' => SpecialPage::getTitleFor( 'Preferences') ) );
				
			$defaultPreferences['password'] =
					array(
						'type' => 'info',
						'raw' => true,
						'default' => $link,
						'label-message' => 'yourpassword',
						'section' => 'personal',
					);
		}
		
		$defaultPreferences['gender'] =
				array(
					'type' => 'select',
					'section' => 'personal',
					'options' => array(
						wfMsg('gender-male') => 'male',
						wfMsg('gender-female') => 'female',
						wfMsg('gender-unknown') => 'unknown',
					),
					'label-message' => 'yourgender',
					'help-message' => 'prefs-help-gender',
				);
				
		// Language
		global $wgContLanguageCode;
		$languages = array_reverse( Language::getLanguageNames( false ) );
		if( !array_key_exists( $wgContLanguageCode, $languages ) ) {
			$languages[$wgContLanguageCode] = $wgContLanguageCode;
		}
		ksort( $languages );
		
		$options = array();
		foreach( $languages as $code => $name ) {
			$display = "$code - $name";
			$options[$display] = $code;
		}
		$defaultPreferences['language'] =
				array(
					'type' => 'select',
					'section' => 'personal',
					'options' => $options,
					'label-message' => 'yourlanguage',
				);
				
		global $wgContLang, $wgDisableLangConversion;
		/* see if there are multiple language variants to choose from*/
		$variantArray = array();
		if(!$wgDisableLangConversion) {
			$variants = $wgContLang->getVariants();

			$languages = Language::getLanguageNames( true );
			foreach($variants as $v) {
				$v = str_replace( '_', '-', strtolower($v));
				if( array_key_exists( $v, $languages ) ) {
					// If it doesn't have a name, we'll pretend it doesn't exist
					$variantArray[$v] = $languages[$v];
				}
			}

			$options = array();
			foreach( $variantArray as $code => $name ) {
				$options[$code] = "$code - $name";
			}

			if(count($variantArray) > 1) {
				$defaultPreferences['variant'] =
					array(
						'label-message' => 'yourvariant',
						'type' => 'select',
						'options' => $options,
						'section' => 'personal',
					);
			}
		}
		
		if(count($variantArray) > 1 && !$wgDisableLangConversion && !$wgDisableTitleConversion) {
			$defaultPreferences['noconvertlink'] =
					array(
						'type' => 'toggle',
						'section' => 'misc',
						'label-message' => 'tog-noconvertlink',
					);
		}
		
		global $wgMaxSigChars;
		$defaultPreferences['nickname'] =
				array(
					'type' => 'text',
					'maxlength' => $wgMaxSigChars,
					'label-message' => 'yournick',
					'validation-callback' =>
						array( 'Preferences', 'validateSignature' ),
					'section' => 'personal',
					'filter-callback' => array( 'Preferences', 'cleanSignature' ),
				);
		$defaultPreferences['fancysig'] =
				array(
					'type' => 'toggle',
					'label-message' => 'tog-fancysig',
					'section' => 'personal'
				);
				
		$defaultPreferences['rememberpassword'] =
				array(
					'type' => 'toggle',
					'label-message' => 'tog-rememberpassword',
					'section' => 'personal',
				);


		## Email #######################################
		## Email stuff
		global $wgEnableEmail, $wgEnableUserEmail;
		if ($wgEnableEmail) {
		
			if ($wgEnableUserEmail) {
				$defaultPreferences['disableemail'] =
						array(
							'type' => 'toggle',
							'invert' => true,
							'section' => 'email',
							'label-message' => 'allowemail',
						);
				$defaultPreferences['ccmeonemails'] =
						array(
							'type' => 'toggle',
							'section' => 'email',
							'label-message' => 'tog-ccmeonemails',
						);
			}
			
			$defaultPreferences['enotifwatchlistpages'] =
					array(
						'type' => 'toggle',
						'section' => 'email',
						'label-message' => 'tog-enotifwatchlistpages',
					);
			$defaultPreferences['enotifusertalkpages'] =
					array(
						'type' => 'toggle',
						'section' => 'email',
						'label-message' => 'tog-enotifusertalkpages',
					);
			$defaultPreferences['enotifminoredits'] =
					array(
						'type' => 'toggle',
						'section' => 'email',
						'label-message' => 'tog-enotifminoredits',
					);
			$defaultPreferences['enotifrevealaddr'] =
					array(
						'type' => 'toggle',
						'section' => 'email',
						'label-message' => 'tog-enotifrevealaddr'
					);
		}
		
		## Skin #####################################
		global $wgAllowUserSkin;
		
		if ($wgAllowUserSkin) {
			$defaultPreferences['skin'] =
					array(
						'type' => 'radio',
						'options' => self::generateSkinOptions( $user ),
						'label' => '&nbsp;',
						'section' => 'skin',
					);
		}
		
		## TODO QUICKBAR
				
		## Math #####################################
		global $wgUseTeX;
		if ($wgUseTeX) {
			$defaultPreferences['math'] =
					array(
						'type' => 'radio',
						'options' =>
							array_flip( array_map( 'wfMsg', $wgLang->getMathNames() ) ),
						'label' => '&nbsp;',
						'section' => 'math',
					);
		}
		
		## Files #####################################
		$defaultPreferences['imagesize'] =
				array(
					'type' => 'select',
					'options' => self::getImageSizes(),
					'label-message' => 'imagemaxsize',
					'section' => 'files',
				);
		$defaultPreferences['thumbsize'] =
				array(
					'type' => 'select',
					'options' => self::getThumbSizes(),
					'label-message' => 'thumbsize',
					'section' => 'files',
				);
		
		## Date and time #####################################
		$dateOptions = self::getDateOptions();
		if ($dateOptions) {
			$defaultPreferences['date'] =
					array(
						'type' => 'radio',
						'options' => $dateOptions,
						'label-message' => 'dateformat',
						'section' => 'datetime',
					);
		}
		
		// Info
		$nowlocal = Xml::element( 'span', array( 'id' => 'wpLocalTime' ),
			$wgLang->time( $now = wfTimestampNow(), true ) );
		$nowserver = $wgLang->time( $now, false ) .
			Xml::hidden( 'wpServerTime', substr( $now, 8, 2 ) * 60 + substr( $now, 10, 2 ) );
		
		$defaultPreferences['nowserver'] =
				array(
					'type' => 'info',
					'raw' => 1,
					'label-message' => 'servertime',
					'default' => $nowserver,
					'section' => 'datetime',
				);
				
		$defaultPreferences['nowlocal'] =
				array(
					'type' => 'info',
					'raw' => 1,
					'label-message' => 'localtime',
					'default' => $nowlocal,
					'section' => 'datetime',
				);
		
		// Grab existing pref.
		$tzOffset = $user->getOption( 'timecorrection' );
		$tz = explode( '|', $tzOffset, 2 );
		
		$tzSetting = $tzOffset;
		if (count($tz) > 1 && $tz[0] == 'Offset') {
			$minDiff = $tz[1];
			$tzSetting = sprintf( '%+03d:%02d', floor($minDiff/60), abs($minDiff)%60 );;
		}
		
		$defaultPreferences['timecorrection'] =
				array(
					'class' => 'HTMLSelectOrOtherField',
					'label-message' => 'timezonelegend',
					'options' => self::getTimezoneOptions(),
					'default' => $tzSetting,
					'section' => 'datetime',
				);
				
		## Page Rendering ##############################
		$defaultPreferences['underline'] =
				array(
					'type' => 'select',
					'options' => array(
						wfMsg( 'underline-never' ) => 0,
						wfMsg( 'underline-always' ) => 1,
						wfMsg( 'underline-default' ) => 2,
					),
					'label-message' => 'tog-underline',
					'section' => 'rendering',
				);
				
		$stubThresholdValues = array( 0, 50, 100, 500, 1000, 2000, 5000, 10000 );
		$stubThresholdOptions = array();
		foreach( $stubThresholdValues as $value ) {
			$stubThresholdOptions[wfMsg( 'size-bytes', $value )] = $value;
		}
		
		$defaultPreferences['stubthreshold'] =
				array(
					'type' => 'selectorother',
					'section' => 'rendering',
					'options' => $stubThresholdOptions,
					'label' => wfMsg('stub-threshold'), // Raw HTML message. Yay?
				);
		$defaultPreferences['highlightbroken'] =
				array(
					'type' => 'toggle',
					'section' => 'rendering',
					'label' => wfMsg('tog-highlightbroken'), // Raw HTML
				);
		$defaultPreferences['showtoc'] =
				array(
					'type' => 'toggle',
					'section' => 'rendering',
					'label-message' => 'tog-showtoc',
				);
		$defaultPreferences['nocache'] =
				array(
					'type' => 'toggle',
					'label-message' => 'tog-nocache',
					'section' => 'rendering',
				);
		$defaultPreferences['showhiddencats'] =
				array(
					'type' => 'toggle',
					'section' => 'rendering',
					'label-message' => 'tog-showhiddencats'
				);
		$defaultPreferences['showjumplinks'] =
				array(
					'type' => 'toggle',
					'section' => 'rendering',
					'label-message' => 'tog-showjumplinks',
				);
		$defaultPreferences['justify'] =
				array(
					'type' => 'toggle',
					'section' => 'rendering',
					'label-message' => 'tog-justify',
				);
		$defaultPreferences['numberheadings'] =
				array(
					'type' => 'toggle',
					'section' => 'rendering',
					'label-message' => 'tog-numberheadings',
				);
		
		## Editing #####################################
		$defaultPreferences['cols'] =
				array(
					'type' => 'int',
					'label-message' => 'columns',
					'section' => 'editing',
					'min' => 4,
					'max' => 1000,
				);
		$defaultPreferences['rows'] =
				array(
					'type' => 'int',
					'label-message' => 'rows',
					'section' => 'editing',
					'min' => 4,
					'max' => 1000,
				);
		$defaultPreferences['previewontop'] =
				array(
					'type' => 'toggle',
					'section' => 'editing',
					'label-message' => 'tog-previewontop',
				);
		$defaultPreferences['previewonfirst'] =
				array(
					'type' => 'toggle',
					'section' => 'editing',
					'label-message' => 'tog-previewonfirst',
				);
		$defaultPreferences['editsection'] =
				array(
					'type' => 'toggle',
					'section' => 'editing',
					'label-message' => 'tog-editsection',
				);
		$defaultPreferences['editsectiononrightclick'] =
				array(
					'type' => 'toggle',
					'section' => 'editing',
					'label-message' => 'tog-editsectiononrightclick',
				);
		$defaultPreferences['editondblclick'] =
				array(
					'type' => 'toggle',
					'section' => 'editing',
					'label-message' => 'tog-editondblclick',
				);
		$defaultPreferences['editwidth'] =
				array(
					'type' => 'toggle',
					'section' => 'editing',
					'label-message' => 'tog-editwidth',
				);
		$defaultPreferences['showtoolbar'] =
				array(
					'type' => 'toggle',
					'section' => 'editing',
					'label-message' => 'tog-showtoolbar',
				);
		$defaultPreferences['minordefault'] =
				array(
					'type' => 'toggle',
					'section' => 'editing',
					'label-message' => 'tog-minordefault',
				);
		$defaultPreferences['externaleditor'] =
				array(
					'type' => 'toggle',
					'section' => 'editing',
					'label-message' => 'tog-externaleditor',
				);
		$defaultPreferences['externaldiff'] =
				array(
					'type' => 'toggle',
					'section' => 'editing',
					'label-message' => 'tog-externaldiff',
				);
		$defaultPreferences['forceeditsummary'] =
				array(
					'type' => 'toggle',
					'section' => 'editing',
					'label-message' => 'tog-forceeditsummary',
				);
		$defaultPreferences['uselivepreview'] =
				array(
					'type' => 'toggle',
					'section' => 'editing',
					'label-message' => 'tog-uselivepreview',
				);
				
		## RecentChanges #####################################
		$defaultPreferences['rcdays'] =
				array(
					'type' => 'int',
					'label-message' => 'recentchangesdays',
					'section' => 'rc',
					'min' => 1,
					'max' => ceil($wgRCMaxAge / (3600*24)),
				);
		$defaultPreferences['rclimit'] =
				array(
					'type' => 'int',
					'label-message' => 'recentchangescount',
					'section' => 'rc',
				);
		$defaultPreferences['usenewrc'] =
				array(
					'type' => 'toggle',
					'label-message' => 'tog-usenewrc',
					'section' => 'rc',
				);
		$defaultPreferences['hideminor'] =
				array(
					'type' => 'toggle',
					'label-message' => 'tog-hideminor',
					'section' => 'rc',
				);
				
		global $wgUseRCPatrol;
		if ($wgUseRCPatrol) {
			$defaultPreferences['hidepatrolled'] =
					array(
						'type' => 'toggle',
						'section' => 'rc',
						'label-message' => 'tog-hidepatrolled',
					);
			$defaultPreferences['newpageshidepatrolled'] =
					array(
						'type' => 'toggle',
						'section' => 'rc',
						'label-message' => 'tog-newpageshidepatrolled',
					);
		}
		
		global $wgRCShowWatchingUsers;
		if ($wgRCShowWatchingUsers) {
			$defaultPreferences['shownumberswatching'] =
					array(
						'type' => 'toggle',
						'section' => 'rc',
						'label-message' => 'tog-shownumberswatching',
					);
		}
				
		## Watchlist #####################################
		$defaultPreferences['wllimit'] =
				array(
					'type' => 'int',
					'min' => 0,
					'max' => 1000,
					'label-message' => 'prefs-watchlist-edits',
					'section' => 'watchlist'
				);
		$defaultPreferences['watchlistdays'] =
				array(
					'type' => 'int',
					'min' => 0,
					'max' => 7,
					'section' => 'watchlist',
					'label-message' => 'prefs-watchlist-days',
				);
		$defaultPreferences['extendwatchlist'] =
				array(
					'type' => 'toggle',
					'section' => 'watchlist',
					'label-message' => 'tog-extendwatchlist',
				);
		$defaultPreferences['watchlisthideminor'] =
				array(
					'type' => 'toggle',
					'section' => 'watchlist',
					'label-message' => 'tog-watchlisthideminor',
				);
		$defaultPreferences['watchlisthidebots'] =
				array(
					'type' => 'toggle',
					'section' => 'watchlist',
					'label-message' => 'tog-watchlisthidebots',
				);
		$defaultPreferences['watchlisthideown'] =
				array(
					'type' => 'toggle',
					'section' => 'watchlist',
					'label-message' => 'tog-watchlisthideown',
				);
		$defaultPreferences['watchlisthideanons'] =
				array(
					'type' => 'toggle',
					'section' => 'watchlist',
					'label-message' => 'tog-watchlisthideanons',
				);
		$defaultPreferences['watchlisthideliu'] =
				array(
					'type' => 'toggle',
					'section' => 'watchlist',
					'label-message' => 'tog-watchlisthideliu',
				);
		
		if ( $wgUseRCPatrol ) {
			$defaultPreferences['watchlisthidepatrolled'] =
					array(
						'type' => 'toggle',
						'section' => 'watchlist',
						'label-message' => 'tog-watchlisthidepatrolled',
					);
		}
		
		$watchTypes = array( 'edit' => 'watchdefault',
								'move' => 'watchmoves',
								'delete' => 'watchdeletion' );
		
		// Kinda hacky
		if( $user->isAllowed( 'createpage' ) || $user->isAllowed( 'createtalk' ) ) {
			$watchTypes['read'] = 'watchcreations';
		}
								
		foreach( $watchTypes as $action => $pref ) {
			if ( $user->isAllowed( $action ) ) {
				$defaultPreferences[$pref] = array(
					'type' => 'toggle',
					'section' => 'watchlist',
					'label-message' => "tog-$pref",
				);
			}
		}
		
		## Search #####################################
		$defaultPreferences['searchlimit'] =
				array(
					'type' => 'int',
					'label-message' => 'resultsperpage',
					'section' => 'searchoptions',
					'min' => 0,
				);
		$defaultPreferences['contextlines'] =
				array(
					'type' => 'int',
					'label-message' => 'contextlines',
					'section' => 'searchoptions',
					'min' => 0,
				);
		$defaultPreferences['contextchars'] =
				array(
					'type' => 'int',
					'label-message' => 'contextchars',
					'section' => 'searchoptions',
					'min' => 0,
				);
		
		// Searchable namespaces back-compat with old format
		$searchableNamespaces = SearchEngine::searchableNamespaces();
		
		$nsOptions = array();
		foreach( $wgContLang->getNamespaces() as $ns => $name ) {
			if ($ns < 0) continue;
			$displayNs = str_replace( '_', ' ', $name );
			
			if (!$displayNs) $displayNs = wfMsg( 'blanknamespace' );
			
			$nsOptions[$displayNs] = $ns;
		}
		
		$defaultPreferences['searchnamespaces'] =
				array(
					'type' => 'multiselect',
					'label-message' => 'defaultns',
					'options' => $nsOptions,
					'section' => 'searchoptions',
					'prefix' => 'searchNs',
				);
				
		global $wgEnableMWSuggest;
		if ($wgEnableMWSuggest) {
			$defaultPreferences['disablesuggest'] =
					array(
						'type' => 'toggle',
						'label-message' => 'mwsuggest-disable',
						'section' => 'searchoptions',
					);
		}
		
		## Misc #####################################
		$defaultPreferences['diffonly'] =
				array(
					'type' => 'toggle',
					'section' => 'misc',
					'label-message' => 'tog-diffonly',
				);
		$defaultPreferences['norollbackdiff'] =
				array(
					'type' => 'toggle',
					'section' => 'misc',
					'label-message' => 'tog-norollbackdiff',
				);
				
		wfRunHooks( 'GetPreferences', array( $user, &$defaultPreferences ) );
				
		## Prod in defaults from the user
		global $wgDefaultUserOptions;
		foreach( $defaultPreferences as $name => &$info ) {
			$prefFromUser = self::getOptionFromUser( $name, $info, $user );
			$field = HTMLForm::loadInputFromParameters( $info ); // For validation
			$globalDefault = isset($wgDefaultUserOptions[$name])
								? $wgDefaultUserOptions[$name]
								: null;
			
			// If it validates, set it as the default
			if ( isset($info['default']) ) {
				// Already set, no problem
				continue;
			} elseif ( !is_null( $prefFromUser ) && // Make sure we're not just pulling nothing
					$field->validate( $prefFromUser, $user->mOptions ) ) {
				$info['default'] = $prefFromUser;
			} elseif( $field->validate( $globalDefault, $user->mOptions ) ) {
				$info['default'] = $globalDefault;
			}
		}
		
		self::$defaultPreferences = $defaultPreferences;
		
		return $defaultPreferences;
	}
	
	// Pull option from a user account. Handles stuff like array-type preferences.
	static function getOptionFromUser( $name, $info, $user ) {
		$val = $user->getOption( $name );
		
		// Handling for array-type preferences
		if ( ( isset($info['type']) && $info['type'] == 'multiselect' ) ||
				( isset($info['class']) && $info['class'] == 'HTMLMultiSelectField' ) ) {

			$options = HTMLFormField::flattenOptions($info['options']);
			$prefix = isset($info['prefix']) ? $info['prefix'] : $name;
			$val = array();
			
			foreach( $options as $label => $value ) {
				if ($user->getOption( "$prefix$value" ) ) {
					$val[] = $value;
				}
			}
		}
	
		return $val;
	}
	
	static function generateSkinOptions( $user ) {
		global $wgDefaultSkin;
		$ret = array();
		
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
		$sk = $user->getSkin();

		foreach( $validSkinNames as $skinkey => $sn ) {
			$mplink = htmlspecialchars( $mptitle->getLocalURL( "useskin=$skinkey" ) );
			$previewlink = "(<a target='_blank' href=\"$mplink\">$previewtext</a>)";
			$extraLinks = '';
			global $wgAllowUserCss, $wgAllowUserJs;
			if( $wgAllowUserCss ) {
				$cssPage = Title::makeTitleSafe( NS_USER, $user->getName().'/'.$skinkey.'.css' );
				$customCSS = $sk->makeLinkObj( $cssPage, wfMsgExt('prefs-custom-css', array() ) );
				$extraLinks .= " ($customCSS)";
			}
			if( $wgAllowUserJs ) {
				$jsPage = Title::makeTitleSafe( NS_USER, $user->getName().'/'.$skinkey.'.js' );
				$customJS = $sk->makeLinkObj( $jsPage, wfMsgHtml('prefs-custom-js') );
				$extraLinks .= " ($customJS)";
			}
			if( $skinkey == $wgDefaultSkin )
				$sn .= ' (' . wfMsg( 'default' ) . ')';
			$display = "$sn $previewlink{$extraLinks}";
			$ret[$display] = $skinkey;
		}
		
		return $ret;
	}
	
	static function getDateOptions() {
		global $wgLang;
		$dateopts = $wgLang->getDatePreferences();
		
		$ret = array();
		
		if ($dateopts) {
			$idCnt = 0;
			$epoch = '20010115161234'; # Wikipedia day
			foreach( $dateopts as $key ) {
				if( $key == 'default' ) {
					$formatted = wfMsg( 'datedefault' );
				} else {
					$formatted = $wgLang->timeanddate( $epoch, false, $key );
				}
				$ret[$formatted] = $key;
			}
		}
		return $ret;
	}
	
	static function getImageSizes() {
		global $wgImageLimits;
		
		$ret = array();
		
		foreach ( $wgImageLimits as $index => $limits ) {
			$display = "{$limits[0]}×{$limits[1]}" . wfMsg('unit-pixel');
			$ret[$display] = $index;
		}
		
		return $ret;
	}
	
	static function getThumbSizes() {
		global $wgThumbLimits;
		
		$ret = array();
		
		foreach ( $wgThumbLimits as $index => $size ) {
			$display = $size . wfMsg('unit-pixel');
			$ret[$display] = $index;
		}
		
		return $ret;
	}
	
	static function validateSignature( $signature, $alldata ) {
		global $wgParser, $wgMaxSigChars, $wgLang;
		if( mb_strlen( $signature ) > $wgMaxSigChars ) {
			return
				Xml::element( 'span', array( 'class' => 'error' ),
					wfMsgExt( 'badsiglength', 'parsemag',
						$wgLang->formatNum( $wgMaxSigChars )
					)
				);
		} elseif( !empty( $alldata['fancysig'] ) &&
				false === $wgParser->validateSig( $signature ) ) {
			return Xml::element( 'span', array( 'class' => 'error' ), wfMsg( 'badsig' ) );
		} else {
			return true;
		}
	}
	
	static function cleanSignature( $signature, $alldata ) {
		global $wgParser;
		if( $alldata['fancysig'] ) {
			$signature = $wgParser->cleanSig( $signature );
		} else {
			// When no fancy sig used, make sure ~{3,5} get removed.
			$signature = $wgParser->cleanSigInSig( $signature );
		}
		
		return $signature;
	}
	
	static function validateEmail( $email, $alldata ) {
		global $wgUser; // To check
		
		if ( $email && !$wgUser->isValidEmailAddr( $email ) ) {
			return wfMsgExt( 'invalidemailaddress', 'parseinline' );
		}
		
		global $wgEmailConfirmToEdit;
		if( $wgEmailConfirmToEdit && !$email ) {
			return wfMsgExt( 'noemailtitle', 'parseinline' );
		}
		return true;
	}
	
	static function getFormObject( $user ) {
		$formDescriptor = Preferences::getPreferences( $user );
		$htmlForm = new PreferencesForm( $formDescriptor, 'prefs' );
		
		$htmlForm->setSubmitText( wfMsg('saveprefs') );
		$htmlForm->setTitle( SpecialPage::getTitleFor( 'Preferences' ) );
		$htmlForm->setSubmitID( 'prefsubmit' );
		$htmlForm->setSubmitCallback( array( 'Preferences', 'tryFormSubmit' ) );
		
		return $htmlForm;
	}
	
	static function getTimezoneOptions() {
		$opt = array();
		
		global $wgLocalTZoffset;

		$opt[wfMsg( 'timezoneuseserverdefault' )] = "System|$wgLocalTZoffset";
		$opt[wfMsg( 'timezoneuseoffset' )] = 'other';
		$opt[wfMsg( 'guesstimezone' )] = 'guess';

		if ( function_exists( 'timezone_identifiers_list' ) ) {
			# Read timezone list
			$tzs = timezone_identifiers_list();
			sort( $tzs );

			$tzRegions = array();
			$tzRegions['Africa'] = wfMsg( 'timezoneregion-africa' );
			$tzRegions['America'] = wfMsg( 'timezoneregion-america' );
			$tzRegions['Antarctica'] = wfMsg( 'timezoneregion-antarctica' );
			$tzRegions['Arctic'] = wfMsg( 'timezoneregion-arctic' );
			$tzRegions['Asia'] = wfMsg( 'timezoneregion-asia' );
			$tzRegions['Atlantic'] = wfMsg( 'timezoneregion-atlantic' );
			$tzRegions['Australia'] = wfMsg( 'timezoneregion-australia' );
			$tzRegions['Europe'] = wfMsg( 'timezoneregion-europe' );
			$tzRegions['Indian'] = wfMsg( 'timezoneregion-indian' );
			$tzRegions['Pacific'] = wfMsg( 'timezoneregion-pacific' );
			asort( $tzRegions );
			
			$prefill = array_fill_keys( array_values($tzRegions), array() );
			$opt = array_merge( $opt, $prefill );

			$now = date_create( 'now' );

			foreach ( $tzs as $tz ) {
				$z = explode( '/', $tz, 2 );

				# timezone_identifiers_list() returns a number of
				# backwards-compatibility entries. This filters them out of the 
				# list presented to the user.
				if ( count( $z ) != 2 || !array_key_exists( $z[0], $tzRegions ) )
					continue;

				# Localize region
				$z[0] = $tzRegions[$z[0]];

				$minDiff = floor( timezone_offset_get( timezone_open( $tz ), $now ) / 60 );
				
				$display = str_replace( '_', ' ', $z[0] . '/' . $z[1] );
				$value = "ZoneInfo|$minDiff|$tz";
				
				$opt[$z[0]][$display] = $value;
			}
		}
		return $opt;
	}
	
	static function filterTimezoneInput( $tz, $alldata ) {
		$data = explode( '|', $tz, 3 );
		switch ( $data[0] ) {
			case 'ZoneInfo':
			case 'System':
				return $tz;
			default:
				$data = explode( ':', $tz, 2 );
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
	
	static function tryFormSubmit( $formData ) {
		global $wgUser, $wgEmailAuthentication, $wgEnableEmail;
		
		// Filter input
		foreach( array_keys($formData) as $name ) {
			if ( isset(self::$saveFilters[$name]) ) {
				$formData[$name] =
					call_user_func( self::$saveFilters[$name], $formData[$name], $formData );
			}
		}
		
		// Stuff that shouldn't be saved as a preference.
		$saveBlacklist = array(
				'realname',
				'emailaddress',
			);
		
		if( $wgEnableEmail ) {
			$newadr = $formData['emailaddress'];
			$oldadr = $wgUser->getEmail();
			if( ($newadr != '') && ($newadr != $oldadr) ) {
				# the user has supplied a new email address on the login page
				# new behaviour: set this new emailaddr from login-page into user database record
				$wgUser->setEmail( $newadr );
				# but flag as "dirty" = unauthenticated
				$wgUser->invalidateEmail();
				if ($wgEmailAuthentication) {
					# Mail a temporary password to the dirty address.
					# User can come back through the confirmation URL to re-enable email.
					$result = $wgUser->sendConfirmationMail();
					if( WikiError::isError( $result ) ) {
						return wfMsg( 'mailerror', htmlspecialchars( $result->getMessage() ) );
					} else {
						// TODO return this somehow
#						wfMsg( 'eauthentsent', $wgUser->getName() );
					}
				}
			} else {
				$wgUser->setEmail( $newadr );
			}
			if( $oldadr != $newadr ) {
				wfRunHooks( 'PrefsEmailAudit', array( $wgUser, $oldadr, $newadr ) );
			}
		}
		
		// Fortunately, the realname field is MUCH simpler
		global $wgAllowRealName;
		if ($wgAllowRealName) {
			$realName = $formData['realname'];
			$wgUser->setRealName( $realName );
		}
		
		foreach( $saveBlacklist as $b )
			unset( $formData[$b] );
			
		//  Keeps old preferences from interfering due to back-compat
		//  code, etc.
		$wgUser->resetOptions();
		
		foreach( $formData as $key => $value ) {
			$wgUser->setOption( $key, $value );
		}
		
		$wgUser->saveSettings();
		
		// Done
		global $wgOut;
		$wgOut->redirect( SpecialPage::getTitleFor( 'Preferences' )->getFullURL( 'success' ) );
		
		return true;
	}
	
	public static function loadOldSearchNs( $user ) {
		$searchableNamespaces = SearchEngine::searchableNamespaces();
		// Back compat with old format
		$arr = array();
		
		foreach( $searchableNamespaces as $ns => $name ) {
			if( $user->getOption( 'searchNs' . $ns ) ) {
				$arr[] = $ns;
			}
		}
		
		return $arr;
	}
}

/** Some tweaks to allow js prefs to work */
class PreferencesForm extends HTMLForm {

	function wrapForm( $html ) {
		$html = Xml::tags( 'div', array( 'id' => 'preferences' ), $html );
		
		return parent::wrapForm( $html );
	}
	
	function getButtons() {
		$html = parent::getButtons();
		
		global $wgUser;
		
		$sk = $wgUser->getSkin();
		$t = SpecialPage::getTitleFor( 'Preferences', 'reset' );
		
		$html .= "\n" . $sk->link( $t, wfMsg( 'restoreprefs' ) );
		
		return $html;
	}
	
	function filterDataForSubmit( $data ) {
		// Support for separating MultiSelect preferences into multiple preferences
		// Due to lack of array support.
		foreach( $this->mFlatFields as $fieldname => $field ) {
			$info = $field->mParams;
			if ($field instanceof HTMLMultiSelectField) {
				$options = HTMLFormField::flattenOptions( $info['options'] );
				$prefix = isset($info['prefix']) ? $info['prefix'] : $fieldname;
				
				foreach( $options as $opt ) {
					$data["$prefix$opt"] = in_array( $opt, $data[$fieldname] );
				}
				
				unset( $data[$fieldname] );
			}
		}
		
		return $data;
	}
}
