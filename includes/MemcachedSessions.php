<?php
# Copyright (C) 2003 Brion Vibber <brion@pobox.com>
# http://www.mediawiki.org/
# 
# This program is free software; you can redistribute it and/or modify
# it under the terms of the GNU General Public License as published by
# the Free Software Foundation; either version 2 of the License, or 
# (at your option) any later version.
# 
# This program is distributed in the hope that it will be useful,
# but WITHOUT ANY WARRANTY; without even the implied warranty of
# MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
# GNU General Public License for more details.
# 
# You should have received a copy of the GNU General Public License along
# with this program; if not, write to the Free Software Foundation, Inc.,
# 59 Temple Place - Suite 330, Boston, MA 02111-1307, USA.
# http://www.gnu.org/copyleft/gpl.html

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
	if( ! $data ) return "";
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
