<?php

namespace MediaWiki\Rest\Handler;

use MediaWiki\Config\Config;
use MediaWiki\Config\ServiceOptions;
use MediaWiki\MainConfigNames;
use MediaWiki\Rest\Handler;
use MediaWiki\Rest\Module\Module;
use MediaWiki\Rest\Module\ModuleMode;

/**
 * Core REST API endpoint that outputs discovery information, including a
 * list of registered modules.
 * Inspired by Google's API directory, see https://developers.google.com/discovery/v1/reference.
 */
class DiscoveryHandler extends Handler {
	/**
	 * @internal
	 */
	private const CONSTRUCTOR_OPTIONS = [
		MainConfigNames::RightsUrl,
		MainConfigNames::RightsText,
		MainConfigNames::EmergencyContact,
		MainConfigNames::RestTermsOfServiceUrl,
		MainConfigNames::Sitename,
		MainConfigNames::Server,
		MainConfigNames::CanonicalServer,
		MainConfigNames::RestExternalModules,
	];

	private ServiceOptions $options;

	public function __construct( Config $config ) {
		$options = new ServiceOptions( self::CONSTRUCTOR_OPTIONS, $config );
		$options->assertRequiredOptions( self::CONSTRUCTOR_OPTIONS );
		$this->options = $options;
	}

	/** @inheritDoc */
	public function execute() {
		// NOTE: must match docs/rest/discovery-1.0.json
		return [
			'mw-discovery' => '1.0',
			'$schema' => 'https://www.mediawiki.org/schema/discovery-1.0',
			'info' => $this->getInfoSpec(),
			'servers' => $this->getServerList(),
			'modules' => $this->getModuleMap(),
			// TODO: link to aggregated spec
			// TODO: list of component schemas
		];
	}

	private function getModuleMap(): array {
		$modules = [];

		$router = $this->getRouter();
		$moduleManager = $router->getModuleManager();
		foreach ( $router->getModuleIds() as $moduleId ) {
			$mode = $moduleManager->getModuleMode( $moduleId );
			// Any module that is not HIDDEN or DISABLED should be listed in /discovery.
			// DISABLED modules won't be in this list, so we don't need to explicitly exclude them.
			if ( $mode !== ModuleMode::HIDDEN ) {
				$module = $router->getModule( $moduleId );
				if ( $module ) {
					$modules[$moduleId] = $this->getModuleSpec( $module );
				}
			}
		}

		$restExternalModules = $this->options->get( MainConfigNames::RestExternalModules );
		$localizer = $this->getJsonLocalizer();
		foreach ( $restExternalModules as $externalModuleId => $em ) {
			$mode = $moduleManager->getModuleMode( $externalModuleId );
			if ( $mode === ModuleMode::DISABLED || $mode === ModuleMode::HIDDEN ) {
				continue;
			}
			$modules[$externalModuleId] = [ 'moduleId' => $externalModuleId ] + $localizer->localizeJson( $em );
		}

		// This will put the "routes not in modules" entry first.
		uksort( $modules, 'strnatcasecmp' );

		return $modules;
	}

	private function getServerList(): array {
		// See https://github.com/OAI/OpenAPI-Specification/blob/main/versions/3.0.3.md#server-object
		return [
			[
				'url' => $this->getRouter()->getRouteUrl( '' ),
			]
		];
	}

	private function getInfoSpec(): array {
		$info = [
			'title' => $this->options->get( MainConfigNames::Sitename ),
			'mediawiki' => MW_VERSION,
			'license' => $this->getLicenseSpec(),
			'contact' => $this->getContactSpec(),
			// TODO: owner/operator
			// TODO: link to https://www.mediawiki.org/wiki/API:REST_API
		];

		$termsOfService = $this->options->get( MainConfigNames::RestTermsOfServiceUrl );
		if ( is_string( $termsOfService ) && $termsOfService !== '' ) {
			$info['termsOfService'] = $termsOfService;
		}

		return $info;
	}

	private function getLicenseSpec(): array {
		// See https://github.com/OAI/OpenAPI-Specification/blob/main/versions/3.0.3.md#license-object
		// TODO: get terms-of-use URL, not content license.
		return [
			'name' => $this->options->get( MainConfigNames::RightsText ),
			'url' => $this->options->get( MainConfigNames::RightsUrl ),
		];
	}

	private function getContactSpec(): array {
		// https://github.com/OAI/OpenAPI-Specification/blob/main/versions/3.0.3.md#contact-object
		$contact = [
			'name' => $this->options->get( MainConfigNames::Sitename ),
			'url' => $this->options->get( MainConfigNames::CanonicalServer ),
		];

		$email = $this->options->get( MainConfigNames::EmergencyContact );
		// OpenAPI requires contact.email to be a valid email address. Keep the rest
		// of the contact object intact and omit the field when the configured value
		// does not satisfy that format.
		if ( is_string( $email ) && filter_var( $email, FILTER_VALIDATE_EMAIL ) !== false ) {
			$contact['email'] = $email;
		}

		return $contact;
	}

	private function getModuleSpec( Module $module ): array {
		return $module->getModuleDescription();
	}

	protected function getResponseBodySchemaFileName( string $method ): ?string {
		return MW_INSTALL_PATH . '/docs/rest/discovery-1.0.json';
	}
}
