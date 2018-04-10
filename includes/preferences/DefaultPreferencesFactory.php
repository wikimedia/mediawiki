<?php
/**
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

namespace MediaWiki\Preferences;

use CentralIdLookup;
use Config;
use DateTime;
use DateTimeZone;
use Exception;
use Hooks;
use Html;
use HtmlArmor;
use HTMLForm;
use HTMLFormField;
use IContextSource;
use Language;
use LanguageCode;
use LanguageConverter;
use MediaWiki\Auth\AuthManager;
use MediaWiki\Auth\PasswordAuthenticationRequest;
use MediaWiki\Linker\LinkRenderer;
use MediaWiki\MediaWikiServices;
use MessageLocalizer;
use MWException;
use MWNamespace;
use MWTimestamp;
use Parser;
use ParserOptions;
use PreferencesForm;
use Psr\Log\LoggerAwareTrait;
use Psr\Log\NullLogger;
use Skin;
use SpecialPage;
use Status;
use Title;
use User;
use UserGroupMembership;
use Xml;

/**
 * This is the default implementation of PreferencesFactory.
 */
class DefaultPreferencesFactory implements PreferencesFactory {
	use LoggerAwareTrait;

	/** @var Config */
	protected $config;

	/** @var Language The wiki's content language, equivalent to $wgContLang. */
	protected $contLang;

	/** @var AuthManager */
	protected $authManager;

	/** @var LinkRenderer */
	protected $linkRenderer;

	/**
	 * @param Config $config
	 * @param Language $contLang
	 * @param AuthManager $authManager
	 * @param LinkRenderer $linkRenderer
	 */
	public function __construct(
		Config $config,
		Language $contLang,
		AuthManager $authManager,
		LinkRenderer $linkRenderer
	) {
		$this->config = $config;
		$this->contLang = $contLang;
		$this->authManager = $authManager;
		$this->linkRenderer = $linkRenderer;
		$this->logger = new NullLogger();
	}

	/**
	 * @return callable[]
	 */
	protected function getSaveFilters() {
		// Wrap intval() so that we can pass it multiple parameters and treat all filters the same.
		$intvalFilter = function ( $value, $alldata ) {
			return intval( $value );
		};
		return [
			'timecorrection' => [ $this, 'filterTimezoneInput' ],
			'rclimit' => $intvalFilter,
			'wllimit' => $intvalFilter,
			'searchlimit' => $intvalFilter,
		];
	}

	/**
	 * @inheritDoc
	 */
	public function getSaveBlacklist() {
		return [
			'realname',
			'emailaddress',
		];
	}

	/**
	 * @throws MWException
	 * @param User $user
	 * @param IContextSource $context
	 * @return array|null
	 */
	public function getFormDescriptor( User $user, IContextSource $context ) {
		$preferences = [];

		$canIPUseHTTPS = wfCanIPUseHTTPS( $context->getRequest()->getIP() );
		$this->profilePreferences( $user, $context, $preferences, $canIPUseHTTPS );
		$this->skinPreferences( $user, $context, $preferences );
		$this->datetimePreferences( $user, $context, $preferences );
		$this->filesPreferences( $context, $preferences );
		$this->renderingPreferences( $context, $preferences );
		$this->editingPreferences( $user, $context, $preferences );
		$this->rcPreferences( $user, $context, $preferences );
		$this->watchlistPreferences( $user, $context, $preferences );
		$this->searchPreferences( $preferences );

		Hooks::run( 'GetPreferences', [ $user, &$preferences ] );

		$this->loadPreferenceValues( $user, $context, $preferences );
		$this->logger->debug( "Created form descriptor for user '{$user->getName()}'" );
		return $preferences;
	}

	/**
	 * Loads existing values for a given array of preferences
	 * @throws MWException
	 * @param User $user
	 * @param IContextSource $context
	 * @param array &$defaultPreferences Array to load values for
	 * @return array|null
	 */
	private function loadPreferenceValues(
		User $user, IContextSource $context, &$defaultPreferences
	) {
		# # Remove preferences that wikis don't want to use
		foreach ( $this->config->get( 'HiddenPrefs' ) as $pref ) {
			if ( isset( $defaultPreferences[$pref] ) ) {
				unset( $defaultPreferences[$pref] );
			}
		}

		# # Make sure that form fields have their parent set. See T43337.
		$dummyForm = new HTMLForm( [], $context );

		$disable = !$user->isAllowed( 'editmyoptions' );

		$defaultOptions = User::getDefaultOptions();
		# # Prod in defaults from the user
		foreach ( $defaultPreferences as $name => &$info ) {
			$prefFromUser = $this->getOptionFromUser( $name, $info, $user );
			if ( $disable && !in_array( $name, $this->getSaveBlacklist() ) ) {
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
	protected function getOptionFromUser( $name, $info, User $user ) {
		$val = $user->getOption( $name );

		// Handling for multiselect preferences
		if ( ( isset( $info['type'] ) && $info['type'] == 'multiselect' ) ||
				( isset( $info['class'] ) && $info['class'] == \HTMLMultiSelectField::class ) ) {
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
				( isset( $info['class'] ) && $info['class'] == \HTMLCheckMatrix::class ) ) {
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
	 * @todo Inject user Language instead of using context.
	 * @param User $user
	 * @param IContextSource $context
	 * @param array &$defaultPreferences
	 * @param bool $canIPUseHTTPS Whether the user's IP is likely to be able to access the wiki
	 * via HTTPS.
	 * @return void
	 */
	protected function profilePreferences(
		User $user, IContextSource $context, &$defaultPreferences, $canIPUseHTTPS
	) {
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

		$lang = $context->getLanguage();

		# Get groups to which the user belongs
		$userEffectiveGroups = $user->getEffectiveGroups();
		$userGroupMemberships = $user->getGroupMemberships();
		$userGroups = $userMembers = $userTempGroups = $userTempMembers = [];
		foreach ( $userEffectiveGroups as $ueg ) {
			if ( $ueg == '*' ) {
				// Skip the default * group, seems useless here
				continue;
			}

			if ( isset( $userGroupMemberships[$ueg] ) ) {
				$groupStringOrObject = $userGroupMemberships[$ueg];
			} else {
				$groupStringOrObject = $ueg;
			}

			$userG = UserGroupMembership::getLink( $groupStringOrObject, $context, 'html' );
			$userM = UserGroupMembership::getLink( $groupStringOrObject, $context, 'html',
				$userName );

			// Store expiring groups separately, so we can place them before non-expiring
			// groups in the list. This is to avoid the ambiguity of something like
			// "administrator, bureaucrat (until X date)" -- users might wonder whether the
			// expiry date applies to both groups, or just the last one
			if ( $groupStringOrObject instanceof UserGroupMembership &&
				$groupStringOrObject->getExpiry()
			) {
				$userTempGroups[] = $userG;
				$userTempMembers[] = $userM;
			} else {
				$userGroups[] = $userG;
				$userMembers[] = $userM;
			}
		}
		sort( $userGroups );
		sort( $userMembers );
		sort( $userTempGroups );
		sort( $userTempMembers );
		$userGroups = array_merge( $userTempGroups, $userGroups );
		$userMembers = array_merge( $userTempMembers, $userMembers );

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

		$contribTitle = SpecialPage::getTitleFor( "Contributions", $userName );
		$formattedEditCount = $lang->formatNum( $user->getEditCount() );
		$editCount = $this->linkRenderer->makeLink( $contribTitle, $formattedEditCount );

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
			'type' => $canEditPrivateInfo && $this->authManager->allowsPropertyChange( 'realname' )
				? 'text' : 'info',
			'default' => $user->getRealName(),
			'section' => 'personal/info',
			'label-message' => 'yourrealname',
			'help-message' => 'prefs-help-realname',
		];

		if ( $canEditPrivateInfo && $this->authManager->allowsAuthenticationDataChange(
			new PasswordAuthenticationRequest(), false )->isGood()
		) {
			$link = $this->linkRenderer->makeLink( SpecialPage::getTitleFor( 'ChangePassword' ),
				$context->msg( 'prefs-resetpass' )->text(), [],
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
		if ( $this->config->get( 'SecureLogin' ) && $canIPUseHTTPS ) {
			$defaultPreferences['prefershttps'] = [
				'type' => 'toggle',
				'label-message' => 'tog-prefershttps',
				'help-message' => 'prefs-help-prefershttps',
				'section' => 'personal/info'
			];
		}

		// Language
		$languages = Language::fetchLanguageNames( null, 'mw' );
		$languageCode = $this->config->get( 'LanguageCode' );
		if ( !array_key_exists( $languageCode, $languages ) ) {
			$languages[$languageCode] = $languageCode;
		}
		ksort( $languages );

		$options = [];
		foreach ( $languages as $code => $name ) {
			$display = LanguageCode::bcp47( $code ) . ' - ' . $name;
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
		if ( !$this->config->get( 'DisableLangConversion' ) ) {
			foreach ( LanguageConverter::$languagesWithVariants as $langCode ) {
				if ( $langCode == $this->contLang->getCode() ) {
					$variants = $this->contLang->getVariants();

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
						$display = LanguageCode::bcp47( $code ) . ' - ' . $name;
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
		$toggles = $this->contLang->getExtraUserToggles();

		foreach ( $toggles as $toggle ) {
			$defaultPreferences[$toggle] = [
				'type' => 'toggle',
				'section' => 'personal/i18n',
				'label-message' => "tog-$toggle",
			];
		}

		// show a preview of the old signature first
		$oldsigWikiText = MediaWikiServices::getInstance()->getParser()->preSaveTransform(
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
			'type' => $this->authManager->allowsPropertyChange( 'nickname' ) ? 'text' : 'info',
			'maxlength' => $this->config->get( 'MaxSigChars' ),
			'label-message' => 'yournick',
			'validation-callback' => function ( $signature, $alldata, HTMLForm $form ) {
				return $this->validateSignature( $signature, $alldata, $form );
			},
			'section' => 'personal/signature',
			'filter-callback' => function ( $signature, array $alldata, HTMLForm $form ) {
				return $this->cleanSignature( $signature, $alldata, $form );
			},
		];
		$defaultPreferences['fancysig'] = [
			'type' => 'toggle',
			'label-message' => 'tog-fancysig',
			// show general help about signature at the bottom of the section
			'help-message' => 'prefs-help-signature',
			'section' => 'personal/signature'
		];

		# # Email stuff

		if ( $this->config->get( 'EnableEmail' ) ) {
			if ( $canViewPrivateInfo ) {
				$helpMessages[] = $this->config->get( 'EmailConfirmToEdit' )
						? 'prefs-help-email-required'
						: 'prefs-help-email';

				if ( $this->config->get( 'EnableUserEmail' ) ) {
					// additional messages when users can send email to each other
					$helpMessages[] = 'prefs-help-email-others';
				}

				$emailAddress = $user->getEmail() ? htmlspecialchars( $user->getEmail() ) : '';
				if ( $canEditPrivateInfo && $this->authManager->allowsPropertyChange( 'emailaddress' ) ) {
					$link = $this->linkRenderer->makeLink(
						SpecialPage::getTitleFor( 'ChangeEmail' ),
						$context->msg( $user->getEmail() ? 'prefs-changeemail' : 'prefs-setemail' )->text(),
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

			if ( $this->config->get( 'EmailAuthentication' ) ) {
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
							$this->linkRenderer->makeKnownLink(
								SpecialPage::getTitleFor( 'Confirmemail' ),
								$context->msg( 'emailconfirmlink' )->text()
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

			if ( $this->config->get( 'EnableUserEmail' ) && $user->isAllowed( 'sendemail' ) ) {
				$defaultPreferences['disablemail'] = [
					'id' => 'wpAllowEmail',
					'type' => 'toggle',
					'invert' => true,
					'section' => 'personal/email',
					'label-message' => 'allowemail',
					'disabled' => $disableEmailPrefs,
				];

				$defaultPreferences['email-allow-new-users'] = [
					'id' => 'wpAllowEmailFromNewUsers',
					'type' => 'toggle',
					'section' => 'personal/email',
					'label-message' => 'email-allow-new-users-label',
					'disabled' => $disableEmailPrefs,
				];

				$defaultPreferences['ccmeonemails'] = [
					'type' => 'toggle',
					'section' => 'personal/email',
					'label-message' => 'tog-ccmeonemails',
					'disabled' => $disableEmailPrefs,
				];

				if ( $this->config->get( 'EnableUserEmailBlacklist' ) ) {
					$lookup = CentralIdLookup::factory();
					$ids = $user->getOption( 'email-blacklist', [] );
					$names = $ids ? $lookup->namesFromCentralIds( $ids, $user ) : [];

					$defaultPreferences['email-blacklist'] = [
						'type' => 'usersmultiselect',
						'label-message' => 'email-blacklist-label',
						'section' => 'personal/email',
						'default' => implode( "\n", $names ),
						'disabled' => $disableEmailPrefs,
					];
				}
			}

			if ( $this->config->get( 'EnotifWatchlist' ) ) {
				$defaultPreferences['enotifwatchlistpages'] = [
					'type' => 'toggle',
					'section' => 'personal/email',
					'label-message' => 'tog-enotifwatchlistpages',
					'disabled' => $disableEmailPrefs,
				];
			}
			if ( $this->config->get( 'EnotifUserTalk' ) ) {
				$defaultPreferences['enotifusertalkpages'] = [
					'type' => 'toggle',
					'section' => 'personal/email',
					'label-message' => 'tog-enotifusertalkpages',
					'disabled' => $disableEmailPrefs,
				];
			}
			if ( $this->config->get( 'EnotifUserTalk' ) || $this->config->get( 'EnotifWatchlist' ) ) {
				if ( $this->config->get( 'EnotifMinorEdits' ) ) {
					$defaultPreferences['enotifminoredits'] = [
						'type' => 'toggle',
						'section' => 'personal/email',
						'label-message' => 'tog-enotifminoredits',
						'disabled' => $disableEmailPrefs,
					];
				}

				if ( $this->config->get( 'EnotifRevealEditorAddress' ) ) {
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
	 * @param array &$defaultPreferences
	 * @return void
	 */
	protected function skinPreferences( User $user, IContextSource $context, &$defaultPreferences ) {
		# # Skin #####################################

		// Skin selector, if there is at least one valid skin
		$skinOptions = $this->generateSkinOptions( $user, $context );
		if ( $skinOptions ) {
			$defaultPreferences['skin'] = [
				'type' => 'radio',
				'options' => $skinOptions,
				'section' => 'rendering/skin',
			];
		}

		$allowUserCss = $this->config->get( 'AllowUserCss' );
		$allowUserJs = $this->config->get( 'AllowUserJs' );
		# Create links to user CSS/JS pages for all skins
		# This code is basically copied from generateSkinOptions().  It'd
		# be nice to somehow merge this back in there to avoid redundancy.
		if ( $allowUserCss || $allowUserJs ) {
			$linkTools = [];
			$userName = $user->getName();

			if ( $allowUserCss ) {
				$cssPage = Title::makeTitleSafe( NS_USER, $userName . '/common.css' );
				$cssLinkText = $context->msg( 'prefs-custom-css' )->text();
				$linkTools[] = $this->linkRenderer->makeLink( $cssPage, $cssLinkText );
			}

			if ( $allowUserJs ) {
				$jsPage = Title::makeTitleSafe( NS_USER, $userName . '/common.js' );
				$jsLinkText = $context->msg( 'prefs-custom-js' )->text();
				$linkTools[] = $this->linkRenderer->makeLink( $jsPage, $jsLinkText );
			}

			$defaultPreferences['commoncssjs'] = [
				'type' => 'info',
				'raw' => true,
				'default' => $context->getLanguage()->pipeList( $linkTools ),
				'label-message' => 'prefs-common-config',
				'section' => 'rendering/skin',
			];
		}
	}

	/**
	 * @param IContextSource $context
	 * @param array &$defaultPreferences
	 */
	protected function filesPreferences( IContextSource $context, &$defaultPreferences ) {
		# # Files #####################################
		$defaultPreferences['imagesize'] = [
			'type' => 'select',
			'options' => $this->getImageSizes( $context ),
			'label-message' => 'imagemaxsize',
			'section' => 'rendering/files',
		];
		$defaultPreferences['thumbsize'] = [
			'type' => 'select',
			'options' => $this->getThumbSizes( $context ),
			'label-message' => 'thumbsize',
			'section' => 'rendering/files',
		];
	}

	/**
	 * @param User $user
	 * @param IContextSource $context
	 * @param array &$defaultPreferences
	 * @return void
	 */
	protected function datetimePreferences( $user, IContextSource $context, &$defaultPreferences ) {
		# # Date and time #####################################
		$dateOptions = $this->getDateOptions( $context );
		if ( $dateOptions ) {
			$defaultPreferences['date'] = [
				'type' => 'radio',
				'options' => $dateOptions,
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

		$tzOptions = $this->getTimezoneOptions( $context );

		$tzSetting = $tzOffset;
		if ( count( $tz ) > 1 && $tz[0] == 'ZoneInfo' &&
			!in_array( $tzOffset, HTMLFormField::flattenOptions( $tzOptions ) )
		) {
			// Timezone offset can vary with DST
			try {
				$userTZ = new DateTimeZone( $tz[2] );
				$minDiff = floor( $userTZ->getOffset( new DateTime( 'now' ) ) / 60 );
				$tzSetting = "ZoneInfo|$minDiff|{$tz[2]}";
			} catch ( Exception $e ) {
				// User has an invalid time zone set. Fall back to just using the offset
				$tz[0] = 'Offset';
			}
		}
		if ( count( $tz ) > 1 && $tz[0] == 'Offset' ) {
			$minDiff = $tz[1];
			$tzSetting = sprintf( '%+03d:%02d', floor( $minDiff / 60 ), abs( $minDiff ) % 60 );
		}

		$defaultPreferences['timecorrection'] = [
			'class' => \HTMLSelectOrOtherField::class,
			'label-message' => 'timezonelegend',
			'options' => $tzOptions,
			'default' => $tzSetting,
			'size' => 20,
			'section' => 'rendering/timeoffset',
		];
	}

	/**
	 * @param MessageLocalizer $l10n
	 * @param array &$defaultPreferences
	 */
	protected function renderingPreferences( MessageLocalizer $l10n, &$defaultPreferences ) {
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
		if ( $this->config->get( 'AllowUserCssPrefs' ) ) {
			$defaultPreferences['underline'] = [
				'type' => 'select',
				'options' => [
					$l10n->msg( 'underline-never' )->text() => 0,
					$l10n->msg( 'underline-always' )->text() => 1,
					$l10n->msg( 'underline-default' )->text() => 2,
				],
				'label-message' => 'tog-underline',
				'section' => 'rendering/advancedrendering',
			];
		}

		$stubThresholdValues = [ 50, 100, 500, 1000, 2000, 5000, 10000 ];
		$stubThresholdOptions = [ $l10n->msg( 'stub-threshold-disabled' )->text() => 0 ];
		foreach ( $stubThresholdValues as $value ) {
			$stubThresholdOptions[$l10n->msg( 'size-bytes', $value )->text()] = $value;
		}

		$defaultPreferences['stubthreshold'] = [
			'type' => 'select',
			'section' => 'rendering/advancedrendering',
			'options' => $stubThresholdOptions,
			// This is not a raw HTML message; label-raw is needed for the manual <a></a>
			'label-raw' => $l10n->msg( 'stub-threshold' )->rawParams(
				'<a href="#" class="stub">' .
				$l10n->msg( 'stub-threshold-sample-link' )->parse() .
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
	 * @param MessageLocalizer $l10n
	 * @param array &$defaultPreferences
	 */
	protected function editingPreferences( User $user, MessageLocalizer $l10n, &$defaultPreferences ) {
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

		if ( $this->config->get( 'AllowUserCssPrefs' ) ) {
			$defaultPreferences['editfont'] = [
				'type' => 'select',
				'section' => 'editing/editor',
				'label-message' => 'editfont-style',
				'options' => [
					$l10n->msg( 'editfont-monospace' )->text() => 'monospace',
					$l10n->msg( 'editfont-sansserif' )->text() => 'sans-serif',
					$l10n->msg( 'editfont-serif' )->text() => 'serif',
				]
			];
		}

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
	 * @param MessageLocalizer $l10n
	 * @param array &$defaultPreferences
	 */
	protected function rcPreferences( User $user, MessageLocalizer $l10n, &$defaultPreferences ) {
		$rcMaxAge = $this->config->get( 'RCMaxAge' );
		# # RecentChanges #####################################
		$defaultPreferences['rcdays'] = [
			'type' => 'float',
			'label-message' => 'recentchangesdays',
			'section' => 'rc/displayrc',
			'min' => 1,
			'max' => ceil( $rcMaxAge / ( 3600 * 24 ) ),
			'help' => $l10n->msg( 'recentchangesdays-max' )->numParams(
				ceil( $rcMaxAge / ( 3600 * 24 ) ) )->escaped()
		];
		$defaultPreferences['rclimit'] = [
			'type' => 'int',
			'min' => 0,
			'max' => 1000,
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
		$defaultPreferences['rcfilters-saved-queries'] = [
			'type' => 'api',
		];
		$defaultPreferences['rcfilters-wl-saved-queries'] = [
			'type' => 'api',
		];
		// Override RCFilters preferences for RecentChanges 'limit'
		$defaultPreferences['rcfilters-limit'] = [
			'type' => 'api',
		];
		$defaultPreferences['rcfilters-saved-queries-versionbackup'] = [
			'type' => 'api',
		];
		$defaultPreferences['rcfilters-wl-saved-queries-versionbackup'] = [
			'type' => 'api',
		];

		if ( $this->config->get( 'RCWatchCategoryMembership' ) ) {
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

		if ( $this->config->get( 'RCShowWatchingUsers' ) ) {
			$defaultPreferences['shownumberswatching'] = [
				'type' => 'toggle',
				'section' => 'rc/advancedrc',
				'label-message' => 'tog-shownumberswatching',
			];
		}

		if ( $this->config->get( 'StructuredChangeFiltersShowPreference' ) ) {
			$defaultPreferences['rcenhancedfilters-disable'] = [
				'type' => 'toggle',
				'section' => 'rc/opt-out',
				'label-message' => 'rcfilters-preference-label',
				'help-message' => 'rcfilters-preference-help',
			];
		}
	}

	/**
	 * @param User $user
	 * @param IContextSource $context
	 * @param array &$defaultPreferences
	 */
	protected function watchlistPreferences(
		User $user, IContextSource $context, &$defaultPreferences
	) {
		$watchlistdaysMax = ceil( $this->config->get( 'RCMaxAge' ) / ( 3600 * 24 ) );

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
				$editWatchlistLinks[] = $this->linkRenderer->makeKnownLink(
					SpecialPage::getTitleFor( $mode[0], $mode[1] ),
					new HtmlArmor( $context->msg( "prefs-editwatchlist-{$editWatchlistMode}" )->parse() )
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

		if ( !\SpecialWatchlist::checkStructuredFilterUiEnabled(
			$this->config,
			$user
		) ) {
			$defaultPreferences['watchlistreloadautomatically'] = [
				'type' => 'toggle',
				'section' => 'watchlist/advancedwatchlist',
				'label-message' => 'tog-watchlistreloadautomatically',
			];
		}

		$defaultPreferences['watchlistunwatchlinks'] = [
			'type' => 'toggle',
			'section' => 'watchlist/advancedwatchlist',
			'label-message' => 'tog-watchlistunwatchlinks',
		];

		if ( $this->config->get( 'RCWatchCategoryMembership' ) ) {
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

		if ( $this->config->get( 'EnableAPI' ) ) {
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
	 * @param array &$defaultPreferences
	 */
	protected function searchPreferences( &$defaultPreferences ) {
		foreach ( MWNamespace::getValidNamespaces() as $n ) {
			$defaultPreferences['searchNs' . $n] = [
				'type' => 'api',
			];
		}
	}

	/**
	 * @param User $user The User object
	 * @param IContextSource $context
	 * @return array Text/links to display as key; $skinkey as value
	 */
	protected function generateSkinOptions( User $user, IContextSource $context ) {
		$ret = [];

		$mptitle = Title::newMainPage();
		$previewtext = $context->msg( 'skin-preview' )->escaped();

		# Only show skins that aren't disabled in $wgSkipSkins
		$validSkinNames = Skin::getAllowedSkins();

		foreach ( $validSkinNames as $skinkey => &$skinname ) {
			$msg = $context->msg( "skinname-{$skinkey}" );
			if ( $msg->exists() ) {
				$skinname = htmlspecialchars( $msg->text() );
			}
		}

		$defaultSkin = $this->config->get( 'DefaultSkin' );
		$allowUserCss = $this->config->get( 'AllowUserCss' );
		$allowUserJs = $this->config->get( 'AllowUserJs' );

		# Sort by the internal name, so that the ordering is the same for each display language,
		# especially if some skin names are translated to use a different alphabet and some are not.
		uksort( $validSkinNames, function ( $a, $b ) use ( $defaultSkin ) {
			# Display the default first in the list by comparing it as lesser than any other.
			if ( strcasecmp( $a, $defaultSkin ) === 0 ) {
				return -1;
			}
			if ( strcasecmp( $b, $defaultSkin ) === 0 ) {
				return 1;
			}
			return strcasecmp( $a, $b );
		} );

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
				$cssLinkText = $context->msg( 'prefs-custom-css' )->text();
				$linkTools[] = $this->linkRenderer->makeLink( $cssPage, $cssLinkText );
			}

			if ( $allowUserJs ) {
				$jsPage = Title::makeTitleSafe( NS_USER, $user->getName() . '/' . $skinkey . '.js' );
				$jsLinkText = $context->msg( 'prefs-custom-js' )->text();
				$linkTools[] = $this->linkRenderer->makeLink( $jsPage, $jsLinkText );
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
	protected function getDateOptions( IContextSource $context ) {
		$lang = $context->getLanguage();
		$dateopts = $lang->getDatePreferences();

		$ret = [];

		if ( $dateopts ) {
			if ( !in_array( 'default', $dateopts ) ) {
				$dateopts[] = 'default'; // Make sure default is always valid T21237
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
	 * @param MessageLocalizer $l10n
	 * @return array
	 */
	protected function getImageSizes( MessageLocalizer $l10n ) {
		$ret = [];
		$pixels = $l10n->msg( 'unit-pixel' )->text();

		foreach ( $this->config->get( 'ImageLimits' ) as $index => $limits ) {
			// Note: A left-to-right marker (\u200e) is inserted, see T144386
			$display = "{$limits[0]}" . json_decode( '"\u200e"' ) . "×{$limits[1]}" . $pixels;
			$ret[$display] = $index;
		}

		return $ret;
	}

	/**
	 * @param MessageLocalizer $l10n
	 * @return array
	 */
	protected function getThumbSizes( MessageLocalizer $l10n ) {
		$ret = [];
		$pixels = $l10n->msg( 'unit-pixel' )->text();

		foreach ( $this->config->get( 'ThumbLimits' ) as $index => $size ) {
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
	protected function validateSignature( $signature, $alldata, HTMLForm $form ) {
		$maxSigChars = $this->config->get( 'MaxSigChars' );
		if ( mb_strlen( $signature ) > $maxSigChars ) {
			return Xml::element( 'span', [ 'class' => 'error' ],
				$form->msg( 'badsiglength' )->numParams( $maxSigChars )->text() );
		} elseif ( isset( $alldata['fancysig'] ) &&
				$alldata['fancysig'] &&
				MediaWikiServices::getInstance()->getParser()->validateSig( $signature ) === false
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
	protected function cleanSignature( $signature, $alldata, HTMLForm $form ) {
		$parser = MediaWikiServices::getInstance()->getParser();
		if ( isset( $alldata['fancysig'] ) && $alldata['fancysig'] ) {
			$signature = $parser->cleanSig( $signature );
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
	 * @return PreferencesForm|HTMLForm
	 */
	public function getForm(
		User $user,
		IContextSource $context,
		$formClass = PreferencesForm::class,
		array $remove = []
	) {
		$formDescriptor = $this->getFormDescriptor( $user, $context );
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
		$htmlForm->setSubmitID( 'prefcontrol' );
		$htmlForm->setSubmitCallback( function ( array $formData, PreferencesForm $form ) {
			return $this->submitForm( $formData, $form );
		} );

		return $htmlForm;
	}

	/**
	 * @param IContextSource $context
	 * @return array
	 */
	protected function getTimezoneOptions( IContextSource $context ) {
		$opt = [];

		$localTZoffset = $this->config->get( 'LocalTZoffset' );
		$timeZoneList = $this->getTimeZoneList( $context->getLanguage() );

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
	 * @param string $tz
	 * @param array $alldata
	 * @return string
	 */
	protected function filterTimezoneInput( $tz, array $alldata ) {
		$data = explode( '|', $tz, 3 );
		switch ( $data[0] ) {
			case 'ZoneInfo':
				$valid = false;

				if ( count( $data ) === 3 ) {
					// Make sure this timezone exists
					try {
						new DateTimeZone( $data[2] );
						// If the constructor didn't throw, we know it's valid
						$valid = true;
					} catch ( Exception $e ) {
						// Not a valid timezone
					}
				}

				if ( !$valid ) {
					// If the supplied timezone doesn't exist, fall back to the encoded offset
					return 'Offset|' . intval( $tz[1] );
				}
				return $tz;
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
				$minDiff = max( $minDiff, -720 ); # -12:00
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
	protected function saveFormData( $formData, PreferencesForm $form ) {
		$user = $form->getModifiedUser();
		$hiddenPrefs = $this->config->get( 'HiddenPrefs' );
		$result = true;

		if ( !$user->isAllowedAny( 'editmyprivateinfo', 'editmyoptions' ) ) {
			return Status::newFatal( 'mypreferencesprotected' );
		}

		// Filter input
		foreach ( array_keys( $formData ) as $name ) {
			$filters = $this->getSaveFilters();
			if ( isset( $filters[$name] ) ) {
				$formData[$name] = call_user_func( $filters[$name], $formData[$name], $formData );
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
			$oldUserOptions = $user->getOptions();

			foreach ( $this->getSaveBlacklist() as $b ) {
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

			// If the user changed the rclimit preference, also change the rcfilters-rclimit preference
			if (
				isset( $formData['rclimit'] ) &&
				intval( $formData[ 'rclimit' ] ) !== $user->getIntOption( 'rclimit' )
			) {
				$formData['rcfilters-limit'] = $formData['rclimit'];
			}

			// Keep old preferences from interfering due to back-compat code, etc.
			$user->resetOptions( 'unused', $form->getContext() );

			foreach ( $formData as $key => $value ) {
				$user->setOption( $key, $value );
			}

			Hooks::run(
				'PreferencesFormPreSave',
				[ $formData, $form, $user, &$result, $oldUserOptions ]
			);
		}

		AuthManager::callLegacyAuthPlugin( 'updateExternalDB', [ $user ] );
		$user->saveSettings();

		return $result;
	}

	/**
	 * DO NOT USE. Temporary function to punch hole for the Preferences class.
	 *
	 * @deprecated since 1.31, its inception
	 *
	 * @param array $formData
	 * @param PreferencesForm $form
	 * @return bool|Status|string
	 */
	public function legacySaveFormData( $formData, PreferencesForm $form ) {
		return $this->saveFormData( $formData, $form );
	}

	/**
	 * Save the form data and reload the page
	 *
	 * @param array $formData
	 * @param PreferencesForm $form
	 * @return Status
	 */
	protected function submitForm( array $formData, PreferencesForm $form ) {
		$res = $this->saveFormData( $formData, $form );

		if ( $res ) {
			$urlOptions = [];

			if ( $res === 'eauth' ) {
				$urlOptions['eauth'] = 1;
			}

			$urlOptions += $form->getExtraSuccessRedirectParameters();

			$url = $form->getTitle()->getFullURL( $urlOptions );

			$context = $form->getContext();
			// Set session data for the success message
			$context->getRequest()->getSession()->set( 'specialPreferencesSaveSuccess', 1 );

			$context->getOutput()->redirect( $url );
		}

		return Status::newGood();
	}

	/**
	 * DO NOT USE. Temporary function to punch hole for the Preferences class.
	 *
	 * @deprecated since 1.31, its inception
	 *
	 * @param array $formData
	 * @param PreferencesForm $form
	 * @return Status
	 */
	public function legacySubmitForm( array $formData, PreferencesForm $form ) {
		return $this->submitForm( $formData, $form );
	}

	/**
	 * Get a list of all time zones
	 * @param Language $language Language used for the localized names
	 * @return array A list of all time zones. The system name of the time zone is used as key and
	 *  the value is an array which contains localized name, the timecorrection value used for
	 *  preferences and the region
	 * @since 1.26
	 */
	protected function getTimeZoneList( Language $language ) {
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
