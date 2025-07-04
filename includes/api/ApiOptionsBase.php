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

namespace MediaWiki\Api;

use MediaWiki\HTMLForm\HTMLForm;
use MediaWiki\Logger\LoggerFactory;
use MediaWiki\Message\Message;
use MediaWiki\Preferences\DefaultPreferencesFactory;
use MediaWiki\Preferences\PreferencesFactory;
use MediaWiki\User\Options\UserOptionsManager;
use MediaWiki\User\User;
use Wikimedia\ParamValidator\ParamValidator;

/**
 * The base class for core's ApiOptions and two modules in the GlobalPreferences
 * extension.
 *
 * @ingroup API
 */
abstract class ApiOptionsBase extends ApiBase {
	/** @var User User account to modify */
	private $userForUpdates;

	private UserOptionsManager $userOptionsManager;
	private PreferencesFactory $preferencesFactory;

	/** @var mixed[][]|null */
	private $preferences;

	/** @var HTMLForm|null */
	private $htmlForm;

	/** @var string[]|null */
	private $prefsKinds;

	public function __construct(
		ApiMain $main,
		string $action,
		UserOptionsManager $userOptionsManager,
		PreferencesFactory $preferencesFactory
	) {
		parent::__construct( $main, $action );
		$this->userOptionsManager = $userOptionsManager;
		$this->preferencesFactory = $preferencesFactory;
	}

	/**
	 * Changes preferences of the current user.
	 */
	public function execute() {
		$user = $this->getUserForUpdatesOrNull();
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

		$this->runHook( $user, $changes, $resetKinds );

		if ( $resetKinds ) {
			$this->resetPreferences( $resetKinds );
			$changed = true;
		}

		if ( !$changed && !count( $changes ) ) {
			$this->dieWithError( 'apierror-nochanges' );
		}

		$this->prefsKinds = $this->preferencesFactory->getResetKinds( $user, $this->getContext(), $changes );

		foreach ( $changes as $key => $value ) {
			if ( $this->shouldIgnoreKey( $key ) ) {
				continue;
			}
			$validation = $this->validate( $key, $value );
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
	 * Run the ApiOptions hook if applicable
	 *
	 * @param User $user
	 * @param string[] $changes
	 * @param string[] $resetKinds
	 */
	protected function runHook( $user, $changes, $resetKinds ) {
	}

	/**
	 * Check whether a key should be ignored.
	 *
	 * This may be overridden to emit a warning as well as returning true.
	 *
	 * @param string $key
	 * @return bool
	 */
	protected function shouldIgnoreKey( $key ) {
		return false;
	}

	/**
	 * Get the preference kinds for the current user's options.
	 * This can only be called after $this->prefsKinds is set in execute()
	 *
	 * @return string[]
	 */
	protected function getPrefsKinds(): array {
		return $this->prefsKinds;
	}

	/**
	 * Get the HTMLForm for the user's preferences
	 *
	 * @return HTMLForm
	 */
	protected function getHtmlForm() {
		if ( !$this->htmlForm ) {
			$this->htmlForm = new HTMLForm(
				DefaultPreferencesFactory::simplifyFormDescriptor( $this->getPreferences() ),
				$this
			);
		}
		return $this->htmlForm;
	}

	/**
	 * Validate a proposed change
	 *
	 * @param string $key
	 * @param mixed &$value
	 * @return bool|\MediaWiki\Message\Message|string
	 */
	protected function validate( $key, &$value ) {
		switch ( $this->getPrefsKinds()[$key] ) {
			case 'registered':
				// Regular option.
				if ( $value === null ) {
					// Reset it
					$validation = true;
				} else {
					// Validate
					$field = $this->getHtmlForm()->getField( $key );
					$validation = $field->validate(
						$value,
						$this->userOptionsManager->getOptions( $this->getUserForUpdates() )
					);
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
						'UserId' => $this->getUserForUpdates()->getId(),
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
		return $validation;
	}

	/**
	 * Load the user from the primary to reduce CAS errors on double post (T95839)
	 * Will throw if the user is anonymous.
	 */
	protected function getUserForUpdates(): User {
		// @phan-suppress-next-line PhanTypeMismatchReturnNullable
		return $this->getUserForUpdatesOrNull();
	}

	/**
	 * Get the user for updates, or null if the user is anonymous
	 *
	 * @return User|null
	 */
	protected function getUserForUpdatesOrNull(): ?User {
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
		if ( !$this->preferences ) {
			$this->preferences = $this->preferencesFactory->getFormDescriptor(
				$this->getUserForUpdates(),
				$this->getContext()
			);
		}
		return $this->preferences;
	}

	protected function getUserOptionsManager(): UserOptionsManager {
		return $this->userOptionsManager;
	}

	protected function getPreferencesFactory(): PreferencesFactory {
		return $this->preferencesFactory;
	}

	/**
	 * Reset preferences of the specified kinds
	 *
	 * @param string[] $kinds One or more types returned by PreferencesFactory::listResetKinds() or 'all'
	 */
	abstract protected function resetPreferences( array $kinds );

	/**
	 * Sets one user preference to be applied by commitChanges()
	 *
	 * @param string $preference
	 * @param mixed $value
	 */
	abstract protected function setPreference( $preference, $value );

	/**
	 * Applies changes to user preferences
	 */
	abstract protected function commitChanges();

	/** @inheritDoc */
	public function mustBePosted() {
		return true;
	}

	/** @inheritDoc */
	public function isWriteMode() {
		return true;
	}

	/** @inheritDoc */
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

	/** @inheritDoc */
	public function needsToken() {
		return 'csrf';
	}
}

/** @deprecated class alias since 1.43 */
class_alias( ApiOptionsBase::class, 'ApiOptionsBase' );
