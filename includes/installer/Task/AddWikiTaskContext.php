<?php

namespace MediaWiki\Installer\Task;

use MediaWiki\Config\Config;
use MediaWiki\Installer\ConnectionStatus;
use MediaWiki\MainConfigNames;
use Wikimedia\Rdbms\DatabaseDomain;
use Wikimedia\Rdbms\DBConnectionError;
use Wikimedia\Rdbms\IMaintainableDatabase;
use Wikimedia\Rdbms\LBFactory;

/**
 * A task context for use in installPreConfigured.php
 *
 * @internal
 */
class AddWikiTaskContext implements ITaskContext {
	/** @var Config */
	private $config;

	/** @var LBFactory */
	private $lbFactory;

	/** @var array */
	private $configOverrides = [];

	/** @var array */
	private $options = [];

	/** @var array */
	private $provisions = [];

	public function __construct( Config $config, LBFactory $lbFactory ) {
		$this->config = $config;
		$this->lbFactory = $lbFactory;
	}

	/** @inheritDoc */
	public function getConfigVar( string $name ) {
		return $this->configOverrides[$name] ?? $this->config->get( $name );
	}

	/** @inheritDoc */
	public function getOption( string $name ) {
		return $this->options[$name] ?? null;
	}

	/** @inheritDoc */
	public function getConnection( $type = self::CONN_DONT_KNOW ): ConnectionStatus {
		$localDomainID = $this->getDomain()->getId();
		$lb = $this->lbFactory->getLoadBalancer( $localDomainID );
		if ( $type === self::CONN_CREATE_DATABASE || $type === self::CONN_CREATE_SCHEMA ) {
			$connectDomain = '';
		} else {
			$connectDomain = $localDomainID;
		}

		$status = new ConnectionStatus;
		try {
			$conn = $lb->getConnection( DB_PRIMARY, [], $connectDomain );
			if ( $conn instanceof IMaintainableDatabase ) {
				$status->setDB( $conn );
			} else {
				throw new \RuntimeException( 'Invalid DB connection class' );
			}
			$conn->setSchemaVars( $this->getSchemaVars() );
		} catch ( DBConnectionError $e ) {
			$status->fatal( 'config-connection-error', $e->getMessage() );
		}

		return $status;
	}

	private function getDomain(): DatabaseDomain {
		return new DatabaseDomain(
			$this->getConfigVar( MainConfigNames::DBname ),
			$this->getConfigVar( MainConfigNames::DBmwschema ),
			$this->getConfigVar( MainConfigNames::DBprefix ) ?? ''
		);
	}

	/**
	 * Get schema variable replacements to be applied to SQL files
	 *
	 * @return array
	 */
	public function getSchemaVars() {
		$tableOptions = $this->getConfigVar( MainConfigNames::DBTableOptions );
		if ( str_contains( $tableOptions, 'TYPE=' ) ) {
			throw new \RuntimeException( '$wgDBTableOptions contains obsolete TYPE option, ' .
				'replace it with ENGINE' );
		}
		if ( str_contains( $tableOptions, 'CHARSET=mysql4' ) ) {
			throw new \RuntimeException( '$wgDBTableOptions contains invalid CHARSET option' );
		}
		return [ 'wgDBTableOptions' => $tableOptions ];
	}

	public function getDbType(): string {
		return $this->getConfigVar( MainConfigNames::DBtype );
	}

	/** @inheritDoc */
	public function provide( string $name, $value ) {
		$this->provisions[$name] = $value;
	}

	/** @inheritDoc */
	public function getProvision( string $name ) {
		if ( isset( $this->provisions[$name] ) ) {
			return $this->provisions[$name];
		} else {
			throw new \RuntimeException( "Can't find provided data \"$name\"" );
		}
	}

	/**
	 * Override a configuration variable
	 *
	 * @param string $name
	 * @param mixed $value
	 */
	public function setConfigVar( string $name, $value ) {
		$this->configOverrides[$name] = $value;
	}

	/**
	 * Set an installer option
	 *
	 * @param string $name
	 * @param mixed $value
	 */
	public function setOption( string $name, $value ) {
		$this->options[$name] = $value;
	}

}
