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

require_once __DIR__ . '/Benchmarker.php';

/**
 * Maintenance script that benchmarks Sanitizer methods.
 *
 * @ingroup Benchmark
 */
class BenchmarkSanitizer extends Benchmarker {
	public function __construct() {
		parent::__construct();
		$this->addDescription( 'Benchmark for Sanitizer methods.' );
		$this->addOption( 'method', 'One of "validateEmail", "encodeAttribute", '
			. '"safeEncodeAttribute", "removeHTMLtags", or "stripAllTags". '
			. 'Default: (All)', false, true );
	}

	public function execute() {
		$textWithHtmlSm = 'Before <wrap><in>and</in> another <unclose> <in>word</in></wrap>.';
		$textWithHtmlLg = str_repeat(
				// 28K (28 chars * 1000)
				wfRandomString( 3 ) . ' <tag>' . wfRandomString( 5 ) . '</tag> ' . wfRandomString( 7 ),
				1000
		);

		$method = $this->getOption( 'method' );
		$benches = [];

		if ( !$method || $method === 'validateEmail' ) {
			$benches['Sanitizer::validateEmail (valid)'] = function () {
				Sanitizer::validateEmail( 'user@example.org' );
			};
			$benches['Sanitizer::validateEmail (invalid)'] = function () {
				Sanitizer::validateEmail( 'username@example! org' );
			};
		}
		if ( !$method || $method === 'encodeAttribute' ) {
			$benches['Sanitizer::encodeAttribute (simple)'] = function () {
				Sanitizer::encodeAttribute( 'simple' );
			};
			$benches['Sanitizer::encodeAttribute (special)'] = function () {
				Sanitizer::encodeAttribute( ":'\"\n https://example" );
			};
		}
		if ( !$method || $method === 'safeEncodeAttribute' ) {
			$benches['Sanitizer::safeEncodeAttribute (simple)'] = function () {
				Sanitizer::safeEncodeAttribute( 'simple' );
			};
			$benches['Sanitizer::safeEncodeAttribute (special)'] = function () {
				Sanitizer::safeEncodeAttribute( ":'\"\n https://example" );
			};
		}
		if ( !$method || $method === 'removeHTMLtags' ) {
			$sm = strlen( $textWithHtmlSm );
			$lg = round( strlen( $textWithHtmlLg ) / 1000 ) . 'K';
			$benches["Sanitizer::removeHTMLtags (input: $sm)"] = function () use ( $textWithHtmlSm ) {
				Sanitizer::removeHTMLtags( $textWithHtmlSm );
			};
			$benches["Sanitizer::removeHTMLtags (input: $lg)"] = function () use ( $textWithHtmlLg ) {
				Sanitizer::removeHTMLtags( $textWithHtmlLg );
			};
		}
		if ( !$method || $method === 'stripAllTags' ) {
			$sm = strlen( $textWithHtmlSm );
			$lg = round( strlen( $textWithHtmlLg ) / 1000 ) . 'K';
			$benches["Sanitizer::stripAllTags (input: $sm)"] = function () use ( $textWithHtmlSm ) {
				Sanitizer::stripAllTags( $textWithHtmlSm );
			};
			$benches["Sanitizer::stripAllTags (input: $lg)"] = function () use ( $textWithHtmlLg ) {
				Sanitizer::stripAllTags( $textWithHtmlLg );
			};
		}

		$this->bench( $benches );
	}
}

$maintClass = BenchmarkSanitizer::class;
require_once RUN_MAINTENANCE_IF_MAIN;
