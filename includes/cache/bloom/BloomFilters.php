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
 * TODO: version for virtual <=> real key linkage
 *
 * @since 1.24
 */
class BloomFilterTitleHasLogs {
	public static function merge(
		BloomCache $bcache, $domain, $virtualKey, $memberPrefix, array $status
	) {
		$age = time() - $status['asOfTime'];
		$scopedLock = ( mt_rand( 0, 9 ) == 0 || $age > 5 )
			? $bcache->getScopedLock( $virtualKey )
			: false;

		if ( $scopedLock ) {
			$limit = 1000;
			$lastID = (int)$status['lastID'];

			$dbr = wfGetDB( DB_SLAVE, array(), $domain );
			$res = $dbr->select( 'logging',
				array( 'log_namespace', 'log_title', 'log_id', 'log_timestamp' ),
				array( 'log_id > ' . $dbr->addQuotes( $lastID ) ),
				__METHOD__,
				array( 'ORDER BY' => 'log_id', 'LIMIT' => 1000 )
			);

			$updates = array();
			if ( $res->numRows() > 0 ) {
				$members = array();
				foreach ( $res as $row ) {
					$members[] = "$memberPrefix:{$row->log_namespace}:{$row->log_title}";
				}
				$lastID = $row->log_id;
				$lastTime = $row->log_timestamp;
				if ( $bcache->add( 'primary', $members ) ) {
					$updates['lastID'] = $lastID;
					$updates['asOfTime'] = wfTimestamp( TS_UNIX, $lastTime );
					$age = time() - $updates['asOfTime'];
				}
			} else {
				$updates['asOfTime'] = time();
				$age = 0;
			}
			if ( $res->numRows() < $limit ) {
				$updates['ready'] = 1;
			}

			$bcache->setStatus( $virtualKey, $updates );
		}

		return ( $status['ready'] && $age < 60 );
	}
}
