<?php
/**
 * Generator of database load balancing objects.
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
 * @ingroup Database
 */

/**
 * An interface for generating database load balancers
 * @ingroup Database
 */
abstract class LBFactory {
	/** @var LBFactory */
	protected static $instance;

	/**
	 * Disables all access to the load balancer, will cause all database access
	 * to throw a DBAccessError
	 */
	public static function disableBackend() {
		global $wgLBFactoryConf;
		self::$instance = new LBFactoryFake( $wgLBFactoryConf );
	}

	/**
	 * Get an LBFactory instance
	 *
	 * @return LBFactory
	 */
	static function &singleton() {
		global $wgLBFactoryConf;

		if ( is_null( self::$instance ) ) {
			$class = self::getLBFactoryClass( $wgLBFactoryConf );

			self::$instance = new $class( $wgLBFactoryConf );
		}

		return self::$instance;
	}

	/**
	 * Returns the LBFactory class to use and the load balancer configuration.
	 *
	 * @param array $config (e.g. $wgLBFactoryConf)
	 * @return string class name
	 */
	public static function getLBFactoryClass( array $config ) {
		// For configuration backward compatibility after removing
		// underscores from class names in MediaWiki 1.23.
		$bcClasses = array(
			'LBFactory_Simple' => 'LBFactorySimple',
			'LBFactory_Single' => 'LBFactorySingle',
			'LBFactory_Multi' => 'LBFactoryMulti',
			'LBFactory_Fake' => 'LBFactoryFake',
		);

		$class = $config['class'];

		if ( isset( $bcClasses[$class] ) ) {
			$class = $bcClasses[$class];
			wfDeprecated(
				'$wgLBFactoryConf must be updated. See RELEASE-NOTES for details',
				'1.23'
			);
		}

		return $class;
	}

	/**
	 * Shut down, close connections and destroy the cached instance.
	 */
	static function destroyInstance() {
		if ( self::$instance ) {
			self::$instance->shutdown();
			self::$instance->forEachLBCallMethod( 'closeAll' );
			self::$instance = null;
		}
	}

	/**
	 * Set the instance to be the given object
	 *
	 * @param LBFactory $instance
	 */
	static function setInstance( $instance ) {
		self::destroyInstance();
		self::$instance = $instance;
	}

	/**
	 * Construct a factory based on a configuration array (typically from $wgLBFactoryConf)
	 * @param array $conf
	 */
	abstract function __construct( $conf );

	/**
	 * Create a new load balancer object. The resulting object will be untracked,
	 * not chronology-protected, and the caller is responsible for cleaning it up.
	 *
	 * @param bool|string $wiki Wiki ID, or false for the current wiki
	 * @return LoadBalancer
	 */
	abstract function newMainLB( $wiki = false );

	/**
	 * Get a cached (tracked) load balancer object.
	 *
	 * @param bool|string $wiki Wiki ID, or false for the current wiki
	 * @return LoadBalancer
	 */
	abstract function getMainLB( $wiki = false );

	/**
	 * Create a new load balancer for external storage. The resulting object will be
	 * untracked, not chronology-protected, and the caller is responsible for
	 * cleaning it up.
	 *
	 * @param string $cluster External storage cluster, or false for core
	 * @param bool|string $wiki Wiki ID, or false for the current wiki
	 * @return LoadBalancer
	 */
	abstract function newExternalLB( $cluster, $wiki = false );

	/**
	 * Get a cached (tracked) load balancer for external storage
	 *
	 * @param string $cluster external storage cluster, or false for core
	 * @param bool|string $wiki Wiki ID, or false for the current wiki
	 * @return LoadBalancer
	 */
	abstract function &getExternalLB( $cluster, $wiki = false );

	/**
	 * Execute a function for each tracked load balancer
	 * The callback is called with the load balancer as the first parameter,
	 * and $params passed as the subsequent parameters.
	 *
	 * @param callable $callback
	 * @param array $params
	 */
	abstract function forEachLB( $callback, $params = array() );

	/**
	 * Prepare all tracked load balancers for shutdown
	 * STUB
	 */
	function shutdown() {
	}

	/**
	 * Call a method of each tracked load balancer
	 *
	 * @param string $methodName
	 * @param array $args
	 */
	function forEachLBCallMethod( $methodName, $args = array() ) {
		$this->forEachLB( array( $this, 'callMethod' ), array( $methodName, $args ) );
	}

	/**
	 * Private helper for forEachLBCallMethod
	 * @param LoadBalancer $loadBalancer
	 * @param string $methodName
	 * @param $args
	 */
	function callMethod( $loadBalancer, $methodName, $args ) {
		call_user_func_array( array( $loadBalancer, $methodName ), $args );
	}

	/**
	 * Commit changes on all master connections
	 */
	function commitMasterChanges() {
		$this->forEachLBCallMethod( 'commitMasterChanges' );
	}

	/**
	 * Rollback changes on all master connections
	 * @since 1.23
	 */
	function rollbackMasterChanges() {
		$this->forEachLBCallMethod( 'rollbackMasterChanges' );
	}

	/**
	 * Detemine if any master connection has pending changes.
	 * @since 1.23
	 * @return bool
	 */
	function hasMasterChanges() {
		$ret = false;
		$this->forEachLB( function ( $lb ) use ( &$ret ) {
			$ret = $ret || $lb->hasMasterChanges();
		} );
		return $ret;
	}
}

/**
 * A simple single-master LBFactory that gets its configuration from the b/c globals
 */
class LBFactorySimple extends LBFactory {
	/** @var LoadBalancer */
	protected $mainLB;

	/** @var LoadBalancer[] */
	protected $extLBs = array();

	/** @var ChronologyProtector */
	protected $chronProt;

	function __construct( $conf ) {
		$this->chronProt = new ChronologyProtector;
	}

	/**
	 * @param bool|string $wiki
	 * @return LoadBalancer
	 */
	function newMainLB( $wiki = false ) {
		global $wgDBservers, $wgMasterWaitTimeout;
		if ( $wgDBservers ) {
			$servers = $wgDBservers;
		} else {
			global $wgDBserver, $wgDBuser, $wgDBpassword, $wgDBname, $wgDBtype, $wgDebugDumpSql;
			global $wgDBssl, $wgDBcompress;

			$flags = DBO_DEFAULT;
			if ( $wgDebugDumpSql ) {
				$flags |= DBO_DEBUG;
			}
			if ( $wgDBssl ) {
				$flags |= DBO_SSL;
			}
			if ( $wgDBcompress ) {
				$flags |= DBO_COMPRESS;
			}

			$servers = array( array(
				'host' => $wgDBserver,
				'user' => $wgDBuser,
				'password' => $wgDBpassword,
				'dbname' => $wgDBname,
				'type' => $wgDBtype,
				'load' => 1,
				'flags' => $flags
			) );
		}

		return new LoadBalancer( array(
			'servers' => $servers,
			'masterWaitTimeout' => $wgMasterWaitTimeout
		) );
	}

	/**
	 * @param bool|string $wiki
	 * @return LoadBalancer
	 */
	function getMainLB( $wiki = false ) {
		if ( !isset( $this->mainLB ) ) {
			$this->mainLB = $this->newMainLB( $wiki );
			$this->mainLB->parentInfo( array( 'id' => 'main' ) );
			$this->chronProt->initLB( $this->mainLB );
		}

		return $this->mainLB;
	}

	/**
	 * @throws MWException
	 * @param string $cluster
	 * @param bool|string $wiki
	 * @return LoadBalancer
	 */
	function newExternalLB( $cluster, $wiki = false ) {
		global $wgExternalServers;
		if ( !isset( $wgExternalServers[$cluster] ) ) {
			throw new MWException( __METHOD__ . ": Unknown cluster \"$cluster\"" );
		}

		return new LoadBalancer( array(
			'servers' => $wgExternalServers[$cluster]
		) );
	}

	/**
	 * @param string $cluster
	 * @param bool|string $wiki
	 * @return array
	 */
	function &getExternalLB( $cluster, $wiki = false ) {
		if ( !isset( $this->extLBs[$cluster] ) ) {
			$this->extLBs[$cluster] = $this->newExternalLB( $cluster, $wiki );
			$this->extLBs[$cluster]->parentInfo( array( 'id' => "ext-$cluster" ) );
			$this->chronProt->initLB( $this->extLBs[$cluster] );
		}

		return $this->extLBs[$cluster];
	}

	/**
	 * Execute a function for each tracked load balancer
	 * The callback is called with the load balancer as the first parameter,
	 * and $params passed as the subsequent parameters.
	 *
	 * @param callable $callback
	 * @param array $params
	 */
	function forEachLB( $callback, $params = array() ) {
		if ( isset( $this->mainLB ) ) {
			call_user_func_array( $callback, array_merge( array( $this->mainLB ), $params ) );
		}
		foreach ( $this->extLBs as $lb ) {
			call_user_func_array( $callback, array_merge( array( $lb ), $params ) );
		}
	}

	function shutdown() {
		if ( $this->mainLB ) {
			$this->chronProt->shutdownLB( $this->mainLB );
		}
		foreach ( $this->extLBs as $extLB ) {
			$this->chronProt->shutdownLB( $extLB );
		}
		$this->chronProt->shutdown();
		$this->commitMasterChanges();
	}
}

/**
 * LBFactory class that throws an error on any attempt to use it.
 * This will typically be done via wfGetDB().
 * Call LBFactory::disableBackend() to start using this, and
 * LBFactory::enableBackend() to return to normal behavior
 */
class LBFactoryFake extends LBFactory {
	function __construct( $conf ) {
	}

	function newMainLB( $wiki = false ) {
		throw new DBAccessError;
	}

	function getMainLB( $wiki = false ) {
		throw new DBAccessError;
	}

	function newExternalLB( $cluster, $wiki = false ) {
		throw new DBAccessError;
	}

	function &getExternalLB( $cluster, $wiki = false ) {
		throw new DBAccessError;
	}

	function forEachLB( $callback, $params = array() ) {
	}
}

/**
 * Exception class for attempted DB access
 */
class DBAccessError extends MWException {
	function __construct() {
		parent::__construct( "Mediawiki tried to access the database via wfGetDB(). " .
			"This is not allowed." );
	}
}
