<?php
/*
A class to upgrade the database schema automagically
for MediaWIki itself, as well as for extensions
(c) 2006 by Magnus Manske
Released under GPL

USAGE:

First, copy AdminSettings.sample to AdminSettings.php and set the correct values there.
Then, set
	$wgAllowUpgrader = true ;
in LocalSettings.php


In your extension, add something like:
$wgUpgrader->setUpgradeFunction ( "MyExtension" , "MyExtensionUpgradeFunction" ) ;

function MyExtensionUpgradeFunction ( &$upgrader ) {

	# Does this installation need to be upgraded to V1.2.3.4?
	if ( $upgrader->needsUpgrade ( "MyExtension" , "1.2.3.4" ) ) {
		$upgrader->startTransaction() ;
		# Do something using the database
		# $upgrader->dba
		$upgrader->endTransaction() ;
	}
	
	# Repeat this for every upgradable version, lowest version first, highest last
	# if ( $upgrader->needsUpgrade ( "MyExtension" , "1.2.3.5" ) ) { ... }
	
}

The upgrade function for MediaWiki itself is at the end of Setup.php

*/

class Upgrader {
	
	var $data = array () ;
	var $upgraders = array () ;
	var $dba = NULL ; # Database, administration mode
	var $data_loaded = false ;
	var $db_transaction = false ;
	var $dbr = NULL ;
	var $dba = NULL ;
	
	function loadVersionInfoFromDatabase () {
		global $wgAllowUpgrader ;
		if ( !$wgAllowUpgrader ) return ;
		if ( $this->data_loaded ) return ;
		
		$fname = "Upgrader::loadVersionInfoFromDatabase" ;
		$this->dbr =& wfGetDB( DB_SLAVE );
		
		# Upgrade to upgrader :-)
		if ( !$this->dbr->tableExists ( "softwareversions" ) ) {
			$this->prepareAdminDB () ;
			$table = $this->dba->tableName( "softwareversions" );
			$sql = "CREATE TABLE {$table} (
				`sv_part` VARCHAR( 128 ) NOT NULL ,
				`sv_version` VARCHAR( 128 ) NOT NULL ,
				PRIMARY KEY ( `sv_part` )
			);" ;
			$this->dba->query ( $sql , $fname."-1" ) ;
			
			# At this point, we wait until the table creation
			# has progressed to the $dbr slave
			while ( !$this->dbr->tableExists ( "softwareversions" ) ) {
				sleep ( 1 ) ;
			}
		}
		
		$res = $this->dbr->select (
			"softwareversions" ,
			"*" ,
			array() ,
			$fname."-2" ) ;
		
		# Read existing versions
		while ( $o = $this->dbr->fetchObject( $res ) ) {
			$this->data[strtolower($o->sv_part)] = $this->makeVersionArray ( $o->sv_version ) ;
		}
		$this->dbr->freeResult( $res );
		
		$this->data_loaded = true ;
	}
	
	/**
	 * Adds an upgrade function to the list, to be evoked by run() later
	 */
	function setUpgradeFunction ( $part , $function ) {
		$this->upgraders[$part] = $function ;
	}
	
	/**
	 * Evokes the upgrade functions
	 */
	function run () {
		global $wgAllowUpgrader ;
		if ( !$wgAllowUpgrader ) return ;

		# Running through the upgrading functions
		foreach ( $this->upgraders AS $part => $function ) {
			$function ( $this ) ;
		}
	}
	
	/**
	 * Tells the caller if the given version is greater than  the current one
	 @return bool TRUE if $part needs updating, FALSE otherwise
	 */
	function needsUpgrade ( $part , $version ) {
		$this->loadVersionInfoFromDatabase () ;
		$currentversion = $this->getCurrentVersion ( $part ) ;
		$this->lastcurrentversion = $currentversion ;
		$this->lastpart = $part ;
		$this->lastnewversion = $version ;
		return $this->compareVersions ( $currentversion , $version ) ;
	}
	
	/**
	 * Initializes the transaction to update the database
	 */
	function startTransaction () {
		if ( $this->db_transaction ) # Already transaction in progress
			return ;

		# Open admin-access db
		$this->prepareAdminDB() ;
		$this->dba->begin() ; # Begin transaction
		$this->db_transaction = true ;
	}
	
	/**
	 * Finalizes the transaction to update the database
	 * Does a last paranoia check
	 */
	function endTransaction () {
		if ( !$this->db_transaction )
			return ;
		if ( $this->dba == NULL )
			return ;

		$fname = "Upgrader::endTransaction" ;
		
		if ( $this->stillCurrentVersion() ) {
			# Version has not changed since initial lookup; so we're the only one updating, hopefully

			# Set new version string
			$v = $this->makeVersionArray ( $this->lastnewversion ) ;
			$this->dba->delete (
				"softwareversions",
				array ( "sv_part" => $this->lastpart ) ,
				$fname . "-DELETE"
			) ;
			$this->dba->insert (
				"softwareversions",
				array (
					"sv_part" => strtolower ( $this->lastpart ) ,
					"sv_version" => implode ( "." , $v ) ,
				) ,
				$fname . "-INSERT"
			) ;
			$this->data[strtolower($this->lastpart)] = $v ;

			$this->dba->commit($fname) ;
			print $this->lastpart . " has been upgraded to version " . implode ( "." , $v ) . "<br/>\n" ;
		} else {
			# Version has changes, so someone probably did the update already
			$this->dba->rollback($fname);
		}
		$this->db_transaction = false ;
	}

	/**
	 * Checks if the current version in the write table is still the same
	 * Multiple upgrading paranoia
	 @return bool
	 */
	function stillCurrentVersion () {
		# Check if this is really, *really* the correct version in the writing DB
		# Using new database to go around the transaction
		$dbw =& wfGetDB( DB_MASTER );
		$res = $dbw->select (
			"softwareversions" ,
			"*" ,
			array ( "sv_part" => $this->lastpart ) ,
			"Upgrader::setLock" ) ;
		$v = "0.0.0.0" ;
		if ( $res != NULL ) {
			if ( $o = $dbw->fetchObject( $res ) ) {
				$dbw->freeResult( $res );
				$v = $o->sv_version ;
				}
		}
		
		# Are they unequal?
		if ( $this->compareVersions ( $v , $this->lastcurrentversion ) OR
			 $this->compareVersions ( $this->lastcurrentversion , $v ) ) {
			$this->error ( "Something funny happened on the way to the moon." ) ;
			return false ;
		}
		return true ;
	}
	
	/**
	 * Open admin access to database
	 */
	function prepareAdminDB () {
		if ( $this->dba != NULL )
			return ;
		
		@include_once ( "AdminSettings.php" ) ;
		if ( !isset ( $wgDBadminuser ) ) {
			$this->error ( "You'll need to set up AdminSettings.php for automatic upgrades!" ) ;
			exit ( 1 ) ;
		}
		
		global $wgDBserver , $wgDBname ;
		$this->dba = new Database ( $wgDBserver , $wgDBadminuser , $wgDBadminpassword , $wgDBname ) ;
	}

	/**
	 * Get the current version of a part
	 @return array
	 */
	function getCurrentVersion ( $part ) {
		$part = strtolower ( $part ) ;
		if ( isset ( $this->data[$part] ) ) {
			return $this->data[$part] ;
		} else {
			# Not set yet, returning version 0.0.0.0
			return $this->makeVersionArray ( "0.0.0.0" ) ;
		}
	}
	
	/**
	 * Compares versions, style 1.2.3.4
	 * Takes arrays or strings (which will be automatically converted)
	 @return bool TRUE if $v1 < $v2
	 */
	function compareVersions ( $v1 , $v2 ) {
		if ( !is_array ( $v1 ) )
			$v1 = $this->makeVersionArray ( $v1 ) ;
		if ( !is_array ( $v2 ) )
			$v2 = $this->makeVersionArray ( $v2 ) ;
			
		# Compare versions
		while ( count ( $v1 ) > 0 ) {
			$a1 = array_shift ( $v1 ) ;
			$a2 = array_shift ( $v2 ) ;
			if ( $a1 < $a2 )
				return true ;
			if ( $a1 > $a2 )
				return false ;
		}
		# Equal versions
		return false ;
	}
	
	/**
	 * Converts a version string ("1.2.3.4") to an array
	 * Ensures exactly four elements (fills up with "0" if necessary)
	 @return array like [ "1" , "2" , "3" , "4" ]
	 */
	function makeVersionArray ( $v ) {
		# Convert to array
		if ( !is_array ( $v ) ) {
			$v = explode ( "." , $v ) ;
			$ret = array () ;
			foreach ( $v AS $x ) {
				$x = trim ( $x ) ;
				if ( !is_numeric ( $x ) )
					continue ;
				$ret[] = $x ;
			}
		} else {
			$ret = $v ;
		}
		
		# Chop/stretch to length four
		while ( count ( $ret ) > 4 )
			array_pop ( $ret ) ;
		while ( count ( $ret ) < 4 )
			$ret[] = "0" ;
		return $ret ;
	}
	
	/**
	 * Print an error and die a horrible death!
	 */
	function error ( $e ) {
		print $e . "<br/>\n" ;
		exit ( 1 ) ;
	}
	
} # End of class "Upgrader"

$wgUpgrader = new Upgrader ;


?>