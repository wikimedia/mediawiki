<?php

// @codeCoverageIgnoreStart
require_once __DIR__ . '/../includes/Benchmarker.php';
// @codeCoverageIgnoreEnd

/**
 * Benchmark any provided code for ad-hoc benchmarks.
 *
 * Usage:
 *
 *    $ php benchmarkEval.php --code "Html::openElement( 'a', [ 'class' => 'foo' ] );"
 *    $ echo "Html::openElement( 'a', [ 'class' => 'foo' ] );" | php benchmarkEval.php
 *    $ php benchmarkEval.php input.txt
 *
 * @ingroup Benchmark
 */
class BenchmarkEval extends Benchmarker {
	public function __construct() {
		parent::__construct();
		$this->addOption( 'inner',
			'Inner loop iterations', false, true );
		$this->addOption( 'code',
			'The code to run',
			false, true, 'e' );
		$this->addOption( 'setup',
			'Code to run once before the first iteration',
			false, true );
		$this->addArg( 'input-file', 'Input file for measured code body', false );
	}

	public function execute() {
		if ( $this->hasOption( 'setup' ) ) {
			$setupCode = $this->getOption( 'setup' ) . ';';
			// phpcs:ignore MediaWiki.Usage.ForbiddenFunctions.eval
			eval( $setupCode );
		}

		if ( $this->hasOption( 'code' ) ) {
			$code = $this->getOption( 'code' );
		} elseif ( $this->hasArg( 0 ) ) {
			$code = file_get_contents( $this->getArg( 0 ) );
			if ( $code === false ) {
				$this->fatalError( "Unable to read input file" );
			}
		} else {
			fwrite( STDERR, "Reading from stdin...\n" );
			$code = stream_get_contents( STDIN );
		}
		$code .= ';';
		$inner = $this->getOption( 'inner', 1 );
		if ( $inner > 1 ) {
			$code = "for ( \$__i = 0; \$__i < $inner; \$__i++ ) { $code }";
		}
		$code = "function wfBenchmarkEvalBody () { $code }";
		// phpcs:ignore MediaWiki.Usage.ForbiddenFunctions.eval
		eval( $code );
		$this->bench( [ 'eval' => [ 'function' => 'wfBenchmarkEvalBody' ] ] );
	}
}

// @codeCoverageIgnoreStart
$maintClass = BenchmarkEval::class;
require_once RUN_MAINTENANCE_IF_MAIN;
// @codeCoverageIgnoreEnd
