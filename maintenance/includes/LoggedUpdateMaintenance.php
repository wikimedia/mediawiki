<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\Maintenance;

/**
 * Class for scripts that perform database maintenance and want to log the
 * update in `updatelog` so we can later skip it
 * @ingroup Maintenance
 */
abstract class LoggedUpdateMaintenance extends Maintenance {
	public function __construct() {
		parent::__construct();
		$this->addOption( 'force', 'Run the update even if it was completed already' );
		$this->setBatchSize( 200 );
	}

	/** @inheritDoc */
	public function execute() {
		if ( !$this->hasOption( 'force' ) && $this->isAlreadyCompleted() ) {
			$this->output( "..." . $this->updateSkippedMessage() . " Use --force to run it again.\n" );
			return true;
		}

		if ( !$this->doDBUpdates() ) {
			return false;
		}

		$db = $this->getPrimaryDB();
		$db->newInsertQueryBuilder()
			->insertInto( 'updatelog' )
			->ignore()
			->row( [ 'ul_key' => $this->getUpdateKey() ] )
			->caller( __METHOD__ )->execute();

		return true;
	}

	public function isAlreadyCompleted(): bool {
		$db = $this->getPrimaryDB();
		return (bool)$db->newSelectQueryBuilder()
			->select( '1' )
			->from( 'updatelog' )
			->where( [ 'ul_key' => $this->getUpdateKey() ] )
			->caller( __METHOD__ )
			->fetchRow();
	}

	/**
	 * Sets whether a run of this maintenance script has the force parameter set
	 * @param bool $forced
	 */
	public function setForce( $forced = true ) {
		$this->setOption( 'force', $forced );
	}

	/**
	 * Message to show that the update was done already and was just skipped
	 * @return string
	 */
	public function updateSkippedMessage() {
		$key = $this->getUpdateKey();

		return "Update '{$key}' already logged as completed.";
	}

	/**
	 * Do the actual work. All child classes will need to implement this.
	 * Return true to log the update as done or false (usually on failure).
	 * @return bool
	 */
	abstract protected function doDBUpdates();

	/**
	 * Get the update key name to go in the update log table
	 * @return string
	 */
	abstract protected function getUpdateKey();
}

/** @deprecated class alias since 1.43 */
class_alias( LoggedUpdateMaintenance::class, 'LoggedUpdateMaintenance' );
