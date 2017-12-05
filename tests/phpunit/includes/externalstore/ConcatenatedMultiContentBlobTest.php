<?php

namespace MediaWiki\ExternalStore;

use ConcatenatedGzipHistoryBlob;

/**
 * @covers MediaWiki\ExternalStore\ConcatenatedMultiContentBlob
 */
class ConcatenatedMultiContentBlobTest extends \MediaWikiTestCase {

	/**
	 * @dataProvider provideItems
	 * @param array $items
	 * @param int $expectSize
	 * @param int $expectCount
	 */
	public function testItems( $items, $expectSize, $expectCount ) {
		$obj = new ConcatenatedMultiContentBlob;

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

	public static function provideItems() {
		return [
			'basic' => [ [ 'foo', 'bar', 'baz' ], 9, 3 ],
			'empty' => [ [], 0, 0 ],
			'repetitious' => [ [ 'foo', 'bar', 'foo', 'baz', 'foo' ], 9, 3 ],
		];
	}

	public function testInvalidMetadata() {
		$obj = MultiContentBlob::decode( "\x7FMCB\xFF\xFE\x06concat\x00\x00\x00\xF2\xBA\x01\x89" );
		$this->assertInstanceOf( ConcatenatedMultiContentBlob::class, $obj, 'sanity check' );

		$this->setExpectedException(
			\InvalidArgumentException::class,
			ConcatenatedMultiContentBlob::class . ' takes no metadata'
		);
		MultiContentBlob::decode( "\x7FMCB\xFF\xFE\x06concat\x00\x02[]\x00\x19j\xD9\x12" );
	}

	/**
	 * @dataProvider provideItems
	 */
	public function testNewFromConcatenatedGzipHistoryBlob( $items, $expectSize, $expectCount ) {
		$obj = new ConcatenatedGzipHistoryBlob;

		$keys = [];
		foreach ( $items as $i ) {
			$k = $obj->addItem( $i );
			$keys[$k] = $i;
		}

		$newObj = ConcatenatedMultiContentBlob::newFromConcatenatedGzipHistoryBlob( $obj );

		$this->assertSame( $expectSize, $newObj->getSize() );
		$this->assertSame( $expectCount, $newObj->getCount() );
		foreach ( $keys as $k => $i ) {
			$this->assertSame( $i, $newObj->getItem( $k ) );
		}
		$this->assertSame( false, $newObj->getItem( 'bogus' ) );
	}
}
