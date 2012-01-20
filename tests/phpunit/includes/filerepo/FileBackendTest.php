<?php

/**
 * @group FileRepo
 */
class FileBackendTest extends MediaWikiTestCase {
	private $backend, $multiBackend;
	private $filesToPrune, $pathsToPrune;

	function setUp() {
		parent::setUp();
		$tmpDir = wfTempDir() . '/file-backend-test-' . time() . '-' . mt_rand();
		$this->singleBackend = new FSFileBackend( array(
			'name'        => 'localtesting',
			'lockManager' => 'fsLockManager',
			'containerPaths' => array(
				'cont1' => "$tmpDir/localtesting/cont1",
				'cont2' => "$tmpDir/localtesting/cont2" )
		) );
		$this->multiBackend = new FileBackendMultiWrite( array(
			'name'        => 'localtesting',
			'lockManager' => 'fsLockManager',
			'backends'    => array(
				array(
					'name'          => 'localmutlitesting1',
					'class'         => 'FSFileBackend',
					'lockManager'   => 'nullLockManager',
					'containerPaths' => array(
						'cont1' => "$tmpDir/localtestingmulti1/cont1",
						'cont2' => "$tmpDir/localtestingmulti1/cont2" ),
					'isMultiMaster' => false
				),
				array(
					'name'          => 'localmutlitesting2',
					'class'         => 'FSFileBackend',
					'lockManager'   => 'nullLockManager',
					'containerPaths' => array(
						'cont1' => "$tmpDir/localtestingmulti2/cont1",
						'cont2' => "$tmpDir/localtestingmulti2/cont2" ),
					'isMultiMaster' => true
				)
			)
		) );
		$this->filesToPrune = $this->pathsToPrune = array();
	}

	private function baseStorePath() {
		return 'mwstore://localtesting';
	}

	private function backendClass() {
		return get_class( $this->backend );
	}

	/**
	 * @dataProvider provider_testStore
	 */
	public function testStore( $op, $source, $dest ) {
		$this->filesToPrune[] = $source;
		$this->pathsToPrune[] = $dest;

		$this->backend = $this->singleBackend;
		$this->doTestStore( $op, $source, $dest );
		$this->tearDownFiles();

		$this->backend = $this->multiBackend;
		$this->doTestStore( $op, $source, $dest );
		$this->tearDownFiles();
	}

	function doTestStore( $op, $source, $dest ) {
		$backendName = $this->backendClass();

		$this->backend->prepare( array( 'dir' => dirname( $dest ) ) );

		file_put_contents( $source, "Unit test file" );
		$status = $this->backend->doOperation( $op );

		$this->assertEquals( array(), $status->errors,
			"Store from $source to $dest succeeded without warnings ($backendName)." );
		$this->assertEquals( true, $status->isOK(),
			"Store from $source to $dest succeeded ($backendName)." );
		$this->assertEquals( array( 0 => true ), $status->success,
			"Store from $source to $dest has proper 'success' field in Status ($backendName)." );
		$this->assertEquals( true, file_exists( $source ),
			"Source file $source still exists ($backendName)." );
		$this->assertEquals( true, $this->backend->fileExists( array( 'src' => $dest ) ),
			"Destination file $dest exists ($backendName)." );

		$this->assertEquals( filesize( $source ),
			$this->backend->getFileSize( array( 'src' => $dest ) ),
			"Destination file $dest has correct size ($backendName)." );

		$props1 = FSFile::getPropsFromPath( $source );
		$props2 = $this->backend->getFileProps( array( 'src' => $dest ) );
		$this->assertEquals( $props1, $props2,
			"Source and destination have the same props ($backendName)." );
	}

	public function provider_testStore() {
		$cases = array();

		$tmpName = TempFSFile::factory( "unittests_", 'txt' )->getPath();
		$toPath = $this->baseStorePath() . '/cont1/fun/obj1.txt';
		$op = array( 'op' => 'store', 'src' => $tmpName, 'dst' => $toPath );
		$cases[] = array(
			$op, // operation
			$tmpName, // source
			$toPath, // dest
		);

		$op['overwrite'] = true;
		$cases[] = array(
			$op, // operation
			$tmpName, // source
			$toPath, // dest
		);

		return $cases;
	}

	/**
	 * @dataProvider provider_testCopy
	 */
	public function testCopy( $op, $source, $dest ) {
		$this->pathsToPrune[] = $source;
		$this->pathsToPrune[] = $dest;

		$this->backend = $this->singleBackend;
		$this->doTestCopy( $op, $source, $dest );
		$this->tearDownFiles();

		$this->backend = $this->multiBackend;
		$this->doTestCopy( $op, $source, $dest );
		$this->tearDownFiles();
	}

	function doTestCopy( $op, $source, $dest ) {
		$backendName = $this->backendClass();

		$this->backend->prepare( array( 'dir' => dirname( $source ) ) );
		$this->backend->prepare( array( 'dir' => dirname( $dest ) ) );

		$status = $this->backend->doOperation(
			array( 'op' => 'create', 'content' => 'blahblah', 'dst' => $source ) );
		$this->assertEquals( true, $status->isOK(),
			"Creation of file at $source succeeded ($backendName)." );

		$status = $this->backend->doOperation( $op );
		$this->assertEquals( array(), $status->errors,
			"Copy from $source to $dest succeeded without warnings ($backendName)." );
		$this->assertEquals( true, $status->isOK(),
			"Copy from $source to $dest succeeded ($backendName)." );
		$this->assertEquals( array( 0 => true ), $status->success,
			"Copy from $source to $dest has proper 'success' field in Status ($backendName)." );
		$this->assertEquals( true, $this->backend->fileExists( array( 'src' => $source ) ),
			"Source file $source still exists ($backendName)." );
		$this->assertEquals( true, $this->backend->fileExists( array( 'src' => $dest ) ),
			"Destination file $dest exists after copy ($backendName)." );

		$this->assertEquals(
			$this->backend->getFileSize( array( 'src' => $source ) ),
			$this->backend->getFileSize( array( 'src' => $dest ) ),
			"Destination file $dest has correct size ($backendName)." );

		$props1 = $this->backend->getFileProps( array( 'src' => $source ) );
		$props2 = $this->backend->getFileProps( array( 'src' => $dest ) );
		$this->assertEquals( $props1, $props2,
			"Source and destination have the same props ($backendName)." );
	}

	public function provider_testCopy() {
		$cases = array();

		$source = $this->baseStorePath() . '/cont1/file.txt';
		$dest = $this->baseStorePath() . '/cont2/fileMoved.txt';

		$op = array( 'op' => 'copy', 'src' => $source, 'dst' => $dest );
		$cases[] = array(
			$op, // operation
			$source, // source
			$dest, // dest
		);

		$op['overwrite'] = true;
		$cases[] = array(
			$op, // operation
			$source, // source
			$dest, // dest
		);

		return $cases;
	}

	/**
	 * @dataProvider provider_testMove
	 */
	public function testMove( $op, $source, $dest ) {
		$this->pathsToPrune[] = $source;
		$this->pathsToPrune[] = $dest;

		$this->backend = $this->singleBackend;
		$this->doTestMove( $op, $source, $dest );
		$this->tearDownFiles();

		$this->backend = $this->multiBackend;
		$this->doTestMove( $op, $source, $dest );
		$this->tearDownFiles();
	}

	public function doTestMove( $op, $source, $dest ) {
		$backendName = $this->backendClass();

		$this->backend->prepare( array( 'dir' => dirname( $source ) ) );
		$this->backend->prepare( array( 'dir' => dirname( $dest ) ) );

		$status = $this->backend->doOperation(
			array( 'op' => 'create', 'content' => 'blahblah', 'dst' => $source ) );
		$this->assertEquals( true, $status->isOK(),
			"Creation of file at $source succeeded ($backendName)." );

		$status = $this->backend->doOperation( $op );
		$this->assertEquals( array(), $status->errors,
			"Move from $source to $dest succeeded without warnings ($backendName)." );
		$this->assertEquals( true, $status->isOK(),
			"Move from $source to $dest succeeded ($backendName)." );
		$this->assertEquals( array( 0 => true ), $status->success,
			"Move from $source to $dest has proper 'success' field in Status ($backendName)." );
		$this->assertEquals( false, $this->backend->fileExists( array( 'src' => $source ) ),
			"Source file $source does not still exists ($backendName)." );
		$this->assertEquals( true, $this->backend->fileExists( array( 'src' => $dest ) ),
			"Destination file $dest exists after move ($backendName)." );

		$this->assertNotEquals(
			$this->backend->getFileSize( array( 'src' => $source ) ),
			$this->backend->getFileSize( array( 'src' => $dest ) ),
			"Destination file $dest has correct size ($backendName)." );

		$props1 = $this->backend->getFileProps( array( 'src' => $source ) );
		$props2 = $this->backend->getFileProps( array( 'src' => $dest ) );
		$this->assertEquals( false, $props1['fileExists'],
			"Source file does not exist accourding to props ($backendName)." );
		$this->assertEquals( true, $props2['fileExists'],
			"Destination file exists accourding to props ($backendName)." );
	}

	public function provider_testMove() {
		$cases = array();

		$source = $this->baseStorePath() . '/cont1/file.txt';
		$dest = $this->baseStorePath() . '/cont2/fileMoved.txt';

		$op = array( 'op' => 'move', 'src' => $source, 'dst' => $dest );
		$cases[] = array(
			$op, // operation
			$source, // source
			$dest, // dest
		);

		$op['overwrite'] = true;
		$cases[] = array(
			$op, // operation
			$source, // source
			$dest, // dest
		);

		return $cases;
	}

	/**
	 * @dataProvider provider_testDelete
	 */
	public function testDelete( $op, $source, $withSource, $okStatus ) {
		$this->pathsToPrune[] = $source;

		$this->backend = $this->singleBackend;
		$this->doTestDelete( $op, $source, $withSource, $okStatus );
		$this->tearDownFiles();

		$this->backend = $this->multiBackend;
		$this->doTestDelete( $op, $source, $withSource, $okStatus );
		$this->tearDownFiles();
	}

	public function doTestDelete( $op, $source, $withSource, $okStatus ) {
		$backendName = $this->backendClass();

		$this->backend->prepare( array( 'dir' => dirname( $source ) ) );

		if ( $withSource ) {
			$status = $this->backend->doOperation(
				array( 'op' => 'create', 'content' => 'blahblah', 'dst' => $source ) );
			$this->assertEquals( true, $status->isOK(),
				"Creation of file at $source succeeded ($backendName)." );
		}

		$status = $this->backend->doOperation( $op );
		if ( $okStatus ) {
			$this->assertEquals( array(), $status->errors,
				"Deletion of file at $source succeeded without warnings ($backendName)." );
			$this->assertEquals( true, $status->isOK(),
				"Deletion of file at $source succeeded ($backendName)." );
			$this->assertEquals( array( 0 => true ), $status->success,
				"Deletion of file at $source has proper 'success' field in Status ($backendName)." );
		} else {
			$this->assertEquals( false, $status->isOK(),
				"Deletion of file at $source failed ($backendName)." );
		}

		$this->assertEquals( false, $this->backend->fileExists( array( 'src' => $source ) ),
			"Source file $source does not exist after move ($backendName)." );

		$this->assertFalse(
			$this->backend->getFileSize( array( 'src' => $source ) ),
			"Source file $source has correct size (false) ($backendName)." );

		$props1 = $this->backend->getFileProps( array( 'src' => $source ) );
		$this->assertFalse( $props1['fileExists'],
			"Source file $source does not exist according to props ($backendName)." );
	}

	public function provider_testDelete() {
		$cases = array();

		$source = $this->baseStorePath() . '/cont1/myfacefile.txt';

		$op = array( 'op' => 'delete', 'src' => $source );
		$cases[] = array(
			$op, // operation
			$source, // source
			true, // with source
			true // succeeds
		);

		$cases[] = array(
			$op, // operation
			$source, // source
			false, // without source
			false // fails
		);

		$op['ignoreMissingSource'] = true;
		$cases[] = array(
			$op, // operation
			$source, // source
			false, // without source
			true // succeeds
		);

		return $cases;
	}

	/**
	 * @dataProvider provider_testCreate
	 */
	public function testCreate( $op, $dest, $alreadyExists, $okStatus, $newSize ) {
		$this->pathsToPrune[] = $dest;

		$this->backend = $this->singleBackend;
		$this->doTestCreate( $op, $dest, $alreadyExists, $okStatus, $newSize );
		$this->tearDownFiles();

		$this->backend = $this->multiBackend;
		$this->doTestCreate( $op, $dest, $alreadyExists, $okStatus, $newSize );
		$this->tearDownFiles();
	}

	public function doTestCreate( $op, $dest, $alreadyExists, $okStatus, $newSize ) {
		$backendName = $this->backendClass();

		$this->backend->prepare( array( 'dir' => dirname( $dest ) ) );

		$oldText = 'blah...blah...waahwaah';
		if ( $alreadyExists ) {
			$status = $this->backend->doOperation(
				array( 'op' => 'create', 'content' => $oldText, 'dst' => $dest ) );
			$this->assertEquals( true, $status->isOK(),
				"Creation of file at $dest succeeded ($backendName)." );
		}

		$status = $this->backend->doOperation( $op );
		if ( $okStatus ) {
			$this->assertEquals( array(), $status->errors,
				"Creation of file at $dest succeeded without warnings ($backendName)." );
			$this->assertEquals( true, $status->isOK(),
				"Creation of file at $dest succeeded ($backendName)." );
			$this->assertEquals( array( 0 => true ), $status->success,
				"Creation of file at $dest has proper 'success' field in Status ($backendName)." );
		} else {
			$this->assertEquals( false, $status->isOK(),
				"Creation of file at $dest failed ($backendName)." );
		}

		$this->assertEquals( true, $this->backend->fileExists( array( 'src' => $dest ) ),
			"Destination file $dest exists after creation ($backendName)." );

		$props1 = $this->backend->getFileProps( array( 'src' => $dest ) );
		$this->assertEquals( true, $props1['fileExists'],
			"Destination file $dest exists according to props ($backendName)." );
		if ( $okStatus ) { // file content is what we saved
			$this->assertEquals( $newSize, $props1['size'],
				"Destination file $dest has expected size according to props ($backendName)." );
			$this->assertEquals( $newSize,
				$this->backend->getFileSize( array( 'src' => $dest ) ),
				"Destination file $dest has correct size ($backendName)." );
		} else { // file content is some other previous text
			$this->assertEquals( strlen( $oldText ), $props1['size'],
				"Destination file $dest has original size according to props ($backendName)." );
			$this->assertEquals( strlen( $oldText ),
				$this->backend->getFileSize( array( 'src' => $dest ) ),
				"Destination file $dest has original size according to props ($backendName)." );
		}
	}

	/**
	 * @dataProvider provider_testCreate
	 */
	public function provider_testCreate() {
		$cases = array();

		$source = $this->baseStorePath() . '/cont2/myspacefile.txt';

		$dummyText = 'hey hey';
		$op = array( 'op' => 'create', 'content' => $dummyText, 'dst' => $source );
		$cases[] = array(
			$op, // operation
			$source, // source
			false, // no dest already exists
			true, // succeeds
			strlen( $dummyText )
		);

		$cases[] = array(
			$op, // operation
			$source, // source
			true, // dest already exists
			false, // fails
			strlen( $dummyText )
		);

		$op['overwrite'] = true;
		$cases[] = array(
			$op, // operation
			$source, // source
			true, // dest already exists
			true, // succeeds
			strlen( $dummyText )
		);

		return $cases;
	}

	/**
	 * @dataProvider provider_testConcatenate
	 */
	public function testConcatenate( $op, $srcs, $srcsContent, $alreadyExists, $okStatus ) {
		$this->pathsToPrune = array_merge( $this->pathsToPrune, $srcs );
		$this->filesToPrune[] = $op['dst'];

		$this->backend = $this->singleBackend;
		$this->doTestConcatenate( $op, $srcs, $srcsContent, $alreadyExists, $okStatus );
		$this->tearDownFiles();

		$this->backend = $this->multiBackend;
		$this->doTestConcatenate( $op, $srcs, $srcsContent, $alreadyExists, $okStatus );
		$this->tearDownFiles();
	}

	public function doTestConcatenate( $params, $srcs, $srcsContent, $alreadyExists, $okStatus ) {
		$backendName = $this->backendClass();

		$expContent = '';
		// Create sources
		$ops = array();
		foreach ( $srcs as $i => $source ) {
			$this->backend->prepare( array( 'dir' => dirname( $source ) ) );
			$ops[] = array(
				'op'      => 'create', // operation
				'dst'     => $source, // source
				'content' => $srcsContent[$i]
			);
			$expContent .= $srcsContent[$i];
		}
		$status = $this->backend->doOperations( $ops );

		$this->assertEquals( true, $status->isOK(),
			"Creation of source files succeeded ($backendName)." );

		$dest = $params['dst'];
		if ( $alreadyExists ) {
			$ok = file_put_contents( $dest, 'blah...blah...waahwaah' ) !== false;
			$this->assertEquals( true, $ok,
				"Creation of file at $dest succeeded ($backendName)." );
		} else {
			$ok = file_put_contents( $dest, '' ) !== false;
			$this->assertEquals( true, $ok,
				"Creation of 0-byte file at $dest succeeded ($backendName)." );
		}

		// Combine the files into one
		$status = $this->backend->concatenate( $params );
		if ( $okStatus ) {
			$this->assertEquals( array(), $status->errors,
				"Creation of concat file at $dest succeeded without warnings ($backendName)." );
			$this->assertEquals( true, $status->isOK(),
				"Creation of concat file at $dest succeeded ($backendName)." );
		} else {
			$this->assertEquals( false, $status->isOK(),
				"Creation of concat file at $dest failed ($backendName)." );
		}

		if ( $okStatus ) {
			$this->assertEquals( true, is_file( $dest ),
				"Dest concat file $dest exists after creation ($backendName)." );
		} else {
			$this->assertEquals( true, is_file( $dest ),
				"Dest concat file $dest exists after failed creation ($backendName)." );
		}

		$contents = file_get_contents( $dest );
		$this->assertNotEquals( false, $contents, "File at $dest exists ($backendName)." );

		if ( $okStatus ) {
			$this->assertEquals( $expContent, $contents,
				"Concat file at $dest has correct contents ($backendName)." );
		} else {
			$this->assertNotEquals( $expContent, $contents,
				"Concat file at $dest has correct contents ($backendName)." );
		}
	}

	function provider_testConcatenate() {
		$cases = array();

		$rand = mt_rand( 0, 2000000000 ) . time();
		$dest = wfTempDir() . "/randomfile!$rand.txt";
		$srcs = array(
			$this->baseStorePath() . '/cont1/file1.txt',
			$this->baseStorePath() . '/cont1/file2.txt',
			$this->baseStorePath() . '/cont1/file3.txt',
			$this->baseStorePath() . '/cont1/file4.txt',
			$this->baseStorePath() . '/cont1/file5.txt',
			$this->baseStorePath() . '/cont1/file6.txt',
			$this->baseStorePath() . '/cont1/file7.txt',
			$this->baseStorePath() . '/cont1/file8.txt',
			$this->baseStorePath() . '/cont1/file9.txt',
			$this->baseStorePath() . '/cont1/file10.txt'
		);
		$content = array(
			'egfage',
			'ageageag',
			'rhokohlr',
			'shgmslkg',
			'kenga',
			'owagmal',
			'kgmae',
			'g eak;g',
			'lkaem;a',
			'legma'
		);
		$params = array( 'srcs' => $srcs, 'dst' => $dest );

		$cases[] = array(
			$params, // operation
			$srcs, // sources
			$content, // content for each source
			false, // no dest already exists
			true, // succeeds
		);

		$cases[] = array(
			$params, // operation
			$srcs, // sources
			$content, // content for each source
			true, // dest already exists
			false, // succeeds
		);

		return $cases;
	}

	/**
	 * @dataProvider provider_testGetFileContents
	 */
	public function testGetFileContents( $src, $content ) {
		$this->pathsToPrune[] = $src;

		$this->backend = $this->singleBackend;
		$this->doTestGetFileContents( $src, $content );
		$this->tearDownFiles();

		$this->backend = $this->multiBackend;
		$this->doTestGetFileContents( $src, $content );
		$this->tearDownFiles();
	}

	/**
	 * @dataProvider provider_testGetFileContents
	 */
	public function doTestGetFileContents( $source, $content ) {
		$backendName = $this->backendClass();

		$this->backend->prepare( array( 'dir' => dirname( $source ) ) );

		$status = $this->backend->doOperation(
			array( 'op' => 'create', 'content' => $content, 'dst' => $source ) );
		$this->assertEquals( true, $status->isOK(),
			"Creation of file at $source succeeded ($backendName)." );

		$newContents = $this->backend->getFileContents( array( 'src' => $source ) );
		$this->assertNotEquals( false, $newContents,
			"Read of file at $source succeeded ($backendName)." );

		$this->assertEquals( $content, $newContents,
			"Contents read match data at $source ($backendName)." );
	}

	function provider_testGetFileContents() {
		$cases = array();

		$base = $this->baseStorePath();
		$cases[] = array( "$base/cont1/b/z/some_file.txt", "some file contents" );
		$cases[] = array( "$base/cont1/b/some-other_file.txt", "more file contents" );

		return $cases;
	}

	/**
	 * @dataProvider provider_testGetLocalCopy
	 */
	public function testGetLocalCopy( $src, $content ) {
		$this->pathsToPrune[] = $src;

		$this->backend = $this->singleBackend;
		$this->doTestGetLocalCopy( $src, $content );
		$this->tearDownFiles();

		$this->backend = $this->multiBackend;
		$this->doTestGetLocalCopy( $src, $content );
		$this->tearDownFiles();
	}

	public function doTestGetLocalCopy( $source, $content ) {
		$backendName = $this->backendClass();

		$this->backend->prepare( array( 'dir' => dirname( $source ) ) );

		$status = $this->backend->doOperation(
			array( 'op' => 'create', 'content' => $content, 'dst' => $source ) );
		$this->assertEquals( true, $status->isOK(),
			"Creation of file at $source succeeded ($backendName)." );

		$tmpFile = $this->backend->getLocalCopy( array( 'src' => $source ) );
		$this->assertNotNull( $tmpFile,
			"Creation of local copy of $source succeeded ($backendName)." );

		$contents = file_get_contents( $tmpFile->getPath() );
		$this->assertNotEquals( false, $contents, "Local copy of $source exists ($backendName)." );
	}

	function provider_testGetLocalCopy() {
		$cases = array();

		$base = $this->baseStorePath();
		$cases[] = array( "$base/cont1/a/z/some_file.txt", "some file contents" );
		$cases[] = array( "$base/cont1/a/some-other_file.txt", "more file contents" );

		return $cases;
	}

	/**
	 * @dataProvider provider_testGetLocalReference
	 */
	public function testGetLocalReference( $src, $content ) {
		$this->pathsToPrune[] = $src;

		$this->backend = $this->singleBackend;
		$this->doTestGetLocalReference( $src, $content );
		$this->tearDownFiles();

		$this->backend = $this->multiBackend;
		$this->doTestGetLocalReference( $src, $content );
		$this->tearDownFiles();
	}

	public function doTestGetLocalReference( $source, $content ) {
		$backendName = $this->backendClass();

		$this->backend->prepare( array( 'dir' => dirname( $source ) ) );

		$status = $this->backend->doOperation(
			array( 'op' => 'create', 'content' => $content, 'dst' => $source ) );
		$this->assertEquals( true, $status->isOK(),
			"Creation of file at $source succeeded ($backendName)." );

		$tmpFile = $this->backend->getLocalReference( array( 'src' => $source ) );
		$this->assertNotNull( $tmpFile,
			"Creation of local copy of $source succeeded ($backendName)." );

		$contents = file_get_contents( $tmpFile->getPath() );
		$this->assertNotEquals( false, $contents, "Local copy of $source exists ($backendName)." );
	}

	function provider_testGetLocalReference() {
		$cases = array();

		$base = $this->baseStorePath();
		$cases[] = array( "$base/cont1/a/z/some_file.txt", "some file contents" );
		$cases[] = array( "$base/cont1/a/some-other_file.txt", "more file contents" );

		return $cases;
	}

	/**
	 * @dataProvider provider_testPrepareAndClean
	 */
	public function testPrepareAndClean( $path, $isOK ) {
		$this->backend = $this->singleBackend;
		$this->doTestPrepareAndClean( $path, $isOK );

		$this->backend = $this->multiBackend;
		$this->doTestPrepareAndClean( $path, $isOK );
	}

	function provider_testPrepareAndClean() {
		$base = $this->baseStorePath();
		return array(
			array( "$base/cont1/a/z/some_file1.txt", true ),
			array( "$base/cont2/a/z/some_file2.txt", true ),
			array( "$base/cont3/a/z/some_file3.txt", false ),
		);
	}

	function doTestPrepareAndClean( $path, $isOK ) {
		$backendName = $this->backendClass();

		$status = $this->backend->prepare( array( 'dir' => $path ) );
		if ( $isOK ) {
			$this->assertEquals( array(), $status->errors,
				"Preparing dir $path succeeded without warnings ($backendName)." );
			$this->assertEquals( true, $status->isOK(),
				"Preparing dir $path succeeded ($backendName)." );
		} else {
			$this->assertEquals( false, $status->isOK(),
				"Preparing dir $path failed ($backendName)." );
		}

		$status = $this->backend->clean( array( 'dir' => $path ) );
		if ( $isOK ) {
			$this->assertEquals( array(), $status->errors,
				"Cleaning dir $path succeeded without warnings ($backendName)." );
			$this->assertEquals( true, $status->isOK(),
				"Cleaning dir $path succeeded ($backendName)." );
		} else {
			$this->assertEquals( false, $status->isOK(),
				"Cleaning dir $path failed ($backendName)." );
		}
	}

	// @TODO: testSecure

	public function testDoOperations() {
		$this->backend = $this->singleBackend;
		$this->doTestDoOperations();

		$this->backend = $this->multiBackend;
		$this->doTestDoOperations();
	}

	function doTestDoOperations() {
		$base = $this->baseStorePath();

		$fileA = "$base/cont1/a/b/fileA.txt";
		$fileAContents = '3tqtmoeatmn4wg4qe-mg3qt3 tq';
		$fileB = "$base/cont1/a/b/fileB.txt";
		$fileBContents = 'g-jmq3gpqgt3qtg q3GT ';
		$fileC = "$base/cont1/a/b/fileC.txt";
		$fileCContents = 'eigna[ogmewt 3qt g3qg flew[ag';
		$fileD = "$base/cont1/a/b/fileD.txt";

		$this->pathsToPrune[] = $fileA;
		$this->pathsToPrune[] = $fileB;
		$this->pathsToPrune[] = $fileC;
		$this->pathsToPrune[] = $fileD;

		$this->backend->prepare( array( 'dir' => dirname( $fileA ) ) );
		$this->backend->create( array( 'dst' => $fileA, 'content' => $fileAContents ) );
		$this->backend->prepare( array( 'dir' => dirname( $fileB ) ) );
		$this->backend->create( array( 'dst' => $fileB, 'content' => $fileBContents ) );
		$this->backend->prepare( array( 'dir' => dirname( $fileC ) ) );
		$this->backend->create( array( 'dst' => $fileC, 'content' => $fileCContents ) );

		$status = $this->backend->doOperations( array(
			array( 'op' => 'copy', 'src' => $fileA, 'dst' => $fileC, 'overwrite' => 1 ),
			// Now: A:<A>, B:<B>, C:<A>, D:<D> (file:<orginal contents>)
			array( 'op' => 'copy', 'src' => $fileC, 'dst' => $fileA, 'overwriteSame' => 1 ),
			// Now: A:<A>, B:<B>, C:<A>, D:<D>
			array( 'op' => 'move', 'src' => $fileC, 'dst' => $fileD, 'overwrite' => 1 ),
			// Now: A:<A>, B:<B>, C:<empty>, D:<A>
			array( 'op' => 'move', 'src' => $fileB, 'dst' => $fileC ),
			// Now: A:<A>, B:<empty>, C:<B>, D:<A>
			array( 'op' => 'move', 'src' => $fileD, 'dst' => $fileA, 'overwriteSame' => 1 ),
			// Now: A:<A>, B:<empty>, C:<B>, D:<empty>
			array( 'op' => 'move', 'src' => $fileC, 'dst' => $fileA, 'overwrite' => 1 ),
			// Now: A:<B>, B:<empty>, C:<empty>, D:<empty>
			array( 'op' => 'copy', 'src' => $fileA, 'dst' => $fileC ),
			// Now: A:<B>, B:<empty>, C:<B>, D:<empty>
			array( 'op' => 'move', 'src' => $fileA, 'dst' => $fileC, 'overwriteSame' => 1 ),
			// Now: A:<empty>, B:<empty>, C:<B>, D:<empty>
			array( 'op' => 'copy', 'src' => $fileC, 'dst' => $fileC, 'overwrite' => 1 ),
			// Does nothing
			array( 'op' => 'copy', 'src' => $fileC, 'dst' => $fileC, 'overwriteSame' => 1 ),
			// Does nothing
			array( 'op' => 'move', 'src' => $fileC, 'dst' => $fileC, 'overwrite' => 1 ),
			// Does nothing
			array( 'op' => 'move', 'src' => $fileC, 'dst' => $fileC, 'overwriteSame' => 1 ),
			// Does nothing
		) );

		$this->assertEquals( array(), $status->errors, "Operation batch succeeded" );
		$this->assertEquals( true, $status->isOK(), "Operation batch succeeded" );
		$this->assertEquals( 12, count( $status->success ),
			"Operation batch has correct success array" );

		$this->assertEquals( false, $this->backend->fileExists( array( 'src' => $fileA ) ),
			"File does not exist at $fileA" );
		$this->assertEquals( false, $this->backend->fileExists( array( 'src' => $fileB ) ),
			"File does not exist at $fileB" );
		$this->assertEquals( false, $this->backend->fileExists( array( 'src' => $fileD ) ),
			"File does not exist at $fileD" );

		$this->assertEquals( true, $this->backend->fileExists( array( 'src' => $fileC ) ),
			"File exists at $fileC" );
		$this->assertEquals( $fileBContents,
			$this->backend->getFileContents( array( 'src' => $fileC ) ),
			"Correct file contents of $fileC" );
		$this->assertEquals( strlen( $fileBContents ),
			$this->backend->getFileSize( array( 'src' => $fileC ) ),
			"Correct file size of $fileC" );
		$this->assertEquals( wfBaseConvert( sha1( $fileBContents ), 16, 36, 31 ),
			$this->backend->getFileSha1Base36( array( 'src' => $fileC ) ),
			"Correct file SHA-1 of $fileC" );

		// @TODO: test some cases where the ops should fail
	}

	public function testGetFileList() {
		$this->backend = $this->singleBackend;
		$this->doTestGetFileList();
		$this->tearDownFiles();

		$this->backend = $this->multiBackend;
		$this->doTestGetFileList();
		$this->tearDownFiles();
	}

	public function doTestGetFileList() {
		$backendName = $this->backendClass();

		$base = $this->baseStorePath();
		$files = array(
			"$base/cont1/test1.txt",
			"$base/cont1/test2.txt",
			"$base/cont1/test3.txt",
			"$base/cont1/subdir1/test1.txt",
			"$base/cont1/subdir1/test2.txt",
			"$base/cont1/subdir2/test3.txt",
			"$base/cont1/subdir2/test4.txt",
			"$base/cont1/subdir2/subdir/test1.txt",
			"$base/cont1/subdir2/subdir/test2.txt",
			"$base/cont1/subdir2/subdir/test3.txt",
			"$base/cont1/subdir2/subdir/test4.txt",
			"$base/cont1/subdir2/subdir/test5.txt",
			"$base/cont1/subdir2/subdir/sub/test0.txt",
			"$base/cont1/subdir2/subdir/sub/120-px-file.txt",
		);
		$this->pathsToPrune = array_merge( $this->pathsToPrune, $files );

		// Add the files
		$ops = array();
		foreach ( $files as $file ) {
			$ops[] = array( 'op' => 'create', 'content' => 'xxy', 'dst' => $file );
			$this->backend->prepare( array( 'dir' => dirname( $file ) ) );
		}
		$status = $this->backend->doOperations( $ops );
		$this->assertEquals( true, $status->isOK(),
			"Creation of files succeeded ($backendName)." );

		// Expected listing
		$expected = array(
			"test1.txt",
			"test2.txt",
			"test3.txt",
			"subdir1/test1.txt",
			"subdir1/test2.txt",
			"subdir2/test3.txt",
			"subdir2/test4.txt",
			"subdir2/subdir/test1.txt",
			"subdir2/subdir/test2.txt",
			"subdir2/subdir/test3.txt",
			"subdir2/subdir/test4.txt",
			"subdir2/subdir/test5.txt",
			"subdir2/subdir/sub/test0.txt",
			"subdir2/subdir/sub/120-px-file.txt",
		);
		sort( $expected );

		// Actual listing (no trailing slash)
		$list = array();
		$iter = $this->backend->getFileList( array( 'dir' => "$base/cont1" ) );
		foreach ( $iter as $file ) {
			$list[] = $file;
		}
		sort( $list );

		$this->assertEquals( $expected, $list, "Correct file listing ($backendName)." );

		// Actual listing (with trailing slash)
		$list = array();
		$iter = $this->backend->getFileList( array( 'dir' => "$base/cont1/" ) );
		foreach ( $iter as $file ) {
			$list[] = $file;
		}
		sort( $list );

		$this->assertEquals( $expected, $list, "Correct file listing ($backendName)." );

		foreach ( $files as $file ) {
			$this->backend->doOperation( array( 'op' => 'delete', 'src' => "$base/$file" ) );
		}

		$iter = $this->backend->getFileList( array( 'dir' => "$base/cont1/not/exists" ) );
		foreach ( $iter as $iter ) {} // no errors
	}

	function tearDownFiles() {
		foreach ( $this->filesToPrune as $file ) {
			@unlink( $file );
		}
		foreach ( $this->pathsToPrune as $file ) {
			$this->backend->doOperation( array( 'op' => 'delete', 'src' => $file ) );
			$tmp = $file;
			while ( $tmp = FileBackend::parentStoragePath( $tmp ) ) {
				$this->backend->clean( array( 'dir' => $tmp ) );
			}
		}
	}

	function tearDown() {
		parent::tearDown();
	}
}
