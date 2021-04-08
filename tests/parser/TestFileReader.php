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
 * @ingroup Testing
 */

use Wikimedia\Parsoid\ParserTests\TestFileReader as ParsoidTestFileReader;

class TestFileReader {
	/**
	 * Read and parse the parser test file named by $file.
	 * @internal
	 * @param string $file
	 * @param array $options
	 * @return array
	 */
	public static function read( $file, array $options = [] ) {
		$options = $options + [
			'runDisabled' => false,
			'filter' => false,
		];
		if ( isset( $options['regex'] ) ) {
			$options['filter'] = [ 'regex' => $options['regex'] ];
		}
		$parsoidReader = ParsoidTestFileReader::read( $file, static function ( $msg ) {
			wfDeprecatedMsg( $msg, '1.35', false, false );
		} );
		$testFormat = intval( $parsoidReader->fileOptions['version'] ?? '1' );
		if ( $testFormat < 2 ) {
			throw new MWException(
				"$file needs an update. Support for the parserTest v1 file format was removed in MediaWiki 1.36"
			);
		}
		$tests = [];
		foreach ( $parsoidReader->testCases as $t ) {
			self::addTest( $tests, $t, $options );
		}
		$articles = [];
		foreach ( $parsoidReader->articles as $a ) {
			$articles[] = [
				'name' => $a->title,
				'text' => $a->text,
				'line' => $a->lineNumStart,
				'file' => $a->filename,
			];
		}
		return [
			'fileOptions' => $parsoidReader->fileOptions,
			'tests' => $tests,
			'articles' => $articles,
		];
	}

	private static function addTest( array &$tests, Wikimedia\Parsoid\ParserTests\Test $t, array $options ) {
		if ( $t->wikitext === false ) {
			$t->error( "Test lacks wikitext section", $t->testName );
		}

		if ( isset( $t->options['disabled'] ) && !$options['runDisabled'] ) {
			// Disabled
			return;
		}

		if ( $t->legacyHtml === null ) {
			if ( isset( $t->sections['html/parsoid'] ) || isset( $t->sections['wikitext/edited'] ) ) {
				// Parsoid only
				return;
			} else {
				$t->error( "Test lacks html section", $t->testName );
			}
		}

		if ( !$t->matchesFilter( $options['filter'] ) ) {
			// Filtered test
			return;
		}

		$tests[] = [
			'test' => $t->testName,
			'desc' => $t->testName,
			'input' => $t->wikitext,
			'result' => $t->legacyHtml,
			'options' => $t->options,
			'config' => $t->config ?? '',
			'line' => $t->lineNumStart,
			'file' => $t->filename,
		];
	}
}
