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
 * @author Aaron Schulz
 */

/**
 * @since 1.24
 */
class BloomFilterTitleHasLogs {
	public static function mergeAndCheck(
		BloomCache $bcache, $domain, $virtualKey, array $status
	) {
		$age = microtime( true ) - $status['asOfTime']; // seconds
		$scopedLock = ( mt_rand( 1, (int)pow( 3, max( 0, 5 - $age ) ) ) == 1 )
			? $bcache->getScopedLock( $virtualKey )
			: false;

		if ( $scopedLock ) {
			$updates = self::merge( $bcache, $domain, $virtualKey, $status );
			if ( isset( $updates['asOfTime'] ) ) {
				$age = ( microtime( true ) - $updates['asOfTime'] );
			}
		}

		return ( $age < 30 );
	}

	public static function merge(
		BloomCache $bcache, $domain, $virtualKey, array $status
	) {
		$limit = 1000;
		$dbr = wfGetDB( DB_SLAVE, array(), $domain );
		$res = $dbr->select( 'logging',
			array( 'log_namespace', 'log_title', 'log_id', 'log_timestamp' ),
			array( 'log_id > ' . $dbr->addQuotes( (int)$status['lastID'] ) ),
			__METHOD__,
			array( 'ORDER BY' => 'log_id', 'LIMIT' => $limit )
		);

		$updates = array();
		if ( $res->numRows() > 0 ) {
			$members = array();
			foreach ( $res as $row ) {
				$members[] = "$virtualKey:{$row->log_namespace}:{$row->log_title}";
			}
			$lastID = $row->log_id;
			$lastTime = $row->log_timestamp;
			if ( !$bcache->add( 'shared', $members ) ) {
				return false;
			}
			$updates['lastID'] = $lastID;
			$updates['asOfTime'] = wfTimestamp( TS_UNIX, $lastTime );
		} else {
			$updates['asOfTime'] = microtime( true );
		}

		$updates['epoch'] = $status['epoch'] ?: microtime( true );

		$bcache->setStatus( $virtualKey, $updates );

		return $updates;
	}
}
