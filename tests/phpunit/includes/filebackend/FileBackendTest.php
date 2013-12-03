<?php

/**
 * @group FileRepo
 * @group FileBackend
 * @group medium
 */
class FileBackendTest extends MediaWikiTestCase {

	/** @var FileBackend */
	private $backend;
	/** @var FileBackendMultiWrite */
	private $multiBackend;
	/** @var FSFileBackend */
	public $singleBackend;
	private $filesToPrune = array();
	private static $backendToUse;

	protected function setUp() {
		global $wgFileBackends;
		parent::setUp();
		$uniqueId = time() . '-' . mt_rand();
		$tmpPrefix = wfTempDir() . '/filebackend-unittest-' . $uniqueId;
		if ( $this->getCliArg( 'use-filebackend=' ) ) {
			if ( self::$backendToUse ) {
				$this->singleBackend = self::$backendToUse;
			} else {
				$name = $this->getCliArg( 'use-filebackend=' );
				$useConfig = array();
				foreach ( $wgFileBackends as $conf ) {
					if ( $conf['name'] == $name ) {
						$useConfig = $conf;
						break;
					}
				}
				$useConfig['name'] = 'localtesting'; // swap name
				$useConfig['shardViaHashLevels'] = array( // test sharding
					'unittest-cont1' => array( 'levels' => 1, 'base' => 16, 'repeat' => 1 )
				);
				$class = $useConfig['class'];
				self::$backendToUse = new $class( $useConfig );
				$this->singleBackend = self::$backendToUse;
			}
		} else {
			$this->singleBackend = new FSFileBackend( array(
				'name' => 'localtesting',
				'lockManager' => 'fsLockManager',
				#'parallelize' => 'implicit',
				'wikiId' => wfWikiID() . $uniqueId,
				'containerPaths' => array(
					'unittest-cont1' => "{$tmpPrefix}-localtesting-cont1",
					'unittest-cont2' => "{$tmpPrefix}-localtesting-cont2" )
			) );
		}
		$this->multiBackend = new FileBackendMultiWrite( array(
			'name' => 'localtesting',
			'lockManager' => 'fsLockManager',
			'parallelize' => 'implicit',
			'wikiId' => wfWikiId() . $uniqueId,
			'backends' => array(
				array(
					'name' => 'localmultitesting1',
					'class' => 'FSFileBackend',
					'lockManager' => 'nullLockManager',
					'containerPaths' => array(
						'unittest-cont1' => "{$tmpPrefix}-localtestingmulti1-cont1",
						'unittest-cont2' => "{$tmpPrefix}-localtestingmulti1-cont2" ),
					'isMultiMaster' => false
				),
				array(
					'name' => 'localmultitesting2',
					'class' => 'FSFileBackend',
					'lockManager' => 'nullLockManager',
					'containerPaths' => array(
						'unittest-cont1' => "{$tmpPrefix}-localtestingmulti2-cont1",
						'unittest-cont2' => "{$tmpPrefix}-localtestingmulti2-cont2" ),
					'isMultiMaster' => true
				)
			)
		) );
		$this->filesToPrune = array();
	}

	private static function baseStorePath() {
		return 'mwstore://localtesting';
	}

	private function backendClass() {
		return get_class( $this->backend );
	}

	/**
	 * @dataProvider provider_testIsStoragePath
	 * @covers FileBackend::isStoragePath
	 */
	public function testIsStoragePath( $path, $isStorePath ) {
		$this->assertEquals( $isStorePath, FileBackend::isStoragePath( $path ),
			"FileBackend::isStoragePath on path '$path'" );
	}

	public static function provider_testIsStoragePath() {
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
	 * @covers FileBackend::splitStoragePath
	 */
	public function testSplitStoragePath( $path, $res ) {
		$this->assertEquals( $res, FileBackend::splitStoragePath( $path ),
			"FileBackend::splitStoragePath on path '$path'" );
	}

	public static function provider_testSplitStoragePath() {
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
	 * @covers FileBackend::normalizeStoragePath
	 */
	public function testNormalizeStoragePath( $path, $res ) {
		$this->assertEquals( $res, FileBackend::normalizeStoragePath( $path ),
			"FileBackend::normalizeStoragePath on path '$path'" );
	}

	public static function provider_normalizeStoragePath() {
		return array(
			array( 'mwstore://backend/container', 'mwstore://backend/container' ),
			array( 'mwstore://backend/container/', 'mwstore://backend/container' ),
			array( 'mwstore://backend/container/path', 'mwstore://backend/container/path' ),
			array( 'mwstore://backend/container//path', 'mwstore://backend/container/path' ),
			array( 'mwstore://backend/container///path', 'mwstore://backend/container/path' ),
			array( 'mwstore://backend/container///path//to///obj', 'mwstore://backend/container/path/to/obj' ),
			array( 'mwstore://', null ),
			array( 'mwstore://backend', null ),
			array( 'mwstore://backend//container/path', null ),
			array( 'mwstore://backend//container//path', null ),
			array( 'mwstore:///', null ),
			array( 'mwstore:/', null ),
			array( 'mwstore:', null ),
		);
	}

	/**
	 * @dataProvider provider_testParentStoragePath
	 * @covers FileBackend::parentStoragePath
	 */
	public function testParentStoragePath( $path, $res ) {
		$this->assertEquals( $res, FileBackend::parentStoragePath( $path ),
			"FileBackend::parentStoragePath on path '$path'" );
	}

	public static function provider_testParentStoragePath() {
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
	 * @covers FileBackend::extensionFromPath
	 */
	public function testExtensionFromPath( $path, $res ) {
		$this->assertEquals( $res, FileBackend::extensionFromPath( $path ),
			"FileBackend::extensionFromPath on path '$path'" );
	}

	public static function provider_testExtensionFromPath() {
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

	/**
	 * @covers FileBackend::doOperation
	 */
	private function doTestStore( $op ) {
		$backendName = $this->backendClass();

		$source = $op['src'];
		$dest = $op['dst'];
		$this->prepare( array( 'dir' => dirname( $dest ) ) );

		file_put_contents( $source, "Unit test file" );

		if ( isset( $op['overwrite'] ) || isset( $op['overwriteSame'] ) ) {
			$this->backend->store( $op );
		}

		$status = $this->backend->doOperation( $op );

		$this->assertGoodStatus( $status,
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

		$this->assertBackendPathsConsistent( array( $dest ) );
	}

	public static function provider_testStore() {
		$cases = array();

		$tmpName = TempFSFile::factory( "unittests_", 'txt' )->getPath();
		$toPath = self::baseStorePath() . '/unittest-cont1/e/fun/obj1.txt';
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
	 * @covers FileBackend::doOperation
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

	private function doTestCopy( $op ) {
		$backendName = $this->backendClass();

		$source = $op['src'];
		$dest = $op['dst'];
		$this->prepare( array( 'dir' => dirname( $source ) ) );
		$this->prepare( array( 'dir' => dirname( $dest ) ) );

		if ( isset( $op['ignoreMissingSource'] ) ) {
			$status = $this->backend->doOperation( $op );
			$this->assertGoodStatus( $status,
				"Move from $source to $dest succeeded without warnings ($backendName)." );
			$this->assertEquals( array( 0 => true ), $status->success,
				"Move from $source to $dest has proper 'success' field in Status ($backendName)." );
			$this->assertEquals( false, $this->backend->fileExists( array( 'src' => $source ) ),
				"Source file $source does not exist ($backendName)." );
			$this->assertEquals( false, $this->backend->fileExists( array( 'src' => $dest ) ),
				"Destination file $dest does not exist ($backendName)." );

			return; // done
		}

		$status = $this->backend->doOperation(
			array( 'op' => 'create', 'content' => 'blahblah', 'dst' => $source ) );
		$this->assertGoodStatus( $status,
			"Creation of file at $source succeeded ($backendName)." );

		if ( isset( $op['overwrite'] ) || isset( $op['overwriteSame'] ) ) {
			$this->backend->copy( $op );
		}

		$status = $this->backend->doOperation( $op );

		$this->assertGoodStatus( $status,
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

		$this->assertBackendPathsConsistent( array( $source, $dest ) );
	}

	public static function provider_testCopy() {
		$cases = array();

		$source = self::baseStorePath() . '/unittest-cont1/e/file.txt';
		$dest = self::baseStorePath() . '/unittest-cont2/a/fileMoved.txt';

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

		$op2 = $op;
		$op2['ignoreMissingSource'] = true;
		$cases[] = array(
			$op2, // operation
			$source, // source
			$dest, // dest
		);

		$op2 = $op;
		$op2['ignoreMissingSource'] = true;
		$cases[] = array(
			$op2, // operation
			self::baseStorePath() . '/unittest-cont-bad/e/file.txt', // source
			$dest, // dest
		);

		return $cases;
	}

	/**
	 * @dataProvider provider_testMove
	 * @covers FileBackend::doOperation
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

		if ( isset( $op['ignoreMissingSource'] ) ) {
			$status = $this->backend->doOperation( $op );
			$this->assertGoodStatus( $status,
				"Move from $source to $dest succeeded without warnings ($backendName)." );
			$this->assertEquals( array( 0 => true ), $status->success,
				"Move from $source to $dest has proper 'success' field in Status ($backendName)." );
			$this->assertEquals( false, $this->backend->fileExists( array( 'src' => $source ) ),
				"Source file $source does not exist ($backendName)." );
			$this->assertEquals( false, $this->backend->fileExists( array( 'src' => $dest ) ),
				"Destination file $dest does not exist ($backendName)." );

			return; // done
		}

		$status = $this->backend->doOperation(
			array( 'op' => 'create', 'content' => 'blahblah', 'dst' => $source ) );
		$this->assertGoodStatus( $status,
			"Creation of file at $source succeeded ($backendName)." );

		if ( isset( $op['overwrite'] ) || isset( $op['overwriteSame'] ) ) {
			$this->backend->copy( $op );
		}

		$status = $this->backend->doOperation( $op );
		$this->assertGoodStatus( $status,
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

		$this->assertBackendPathsConsistent( array( $source, $dest ) );
	}

	public static function provider_testMove() {
		$cases = array();

		$source = self::baseStorePath() . '/unittest-cont1/e/file.txt';
		$dest = self::baseStorePath() . '/unittest-cont2/a/fileMoved.txt';

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

		$op2 = $op;
		$op2['ignoreMissingSource'] = true;
		$cases[] = array(
			$op2, // operation
			$source, // source
			$dest, // dest
		);

		$op2 = $op;
		$op2['ignoreMissingSource'] = true;
		$cases[] = array(
			$op2, // operation
			self::baseStorePath() . '/unittest-cont-bad/e/file.txt', // source
			$dest, // dest
		);

		return $cases;
	}

	/**
	 * @dataProvider provider_testDelete
	 * @covers FileBackend::doOperation
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
			$this->assertGoodStatus( $status,
				"Creation of file at $source succeeded ($backendName)." );
		}

		$status = $this->backend->doOperation( $op );
		if ( $okStatus ) {
			$this->assertGoodStatus( $status,
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

		$this->assertBackendPathsConsistent( array( $source ) );
	}

	public static function provider_testDelete() {
		$cases = array();

		$source = self::baseStorePath() . '/unittest-cont1/e/myfacefile.txt';

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

		$op['ignoreMissingSource'] = true;
		$op['src'] = self::baseStorePath() . '/unittest-cont-bad/e/file.txt';
		$cases[] = array(
			$op, // operation
			false, // without source
			true // succeeds
		);

		return $cases;
	}

	/**
	 * @dataProvider provider_testDescribe
	 * @covers FileBackend::doOperation
	 */
	public function testDescribe( $op, $withSource, $okStatus ) {
		$this->backend = $this->singleBackend;
		$this->tearDownFiles();
		$this->doTestDescribe( $op, $withSource, $okStatus );
		$this->tearDownFiles();

		$this->backend = $this->multiBackend;
		$this->tearDownFiles();
		$this->doTestDescribe( $op, $withSource, $okStatus );
		$this->tearDownFiles();
	}

	private function doTestDescribe( $op, $withSource, $okStatus ) {
		$backendName = $this->backendClass();

		$source = $op['src'];
		$this->prepare( array( 'dir' => dirname( $source ) ) );

		if ( $withSource ) {
			$status = $this->backend->doOperation(
				array( 'op' => 'create', 'content' => 'blahblah', 'dst' => $source ) );
			$this->assertGoodStatus( $status,
				"Creation of file at $source succeeded ($backendName)." );
		}

		$status = $this->backend->doOperation( $op );
		if ( $okStatus ) {
			$this->assertGoodStatus( $status,
				"Describe of file at $source succeeded without warnings ($backendName)." );
			$this->assertEquals( true, $status->isOK(),
				"Describe of file at $source succeeded ($backendName)." );
			$this->assertEquals( array( 0 => true ), $status->success,
				"Describe of file at $source has proper 'success' field in Status ($backendName)." );
		} else {
			$this->assertEquals( false, $status->isOK(),
				"Describe of file at $source failed ($backendName)." );
		}

		$this->assertBackendPathsConsistent( array( $source ) );
	}

	public static function provider_testDescribe() {
		$cases = array();

		$source = self::baseStorePath() . '/unittest-cont1/e/myfacefile.txt';

		$op = array( 'op' => 'describe', 'src' => $source,
			'headers' => array( 'X-Content-Length' => '91.3', 'Content-Old-Header' => '' ),
			'disposition' => 'inline' );
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

		return $cases;
	}

	/**
	 * @dataProvider provider_testCreate
	 * @covers FileBackend::doOperation
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
			$this->assertGoodStatus( $status,
				"Creation of file at $dest succeeded ($backendName)." );
		}

		$status = $this->backend->doOperation( $op );
		if ( $okStatus ) {
			$this->assertGoodStatus( $status,
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

		$this->assertBackendPathsConsistent( array( $dest ) );
	}

	/**
	 * @dataProvider provider_testCreate
	 */
	public static function provider_testCreate() {
		$cases = array();

		$dest = self::baseStorePath() . '/unittest-cont2/a/myspacefile.txt';

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
	 * @covers FileBackend::doQuickOperations
	 */
	public function testDoQuickOperations() {
		$this->backend = $this->singleBackend;
		$this->doTestDoQuickOperations();
		$this->tearDownFiles();

		$this->backend = $this->multiBackend;
		$this->doTestDoQuickOperations();
		$this->tearDownFiles();
	}

	private function doTestDoQuickOperations() {
		$backendName = $this->backendClass();

		$base = self::baseStorePath();
		$files = array(
			"$base/unittest-cont1/e/fileA.a",
			"$base/unittest-cont1/e/fileB.a",
			"$base/unittest-cont1/e/fileC.a"
		);
		$createOps = array();
		$purgeOps = array();
		foreach ( $files as $path ) {
			$status = $this->prepare( array( 'dir' => dirname( $path ) ) );
			$this->assertGoodStatus( $status,
				"Preparing $path succeeded without warnings ($backendName)." );
			$createOps[] = array( 'op' => 'create', 'dst' => $path, 'content' => mt_rand( 0, 50000 ) );
			$copyOps[] = array( 'op' => 'copy', 'src' => $path, 'dst' => "$path-2" );
			$moveOps[] = array( 'op' => 'move', 'src' => "$path-2", 'dst' => "$path-3" );
			$purgeOps[] = array( 'op' => 'delete', 'src' => $path );
			$purgeOps[] = array( 'op' => 'delete', 'src' => "$path-3" );
		}
		$purgeOps[] = array( 'op' => 'null' );

		$this->assertGoodStatus(
			$this->backend->doQuickOperations( $createOps ),
			"Creation of source files succeeded ($backendName)." );
		foreach ( $files as $file ) {
			$this->assertTrue( $this->backend->fileExists( array( 'src' => $file ) ),
				"File $file exists." );
		}

		$this->assertGoodStatus(
			$this->backend->doQuickOperations( $copyOps ),
			"Quick copy of source files succeeded ($backendName)." );
		foreach ( $files as $file ) {
			$this->assertTrue( $this->backend->fileExists( array( 'src' => "$file-2" ) ),
				"File $file-2 exists." );
		}

		$this->assertGoodStatus(
			$this->backend->doQuickOperations( $moveOps ),
			"Quick move of source files succeeded ($backendName)." );
		foreach ( $files as $file ) {
			$this->assertTrue( $this->backend->fileExists( array( 'src' => "$file-3" ) ),
				"File $file-3 move in." );
			$this->assertFalse( $this->backend->fileExists( array( 'src' => "$file-2" ) ),
				"File $file-2 moved away." );
		}

		$this->assertGoodStatus(
			$this->backend->quickCopy( array( 'src' => $files[0], 'dst' => $files[0] ) ),
			"Copy of file {$files[0]} over itself succeeded ($backendName)." );
		$this->assertTrue( $this->backend->fileExists( array( 'src' => $files[0] ) ),
			"File {$files[0]} still exists." );

		$this->assertGoodStatus(
			$this->backend->quickMove( array( 'src' => $files[0], 'dst' => $files[0] ) ),
			"Move of file {$files[0]} over itself succeeded ($backendName)." );
		$this->assertTrue( $this->backend->fileExists( array( 'src' => $files[0] ) ),
			"File {$files[0]} still exists." );

		$this->assertGoodStatus(
			$this->backend->doQuickOperations( $purgeOps ),
			"Quick deletion of source files succeeded ($backendName)." );
		foreach ( $files as $file ) {
			$this->assertFalse( $this->backend->fileExists( array( 'src' => $file ) ),
				"File $file purged." );
			$this->assertFalse( $this->backend->fileExists( array( 'src' => "$file-3" ) ),
				"File $file-3 purged." );
		}
	}

	/**
	 * @dataProvider provider_testConcatenate
	 */
	public function testConcatenate( $op, $srcs, $srcsContent, $alreadyExists, $okStatus ) {
		$this->filesToPrune[] = $op['dst'];

		$this->backend = $this->singleBackend;
		$this->tearDownFiles();
		$this->doTestConcatenate( $op, $srcs, $srcsContent, $alreadyExists, $okStatus );
		$this->filesToPrune[] = $op['dst']; # avoid file leaking
		$this->tearDownFiles();

		$this->backend = $this->multiBackend;
		$this->tearDownFiles();
		$this->doTestConcatenate( $op, $srcs, $srcsContent, $alreadyExists, $okStatus );
		$this->filesToPrune[] = $op['dst']; # avoid file leaking
		$this->tearDownFiles();
	}

	private function doTestConcatenate( $params, $srcs, $srcsContent, $alreadyExists, $okStatus ) {
		$backendName = $this->backendClass();

		$expContent = '';
		// Create sources
		$ops = array();
		foreach ( $srcs as $i => $source ) {
			$this->prepare( array( 'dir' => dirname( $source ) ) );
			$ops[] = array(
				'op' => 'create', // operation
				'dst' => $source, // source
				'content' => $srcsContent[$i]
			);
			$expContent .= $srcsContent[$i];
		}
		$status = $this->backend->doOperations( $ops );

		$this->assertGoodStatus( $status,
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
			$this->assertGoodStatus( $status,
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

	public static function provider_testConcatenate() {
		$cases = array();

		$rand = mt_rand( 0, 2000000000 ) . time();
		$dest = wfTempDir() . "/randomfile!$rand.txt";
		$srcs = array(
			self::baseStorePath() . '/unittest-cont1/e/file1.txt',
			self::baseStorePath() . '/unittest-cont1/e/file2.txt',
			self::baseStorePath() . '/unittest-cont1/e/file3.txt',
			self::baseStorePath() . '/unittest-cont1/e/file4.txt',
			self::baseStorePath() . '/unittest-cont1/e/file5.txt',
			self::baseStorePath() . '/unittest-cont1/e/file6.txt',
			self::baseStorePath() . '/unittest-cont1/e/file7.txt',
			self::baseStorePath() . '/unittest-cont1/e/file8.txt',
			self::baseStorePath() . '/unittest-cont1/e/file9.txt',
			self::baseStorePath() . '/unittest-cont1/e/file10.txt'
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
	 * @covers FileBackend::getFileStat
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
			$status = $this->create( array( 'dst' => $path, 'content' => $content ) );
			$this->assertGoodStatus( $status,
				"Creation of file at $path succeeded ($backendName)." );

			$size = $this->backend->getFileSize( array( 'src' => $path ) );
			$time = $this->backend->getFileTimestamp( array( 'src' => $path ) );
			$stat = $this->backend->getFileStat( array( 'src' => $path ) );

			$this->assertEquals( strlen( $content ), $size,
				"Correct file size of '$path'" );
			$this->assertTrue( abs( time() - wfTimestamp( TS_UNIX, $time ) ) < 10,
				"Correct file timestamp of '$path'" );

			$size = $stat['size'];
			$time = $stat['mtime'];
			$this->assertEquals( strlen( $content ), $size,
				"Correct file size of '$path'" );
			$this->assertTrue( abs( time() - wfTimestamp( TS_UNIX, $time ) ) < 10,
				"Correct file timestamp of '$path'" );

			$this->backend->clearCache( array( $path ) );

			$size = $this->backend->getFileSize( array( 'src' => $path ) );

			$this->assertEquals( strlen( $content ), $size,
				"Correct file size of '$path'" );

			$this->backend->preloadCache( array( $path ) );

			$size = $this->backend->getFileSize( array( 'src' => $path ) );

			$this->assertEquals( strlen( $content ), $size,
				"Correct file size of '$path'" );
		} else {
			$size = $this->backend->getFileSize( array( 'src' => $path ) );
			$time = $this->backend->getFileTimestamp( array( 'src' => $path ) );
			$stat = $this->backend->getFileStat( array( 'src' => $path ) );

			$this->assertFalse( $size, "Correct file size of '$path'" );
			$this->assertFalse( $time, "Correct file timestamp of '$path'" );
			$this->assertFalse( $stat, "Correct file stat of '$path'" );
		}
	}

	public static function provider_testGetFileStat() {
		$cases = array();

		$base = self::baseStorePath();
		$cases[] = array( "$base/unittest-cont1/e/b/z/some_file.txt", "some file contents", true );
		$cases[] = array( "$base/unittest-cont1/e/b/some-other_file.txt", "", true );
		$cases[] = array( "$base/unittest-cont1/e/b/some-diff_file.txt", null, false );

		return $cases;
	}

	/**
	 * @dataProvider provider_testGetFileContents
	 * @covers FileBackend::getFileContents
	 * @covers FileBackend::getFileContentsMulti
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

	private function doTestGetFileContents( $source, $content ) {
		$backendName = $this->backendClass();

		$srcs = (array)$source;
		$content = (array)$content;
		foreach ( $srcs as $i => $src ) {
			$this->prepare( array( 'dir' => dirname( $src ) ) );
			$status = $this->backend->doOperation(
				array( 'op' => 'create', 'content' => $content[$i], 'dst' => $src ) );
			$this->assertGoodStatus( $status,
				"Creation of file at $src succeeded ($backendName)." );
		}

		if ( is_array( $source ) ) {
			$contents = $this->backend->getFileContentsMulti( array( 'srcs' => $source ) );
			foreach ( $contents as $path => $data ) {
				$this->assertNotEquals( false, $data, "Contents of $path exists ($backendName)." );
				$this->assertEquals( current( $content ), $data, "Contents of $path is correct ($backendName)." );
				next( $content );
			}
			$this->assertEquals( $source, array_keys( $contents ), "Contents in right order ($backendName)." );
			$this->assertEquals( count( $source ), count( $contents ), "Contents array size correct ($backendName)." );
		} else {
			$data = $this->backend->getFileContents( array( 'src' => $source ) );
			$this->assertNotEquals( false, $data, "Contents of $source exists ($backendName)." );
			$this->assertEquals( $content[0], $data, "Contents of $source is correct ($backendName)." );
		}
	}

	public static function provider_testGetFileContents() {
		$cases = array();

		$base = self::baseStorePath();
		$cases[] = array( "$base/unittest-cont1/e/b/z/some_file.txt", "some file contents" );
		$cases[] = array( "$base/unittest-cont1/e/b/some-other_file.txt", "more file contents" );
		$cases[] = array(
			array( "$base/unittest-cont1/e/a/x.txt", "$base/unittest-cont1/e/a/y.txt",
				"$base/unittest-cont1/e/a/z.txt" ),
			array( "contents xx", "contents xy", "contents xz" )
		);

		return $cases;
	}

	/**
	 * @dataProvider provider_testGetLocalCopy
	 * @covers FileBackend::getLocalCopy
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

	private function doTestGetLocalCopy( $source, $content ) {
		$backendName = $this->backendClass();

		$srcs = (array)$source;
		$content = (array)$content;
		foreach ( $srcs as $i => $src ) {
			$this->prepare( array( 'dir' => dirname( $src ) ) );
			$status = $this->backend->doOperation(
				array( 'op' => 'create', 'content' => $content[$i], 'dst' => $src ) );
			$this->assertGoodStatus( $status,
				"Creation of file at $src succeeded ($backendName)." );
		}

		if ( is_array( $source ) ) {
			$tmpFiles = $this->backend->getLocalCopyMulti( array( 'srcs' => $source ) );
			foreach ( $tmpFiles as $path => $tmpFile ) {
				$this->assertNotNull( $tmpFile,
					"Creation of local copy of $path succeeded ($backendName)." );
				$contents = file_get_contents( $tmpFile->getPath() );
				$this->assertNotEquals( false, $contents, "Local copy of $path exists ($backendName)." );
				$this->assertEquals( current( $content ), $contents, "Local copy of $path is correct ($backendName)." );
				next( $content );
			}
			$this->assertEquals( $source, array_keys( $tmpFiles ), "Local copies in right order ($backendName)." );
			$this->assertEquals( count( $source ), count( $tmpFiles ), "Local copies array size correct ($backendName)." );
		} else {
			$tmpFile = $this->backend->getLocalCopy( array( 'src' => $source ) );
			$this->assertNotNull( $tmpFile,
				"Creation of local copy of $source succeeded ($backendName)." );
			$contents = file_get_contents( $tmpFile->getPath() );
			$this->assertNotEquals( false, $contents, "Local copy of $source exists ($backendName)." );
			$this->assertEquals( $content[0], $contents, "Local copy of $source is correct ($backendName)." );
		}

		$obj = new stdClass();
		$tmpFile->bind( $obj );
	}

	public static function provider_testGetLocalCopy() {
		$cases = array();

		$base = self::baseStorePath();
		$cases[] = array( "$base/unittest-cont1/e/a/z/some_file.txt", "some file contents" );
		$cases[] = array( "$base/unittest-cont1/e/a/some-other_file.txt", "more file contents" );
		$cases[] = array( "$base/unittest-cont1/e/a/\$odd&.txt", "test file contents" );
		$cases[] = array(
			array( "$base/unittest-cont1/e/a/x.txt", "$base/unittest-cont1/e/a/y.txt",
				"$base/unittest-cont1/e/a/z.txt" ),
			array( "contents xx", "contents xy", "contents xz" )
		);

		return $cases;
	}

	/**
	 * @dataProvider provider_testGetLocalReference
	 * @covers FileBackend::getLocalReference
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

		$srcs = (array)$source;
		$content = (array)$content;
		foreach ( $srcs as $i => $src ) {
			$this->prepare( array( 'dir' => dirname( $src ) ) );
			$status = $this->backend->doOperation(
				array( 'op' => 'create', 'content' => $content[$i], 'dst' => $src ) );
			$this->assertGoodStatus( $status,
				"Creation of file at $src succeeded ($backendName)." );
		}

		if ( is_array( $source ) ) {
			$tmpFiles = $this->backend->getLocalReferenceMulti( array( 'srcs' => $source ) );
			foreach ( $tmpFiles as $path => $tmpFile ) {
				$this->assertNotNull( $tmpFile,
					"Creation of local copy of $path succeeded ($backendName)." );
				$contents = file_get_contents( $tmpFile->getPath() );
				$this->assertNotEquals( false, $contents, "Local ref of $path exists ($backendName)." );
				$this->assertEquals( current( $content ), $contents, "Local ref of $path is correct ($backendName)." );
				next( $content );
			}
			$this->assertEquals( $source, array_keys( $tmpFiles ), "Local refs in right order ($backendName)." );
			$this->assertEquals( count( $source ), count( $tmpFiles ), "Local refs array size correct ($backendName)." );
		} else {
			$tmpFile = $this->backend->getLocalReference( array( 'src' => $source ) );
			$this->assertNotNull( $tmpFile,
				"Creation of local copy of $source succeeded ($backendName)." );
			$contents = file_get_contents( $tmpFile->getPath() );
			$this->assertNotEquals( false, $contents, "Local ref of $source exists ($backendName)." );
			$this->assertEquals( $content[0], $contents, "Local ref of $source is correct ($backendName)." );
		}
	}

	public static function provider_testGetLocalReference() {
		$cases = array();

		$base = self::baseStorePath();
		$cases[] = array( "$base/unittest-cont1/e/a/z/some_file.txt", "some file contents" );
		$cases[] = array( "$base/unittest-cont1/e/a/some-other_file.txt", "more file contents" );
		$cases[] = array( "$base/unittest-cont1/e/a/\$odd&.txt", "test file contents" );
		$cases[] = array(
			array( "$base/unittest-cont1/e/a/x.txt", "$base/unittest-cont1/e/a/y.txt",
				"$base/unittest-cont1/e/a/z.txt" ),
			array( "contents xx", "contents xy", "contents xz" )
		);

		return $cases;
	}

	/**
	 * @covers FileBackend::getLocalCopy
	 * @covers FileBackend::getLocalReference
	 */
	public function testGetLocalCopyAndReference404() {
		$this->backend = $this->singleBackend;
		$this->tearDownFiles();
		$this->doTestGetLocalCopyAndReference404();
		$this->tearDownFiles();

		$this->backend = $this->multiBackend;
		$this->tearDownFiles();
		$this->doTestGetLocalCopyAndReference404();
		$this->tearDownFiles();
	}

	public function doTestGetLocalCopyAndReference404() {
		$backendName = $this->backendClass();

		$base = self::baseStorePath();

		$tmpFile = $this->backend->getLocalCopy( array(
			'src' => "$base/unittest-cont1/not-there" ) );
		$this->assertEquals( null, $tmpFile, "Local copy of not existing file is null ($backendName)." );

		$tmpFile = $this->backend->getLocalReference( array(
			'src' => "$base/unittest-cont1/not-there" ) );
		$this->assertEquals( null, $tmpFile, "Local ref of not existing file is null ($backendName)." );
	}

	/**
	 * @dataProvider provider_testGetFileHttpUrl
	 * @covers FileBackend::getFileHttpUrl
	 */
	public function testGetFileHttpUrl( $source, $content ) {
		$this->backend = $this->singleBackend;
		$this->tearDownFiles();
		$this->doTestGetFileHttpUrl( $source, $content );
		$this->tearDownFiles();

		$this->backend = $this->multiBackend;
		$this->tearDownFiles();
		$this->doTestGetFileHttpUrl( $source, $content );
		$this->tearDownFiles();
	}

	private function doTestGetFileHttpUrl( $source, $content ) {
		$backendName = $this->backendClass();

		$this->prepare( array( 'dir' => dirname( $source ) ) );
		$status = $this->backend->doOperation(
			array( 'op' => 'create', 'content' => $content, 'dst' => $source ) );
		$this->assertGoodStatus( $status,
			"Creation of file at $source succeeded ($backendName)." );

		$url = $this->backend->getFileHttpUrl( array( 'src' => $source ) );

		if ( $url !== null ) { // supported
			$data = Http::request( "GET", $url );
			$this->assertEquals( $content, $data,
				"HTTP GET of URL has right contents ($backendName)." );
		}
	}

	public static function provider_testGetFileHttpUrl() {
		$cases = array();

		$base = self::baseStorePath();
		$cases[] = array( "$base/unittest-cont1/e/a/z/some_file.txt", "some file contents" );
		$cases[] = array( "$base/unittest-cont1/e/a/some-other_file.txt", "more file contents" );
		$cases[] = array( "$base/unittest-cont1/e/a/\$odd&.txt", "test file contents" );

		return $cases;
	}

	/**
	 * @dataProvider provider_testPrepareAndClean
	 * @covers FileBackend::prepare
	 * @covers FileBackend::clean
	 */
	public function testPrepareAndClean( $path, $isOK ) {
		$this->backend = $this->singleBackend;
		$this->doTestPrepareAndClean( $path, $isOK );
		$this->tearDownFiles();

		$this->backend = $this->multiBackend;
		$this->doTestPrepareAndClean( $path, $isOK );
		$this->tearDownFiles();
	}

	public static function provider_testPrepareAndClean() {
		$base = self::baseStorePath();

		return array(
			array( "$base/unittest-cont1/e/a/z/some_file1.txt", true ),
			array( "$base/unittest-cont2/a/z/some_file2.txt", true ),
			# Specific to FS backend with no basePath field set
			#array( "$base/unittest-cont3/a/z/some_file3.txt", false ),
		);
	}

	private function doTestPrepareAndClean( $path, $isOK ) {
		$backendName = $this->backendClass();

		$status = $this->prepare( array( 'dir' => dirname( $path ) ) );
		if ( $isOK ) {
			$this->assertGoodStatus( $status,
				"Preparing dir $path succeeded without warnings ($backendName)." );
			$this->assertEquals( true, $status->isOK(),
				"Preparing dir $path succeeded ($backendName)." );
		} else {
			$this->assertEquals( false, $status->isOK(),
				"Preparing dir $path failed ($backendName)." );
		}

		$status = $this->backend->clean( array( 'dir' => dirname( $path ) ) );
		if ( $isOK ) {
			$this->assertGoodStatus( $status,
				"Cleaning dir $path succeeded without warnings ($backendName)." );
			$this->assertEquals( true, $status->isOK(),
				"Cleaning dir $path succeeded ($backendName)." );
		} else {
			$this->assertEquals( false, $status->isOK(),
				"Cleaning dir $path failed ($backendName)." );
		}
	}

	public function testRecursiveClean() {
		$this->backend = $this->singleBackend;
		$this->doTestRecursiveClean();
		$this->tearDownFiles();

		$this->backend = $this->multiBackend;
		$this->doTestRecursiveClean();
		$this->tearDownFiles();
	}

	/**
	 * @covers FileBackend::clean
	 */
	private function doTestRecursiveClean() {
		$backendName = $this->backendClass();

		$base = self::baseStorePath();
		$dirs = array(
			"$base/unittest-cont1",
			"$base/unittest-cont1/e",
			"$base/unittest-cont1/e/a",
			"$base/unittest-cont1/e/a/b",
			"$base/unittest-cont1/e/a/b/c",
			"$base/unittest-cont1/e/a/b/c/d0",
			"$base/unittest-cont1/e/a/b/c/d1",
			"$base/unittest-cont1/e/a/b/c/d2",
			"$base/unittest-cont1/e/a/b/c/d0/1",
			"$base/unittest-cont1/e/a/b/c/d0/2",
			"$base/unittest-cont1/e/a/b/c/d1/3",
			"$base/unittest-cont1/e/a/b/c/d1/4",
			"$base/unittest-cont1/e/a/b/c/d2/5",
			"$base/unittest-cont1/e/a/b/c/d2/6"
		);
		foreach ( $dirs as $dir ) {
			$status = $this->prepare( array( 'dir' => $dir ) );
			$this->assertGoodStatus( $status,
				"Preparing dir $dir succeeded without warnings ($backendName)." );
		}

		if ( $this->backend instanceof FSFileBackend ) {
			foreach ( $dirs as $dir ) {
				$this->assertEquals( true, $this->backend->directoryExists( array( 'dir' => $dir ) ),
					"Dir $dir exists ($backendName)." );
			}
		}

		$status = $this->backend->clean(
			array( 'dir' => "$base/unittest-cont1", 'recursive' => 1 ) );
		$this->assertGoodStatus( $status,
			"Recursive cleaning of dir $dir succeeded without warnings ($backendName)." );

		foreach ( $dirs as $dir ) {
			$this->assertEquals( false, $this->backend->directoryExists( array( 'dir' => $dir ) ),
				"Dir $dir no longer exists ($backendName)." );
		}
	}

	// @todo testSecure

	/**
	 * @covers FileBackend::doOperations
	 */
	public function testDoOperations() {
		$this->backend = $this->singleBackend;
		$this->tearDownFiles();
		$this->doTestDoOperations();
		$this->tearDownFiles();

		$this->backend = $this->multiBackend;
		$this->tearDownFiles();
		$this->doTestDoOperations();
		$this->tearDownFiles();
	}

	private function doTestDoOperations() {
		$base = self::baseStorePath();

		$fileA = "$base/unittest-cont1/e/a/b/fileA.txt";
		$fileAContents = '3tqtmoeatmn4wg4qe-mg3qt3 tq';
		$fileB = "$base/unittest-cont1/e/a/b/fileB.txt";
		$fileBContents = 'g-jmq3gpqgt3qtg q3GT ';
		$fileC = "$base/unittest-cont1/e/a/b/fileC.txt";
		$fileCContents = 'eigna[ogmewt 3qt g3qg flew[ag';
		$fileD = "$base/unittest-cont1/e/a/b/fileD.txt";

		$this->prepare( array( 'dir' => dirname( $fileA ) ) );
		$this->create( array( 'dst' => $fileA, 'content' => $fileAContents ) );
		$this->prepare( array( 'dir' => dirname( $fileB ) ) );
		$this->create( array( 'dst' => $fileB, 'content' => $fileBContents ) );
		$this->prepare( array( 'dir' => dirname( $fileC ) ) );
		$this->create( array( 'dst' => $fileC, 'content' => $fileCContents ) );
		$this->prepare( array( 'dir' => dirname( $fileD ) ) );

		$status = $this->backend->doOperations( array(
			array( 'op' => 'describe', 'src' => $fileA,
				'headers' => array( 'X-Content-Length' => '91.3' ), 'disposition' => 'inline' ),
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

		$this->assertGoodStatus( $status, "Operation batch succeeded" );
		$this->assertEquals( true, $status->isOK(), "Operation batch succeeded" );
		$this->assertEquals( 14, count( $status->success ),
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

	/**
	 * @covers FileBackend::doOperations
	 */
	public function testDoOperationsPipeline() {
		$this->backend = $this->singleBackend;
		$this->tearDownFiles();
		$this->doTestDoOperationsPipeline();
		$this->tearDownFiles();

		$this->backend = $this->multiBackend;
		$this->tearDownFiles();
		$this->doTestDoOperationsPipeline();
		$this->tearDownFiles();
	}

	// concurrency orientated
	private function doTestDoOperationsPipeline() {
		$base = self::baseStorePath();

		$fileAContents = '3tqtmoeatmn4wg4qe-mg3qt3 tq';
		$fileBContents = 'g-jmq3gpqgt3qtg q3GT ';
		$fileCContents = 'eigna[ogmewt 3qt g3qg flew[ag';

		$tmpNameA = TempFSFile::factory( "unittests_", 'txt' )->getPath();
		file_put_contents( $tmpNameA, $fileAContents );
		$tmpNameB = TempFSFile::factory( "unittests_", 'txt' )->getPath();
		file_put_contents( $tmpNameB, $fileBContents );
		$tmpNameC = TempFSFile::factory( "unittests_", 'txt' )->getPath();
		file_put_contents( $tmpNameC, $fileCContents );

		$this->filesToPrune[] = $tmpNameA; # avoid file leaking
		$this->filesToPrune[] = $tmpNameB; # avoid file leaking
		$this->filesToPrune[] = $tmpNameC; # avoid file leaking

		$fileA = "$base/unittest-cont1/e/a/b/fileA.txt";
		$fileB = "$base/unittest-cont1/e/a/b/fileB.txt";
		$fileC = "$base/unittest-cont1/e/a/b/fileC.txt";
		$fileD = "$base/unittest-cont1/e/a/b/fileD.txt";

		$this->prepare( array( 'dir' => dirname( $fileA ) ) );
		$this->create( array( 'dst' => $fileA, 'content' => $fileAContents ) );
		$this->prepare( array( 'dir' => dirname( $fileB ) ) );
		$this->prepare( array( 'dir' => dirname( $fileC ) ) );
		$this->prepare( array( 'dir' => dirname( $fileD ) ) );

		$status = $this->backend->doOperations( array(
			array( 'op' => 'store', 'src' => $tmpNameA, 'dst' => $fileA, 'overwriteSame' => 1 ),
			array( 'op' => 'store', 'src' => $tmpNameB, 'dst' => $fileB, 'overwrite' => 1 ),
			array( 'op' => 'store', 'src' => $tmpNameC, 'dst' => $fileC, 'overwrite' => 1 ),
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

		$this->assertGoodStatus( $status, "Operation batch succeeded" );
		$this->assertEquals( true, $status->isOK(), "Operation batch succeeded" );
		$this->assertEquals( 16, count( $status->success ),
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

	/**
	 * @covers FileBackend::doOperations
	 */
	public function testDoOperationsFailing() {
		$this->backend = $this->singleBackend;
		$this->tearDownFiles();
		$this->doTestDoOperationsFailing();
		$this->tearDownFiles();

		$this->backend = $this->multiBackend;
		$this->tearDownFiles();
		$this->doTestDoOperationsFailing();
		$this->tearDownFiles();
	}

	private function doTestDoOperationsFailing() {
		$base = self::baseStorePath();

		$fileA = "$base/unittest-cont2/a/b/fileA.txt";
		$fileAContents = '3tqtmoeatmn4wg4qe-mg3qt3 tq';
		$fileB = "$base/unittest-cont2/a/b/fileB.txt";
		$fileBContents = 'g-jmq3gpqgt3qtg q3GT ';
		$fileC = "$base/unittest-cont2/a/b/fileC.txt";
		$fileCContents = 'eigna[ogmewt 3qt g3qg flew[ag';
		$fileD = "$base/unittest-cont2/a/b/fileD.txt";

		$this->prepare( array( 'dir' => dirname( $fileA ) ) );
		$this->create( array( 'dst' => $fileA, 'content' => $fileAContents ) );
		$this->prepare( array( 'dir' => dirname( $fileB ) ) );
		$this->create( array( 'dst' => $fileB, 'content' => $fileBContents ) );
		$this->prepare( array( 'dir' => dirname( $fileC ) ) );
		$this->create( array( 'dst' => $fileC, 'content' => $fileCContents ) );

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

	/**
	 * @covers FileBackend::getFileList
	 */
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
		$base = self::baseStorePath();

		// Should have no errors
		$iter = $this->backend->getFileList( array( 'dir' => "$base/unittest-cont-notexists" ) );

		$files = array(
			"$base/unittest-cont1/e/test1.txt",
			"$base/unittest-cont1/e/test2.txt",
			"$base/unittest-cont1/e/test3.txt",
			"$base/unittest-cont1/e/subdir1/test1.txt",
			"$base/unittest-cont1/e/subdir1/test2.txt",
			"$base/unittest-cont1/e/subdir2/test3.txt",
			"$base/unittest-cont1/e/subdir2/test4.txt",
			"$base/unittest-cont1/e/subdir2/subdir/test1.txt",
			"$base/unittest-cont1/e/subdir2/subdir/test2.txt",
			"$base/unittest-cont1/e/subdir2/subdir/test3.txt",
			"$base/unittest-cont1/e/subdir2/subdir/test4.txt",
			"$base/unittest-cont1/e/subdir2/subdir/test5.txt",
			"$base/unittest-cont1/e/subdir2/subdir/sub/test0.txt",
			"$base/unittest-cont1/e/subdir2/subdir/sub/120-px-file.txt",
		);

		// Add the files
		$ops = array();
		foreach ( $files as $file ) {
			$this->prepare( array( 'dir' => dirname( $file ) ) );
			$ops[] = array( 'op' => 'create', 'content' => 'xxy', 'dst' => $file );
		}
		$status = $this->backend->doQuickOperations( $ops );
		$this->assertGoodStatus( $status,
			"Creation of files succeeded ($backendName)." );
		$this->assertEquals( true, $status->isOK(),
			"Creation of files succeeded with OK status ($backendName)." );

		// Expected listing at root
		$expected = array(
			"e/test1.txt",
			"e/test2.txt",
			"e/test3.txt",
			"e/subdir1/test1.txt",
			"e/subdir1/test2.txt",
			"e/subdir2/test3.txt",
			"e/subdir2/test4.txt",
			"e/subdir2/subdir/test1.txt",
			"e/subdir2/subdir/test2.txt",
			"e/subdir2/subdir/test3.txt",
			"e/subdir2/subdir/test4.txt",
			"e/subdir2/subdir/test5.txt",
			"e/subdir2/subdir/sub/test0.txt",
			"e/subdir2/subdir/sub/120-px-file.txt",
		);
		sort( $expected );

		// Actual listing (no trailing slash) at root
		$iter = $this->backend->getFileList( array( 'dir' => "$base/unittest-cont1" ) );
		$list = $this->listToArray( $iter );
		sort( $list );
		$this->assertEquals( $expected, $list, "Correct file listing ($backendName)." );

		// Actual listing (no trailing slash) at root with advise
		$iter = $this->backend->getFileList( array( 'dir' => "$base/unittest-cont1", 'adviseStat' => 1 ) );
		$list = $this->listToArray( $iter );
		sort( $list );
		$this->assertEquals( $expected, $list, "Correct file listing ($backendName)." );

		// Actual listing (with trailing slash) at root
		$list = array();
		$iter = $this->backend->getFileList( array( 'dir' => "$base/unittest-cont1/" ) );
		foreach ( $iter as $file ) {
			$list[] = $file;
		}
		sort( $list );
		$this->assertEquals( $expected, $list, "Correct file listing ($backendName)." );

		// Expected listing at subdir
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

		// Actual listing (no trailing slash) at subdir
		$iter = $this->backend->getFileList( array( 'dir' => "$base/unittest-cont1/e/subdir2/subdir" ) );
		$list = $this->listToArray( $iter );
		sort( $list );
		$this->assertEquals( $expected, $list, "Correct file listing ($backendName)." );

		// Actual listing (no trailing slash) at subdir with advise
		$iter = $this->backend->getFileList( array( 'dir' => "$base/unittest-cont1/e/subdir2/subdir", 'adviseStat' => 1 ) );
		$list = $this->listToArray( $iter );
		sort( $list );
		$this->assertEquals( $expected, $list, "Correct file listing ($backendName)." );

		// Actual listing (with trailing slash) at subdir
		$list = array();
		$iter = $this->backend->getFileList( array( 'dir' => "$base/unittest-cont1/e/subdir2/subdir/" ) );
		foreach ( $iter as $file ) {
			$list[] = $file;
		}
		sort( $list );
		$this->assertEquals( $expected, $list, "Correct file listing ($backendName)." );

		// Actual listing (using iterator second time)
		$list = $this->listToArray( $iter );
		sort( $list );
		$this->assertEquals( $expected, $list, "Correct file listing ($backendName), second iteration." );

		// Actual listing (top files only) at root
		$iter = $this->backend->getTopFileList( array( 'dir' => "$base/unittest-cont1" ) );
		$list = $this->listToArray( $iter );
		sort( $list );
		$this->assertEquals( array(), $list, "Correct top file listing ($backendName)." );

		// Expected listing (top files only) at subdir
		$expected = array(
			"test1.txt",
			"test2.txt",
			"test3.txt",
			"test4.txt",
			"test5.txt"
		);
		sort( $expected );

		// Actual listing (top files only) at subdir
		$iter = $this->backend->getTopFileList( array( 'dir' => "$base/unittest-cont1/e/subdir2/subdir" ) );
		$list = $this->listToArray( $iter );
		sort( $list );
		$this->assertEquals( $expected, $list, "Correct top file listing ($backendName)." );

		// Actual listing (top files only) at subdir with advise
		$iter = $this->backend->getTopFileList( array( 'dir' => "$base/unittest-cont1/e/subdir2/subdir", 'adviseStat' => 1 ) );
		$list = $this->listToArray( $iter );
		sort( $list );
		$this->assertEquals( $expected, $list, "Correct top file listing ($backendName)." );

		foreach ( $files as $file ) { // clean up
			$this->backend->doOperation( array( 'op' => 'delete', 'src' => $file ) );
		}

		$iter = $this->backend->getFileList( array( 'dir' => "$base/unittest-cont1/not/exists" ) );
		foreach ( $iter as $iter ) {
			// no errors
		}
	}

	/**
	 * @covers FileBackend::getTopDirectoryList
	 * @covers FileBackend::getDirectoryList
	 */
	public function testGetDirectoryList() {
		$this->backend = $this->singleBackend;
		$this->tearDownFiles();
		$this->doTestGetDirectoryList();
		$this->tearDownFiles();

		$this->backend = $this->multiBackend;
		$this->tearDownFiles();
		$this->doTestGetDirectoryList();
		$this->tearDownFiles();
	}

	private function doTestGetDirectoryList() {
		$backendName = $this->backendClass();

		$base = self::baseStorePath();
		$files = array(
			"$base/unittest-cont1/e/test1.txt",
			"$base/unittest-cont1/e/test2.txt",
			"$base/unittest-cont1/e/test3.txt",
			"$base/unittest-cont1/e/subdir1/test1.txt",
			"$base/unittest-cont1/e/subdir1/test2.txt",
			"$base/unittest-cont1/e/subdir2/test3.txt",
			"$base/unittest-cont1/e/subdir2/test4.txt",
			"$base/unittest-cont1/e/subdir2/subdir/test1.txt",
			"$base/unittest-cont1/e/subdir3/subdir/test2.txt",
			"$base/unittest-cont1/e/subdir4/subdir/test3.txt",
			"$base/unittest-cont1/e/subdir4/subdir/test4.txt",
			"$base/unittest-cont1/e/subdir4/subdir/test5.txt",
			"$base/unittest-cont1/e/subdir4/subdir/sub/test0.txt",
			"$base/unittest-cont1/e/subdir4/subdir/sub/120-px-file.txt",
		);

		// Add the files
		$ops = array();
		foreach ( $files as $file ) {
			$this->prepare( array( 'dir' => dirname( $file ) ) );
			$ops[] = array( 'op' => 'create', 'content' => 'xxy', 'dst' => $file );
		}
		$status = $this->backend->doQuickOperations( $ops );
		$this->assertGoodStatus( $status,
			"Creation of files succeeded ($backendName)." );
		$this->assertEquals( true, $status->isOK(),
			"Creation of files succeeded with OK status ($backendName)." );

		$this->assertEquals( true,
			$this->backend->directoryExists( array( 'dir' => "$base/unittest-cont1/e/subdir1" ) ),
			"Directory exists in ($backendName)." );
		$this->assertEquals( true,
			$this->backend->directoryExists( array( 'dir' => "$base/unittest-cont1/e/subdir2/subdir" ) ),
			"Directory exists in ($backendName)." );
		$this->assertEquals( false,
			$this->backend->directoryExists( array( 'dir' => "$base/unittest-cont1/e/subdir2/test1.txt" ) ),
			"Directory does not exists in ($backendName)." );

		// Expected listing
		$expected = array(
			"e",
		);
		sort( $expected );

		// Actual listing (no trailing slash)
		$list = array();
		$iter = $this->backend->getTopDirectoryList( array( 'dir' => "$base/unittest-cont1" ) );
		foreach ( $iter as $file ) {
			$list[] = $file;
		}
		sort( $list );

		$this->assertEquals( $expected, $list, "Correct top dir listing ($backendName)." );

		// Expected listing
		$expected = array(
			"subdir1",
			"subdir2",
			"subdir3",
			"subdir4",
		);
		sort( $expected );

		// Actual listing (no trailing slash)
		$list = array();
		$iter = $this->backend->getTopDirectoryList( array( 'dir' => "$base/unittest-cont1/e" ) );
		foreach ( $iter as $file ) {
			$list[] = $file;
		}
		sort( $list );

		$this->assertEquals( $expected, $list, "Correct top dir listing ($backendName)." );

		// Actual listing (with trailing slash)
		$list = array();
		$iter = $this->backend->getTopDirectoryList( array( 'dir' => "$base/unittest-cont1/e/" ) );
		foreach ( $iter as $file ) {
			$list[] = $file;
		}
		sort( $list );

		$this->assertEquals( $expected, $list, "Correct top dir listing ($backendName)." );

		// Expected listing
		$expected = array(
			"subdir",
		);
		sort( $expected );

		// Actual listing (no trailing slash)
		$list = array();
		$iter = $this->backend->getTopDirectoryList( array( 'dir' => "$base/unittest-cont1/e/subdir2" ) );
		foreach ( $iter as $file ) {
			$list[] = $file;
		}
		sort( $list );

		$this->assertEquals( $expected, $list, "Correct top dir listing ($backendName)." );

		// Actual listing (with trailing slash)
		$list = array();
		$iter = $this->backend->getTopDirectoryList( array( 'dir' => "$base/unittest-cont1/e/subdir2/" ) );
		foreach ( $iter as $file ) {
			$list[] = $file;
		}
		sort( $list );

		$this->assertEquals( $expected, $list, "Correct top dir listing ($backendName)." );

		// Actual listing (using iterator second time)
		$list = array();
		foreach ( $iter as $file ) {
			$list[] = $file;
		}
		sort( $list );

		$this->assertEquals( $expected, $list, "Correct top dir listing ($backendName), second iteration." );

		// Expected listing (recursive)
		$expected = array(
			"e",
			"e/subdir1",
			"e/subdir2",
			"e/subdir3",
			"e/subdir4",
			"e/subdir2/subdir",
			"e/subdir3/subdir",
			"e/subdir4/subdir",
			"e/subdir4/subdir/sub",
		);
		sort( $expected );

		// Actual listing (recursive)
		$list = array();
		$iter = $this->backend->getDirectoryList( array( 'dir' => "$base/unittest-cont1/" ) );
		foreach ( $iter as $file ) {
			$list[] = $file;
		}
		sort( $list );

		$this->assertEquals( $expected, $list, "Correct dir listing ($backendName)." );

		// Expected listing (recursive)
		$expected = array(
			"subdir",
			"subdir/sub",
		);
		sort( $expected );

		// Actual listing (recursive)
		$list = array();
		$iter = $this->backend->getDirectoryList( array( 'dir' => "$base/unittest-cont1/e/subdir4" ) );
		foreach ( $iter as $file ) {
			$list[] = $file;
		}
		sort( $list );

		$this->assertEquals( $expected, $list, "Correct dir listing ($backendName)." );

		// Actual listing (recursive, second time)
		$list = array();
		foreach ( $iter as $file ) {
			$list[] = $file;
		}
		sort( $list );

		$this->assertEquals( $expected, $list, "Correct dir listing ($backendName)." );

		$iter = $this->backend->getDirectoryList( array( 'dir' => "$base/unittest-cont1/e/subdir1" ) );
		$items = $this->listToArray( $iter );
		$this->assertEquals( array(), $items, "Directory listing is empty." );

		foreach ( $files as $file ) { // clean up
			$this->backend->doOperation( array( 'op' => 'delete', 'src' => $file ) );
		}

		$iter = $this->backend->getDirectoryList( array( 'dir' => "$base/unittest-cont1/not/exists" ) );
		foreach ( $iter as $file ) {
			// no errors
		}

		$items = $this->listToArray( $iter );
		$this->assertEquals( array(), $items, "Directory listing is empty." );

		$iter = $this->backend->getDirectoryList( array( 'dir' => "$base/unittest-cont1/e/not/exists" ) );
		$items = $this->listToArray( $iter );
		$this->assertEquals( array(), $items, "Directory listing is empty." );
	}

	/**
	 * @covers FileBackend::lockFiles
	 * @covers FileBackend::unlockFiles
	 */
	public function testLockCalls() {
		$this->backend = $this->singleBackend;
		$this->doTestLockCalls();
	}

	private function doTestLockCalls() {
		$backendName = $this->backendClass();

		$paths = array(
			"test1.txt",
			"test2.txt",
			"test3.txt",
			"subdir1",
			"subdir1", // duplicate
			"subdir1/test1.txt",
			"subdir1/test2.txt",
			"subdir2",
			"subdir2", // duplicate
			"subdir2/test3.txt",
			"subdir2/test4.txt",
			"subdir2/subdir",
			"subdir2/subdir/test1.txt",
			"subdir2/subdir/test2.txt",
			"subdir2/subdir/test3.txt",
			"subdir2/subdir/test4.txt",
			"subdir2/subdir/test5.txt",
			"subdir2/subdir/sub",
			"subdir2/subdir/sub/test0.txt",
			"subdir2/subdir/sub/120-px-file.txt",
		);

		for ( $i = 0; $i < 25; $i++ ) {
			$status = $this->backend->lockFiles( $paths, LockManager::LOCK_EX );
			$this->assertEquals( print_r( array(), true ), print_r( $status->errors, true ),
				"Locking of files succeeded ($backendName) ($i)." );
			$this->assertEquals( true, $status->isOK(),
				"Locking of files succeeded with OK status ($backendName) ($i)." );

			$status = $this->backend->lockFiles( $paths, LockManager::LOCK_SH );
			$this->assertEquals( print_r( array(), true ), print_r( $status->errors, true ),
				"Locking of files succeeded ($backendName) ($i)." );
			$this->assertEquals( true, $status->isOK(),
				"Locking of files succeeded with OK status ($backendName) ($i)." );

			$status = $this->backend->unlockFiles( $paths, LockManager::LOCK_SH );
			$this->assertEquals( print_r( array(), true ), print_r( $status->errors, true ),
				"Locking of files succeeded ($backendName) ($i)." );
			$this->assertEquals( true, $status->isOK(),
				"Locking of files succeeded with OK status ($backendName) ($i)." );

			$status = $this->backend->unlockFiles( $paths, LockManager::LOCK_EX );
			$this->assertEquals( print_r( array(), true ), print_r( $status->errors, true ),
				"Locking of files succeeded ($backendName). ($i)" );
			$this->assertEquals( true, $status->isOK(),
				"Locking of files succeeded with OK status ($backendName) ($i)." );

			## Flip the acquire/release ordering around ##

			$status = $this->backend->lockFiles( $paths, LockManager::LOCK_SH );
			$this->assertEquals( print_r( array(), true ), print_r( $status->errors, true ),
				"Locking of files succeeded ($backendName) ($i)." );
			$this->assertEquals( true, $status->isOK(),
				"Locking of files succeeded with OK status ($backendName) ($i)." );

			$status = $this->backend->lockFiles( $paths, LockManager::LOCK_EX );
			$this->assertEquals( print_r( array(), true ), print_r( $status->errors, true ),
				"Locking of files succeeded ($backendName) ($i)." );
			$this->assertEquals( true, $status->isOK(),
				"Locking of files succeeded with OK status ($backendName) ($i)." );

			$status = $this->backend->unlockFiles( $paths, LockManager::LOCK_EX );
			$this->assertEquals( print_r( array(), true ), print_r( $status->errors, true ),
				"Locking of files succeeded ($backendName). ($i)" );
			$this->assertEquals( true, $status->isOK(),
				"Locking of files succeeded with OK status ($backendName) ($i)." );

			$status = $this->backend->unlockFiles( $paths, LockManager::LOCK_SH );
			$this->assertEquals( print_r( array(), true ), print_r( $status->errors, true ),
				"Locking of files succeeded ($backendName) ($i)." );
			$this->assertEquals( true, $status->isOK(),
				"Locking of files succeeded with OK status ($backendName) ($i)." );
		}

		$status = Status::newGood();
		$sl = $this->backend->getScopedFileLocks( $paths, LockManager::LOCK_EX, $status );
		$this->assertType( 'ScopedLock', $sl,
			"Scoped locking of files succeeded ($backendName)." );
		$this->assertEquals( array(), $status->errors,
			"Scoped locking of files succeeded ($backendName)." );
		$this->assertEquals( true, $status->isOK(),
			"Scoped locking of files succeeded with OK status ($backendName)." );

		ScopedLock::release( $sl );
		$this->assertEquals( null, $sl,
			"Scoped unlocking of files succeeded ($backendName)." );
		$this->assertEquals( array(), $status->errors,
			"Scoped unlocking of files succeeded ($backendName)." );
		$this->assertEquals( true, $status->isOK(),
			"Scoped unlocking of files succeeded with OK status ($backendName)." );
	}

	// helper function
	private function listToArray( $iter ) {
		return is_array( $iter ) ? $iter : iterator_to_array( $iter );
	}

	// test helper wrapper for backend prepare() function
	private function prepare( array $params ) {
		return $this->backend->prepare( $params );
	}

	// test helper wrapper for backend prepare() function
	private function create( array $params ) {
		$params['op'] = 'create';

		return $this->backend->doQuickOperations( array( $params ) );
	}

	function tearDownFiles() {
		foreach ( $this->filesToPrune as $file ) {
			if ( is_file( $file ) ) {
				unlink( $file );
			}
		}
		$containers = array( 'unittest-cont1', 'unittest-cont2' );
		foreach ( $containers as $container ) {
			$this->deleteFiles( $container );
		}
		$this->filesToPrune = array();
	}

	private function deleteFiles( $container ) {
		$base = self::baseStorePath();
		$iter = $this->backend->getFileList( array( 'dir' => "$base/$container" ) );
		if ( $iter ) {
			foreach ( $iter as $file ) {
				$this->backend->quickDelete( array( 'src' => "$base/$container/$file" ) );
			}
			// free the directory, to avoid Permission denied under windows on rmdir
			unset( $iter );
		}
		$this->backend->clean( array( 'dir' => "$base/$container", 'recursive' => 1 ) );
	}

	function assertBackendPathsConsistent( array $paths ) {
		if ( $this->backend instanceof FileBackendMultiWrite ) {
			$status = $this->backend->consistencyCheck( $paths );
			$this->assertGoodStatus( $status, "Files synced: " . implode( ',', $paths ) );
		}
	}

	function assertGoodStatus( $status, $msg ) {
		$this->assertEquals( print_r( array(), 1 ), print_r( $status->errors, 1 ), $msg );
	}
}
