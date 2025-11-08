<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

namespace Wikimedia\Tests\Rdbms;

use MediaWiki\Tests\Unit\Libs\Rdbms\AddQuoterMock;
use MediaWikiCoversValidator;
use PHPUnit\Framework\TestCase;
use Wikimedia\Rdbms\DatabaseDomain;
use Wikimedia\Rdbms\DBLanguageError;
use Wikimedia\Rdbms\Platform\MySQLPlatform;

/**
 * @covers \Wikimedia\Rdbms\Platform\MySQLPlatform
 */
class MySQLPlatformTest extends TestCase {

	use MediaWikiCoversValidator;

	private MySQLPlatform $platform;

	protected function setUp(): void {
		parent::setUp();
		$this->platform = new MySQLPlatform( new AddQuoterMock() );
	}

	/**
	 * @dataProvider provideDiapers
	 */
	public function testAddIdentifierQuotes( $expected, $in ) {
		if ( $expected === 'yagni' ) {
			$this->expectException( DBLanguageError::class );
		}
		$quoted = $this->platform->addIdentifierQuotes( $in );
		$this->assertEquals( $expected, $quoted );
	}

	public function testAddIdentifierQuotesNull() {
		// Ignore PHP 8.1+ warning about null to str_replace()
		$quoted = @$this->platform->addIdentifierQuotes( null );
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
			// If expected is "yagni" that means that you're probably not going
			// to need the case to work correctly
			[ '``', '' ],

			// Dear codereviewer, guess what addIdentifierQuotes()
			// will return with thoses:
			[ '``', false ],
			[ '`1`', true ],

			// We never know what could happen
			[ '`0`', 0 ],
			[ '`1`', 1 ],

			// Whatchout! Should probably use something more meaningful
			'single quote' => [ 'yagni', "'" ],
			'double quote' => [ 'yagni', '"' ],
			'backtick' => [ 'yagni', '`' ],
			'apostrophe' => [ '`’`', '’' ],

			// sneaky NUL bytes are lurking everywhere
			[ 'yagni', "\0" ],
			[ 'yagni', "\0x\0y\0z\0z\0y\0" ],

			// unicode chars
			[
				"`\u{0001}a\u{FFFF}b`",
				"\u{0001}a\u{FFFF}b"
			],
			[
				"yagni",
				"\u{0001}\u{0000}\u{FFFF}\u{0000}"
			],
			[ '`☃`', '☃' ],
			[ '`メインページ`', 'メインページ' ],
			[ '`Басты_бет`', 'Басты_бет' ],

			// Real world:
			[ '`Alix`', 'Alix' ], # while( ! $recovered ) { sleep(); }
			[ 'yagni', 'Backtick: `' ],
			[ '`This is a test`', 'This is a test' ],
		];
	}

	public static function provideTableIdentifiers() {
		// No DB name set
		yield [
			'table',
			new DatabaseDomain( null, null, '' ),
			[ null, 'table' ],
			null
		];
		yield [
			'database.table',
			new DatabaseDomain( null, null, '' ),
			[ 'database', 'table' ],
			null
		];
		yield [
			'database.schema.table',
			new DatabaseDomain( null, null, '' ),
			[ 'database', 'table' ],
			DBLanguageError::class
		];
		yield [
			'"database"."schema"."table"',
			new DatabaseDomain( null, null, '' ),
			[ 'database', 'table' ],
			DBLanguageError::class
		];
		yield [
			'`database`.`table`',
			new DatabaseDomain( null, null, '' ),
			[ 'database', 'table' ],
			null
		];
		// DB name set
		yield [
			'table',
			new DatabaseDomain( 'database', null, '' ),
			[ 'database', 'table' ],
			null
		];
		yield [
			'database.table',
			new DatabaseDomain( 'database', null, '' ),
			[ 'database', 'table' ],
			null
		];
		yield [
			'database.schema.table',
			new DatabaseDomain( 'database', null, '' ),
			[ 'database', 'table' ],
			DBLanguageError::class
		];
		yield [
			'`database`.`schema`.`table`',
			new DatabaseDomain( 'database', null, '' ),
			[ 'database', 'table' ],
			DBLanguageError::class
		];
		yield [
			'`database`.`table`',
			new DatabaseDomain( 'database', null, '' ),
			[ 'database', 'table' ],
			null
		];
	}

	/**
	 * @dataProvider provideTableIdentifiers
	 */
	public function testGetDatabaseAndTableIdentifiers(
		$tableName,
		$domain,
		$expectedIdentifiers,
		$expectedException
	) {
		$platform = new MySQLPlatform( new AddQuoterMock(), null, $domain );

		if ( $expectedException !== null ) {
			$this->expectException( DBLanguageError::class );
			$platform->getDatabaseAndTableIdentifier( $tableName );
		} else {
			$this->assertSame(
				$expectedIdentifiers,
				$platform->getDatabaseAndTableIdentifier( $tableName )
			);
		}
	}
}
