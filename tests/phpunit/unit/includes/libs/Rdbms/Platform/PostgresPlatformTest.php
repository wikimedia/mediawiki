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
use Wikimedia\Rdbms\Platform\PostgresPlatform;

/**
 * @covers \Wikimedia\Rdbms\Platform\PostgresPlatform
 */
class PostgresPlatformTest extends TestCase {

	use MediaWikiCoversValidator;

	private PostgresPlatform $platform;

	protected function setUp(): void {
		parent::setUp();
		$this->platform = new PostgresPlatform( new AddQuoterMock() );
	}

	public static function provideTableIdentifiers() {
		yield [
			'table',
			new DatabaseDomain( 'database', null, '' ),
			[ 'database', 'table' ]
		];
		yield [
			'database.table',
			new DatabaseDomain( 'database', null, '' ),
			[ 'database', 'table' ]
		];
		yield [
			'database.schema.table',
			new DatabaseDomain( 'database', null, '' ),
			[ 'database', 'table' ]
		];
		yield [
			'"database"."schema"."table"',
			new DatabaseDomain( 'database', null, '' ),
			[ 'database', 'table' ]
		];
		yield [
			'"database"."table"',
			new DatabaseDomain( 'database', null, '' ),
			[ 'database', 'table' ]
		];
	}

	/**
	 * @dataProvider provideTableIdentifiers
	 */
	public function testGetDatabaseAndTableIdentifiers(
		$tableName,
		$domain,
		$expectedIdentifiers
	) {
		$platform = new PostgresPlatform( new AddQuoterMock(), null, $domain );

		$this->assertSame(
			$expectedIdentifiers,
			$platform->getDatabaseAndTableIdentifier( $tableName )
		);
	}
}
