<?

/*
	This file gets included if $wgSessionsInMemcache is set in the config.
	It redirects session handling functions to store their data in memcached
	instead of the local filesystem. Depending on circumstances, it may also
	be necessary to change the cookie settings to work across hostnames.
	
	See: http://www.php.net/manual/en/function.session-set-save-handler.php
*/


function memsess_key( $id ) {
	global $wgDBname;
	return "$wgDBname:session:$id";
}

function memsess_open( $save_path, $session_name ) {
	# NOP, $wgMemc should be set up already
	return true;
}

function memsess_close() {
	# NOP
	return true;
}

function memsess_read( $id ) {
	global $wgMemc;
	$data = $wgMemc->get( memsess_key( $id ) );
	if( $data === FALSE ) return "";
	return $data;
}

function memsess_write( $id, $data ) {
	global $wgMemc;
	$wgMemc->set( memsess_key( $id ), $data, 3600 );
	return true;
}

function memsess_destroy( $id ) {
	global $wgMemc;
	$wgMemc->delete( memsess_key( $id ) );
	return true;
}

function memsess_gc( $maxlifetime ) {
	# NOP: Memcached performs garbage collection.
	return true;
}

session_set_save_handler( "memsess_open", "memsess_close", "memsess_read", "memsess_write", "memsess_destroy", "memsess_gc" );

?>
