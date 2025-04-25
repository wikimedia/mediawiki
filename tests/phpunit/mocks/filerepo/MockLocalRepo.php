<?php

use MediaWiki\FileRepo\LocalRepo;

/**
 * Class simulating a local file repo.
 *
 * @ingroup FileRepo
 * @since 1.28
 */
class MockLocalRepo extends LocalRepo {
	public function getLocalCopy( $virtualUrl ) {
		return new MockFSFile( "Fake path for $virtualUrl" );
	}

	public function getLocalReference( $virtualUrl ) {
		return new MockFSFile( "Fake path for $virtualUrl" );
	}

	public function getFileProps( $virtualUrl ) {
		$fsFile = $this->getLocalReference( $virtualUrl );
		return $fsFile->getProps();
	}
}
