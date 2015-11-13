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
	/** @var ChronologyProtector */
	protected $chronProt;

	/** @var LBFactory */
	private static $instance;

	/** @var string|bool Reason all LBs are read-only or false if not */
	protected $readOnlyReason = false;

	const SHUTDOWN_NO_CHRONPROT = 1; // don't save ChronologyProtector positions (for async code)

	/**
	 * Construct a factory based on a configuration array (typically from $wgLBFactoryConf)
	 * @param array $conf
	 */
	public function __construct( array $conf ) {
		if ( isset( $conf['readOnlyReason'] ) && is_string( $conf['readOnlyReason'] ) ) {
			$this->readOnlyReason = $conf['readOnlyReason'];
		}

		$this->chronProt = $this->newChronologyProtector();
	}

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
	public static function singleton() {
		global $wgLBFactoryConf;

		if ( is_null( self::$instance ) ) {
			$class = self::getLBFactoryClass( $wgLBFactoryConf );
			$config = $wgLBFactoryConf;
			if ( !isset( $config['readOnlyReason'] ) ) {
				$config['readOnlyReason'] = wfConfiguredReadOnlyReason();
			}
			self::$instance = new $class( $config );
		}

		return self::$instance;
	}

	/**
	 * Returns the LBFactory class to use and the load balancer configuration.
	 *
	 * @param array $config (e.g. $wgLBFactoryConf)
	 * @return string Class name
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
	public static function destroyInstance() {
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
	public static function setInstance( $instance ) {
		self::destroyInstance();
		self::$instance = $instance;
	}

	/**
	 * Create a new load balancer object. The resulting object will be untracked,
	 * not chronology-protected, and the caller is responsible for cleaning it up.
	 *
	 * @param bool|string $wiki Wiki ID, or false for the current wiki
	 * @return LoadBalancer
	 */
	abstract public function newMainLB( $wiki = false );

	/**
	 * Get a cached (tracked) load balancer object.
	 *
	 * @param bool|string $wiki Wiki ID, or false for the current wiki
	 * @return LoadBalancer
	 */
	abstract public function getMainLB( $wiki = false );

	/**
	 * Create a new load balancer for external storage. The resulting object will be
	 * untracked, not chronology-protected, and the caller is responsible for
	 * cleaning it up.
	 *
	 * @param string $cluster External storage cluster, or false for core
	 * @param bool|string $wiki Wiki ID, or false for the current wiki
	 * @return LoadBalancer
	 */
	abstract protected function newExternalLB( $cluster, $wiki = false );

	/**
	 * Get a cached (tracked) load balancer for external storage
	 *
	 * @param string $cluster External storage cluster, or false for core
	 * @param bool|string $wiki Wiki ID, or false for the current wiki
	 * @return LoadBalancer
	 */
	abstract public function &getExternalLB( $cluster, $wiki = false );

	/**
	 * Execute a function for each tracked load balancer
	 * The callback is called with the load balancer as the first parameter,
	 * and $params passed as the subsequent parameters.
	 *
	 * @param callable $callback
	 * @param array $params
	 */
	abstract public function forEachLB( $callback, array $params = array() );

	/**
	 * Prepare all tracked load balancers for shutdown
	 * @param integer $flags Supports SHUTDOWN_* flags
	 * STUB
	 */
	public function shutdown( $flags = 0 ) {
	}

	/**
	 * Call a method of each tracked load balancer
	 *
	 * @param string $methodName
	 * @param array $args
	 */
	private function forEachLBCallMethod( $methodName, array $args = array() ) {
		$this->forEachLB( function ( LoadBalancer $loadBalancer, $methodName, array $args ) {
			call_user_func_array( array( $loadBalancer, $methodName ), $args );
		}, array( $methodName, $args ) );
	}

	/**
	 * Commit on all connections. Done for two reasons:
	 * 1. To commit changes to the masters.
	 * 2. To release the snapshot on all connections, master and slave.
	 */
	public function commitAll() {
		$this->forEachLBCallMethod( 'commitAll' );
	}

	/**
	 * Commit changes on all master connections
	 */
	public function commitMasterChanges() {
		$start = microtime( true );
		$this->forEachLBCallMethod( 'commitMasterChanges' );
		$timeMs = 1000 * ( microtime( true ) - $start );
		RequestContext::getMain()->getStats()->timing( "db.commit-masters", $timeMs );
	}

	/**
	 * Rollback changes on all master connections
	 * @since 1.23
	 */
	public function rollbackMasterChanges() {
		$this->forEachLBCallMethod( 'rollbackMasterChanges' );
	}

	/**
	 * Determine if any master connection has pending changes
	 * @return bool
	 * @since 1.23
	 */
	public function hasMasterChanges() {
		$ret = false;
		$this->forEachLB( function ( LoadBalancer $lb ) use ( &$ret ) {
			$ret = $ret || $lb->hasMasterChanges();
		} );

		return $ret;
	}

	/**
	 * Detemine if any lagged slave connection was used
	 * @since 1.27
	 * @return bool
	 */
	public function laggedSlaveUsed() {
		$ret = false;
		$this->forEachLB( function ( LoadBalancer $lb ) use ( &$ret ) {
			$ret = $ret || $lb->laggedSlaveUsed();
		} );

		return $ret;
	}

	/**
	 * Determine if any master connection has pending/written changes from this request
	 * @return bool
	 * @since 1.27
	 */
	public function hasOrMadeRecentMasterChanges() {
		$ret = false;
		$this->forEachLB( function ( LoadBalancer $lb ) use ( &$ret ) {
			$ret = $ret || $lb->hasOrMadeRecentMasterChanges();
		} );
		return $ret;
	}

	/**
	 * Disable the ChronologyProtector for all load balancers
	 *
	 * This can be called at the start of special API entry points
	 *
	 * @since 1.27
	 */
	public function disableChronologyProtection() {
		$this->chronProt->setEnabled( false );
	}

	/**
	 * @return ChronologyProtector
	 */
	protected function newChronologyProtector() {
		$request = RequestContext::getMain()->getRequest();
		$chronProt = new ChronologyProtector(
			ObjectCache::getMainStashInstance(),
			array(
				'ip' => $request->getIP(),
				'agent' => $request->getHeader( 'User-Agent' )
			)
		);
		if ( PHP_SAPI === 'cli' ) {
			$chronProt->setEnabled( false );
		} elseif ( $request->getHeader( 'ChronologyProtection' ) === 'false' ) {
			// Request opted out of using position wait logic. This is useful for requests
			// done by the job queue or background ETL that do not have a meaningful session.
			$chronProt->setWaitEnabled( false );
		}

		return $chronProt;
	}

	/**
	 * @param ChronologyProtector $cp
	 */
	protected function shutdownChronologyProtector( ChronologyProtector $cp ) {
		// Get all the master positions needed
		$this->forEachLB( function ( LoadBalancer $lb ) use ( $cp ) {
			$cp->shutdownLB( $lb );
		} );
		// Write them to the stash
		$unsavedPositions = $cp->shutdown();
		// If the positions failed to write to the stash, at least wait on local datacenter
		// slaves to catch up before responding. Even if there are several DCs, this increases
		// the chance that the user will see their own changes immediately afterwards. As long
		// as the sticky DC cookie applies (same domain), this is not even an issue.
		$this->forEachLB( function ( LoadBalancer $lb ) use ( $unsavedPositions ) {
			$masterName = $lb->getServerName( $lb->getWriterIndex() );
			if ( isset( $unsavedPositions[$masterName] ) ) {
				$lb->waitForAll( $unsavedPositions[$masterName] );
			}
		} );
	}
}

/**
 * Exception class for attempted DB access
 */
class DBAccessError extends MWException {
	public function __construct() {
		parent::__construct( "Mediawiki tried to access the database via wfGetDB(). " .
			"This is not allowed." );
	}
}
