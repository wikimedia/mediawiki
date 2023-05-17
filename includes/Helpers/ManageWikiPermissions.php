<?php

namespace Miraheze\ManageWiki\Helpers;

use Config;
use MediaWiki\MediaWikiServices;
use Miraheze\CreateWiki\CreateWikiJson;
use User;
use Wikimedia\Rdbms\MaintainableDBConnRef;

/**
 * Handler for interacting with Permissions
 */
class ManageWikiPermissions {
	/** @var bool Whether changes are committed to the database */
	private $committed = false;
	/** @var Config Configuration object */
	private $config;
	/** @var MaintainableDBConnRef Database connection */
	private $dbw;
	/** @var array Deletion queue */
	private $deleteGroups = [];
	/** @var array Permissions configuration */
	private $livePermissions = [];
	/** @var string WikiID */
	private $wiki;

	/** @var array Changes to be committed */
	public $changes = [];
	/** @var array Errors */
	public $errors = [];
	/** @var string Log type */
	public $log = 'rights';
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

		$perms = $this->dbw->select(
			'mw_permissions',
			'*',
			[
				'perm_dbname' => $wiki
			]
		);

		// Bring database values to class scope
		foreach ( $perms as $perm ) {
			$this->livePermissions[$perm->perm_group] = [
				'permissions' => json_decode( $perm->perm_permissions, true ),
				'addgroups' => json_decode( $perm->perm_addgroups, true ),
				'removegroups' => json_decode( $perm->perm_removegroups, true ),
				'addself' => json_decode( $perm->perm_addgroupstoself, true ),
				'removeself' => json_decode( $perm->perm_removegroupsfromself, true ),
				'autopromote' => json_decode( $perm->perm_autopromote, true )
			];
		}
	}

	/**
	 * Lists either all groups or a specific one
	 * @param string|null $group Group wanted (null for all)
	 * @return array Group configuration
	 */
	public function list( string $group = null ) {
		if ( $group === null ) {
			return $this->livePermissions;
		} else {
			return $this->livePermissions[$group] ?? [
					'permissions' => [],
					'addgroups' => [],
					'removegroups' => [],
					'addself' => [],
					'removeself' => [],
					'autopromote' => null
				];
		}
	}

	/**
	 * Modify a group handler
	 * @param string $group Group name
	 * @param array $data Merging information about the group
	 */
	public function modify( string $group, array $data ) {
		// We will handle all processing in final stages
		$permData = [
			'permissions' => $this->livePermissions[$group]['permissions'] ?? [],
			'addgroups' => $this->livePermissions[$group]['addgroups'] ?? [],
			'removegroups' => $this->livePermissions[$group]['removegroups'] ?? [],
			'addself' => $this->livePermissions[$group]['addself'] ?? [],
			'removeself' => $this->livePermissions[$group]['removeself'] ?? [],
			'autopromote' => $this->livePermissions[$group]['autopromote'] ?? null
		];

		// Overwrite the defaults above with our new modified values
		foreach ( $data as $name => $array ) {
			if ( $name != 'autopromote' ) {
				foreach ( $array as $type => $value ) {
					$permData[$name] = ( $type == 'add' ) ? array_merge( $permData[$name], $value ) : array_diff( $permData[$name], $value );

					$this->changes[$group][$name][$type] = $value;
				}
			} elseif ( $permData['autopromote'] != $data['autopromote'] ) {
				$permData['autopromote'] = $data['autopromote'];

				$this->changes[$group]['autopromote'] = true;
			}
		}

		$this->livePermissions[$group] = $permData;
	}

	/**
	 * Remove a group
	 * @param string $group Group name
	 */
	public function remove( string $group ) {
		// Utilise changes differently in this case
		foreach ( $this->livePermissions[$group] as $name => $value ) {
			$this->changes[$group][$name] = [
				'add' => null,
				'remove' => $value
			];
		}

		// We will handle all processing in final stages
		unset( $this->livePermissions[$group] );

		// Push to a deletion queue
		$this->deleteGroups[] = $group;
	}

	/**
	 * Commits all changes to database
	 */
	public function commit() {
		$logNULL = wfMessage( 'rightsnone' )->inContentLanguage()->text();

		foreach ( array_keys( $this->changes ) as $group ) {
			if ( in_array( $group, $this->deleteGroups ) ) {
				$this->log = 'delete-group';

				$this->dbw->delete(
					'mw_permissions',
					[
						'perm_dbname' => $this->wiki,
						'perm_group' => $group
					]
				);

				$this->deleteUsersFromGroup( $group );
			} else {
				if ( empty( $this->livePermissions[$group]['permissions'] ) ) {
					$this->errors[] = [
						'managewiki-error-emptygroup' => []
					];
				} else {
					$builtTable = [
						'perm_permissions' => json_encode( $this->livePermissions[$group]['permissions'] ),
						'perm_addgroups' => json_encode( $this->livePermissions[$group]['addgroups'] ),
						'perm_removegroups' => json_encode( $this->livePermissions[$group]['removegroups'] ),
						'perm_addgroupstoself' => json_encode( $this->livePermissions[$group]['addself'] ),
						'perm_removegroupsfromself' => json_encode( $this->livePermissions[$group]['removeself'] ),
						'perm_autopromote' => $this->livePermissions[$group]['autopromote'] === null ? null : json_encode( $this->livePermissions[$group]['autopromote'] )
					];

					$this->dbw->upsert(
						'mw_permissions',
						[
							'perm_dbname' => $this->wiki,
							'perm_group' => $group
						] + $builtTable,
						[
							[
								'perm_dbname',
								'perm_group'
							]
						],
						$builtTable
					);

					$logAP = ( $this->changes[$group]['autopromote'] ?? false ) ? 'htmlform-yes' : 'htmlform-no';
					$this->logParams = [
						'4::ar' => !empty( $this->changes[$group]['permissions']['add'] ) ? implode( ', ', $this->changes[$group]['permissions']['add'] ) : $logNULL,
						'5::rr' => !empty( $this->changes[$group]['permissions']['remove'] ) ? implode( ', ', $this->changes[$group]['permissions']['remove'] ) : $logNULL,
						'6::aag' => !empty( $this->changes[$group]['addgroups']['add'] ) ? implode( ', ', $this->changes[$group]['addgroups']['add'] ) : $logNULL,
						'7::rag' => !empty( $this->changes[$group]['addgroups']['remove'] ) ? implode( ', ', $this->changes[$group]['addgroups']['remove'] ) : $logNULL,
						'8::arg' => !empty( $this->changes[$group]['removegroups']['add'] ) ? implode( ', ', $this->changes[$group]['removegroups']['add'] ) : $logNULL,
						'9::rrg' => !empty( $this->changes[$group]['removegroups']['remove'] ) ? implode( ', ', $this->changes[$group]['removegroups']['remove'] ) : $logNULL,
						'10::aags' => !empty( $this->changes[$group]['addself']['add'] ) ? implode( ', ', $this->changes[$group]['addself']['add'] ) : $logNULL,
						'11::rags' => !empty( $this->changes[$group]['addself']['remove'] ) ? implode( ', ', $this->changes[$group]['addself']['remove'] ) : $logNULL,
						'12::args' => !empty( $this->changes[$group]['removeself']['add'] ) ? implode( ', ', $this->changes[$group]['removeself']['add'] ) : $logNULL,
						'13::rrgs' => !empty( $this->changes[$group]['removeself']['remove'] ) ? implode( ', ', $this->changes[$group]['removeself']['remove'] ) : $logNULL,
						'14::ap' => strtolower( wfMessage( $logAP )->inContentLanguage()->text() )
					];
				}
			}
		}

		if ( $this->wiki != 'default' ) {
			$cWJ = new CreateWikiJson( $this->wiki );
			$cWJ->resetWiki();
		}
		$this->committed = true;
	}

	private function deleteUsersFromGroup( string $group ) {
		$groupManager = MediaWikiServices::getInstance()->getUserGroupManager();
		$dbr = MediaWikiServices::getInstance()->getDBLoadBalancerFactory()
			->getMainLB( $this->wiki )
			->getMaintenanceConnectionRef( DB_REPLICA, [], $this->wiki );

		$res = $dbr->select(
			'user_groups',
			'ug_user',
			[
				'ug_group' => $group
			]
		);

		foreach ( $res as $row ) {
			$groupManager->removeUserFromGroup( User::newFromId( $row->ug_user ), $group );
		}
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
