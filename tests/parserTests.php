<?php
/**
 * MediaWiki parser test suite
 *
 * Copyright © 2004 Brion Vibber <brion@pobox.com>
 * https://www.mediawiki.org/
 *
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

$otions = array( 'quick', 'color', 'quiet', 'help', 'show-output',
	'record', 'run-disabled', 'run-parsoid' );
$optionsWithArgs = array( 'regex', 'filter', 'seed', 'setversion' );

require_once __DIR__ . '/../maintenance/commandLine.inc';
require_once __DIR__ . '/TestsAutoLoader.php';

if ( isset( $options['help'] ) ) {
	echo <<<ENDS
MediaWiki $wgVersion parser test suite
Usage: php parserTests.php [options...]

Options:
  --quick          Suppress diff output of failed tests
  --quiet          Suppress notification of passed tests (shows only failed tests)
  --show-output    Show expected and actual output
  --color[=yes|no] Override terminal detection and force color output on or off
                   use wgCommandLineDarkBg = true; if your term is dark
  --regex          Only run tests whose descriptions which match given regex
  --filter         Alias for --regex
  --file=<testfile> Run test cases from a custom file instead of parserTests.txt
  --record         Record tests in database
  --compare        Compare with recorded results, without updating the database.
  --setversion     When using --record, set the version string to use (useful
                   with git-svn so that you can get the exact revision)
  --keep-uploads   Re-use the same upload directory for each test, don't delete it
  --fuzz           Do a fuzz test instead of a normal test
  --seed <n>       Start the fuzz test from the specified seed
  --help           Show this help message
  --run-disabled   run disabled tests
  --run-parsoid    run parsoid tests (normally disabled)

ENDS;
	exit( 0 );
}

# Cases of weird db corruption were encountered when running tests on earlyish
# versions of SQLite
if ( $wgDBtype == 'sqlite' ) {
	$db = wfGetDB( DB_MASTER );
	$version = $db->getServerVersion();
	if ( version_compare( $version, '3.6' ) < 0 ) {
		die( "Parser tests require SQLite version 3.6 or later, you have $version\n" );
	}
}

$tester = new ParserTest( $options );

if ( isset( $options['file'] ) ) {
	$files = array( $options['file'] );
} else {
	// Default parser tests and any set from extensions or local config
	$files = $wgParserTestFiles;
}

# Print out software version to assist with locating regressions
$version = SpecialVersion::getVersion( 'nodb' );
echo "This is MediaWiki version {$version}.\n\n";

if ( isset( $options['fuzz'] ) ) {
	$tester->fuzzTest( $files );
} else {
	$ok = $tester->runTestsFromFiles( $files );
	exit( $ok ? 0 : 1 );
}
