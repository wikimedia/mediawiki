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
		$containers = array( 'data' => $this->filePath );
		if ( $this->createsThumbnails() ) {
			// We need a temp directory for the thumbnails
			// the container is named 'temp-thumb' because it is the
			// thumb directory for a FSRepo named "temp".
			$containers['temp-thumb'] = $this->getNewTempDirectory();
		}

		$this->backend = new FSFileBackend( array(
			'name' => 'localtesting',
			'wikiId' => wfWikiId(),
			'containerPaths' => $containers
		) );
		$this->repo = new FSRepo( array(
			'name' => 'temp',
			'url' => 'http://localhost/thumbtest',
			'backend' => $this->backend
		) );
	}

	/**
	 * The result of this method will set the file path to use,
	 * as well as the protected member $filePath
	 *
	 * @return String path where files are
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
	 * @return boolean
	 */
	protected function createsThumbnails() {
		return false;
	}

	/**
	 * Utility function: Get a new file object for a file on disk but not actually in db.
	 *
	 * File must be in the path returned by getFilePath()
	 * @param $name String File name
	 * @param $type String MIME type
	 * @return UnregisteredLocalFile
	 */
	protected function dataFile( $name, $type ) {
		return new UnregisteredLocalFile( false, $this->repo,
			"mwstore://localtesting/data/$name", $type );
	}
}
