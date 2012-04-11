<?php

/**
 * @group FileRepo
 * @group FileBackend
 */
class FileBackendTest extends MediaWikiTestCase {
	private $backend, $multiBackend;
	private $filesToPrune = array();
	private $dirsToPrune = array();
	private static $backendToUse;

	function setUp() {
		global $wgFileBackends;
		parent::setUp();
		$tmpPrefix = wfTempDir() . '/filebackend-unittest-' . time() . '-' . mt_rand();
		if ( $this->getCliArg( 'use-filebackend=' ) ) {
			if ( self::$backendToUse ) {
				$this->singleBackend = self::$backendToUse;
			} else {
				$name = $this->getCliArg( 'use-filebackend=' );
				$useConfig = array();
				foreach ( $wgFileBackends as $conf ) {
					if ( $conf['name'] == $name ) {
						$useConfig = $conf;
					}
				}
				$useConfig['name'] = 'localtesting'; // swap name
				$class = $conf['class'];
				self::$backendToUse = new $class( $useConfig );
				$this->singleBackend = self::$backendToUse;
			}
		} else {
			$this->singleBackend = new FSFileBackend( array(
				'name'        => 'localtesting',
				'lockManager' => 'fsLockManager',
				'containerPaths' => array(
					'unittest-cont1' => "{$tmpPrefix}-localtesting-cont1",
					'unittest-cont2' => "{$tmpPrefix}-localtesting-cont2" )
			) );
		}
		$this->multiBackend = new FileBackendMultiWrite( array(
			'name'        => 'localtesting',
			'lockManager' => 'fsLockManager',
			'backends'    => array(
				array(
					'name'          => 'localmutlitesting1',
					'class'         => 'FSFileBackend',
					'lockManager'   => 'nullLockManager',
					'containerPaths' => array(
						'unittest-cont1' => "{$tmpPrefix}-localtestingmulti1-cont1",
						'unittest-cont2' => "{$tmpPrefix}-localtestingmulti1-cont2" ),
					'isMultiMaster' => false
				),
				array(
					'name'          => 'localmutlitesting2',
					'class'         => 'FSFileBackend',
					'lockManager'   => 'nullLockManager',
					'containerPaths' => array(
						'unittest-cont1' => "{$tmpPrefix}-localtestingmulti2-cont1",
						'unittest-cont2' => "{$tmpPrefix}-localtestingmulti2-cont2" ),
					'isMultiMaster' => true
				)
			)
		) );
		$this->filesToPrune = array();
	}

	private function baseStorePath() {
		return 'mwstore://localtesting';
	}

	private function backendClass() {
		return get_class( $this->backend );
	}

	/**
	 * @dataProvider provider_testIsStoragePath
	 */
	public function testIsStoragePath( $path, $isStorePath ) {
		$this->assertEquals( $isStorePath, FileBackend::isStoragePath( $path ),
			"FileBackend::isStoragePath on path '$path'" );
	}

	function provider_testIsStoragePath() {
		return array(
			array( 'mwstore://', true ),
			array( 'mwstore://backend', true ),
			array( 'mwstore://backend/container', true ),
			array( 'mwstore://backend/container/', true ),
			array( 'mwstore://backend/container/path', true ),
			array( 'mwstore://backend//container/', true ),
			array( 'mwstore://backend//container//', true ),
			array( 'mwstore://backend//container//path', true ),
			array( 'mwstore:///', true ),
			array( 'mwstore:/', false ),
			array( 'mwstore:', false ),
		);
	}

	/**
	 * @dataProvider provider_testSplitStoragePath
	 */
	public function testSplitStoragePath( $path, $res ) {
		$this->assertEquals( $res, FileBackend::splitStoragePath( $path ),
			"FileBackend::splitStoragePath on path '$path'" );
	}

	function provider_testSplitStoragePath() {
		return array(
			array( 'mwstore://backend/container', array( 'backend', 'container', '' ) ),
			array( 'mwstore://backend/container/', array( 'backend', 'container', '' ) ),
			array( 'mwstore://backend/container/path', array( 'backend', 'container', 'path' ) ),
			array( 'mwstore://backend/container//path', array( 'backend', 'container', '/path' ) ),
			array( 'mwstore://backend//container/path', array( null, null, null ) ),
			array( 'mwstore://backend//container//path', array( null, null, null ) ),
			array( 'mwstore://', array( null, null, null ) ),
			array( 'mwstore://backend', array( null, null, null ) ),
			array( 'mwstore:///', array( null, null, null ) ),
			array( 'mwstore:/', array( null, null, null ) ),
			array( 'mwstore:', array( null, null, null ) )
		);
	}

	/**
	 * @dataProvider provider_normalizeStoragePath
	 */
	public function testNormalizeStoragePath( $path, $res ) {
		$this->assertEquals( $res, FileBackend::normalizeStoragePath( $path ),
			"FileBackend::normalizeStoragePath on path '$path'" );
	}

	function provider_normalizeStoragePath() {
		return array(
			array( 'mwstore://backend/container', 'mwstore://backend/container' ),
			array( 'mwstore://backend/container/', 'mwstore://backend/container' ),
			array( 'mwstore://backend/container/path', 'mwstore://backend/container/path' ),
			array( 'mwstore://backend/container//path', 'mwstore://backend/container/path' ),
			array( 'mwstore://backend/container///path', 'mwstore://backend/container/path' ),
			array( 'mwstore://backend/container///path//to///obj', 'mwstore://backend/container/path/to/obj',
			array( 'mwstore://', null ),
			array( 'mwstore://backend', null ),
			array( 'mwstore://backend//container/path', null ),
			array( 'mwstore://backend//container//path', null ),
			array( 'mwstore:///', null ),
			array( 'mwstore:/', null ),
			array( 'mwstore:', null ), )
		);
	}

	/**
	 * @dataProvider provider_testParentStoragePath
	 */
	public function testParentStoragePath( $path, $res ) {
		$this->assertEquals( $res, FileBackend::parentStoragePath( $path ),
			"FileBackend::parentStoragePath on path '$path'" );
	}

	function provider_testParentStoragePath() {
		return array(
			array( 'mwstore://backend/container/path/to/obj', 'mwstore://backend/container/path/to' ),
			array( 'mwstore://backend/container/path/to', 'mwstore://backend/container/path' ),
			array( 'mwstore://backend/container/path', 'mwstore://backend/container' ),
			array( 'mwstore://backend/container', null ),
			array( 'mwstore://backend/container/path/to/obj/', 'mwstore://backend/container/path/to' ),
			array( 'mwstore://backend/container/path/to/', 'mwstore://backend/container/path' ),
			array( 'mwstore://backend/container/path/', 'mwstore://backend/container' ),
			array( 'mwstore://backend/container/', null ),
		);
	}

	/**
	 * @dataProvider provider_testExtensionFromPath
	 */
	public function testExtensionFromPath( $path, $res ) {
		$this->assertEquals( $res, FileBackend::extensionFromPath( $path ),
			"FileBackend::extensionFromPath on path '$path'" );
	}

	function provider_testExtensionFromPath() {
		return array(
			array( 'mwstore://backend/container/path.txt', 'txt' ),
			array( 'mwstore://backend/container/path.svg.png', 'png' ),
			array( 'mwstore://backend/container/path', '' ),
			array( 'mwstore://backend/container/path.', '' ),
		);
	}

	/**
	 * @dataProvider provider_testStore
	 */
	public function testStore( $op ) {
		$this->filesToPrune[] = $op['src'];

		$this->backend = $this->singleBackend;
		$this->tearDownFiles();
		$this->doTestStore( $op );
		$this->tearDownFiles();

		$this->backend = $this->multiBackend;
		$this->tearDownFiles();
		$this->doTestStore( $op );
		$this->filesToPrune[] = $op['src']; # avoid file leaking
		$this->tearDownFiles();
	}

	function doTestStore( $op ) {
		$backendName = $this->backendClass();

		$source = $op['src'];
		$dest = $op['dst'];
		$this->prepare( array( 'dir' => dirname( $dest ) ) );

		file_put_contents( $source, "Unit test file" );

		if ( isset( $op['overwrite'] ) || isset( $op['overwriteSame'] ) ) {
			$this->backend->store( $op );
		}

		$status = $this->backend->doOperation( $op );

		$this->assertEquals( array(), $status->errors,
			"Store from $source to $dest succeeded without warnings ($backendName)." );
		$this->assertEquals( array(), $status->errors,
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
		$toPath = $this->baseStorePath() . '/unittest-cont1/fun/obj1.txt';
		$op = array( 'op' => 'store', 'src' => $tmpName, 'dst' => $toPath );
		$cases[] = array(
			$op, // operation
			$tmpName, // source
			$toPath, // dest
		);

		$op2 = $op;
		$op2['overwrite'] = true;
		$cases[] = array(
			$op2, // operation
			$tmpName, // source
			$toPath, // dest
		);

		$op2 = $op;
		$op2['overwriteSame'] = true;
		$cases[] = array(
			$op2, // operation
			$tmpName, // source
			$toPath, // dest
		);

		return $cases;
	}

	/**
	 * @dataProvider provider_testCopy
	 */
	public function testCopy( $op ) {
		$this->backend = $this->singleBackend;
		$this->tearDownFiles();
		$this->doTestCopy( $op );
		$this->tearDownFiles();

		$this->backend = $this->multiBackend;
		$this->tearDownFiles();
		$this->doTestCopy( $op );
		$this->tearDownFiles();
	}

	function doTestCopy( $op ) {
		$backendName = $this->backendClass();

		$source = $op['src'];
		$dest = $op['dst'];
		$this->prepare( array( 'dir' => dirname( $source ) ) );
		$this->prepare( array( 'dir' => dirname( $dest ) ) );

		$status = $this->backend->doOperation(
			array( 'op' => 'create', 'content' => 'blahblah', 'dst' => $source ) );
		$this->assertEquals( array(), $status->errors,
			"Creation of file at $source succeeded ($backendName)." );

		if ( isset( $op['overwrite'] ) || isset( $op['overwriteSame'] ) ) {
			$this->backend->copy( $op );
		}

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

		$source = $this->baseStorePath() . '/unittest-cont1/file.txt';
		$dest = $this->baseStorePath() . '/unittest-cont2/fileMoved.txt';

		$op = array( 'op' => 'copy', 'src' => $source, 'dst' => $dest );
		$cases[] = array(
			$op, // operation
			$source, // source
			$dest, // dest
		);

		$op2 = $op;
		$op2['overwrite'] = true;
		$cases[] = array(
			$op2, // operation
			$source, // source
			$dest, // dest
		);

		$op2 = $op;
		$op2['overwriteSame'] = true;
		$cases[] = array(
			$op2, // operation
			$source, // source
			$dest, // dest
		);

		return $cases;
	}

	/**
	 * @dataProvider provider_testMove
	 */
	public function testMove( $op ) {
		$this->backend = $this->singleBackend;
		$this->tearDownFiles();
		$this->doTestMove( $op );
		$this->tearDownFiles();

		$this->backend = $this->multiBackend;
		$this->tearDownFiles();
		$this->doTestMove( $op );
		$this->tearDownFiles();
	}

	private function doTestMove( $op ) {
		$backendName = $this->backendClass();

		$source = $op['src'];
		$dest = $op['dst'];
		$this->prepare( array( 'dir' => dirname( $source ) ) );
		$this->prepare( array( 'dir' => dirname( $dest ) ) );

		$status = $this->backend->doOperation(
			array( 'op' => 'create', 'content' => 'blahblah', 'dst' => $source ) );
		$this->assertEquals( array(), $status->errors,
			"Creation of file at $source succeeded ($backendName)." );

		if ( isset( $op['overwrite'] ) || isset( $op['overwriteSame'] ) ) {
			$this->backend->copy( $op );
		}

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

		$source = $this->baseStorePath() . '/unittest-cont1/file.txt';
		$dest = $this->baseStorePath() . '/unittest-cont2/fileMoved.txt';

		$op = array( 'op' => 'move', 'src' => $source, 'dst' => $dest );
		$cases[] = array(
			$op, // operation
			$source, // source
			$dest, // dest
		);

		$op2 = $op;
		$op2['overwrite'] = true;
		$cases[] = array(
			$op2, // operation
			$source, // source
			$dest, // dest
		);

		$op2 = $op;
		$op2['overwriteSame'] = true;
		$cases[] = array(
			$op2, // operation
			$source, // source
			$dest, // dest
		);

		return $cases;
	}

	/**
	 * @dataProvider provider_testDelete
	 */
	public function testDelete( $op, $withSource, $okStatus ) {
		$this->backend = $this->singleBackend;
		$this->tearDownFiles();
		$this->doTestDelete( $op, $withSource, $okStatus );
		$this->tearDownFiles();

		$this->backend = $this->multiBackend;
		$this->tearDownFiles();
		$this->doTestDelete( $op, $withSource, $okStatus );
		$this->tearDownFiles();
	}

	private function doTestDelete( $op, $withSource, $okStatus ) {
		$backendName = $this->backendClass();

		$source = $op['src'];
		$this->prepare( array( 'dir' => dirname( $source ) ) );

		if ( $withSource ) {
			$status = $this->backend->doOperation(
				array( 'op' => 'create', 'content' => 'blahblah', 'dst' => $source ) );
			$this->assertEquals( array(), $status->errors,
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

		$source = $this->baseStorePath() . '/unittest-cont1/myfacefile.txt';

		$op = array( 'op' => 'delete', 'src' => $source );
		$cases[] = array(
			$op, // operation
			true, // with source
			true // succeeds
		);

		$cases[] = array(
			$op, // operation
			false, // without source
			false // fails
		);

		$op['ignoreMissingSource'] = true;
		$cases[] = array(
			$op, // operation
			false, // without source
			true // succeeds
		);

		return $cases;
	}

	/**
	 * @dataProvider provider_testCreate
	 */
	public function testCreate( $op, $alreadyExists, $okStatus, $newSize ) {
		$this->backend = $this->singleBackend;
		$this->tearDownFiles();
		$this->doTestCreate( $op, $alreadyExists, $okStatus, $newSize );
		$this->tearDownFiles();

		$this->backend = $this->multiBackend;
		$this->tearDownFiles();
		$this->doTestCreate( $op, $alreadyExists, $okStatus, $newSize );
		$this->tearDownFiles();
	}

	private function doTestCreate( $op, $alreadyExists, $okStatus, $newSize ) {
		$backendName = $this->backendClass();

		$dest = $op['dst'];
		$this->prepare( array( 'dir' => dirname( $dest ) ) );

		$oldText = 'blah...blah...waahwaah';
		if ( $alreadyExists ) {
			$status = $this->backend->doOperation(
				array( 'op' => 'create', 'content' => $oldText, 'dst' => $dest ) );
			$this->assertEquals( array(), $status->errors,
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

		$dest = $this->baseStorePath() . '/unittest-cont2/myspacefile.txt';

		$op = array( 'op' => 'create', 'content' => 'test test testing', 'dst' => $dest );
		$cases[] = array(
			$op, // operation
			false, // no dest already exists
			true, // succeeds
			strlen( $op['content'] )
		);

		$op2 = $op;
		$op2['content'] = "\n";
		$cases[] = array(
			$op2, // operation
			false, // no dest already exists
			true, // succeeds
			strlen( $op2['content'] )
		);

		$op2 = $op;
		$op2['content'] = "fsf\n waf 3kt";
		$cases[] = array(
			$op2, // operation
			true, // dest already exists
			false, // fails
			strlen( $op2['content'] )
		);

		$op2 = $op;
		$op2['content'] = "egm'g gkpe gpqg eqwgwqg";
		$op2['overwrite'] = true;
		$cases[] = array(
			$op2, // operation
			true, // dest already exists
			true, // succeeds
			strlen( $op2['content'] )
		);

		$op2 = $op;
		$op2['content'] = "39qjmg3-qg";
		$op2['overwriteSame'] = true;
		$cases[] = array(
			$op2, // operation
			true, // dest already exists
			false, // succeeds
			strlen( $op2['content'] )
		);

		return $cases;
	}

	/**
	 * @dataProvider provider_testConcatenate
	 */
	public function testConcatenate( $op, $srcs, $srcsContent, $alreadyExists, $okStatus ) {
		$this->filesToPrune[] = $op['dst'];

		$this->backend = $this->singleBackend;
		$this->tearDownFiles();
		$this->doTestConcatenate( $op, $srcs, $srcsContent, $alreadyExists, $okStatus );
		$this->tearDownFiles();

		$this->backend = $this->multiBackend;
		$this->tearDownFiles();
		$this->doTestConcatenate( $op, $srcs, $srcsContent, $alreadyExists, $okStatus );
		$this->filesToPrune[] = $op['dst']; # avoid file leaking
		$this->tearDownFiles();
	}

	public function doTestConcatenate( $params, $srcs, $srcsContent, $alreadyExists, $okStatus ) {
		$backendName = $this->backendClass();

		$expContent = '';
		// Create sources
		$ops = array();
		foreach ( $srcs as $i => $source ) {
			$this->prepare( array( 'dir' => dirname( $source ) ) );
			$ops[] = array(
				'op'      => 'create', // operation
				'dst'     => $source, // source
				'content' => $srcsContent[$i]
			);
			$expContent .= $srcsContent[$i];
		}
		$status = $this->backend->doOperations( $ops );

		$this->assertEquals( array(), $status->errors,
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
			$this->baseStorePath() . '/unittest-cont1/file1.txt',
			$this->baseStorePath() . '/unittest-cont1/file2.txt',
			$this->baseStorePath() . '/unittest-cont1/file3.txt',
			$this->baseStorePath() . '/unittest-cont1/file4.txt',
			$this->baseStorePath() . '/unittest-cont1/file5.txt',
			$this->baseStorePath() . '/unittest-cont1/file6.txt',
			$this->baseStorePath() . '/unittest-cont1/file7.txt',
			$this->baseStorePath() . '/unittest-cont1/file8.txt',
			$this->baseStorePath() . '/unittest-cont1/file9.txt',
			$this->baseStorePath() . '/unittest-cont1/file10.txt'
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
	 * @dataProvider provider_testGetFileStat
	 */
	public function testGetFileStat( $path, $content, $alreadyExists ) {
		$this->backend = $this->singleBackend;
		$this->tearDownFiles();
		$this->doTestGetFileStat( $path, $content, $alreadyExists );
		$this->tearDownFiles();

		$this->backend = $this->multiBackend;
		$this->tearDownFiles();
		$this->doTestGetFileStat( $path, $content, $alreadyExists );
		$this->tearDownFiles();
	}

	private function doTestGetFileStat( $path, $content, $alreadyExists ) {
		$backendName = $this->backendClass();

		if ( $alreadyExists ) {
			$this->prepare( array( 'dir' => dirname( $path ) ) );
			$status = $this->backend->create( array( 'dst' => $path, 'content' => $content ) );
			$this->assertEquals( array(), $status->errors,
				"Creation of file at $path succeeded ($backendName)." );

			$size = $this->backend->getFileSize( array( 'src' => $path ) );
			$time = $this->backend->getFileTimestamp( array( 'src' => $path ) );
			$stat = $this->backend->getFileStat( array( 'src' => $path ) );

			$this->assertEquals( strlen( $content ), $size,
				"Correct file size of '$path'" );
			$this->assertTrue( abs( time() - wfTimestamp( TS_UNIX, $time ) ) < 5,
				"Correct file timestamp of '$path'" );

			$size = $stat['size'];
			$time = $stat['mtime'];
			$this->assertEquals( strlen( $content ), $size,
				"Correct file size of '$path'" );
			$this->assertTrue( abs( time() - wfTimestamp( TS_UNIX, $time ) ) < 5,
				"Correct file timestamp of '$path'" );
		} else {
			$size = $this->backend->getFileSize( array( 'src' => $path ) );
			$time = $this->backend->getFileTimestamp( array( 'src' => $path ) );
			$stat = $this->backend->getFileStat( array( 'src' => $path ) );
			
			$this->assertFalse( $size, "Correct file size of '$path'" );
			$this->assertFalse( $time, "Correct file timestamp of '$path'" );
			$this->assertFalse( $stat, "Correct file stat of '$path'" );
		}
	}

	function provider_testGetFileStat() {
		$cases = array();

		$base = $this->baseStorePath();
		$cases[] = array( "$base/unittest-cont1/b/z/some_file.txt", "some file contents", true );
		$cases[] = array( "$base/unittest-cont1/b/some-other_file.txt", "", true );
		$cases[] = array( "$base/unittest-cont1/b/some-diff_file.txt", null, false );

		return $cases;
	}

	/**
	 * @dataProvider provider_testGetFileContents
	 */
	public function testGetFileContents( $source, $content ) {
		$this->backend = $this->singleBackend;
		$this->tearDownFiles();
		$this->doTestGetFileContents( $source, $content );
		$this->tearDownFiles();

		$this->backend = $this->multiBackend;
		$this->tearDownFiles();
		$this->doTestGetFileContents( $source, $content );
		$this->tearDownFiles();
	}

	public function doTestGetFileContents( $source, $content ) {
		$backendName = $this->backendClass();

		$this->prepare( array( 'dir' => dirname( $source ) ) );

		$status = $this->backend->doOperation(
			array( 'op' => 'create', 'content' => $content, 'dst' => $source ) );
		$this->assertEquals( array(), $status->errors,
			"Creation of file at $source succeeded ($backendName)." );
		$this->assertEquals( true, $status->isOK(),
			"Creation of file at $source succeeded with OK status ($backendName)." );

		$newContents = $this->backend->getFileContents( array( 'src' => $source, 'latest' => 1 ) );
		$this->assertNotEquals( false, $newContents,
			"Read of file at $source succeeded ($backendName)." );

		$this->assertEquals( $content, $newContents,
			"Contents read match data at $source ($backendName)." );
	}

	function provider_testGetFileContents() {
		$cases = array();

		$base = $this->baseStorePath();
		$cases[] = array( "$base/unittest-cont1/b/z/some_file.txt", "some file contents" );
		$cases[] = array( "$base/unittest-cont1/b/some-other_file.txt", "more file contents" );

		return $cases;
	}

	/**
	 * @dataProvider provider_testGetLocalCopy
	 */
	public function testGetLocalCopy( $source, $content ) {
		$this->backend = $this->singleBackend;
		$this->tearDownFiles();
		$this->doTestGetLocalCopy( $source, $content );
		$this->tearDownFiles();

		$this->backend = $this->multiBackend;
		$this->tearDownFiles();
		$this->doTestGetLocalCopy( $source, $content );
		$this->tearDownFiles();
	}

	public function doTestGetLocalCopy( $source, $content ) {
		$backendName = $this->backendClass();

		$this->prepare( array( 'dir' => dirname( $source ) ) );

		$status = $this->backend->doOperation(
			array( 'op' => 'create', 'content' => $content, 'dst' => $source ) );
		$this->assertEquals( array(), $status->errors,
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
		$cases[] = array( "$base/unittest-cont1/a/z/some_file.txt", "some file contents" );
		$cases[] = array( "$base/unittest-cont1/a/some-other_file.txt", "more file contents" );

		return $cases;
	}

	/**
	 * @dataProvider provider_testGetLocalReference
	 */
	public function testGetLocalReference( $source, $content ) {
		$this->backend = $this->singleBackend;
		$this->tearDownFiles();
		$this->doTestGetLocalReference( $source, $content );
		$this->tearDownFiles();

		$this->backend = $this->multiBackend;
		$this->tearDownFiles();
		$this->doTestGetLocalReference( $source, $content );
		$this->tearDownFiles();
	}

	private function doTestGetLocalReference( $source, $content ) {
		$backendName = $this->backendClass();

		$this->prepare( array( 'dir' => dirname( $source ) ) );

		$status = $this->backend->doOperation(
			array( 'op' => 'create', 'content' => $content, 'dst' => $source ) );
		$this->assertEquals( array(), $status->errors,
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
		$cases[] = array( "$base/unittest-cont1/a/z/some_file.txt", "some file contents" );
		$cases[] = array( "$base/unittest-cont1/a/some-other_file.txt", "more file contents" );

		return $cases;
	}

	/**
	 * @dataProvider provider_testPrepareAndClean
	 */
	public function testPrepareAndClean( $path, $isOK ) {
		$this->backend = $this->singleBackend;
		$this->doTestPrepareAndClean( $path, $isOK );
		$this->tearDownFiles();

		$this->backend = $this->multiBackend;
		$this->doTestPrepareAndClean( $path, $isOK );
		$this->tearDownFiles();
	}

	function provider_testPrepareAndClean() {
		$base = $this->baseStorePath();
		return array(
			array( "$base/unittest-cont1/a/z/some_file1.txt", true ),
			array( "$base/unittest-cont2/a/z/some_file2.txt", true ),
			# Specific to FS backend with no basePath field set
			#array( "$base/unittest-cont3/a/z/some_file3.txt", false ),
		);
	}

	function doTestPrepareAndClean( $path, $isOK ) {
		$backendName = $this->backendClass();

		$status = $this->prepare( array( 'dir' => dirname( $path ) ) );
		if ( $isOK ) {
			$this->assertEquals( array(), $status->errors,
				"Preparing dir $path succeeded without warnings ($backendName)." );
			$this->assertEquals( true, $status->isOK(),
				"Preparing dir $path succeeded ($backendName)." );
		} else {
			$this->assertEquals( false, $status->isOK(),
				"Preparing dir $path failed ($backendName)." );
		}

		$status = $this->backend->clean( array( 'dir' => dirname( $path ) ) );
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
		$this->tearDownFiles();
		$this->doTestDoOperations();
		$this->tearDownFiles();

		$this->backend = $this->multiBackend;
		$this->tearDownFiles();
		$this->doTestDoOperations();
		$this->tearDownFiles();

		$this->backend = $this->singleBackend;
		$this->tearDownFiles();
		$this->doTestDoOperationsFailing();
		$this->tearDownFiles();

		$this->backend = $this->multiBackend;
		$this->tearDownFiles();
		$this->doTestDoOperationsFailing();
		$this->tearDownFiles();

		// @TODO: test some cases where the ops should fail
	}

	function doTestDoOperations() {
		$base = $this->baseStorePath();

		$fileA = "$base/unittest-cont1/a/b/fileA.txt";
		$fileAContents = '3tqtmoeatmn4wg4qe-mg3qt3 tq';
		$fileB = "$base/unittest-cont1/a/b/fileB.txt";
		$fileBContents = 'g-jmq3gpqgt3qtg q3GT ';
		$fileC = "$base/unittest-cont1/a/b/fileC.txt";
		$fileCContents = 'eigna[ogmewt 3qt g3qg flew[ag';
		$fileD = "$base/unittest-cont1/a/b/fileD.txt";

		$this->prepare( array( 'dir' => dirname( $fileA ) ) );
		$this->backend->create( array( 'dst' => $fileA, 'content' => $fileAContents ) );
		$this->prepare( array( 'dir' => dirname( $fileB ) ) );
		$this->backend->create( array( 'dst' => $fileB, 'content' => $fileBContents ) );
		$this->prepare( array( 'dir' => dirname( $fileC ) ) );
		$this->backend->create( array( 'dst' => $fileC, 'content' => $fileCContents ) );

		$status = $this->backend->doOperations( array(
			array( 'op' => 'copy', 'src' => $fileA, 'dst' => $fileC, 'overwrite' => 1 ),
			// Now: A:<A>, B:<B>, C:<A>, D:<empty> (file:<orginal contents>)
			array( 'op' => 'copy', 'src' => $fileC, 'dst' => $fileA, 'overwriteSame' => 1 ),
			// Now: A:<A>, B:<B>, C:<A>, D:<empty>
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
			array( 'op' => 'null' ),
			// Does nothing
		) );

		$this->assertEquals( array(), $status->errors, "Operation batch succeeded" );
		$this->assertEquals( true, $status->isOK(), "Operation batch succeeded" );
		$this->assertEquals( 13, count( $status->success ),
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
	}

	function doTestDoOperationsFailing() {
		$base = $this->baseStorePath();

		$fileA = "$base/unittest-cont2/a/b/fileA.txt";
		$fileAContents = '3tqtmoeatmn4wg4qe-mg3qt3 tq';
		$fileB = "$base/unittest-cont2/a/b/fileB.txt";
		$fileBContents = 'g-jmq3gpqgt3qtg q3GT ';
		$fileC = "$base/unittest-cont2/a/b/fileC.txt";
		$fileCContents = 'eigna[ogmewt 3qt g3qg flew[ag';
		$fileD = "$base/unittest-cont2/a/b/fileD.txt";

		$this->prepare( array( 'dir' => dirname( $fileA ) ) );
		$this->backend->create( array( 'dst' => $fileA, 'content' => $fileAContents ) );
		$this->prepare( array( 'dir' => dirname( $fileB ) ) );
		$this->backend->create( array( 'dst' => $fileB, 'content' => $fileBContents ) );
		$this->prepare( array( 'dir' => dirname( $fileC ) ) );
		$this->backend->create( array( 'dst' => $fileC, 'content' => $fileCContents ) );

		$status = $this->backend->doOperations( array(
			array( 'op' => 'copy', 'src' => $fileA, 'dst' => $fileC, 'overwrite' => 1 ),
			// Now: A:<A>, B:<B>, C:<A>, D:<empty> (file:<orginal contents>)
			array( 'op' => 'copy', 'src' => $fileC, 'dst' => $fileA, 'overwriteSame' => 1 ),
			// Now: A:<A>, B:<B>, C:<A>, D:<empty>
			array( 'op' => 'copy', 'src' => $fileB, 'dst' => $fileD, 'overwrite' => 1 ),
			// Now: A:<A>, B:<B>, C:<A>, D:<B>
			array( 'op' => 'move', 'src' => $fileC, 'dst' => $fileD ),
			// Now: A:<A>, B:<B>, C:<A>, D:<empty> (failed)
			array( 'op' => 'move', 'src' => $fileB, 'dst' => $fileC, 'overwriteSame' => 1 ),
			// Now: A:<A>, B:<B>, C:<A>, D:<empty> (failed)
			array( 'op' => 'move', 'src' => $fileB, 'dst' => $fileA, 'overwrite' => 1 ),
			// Now: A:<B>, B:<empty>, C:<A>, D:<empty>
			array( 'op' => 'delete', 'src' => $fileD ),
			// Now: A:<B>, B:<empty>, C:<A>, D:<empty>
			array( 'op' => 'null' ),
			// Does nothing
		), array( 'force' => 1 ) );

		$this->assertNotEquals( array(), $status->errors, "Operation had warnings" );
		$this->assertEquals( true, $status->isOK(), "Operation batch succeeded" );
		$this->assertEquals( 8, count( $status->success ),
			"Operation batch has correct success array" );

		$this->assertEquals( false, $this->backend->fileExists( array( 'src' => $fileB ) ),
			"File does not exist at $fileB" );
		$this->assertEquals( false, $this->backend->fileExists( array( 'src' => $fileD ) ),
			"File does not exist at $fileD" );

		$this->assertEquals( true, $this->backend->fileExists( array( 'src' => $fileA ) ),
			"File does not exist at $fileA" );
		$this->assertEquals( true, $this->backend->fileExists( array( 'src' => $fileC ) ),
			"File exists at $fileC" );
		$this->assertEquals( $fileBContents,
			$this->backend->getFileContents( array( 'src' => $fileA ) ),
			"Correct file contents of $fileA" );
		$this->assertEquals( strlen( $fileBContents ),
			$this->backend->getFileSize( array( 'src' => $fileA ) ),
			"Correct file size of $fileA" );
		$this->assertEquals( wfBaseConvert( sha1( $fileBContents ), 16, 36, 31 ),
			$this->backend->getFileSha1Base36( array( 'src' => $fileA ) ),
			"Correct file SHA-1 of $fileA" );
	}

	public function testGetFileList() {
		$this->backend = $this->singleBackend;
		$this->tearDownFiles();
		$this->doTestGetFileList();
		$this->tearDownFiles();

		$this->backend = $this->multiBackend;
		$this->tearDownFiles();
		$this->doTestGetFileList();
		$this->tearDownFiles();
	}

	private function doTestGetFileList() {
		$backendName = $this->backendClass();

		$base = $this->baseStorePath();
		$files = array(
			"$base/unittest-cont1/test1.txt",
			"$base/unittest-cont1/test2.txt",
			"$base/unittest-cont1/test3.txt",
			"$base/unittest-cont1/subdir1/test1.txt",
			"$base/unittest-cont1/subdir1/test2.txt",
			"$base/unittest-cont1/subdir2/test3.txt",
			"$base/unittest-cont1/subdir2/test4.txt",
			"$base/unittest-cont1/subdir2/subdir/test1.txt",
			"$base/unittest-cont1/subdir2/subdir/test2.txt",
			"$base/unittest-cont1/subdir2/subdir/test3.txt",
			"$base/unittest-cont1/subdir2/subdir/test4.txt",
			"$base/unittest-cont1/subdir2/subdir/test5.txt",
			"$base/unittest-cont1/subdir2/subdir/sub/test0.txt",
			"$base/unittest-cont1/subdir2/subdir/sub/120-px-file.txt",
		);

		// Add the files
		$ops = array();
		foreach ( $files as $file ) {
			$this->prepare( array( 'dir' => dirname( $file ) ) );
			$ops[] = array( 'op' => 'create', 'content' => 'xxy', 'dst' => $file );
		}
		$status = $this->backend->doOperations( $ops );
		$this->assertEquals( array(), $status->errors,
			"Creation of files succeeded ($backendName)." );
		$this->assertEquals( true, $status->isOK(),
			"Creation of files succeeded with OK status ($backendName)." );

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
		$iter = $this->backend->getFileList( array( 'dir' => "$base/unittest-cont1" ) );
		foreach ( $iter as $file ) {
			$list[] = $file;
		}
		sort( $list );

		$this->assertEquals( $expected, $list, "Correct file listing ($backendName)." );

		// Actual listing (with trailing slash)
		$list = array();
		$iter = $this->backend->getFileList( array( 'dir' => "$base/unittest-cont1/" ) );
		foreach ( $iter as $file ) {
			$list[] = $file;
		}
		sort( $list );

		$this->assertEquals( $expected, $list, "Correct file listing ($backendName)." );

		// Expected listing
		$expected = array(
			"test1.txt",
			"test2.txt",
			"test3.txt",
			"test4.txt",
			"test5.txt",
			"sub/test0.txt",
			"sub/120-px-file.txt",
		);
		sort( $expected );

		// Actual listing (no trailing slash)
		$list = array();
		$iter = $this->backend->getFileList( array( 'dir' => "$base/unittest-cont1/subdir2/subdir" ) );
		foreach ( $iter as $file ) {
			$list[] = $file;
		}
		sort( $list );

		$this->assertEquals( $expected, $list, "Correct file listing ($backendName)." );

		// Actual listing (with trailing slash)
		$list = array();
		$iter = $this->backend->getFileList( array( 'dir' => "$base/unittest-cont1/subdir2/subdir/" ) );
		foreach ( $iter as $file ) {
			$list[] = $file;
		}
		sort( $list );

		$this->assertEquals( $expected, $list, "Correct file listing ($backendName)." );

		// Actual listing (using iterator second time)
		$list = array();
		foreach ( $iter as $file ) {
			$list[] = $file;
		}
		sort( $list );

		$this->assertEquals( $expected, $list, "Correct file listing ($backendName), second iteration." );

		foreach ( $files as $file ) { // clean up
			$this->backend->doOperation( array( 'op' => 'delete', 'src' => $file ) );
		}

		$iter = $this->backend->getFileList( array( 'dir' => "$base/unittest-cont1/not/exists" ) );
		foreach ( $iter as $iter ) {} // no errors
	}

	// test helper wrapper for backend prepare() function
	private function prepare( array $params ) {
		$this->dirsToPrune[] = $params['dir'];
		return $this->backend->prepare( $params );
	}

	function tearDownFiles() {
		foreach ( $this->filesToPrune as $file ) {
			@unlink( $file );
		}
		$containers = array( 'unittest-cont1', 'unittest-cont2', 'unittest-cont3' );
		foreach ( $containers as $container ) {
			$this->deleteFiles( $container );
		}
		foreach ( $this->dirsToPrune as $dir ) {
			$this->recursiveClean( $dir );
		}
		$this->filesToPrune = $this->dirsToPrune = array();
	}

	private function deleteFiles( $container ) {
		$base = $this->baseStorePath();
		$iter = $this->backend->getFileList( array( 'dir' => "$base/$container" ) );
		if ( $iter ) {
			foreach ( $iter as $file ) {
				$this->backend->delete( array( 'src' => "$base/$container/$file" ), array( 'force' => 1 ) );
			}
		}
	}

	private function recursiveClean( $dir ) {
		do {
			if ( !$this->backend->clean( array( 'dir' => $dir ) )->isOK() ) {
				break;
			}
		} while ( $dir = FileBackend::parentStoragePath( $dir ) );
	}

	function tearDown() {
		parent::tearDown();
	}
}
