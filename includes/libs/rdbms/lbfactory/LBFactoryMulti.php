<?php
/**
 * Advanced generator of database load balancing objects for database farms.
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

namespace Wikimedia\Rdbms;

use InvalidArgumentException;
use UnexpectedValueException;

/**
 * A multi-database, multi-master factory for Wikimedia and similar installations.
 * Ignores the old configuration globals.
 *
 * @ingroup Database
 */
class LBFactoryMulti extends LBFactory {
	/** @var LoadBalancer[] Tracked main load balancer instances */
	private $mainLBs = [];
	/** @var LoadBalancer[] Tracked external load balancer instances */
	private $externalLBs = [];

	/** @var string[] Map of (hostname => IP address) */
	private $hostsByName;
	/** @var string[] Map of (database name => section name) */
	private $sectionsByDB;
	/** @var int[][][] Map of (section => group => host => load ratio) */
	private $groupLoadsBySection;
	/** @var int[][][] Map of (database => group => host => load ratio) */
	private $groupLoadsByDB;
	/** @var int[][] Map of (cluster => host => load ratio) */
	private $externalLoads;
	/** @var array Server config map ("host", "hostName", "load", and "groupLoads" are ignored) */
	private $serverTemplate;
	/** @var array Server config map overriding "serverTemplate" for external storage */
	private $externalTemplateOverrides;
	/** @var array[] Map of (section => server config map overrides) */
	private $templateOverridesBySection;
	/** @var array[] Map of (cluster => server config map overrides) for external storage */
	private $templateOverridesByCluster;
	/** @var array Server config override map for all main and external master servers */
	private $masterTemplateOverrides;
	/** @var array[] Map of (host => server config map overrides) for main and external servers */
	private $templateOverridesByServer;
	/** @var string[]|bool[] A map of section name to read-only message */
	private $readOnlyBySection;
	/** @var array Configuration for the LoadMonitor to use within LoadBalancer instances */
	private $loadMonitorConfig;

	/**
	 * Template override precedence (highest => lowest):
	 *   - templateOverridesByServer
	 *   - masterTemplateOverrides
	 *   - templateOverridesBySection/templateOverridesByCluster
	 *   - externalTemplateOverrides
	 *   - serverTemplate
	 * Overrides only work on top level keys (so nested values will not be merged).
	 *
	 * Server config maps should be of the format Database::factory() requires.
	 * Additionally, a 'max lag' key should also be set on server maps, indicating how stale the
	 * data can be before the load balancer tries to avoid using it. The map can have 'is static'
	 * set to disable blocking  replication sync checks (intended for archive servers with
	 * unchanging data).
	 *
	 * @see LBFactory::__construct()
	 * @param array $conf Additional parameters include:
	 *   - hostsByName: map of (hostname => IP address). [optional]
	 *   - sectionsByDB: map of (database => section name). The database name "DEFAULT" is
	 *      interpeted as a catch-all for all databases not otherwise mentioned. [optional]
	 *   - sectionLoads: map of (section => host => load ratio); the first host listed in
	 *      each section is the master server for that section. [optional]
	 *   - groupLoadsBySection: map of (section => group => host => group load ratio).
	 *      Any ILoadBalancer::GROUP_GENERIC group will be ignored. [optional]
	 *   - groupLoadsByDB: map of (database => group => host => load ratio) map. [optional]
	 *   - externalLoads: map of (cluster => host => load ratio) map. [optional]
	 *   - serverTemplate: server config map for Database::factory().
	 *      Note that "host", "hostName" and "load" entries will be overridden by
	 *      "groupLoadsBySection" and "hostsByName". [optional]
	 *   - externalTemplateOverrides: server config map overrides for external stores;
	 *      respects the override precedence described above. [optional]
	 *   - templateOverridesBySection: map of (section => server config map overrides);
	 *      respects the override precedence described above. [optional]
	 *   - templateOverridesByCluster: map of (external cluster => server config map overrides);
	 *      respects the override precedence described above. [optional]
	 *   - masterTemplateOverrides: server config map overrides for masters;
	 *      respects the override precedence described above. [optional]
	 *   - templateOverridesByServer: map of (host => server config map overrides);
	 *      respects the override precedence described above and applies to both core
	 *      and external storage. [optional]
	 *   - loadMonitor: LoadMonitor::__construct() parameters with "class" field. [optional]
	 *   - readOnlyBySection: map of (section name => message text or false).
	 *      String values make sections read only, whereas anything else does not
	 *      restrict read/write mode. [optional]
	 */
	public function __construct( array $conf ) {
		parent::__construct( $conf );

		$this->hostsByName = $conf['hostsByName'] ?? [];
		$this->sectionsByDB = $conf['sectionsByDB'];
		$this->groupLoadsBySection = $conf['groupLoadsBySection'] ?? [];
		foreach ( ( $conf['sectionLoads'] ?? [] ) as $section => $loadByHost ) {
			$this->groupLoadsBySection[$section][ILoadBalancer::GROUP_GENERIC] = $loadByHost;
		}
		$this->groupLoadsByDB = $conf['groupLoadsByDB'] ?? [];
		$this->externalLoads = $conf['externalLoads'] ?? [];
		$this->serverTemplate = $conf['serverTemplate'] ?? [];
		$this->externalTemplateOverrides = $conf['externalTemplateOverrides'] ?? [];
		$this->templateOverridesBySection = $conf['templateOverridesBySection'] ?? [];
		$this->templateOverridesByCluster = $conf['templateOverridesByCluster'] ?? [];
		$this->masterTemplateOverrides = $conf['masterTemplateOverrides'] ?? [];
		$this->templateOverridesByServer = $conf['templateOverridesByServer'] ?? [];
		$this->readOnlyBySection = $conf['readOnlyBySection'] ?? [];

		if ( isset( $conf['loadMonitor'] ) ) {
			$this->loadMonitorConfig = $conf['loadMonitor'];
		} elseif ( isset( $conf['loadMonitorClass'] ) ) { // b/c
			$this->loadMonitorConfig = [ 'class' => $conf['loadMonitorClass'] ];
		} else {
			$this->loadMonitorConfig = [ 'class' => LoadMonitor::class ];
		}
	}

	public function newMainLB( $domain = false, $owner = null ) {
		$domainInstance = $this->resolveDomainInstance( $domain );
		$database = $domainInstance->getDatabase();
		$section = $this->getSectionFromDatabase( $database );
		if ( !isset( $this->groupLoadsBySection[$section][ILoadBalancer::GROUP_GENERIC] ) ) {
			throw new UnexpectedValueException( "Section '$section' has no hosts defined." );
		}
		$dbGroupLoads = $this->groupLoadsByDB[$database] ?? [];
		unset( $dbGroupLoads[ILoadBalancer::GROUP_GENERIC] ); // cannot override
		return $this->newLoadBalancer(
			array_merge(
				$this->serverTemplate,
				$this->templateOverridesBySection[$section] ?? []
			),
			array_merge( $this->groupLoadsBySection[$section], $dbGroupLoads ),
			// Use the LB-specific read-only reason if everything isn't already read-only
			is_string( $this->readOnlyReason )
				? $this->readOnlyReason
				: ( $this->readOnlyBySection[$section] ?? false ),
			$owner
		);
	}

	public function getMainLB( $domain = false ) {
		$domainInstance = $this->resolveDomainInstance( $domain );
		$section = $this->getSectionFromDatabase( $domainInstance->getDatabase() );

		if ( !isset( $this->mainLBs[$section] ) ) {
			$this->mainLBs[$section] = $this->newMainLB( $domain, $this->getOwnershipId() );
		}

		return $this->mainLBs[$section];
	}

	public function newExternalLB( $cluster, $owner = null ) {
		if ( !isset( $this->externalLoads[$cluster] ) ) {
			throw new InvalidArgumentException( "Unknown cluster '$cluster'" );
		}
		return $this->newLoadBalancer(
			array_merge(
				$this->serverTemplate,
				$this->externalTemplateOverrides,
				$this->templateOverridesByCluster[$cluster] ?? []
			),
			[ ILoadBalancer::GROUP_GENERIC => $this->externalLoads[$cluster] ],
			$this->readOnlyReason,
			$owner
		);
	}

	public function getExternalLB( $cluster ) {
		if ( !isset( $this->externalLBs[$cluster] ) ) {
			$this->externalLBs[$cluster] =
				$this->newExternalLB( $cluster, $this->getOwnershipId() );
		}

		return $this->externalLBs[$cluster];
	}

	public function getAllMainLBs() {
		$lbs = [];
		foreach ( $this->sectionsByDB as $db => $section ) {
			if ( !isset( $lbs[$section] ) ) {
				$lbs[$section] = $this->getMainLB( $db );
			}
		}

		return $lbs;
	}

	public function getAllExternalLBs() {
		$lbs = [];
		foreach ( $this->externalLoads as $cluster => $unused ) {
			$lbs[$cluster] = $this->getExternalLB( $cluster );
		}

		return $lbs;
	}

	public function forEachLB( $callback, array $params = [] ) {
		foreach ( $this->mainLBs as $lb ) {
			$callback( $lb, ...$params );
		}
		foreach ( $this->externalLBs as $lb ) {
			$callback( $lb, ...$params );
		}
	}

	/**
	 * Make a new load balancer object based on template and load array
	 *
	 * @param array $serverTemplate
	 * @param array $groupLoads
	 * @param string|bool $readOnlyReason
	 * @param int|null $owner
	 * @return LoadBalancer
	 */
	private function newLoadBalancer( $serverTemplate, $groupLoads, $readOnlyReason, $owner ) {
		$lb = new LoadBalancer( array_merge(
			$this->baseLoadBalancerParams( $owner ),
			[
				'servers' => $this->makeServerConfigArrays( $serverTemplate, $groupLoads ),
				'loadMonitor' => $this->loadMonitorConfig,
				'readOnlyReason' => $readOnlyReason
			]
		) );
		$this->initLoadBalancer( $lb );

		return $lb;
	}

	/**
	 * Make a server array as expected by LoadBalancer::__construct()
	 *
	 * @param array $serverTemplate Server config map
	 * @param int[][] $groupLoads Map of (group => host => load)
	 * @return array[] List of server config maps
	 */
	private function makeServerConfigArrays( array $serverTemplate, array $groupLoads ) {
		// The master server is the first host explicitly listed in the generic load group
		if ( !$groupLoads[ILoadBalancer::GROUP_GENERIC] ) {
			throw new UnexpectedValueException( "Empty generic load array; no master defined." );
		}
		$groupLoadsByHost = $this->reindexGroupLoadsByHost( $groupLoads );
		// Get the ordered map of (host => load); the master server is first
		$genericLoads = $groupLoads[ILoadBalancer::GROUP_GENERIC];
		// Implictly append any hosts that only appear in custom load groups
		$genericLoads += array_fill_keys( array_keys( $groupLoadsByHost ), 0 );
		$servers = [];
		foreach ( $genericLoads as $host => $load ) {
			$servers[] = array_merge(
				$serverTemplate,
				$servers ? [] : $this->masterTemplateOverrides,
				$this->templateOverridesByServer[$host] ?? [],
				[
					'host' => $this->hostsByName[$host] ?? $host,
					'hostName' => $host,
					'load' => $load,
					'groupLoads' => $groupLoadsByHost[$host] ?? []
				]
			);
		}

		return $servers;
	}

	/**
	 * Take a group load array indexed by group then server, and reindex it by server then group
	 * @param int[][] $groupLoads Map of (group => host => load)
	 * @return int[][] Map of (host => group => load)
	 */
	private function reindexGroupLoadsByHost( $groupLoads ) {
		$groupLoadsByHost = [];
		foreach ( $groupLoads as $group => $loadByHost ) {
			foreach ( $loadByHost as $host => $load ) {
				$groupLoadsByHost[$host][$group] = $load;
			}
		}

		return $groupLoadsByHost;
	}

	/**
	 * @param string $database
	 * @return string Section name
	 */
	private function getSectionFromDatabase( $database ) {
		return $this->sectionsByDB[$database] ?? 'DEFAULT';
	}
}
