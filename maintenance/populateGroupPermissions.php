<?php

namespace Miraheze\ManageWiki\Maintenance;

$IP = getenv( 'MW_INSTALL_PATH' );
if ( $IP === false ) {
	$IP = __DIR__ . '/../../..';
}
require_once "$IP/maintenance/Maintenance.php";

use Maintenance;
use MediaWiki\MediaWikiServices;
use Miraheze\ManageWiki\ManageWiki;

class PopulateGroupPermissions extends Maintenance {
	public function execute() {
		$config = MediaWikiServices::getInstance()->getConfigFactory()->makeConfig( 'managewiki' );

		if ( ManageWiki::checkSetup( 'permissions' ) ) {
			$this->fatalError( 'Disable ManageWiki Permissions on this wiki.' );
		}

		$excluded = $config->get( 'ManageWikiPermissionsDisallowedGroups' );

		$grouparray = [];

		foreach ( $config->get( 'GroupPermissions' ) as $group => $perm ) {
			$permsarray = [];

			if ( !in_array( $group, $excluded ) ) {
				foreach ( $perm as $name => $value ) {
					if ( $value ) {
						$permsarray[] = $name;
					}
				}

				$grouparray[$group]['perms'] = json_encode( $permsarray );
			}
		}

		foreach ( $config->get( 'AddGroups' ) as $group => $add ) {
			if ( !in_array( $group, $excluded ) ) {
				$grouparray[$group]['add'] = json_encode( $add );
			}
		}

		foreach ( $config->get( 'RemoveGroups' ) as $group => $remove ) {
			if ( !in_array( $group, $excluded ) ) {
				$grouparray[$group]['remove'] = json_encode( $remove );
			}
		}

		foreach ( $config->get( 'GroupsAddToSelf' ) as $group => $adds ) {
			if ( !in_array( $group, $excluded ) ) {
				$grouparray[$group]['addself'] = json_encode( $adds );
			}
		}

		foreach ( $config->get( 'GroupsRemoveFromSelf' ) as $group => $removes ) {
			if ( !in_array( $group, $excluded ) ) {
				$grouparray[$group]['removeself'] = json_encode( $removes );
			}
		}

		foreach ( $config->get( 'Autopromote' ) as $group => $promo ) {
			if ( !in_array( $group, $excluded ) ) {
				$grouparray[$group]['autopromote'] = json_encode( $promo );
			}
		}

		$dbw = $this->getDB( DB_PRIMARY, [], $config->get( 'CreateWikiDatabase' ) );

		foreach ( $grouparray as $groupname => $groupatr ) {
			$check = $dbw->selectRow(
				'mw_permissions',
				[ 'perm_group' ],
				[
					'perm_dbname' => $config->get( 'DBname' ),
					'perm_group' => $groupname
				],
				__METHOD__
			);

			if ( !$check ) {
				$dbw->insert( 'mw_permissions',
					[
						'perm_dbname' => $config->get( 'DBname' ),
						'perm_group' => $groupname,
						'perm_permissions' => $groupatr['perms'],
						'perm_addgroups' => empty( $groupatr['add'] ) ? json_encode( [] ) : $groupatr['add'],
						'perm_removegroups' => empty( $groupatr['remove'] ) ? json_encode( [] ) : $groupatr['remove'],
						'perm_addgroupstoself' => empty( $groupatr['adds'] ) ? json_encode( [] ) : $groupatr['adds'],
						'perm_removegroupsfromself' => empty( $groupatr['removes'] ) ? json_encode( [] ) : $groupatr['removes'],
						'perm_autopromote' => empty( $groupatr['autopromote'] ) ? json_encode( [] ) : $groupatr['autopromote']
					],
					__METHOD__
				);
			}
		}
	}
}

$maintClass = PopulateGroupPermissions::class;
require_once RUN_MAINTENANCE_IF_MAIN;
