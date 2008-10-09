<?php

require( 'commandLine.inc' );

$batchSize = 1000;
$start = '';
$dbr = wfGetDB( DB_SLAVE );
$localRepo = RepoGroup::singleton()->getLocalRepo();

$numImages = 0;
$numGood = 0;

do {
	$res = $dbr->select( 'image', '*', array( 'img_name > ' . $dbr->addQuotes( $start ) ) );
	foreach ( $res as $row ) {
		$numImages++;
		$start = $row->img_name;
		$file = $localRepo->newFileFromRow( $row );
		$path = $file->getPath();
		if ( !$path ) {
			echo "{$row->img_name}: not locally accessible\n";
			continue;
		}
		$stat = @stat( $file->getPath() );
		if ( !$stat ) {
			echo "{$row->img_name}: missing\n";
			continue;
		}

		if ( $stat['size'] == 0 && $row->img_size != 0 ) {
			echo "{$row->img_name}: truncated, was {$row->img_size}\n";
			continue;
		}

		if ( $stat['size'] != $row->img_size ) {
			echo "{$row->img_name}: size mismatch DB={$row->img_size}, actual={$row->img_size}\n";
			continue;
		}

		$numGood++;
	}

} while ( $res->numRows() );

echo "Good images: $numGood/$numImages\n";
