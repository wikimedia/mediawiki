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
 * @ingroup Maintenance ExternalStorage
 */

require_once __DIR__ . '/../Maintenance.php';

class StorageTypeStats extends Maintenance {
	function execute() {
		$dbr = $this->getDB( DB_REPLICA );

		$endId = $dbr->selectField( 'text', 'MAX(old_id)', '', __METHOD__ );
		if ( !$endId ) {
			echo "No text rows!\n";
			exit( 1 );
		}

		$binSize = intval( pow( 10, floor( log10( $endId ) ) - 3 ) );
		if ( $binSize < 100 ) {
			$binSize = 100;
		}
		echo "Using bin size of $binSize\n";

		$stats = [];

		$classSql = <<<SQL
			IF(old_flags LIKE '%external%',
				IF(old_text REGEXP '^DB://[[:alnum:]]+/[0-9]+/[0-9a-f]{32}$',
					'CGZ pointer',
					IF(old_text REGEXP '^DB://[[:alnum:]]+/[0-9]+/[0-9]{1,6}$',
						'DHB pointer',
						IF(old_text REGEXP '^DB://[[:alnum:]]+/[0-9]+$',
							'simple pointer',
							'UNKNOWN pointer'
						)
					)
				),
				IF(old_flags LIKE '%object%',
					TRIM('"' FROM SUBSTRING_INDEX(SUBSTRING_INDEX(old_text, ':', 3), ':', -1)),
					'[none]'
				)
			)
SQL;

		for ( $rangeStart = 0; $rangeStart < $endId; $rangeStart += $binSize ) {
			if ( $rangeStart / $binSize % 10 == 0 ) {
				echo "$rangeStart\r";
			}
			$res = $dbr->select(
				'text',
				[
					'old_flags',
					"$classSql AS class",
					'COUNT(*) as count',
				],
				[
					'old_id >= ' . intval( $rangeStart ),
					'old_id < ' . intval( $rangeStart + $binSize )
				],
				__METHOD__,
				[ 'GROUP BY' => 'old_flags, class' ]
			);

			foreach ( $res as $row ) {
				$flags = $row->old_flags;
				if ( $flags === '' ) {
					$flags = '[none]';
				}
				$class = $row->class;
				$count = $row->count;
				if ( !isset( $stats[$flags][$class] ) ) {
					$stats[$flags][$class] = [
						'count' => 0,
						'first' => $rangeStart,
						'last' => 0
					];
				}
				$entry =& $stats[$flags][$class];
				$entry['count'] += $count;
				$entry['last'] = max( $entry['last'], $rangeStart + $binSize );
				unset( $entry );
			}
		}
		echo "\n\n";

		$format = "%-29s %-39s %-19s %-29s\n";
		printf( $format, "Flags", "Class", "Count", "old_id range" );
		echo str_repeat( '-', 120 ) . "\n";
		foreach ( $stats as $flags => $flagStats ) {
			foreach ( $flagStats as $class => $entry ) {
				printf( $format, $flags, $class, $entry['count'],
					sprintf( "%-13d - %-13d", $entry['first'], $entry['last'] ) );
			}
		}
	}
}

$maintClass = StorageTypeStats::class;
require_once RUN_MAINTENANCE_IF_MAIN;
