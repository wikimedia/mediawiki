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
 * @author Timo Tijhof
 */

require_once __DIR__ . '/Benchmarker.php';

/**
 * Maintenance script that benchmarks CSSMin.
 *
 * @ingroup Benchmark
 */
class BenchmarkCSSMin extends Benchmarker {
	public function __construct() {
		parent::__construct();
		$this->addDescription( 'Benchmarks CSSMin.' );
		$this->addOption( 'file', 'Path to CSS file (may be gzipped)', false, true );
		$this->addOption( 'out', 'Echo output of one run to stdout for inspection', false, false );
	}

	public function execute() {
		$file = $this->getOption( 'file', __DIR__ . '/cssmin/styles.css' );
		$filename = basename( $file );
		$css = $this->loadFile( $file );

		if ( $this->hasOption( 'out' ) ) {
			echo "## minify\n\n",
				CSSMin::minify( $css ),
				"\n\n";
			echo "## remap\n\n",
				CSSMin::remap( $css, dirname( $file ), 'https://example.org/test/', true ),
				"\n";
			return;
		}

		$this->bench( [
			"minify ($filename)" => [
				'function' => [ CSSMin::class, 'minify' ],
				'args' => [ $css ]
			],
			"remap ($filename)" => [
				'function' => [ CSSMin::class, 'remap' ],
				'args' => [ $css, dirname( $file ), 'https://example.org/test/', true ]
			],
		] );
	}

	private function loadFile( $file ) {
		$css = file_get_contents( $file );
		// Detect GZIP compression header
		if ( substr( $css, 0, 2 ) === "\037\213" ) {
			$css = gzdecode( $css );
		}
		return $css;
	}
}

$maintClass = BenchmarkCSSMin::class;
require_once RUN_MAINTENANCE_IF_MAIN;
