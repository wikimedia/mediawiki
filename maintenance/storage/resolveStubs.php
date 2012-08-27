<?php
/**
 * Script to convert history stubs that point to an external row to direct
 * external pointers.
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

define( 'REPORTING_INTERVAL', 100 );

if ( !defined( 'MEDIAWIKI' ) ) {
	$optionsWithArgs = array( 'm' );

	require_once( __DIR__ . '/../commandLine.inc' );

	resolveStubs();
}

/**
 * Convert history stubs that point to an external row to direct
 * external pointers
 */
function resolveStubs() {
	$fname = 'resolveStubs';

	$dbr = wfGetDB( DB_SLAVE );
	$maxID = $dbr->selectField( 'text', 'MAX(old_id)', false, $fname );
	$blockSize = 10000;
	$numBlocks = intval( $maxID / $blockSize ) + 1;

	for ( $b = 0; $b < $numBlocks; $b++ ) {
		wfWaitForSlaves();

		printf( "%5.2f%%\n", $b / $numBlocks * 100 );
		$start = intval( $maxID / $numBlocks ) * $b + 1;
		$end = intval( $maxID / $numBlocks ) * ( $b + 1 );

		$res = $dbr->select( 'text', array( 'old_id', 'old_text', 'old_flags' ),
			"old_id>=$start AND old_id<=$end " .
			"AND old_flags LIKE '%object%' AND old_flags NOT LIKE '%external%' " .
			'AND LOWER(CONVERT(LEFT(old_text,22) USING latin1)) = \'o:15:"historyblobstub"\'',
			$fname );
		foreach ( $res as $row ) {
			resolveStub( $row->old_id, $row->old_text, $row->old_flags );
		}
	}
	print "100%\n";
}

/**
 * Resolve a history stub
 */
function resolveStub( $id, $stubText, $flags ) {
	$fname = 'resolveStub';

	$stub = unserialize( $stubText );
	$flags = explode( ',', $flags );

	$dbr = wfGetDB( DB_SLAVE );
	$dbw = wfGetDB( DB_MASTER );

	if ( strtolower( get_class( $stub ) ) !== 'historyblobstub' ) {
		print "Error found object of class " . get_class( $stub ) . ", expecting historyblobstub\n";
		return;
	}

	# Get the (maybe) external row
	$externalRow = $dbr->selectRow( 'text', array( 'old_text' ),
		array( 'old_id' => $stub->mOldId, 'old_flags' . $dbr->buildLike( $dbr->anyString(), 'external', $dbr->anyString() ) ),
		$fname
	);

	if ( !$externalRow ) {
		# Object wasn't external
		return;
	}

	# Preserve the legacy encoding flag, but switch from object to external
	if ( in_array( 'utf-8', $flags ) ) {
		$newFlags = 'external,utf-8';
	} else {
		$newFlags = 'external';
	}

	# Update the row
	# print "oldid=$id\n";
	$dbw->update( 'text',
		array( /* SET */
			'old_flags' => $newFlags,
			'old_text' => $externalRow->old_text . '/' . $stub->mHash
		),
		array( /* WHERE */
			'old_id' => $id
		), $fname
	);
}

