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
 * @ingroup Maintenance Memcached
 */

require_once( __DIR__ . '/commandLine.inc' );

function purgeStaleMemcachedText() {
	global $wgMemc, $wgDBname;
	$db = wfGetDB( DB_MASTER );
	$maxTextId = $db->selectField( 'text', 'max(old_id)' );
	$latestReplicatedTextId = $db->selectField( array( 'recentchanges', 'revision' ), 'rev_text_id', 
		array( 'rev_id = rc_this_oldid', "rc_timestamp < '20101225183000'"),  'purgeStaleMemcachedText', 
		array( 'ORDER BY' => 'rc_timestamp DESC' ) );
	$latestReplicatedTextId -= 100; # A bit of paranoia

	echo "Going to purge text entries from $latestReplicatedTextId to $maxTextId in $wgDBname\n";

	for ( $i = $latestReplicatedTextId; $i < $maxTextId; $i++ ) {
		$key = wfMemcKey( 'revisiontext', 'textid', $i );
		
		while (1) {
			if (! $wgMemc->delete( $key ) ) {
				echo "Memcache delete for $key returned false\n";
			}
			if ( $wgMemc->get( $key ) ) {
				echo "There's still content in $key!\n";
			} else {
				break;
			}
		}
		
	}
}

purgeStaleMemcachedText();

