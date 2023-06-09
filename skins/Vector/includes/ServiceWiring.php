<?php

/**
 * Service Wirings for Vector skin
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
 * @since 1.35
 */

use MediaWiki\MediaWikiServices;
use MediaWiki\Skins\Vector\Constants;
use MediaWiki\Skins\Vector\FeatureManagement\FeatureManager;
use MediaWiki\Skins\Vector\FeatureManagement\Requirements\ABRequirement;
use MediaWiki\Skins\Vector\FeatureManagement\Requirements\DynamicConfigRequirement;
use MediaWiki\Skins\Vector\FeatureManagement\Requirements\LimitedWidthContentRequirement;
use MediaWiki\Skins\Vector\FeatureManagement\Requirements\LoggedInRequirement;
use MediaWiki\Skins\Vector\FeatureManagement\Requirements\OverridableConfigRequirement;
use MediaWiki\Skins\Vector\FeatureManagement\Requirements\UserPreferenceRequirement;

// PHP unit does not understand code coverage for this file
// as the @covers annotation cannot cover a specific file
// This is partly tested in ServiceWiringTest.php
// @codeCoverageIgnoreStart

return [
	Constants::SERVICE_FEATURE_MANAGER => static function ( MediaWikiServices $services ) {
		$featureManager = new FeatureManager();

		$featureManager->registerRequirement(
			new DynamicConfigRequirement(
				$services->getMainConfig(),
				Constants::CONFIG_KEY_FULLY_INITIALISED,
				Constants::REQUIREMENT_FULLY_INITIALISED
			)
		);

		$context = RequestContext::getMain();

		// Feature: Languages in sidebar
		// ================================
		$featureManager->registerRequirement(
			new OverridableConfigRequirement(
				$services->getMainConfig(),
				$context->getUser(),
				$context->getRequest(),
				Constants::CONFIG_KEY_LANGUAGE_IN_HEADER,
				Constants::REQUIREMENT_LANGUAGE_IN_HEADER
			)
		);

		// ---

		// Temporary T286932 - remove after languages A/B test is finished.
		$requirementName = 'T286932';

		// MultiConfig checks each config in turn, allowing us to override the main config for specific keys.
		$config = new MultiConfig( [
			new HashConfig( [
				Constants::REQUIREMENT_ZEBRA_AB_TEST => true,
			] ),
			$services->getMainConfig(),
		] );

		$featureManager->registerRequirement(
			new ABRequirement(
				$services->getMainConfig(),
				$context->getUser(),
				'skin-vector-zebra-experiment',
				Constants::REQUIREMENT_ZEBRA_AB_TEST
			)
		);

		$featureManager->registerRequirement(
			new OverridableConfigRequirement(
				$config,
				$context->getUser(),
				$context->getRequest(),
				Constants::CONFIG_KEY_LANGUAGE_IN_HEADER,
				$requirementName
			)
		);

		// ---

		$featureManager->registerFeature(
			Constants::FEATURE_LANGUAGE_IN_HEADER,
			[
				Constants::REQUIREMENT_FULLY_INITIALISED,
				Constants::REQUIREMENT_LANGUAGE_IN_HEADER,
			]
		);

		// Feature: T293470: Language in main page header
		// ================================
		$featureManager->registerRequirement(
			new OverridableConfigRequirement(
				$services->getMainConfig(),
				$context->getUser(),
				$context->getRequest(),
				Constants::CONFIG_LANGUAGE_IN_MAIN_PAGE_HEADER,
				Constants::REQUIREMENT_LANGUAGE_IN_MAIN_PAGE_HEADER
			)
		);

		$featureManager->registerSimpleRequirement(
			Constants::REQUIREMENT_IS_MAIN_PAGE,
			$context->getTitle() ? $context->getTitle()->isMainPage() : false
		);

		$featureManager->registerFeature(
			Constants::FEATURE_LANGUAGE_IN_MAIN_PAGE_HEADER,
			[
				Constants::REQUIREMENT_FULLY_INITIALISED,
				Constants::REQUIREMENT_IS_MAIN_PAGE,
				Constants::REQUIREMENT_LANGUAGE_IN_HEADER,
				Constants::REQUIREMENT_LANGUAGE_IN_MAIN_PAGE_HEADER
			]
		);

		// Feature: Sticky header
		// ================================
		$featureManager->registerRequirement(
			new OverridableConfigRequirement(
				$services->getMainConfig(),
				$context->getUser(),
				$context->getRequest(),
				Constants::CONFIG_STICKY_HEADER,
				Constants::REQUIREMENT_STICKY_HEADER
			)
		);

		$featureManager->registerFeature(
			Constants::FEATURE_STICKY_HEADER,
			[
				Constants::REQUIREMENT_FULLY_INITIALISED,
				Constants::REQUIREMENT_STICKY_HEADER
			]
		);

		// Feature: Page tools pinned
		// ================================
		$featureManager->registerRequirement(
			new LoggedInRequirement(
				$context->getUser(),
				Constants::REQUIREMENT_LOGGED_IN
			)
		);

		$featureManager->registerRequirement(
			new UserPreferenceRequirement(
				$context->getUser(),
				$services->getUserOptionsLookup(),
				Constants::PREF_KEY_PAGE_TOOLS_PINNED,
				Constants::REQUIREMENT_PAGE_TOOLS_PINNED,
				$context->getTitle()
			)
		);

		$featureManager->registerFeature(
			Constants::FEATURE_PAGE_TOOLS_PINNED,
			[
				Constants::REQUIREMENT_FULLY_INITIALISED,
				Constants::REQUIREMENT_LOGGED_IN,
				Constants::REQUIREMENT_PAGE_TOOLS_PINNED
			]
		);

		// Feature: Table of Contents pinned
		// ================================
		$featureManager->registerRequirement(
			new UserPreferenceRequirement(
				$context->getUser(),
				$services->getUserOptionsLookup(),
				Constants::PREF_KEY_TOC_PINNED,
				Constants::REQUIREMENT_TOC_PINNED,
				$context->getTitle()
			)
		);

		$featureManager->registerFeature(
			Constants::FEATURE_TOC_PINNED,
			[
				Constants::REQUIREMENT_FULLY_INITIALISED,
				Constants::REQUIREMENT_TOC_PINNED
			]
		);

		// Feature: Main menu pinned
		// ================================
		$featureManager->registerRequirement(
			new UserPreferenceRequirement(
				$context->getUser(),
				$services->getUserOptionsLookup(),
				Constants::PREF_KEY_MAIN_MENU_PINNED,
				Constants::REQUIREMENT_MAIN_MENU_PINNED,
				$context->getTitle()
			)
		);

		$featureManager->registerFeature(
			Constants::FEATURE_MAIN_MENU_PINNED,
			[
				Constants::REQUIREMENT_FULLY_INITIALISED,
				Constants::REQUIREMENT_LOGGED_IN,
				Constants::REQUIREMENT_MAIN_MENU_PINNED
			]
		);

		// Feature: Max Width (skin)
		// ================================
		$featureManager->registerRequirement(
			new UserPreferenceRequirement(
				$context->getUser(),
				$services->getUserOptionsLookup(),
				Constants::PREF_KEY_LIMITED_WIDTH,
				Constants::REQUIREMENT_LIMITED_WIDTH,
				$context->getTitle()
			)
		);
		$featureManager->registerFeature(
			Constants::FEATURE_LIMITED_WIDTH,
			[
				Constants::REQUIREMENT_FULLY_INITIALISED,
				Constants::REQUIREMENT_LIMITED_WIDTH
			]
		);

		// Feature: Max Width (content)
		// ================================
		$featureManager->registerRequirement(
			new LimitedWidthContentRequirement(
				$services->getMainConfig(),
				$context->getRequest(),
				$context->getTitle()
			)
		);
		$featureManager->registerFeature(
			Constants::FEATURE_LIMITED_WIDTH_CONTENT,
			[
				Constants::REQUIREMENT_FULLY_INITIALISED,
				Constants::REQUIREMENT_LIMITED_WIDTH_CONTENT,
			]
		);

		// Feature: T332448: feature Zebra#9 design update
		// ================================
		$featureManager->registerRequirement(
			new OverridableConfigRequirement(
				$services->getMainConfig(),
				$context->getUser(),
				$context->getRequest(),
				Constants::CONFIG_ZEBRA_DESIGN,
				Constants::REQUIREMENT_ZEBRA_DESIGN
			)
		);
		$featureManager->registerFeature(
			Constants::FEATURE_ZEBRA_DESIGN,
			[
				Constants::REQUIREMENT_FULLY_INITIALISED,
				Constants::REQUIREMENT_ZEBRA_DESIGN,
				Constants::REQUIREMENT_ZEBRA_AB_TEST
			]
		);

		return $featureManager;
	}
];

// @codeCoverageIgnoreEnd
