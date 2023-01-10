<?php

namespace MediaWiki\Tests\Maintenance;

use Benchmarker;
use MediaWikiCoversValidator;
use PHPUnit\Framework\TestCase;
use Wikimedia\TestingAccessWrapper;

/**
 * @covers \Benchmarker
 */
class BenchmarkerTest extends TestCase {

	use MediaWikiCoversValidator;

	public function testBenchSimple() {
		$bench = $this->getMockBuilder( Benchmarker::class )
			->onlyMethods( [ 'execute', 'output' ] )
			->getMock();
		$benchProxy = TestingAccessWrapper::newFromObject( $bench );
		$benchProxy->defaultCount = 3;

		$count = 0;
		$bench->bench( [
			'test' => static function () use ( &$count ) {
				$count++;
			}
		] );

		$this->assertSame( 3, $count );
	}

	public function testBenchSetup() {
		$bench = $this->getMockBuilder( Benchmarker::class )
			->onlyMethods( [ 'execute', 'output' ] )
			->getMock();
		$benchProxy = TestingAccessWrapper::newFromObject( $bench );
		$benchProxy->defaultCount = 2;

		$buffer = [];
		$bench->bench( [
			'test' => [
				'setup' => static function () use ( &$buffer ) {
					$buffer[] = 'setup';
				},
				'function' => static function () use ( &$buffer ) {
					$buffer[] = 'run';
				}
			]
		] );

		$this->assertSame( [ 'setup', 'run', 'run' ], $buffer );
	}

	public function testBenchVerbose() {
		$bench = $this->getMockBuilder( Benchmarker::class )
			->onlyMethods( [ 'execute', 'output', 'hasOption', 'verboseRun' ] )
			->getMock();
		$benchProxy = TestingAccessWrapper::newFromObject( $bench );
		$benchProxy->defaultCount = 1;

		$bench->expects( $this->once() )->method( 'hasOption' )
			->willReturnMap( [
				[ 'verbose', true ],
			] );

		$bench->expects( $this->once() )->method( 'verboseRun' )
			->with( 0 )
			->willReturn( null );

		$bench->bench( [
			'test' => static function () {
			}
		] );
	}

	public function noop() {
	}

	public function testBenchName_method() {
		$bench = $this->getMockBuilder( Benchmarker::class )
			->onlyMethods( [ 'execute', 'output', 'addResult' ] )
			->getMock();
		$benchProxy = TestingAccessWrapper::newFromObject( $bench );
		$benchProxy->defaultCount = 1;

		$bench->expects( $this->once() )->method( 'addResult' )
			->with( $this->callback( static function ( $res ) {
				return isset( $res['name'] ) && $res['name'] === ( __CLASS__ . '::noop()' );
			} ) );

		$bench->bench( [
			[ 'function' => [ $this, 'noop' ] ]
		] );
	}

	public function testBenchName_string() {
		$bench = $this->getMockBuilder( Benchmarker::class )
			->onlyMethods( [ 'execute', 'output', 'addResult' ] )
			->getMock();
		$benchProxy = TestingAccessWrapper::newFromObject( $bench );
		$benchProxy->defaultCount = 1;

		$bench->expects( $this->once() )->method( 'addResult' )
			->with( $this->callback( static function ( $res ) {
				return $res['name'] === "strtolower('A')";
			} ) );

		$bench->bench( [ [
			'function' => 'strtolower',
			'args' => [ 'A' ],
		] ] );
	}

	public function testVerboseRun() {
		$bench = $this->getMockBuilder( Benchmarker::class )
			->onlyMethods( [ 'execute', 'output', 'hasOption', 'startBench', 'addResult' ] )
			->getMock();
		$benchProxy = TestingAccessWrapper::newFromObject( $bench );
		$benchProxy->defaultCount = 1;

		$bench->expects( $this->once() )->method( 'hasOption' )
			->willReturnMap( [
				[ 'verbose', true ],
			] );

		$bench->expects( $this->once() )->method( 'output' )
			->with( $this->callback( static function ( $out ) {
				return preg_match( '/memory.+ peak/', $out ) === 1;
			} ) );

		$bench->bench( [
			'test' => static function () {
			}
		] );
	}

	public function testNaming() {
		$bench = $this->getMockBuilder( Benchmarker::class )
			->onlyMethods( [ 'execute', 'output', 'startBench' ] )
			->getMock();
		$benchProxy = TestingAccessWrapper::newFromObject( $bench );
		$benchProxy->defaultCount = 1;

		$out = '';
		$bench->expects( $this->any() )->method( 'output' )
			->will( $this->returnCallback( static function ( $str ) use ( &$out ) {
				$out .= $str;
				return null;
			} ) );

		$bench->bench( [
			[
				'function' => 'in_array',
				'args' => [ 'A', [ 'X', 'Y' ] ],
			],
			[
				'function' => 'in_array',
				'args' => [ 'A', [ 'X', 'Y', str_repeat( 'z', 900 ) ] ],
			],
			[
				'function' => 'strtolower',
				'args' => [ 'A' ],
			],
			[
				'function' => 'strtolower',
				'args' => [ str_repeat( 'x', 900 ) ],
			],
			[
				'function' => 'in_array',
				'args' => [ str_repeat( 'y', 900 ), [] ],
			],
			[
				'function' => 'in_array',
				'args' => [ str_repeat( 'z', 900 ), [] ],
			],
		] );

		$out = preg_replace( '/^.*(: |memory).*\n/m', '', $out );
		$out = trim( str_replace( "\n\n", "\n", $out ) );
		$this->assertEquals(
			<<<TEXT
			in_array@1
			in_array@2
			strtolower('A')
			strtolower@2
			in_array@3
			in_array@4
			TEXT,
			$out
		);
	}
}
