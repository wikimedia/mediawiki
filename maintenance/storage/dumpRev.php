<?php

require_once( 'commandLine.inc' );
$dbr =& wfGetDB( DB_SLAVE );
$row = $dbr->selectRow( 'old', array( 'old_flags', 'old_text' ), array( 'old_id' => $args[0] ) );
$obj = unserialize( $row->old_text );

if ( get_class( $obj ) == 'concatenatedgziphistoryblob' ) {
	print_r( array_keys( $obj->mItems ) );
} else {
	var_dump( $obj );
}

?>
