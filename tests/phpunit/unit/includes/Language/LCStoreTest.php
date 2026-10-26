<?php

namespace MediaWiki\Tests\Unit\Language;

use MediaWiki\Language\LCStore;
use MediaWikiUnitTestCase;

/**
 * @covers \MediaWiki\Language\LCStore
 * @group Language
 */
class LCStoreTest extends MediaWikiUnitTestCase {

	public function testSetMessages() {
		$store = new class extends LCStore {
			public array $calls = [];

			public function get( $code, $key ) {
				return null;
			}

			public function startWrite( $code ) {
			}

			public function finishWrite() {
			}

			public function set( $key, $value ) {
				$this->calls[] = [ $key, $value ];
			}

			public function lateFallback(): bool {
				return false;
			}
		};

		$store->setMessages( [] );
		$store->setMessages( [ 'foo' => 'Foo', 'bar' => [ 'Bar' ] ] );

		$this->assertSame(
			[ [ 'messages:foo', 'Foo' ], [ 'messages:bar', [ 'Bar' ] ] ],
			$store->calls
		);
	}
}
