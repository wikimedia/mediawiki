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
	/** @var array */
	protected static $defaultPreferences = null;

	/** @var array */
	protected static $saveFilters = array(
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
	 * @param User $user
	 * @param IContextSource $context
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

		Hooks::run( 'GetPreferences', array( $user, &$defaultPreferences ) );

		self::loadPreferenceValues( $user, $context, $defaultPreferences );
		self::$defaultPreferences = $defaultPreferences;
		return $defaultPreferences;
	}

	/**
	 * Loads existing values for a given array of preferences
	 * @throws MWException
	 * @param User $user
	 * @param IContextSource $context
	 * @param array $defaultPreferences Array to load values for
	 * @return array|null
	 */
	static function loadPreferenceValues( $user, $context, &$defaultPreferences ) {
		## Remove preferences that wikis don't want to use
		foreach ( $context->getConfig()->get( 'HiddenPrefs' ) as $pref ) {
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
			$field = HTMLForm::loadInputFromParameters( $name, $info, $dummyForm ); // For validation
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

		return $defaultPreferences;
	}

	/**
	 * Pull option from a user account. Handles stuff like array-type preferences.
	 *
	 * @param string $name
	 * @param array $info
	 * @param User $user
	 * @return array|string
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
	 * @param User $user
	 * @param IContextSource $context
	 * @param array $defaultPreferences
	 * @return void
	 */
	static function profilePreferences( $user, IContextSource $context, &$defaultPreferences ) {
		global $wgAuth, $wgContLang, $wgParser;

		$config = $context->getConfig();
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
			'default' => $context->msg( 'prefs-memberingroups-type' )
				->rawParams( $lang->commaList( $userGroups ), $lang->commaList( $userMembers ) )
				->escaped(),
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
		if ( $config->get( 'SecureLogin' ) && wfCanIPUseHTTPS( $context->getRequest()->getIP() ) ) {
			$defaultPreferences['prefershttps'] = array(
				'type' => 'toggle',
				'label-message' => 'tog-prefershttps',
				'help-message' => 'prefs-help-prefershttps',
				'section' => 'personal/info'
			);
		}

		// Language
		$languages = Language::fetchLanguageNames( null, 'mw' );
		$languageCode = $config->get( 'LanguageCode' );
		if ( !array_key_exists( $languageCode, $languages ) ) {
			$languages[$languageCode] = $languageCode;
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
				$context->msg( 'parentheses' )
					->params( $context->msg( 'gender-unknown' )->plain() )
					->escaped() => 'unknown',
				$context->msg( 'gender-female' )->escaped() => 'female',
				$context->msg( 'gender-male' )->escaped() => 'male',
			),
			'label-message' => 'yourgender',
			'help-message' => 'prefs-help-gender',
		);

		// see if there are multiple language variants to choose from
		if ( !$config->get( 'DisableLangConversion' ) ) {
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
			'maxlength' => $config->get( 'MaxSigChars' ),
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

		if ( $config->get( 'EnableEmail' ) ) {
			if ( $canViewPrivateInfo ) {
				$helpMessages[] = $config->get( 'EmailConfirmToEdit' )
						? 'prefs-help-email-required'
						: 'prefs-help-email';

				if ( $config->get( 'EnableUserEmail' ) ) {
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
						$context->msg( 'word-separator' )->escaped()
						. $context->msg( 'parentheses' )->rawParams( $link )->escaped()
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

			if ( $config->get( 'EmailAuthentication' ) ) {
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

			if ( $config->get( 'EnableUserEmail' ) && $user->isAllowed( 'sendemail' ) ) {
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

			if ( $config->get( 'EnotifWatchlist' ) ) {
				$defaultPreferences['enotifwatchlistpages'] = array(
					'type' => 'toggle',
					'section' => 'personal/email',
					'label-message' => 'tog-enotifwatchlistpages',
					'disabled' => $disableEmailPrefs,
				);
			}
			if ( $config->get( 'EnotifUserTalk' ) ) {
				$defaultPreferences['enotifusertalkpages'] = array(
					'type' => 'toggle',
					'section' => 'personal/email',
					'label-message' => 'tog-enotifusertalkpages',
					'disabled' => $disableEmailPrefs,
				);
			}
			if ( $config->get( 'EnotifUserTalk' ) || $config->get( 'EnotifWatchlist' ) ) {
				$defaultPreferences['enotifminoredits'] = array(
					'type' => 'toggle',
					'section' => 'personal/email',
					'label-message' => 'tog-enotifminoredits',
					'disabled' => $disableEmailPrefs,
				);

				if ( $config->get( 'EnotifRevealEditorAddress' ) ) {
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
	 * @param User $user
	 * @param IContextSource $context
	 * @param array $defaultPreferences
	 * @return void
	 */
	static function skinPreferences( $user, IContextSource $context, &$defaultPreferences ) {
		## Skin #####################################

		// Skin selector, if there is at least one valid skin
		$skinOptions = self::generateSkinOptions( $user, $context );
		if ( $skinOptions ) {
			$defaultPreferences['skin'] = array(
				'type' => 'radio',
				'options' => $skinOptions,
				'label' => '&#160;',
				'section' => 'rendering/skin',
			);
		}

		$config = $context->getConfig();
		$allowUserCss = $config->get( 'AllowUserCss' );
		$allowUserJs = $config->get( 'AllowUserJs' );
		# Create links to user CSS/JS pages for all skins
		# This code is basically copied from generateSkinOptions().  It'd
		# be nice to somehow merge this back in there to avoid redundancy.
		if ( $allowUserCss || $allowUserJs ) {
			$linkTools = array();
			$userName = $user->getName();

			if ( $allowUserCss ) {
				$cssPage = Title::makeTitleSafe( NS_USER, $userName . '/common.css' );
				$linkTools[] = Linker::link( $cssPage, $context->msg( 'prefs-custom-css' )->escaped() );
			}

			if ( $allowUserJs ) {
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
	 * @param User $user
	 * @param IContextSource $context
	 * @param array $defaultPreferences
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
	 * @param User $user
	 * @param IContextSource $context
	 * @param array $defaultPreferences
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
	 * @param User $user
	 * @param IContextSource $context
	 * @param array $defaultPreferences
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
		if ( $context->getConfig()->get( 'AllowUserCssPrefs' ) ) {
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
	 * @param User $user
	 * @param IContextSource $context
	 * @param array $defaultPreferences
	 */
	static function editingPreferences( $user, IContextSource $context, &$defaultPreferences ) {
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

		if ( $context->getConfig()->get( 'AllowUserCssPrefs' ) ) {
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
	 * @param User $user
	 * @param IContextSource $context
	 * @param array $defaultPreferences
	 */
	static function rcPreferences( $user, IContextSource $context, &$defaultPreferences ) {
		$config = $context->getConfig();
		$rcMaxAge = $config->get( 'RCMaxAge' );
		## RecentChanges #####################################
		$defaultPreferences['rcdays'] = array(
			'type' => 'float',
			'label-message' => 'recentchangesdays',
			'section' => 'rc/displayrc',
			'min' => 1,
			'max' => ceil( $rcMaxAge / ( 3600 * 24 ) ),
			'help' => $context->msg( 'recentchangesdays-max' )->numParams(
				ceil( $rcMaxAge / ( 3600 * 24 ) ) )->escaped()
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
		}

		if ( $user->useNPPatrol() ) {
			$defaultPreferences['newpageshidepatrolled'] = array(
				'type' => 'toggle',
				'section' => 'rc/advancedrc',
				'label-message' => 'tog-newpageshidepatrolled',
			);
		}

		if ( $config->get( 'RCShowWatchingUsers' ) ) {
			$defaultPreferences['shownumberswatching'] = array(
				'type' => 'toggle',
				'section' => 'rc/advancedrc',
				'label-message' => 'tog-shownumberswatching',
			);
		}
	}

	/**
	 * @param User $user
	 * @param IContextSource $context
	 * @param array $defaultPreferences
	 */
	static function watchlistPreferences( $user, IContextSource $context, &$defaultPreferences ) {
		$config = $context->getConfig();
		$watchlistdaysMax = ceil( $config->get( 'RCMaxAge' ) / ( 3600 * 24 ) );

		## Watchlist #####################################
		if ( $user->isAllowed( 'editmywatchlist' ) ) {
			$editWatchlistLinks = array();
			$editWatchlistModes = array(
				'edit' => array( 'EditWatchlist', false ),
				'raw' => array( 'EditWatchlist', 'raw' ),
				'clear' => array( 'EditWatchlist', 'clear' ),
			);
			foreach ( $editWatchlistModes as $editWatchlistMode => $mode ) {
				// Messages: prefs-editwatchlist-edit, prefs-editwatchlist-raw, prefs-editwatchlist-clear
				$editWatchlistLinks[] = Linker::linkKnown(
					SpecialPage::getTitleFor( $mode[0], $mode[1] ),
					$context->msg( "prefs-editwatchlist-{$editWatchlistMode}" )->parse()
				);
			}

			$defaultPreferences['editwatchlist'] = array(
				'type' => 'info',
				'raw' => true,
				'default' => $context->getLanguage()->pipeList( $editWatchlistLinks ),
				'label-message' => 'prefs-editwatchlist-label',
				'section' => 'watchlist/editwatchlist',
			);
		}

		$defaultPreferences['watchlistdays'] = array(
			'type' => 'float',
			'min' => 0,
			'max' => $watchlistdaysMax,
			'section' => 'watchlist/displaywatchlist',
			'help' => $context->msg( 'prefs-watchlist-days-max' )->numParams(
				$watchlistdaysMax )->escaped(),
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

		if ( $user->useRCPatrol() ) {
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

		if ( $user->isAllowed( 'rollback' ) ) {
			$watchTypes['rollback'] = 'watchrollback';
		}

		foreach ( $watchTypes as $action => $pref ) {
			if ( $user->isAllowed( $action ) ) {
				// Messages:
				// tog-watchdefault, tog-watchmoves, tog-watchdeletion, tog-watchcreations
				// tog-watchrollback
				$defaultPreferences[$pref] = array(
					'type' => 'toggle',
					'section' => 'watchlist/advancedwatchlist',
					'label-message' => "tog-$pref",
				);
			}
		}

		if ( $config->get( 'EnableAPI' ) ) {
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
	 * @param User $user
	 * @param IContextSource $context
	 * @param array $defaultPreferences
	 */
	static function searchPreferences( $user, IContextSource $context, &$defaultPreferences ) {
		foreach ( MWNamespace::getValidNamespaces() as $n ) {
			$defaultPreferences['searchNs' . $n] = array(
				'type' => 'api',
			);
		}
	}

	/**
	 * Dummy, kept for backwards-compatibility.
	 */
	static function miscPreferences( $user, IContextSource $context, &$defaultPreferences ) {
	}

	/**
	 * @param User $user The User object
	 * @param IContextSource $context
	 * @return array Text/links to display as key; $skinkey as value
	 */
	static function generateSkinOptions( $user, IContextSource $context ) {
		$ret = array();

		$mptitle = Title::newMainPage();
		$previewtext = $context->msg( 'skin-preview' )->escaped();

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

		$config = $context->getConfig();
		$defaultSkin = $config->get( 'DefaultSkin' );
		$allowUserCss = $config->get( 'AllowUserCss' );
		$allowUserJs = $config->get( 'AllowUserJs' );

		$foundDefault = false;
		foreach ( $validSkinNames as $skinkey => $sn ) {
			$linkTools = array();

			# Mark the default skin
			if ( strcasecmp( $skinkey, $defaultSkin ) === 0 ) {
				$linkTools[] = $context->msg( 'default' )->escaped();
				$foundDefault = true;
			}

			# Create preview link
			$mplink = htmlspecialchars( $mptitle->getLocalURL( array( 'useskin' => $skinkey ) ) );
			$linkTools[] = "<a target='_blank' href=\"$mplink\">$previewtext</a>";

			# Create links to user CSS/JS pages
			if ( $allowUserCss ) {
				$cssPage = Title::makeTitleSafe( NS_USER, $user->getName() . '/' . $skinkey . '.css' );
				$linkTools[] = Linker::link( $cssPage, $context->msg( 'prefs-custom-css' )->escaped() );
			}

			if ( $allowUserJs ) {
				$jsPage = Title::makeTitleSafe( NS_USER, $user->getName() . '/' . $skinkey . '.js' );
				$linkTools[] = Linker::link( $jsPage, $context->msg( 'prefs-custom-js' )->escaped() );
			}

			$display = $sn . ' ' . $context->msg( 'parentheses' )
				->rawParams( $context->getLanguage()->pipeList( $linkTools ) )
				->escaped();
			$ret[$display] = $skinkey;
		}

		if ( !$foundDefault ) {
			// If the default skin is not available, things are going to break horribly because the
			// default value for skin selector will not be a valid value. Let's just not show it then.
			return array();
		}

		return $ret;
	}

	/**
	 * @param IContextSource $context
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

			// FIXME KLUGE: site default might not be valid for user language
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
	 * @param IContextSource $context
	 * @return array
	 */
	static function getImageSizes( IContextSource $context ) {
		$ret = array();
		$pixels = $context->msg( 'unit-pixel' )->text();

		foreach ( $context->getConfig()->get( 'ImageLimits' ) as $index => $limits ) {
			$display = "{$limits[0]}×{$limits[1]}" . $pixels;
			$ret[$display] = $index;
		}

		return $ret;
	}

	/**
	 * @param IContextSource $context
	 * @return array
	 */
	static function getThumbSizes( IContextSource $context ) {
		$ret = array();
		$pixels = $context->msg( 'unit-pixel' )->text();

		foreach ( $context->getConfig()->get( 'ThumbLimits' ) as $index => $size ) {
			$display = $size . $pixels;
			$ret[$display] = $index;
		}

		return $ret;
	}

	/**
	 * @param string $signature
	 * @param array $alldata
	 * @param HTMLForm $form
	 * @return bool|string
	 */
	static function validateSignature( $signature, $alldata, $form ) {
		global $wgParser;
		$maxSigChars = $form->getConfig()->get( 'MaxSigChars' );
		if ( mb_strlen( $signature ) > $maxSigChars ) {
			return Xml::element( 'span', array( 'class' => 'error' ),
				$form->msg( 'badsiglength' )->numParams( $maxSigChars )->text() );
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
	 * @param string $signature
	 * @param array $alldata
	 * @param HTMLForm $form
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
	 * @param User $user
	 * @param IContextSource $context
	 * @param string $formClass
	 * @param array $remove Array of items to remove
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
	 * @param IContextSource $context
	 * @return array
	 */
	static function getTimezoneOptions( IContextSource $context ) {
		$opt = array();

		$localTZoffset = $context->getConfig()->get( 'LocalTZoffset' );
		$timestamp = MWTimestamp::getLocalInstance();
		// Check that the LocalTZoffset is the same as the local time zone offset
		if ( $localTZoffset == $timestamp->format( 'Z' ) / 60 ) {
			$server_tz_msg = $context->msg(
				'timezoneuseserverdefault',
				$timestamp->getTimezone()->getName()
			)->text();
		} else {
			$tzstring = sprintf(
				'%+03d:%02d',
				floor( $localTZoffset / 60 ),
				abs( $localTZoffset ) % 60
			);
			$server_tz_msg = $context->msg( 'timezoneuseserverdefault', $tzstring )->text();
		}
		$opt[$server_tz_msg] = "System|$localTZoffset";
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
	 * @param string $value
	 * @param array $alldata
	 * @return int
	 */
	static function filterIntval( $value, $alldata ) {
		return intval( $value );
	}

	/**
	 * @param string $tz
	 * @param array $alldata
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
	 * @param array $formData
	 * @param PreferencesForm $form
	 * @return bool|Status|string
	 */
	static function tryFormSubmit( $formData, $form ) {
		global $wgAuth;

		$user = $form->getModifiedUser();
		$hiddenPrefs = $form->getConfig()->get( 'HiddenPrefs' );
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
		if ( !in_array( 'realname', $hiddenPrefs )
			&& $user->isAllowed( 'editmyprivateinfo' )
			&& array_key_exists( 'realname', $formData )
		) {
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
			foreach ( $hiddenPrefs as $pref ) {
				# If the user has not set a non-default value here, the default will be returned
				# and subsequently discarded
				$formData[$pref] = $user->getOption( $pref, null, true );
			}

			// Keep old preferences from interfering due to back-compat code, etc.
			$user->resetOptions( 'unused', $form->getContext() );

			foreach ( $formData as $key => $value ) {
				$user->setOption( $key, $value );
			}

			Hooks::run( 'PreferencesFormPreSave', array( $formData, $form, $user, &$result ) );
			$user->saveSettings();
		}

		$wgAuth->updateExternalDB( $user );

		return $result;
	}

	/**
	 * @param array $formData
	 * @param PreferencesForm $form
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
}

/** Some tweaks to allow js prefs to work */
class PreferencesForm extends HTMLForm {
	// Override default value from HTMLForm
	protected $mSubSectionBeforeFields = false;

	private $modifiedUser;

	/**
	 * @param User $user
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
	 * @param string $html
	 * @return string
	 */
	function wrapForm( $html ) {
		$html = Xml::tags( 'div', array( 'id' => 'preferences' ), $html );

		return parent::wrapForm( $html );
	}

	/**
	 * @return string
	 */
	function getButtons() {

		$attrs = array( 'id' => 'mw-prefs-restoreprefs' );

		if ( !$this->getModifiedUser()->isAllowedAny( 'editmyprivateinfo', 'editmyoptions' ) ) {
			return '';
		}

		$html = parent::getButtons();

		if ( $this->getModifiedUser()->isAllowed( 'editmyoptions' ) ) {
			$t = SpecialPage::getTitleFor( 'Preferences', 'reset' );

			$html .= "\n" . Linker::link( $t, $this->msg( 'restoreprefs' )->escaped(),
				Html::buttonAttributes( $attrs, array( 'mw-ui-quiet' ) ) );

			$html = Xml::tags( 'div', array( 'class' => 'mw-prefs-buttons' ), $html );
		}

		return $html;
	}

	/**
	 * Separate multi-option preferences into multiple preferences, since we
	 * have to store them separately
	 * @param array $data
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
	 * @param string $key
	 * @return string
	 */
	function getLegend( $key ) {
		$legend = parent::getLegend( $key );
		Hooks::run( 'PreferencesGetLegend', array( $this, $key, &$legend ) );
		return $legend;
	}
}
