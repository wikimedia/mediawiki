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
use MediaWiki\Auth\AuthManager;
use MediaWiki\Auth\PasswordAuthenticationRequest;

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
	protected static $saveFilters = [
		'timecorrection' => [ 'Preferences', 'filterTimezoneInput' ],
		'cols' => [ 'Preferences', 'filterIntval' ],
		'rows' => [ 'Preferences', 'filterIntval' ],
		'rclimit' => [ 'Preferences', 'filterIntval' ],
		'wllimit' => [ 'Preferences', 'filterIntval' ],
		'searchlimit' => [ 'Preferences', 'filterIntval' ],
	];

	// Stuff that shouldn't be saved as a preference.
	private static $saveBlacklist = [
		'realname',
		'emailaddress',
	];

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

		$defaultPreferences = [];

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

		Hooks::run( 'GetPreferences', [ $user, &$defaultPreferences ] );

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
		# # Remove preferences that wikis don't want to use
		foreach ( $context->getConfig()->get( 'HiddenPrefs' ) as $pref ) {
			if ( isset( $defaultPreferences[$pref] ) ) {
				unset( $defaultPreferences[$pref] );
			}
		}

		# # Make sure that form fields have their parent set. See bug 41337.
		$dummyForm = new HTMLForm( [], $context );

		$disable = !$user->isAllowed( 'editmyoptions' );

		$defaultOptions = User::getDefaultOptions();
		# # Prod in defaults from the user
		foreach ( $defaultPreferences as $name => &$info ) {
			$prefFromUser = self::getOptionFromUser( $name, $info, $user );
			if ( $disable && !in_array( $name, self::$saveBlacklist ) ) {
				$info['disabled'] = 'disabled';
			}
			$field = HTMLForm::loadInputFromParameters( $name, $info, $dummyForm ); // For validation
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
			$val = [];

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
			$val = [];

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
		global $wgContLang, $wgParser;

		$authManager = AuthManager::singleton();
		$config = $context->getConfig();
		// retrieving user name for GENDER and misc.
		$userName = $user->getName();

		# # User info #####################################
		// Information panel
		$defaultPreferences['username'] = [
			'type' => 'info',
			'label-message' => [ 'username', $userName ],
			'default' => $userName,
			'section' => 'personal/info',
		];

		# Get groups to which the user belongs
		$userEffectiveGroups = $user->getEffectiveGroups();
		$userGroups = $userMembers = [];
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

		$defaultPreferences['usergroups'] = [
			'type' => 'info',
			'label' => $context->msg( 'prefs-memberingroups' )->numParams(
				count( $userGroups ) )->params( $userName )->parse(),
			'default' => $context->msg( 'prefs-memberingroups-type' )
				->rawParams( $lang->commaList( $userGroups ), $lang->commaList( $userMembers ) )
				->escaped(),
			'raw' => true,
			'section' => 'personal/info',
		];

		$editCount = Linker::link( SpecialPage::getTitleFor( "Contributions", $userName ),
			$lang->formatNum( $user->getEditCount() ) );

		$defaultPreferences['editcount'] = [
			'type' => 'info',
			'raw' => true,
			'label-message' => 'prefs-edits',
			'default' => $editCount,
			'section' => 'personal/info',
		];

		if ( $user->getRegistration() ) {
			$displayUser = $context->getUser();
			$userRegistration = $user->getRegistration();
			$defaultPreferences['registrationdate'] = [
				'type' => 'info',
				'label-message' => 'prefs-registration',
				'default' => $context->msg(
					'prefs-registration-date-time',
					$lang->userTimeAndDate( $userRegistration, $displayUser ),
					$lang->userDate( $userRegistration, $displayUser ),
					$lang->userTime( $userRegistration, $displayUser )
				)->parse(),
				'section' => 'personal/info',
			];
		}

		$canViewPrivateInfo = $user->isAllowed( 'viewmyprivateinfo' );
		$canEditPrivateInfo = $user->isAllowed( 'editmyprivateinfo' );

		// Actually changeable stuff
		$defaultPreferences['realname'] = [
			// (not really "private", but still shouldn't be edited without permission)
			'type' => $canEditPrivateInfo && $authManager->allowsPropertyChange( 'realname' )
				? 'text' : 'info',
			'default' => $user->getRealName(),
			'section' => 'personal/info',
			'label-message' => 'yourrealname',
			'help-message' => 'prefs-help-realname',
		];

		if ( $canEditPrivateInfo && $authManager->allowsAuthenticationDataChange(
			new PasswordAuthenticationRequest(), false )->isGood()
		) {
			$link = Linker::link( SpecialPage::getTitleFor( 'ChangePassword' ),
				$context->msg( 'prefs-resetpass' )->escaped(), [],
				[ 'returnto' => SpecialPage::getTitleFor( 'Preferences' )->getPrefixedText() ] );

			$defaultPreferences['password'] = [
				'type' => 'info',
				'raw' => true,
				'default' => $link,
				'label-message' => 'yourpassword',
				'section' => 'personal/info',
			];
		}
		// Only show prefershttps if secure login is turned on
		if ( $config->get( 'SecureLogin' ) && wfCanIPUseHTTPS( $context->getRequest()->getIP() ) ) {
			$defaultPreferences['prefershttps'] = [
				'type' => 'toggle',
				'label-message' => 'tog-prefershttps',
				'help-message' => 'prefs-help-prefershttps',
				'section' => 'personal/info'
			];
		}

		// Language
		$languages = Language::fetchLanguageNames( null, 'mw' );
		$languageCode = $config->get( 'LanguageCode' );
		if ( !array_key_exists( $languageCode, $languages ) ) {
			$languages[$languageCode] = $languageCode;
		}
		ksort( $languages );

		$options = [];
		foreach ( $languages as $code => $name ) {
			$display = wfBCP47( $code ) . ' - ' . $name;
			$options[$display] = $code;
		}
		$defaultPreferences['language'] = [
			'type' => 'select',
			'section' => 'personal/i18n',
			'options' => $options,
			'label-message' => 'yourlanguage',
		];

		$defaultPreferences['gender'] = [
			'type' => 'radio',
			'section' => 'personal/i18n',
			'options' => [
				$context->msg( 'parentheses' )
					->params( $context->msg( 'gender-unknown' )->plain() )
					->escaped() => 'unknown',
				$context->msg( 'gender-female' )->escaped() => 'female',
				$context->msg( 'gender-male' )->escaped() => 'male',
			],
			'label-message' => 'yourgender',
			'help-message' => 'prefs-help-gender',
		];

		// see if there are multiple language variants to choose from
		if ( !$config->get( 'DisableLangConversion' ) ) {
			foreach ( LanguageConverter::$languagesWithVariants as $langCode ) {
				if ( $langCode == $wgContLang->getCode() ) {
					$variants = $wgContLang->getVariants();

					if ( count( $variants ) <= 1 ) {
						continue;
					}

					$variantArray = [];
					foreach ( $variants as $v ) {
						$v = str_replace( '_', '-', strtolower( $v ) );
						$variantArray[$v] = $lang->getVariantname( $v, false );
					}

					$options = [];
					foreach ( $variantArray as $code => $name ) {
						$display = wfBCP47( $code ) . ' - ' . $name;
						$options[$display] = $code;
					}

					$defaultPreferences['variant'] = [
						'label-message' => 'yourvariant',
						'type' => 'select',
						'options' => $options,
						'section' => 'personal/i18n',
						'help-message' => 'prefs-help-variant',
					];
				} else {
					$defaultPreferences["variant-$langCode"] = [
						'type' => 'api',
					];
				}
			}
		}

		// Stuff from Language::getExtraUserToggles()
		// FIXME is this dead code? $extraUserToggles doesn't seem to be defined for any language
		$toggles = $wgContLang->getExtraUserToggles();

		foreach ( $toggles as $toggle ) {
			$defaultPreferences[$toggle] = [
				'type' => 'toggle',
				'section' => 'personal/i18n',
				'label-message' => "tog-$toggle",
			];
		}

		// show a preview of the old signature first
		$oldsigWikiText = $wgParser->preSaveTransform(
			'~~~',
			$context->getTitle(),
			$user,
			ParserOptions::newFromContext( $context )
		);
		$oldsigHTML = $context->getOutput()->parseInline( $oldsigWikiText, true, true );
		$defaultPreferences['oldsig'] = [
			'type' => 'info',
			'raw' => true,
			'label-message' => 'tog-oldsig',
			'default' => $oldsigHTML,
			'section' => 'personal/signature',
		];
		$defaultPreferences['nickname'] = [
			'type' => $authManager->allowsPropertyChange( 'nickname' ) ? 'text' : 'info',
			'maxlength' => $config->get( 'MaxSigChars' ),
			'label-message' => 'yournick',
			'validation-callback' => [ 'Preferences', 'validateSignature' ],
			'section' => 'personal/signature',
			'filter-callback' => [ 'Preferences', 'cleanSignature' ],
		];
		$defaultPreferences['fancysig'] = [
			'type' => 'toggle',
			'label-message' => 'tog-fancysig',
			// show general help about signature at the bottom of the section
			'help-message' => 'prefs-help-signature',
			'section' => 'personal/signature'
		];

		# # Email stuff

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
				if ( $canEditPrivateInfo && $authManager->allowsPropertyChange( 'emailaddress' ) ) {
					$link = Linker::link(
						SpecialPage::getTitleFor( 'ChangeEmail' ),
						$context->msg( $user->getEmail() ? 'prefs-changeemail' : 'prefs-setemail' )->escaped(),
						[],
						[ 'returnto' => SpecialPage::getTitleFor( 'Preferences' )->getPrefixedText() ] );

					$emailAddress .= $emailAddress == '' ? $link : (
						$context->msg( 'word-separator' )->escaped()
						. $context->msg( 'parentheses' )->rawParams( $link )->escaped()
					);
				}

				$defaultPreferences['emailaddress'] = [
					'type' => 'info',
					'raw' => true,
					'default' => $emailAddress,
					'label-message' => 'youremail',
					'section' => 'personal/email',
					'help-messages' => $helpMessages,
					# 'cssclass' chosen below
				];
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
					$defaultPreferences['emailauthentication'] = [
						'type' => 'info',
						'raw' => true,
						'section' => 'personal/email',
						'label-message' => 'prefs-emailconfirm-label',
						'default' => $emailauthenticated,
						# Apply the same CSS class used on the input to the message:
						'cssclass' => $emailauthenticationclass,
					];
				}
			}

			if ( $config->get( 'EnableUserEmail' ) && $user->isAllowed( 'sendemail' ) ) {
				$defaultPreferences['disablemail'] = [
					'type' => 'toggle',
					'invert' => true,
					'section' => 'personal/email',
					'label-message' => 'allowemail',
					'disabled' => $disableEmailPrefs,
				];
				$defaultPreferences['ccmeonemails'] = [
					'type' => 'toggle',
					'section' => 'personal/email',
					'label-message' => 'tog-ccmeonemails',
					'disabled' => $disableEmailPrefs,
				];
			}

			if ( $config->get( 'EnotifWatchlist' ) ) {
				$defaultPreferences['enotifwatchlistpages'] = [
					'type' => 'toggle',
					'section' => 'personal/email',
					'label-message' => 'tog-enotifwatchlistpages',
					'disabled' => $disableEmailPrefs,
				];
			}
			if ( $config->get( 'EnotifUserTalk' ) ) {
				$defaultPreferences['enotifusertalkpages'] = [
					'type' => 'toggle',
					'section' => 'personal/email',
					'label-message' => 'tog-enotifusertalkpages',
					'disabled' => $disableEmailPrefs,
				];
			}
			if ( $config->get( 'EnotifUserTalk' ) || $config->get( 'EnotifWatchlist' ) ) {
				if ( $config->get( 'EnotifMinorEdits' ) ) {
					$defaultPreferences['enotifminoredits'] = [
						'type' => 'toggle',
						'section' => 'personal/email',
						'label-message' => 'tog-enotifminoredits',
						'disabled' => $disableEmailPrefs,
					];
				}

				if ( $config->get( 'EnotifRevealEditorAddress' ) ) {
					$defaultPreferences['enotifrevealaddr'] = [
						'type' => 'toggle',
						'section' => 'personal/email',
						'label-message' => 'tog-enotifrevealaddr',
						'disabled' => $disableEmailPrefs,
					];
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
		# # Skin #####################################

		// Skin selector, if there is at least one valid skin
		$skinOptions = self::generateSkinOptions( $user, $context );
		if ( $skinOptions ) {
			$defaultPreferences['skin'] = [
				'type' => 'radio',
				'options' => $skinOptions,
				'label' => '&#160;',
				'section' => 'rendering/skin',
			];
		}

		$config = $context->getConfig();
		$allowUserCss = $config->get( 'AllowUserCss' );
		$allowUserJs = $config->get( 'AllowUserJs' );
		# Create links to user CSS/JS pages for all skins
		# This code is basically copied from generateSkinOptions().  It'd
		# be nice to somehow merge this back in there to avoid redundancy.
		if ( $allowUserCss || $allowUserJs ) {
			$linkTools = [];
			$userName = $user->getName();

			if ( $allowUserCss ) {
				$cssPage = Title::makeTitleSafe( NS_USER, $userName . '/common.css' );
				$linkTools[] = Linker::link( $cssPage, $context->msg( 'prefs-custom-css' )->escaped() );
			}

			if ( $allowUserJs ) {
				$jsPage = Title::makeTitleSafe( NS_USER, $userName . '/common.js' );
				$linkTools[] = Linker::link( $jsPage, $context->msg( 'prefs-custom-js' )->escaped() );
			}

			$defaultPreferences['commoncssjs'] = [
				'type' => 'info',
				'raw' => true,
				'default' => $context->getLanguage()->pipeList( $linkTools ),
				'label-message' => 'prefs-common-css-js',
				'section' => 'rendering/skin',
			];
		}
	}

	/**
	 * @param User $user
	 * @param IContextSource $context
	 * @param array $defaultPreferences
	 */
	static function filesPreferences( $user, IContextSource $context, &$defaultPreferences ) {
		# # Files #####################################
		$defaultPreferences['imagesize'] = [
			'type' => 'select',
			'options' => self::getImageSizes( $context ),
			'label-message' => 'imagemaxsize',
			'section' => 'rendering/files',
		];
		$defaultPreferences['thumbsize'] = [
			'type' => 'select',
			'options' => self::getThumbSizes( $context ),
			'label-message' => 'thumbsize',
			'section' => 'rendering/files',
		];
	}

	/**
	 * @param User $user
	 * @param IContextSource $context
	 * @param array $defaultPreferences
	 * @return void
	 */
	static function datetimePreferences( $user, IContextSource $context, &$defaultPreferences ) {
		# # Date and time #####################################
		$dateOptions = self::getDateOptions( $context );
		if ( $dateOptions ) {
			$defaultPreferences['date'] = [
				'type' => 'radio',
				'options' => $dateOptions,
				'label' => '&#160;',
				'section' => 'rendering/dateformat',
			];
		}

		// Info
		$now = wfTimestampNow();
		$lang = $context->getLanguage();
		$nowlocal = Xml::element( 'span', [ 'id' => 'wpLocalTime' ],
			$lang->userTime( $now, $user ) );
		$nowserver = $lang->userTime( $now, $user,
				[ 'format' => false, 'timecorrection' => false ] ) .
			Html::hidden( 'wpServerTime', (int)substr( $now, 8, 2 ) * 60 + (int)substr( $now, 10, 2 ) );

		$defaultPreferences['nowserver'] = [
			'type' => 'info',
			'raw' => 1,
			'label-message' => 'servertime',
			'default' => $nowserver,
			'section' => 'rendering/timeoffset',
		];

		$defaultPreferences['nowlocal'] = [
			'type' => 'info',
			'raw' => 1,
			'label-message' => 'localtime',
			'default' => $nowlocal,
			'section' => 'rendering/timeoffset',
		];

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

		$defaultPreferences['timecorrection'] = [
			'class' => 'HTMLSelectOrOtherField',
			'label-message' => 'timezonelegend',
			'options' => $tzOptions,
			'default' => $tzSetting,
			'size' => 20,
			'section' => 'rendering/timeoffset',
		];
	}

	/**
	 * @param User $user
	 * @param IContextSource $context
	 * @param array $defaultPreferences
	 */
	static function renderingPreferences( $user, IContextSource $context, &$defaultPreferences ) {
		# # Diffs ####################################
		$defaultPreferences['diffonly'] = [
			'type' => 'toggle',
			'section' => 'rendering/diffs',
			'label-message' => 'tog-diffonly',
		];
		$defaultPreferences['norollbackdiff'] = [
			'type' => 'toggle',
			'section' => 'rendering/diffs',
			'label-message' => 'tog-norollbackdiff',
		];

		# # Page Rendering ##############################
		if ( $context->getConfig()->get( 'AllowUserCssPrefs' ) ) {
			$defaultPreferences['underline'] = [
				'type' => 'select',
				'options' => [
					$context->msg( 'underline-never' )->text() => 0,
					$context->msg( 'underline-always' )->text() => 1,
					$context->msg( 'underline-default' )->text() => 2,
				],
				'label-message' => 'tog-underline',
				'section' => 'rendering/advancedrendering',
			];
		}

		$stubThresholdValues = [ 50, 100, 500, 1000, 2000, 5000, 10000 ];
		$stubThresholdOptions = [ $context->msg( 'stub-threshold-disabled' )->text() => 0 ];
		foreach ( $stubThresholdValues as $value ) {
			$stubThresholdOptions[$context->msg( 'size-bytes', $value )->text()] = $value;
		}

		$defaultPreferences['stubthreshold'] = [
			'type' => 'select',
			'section' => 'rendering/advancedrendering',
			'options' => $stubThresholdOptions,
			// This is not a raw HTML message; label-raw is needed for the manual <a></a>
			'label-raw' => $context->msg( 'stub-threshold' )->rawParams(
				'<a href="#" class="stub">' .
				$context->msg( 'stub-threshold-sample-link' )->parse() .
				'</a>' )->parse(),
		];

		$defaultPreferences['showhiddencats'] = [
			'type' => 'toggle',
			'section' => 'rendering/advancedrendering',
			'label-message' => 'tog-showhiddencats'
		];

		$defaultPreferences['numberheadings'] = [
			'type' => 'toggle',
			'section' => 'rendering/advancedrendering',
			'label-message' => 'tog-numberheadings',
		];
	}

	/**
	 * @param User $user
	 * @param IContextSource $context
	 * @param array $defaultPreferences
	 */
	static function editingPreferences( $user, IContextSource $context, &$defaultPreferences ) {
		# # Editing #####################################
		$defaultPreferences['editsectiononrightclick'] = [
			'type' => 'toggle',
			'section' => 'editing/advancedediting',
			'label-message' => 'tog-editsectiononrightclick',
		];
		$defaultPreferences['editondblclick'] = [
			'type' => 'toggle',
			'section' => 'editing/advancedediting',
			'label-message' => 'tog-editondblclick',
		];

		if ( $context->getConfig()->get( 'AllowUserCssPrefs' ) ) {
			$defaultPreferences['editfont'] = [
				'type' => 'select',
				'section' => 'editing/editor',
				'label-message' => 'editfont-style',
				'options' => [
					$context->msg( 'editfont-default' )->text() => 'default',
					$context->msg( 'editfont-monospace' )->text() => 'monospace',
					$context->msg( 'editfont-sansserif' )->text() => 'sans-serif',
					$context->msg( 'editfont-serif' )->text() => 'serif',
				]
			];
		}
		$defaultPreferences['cols'] = [
			'type' => 'int',
			'label-message' => 'columns',
			'section' => 'editing/editor',
			'min' => 4,
			'max' => 1000,
		];
		$defaultPreferences['rows'] = [
			'type' => 'int',
			'label-message' => 'rows',
			'section' => 'editing/editor',
			'min' => 4,
			'max' => 1000,
		];
		if ( $user->isAllowed( 'minoredit' ) ) {
			$defaultPreferences['minordefault'] = [
				'type' => 'toggle',
				'section' => 'editing/editor',
				'label-message' => 'tog-minordefault',
			];
		}
		$defaultPreferences['forceeditsummary'] = [
			'type' => 'toggle',
			'section' => 'editing/editor',
			'label-message' => 'tog-forceeditsummary',
		];
		$defaultPreferences['useeditwarning'] = [
			'type' => 'toggle',
			'section' => 'editing/editor',
			'label-message' => 'tog-useeditwarning',
		];
		$defaultPreferences['showtoolbar'] = [
			'type' => 'toggle',
			'section' => 'editing/editor',
			'label-message' => 'tog-showtoolbar',
		];

		$defaultPreferences['previewonfirst'] = [
			'type' => 'toggle',
			'section' => 'editing/preview',
			'label-message' => 'tog-previewonfirst',
		];
		$defaultPreferences['previewontop'] = [
			'type' => 'toggle',
			'section' => 'editing/preview',
			'label-message' => 'tog-previewontop',
		];
		$defaultPreferences['uselivepreview'] = [
			'type' => 'toggle',
			'section' => 'editing/preview',
			'label-message' => 'tog-uselivepreview',
		];

	}

	/**
	 * @param User $user
	 * @param IContextSource $context
	 * @param array $defaultPreferences
	 */
	static function rcPreferences( $user, IContextSource $context, &$defaultPreferences ) {
		$config = $context->getConfig();
		$rcMaxAge = $config->get( 'RCMaxAge' );
		# # RecentChanges #####################################
		$defaultPreferences['rcdays'] = [
			'type' => 'float',
			'label-message' => 'recentchangesdays',
			'section' => 'rc/displayrc',
			'min' => 1,
			'max' => ceil( $rcMaxAge / ( 3600 * 24 ) ),
			'help' => $context->msg( 'recentchangesdays-max' )->numParams(
				ceil( $rcMaxAge / ( 3600 * 24 ) ) )->escaped()
		];
		$defaultPreferences['rclimit'] = [
			'type' => 'int',
			'label-message' => 'recentchangescount',
			'help-message' => 'prefs-help-recentchangescount',
			'section' => 'rc/displayrc',
		];
		$defaultPreferences['usenewrc'] = [
			'type' => 'toggle',
			'label-message' => 'tog-usenewrc',
			'section' => 'rc/advancedrc',
		];
		$defaultPreferences['hideminor'] = [
			'type' => 'toggle',
			'label-message' => 'tog-hideminor',
			'section' => 'rc/advancedrc',
		];

		if ( $config->get( 'RCWatchCategoryMembership' ) ) {
			$defaultPreferences['hidecategorization'] = [
				'type' => 'toggle',
				'label-message' => 'tog-hidecategorization',
				'section' => 'rc/advancedrc',
			];
		}

		if ( $user->useRCPatrol() ) {
			$defaultPreferences['hidepatrolled'] = [
				'type' => 'toggle',
				'section' => 'rc/advancedrc',
				'label-message' => 'tog-hidepatrolled',
			];
		}

		if ( $user->useNPPatrol() ) {
			$defaultPreferences['newpageshidepatrolled'] = [
				'type' => 'toggle',
				'section' => 'rc/advancedrc',
				'label-message' => 'tog-newpageshidepatrolled',
			];
		}

		if ( $config->get( 'RCShowWatchingUsers' ) ) {
			$defaultPreferences['shownumberswatching'] = [
				'type' => 'toggle',
				'section' => 'rc/advancedrc',
				'label-message' => 'tog-shownumberswatching',
			];
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

		# # Watchlist #####################################
		if ( $user->isAllowed( 'editmywatchlist' ) ) {
			$editWatchlistLinks = [];
			$editWatchlistModes = [
				'edit' => [ 'EditWatchlist', false ],
				'raw' => [ 'EditWatchlist', 'raw' ],
				'clear' => [ 'EditWatchlist', 'clear' ],
			];
			foreach ( $editWatchlistModes as $editWatchlistMode => $mode ) {
				// Messages: prefs-editwatchlist-edit, prefs-editwatchlist-raw, prefs-editwatchlist-clear
				$editWatchlistLinks[] = Linker::linkKnown(
					SpecialPage::getTitleFor( $mode[0], $mode[1] ),
					$context->msg( "prefs-editwatchlist-{$editWatchlistMode}" )->parse()
				);
			}

			$defaultPreferences['editwatchlist'] = [
				'type' => 'info',
				'raw' => true,
				'default' => $context->getLanguage()->pipeList( $editWatchlistLinks ),
				'label-message' => 'prefs-editwatchlist-label',
				'section' => 'watchlist/editwatchlist',
			];
		}

		$defaultPreferences['watchlistdays'] = [
			'type' => 'float',
			'min' => 0,
			'max' => $watchlistdaysMax,
			'section' => 'watchlist/displaywatchlist',
			'help' => $context->msg( 'prefs-watchlist-days-max' )->numParams(
				$watchlistdaysMax )->escaped(),
			'label-message' => 'prefs-watchlist-days',
		];
		$defaultPreferences['wllimit'] = [
			'type' => 'int',
			'min' => 0,
			'max' => 1000,
			'label-message' => 'prefs-watchlist-edits',
			'help' => $context->msg( 'prefs-watchlist-edits-max' )->escaped(),
			'section' => 'watchlist/displaywatchlist',
		];
		$defaultPreferences['extendwatchlist'] = [
			'type' => 'toggle',
			'section' => 'watchlist/advancedwatchlist',
			'label-message' => 'tog-extendwatchlist',
		];
		$defaultPreferences['watchlisthideminor'] = [
			'type' => 'toggle',
			'section' => 'watchlist/advancedwatchlist',
			'label-message' => 'tog-watchlisthideminor',
		];
		$defaultPreferences['watchlisthidebots'] = [
			'type' => 'toggle',
			'section' => 'watchlist/advancedwatchlist',
			'label-message' => 'tog-watchlisthidebots',
		];
		$defaultPreferences['watchlisthideown'] = [
			'type' => 'toggle',
			'section' => 'watchlist/advancedwatchlist',
			'label-message' => 'tog-watchlisthideown',
		];
		$defaultPreferences['watchlisthideanons'] = [
			'type' => 'toggle',
			'section' => 'watchlist/advancedwatchlist',
			'label-message' => 'tog-watchlisthideanons',
		];
		$defaultPreferences['watchlisthideliu'] = [
			'type' => 'toggle',
			'section' => 'watchlist/advancedwatchlist',
			'label-message' => 'tog-watchlisthideliu',
		];
		$defaultPreferences['watchlistreloadautomatically'] = [
			'type' => 'toggle',
			'section' => 'watchlist/advancedwatchlist',
			'label-message' => 'tog-watchlistreloadautomatically',
		];

		if ( $config->get( 'RCWatchCategoryMembership' ) ) {
			$defaultPreferences['watchlisthidecategorization'] = [
				'type' => 'toggle',
				'section' => 'watchlist/advancedwatchlist',
				'label-message' => 'tog-watchlisthidecategorization',
			];
		}

		if ( $user->useRCPatrol() ) {
			$defaultPreferences['watchlisthidepatrolled'] = [
				'type' => 'toggle',
				'section' => 'watchlist/advancedwatchlist',
				'label-message' => 'tog-watchlisthidepatrolled',
			];
		}

		$watchTypes = [
			'edit' => 'watchdefault',
			'move' => 'watchmoves',
			'delete' => 'watchdeletion'
		];

		// Kinda hacky
		if ( $user->isAllowed( 'createpage' ) || $user->isAllowed( 'createtalk' ) ) {
			$watchTypes['read'] = 'watchcreations';
		}

		if ( $user->isAllowed( 'rollback' ) ) {
			$watchTypes['rollback'] = 'watchrollback';
		}

		if ( $user->isAllowed( 'upload' ) ) {
			$watchTypes['upload'] = 'watchuploads';
		}

		foreach ( $watchTypes as $action => $pref ) {
			if ( $user->isAllowed( $action ) ) {
				// Messages:
				// tog-watchdefault, tog-watchmoves, tog-watchdeletion, tog-watchcreations, tog-watchuploads
				// tog-watchrollback
				$defaultPreferences[$pref] = [
					'type' => 'toggle',
					'section' => 'watchlist/advancedwatchlist',
					'label-message' => "tog-$pref",
				];
			}
		}

		if ( $config->get( 'EnableAPI' ) ) {
			$defaultPreferences['watchlisttoken'] = [
				'type' => 'api',
			];
			$defaultPreferences['watchlisttoken-info'] = [
				'type' => 'info',
				'section' => 'watchlist/tokenwatchlist',
				'label-message' => 'prefs-watchlist-token',
				'default' => $user->getTokenFromOption( 'watchlisttoken' ),
				'help-message' => 'prefs-help-watchlist-token2',
			];
		}
	}

	/**
	 * @param User $user
	 * @param IContextSource $context
	 * @param array $defaultPreferences
	 */
	static function searchPreferences( $user, IContextSource $context, &$defaultPreferences ) {
		foreach ( MWNamespace::getValidNamespaces() as $n ) {
			$defaultPreferences['searchNs' . $n] = [
				'type' => 'api',
			];
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
		$ret = [];

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
			$linkTools = [];

			# Mark the default skin
			if ( strcasecmp( $skinkey, $defaultSkin ) === 0 ) {
				$linkTools[] = $context->msg( 'default' )->escaped();
				$foundDefault = true;
			}

			# Create preview link
			$mplink = htmlspecialchars( $mptitle->getLocalURL( [ 'useskin' => $skinkey ] ) );
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
			return [];
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

		$ret = [];

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
		$ret = [];
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
		$ret = [];
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
			return Xml::element( 'span', [ 'class' => 'error' ],
				$form->msg( 'badsiglength' )->numParams( $maxSigChars )->text() );
		} elseif ( isset( $alldata['fancysig'] ) &&
				$alldata['fancysig'] &&
				$wgParser->validateSig( $signature ) === false
		) {
			return Xml::element(
				'span',
				[ 'class' => 'error' ],
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
	 * @return PreferencesForm|HtmlForm
	 */
	static function getFormObject(
		$user,
		IContextSource $context,
		$formClass = 'PreferencesForm',
		array $remove = []
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
		$htmlForm->setAutocomplete( 'off' );
		$htmlForm->setSubmitText( $context->msg( 'saveprefs' )->text() );
		# Used message keys: 'accesskey-preferences-save', 'tooltip-preferences-save'
		$htmlForm->setSubmitTooltip( 'preferences-save' );
		$htmlForm->setSubmitID( 'prefsubmit' );
		$htmlForm->setSubmitCallback( [ 'Preferences', 'tryFormSubmit' ] );

		return $htmlForm;
	}

	/**
	 * @param IContextSource $context
	 * @return array
	 */
	static function getTimezoneOptions( IContextSource $context ) {
		$opt = [];

		$localTZoffset = $context->getConfig()->get( 'LocalTZoffset' );
		$timeZoneList = self::getTimeZoneList( $context->getLanguage() );

		$timestamp = MWTimestamp::getLocalInstance();
		// Check that the LocalTZoffset is the same as the local time zone offset
		if ( $localTZoffset == $timestamp->format( 'Z' ) / 60 ) {
			$timezoneName = $timestamp->getTimezone()->getName();
			// Localize timezone
			if ( isset( $timeZoneList[$timezoneName] ) ) {
				$timezoneName = $timeZoneList[$timezoneName]['name'];
			}
			$server_tz_msg = $context->msg(
				'timezoneuseserverdefault',
				$timezoneName
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

		foreach ( $timeZoneList as $timeZoneInfo ) {
			$region = $timeZoneInfo['region'];
			if ( !isset( $opt[$region] ) ) {
				$opt[$region] = [];
			}
			$opt[$region][$timeZoneInfo['name']] = $timeZoneInfo['timecorrection'];
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
				# https://en.wikipedia.org/wiki/Timezone
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

			Hooks::run( 'PreferencesFormPreSave', [ $formData, $form, $user, &$result ] );
		}

		MediaWiki\Auth\AuthManager::callLegacyAuthPlugin( 'updateExternalDB', [ $user ] );
		$user->saveSettings();

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
			$urlOptions = [];

			if ( $res === 'eauth' ) {
				$urlOptions['eauth'] = 1;
			}

			$urlOptions += $form->getExtraSuccessRedirectParameters();

			$url = $form->getTitle()->getFullURL( $urlOptions );

			$context = $form->getContext();
			// Set session data for the success message
			$context->getRequest()->setSessionData( 'specialPreferencesSaveSuccess', 1 );

			$context->getOutput()->redirect( $url );
		}

		return Status::newGood();
	}

	/**
	 * Get a list of all time zones
	 * @param Language $language Language used for the localized names
	 * @return array A list of all time zones. The system name of the time zone is used as key and
	 *  the value is an array which contains localized name, the timecorrection value used for
	 *  preferences and the region
	 * @since 1.26
	 */
	public static function getTimeZoneList( Language $language ) {
		$identifiers = DateTimeZone::listIdentifiers();
		if ( $identifiers === false ) {
			return [];
		}
		sort( $identifiers );

		$tzRegions = [
			'Africa' => wfMessage( 'timezoneregion-africa' )->inLanguage( $language )->text(),
			'America' => wfMessage( 'timezoneregion-america' )->inLanguage( $language )->text(),
			'Antarctica' => wfMessage( 'timezoneregion-antarctica' )->inLanguage( $language )->text(),
			'Arctic' => wfMessage( 'timezoneregion-arctic' )->inLanguage( $language )->text(),
			'Asia' => wfMessage( 'timezoneregion-asia' )->inLanguage( $language )->text(),
			'Atlantic' => wfMessage( 'timezoneregion-atlantic' )->inLanguage( $language )->text(),
			'Australia' => wfMessage( 'timezoneregion-australia' )->inLanguage( $language )->text(),
			'Europe' => wfMessage( 'timezoneregion-europe' )->inLanguage( $language )->text(),
			'Indian' => wfMessage( 'timezoneregion-indian' )->inLanguage( $language )->text(),
			'Pacific' => wfMessage( 'timezoneregion-pacific' )->inLanguage( $language )->text(),
		];
		asort( $tzRegions );

		$timeZoneList = [];

		$now = new DateTime();

		foreach ( $identifiers as $identifier ) {
			$parts = explode( '/', $identifier, 2 );

			// DateTimeZone::listIdentifiers() returns a number of
			// backwards-compatibility entries. This filters them out of the
			// list presented to the user.
			if ( count( $parts ) !== 2 || !array_key_exists( $parts[0], $tzRegions ) ) {
				continue;
			}

			// Localize region
			$parts[0] = $tzRegions[$parts[0]];

			$dateTimeZone = new DateTimeZone( $identifier );
			$minDiff = floor( $dateTimeZone->getOffset( $now ) / 60 );

			$display = str_replace( '_', ' ', $parts[0] . '/' . $parts[1] );
			$value = "ZoneInfo|$minDiff|$identifier";

			$timeZoneList[$identifier] = [
				'name' => $display,
				'timecorrection' => $value,
				'region' => $parts[0],
			];
		}

		return $timeZoneList;
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
		return [];
	}

	/**
	 * @param string $html
	 * @return string
	 */
	function wrapForm( $html ) {
		$html = Xml::tags( 'div', [ 'id' => 'preferences' ], $html );

		return parent::wrapForm( $html );
	}

	/**
	 * @return string
	 */
	function getButtons() {

		$attrs = [ 'id' => 'mw-prefs-restoreprefs' ];

		if ( !$this->getModifiedUser()->isAllowedAny( 'editmyprivateinfo', 'editmyoptions' ) ) {
			return '';
		}

		$html = parent::getButtons();

		if ( $this->getModifiedUser()->isAllowed( 'editmyoptions' ) ) {
			$t = SpecialPage::getTitleFor( 'Preferences', 'reset' );

			$html .= "\n" . Linker::link( $t, $this->msg( 'restoreprefs' )->escaped(),
				Html::buttonAttributes( $attrs, [ 'mw-ui-quiet' ] ) );

			$html = Xml::tags( 'div', [ 'class' => 'mw-prefs-buttons' ], $html );
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
		Hooks::run( 'PreferencesGetLegend', [ $this, $key, &$legend ] );
		return $legend;
	}

	/**
	 * Get the keys of each top level preference section.
	 * @return array of section keys
	 */
	function getPreferenceSections() {
		return array_keys( array_filter( $this->mFieldTree, 'is_array' ) );
	}
}
