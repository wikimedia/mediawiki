<?php
/**
 * @license GPL-2.0-or-later
 * @file
 * @ingroup Benchmark
 */

use MediaWiki\Maintenance\Benchmarker;

// @codeCoverageIgnoreStart
require_once __DIR__ . '/../includes/Benchmarker.php';
// @codeCoverageIgnoreEnd

/**
 * Measure ResourceLoader syntax validation for user-supplied JavaScript.
 *
 * @see ResourceLoader\Module::validateScriptFile
 * @see JSParseHelper
 * @ingroup Benchmark
 */
class BenchmarkJsValidate extends Benchmarker {
	/** @inheritDoc */
	protected $defaultCount = 10;

	public function __construct() {
		parent::__construct();
		$this->addDescription( 'Measure JavaScript syntax validation.' );
		$this->addOption( 'file', 'Path to JS file. Default: jquery', false, true );
	}

	public function execute() {
		$file = $this->getOption( 'file', __DIR__ . '/data/jsmin/jquery-3.2.1.js.gz' );
		$content = $this->loadFile( $file );
		if ( $content === false ) {
			$this->fatalError( 'Unable to open input file' );
		}

		$filename = basename( $file );

		$this->bench( [
			"Peast::parse ($filename)" => [
				'function' => static function ( $content ) {
					Peast\Peast::ES2017( $content )->parse();
				},
				'args' => [ $content ]
			]
		] );
	}
}

// @codeCoverageIgnoreStart
$maintClass = BenchmarkJsValidate::class;
require_once RUN_MAINTENANCE_IF_MAIN;
// @codeCoverageIgnoreEnd
