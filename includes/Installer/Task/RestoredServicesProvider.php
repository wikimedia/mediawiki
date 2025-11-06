<?php

namespace MediaWiki\Installer\Task;

use MediaWiki\MainConfigNames;
use MediaWiki\MediaWikiServices;
use MediaWiki\Status\Status;
use MediaWiki\Utils\UrlUtils;
use MWLBFactory;
use Wikimedia\Rdbms\LBFactorySingle;
use Wikimedia\Services\ServiceContainer;

/**
 * Provide a service container with storage enabled.
 *
 * @internal For use by the installer
 */
class RestoredServicesProvider extends Task {
	public function getName(): string {
		return 'restore-services';
	}

	/** @inheritDoc */
	public function getDependencies() {
		return [ 'tables', 'VirtualDomains' ];
	}

	/** @inheritDoc */
	public function getProvidedNames() {
		return 'services';
	}

	public function execute(): Status {
		// Apply wgServer, so it's available for database initialization hooks.
		$urlOptions = [
			UrlUtils::SERVER => $this->getConfigVar( MainConfigNames::Server ),
		];

		$connection = $this->definitelyGetConnection( ITaskContext::CONN_CREATE_TABLES );
		$virtualDomains = array_merge(
			$this->getVirtualDomains(),
			MWLBFactory::CORE_VIRTUAL_DOMAINS
		);

		$services = $this->resetMediaWikiServices( [
			'DBLoadBalancerFactory' => static function () use ( $virtualDomains, $connection ) {
				return LBFactorySingle::newFromConnection(
					$connection,
					[ 'virtualDomains' => $virtualDomains ]
				);
			},
			'UrlUtils' => static function ( MediaWikiServices $services ) use ( $urlOptions ) {
				return new UrlUtils( $urlOptions );
			},
			'UserOptionsLookup' => static function ( MediaWikiServices $services ) {
				return $services->get( 'UserOptionsManager' );
			},
		] );
		$this->getContext()->provide( 'services', $services );
		return Status::newGood();
	}

	private function resetMediaWikiServices( array $serviceOverrides ): ServiceContainer {
		// Reset all services and inject config overrides.
		MediaWikiServices::resetGlobalInstance( null, 'reload' );

		$services = MediaWikiServices::getInstance();

		foreach ( $serviceOverrides as $name => $callback ) {
			$services->redefineService( $name, $callback );
		}

		return $services;
	}
}
