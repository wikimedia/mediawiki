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

require_once __DIR__ . '/../includes/Benchmarker.php';

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
			. '"safeEncodeAttribute", "internalRemoveHtmlTags", "removeSomeTags", "tidy", or "stripAllTags". '
			. 'Default: (All)', false, true );
	}

	public function execute() {
		# text with no html simulates an interface message string or a title
		$textWithNoHtml = 'This could be an article title';
		$textWithHtmlSm = 'Before <wrap><in>and</in> another <unclose> <in>word</in></wrap>.';
		$textWithHtmlLg = str_repeat(
				// 28K (28 chars * 1000)
				wfRandomString( 3 ) . ' <tag>' . wfRandomString( 5 ) . '</tag> ' . wfRandomString( 7 ),
				1000
		);

		$method = $this->getOption( 'method' );
		$benches = [];

		if ( !$method || $method === 'validateEmail' ) {
			$benches['Sanitizer::validateEmail (valid)'] = static function () {
				Sanitizer::validateEmail( 'user@example.org' );
			};
			$benches['Sanitizer::validateEmail (invalid)'] = static function () {
				Sanitizer::validateEmail( 'username@example! org' );
			};
		}
		if ( !$method || $method === 'encodeAttribute' ) {
			$benches['Sanitizer::encodeAttribute (simple)'] = static function () {
				Sanitizer::encodeAttribute( 'simple' );
			};
			$benches['Sanitizer::encodeAttribute (special)'] = static function () {
				Sanitizer::encodeAttribute( ":'\"\n https://example" );
			};
		}
		if ( !$method || $method === 'safeEncodeAttribute' ) {
			$benches['Sanitizer::safeEncodeAttribute (simple)'] = static function () {
				Sanitizer::safeEncodeAttribute( 'simple' );
			};
			$benches['Sanitizer::safeEncodeAttribute (special)'] = static function () {
				Sanitizer::safeEncodeAttribute( ":'\"\n https://example" );
			};
		}
		if ( !$method || $method === 'internalRemoveHtmlTags' ) {
			$tiny = strlen( $textWithNoHtml );
			$sm = strlen( $textWithHtmlSm );
			$lg = round( strlen( $textWithHtmlLg ) / 1000 ) . 'K';
			$benches["Sanitizer::internalRemoveHtmlTags (input: $tiny)"] = static function () use ( $textWithNoHtml ) {
				Sanitizer::internalRemoveHtmlTags( $textWithNoHtml );
			};
			$benches["Sanitizer::internalRemoveHtmlTags (input: $sm)"] = static function () use ( $textWithHtmlSm ) {
				Sanitizer::internalRemoveHtmlTags( $textWithHtmlSm );
			};
			$benches["Sanitizer::internalRemoveHtmlTags (input: $lg)"] = static function () use ( $textWithHtmlLg ) {
				Sanitizer::internalRemoveHtmlTags( $textWithHtmlLg );
			};
		}
		if ( !$method || $method === 'tidy' ) {
			# This matches what DISPLAYTITLE was previously doing to sanitize
			# title strings
			$tiny = strlen( $textWithNoHtml );
			$sm = strlen( $textWithHtmlSm );
			$lg = round( strlen( $textWithHtmlLg ) / 1000 ) . 'K';
			$doit = static function ( $text ) {
				return static function () use ( $text ) {
					$tidy = new \MediaWiki\Tidy\RemexDriver( new \MediaWiki\Config\ServiceOptions( [ 'TidyConfig' ], [
						'TidyConfig' => [ 'pwrap' => false ],
					] ) );
					$textWithTags = $tidy->tidy( $text, [ Sanitizer::class, 'armorFrenchSpaces' ] );
					$textWithTags = Sanitizer::normalizeCharReferences(
						Sanitizer::internalRemoveHtmlTags( $textWithTags )
					);
				};
			};
			$benches["DISPLAYTITLE tidy (input: $tiny)"] = $doit( $textWithNoHtml );
			$benches["DISPLAYTITLE tidy (input: $sm)"] = $doit( $textWithHtmlSm );
			$benches["DISPLAYTITLE tidy (input: $lg)"] = $doit( $textWithHtmlLg );
		}
		if ( !$method || $method === 'removeSomeTags' ) {
			$tiny = strlen( $textWithNoHtml );
			$sm = strlen( $textWithHtmlSm );
			$lg = round( strlen( $textWithHtmlLg ) / 1000 ) . 'K';
			$benches["Sanitizer::removeSomeTags (input: $tiny)"] = static function () use ( $textWithNoHtml ) {
				Sanitizer::removeSomeTags( $textWithNoHtml );
			};
			$benches["Sanitizer::removeSomeTags (input: $sm)"] = static function () use ( $textWithHtmlSm ) {
				Sanitizer::removeSomeTags( $textWithHtmlSm );
			};
			$benches["Sanitizer::removeSomeTags (input: $lg)"] = static function () use ( $textWithHtmlLg ) {
				Sanitizer::removeSomeTags( $textWithHtmlLg );
			};
		}
		if ( !$method || $method === 'stripAllTags' ) {
			$sm = strlen( $textWithHtmlSm );
			$lg = round( strlen( $textWithHtmlLg ) / 1000 ) . 'K';
			$benches["Sanitizer::stripAllTags (input: $sm)"] = static function () use ( $textWithHtmlSm ) {
				Sanitizer::stripAllTags( $textWithHtmlSm );
			};
			$benches["Sanitizer::stripAllTags (input: $lg)"] = static function () use ( $textWithHtmlLg ) {
				Sanitizer::stripAllTags( $textWithHtmlLg );
			};
		}

		$this->bench( $benches );
	}
}

$maintClass = BenchmarkSanitizer::class;
require_once RUN_MAINTENANCE_IF_MAIN;
