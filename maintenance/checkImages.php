<?php
/**
 * Check images to see if they exist, are readable, etc etc
 */
require_once( "Maintenance.php" );

class CheckImages extends Maintenance {

	public function __construct() {
		parent::__construct();
		$this->mDescription = "Check images to see if they exist, are readable, etc";
	}
	
	public function execute() {
		$batchSize = 1000;
		$start = '';
		$dbr = wfGetDB( DB_SLAVE );

		$numImages = 0;
		$numGood = 0;
	
		do {
			$res = $dbr->select( 'image', '*', array( 'img_name > ' . $dbr->addQuotes( $start ) ), 
				__METHOD__, array( 'LIMIT' => $batchSize ) );
			foreach ( $res as $row ) {
				$numImages++;
				$start = $row->img_name;
				$file = RepoGroup::singleton()->getLocalRepo()->newFileFromRow( $row );
				$path = $file->getPath();
				if ( !$path ) {
					$this->output( "{$row->img_name}: not locally accessible\n";
					continue;
				}
				$stat = @stat( $file->getPath() );
				if ( !$stat ) {
					$this->output( "{$row->img_name}: missing\n" );
					continue;
				}
	
				if ( $stat['mode'] & 040000 ) {
					$this->output( "{$row->img_name}: is a directory\n" );
					continue;
				}
	
				if ( $stat['size'] == 0 && $row->img_size != 0 ) {
					$this->output( "{$row->img_name}: truncated, was {$row->img_size}\n" );
					continue;
				}
	
				if ( $stat['size'] != $row->img_size ) {
					$this->output( "{$row->img_name}: size mismatch DB={$row->img_size}, actual={$stat['size']}\n" );
					continue;
				}
	
				$numGood++;
			}
	
		} while ( $res->numRows() );
	
		$this->output( "Good images: $numGood/$numImages\n" );
	}
}

