<?php
/**
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
 */
namespace Wikimedia\Rdbms;

use InvalidArgumentException;
use LogicException;
use UnexpectedValueException;

/**
 * Advanced manager for multiple database sections, e.g. for large wiki farms.
 *
 * This means different wikis can be stored on different database servers.
 * It includes support for multi-primary setups.
 *
 * @ingroup Database
 */
class LBFactoryMulti extends LBFactory {
	/** @var array<string,LoadBalancer> Map of (main section => tracked LoadBalancer) */
	private $mainLBs = [];
	/** @var array<string,LoadBalancer> Map of (external cluster => tracked LoadBalancer) */
	private $externalLBs = [];

	/** @var string[] Map of (server name => IP address) */
	private $hostsByServerName;
	/** @var string[] Map of (database name => main section) */
	private $sectionsByDB;
	/** @var int[][][] Map of (main section => group => server name => load ratio) */
	private $groupLoadsBySection;
	/** @var int[][] Map of (external cluster => server name => load ratio) */
	private $externalLoadsByCluster;
	/** @var array Server config map ("host", "serverName", "load", and "groupLoads" ignored) */
	private $serverTemplate;
	/** @var array Server config map overriding "serverTemplate" for all external servers */
	private $externalTemplateOverrides;
	/** @var array[] Map of (main section => server config map overrides) */
	private $templateOverridesBySection;
	/** @var array[] Map of (external cluster => server config map overrides) */
	private $templateOverridesByCluster;
	/** @var array Server config override map for all main/external primary DB servers */
	private $masterTemplateOverrides;
	/** @var array[] Map of (server name => server config map overrides) for all servers */
	private $templateOverridesByServer;
	/** @var string[]|bool[] A map of (main section => read-only message) */
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
	 *   - hostsByName: map of (server name => IP address). [optional]
	 *   - sectionsByDB: map of (database => main section). The database name "DEFAULT" is
	 *      interpreted as a catch-all for all databases not otherwise mentioned. [optional]
	 *   - sectionLoads: map of (main section => server name => load ratio); the first host
	 *      listed in each section is the primary DB server for that section. [optional]
	 *   - groupLoadsBySection: map of (main section => group => server name => group load ratio).
	 *      Any ILoadBalancer::GROUP_GENERIC group will be ignored. [optional]
	 *   - externalLoads: map of (cluster => server name => load ratio) map. [optional]
	 *   - serverTemplate: server config map for Database::factory().
	 *      Note that "host", "serverName" and "load" entries will be overridden by
	 *      "groupLoadsBySection" and "hostsByName". [optional]
	 *   - externalTemplateOverrides: server config map overrides for external stores;
	 *      respects the override precedence described above. [optional]
	 *   - templateOverridesBySection: map of (main section => server config map overrides);
	 *      respects the override precedence described above. [optional]
	 *   - templateOverridesByCluster: map of (external cluster => server config map overrides);
	 *      respects the override precedence described above. [optional]
	 *   - masterTemplateOverrides: server config map overrides for masters;
	 *      respects the override precedence described above. [optional]
	 *   - templateOverridesByServer: map of (server name => server config map overrides);
	 *      respects the override precedence described above and applies to both core
	 *      and external storage. [optional]
	 *   - loadMonitor: LoadMonitor::__construct() parameters with "class" field. [optional]
	 *   - readOnlyBySection: map of (main section => message text or false).
	 *      String values make sections read only, whereas anything else does not
	 *      restrict read/write mode. [optional]
	 *   - configCallback: A callback that returns a conf array that can be passed to
	 *      the reconfigure() method. This will be used to autoReconfigure() to load
	 *      any updated configuration.
	 */
	public function __construct( array $conf ) {
		parent::__construct( $conf );

		$this->hostsByServerName = $conf['hostsByName'] ?? [];
		$this->sectionsByDB = $conf['sectionsByDB'];
		$this->groupLoadsBySection = $conf['groupLoadsBySection'] ?? [];
		foreach ( ( $conf['sectionLoads'] ?? [] ) as $section => $loadsByServerName ) {
			$this->groupLoadsBySection[$section][ILoadBalancer::GROUP_GENERIC] = $loadsByServerName;
		}
		$this->externalLoadsByCluster = $conf['externalLoads'] ?? [];
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

		foreach ( array_keys( $this->externalLoadsByCluster ) as $cluster ) {
			if ( isset( $this->groupLoadsBySection[$cluster] ) ) {
				throw new LogicException(
					"External cluster '$cluster' has the same name as a main section/cluster"
				);
			}
		}
	}

	public function newMainLB( $domain = false ): ILoadBalancerForOwner {
		$domainInstance = $this->resolveDomainInstance( $domain );
		$database = $domainInstance->getDatabase();
		$section = $this->getSectionFromDatabase( $database );

		if ( !isset( $this->groupLoadsBySection[$section][ILoadBalancer::GROUP_GENERIC] ) ) {
			throw new UnexpectedValueException( "Section '$section' has no hosts defined." );
		}

		return $this->newLoadBalancer(
			$section,
			array_merge(
				$this->serverTemplate,
				$this->templateOverridesBySection[$section] ?? []
			),
			$this->groupLoadsBySection[$section],
			// Use the LB-specific read-only reason if everything isn't already read-only
			is_string( $this->readOnlyReason )
				? $this->readOnlyReason
				: ( $this->readOnlyBySection[$section] ?? false )
		);
	}

	public function getMainLB( $domain = false ): ILoadBalancer {
		$domainInstance = $this->resolveDomainInstance( $domain );
		$section = $this->getSectionFromDatabase( $domainInstance->getDatabase() );

		if ( !isset( $this->mainLBs[$section] ) ) {
			$this->mainLBs[$section] = $this->newMainLB( $domain );
		}

		return $this->mainLBs[$section];
	}

	public function newExternalLB( $cluster ): ILoadBalancerForOwner {
		if ( !isset( $this->externalLoadsByCluster[$cluster] ) ) {
			throw new InvalidArgumentException( "Unknown cluster '$cluster'" );
		}
		return $this->newLoadBalancer(
			$cluster,
			array_merge(
				$this->serverTemplate,
				$this->externalTemplateOverrides,
				$this->templateOverridesByCluster[$cluster] ?? []
			),
			[ ILoadBalancer::GROUP_GENERIC => $this->externalLoadsByCluster[$cluster] ],
			$this->readOnlyReason
		);
	}

	public function getExternalLB( $cluster ): ILoadBalancer {
		if ( !isset( $this->externalLBs[$cluster] ) ) {
			$this->externalLBs[$cluster] = $this->newExternalLB(
				$cluster
			);
		}

		return $this->externalLBs[$cluster];
	}

	public function getAllMainLBs(): array {
		$lbs = [];
		foreach ( $this->sectionsByDB as $db => $section ) {
			if ( !isset( $lbs[$section] ) ) {
				$lbs[$section] = $this->getMainLB( $db );
			}
		}

		return $lbs;
	}

	public function getAllExternalLBs(): array {
		$lbs = [];
		foreach ( $this->externalLoadsByCluster as $cluster => $unused ) {
			$lbs[$cluster] = $this->getExternalLB( $cluster );
		}

		return $lbs;
	}

	public function forEachLB( $callback, array $params = [] ) {
		wfDeprecated( __METHOD__, '1.39' );
		foreach ( $this->mainLBs as $lb ) {
			$callback( $lb, ...$params );
		}
		foreach ( $this->externalLBs as $lb ) {
			$callback( $lb, ...$params );
		}
	}

	protected function getLBsForOwner() {
		foreach ( $this->mainLBs as $lb ) {
			yield $lb;
		}
		foreach ( $this->externalLBs as $lb ) {
			yield $lb;
		}
	}

	/**
	 * Make a new load balancer object based on template and load array
	 *
	 * @param string $clusterName
	 * @param array $serverTemplate
	 * @param array $groupLoads
	 * @param string|false $readOnlyReason
	 * @return LoadBalancer
	 */
	private function newLoadBalancer(
		string $clusterName,
		array $serverTemplate,
		array $groupLoads,
		$readOnlyReason
	) {
		$lb = new LoadBalancer( array_merge(
			$this->baseLoadBalancerParams(),
			[
				'servers' => $this->makeServerConfigArrays( $serverTemplate, $groupLoads ),
				'loadMonitor' => $this->loadMonitorConfig,
				'readOnlyReason' => $readOnlyReason,
				'clusterName' => $clusterName
			]
		) );
		$this->initLoadBalancer( $lb );

		return $lb;
	}

	/**
	 * Make a server array as expected by LoadBalancer::__construct()
	 *
	 * @param array $serverTemplate Server config map
	 * @param int[][] $groupLoads Map of (group => server name => load)
	 * @return array[] List of server config maps
	 */
	private function makeServerConfigArrays( array $serverTemplate, array $groupLoads ) {
		// The primary DB server is the first host explicitly listed in the generic load group
		if ( !$groupLoads[ILoadBalancer::GROUP_GENERIC] ) {
			throw new UnexpectedValueException( "Empty generic load array; no primary DB defined." );
		}
		$groupLoadsByServerName = $this->reindexGroupLoadsByServerName( $groupLoads );
		// Get the ordered map of (server name => load); the primary DB server is first
		$genericLoads = $groupLoads[ILoadBalancer::GROUP_GENERIC];
		// Implicitly append any hosts that only appear in custom load groups
		$genericLoads += array_fill_keys( array_keys( $groupLoadsByServerName ), 0 );
		$servers = [];
		foreach ( $genericLoads as $serverName => $load ) {
			$servers[] = array_merge(
				$serverTemplate,
				$servers ? [] : $this->masterTemplateOverrides,
				$this->templateOverridesByServer[$serverName] ?? [],
				[
					'host' => $this->hostsByServerName[$serverName] ?? $serverName,
					'serverName' => $serverName,
					'load' => $load,
					'groupLoads' => $groupLoadsByServerName[$serverName] ?? []
				]
			);
		}

		return $servers;
	}

	/**
	 * Take a group load array indexed by (group,server) and reindex it by (server,group)
	 *
	 * @param int[][] $groupLoads Map of (group => server name => load)
	 * @return int[][] Map of (server name => group => load)
	 */
	private function reindexGroupLoadsByServerName( array $groupLoads ) {
		$groupLoadsByServerName = [];
		foreach ( $groupLoads as $group => $loadByServerName ) {
			foreach ( $loadByServerName as $serverName => $load ) {
				$groupLoadsByServerName[$serverName][$group] = $load;
			}
		}

		return $groupLoadsByServerName;
	}

	/**
	 * @param string $database
	 * @return string Section name
	 */
	private function getSectionFromDatabase( $database ) {
		return $this->sectionsByDB[$database] ?? self::CLUSTER_MAIN_DEFAULT;
	}
}
