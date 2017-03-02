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
		
		$usersWithSourcePreference = $dbr->select(
			'user_properties',
			[ 'up_user', 'up_value' ],
			[ 
				'up_property' => $source,
				'up_value IS NOT NULL',
			],
			__METHOD__
		);

		$counter = 0;
		$this->beginTransaction( $dbw, __METHOD__ );
		foreach ($usersWithSourcePreference as $userPreference) {
			$values = [
				'up_user' => $userPreference->up_user,
				'up_property' => $target,
				'up_value' => $userPreference->up_value,
			];
			$dbw->upsert(
				'user_properties',
				$values,
				[ 'up_user', 'up_property' ],
				$values,
				__METHOD__
			);

			$counter++;
			if ( $counter % $this->mBatchSize === 0 ) {
				$this->commitTransaction( $dbw, __METHOD__ );
				$this->beginTransaction( $dbw, __METHOD__ );
			}
		}
		$this->commitTransaction( $dbw, __METHOD__ );
		
		$this->output( "Processed $counter user(s)\n" );
		$this->output( "Finished!\n" );
	}
}

$maintClass = 'InitUserPreference'; // Tells it to run the class
require_once RUN_MAINTENANCE_IF_MAIN;
