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
 * LoadBalancer manager for sites with several "main" database clusters
 *
 * Each database cluster consists of a "primary" server and any number of replica servers,
 * all of which converge, as soon as possible, to contain the same schemas and records. If
 * a replication topology has multiple primaries, then the "primary" is merely the preferred
 * co-primary for the current context (e.g. datacenter).
 *
 * For single-primary topologies, the schemas and records of the primary define the "dataset".
 * For multiple-primary topologies, the "dataset" is the convergent result of applying/merging
 * all committed events (regardless of the co-primary they originated on); it possible that no
 * co-primary has yet converged upon this state at any given time (especially when there are
 * frequent writes and co-primaries are geographically distant).
 *
 * A "main" cluster contain a "main" dataset, which consists of data that is compact, highly
 * relational (e.g. read by JOIN queries), and essential to one or more sites. The "external"
 * clusters each store an "external" dataset, which consists of data that is non-relational
 * (e.g. key/value pairs), self-contained (e.g. JOIN queries and transactions thereof never
 * involve a main dataset), or too bulky to reside in a main dataset (e.g. text blobs).
 *
 * The class allows for large site farms to split up their data in the following ways:
 *   - Vertically shard compact site-specific data by site (e.g. page/comment metadata)
 *   - Vertically shard compact global data by module (e.g. account/notification data)
 *   - Horizontally shard any bulk data by blob key (e.g. page/comment content blobs)
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
	/** @var DatabaseDomain[] Map of (domain ID => domain instance) */
	private $nonLocalDomainCache = [];

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
	 *      interpreted as a catch-all for all databases not otherwise mentioned. If no section
	 *      name is specified for "DEFAULT", then the catch-all section is assumed to be named
	 *      "DEFAULT". [optional]
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
		$this->sectionsByDB += [ self::CLUSTER_MAIN_DEFAULT => self::CLUSTER_MAIN_DEFAULT ];
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

		foreach ( $this->externalLoadsByCluster as $cluster => $_ ) {
			if ( isset( $this->groupLoadsBySection[$cluster] ) ) {
				throw new LogicException(
					"External cluster '$cluster' has the same name as a main section/cluster"
				);
			}
		}
	}

	/** @inheritDoc */
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

	/**
	 * @param DatabaseDomain|string|false $domain
	 * @return DatabaseDomain
	 */
	private function resolveDomainInstance( $domain ) {
		if ( $domain instanceof DatabaseDomain ) {
			return $domain; // already a domain instance
		} elseif ( $domain === false || $domain === $this->localDomain->getId() ) {
			return $this->localDomain;
		} elseif ( isset( $this->domainAliases[$domain] ) ) {
			// This array acts as both the original map and as instance cache.
			// Instances pass-through DatabaseDomain::newFromId as-is.
			$this->domainAliases[$domain] =
				DatabaseDomain::newFromId( $this->domainAliases[$domain] );

			return $this->domainAliases[$domain];
		}

		$cachedDomain = $this->nonLocalDomainCache[$domain] ?? null;
		if ( $cachedDomain === null ) {
			$cachedDomain = DatabaseDomain::newFromId( $domain );
			$this->nonLocalDomainCache = [ $domain => $cachedDomain ];
		}

		return $cachedDomain;
	}

	/** @inheritDoc */
	public function getMainLB( $domain = false ): ILoadBalancer {
		$domainInstance = $this->resolveDomainInstance( $domain );
		$section = $this->getSectionFromDatabase( $domainInstance->getDatabase() );

		if ( !isset( $this->mainLBs[$section] ) ) {
			$this->mainLBs[$section] = $this->newMainLB( $domain );
		}

		return $this->mainLBs[$section];
	}

	/** @inheritDoc */
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

	/** @inheritDoc */
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

	/** @inheritDoc */
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
		$groupLoadsByServerName = [];
		foreach ( $groupLoads as $group => $loadByServerName ) {
			foreach ( $loadByServerName as $serverName => $load ) {
				$groupLoadsByServerName[$serverName][$group] = $load;
			}
		}

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
	 * @param string $database
	 * @return string Main section name
	 */
	private function getSectionFromDatabase( $database ) {
		return $this->sectionsByDB[$database]
			?? $this->sectionsByDB[self::CLUSTER_MAIN_DEFAULT]
			?? self::CLUSTER_MAIN_DEFAULT;
	}

	public function reconfigure( array $conf ): void {
		if ( !$conf ) {
			return;
		}

		foreach ( $this->mainLBs as $lb ) {
			// Approximate what LBFactoryMulti::__construct does (T346365)
			$groupLoads = $conf['groupLoadsBySection'][$lb->getClusterName()] ?? [];
			$groupLoads[ILoadBalancer::GROUP_GENERIC] = $conf['sectionLoads'][$lb->getClusterName()];
			$config = [
				'servers' => $this->makeServerConfigArrays( $conf['serverTemplate'] ?? [], $groupLoads )
			];
			$lb->reconfigure( $config );

		}
		foreach ( $this->externalLBs as $lb ) {
			$groupLoads = [
				ILoadBalancer::GROUP_GENERIC => $conf['externalLoads'][$lb->getClusterName()]
			];
			$config = [
				'servers' => $this->makeServerConfigArrays( $conf['serverTemplate'] ?? [], $groupLoads )
			];
			$lb->reconfigure( $config );
		}
	}
}
