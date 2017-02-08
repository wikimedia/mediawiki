<?php

use Wikimedia\Rdbms\DatabaseDomain;

/**
 * @covers Wikimedia\Rdbms\DatabaseDomain
 */
class DatabaseDomainTest extends PHPUnit_Framework_TestCase {
	public static function provideConstruct() {
		return [
			// All strings
			[ 'foo', 'bar', 'baz', 'foo-bar-baz' ],
			// Nothing
			[ null, null, '', '' ],
			// Invalid $database
			[ 0, 'bar', '', '', true ],
			// - in one of the fields
			[ 'foo-bar', 'baz', 'baa', 'foo?hbar-baz-baa' ],
			// ? in one of the fields
			[ 'foo?bar', 'baz', 'baa', 'foo??bar-baz-baa' ],
		];
	}

	/**
	 * @dataProvider provideConstruct
	 */
	public function testConstruct( $db, $schema, $prefix, $id, $exception = false ) {
		if ( $exception ) {
			$this->setExpectedException( InvalidArgumentException::class );
		}

		$domain = new DatabaseDomain( $db, $schema, $prefix );
		$this->assertInstanceOf( DatabaseDomain::class, $domain );
		$this->assertEquals( $db, $domain->getDatabase() );
		$this->assertEquals( $schema, $domain->getSchema() );
		$this->assertEquals( $prefix, $domain->getTablePrefix() );
		$this->assertEquals( $id, $domain->getId() );
	}

	public static function provideNewFromId() {
		return [
			// basic
			[ 'foo', 'foo', null, '' ],
			// <database>-<prefix>
			[ 'foo-bar', 'foo', null, 'bar' ],
			[ 'foo-bar-baz', 'foo', 'bar', 'baz' ],
			// ?h -> -
			[ 'foo?hbar-baz-baa', 'foo-bar', 'baz', 'baa' ],
			// ?? -> ?
			[ 'foo??bar-baz-baa', 'foo?bar', 'baz', 'baa' ],
			// ? is left alone
			[ 'foo?bar-baz-baa', 'foo?bar', 'baz', 'baa' ],
			// too many parts
			[ 'foo-bar-baz-baa', '', '', '', true ],
		];
	}

	/**
	 * @dataProvider provideNewFromId
	 */
	public function testNewFromId( $id, $db, $schema, $prefix, $exception = false ) {
		if ( $exception ) {
			$this->setExpectedException( InvalidArgumentException::class );
		}
		$domain = DatabaseDomain::newFromId( $id );
		$this->assertInstanceOf( DatabaseDomain::class, $domain );
		$this->assertEquals( $db, $domain->getDatabase() );
		$this->assertEquals( $schema, $domain->getSchema() );
		$this->assertEquals( $prefix, $domain->getTablePrefix() );
	}
}
