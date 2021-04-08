<?php

use Wikimedia\Rdbms\DatabaseDomain;

/**
 * @covers Wikimedia\Rdbms\DatabaseDomain
 */
class DatabaseDomainTest extends PHPUnit\Framework\TestCase {

	use MediaWikiCoversValidator;

	public static function provideConstruct() {
		return [
			'All strings' =>
				[ 'foo', 'bar', 'baz_', 'foo-bar-baz_' ],
			'Nothing' =>
				[ null, null, '', '' ],
			'Invalid $database' =>
				[ 0, 'bar', '', '', true ],
			'Invalid $schema' =>
				[ 'foo', 0, '', '', true ],
			'Invalid $prefix' =>
				[ 'foo', 'bar', 0, '', true ],
			'Dash' =>
				[ 'foo-bar', 'baz', 'baa_', 'foo?hbar-baz-baa_' ],
			'Question mark' =>
				[ 'foo?bar', 'baz', 'baa_', 'foo??bar-baz-baa_' ],
		];
	}

	/**
	 * @dataProvider provideConstruct
	 */
	public function testConstruct( $db, $schema, $prefix, $id, $exception = false ) {
		if ( $exception ) {
			$this->expectException( InvalidArgumentException::class );
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
				[ 'foo-bar_', 'foo', null, 'bar_' ],
			'db+schema+prefix' =>
				[ 'foo-bar-baz_', 'foo', 'bar', 'baz_' ],
			'?h -> -' =>
				[ 'foo?hbar-baz-baa_', 'foo-bar', 'baz', 'baa_' ],
			'?? -> ?' =>
				[ 'foo??bar-baz-baa_', 'foo?bar', 'baz', 'baa_' ],
			'? is left alone' =>
				[ 'foo?bar-baz-baa_', 'foo?bar', 'baz', 'baa_' ],
			'too many parts' =>
				[ 'foo-bar-baz-baa_', '', '', '', true ],
			'from instance' =>
				[ DatabaseDomain::newUnspecified(), null, null, '' ],
		];
	}

	/**
	 * @dataProvider provideNewFromId
	 */
	public function testNewFromId( $id, $db, $schema, $prefix, $exception = false ) {
		if ( $exception ) {
			$this->expectException( InvalidArgumentException::class );
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
				[ 'foo-bar_', 'foo', null, 'bar_' ],
			'db+schema+prefix' =>
				[ 'foo-bar-baz_', 'foo', 'bar', 'baz_' ],
			'?h -> -' =>
				[ 'foo?hbar-baz-baa_', 'foo-bar', 'baz', 'baa_' ],
			'?? -> ?' =>
				[ 'foo??bar-baz-baa_', 'foo?bar', 'baz', 'baa_' ],
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

	public static function provideIsCompatible() {
		return [
			'Basic' =>
				[ 'foo', 'foo', null, '', true ],
			'db+prefix' =>
				[ 'foo-bar_', 'foo', null, 'bar_', true ],
			'db+schema+prefix' =>
				[ 'foo-bar-baz_', 'foo', 'bar', 'baz_', true ],
			'db+dontcare_schema+prefix' =>
				[ 'foo-bar-baz_', 'foo', null, 'baz_', false ],
			'?h -> -' =>
				[ 'foo?hbar-baz-baa_', 'foo-bar', 'baz', 'baa_', true ],
			'?? -> ?' =>
				[ 'foo??bar-baz-baa_', 'foo?bar', 'baz', 'baa_', true ],
			'Nothing' =>
				[ '', null, null, '', true ],
			'dontcaredb+dontcaredbschema+prefix' =>
				[ 'mywiki-mediawiki-prefix_', null, null, 'prefix_', false ],
			'db+dontcareschema+prefix' =>
				[ 'mywiki-schema-prefix_', 'mywiki', null, 'prefix_', false ],
			'postgres-db-jobqueue' =>
				[ 'postgres-mediawiki-', 'postgres', null, '', false ]
		];
	}

	/**
	 * @dataProvider provideIsCompatible
	 * @covers Wikimedia\Rdbms\DatabaseDomain::isCompatible
	 */
	public function testIsCompatible( $id, $db, $schema, $prefix, $transitive ) {
		$compareIdObj = DatabaseDomain::newFromId( $id );
		$this->assertInstanceOf( DatabaseDomain::class, $compareIdObj );

		$fromId = new DatabaseDomain( $db, $schema, $prefix );

		$this->assertTrue( $fromId->isCompatible( $id ), 'constructed equals string' );
		$this->assertTrue( $fromId->isCompatible( $compareIdObj ), 'fromId equals string' );

		$this->assertEquals( $transitive, $compareIdObj->isCompatible( $fromId ),
			'test transitivity of nulls components' );
	}

	public static function provideIsCompatible2() {
		return [
			'db+schema+prefix' =>
				[ 'mywiki-schema-prefix_', 'thatwiki', 'schema', 'prefix_' ],
			'dontcaredb+dontcaredbschema+prefix' =>
				[ 'thatwiki-mediawiki-otherprefix_', null, null, 'prefix_' ],
			'db+dontcareschema+prefix' =>
				[ 'notmywiki-schema-prefix_', 'mywiki', null, 'prefix_' ],
		];
	}

	/**
	 * @dataProvider provideIsCompatible2
	 * @covers Wikimedia\Rdbms\DatabaseDomain::isCompatible
	 */
	public function testIsCompatible2( $id, $db, $schema, $prefix ) {
		$compareIdObj = DatabaseDomain::newFromId( $id );
		$this->assertInstanceOf( DatabaseDomain::class, $compareIdObj );

		$fromId = new DatabaseDomain( $db, $schema, $prefix );

		$this->assertFalse( $fromId->isCompatible( $id ), 'constructed equals string' );
		$this->assertFalse( $fromId->isCompatible( $compareIdObj ), 'fromId equals string' );
	}

	public function testSchemaWithNoDB1() {
		$this->expectException( InvalidArgumentException::class );
		new DatabaseDomain( null, 'schema', '' );
	}

	public function testSchemaWithNoDB2() {
		$this->expectException( InvalidArgumentException::class );
		DatabaseDomain::newFromId( '-schema-prefix' );
	}

	/**
	 * @covers Wikimedia\Rdbms\DatabaseDomain::isUnspecified
	 */
	public function testIsUnspecified() {
		$domain = new DatabaseDomain( null, null, '' );
		$this->assertTrue( $domain->isUnspecified() );
		$domain = new DatabaseDomain( 'mywiki', null, '' );
		$this->assertFalse( $domain->isUnspecified() );
		$domain = new DatabaseDomain( 'mywiki', null, '' );
		$this->assertFalse( $domain->isUnspecified() );
	}
}
