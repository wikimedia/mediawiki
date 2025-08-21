<?php

namespace MediaWiki\Installer\Task;

use ExternalStoreDB;
use MediaWiki\MediaWikiServices;
use MediaWiki\Status\Status;
use Wikimedia\Rdbms\DatabaseDomain;
use Wikimedia\Rdbms\ILoadBalancer;
use Wikimedia\Rdbms\LBFactory;

/**
 * Create databases referenced in virtual domain and external store config.
 * Initialise external store tables.
 */
class CreateExternalDomainsTask extends Task {
	/** @var LBFactory */
	private $lbFactory;

	/** @var \ExternalStoreFactory */
	private $esFactory;

	/** @inheritDoc */
	public function getName() {
		return 'external-domains';
	}

	/** @inheritDoc */
	public function getDependencies() {
		return [ 'VirtualDomains', 'services' ];
	}

	public function execute(): Status {
		$this->initServices( $this->getServices() );
		$status = $this->createVirtualDomains();
		$status->merge( $this->createExternalStoreDomains() );
		return $status;
	}

	private function initServices( MediaWikiServices $services ) {
		$this->lbFactory = $services->getDBLoadBalancerFactory();
		$this->esFactory = $services->getExternalStoreFactory();
	}

	private function createVirtualDomains(): Status {
		$status = Status::newGood();
		foreach ( $this->getVirtualDomains() as $virtualDomain ) {
			if ( !$this->shouldDoShared()
				&& $this->lbFactory->isSharedVirtualDomain( $virtualDomain )
			) {
				$status->warning( 'config-skip-shared-domain', $virtualDomain );
				// Skip update of shared virtual domain
				continue;
			}
			if ( $this->lbFactory->isLocalDomain( $virtualDomain ) ) {
				// No need to create the main wiki domain
				continue;
			}
			$lb = $this->lbFactory->getLoadBalancer( $virtualDomain );
			$realDomainId = $this->lbFactory->getMappedDomain( $virtualDomain );
			$status->merge( $this->maybeCreateDomain( $lb, $realDomainId ) );
		}

		return $status;
	}

	private function createExternalStoreDomains(): Status {
		$status = Status::newGood();
		$localDomainId = $this->lbFactory->getLocalDomainID();
		foreach ( $this->esFactory->getWriteBaseUrls() as $url ) {
			$store = $this->esFactory->getStoreForUrl( $url );
			if ( $store instanceof ExternalStoreDB ) {
				$cluster = $store->getClusterForUrl( $url );
				if ( $cluster === null ) {
					throw new \RuntimeException( "Invalid store url \"$url\"" );
				}
				$lb = $this->lbFactory->getExternalLB( $cluster );
				$domainId = $store->getDomainIdForCluster( $cluster );
				if ( $domainId !== false && $domainId !== $localDomainId && !$this->shouldDoShared() ) {
					// Skip potentially shared domain
					$status->warning( 'config-skip-shared-domain', "$cluster/$domainId" );
					continue;
				}
				$status->merge( $this->maybeCreateDomain( $lb, $domainId ) );

				$conn = $lb->getMaintenanceConnectionRef( DB_PRIMARY, [], $domainId );
				$conn->setSchemaVars( $this->getContext()->getSchemaVars() );
				if ( !$conn->tableExists( $store->getTable( $cluster ), __METHOD__ ) ) {
					$store->initializeTable( $cluster );
				}
			}
		}
		return $status;
	}

	/**
	 * Create a domain on a LoadBalancer, if it doesn't already exist.
	 * If the domain is false, meaning the local wiki, resolve this to a string
	 * since the main point of this is to create external databases for the
	 * local wiki.
	 *
	 * @param ILoadBalancer $lb
	 * @param string|false $domainId
	 * @return Status
	 */
	private function maybeCreateDomain( $lb, $domainId ) {
		$databaseCreator = $this->getDatabaseCreator();
		if ( $domainId === false ) {
			$domainId = $this->lbFactory->getLocalDomainID();
		}
		$database = DatabaseDomain::newFromId( $domainId )->getDatabase();
		if ( !$databaseCreator->existsInLoadBalancer( $lb, $database ) ) {
			return $databaseCreator->createInLoadBalancer( $lb, $database );
		}
		return Status::newGood();
	}

	/**
	 * Whether to do statically configured domain IDs, which may be shared with other wikis.
	 *
	 * @return bool
	 */
	private function shouldDoShared() {
		return (bool)$this->getOption( 'Shared' );
	}
}
