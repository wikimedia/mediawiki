<?
require_once( 'commandLine.inc' );
$dbr =& wfGetDB( DB_SLAVE );
$row = $dbr->selectRow( 'old', array( 'old_flags', 'old_text' ), array( 'old_id' => 52 ) );
$obj = unserialize( $row->old_text );
print_r( array_keys( $obj->mItems ) );

?>
