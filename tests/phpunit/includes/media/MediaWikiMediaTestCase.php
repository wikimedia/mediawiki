<?php
/**
 * Specificly for testing Media handlers. Sets up a FSFile backend
 */
abstract class MediaWikiMediaTestCase extends MediaWikiTestCase {

	/** @var FSRepo */
	protected $repo;
	/** @var FSFileBackend */
	protected $backend;
	/** @var string */
	protected $filePath;

	protected function setUp() {
		parent::setUp();

		$this->filePath = $this->getFilePath();
		$containers = [ 'data' => $this->filePath ];
		if ( $this->createsThumbnails() ) {
			// We need a temp directory for the thumbnails
			// the container is named 'temp-thumb' because it is the
			// thumb directory for a FSRepo named "temp".
			$containers['temp-thumb'] = $this->getNewTempDirectory();
		}

		$this->backend = new FSFileBackend( [
			'name' => 'localtesting',
			'wikiId' => wfWikiID(),
			'containerPaths' => $containers,
			'tmpDirectory' => $this->getNewTempDirectory()
		] );
		$this->repo = new FSRepo( $this->getRepoOptions() );
	}

	/**
	 * @return array Argument for FSRepo constructor
	 */
	protected function getRepoOptions() {
		return [
			'name' => 'temp',
			'url' => 'http://localhost/thumbtest',
			'backend' => $this->backend
		];
	}

	/**
	 * The result of this method will set the file path to use,
	 * as well as the protected member $filePath
	 *
	 * @return string Path where files are
	 */
	protected function getFilePath() {
		return __DIR__ . '/../../data/media/';
	}

	/**
	 * Will the test create thumbnails (and thus do we need to set aside
	 * a temporary directory for them?)
	 *
	 * Override this method if your test case creates thumbnails
	 *
	 * @return bool
	 */
	protected function createsThumbnails() {
		return false;
	}

	/**
	 * Utility function: Get a new file object for a file on disk but not actually in db.
	 *
	 * File must be in the path returned by getFilePath()
	 * @param string $name File name
	 * @param string $type MIME type [optional]
	 * @return UnregisteredLocalFile
	 */
	protected function dataFile( $name, $type = null ) {
		if ( !$type ) {
			// Autodetect by file extension for the lazy.
			$magic = MimeMagic::singleton();
			$parts = explode( $name, '.' );
			$type = $magic->guessTypesForExtension( $parts[count( $parts ) - 1] );
		}
		return new UnregisteredLocalFile( false, $this->repo,
			"mwstore://localtesting/data/$name", $type );
	}
}
