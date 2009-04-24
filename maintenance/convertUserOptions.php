<?php
require( './commandLine.inc' );

// Do each user sequentially, since accounts can't be deleted

print "Beginning batch conversion of user options.\n";

$id = 0;
$dbw = wfGetDB( DB_MASTER );
$conversionCount = 0;

while ($id !== null) {
	$idCond = 'user_id>'.$dbw->addQuotes( $id );
	$optCond = "user_options!=".$dbw->addQuotes( '' ); // For compatibility
	$res = $dbw->select( 'user', '*',
			array( $optCond, $idCond ), __METHOD__,
			array( 'LIMIT' => 50, 'FOR UPDATE' ) );
	$id = convertOptionBatch( $res, $dbw );
	$dbw->commit();
	
	wfWaitForSlaves( 1 );
	
	if ($id)
		print "--Converted to ID $id\n";
}
print "Conversion done. Converted $conversionCount user records.\n";

function convertOptionBatch( $res, $dbw ) {
	$id = null;
	while ($row = $dbw->fetchObject( $res ) ) {
		global $conversionCount;
		$conversionCount++;
		
		$u = User::newFromRow( $row );
		
		$u->saveSettings();
		$id = $row->user_id;
	}
	
	return $id;
}
