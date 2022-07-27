<?php
/**
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along
 * with this program; if not, write to the Free Software Foundation, Inc.,
 * 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA.
 * http://www.gnu.org/copyleft/gpl.html
 *
 * @file
 */

use MediaWiki\Tests\Unit\Libs\Rdbms\AddQuoterMock;
use Wikimedia\Rdbms\Platform\MySQLPlatform;

class MySQLPlatformTest extends PHPUnit\Framework\TestCase {

	use MediaWikiCoversValidator;

	/**
	 * @dataProvider provideDiapers
	 * @covers \Wikimedia\Rdbms\Platform\MySQLPlatform::addIdentifierQuotes
	 */
	public function testAddIdentifierQuotes( $expected, $in ) {
		$platform = new MySQLPlatform( new AddQuoterMock() );
		$quoted = $platform->addIdentifierQuotes( $in );
		$this->assertEquals( $expected, $quoted );
	}

	/**
	 * @covers \Wikimedia\Rdbms\Platform\MySQLPlatform::addIdentifierQuotes
	 */
	public function testAddIdentifierQuotesNull() {
		$platform = new MySQLPlatform( new AddQuoterMock() );
		// Ignore PHP 8.1+ warning about null to str_replace()
		$quoted = @$platform->addIdentifierQuotes( null );
		$this->assertEquals( '``', $quoted );
	}

	/**
	 * Feeds testAddIdentifierQuotes
	 *
	 * Named per T22281 convention.
	 */
	public static function provideDiapers() {
		return [
			// Format: expected, input
			[ '``', '' ],

			// Dear codereviewer, guess what addIdentifierQuotes()
			// will return with thoses:
			[ '``', false ],
			[ '`1`', true ],

			// We never know what could happen
			[ '`0`', 0 ],
			[ '`1`', 1 ],

			// Whatchout! Should probably use something more meaningful
			[ "`'`", "'" ],  # single quote
			[ '`"`', '"' ],  # double quote
			[ '````', '`' ], # backtick
			[ '`’`', '’' ],  # apostrophe (look at your encyclopedia)

			// sneaky NUL bytes are lurking everywhere
			[ '``', "\0" ],
			[ '`xyzzy`', "\0x\0y\0z\0z\0y\0" ],

			// unicode chars
			[
				"`\u{0001}a\u{FFFF}b`",
				"\u{0001}a\u{FFFF}b"
			],
			[
				"`\u{0001}\u{FFFF}`",
				"\u{0001}\u{0000}\u{FFFF}\u{0000}"
			],
			[ '`☃`', '☃' ],
			[ '`メインページ`', 'メインページ' ],
			[ '`Басты_бет`', 'Басты_бет' ],

			// Real world:
			[ '`Alix`', 'Alix' ],  # while( ! $recovered ) { sleep(); }
			[ '`Backtick: ```', 'Backtick: `' ],
			[ '`This is a test`', 'This is a test' ],
		];
	}
}
