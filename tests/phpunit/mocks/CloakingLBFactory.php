<?php

use Wikimedia\Rdbms\DatabaseDomain;
use Wikimedia\Rdbms\DBAccessError;
use Wikimedia\Rdbms\LBFactorySimple;
use Wikimedia\Rdbms\LoadBalancer;

/**
 * A cloaking LBFactory.
 *
 * This class allows database connections to be "cloaked" by applying a table prefix.
 * It also prevents access to any additionally configured databases, to avoid test
 * runs interfering with live data.
 *
 * @see CloakingLoadBalancer
 *
 * @since 1.27
 */
class CloakingLBFactory extends LBFactorySimple {

	/**
	 * @var array|null
	 */
	private $cloakParams = null;

	public function __construct( array $conf ) {
		if ( $conf['class'] !== 'LBFactorySimple' ) {
			throw new MWException( 'CloakingLBFactory is not compatible with ' . $conf['class'] );
		}

		parent::__construct( $conf );
	}

	/**
	 * @param array $servers
	 *
	 * @return LoadBalancer
	 */
	protected function newLoadBalancer( array $servers ) {
		$lb = new CloakingLoadBalancer( $servers );

		if ( $this->isCloaked() ) {
			$lb->cloakDatabase( $this->cloakParams );
		}

		return $lb;
	}

	/**
	 * @return bool
	 */
	public function isCloaked() {
		return $this->cloakParams !== null;
	}

	/**
	 * Cloaks the database layer. LoadBalancer instances stay intact.
	 * All open connections are closed. New connections will automatically use
	 * cloaking according to $cloakParams.
	 *
	 * @see UnitTestLoadBalancer::cloakDatabase
	 *
	 * @param array $cloakParams defines keys: testDbPrefix: string, useTemporaryTables: bool,
	 *        reuseDB: bool.
	 */
	public function cloakDatabase( array $cloakParams ) {
		$this->cloakParams = $cloakParams;
		$this->forEachLB( function( CloakingLoadBalancer $lb ) use ( $cloakParams ) {
			$lb->cloakDatabase( $cloakParams );
		} );
	}

	/**
	 * Uncloaks the database layer. LoadBalancer instances stay intact.
	 * All open connections are closed. New connections will function without cloaking.
	 *
	 * @see UnitTestLoadBalancer::uncloakDatabase
	 */
	public function uncloakDatabase() {
		if ( !$this->isCloaked() ) {
			return;
		}

		$this->cloakParams = null;
		$this->forEachLB( function( CloakingLoadBalancer $lb ) {
			$lb->uncloakDatabase();
		} );
	}

	/**
	 * @param DatabaseDomain|string|bool $domain Domain ID for a selective domain
	 *                            false or "" equivalent DatabaseDomain object for current domain
	 *
	 * @return bool
	 */
	private function isForeignWiki( $domain ) {
		if ( $domain instanceof DatabaseDomain ) {
			$domain = $domain->getId();
		}
		if ( $domain ) {
			// strip table prefixs
			$domain = preg_replace( '/-.*$/', '', $domain );
			$wikiId = preg_replace( '/-.*$/', '', wfWikiID() );
			return $domain !== $wikiId;
		}

		return false;
	}

	/**
	 * Overwritten to fail for foreign database access when cloaking is active.
	 *
	 * @param bool|string $wiki must be false.
	 *
	 * @throws DBAccessError
	 * @return LoadBalancer
	 */
	public function newMainLB( $wiki = false ) {
		if ( $this->isCloaked() && $this->isForeignWiki( $wiki ) ) {
			throw new DBAccessError();
		}

		return parent::newMainLB( $wiki );
	}

	/**
	 * Overwritten to fail for foreign database access when cloaking is active.
	 *
	 * @param bool|string $wiki must be false.
	 *
	 * @throws DBAccessError
	 * @return LoadBalancer
	 */
	public function getMainLB( $wiki = false ) {
		if ( $this->isCloaked() && $this->isForeignWiki( $wiki ) ) {
			throw new DBAccessError();
		}

		return parent::getMainLB( $wiki );
	}

	/**
	 * Overwritten to always fail when cloaking is active.
	 *
	 * @param string $cluster
	 *
	 * @throws DBAccessError
	 * @return LoadBalancer
	 */
	public function newExternalLB( $cluster ) {
		if ( $this->isCloaked() ) {
			throw new DBAccessError();
		}

		return parent::newExternalLB( $cluster );
	}

	/**
	 * Overwritten to always fail when cloaking is active.
	 *
	 * @param string $cluster
	 *
	 * @throws DBAccessError
	 * @return LoadBalancer
	 */
	public function &getExternalLB( $cluster ) {
		if ( $this->isCloaked() ) {
			throw new DBAccessError();
		}

		return parent::getExternalLB( $cluster );
	}

	/**
	 * Change the table prefix on all open DB connections
	 *
	 * @param string $prefix
	 * @return void
	 */
	public function changePrefix( $prefix ) {
		$this->forEachLB( [ $this, 'changeLBPrefix' ], [ $prefix ] );
	}

	/**
	 * @param LoadBalancer $lb
	 * @param string $prefix
	 * @return void
	 */
	private function changeLBPrefix( $lb, $prefix ) {
		$lb->forEachOpenConnection( [ $this, 'changeDBPrefix' ], [ $prefix ] );
	}

}
