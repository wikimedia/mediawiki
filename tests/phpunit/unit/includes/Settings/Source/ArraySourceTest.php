<?php

namespace MediaWiki\Tests\Unit\Settings\Source;

use MediaWiki\Settings\Source\ArraySource;
use MediaWikiCoversValidator;
use PHPUnit\Framework\TestCase;

/**
 * @covers \MediaWiki\Settings\Source\ArraySource
 */
class ArraySourceTest extends TestCase {
	use MediaWikiCoversValidator;

	public function testLoad() {
		$source = new ArraySource(
			[ 'config' => [ 'MySettings' => 'BlaBla' ] ]
		);

		$this->assertSame(
			[ 'config' => [ 'MySettings' => 'BlaBla' ] ],
			$source->load()
		);
	}
}
