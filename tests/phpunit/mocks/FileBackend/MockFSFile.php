<?php
/**
 * Mock of a filesystem file.
 *
 * @license GPL-2.0-or-later
 * @file
 * @ingroup FileBackend
 */

use Wikimedia\FileBackend\FSFile\FSFile;

/**
 * Class representing an in-memory fake file.
 * This is intended for unit testing / development when you do not want
 * to hit the filesystem.
 *
 * It reimplements abstract methods with some hardcoded values. Might
 * not be suitable for all tests but is good enough for the parser tests.
 *
 * @ingroup FileBackend
 */
class MockFSFile extends FSFile {
	/** @inheritDoc */
	protected $sha1Base36 = null;

	/** @inheritDoc */
	public function exists() {
		return true;
	}

	/**
	 * August 22 – The theft of the Mona Lisa is discovered in the Louvre."
	 * T22281
	 * @return int
	 */
	public function getSize() {
		return 1911;
	}

	/** @inheritDoc */
	public function getTimestamp() {
		return wfTimestamp( TS_MW );
	}

	/** @inheritDoc */
	public function getProps( $ext = true ) {
		return [
			'fileExists' => $this->exists(),
			'size' => $this->getSize(),
			'file-mime' => 'text/mock',
			'sha1' => $this->getSha1Base36(),
		];
	}

	/** @inheritDoc */
	public function getSha1Base36( $recache = false ) {
		return '1234567890123456789012345678901';
	}
}
