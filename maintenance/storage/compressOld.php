<?php
/**
 * Compress the text of a wiki.
 *
 * Usage:
 *
 * Non-wikimedia
 * php compressOld.php [options...]
 *
 * Wikimedia
 * php compressOld.php <database> [options...]
 *
 * Options are:
 *  -t <type>           set compression type to either:
 *                          gzip: compress revisions independently
 *                          concat: concatenate revisions and compress in chunks (default)
 *  -c <chunk-size>     maximum number of revisions in a concat chunk
 *  -b <begin-date>     earliest date to check for uncompressed revisions
 *  -e <end-date>       latest revision date to compress
 *  -s <start-id>       the old_id to start from
 *  --extdb <cluster>   store specified revisions in an external cluster (untested)
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
 * @ingroup Maintenance ExternalStorage
 */

$optionsWithArgs = array( 't', 'c', 's', 'f', 'h', 'extdb', 'endid', 'e' );
require_once( dirname( __FILE__ ) . '/../commandLine.inc' );
require_once( "compressOld.inc" );

if ( !function_exists( "gzdeflate" ) ) {
	print "You must enable zlib support in PHP to compress old revisions!\n";
	print "Please see http://www.php.net/manual/en/ref.zlib.php\n\n";
	wfDie();
}

$defaults = array(
	't' => 'concat',
	'c' => 20,
	's' => 0,
	'b' => '',
	'e' => '',
	'extdb' => '',
	'endid' => false,
);

$options = $options + $defaults;

if ( $options['t'] != 'concat' && $options['t'] != 'gzip' ) {
	print "Type \"{$options['t']}\" not supported\n";
}

if ( $options['extdb'] != '' ) {
	print "Compressing database $wgDBname to external cluster {$options['extdb']}\n" . str_repeat( '-', 76 ) . "\n\n";
} else {
	print "Compressing database $wgDBname\n" . str_repeat( '-', 76 ) . "\n\n";
}

$success = true;
if ( $options['t'] == 'concat' ) {
	$success = compressWithConcat( $options['s'], $options['c'], $options['b'],
		$options['e'], $options['extdb'], $options['endid'] );
} else {
	compressOldPages( $options['s'], $options['extdb'] );
}

if ( $success ) {
	print "Done.\n";
}

exit( 0 );


