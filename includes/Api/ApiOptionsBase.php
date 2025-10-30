<?php
/**
 * Copyright © 2012 Szymon Świerkosz beau@adres.pl
 *
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\Api;

use MediaWiki\HTMLForm\HTMLForm;
use MediaWiki\Logger\LoggerFactory;
use MediaWiki\MainConfigNames;
use MediaWiki\Message\Message;
use MediaWiki\Preferences\DefaultPreferencesFactory;
use MediaWiki\Preferences\PreferencesFactory;
use MediaWiki\User\Options\UserOptionsLookup;
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
	/** @var User|null User account to modify */
	private ?User $userFromPrimary = null;

	private UserOptionsManager $userOptionsManager;
	private PreferencesFactory $preferencesFactory;

	/** @var mixed[][]|null */
	private $preferences;

	/** @var HTMLForm|null */
	private $htmlForm;

	/** @var string[]|null */
	private $prefsKinds;
	private int $userJsLimit;

	public function __construct(
		ApiMain $main,
		string $action,
		UserOptionsManager $userOptionsManager,
		PreferencesFactory $preferencesFactory
	) {
		parent::__construct( $main, $action );
		$this->userOptionsManager = $userOptionsManager;
		$this->preferencesFactory = $preferencesFactory;
		$this->userJsLimit = $this->getConfig()->get( MainConfigNames::UserJsPrefLimit );
	}

	/**
	 * Changes preferences of the current user.
	 */
	public function execute() {
		$user = $this->getUserFromPrimaryOrNull();
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
						$this->userOptionsManager->getOptions( $this->getUserFromPrimary() )
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
				} elseif ( $this->countUserJsOptions() >= $this->userJsLimit ) {
					$validation = $this->msg(
						'apiwarn-validationfailed-toomanyuserjs',
						Message::numParam( $this->userJsLimit )
					);
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
						'UserId' => $this->getUserFromPrimary()->getId(),
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

	private function countUserJsOptions(): int {
		$options = $this->userOptionsManager->getOptions(
			$this->getUserFromPrimary(),
			UserOptionsLookup::EXCLUDE_DEFAULTS
		);
		$userJsCount = 0;
		foreach ( $options as $prefName => $value ) {
			if ( str_starts_with( $prefName, 'userjs-' ) ) {
				$userJsCount += 1;
			}
		}
		return $userJsCount;
	}

	/**
	 * Load the user from the primary to reduce CAS errors on double post (T95839)
	 * Will throw if the user is anonymous.
	 */
	protected function getUserFromPrimary(): User {
		// @phan-suppress-next-line PhanTypeMismatchReturnNullable
		return $this->getUserFromPrimaryOrNull();
	}

	/**
	 * Get the user from the primary, or null if the user is anonymous
	 */
	protected function getUserFromPrimaryOrNull(): ?User {
		if ( !$this->userFromPrimary ) {
			$this->userFromPrimary = $this->getUser()->getInstanceFromPrimary();
		}

		return $this->userFromPrimary;
	}

	/**
	 * Returns preferences form descriptor
	 * @return mixed[][]
	 */
	protected function getPreferences() {
		if ( !$this->preferences ) {
			$this->preferences = $this->preferencesFactory->getFormDescriptor(
				$this->getUserFromPrimary(),
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
