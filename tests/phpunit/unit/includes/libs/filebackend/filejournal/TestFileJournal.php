<?php

class TestFileJournal extends NullFileJournal {
	/** @var bool */
	private $purged = false;

	public function getTtlDays() {
		return $this->ttlDays;
	}

	public function getBackend() {
		return $this->backend;
	}

	protected function doLogChangeBatch( array $entries, $batchId ) {
		return StatusValue::newGood( 'Logged' );
	}

	protected function doGetCurrentPosition() {
		return 613;
	}

	protected function doGetPositionAtTime( $time ) {
		return 248;
	}

	protected function doGetChangeEntries( $start, $limit ) {
		return array_slice( [
			[ 'id' => 1 ],
			[ 'id' => 2 ],
			[ 'id' => 3 ],
		], $start === null ? 0 : $start - 1, $limit ? $limit : null );
	}

	protected function doPurgeOldLogs() {
		$this->purged = true;
	}

	public function getPurged() {
		return $this->purged;
	}
}
