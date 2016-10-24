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

/**
 * A multi-database, multi-master factory for Wikimedia and similar installations.
 * Ignores the old configuration globals.
 *
 * @ingroup Database
 */
class LBFactoryMulti extends LBFactory {
	/** @var array A map of database names to section names */
	private $sectionsByDB;

	/**
	 * @var array A 2-d map. For each section, gives a map of server names to
	 * load ratios
	 */
	private $sectionLoads;

	/**
	 * @var array[] Server info associative array
	 * @note The host, hostName and load entries will be overridden
	 */
	private $serverTemplate;

	// Optional settings

	/** @var array A 3-d map giving server load ratios for each section and group */
	private $groupLoadsBySection = [];

	/** @var array A 3-d map giving server load ratios by DB name */
	private $groupLoadsByDB = [];

	/** @var array A map of hostname to IP address */
	private $hostsByName = [];

	/** @var array A map of external storage cluster name to server load map */
	private $externalLoads = [];

	/**
	 * @var array A set of server info keys overriding serverTemplate for
	 * external storage
	 */
	private $externalTemplateOverrides;

	/**
	 * @var array A 2-d map overriding serverTemplate and
	 * externalTemplateOverrides on a server-by-server basis. Applies to both
	 * core and external storage
	 */
	private $templateOverridesByServer;

	/** @var array A 2-d map overriding the server info by section */
	private $templateOverridesBySection;

	/** @var array A 2-d map overriding the server info by external storage cluster */
	private $templateOverridesByCluster;

	/** @var array An override array for all master servers */
	private $masterTemplateOverrides;

	/**
	 * @var array|bool A map of section name to read-only message. Missing or
	 * false for read/write
	 */
	private $readOnlyBySection = [];

	/** @var array Load balancer factory configuration */
	private $conf;

	/** @var LoadBalancer[] */
	private $mainLBs = [];

	/** @var LoadBalancer[] */
	private $extLBs = [];

	/** @var string */
	private $loadMonitorClass = 'LoadMonitor';

	/** @var string */
	private $lastDomain;

	/** @var string */
	private $lastSection;

	/**
	 * @see LBFactory::__construct()
	 *
	 * Template override precedence (highest => lowest):
	 *   - templateOverridesByServer
	 *   - masterTemplateOverrides
	 *   - templateOverridesBySection/templateOverridesByCluster
	 *   - externalTemplateOverrides
	 *   - serverTemplate
	 * Overrides only work on top level keys (so nested values will not be merged).
	 *
	 * Server configuration maps should be of the format Database::factory() requires.
	 * Additionally, a 'max lag' key should also be set on server maps, indicating how stale the
	 * data can be before the load balancer tries to avoid using it. The map can have 'is static'
	 * set to disable blocking  replication sync checks (intended for archive servers with
	 * unchanging data).
	 *
	 * @param array $conf Parameters of LBFactory::__construct() as well as:
	 *   - sectionsByDB                Map of database names to section names.
	 *   - sectionLoads                2-d map. For each section, gives a map of server names to
	 *                                 load ratios. For example:
	 *                                 [
	 *                                     'section1' => [
	 *                                         'db1' => 100,
	 *                                         'db2' => 100
	 *                                     ]
	 *                                 ]
	 *   - serverTemplate              Server configuration map intended for Database::factory().
	 *                                 Note that "host", "hostName" and "load" entries will be
	 *                                 overridden by "sectionLoads" and "hostsByName".
	 *   - groupLoadsBySection         3-d map giving server load ratios for each section/group.
	 *                                 For example:
	 *                                 [
	 *                                     'section1' => [
	 *                                         'group1' => [
	 *                                             'db1' => 100,
	 *                                             'db2' => 100
	 *                                         ]
	 *                                     ]
	 *                                 ]
	 *   - groupLoadsByDB              3-d map giving server load ratios by DB name.
	 *   - hostsByName                 Map of hostname to IP address.
	 *   - externalLoads               Map of external storage cluster name to server load map.
	 *   - externalTemplateOverrides   Set of server configuration maps overriding
	 *                                 "serverTemplate" for external storage.
	 *   - templateOverridesByServer   2-d map overriding "serverTemplate" and
	 *                                 "externalTemplateOverrides" on a server-by-server basis.
	 *                                 Applies to both core and external storage.
	 *   - templateOverridesBySection  2-d map overriding the server configuration maps by section.
	 *   - templateOverridesByCluster  2-d map overriding the server configuration maps by external
	 *                                 storage cluster.
	 *   - masterTemplateOverrides     Server configuration map overrides for all master servers.
	 *   - loadMonitorClass            Name of the LoadMonitor class to always use.
	 *   - readOnlyBySection           A map of section name to read-only message.
	 *                                 Missing or false for read/write.
	 */
	public function __construct( array $conf ) {
		parent::__construct( $conf );

		$this->conf = $conf;
		$required = [ 'sectionsByDB', 'sectionLoads', 'serverTemplate' ];
		$optional = [ 'groupLoadsBySection', 'groupLoadsByDB', 'hostsByName',
			'externalLoads', 'externalTemplateOverrides', 'templateOverridesByServer',
			'templateOverridesByCluster', 'templateOverridesBySection', 'masterTemplateOverrides',
			'readOnlyBySection', 'loadMonitorClass' ];

		foreach ( $required as $key ) {
			if ( !isset( $conf[$key] ) ) {
				throw new InvalidArgumentException( __CLASS__ . ": $key is required." );
			}
			$this->$key = $conf[$key];
		}

		foreach ( $optional as $key ) {
			if ( isset( $conf[$key] ) ) {
				$this->$key = $conf[$key];
			}
		}
	}

	/**
	 * @param bool|string $domain
	 * @return string
	 */
	private function getSectionForDomain( $domain = false ) {
		if ( $this->lastDomain === $domain ) {
			return $this->lastSection;
		}
		list( $dbName, ) = $this->getDBNameAndPrefix( $domain );
		if ( isset( $this->sectionsByDB[$dbName] ) ) {
			$section = $this->sectionsByDB[$dbName];
		} else {
			$section = 'DEFAULT';
		}
		$this->lastSection = $section;
		$this->lastDomain = $domain;

		return $section;
	}

	/**
	 * @param bool|string $domain
	 * @return LoadBalancer
	 */
	public function newMainLB( $domain = false ) {
		list( $dbName, ) = $this->getDBNameAndPrefix( $domain );
		$section = $this->getSectionForDomain( $domain );
		if ( isset( $this->groupLoadsByDB[$dbName] ) ) {
			$groupLoads = $this->groupLoadsByDB[$dbName];
		} else {
			$groupLoads = [];
		}

		if ( isset( $this->groupLoadsBySection[$section] ) ) {
			$groupLoads = array_merge_recursive(
				$groupLoads, $this->groupLoadsBySection[$section] );
		}

		$readOnlyReason = $this->readOnlyReason;
		// Use the LB-specific read-only reason if everything isn't already read-only
		if ( $readOnlyReason === false && isset( $this->readOnlyBySection[$section] ) ) {
			$readOnlyReason = $this->readOnlyBySection[$section];
		}

		$template = $this->serverTemplate;
		if ( isset( $this->templateOverridesBySection[$section] ) ) {
			$template = $this->templateOverridesBySection[$section] + $template;
		}

		return $this->newLoadBalancer(
			$template,
			$this->sectionLoads[$section],
			$groupLoads,
			$readOnlyReason
		);
	}

	/**
	 * @param DatabaseDomain|string|bool $domain Domain ID, or false for the current domain
	 * @return LoadBalancer
	 */
	public function getMainLB( $domain = false ) {
		$section = $this->getSectionForDomain( $domain );
		if ( !isset( $this->mainLBs[$section] ) ) {
			$lb = $this->newMainLB( $domain );
			$this->getChronologyProtector()->initLB( $lb );
			$this->mainLBs[$section] = $lb;
		}

		return $this->mainLBs[$section];
	}

	public function newExternalLB( $cluster ) {
		if ( !isset( $this->externalLoads[$cluster] ) ) {
			throw new InvalidArgumentException( __METHOD__ . ": Unknown cluster \"$cluster\"" );
		}
		$template = $this->serverTemplate;
		if ( isset( $this->externalTemplateOverrides ) ) {
			$template = $this->externalTemplateOverrides + $template;
		}
		if ( isset( $this->templateOverridesByCluster[$cluster] ) ) {
			$template = $this->templateOverridesByCluster[$cluster] + $template;
		}

		return $this->newLoadBalancer(
			$template,
			$this->externalLoads[$cluster],
			[],
			$this->readOnlyReason
		);
	}

	public function getExternalLB( $cluster ) {
		if ( !isset( $this->extLBs[$cluster] ) ) {
			$this->extLBs[$cluster] = $this->newExternalLB( $cluster );
			$this->getChronologyProtector()->initLB( $this->extLBs[$cluster] );
		}

		return $this->extLBs[$cluster];
	}

	/**
	 * Make a new load balancer object based on template and load array
	 *
	 * @param array $template
	 * @param array $loads
	 * @param array $groupLoads
	 * @param string|bool $readOnlyReason
	 * @return LoadBalancer
	 */
	private function newLoadBalancer( $template, $loads, $groupLoads, $readOnlyReason ) {
		$lb = new LoadBalancer( array_merge(
			$this->baseLoadBalancerParams(),
			[
				'servers' => $this->makeServerArray( $template, $loads, $groupLoads ),
				'loadMonitor' => [ 'class' => $this->loadMonitorClass ],
				'readOnlyReason' => $readOnlyReason
			]
		) );
		$this->initLoadBalancer( $lb );

		return $lb;
	}

	/**
	 * Make a server array as expected by LoadBalancer::__construct, using a template and load array
	 *
	 * @param array $template
	 * @param array $loads
	 * @param array $groupLoads
	 * @return array
	 */
	private function makeServerArray( $template, $loads, $groupLoads ) {
		$servers = [];
		$master = true;
		$groupLoadsByServer = $this->reindexGroupLoads( $groupLoads );
		foreach ( $groupLoadsByServer as $server => $stuff ) {
			if ( !isset( $loads[$server] ) ) {
				$loads[$server] = 0;
			}
		}
		foreach ( $loads as $serverName => $load ) {
			$serverInfo = $template;
			if ( $master ) {
				$serverInfo['master'] = true;
				if ( isset( $this->masterTemplateOverrides ) ) {
					$serverInfo = $this->masterTemplateOverrides + $serverInfo;
				}
				$master = false;
			} else {
				$serverInfo['replica'] = true;
			}
			if ( isset( $this->templateOverridesByServer[$serverName] ) ) {
				$serverInfo = $this->templateOverridesByServer[$serverName] + $serverInfo;
			}
			if ( isset( $groupLoadsByServer[$serverName] ) ) {
				$serverInfo['groupLoads'] = $groupLoadsByServer[$serverName];
			}
			if ( isset( $this->hostsByName[$serverName] ) ) {
				$serverInfo['host'] = $this->hostsByName[$serverName];
			} else {
				$serverInfo['host'] = $serverName;
			}
			$serverInfo['hostName'] = $serverName;
			$serverInfo['load'] = $load;
			$serverInfo += [ 'flags' => IDatabase::DBO_DEFAULT ];

			$servers[] = $serverInfo;
		}

		return $servers;
	}

	/**
	 * Take a group load array indexed by group then server, and reindex it by server then group
	 * @param array $groupLoads
	 * @return array
	 */
	private function reindexGroupLoads( $groupLoads ) {
		$reindexed = [];
		foreach ( $groupLoads as $group => $loads ) {
			foreach ( $loads as $server => $load ) {
				$reindexed[$server][$group] = $load;
			}
		}

		return $reindexed;
	}

	/**
	 * @param DatabaseDomain|string|bool $domain Domain ID, or false for the current domain
	 * @return array [database name, table prefix]
	 */
	private function getDBNameAndPrefix( $domain = false ) {
		$domain = ( $domain === false )
			? $this->localDomain
			: DatabaseDomain::newFromId( $domain );

		return [ $domain->getDatabase(), $domain->getTablePrefix() ];
	}

	/**
	 * Execute a function for each tracked load balancer
	 * The callback is called with the load balancer as the first parameter,
	 * and $params passed as the subsequent parameters.
	 * @param callable $callback
	 * @param array $params
	 */
	public function forEachLB( $callback, array $params = [] ) {
		foreach ( $this->mainLBs as $lb ) {
			call_user_func_array( $callback, array_merge( [ $lb ], $params ) );
		}
		foreach ( $this->extLBs as $lb ) {
			call_user_func_array( $callback, array_merge( [ $lb ], $params ) );
		}
	}
}
