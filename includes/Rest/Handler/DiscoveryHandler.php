<?php

namespace MediaWiki\Rest\Handler;

use MediaWiki\Config\Config;
use MediaWiki\Config\ServiceOptions;
use MediaWiki\MainConfigNames;
use MediaWiki\Rest\Handler;
use MediaWiki\Rest\Module\ModuleInfo;
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
	];

	private readonly ServiceOptions $options;

	public function __construct( Config $config ) {
		$options = new ServiceOptions( self::CONSTRUCTOR_OPTIONS, $config );
		$options->assertRequiredOptions( self::CONSTRUCTOR_OPTIONS );
		$this->options = $options;
	}

	/** @inheritDoc */
	public function execute() {
		// NOTE: Must match docs/rest/discovery-1.1.json
		return [
			'mw-discovery' => '1.1',
			'$schema' => 'https://www.mediawiki.org/schema/discovery-1.1',
			'info' => $this->getInfoSpec(),
			'servers' => $this->getServerList(),
			'modules' => $this->getModuleMap(),
			// TODO: link to aggregated spec
			// TODO: list of component schemas
		];
	}

	private function getModuleMap(): object {
		$modules = [];

		$router = $this->getRouter();
		$moduleInfos = $router->getModuleManager()->getModuleInfos();
		foreach ( $moduleInfos as $moduleId => $moduleInfo ) {
			$availability = $moduleInfo->getAvailability();
			// `getModuleInfos()` includes all registered modules.
			// Exclude `HIDDEN` and `DISABLED` modules from `/discovery`.
			if ( $availability === ModuleMode::DISABLED || $availability === ModuleMode::HIDDEN ) {
				continue;
			}

			$modules[$moduleId] = $this->getModuleSpec( $moduleInfo );
		}

		return (object)$modules;
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

	private function getModuleSpec( ModuleInfo $moduleInfo ): array {
		$moduleId = $moduleInfo->getId();
		$infoSpec = [
			'title' => $moduleInfo->getTitle() ?? $moduleId,
			'groups' => $moduleInfo->getGroups(),
		];
		if ( $moduleInfo->getVersion() !== null ) {
			$infoSpec['version'] = $moduleInfo->getVersion();
		}
		if ( $moduleInfo->getDescription() !== null ) {
			$infoSpec['description'] = $moduleInfo->getDescription();
		}

		$router = $this->getRouter();
		return [
			'moduleId' => $moduleId,
			'info' => $infoSpec,
			'base' => $router->getModuleBaseUrl( $moduleId ) ?? '',
			'spec' => $router->getModuleSpecUrl( $moduleId ) ?? '',
		];
	}

	protected function getResponseBodySchemaFileName( string $method ): ?string {
		return MW_INSTALL_PATH . '/docs/rest/discovery-1.1.json';
	}

	/** @inheritDoc */
	public function needsWriteAccess() {
		return false;
	}
}
