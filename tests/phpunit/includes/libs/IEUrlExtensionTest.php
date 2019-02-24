<?php

class IEUrlExtensionTest extends PHPUnit\Framework\TestCase {

	use MediaWikiCoversValidator;

	public function provideFindIE6Extension() {
		return [
			// url, expected, message
			[ 'x.y', 'y', 'Simple extension' ],
			[ 'x', '', 'No extension' ],
			[ '', '', 'Empty string' ],
			[ '?', '', 'Question mark only' ],
			[ '.x?', 'x', 'Extension then question mark' ],
			[ '?.x', 'x', 'Question mark then extension' ],
			[ '.x*', '', 'Extension with invalid character' ],
			[ '*.x', 'x', 'Invalid character followed by an extension' ],
			[ 'a?b?.c?.d?e?f', 'c', 'Multiple question marks' ],
			[ 'a?b?.exe?.d?.e', 'd', '.exe exception' ],
			[ 'a?b?.exe', 'exe', '.exe exception 2' ],
			[ 'a#b.c', '', 'Hash character preceding extension' ],
			[ 'a?#b.c', '', 'Hash character preceding extension 2' ],
			[ '.', '', 'Dot at end of string' ],
			[ 'x.y.z', 'z', 'Two dots' ],
			[ 'example.php?foo=a&bar=b', 'php', 'Script with query' ],
			[ 'example%2Ephp?foo=a&bar=b', '', 'Script with urlencoded dot and query' ],
			[ 'example%2Ephp?foo=a.x&bar=b.y', 'y', 'Script with urlencoded dot and query with dot' ],
		];
	}

	/**
	 * @covers IEUrlExtension::findIE6Extension
	 * @dataProvider provideFindIE6Extension
	 */
	public function testFindIE6Extension( $url, $expected, $message ) {
		$this->assertEquals(
			$expected,
			IEUrlExtension::findIE6Extension( $url ),
			$message
		);
	}
}
