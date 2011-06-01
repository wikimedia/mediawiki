<?php

class ExtensionUpdater extends DatabaseUpdater {

	// overload the constructor, so the init and hooks don't get called
	// while still have all the patching code without duplicating
	public __construct( DatabaseBase &$db, $shared, Maintenance $maintenance = null  ) {
		$this->db = $db;
		$this->shared = $shared;
		$this->maintenance = $maintenance;
	}

	public function doUpdates() {
		$this->runUpdates( $this->getCoreUpdateList(), false );
	}
}
