<?php

require_once( dirname( __FILE__ ) . '/../Maintenance.php' );

class StorageTypeStats extends Maintenance {
	function execute() {
		$dbr = wfGetDB( DB_SLAVE );

		$endId = $dbr->selectField( 'text', 'MAX(old_id)', false, __METHOD__ );
		if ( !$endId ) {
			echo "No text rows!\n";
			exit( 1 );
		}

		$rangeStart = 0;
		$binSize = intval( pow( 10, floor( log10( $endId ) ) - 3 ) );
		if ( $binSize < 100 ) {
			$binSize = 100;
		}
		echo "Using bin size of $binSize\n";

		$stats = array();

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
				array(
					'old_flags',
					"$classSql AS class",
					'COUNT(*) as count',
				),
				array(
					'old_id >= ' . intval( $rangeStart ),
					'old_id < ' . intval( $rangeStart + $binSize )
				),
				__METHOD__,
				array( 'GROUP BY' => 'old_flags, class' )
			);

			foreach ( $res as $row ) {
				$flags = $row->old_flags;
				if ( $flags === '' ) {
					$flags = '[none]';
				}
				$class = $row->class;
				$count = $row->count;
				if ( !isset( $stats[$flags][$class] ) ) {
					$stats[$flags][$class] = array(
						'count' => 0,
						'first' => $rangeStart,
						'last' => 0
					);
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

$maintClass = 'StorageTypeStats';
require_once( DO_MAINTENANCE );

