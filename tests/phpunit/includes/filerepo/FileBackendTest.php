<?php

// @TODO: fix empty dir leakage
class FileBackendTest extends MediaWikiTestCase {
	private $backend, $multiBackend;
	private $filesToPrune, $pathsToPrune;

	function setUp() {
		parent::setUp();
		$this->backend = new FSFileBackend( array(
			'name'        => 'localtesting',
			'lockManager' => 'fsLockManager',
			'containerPaths' => array(
				'cont1' => wfTempDir() . '/localtesting/cont1',
				'cont2' => wfTempDir() . '/localtesting/cont2' )
		) );
		$this->multiBackend = new FileBackendMultiWrite( array(
			'name'        => 'localtestingmulti',
			'lockManager' => 'fsLockManager',
			'backends'    => array(
				array(
					'name'          => 'localmutlitesting1',
					'class'         => 'FSFileBackend',
					'lockManager'   => 'nullLockManager',
					'containerPaths' => array(
						'cont1' => wfTempDir() . '/localtestingmulti1/cont1',
						'cont2' => wfTempDir() . '/localtestingmulti1/cont2' ),
					'isMultiMaster' => false
				),
				array(
					'name'          => 'localmutlitesting2',
					'class'         => 'FSFileBackend',
					'lockManager'   => 'nullLockManager',
					'containerPaths' => array(
						'cont1' => wfTempDir() . '/localtestingmulti2/cont1',
						'cont2' => wfTempDir() . '/localtestingmulti2/cont2' ),
					'isMultiMaster' => true
				)
			)
		) );
		$this->filesToPrune = $this->pathsToPrune = array();
	}

	private function singleBasePath() {
		return 'mwstore://localtesting';
	}

	/**
	 * @dataProvider provider_testStore
	 */
	public function testStore( $op, $source, $dest ) {
		$this->filesToPrune[] = $source;
		$this->pathsToPrune[] = $dest;

		file_put_contents( $source, "Unit test file" );
		$status = $this->backend->doOperation( $op );

		$this->assertEquals( true, $status->isOK(),
			"Store from $source to $dest succeeded." );
		$this->assertEquals( true, $status->isGood(),
			"Store from $source to $dest succeeded without warnings." );
		$this->assertEquals( true, file_exists( $source ),
			"Source file $source still exists." );
		$this->assertEquals( true, $this->backend->fileExists( array( 'src' => $dest ) ),
			"Destination file $dest exists." );

		$props1 = FSFile::getPropsFromPath( $source );
		$props2 = $this->backend->getFileProps( array( 'src' => $dest ) );
		$this->assertEquals( $props1, $props2,
			"Source and destination have the same props." );
	}

	public function provider_testStore() {
		$cases = array();

		$tmpName = TempFSFile::factory( "unittests_", 'txt' )->getPath();
		$toPath = $this->singleBasePath() . '/cont1/fun/obj1.txt';
		$op = array( 'op' => 'store', 'src' => $tmpName, 'dst' => $toPath );
		$cases[] = array(
			$op, // operation
			$tmpName, // source
			$toPath, // dest
		);

		$op['overwriteDest'] = true;
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

		$status = $this->backend->doOperation(
			array( 'op' => 'create', 'content' => 'blahblah', 'dst' => $source ) );
		$this->assertEquals( true, $status->isOK(), "Creation of file at $source succeeded." );

		$status = $this->backend->doOperation( $op );
		$this->assertEquals( true, $status->isOK(),
			"Copy from $source to $dest succeeded." );
		$this->assertEquals( true, $status->isGood(),
			"Copy from $source to $dest succeeded without warnings." );
		$this->assertEquals( true, $this->backend->fileExists( array( 'src' => $source ) ),
			"Source file $source still exists." );
		$this->assertEquals( true, $this->backend->fileExists( array( 'src' => $dest ) ),
			"Destination file $dest exists after copy." );

		$props1 = $this->backend->getFileProps( array( 'src' => $source ) );
		$props2 = $this->backend->getFileProps( array( 'src' => $dest ) );
		$this->assertEquals( $props1, $props2,
			"Source and destination have the same props." );
	}

	public function provider_testCopy() {
		$cases = array();

		$source = $this->singleBasePath() . '/cont1/file.txt';
		$dest = $this->singleBasePath() . '/cont2/fileMoved.txt';

		$op = array( 'op' => 'copy', 'src' => $source, 'dst' => $dest );
		$cases[] = array(
			$op, // operation
			$source, // source
			$dest, // dest
		);

		$op['overwriteDest'] = true;
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

		$status = $this->backend->doOperation(
			array( 'op' => 'create', 'content' => 'blahblah', 'dst' => $source ) );
		$this->assertEquals( true, $status->isOK(), "Creation of file at $source succeeded." );

		$status = $this->backend->doOperation( $op );
		$this->assertEquals( true, $status->isOK(),
			"Move from $source to $dest succeeded." );
		$this->assertEquals( true, $status->isGood(),
			"Move from $source to $dest succeeded without warnings." );
		$this->assertEquals( false, $this->backend->fileExists( array( 'src' => $source ) ),
			"Source file $source does not still exists." );
		$this->assertEquals( true, $this->backend->fileExists( array( 'src' => $dest ) ),
			"Destination file $dest exists after move." );

		$props1 = $this->backend->getFileProps( array( 'src' => $source ) );
		$props2 = $this->backend->getFileProps( array( 'src' => $dest ) );
		$this->assertEquals( false, $props1['fileExists'],
			"Source file does not exist accourding to props." );
		$this->assertEquals( true, $props2['fileExists'],
			"Destination file exists accourding to props." );
	}

	public function provider_testMove() {
		$cases = array();

		$source = $this->singleBasePath() . '/cont1/file.txt';
		$dest = $this->singleBasePath() . '/cont2/fileMoved.txt';

		$op = array( 'op' => 'move', 'src' => $source, 'dst' => $dest );
		$cases[] = array(
			$op, // operation
			$source, // source
			$dest, // dest
		);

		$op['overwriteDest'] = true;
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

		if ( $withSource ) {
			$status = $this->backend->doOperation(
				array( 'op' => 'create', 'content' => 'blahblah', 'dst' => $source ) );
			$this->assertEquals( true, $status->isOK(), "Creation of file at $source succeeded." );
		}

		$status = $this->backend->doOperation( $op );
		if ( $okStatus ) {
			$this->assertEquals( true, $status->isOK(), "Deletion of file at $source succeeded." );
		} else {
			$this->assertEquals( false, $status->isOK(), "Deletion of file at $source failed." );
		}

		$this->assertEquals( false, $this->backend->fileExists( array( 'src' => $source ) ),
			"Source file $source does not exist after move." );

		$props1 = $this->backend->getFileProps( array( 'src' => $source ) );
		$this->assertEquals( false, $props1['fileExists'],
			"Source file $source does not exist according to props." );
	}

	public function provider_testDelete() {
		$cases = array();

		$source = $this->singleBasePath() . '/cont1/myfacefile.txt';

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

		$oldText = 'blah...blah...waahwaah';
		if ( $alreadyExists ) {
			$status = $this->backend->doOperation(
				array( 'op' => 'create', 'content' => $oldText, 'dst' => $dest ) );
			$this->assertEquals( true, $status->isOK(), "Creation of file at $dest succeeded." );
		}

		$status = $this->backend->doOperation( $op );
		if ( $okStatus ) {
			$this->assertEquals( true, $status->isOK(), "Creation of file at $dest succeeded." );
		} else {
			$this->assertEquals( false, $status->isOK(), "Creation of file at $dest failed." );
		}

		$this->assertEquals( true, $this->backend->fileExists( array( 'src' => $dest ) ),
			"Dest file $dest exists after creation." );

		$props1 = $this->backend->getFileProps( array( 'src' => $dest ) );
		$this->assertEquals( true, $props1['fileExists'],
			"Dest file $dest exists according to props." );
		if ( $okStatus ) { // file content is what we saved
			$this->assertEquals( $newSize, $props1['size'],
				"Dest file $dest has expected size according to props." );
		} else { // file content is some other previous text
			$this->assertEquals( strlen( $oldText ), $props1['size'],
				"Dest file $dest has different size that given text according to props." );
		}
	}

	/**
	 * @dataProvider provider_testCreate
	 */
	public function provider_testCreate() {
		$cases = array();

		$source = $this->singleBasePath() . '/cont2/myspacefile.txt';

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

		$op['overwriteDest'] = true;
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
		$this->pathsToPrune[] = $op['dst'];

		$expContent = '';
		// Create sources
		$ops = array();
		foreach ( $srcs as $i => $source ) {
			$ops[] = array(
				'op'      => 'create', // operation
				'dst'     => $source, // source
				'content' => $srcsContent[$i]
			);
			$expContent .= $srcsContent[$i];
		}
		$status = $this->backend->doOperations( $ops );

		$this->assertEquals( true, $status->isOK(), "Creation of source files succeeded." );

		$dest = $op['dst'];
		if ( $alreadyExists ) {
			$oldText = 'blah...blah...waahwaah';
			$status = $this->backend->doOperation(
				array( 'op' => 'create', 'content' => $oldText, 'dst' => $dest ) );
			$this->assertEquals( true, $status->isOK(), "Creation of file at $dest succeeded." );
		}

		// Combine them
		$status = $this->backend->doOperation( $op );
		if ( $okStatus ) {
			$this->assertEquals( true, $status->isOK(), "Creation of concat file at $dest succeeded." );
		} else {
			$this->assertEquals( false, $status->isOK(), "Creation of concat file at $dest failed." );
		}

		if ( $okStatus ) {
			$this->assertEquals( true, $this->backend->fileExists( array( 'src' => $dest ) ),
				"Dest concat file $dest exists after creation." );
		} else {
			$this->assertEquals( true, $this->backend->fileExists( array( 'src' => $dest ) ),
				"Dest concat file $dest exists after failed creation." );
		}

		$tmpFile = $this->backend->getLocalCopy( array( 'src' => $dest ) );
		$this->assertNotNull( $tmpFile, "Creation of local copy of $dest succeeded." );

		$contents = file_get_contents( $tmpFile->getPath() );
		$this->assertNotEquals( false, $contents, "Local copy of $dest exists." );

		if ( $okStatus ) {
			$this->assertEquals( $expContent, $contents, "Concat file at $dest has correct contents." );
		} else {
			$this->assertNotEquals( $expContent, $contents, "Concat file at $dest has correct contents." );
		}
	}

	function provider_testConcatenate() {
		$cases = array();

		$dest = $this->singleBasePath() . '/cont1/full_file.txt';
		$srcs = array(
			$this->singleBasePath() . '/cont1/file1.txt',
			$this->singleBasePath() . '/cont1/file2.txt',
			$this->singleBasePath() . '/cont1/file3.txt',
			$this->singleBasePath() . '/cont1/file4.txt',
			$this->singleBasePath() . '/cont1/file5.txt',
			$this->singleBasePath() . '/cont1/file6.txt',
			$this->singleBasePath() . '/cont1/file7.txt',
			$this->singleBasePath() . '/cont1/file8.txt',
			$this->singleBasePath() . '/cont1/file9.txt',
			$this->singleBasePath() . '/cont1/file10.txt'
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
		$op = array( 'op' => 'concatenate', 'srcs' => $srcs, 'dst' => $dest );

		$cases[] = array(
			$op, // operation
			$srcs, // sources
			$content, // content for each source
			false, // no dest already exists
			true, // succeeds
		);

		$cases[] = array(
			$op, // operation
			$srcs, // sources
			$content, // content for each source
			true, // no dest already exists
			false, // succeeds
		);

		$op['overwriteDest'] = true;
		$cases[] = array(
			$op, // operation
			$srcs, // sources
			$content, // content for each source
			true, // no dest already exists
			true, // succeeds
		);

		return $cases;
	}

	/**
	 * @dataProvider provider_testGetLocalCopy
	 */
	public function testGetLocalCopy( $src, $content ) {
		$this->pathsToPrune[] = $src;

		$status = $this->backend->doOperation(
			array( 'op' => 'create', 'content' => $content, 'dst' => $src ) );
		$this->assertEquals( true, $status->isOK(), "Creation of file at $src succeeded." );

		$tmpFile = $this->backend->getLocalCopy( array( 'src' => $src ) );
		$this->assertNotNull( $tmpFile, "Creation of local copy of $src succeeded." );

		$contents = file_get_contents( $tmpFile->getPath() );
		$this->assertNotEquals( false, $contents, "Local copy of $src exists." );
	}

	function provider_testGetLocalCopy() {
		$cases = array();

		$base = $this->singleBasePath();
		$cases[] = array( "$base/cont1/a/z/some_file.txt", "some file contents" );
		$cases[] = array( "$base/cont1/a/some-other_file.txt", "more file contents" );

		return $cases;
	}

	/**
	 * @dataProvider provider_testGetReference
	 */
	public function testGetLocalReference( $src, $content ) {
		$this->pathsToPrune[] = $src;

		$status = $this->backend->doOperation(
			array( 'op' => 'create', 'content' => $content, 'dst' => $src ) );
		$this->assertEquals( true, $status->isOK(), "Creation of file at $src succeeded." );

		$tmpFile = $this->backend->getLocalReference( array( 'src' => $src ) );
		$this->assertNotNull( $tmpFile, "Creation of local copy of $src succeeded." );

		$contents = file_get_contents( $tmpFile->getPath() );
		$this->assertNotEquals( false, $contents, "Local copy of $src exists." );
	}

	function provider_testGetReference() {
		$cases = array();

		$base = $this->singleBasePath();
		$cases[] = array( "$base/cont1/a/z/some_file.txt", "some file contents" );
		$cases[] = array( "$base/cont1/a/some-other_file.txt", "more file contents" );

		return $cases;
	}

	// @TODO: testPrepare

	// @TODO: testSecure

	// @TODO: testClean

	// @TODO: testDoOperations

	public function testGetFileList() {
		$base = $this->singleBasePath();
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
		}
		$status = $this->backend->doOperations( $ops );
		$this->assertEquals( true, $status->isOK(), "Creation of files succeeded." );

		// Expected listing
		$expected = array(
			"test1.txt",
			"test2.txt",
			"test3.txt",
			"subdir1/test1.txt",
			"subdir1/test2.txt",
			"subdir2/test3.txt",
			"subdir2/test1.txt",
			"subdir2/subdir/test1.txt",
			"subdir2/subdir/test2.txt",
			"subdir2/subdir/test3.txt",
			"subdir2/subdir/test4.txt",
			"subdir2/subdir/test5.txt",
			"subdir2/subdir/sub/test0.txt",
			"subdir2/subdir/sub/120-px-file.txt",
		);
		$expected = sort( $expected );

		// Actual listing (no trailing slash)
		$list = array();
		$iter = $this->backend->getFileList( array( 'dir' => "$base/cont1" ) );
		foreach ( $iter as $file ) {
			$list[] = $file;
		}

		$this->assertEquals( $expected, sort( $list ), "Correct file listing." );

		// Actual listing (with trailing slash)
		$list = array();
		$iter = $this->backend->getFileList( array( 'dir' => "$base/cont1/" ) );
		foreach ( $iter as $file ) {
			$list[] = $file;
		}

		$this->assertEquals( $expected, sort( $list ), "Correct file listing." );

		foreach ( $files as $file ) {
			$this->backend->delete( array( 'src' => "$base/$files" ) );
		}

		$iter = $this->backend->getFileList( array( 'dir' => "$base/cont1/not/exists" ) );
		foreach ( $iter as $iter ) {} // no errors
	}

	function tearDown() {
		parent::tearDown();
		foreach ( $this->filesToPrune as $file ) {
			@unlink( $file );
		}
		foreach ( $this->pathsToPrune as $file ) {
			$this->backend->doOperation( array( 'op' => 'delete', 'src' => $file ) );
			$this->multiBackend->doOperation( array( 'op' => 'delete', 'src' => $file ) );
		}
		$this->backend = $this->multiBackend = null;
		$this->filesToPrune = $this->pathsToPrune = array();
	}
}
