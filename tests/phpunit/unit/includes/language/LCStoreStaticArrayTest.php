<?php
// phpcs:disable Generic.Files.LineLength.TooLong

/**
 * @covers LCStoreStaticArray
 * @group Language
 */
class LCStoreStaticArrayTest extends MediaWikiUnitTestCase {
	private $dir;
	private $file;

	protected function setUp() : void {
		parent::setUp();
		$this->dir = sys_get_temp_dir() . '/lcstore-array';
		$this->file = $this->dir . '/en.l10n.php';
	}

	protected function tearDown() : void {
		Wikimedia\AtEase\AtEase::quietCall( 'unlink', $this->file );
		Wikimedia\AtEase\AtEase::quietCall( 'rmdir', $this->dir );
		parent::tearDown();
	}

	private function prepareDir() {
		Wikimedia\AtEase\AtEase::quietCall( 'mkdir', $this->dir );
		return $this->dir;
	}

	private function prepareFile( $dir, $lang, array $data ) {
		file_put_contents(
			$this->file,
			'<?php return ' . var_export( $data, true ) . ';'
		);
	}

	public function testEncodeDecode() {
		$dir = $this->prepareDir();
		$cache = new LCStoreStaticArray( [ 'directory' => $dir ] );

		$data = [
			'mr-boole' => false,
			'the-zero' => 0,
			'a-number' => 42,
			'some-string' => '0',
			'common-data' => [
				3 => [ 'three', 'threa', 'phree' ],
				6 => [ 'six', 'seaks', 'phrix' ],
			],
			'some-obj' => new DateTimeZone( '-0630' ),
			'unlikely' => [
				3 => 'three',
				6 => new DateTimeZone( '-0315' ),
			],
		];
		$this->file = $dir . '/en.l10n.php';
		$cache->startWrite( 'en' );
		foreach ( $data as $key => $value ) {
			$cache->set( $key, $value );
		}
		$cache->finishWrite();

		$this->assertEquals(
			[
				'mr-boole' => false,
				'the-zero' => 0,
				'a-number' => 42,
				'some-string' => '0',
				'common-data' => [
					'v',
					[
						3 => [ 'three', 'threa', 'phree' ],
						6 => [ 'six', 'seaks', 'phrix' ],
					]
				],
				'some-obj' => [
					's',
					'O:12:"DateTimeZone":2:{s:13:"timezone_type";i:1;s:8:"timezone";s:6:"-06:30";}'
				],
				'unlikely' => [
					's',
					'a:2:{i:3;s:5:"three";i:6;O:12:"DateTimeZone":2:{s:13:"timezone_type";i:1;s:8:"timezone";s:6:"-03:15";}}'
				],
			],
			require $dir . '/en.l10n.php',
			'Encoded data'
		);

		$this->assertSame( false, $cache->get( 'en', 'mr-boole' ), 'decode boolean' );
		$this->assertSame( 0, $cache->get( 'en', 'the-zero' ), 'decode number' );
		$this->assertSame( '0', $cache->get( 'en', 'some-string' ), 'decode string' );
		$this->assertSame( [ 'six', 'seaks', 'phrix' ], $cache->get( 'en', 'common-data' )[6], 'decode array' );
		$this->assertInstanceOf(
			DateTimeZone::class,
			$cache->get( 'en', 'some-obj' ),
			'decode object'
		);
		$this->assertInstanceOf(
			DateTimeZone::class,
			$cache->get( 'en', 'unlikely' )[6],
			'decode nested object'
		);
	}

	public function testDecodeMw132Value() {
		$dir = $this->prepareDir();
		$this->prepareFile( $dir, 'en', [
			'mr-boole' => [ 'v', false ],
			'the-zero' => [ 'v', 0 ],
			'a-number' => [ 'v', 42 ],
			'some-string' => [ 'v', '0' ],
		] );
		$cache = new LCStoreStaticArray( [ 'directory' => $dir ] );

		$this->assertSame( false, $cache->get( 'en', 'mr-boole' ) );
		$this->assertSame( 0, $cache->get( 'en', 'the-zero' ) );
		$this->assertSame( '0', $cache->get( 'en', 'some-string' ) );
		$this->assertSame( 42, $cache->get( 'en', 'a-number' ) );
	}
}
