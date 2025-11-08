<?php

use MediaWiki\Config\ServiceOptions;
use MediaWiki\Http\HttpRequestFactory;
use MediaWiki\Logger\LoggerFactory;
use MediaWiki\Status\Status;
use Shellbox\Shellbox;
use Wikimedia\FileBackend\FileBackend;
use Wikimedia\FileBackend\FSFile\FSFile;
use Wikimedia\FileBackend\FSFile\TempFSFile;
use Wikimedia\FileBackend\FSFileBackend;

abstract class FileBackendIntegrationTestBase extends MediaWikiIntegrationTestCase {

	/** @var FileBackend */
	protected $backend;

	abstract protected function getBackend();

	protected function setUp(): void {
		$this->backend = $this->getBackend();
	}

	protected function tearDown(): void {
		if ( $this->backend ) {
			$this->tearDownFiles();
		}
	}

	private static function baseStorePath() {
		return 'mwstore://localtesting';
	}

	private function backendClass() {
		return get_class( $this->backend );
	}

	/**
	 * @dataProvider provider_testStore
	 */
	public function testStore( $op ) {
		$this->addTmpFiles( $op['src'] );
		$backendName = $this->backendClass();

		$source = $op['src'];
		$dest = $op['dst'];
		$this->prepare( [ 'dir' => dirname( $dest ) ] );

		file_put_contents( $source, "Unit test file" );

		if ( isset( $op['overwrite'] ) || isset( $op['overwriteSame'] ) ) {
			$this->backend->store( $op );
		}

		$status = $this->backend->doOperation( $op );

		$this->assertStatusGood( $status,
			"Store from $source to $dest succeeded without warnings ($backendName)." );
		$this->assertEquals( [ 0 => true ], $status->success,
			"Store from $source to $dest has proper 'success' field in Status ($backendName)." );
		$this->assertTrue( is_file( $source ),
			"Source file $source still exists ($backendName)." );
		$this->assertTrue( $this->backend->fileExists( [ 'src' => $dest ] ),
			"Destination file $dest exists ($backendName)." );

		$this->assertEquals( filesize( $source ),
			$this->backend->getFileSize( [ 'src' => $dest ] ),
			"Destination file $dest has correct size ($backendName)." );

		$props1 = FSFile::getPropsFromPath( $source );
		$props2 = $this->backend->getFileProps( [ 'src' => $dest ] );
		$this->assertEquals( $props1, $props2,
			"Source and destination have the same props ($backendName)." );
	}

	public static function provider_testStore() {
		$cases = [];

		$tmpName = TempFSFile::factory( "unittests_", 'txt', wfTempDir() )->getPath();
		$toPath = self::baseStorePath() . '/unittest-cont1/e/fun/obj1.txt';
		$op = [ 'op' => 'store', 'src' => $tmpName, 'dst' => $toPath ];
		$cases[] = [ $op ];

		$op2 = $op;
		$op2['overwrite'] = true;
		$cases[] = [ $op2 ];

		$op3 = $op;
		$op3['overwriteSame'] = true;
		$cases[] = [ $op3 ];

		return $cases;
	}

	/**
	 * @dataProvider provider_testCopy
	 */
	public function testCopy( $op, $srcContent, $dstContent, $okStatus ) {
		$backendName = $this->backendClass();

		$source = $op['src'];
		$dest = $op['dst'];
		$this->prepare( [ 'dir' => dirname( $source ) ] );
		$this->prepare( [ 'dir' => dirname( $dest ) ] );

		if ( is_string( $srcContent ) ) {
			$status = $this->backend->create( [ 'content' => $srcContent, 'dst' => $source ] );
			$this->assertStatusGood( $status, "Creation of $source succeeded ($backendName)." );
		}
		if ( is_string( $dstContent ) ) {
			$status = $this->backend->create( [ 'content' => $dstContent, 'dst' => $dest ] );
			$this->assertStatusGood( $status, "Creation of $dest succeeded ($backendName)." );
		}

		$status = $this->backend->doOperation( $op );

		if ( $okStatus ) {
			$this->assertStatusGood(
				$status,
				"Copy from $source to $dest succeeded without warnings ($backendName)." );
			$this->assertEquals( [ 0 => true ], $status->success,
				"Copy from $source to $dest has proper 'success' field in Status ($backendName)." );
			if ( !is_string( $srcContent ) ) {
				$this->assertSame(
					is_string( $dstContent ),
					$this->backend->fileExists( [ 'src' => $dest ] ),
					"Destination file $dest unchanged after no-op copy ($backendName)." );
				$this->assertSame(
					$dstContent,
					$this->backend->getFileContents( [ 'src' => $dest ] ),
					"Destination file $dest unchanged after no-op copy ($backendName)." );
			} else {
				$this->assertEquals(
					$this->backend->getFileSize( [ 'src' => $source ] ),
					$this->backend->getFileSize( [ 'src' => $dest ] ),
					"Destination file $dest has correct size ($backendName)." );
				$props1 = $this->backend->getFileProps( [ 'src' => $source ] );
				$props2 = $this->backend->getFileProps( [ 'src' => $dest ] );
				$this->assertEquals(
					$props1,
					$props2,
					"Source and destination have the same props ($backendName)." );
			}
		} else {
			$this->assertStatusNotOK(
				$status,
				"Copy from $source to $dest fails ($backendName)." );
			$this->assertSame(
				is_string( $dstContent ),
				(bool)$this->backend->fileExists( [ 'src' => $dest ] ),
				"Destination file $dest unchanged after failed copy ($backendName)." );
			$this->assertSame(
				$dstContent,
				$this->backend->getFileContents( [ 'src' => $dest ] ),
				"Destination file $dest unchanged after failed copy ($backendName)." );
		}

		$this->assertSame(
			is_string( $srcContent ),
			(bool)$this->backend->fileExists( [ 'src' => $source ] ),
			"Source file $source unchanged after copy ($backendName)."
		);
		$this->assertSame(
			$srcContent,
			$this->backend->getFileContents( [ 'src' => $source ] ),
			"Source file $source unchanged after copy ($backendName)."
		);
		if ( is_string( $dstContent ) ) {
			$this->assertTrue(
				(bool)$this->backend->fileExists( [ 'src' => $dest ] ),
				"Destination file $dest exists after copy ($backendName)." );
		}
	}

	/**
	 * @return array (op, source exists, dest exists, op succeeds)
	 */
	public static function provider_testCopy() {
		$cases = [];

		$source = self::baseStorePath() . '/unittest-cont1/e/file.txt';
		$dest = self::baseStorePath() . '/unittest-cont2/a/fileCopied.txt';
		$opBase = [ 'op' => 'copy', 'src' => $source, 'dst' => $dest ];

		$op = $opBase;
		$cases[] = [ $op, 'yyy', false, true ];

		$op = $opBase;
		$op['overwrite'] = true;
		$cases[] = [ $op, 'yyy', false, true ];

		$op = $opBase;
		$op['overwrite'] = true;
		$cases[] = [ $op, 'yyy', 'xxx', true ];

		$op = $opBase;
		$op['overwriteSame'] = true;
		$cases[] = [ $op, 'yyy', false, true ];

		$op = $opBase;
		$op['overwriteSame'] = true;
		$cases[] = [ $op, 'yyy', 'yyy', true ];

		$op = $opBase;
		$op['overwriteSame'] = true;
		$cases[] = [ $op, 'yyy', 'zzz', false ];

		$op = $opBase;
		$op['ignoreMissingSource'] = true;
		$cases[] = [ $op, 'xxx', false, true ];

		$op = $opBase;
		$op['ignoreMissingSource'] = true;
		$cases[] = [ $op, false, false, true ];

		$op = $opBase;
		$op['ignoreMissingSource'] = true;
		$cases[] = [ $op, false, 'xxx', true ];

		$op = $opBase;
		$op['src'] = 'mwstore://wrongbackend/unittest-cont1/e/file.txt';
		$op['ignoreMissingSource'] = true;
		$cases[] = [ $op, false, false, false ];

		return $cases;
	}

	/**
	 * @dataProvider provider_testMove
	 */
	public function testMove( $op, $srcContent, $dstContent, $okStatus ) {
		$backendName = $this->backendClass();

		$source = $op['src'];
		$dest = $op['dst'];
		$this->prepare( [ 'dir' => dirname( $source ) ] );
		$this->prepare( [ 'dir' => dirname( $dest ) ] );

		if ( is_string( $srcContent ) ) {
			$status = $this->backend->create( [ 'content' => $srcContent, 'dst' => $source ] );
			$this->assertStatusGood( $status, "Creation of $source succeeded ($backendName)." );
		}
		if ( is_string( $dstContent ) ) {
			$status = $this->backend->create( [ 'content' => $dstContent, 'dst' => $dest ] );
			$this->assertStatusGood( $status, "Creation of $dest succeeded ($backendName)." );
		}

		$oldSrcProps = $this->backend->getFileProps( [ 'src' => $source ] );

		$status = $this->backend->doOperation( $op );

		if ( $okStatus ) {
			$this->assertStatusGood(
				$status,
				"Move from $source to $dest succeeded without warnings ($backendName)." );
			$this->assertEquals( [ 0 => true ], $status->success,
				"Move from $source to $dest has proper 'success' field in Status ($backendName)." );
			if ( !is_string( $srcContent ) ) {
				$this->assertSame(
					is_string( $dstContent ),
					$this->backend->fileExists( [ 'src' => $dest ] ),
					"Destination file $dest unchanged after no-op move ($backendName)." );
				$this->assertSame(
					$dstContent,
					$this->backend->getFileContents( [ 'src' => $dest ] ),
					"Destination file $dest unchanged after no-op move ($backendName)." );
			} else {
				$this->assertEquals(
					$this->backend->getFileSize( [ 'src' => $dest ] ),
					strlen( $srcContent ),
					"Destination file $dest has correct size ($backendName)." );
				$this->assertEquals(
					$oldSrcProps,
					$this->backend->getFileProps( [ 'src' => $dest ] ),
					"Source and destination have the same props ($backendName)." );
			}
		} else {
			$this->assertStatusNotOK(
				$status,
				"Move from $source to $dest fails ($backendName)." );
			$this->assertSame(
				is_string( $dstContent ),
				(bool)$this->backend->fileExists( [ 'src' => $dest ] ),
				"Destination file $dest unchanged after failed move ($backendName)." );
			$this->assertSame(
				$dstContent,
				$this->backend->getFileContents( [ 'src' => $dest ] ),
				"Destination file $dest unchanged after failed move ($backendName)." );
			$this->assertSame(
				is_string( $srcContent ),
				(bool)$this->backend->fileExists( [ 'src' => $source ] ),
				"Source file $source unchanged after failed move ($backendName)."
			);
			$this->assertSame(
				$srcContent,
				$this->backend->getFileContents( [ 'src' => $source ] ),
				"Source file $source unchanged after failed move ($backendName)."
			);
		}

		if ( is_string( $dstContent ) ) {
			$this->assertTrue(
				(bool)$this->backend->fileExists( [ 'src' => $dest ] ),
				"Destination file $dest exists after move ($backendName)." );
		}
	}

	/**
	 * @return array (op, source exists, dest exists, op succeeds)
	 */
	public static function provider_testMove() {
		$cases = [];

		$source = self::baseStorePath() . '/unittest-cont1/e/file.txt';
		$dest = self::baseStorePath() . '/unittest-cont2/a/fileMoved.txt';
		$opBase = [ 'op' => 'move', 'src' => $source, 'dst' => $dest ];

		$op = $opBase;
		$cases[] = [ $op, 'yyy', false, true ];

		$op = $opBase;
		$op['overwrite'] = true;
		$cases[] = [ $op, 'yyy', false, true ];

		$op = $opBase;
		$op['overwrite'] = true;
		$cases[] = [ $op, 'yyy', 'xxx', true ];

		$op = $opBase;
		$op['overwriteSame'] = true;
		$cases[] = [ $op, 'yyy', false, true ];

		$op = $opBase;
		$op['overwriteSame'] = true;
		$cases[] = [ $op, 'yyy', 'yyy', true ];

		$op = $opBase;
		$op['overwriteSame'] = true;
		$cases[] = [ $op, 'yyy', 'zzz', false ];

		$op = $opBase;
		$op['ignoreMissingSource'] = true;
		$cases[] = [ $op, 'xxx', false, true ];

		$op = $opBase;
		$op['ignoreMissingSource'] = true;
		$cases[] = [ $op, false, false, true ];

		$op = $opBase;
		$op['ignoreMissingSource'] = true;
		$cases[] = [ $op, false, 'xxx', true ];

		$op = $opBase;
		$op['src'] = 'mwstore://wrongbackend/unittest-cont1/e/file.txt';
		$op['ignoreMissingSource'] = true;
		$cases[] = [ $op, false, false, false ];

		return $cases;
	}

	/**
	 * @dataProvider provider_testDelete
	 */
	public function testDelete( $op, $srcContent, $okStatus ) {
		$backendName = $this->backendClass();

		$source = $op['src'];
		$this->prepare( [ 'dir' => dirname( $source ) ] );

		if ( is_string( $srcContent ) ) {
			$status = $this->backend->doOperation(
				[ 'op' => 'create', 'content' => $srcContent, 'dst' => $source ] );
			$this->assertStatusGood( $status,
				"Creation of file at $source succeeded ($backendName)." );
		}

		$status = $this->backend->doOperation( $op );
		if ( $okStatus ) {
			$this->assertStatusGood( $status,
				"Deletion of file at $source succeeded without warnings ($backendName)." );
			$this->assertEquals( [ 0 => true ], $status->success,
				"Deletion of file at $source has proper 'success' field in Status ($backendName)." );
		} else {
			$this->assertStatusNotOK( $status,
				"Deletion of file at $source failed ($backendName)." );
		}

		$this->assertFalse(
			(bool)$this->backend->fileExists( [ 'src' => $source ] ),
			"Source file $source does not exist after move ($backendName)." );

		$this->assertFalse(
			$this->backend->getFileSize( [ 'src' => $source ] ),
			"Source file $source has correct size (false) ($backendName)." );

		$props1 = $this->backend->getFileProps( [ 'src' => $source ] );
		$this->assertFalse(
			$props1['fileExists'],
			"Source file $source does not exist according to props ($backendName)." );
	}

	/**
	 * @return array (op, source content, op succeeds)
	 */
	public static function provider_testDelete() {
		$cases = [];

		$source = self::baseStorePath() . '/unittest-cont1/e/myfacefile.txt';
		$baseOp = [ 'op' => 'delete', 'src' => $source ];

		$op = $baseOp;
		$cases[] = [ $op, 'xxx', true ];

		$op = $baseOp;
		$op['ignoreMissingSource'] = true;
		$cases[] = [ $op, 'xxx', true ];

		$op = $baseOp;
		$cases[] = [ $op, false, false ];

		$op = $baseOp;
		$op['ignoreMissingSource'] = true;
		$cases[] = [ $op, false, true ];

		$op = $baseOp;
		$op['ignoreMissingSource'] = true;
		$op['src'] = 'mwstore://wrongbackend/unittest-cont1/e/file.txt';
		$cases[] = [ $op, false, false ];

		return $cases;
	}

	/**
	 * @dataProvider provider_testDescribe
	 */
	public function testDescribe( $op, $withSource, $okStatus ) {
		$backendName = $this->backendClass();

		$source = $op['src'];
		$this->prepare( [ 'dir' => dirname( $source ) ] );

		if ( $withSource ) {
			$status = $this->backend->doOperation(
				[ 'op' => 'create', 'content' => 'blahblah', 'dst' => $source,
					'headers' => [ 'Content-Disposition' => 'xxx' ] ] );
			$this->assertStatusGood( $status,
				"Creation of file at $source succeeded ($backendName)." );
			if ( $this->backend->hasFeatures( FileBackend::ATTR_HEADERS ) ) {
				$attr = $this->backend->getFileXAttributes( [ 'src' => $source ] );
				$this->assertHasHeaders( [ 'Content-Disposition' => 'xxx' ], $attr );
			}

			$status = $this->backend->describe( [ 'src' => $source,
				'headers' => [ 'Content-Disposition' => '' ] ] ); // remove
			$this->assertStatusGood( $status,
				"Removal of header for $source succeeded ($backendName)." );

			if ( $this->backend->hasFeatures( FileBackend::ATTR_HEADERS ) ) {
				$attr = $this->backend->getFileXAttributes( [ 'src' => $source ] );
				$this->assertFalse( isset( $attr['headers']['content-disposition'] ),
					"File 'Content-Disposition' header removed." );
			}
		}

		$status = $this->backend->doOperation( $op );
		if ( $okStatus ) {
			$this->assertStatusGood( $status,
				"Describe of file at $source succeeded without warnings ($backendName)." );
			$this->assertEquals( [ 0 => true ], $status->success,
				"Describe of file at $source has proper 'success' field in Status ($backendName)." );
			if ( $this->backend->hasFeatures( FileBackend::ATTR_HEADERS ) ) {
				$attr = $this->backend->getFileXAttributes( [ 'src' => $source ] );
				$this->assertHasHeaders( $op['headers'], $attr );
			}
		} else {
			$this->assertStatusNotOK( $status,
				"Describe of file at $source failed ($backendName)." );
		}
	}

	private function assertHasHeaders( array $headers, array $attr ) {
		foreach ( $headers as $n => $v ) {
			if ( $n !== '' ) {
				$this->assertTrue( isset( $attr['headers'][strtolower( $n )] ),
					"File has '$n' header." );
				$this->assertEquals( $v, $attr['headers'][strtolower( $n )],
					"File has '$n' header value." );
			} else {
				$this->assertFalse( isset( $attr['headers'][strtolower( $n )] ),
					"File does not have '$n' header." );
			}
		}
	}

	public static function provider_testDescribe() {
		$cases = [];

		$source = self::baseStorePath() . '/unittest-cont1/e/myfacefile.txt';

		$op = [ 'op' => 'describe', 'src' => $source,
			'headers' => [ 'Content-Disposition' => 'inline' ], ];
		$cases[] = [
			$op, // operation
			true, // with source
			true // succeeds
		];

		$cases[] = [
			$op, // operation
			false, // without source
			false // fails
		];

		return $cases;
	}

	/**
	 * @dataProvider provider_testCreate
	 */
	public function testCreate( $op, $alreadyExists, $okStatus, $newSize ) {
		$backendName = $this->backendClass();

		$dest = $op['dst'];
		$this->prepare( [ 'dir' => dirname( $dest ) ] );

		$oldText = 'blah...blah...waahwaah';
		if ( $alreadyExists ) {
			$status = $this->backend->doOperation(
				[ 'op' => 'create', 'content' => $oldText, 'dst' => $dest ] );
			$this->assertStatusGood( $status,
				"Creation of file at $dest succeeded ($backendName)." );
		}

		$status = $this->backend->doOperation( $op );
		if ( $okStatus ) {
			$this->assertStatusGood( $status,
				"Creation of file at $dest succeeded without warnings ($backendName)." );
			$this->assertEquals( [ 0 => true ], $status->success,
				"Creation of file at $dest has proper 'success' field in Status ($backendName)." );
		} else {
			$this->assertStatusNotOK( $status,
				"Creation of file at $dest failed ($backendName)." );
		}

		$this->assertTrue( $this->backend->fileExists( [ 'src' => $dest ] ),
			"Destination file $dest exists after creation ($backendName)." );

		$props1 = $this->backend->getFileProps( [ 'src' => $dest ] );
		$this->assertTrue( $props1['fileExists'],
			"Destination file $dest exists according to props ($backendName)." );
		if ( $okStatus ) { // file content is what we saved
			$this->assertEquals( $newSize, $props1['size'],
				"Destination file $dest has expected size according to props ($backendName)." );
			$this->assertEquals( $newSize,
				$this->backend->getFileSize( [ 'src' => $dest ] ),
				"Destination file $dest has correct size ($backendName)." );
		} else { // file content is some other previous text
			$this->assertEquals( strlen( $oldText ), $props1['size'],
				"Destination file $dest has original size according to props ($backendName)." );
			$this->assertEquals( strlen( $oldText ),
				$this->backend->getFileSize( [ 'src' => $dest ] ),
				"Destination file $dest has original size according to props ($backendName)." );
		}
	}

	/**
	 * @dataProvider provider_testCreate
	 */
	public static function provider_testCreate() {
		$cases = [];

		$dest = self::baseStorePath() . '/unittest-cont2/a/myspacefile.txt';

		$op = [ 'op' => 'create', 'content' => 'test test testing', 'dst' => $dest ];
		$cases[] = [
			$op, // operation
			false, // no dest already exists
			true, // succeeds
			strlen( $op['content'] )
		];

		$op2 = $op;
		$op2['content'] = "\n";
		$cases[] = [
			$op2, // operation
			false, // no dest already exists
			true, // succeeds
			strlen( $op2['content'] )
		];

		$op2 = $op;
		$op2['content'] = "fsf\n waf 3kt";
		$cases[] = [
			$op2, // operation
			true, // dest already exists
			false, // fails
			strlen( $op2['content'] )
		];

		$op2 = $op;
		$op2['content'] = "egm'g gkpe gpqg eqwgwqg";
		$op2['overwrite'] = true;
		$cases[] = [
			$op2, // operation
			true, // dest already exists
			true, // succeeds
			strlen( $op2['content'] )
		];

		$op2 = $op;
		$op2['content'] = "39qjmg3-qg";
		$op2['overwriteSame'] = true;
		$cases[] = [
			$op2, // operation
			true, // dest already exists
			false, // succeeds
			strlen( $op2['content'] )
		];

		return $cases;
	}

	/**
	 * @dataProvider provider_quickOperations
	 */
	public function testDoQuickOperations(
		$files, $createOps, $copyOps, $moveOps, $overSelfOps, $deleteOps, $batchSize
	) {
		$backendName = $this->backendClass();

		foreach ( $files as $path ) {
			$status = $this->prepare( [ 'dir' => dirname( $path ) ] );
			$this->assertStatusGood( $status,
				"Preparing $path succeeded without warnings ($backendName)." );
		}

		foreach ( array_chunk( $createOps, $batchSize ) as $batchOps ) {
			$this->assertStatusGood(
				$this->backend->doQuickOperations( $batchOps ),
				"Creation of source files succeeded ($backendName)."
			);
		}
		foreach ( $files as $file ) {
			$this->assertTrue(
				$this->backend->fileExists( [ 'src' => $file ] ),
				"File $file exists."
			);
		}

		foreach ( array_chunk( $copyOps, $batchSize ) as $batchOps ) {
			$this->assertStatusGood(
				$this->backend->doQuickOperations( $batchOps ),
				"Quick copy of source files succeeded ($backendName)."
			);
		}
		foreach ( $files as $file ) {
			$this->assertTrue(
				$this->backend->fileExists( [ 'src' => "$file-2" ] ),
				"File $file-2 exists."
			);
		}

		foreach ( array_chunk( $moveOps, $batchSize ) as $batchOps ) {
			$this->assertStatusGood(
				$this->backend->doQuickOperations( $batchOps ),
				"Quick move of source files succeeded ($backendName)."
			);
		}
		foreach ( $files as $file ) {
			$this->assertTrue(
				$this->backend->fileExists( [ 'src' => "$file-3" ] ),
				"File $file-3 move in."
			);
			$this->assertFalse(
				$this->backend->fileExists( [ 'src' => "$file-2" ] ),
				"File $file-2 moved away."
			);
		}

		foreach ( array_chunk( $overSelfOps, $batchSize ) as $batchOps ) {
			$this->assertStatusGood(
				$this->backend->doQuickOperations( $batchOps ),
				"Quick copy/move of source files over themselves succeeded ($backendName)."
			);
		}
		foreach ( $files as $file ) {
			$this->assertTrue(
				$this->backend->fileExists( [ 'src' => $file ] ),
				"File $file still exists after copy/move over self."
			);
		}

		foreach ( array_chunk( $deleteOps, $batchSize ) as $batchOps ) {
			$this->assertStatusGood(
				$this->backend->doQuickOperations( $batchOps ),
				"Quick deletion of source files succeeded ($backendName)."
			);
		}
		foreach ( $files as $file ) {
			$this->assertFalse( $this->backend->fileExists( [ 'src' => $file ] ),
				"File $file purged." );
			$this->assertFalse( $this->backend->fileExists( [ 'src' => "$file-3" ] ),
				"File $file-3 purged." );
		}
	}

	public static function provider_quickOperations() {
		$base = self::baseStorePath();
		$files = [
			"$base/unittest-cont1/e/fileA.a",
			"$base/unittest-cont1/e/fileB.a",
			"$base/unittest-cont1/e/fileC.a"
		];

		$createOps = $copyOps = $moveOps = $overSelfOps = $deleteOps = [];
		foreach ( $files as $path ) {
			$createOps[] = [ 'op' => 'create', 'dst' => $path, 'content' => 52525 ];
			$createOps[] = [ 'op' => 'create', 'dst' => "$path-x", 'content' => 832 ];
			$createOps[] = [ 'op' => 'null' ];

			$copyOps[] = [ 'op' => 'copy', 'src' => $path, 'dst' => "$path-2" ];
			$copyOps[] = [
				'op' => 'copy',
				'src' => "$path-nothing",
				'dst' => "$path-nowhere",
				'ignoreMissingSource' => true
			];
			$copyOps[] = [ 'op' => 'null' ];

			$moveOps[] = [ 'op' => 'move', 'src' => "$path-2", 'dst' => "$path-3" ];
			$moveOps[] = [
				'op' => 'move',
				'src' => "$path-nothing",
				'dst' => "$path-nowhere",
				'ignoreMissingSource' => true
			];
			$moveOps[] = [ 'op' => 'null' ];

			$overSelfOps[] = [ 'op' => 'copy', 'src' => $path, 'dst' => $path ];
			$overSelfOps[] = [ 'op' => 'move', 'src' => $path, 'dst' => $path ];

			$deleteOps[] = [ 'op' => 'delete', 'src' => $path ];
			$deleteOps[] = [ 'op' => 'delete', 'src' => "$path-3" ];
			$deleteOps[] = [
				'op' => 'delete',
				'src' => "$path-gone",
				'ignoreMissingSource' => true
			];
			$deleteOps[] = [ 'op' => 'null' ];
		}

		return [
			[ $files, $createOps, $copyOps, $moveOps, $overSelfOps, $deleteOps, 1 ],
			[ $files, $createOps, $copyOps, $moveOps, $overSelfOps, $deleteOps, 2 ],
			[ $files, $createOps, $copyOps, $moveOps, $overSelfOps, $deleteOps, 100 ]
		];
	}

	/**
	 * @dataProvider provider_testConcatenate
	 */
	public function testConcatenate( $params, $srcs, $srcsContent, $alreadyExists, $okStatus ) {
		$backendName = $this->backendClass();

		$expContent = '';
		// Create sources
		$ops = [];
		foreach ( $srcs as $i => $source ) {
			$this->prepare( [ 'dir' => dirname( $source ) ] );
			$ops[] = [
				'op' => 'create', // operation
				'dst' => $source, // source
				'content' => $srcsContent[$i]
			];
			$expContent .= $srcsContent[$i];
		}
		$status = $this->backend->doOperations( $ops );

		$this->assertStatusGood( $status,
			"Creation of source files succeeded ($backendName)." );

		$dest = $params['dst'] = $this->getNewTempFile();
		if ( $alreadyExists ) {
			$ok = file_put_contents( $dest, 'blah...blah...waahwaah' ) !== false;
			$this->assertTrue( $ok,
				"Creation of file at $dest succeeded ($backendName)." );
		} else {
			$ok = file_put_contents( $dest, '' ) !== false;
			$this->assertTrue( $ok,
				"Creation of 0-byte file at $dest succeeded ($backendName)." );
		}

		// Combine the files into one
		$status = $this->backend->concatenate( $params );
		if ( $okStatus ) {
			$this->assertStatusGood( $status,
				"Creation of concat file at $dest succeeded without warnings ($backendName)." );
		} else {
			$this->assertStatusNotOK( $status,
				"Creation of concat file at $dest failed ($backendName)." );
		}

		if ( $okStatus ) {
			$this->assertTrue( is_file( $dest ),
				"Dest concat file $dest exists after creation ($backendName)." );
		} else {
			$this->assertTrue( is_file( $dest ),
				"Dest concat file $dest exists after failed creation ($backendName)." );
		}

		$contents = file_get_contents( $dest );
		$this->assertIsString( $contents, "File at $dest exists ($backendName)." );

		if ( $okStatus ) {
			$this->assertEquals( $expContent, $contents,
				"Concat file at $dest has correct contents ($backendName)." );
		} else {
			$this->assertNotEquals( $expContent, $contents,
				"Concat file at $dest has correct contents ($backendName)." );
		}
	}

	public static function provider_testConcatenate() {
		$cases = [];

		$srcs = [
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
		];
		$content = [
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
		];
		$params = [ 'srcs' => $srcs ];

		$cases[] = [
			$params, // operation
			$srcs, // sources
			$content, // content for each source
			false, // no dest already exists
			true, // succeeds
		];

		$cases[] = [
			$params, // operation
			$srcs, // sources
			$content, // content for each source
			true, // dest already exists
			false, // succeeds
		];

		return $cases;
	}

	/**
	 * @dataProvider provider_testGetFileStat
	 */
	public function testGetFileStat( $path, $content, $alreadyExists ) {
		$backendName = $this->backendClass();

		if ( $alreadyExists ) {
			$this->prepare( [ 'dir' => dirname( $path ) ] );
			$status = $this->create( [ 'dst' => $path, 'content' => $content ] );
			$this->assertStatusGood( $status,
				"Creation of file at $path succeeded ($backendName)." );

			$size = $this->backend->getFileSize( [ 'src' => $path ] );
			$time = $this->backend->getFileTimestamp( [ 'src' => $path ] );
			$stat = $this->backend->getFileStat( [ 'src' => $path ] );

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

			$this->backend->clearCache( [ $path ] );

			$size = $this->backend->getFileSize( [ 'src' => $path ] );

			$this->assertEquals( strlen( $content ), $size,
				"Correct file size of '$path'" );

			$this->backend->preloadCache( [ $path ] );

			$size = $this->backend->getFileSize( [ 'src' => $path ] );

			$this->assertEquals( strlen( $content ), $size,
				"Correct file size of '$path'" );
		} else {
			$size = $this->backend->getFileSize( [ 'src' => $path ] );
			$time = $this->backend->getFileTimestamp( [ 'src' => $path ] );
			$stat = $this->backend->getFileStat( [ 'src' => $path ] );

			$this->assertFalse( $size, "Correct file size of '$path'" );
			$this->assertFalse( $time, "Correct file timestamp of '$path'" );
			$this->assertFalse( $stat, "Correct file stat of '$path'" );
		}
	}

	public static function provider_testGetFileStat() {
		$cases = [];

		$base = self::baseStorePath();
		$cases[] = [ "$base/unittest-cont1/e/b/z/some_file.txt", "some file contents", true ];
		$cases[] = [ "$base/unittest-cont1/e/b/some-other_file.txt", "", true ];
		$cases[] = [ "$base/unittest-cont1/e/b/some-diff_file.txt", null, false ];

		return $cases;
	}

	/**
	 * @dataProvider provider_testGetFileStat
	 */
	public function testStreamFile( $path, $content, $alreadyExists ) {
		$backendName = $this->backendClass();

		if ( $content !== null ) {
			$this->prepare( [ 'dir' => dirname( $path ) ] );
			$status = $this->create( [ 'dst' => $path, 'content' => $content ] );
			$this->assertStatusGood( $status,
				"Creation of file at $path succeeded ($backendName)." );

			ob_start();
			$this->backend->streamFile( [ 'src' => $path, 'headless' => 1, 'allowOB' => 1 ] );
			$data = ob_get_contents();
			ob_end_clean();

			$this->assertEquals( $content, $data, "Correct content streamed from '$path'" );
		} else { // 404 case
			ob_start();
			$this->backend->streamFile( [ 'src' => $path, 'headless' => 1, 'allowOB' => 1 ] );
			$data = ob_get_contents();
			ob_end_clean();

			$this->assertMatchesRegularExpression( '#<h1>File not found</h1>#', $data,
				"Correct content streamed from '$path' ($backendName)" );
		}
	}

	public static function provider_testStreamFile() {
		$cases = [];

		$base = self::baseStorePath();
		$cases[] = [ "$base/unittest-cont1/e/b/z/some_file.txt", "some file contents" ];
		$cases[] = [ "$base/unittest-cont1/e/b/some-other_file.txt", null ];

		return $cases;
	}

	public function testStreamFileRange() {
		$backendName = $this->backendClass();

		$base = self::baseStorePath();
		$path = "$base/unittest-cont1/e/b/z/range_file.txt";
		$content = "0123456789ABCDEF";

		$this->prepare( [ 'dir' => dirname( $path ) ] );
		$status = $this->create( [ 'dst' => $path, 'content' => $content ] );
		$this->assertStatusGood( $status,
			"Creation of file at $path succeeded ($backendName)." );

		static $ranges = [
			'bytes=0-0'   => '0',
			'bytes=0-3'   => '0123',
			'bytes=4-8'   => '45678',
			'bytes=15-15' => 'F',
			'bytes=14-15' => 'EF',
			'bytes=-5'    => 'BCDEF',
			'bytes=-1'    => 'F',
			'bytes=10-16' => 'ABCDEF',
			'bytes=10-99' => 'ABCDEF',
		];

		foreach ( $ranges as $range => $chunk ) {
			ob_start();
			$this->backend->streamFile( [ 'src' => $path, 'headless' => 1, 'allowOB' => 1,
				'options' => [ 'range' => $range ] ] );
			$data = ob_get_contents();
			ob_end_clean();

			$this->assertEquals( $chunk, $data, "Correct chunk streamed from '$path' for '$range'" );
		}
	}

	/**
	 * @dataProvider provider_testGetFileContents
	 */
	public function testGetFileContents( $source, $content ) {
		$backendName = $this->backendClass();

		$srcs = (array)$source;
		$content = (array)$content;
		foreach ( $srcs as $i => $src ) {
			$this->prepare( [ 'dir' => dirname( $src ) ] );
			$status = $this->backend->doOperation(
				[ 'op' => 'create', 'content' => $content[$i], 'dst' => $src ] );
			$this->assertStatusGood( $status,
				"Creation of file at $src succeeded ($backendName)." );
		}

		if ( is_array( $source ) ) {
			$contents = $this->backend->getFileContentsMulti( [ 'srcs' => $source ] );
			foreach ( $contents as $path => $data ) {
				$this->assertIsString( $data, "Contents of $path exists ($backendName)." );
				$this->assertEquals(
					current( $content ),
					$data,
					"Contents of $path is correct ($backendName)."
				);
				next( $content );
			}
			$this->assertEquals(
				$source,
				array_keys( $contents ),
				"Contents in right order ($backendName)."
			);
			$this->assertSameSize(
				$source,
				$contents,
				"Contents array size correct ($backendName)."
			);
		} else {
			$data = $this->backend->getFileContents( [ 'src' => $source ] );
			$this->assertIsString( $data, "Contents of $source exists ($backendName)." );
			$this->assertEquals( $content[0], $data, "Contents of $source is correct ($backendName)." );
		}
	}

	public static function provider_testGetFileContents() {
		$cases = [];

		$base = self::baseStorePath();
		$cases[] = [ "$base/unittest-cont1/e/b/z/some_file.txt", "some file contents" ];
		$cases[] = [ "$base/unittest-cont1/e/b/some-other_file.txt", "more file contents" ];
		$cases[] = [
			[ "$base/unittest-cont1/e/a/x.txt", "$base/unittest-cont1/e/a/y.txt",
				"$base/unittest-cont1/e/a/z.txt" ],
			[ "contents xx", "contents xy", "contents xz" ]
		];

		return $cases;
	}

	/**
	 * @dataProvider provider_testGetLocalCopy
	 */
	public function testGetLocalCopy( $source, $content ) {
		$backendName = $this->backendClass();

		$srcs = (array)$source;
		$content = (array)$content;
		foreach ( $srcs as $i => $src ) {
			$this->prepare( [ 'dir' => dirname( $src ) ] );
			$status = $this->backend->doOperation(
				[ 'op' => 'create', 'content' => $content[$i], 'dst' => $src ] );
			$this->assertStatusGood( $status,
				"Creation of file at $src succeeded ($backendName)." );
		}

		if ( is_array( $source ) ) {
			$tmpFiles = $this->backend->getLocalCopyMulti( [ 'srcs' => $source ] );
			foreach ( $tmpFiles as $path => $tmpFile ) {
				$this->assertNotNull( $tmpFile,
					"Creation of local copy of $path succeeded ($backendName)." );
				$contents = file_get_contents( $tmpFile->getPath() );
				$this->assertIsString( $contents, "Local copy of $path exists ($backendName)." );
				$this->assertEquals(
					current( $content ),
					$contents,
					"Local copy of $path is correct ($backendName)."
				);
				next( $content );
			}
			$this->assertEquals(
				$source,
				array_keys( $tmpFiles ),
				"Local copies in right order ($backendName)."
			);
			$this->assertSameSize(
				$source,
				$tmpFiles,
				"Local copies array size correct ($backendName)."
			);
		} else {
			$tmpFile = $this->backend->getLocalCopy( [ 'src' => $source ] );
			$this->assertNotNull( $tmpFile,
				"Creation of local copy of $source succeeded ($backendName)." );
			$contents = file_get_contents( $tmpFile->getPath() );
			$this->assertIsString( $contents, "Local copy of $source exists ($backendName)." );
			$this->assertEquals(
				$content[0],
				$contents,
				"Local copy of $source is correct ($backendName)."
			);
		}

		$obj = (object)[];
		$tmpFile->bind( $obj );
	}

	public static function provider_testGetLocalCopy() {
		$cases = [];

		$base = self::baseStorePath();
		$cases[] = [ "$base/unittest-cont1/e/a/z/some_file.txt", "some file contents" ];
		$cases[] = [ "$base/unittest-cont1/e/a/some-other_file.txt", "more file contents" ];
		$cases[] = [ "$base/unittest-cont1/e/a/\$odd&.txt", "test file contents" ];
		$cases[] = [
			[ "$base/unittest-cont1/e/a/x.txt", "$base/unittest-cont1/e/a/y.txt",
				"$base/unittest-cont1/e/a/z.txt" ],
			[ "contents xx $", "contents xy 111", "contents xz" ]
		];

		return $cases;
	}

	/**
	 * @dataProvider provider_testGetLocalReference
	 */
	public function testGetLocalReference( $source, $content ) {
		$backendName = $this->backendClass();

		$srcs = (array)$source;
		$content = (array)$content;
		foreach ( $srcs as $i => $src ) {
			$this->prepare( [ 'dir' => dirname( $src ) ] );
			$status = $this->backend->doOperation(
				[ 'op' => 'create', 'content' => $content[$i], 'dst' => $src ] );
			$this->assertStatusGood( $status,
				"Creation of file at $src succeeded ($backendName)." );
		}

		if ( is_array( $source ) ) {
			$tmpFiles = $this->backend->getLocalReferenceMulti( [ 'srcs' => $source ] );
			foreach ( $tmpFiles as $path => $tmpFile ) {
				$this->assertNotNull( $tmpFile,
					"Creation of local copy of $path succeeded ($backendName)." );
				$contents = file_get_contents( $tmpFile->getPath() );
				$this->assertIsString( $contents, "Local ref of $path exists ($backendName)." );
				$this->assertEquals(
					current( $content ),
					$contents,
					"Local ref of $path is correct ($backendName)."
				);
				next( $content );
			}
			$this->assertEquals(
				$source,
				array_keys( $tmpFiles ),
				"Local refs in right order ($backendName)."
			);
			$this->assertSameSize(
				$source,
				$tmpFiles,
				"Local refs array size correct ($backendName)."
			);
		} else {
			$tmpFile = $this->backend->getLocalReference( [ 'src' => $source ] );
			$this->assertNotNull( $tmpFile,
				"Creation of local copy of $source succeeded ($backendName)." );
			$contents = file_get_contents( $tmpFile->getPath() );
			$this->assertIsString( $contents, "Local ref of $source exists ($backendName)." );
			$this->assertEquals( $content[0], $contents, "Local ref of $source is correct ($backendName)." );
		}
	}

	public static function provider_testGetLocalReference() {
		$cases = [];

		$base = self::baseStorePath();
		$cases[] = [ "$base/unittest-cont1/e/a/z/some_file.txt", "some file contents" ];
		$cases[] = [ "$base/unittest-cont1/e/a/some-other_file.txt", "more file contents" ];
		$cases[] = [ "$base/unittest-cont1/e/a/\$odd&.txt", "test file contents" ];
		$cases[] = [
			[ "$base/unittest-cont1/e/a/x.txt", "$base/unittest-cont1/e/a/y.txt",
				"$base/unittest-cont1/e/a/z.txt" ],
			[ "contents xx 1111", "contents xy %", "contents xz $" ]
		];

		return $cases;
	}

	public function testGetLocalCopyAndReference404() {
		$backendName = $this->backendClass();

		$base = self::baseStorePath();

		$tmpFile = $this->backend->getLocalCopy( [
			'src' => "$base/unittest-cont1/a/not-there" ] );
		$this->assertFalse( $tmpFile, "Local copy of not existing file is false ($backendName)." );

		$tmpFile = $this->backend->getLocalReference( [
			'src' => "$base/unittest-cont1/a/not-there" ] );
		$this->assertFalse( $tmpFile, "Local ref of not existing file is false ($backendName)." );
	}

	/**
	 * Get a real HTTP request factory, overriding the restrictions on tests that do HTTP requests
	 * @return HttpRequestFactory
	 */
	private function getRealHttpRequestFactory() {
		return new HttpRequestFactory(
			new ServiceOptions(
				HttpRequestFactory::CONSTRUCTOR_OPTIONS,
				$this->getServiceContainer()->getMainConfig()
			),
			LoggerFactory::getInstance( 'http' )
		);
	}

	/**
	 * @dataProvider provider_testGetFileHttpUrl
	 */
	public function testGetFileHttpUrl( $source, $content ) {
		$backendName = $this->backendClass();

		$this->prepare( [ 'dir' => dirname( $source ) ] );
		$status = $this->backend->doOperation(
			[ 'op' => 'create', 'content' => $content, 'dst' => $source ] );
		$this->assertStatusGood( $status,
			"Creation of file at $source succeeded ($backendName)." );

		$url = $this->backend->getFileHttpUrl( [ 'src' => $source ] );

		if ( $url !== null ) { // supported
			$data = $this->getRealHttpRequestFactory()->get( $url, [], __METHOD__ );
			$this->assertEquals( $content, $data,
				"HTTP GET of URL has right contents ($backendName)." );
		}
	}

	public static function provider_testGetFileHttpUrl() {
		$cases = [];

		$base = self::baseStorePath();
		$cases[] = [ "$base/unittest-cont1/e/a/z/some_file.txt", "some file contents" ];
		$cases[] = [ "$base/unittest-cont1/e/a/some-other_file.txt", "more file contents" ];
		$cases[] = [ "$base/unittest-cont1/e/a/\$odd&.txt", "test file contents" ];

		return $cases;
	}

	public function testAddShellboxInputFile() {
		if ( wfIsWindows() ) {
			$this->markTestSkipped( 'This test requires a POSIX environment.' );
		}
		$backendName = $this->backendClass();
		$base = self::baseStorePath();
		$src = "$base/unittest-cont1/e/a/b/fileA.txt";
		$contents = 'test';
		$this->prepare( [ 'dir' => dirname( $src ) ] );
		$status = $this->backend->create( [ 'content' => $contents, 'dst' => $src ] );
		$this->assertStatusGood( $status,
			"Creation of file at $src succeeded ($backendName)." );
		$executor = Shellbox::createBoxedExecutor();
		$executor->setUrlFileClient( $this->getRealHttpRequestFactory()->createGuzzleClient() );
		$command = $executor->createCommand();
		$this->backend->addShellboxInputFile( $command, 'fileA.txt', [ 'src' => $src ] );
		$result = $command
			->params( 'cat', 'fileA.txt' )
			->routeName( 'test' )
			->execute();
		$this->assertSame( 'test', $result->getStdout() );
	}

	public static function provider_testPrepareAndClean() {
		$base = self::baseStorePath();

		return [
			[ "$base/unittest-cont1/e/a/z/some_file1.txt", true ],
			[ "$base/unittest-cont2/a/z/some_file2.txt", true ],
			# Specific to FS backend with no basePath field set
			# [ "$base/unittest-cont3/a/z/some_file3.txt", false ],
		];
	}

	/**
	 * @dataProvider provider_testPrepareAndClean
	 */
	public function testPrepareAndClean( $path, $isOK ) {
		$backendName = $this->backendClass();

		$status = $this->prepare( [ 'dir' => dirname( $path ) ] );
		if ( $isOK ) {
			$this->assertStatusGood( $status,
				"Preparing dir $path succeeded without warnings ($backendName)." );
		} else {
			$this->assertStatusNotOK( $status,
				"Preparing dir $path failed ($backendName)." );
		}

		$status = $this->backend->secure( [ 'dir' => dirname( $path ) ] );
		if ( $isOK ) {
			$this->assertStatusGood( $status,
				"Securing dir $path succeeded without warnings ($backendName)." );
		} else {
			$this->assertStatusNotOK( $status,
				"Securing dir $path failed ($backendName)." );
		}

		$status = $this->backend->publish( [ 'dir' => dirname( $path ) ] );
		if ( $isOK ) {
			$this->assertStatusGood( $status,
				"Publishing dir $path succeeded without warnings ($backendName)." );
		} else {
			$this->assertStatusNotOK( $status,
				"Publishing dir $path failed ($backendName)." );
		}

		$status = $this->backend->clean( [ 'dir' => dirname( $path ) ] );
		if ( $isOK ) {
			$this->assertStatusGood( $status,
				"Cleaning dir $path succeeded without warnings ($backendName)." );
		} else {
			$this->assertStatusNotOK( $status,
				"Cleaning dir $path failed ($backendName)." );
		}
	}

	public function testRecursiveClean() {
		$backendName = $this->backendClass();

		$base = self::baseStorePath();
		$dirs = [
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
		];
		foreach ( $dirs as $dir ) {
			$status = $this->prepare( [ 'dir' => $dir ] );
			$this->assertStatusGood( $status,
				"Preparing dir $dir succeeded without warnings ($backendName)." );
		}

		if ( $this->backend instanceof FSFileBackend ) {
			foreach ( $dirs as $dir ) {
				$this->assertTrue( $this->backend->directoryExists( [ 'dir' => $dir ] ),
					"Dir $dir exists ($backendName)." );
			}
		}

		$status = $this->backend->clean(
			[ 'dir' => "$base/unittest-cont1", 'recursive' => 1 ] );
		$this->assertStatusGood( $status,
			"Recursive cleaning of dir $dir succeeded without warnings ($backendName)." );

		foreach ( $dirs as $dir ) {
			$this->assertFalse( $this->backend->directoryExists( [ 'dir' => $dir ] ),
				"Dir $dir no longer exists ($backendName)." );
		}
	}

	public function testDoOperationsSuccessful() {
		$base = self::baseStorePath();

		$fileA = "$base/unittest-cont1/e/a/b/fileA.txt";
		$fileAContents = '3tqtmoeatmn4wg4qe-mg3qt3 tq';
		$fileB = "$base/unittest-cont1/e/a/b/fileB.txt";
		$fileBContents = 'g-jmq3gpqgt3qtg q3GT ';
		$fileC = "$base/unittest-cont1/e/a/b/fileC.txt";
		$fileCContents = 'eigna[ogmewt 3qt g3qg flew[ag';
		$fileD = "$base/unittest-cont1/e/a/b/fileD.txt";

		$this->prepare( [ 'dir' => dirname( $fileA ) ] );
		$this->create( [ 'dst' => $fileA, 'content' => $fileAContents ] );
		$this->prepare( [ 'dir' => dirname( $fileB ) ] );
		$this->create( [ 'dst' => $fileB, 'content' => $fileBContents ] );
		$this->prepare( [ 'dir' => dirname( $fileC ) ] );
		$this->create( [ 'dst' => $fileC, 'content' => $fileCContents ] );
		$this->prepare( [ 'dir' => dirname( $fileD ) ] );

		$status = $this->backend->doOperations( [
			[ 'op' => 'describe', 'src' => $fileA,
				'headers' => [ 'X-Content-Length' => '91.3' ], 'disposition' => 'inline' ],
			[ 'op' => 'copy', 'src' => $fileA, 'dst' => $fileC, 'overwrite' => 1 ],
			// Now: A:<A>, B:<B>, C:<A>, D:<empty> (file:<orginal contents>)
			[ 'op' => 'copy', 'src' => $fileC, 'dst' => $fileA, 'overwriteSame' => 1 ],
			// Now: A:<A>, B:<B>, C:<A>, D:<empty>
			[ 'op' => 'move', 'src' => $fileC, 'dst' => $fileD, 'overwrite' => 1 ],
			// Now: A:<A>, B:<B>, C:<empty>, D:<A>
			[ 'op' => 'move', 'src' => $fileB, 'dst' => $fileC ],
			// Now: A:<A>, B:<empty>, C:<B>, D:<A>
			[ 'op' => 'move', 'src' => $fileD, 'dst' => $fileA, 'overwriteSame' => 1 ],
			// Now: A:<A>, B:<empty>, C:<B>, D:<empty>
			[ 'op' => 'move', 'src' => $fileC, 'dst' => $fileA, 'overwrite' => 1 ],
			// Now: A:<B>, B:<empty>, C:<empty>, D:<empty>
			[ 'op' => 'copy', 'src' => $fileA, 'dst' => $fileC ],
			// Now: A:<B>, B:<empty>, C:<B>, D:<empty>
			[ 'op' => 'move', 'src' => $fileA, 'dst' => $fileC, 'overwriteSame' => 1 ],
			// Now: A:<empty>, B:<empty>, C:<B>, D:<empty>
			[ 'op' => 'copy', 'src' => $fileC, 'dst' => $fileC, 'overwrite' => 1 ],
			// Does nothing
			[ 'op' => 'copy', 'src' => $fileC, 'dst' => $fileC, 'overwriteSame' => 1 ],
			// Does nothing
			[ 'op' => 'move', 'src' => $fileC, 'dst' => $fileC, 'overwrite' => 1 ],
			// Does nothing
			[ 'op' => 'move', 'src' => $fileC, 'dst' => $fileC, 'overwriteSame' => 1 ],
			// Does nothing
			[ 'op' => 'null' ],
			// Does nothing
		] );

		$this->assertStatusGood( $status, "Operation batch succeeded" );
		$this->assertCount( 14, $status->success,
			"Operation batch has correct success array" );

		$this->assertFalse( $this->backend->fileExists( [ 'src' => $fileA ] ),
			"File does not exist at $fileA" );
		$this->assertFalse( $this->backend->fileExists( [ 'src' => $fileB ] ),
			"File does not exist at $fileB" );
		$this->assertFalse( $this->backend->fileExists( [ 'src' => $fileD ] ),
			"File does not exist at $fileD" );

		$this->assertTrue( $this->backend->fileExists( [ 'src' => $fileC ] ),
			"File exists at $fileC" );
		$this->assertEquals( $fileBContents,
			$this->backend->getFileContents( [ 'src' => $fileC ] ),
			"Correct file contents of $fileC" );
		$this->assertEquals( strlen( $fileBContents ),
			$this->backend->getFileSize( [ 'src' => $fileC ] ),
			"Correct file size of $fileC" );
		$this->assertEquals( Wikimedia\base_convert( sha1( $fileBContents ), 16, 36, 31 ),
			$this->backend->getFileSha1Base36( [ 'src' => $fileC ] ),
			"Correct file SHA-1 of $fileC" );
	}

	public function testDoOperationsPipeline() {
		$base = self::baseStorePath();

		$fileAContents = '3tqtmoeatmn4wg4qe-mg3qt3 tq';
		$fileBContents = 'g-jmq3gpqgt3qtg q3GT ';
		$fileCContents = 'eigna[ogmewt 3qt g3qg flew[ag';

		$tmpNameA = TempFSFile::factory( "unittests_", 'txt', wfTempDir() )->getPath();
		$tmpNameB = TempFSFile::factory( "unittests_", 'txt', wfTempDir() )->getPath();
		$tmpNameC = TempFSFile::factory( "unittests_", 'txt', wfTempDir() )->getPath();
		$this->addTmpFiles( [ $tmpNameA, $tmpNameB, $tmpNameC ] );
		file_put_contents( $tmpNameA, $fileAContents );
		file_put_contents( $tmpNameB, $fileBContents );
		file_put_contents( $tmpNameC, $fileCContents );

		$fileA = "$base/unittest-cont1/e/a/b/fileA.txt";
		$fileB = "$base/unittest-cont1/e/a/b/fileB.txt";
		$fileC = "$base/unittest-cont1/e/a/b/fileC.txt";
		$fileD = "$base/unittest-cont1/e/a/b/fileD.txt";

		$this->prepare( [ 'dir' => dirname( $fileA ) ] );
		$this->create( [ 'dst' => $fileA, 'content' => $fileAContents ] );
		$this->prepare( [ 'dir' => dirname( $fileB ) ] );
		$this->prepare( [ 'dir' => dirname( $fileC ) ] );
		$this->prepare( [ 'dir' => dirname( $fileD ) ] );

		$status = $this->backend->doOperations( [
			[ 'op' => 'store', 'src' => $tmpNameA, 'dst' => $fileA, 'overwriteSame' => 1 ],
			[ 'op' => 'store', 'src' => $tmpNameB, 'dst' => $fileB, 'overwrite' => 1 ],
			[ 'op' => 'store', 'src' => $tmpNameC, 'dst' => $fileC, 'overwrite' => 1 ],
			[ 'op' => 'copy', 'src' => $fileA, 'dst' => $fileC, 'overwrite' => 1 ],
			// Now: A:<A>, B:<B>, C:<A>, D:<empty> (file:<orginal contents>)
			[ 'op' => 'copy', 'src' => $fileC, 'dst' => $fileA, 'overwriteSame' => 1 ],
			// Now: A:<A>, B:<B>, C:<A>, D:<empty>
			[ 'op' => 'move', 'src' => $fileC, 'dst' => $fileD, 'overwrite' => 1 ],
			// Now: A:<A>, B:<B>, C:<empty>, D:<A>
			[ 'op' => 'move', 'src' => $fileB, 'dst' => $fileC ],
			// Now: A:<A>, B:<empty>, C:<B>, D:<A>
			[ 'op' => 'move', 'src' => $fileD, 'dst' => $fileA, 'overwriteSame' => 1 ],
			// Now: A:<A>, B:<empty>, C:<B>, D:<empty>
			[ 'op' => 'move', 'src' => $fileC, 'dst' => $fileA, 'overwrite' => 1 ],
			// Now: A:<B>, B:<empty>, C:<empty>, D:<empty>
			[ 'op' => 'copy', 'src' => $fileA, 'dst' => $fileC ],
			// Now: A:<B>, B:<empty>, C:<B>, D:<empty>
			[ 'op' => 'move', 'src' => $fileA, 'dst' => $fileC, 'overwriteSame' => 1 ],
			// Now: A:<empty>, B:<empty>, C:<B>, D:<empty>
			[ 'op' => 'copy', 'src' => $fileC, 'dst' => $fileC, 'overwrite' => 1 ],
			// Does nothing
			[ 'op' => 'copy', 'src' => $fileC, 'dst' => $fileC, 'overwriteSame' => 1 ],
			// Does nothing
			[ 'op' => 'move', 'src' => $fileC, 'dst' => $fileC, 'overwrite' => 1 ],
			// Does nothing
			[ 'op' => 'move', 'src' => $fileC, 'dst' => $fileC, 'overwriteSame' => 1 ],
			// Does nothing
			[ 'op' => 'null' ],
			// Does nothing
		] );

		$this->assertStatusGood( $status, "Operation batch succeeded" );
		$this->assertCount( 16, $status->success,
			"Operation batch has correct success array" );

		$this->assertFalse( $this->backend->fileExists( [ 'src' => $fileA ] ),
			"File does not exist at $fileA" );
		$this->assertFalse( $this->backend->fileExists( [ 'src' => $fileB ] ),
			"File does not exist at $fileB" );
		$this->assertFalse( $this->backend->fileExists( [ 'src' => $fileD ] ),
			"File does not exist at $fileD" );

		$this->assertTrue( $this->backend->fileExists( [ 'src' => $fileC ] ),
			"File exists at $fileC" );
		$this->assertEquals( $fileBContents,
			$this->backend->getFileContents( [ 'src' => $fileC ] ),
			"Correct file contents of $fileC" );
		$this->assertEquals( strlen( $fileBContents ),
			$this->backend->getFileSize( [ 'src' => $fileC ] ),
			"Correct file size of $fileC" );
		$this->assertEquals( Wikimedia\base_convert( sha1( $fileBContents ), 16, 36, 31 ),
			$this->backend->getFileSha1Base36( [ 'src' => $fileC ] ),
			"Correct file SHA-1 of $fileC" );
	}

	public function testDoOperationsFailing() {
		$base = self::baseStorePath();

		$fileA = "$base/unittest-cont2/a/b/fileA.txt";
		$fileAContents = '3tqtmoeatmn4wg4qe-mg3qt3 tq';
		$fileB = "$base/unittest-cont2/a/b/fileB.txt";
		$fileBContents = 'g-jmq3gpqgt3qtg q3GT ';
		$fileC = "$base/unittest-cont2/a/b/fileC.txt";
		$fileCContents = 'eigna[ogmewt 3qt g3qg flew[ag';
		$fileD = "$base/unittest-cont2/a/b/fileD.txt";

		$this->prepare( [ 'dir' => dirname( $fileA ) ] );
		$this->create( [ 'dst' => $fileA, 'content' => $fileAContents ] );
		$this->prepare( [ 'dir' => dirname( $fileB ) ] );
		$this->create( [ 'dst' => $fileB, 'content' => $fileBContents ] );
		$this->prepare( [ 'dir' => dirname( $fileC ) ] );
		$this->create( [ 'dst' => $fileC, 'content' => $fileCContents ] );

		$status = $this->backend->doOperations( [
			[ 'op' => 'copy', 'src' => $fileA, 'dst' => $fileC, 'overwrite' => 1 ],
			// Now: A:<A>, B:<B>, C:<A>, D:<empty> (file:<orginal contents>)
			[ 'op' => 'copy', 'src' => $fileC, 'dst' => $fileA, 'overwriteSame' => 1 ],
			// Now: A:<A>, B:<B>, C:<A>, D:<empty>
			[ 'op' => 'copy', 'src' => $fileB, 'dst' => $fileD, 'overwrite' => 1 ],
			// Now: A:<A>, B:<B>, C:<A>, D:<B>
			[ 'op' => 'move', 'src' => $fileC, 'dst' => $fileD ],
			// Now: A:<A>, B:<B>, C:<A>, D:<empty> (failed)
			[ 'op' => 'move', 'src' => $fileB, 'dst' => $fileC, 'overwriteSame' => 1 ],
			// Now: A:<A>, B:<B>, C:<A>, D:<empty> (failed)
			[ 'op' => 'move', 'src' => $fileB, 'dst' => $fileA, 'overwrite' => 1 ],
			// Now: A:<B>, B:<empty>, C:<A>, D:<empty>
			[ 'op' => 'delete', 'src' => $fileD ],
			// Now: A:<B>, B:<empty>, C:<A>, D:<empty>
			[ 'op' => 'null' ],
			// Does nothing
		], [ 'force' => 1 ] );

		$this->assertStatusWarning( 'backend-fail-alreadyexists', $status );
		$this->assertStatusWarning( 'backend-fail-notsame', $status );
		$this->assertCount( 8, $status->success,
			"Operation batch has correct success array" );

		$this->assertFalse( $this->backend->fileExists( [ 'src' => $fileB ] ),
			"File does not exist at $fileB" );
		$this->assertFalse( $this->backend->fileExists( [ 'src' => $fileD ] ),
			"File does not exist at $fileD" );

		$this->assertTrue( $this->backend->fileExists( [ 'src' => $fileA ] ),
			"File does not exist at $fileA" );
		$this->assertTrue( $this->backend->fileExists( [ 'src' => $fileC ] ),
			"File exists at $fileC" );
		$this->assertEquals( $fileBContents,
			$this->backend->getFileContents( [ 'src' => $fileA ] ),
			"Correct file contents of $fileA" );
		$this->assertEquals( strlen( $fileBContents ),
			$this->backend->getFileSize( [ 'src' => $fileA ] ),
			"Correct file size of $fileA" );
		$this->assertEquals( Wikimedia\base_convert( sha1( $fileBContents ), 16, 36, 31 ),
			$this->backend->getFileSha1Base36( [ 'src' => $fileA ] ),
			"Correct file SHA-1 of $fileA" );
	}

	public function testGetFileList() {
		$backendName = $this->backendClass();
		$base = self::baseStorePath();

		// This is null on FSFileBackend, because it knows about all containers
		// that exist, whereas Swift would need to do a remote request, so it
		// just returns an empty iterator.
		$iter = $this->backend->getFileList( [ 'dir' => "$base/unittest-cont-notexists" ] );
		$this->assertThat( $iter, $this->logicalOr( $this->isNull(), $this->countOf( 0 ) ) );

		$files = [
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
		];

		// Add the files
		$ops = [];
		foreach ( $files as $file ) {
			$this->prepare( [ 'dir' => dirname( $file ) ] );
			$ops[] = [ 'op' => 'create', 'content' => 'xxy', 'dst' => $file ];
		}
		$status = $this->backend->doQuickOperations( $ops );
		$this->assertStatusGood( $status,
			"Creation of files succeeded ($backendName)." );

		// Expected listing at root
		$expected = [
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
		];
		sort( $expected );

		// Actual listing (no trailing slash) at root
		$iter = $this->backend->getFileList( [ 'dir' => "$base/unittest-cont1" ] );
		$this->assertNotNull( $iter );
		$list = $this->listToArray( $iter );
		sort( $list );
		$this->assertEquals( $expected, $list, "Correct file listing ($backendName)." );

		// Actual listing (no trailing slash) at root with advise
		$iter = $this->backend->getFileList( [
			'dir' => "$base/unittest-cont1",
			'adviseStat' => 1
		] );
		$this->assertNotNull( $iter );
		$list = $this->listToArray( $iter );
		sort( $list );
		$this->assertEquals( $expected, $list, "Correct file listing ($backendName)." );

		// Actual listing (with trailing slash) at root
		$list = [];
		$iter = $this->backend->getFileList( [ 'dir' => "$base/unittest-cont1/" ] );
		$this->assertNotNull( $iter );
		foreach ( $iter as $file ) {
			$list[] = $file;
		}
		sort( $list );
		$this->assertEquals( $expected, $list, "Correct file listing ($backendName)." );

		// Expected listing at subdir
		$expected = [
			"test1.txt",
			"test2.txt",
			"test3.txt",
			"test4.txt",
			"test5.txt",
			"sub/test0.txt",
			"sub/120-px-file.txt",
		];
		sort( $expected );

		// Actual listing (no trailing slash) at subdir
		$iter = $this->backend->getFileList( [ 'dir' => "$base/unittest-cont1/e/subdir2/subdir" ] );
		$this->assertNotNull( $iter );
		$list = $this->listToArray( $iter );
		sort( $list );
		$this->assertEquals( $expected, $list, "Correct file listing ($backendName)." );

		// Actual listing (no trailing slash) at subdir with advise
		$iter = $this->backend->getFileList( [
			'dir' => "$base/unittest-cont1/e/subdir2/subdir",
			'adviseStat' => 1
		] );
		$this->assertNotNull( $iter );
		$list = $this->listToArray( $iter );
		sort( $list );
		$this->assertEquals( $expected, $list, "Correct file listing ($backendName)." );

		// Actual listing (with trailing slash) at subdir
		$list = [];
		$iter = $this->backend->getFileList( [ 'dir' => "$base/unittest-cont1/e/subdir2/subdir/" ] );
		$this->assertNotNull( $iter );
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
		$iter = $this->backend->getTopFileList( [ 'dir' => "$base/unittest-cont1" ] );
		$this->assertNotNull( $iter );
		$list = $this->listToArray( $iter );
		sort( $list );
		$this->assertEquals( [], $list, "Correct top file listing ($backendName)." );

		// Expected listing (top files only) at subdir
		$expected = [
			"test1.txt",
			"test2.txt",
			"test3.txt",
			"test4.txt",
			"test5.txt"
		];
		sort( $expected );

		// Actual listing (top files only) at subdir
		$iter = $this->backend->getTopFileList(
			[ 'dir' => "$base/unittest-cont1/e/subdir2/subdir" ]
		);
		$this->assertNotNull( $iter );
		$list = $this->listToArray( $iter );
		sort( $list );
		$this->assertEquals( $expected, $list, "Correct top file listing ($backendName)." );

		// Actual listing (top files only) at subdir with advise
		$iter = $this->backend->getTopFileList( [
			'dir' => "$base/unittest-cont1/e/subdir2/subdir",
			'adviseStat' => 1
		] );
		$this->assertNotNull( $iter );
		$list = $this->listToArray( $iter );
		sort( $list );
		$this->assertEquals( $expected, $list, "Correct top file listing ($backendName)." );

		foreach ( $files as $file ) { // clean up
			$this->backend->doOperation( [ 'op' => 'delete', 'src' => $file ] );
		}

		$iter = $this->backend->getFileList( [ 'dir' => "$base/unittest-cont1/not/exists" ] );
		$this->assertNotNull( $iter );
		foreach ( $iter as $item ) {
			// no errors
		}
	}

	public function testGetDirectoryList() {
		$backendName = $this->backendClass();

		$base = self::baseStorePath();
		$files = [
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
		];

		// Add the files
		$ops = [];
		foreach ( $files as $file ) {
			$this->prepare( [ 'dir' => dirname( $file ) ] );
			$ops[] = [ 'op' => 'create', 'content' => 'xxy', 'dst' => $file ];
		}
		$status = $this->backend->doQuickOperations( $ops );
		$this->assertStatusGood( $status,
			"Creation of files succeeded ($backendName)." );

		$this->assertTrue(
			$this->backend->directoryExists( [ 'dir' => "$base/unittest-cont1/e/subdir1" ] ),
			"Directory exists in ($backendName)."
		);
		$this->assertTrue(
			$this->backend->directoryExists( [ 'dir' => "$base/unittest-cont1/e/subdir2/subdir" ] ),
			"Directory exists in ($backendName)."
		);
		$this->assertFalse(
			$this->backend->directoryExists( [ 'dir' => "$base/unittest-cont1/e/subdir2/test1.txt" ] ),
			"Directory does not exists in ($backendName)."
		);

		// Expected listing
		$expected = [
			"e",
		];
		sort( $expected );

		// Actual listing (no trailing slash)
		$list = [];
		$iter = $this->backend->getTopDirectoryList( [ 'dir' => "$base/unittest-cont1" ] );
		$this->assertNotNull( $iter );
		foreach ( $iter as $file ) {
			$list[] = $file;
		}
		sort( $list );

		$this->assertEquals( $expected, $list, "Correct top dir listing ($backendName)." );

		// Expected listing
		$expected = [
			"subdir1",
			"subdir2",
			"subdir3",
			"subdir4",
		];
		sort( $expected );

		// Actual listing (no trailing slash)
		$list = [];
		$iter = $this->backend->getTopDirectoryList( [ 'dir' => "$base/unittest-cont1/e" ] );
		$this->assertNotNull( $iter );
		foreach ( $iter as $file ) {
			$list[] = $file;
		}
		sort( $list );

		$this->assertEquals( $expected, $list, "Correct top dir listing ($backendName)." );

		// Actual listing (with trailing slash)
		$list = [];
		$iter = $this->backend->getTopDirectoryList( [ 'dir' => "$base/unittest-cont1/e/" ] );
		$this->assertNotNull( $iter );
		foreach ( $iter as $file ) {
			$list[] = $file;
		}
		sort( $list );

		$this->assertEquals( $expected, $list, "Correct top dir listing ($backendName)." );

		// Expected listing
		$expected = [
			"subdir",
		];
		sort( $expected );

		// Actual listing (no trailing slash)
		$list = [];
		$iter = $this->backend->getTopDirectoryList( [ 'dir' => "$base/unittest-cont1/e/subdir2" ] );
		$this->assertNotNull( $iter );
		foreach ( $iter as $file ) {
			$list[] = $file;
		}
		sort( $list );

		$this->assertEquals( $expected, $list, "Correct top dir listing ($backendName)." );

		// Actual listing (with trailing slash)
		$list = [];
		$iter = $this->backend->getTopDirectoryList(
			[ 'dir' => "$base/unittest-cont1/e/subdir2/" ]
		);
		$this->assertNotNull( $iter );

		foreach ( $iter as $file ) {
			$list[] = $file;
		}
		sort( $list );

		$this->assertEquals( $expected, $list, "Correct top dir listing ($backendName)." );

		// Actual listing (using iterator second time)
		$list = [];
		foreach ( $iter as $file ) {
			$list[] = $file;
		}
		sort( $list );

		$this->assertEquals(
			$expected,
			$list,
			"Correct top dir listing ($backendName), second iteration."
		);

		// Expected listing (recursive)
		$expected = [
			"e",
			"e/subdir1",
			"e/subdir2",
			"e/subdir3",
			"e/subdir4",
			"e/subdir2/subdir",
			"e/subdir3/subdir",
			"e/subdir4/subdir",
			"e/subdir4/subdir/sub",
		];
		sort( $expected );

		// Actual listing (recursive)
		$list = [];
		$iter = $this->backend->getDirectoryList( [ 'dir' => "$base/unittest-cont1/" ] );
		$this->assertNotNull( $iter );
		foreach ( $iter as $file ) {
			$list[] = $file;
		}
		sort( $list );

		$this->assertEquals( $expected, $list, "Correct dir listing ($backendName)." );

		// Expected listing (recursive)
		$expected = [
			"subdir",
			"subdir/sub",
		];
		sort( $expected );

		// Actual listing (recursive)
		$list = [];
		$iter = $this->backend->getDirectoryList( [ 'dir' => "$base/unittest-cont1/e/subdir4" ] );
		$this->assertNotNull( $iter );
		foreach ( $iter as $file ) {
			$list[] = $file;
		}
		sort( $list );

		$this->assertEquals( $expected, $list, "Correct dir listing ($backendName)." );

		// Actual listing (recursive, second time)
		$list = [];
		foreach ( $iter as $file ) {
			$list[] = $file;
		}
		sort( $list );

		$this->assertEquals( $expected, $list, "Correct dir listing ($backendName)." );

		$iter = $this->backend->getDirectoryList( [ 'dir' => "$base/unittest-cont1/e/subdir1" ] );
		$this->assertNotNull( $iter );
		$items = $this->listToArray( $iter );
		$this->assertEquals( [], $items, "Directory listing is empty." );

		foreach ( $files as $file ) { // clean up
			$this->backend->doOperation( [ 'op' => 'delete', 'src' => $file ] );
		}

		$iter = $this->backend->getDirectoryList( [ 'dir' => "$base/unittest-cont1/not/exists" ] );
		$this->assertNotNull( $iter );
		foreach ( $iter as $file ) {
			// no errors
		}

		$items = $this->listToArray( $iter );
		$this->assertEquals( [], $items, "Directory listing is empty." );

		$iter = $this->backend->getDirectoryList( [ 'dir' => "$base/unittest-cont1/e/not/exists" ] );
		$this->assertNotNull( $iter );
		$items = $this->listToArray( $iter );
		$this->assertEquals( [], $items, "Directory listing is empty." );
	}

	public function testLockCalls() {
		$backendName = $this->backendClass();
		$base = $this->backend->getContainerStoragePath( 'test' );

		$paths = [
			"$base/test1.txt",
			"$base/test2.txt",
			"$base/test3.txt",
			"$base/subdir1",
			"$base/subdir1", // duplicate
			"$base/subdir1/test1.txt",
			"$base/subdir1/test2.txt",
			"$base/subdir2",
			"$base/subdir2", // duplicate
			"$base/subdir2/test3.txt",
			"$base/subdir2/test4.txt",
			"$base/subdir2/subdir",
			"$base/subdir2/subdir/test1.txt",
			"$base/subdir2/subdir/test2.txt",
			"$base/subdir2/subdir/test3.txt",
			"$base/subdir2/subdir/test4.txt",
			"$base/subdir2/subdir/test5.txt",
			"$base/subdir2/subdir/sub",
			"$base/subdir2/subdir/sub/test0.txt",
			"$base/subdir2/subdir/sub/120-px-file.txt",
		];

		for ( $i = 0; $i < 2; $i++ ) {
			$status = $this->backend->lockFiles( $paths, LockManager::LOCK_EX );
			$this->assertStatusGood( $status,
				"Locking of files succeeded ($backendName) ($i)." );

			$status = $this->backend->lockFiles( $paths, LockManager::LOCK_SH );
			$this->assertStatusGood( $status,
				"Locking of files succeeded ($backendName) ($i)." );

			$status = $this->backend->unlockFiles( $paths, LockManager::LOCK_SH );
			$this->assertStatusGood( $status,
				"Locking of files succeeded ($backendName) ($i)." );

			$status = $this->backend->unlockFiles( $paths, LockManager::LOCK_EX );
			$this->assertStatusGood( $status,
				"Locking of files succeeded ($backendName). ($i)" );

			# # Flip the acquire/release ordering around ##

			$status = $this->backend->lockFiles( $paths, LockManager::LOCK_SH );
			$this->assertStatusGood( $status,
				"Locking of files succeeded ($backendName) ($i)." );

			$status = $this->backend->lockFiles( $paths, LockManager::LOCK_EX );
			$this->assertStatusGood( $status,
				"Locking of files succeeded ($backendName) ($i)." );

			$status = $this->backend->unlockFiles( $paths, LockManager::LOCK_EX );
			$this->assertStatusGood( $status,
				"Locking of files succeeded ($backendName). ($i)" );

			$status = $this->backend->unlockFiles( $paths, LockManager::LOCK_SH );
			$this->assertStatusGood( $status,
				"Locking of files succeeded ($backendName) ($i)." );
		}

		$status = Status::newGood();
		$sl = $this->backend->getScopedFileLocks( $paths, LockManager::LOCK_EX, $status );
		$this->assertInstanceOf( ScopedLock::class, $sl,
			"Scoped locking of files succeeded ($backendName)." );
		$this->assertStatusGood( $status,
			"Scoped locking of files succeeded ($backendName)." );

		ScopedLock::release( $sl );
		$this->assertNull( $sl,
			"Scoped unlocking of files succeeded ($backendName)." );
		$this->assertStatusGood( $status,
			"Scoped unlocking of files succeeded ($backendName)." );
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

		return $this->backend->doQuickOperations( [ $params ] );
	}

	public function tearDownFiles() {
		$containers = [ 'unittest-cont1', 'unittest-cont2' ];
		foreach ( $containers as $container ) {
			$this->deleteFiles( $container );
		}
	}

	private function deleteFiles( $container ) {
		$base = self::baseStorePath();
		$iter = $this->backend->getFileList( [ 'dir' => "$base/$container" ] );
		if ( $iter ) {
			foreach ( $iter as $file ) {
				$this->backend->quickDelete( [ 'src' => "$base/$container/$file" ] );
			}
			// free the directory, to avoid Permission denied under windows on rmdir
			unset( $iter );
		}
		$this->backend->clean( [ 'dir' => "$base/$container", 'recursive' => 1 ] );
	}
}
