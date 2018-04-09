<?php

use Wikimedia\Rdbms\DatabaseDomain;

/**
 * @covers Wikimedia\Rdbms\DatabaseDomain
 */
class DatabaseDomainTest extends PHPUnit\Framework\TestCase {

	use MediaWikiCoversValidator;
	use PHPUnit4And6Compat;

	public static function provideConstruct() {
		return [
			'All strings' =>
				[ 'foo', 'bar', 'baz', 'foo-bar-baz' ],
			'Nothing' =>
				[ null, null, '', '' ],
			'Invalid $database' =>
				[ 0, 'bar', '', '', true ],
			'Invalid $schema' =>
				[ 'foo', 0, '', '', true ],
			'Invalid $prefix' =>
				[ 'foo', 'bar', 0, '', true ],
			'Dash' =>
				[ 'foo-bar', 'baz', 'baa', 'foo?hbar-baz-baa' ],
			'Question mark' =>
				[ 'foo?bar', 'baz', 'baa', 'foo??bar-baz-baa' ],
		];
	}

	/**
	 * @dataProvider provideConstruct
	 */
	public function testConstruct( $db, $schema, $prefix, $id, $exception = false ) {
		if ( $exception ) {
			$this->setExpectedException( InvalidArgumentException::class );
			new DatabaseDomain( $db, $schema, $prefix );
			return;
		}

		$domain = new DatabaseDomain( $db, $schema, $prefix );
		$this->assertInstanceOf( DatabaseDomain::class, $domain );
		$this->assertEquals( $db, $domain->getDatabase() );
		$this->assertEquals( $schema, $domain->getSchema() );
		$this->assertEquals( $prefix, $domain->getTablePrefix() );
		$this->assertEquals( $id, $domain->getId() );
		$this->assertEquals( $id, strval( $domain ), 'toString' );
	}

	public static function provideNewFromId() {
		return [
			'Basic' =>
				[ 'foo', 'foo', null, '' ],
			'db+prefix' =>
				[ 'foo-bar', 'foo', null, 'bar' ],
			'db+schema+prefix' =>
				[ 'foo-bar-baz', 'foo', 'bar', 'baz' ],
			'?h -> -' =>
				[ 'foo?hbar-baz-baa', 'foo-bar', 'baz', 'baa' ],
			'?? -> ?' =>
				[ 'foo??bar-baz-baa', 'foo?bar', 'baz', 'baa' ],
			'? is left alone' =>
				[ 'foo?bar-baz-baa', 'foo?bar', 'baz', 'baa' ],
			'too many parts' =>
				[ 'foo-bar-baz-baa', '', '', '', true ],
			'from instance' =>
				[ DatabaseDomain::newUnspecified(), null, null, '' ],
		];
	}

	/**
	 * @dataProvider provideNewFromId
	 */
	public function testNewFromId( $id, $db, $schema, $prefix, $exception = false ) {
		if ( $exception ) {
			$this->setExpectedException( InvalidArgumentException::class );
			DatabaseDomain::newFromId( $id );
			return;
		}
		$domain = DatabaseDomain::newFromId( $id );
		$this->assertInstanceOf( DatabaseDomain::class, $domain );
		$this->assertEquals( $db, $domain->getDatabase() );
		$this->assertEquals( $schema, $domain->getSchema() );
		$this->assertEquals( $prefix, $domain->getTablePrefix() );
	}

	public static function provideEquals() {
		return [
			'Basic' =>
				[ 'foo', 'foo', null, '' ],
			'db+prefix' =>
				[ 'foo-bar', 'foo', null, 'bar' ],
			'db+schema+prefix' =>
				[ 'foo-bar-baz', 'foo', 'bar', 'baz' ],
			'?h -> -' =>
				[ 'foo?hbar-baz-baa', 'foo-bar', 'baz', 'baa' ],
			'?? -> ?' =>
				[ 'foo??bar-baz-baa', 'foo?bar', 'baz', 'baa' ],
			'Nothing' =>
				[ '', null, null, '' ],
		];
	}

	/**
	 * @dataProvider provideEquals
	 * @covers Wikimedia\Rdbms\DatabaseDomain::equals
	 */
	public function testEquals( $id, $db, $schema, $prefix ) {
		$fromId = DatabaseDomain::newFromId( $id );
		$this->assertInstanceOf( DatabaseDomain::class, $fromId );

		$constructed = new DatabaseDomain( $db, $schema, $prefix );

		$this->assertTrue( $constructed->equals( $id ), 'constructed equals string' );
		$this->assertTrue( $fromId->equals( $id ), 'fromId equals string' );

		$this->assertTrue( $constructed->equals( $fromId ), 'compare constructed to newId' );
		$this->assertTrue( $fromId->equals( $constructed ), 'compare newId to constructed' );
	}

	/**
	 * @covers Wikimedia\Rdbms\DatabaseDomain::newUnspecified
	 */
	public function testNewUnspecified() {
		$domain = DatabaseDomain::newUnspecified();
		$this->assertInstanceOf( DatabaseDomain::class, $domain );
		$this->assertTrue( $domain->equals( '' ) );
		$this->assertSame( null, $domain->getDatabase() );
		$this->assertSame( null, $domain->getSchema() );
		$this->assertSame( '', $domain->getTablePrefix() );
	}
}
