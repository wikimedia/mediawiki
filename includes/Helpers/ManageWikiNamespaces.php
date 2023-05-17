<?php

namespace Miraheze\ManageWiki\Helpers;

use Config;
use MediaWiki\MediaWikiServices;
use Miraheze\CreateWiki\CreateWikiJson;
use Miraheze\ManageWiki\Jobs\NamespaceMigrationJob;
use SpecialPage;
use Wikimedia\Rdbms\MaintainableDBConnRef;

/**
 * Handler for interacting with Namespace configuration
 */
class ManageWikiNamespaces {
	/** @var bool Whether changes are committed to the database */
	private $committed = false;
	/** @var Config Configuration object */
	private $config;
	/** @var MaintainableDBConnRef Database connection */
	private $dbw;
	/** @var array Namespace IDs to be deleted */
	private $deleteNamespaces = [];
	/** @var array Known namespaces configuration */
	private $liveNamespaces = [];
	/** @var string WikiID */
	private $wiki;

	/** @var array Changes to be committed */
	public $changes = [];

	/** @var array Errors */
	public $errors = [];
	/** @var string Log type */
	public $log = 'namespaces';
	/** @var array Log parameters */
	public $logParams = [];

	/**
	 * ManageWikiNamespaces constructor.
	 * @param string $wiki WikiID
	 */
	public function __construct( string $wiki ) {
		$this->wiki = $wiki;
		$this->config = MediaWikiServices::getInstance()->getConfigFactory()->makeConfig( 'managewiki' );

		$this->dbw = MediaWikiServices::getInstance()->getDBLoadBalancerFactory()
			->getMainLB( $this->config->get( 'CreateWikiDatabase' ) )
			->getMaintenanceConnectionRef( DB_PRIMARY, [], $this->config->get( 'CreateWikiDatabase' ) );

		$namespaces = $this->dbw->select(
			'mw_namespaces',
			'*',
			[
				'ns_dbname' => $wiki
			]
		);

		// Bring database values to class scope
		foreach ( $namespaces as $ns ) {
			$this->liveNamespaces[$ns->ns_namespace_id] = [
				'name' => $ns->ns_namespace_name,
				'searchable' => $ns->ns_searchable,
				'subpages' => $ns->ns_subpages,
				'content' => $ns->ns_content,
				'contentmodel' => $ns->ns_content_model,
				'protection' => $ns->ns_protection,
				'aliases' => json_decode( $ns->ns_aliases, true ),
				'core' => $ns->ns_core,
				'additional' => json_decode( $ns->ns_additional, true )
			];
		}
	}

	/**
	 * Lists either all namespaces or a specific one
	 * @param int|null $id Namespace ID wanted (null for all)
	 * @return array Namespace configuration
	 */
	public function list( int $id = null ) {
		if ( $id === null ) {
			return $this->liveNamespaces;
		} else {
			return $this->liveNamespaces[$id] ?? [
					'name' => null,
					'searchable' => 0,
					'subpages' => 0,
					'content' => 0,
					'contentmodel' => 'wikitext',
					'protection' => '',
					'aliases' => [],
					'core' => 0,
					'additional' => []
				];
		}
	}

	/**
	 * Modify a namespace handler
	 * @param int $id Namespace ID
	 * @param array $data Overriding information about the namespace
	 * @param bool $maintainPrefix|false
	 */
	public function modify( int $id, array $data, bool $maintainPrefix = false ) {
		$excluded = array_map( 'strtolower', $this->config->get( 'ManageWikiNamespacesDisallowedNames' ) );
		if ( in_array( strtolower( $data['name'] ), $excluded ) ) {
			$this->errors[] = [
				'managewiki-error-disallowednamespace' => [
					$data['name']
				]
			];
		}

		// We will handle all processing in final stages
		$nsData = [
			'name' => $this->liveNamespaces[$id]['name'] ?? null,
			'searchable' => $this->liveNamespaces[$id]['searchable'] ?? 0,
			'subpages' => $this->liveNamespaces[$id]['subpages'] ?? 0,
			'content' => $this->liveNamespaces[$id]['content'] ?? 0,
			'contentmodel' => $this->liveNamespaces[$id]['contentmodel'] ?? 'wikitext',
			'protection' => $this->liveNamespaces[$id]['protection'] ?? '',
			'aliases' => $this->liveNamespaces[$id]['aliases'] ?? [],
			'core' => $this->liveNamespaces[$id]['core'] ?? 0,
			'additional' => $this->liveNamespaces[$id]['additional'] ?? [],
			'maintainprefix' => $maintainPrefix
		];

		// Overwrite the defaults above with our new modified values
		foreach ( $data as $name => $value ) {
			if ( $nsData[$name] != $value ) {
				$this->changes[$id][$name] = [
					'old' => $nsData[$name],
					'new' => $value
				];

				$nsData[$name] = $value;
			}
		}

		$this->liveNamespaces[$id] = $nsData;
	}

	/**
	 * Remove a namespace
	 * @param int $id Namespace ID
	 * @param int $newNamespace Namespace ID to migrate to
	 * @param bool $maintainPrefix|false
	 */
	public function remove( int $id, int $newNamespace, bool $maintainPrefix = false ) {
		// Utilise changes differently in this case
		$this->changes[$id] = [
			'old' => [
				'name' => $this->liveNamespaces[$id]['name'],
			],
			'new' => [
				'name' => $newNamespace,
				'maintainprefix' => $maintainPrefix
			]
		];

		// We will handle all processing in final stages
		unset( $this->liveNamespaces[$id] );

		// Push to a deletion queue
		$this->deleteNamespaces[] = $id;
	}

	/**
	 * Commits all changes to database. Also files a job to move pages into or out of namespace
	 */
	public function commit() {
		foreach ( array_keys( $this->changes ) as $id ) {
			if ( in_array( $id, $this->deleteNamespaces ) ) {
				$this->log = 'namespaces-delete';

				if ( !( $id % 2 ) ) {
					$this->logParams = [
						'5::namespace' => $this->changes[$id]['old']['name']
					];
				}

				$this->dbw->delete(
					'mw_namespaces',
					[
						'ns_dbname' => $this->wiki,
						'ns_namespace_id' => $id
					]
				);

				$jobParams = [
					'action' => 'delete',
					'nsID' => $id,
					'nsName' => $this->changes[$id]['old']['name'],
					'nsNew' => $this->changes[$id]['new']['name'],
					'maintainPrefix' => $this->changes[$id]['new']['maintainprefix']
				];
			} else {
				$builtTable = [
					'ns_namespace_name' => $this->liveNamespaces[$id]['name'],
					'ns_searchable' => $this->liveNamespaces[$id]['searchable'],
					'ns_subpages' => $this->liveNamespaces[$id]['subpages'],
					'ns_content' => $this->liveNamespaces[$id]['content'],
					'ns_content_model' => $this->liveNamespaces[$id]['contentmodel'],
					'ns_protection' => $this->liveNamespaces[$id]['protection'],
					'ns_aliases' => json_encode( $this->liveNamespaces[$id]['aliases'] ),
					'ns_core' => $this->liveNamespaces[$id]['core'],
					'ns_additional' => json_encode( $this->liveNamespaces[$id]['additional'] )
				];

				$jobParams = [
					'action' => 'rename',
					'nsID' => $id,
					'nsName' => $this->liveNamespaces[$id]['name'],
					'maintainPrefix' => $this->liveNamespaces[$id]['maintainprefix'] ?? false
				];

				$this->dbw->upsert(
					'mw_namespaces',
					[
						'ns_dbname' => $this->wiki,
						'ns_namespace_id' => $id
					] + $builtTable,
					[
						[
							'ns_dbname',
							'ns_namespace_id'
						]
					],
					$builtTable
				);

				if ( empty( $this->logParams ) || !( $id % 2 ) ) {
					$this->logParams = [
						'5::namespace' => $this->liveNamespaces[$id]['name']
					];
				}
			}

			$job = new NamespaceMigrationJob( SpecialPage::getTitleFor( 'ManageWiki' ), $jobParams );
			MediaWikiServices::getInstance()->getJobQueueGroupFactory()->makeJobQueueGroup()->push( $job );
		}

		if ( $this->wiki != 'default' ) {
			$cWJ = new CreateWikiJson( $this->wiki );
			$cWJ->resetWiki();
		}
		$this->committed = true;
	}

	/**
	 * Checks if changes are committed to the database or not
	 */
	public function __destruct() {
		if ( !$this->committed && !empty( $this->changes ) ) {
			print 'Changes have not been committed to the database!';
		}
	}
}
