<?php

use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;
use Wikimedia\Rdbms\TransactionProfiler;

/**
 * @covers \Wikimedia\Rdbms\TransactionProfiler
 */
class TransactionProfilerTest extends TestCase {

	use MediaWikiCoversValidator;

	public function testAffected() {
		$logger = $this->createMock( LoggerInterface::class );
		$logger->expects( $this->exactly( 3 ) )->method( 'warning' );

		$now = 1668108368.0;
		$tp = new TransactionProfiler();
		$tp->setMockTime( $now );
		$tp->setLogger( $logger );
		$tp->setExpectation( 'maxAffected', 100, __METHOD__ );

		$tp->transactionWritingIn( 'srv1', 'db1', '123', $now );
		$tp->recordQueryCompletion( "SQL 1", $now - 3, true, 200, '1' );
		$tp->recordQueryCompletion( "SQL 2", $now - 3, true, 200, '1' );
		$tp->transactionWritingOut( 'srv1', 'db1', '123', 1, 400 );
	}

	public function testReadTime() {
		$logger = $this->createMock( LoggerInterface::class );
		// 1 per query
		$logger->expects( $this->exactly( 2 ) )->method( 'warning' );

		$now = 1668108368.0;
		$tp = new TransactionProfiler();
		$tp->setMockTime( $now );
		$tp->setLogger( $logger );
		$tp->setExpectation( 'readQueryTime', 5, __METHOD__ );

		$tp->transactionWritingIn( 'srv1', 'db1', '123', $now );
		$tp->recordQueryCompletion( "SQL 1", $now - 10, false, 1, '1' );
		$tp->recordQueryCompletion( "SQL 2", $now - 10, false, 1, '1' );
		$tp->transactionWritingOut( 'srv1', 'db1', '123', 0, 0 );
	}

	public function testWriteTime() {
		$logger = $this->createMock( LoggerInterface::class );
		// 1 per query, 1 per trx, and one "sub-optimal trx" entry
		$logger->expects( $this->exactly( 4 ) )->method( 'warning' );

		$now = 1668108368.0;
		$tp = new TransactionProfiler();
		$tp->setMockTime( $now );
		$tp->setLogger( $logger );
		$tp->setExpectation( 'writeQueryTime', 5, __METHOD__ );

		$tp->transactionWritingIn( 'srv1', 'db1', '123', $now );
		$tp->recordQueryCompletion( "SQL 1", $now - 10, true, 1, '1' );
		$tp->recordQueryCompletion( "SQL 2", $now - 10, true, 1, '1' );
		$tp->transactionWritingOut( 'srv1', 'db1', '123', 20, 1 );
	}

	public function testAffectedTrx() {
		$logger = $this->createMock( LoggerInterface::class );
		$logger->expects( $this->once() )->method( 'warning' );

		$now = 1668108368.0;
		$tp = new TransactionProfiler();
		$tp->setMockTime( $now );
		$tp->setLogger( $logger );
		$tp->setExpectation( 'maxAffected', 100, __METHOD__ );

		$tp->transactionWritingIn( 'srv1', 'db1', '123', $now );
		$tp->transactionWritingOut( 'srv1', 'db1', '123', 1, 200 );
	}

	public function testWriteTimeTrx() {
		$logger = $this->createMock( LoggerInterface::class );
		// 1 per trx, and one "sub-optimal trx" entry
		$logger->expects( $this->exactly( 2 ) )->method( 'warning' );

		$now = 1668108368.0;
		$tp = new TransactionProfiler();
		$tp->setMockTime( $now );
		$tp->setLogger( $logger );
		$tp->setExpectation( 'writeQueryTime', 5, __METHOD__ );

		$tp->transactionWritingIn( 'srv1', 'db1', '123', $now );
		$tp->transactionWritingOut( 'srv1', 'db1', '123', 10, 1 );
	}

	public function testConns() {
		$logger = $this->createMock( LoggerInterface::class );
		$logger->expects( $this->exactly( 2 ) )->method( 'warning' );

		$now = 1668108368.0;
		$tp = new TransactionProfiler();
		$tp->setMockTime( $now );
		$tp->setLogger( $logger );
		$tp->setExpectation( 'conns', 2, __METHOD__ );

		$tp->recordConnection( 'srv1', 'db1', false );
		$tp->recordConnection( 'srv1', 'db2', false );
		$tp->recordConnection( 'srv1', 'db3', false ); // warn
		$tp->recordConnection( 'srv1', 'db4', false ); // warn
	}

	public function testMasterConns() {
		$logger = $this->createMock( LoggerInterface::class );
		$logger->expects( $this->exactly( 2 ) )->method( 'warning' );

		$now = 1668108368.0;
		$tp = new TransactionProfiler();
		$tp->setMockTime( $now );
		$tp->setLogger( $logger );
		$tp->setExpectation( 'masterConns', 2, __METHOD__ );

		$tp->recordConnection( 'srv1', 'db1', false );
		$tp->recordConnection( 'srv1', 'db2', false );

		$tp->recordConnection( 'srv1', 'db1', true );
		$tp->recordConnection( 'srv1', 'db2', true );
		$tp->recordConnection( 'srv1', 'db3', true ); // warn
		$tp->recordConnection( 'srv1', 'db4', true ); // warn
	}

	public function testReadQueryCount() {
		$logger = $this->createMock( LoggerInterface::class );
		$logger->expects( $this->exactly( 2 ) )->method( 'warning' );

		$now = 1668108368.0;
		$tp = new TransactionProfiler();
		$tp->setMockTime( $now );
		$tp->setLogger( $logger );
		$tp->setExpectation( 'queries', 2, __METHOD__ );

		$tp->recordQueryCompletion( "SQL 1", $now - 0.01, false, 0, '1' );
		$tp->recordQueryCompletion( "SQL 2", $now - 0.01, false, 0, '1' );
		$tp->recordQueryCompletion( "SQL 3", $now - 0.01, false, 0, '1' ); // warn
		$tp->recordQueryCompletion( "SQL 4", $now - 0.01, false, 0, '1' ); // warn
	}

	public function testWriteQueryCount() {
		$logger = $this->createMock( LoggerInterface::class );
		$logger->expects( $this->exactly( 2 ) )->method( 'warning' );

		$now = 1668108368.0;
		$tp = new TransactionProfiler();
		$tp->setMockTime( $now );
		$tp->setLogger( $logger );
		$tp->setExpectation( 'writes', 2, __METHOD__ );

		$tp->recordQueryCompletion( "SQL 1", $now - 0.01, false, 0, '1' );
		$tp->recordQueryCompletion( "SQL 2", $now - 0.01, false, 0, '1' );
		$tp->recordQueryCompletion( "SQL 3", $now - 0.01, false, 0, '1' );
		$tp->recordQueryCompletion( "SQL 4", $now - 0.01, false, 0, '1' );

		$tp->transactionWritingIn( 'srv1', 'db1', '123', $now );
		$tp->recordQueryCompletion( "SQL 1w", $now - 0.01, true, 2, '1' );
		$tp->recordQueryCompletion( "SQL 2w", $now - 0.01, true, 5, '1' );
		$tp->recordQueryCompletion( "SQL 3w", $now - 0.01, true, 3, '1' );
		$tp->recordQueryCompletion( "SQL 4w", $now - 0.01, true, 1, '1' );
		$tp->transactionWritingOut( 'srv1', 'db1', '123', 1, 1 );
	}

	public function testSilence() {
		$logger = $this->createMock( LoggerInterface::class );
		$logger->expects( $this->never() )->method( 'warning' );

		$now = 1668108368.0;
		$tp = new TransactionProfiler();
		$tp->setMockTime( $now );
		$tp->setLogger( $logger );
		$tp->setExpectation( 'conns', 2, __METHOD__ );
		$tp->setExpectation( 'masterConns', 0, __METHOD__ );
		$tp->setExpectation( 'writes', 0, __METHOD__ );
		$tp->setExpectation( 'writeQueryTime', 5, __METHOD__ );

		$scope = $tp->silenceForScope();

		$tp->recordConnection( 'srv1', 'enwiki', true );
		$tp->transactionWritingIn( 'srv1', 'db1', '123', $now );
		$tp->recordConnection( 'srv2', 'enwiki', false );
		$tp->recordConnection( 'srv3', 'enwiki', false );
		$tp->recordQueryCompletion( "SQL 1", $now - 10, true, 1, '1' );
		$tp->transactionWritingOut( 'srv1', 'db1', '123', 10, 1 );

		unset( $scope );
	}

	public function testUnsilence() {
		$logger = $this->createMock( LoggerInterface::class );
		// 1 "masterConns" entry, 1 "conns" entry, 1 "writes" entry, 1 "writeQueryTime" entry
		$logger->expects( $this->exactly( 4 ) )->method( 'warning' );

		$now = 1668108368.0;
		$tp = new TransactionProfiler();
		$tp->setMockTime( $now );
		$tp->setLogger( $logger );

		$tp->setExpectation( 'conns', 2, __METHOD__ );
		$tp->setExpectation( 'masterConns', 0, __METHOD__ );
		$tp->setExpectation( 'writes', 0, __METHOD__ );
		$tp->setExpectation( 'writeQueryTime', 5, __METHOD__ );

		$scope = $tp->silenceForScope();
		$tp->recordConnection( 'srv1', 'enwiki', true );
		$tp->transactionWritingIn( 'srv1', 'db1', '123', $now );
		$tp->recordConnection( 'srv2', 'enwiki', false );
		$tp->recordConnection( 'srv3', 'enwiki', false );
		$tp->recordQueryCompletion( "SQL 1", $now - 10, true, 1, '1' );
		$tp->transactionWritingOut( 'srv1', 'db1', '123', 10, 1 );
		unset( $scope );

		$tp->recordConnection( 'srv1', 'enwiki', true );
		$tp->recordConnection( 'srv2', 'enwiki', false );
		$tp->recordConnection( 'srv3', 'enwiki', false );
		$tp->recordQueryCompletion( "SQL 2", $now - 10, true, 1, '1' );
	}

	public function testPartialSilence() {
		$logger = $this->createMock( LoggerInterface::class );
		// 1 entry for slow write
		$logger->expects( $this->once() )->method( 'warning' );

		$now = 1668108368.0;
		$tp = new TransactionProfiler();
		$tp->setMockTime( $now );
		$tp->setLogger( $logger );
		$tp->setExpectation( 'conns', 2, __METHOD__ );
		$tp->setExpectation( 'masterConns', 0, __METHOD__ );
		$tp->setExpectation( 'writes', 0, __METHOD__ );
		$tp->setExpectation( 'writeQueryTime', 5, __METHOD__ );

		$scope = $tp->silenceForScope( $tp::EXPECTATION_REPLICAS_ONLY );

		$tp->recordConnection( 'srv1', 'enwiki', true );
		$tp->recordConnection( 'srv2', 'enwiki', false );
		$tp->recordQueryCompletion( "SQL 1", $now - 10, true, 1, '1' );

		unset( $scope );
	}

	/** @dataProvider provideGetExpectation */
	public function testGetExpectation( $expectations, $event, $expectedReturnValue ) {
		$tp = new TransactionProfiler();
		$tp->setExpectations( $expectations, __METHOD__ );
		$this->assertSame( $expectedReturnValue, $tp->getExpectation( $event ) );
	}

	public static function provideGetExpectation() {
		return [
			'Provided event name is unset' => [ [], 'writes', INF ],
			'Provided event name is set' => [ [ 'writes' => 3, 'conns' => 1 ], 'writes', 3 ],
		];
	}

	public function testGetExpectationOnInvalidEventName() {
		$this->expectException( InvalidArgumentException::class );
		$this->testGetExpectation( [], 'abc', 1234 );
	}
}
