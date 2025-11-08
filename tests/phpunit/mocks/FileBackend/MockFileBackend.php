<?php
/**
 * Simulation (mock) of a backend storage.
 *
 * @license GPL-2.0-or-later
 * @file
 * @ingroup FileBackend
 * @author Antoine Musso <hashar@free.fr>
 */

use Wikimedia\FileBackend\MemoryFileBackend;

/**
 * Class simulating a backend store.
 *
 * @ingroup FileBackend
 * @since 1.22
 */
class MockFileBackend extends MemoryFileBackend {
	/** @inheritDoc */
	protected function doGetLocalCopyMulti( array $params ) {
		$tmpFiles = []; // (path => MockFSFile)
		foreach ( $params['srcs'] as $src ) {
			$tmpFiles[$src] = new MockFSFile( "Fake path for $src" );
		}
		return $tmpFiles;
	}
}
