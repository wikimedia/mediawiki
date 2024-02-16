<?php

use MediaWiki\Tests\MockDatabase;
use Wikimedia\Rdbms\IResultWrapper;

/**
 * Ensure that MockDatabase methods can be called without triggering unit test
 * constraints, such as service container access.
 *
 * @covers \MediaWiki\Tests\MockDatabase
 */
class MockDatabaseTest extends MediaWikiUnitTestCase {

	public function testConstruct() {
		new MockDatabase;
		$this->assertTrue( true );
	}

	/***************************************************************************/
	// region   ISQLPlatform methods

	public function testAddIdentifierQuotes() {
		$this->assertSame(
			'"foo"',
			( new MockDatabase )->addIdentifierQuotes( 'foo' )
		);
	}

	public function testAddQuotes() {
		$this->assertSame(
			"'shouldn\\'t use apostrophes'",
			( new MockDatabase )->addQuotes( "shouldn't use apostrophes" )
		);
	}

	// endregion -- end of ISQLPlatform methods

	/***************************************************************************/
	// region   IReadableDatabase methods

	public function testIsOpen() {
		$this->assertTrue( ( new MockDatabase )->isOpen() );
	}

	public function testGetDomainID() {
		$this->assertSame( 'test', ( new MockDatabase )->getDomainID() );
	}

	public function testNewSelectQueryBuilder() {
		$field = ( new MockDatabase )
			->newSelectQueryBuilder()
			->select( '1' )
			->from( 'table' )
			->fetchField();
		// Default result is empty
		$this->assertFalse( $field );
	}

	public function testSelect() {
		$res = ( new MockDatabase )->select( 'table', '1' );
		$this->assertInstanceOf( IResultWrapper::class, $res );
		$this->assertSame( 0, $res->numRows() );
	}

	public function testPing() {
		$this->assertTrue( ( new MockDatabase )->ping() );
	}

	public function testGetLag() {
		$this->assertSame( 0, ( new MockDatabase )->getLag() );
	}

	// endregion -- end of IReadableDatabase methods

	/***************************************************************************/
	// region   IDatabase methods

	public function testInsertId() {
		$this->assertIsInt( ( new MockDatabase )->insertId() );
	}

	public function testAffectedRows() {
		$this->assertSame( 0, ( new MockDatabase )->affectedRows() );
	}

	public function testQuery() {
		$this->assertInstanceOf(
			IResultWrapper::class,
			( new MockDatabase )->query( 'show something' )
		);
	}

	public function testNewUpdateQueryBuilder() {
		( new MockDatabase )
			->newUpdateQueryBuilder()
			->update( 'table' )
			->set( [ 'x' => 1 ] )
			->where( '1=1' )
			->execute();
		$this->assertTrue( true );
	}

	public function testNewDeleteQueryBuilder() {
		( new MockDatabase )
			->newDeleteQueryBuilder()
			->deleteFrom( 'table' )
			->where( [ 'x' => 1 ] )
			->execute();
		$this->assertTrue( true );
	}

	public function testNewInsertQueryBuilder() {
		( new MockDatabase )
			->newInsertQueryBuilder()
			->insertInto( 'table' )
			->row( [ 'x' => 1 ] )
			->execute();
		$this->assertTrue( true );
	}

	// endregion -- end of IDatabase methods
}
