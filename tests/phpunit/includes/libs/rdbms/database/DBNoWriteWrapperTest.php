<?php

use Wikimedia\Rdbms\DBError;
use Wikimedia\Rdbms\DBNoWriteWrapper;
use Wikimedia\Rdbms\FakeResultWrapper;
use Wikimedia\Rdbms\IDatabase;
use Wikimedia\Rdbms\ResultWrapper;

/**
 * @covers Wikimedia\Rdbms\DBNoWriteWrapper
 */
class DBNoWriteWrapperTest extends PHPUnit_Framework_TestCase {

	/**
	 * @return IDatabase
	 */
	private function getDatabaseMock() {
		$db = $this->getMock( IDatabase::class );

		$db->method( 'select' )->willReturn( new FakeResultWrapper( [] ) );
		$db->method( 'query' )->willReturn( new FakeResultWrapper( [] ) );

		$db->method( 'getLBInfo' )->willReturnCallback(
			function( $name = null ) {
				if ( $name === 'test' ) {
					return true;
				} elseif ( $name === null ) {
					return [ 'test' => true ];
				} else {
					return null;
				}
			}
		);

		return $db;
	}

	/**
	 * @return IDatabase
	 */
	private function getDBNoWriteWrapper() {
		return new DBNoWriteWrapper( $this->getDatabaseMock() );
	}

	public function testConstruct() {
		$wrapper = new DBNoWriteWrapper( $this->getDatabaseMock() );

		$this->assertInstanceOf( ResultWrapper::class, $wrapper->select( 'whatever', '*' ) );
	}

	public function testConstruct_callback() {
		$wrapper = new DBNoWriteWrapper(
			function () {
				return $this->getDatabaseMock();
			}
		);

		$this->assertInstanceOf( ResultWrapper::class, $wrapper->select( 'whatever', '*' ) );
	}

	public function testConstruct_failure() {
		$this->setExpectedException( InvalidArgumentException::class, '' );
		new DBNoWriteWrapper( 17 ); // bad constructor argument
	}

	public function provideQuery_read() {
		yield [ 'SELECT * FROM whatever' ];
	}

	/**
	 * @dataProvider provideQuery_read()
	 * @param string $sql
	 */
	public function testQuery_read( $sql ) {
		$wrapper = $this->getDBNoWriteWrapper();
		$this->assertInstanceOf( ResultWrapper::class, $wrapper->query( $sql, 'TEST' ) );
	}

	public function provideQuery_write() {
		yield [
			'DELETE FROM whatever',
			'Write operation (DELETE) is not allowed on this database connection! Caused by TEST.',
		];
		yield [
			'UPDATE whatever SET foo = 0 WHERE bar = 1',
			'Write operation (UPDATE) is not allowed on this database connection! Caused by TEST.',
		];
		yield [
			'INSERT INTO whatever ( foo, bar ) VALUES ( foo = 0, bar = 1 )',
			'Write operation (INSERT) is not allowed on this database connection! Caused by TEST.',
		];
	}

	/**
	 * @dataProvider provideQuery_write()
	 * @param string $sql
	 * @param string $message
	 */
	public function testQuery_write( $sql, $message ) {
		$this->setExpectedException( DBError::class, $message );

		$wrapper = $this->getDBNoWriteWrapper();
		$wrapper->query( $sql, 'TEST' );
	}

	public function testDelete() {
		$this->setExpectedException(
			DBError::class,
			'Write operation (delete) is not allowed on this database connection! Caused by TEST.'
		);

		$wrapper = $this->getDBNoWriteWrapper();
		$wrapper->delete( 'whatever', '*', 'TEST' );
	}

	public function testGetLBInfo() {
		$wrapper = $this->getDBNoWriteWrapper();

		$this->assertTrue( $wrapper->getLBInfo( 'test' ) );
		$this->assertTrue( $wrapper->getLBInfo( 'noWrite' ) );
		$this->assertNull( $wrapper->getLBInfo( 'whatever' ) );
		$this->assertEquals( [ 'test' => true, 'noWrite' => true ], $wrapper->getLBInfo() );
	}

	public function testInsert() {
		$this->setExpectedException(
			DBError::class,
			'Write operation (insert) is not allowed on this database connection! Caused by TEST.'
		);

		$wrapper = $this->getDBNoWriteWrapper();
		$wrapper->insert( 'whatever', [], 'TEST' );
	}

	public function testUpdate() {
		$this->setExpectedException(
			DBError::class,
			'Write operation (update) is not allowed on this database connection! Caused by TEST.'
		);

		$wrapper = $this->getDBNoWriteWrapper();
		$wrapper->update( 'whatever', [], [], 'TEST' );
	}

	public function testReplace() {
		$this->setExpectedException(
			DBError::class,
			'Write operation (replace) is not allowed on this database connection! Caused by TEST.'
		);

		$wrapper = $this->getDBNoWriteWrapper();
		$wrapper->replace( 'whatever', [], [], 'TEST' );
	}

	public function testUpsert() {
		$this->setExpectedException(
			DBError::class,
			'Write operation (upsert) is not allowed on this database connection! Caused by TEST.'
		);

		$wrapper = $this->getDBNoWriteWrapper();
		$wrapper->upsert( 'whatever', [], [], [], 'TEST' );
	}

	public function testDeleteJoin() {
		$this->setExpectedException(
			DBError::class,
			'Write operation (deleteJoin) is not allowed on this database connection! Caused by TEST.'
		);

		$wrapper = $this->getDBNoWriteWrapper();
		$wrapper->deleteJoin( 'whatever', 'something', [], [], [], 'TEST' );
	}

	public function testInsertSelect() {
		$this->setExpectedException(
			DBError::class,
			'Write operation (insertSelect) is not allowed on this database connection! Caused by TEST.'
		);

		$wrapper = $this->getDBNoWriteWrapper();
		$wrapper->insertSelect( 'whatever', 'something', [], [], 'TEST' );
	}

	public function testSelect() {
		$wrapper = $this->getDBNoWriteWrapper();
		$this->assertInstanceOf( ResultWrapper::class, $wrapper->select( 'whatever', '*' ) );
	}

	public function testIsOpen() {
		$wrapper = $this->getDBNoWriteWrapper();
		$this->assertNull( $wrapper->isOpen() );
	}

	public function testGetDomainID() {
		$wrapper = $this->getDBNoWriteWrapper();
		$this->assertNull( $wrapper->getDomainID() );
	}

	public function testGetReplicaPos() {
		$wrapper = $this->getDBNoWriteWrapper();
		$this->assertNull( $wrapper->getReplicaPos() );
	}

	public function testTableExists() {
		$wrapper = $this->getDBNoWriteWrapper();
		$this->assertNull( $wrapper->tableExists( 'whatever', 'TEST' ) );
	}

}
