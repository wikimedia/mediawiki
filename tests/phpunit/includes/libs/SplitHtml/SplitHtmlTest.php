<?php

use Wikimedia\SplitHtml\SplitHtml;

class SplitHtmlTest extends MediaWikiTestCase {
	public static function provideSplitAtCharOffset() {
		return [
			// Plain text
			[ '123', 0, [ '', '123' ] ],
			[ '123', 1, [ '1', '23' ] ],
			[ '123', 2, [ '12', '3' ] ],
			[ '123', 3, [ '123', '' ] ],
			[ '123', 4, [ '123', '' ] ],

			// Count chars, respect tree depth
			[ '12<span>34</span>56', 1, [ '1', '2<span>34</span>56' ] ],
			[ '12<span>34</span>56', 2, [ '12', '<span>34</span>56' ] ],
			[ '12<span>34</span>56', 3, [ '12<span>34</span>', '56' ] ],
			[ '12<span>34</span>56', 4, [ '12<span>34</span>', '56' ] ],
			[ '12<span>34</span>56', 5, [ '12<span>34</span>5', '6' ] ],
			[ '12<span>34</span>56', 6, [ '12<span>34</span>56', '' ] ],

			// Attribute re-escaping
			[ '1<a b="&quot;">', 0, [ '', '1<a b="&quot;">' ] ],

			// Entity references are counted as single characters and re-encoded
			[ '&lt;', 0, [ '', '&lt;' ] ],
			[ '&lt;', 1, [ '&lt;', '' ] ],
		];
	}

	/** @dataProvider provideSplitAtCharOffset */
	public function testSplitAtCharOffset( $text, $offset, $expected ) {
		$result = SplitHtml::splitAtCharOffset( $text, $offset );
		$this->assertSame( $expected, $result );
	}
}
