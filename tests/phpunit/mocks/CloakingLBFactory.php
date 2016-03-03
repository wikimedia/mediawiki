<?php

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
class CloakingLBFactory extends LBFactorySimple { // FIXME TESTME

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
	 * @param array $params
	 *
	 * @return LoadBalancer
	 */
	protected function newLoadBalancer( array $params ) {
		$lb = new CloakingLoadBalancer( $params );

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
	 * @return array|null
	 */
	public function getCloakParams() {
		return $this->cloakParams;
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
	 * @param string $wiki
	 *
	 * @return bool
	 */
	private function isForeignWiki( $wiki ) {
		return $wiki !== false && $wiki !== wfWikiID();
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
			throw new DBAccessError( 'Cannot access foreign databases when cloaked: ' . $wiki );
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
			throw new DBAccessError( 'Cannot access foreign databases when cloaked: ' . $wiki );
		}

		return parent::getMainLB( $wiki );
	}

	/**
	 * Overwritten to always fail when cloaking is active.
	 *
	 * @param string $cluster
	 * @param bool|string $wiki
	 *
	 * @throws DBAccessError
	 * @return LoadBalancer
	 */
	protected function newExternalLB( $cluster, $wiki = false ) {
		if ( $this->isCloaked() ) {
			throw new DBAccessError( 'Cannot access external storage databases when cloaked: ' . $wiki );
		}

		return parent::newExternalLB( $cluster, $wiki );
	}

	/**
	 * Overwritten to always fail when cloaking is active.
	 *
	 * @param string $cluster
	 * @param bool|string $wiki
	 *
	 * @throws DBAccessError
	 * @return LoadBalancer
	 */
	public function &getExternalLB( $cluster, $wiki = false ) {
		if ( $this->isCloaked() ) {
			throw new DBAccessError( 'Cannot access external storage databases when cloaked: ' . $wiki );
		}

		return parent::getExternalLB( $cluster, $wiki );
	}

}
