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

namespace MediaWiki\Skins\Vector\FeatureManagement\Requirements;

use Config;
use MediaWiki\Skins\Vector\FeatureManagement\Requirement;
use User;
use WebRequest;

/**
 * The `OverridableConfigRequirement` allows us to define requirements that can override
 * configuration like querystring parameters, e.g.
 *
 * ```lang=php
 * $featureManager->registerRequirement(
 *   new OverridableConfigRequirement(
 *     $config,
 *     $user,
 *     $request,
 *     MainConfigNames::Sitename,
 *     'requirementName',
 *     'overrideName',
 *     'configTestName',
 *   )
 * );
 * ```
 *
 * registers a requirement that will evaluate to true only when `mediawiki/includes/Setup.php` has
 * finished executing (after all service wiring has executed). I.e., every call to
 * `Requirement->isMet()` re-interrogates the request, user authentication status,
 * and config object for the current state and returns it. Contrast to:
 *
 * ```lang=php
 * $featureManager->registerSimpleRequirement(
 *   'requirementName',
 *   (bool)$config->get( MainConfigNames::Sitename )
 * );
 * ```
 *
 * wherein state is evaluated only once at registration time and permanently cached.
 *
 * NOTE: This API hasn't settled. It may change at any time without warning. Please don't bind to
 * it unless you absolutely need to
 *
 * @package MediaWiki\Skins\Vector\FeatureManagement\Requirements
 */
final class OverridableConfigRequirement implements Requirement {

	/**
	 * @var Config
	 */
	private $config;

	/**
	 * @var User
	 */
	private $user;

	/**
	 * @var WebRequest
	 */
	private $request;

	/**
	 * @var string
	 */
	private $configName;

	/**
	 * @var string
	 */
	private $requirementName;

	/**
	 * @var string
	 */
	private $overrideName;

	/**
	 * This constructor accepts all dependencies needed to determine whether
	 * the overridable config is enabled for the current user and request.
	 *
	 * @param Config $config
	 * @param User $user
	 * @param WebRequest $request
	 * @param string $configName Any `Config` key. This name is used to query `$config` state.
	 * @param string $requirementName The name of the requirement presented to FeatureManager.
	 */
	public function __construct(
		Config $config,
		User $user,
		WebRequest $request,
		string $configName,
		string $requirementName
	) {
		$this->config = $config;
		$this->user = $user;
		$this->request = $request;
		$this->configName = $configName;
		$this->requirementName = $requirementName;
		$this->overrideName = strtolower( $configName );
	}

	/**
	 * @inheritDoc
	 */
	public function getName(): string {
		return $this->requirementName;
	}

	/**
	 * Check query parameter to override config or not.
	 * Then check for AB test value.
	 * Fallback to config value.
	 *
	 * @inheritDoc
	 */
	public function isMet(): bool {
		// Check query parameter.
		if ( $this->request->getCheck( $this->overrideName ) ) {
			return $this->request->getBool( $this->overrideName );
		}
		if ( $this->request->getCheck( $this->configName ) ) {
			return $this->request->getBool( $this->configName );
		}

		// If AB test is not enabled, fallback to checking config state.
		$thisConfig = $this->config->get( $this->configName );

		// Backwards compatibility with config variables that have been set in production.
		if ( is_bool( $thisConfig ) ) {
			$thisConfig = [
				'logged_in' => $thisConfig,
				'logged_out' => $thisConfig,
			];
		} elseif ( array_key_exists( 'default', $thisConfig ) ) {
			$thisConfig = [
				'default' => $thisConfig['default'],
			];
		} else {
			$thisConfig = [
				'logged_in' => $thisConfig['logged_in'] ?? false,
				'logged_out' => $thisConfig['logged_out'] ?? false,
			];
		}

		// Fallback to config.
		return array_key_exists( 'default', $thisConfig ) ?
			$thisConfig[ 'default' ] :
			$thisConfig[ $this->user->isRegistered() ? 'logged_in' : 'logged_out' ];
	}
}
