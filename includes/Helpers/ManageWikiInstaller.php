<?php

namespace Miraheze\ManageWiki\Helpers;

use Exception;
use MediaWiki\MediaWikiServices;
use MediaWiki\Shell\Shell;
use Miraheze\ManageWiki\Jobs\MWScriptJob;
use MWException;
use Title;

class ManageWikiInstaller {
	public static function process( string $dbname, array $actions, bool $install = true ) {
		// Produces an array of steps and results (so we can fail what we can't do but apply what works)
		$stepresponse = [];

		foreach ( $actions as $action => $data ) {
			switch ( $action ) {
				case 'sql':
					$stepresponse['sql'] = self::sql( $dbname, $data );
					break;
				case 'files':
					$stepresponse['files'] = self::files( $dbname, $data );
					break;
				case 'permissions':
					$stepresponse['permissions'] = self::permissions( $dbname, $data, $install );
					break;
				case 'namespaces':
					$stepresponse['namespaces'] = self::namespaces( $dbname, $data, $install );
					break;
				case 'mwscript':
					$stepresponse['mwscript'] = self::mwscript( $dbname, $data );
					break;
				case 'settings':
					$stepresponse['settings'] = self::settings( $dbname, $data );
					break;
				default:
					return false;
			}
		}

		return !(bool)array_search( false, $stepresponse );
	}

	private static function sql( string $dbname, array $data ) {
		$dbw = MediaWikiServices::getInstance()->getDBLoadBalancerFactory()
			->getMainLB( $dbname )
			->getMaintenanceConnectionRef( DB_PRIMARY, [], $dbname );

		foreach ( $data as $table => $sql ) {
			if ( !$dbw->tableExists( $table ) ) {
				try {
					$dbw->sourceFile( $sql );
				} catch ( Exception $e ) {
					return false;
				}
			}
		}

		return true;
	}

	private static function files( string $dbname, array $data ) {
		$config = MediaWikiServices::getInstance()->getConfigFactory()->makeConfig( 'managewiki' );

		$baseloc = $config->get( 'UploadDirectory' ) . $dbname;

		foreach ( $data as $location => $source ) {
			if ( substr( $location, -1 ) == '/' ) {
				if ( $source === true ) {
					if ( !is_dir( $baseloc . $location ) && !mkdir( $baseloc . $location ) ) {
						return false;
					}
				} else {
					$files = array_diff( scandir( $source ), [ '.', '..' ] );

					foreach ( $files as $file ) {
						if ( !copy( $source . $file, $baseloc . $location . $file ) ) {
							return false;
						}
					}
				}
			} else {
				if ( !copy( $source, $baseloc . $location ) ) {
					return false;
				}
			}
		}

		return true;
	}

	private static function permissions( string $dbname, array $data, bool $install ) {
		$mwPermissions = new ManageWikiPermissions( $dbname );

		$action = ( $install ) ? 'add' : 'remove';

		foreach ( $data as $group => $mod ) {
			$groupData = [
				'permissions' => [
					$action => $mod['permissions'] ?? []
				],
				'addgroups' => [
					$action => $mod['addgroups'] ?? []
				],
				'removegroups' => [
					$action => $mod['removegroups'] ?? []
				]
			];

			$mwPermissions->modify( $group, $groupData );
		}

		$mwPermissions->commit();

		return true;
	}

	private static function namespaces( string $dbname, array $data, bool $install ) {
		$mwNamespaces = new ManageWikiNamespaces( $dbname );
		foreach ( $data as $name => $i ) {
			if ( $install ) {
				$id = $i['id'];
				unset( $i['id'] );
				$i['name'] = $name;

				$mwNamespaces->modify( $id, $i, true );
			} else {
				$mwNamespaces->remove( $i['id'], $i['id'] % 2, true );
			}
		}

		$mwNamespaces->commit();

		return true;
	}

	private static function mwscript( string $dbname, array $data ) {
		if ( Shell::isDisabled() ) {
			throw new MWException( 'Shell is disabled.' );
		}

		foreach ( $data as $script => $options ) {
			$params = [
				'dbname' => $dbname,
				'script' => $script,
				'options' => $options
			];

			$mwJob = new MWScriptJob( Title::newMainPage(), $params );

			MediaWikiServices::getInstance()->getJobQueueGroupFactory()->makeJobQueueGroup()->push( $mwJob );
		}

		return true;
	}

	private static function settings( string $dbname, array $data ) {
		$mwSettings = new ManageWikiSettings( $dbname );
		$mwSettings->modify( $data );
		$mwSettings->commit();

		return true;
	}
}
