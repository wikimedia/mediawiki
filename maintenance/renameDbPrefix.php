<?php
/**
 * Run this script to after changing $wgDBprefix on a wiki.
 * The wiki will have to get downtime to do this correctly.
 *
 * @file
 * @ingroup Maintenance
 */
 
require_once( "Maintenance.php" );

class RenameDbPrefix extends Maintenance {
	public function __construct() {
		parent::__construct();
		$this->addParam( "old", "Old db prefix [0 for none]", true, true );
		$this->addParam( "new", "New db prefix [0 for none]", true, true );
	}
	
	public function execute() {
		// Allow for no old prefix
		if( $this->getOption( 'old', 0 ) === '0' ) {
			$old = '';
		} else {
			// Use nice safe, sane, prefixes
			preg_match( '/^[a-zA-Z]+_$/', $this->getOption('old'), $m );
			$old = isset( $m[0] ) ? $m[0] : false;
		}
		// Allow for no new prefix
		if( $this->getOption( 'new', 0 ) === '0' ) {
			$new = '';
		} else {
			// Use nice safe, sane, prefixes
			preg_match( '/^[a-zA-Z]+_$/', $this->getOption('new'), $m );
			$new = isset( $m[0] ) ? $m[0] : false;
		}
	
		if( $old === false || $new === false ) {
			$this->error( "Invalid prefix!\n", true );
		}
		if( $old === $new ) {
			$this->( "Same prefix. Nothing to rename!\n", true );
		}
	
		$this->output( "Renaming DB prefix for tables of $wgDBname from '$old' to '$new'\n" );
		$count = 0;
	
		$dbw = wfGetDB( DB_MASTER );
		$res = $dbw->query( "SHOW TABLES LIKE '".$dbw->escapeLike( $old )."%'" );
		foreach( $res as $row ) {
			// XXX: odd syntax. MySQL outputs an oddly cased "Tables of X"
			// sort of message. Best not to try $row->x stuff...
			$fields = get_object_vars( $row );
			// Silly for loop over one field...
			foreach( $fields as $resName => $table ) {
				// $old should be regexp safe ([a-zA-Z_])
				$newTable = preg_replace( '/^'.$old.'/', $new, $table );
				$this->output( "Renaming table $table to $newTable\n" );
				$dbw->query( "RENAME TABLE $table TO $newTable" );
			}
			$count++;
		}
		$this->output( "Done! [$count tables]\n" );
	}
}
