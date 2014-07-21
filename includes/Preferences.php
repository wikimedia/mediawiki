<?php
/**
 * Form to edit user preferences.
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along
 * with this program; if not, write to the Free Software Foundation, Inc.,
 * 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA.
 * http://www.gnu.org/copyleft/gpl.html
 *
 * @file
 */

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

	// Stuff that shouldn't be saved as a preference.
	private static $saveBlacklist = array(
		'realname',
		'emailaddress',
	);

	/**
	 * @return array
	 */
	static function getSaveBlacklist() {
		return self::$saveBlacklist;
	}

	/**
	 * @throws MWException
	 * @param $user User
	 * @param $context IContextSource
	 * @return array|null
	 */
	static function getPreferences( $user, IContextSource $context ) {
		if ( self::$defaultPreferences ) {
			return self::$defaultPreferences;
		}

		$defaultPreferences = array();

		self::profilePreferences( $user, $context, $defaultPreferences );
		self::skinPreferences( $user, $context, $defaultPreferences );
		self::datetimePreferences( $user, $context, $defaultPreferences );
		self::filesPreferences( $user, $context, $defaultPreferences );
		self::renderingPreferences( $user, $context, $defaultPreferences );
		self::editingPreferences( $user, $context, $defaultPreferences );
		self::rcPreferences( $user, $context, $defaultPreferences );
		self::watchlistPreferences( $user, $context, $defaultPreferences );
		self::searchPreferences( $user, $context, $defaultPreferences );
		self::miscPreferences( $user, $context, $defaultPreferences );

		wfRunHooks( 'GetPreferences', array( $user, &$defaultPreferences ) );

		## Remove preferences that wikis don't want to use
		global $wgHiddenPrefs;
		foreach ( $wgHiddenPrefs as $pref ) {
			if ( isset( $defaultPreferences[$pref] ) ) {
				unset( $defaultPreferences[$pref] );
			}
		}

		## Make sure that form fields have their parent set. See bug 41337.
		$dummyForm = new HTMLForm( array(), $context );

		$disable = !$user->isAllowed( 'editmyoptions' );

		## Prod in defaults from the user
		foreach ( $defaultPreferences as $name => &$info ) {
			$prefFromUser = self::getOptionFromUser( $name, $info, $user );
			if ( $disable && !in_array( $name, self::$saveBlacklist ) ) {
				$info['disabled'] = 'disabled';
			}
			$field = HTMLForm::loadInputFromParameters( $name, $info ); // For validation
			$field->mParent = $dummyForm;
			$defaultOptions = User::getDefaultOptions();
			$globalDefault = isset( $defaultOptions[$name] )
				? $defaultOptions[$name]
				: null;

			// If it validates, set it as the default
			if ( isset( $info['default'] ) ) {
				// Already set, no problem
				continue;
			} elseif ( !is_null( $prefFromUser ) && // Make sure we're not just pulling nothing
					$field->validate( $prefFromUser, $user->getOptions() ) === true ) {
				$info['default'] = $prefFromUser;
			} elseif ( $field->validate( $globalDefault, $user->getOptions() ) === true ) {
				$info['default'] = $globalDefault;
			} else {
				throw new MWException( "Global default '$globalDefault' is invalid for field $name" );
			}
		}

		self::$defaultPreferences = $defaultPreferences;

		return $defaultPreferences;
	}

	/**
	 * Pull option from a user account. Handles stuff like array-type preferences.
	 *
	 * @param $name
	 * @param $info
	 * @param $user User
	 * @return array|String
	 */
	static function getOptionFromUser( $name, $info, $user ) {
		$val = $user->getOption( $name );

		// Handling for multiselect preferences
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

		// Handling for checkmatrix preferences
		if ( ( isset( $info['type'] ) && $info['type'] == 'checkmatrix' ) ||
				( isset( $info['class'] ) && $info['class'] == 'HTMLCheckMatrix' ) ) {
			$columns = HTMLFormField::flattenOptions( $info['columns'] );
			$rows = HTMLFormField::flattenOptions( $info['rows'] );
			$prefix = isset( $info['prefix'] ) ? $info['prefix'] : $name;
			$val = array();

			foreach ( $columns as $column ) {
				foreach ( $rows as $row ) {
					if ( $user->getOption( "$prefix$column-$row" ) ) {
						$val[] = "$column-$row";
					}
				}
			}
		}

		return $val;
	}

	/**
	 * @param $user User
	 * @param $context IContextSource
	 * @param $defaultPreferences
	 * @return void
	 */
	static function profilePreferences( $user, IContextSource $context, &$defaultPreferences ) {
		global $wgAuth, $wgContLang, $wgParser, $wgCookieExpiration, $wgLanguageCode,
			$wgDisableLangConversion, $wgMaxSigChars,
			$wgEnableEmail, $wgEmailConfirmToEdit, $wgEnableUserEmail, $wgEmailAuthentication,
			$wgEnotifWatchlist, $wgEnotifUserTalk, $wgEnotifRevealEditorAddress,
			$wgSecureLogin;

		// retrieving user name for GENDER and misc.
		$userName = $user->getName();

		## User info #####################################
		// Information panel
		$defaultPreferences['username'] = array(
			'type' => 'info',
			'label-message' => array( 'username', $userName ),
			'default' => $userName,
			'section' => 'personal/info',
		);

		$defaultPreferences['userid'] = array(
			'type' => 'info',
			'label-message' => array( 'uid', $userName ),
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
			$groupName = User::getGroupName( $ueg );
			$userGroups[] = User::makeGroupLinkHTML( $ueg, $groupName );

			$memberName = User::getGroupMember( $ueg, $userName );
			$userMembers[] = User::makeGroupLinkHTML( $ueg, $memberName );
		}
		asort( $userGroups );
		asort( $userMembers );

		$lang = $context->getLanguage();

		$defaultPreferences['usergroups'] = array(
			'type' => 'info',
			'label' => $context->msg( 'prefs-memberingroups' )->numParams(
				count( $userGroups ) )->params( $userName )->parse(),
			'default' => $context->msg( 'prefs-memberingroups-type',
				$lang->commaList( $userGroups ),
				$lang->commaList( $userMembers )
			)->plain(),
			'raw' => true,
			'section' => 'personal/info',
		);

		$editCount = Linker::link( SpecialPage::getTitleFor( "Contributions", $userName ),
			$lang->formatNum( $user->getEditCount() ) );

		$defaultPreferences['editcount'] = array(
			'type' => 'info',
			'raw' => true,
			'label-message' => 'prefs-edits',
			'default' => $editCount,
			'section' => 'personal/info',
		);

		if ( $user->getRegistration() ) {
			$displayUser = $context->getUser();
			$userRegistration = $user->getRegistration();
			$defaultPreferences['registrationdate'] = array(
				'type' => 'info',
				'label-message' => 'prefs-registration',
				'default' => $context->msg(
					'prefs-registration-date-time',
					$lang->userTimeAndDate( $userRegistration, $displayUser ),
					$lang->userDate( $userRegistration, $displayUser ),
					$lang->userTime( $userRegistration, $displayUser )
				)->parse(),
				'section' => 'personal/info',
			);
		}

		$canViewPrivateInfo = $user->isAllowed( 'viewmyprivateinfo' );
		$canEditPrivateInfo = $user->isAllowed( 'editmyprivateinfo' );

		// Actually changeable stuff
		$defaultPreferences['realname'] = array(
			// (not really "private", but still shouldn't be edited without permission)
			'type' => $canEditPrivateInfo && $wgAuth->allowPropChange( 'realname' ) ? 'text' : 'info',
			'default' => $user->getRealName(),
			'section' => 'personal/info',
			'label-message' => 'yourrealname',
			'help-message' => 'prefs-help-realname',
		);

		if ( $canEditPrivateInfo && $wgAuth->allowPasswordChange() ) {
			$link = Linker::link( SpecialPage::getTitleFor( 'ChangePassword' ),
				$context->msg( 'prefs-resetpass' )->escaped(), array(),
				array( 'returnto' => SpecialPage::getTitleFor( 'Preferences' )->getPrefixedText() ) );

			$defaultPreferences['password'] = array(
				'type' => 'info',
				'raw' => true,
				'default' => $link,
				'label-message' => 'yourpassword',
				'section' => 'personal/info',
			);
		}
		// Only show prefershttps if secure login is turned on
		if ( $wgSecureLogin && wfCanIPUseHTTPS( $context->getRequest()->getIP() ) ) {
			$defaultPreferences['prefershttps'] = array(
				'type' => 'toggle',
				'label-message' => 'tog-prefershttps',
				'help-message' => 'prefs-help-prefershttps',
				'section' => 'personal/info'
			);
		}

		// Language
		$languages = Language::fetchLanguageNames( null, 'mw' );
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

		$defaultPreferences['gender'] = array(
			'type' => 'radio',
			'section' => 'personal/i18n',
			'options' => array(
				$context->msg( 'parentheses',
					$context->msg( 'gender-unknown' )->text()
				)->text() => 'unknown',
				$context->msg( 'gender-female' )->text() => 'female',
				$context->msg( 'gender-male' )->text() => 'male',
			),
			'label-message' => 'yourgender',
			'help-message' => 'prefs-help-gender',
		);

		// see if there are multiple language variants to choose from
		if ( !$wgDisableLangConversion ) {
			foreach ( LanguageConverter::$languagesWithVariants as $langCode ) {
				if ( $langCode == $wgContLang->getCode() ) {
					$variants = $wgContLang->getVariants();

					if ( count( $variants ) <= 1 ) {
						continue;
					}

					$variantArray = array();
					foreach ( $variants as $v ) {
						$v = str_replace( '_', '-', strtolower( $v ) );
						$variantArray[$v] = $lang->getVariantname( $v, false );
					}

					$options = array();
					foreach ( $variantArray as $code => $name ) {
						$display = wfBCP47( $code ) . ' - ' . $name;
						$options[$display] = $code;
					}

					$defaultPreferences['variant'] = array(
						'label-message' => 'yourvariant',
						'type' => 'select',
						'options' => $options,
						'section' => 'personal/i18n',
						'help-message' => 'prefs-help-variant',
					);
				} else {
					$defaultPreferences["variant-$langCode"] = array(
						'type' => 'api',
					);
				}
			}
		}

		// Stuff from Language::getExtraUserToggles()
		// FIXME is this dead code? $extraUserToggles doesn't seem to be defined for any language
		$toggles = $wgContLang->getExtraUserToggles();

		foreach ( $toggles as $toggle ) {
			$defaultPreferences[$toggle] = array(
				'type' => 'toggle',
				'section' => 'personal/i18n',
				'label-message' => "tog-$toggle",
			);
		}

		// show a preview of the old signature first
		$oldsigWikiText = $wgParser->preSaveTransform(
			'~~~',
			$context->getTitle(),
			$user,
			ParserOptions::newFromContext( $context )
		);
		$oldsigHTML = $context->getOutput()->parseInline( $oldsigWikiText, true, true );
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
			// show general help about signature at the bottom of the section
			'help-message' => 'prefs-help-signature',
			'section' => 'personal/signature'
		);

		## Email stuff

		if ( $wgEnableEmail ) {
			if ( $canViewPrivateInfo ) {
				$helpMessages[] = $wgEmailConfirmToEdit
						? 'prefs-help-email-required'
						: 'prefs-help-email';

				if ( $wgEnableUserEmail ) {
					// additional messages when users can send email to each other
					$helpMessages[] = 'prefs-help-email-others';
				}

				$emailAddress = $user->getEmail() ? htmlspecialchars( $user->getEmail() ) : '';
				if ( $canEditPrivateInfo && $wgAuth->allowPropChange( 'emailaddress' ) ) {
					$link = Linker::link(
						SpecialPage::getTitleFor( 'ChangeEmail' ),
						$context->msg( $user->getEmail() ? 'prefs-changeemail' : 'prefs-setemail' )->escaped(),
						array(),
						array( 'returnto' => SpecialPage::getTitleFor( 'Preferences' )->getPrefixedText() ) );

					$emailAddress .= $emailAddress == '' ? $link : (
						$context->msg( 'word-separator' )->plain()
						. $context->msg( 'parentheses' )->rawParams( $link )->plain()
					);
				}

				$defaultPreferences['emailaddress'] = array(
					'type' => 'info',
					'raw' => true,
					'default' => $emailAddress,
					'label-message' => 'youremail',
					'section' => 'personal/email',
					'help-messages' => $helpMessages,
					# 'cssclass' chosen below
				);
			}

			$disableEmailPrefs = false;

			if ( $wgEmailAuthentication ) {
				$emailauthenticationclass = 'mw-email-not-authenticated';
				if ( $user->getEmail() ) {
					if ( $user->getEmailAuthenticationTimestamp() ) {
						// date and time are separate parameters to facilitate localisation.
						// $time is kept for backward compat reasons.
						// 'emailauthenticated' is also used in SpecialConfirmemail.php
						$displayUser = $context->getUser();
						$emailTimestamp = $user->getEmailAuthenticationTimestamp();
						$time = $lang->userTimeAndDate( $emailTimestamp, $displayUser );
						$d = $lang->userDate( $emailTimestamp, $displayUser );
						$t = $lang->userTime( $emailTimestamp, $displayUser );
						$emailauthenticated = $context->msg( 'emailauthenticated',
							$time, $d, $t )->parse() . '<br />';
						$disableEmailPrefs = false;
						$emailauthenticationclass = 'mw-email-authenticated';
					} else {
						$disableEmailPrefs = true;
						$emailauthenticated = $context->msg( 'emailnotauthenticated' )->parse() . '<br />' .
							Linker::linkKnown(
								SpecialPage::getTitleFor( 'Confirmemail' ),
								$context->msg( 'emailconfirmlink' )->escaped()
							) . '<br />';
						$emailauthenticationclass = "mw-email-not-authenticated";
					}
				} else {
					$disableEmailPrefs = true;
					$emailauthenticated = $context->msg( 'noemailprefs' )->escaped();
					$emailauthenticationclass = 'mw-email-none';
				}

				if ( $canViewPrivateInfo ) {
					$defaultPreferences['emailauthentication'] = array(
						'type' => 'info',
						'raw' => true,
						'section' => 'personal/email',
						'label-message' => 'prefs-emailconfirm-label',
						'default' => $emailauthenticated,
						# Apply the same CSS class used on the input to the message:
						'cssclass' => $emailauthenticationclass,
					);
					$defaultPreferences['emailaddress']['cssclass'] = $emailauthenticationclass;
				}
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

			if ( $wgEnotifWatchlist ) {
				$defaultPreferences['enotifwatchlistpages'] = array(
					'type' => 'toggle',
					'section' => 'personal/email',
					'label-message' => 'tog-enotifwatchlistpages',
					'disabled' => $disableEmailPrefs,
				);
			}
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

	/**
	 * @param $user User
	 * @param $context IContextSource
	 * @param $defaultPreferences
	 * @return void
	 */
	static function skinPreferences( $user, IContextSource $context, &$defaultPreferences ) {
		## Skin #####################################
		global $wgAllowUserCss, $wgAllowUserJs;

		$defaultPreferences['skin'] = array(
			'type' => 'radio',
			'options' => self::generateSkinOptions( $user, $context ),
			'label' => '&#160;',
			'section' => 'rendering/skin',
		);

		# Create links to user CSS/JS pages for all skins
		# This code is basically copied from generateSkinOptions().  It'd
		# be nice to somehow merge this back in there to avoid redundancy.
		if ( $wgAllowUserCss || $wgAllowUserJs ) {
			$linkTools = array();
			$userName = $user->getName();

			if ( $wgAllowUserCss ) {
				$cssPage = Title::makeTitleSafe( NS_USER, $userName . '/common.css' );
				$linkTools[] = Linker::link( $cssPage, $context->msg( 'prefs-custom-css' )->escaped() );
			}

			if ( $wgAllowUserJs ) {
				$jsPage = Title::makeTitleSafe( NS_USER, $userName . '/common.js' );
				$linkTools[] = Linker::link( $jsPage, $context->msg( 'prefs-custom-js' )->escaped() );
			}

			$defaultPreferences['commoncssjs'] = array(
				'type' => 'info',
				'raw' => true,
				'default' => $context->getLanguage()->pipeList( $linkTools ),
				'label-message' => 'prefs-common-css-js',
				'section' => 'rendering/skin',
			);
		}
	}

	/**
	 * @param $user User
	 * @param $context IContextSource
	 * @param $defaultPreferences Array
	 */
	static function filesPreferences( $user, IContextSource $context, &$defaultPreferences ) {
		## Files #####################################
		$defaultPreferences['imagesize'] = array(
			'type' => 'select',
			'options' => self::getImageSizes( $context ),
			'label-message' => 'imagemaxsize',
			'section' => 'rendering/files',
		);
		$defaultPreferences['thumbsize'] = array(
			'type' => 'select',
			'options' => self::getThumbSizes( $context ),
			'label-message' => 'thumbsize',
			'section' => 'rendering/files',
		);
	}

	/**
	 * @param $user User
	 * @param $context IContextSource
	 * @param $defaultPreferences
	 * @return void
	 */
	static function datetimePreferences( $user, IContextSource $context, &$defaultPreferences ) {
		## Date and time #####################################
		$dateOptions = self::getDateOptions( $context );
		if ( $dateOptions ) {
			$defaultPreferences['date'] = array(
				'type' => 'radio',
				'options' => $dateOptions,
				'label' => '&#160;',
				'section' => 'rendering/dateformat',
			);
		}

		// Info
		$now = wfTimestampNow();
		$lang = $context->getLanguage();
		$nowlocal = Xml::element( 'span', array( 'id' => 'wpLocalTime' ),
			$lang->time( $now, true ) );
		$nowserver = $lang->time( $now, false ) .
			Html::hidden( 'wpServerTime', (int)substr( $now, 8, 2 ) * 60 + (int)substr( $now, 10, 2 ) );

		$defaultPreferences['nowserver'] = array(
			'type' => 'info',
			'raw' => 1,
			'label-message' => 'servertime',
			'default' => $nowserver,
			'section' => 'rendering/timeoffset',
		);

		$defaultPreferences['nowlocal'] = array(
			'type' => 'info',
			'raw' => 1,
			'label-message' => 'localtime',
			'default' => $nowlocal,
			'section' => 'rendering/timeoffset',
		);

		// Grab existing pref.
		$tzOffset = $user->getOption( 'timecorrection' );
		$tz = explode( '|', $tzOffset, 3 );

		$tzOptions = self::getTimezoneOptions( $context );

		$tzSetting = $tzOffset;
		if ( count( $tz ) > 1 && $tz[0] == 'Offset' ) {
			$minDiff = $tz[1];
			$tzSetting = sprintf( '%+03d:%02d', floor( $minDiff / 60 ), abs( $minDiff ) % 60 );
		} elseif ( count( $tz ) > 1 && $tz[0] == 'ZoneInfo' &&
			!in_array( $tzOffset, HTMLFormField::flattenOptions( $tzOptions ) )
		) {
			# Timezone offset can vary with DST
			$userTZ = timezone_open( $tz[2] );
			if ( $userTZ !== false ) {
				$minDiff = floor( timezone_offset_get( $userTZ, date_create( 'now' ) ) / 60 );
				$tzSetting = "ZoneInfo|$minDiff|{$tz[2]}";
			}
		}

		$defaultPreferences['timecorrection'] = array(
			'class' => 'HTMLSelectOrOtherField',
			'label-message' => 'timezonelegend',
			'options' => $tzOptions,
			'default' => $tzSetting,
			'size' => 20,
			'section' => 'rendering/timeoffset',
		);
	}

	/**
	 * @param $user User
	 * @param $context IContextSource
	 * @param $defaultPreferences Array
	 */
	static function renderingPreferences( $user, IContextSource $context, &$defaultPreferences ) {
		## Diffs ####################################
		$defaultPreferences['diffonly'] = array(
			'type' => 'toggle',
			'section' => 'rendering/diffs',
			'label-message' => 'tog-diffonly',
		);
		$defaultPreferences['norollbackdiff'] = array(
			'type' => 'toggle',
			'section' => 'rendering/diffs',
			'label-message' => 'tog-norollbackdiff',
		);

		## Page Rendering ##############################
		global $wgAllowUserCssPrefs;
		if ( $wgAllowUserCssPrefs ) {
			$defaultPreferences['underline'] = array(
				'type' => 'select',
				'options' => array(
					$context->msg( 'underline-never' )->text() => 0,
					$context->msg( 'underline-always' )->text() => 1,
					$context->msg( 'underline-default' )->text() => 2,
				),
				'label-message' => 'tog-underline',
				'section' => 'rendering/advancedrendering',
			);
		}

		$stubThresholdValues = array( 50, 100, 500, 1000, 2000, 5000, 10000 );
		$stubThresholdOptions = array( $context->msg( 'stub-threshold-disabled' )->text() => 0 );
		foreach ( $stubThresholdValues as $value ) {
			$stubThresholdOptions[$context->msg( 'size-bytes', $value )->text()] = $value;
		}

		$defaultPreferences['stubthreshold'] = array(
			'type' => 'select',
			'section' => 'rendering/advancedrendering',
			'options' => $stubThresholdOptions,
			'label-raw' => $context->msg( 'stub-threshold' )->text(), // Raw HTML message. Yay?
		);

		$defaultPreferences['showhiddencats'] = array(
			'type' => 'toggle',
			'section' => 'rendering/advancedrendering',
			'label-message' => 'tog-showhiddencats'
		);

		$defaultPreferences['numberheadings'] = array(
			'type' => 'toggle',
			'section' => 'rendering/advancedrendering',
			'label-message' => 'tog-numberheadings',
		);
	}

	/**
	 * @param $user User
	 * @param $context IContextSource
	 * @param $defaultPreferences Array
	 */
	static function editingPreferences( $user, IContextSource $context, &$defaultPreferences ) {
		global $wgAllowUserCssPrefs;

		## Editing #####################################
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

		if ( $wgAllowUserCssPrefs ) {
			$defaultPreferences['editfont'] = array(
				'type' => 'select',
				'section' => 'editing/editor',
				'label-message' => 'editfont-style',
				'options' => array(
					$context->msg( 'editfont-default' )->text() => 'default',
					$context->msg( 'editfont-monospace' )->text() => 'monospace',
					$context->msg( 'editfont-sansserif' )->text() => 'sans-serif',
					$context->msg( 'editfont-serif' )->text() => 'serif',
				)
			);
		}
		$defaultPreferences['cols'] = array(
			'type' => 'int',
			'label-message' => 'columns',
			'section' => 'editing/editor',
			'min' => 4,
			'max' => 1000,
		);
		$defaultPreferences['rows'] = array(
			'type' => 'int',
			'label-message' => 'rows',
			'section' => 'editing/editor',
			'min' => 4,
			'max' => 1000,
		);
		if ( $user->isAllowed( 'minoredit' ) ) {
			$defaultPreferences['minordefault'] = array(
				'type' => 'toggle',
				'section' => 'editing/editor',
				'label-message' => 'tog-minordefault',
			);
		}
		$defaultPreferences['forceeditsummary'] = array(
			'type' => 'toggle',
			'section' => 'editing/editor',
			'label-message' => 'tog-forceeditsummary',
		);
		$defaultPreferences['useeditwarning'] = array(
			'type' => 'toggle',
			'section' => 'editing/editor',
			'label-message' => 'tog-useeditwarning',
		);
		$defaultPreferences['showtoolbar'] = array(
			'type' => 'toggle',
			'section' => 'editing/editor',
			'label-message' => 'tog-showtoolbar',
		);

		$defaultPreferences['previewonfirst'] = array(
			'type' => 'toggle',
			'section' => 'editing/preview',
			'label-message' => 'tog-previewonfirst',
		);
		$defaultPreferences['previewontop'] = array(
			'type' => 'toggle',
			'section' => 'editing/preview',
			'label-message' => 'tog-previewontop',
		);
		$defaultPreferences['uselivepreview'] = array(
			'type' => 'toggle',
			'section' => 'editing/preview',
			'label-message' => 'tog-uselivepreview',
		);

	}

	/**
	 * @param $user User
	 * @param $context IContextSource
	 * @param $defaultPreferences Array
	 */
	static function rcPreferences( $user, IContextSource $context, &$defaultPreferences ) {
		global $wgRCMaxAge, $wgRCShowWatchingUsers;

		## RecentChanges #####################################
		$defaultPreferences['rcdays'] = array(
			'type' => 'float',
			'label-message' => 'recentchangesdays',
			'section' => 'rc/displayrc',
			'min' => 1,
			'max' => ceil( $wgRCMaxAge / ( 3600 * 24 ) ),
			'help' => $context->msg( 'recentchangesdays-max' )->numParams(
				ceil( $wgRCMaxAge / ( 3600 * 24 ) ) )->text()
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

		if ( $user->useRCPatrol() ) {
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

		if ( $wgRCShowWatchingUsers ) {
			$defaultPreferences['shownumberswatching'] = array(
				'type' => 'toggle',
				'section' => 'rc/advancedrc',
				'label-message' => 'tog-shownumberswatching',
			);
		}
	}

	/**
	 * @param $user User
	 * @param $context IContextSource
	 * @param $defaultPreferences
	 */
	static function watchlistPreferences( $user, IContextSource $context, &$defaultPreferences ) {
		global $wgUseRCPatrol, $wgEnableAPI, $wgRCMaxAge;

		$watchlistdaysMax = ceil( $wgRCMaxAge / ( 3600 * 24 ) );

		## Watchlist #####################################
		$defaultPreferences['watchlistdays'] = array(
			'type' => 'float',
			'min' => 0,
			'max' => $watchlistdaysMax,
			'section' => 'watchlist/displaywatchlist',
			'help' => $context->msg( 'prefs-watchlist-days-max' )->numParams(
				$watchlistdaysMax )->text(),
			'label-message' => 'prefs-watchlist-days',
		);
		$defaultPreferences['wllimit'] = array(
			'type' => 'int',
			'min' => 0,
			'max' => 1000,
			'label-message' => 'prefs-watchlist-edits',
			'help' => $context->msg( 'prefs-watchlist-edits-max' )->escaped(),
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
				// Messages:
				// tog-watchdefault, tog-watchmoves, tog-watchdeletion, tog-watchcreations
				$defaultPreferences[$pref] = array(
					'type' => 'toggle',
					'section' => 'watchlist/advancedwatchlist',
					'label-message' => "tog-$pref",
				);
			}
		}

		if ( $wgEnableAPI ) {
			$defaultPreferences['watchlisttoken'] = array(
				'type' => 'api',
			);
			$defaultPreferences['watchlisttoken-info'] = array(
				'type' => 'info',
				'section' => 'watchlist/tokenwatchlist',
				'label-message' => 'prefs-watchlist-token',
				'default' => $user->getTokenFromOption( 'watchlisttoken' ),
				'help-message' => 'prefs-help-watchlist-token2',
			);
		}
	}

	/**
	 * @param $user User
	 * @param $context IContextSource
	 * @param $defaultPreferences Array
	 */
	static function searchPreferences( $user, IContextSource $context, &$defaultPreferences ) {
		global $wgContLang;

		$defaultPreferences['searcheverything'] = array(
			'type' => 'toggle',
			'label-message' => 'searcheverything-enable',
			'section' => 'searchoptions/advancedsearchoptions',
		);

		$nsOptions = $wgContLang->getFormattedNamespaces();
		$nsOptions[0] = $context->msg( 'blanknamespace' )->text();
		foreach ( $nsOptions as $ns => $name ) {
			if ( $ns < 0 ) {
				unset( $nsOptions[$ns] );
			}
		}

		$defaultPreferences['searchnamespaces'] = array(
			'type' => 'multiselect',
			'label-message' => 'defaultns',
			'options' => array_flip( $nsOptions ),
			'section' => 'searchoptions/advancedsearchoptions',
			'prefix' => 'searchNs',
		);
	}

	/**
	 * Dummy, kept for backwards-compatibility.
	 */
	static function miscPreferences( $user, IContextSource $context, &$defaultPreferences ) {
	}

	/**
	 * @param $user User The User object
	 * @param $context IContextSource
	 * @return Array: text/links to display as key; $skinkey as value
	 */
	static function generateSkinOptions( $user, IContextSource $context ) {
		global $wgDefaultSkin, $wgAllowUserCss, $wgAllowUserJs;
		$ret = array();

		$mptitle = Title::newMainPage();
		$previewtext = $context->msg( 'skin-preview' )->text();

		# Only show skins that aren't disabled in $wgSkipSkins
		$validSkinNames = Skin::getAllowedSkins();

		# Sort by UI skin name. First though need to update validSkinNames as sometimes
		# the skinkey & UI skinname differ (e.g. "standard" skinkey is "Classic" in the UI).
		foreach ( $validSkinNames as $skinkey => &$skinname ) {
			$msg = $context->msg( "skinname-{$skinkey}" );
			if ( $msg->exists() ) {
				$skinname = htmlspecialchars( $msg->text() );
			}
		}
		asort( $validSkinNames );

		foreach ( $validSkinNames as $skinkey => $sn ) {
			$linkTools = array();

			# Mark the default skin
			if ( $skinkey == $wgDefaultSkin ) {
				$linkTools[] = $context->msg( 'default' )->escaped();
			}

			# Create preview link
			$mplink = htmlspecialchars( $mptitle->getLocalURL( array( 'useskin' => $skinkey ) ) );
			$linkTools[] = "<a target='_blank' href=\"$mplink\">$previewtext</a>";

			# Create links to user CSS/JS pages
			if ( $wgAllowUserCss ) {
				$cssPage = Title::makeTitleSafe( NS_USER, $user->getName() . '/' . $skinkey . '.css' );
				$linkTools[] = Linker::link( $cssPage, $context->msg( 'prefs-custom-css' )->escaped() );
			}

			if ( $wgAllowUserJs ) {
				$jsPage = Title::makeTitleSafe( NS_USER, $user->getName() . '/' . $skinkey . '.js' );
				$linkTools[] = Linker::link( $jsPage, $context->msg( 'prefs-custom-js' )->escaped() );
			}

			$display = $sn . ' ' . $context->msg(
				'parentheses',
				$context->getLanguage()->pipeList( $linkTools )
			)->text();
			$ret[$display] = $skinkey;
		}

		return $ret;
	}

	/**
	 * @param $context IContextSource
	 * @return array
	 */
	static function getDateOptions( IContextSource $context ) {
		$lang = $context->getLanguage();
		$dateopts = $lang->getDatePreferences();

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
					$formatted = $context->msg( 'datedefault' )->escaped();
				} else {
					$formatted = htmlspecialchars( $lang->timeanddate( $epoch, false, $key ) );
				}
				$ret[$formatted] = $key;
			}
		}
		return $ret;
	}

	/**
	 * @param $context IContextSource
	 * @return array
	 */
	static function getImageSizes( IContextSource $context ) {
		global $wgImageLimits;

		$ret = array();
		$pixels = $context->msg( 'unit-pixel' )->text();

		foreach ( $wgImageLimits as $index => $limits ) {
			$display = "{$limits[0]}×{$limits[1]}" . $pixels;
			$ret[$display] = $index;
		}

		return $ret;
	}

	/**
	 * @param $context IContextSource
	 * @return array
	 */
	static function getThumbSizes( IContextSource $context ) {
		global $wgThumbLimits;

		$ret = array();
		$pixels = $context->msg( 'unit-pixel' )->text();

		foreach ( $wgThumbLimits as $index => $size ) {
			$display = $size . $pixels;
			$ret[$display] = $index;
		}

		return $ret;
	}

	/**
	 * @param $signature string
	 * @param $alldata array
	 * @param $form HTMLForm
	 * @return bool|string
	 */
	static function validateSignature( $signature, $alldata, $form ) {
		global $wgParser, $wgMaxSigChars;
		if ( mb_strlen( $signature ) > $wgMaxSigChars ) {
			return Xml::element( 'span', array( 'class' => 'error' ),
				$form->msg( 'badsiglength' )->numParams( $wgMaxSigChars )->text() );
		} elseif ( isset( $alldata['fancysig'] ) &&
				$alldata['fancysig'] &&
				$wgParser->validateSig( $signature ) === false
		) {
			return Xml::element(
				'span',
				array( 'class' => 'error' ),
				$form->msg( 'badsig' )->text()
			);
		} else {
			return true;
		}
	}

	/**
	 * @param $signature string
	 * @param $alldata array
	 * @param $form HTMLForm
	 * @return string
	 */
	static function cleanSignature( $signature, $alldata, $form ) {
		if ( isset( $alldata['fancysig'] ) && $alldata['fancysig'] ) {
			global $wgParser;
			$signature = $wgParser->cleanSig( $signature );
		} else {
			// When no fancy sig used, make sure ~{3,5} get removed.
			$signature = Parser::cleanSigInSig( $signature );
		}

		return $signature;
	}

	/**
	 * @param $user User
	 * @param $context IContextSource
	 * @param $formClass string
	 * @param array $remove array of items to remove
	 * @return HtmlForm
	 */
	static function getFormObject(
		$user,
		IContextSource $context,
		$formClass = 'PreferencesForm',
		array $remove = array()
	) {
		$formDescriptor = Preferences::getPreferences( $user, $context );
		if ( count( $remove ) ) {
			$removeKeys = array_flip( $remove );
			$formDescriptor = array_diff_key( $formDescriptor, $removeKeys );
		}

		// Remove type=api preferences. They are not intended for rendering in the form.
		foreach ( $formDescriptor as $name => $info ) {
			if ( isset( $info['type'] ) && $info['type'] === 'api' ) {
				unset( $formDescriptor[$name] );
			}
		}

		/**
		 * @var $htmlForm PreferencesForm
		 */
		$htmlForm = new $formClass( $formDescriptor, $context, 'prefs' );

		$htmlForm->setModifiedUser( $user );
		$htmlForm->setId( 'mw-prefs-form' );
		$htmlForm->setSubmitText( $context->msg( 'saveprefs' )->text() );
		# Used message keys: 'accesskey-preferences-save', 'tooltip-preferences-save'
		$htmlForm->setSubmitTooltip( 'preferences-save' );
		$htmlForm->setSubmitID( 'prefsubmit' );
		$htmlForm->setSubmitCallback( array( 'Preferences', 'tryFormSubmit' ) );

		return $htmlForm;
	}

	/**
	 * @param $context IContextSource
	 * @return array
	 */
	static function getTimezoneOptions( IContextSource $context ) {
		$opt = array();

		global $wgLocalTZoffset;
		$timestamp = MWTimestamp::getLocalInstance();
		// Check that $wgLocalTZoffset is the same as the local time zone offset
		if ( $wgLocalTZoffset == $timestamp->format( 'Z' ) / 60 ) {
			$server_tz_msg = $context->msg(
				'timezoneuseserverdefault',
				$timestamp->getTimezone()->getName()
			)->text();
		} else {
			$tzstring = sprintf(
				'%+03d:%02d',
				floor( $wgLocalTZoffset / 60 ),
				abs( $wgLocalTZoffset ) % 60
			);
			$server_tz_msg = $context->msg( 'timezoneuseserverdefault', $tzstring )->text();
		}
		$opt[$server_tz_msg] = "System|$wgLocalTZoffset";
		$opt[$context->msg( 'timezoneuseoffset' )->text()] = 'other';
		$opt[$context->msg( 'guesstimezone' )->text()] = 'guess';

		if ( function_exists( 'timezone_identifiers_list' ) ) {
			# Read timezone list
			$tzs = timezone_identifiers_list();
			sort( $tzs );

			$tzRegions = array();
			$tzRegions['Africa'] = $context->msg( 'timezoneregion-africa' )->text();
			$tzRegions['America'] = $context->msg( 'timezoneregion-america' )->text();
			$tzRegions['Antarctica'] = $context->msg( 'timezoneregion-antarctica' )->text();
			$tzRegions['Arctic'] = $context->msg( 'timezoneregion-arctic' )->text();
			$tzRegions['Asia'] = $context->msg( 'timezoneregion-asia' )->text();
			$tzRegions['Atlantic'] = $context->msg( 'timezoneregion-atlantic' )->text();
			$tzRegions['Australia'] = $context->msg( 'timezoneregion-australia' )->text();
			$tzRegions['Europe'] = $context->msg( 'timezoneregion-europe' )->text();
			$tzRegions['Indian'] = $context->msg( 'timezoneregion-indian' )->text();
			$tzRegions['Pacific'] = $context->msg( 'timezoneregion-pacific' )->text();
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

	/**
	 * @param $value
	 * @param $alldata
	 * @return int
	 */
	static function filterIntval( $value, $alldata ) {
		return intval( $value );
	}

	/**
	 * @param $tz
	 * @param $alldata
	 * @return string
	 */
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
					if ( $data[0] < 0 ) {
						$minDiff = - $minDiff;
					}
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

	/**
	 * Handle the form submission if everything validated properly
	 *
	 * @param $formData
	 * @param $form PreferencesForm
	 * @return bool|Status|string
	 */
	static function tryFormSubmit( $formData, $form ) {
		global $wgHiddenPrefs, $wgAuth;

		$user = $form->getModifiedUser();
		$result = true;

		if ( !$user->isAllowedAny( 'editmyprivateinfo', 'editmyoptions' ) ) {
			return Status::newFatal( 'mypreferencesprotected' );
		}

		// Filter input
		foreach ( array_keys( $formData ) as $name ) {
			if ( isset( self::$saveFilters[$name] ) ) {
				$formData[$name] =
					call_user_func( self::$saveFilters[$name], $formData[$name], $formData );
			}
		}

		// Fortunately, the realname field is MUCH simpler
		// (not really "private", but still shouldn't be edited without permission)
		if ( !in_array( 'realname', $wgHiddenPrefs ) && $user->isAllowed( 'editmyprivateinfo' ) && array_key_exists( 'realname', $formData ) ) {
			$realName = $formData['realname'];
			$user->setRealName( $realName );
		}

		if ( $user->isAllowed( 'editmyoptions' ) ) {
			foreach ( self::$saveBlacklist as $b ) {
				unset( $formData[$b] );
			}

			# If users have saved a value for a preference which has subsequently been disabled
			# via $wgHiddenPrefs, we don't want to destroy that setting in case the preference
			# is subsequently re-enabled
			foreach ( $wgHiddenPrefs as $pref ) {
				# If the user has not set a non-default value here, the default will be returned
				# and subsequently discarded
				$formData[$pref] = $user->getOption( $pref, null, true );
			}

			// Keep old preferences from interfering due to back-compat code, etc.
			$user->resetOptions( 'unused', $form->getContext() );

			foreach ( $formData as $key => $value ) {
				$user->setOption( $key, $value );
			}

			wfRunHooks( 'PreferencesFormPreSave', array( $formData, $form, $user, &$result ) );
			$user->saveSettings();
		}

		$wgAuth->updateExternalDB( $user );

		return $result;
	}

	/**
	 * @param $formData
	 * @param $form PreferencesForm
	 * @return Status
	 */
	public static function tryUISubmit( $formData, $form ) {
		$res = self::tryFormSubmit( $formData, $form );

		if ( $res ) {
			$urlOptions = array( 'success' => 1 );

			if ( $res === 'eauth' ) {
				$urlOptions['eauth'] = 1;
			}

			$urlOptions += $form->getExtraSuccessRedirectParameters();

			$url = $form->getTitle()->getFullURL( $urlOptions );

			$form->getContext()->getOutput()->redirect( $url );
		}

		return Status::newGood();
	}

	/**
	 * Try to set a user's email address.
	 * This does *not* try to validate the address.
	 * Caller is responsible for checking $wgAuth and 'editmyprivateinfo'
	 * right.
	 *
	 * @deprecated in 1.20; use User::setEmailWithConfirmation() instead.
	 * @param $user User
	 * @param string $newaddr New email address
	 * @return Array (true on success or Status on failure, info string)
	 */
	public static function trySetUserEmail( User $user, $newaddr ) {
		wfDeprecated( __METHOD__, '1.20' );

		$result = $user->setEmailWithConfirmation( $newaddr );
		if ( $result->isGood() ) {
			return array( true, $result->value );
		} else {
			return array( $result, 'mailerror' );
		}
	}

	/**
	 * @deprecated in 1.19
	 * @param $user User
	 * @return array
	 */
	public static function loadOldSearchNs( $user ) {
		wfDeprecated( __METHOD__, '1.19' );

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
	// Override default value from HTMLForm
	protected $mSubSectionBeforeFields = false;

	private $modifiedUser;

	/**
	 * @param $user User
	 */
	public function setModifiedUser( $user ) {
		$this->modifiedUser = $user;
	}

	/**
	 * @return User
	 */
	public function getModifiedUser() {
		if ( $this->modifiedUser === null ) {
			return $this->getUser();
		} else {
			return $this->modifiedUser;
		}
	}

	/**
	 * Get extra parameters for the query string when redirecting after
	 * successful save.
	 *
	 * @return array()
	 */
	public function getExtraSuccessRedirectParameters() {
		return array();
	}

	/**
	 * @param $html string
	 * @return String
	 */
	function wrapForm( $html ) {
		$html = Xml::tags( 'div', array( 'id' => 'preferences' ), $html );

		return parent::wrapForm( $html );
	}

	/**
	 * @return String
	 */
	function getButtons() {
		if ( !$this->getModifiedUser()->isAllowedAny( 'editmyprivateinfo', 'editmyoptions' ) ) {
			return '';
		}

		$html = parent::getButtons();

		if ( $this->getModifiedUser()->isAllowed( 'editmyoptions' ) ) {
			$t = SpecialPage::getTitleFor( 'Preferences', 'reset' );

			$html .= "\n" . Linker::link( $t, $this->msg( 'restoreprefs' )->escaped() );

			$html = Xml::tags( 'div', array( 'class' => 'mw-prefs-buttons' ), $html );
		}

		return $html;
	}

	/**
	 * Separate multi-option preferences into multiple preferences, since we
	 * have to store them separately
	 * @param $data array
	 * @return array
	 */
	function filterDataForSubmit( $data ) {
		foreach ( $this->mFlatFields as $fieldname => $field ) {
			if ( $field instanceof HTMLNestedFilterable ) {
				$info = $field->mParams;
				$prefix = isset( $info['prefix'] ) ? $info['prefix'] : $fieldname;
				foreach ( $field->filterDataForSubmit( $data[$fieldname] ) as $key => $value ) {
					$data["$prefix$key"] = $value;
				}
				unset( $data[$fieldname] );
			}
		}

		return $data;
	}

	/**
	 * Get the whole body of the form.
	 * @return string
	 */
	function getBody() {
		return $this->displaySection( $this->mFieldTree, '', 'mw-prefsection-' );
	}

	/**
	 * Get the "<legend>" for a given section key. Normally this is the
	 * prefs-$key message but we'll allow extensions to override it.
	 * @param $key string
	 * @return string
	 */
	function getLegend( $key ) {
		$legend = parent::getLegend( $key );
		wfRunHooks( 'PreferencesGetLegend', array( $this, $key, &$legend ) );
		return $legend;
	}
}
