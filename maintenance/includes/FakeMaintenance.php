<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\Maintenance;

/**
 * Fake maintenance wrapper, mostly used for the web installer/updater
 * @ingroup Maintenance
 * @codeCoverageIgnore
 */
class FakeMaintenance extends Maintenance {
	/** @inheritDoc */
	protected $mSelf = "FakeMaintenanceScript";

	public function execute() {
	}
}

/** @deprecated class alias since 1.43 */
class_alias( FakeMaintenance::class, 'FakeMaintenance' );
