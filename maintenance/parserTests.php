<?php
# Copyright (C) 2004 Brion Vibber <brion@pobox.com>
# http://www.mediawiki.org/
#
# This program is free software; you can redistribute it and/or modify
# it under the terms of the GNU General Public License as published by
# the Free Software Foundation; either version 2 of the License, or
# (at your option) any later version.
#
# This program is distributed in the hope that it will be useful,
# but WITHOUT ANY WARRANTY; without even the implied warranty of
# MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
# GNU General Public License for more details.
#
# You should have received a copy of the GNU General Public License along
# with this program; if not, write to the Free Software Foundation, Inc.,
# 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA.
# http://www.gnu.org/copyleft/gpl.html

/**
 * @package MediaWiki
 * @subpackage Maintenance
 */

/** */
require('parserTests.inc');

if( isset( $options['help'] ) ) {
    echo <<<END
MediaWiki $wgVersion parser test suite
Usage: php parserTests.php [--quick] [--quiet] [--color[=(yes|no|light)]]
                           [--regex=<expression>] [--file=<testfile>]
                           [--help]
Options:
  --quick  Suppress diff output of failed tests
  --quiet  Suppress notification of passed tests (shows only failed tests)
  --color  Override terminal detection and force color output on or off
           'light' option is similar to 'yes' but with color for dark backgrounds
  --regex  Only run tests whose descriptions which match given regex
  --file   Run test cases from a custom file instead of parserTests.txt
  --help   Show this help message


END;
    exit( 0 );
}

# There is a convention that the parser should never
# refer to $wgTitle directly, but instead use the title
# passed to it.
$wgTitle = Title::newFromText( 'Parser test script do not use' );
$tester =& new ParserTest();

if( isset( $options['file'] ) ) {
	$file = $options['file'];
} else {
	# Note: the command line setup changes the current working directory
	# to the parent, which is why we have to put the subdir here:
	$file = $IP.'/maintenance/parserTests.txt';
}
$ok = $tester->runTestsFromFile( $file );

exit ($ok ? 0 : -1);
?>
