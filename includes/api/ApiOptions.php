<?php
/**
 * Copyright © 2012 Szymon Świerkosz beau@adres.pl
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

use MediaWiki\Logger\LoggerFactory;
use MediaWiki\MediaWikiServices;
use MediaWiki\Preferences\DefaultPreferencesFactory;
use MediaWiki\Preferences\PreferencesFactory;
use MediaWiki\User\Options\UserOptionsManager;
use MediaWiki\User\User;
use Wikimedia\ParamValidator\ParamValidator;

/**
 * API module that facilitates the changing of user's preferences.
 * Requires API write mode to be enabled.
 *
 * @ingroup API
 */
class ApiOptions extends ApiBase {
	/** @var User User account to modify */
	private $userForUpdates;

	private UserOptionsManager $userOptionsManager;
	private PreferencesFactory $preferencesFactory;

	/**
	 * @param ApiMain $main
	 * @param string $action
	 * @param UserOptionsManager|null $userOptionsManager
	 * @param PreferencesFactory|null $preferencesFactory
	 */
	public function __construct(
		ApiMain $main,
		$action,
		UserOptionsManager $userOptionsManager = null,
		PreferencesFactory $preferencesFactory = null
	) {
		parent::__construct( $main, $action );
		/**
		 * This class is extended by GlobalPreferences extension.
		 * So it falls back to the global state.
		 */
		$services = MediaWikiServices::getInstance();
		$this->userOptionsManager = $userOptionsManager ?? $services->getUserOptionsManager();
		$this->preferencesFactory = $preferencesFactory ?? $services->getPreferencesFactory();
	}

	/**
	 * Changes preferences of the current user.
	 */
	public function execute() {
		$user = $this->getUserForUpdates();
		if ( !$user || !$user->isNamed() ) {
			$this->dieWithError(
				[ 'apierror-mustbeloggedin', $this->msg( 'action-editmyoptions' ) ], 'notloggedin'
			);
		}

		$this->checkUserRightsAny( 'editmyoptions' );

		$params = $this->extractRequestParams();
		$changed = false;

		if ( isset( $params['optionvalue'] ) && !isset( $params['optionname'] ) ) {
			$this->dieWithError( [ 'apierror-missingparam', 'optionname' ] );
		}

		$resetKinds = $params['resetkinds'];
		if ( !$params['reset'] ) {
			$resetKinds = [];
		}

		$changes = [];
		if ( $params['change'] ) {
			foreach ( $params['change'] as $entry ) {
				$array = explode( '=', $entry, 2 );
				$changes[$array[0]] = $array[1] ?? null;
			}
		}
		if ( isset( $params['optionname'] ) ) {
			$newValue = $params['optionvalue'] ?? null;
			$changes[$params['optionname']] = $newValue;
		}

		$this->getHookRunner()->onApiOptions( $this, $user, $changes, $resetKinds );

		if ( $resetKinds ) {
			$this->resetPreferences( $resetKinds );
			$changed = true;
		}

		if ( !$changed && !count( $changes ) ) {
			$this->dieWithError( 'apierror-nochanges' );
		}

		$prefs = $this->getPreferences();
		$prefsKinds = $this->preferencesFactory->getResetKinds( $user, $this->getContext(), $changes );

		$htmlForm = new HTMLForm( DefaultPreferencesFactory::simplifyFormDescriptor( $prefs ), $this );
		foreach ( $changes as $key => $value ) {
			switch ( $prefsKinds[$key] ) {
				case 'registered':
					// Regular option.
					if ( $value === null ) {
						// Reset it
						$validation = true;
					} else {
						// Validate
						$field = $htmlForm->getField( $key );
						$validation = $field->validate( $value, $this->userOptionsManager->getOptions( $user ) );
					}
					break;
				case 'registered-multiselect':
				case 'registered-checkmatrix':
					// A key for a multiselect or checkmatrix option.
					// TODO: Apply validation properly.
					$validation = true;
					$value = $value !== null ? (bool)$value : null;
					break;
				case 'userjs':
					// Allow non-default preferences prefixed with 'userjs-', to be set by user scripts
					if ( strlen( $key ) > 255 ) {
						$validation = $this->msg( 'apiwarn-validationfailed-keytoolong', Message::numParam( 255 ) );
					} elseif ( preg_match( '/[^a-zA-Z0-9_-]/', $key ) !== 0 ) {
						$validation = $this->msg( 'apiwarn-validationfailed-badchars' );
					} else {
						$validation = true;
					}

					LoggerFactory::getInstance( 'api-warning' )->info(
						'ApiOptions: Setting userjs option',
						[
							'phab' => 'T259073',
							'OptionName' => substr( $key, 0, 255 ),
							'OptionValue' => substr( $value ?? '', 0, 255 ),
							'OptionSize' => strlen( $value ?? '' ),
							'OptionValidation' => $validation,
							'UserId' => $user->getId(),
							'RequestIP' => $this->getRequest()->getIP(),
							'RequestUA' => $this->getRequest()->getHeader( 'User-Agent' )
						]
					);
					break;
				case 'special':
					$validation = $this->msg( 'apiwarn-validationfailed-cannotset' );
					break;
				case 'unused':
				default:
					$validation = $this->msg( 'apiwarn-validationfailed-badpref' );
					break;
			}
			if ( $validation === true && is_string( $value ) &&
				strlen( $value ) > UserOptionsManager::MAX_BYTES_OPTION_VALUE
			) {
				$validation = $this->msg(
					'apiwarn-validationfailed-valuetoolong',
					Message::numParam( UserOptionsManager::MAX_BYTES_OPTION_VALUE )
				);
			}
			if ( $validation === true ) {
				$this->setPreference( $key, $value );
				$changed = true;
			} else {
				$this->addWarning( [ 'apiwarn-validationfailed', wfEscapeWikiText( $key ), $validation ] );
			}
		}

		if ( $changed ) {
			$this->commitChanges();
		}

		$this->getResult()->addValue( null, $this->getModuleName(), 'success' );
	}

	/**
	 * Load the user from the primary to reduce CAS errors on double post (T95839)
	 *
	 * @return User|null
	 */
	protected function getUserForUpdates() {
		if ( !$this->userForUpdates ) {
			$this->userForUpdates = $this->getUser()->getInstanceForUpdate();
		}

		return $this->userForUpdates;
	}

	/**
	 * Returns preferences form descriptor
	 * @return mixed[][]
	 */
	protected function getPreferences() {
		return $this->preferencesFactory->getFormDescriptor( $this->getUserForUpdates(),
			$this->getContext() );
	}

	/**
	 * @param string[] $kinds One or more types returned by UserOptionsManager::listOptionKinds() or 'all'
	 */
	protected function resetPreferences( array $kinds ) {
		$optionNames = $this->preferencesFactory->getOptionNamesForReset(
			$this->getUserForUpdates(), $this->getContext(), $kinds );
		$this->userOptionsManager->resetOptionsByName( $this->getUserForUpdates(), $optionNames );
	}

	/**
	 * Sets one user preference to be applied by commitChanges()
	 *
	 * @param string $preference
	 * @param mixed $value
	 */
	protected function setPreference( $preference, $value ) {
		$this->userOptionsManager->setOption( $this->getUserForUpdates(), $preference, $value );
	}

	/**
	 * Applies changes to user preferences
	 */
	protected function commitChanges() {
		$this->getUserForUpdates()->saveSettings();
	}

	public function mustBePosted() {
		return true;
	}

	public function isWriteMode() {
		return true;
	}

	public function getAllowedParams() {
		$optionKinds = $this->preferencesFactory->listResetKinds();
		$optionKinds[] = 'all';

		return [
			'reset' => false,
			'resetkinds' => [
				ParamValidator::PARAM_TYPE => $optionKinds,
				ParamValidator::PARAM_DEFAULT => 'all',
				ParamValidator::PARAM_ISMULTI => true
			],
			'change' => [
				ParamValidator::PARAM_ISMULTI => true,
			],
			'optionname' => [
				ParamValidator::PARAM_TYPE => 'string',
			],
			'optionvalue' => [
				ParamValidator::PARAM_TYPE => 'string',
			],
		];
	}

	public function needsToken() {
		return 'csrf';
	}

	public function getHelpUrls() {
		return 'https://www.mediawiki.org/wiki/Special:MyLanguage/API:Options';
	}

	protected function getExamplesMessages() {
		return [
			'action=options&reset=&token=123ABC'
				=> 'apihelp-options-example-reset',
			'action=options&change=skin=vector|hideminor=1&token=123ABC'
				=> 'apihelp-options-example-change',
			'action=options&reset=&change=skin=monobook&optionname=nickname&' .
				'optionvalue=[[User:Beau|Beau]]%20([[User_talk:Beau|talk]])&token=123ABC'
				=> 'apihelp-options-example-complex',
		];
	}
}
