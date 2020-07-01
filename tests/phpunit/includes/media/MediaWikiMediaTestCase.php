<?php
/**
 * Specificly for testing Media handlers. Sets up a FileRepo backend
 */
abstract class MediaWikiMediaTestCase extends MediaWikiIntegrationTestCase {

	/** @var FileRepo */
	protected $repo;
	/** @var FSFileBackend */
	protected $backend;
	/** @var string */
	protected $filePath;

	protected function setUp() : void {
		parent::setUp();

		$this->filePath = $this->getFilePath();
		$containers = [ 'data' => $this->filePath ];
		if ( $this->createsThumbnails() ) {
			// We need a temp directory for the thumbnails
			// the container is named 'temp-thumb' because it is the
			// thumb directory for a repo named "temp".
			$containers['temp-thumb'] = $this->getNewTempDirectory();
		}

		$this->backend = new FSFileBackend( [
			'name' => 'localtesting',
			'wikiId' => wfWikiID(),
			'containerPaths' => $containers,
			'tmpDirectory' => $this->getNewTempDirectory()
		] );
		$this->repo = new FileRepo( $this->getRepoOptions() );
	}

	/**
	 * @return array Argument for FileRepo constructor
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
	 * @param string|false $type MIME type [optional]
	 * @return UnregisteredLocalFile
	 */
	protected function dataFile( $name, $type = false ) {
		return new UnregisteredLocalFile( false, $this->repo,
			"mwstore://localtesting/data/$name", $type );
	}
}
