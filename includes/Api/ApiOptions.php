<?php
/**
 * Copyright © 2012 Szymon Świerkosz beau@adres.pl
 *
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\Api;

use MediaWiki\MediaWikiServices;
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
class ApiOptions extends ApiOptionsBase {
	public function __construct(
		ApiMain $main,
		string $action,
		?UserOptionsManager $userOptionsManager = null,
		?PreferencesFactory $preferencesFactory = null
	) {
		if ( $userOptionsManager === null || $preferencesFactory === null ) {
			wfDeprecatedMsg(
				__METHOD__ . ': calling without $userOptionsManager and $preferencesFactory is deprecated',
				'1.45'
			);
			$services = MediaWikiServices::getInstance();
			$userOptionsManager ??= $services->getUserOptionsManager();
			$preferencesFactory ??= $services->getPreferencesFactory();
		}
		parent::__construct( $main, $action, $userOptionsManager, $preferencesFactory );
	}

	/**
	 * @param User $user
	 * @param array $changes
	 * @param string[] $resetKinds
	 */
	protected function runHook( $user, $changes, $resetKinds ) {
		$this->getHookRunner()->onApiOptions( $this, $user, $changes, $resetKinds );
	}

	/**
	 * @param string $key
	 * @return bool
	 */
	protected function shouldIgnoreKey( $key ) {
		$user = $this->getUserFromPrimary();
		$manager = $this->getUserOptionsManager();
		if ( $this->getGlobalParam() === 'ignore' && $manager->isOptionGlobal( $user, $key ) ) {
			$this->addWarning( $this->msg( 'apiwarn-global-option-ignored', $key ) );
			return true;
		}
		return false;
	}

	protected function resetPreferences( array $kinds ) {
		$optionNames = $this->getPreferencesFactory()->getOptionNamesForReset(
			$this->getUserFromPrimary(), $this->getContext(), $kinds );
		$this->getUserOptionsManager()->resetOptionsByName( $this->getUserFromPrimary(), $optionNames );
	}

	/**
	 * @param string $preference
	 * @param mixed $value
	 */
	protected function setPreference( $preference, $value ) {
		$globalUpdateType = match ( $this->getGlobalParam() ) {
			'ignore' => UserOptionsManager::GLOBAL_IGNORE,
			'update' => UserOptionsManager::GLOBAL_UPDATE,
			'override' => UserOptionsManager::GLOBAL_OVERRIDE,
			'create' => UserOptionsManager::GLOBAL_CREATE,
		};

		$this->getUserOptionsManager()->setOption(
			$this->getUserFromPrimary(),
			$preference,
			$value,
			$globalUpdateType
		);
	}

	private function getGlobalParam(): string {
		return $this->extractRequestParams()['global'];
	}

	protected function commitChanges() {
		$this->getUserOptionsManager()->saveOptions( $this->getUserFromPrimary() );
	}

	/** @codeCoverageIgnore Merely declarative */
	public function getHelpUrls(): string {
		return 'https://www.mediawiki.org/wiki/Special:MyLanguage/API:Options';
	}

	/** @codeCoverageIgnore Merely declarative */
	protected function getExamplesMessages(): array {
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

	/** @inheritDoc */
	public function getAllowedParams() {
		return parent::getAllowedParams() + [
			'global' => [
				ParamValidator::PARAM_TYPE => [ 'ignore', 'update', 'override', 'create' ],
				ParamValidator::PARAM_DEFAULT => 'ignore'
			]
		];
	}
}

/** @deprecated class alias since 1.43 */
class_alias( ApiOptions::class, 'ApiOptions' );
