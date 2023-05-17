<?php

namespace Miraheze\ManageWiki\Helpers;

use Config;
use MediaWiki\MediaWikiServices;
use Miraheze\CreateWiki\CreateWikiJson;
use Wikimedia\Rdbms\MaintainableDBConnRef;

/**
 * Handler class for managing settings
 */
class ManageWikiSettings {
	/** @var bool Whether changes have been committed */
	private $committed = false;
	/** @var Config Configuration object */
	private $config;
	/** @var MaintainableDBConnRef Database object */
	private $dbw;
	/** @var array Settings configuration ($wgManageWikiSettings) */
	private $settingsConfig;
	/** @var array Current settings with their respective values */
	private $liveSettings;
	/** @var array Maintenance scripts that need to be ran on enabling/disabling a setting */
	private $scripts = [];
	/** @var string WikiID */
	private $wiki;

	/** @var array Changes to be committed */
	public $changes = [];
	/** @var array Errors */
	public $errors = [];
	/** @var string Log type */
	public $log = 'settings';
	/** @var array Log parameters */
	public $logParams = [];

	/**
	 * ManageWikiSettings constructor.
	 * @param string $wiki WikiID
	 */
	public function __construct( string $wiki ) {
		$this->wiki = $wiki;
		$this->config = MediaWikiServices::getInstance()->getConfigFactory()->makeConfig( 'managewiki' );
		$this->settingsConfig = $this->config->get( 'ManageWikiSettings' );

		$this->dbw = MediaWikiServices::getInstance()->getDBLoadBalancerFactory()
			->getMainLB( $this->config->get( 'CreateWikiDatabase' ) )
			->getMaintenanceConnectionRef( DB_PRIMARY, [], $this->config->get( 'CreateWikiDatabase' ) );

		$settings = $this->dbw->selectRow(
			'mw_settings',
			's_settings',
			[
				's_dbname' => $wiki
			]
		)->s_settings ?? '[]';

		// Bring json_decoded values to class scope
		$this->liveSettings = (array)json_decode( $settings, true );
	}

	/**
	 * Lists either all settings or the value of a specific one
	 * @param string|null $setting Setting to retrieve value of
	 * @return array|string|null Value or all settings, null if no value
	 */
	public function list( string $setting = null ) {
		if ( $setting === null ) {
			return $this->liveSettings;
		} else {
			return $this->liveSettings[$setting] ?? null;
		}
	}

	/**
	 * Adds or changes a setting
	 * @param array $settings Setting to change with value
	 */
	public function modify( array $settings ) {
		// We will handle all processing in final stages
		foreach ( $settings as $var => $value ) {
			if ( $value != ( $this->liveSettings[$var] ?? $this->settingsConfig[$var]['overridedefault'] ) ) {
				$this->changes[$var] = [
					'old' => $this->liveSettings[$var] ?? $this->settingsConfig[$var]['overridedefault'],
					'new' => $value
				];

				$this->liveSettings[$var] = $value;

				if ( isset( $this->settingsConfig[$var]['script'] ) ) {
					foreach ( $this->settingsConfig[$var]['script'] as $script => $opts ) {
						$this->scripts[$script] = $opts;
					}
				}
			}
		}
	}

	/**
	 * Removes a setting
	 * @param string|string[] $settings Settings to remove
	 */
	public function remove( $settings ) {
		// We allow removing of a single variable or many variables
		// We will handle all processing in final stages
		foreach ( (array)$settings as $var ) {
			$this->changes[$var] = [
				'old' => $this->liveSettings[$var],
				'new' => $this->settingsConfig[$var]['overridedefault']
			];

			unset( $this->liveSettings[$var] );
		}
	}

	/**
	 * Allows multiples settings to be changed at once
	 * @param array $settings Settings to change
	 * @param bool $remove Whether to remove settings if they do not exist
	 */
	public function overwriteAll( array $settings, bool $remove = true ) {
		$overwrittenSettings = $this->list();

		foreach ( $this->settingsConfig as $var => $setConfig ) {
			if ( !array_key_exists( $var, $settings ) && array_key_exists( $var, $overwrittenSettings ) && $remove ) {
				$this->remove( $var );
			} elseif ( ( $settings[$var] ?? null ) !== null ) {
				$this->modify( [ $var => $settings[$var] ] );
			}
		}
	}

	/**
	 * Commits all changes to the database
	 */
	public function commit() {
		$this->dbw->upsert(
			'mw_settings',
			[
				's_dbname' => $this->wiki,
				's_extensions' => json_encode( [] ),
				's_settings' => json_encode( $this->liveSettings )
			],
			[
				's_dbname'
			],
			[
				's_settings' => json_encode( $this->liveSettings )
			]
		);

		if ( !empty( $this->scripts ) ) {
			ManageWikiInstaller::process( $this->wiki, [ 'mwscript' => $this->scripts ] );
		}

		$cWJ = new CreateWikiJson( $this->wiki );
		$cWJ->resetWiki();
		$this->committed = true;

		$this->logParams = [
			'5::changes' => implode( ', ', array_keys( $this->changes ) )
		];
	}

	/**
	 * Checks whether changes have been committed
	 */
	public function __destruct() {
		if ( !$this->committed && !empty( $this->changes ) ) {
			print 'Changes have not been committed to the database!';
		}
	}
}
