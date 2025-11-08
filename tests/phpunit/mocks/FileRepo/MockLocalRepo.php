<?php

use MediaWiki\FileRepo\LocalRepo;

/**
 * Class simulating a local file repo.
 *
 * @ingroup FileRepo
 * @since 1.28
 */
class MockLocalRepo extends LocalRepo {
	/** @inheritDoc */
	public function getLocalCopy( $virtualUrl ) {
		return new MockFSFile( "Fake path for $virtualUrl" );
	}

	/** @inheritDoc */
	public function getLocalReference( $virtualUrl ) {
		return new MockFSFile( "Fake path for $virtualUrl" );
	}

	/** @inheritDoc */
	public function getFileProps( $virtualUrl ) {
		$fsFile = $this->getLocalReference( $virtualUrl );
		return $fsFile->getProps();
	}
}
