<?php

namespace MediaWiki\ExternalStore;

use DiffHistoryBlob;
use Wikimedia\TestingAccessWrapper;

/**
 * @covers MediaWiki\ExternalStore\XdiffMultiContentBlob
 */
class XdiffMultiContentBlobTest extends \MediaWikiTestCase {

	protected function skipUnlessXdiffIsAvailable() {
		$this->checkPHPExtension( 'xdiff' );

		if ( !function_exists( 'xdiff_string_rabdiff' ) ) {
			$this->markTestSkipped( 'The version of xdiff extension is lower than 1.5.0' );
		}
	}

	/**
	 * @dataProvider provideXdiffAdler32
	 */
	public function testXdiffAdler32( $input, $expect ) {
		$dhb = TestingAccessWrapper::newFromObject( new XdiffMultiContentBlob );
		$myHash = $dhb->xdiffAdler32( $input );
		$this->assertSame( $expect, bin2hex( $myHash ),
			"Hash of " . addcslashes( $input, "\0..\37!@\@\177..\377" ) );
	}

	/**
	 * @dataProvider provideXdiffAdler32
	 */
	public function testXdiffAdler32MatchesXdiff( $input ) {
		$this->skipUnlessXdiffIsAvailable();

		$xdiffHash = substr( xdiff_string_rabdiff( $input, '' ), 0, 4 );
		$dhb = TestingAccessWrapper::newFromObject( new XdiffMultiContentBlob );
		$myHash = $dhb->xdiffAdler32( $input );
		$this->assertSame( bin2hex( $xdiffHash ), bin2hex( $myHash ),
			"Hash of " . addcslashes( $input, "\0..\37!@\@\177..\377" ) );
	}

	public function provideXdiffAdler32() {
		return [
			'Empty string' => [ '', '00000000' ],
			'Null' => [ "\0", '00000000' ],
			'Several nulls' => [ "\0\0\0", '00000000' ],
			'An ASCII string' => [ "Hello", 'f4018705' ],
			'A string larger than xdiff\'s NMAX (5552)' => [ str_repeat( "x", 6000 ), '16fd3406' ],
		];
	}

	/**
	 * @dataProvider provideItems
	 * @param array $items
	 * @param int $expectSize
	 * @param int $expectCount
	 */
	public function testItems( $items, $expectSize, $expectCount ) {
		$obj = new XdiffMultiContentBlob;

		$keys = [];
		foreach ( $items as $i ) {
			$k = $obj->addItem( $i );
			$keys[$k] = $i;
		}

		$this->assertSame( $expectSize, $obj->getSize() );
		$this->assertSame( $expectCount, $obj->getCount() );
		foreach ( $keys as $k => $i ) {
			$this->assertSame( $i, $obj->getItem( $k ) );
		}
		$this->assertSame( false, $obj->getItem( 'bogus' ) );

		if ( function_exists( 'xdiff_string_rabdiff' ) ) {
			$blob = $obj->encode();
			$this->assertInternalType( 'string', $blob );
			$newObj = MultiContentBlob::decode( $blob );

			$this->assertSame( $expectSize, $newObj->getSize() );
			$this->assertSame( $expectCount, $newObj->getCount() );
			foreach ( $keys as $k => $i ) {
				$this->assertSame( $i, $newObj->getItem( $k ) );
			}
			$this->assertSame( false, $newObj->getItem( 'bogus' ) );
		}
	}

	public static function provideItems() {
		return [
			'basic' => [ [ 'foo', 'bar', 'baz' ], 9, 3 ],
			'empty' => [ [], 0, 0 ],
			'repetitious' => [ [ 'foo', 'bar', 'foo', 'baz', 'foo' ], 15, 5 ],
		];
	}

	/**
	 * This one fakes up the data so it won't call ->compress()
	 * @dataProvider provideEncodeDecode
	 * @param array $diffs
	 * @param array $diffMap
	 * @param array $expect
	 * @param int $expectSize
	 * @param int $expectCount
	 */
	public function testEncodeDecode( $diffs, $diffMap, $expect, $expectSize, $expectCount ) {
		$obj = new XdiffMultiContentBlob;

		// Fill in data so it won't call ->compress().
		$w = TestingAccessWrapper::newFromObject( $obj );
		$w->items = $diffs; // dummy
		$w->diffs = $diffs;
		$w->diffMap = $diffMap;

		$blob = $obj->encode();
		$this->assertInternalType( 'string', $blob );
		$newObj = MultiContentBlob::decode( $blob );

		$this->assertSame( $expectSize, $newObj->getSize() );
		$this->assertSame( $expectCount, $newObj->getCount() );
		foreach ( $expect as $k => $v ) {
			$this->assertSame( $v, $newObj->getItem( $k ) );
		}
		$this->assertSame( false, $newObj->getItem( 'bogus' ) );

		try {
			$newObj->addItem( 'foo' );
			$this->fail( 'Expected exception not thrown' );
		} catch ( \BadMethodCallException $ex ) {
			$this->assertSame(
				XdiffMultiContentBlob::class . '::addItem: Cannot add more items after sleep/wakeup',
				$ex->getMessage()
			);
		}
	}

	public static function provideEncodeDecode() {
		return [
			'From enwiki https://en.wikipedia.org/w/index.php?title=!&action=history' => [
				[
					"\000\000\000\000\000\000\000\000\001\036#REDIRECT [[Exclamation mark]]",
					"E\nS\222\036\000\000\000\001\036#redirect [[Exclamation mark]]",
				],
				[ 0, 1 ],
				[
					'#REDIRECT [[Exclamation mark]]',
					'#redirect [[Exclamation mark]]',
				],
				60, 2
			],
			'empty' => [ [], [], [], 0, 0 ],
		];
	}

	public function testDecodeBadMetadata() {
		// @codingStandardsIgnoreStart Generic.Files.LineLength.TooLong
		$blob1 = "\x7FMCB\xFF\xFE\x05xdiff\x00\x0B{\"map\":\"0\"}\x01\x0D\x00\x00\x00\x00\x00\x00\x00\x00\x01\x03foo\xDA\x41\xEC\x75";
		$blob2 = "\x7FMCB\xFF\xFE\x05xdiff\x00\x0B{\"Map\":\"0\"}\x01\x0D\x00\x00\x00\x00\x00\x00\x00\x00\x01\x03foo\x24\x97\x12\x0F";
		// @codingStandardsIgnoreEnd

		$obj = MultiContentBlob::decode( $blob1 );
		$this->assertInstanceOf( XdiffMultiContentBlob::class, $obj, 'sanity check' );

		$this->setExpectedException(
			\InvalidArgumentException::class,
			'$metadata lacks \'map\''
		);
		MultiContentBlob::decode( $blob2 );
	}

	/**
	 * This one fakes up the data so it won't call ->compress()
	 * @dataProvider provideEncodeDecode
	 * @param array $diffs
	 * @param array $diffMap
	 * @param array $expect
	 * @param int $expectSize
	 * @param int $expectCount
	 */
	public function testNewFromDiffHistoryBlob(
		$diffs, $diffMap, $expect, $expectSize, $expectCount
	) {
		$obj = new DiffHistoryBlob;
		$obj->mDiffs = $diffs;
		$obj->mDiffMap = $diffMap;

		$newObj = XdiffMultiContentBlob::newFromDiffHistoryBlob( $obj );

		$this->assertSame( $expectSize, $newObj->getSize() );
		$this->assertSame( $expectCount, $newObj->getCount() );
		foreach ( $expect as $k => $v ) {
			$this->assertSame( $v, $newObj->getItem( $k ) );
		}
		$this->assertSame( false, $newObj->getItem( 'bogus' ) );

		try {
			$newObj->addItem( 'foo' );
			$this->fail( 'Expected exception not thrown' );
		} catch ( \BadMethodCallException $ex ) {
			$this->assertSame(
				XdiffMultiContentBlob::class . '::addItem: Cannot add more items after sleep/wakeup',
				$ex->getMessage()
			);
		}
	}
}
