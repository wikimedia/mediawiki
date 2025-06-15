<?php
/**
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along
 * with this program; if not, write to the Free Software Foundation, Inc.,
 * 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA.
 * http://www.gnu.org/copyleft/gpl.html
 *
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
