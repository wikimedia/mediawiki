<?php

use Wikimedia\Rdbms\TransactionProfiler;
use Psr\Log\LoggerInterface;

/**
 * @covers \Wikimedia\Rdbms\TransactionProfiler
 */
class TransactionProfilerTest extends PHPUnit\Framework\TestCase {

	use MediaWikiCoversValidator;

	public function testAffected() {
		$logger = $this->getMockBuilder( LoggerInterface::class )->getMock();
		$logger->expects( $this->exactly( 3 ) )->method( 'warning' );

		$tp = new TransactionProfiler();
		$tp->setLogger( $logger );
		$tp->setExpectation( 'maxAffected', 100, __METHOD__ );

		$tp->transactionWritingIn( 'srv1', 'db1', '123' );
		$tp->recordQueryCompletion( "SQL 1", microtime( true ) - 3, true, 200 );
		$tp->recordQueryCompletion( "SQL 2", microtime( true ) - 3, true, 200 );
		$tp->transactionWritingOut( 'srv1', 'db1', '123', 1, 400 );
	}

	public function testReadTime() {
		$logger = $this->getMockBuilder( LoggerInterface::class )->getMock();
		// 1 per query
		$logger->expects( $this->exactly( 2 ) )->method( 'warning' );

		$tp = new TransactionProfiler();
		$tp->setLogger( $logger );
		$tp->setExpectation( 'readQueryTime', 5, __METHOD__ );

		$tp->transactionWritingIn( 'srv1', 'db1', '123' );
		$tp->recordQueryCompletion( "SQL 1", microtime( true ) - 10, false, 1 );
		$tp->recordQueryCompletion( "SQL 2", microtime( true ) - 10, false, 1 );
		$tp->transactionWritingOut( 'srv1', 'db1', '123', 0, 0 );
	}

	public function testWriteTime() {
		$logger = $this->getMockBuilder( LoggerInterface::class )->getMock();
		// 1 per query, 1 per trx, and one "sub-optimal trx" entry
		$logger->expects( $this->exactly( 4 ) )->method( 'warning' );

		$tp = new TransactionProfiler();
		$tp->setLogger( $logger );
		$tp->setExpectation( 'writeQueryTime', 5, __METHOD__ );

		$tp->transactionWritingIn( 'srv1', 'db1', '123' );
		$tp->recordQueryCompletion( "SQL 1", microtime( true ) - 10, true, 1 );
		$tp->recordQueryCompletion( "SQL 2", microtime( true ) - 10, true, 1 );
		$tp->transactionWritingOut( 'srv1', 'db1', '123', 20, 1 );
	}

	public function testAffectedTrx() {
		$logger = $this->getMockBuilder( LoggerInterface::class )->getMock();
		$logger->expects( $this->exactly( 1 ) )->method( 'warning' );

		$tp = new TransactionProfiler();
		$tp->setLogger( $logger );
		$tp->setExpectation( 'maxAffected', 100, __METHOD__ );

		$tp->transactionWritingIn( 'srv1', 'db1', '123' );
		$tp->transactionWritingOut( 'srv1', 'db1', '123', 1, 200 );
	}

	public function testWriteTimeTrx() {
		$logger = $this->getMockBuilder( LoggerInterface::class )->getMock();
		// 1 per trx, and one "sub-optimal trx" entry
		$logger->expects( $this->exactly( 2 ) )->method( 'warning' );

		$tp = new TransactionProfiler();
		$tp->setLogger( $logger );
		$tp->setExpectation( 'writeQueryTime', 5, __METHOD__ );

		$tp->transactionWritingIn( 'srv1', 'db1', '123' );
		$tp->transactionWritingOut( 'srv1', 'db1', '123', 10, 1 );
	}

	public function testConns() {
		$logger = $this->getMockBuilder( LoggerInterface::class )->getMock();
		$logger->expects( $this->exactly( 2 ) )->method( 'warning' );

		$tp = new TransactionProfiler();
		$tp->setLogger( $logger );
		$tp->setExpectation( 'conns', 2, __METHOD__ );

		$tp->recordConnection( 'srv1', 'db1', false );
		$tp->recordConnection( 'srv1', 'db2', false );
		$tp->recordConnection( 'srv1', 'db3', false ); // warn
		$tp->recordConnection( 'srv1', 'db4', false ); // warn
	}

	public function testMasterConns() {
		$logger = $this->getMockBuilder( LoggerInterface::class )->getMock();
		$logger->expects( $this->exactly( 2 ) )->method( 'warning' );

		$tp = new TransactionProfiler();
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
		$logger = $this->getMockBuilder( LoggerInterface::class )->getMock();
		$logger->expects( $this->exactly( 2 ) )->method( 'warning' );

		$tp = new TransactionProfiler();
		$tp->setLogger( $logger );
		$tp->setExpectation( 'queries', 2, __METHOD__ );

		$tp->recordQueryCompletion( "SQL 1", microtime( true ) - 0.01, false, 0 );
		$tp->recordQueryCompletion( "SQL 2", microtime( true ) - 0.01, false, 0 );
		$tp->recordQueryCompletion( "SQL 3", microtime( true ) - 0.01, false, 0 ); // warn
		$tp->recordQueryCompletion( "SQL 4", microtime( true ) - 0.01, false, 0 ); // warn
	}

	public function testWriteQueryCount() {
		$logger = $this->getMockBuilder( LoggerInterface::class )->getMock();
		$logger->expects( $this->exactly( 2 ) )->method( 'warning' );

		$tp = new TransactionProfiler();
		$tp->setLogger( $logger );
		$tp->setExpectation( 'writes', 2, __METHOD__ );

		$tp->recordQueryCompletion( "SQL 1", microtime( true ) - 0.01, false, 0 );
		$tp->recordQueryCompletion( "SQL 2", microtime( true ) - 0.01, false, 0 );
		$tp->recordQueryCompletion( "SQL 3", microtime( true ) - 0.01, false, 0 );
		$tp->recordQueryCompletion( "SQL 4", microtime( true ) - 0.01, false, 0 );

		$tp->transactionWritingIn( 'srv1', 'db1', '123' );
		$tp->recordQueryCompletion( "SQL 1w", microtime( true ) - 0.01, true, 2 );
		$tp->recordQueryCompletion( "SQL 2w", microtime( true ) - 0.01, true, 5 );
		$tp->recordQueryCompletion( "SQL 3w", microtime( true ) - 0.01, true, 3 );
		$tp->recordQueryCompletion( "SQL 4w", microtime( true ) - 0.01, true, 1 );
		$tp->transactionWritingOut( 'srv1', 'db1', '123', 1, 1 );
	}
}
