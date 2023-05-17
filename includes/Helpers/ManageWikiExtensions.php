<?php

namespace Miraheze\ManageWiki\Helpers;

use Config;
use MediaWiki\MediaWikiServices;
use Miraheze\CreateWiki\CreateWikiJson;
use Miraheze\CreateWiki\RemoteWiki;
use Wikimedia\Rdbms\MaintainableDBConnRef;

/**
 * Handler for all interactions with Extension changes within ManageWiki
 */
class ManageWikiExtensions {
	/** @var bool Whether changes are committed or not */
	private $committed = false;
	/** @var Config Configuration Object */
	private $config;
	/** @var MaintainableDBConnRef Database Connection */
	private $dbw;
	/** @var array Extension configuration ($wgManageWikiExtensions) */
	private $extConfig;
	/** @var array Array of enabled extensions with their configuration */
	private $liveExts = [];
	/** @var array Array of extensions to be removed with configuration */
	private $removedExts = [];
	/** @var string WikiID */
	private $wiki;

	/** @var array Changes that are being made on commit() */
	public $changes = [];
	/** @var array Errors */
	public $errors = [];
	/** @var string Log type */
	public $log = 'settings';
	/** @var array Log parameters */
	public $logParams = [];

	/**
	 * @param string $wiki WikiID
	 */
	public function __construct( string $wiki ) {
		$this->wiki = $wiki;
		$this->config = MediaWikiServices::getInstance()->getConfigFactory()->makeConfig( 'managewiki' );
		$this->extConfig = $this->config->get( 'ManageWikiExtensions' );

		$this->dbw = MediaWikiServices::getInstance()->getDBLoadBalancerFactory()
			->getMainLB( $this->config->get( 'CreateWikiDatabase' ) )
			->getMaintenanceConnectionRef( DB_PRIMARY, [], $this->config->get( 'CreateWikiDatabase' ) );

		$exts = $this->dbw->selectRow(
			'mw_settings',
			's_extensions',
			[
				's_dbname' => $wiki
			]
		)->s_extensions ?? '[]';

		// To simplify clean up and to reduce the need to constantly refer back to many different variables, we now
		// populate extension lists with config associated with them.
		foreach ( json_decode( $exts, true ) as $ext ) {
			$this->liveExts[$ext] = $this->extConfig[$ext];
		}
	}

	/**
	 * Lists an array of all extensions currently 'enabled'
	 * @return array 1D array of extensions enabled
	 */
	public function list() {
		return array_keys( $this->liveExts );
	}

	/**
	 * Adds an extension to the 'enabled' list
	 * @param string|string[] $extensions Either an array or string of extensions to enable
	 */
	public function add( $extensions ) {
		// We allow adding either one extension (string) or many (array)
		// We will handle all processing in final stages
		foreach ( (array)$extensions as $ext ) {
			$this->liveExts[$ext] = $this->extConfig[$ext];

			$this->changes[$ext] = [
				'old' => 0,
				'new' => 1
			];
		}
	}

	/**
	 * Removes an extension from the 'enabled' list
	 * @param string|string[] $extensions Either an array or string of extensions to disable
	 */
	public function remove( $extensions ) {
		// We allow remove either one extension (string) or many (array)
		// We will handle all processing in final stages
		foreach ( (array)$extensions as $ext ) {
			if ( !isset( $this->liveExts[$ext] ) ) {
				continue;
			}

			$this->removedExts[$ext] = $this->liveExts[$ext];
			unset( $this->liveExts[$ext] );

			$this->changes[$ext] = [
				'old' => 1,
				'new' => 0
			];
		}
	}

	/**
	 * Allows multiples extensions to be either enabled or disabled
	 * @param array $extensions Array of extensions that should be enabled, absolute
	 */
	public function overwriteAll( array $extensions ) {
		$overwrittenExts = $this->list();

		foreach ( $this->extConfig as $ext => $extConfig ) {
			if ( !is_string( $ext ) ) {
				continue;
			}

			if ( in_array( $ext, $extensions ) && !in_array( $ext, $overwrittenExts ) ) {
				$this->add( $ext );
			} elseif ( !in_array( $ext, $extensions ) && in_array( $ext, $overwrittenExts ) ) {
				$this->remove( $ext );
			}
		}
	}

	/**
	 * Commits all changes made to extension lists to the database
	 */
	public function commit() {
		$remoteWiki = new RemoteWiki( $this->wiki );

		foreach ( $this->liveExts as $name => $extConfig ) {
			// Check if we have a conflict first
			if ( in_array( $extConfig['conflicts'], $this->list() ) ) {
				unset( $this->liveExts[$name] );
				unset( $this->changes[$name] );
				$this->errors[] = [
					'managewiki-error-conflict' => [
						$extConfig['name'],
						$extConfig['conflicts']
					]
				];

				// We have a conflict and we have unset it. Therefore we have nothing else to do for this extension
				continue;
			}

			// Define a 'current' extension as one with no changes entry
			$enabledExt = !isset( $this->changes[$name] );
			// Now we need to check if we fulfil the requirements to enable this extension
			$requirementsCheck = ManageWikiRequirements::process( $extConfig['requires'], $this->list(), $enabledExt, $remoteWiki );

			if ( $requirementsCheck ) {
				$installResult = ( !isset( $extConfig['install'] ) || $enabledExt ) ? true : ManageWikiInstaller::process( $this->wiki, $extConfig['install'] );

				if ( !$installResult ) {
					unset( $this->liveExts[$name] );
					unset( $this->changes[$name] );
					$this->errors[] = [
						'managewiki-error-install' => [
							$extConfig['name']
						]
					];
				}
			} else {
				unset( $this->liveExts[$name] );
				unset( $this->changes[$name] );
				$this->errors[] = [
					'managewiki-error-requirements' => [
						$extConfig['name']
					]
				];
			}
		}

		foreach ( $this->removedExts as $name => $extConfig ) {
			// Unlike installing, we are not too fussed about whether this fails, let us just do it
			if ( isset( $extConfig['remove'] ) ) {
				ManageWikiInstaller::process( $this->wiki, $extConfig['remove'], false );
			}
		}

		$this->write();
		$cWJ = new CreateWikiJson( $this->wiki );
		$cWJ->resetWiki();
		$this->committed = true;

		$this->logParams = [
			'5::changes' => implode( ', ', array_keys( $this->changes ) )
		];
	}

	/**
	 * Function to write changes to the databases
	 */
	private function write() {
		$this->dbw->upsert(
			'mw_settings',
			[
				's_dbname' => $this->wiki,
				's_settings' => json_encode( [] ),
				's_extensions' => json_encode( $this->list() )
			],
			[
				's_dbname'
			],
			[
				's_extensions' => json_encode( $this->list() )
			]
		);
	}

	/**
	 * Safe check to inform of non-committed changed
	 */
	public function __destruct() {
		if ( !$this->committed && !empty( $this->changes ) ) {
			print 'Changes have not been committed to the database!';
		}
	}
}
