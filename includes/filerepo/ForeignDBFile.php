<?php

class ForeignDBFile extends LocalFile {
	static function newFromTitle( $title, $repo ) {
		return new self( $title, $repo );
	}

	function getCacheKey() {
		if ( $this->repo->hasSharedCache ) {
			$hashedName = md5($this->name);
			return wfForeignMemcKey( $this->repo->dbName, $this->repo->tablePrefix, 
				'file', $hashedName );
		} else {
			return false;
		}
	}

	function publish( /*...*/ ) {
		$this->readOnlyError();
	}

	function recordUpload( /*...*/ ) {
		$this->readOnlyError();
	}
	function restore(  /*...*/  ) {
		$this->readOnlyError();
	}
	function delete( /*...*/ ) {
		$this->readOnlyError();
	}

	function getDescriptionUrl() {
		// Restore remote behaviour
		return File::getDescriptionUrl();
	}

	function getDescriptionText() {
		// Restore remote behaviour
		return File::getDescriptionText();
	}
}
?>
