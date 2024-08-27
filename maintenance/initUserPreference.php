<?php
/**
 * Initialize a user preference based on the value
 * of another preference.
 *
 * @ingroup Maintenance
 */

// @codeCoverageIgnoreStart
require_once __DIR__ . '/Maintenance.php';
// @codeCoverageIgnoreEnd

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

		$dbr = $this->getReplicaDB();
		$dbw = $this->getPrimaryDB();

		$iterator = new BatchRowIterator(
			$dbr,
			'user_properties',
			[ 'up_user', 'up_property' ],
			$this->getBatchSize()
		);
		$iterator->setFetchColumns( [ 'up_user', 'up_value' ] );
		$iterator->addConditions( [
			'up_property' => $source,
			$dbr->expr( 'up_value', '!=', null ),
			$dbr->expr( 'up_value', '!=', 0 ),
		] );
		$iterator->setCaller( __METHOD__ );

		$processed = 0;
		foreach ( $iterator as $batch ) {
			foreach ( $batch as $row ) {
				$dbw->newInsertQueryBuilder()
					->insertInto( 'user_properties' )
					->row( [
						'up_user' => $row->up_user,
						'up_property' => $target,
						'up_value' => $row->up_value,
					] )
					->onDuplicateKeyUpdate()
					->uniqueIndexFields( [ 'up_user', 'up_property' ] )
					->set( [ 'up_value' => $row->up_value ] )
					->caller( __METHOD__ )->execute();

				$processed += $dbw->affectedRows();
			}
		}

		$this->output( "Processed $processed user(s)\n" );
		$this->output( "Finished!\n" );
	}
}

// @codeCoverageIgnoreStart
$maintClass = InitUserPreference::class;
require_once RUN_MAINTENANCE_IF_MAIN;
// @codeCoverageIgnoreEnd
