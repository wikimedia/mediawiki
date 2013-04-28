<?php
class MockFile extends FSFile {
	private $sha1Base36 = null; // File Sha1Base36

	public function exists() {
		return true;
	}

	public function getSize() {
		return 1911;
	}

	public function getTimestamp() {
		return wfTimestamp( TS_MW );
	}

	public function getMimeType() {
		return 'text/mock';
	}

	public function getProps( $ext = true ) {
		return array(
			'fileExists' => $this->exists(),
			'size' => $this->getSize(),
			'file-mime' => $this->getMimeType(),
			'sha1' => $this->getSha1Base36(),
		);
	}

	public function getSha1Base36( $recache = false ) {
		return '1234567890123456789012345678901';
	}
}
