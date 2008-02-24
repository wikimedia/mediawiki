<?php
/**
 * Maintenance script to fix the page restrictions which were added using an
 * older version of MediaWiki.
 * 
 * Background
 *
 * Before page_restrictions table was introduced in version 1.10
 * (r19095–r19703), page protection was handled by modifying the value of 
 * page_restrictions field of the page table. Initially, this field either was
 * left empty (ie page was not protected) or filled with the value "sysop" or
 * "autoconfirmed". Later, it was decided that edit protection and move 
 * protection should be handled separately, so again, either the field was
 * left empty, or filled with something like "edit=x:move=y" where x and y were
 * either "sysop" or "autoconfirmed" (without quotes) themselves. Often,
 * the order of the two parts were reversed (ie "move=x:edit=y").
 *
 * When page_restrictions table was introduced, the code was touched in a way
 * that if a page's protection level was changed, the old values in the page
 * table were wiped out. The problem is, this part of code only runs when the
 * protection level of the page *is changed*, and unfortunately, if an admin
 * tries to unprotect the page, the code doesn't *sense* a change in protection
 * level (because it compares the new value -- ie, nothing -- against the value
 * of the page_restrictions table -- which is again nothing. This is reported on
 * bugzilla as bug 13132.
 * 
 * This maintenance script deals with the problem, by moving the protection data
 * from page table to page_restrictions table.
 *
 * @addtogroup Maintenance
 * @author Hojjat (aka Huji) <huji.huji@gmail.com>
 */

require_once( 'commandLine.inc' );

$fname = 'fixPageRestrictions.php';

$dbw = wfGetDB( DB_MASTER );

// Get user IDs which need fixing
$res = $dbw->select( 'page', 'page_id, page_restrictions', 'page_restrictions <>\'\'', $fname );

while ( $row = $dbw->fetchObject( $res ) ) {
	$id = $row->page_id;
	$old_restrictions = $row->page_restrictions;
	$edit_restriction = '';
	$move_restriction = '';
	if ( strpos( $old_restrictions, '=' ) !== false ) {
		if ( strpos( $old_restrictions, ':' ) !== false ) {
			# either "edit=x:move=y" or "move=x:edit=y"
			if ( strpos( $old_restrictions, 'edit' ) == 0 ){
				# "edit=x:move=y"
				$edit_restriction = substr( $old_restrictions, 5, strlen( $old_restrictions) - strpos( $old_restrictions, ':') - 6);
				$move_restriction = substr( $old_restrictions, 5, strlen( $old_restrictions) - strpos( $old_restrictions, ':') - 6);
			} else {
				# "move=x:edit=y"
				$move_restriction = substr( $old_restrictions, 5, strlen( $old_restrictions) - strpos( $old_restrictions, ':') - 6);
				$edit_restriction = substr( $old_restrictions, 5, strlen( $old_restrictions) - strpos( $old_restrictions, ':') - 6);
			}
		} else {
			# either "edit=x" or "move=x"
			if ( strpos( $old_restrictions, 'edit') !== false ) {
				$edit_restriction = substr( $old_restrictions, 5);
			} else {
				$move_restriction = substr( $old_restrictions, 5);
			}
		}
	} else {
		# either "sysop" or "autoconfirmed" -- or an unexpected value
		if ( $old_restrictions == 'sysop' ) {
			$edit_restriction = 'sysop';
			$move_restriction = 'sysop';
		} elseif ( $old_restrictions == 'autoconfirmed' ) {
			$edit_restriction = 'autoconfirmed';
			$move_restriction = 'autoconfirmed';
		} else {
			#Shouldn't happen
			print "WARNING: I found a record with old restriction set to '$old_restrictions' and I can't handle it!\n";
		}
	}
	
	if ( $edit_restriction <> '' ) {
		$dbw->replace( 'page_restrictions',
			array(array('pr_page', 'pr_type')),
			array( 'pr_page' => $id, 'pr_type' => 'edit'
				, 'pr_level' => $edit_restriction, 'pr_cascade' => 0
				, 'pr_expiry' => 'infinity' ),
			$fname );
	}
	if ( $move_restriction <> '' ) {
		$dbw->replace( 'page_restrictions',
			array(array('pr_page', 'pr_type')),
			array( 'pr_page' => $id, 'pr_type' => 'move'
				, 'pr_level' => $move_restriction, 'pr_cascade' => 0
				, 'pr_expiry' => 'infinity' ),
			$fname );
	}
	
	$dbw->update( 'page', array( 'page_restrictions' => ''), array( 'page_id' => $id), $fname);
	
	print "Fixed restrictions for page_id=$id\n   from '$old_restrictions'\n   to 'edit=$edit_restriction, move=$move_restriction'\n";
}
print "\n";