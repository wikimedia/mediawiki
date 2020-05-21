<?php
/**
 * Initialize a user preference based on the value
 * of another preference.
 *
 * @ingroup Maintenance
 */

require_once __DIR__ . '/Maintenance.php';

/**
 * Maintenance script that initializes a user preference
 * based on the value of another preference.
 *
 * @ingroup Maintenance
 */
class InitUserPreference extends Maintenance {
	public function __construct() {
		parent::__construct();
		$this->addOption(
			'target',
			'Name of the user preference to initialize',
			true,
			true,
			't'
		);
		$this->addOption(
			'source',
			'Name of the user preference to take the value from',
			true,
			true,
			's'
		);
		$this->setBatchSize( 300 );
	}

	public function execute() {
		$target = $this->getOption( 'target' );
		$source = $this->getOption( 'source' );
		$this->output( "Initializing '$target' based on the value of '$source'\n" );

		$dbr = $this->getDB( DB_REPLICA );
		$dbw = $this->getDB( DB_MASTER );

		$iterator = new BatchRowIterator(
			$dbr,
			'user_properties',
			[ 'up_user', 'up_property' ],
			$this->getBatchSize()
		);
		$iterator->setFetchColumns( [ 'up_user', 'up_value' ] );
		$iterator->addConditions( [
			'up_property' => $source,
			'up_value IS NOT NULL',
			'up_value != 0',
		] );

		$processed = 0;
		foreach ( $iterator as $batch ) {
			foreach ( $batch as $row ) {
				$dbw->upsert(
					'user_properties',
					[
						'up_user' => $row->up_user,
						'up_property' => $target,
						'up_value' => $row->up_value,
					],
					[ [ 'up_user', 'up_property' ] ],
					[
						'up_value' => $row->up_value,
					],
					__METHOD__
				);

				$processed += $dbw->affectedRows();
			}
		}

		$this->output( "Processed $processed user(s)\n" );
		$this->output( "Finished!\n" );
	}
}

$maintClass = InitUserPreference::class;
require_once RUN_MAINTENANCE_IF_MAIN;
