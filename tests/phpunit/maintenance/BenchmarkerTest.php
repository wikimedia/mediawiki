<?php

namespace MediaWiki\Tests\Maintenance;

use Benchmarker;
use MediaWikiCoversValidator;
use Wikimedia\TestingAccessWrapper;

/**
 * @covers Benchmarker
 */
class BenchmarkerTest extends \PHPUnit\Framework\TestCase {

	use MediaWikiCoversValidator;

	public function testBenchSimple() {
		$bench = $this->getMockBuilder( Benchmarker::class )
			->setMethods( [ 'execute', 'output' ] )
			->getMock();
		$benchProxy = TestingAccessWrapper::newFromObject( $bench );
		$benchProxy->defaultCount = 3;

		$count = 0;
		$bench->bench( [
			'test' => function () use ( &$count ) {
					$count++;
			}
		] );

		$this->assertSame( 3, $count );
	}

	public function testBenchSetup() {
		$bench = $this->getMockBuilder( Benchmarker::class )
			->setMethods( [ 'execute', 'output' ] )
			->getMock();
		$benchProxy = TestingAccessWrapper::newFromObject( $bench );
		$benchProxy->defaultCount = 2;

		$buffer = [];
		$bench->bench( [
			'test' => [
				'setup' => function () use ( &$buffer ) {
						$buffer[] = 'setup';
				},
				'function' => function () use ( &$buffer ) {
						$buffer[] = 'run';
				}
			]
		] );

		$this->assertSame( [ 'setup', 'run', 'run' ], $buffer );
	}

	public function testBenchVerbose() {
		$bench = $this->getMockBuilder( Benchmarker::class )
			->setMethods( [ 'execute', 'output', 'hasOption', 'verboseRun' ] )
			->getMock();
		$benchProxy = TestingAccessWrapper::newFromObject( $bench );
		$benchProxy->defaultCount = 1;

		$bench->expects( $this->exactly( 2 ) )->method( 'hasOption' )
			->will( $this->returnValueMap( [
					[ 'verbose', true ],
					[ 'count', false ],
				] ) );

		$bench->expects( $this->once() )->method( 'verboseRun' )
			->with( 0 )
			->willReturn( null );

		$bench->bench( [
			'test' => function () {
			}
		] );
	}

	public function noop() {
	}

	public function testBenchName_method() {
		$bench = $this->getMockBuilder( Benchmarker::class )
			->setMethods( [ 'execute', 'output', 'addResult' ] )
			->getMock();
		$benchProxy = TestingAccessWrapper::newFromObject( $bench );
		$benchProxy->defaultCount = 1;

		$bench->expects( $this->once() )->method( 'addResult' )
			->with( $this->callback( function ( $res ) {
				return isset( $res['name'] ) && $res['name'] === ( __CLASS__ . '::noop()' );
			} ) );

		$bench->bench( [
			[ 'function' => [ $this, 'noop' ] ]
		] );
	}

	public function testBenchName_string() {
		$bench = $this->getMockBuilder( Benchmarker::class )
			->setMethods( [ 'execute', 'output', 'addResult' ] )
			->getMock();
		$benchProxy = TestingAccessWrapper::newFromObject( $bench );
		$benchProxy->defaultCount = 1;

		$bench->expects( $this->once() )->method( 'addResult' )
			->with( $this->callback( function ( $res ) {
				return $res['name'] === 'strtolower(A)';
			} ) );

		$bench->bench( [ [
			'function' => 'strtolower',
			'args' => [ 'A' ],
		] ] );
	}

	/**
	 * @covers Benchmarker::verboseRun
	 */
	public function testVerboseRun() {
		$bench = $this->getMockBuilder( Benchmarker::class )
			->setMethods( [ 'execute', 'output', 'hasOption', 'startBench', 'addResult' ] )
			->getMock();
		$benchProxy = TestingAccessWrapper::newFromObject( $bench );
		$benchProxy->defaultCount = 1;

		$bench->expects( $this->exactly( 2 ) )->method( 'hasOption' )
			->will( $this->returnValueMap( [
					[ 'verbose', true ],
					[ 'count', false ],
				] ) );

		$bench->expects( $this->once() )->method( 'output' )
			->with( $this->callback( function ( $out ) {
				return preg_match( '/memory.+ peak/', $out ) === 1;
			} ) );

		$bench->bench( [
			'test' => function () {
			}
		] );
	}
}
