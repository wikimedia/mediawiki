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
 * @since 1.35
 */

namespace MediaWiki\Skins\Vector\FeatureManagement\Requirements;

use MediaWiki\Skins\Vector\FeatureManagement\Requirement;

/**
 * Some application state changes throughout the lifetime of the application, e.g. `wgSitename` or
 * `wgFullyInitialised`, which signals whether the application boot process has finished and
 * critical resources like database connections are available.
 *
 * The `DynamicStateRequirement` allows us to define requirements that lazily evaluate the
 * application state statically, e.g.
 *
 * ```lang=php
 * $featureManager->registerRequirement(
 *   new DynamicConfigRequirement(
 *     $config,
 *     MainConfigNames::Sitename,
 *     'requirementName'
 *   )
 * );
 * ```
 *
 * registers a requirement that will evaluate to true only when `mediawiki/includes/Setup.php` has
 * finished executing (after all service wiring has executed). I.e., every call to
 * `Requirement->isMet()` reinterrogates the Config object for the current state and returns it.
 * Contrast to
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
 * @unstable
 *
 * @package MediaWiki\Skins\Vector\FeatureManagement\Requirements
 * @internal
 */
final class DynamicConfigRequirement implements Requirement {

	/**
	 * @var \Config
	 */
	private $config;

	/**
	 * @var string
	 */
	private $configName;

	/**
	 * @var string
	 */
	private $requirementName;

	/**
	 * @param \Config $config
	 * @param string $configName Any `Config` key. This name is used to query `$config` state. E.g.,
	 *   `'DBname'`. See https://www.mediawiki.org/wiki/Manual:Configuration_settings
	 * @param string $requirementName The name of the requirement presented to FeatureManager.
	 *   This name _usually_ matches the `$configName` parameter for simplicity but allows for
	 *   abstraction as needed. See `Requirement->getName()`.
	 */
	public function __construct( \Config $config, string $configName, string $requirementName ) {
		$this->config = $config;
		$this->configName = $configName;
		$this->requirementName = $requirementName;
	}

	/**
	 * @inheritDoc
	 */
	public function getName(): string {
		return $this->requirementName;
	}

	/**
	 * @inheritDoc
	 */
	public function isMet(): bool {
		return (bool)$this->config->get( $this->configName );
	}
}
