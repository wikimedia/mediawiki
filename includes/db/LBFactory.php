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

	/**
	 * @var LBFactory
	 */
	static $instance;

	/**
	 * Disables all access to the load balancer, will cause all database access
	 * to throw a DBAccessError
	 */
	public static function disableBackend() {
		global $wgLBFactoryConf;
		self::$instance = new LBFactory_Fake( $wgLBFactoryConf );
	}

	/**
	 * Get an LBFactory instance
	 *
	 * @return LBFactory
	 */
	static function &singleton() {
		if ( is_null( self::$instance ) ) {
			global $wgLBFactoryConf;
			$class = $wgLBFactoryConf['class'];
			self::$instance = new $class( $wgLBFactoryConf );
		}
		return self::$instance;
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
	 * @param $instance LBFactory
	 */
	static function setInstance( $instance ) {
		self::destroyInstance();
		self::$instance = $instance;
	}

	/**
	 * Construct a factory based on a configuration array (typically from $wgLBFactoryConf)
	 * @param $conf
	 */
	abstract function __construct( $conf );

	/**
	 * Create a new load balancer object. The resulting object will be untracked,
	 * not chronology-protected, and the caller is responsible for cleaning it up.
	 *
	 * @param $wiki String: wiki ID, or false for the current wiki
	 * @return LoadBalancer
	 */
	abstract function newMainLB( $wiki = false );

	/**
	 * Get a cached (tracked) load balancer object.
	 *
	 * @param $wiki String: wiki ID, or false for the current wiki
	 * @return LoadBalancer
	 */
	abstract function getMainLB( $wiki = false );

	/**
	 * Create a new load balancer for external storage. The resulting object will be
	 * untracked, not chronology-protected, and the caller is responsible for
	 * cleaning it up.
	 *
	 * @param $cluster String: external storage cluster, or false for core
	 * @param $wiki String: wiki ID, or false for the current wiki
	 *
	 * @return LoadBalancer
	 */
	abstract function newExternalLB( $cluster, $wiki = false );

	/**
	 * Get a cached (tracked) load balancer for external storage
	 *
	 * @param $cluster String: external storage cluster, or false for core
	 * @param $wiki String: wiki ID, or false for the current wiki
	 *
	 * @return LoadBalancer
	 */
	abstract function &getExternalLB( $cluster, $wiki = false );

	/**
	 * Execute a function for each tracked load balancer
	 * The callback is called with the load balancer as the first parameter,
	 * and $params passed as the subsequent parameters.
	 * @param $callback string|array
	 * @param array $params
	 */
	abstract function forEachLB( $callback, $params = array() );

	/**
	 * Prepare all tracked load balancers for shutdown
	 * STUB
	 */
	function shutdown() {}

	/**
	 * Call a method of each tracked load balancer
	 * @param $methodName string
	 * @param $args array
	 */
	function forEachLBCallMethod( $methodName, $args = array() ) {
		$this->forEachLB( array( $this, 'callMethod' ), array( $methodName, $args ) );
	}

	/**
	 * Private helper for forEachLBCallMethod
	 * @param $loadBalancer
	 * @param $methodName string
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
}

/**
 * A simple single-master LBFactory that gets its configuration from the b/c globals
 */
class LBFactory_Simple extends LBFactory {

	/**
	 * @var LoadBalancer
	 */
	var $mainLB;
	var $extLBs = array();

	# Chronology protector
	var $chronProt;

	function __construct( $conf ) {
		$this->chronProt = new ChronologyProtector;
	}

	/**
	 * @param $wiki
	 * @return LoadBalancer
	 */
	function newMainLB( $wiki = false ) {
		global $wgDBservers, $wgMasterWaitTimeout;
		if ( $wgDBservers ) {
			$servers = $wgDBservers;
		} else {
			global $wgDBserver, $wgDBuser, $wgDBpassword, $wgDBname, $wgDBtype, $wgDebugDumpSql;
			global $wgDBssl, $wgDBcompress;

			$flags = ( $wgDebugDumpSql ? DBO_DEBUG : 0 ) | DBO_DEFAULT;
			if ( $wgDBssl ) {
				$flags |= DBO_SSL;
			}
			if ( $wgDBcompress ) {
				$flags |= DBO_COMPRESS;
			}

			$servers = array(array(
				'host' => $wgDBserver,
				'user' => $wgDBuser,
				'password' => $wgDBpassword,
				'dbname' => $wgDBname,
				'type' => $wgDBtype,
				'load' => 1,
				'flags' => $flags
			));
		}

		return new LoadBalancer( array(
			'servers' => $servers,
			'masterWaitTimeout' => $wgMasterWaitTimeout
		));
	}

	/**
	 * @param $wiki
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
	 * @param $cluster
	 * @param $wiki
	 * @return LoadBalancer
	 */
	function newExternalLB( $cluster, $wiki = false ) {
		global $wgExternalServers;
		if ( !isset( $wgExternalServers[$cluster] ) ) {
			throw new MWException( __METHOD__.": Unknown cluster \"$cluster\"" );
		}
		return new LoadBalancer( array(
			'servers' => $wgExternalServers[$cluster]
		));
	}

	/**
	 * @param $cluster
	 * @param $wiki
	 * @return array
	 */
	function &getExternalLB( $cluster, $wiki = false ) {
		if ( !isset( $this->extLBs[$cluster] ) ) {
			$this->extLBs[$cluster] = $this->newExternalLB( $cluster, $wiki );
			$this->extLBs[$cluster]->parentInfo( array( 'id' => "ext-$cluster" ) );
		}
		return $this->extLBs[$cluster];
	}

	/**
	 * Execute a function for each tracked load balancer
	 * The callback is called with the load balancer as the first parameter,
	 * and $params passed as the subsequent parameters.
	 * @param $callback
	 * @param $params array
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
class LBFactory_Fake extends LBFactory {
	function __construct( $conf ) {}

	function newMainLB( $wiki = false) {
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
	function forEachLB( $callback, $params = array() ) {}
}

/**
 * Exception class for attempted DB access
 */
class DBAccessError extends MWException {
	function __construct() {
		parent::__construct( "Mediawiki tried to access the database via wfGetDB(). This is not allowed." );
	}
}

/**
 * Class for ensuring a consistent ordering of events as seen by the user, despite replication.
 * Kind of like Hawking's [[Chronology Protection Agency]].
 */
class ChronologyProtector {
	var $startupPos;
	var $shutdownPos = array();

	/**
	 * Initialise a LoadBalancer to give it appropriate chronology protection.
	 *
	 * @param $lb LoadBalancer
	 */
	function initLB( $lb ) {
		if ( $this->startupPos === null ) {
			if ( !empty( $_SESSION[__CLASS__] ) ) {
				$this->startupPos = $_SESSION[__CLASS__];
			}
		}
		if ( !$this->startupPos ) {
			return;
		}
		$masterName = $lb->getServerName( 0 );

		if ( $lb->getServerCount() > 1 && !empty( $this->startupPos[$masterName] ) ) {
			$info = $lb->parentInfo();
			$pos = $this->startupPos[$masterName];
			wfDebug( __METHOD__.": LB " . $info['id'] . " waiting for master pos $pos\n" );
			$lb->waitFor( $this->startupPos[$masterName] );
		}
	}

	/**
	 * Notify the ChronologyProtector that the LoadBalancer is about to shut
	 * down. Saves replication positions.
	 *
	 * @param $lb LoadBalancer
	 */
	function shutdownLB( $lb ) {
		// Don't start a session, don't bother with non-replicated setups
		if ( strval( session_id() ) == '' || $lb->getServerCount() <= 1 ) {
			return;
		}
		$masterName = $lb->getServerName( 0 );
		if ( isset( $this->shutdownPos[$masterName] ) ) {
			// Already done
			return;
		}
		// Only save the position if writes have been done on the connection
		$db = $lb->getAnyOpenConnection( 0 );
		$info = $lb->parentInfo();
		if ( !$db || !$db->doneWrites() ) {
			wfDebug( __METHOD__.": LB {$info['id']}, no writes done\n" );
			return;
		}
		$pos = $db->getMasterPos();
		wfDebug( __METHOD__.": LB {$info['id']} has master pos $pos\n" );
		$this->shutdownPos[$masterName] = $pos;
	}

	/**
	 * Notify the ChronologyProtector that the LBFactory is done calling shutdownLB() for now.
	 * May commit chronology data to persistent storage.
	 */
	function shutdown() {
		if ( session_id() != '' && count( $this->shutdownPos ) ) {
			wfDebug( __METHOD__.": saving master pos for " .
				count( $this->shutdownPos ) . " master(s)\n" );
			$_SESSION[__CLASS__] = $this->shutdownPos;
		}
	}
}
