<?php
/**
 * We're now using the HTMLForm object with some customisation to generate the
 * Preferences form. This object handles generic submission, CSRF protection,
 * layout and other logic in a reusable manner. We subclass it as a PreferencesForm
 * to make some minor customisations.
 *
 * In order to generate the form, the HTMLForm object needs an array structure
 * detailing the form fields available, and that's what this class is for. Each
 * element of the array is a basic property-list, including the type of field,
 * the label it is to be given in the form, callbacks for validation and
 * 'filtering', and other pertinent information. Note that the 'default' field
 * is named for generic forms, and does not represent the preference's default
 * (which is stored in $wgDefaultUserOptions), but the default for the form
 * field, which should be whatever the user has set for that preference. There
 * is no need to override it unless you have some special storage logic (for
 * instance, those not presently stored as options, but which are best set from
 * the user preferences view).
 *
 * Field types are implemented as subclasses of the generic HTMLFormField
 * object, and typically implement at least getInputHTML, which generates the
 * HTML for the input field to be placed in the table.
 *
 * Once fields have been retrieved and validated, submission logic is handed
 * over to the tryUISubmit static method of this class.
 */

class Preferences {
	static $defaultPreferences = null;
	static $saveFilters = array(
			'timecorrection' => array( 'Preferences', 'filterTimezoneInput' ),
			'cols' => array( 'Preferences', 'filterIntval' ),
			'rows' => array( 'Preferences', 'filterIntval' ),
			'rclimit' => array( 'Preferences', 'filterIntval' ),
			'wllimit' => array( 'Preferences', 'filterIntval' ),
			'searchlimit' => array( 'Preferences', 'filterIntval' ),
	);

	static function getPreferences( $user ) {
		if ( self::$defaultPreferences )
			return self::$defaultPreferences;

		$defaultPreferences = array();

		self::profilePreferences( $user, $defaultPreferences );
		self::skinPreferences( $user, $defaultPreferences );
		self::filesPreferences( $user, $defaultPreferences );
		self::mathPreferences( $user, $defaultPreferences );
		self::datetimePreferences( $user, $defaultPreferences );
		self::renderingPreferences( $user, $defaultPreferences );
		self::editingPreferences( $user, $defaultPreferences );
		self::rcPreferences( $user, $defaultPreferences );
		self::watchlistPreferences( $user, $defaultPreferences );
		self::searchPreferences( $user, $defaultPreferences );
		self::miscPreferences( $user, $defaultPreferences );

		wfRunHooks( 'GetPreferences', array( $user, &$defaultPreferences ) );

		## Remove preferences that wikis don't want to use
		global $wgHiddenPrefs;
		foreach ( $wgHiddenPrefs as $pref ) {
			if ( isset( $defaultPreferences[$pref] ) ) {
				unset( $defaultPreferences[$pref] );
			}
		}

		## Prod in defaults from the user
		foreach ( $defaultPreferences as $name => &$info ) {
			$prefFromUser = self::getOptionFromUser( $name, $info, $user );
			$field = HTMLForm::loadInputFromParameters( $name, $info ); // For validation
			$defaultOptions = User::getDefaultOptions();
			$globalDefault = isset( $defaultOptions[$name] )
				? $defaultOptions[$name]
				: null;

			// If it validates, set it as the default
			if ( isset( $info['default'] ) ) {
				// Already set, no problem
				continue;
			} elseif ( !is_null( $prefFromUser ) && // Make sure we're not just pulling nothing
					$field->validate( $prefFromUser, $user->mOptions ) === true ) {
				$info['default'] = $prefFromUser;
			} elseif ( $field->validate( $globalDefault, $user->mOptions ) === true ) {
				$info['default'] = $globalDefault;
			} else {
				throw new MWException( "Global default '$globalDefault' is invalid for field $name" );
			}
		}

		self::$defaultPreferences = $defaultPreferences;

		return $defaultPreferences;
	}

	// Pull option from a user account. Handles stuff like array-type preferences.
	static function getOptionFromUser( $name, $info, $user ) {
		$val = $user->getOption( $name );

		// Handling for array-type preferences
		if ( ( isset( $info['type'] ) && $info['type'] == 'multiselect' ) ||
				( isset( $info['class'] ) && $info['class'] == 'HTMLMultiSelectField' ) ) {
			$options = HTMLFormField::flattenOptions( $info['options'] );
			$prefix = isset( $info['prefix'] ) ? $info['prefix'] : $name;
			$val = array();

			foreach ( $options as $value ) {
				if ( $user->getOption( "$prefix$value" ) ) {
					$val[] = $value;
				}
			}
		}

		return $val;
	}

	static function profilePreferences( $user, &$defaultPreferences ) {
		global $wgLang, $wgUser;
		## User info #####################################
		// Information panel
		$defaultPreferences['username'] = array(
			'type' => 'info',
			'label-message' => 'username',
			'default' => $user->getName(),
			'section' => 'personal/info',
		);

		$defaultPreferences['userid'] = array(
			'type' => 'info',
			'label-message' => 'uid',
			'default' => $user->getId(),
			'section' => 'personal/info',
		);

		# Get groups to which the user belongs
		$userEffectiveGroups = $user->getEffectiveGroups();
		$userGroups = $userMembers = array();
		foreach ( $userEffectiveGroups as $ueg ) {
			if ( $ueg == '*' ) {
				// Skip the default * group, seems useless here
				continue;
			}
			$groupName  = User::getGroupName( $ueg );
			$userGroups[] = User::makeGroupLinkHTML( $ueg, $groupName );

			$memberName = User::getGroupMember( $ueg );
			$userMembers[] = User::makeGroupLinkHTML( $ueg, $memberName );
		}
		asort( $userGroups );
		asort( $userMembers );

		$defaultPreferences['usergroups'] = array(
			'type' => 'info',
			'label' => wfMsgExt(
				'prefs-memberingroups', 'parseinline',
				$wgLang->formatNum( count( $userGroups ) )
			),
			'default' => wfMsgExt(
				'prefs-memberingroups-type', array(),
				$wgLang->commaList( $userGroups ),
				$wgLang->commaList( $userMembers )
			),
			'raw' => true,
			'section' => 'personal/info',
		);

		$defaultPreferences['editcount'] = array(
			'type' => 'info',
			'label-message' => 'prefs-edits',
			'default' => $wgLang->formatNum( $user->getEditCount() ),
			'section' => 'personal/info',
		);

		if ( $user->getRegistration() ) {
			$defaultPreferences['registrationdate'] = array(
				'type' => 'info',
				'label-message' => 'prefs-registration',
				'default' => wfMsgExt(
					'prefs-registration-date-time', 'parsemag',
					$wgLang->timeanddate( $user->getRegistration(), true ),
					$wgLang->date( $user->getRegistration(), true ),
					$wgLang->time( $user->getRegistration(), true )
				),
				'section' => 'personal/info',
			);
		}

		// Actually changeable stuff
		global $wgAuth;
		$defaultPreferences['realname'] = array(
			'type' => $wgAuth->allowPropChange( 'realname' ) ? 'text' : 'info',
			'default' => $user->getRealName(),
			'section' => 'personal/info',
			'label-message' => 'yourrealname',
			'help-message' => 'prefs-help-realname',
		);

		$defaultPreferences['gender'] = array(
			'type' => 'select',
			'section' => 'personal/info',
			'options' => array(
				wfMsg( 'gender-male' ) => 'male',
				wfMsg( 'gender-female' ) => 'female',
				wfMsg( 'gender-unknown' ) => 'unknown',
			),
			'label-message' => 'yourgender',
			'help-message' => 'prefs-help-gender',
		);

		if ( $wgAuth->allowPasswordChange() ) {
			$link = $wgUser->getSkin()->link( SpecialPage::getTitleFor( 'Resetpass' ),
				wfMsgHtml( 'prefs-resetpass' ), array(),
				array( 'returnto' => SpecialPage::getTitleFor( 'Preferences' ) ) );

			$defaultPreferences['password'] = array(
				'type' => 'info',
				'raw' => true,
				'default' => $link,
				'label-message' => 'yourpassword',
				'section' => 'personal/info',
			);
		}
		global $wgCookieExpiration;
		if ( $wgCookieExpiration > 0 ) {
			$defaultPreferences['rememberpassword'] = array(
				'type' => 'toggle',
				'label' => wfMsgExt(
					'tog-rememberpassword',
					array( 'parsemag' ),
					$wgLang->formatNum( ceil( $wgCookieExpiration / ( 3600 * 24 ) ) )
					),
				'section' => 'personal/info',
			);
		}

		// Language
		global $wgLanguageCode;
		$languages = Language::getLanguageNames( false );
		if ( !array_key_exists( $wgLanguageCode, $languages ) ) {
			$languages[$wgLanguageCode] = $wgLanguageCode;
		}
		ksort( $languages );

		$options = array();
		foreach ( $languages as $code => $name ) {
			$display = wfBCP47( $code ) . ' - ' . $name;
			$options[$display] = $code;
		}
		$defaultPreferences['language'] = array(
			'type' => 'select',
			'section' => 'personal/i18n',
			'options' => $options,
			'label-message' => 'yourlanguage',
		);

		global $wgContLang, $wgDisableLangConversion;
		global $wgDisableTitleConversion;
		/* see if there are multiple language variants to choose from*/
		$variantArray = array();
		if ( !$wgDisableLangConversion ) {
			$variants = $wgContLang->getVariants();

			$languages = Language::getLanguageNames( true );
			foreach ( $variants as $v ) {
				$v = str_replace( '_', '-', strtolower( $v ) );
				if ( array_key_exists( $v, $languages ) ) {
					// If it doesn't have a name, we'll pretend it doesn't exist
					$variantArray[$v] = $languages[$v];
				}
			}

			$options = array();
			foreach ( $variantArray as $code => $name ) {
				$display = wfBCP47( $code ) . ' - ' . $name;
				$options[$display] = $code;
			}

			if ( count( $variantArray ) > 1 ) {
				$defaultPreferences['variant'] = array(
					'label-message' => 'yourvariant',
					'type' => 'select',
					'options' => $options,
					'section' => 'personal/i18n',
				);
			}
		}

		if ( count( $variantArray ) > 1 && !$wgDisableLangConversion && !$wgDisableTitleConversion ) {
			$defaultPreferences['noconvertlink'] =
					array(
				'type' => 'toggle',
				'section' => 'personal/i18n',
				'label-message' => 'tog-noconvertlink',
			);
		}

		global $wgMaxSigChars, $wgOut, $wgParser;

		// show a preview of the old signature first
		$oldsigWikiText = $wgParser->preSaveTransform( "~~~", new Title , $user, new ParserOptions );
		$oldsigHTML = $wgOut->parseInline( $oldsigWikiText );
		$defaultPreferences['oldsig'] = array(
			'type' => 'info',
			'raw' => true,
			'label-message' => 'tog-oldsig',
			'default' => $oldsigHTML,
			'section' => 'personal/signature',
		);
		$defaultPreferences['nickname'] = array(
			'type' => $wgAuth->allowPropChange( 'nickname' ) ? 'text' : 'info',
			'maxlength' => $wgMaxSigChars,
			'label-message' => 'yournick',
			'validation-callback' => array( 'Preferences', 'validateSignature' ),
			'section' => 'personal/signature',
			'filter-callback' => array( 'Preferences', 'cleanSignature' ),
		);
		$defaultPreferences['fancysig'] = array(
			'type' => 'toggle',
			'label-message' => 'tog-fancysig',
			'help-message' => 'prefs-help-signature', // show general help about signature at the bottom of the section
			'section' => 'personal/signature'
		);

		## Email stuff

		global $wgEnableEmail;
		if ( $wgEnableEmail ) {
			global $wgEmailConfirmToEdit;
			global $wgEnableUserEmail;

			$helpMessages[] = $wgEmailConfirmToEdit
					? 'prefs-help-email-required'
					: 'prefs-help-email' ;

			if( $wgEnableUserEmail ) {
				// additional messages when users can send email to each other
				$helpMessages[] = 'prefs-help-email-others';
			}

			$defaultPreferences['emailaddress'] = array(
				'type' => $wgAuth->allowPropChange( 'emailaddress' ) ? 'email' : 'info',
				'default' => $user->getEmail(),
				'section' => 'personal/email',
				'label-message' => 'youremail',
				'help-messages' => $helpMessages,
				'validation-callback' => array( 'Preferences', 'validateEmail' ),
			);

			global $wgEmailAuthentication;

			$disableEmailPrefs = false;

			if ( $wgEmailAuthentication ) {
				if ( $user->getEmail() ) {
					if ( $user->getEmailAuthenticationTimestamp() ) {
						// date and time are separate parameters to facilitate localisation.
						// $time is kept for backward compat reasons.
						// 'emailauthenticated' is also used in SpecialConfirmemail.php
						$time = $wgLang->timeAndDate( $user->getEmailAuthenticationTimestamp(), true );
						$d = $wgLang->date( $user->getEmailAuthenticationTimestamp(), true );
						$t = $wgLang->time( $user->getEmailAuthenticationTimestamp(), true );
						$emailauthenticated = wfMsgExt(
							'emailauthenticated', 'parseinline',
							array( $time, $d, $t )
						) . '<br />';
						$disableEmailPrefs = false;
					} else {
						$disableEmailPrefs = true;
						$skin = $wgUser->getSkin();
						$emailauthenticated = wfMsgExt( 'emailnotauthenticated', 'parseinline' ) . '<br />' .
							$skin->link(
								SpecialPage::getTitleFor( 'Confirmemail' ),
								wfMsg( 'emailconfirmlink' ),
								array(),
								array(),
								array( 'known', 'noclasses' )
							) . '<br />';
					}
				} else {
					$disableEmailPrefs = true;
					$emailauthenticated = wfMsgHtml( 'noemailprefs' );
				}

				$defaultPreferences['emailauthentication'] = array(
					'type' => 'info',
					'raw' => true,
					'section' => 'personal/email',
					'label-message' => 'prefs-emailconfirm-label',
					'default' => $emailauthenticated,
				);

			}

			if ( $wgEnableUserEmail && $user->isAllowed( 'sendemail' ) ) {
				$defaultPreferences['disablemail'] = array(
					'type' => 'toggle',
					'invert' => true,
					'section' => 'personal/email',
					'label-message' => 'allowemail',
					'disabled' => $disableEmailPrefs,
				);
				$defaultPreferences['ccmeonemails'] = array(
					'type' => 'toggle',
					'section' => 'personal/email',
					'label-message' => 'tog-ccmeonemails',
					'disabled' => $disableEmailPrefs,
				);
			}

			global $wgEnotifWatchlist;
			if ( $wgEnotifWatchlist ) {
				$defaultPreferences['enotifwatchlistpages'] = array(
					'type' => 'toggle',
					'section' => 'personal/email',
					'label-message' => 'tog-enotifwatchlistpages',
					'disabled' => $disableEmailPrefs,
				);
			}
			global $wgEnotifUserTalk;
			if ( $wgEnotifUserTalk ) {
				$defaultPreferences['enotifusertalkpages'] = array(
					'type' => 'toggle',
					'section' => 'personal/email',
					'label-message' => 'tog-enotifusertalkpages',
					'disabled' => $disableEmailPrefs,
				);
			}
			if ( $wgEnotifUserTalk || $wgEnotifWatchlist ) {
				$defaultPreferences['enotifminoredits'] = array(
					'type' => 'toggle',
					'section' => 'personal/email',
					'label-message' => 'tog-enotifminoredits',
					'disabled' => $disableEmailPrefs,
				);

				global $wgEnotifRevealEditorAddress;
				if ( $wgEnotifRevealEditorAddress ) {
					$defaultPreferences['enotifrevealaddr'] = array(
						'type' => 'toggle',
						'section' => 'personal/email',
						'label-message' => 'tog-enotifrevealaddr',
						'disabled' => $disableEmailPrefs,
					);
				}
			}
		}
	}

	static function skinPreferences( $user, &$defaultPreferences ) {
		## Skin #####################################
		global $wgLang, $wgAllowUserCss, $wgAllowUserJs;

		$defaultPreferences['skin'] = array(
			'type' => 'radio',
			'options' => self::generateSkinOptions( $user ),
			'label' => '&#160;',
			'section' => 'rendering/skin',
		);

		# Create links to user CSS/JS pages for all skins
		# This code is basically copied from generateSkinOptions().  It'd
		# be nice to somehow merge this back in there to avoid redundancy.
		if ( $wgAllowUserCss || $wgAllowUserJs ) {
			$sk = $user->getSkin();
			$linkTools = array();

			if ( $wgAllowUserCss ) {
				$cssPage = Title::makeTitleSafe( NS_USER, $user->getName() . '/common.css' );
				$linkTools[] = $sk->link( $cssPage, wfMsgHtml( 'prefs-custom-css' ) );
			}

			if ( $wgAllowUserJs ) {
				$jsPage = Title::makeTitleSafe( NS_USER, $user->getName() . '/common.js' );
				$linkTools[] = $sk->link( $jsPage, wfMsgHtml( 'prefs-custom-js' ) );
			}

			$defaultPreferences['commoncssjs'] = array(
				'type' => 'info',
				'raw' => true,
				'default' => $wgLang->pipeList( $linkTools ),
				'label-message' => 'prefs-common-css-js',
				'section' => 'rendering/skin',
			);
		}

		$selectedSkin = $user->getOption( 'skin' );
		if ( in_array( $selectedSkin, array( 'cologneblue', 'standard' ) ) ) {
			$settings = array_flip( $wgLang->getQuickbarSettings() );

			$defaultPreferences['quickbar'] = array(
				'type' => 'radio',
				'options' => $settings,
				'section' => 'rendering/skin',
				'label-message' => 'qbsettings',
			);
		}
	}

	static function mathPreferences( $user, &$defaultPreferences ) {
		## Math #####################################
		global $wgUseTeX, $wgLang;
		if ( $wgUseTeX ) {
			$defaultPreferences['math'] = array(
				'type' => 'radio',
				'options' => array_flip( array_map( 'wfMsgHtml', $wgLang->getMathNames() ) ),
				'label' => '&#160;',
				'section' => 'rendering/math',
			);
		}
	}

	static function filesPreferences( $user, &$defaultPreferences ) {
		## Files #####################################
		$defaultPreferences['imagesize'] = array(
			'type' => 'select',
			'options' => self::getImageSizes(),
			'label-message' => 'imagemaxsize',
			'section' => 'rendering/files',
		);
		$defaultPreferences['thumbsize'] = array(
			'type' => 'select',
			'options' => self::getThumbSizes(),
			'label-message' => 'thumbsize',
			'section' => 'rendering/files',
		);
	}

	static function datetimePreferences( $user, &$defaultPreferences ) {
		global $wgLang;

		## Date and time #####################################
		$dateOptions = self::getDateOptions();
		if ( $dateOptions ) {
			$defaultPreferences['date'] = array(
				'type' => 'radio',
				'options' => $dateOptions,
				'label' => '&#160;',
				'section' => 'datetime/dateformat',
			);
		}

		// Info
		$nowlocal = Xml::element( 'span', array( 'id' => 'wpLocalTime' ),
			$wgLang->time( $now = wfTimestampNow(), true ) );
		$nowserver = $wgLang->time( $now, false ) .
			Html::hidden( 'wpServerTime', substr( $now, 8, 2 ) * 60 + substr( $now, 10, 2 ) );

		$defaultPreferences['nowserver'] = array(
			'type' => 'info',
			'raw' => 1,
			'label-message' => 'servertime',
			'default' => $nowserver,
			'section' => 'datetime/timeoffset',
		);

		$defaultPreferences['nowlocal'] = array(
			'type' => 'info',
			'raw' => 1,
			'label-message' => 'localtime',
			'default' => $nowlocal,
			'section' => 'datetime/timeoffset',
		);

		// Grab existing pref.
		$tzOffset = $user->getOption( 'timecorrection' );
		$tz = explode( '|', $tzOffset, 2 );

		$tzSetting = $tzOffset;
		if ( count( $tz ) > 1 && $tz[0] == 'Offset' ) {
			$minDiff = $tz[1];
			$tzSetting = sprintf( '%+03d:%02d', floor( $minDiff / 60 ), abs( $minDiff ) % 60 );
		}

		$defaultPreferences['timecorrection'] = array(
			'class' => 'HTMLSelectOrOtherField',
			'label-message' => 'timezonelegend',
			'options' => self::getTimezoneOptions(),
			'default' => $tzSetting,
			'size' => 20,
			'section' => 'datetime/timeoffset',
		);
	}

	static function renderingPreferences( $user, &$defaultPreferences ) {
		## Page Rendering ##############################
		global $wgAllowUserCssPrefs;
		if ( $wgAllowUserCssPrefs ) {
			$defaultPreferences['underline'] = array(
				'type' => 'select',
				'options' => array(
					wfMsg( 'underline-never' ) => 0,
					wfMsg( 'underline-always' ) => 1,
					wfMsg( 'underline-default' ) => 2,
				),
				'label-message' => 'tog-underline',
				'section' => 'rendering/advancedrendering',
			);
		}

		$stubThresholdValues = array( 50, 100, 500, 1000, 2000, 5000, 10000 );
		$stubThresholdOptions = array( wfMsg( 'stub-threshold-disabled' ) => 0 );
		foreach ( $stubThresholdValues as $value ) {
			$stubThresholdOptions[wfMsg( 'size-bytes', $value )] = $value;
		}

		$defaultPreferences['stubthreshold'] = array(
			'type' => 'selectorother',
			'section' => 'rendering/advancedrendering',
			'options' => $stubThresholdOptions,
			'size' => 20,
			'label' => wfMsg( 'stub-threshold' ), // Raw HTML message. Yay?
		);

		if ( $wgAllowUserCssPrefs ) {
			$defaultPreferences['highlightbroken'] = array(
				'type' => 'toggle',
				'section' => 'rendering/advancedrendering',
				'label' => wfMsg( 'tog-highlightbroken' ), // Raw HTML
			);
			$defaultPreferences['showtoc'] = array(
				'type' => 'toggle',
				'section' => 'rendering/advancedrendering',
				'label-message' => 'tog-showtoc',
			);
		}
		$defaultPreferences['nocache'] = array(
			'type' => 'toggle',
			'label-message' => 'tog-nocache',
			'section' => 'rendering/advancedrendering',
		);
		$defaultPreferences['showhiddencats'] = array(
			'type' => 'toggle',
			'section' => 'rendering/advancedrendering',
			'label-message' => 'tog-showhiddencats'
		);
		$defaultPreferences['showjumplinks'] = array(
			'type' => 'toggle',
			'section' => 'rendering/advancedrendering',
			'label-message' => 'tog-showjumplinks',
		);

		if ( $wgAllowUserCssPrefs ) {
			$defaultPreferences['justify'] = array(
				'type' => 'toggle',
				'section' => 'rendering/advancedrendering',
				'label-message' => 'tog-justify',
			);
		}

		$defaultPreferences['numberheadings'] = array(
			'type' => 'toggle',
			'section' => 'rendering/advancedrendering',
			'label-message' => 'tog-numberheadings',
		);
	}

	static function editingPreferences( $user, &$defaultPreferences ) {
		global $wgUseExternalEditor, $wgAllowUserCssPrefs;

		## Editing #####################################
		$defaultPreferences['cols'] = array(
			'type' => 'int',
			'label-message' => 'columns',
			'section' => 'editing/textboxsize',
			'min' => 4,
			'max' => 1000,
		);
		$defaultPreferences['rows'] = array(
			'type' => 'int',
			'label-message' => 'rows',
			'section' => 'editing/textboxsize',
			'min' => 4,
			'max' => 1000,
		);

		if ( $wgAllowUserCssPrefs ) {
			$defaultPreferences['editfont'] = array(
				'type' => 'select',
				'section' => 'editing/advancedediting',
				'label-message' => 'editfont-style',
				'options' => array(
					wfMsg( 'editfont-default' ) => 'default',
					wfMsg( 'editfont-monospace' ) => 'monospace',
					wfMsg( 'editfont-sansserif' ) => 'sans-serif',
					wfMsg( 'editfont-serif' ) => 'serif',
				)
			);
		}
		$defaultPreferences['previewontop'] = array(
			'type' => 'toggle',
			'section' => 'editing/advancedediting',
			'label-message' => 'tog-previewontop',
		);
		$defaultPreferences['previewonfirst'] = array(
			'type' => 'toggle',
			'section' => 'editing/advancedediting',
			'label-message' => 'tog-previewonfirst',
		);

		if ( $wgAllowUserCssPrefs ) {
			$defaultPreferences['editsection'] = array(
				'type' => 'toggle',
				'section' => 'editing/advancedediting',
				'label-message' => 'tog-editsection',
			);
		}
		$defaultPreferences['editsectiononrightclick'] = array(
			'type' => 'toggle',
			'section' => 'editing/advancedediting',
			'label-message' => 'tog-editsectiononrightclick',
		);
		$defaultPreferences['editondblclick'] = array(
			'type' => 'toggle',
			'section' => 'editing/advancedediting',
			'label-message' => 'tog-editondblclick',
		);
		$defaultPreferences['showtoolbar'] = array(
			'type' => 'toggle',
			'section' => 'editing/advancedediting',
			'label-message' => 'tog-showtoolbar',
		);
		$defaultPreferences['minordefault'] = array(
			'type' => 'toggle',
			'section' => 'editing/advancedediting',
			'label-message' => 'tog-minordefault',
		);

		if ( $wgUseExternalEditor ) {
			$defaultPreferences['externaleditor'] = array(
				'type' => 'toggle',
				'section' => 'editing/advancedediting',
				'label-message' => 'tog-externaleditor',
			);
			$defaultPreferences['externaldiff'] = array(
				'type' => 'toggle',
				'section' => 'editing/advancedediting',
				'label-message' => 'tog-externaldiff',
			);
		}

		$defaultPreferences['forceeditsummary'] = array(
			'type' => 'toggle',
			'section' => 'editing/advancedediting',
			'label-message' => 'tog-forceeditsummary',
		);

				
		$defaultPreferences['uselivepreview'] = array(
			'type' => 'toggle',
			'section' => 'editing/advancedediting',
			'label-message' => 'tog-uselivepreview',
		);
	}

	static function rcPreferences( $user, &$defaultPreferences ) {
		global $wgRCMaxAge, $wgUseRCPatrol, $wgLang;

		## RecentChanges #####################################
		$defaultPreferences['rcdays'] = array(
			'type' => 'float',
			'label-message' => 'recentchangesdays',
			'section' => 'rc/displayrc',
			'min' => 1,
			'max' => ceil( $wgRCMaxAge / ( 3600 * 24 ) ),
			'help' => wfMsgExt(
				'recentchangesdays-max',
				array( 'parsemag' ),
				$wgLang->formatNum( ceil( $wgRCMaxAge / ( 3600 * 24 ) ) )
			)
		);
		$defaultPreferences['rclimit'] = array(
			'type' => 'int',
			'label-message' => 'recentchangescount',
			'help-message' => 'prefs-help-recentchangescount',
			'section' => 'rc/displayrc',
		);
		$defaultPreferences['usenewrc'] = array(
			'type' => 'toggle',
			'label-message' => 'tog-usenewrc',
			'section' => 'rc/advancedrc',
		);
		$defaultPreferences['hideminor'] = array(
			'type' => 'toggle',
			'label-message' => 'tog-hideminor',
			'section' => 'rc/advancedrc',
		);

		if ( $wgUseRCPatrol ) {
			$defaultPreferences['hidepatrolled'] = array(
				'type' => 'toggle',
				'section' => 'rc/advancedrc',
				'label-message' => 'tog-hidepatrolled',
			);
			$defaultPreferences['newpageshidepatrolled'] = array(
				'type' => 'toggle',
				'section' => 'rc/advancedrc',
				'label-message' => 'tog-newpageshidepatrolled',
			);
		}

		global $wgRCShowWatchingUsers;
		if ( $wgRCShowWatchingUsers ) {
			$defaultPreferences['shownumberswatching'] = array(
				'type' => 'toggle',
				'section' => 'rc/advancedrc',
				'label-message' => 'tog-shownumberswatching',
			);
		}
	}

	static function watchlistPreferences( $user, &$defaultPreferences ) {
		global $wgUseRCPatrol, $wgEnableAPI;

		## Watchlist #####################################
		$defaultPreferences['watchlistdays'] = array(
			'type' => 'float',
			'min' => 0,
			'max' => 7,
			'section' => 'watchlist/displaywatchlist',
			'help' => wfMsgHtml( 'prefs-watchlist-days-max' ),
			'label-message' => 'prefs-watchlist-days',
		);
		$defaultPreferences['wllimit'] = array(
			'type' => 'int',
			'min' => 0,
			'max' => 1000,
			'label-message' => 'prefs-watchlist-edits',
			'help' => wfMsgHtml( 'prefs-watchlist-edits-max' ),
			'section' => 'watchlist/displaywatchlist',
		);
		$defaultPreferences['extendwatchlist'] = array(
			'type' => 'toggle',
			'section' => 'watchlist/advancedwatchlist',
			'label-message' => 'tog-extendwatchlist',
		);
		$defaultPreferences['watchlisthideminor'] = array(
			'type' => 'toggle',
			'section' => 'watchlist/advancedwatchlist',
			'label-message' => 'tog-watchlisthideminor',
		);
		$defaultPreferences['watchlisthidebots'] = array(
			'type' => 'toggle',
			'section' => 'watchlist/advancedwatchlist',
			'label-message' => 'tog-watchlisthidebots',
		);
		$defaultPreferences['watchlisthideown'] = array(
			'type' => 'toggle',
			'section' => 'watchlist/advancedwatchlist',
			'label-message' => 'tog-watchlisthideown',
		);
		$defaultPreferences['watchlisthideanons'] = array(
			'type' => 'toggle',
			'section' => 'watchlist/advancedwatchlist',
			'label-message' => 'tog-watchlisthideanons',
		);
		$defaultPreferences['watchlisthideliu'] = array(
			'type' => 'toggle',
			'section' => 'watchlist/advancedwatchlist',
			'label-message' => 'tog-watchlisthideliu',
		);

		if ( $wgUseRCPatrol ) {
			$defaultPreferences['watchlisthidepatrolled'] = array(
				'type' => 'toggle',
				'section' => 'watchlist/advancedwatchlist',
				'label-message' => 'tog-watchlisthidepatrolled',
			);
		}

		if ( $wgEnableAPI ) {
			# Some random gibberish as a proposed default
			$hash = sha1( mt_rand() . microtime( true ) );

			$defaultPreferences['watchlisttoken'] = array(
				'type' => 'text',
				'section' => 'watchlist/advancedwatchlist',
				'label-message' => 'prefs-watchlist-token',
				'help' => wfMsgHtml( 'prefs-help-watchlist-token', $hash )
			);
		}

		$watchTypes = array(
			'edit' => 'watchdefault',
			'move' => 'watchmoves',
			'delete' => 'watchdeletion'
		);

		// Kinda hacky
		if ( $user->isAllowed( 'createpage' ) || $user->isAllowed( 'createtalk' ) ) {
			$watchTypes['read'] = 'watchcreations';
		}

		foreach ( $watchTypes as $action => $pref ) {
			if ( $user->isAllowed( $action ) ) {
				$defaultPreferences[$pref] = array(
					'type' => 'toggle',
					'section' => 'watchlist/advancedwatchlist',
					'label-message' => "tog-$pref",
				);
			}
		}
	}

	static function searchPreferences( $user, &$defaultPreferences ) {
		global $wgContLang;

		## Search #####################################
		$defaultPreferences['searchlimit'] = array(
			'type' => 'int',
			'label-message' => 'resultsperpage',
			'section' => 'searchoptions/displaysearchoptions',
			'min' => 0,
		);
		$defaultPreferences['contextlines'] = array(
			'type' => 'int',
			'label-message' => 'contextlines',
			'section' => 'searchoptions/displaysearchoptions',
			'min' => 0,
		);
		$defaultPreferences['contextchars'] = array(
			'type' => 'int',
			'label-message' => 'contextchars',
			'section' => 'searchoptions/displaysearchoptions',
			'min' => 0,
		);

		global $wgEnableMWSuggest;
		if ( $wgEnableMWSuggest ) {
			$defaultPreferences['disablesuggest'] = array(
				'type' => 'toggle',
				'label-message' => 'mwsuggest-disable',
				'section' => 'searchoptions/displaysearchoptions',
			);
		}
		
		global $wgVectorUseSimpleSearch;
		if ( $wgVectorUseSimpleSearch ) {
			$defaultPreferences['vector-simplesearch'] = array(
				'type' => 'toggle',
				'label-message' => 'vector-simplesearch-preference',
				'section' => 'searchoptions/displaysearchoptions'
			);
		}

		$defaultPreferences['searcheverything'] = array(
			'type' => 'toggle',
			'label-message' => 'searcheverything-enable',
			'section' => 'searchoptions/advancedsearchoptions',
		);

		// Searchable namespaces back-compat with old format
		$searchableNamespaces = SearchEngine::searchableNamespaces();

		$nsOptions = array();

		foreach ( $wgContLang->getNamespaces() as $ns => $name ) {
			if ( $ns < 0 ) {
				continue;
			}

			$displayNs = str_replace( '_', ' ', $name );

			if ( !$displayNs ) {
				$displayNs = wfMsg( 'blanknamespace' );
			}

			$displayNs = htmlspecialchars( $displayNs );
			$nsOptions[$displayNs] = $ns;
		}

		$defaultPreferences['searchnamespaces'] = array(
			'type' => 'multiselect',
			'label-message' => 'defaultns',
			'options' => $nsOptions,
			'section' => 'searchoptions/advancedsearchoptions',
			'prefix' => 'searchNs',
		);
	}

	static function miscPreferences( $user, &$defaultPreferences ) {
		## Misc #####################################
		$defaultPreferences['diffonly'] = array(
			'type' => 'toggle',
			'section' => 'misc/diffs',
			'label-message' => 'tog-diffonly',
		);
		$defaultPreferences['norollbackdiff'] = array(
			'type' => 'toggle',
			'section' => 'misc/diffs',
			'label-message' => 'tog-norollbackdiff',
		);

		// Stuff from Language::getExtraUserToggles()
		global $wgContLang;

		$toggles = $wgContLang->getExtraUserToggles();

		foreach ( $toggles as $toggle ) {
			$defaultPreferences[$toggle] = array(
				'type' => 'toggle',
				'section' => 'personal/i18n',
				'label-message' => "tog-$toggle",
			);
		}
	}

	/**
	 * @param $user The User object
	 * @return Array: text/links to display as key; $skinkey as value
	 */
	static function generateSkinOptions( $user ) {
		global $wgDefaultSkin, $wgLang, $wgAllowUserCss, $wgAllowUserJs;
		$ret = array();

		$mptitle = Title::newMainPage();
		$previewtext = wfMsgHtml( 'skin-preview' );

		# Only show members of Skin::getSkinNames() rather than
		# $skinNames (skins is all skin names from Language.php)
		$validSkinNames = Skin::getUsableSkins();

		# Sort by UI skin name. First though need to update validSkinNames as sometimes
		# the skinkey & UI skinname differ (e.g. "standard" skinkey is "Classic" in the UI).
		foreach ( $validSkinNames as $skinkey => &$skinname ) {
			$msg = wfMessage( "skinname-{$skinkey}" );
			if ( $msg->exists() ) {
				$skinname = htmlspecialchars( $msg->text() );
			}
		}
		asort( $validSkinNames );
		$sk = $user->getSkin();

		foreach ( $validSkinNames as $skinkey => $sn ) {
			$linkTools = array();

			# Mark the default skin
			if ( $skinkey == $wgDefaultSkin ) {
				$linkTools[] = wfMsgHtml( 'default' );
			}

			# Create preview link
			$mplink = htmlspecialchars( $mptitle->getLocalURL( "useskin=$skinkey" ) );
			$linkTools[] = "<a target='_blank' href=\"$mplink\">$previewtext</a>";

			# Create links to user CSS/JS pages
			if ( $wgAllowUserCss ) {
				$cssPage = Title::makeTitleSafe( NS_USER, $user->getName() . '/' . $skinkey . '.css' );
				$linkTools[] = $sk->link( $cssPage, wfMsgHtml( 'prefs-custom-css' ) );
			}

			if ( $wgAllowUserJs ) {
				$jsPage = Title::makeTitleSafe( NS_USER, $user->getName() . '/' . $skinkey . '.js' );
				$linkTools[] = $sk->link( $jsPage, wfMsgHtml( 'prefs-custom-js' ) );
			}

			$display = $sn . ' ' . wfMsg( 'parentheses', $wgLang->pipeList( $linkTools ) );
			$ret[$display] = $skinkey;
		}

		return $ret;
	}

	static function getDateOptions() {
		global $wgLang;
		$dateopts = $wgLang->getDatePreferences();

		$ret = array();

		if ( $dateopts ) {
			if ( !in_array( 'default', $dateopts ) ) {
				$dateopts[] = 'default'; // Make sure default is always valid
										// Bug 19237
			}

			// KLUGE: site default might not be valid for user language
			global $wgDefaultUserOptions;
			if ( !in_array( $wgDefaultUserOptions['date'], $dateopts ) ) {
				$wgDefaultUserOptions['date'] = 'default';
			}

			$epoch = wfTimestampNow();
			foreach ( $dateopts as $key ) {
				if ( $key == 'default' ) {
					$formatted = wfMsgHtml( 'datedefault' );
				} else {
					$formatted = htmlspecialchars( $wgLang->timeanddate( $epoch, false, $key ) );
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
			$display = "{$limits[0]}×{$limits[1]}" . wfMsg( 'unit-pixel' );
			$ret[$display] = $index;
		}

		return $ret;
	}

	static function getThumbSizes() {
		global $wgThumbLimits;

		$ret = array();

		foreach ( $wgThumbLimits as $index => $size ) {
			$display = $size . wfMsg( 'unit-pixel' );
			$ret[$display] = $index;
		}

		return $ret;
	}

	static function validateSignature( $signature, $alldata ) {
		global $wgParser, $wgMaxSigChars, $wgLang;
		if ( mb_strlen( $signature ) > $wgMaxSigChars ) {
			return Xml::element( 'span', array( 'class' => 'error' ),
				wfMsgExt( 'badsiglength', 'parsemag',
					$wgLang->formatNum( $wgMaxSigChars )
				)
			);
		} elseif ( isset( $alldata['fancysig'] ) &&
				$alldata['fancysig'] &&
				false === $wgParser->validateSig( $signature ) ) {
			return Xml::element( 'span', array( 'class' => 'error' ), wfMsg( 'badsig' ) );
		} else {
			return true;
		}
	}

	static function cleanSignature( $signature, $alldata ) {
		global $wgParser;
		if ( isset( $alldata['fancysig'] ) && $alldata['fancysig'] ) {
			$signature = $wgParser->cleanSig( $signature );
		} else {
			// When no fancy sig used, make sure ~{3,5} get removed.
			$signature = $wgParser->cleanSigInSig( $signature );
		}

		return $signature;
	}

	static function validateEmail( $email, $alldata ) {
		if ( $email && !User::isValidEmailAddr( $email ) ) {
			return wfMsgExt( 'invalidemailaddress', 'parseinline' );
		}

		global $wgEmailConfirmToEdit;
		if ( $wgEmailConfirmToEdit && !$email ) {
			return wfMsgExt( 'noemailtitle', 'parseinline' );
		}
		return true;
	}

	static function getFormObject( $user, $formClass = 'PreferencesForm' ) {
		$formDescriptor = Preferences::getPreferences( $user );
		$htmlForm = new $formClass( $formDescriptor, 'prefs' );

		$htmlForm->setId( 'mw-prefs-form' );
		$htmlForm->setSubmitText( wfMsg( 'saveprefs' ) );
		# Used message keys: 'accesskey-preferences-save', 'tooltip-preferences-save'
		$htmlForm->setSubmitTooltip( 'preferences-save' );
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

			$prefill = array_fill_keys( array_values( $tzRegions ), array() );
			$opt = array_merge( $opt, $prefill );

			$now = date_create( 'now' );

			foreach ( $tzs as $tz ) {
				$z = explode( '/', $tz, 2 );

				# timezone_identifiers_list() returns a number of
				# backwards-compatibility entries. This filters them out of the
				# list presented to the user.
				if ( count( $z ) != 2 || !array_key_exists( $z[0], $tzRegions ) ) {
					continue;
				}

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
	
	static function filterIntval( $value, $alldata ){
		return intval( $value );
	}

	static function filterTimezoneInput( $tz, $alldata ) {
		$data = explode( '|', $tz, 3 );
		switch ( $data[0] ) {
			case 'ZoneInfo':
			case 'System':
				return $tz;
			default:
				$data = explode( ':', $tz, 2 );
				if ( count( $data ) == 2 ) {
					$data[0] = intval( $data[0] );
					$data[1] = intval( $data[1] );
					$minDiff = abs( $data[0] ) * 60 + $data[1];
					if ( $data[0] < 0 ) $minDiff = - $minDiff;
				} else {
					$minDiff = intval( $data[0] ) * 60;
				}

				# Max is +14:00 and min is -12:00, see:
				# http://en.wikipedia.org/wiki/Timezone
				$minDiff = min( $minDiff, 840 );  # 14:00
				$minDiff = max( $minDiff, - 720 ); # -12:00
				return 'Offset|' . $minDiff;
		}
	}

	static function tryFormSubmit( $formData, $entryPoint = 'internal' ) {
		global $wgUser, $wgEmailAuthentication, $wgEnableEmail;

		$result = true;

		// Filter input
		foreach ( array_keys( $formData ) as $name ) {
			if ( isset( self::$saveFilters[$name] ) ) {
				$formData[$name] =
					call_user_func( self::$saveFilters[$name], $formData[$name], $formData );
			}
		}

		// Stuff that shouldn't be saved as a preference.
		$saveBlacklist = array(
			'realname',
			'emailaddress',
		);

		if ( $wgEnableEmail ) {
			$newaddr = $formData['emailaddress'];
			$oldaddr = $wgUser->getEmail();
			if ( ( $newaddr != '' ) && ( $newaddr != $oldaddr ) ) {
				# the user has supplied a new email address on the login page
				# new behaviour: set this new emailaddr from login-page into user database record
				$wgUser->setEmail( $newaddr );
				# but flag as "dirty" = unauthenticated
				$wgUser->invalidateEmail();
				if ( $wgEmailAuthentication ) {
					# Mail a temporary password to the dirty address.
					# User can come back through the confirmation URL to re-enable email.
					$type = $oldaddr != '' ? 'changed' : 'set';
					$result = $wgUser->sendConfirmationMail( $type );
					if ( !$result->isGood() ) {
						return htmlspecialchars( $result->getWikiText( 'mailerror' ) );
					} elseif ( $entryPoint == 'ui' ) {
						$result = 'eauth';
					}
				}
			} else {
				$wgUser->setEmail( $newaddr );
			}
			if ( $oldaddr != $newaddr ) {
				wfRunHooks( 'PrefsEmailAudit', array( $wgUser, $oldaddr, $newaddr ) );
			}
		}

		// Fortunately, the realname field is MUCH simpler
		global $wgHiddenPrefs;
		if ( !in_array( 'realname', $wgHiddenPrefs ) ) {
			$realName = $formData['realname'];
			$wgUser->setRealName( $realName );
		}

		foreach ( $saveBlacklist as $b ) {
			unset( $formData[$b] );
		}

		# If users have saved a value for a preference which has subsequently been disabled
		# via $wgHiddenPrefs, we don't want to destroy that setting in case the preference
		# is subsequently re-enabled
		# TODO: maintenance script to actually delete these
		foreach( $wgHiddenPrefs as $pref ){
			# If the user has not set a non-default value here, the default will be returned
			# and subsequently discarded
			$formData[$pref] = $wgUser->getOption( $pref, null, true );
		}

		//  Keeps old preferences from interfering due to back-compat
		//  code, etc.
		$wgUser->resetOptions();

		foreach ( $formData as $key => $value ) {
			$wgUser->setOption( $key, $value );
		}

		$wgUser->saveSettings();

		return $result;
	}

	public static function tryUISubmit( $formData ) {
		$res = self::tryFormSubmit( $formData, 'ui' );

		if ( $res ) {
			$urlOptions = array( 'success' );

			if ( $res === 'eauth' ) {
				$urlOptions[] = 'eauth';
			}

			$queryString = implode( '&', $urlOptions );

			$url = SpecialPage::getTitleFor( 'Preferences' )->getFullURL( $queryString );

			global $wgOut;
			$wgOut->redirect( $url );
		}

		return Status::newGood();
	}

	public static function loadOldSearchNs( $user ) {
		$searchableNamespaces = SearchEngine::searchableNamespaces();
		// Back compat with old format
		$arr = array();

		foreach ( $searchableNamespaces as $ns => $name ) {
			if ( $user->getOption( 'searchNs' . $ns ) ) {
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

		$html .= "\n" . $sk->link( $t, wfMsgHtml( 'restoreprefs' ) );

		$html = Xml::tags( 'div', array( 'class' => 'mw-prefs-buttons' ), $html );

		return $html;
	}

	function filterDataForSubmit( $data ) {
		// Support for separating MultiSelect preferences into multiple preferences
		// Due to lack of array support.
		foreach ( $this->mFlatFields as $fieldname => $field ) {
			$info = $field->mParams;
			if ( $field instanceof HTMLMultiSelectField ) {
				$options = HTMLFormField::flattenOptions( $info['options'] );
				$prefix = isset( $info['prefix'] ) ? $info['prefix'] : $fieldname;

				foreach ( $options as $opt ) {
					$data["$prefix$opt"] = in_array( $opt, $data[$fieldname] );
				}

				unset( $data[$fieldname] );
			}
		}

		return $data;
	}
}
