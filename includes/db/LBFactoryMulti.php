<?php
/**
 * Advanced generator of database load balancing objects for wiki farms.
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
 * A multi-wiki, multi-master factory for Wikimedia and similar installations.
 * Ignores the old configuration globals
 *
 * Configuration:
 *     sectionsByDB                A map of database names to section names.
 *
 *     sectionLoads                A 2-d map. For each section, gives a map of server names to
 *                                 load ratios. For example:
 *                                 array(
 *                                     'section1' => array(
 *                                         'db1' => 100,
 *                                         'db2' => 100
 *                                     )
 *                                 )
 *
 *     serverTemplate              A server info associative array as documented for $wgDBservers.
 *                                 The host, hostName and load entries will be overridden.
 *
 *     groupLoadsBySection         A 3-d map giving server load ratios for each section and group.
 *                                 For example:
 *                                 array(
 *                                     'section1' => array(
 *                                         'group1' => array(
 *                                             'db1' => 100,
 *                                             'db2' => 100
 *                                         )
 *                                     )
 *                                 )
 *
 *     groupLoadsByDB              A 3-d map giving server load ratios by DB name.
 *
 *     hostsByName                 A map of hostname to IP address.
 *
 *     externalLoads               A map of external storage cluster name to server load map.
 *
 *     externalTemplateOverrides   A set of server info keys overriding serverTemplate for external
 *                                 storage.
 *
 *     templateOverridesByServer   A 2-d map overriding serverTemplate and
 *                                 externalTemplateOverrides on a server-by-server basis. Applies
 *                                 to both core and external storage.
 *
 *     templateOverridesByCluster  A 2-d map overriding the server info by external storage cluster.
 *
 *     masterTemplateOverrides     An override array for all master servers.
 *
 *     readOnlyBySection           A map of section name to read-only message.
 *                                 Missing or false for read/write.
 *
 * @ingroup Database
 */
class LBFactoryMulti extends LBFactory {
	// Required settings

	/** @var array A map of database names to section names */
	private $sectionsByDB;

	/**
	 * @var array A 2-d map. For each section, gives a map of server names to
	 * load ratios
	 */
	private $sectionLoads;

	/**
	 * @var array A server info associative array as documented for
	 * $wgDBservers. The host, hostName and load entries will be
	 * overridden
	 */
	private $serverTemplate;

	// Optional settings

	/** @var array A 3-d map giving server load ratios for each section and group */
	private $groupLoadsBySection = array();

	/** @var array A 3-d map giving server load ratios by DB name */
	private $groupLoadsByDB = array();

	/** @var array A map of hostname to IP address */
	private $hostsByName = array();

	/** @var array A map of external storage cluster name to server load map */
	private $externalLoads = array();

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

	/** @var array A 2-d map overriding the server info by external storage cluster */
	private $templateOverridesByCluster;

	/** @var array An override array for all master servers */
	private $masterTemplateOverrides;

	/**
	 * @var array|bool A map of section name to read-only message. Missing or
	 * false for read/write
	 */
	private $readOnlyBySection = array();

	// Other stuff

	/** @var array Load balancer factory configuration */
	private $conf;

	/** @var LoadBalancer[] */
	private $mainLBs = array();

	/** @var LoadBalancer[] */
	private $extLBs = array();

	/** @var string */
	private $lastWiki;

	/** @var string */
	private $lastSection;

	/**
	 * @param array $conf
	 * @throws MWException
	 */
	public function __construct( array $conf ) {
		$this->chronProt = new ChronologyProtector;
		$this->conf = $conf;
		$required = array( 'sectionsByDB', 'sectionLoads', 'serverTemplate' );
		$optional = array( 'groupLoadsBySection', 'groupLoadsByDB', 'hostsByName',
			'externalLoads', 'externalTemplateOverrides', 'templateOverridesByServer',
			'templateOverridesByCluster', 'masterTemplateOverrides',
			'readOnlyBySection' );

		foreach ( $required as $key ) {
			if ( !isset( $conf[$key] ) ) {
				throw new MWException( __CLASS__ . ": $key is required in configuration" );
			}
			$this->$key = $conf[$key];
		}

		foreach ( $optional as $key ) {
			if ( isset( $conf[$key] ) ) {
				$this->$key = $conf[$key];
			}
		}

		// Check for read-only mode
		$section = $this->getSectionForWiki();
		if ( !empty( $this->readOnlyBySection[$section] ) ) {
			global $wgReadOnly;
			$wgReadOnly = $this->readOnlyBySection[$section];
		}
	}

	/**
	 * @param bool|string $wiki
	 * @return string
	 */
	private function getSectionForWiki( $wiki = false ) {
		if ( $this->lastWiki === $wiki ) {
			return $this->lastSection;
		}
		list( $dbName, ) = $this->getDBNameAndPrefix( $wiki );
		if ( isset( $this->sectionsByDB[$dbName] ) ) {
			$section = $this->sectionsByDB[$dbName];
		} else {
			$section = 'DEFAULT';
		}
		$this->lastSection = $section;
		$this->lastWiki = $wiki;

		return $section;
	}

	/**
	 * @param bool|string $wiki
	 * @return LoadBalancer
	 */
	public function newMainLB( $wiki = false ) {
		list( $dbName, ) = $this->getDBNameAndPrefix( $wiki );
		$section = $this->getSectionForWiki( $wiki );
		$groupLoads = array();
		if ( isset( $this->groupLoadsByDB[$dbName] ) ) {
			$groupLoads = $this->groupLoadsByDB[$dbName];
		}

		if ( isset( $this->groupLoadsBySection[$section] ) ) {
			$groupLoads = array_merge_recursive( $groupLoads, $this->groupLoadsBySection[$section] );
		}

		return $this->newLoadBalancer(
			$this->serverTemplate,
			$this->sectionLoads[$section],
			$groupLoads
		);
	}

	/**
	 * @param bool|string $wiki
	 * @return LoadBalancer
	 */
	public function getMainLB( $wiki = false ) {
		$section = $this->getSectionForWiki( $wiki );
		if ( !isset( $this->mainLBs[$section] ) ) {
			$lb = $this->newMainLB( $wiki, $section );
			$lb->parentInfo( array( 'id' => "main-$section" ) );
			$this->chronProt->initLB( $lb );
			$this->mainLBs[$section] = $lb;
		}

		return $this->mainLBs[$section];
	}

	/**
	 * @param string $cluster
	 * @param bool|string $wiki
	 * @throws MWException
	 * @return LoadBalancer
	 */
	protected function newExternalLB( $cluster, $wiki = false ) {
		if ( !isset( $this->externalLoads[$cluster] ) ) {
			throw new MWException( __METHOD__ . ": Unknown cluster \"$cluster\"" );
		}
		$template = $this->serverTemplate;
		if ( isset( $this->externalTemplateOverrides ) ) {
			$template = $this->externalTemplateOverrides + $template;
		}
		if ( isset( $this->templateOverridesByCluster[$cluster] ) ) {
			$template = $this->templateOverridesByCluster[$cluster] + $template;
		}

		return $this->newLoadBalancer( $template, $this->externalLoads[$cluster], array() );
	}

	/**
	 * @param string $cluster External storage cluster, or false for core
	 * @param bool|string $wiki Wiki ID, or false for the current wiki
	 * @return LoadBalancer
	 */
	public function &getExternalLB( $cluster, $wiki = false ) {
		if ( !isset( $this->extLBs[$cluster] ) ) {
			$this->extLBs[$cluster] = $this->newExternalLB( $cluster, $wiki );
			$this->extLBs[$cluster]->parentInfo( array( 'id' => "ext-$cluster" ) );
			$this->chronProt->initLB( $this->extLBs[$cluster] );
		}

		return $this->extLBs[$cluster];
	}

	/**
	 * Make a new load balancer object based on template and load array
	 *
	 * @param array $template
	 * @param array $loads
	 * @param array $groupLoads
	 * @return LoadBalancer
	 */
	private function newLoadBalancer( $template, $loads, $groupLoads ) {
		$servers = $this->makeServerArray( $template, $loads, $groupLoads );
		$lb = new LoadBalancer( array(
			'servers' => $servers,
		) );

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
		$servers = array();
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
		$reindexed = array();
		foreach ( $groupLoads as $group => $loads ) {
			foreach ( $loads as $server => $load ) {
				$reindexed[$server][$group] = $load;
			}
		}

		return $reindexed;
	}

	/**
	 * Get the database name and prefix based on the wiki ID
	 * @param bool|string $wiki
	 * @return array
	 */
	private function getDBNameAndPrefix( $wiki = false ) {
		if ( $wiki === false ) {
			global $wgDBname, $wgDBprefix;

			return array( $wgDBname, $wgDBprefix );
		} else {
			return wfSplitWikiID( $wiki );
		}
	}

	/**
	 * Execute a function for each tracked load balancer
	 * The callback is called with the load balancer as the first parameter,
	 * and $params passed as the subsequent parameters.
	 * @param callable $callback
	 * @param array $params
	 */
	public function forEachLB( $callback, array $params = array() ) {
		foreach ( $this->mainLBs as $lb ) {
			call_user_func_array( $callback, array_merge( array( $lb ), $params ) );
		}
		foreach ( $this->extLBs as $lb ) {
			call_user_func_array( $callback, array_merge( array( $lb ), $params ) );
		}
	}

	public function shutdown() {
		foreach ( $this->mainLBs as $lb ) {
			$this->chronProt->shutdownLB( $lb );
		}
		foreach ( $this->extLBs as $extLB ) {
			$this->chronProt->shutdownLB( $extLB );
		}
		$this->chronProt->shutdown();
		$this->commitMasterChanges();
	}
}
