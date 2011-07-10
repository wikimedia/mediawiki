<?php
/**
 * Maintenance script to clean up after incomplete user renames
 * Sometimes user edits are left lying around under the old name,
 * check for that and assign them to the new username
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along
 * with this program; if not, write to the Free Software Foundation, Inc.,
 * 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA.
 * http://www.gnu.org/copyleft/gpl.html
 *
 * @ingroup Maintenance
 * @author Ariel Glenn <ariel@wikimedia.orf>
 */

require_once( dirname( __FILE__ ) . '/Maintenance.php' );

class RenameUserCleanup extends Maintenance {
	const BATCH_SIZE = 1000;

	public function __construct() {
		parent::__construct();
		$this->mDescription = "Maintenance script to finish incomplete rename user, in particular to reassign edits that were missed";
		$this->addOption( 'olduser', 'Old user name', true, true );
		$this->addOption( 'newuser', 'New user name', true, true );
	}

	public function execute() {
		$this->output( "Rename User Cleanup starting...\n\n" );
		$olduser = User::newFromName( $this->getOption( 'olduser' ) );
		$newuser = User::newFromName( $this->getOption( 'newuser' ) );
		if ( !$newuser->getId() ) {
			$this->error( "No such user: " . $this->getOption( 'newuser' ), true );
			exit(1);
		}
		if ($olduser->getId() ) {
			print( "WARNING!!: Old user still exists: " . $this->getOption( 'olduser' ) . "\n");
			print("proceed anyways? We'll only re-attribute edits that have the new user uid (or 0) and the old user name.  [N/y]  ");
			$stdin = fopen ("php://stdin","rt");
			$line = fgets($stdin);
			fclose($stdin);
			if ( $line[0] != "Y" && $line[0] != "y" ) {
				print("Exiting at user's request\n");
				exit(0);
			}
		}

		$dbr = wfGetDB( DB_SLAVE );
		$result = $dbr->select( 'logging', '*',
			array( 'log_type' => 'renameuser',
				'log_action'    => 'renameuser',
				'log_title'     => $olduser->getName(),
				'log_params'    => $newuser->getName()
			     ),
			__METHOD__
		);
		if (! $result ) {
			print("No log entry found for a rename of ".$olduser->getName()." to ".$newuser->getName().", giving up\n");
			exit(1);
		}
		foreach ( $result as $row ) {
			print("Found log entry of the rename: ".$olduser->getName()." to ".$newuser->getName()." on $row->log_timestamp\n");
		}
		if ($result->numRows() > 1) {
			print("More than one rename entry found in the log, not sure what to do. Continue anyways? [N/y]  ");
			$stdin = fopen ("php://stdin","rt");
			$line = fgets($stdin);
			fclose($stdin);
			if ( $line[0] != "Y" && $line[0] != "y" ) {
				print("Exiting at user's request\n");
				exit(1);
			}
		}
		$dbw = wfGetDB( DB_MASTER );

		$this->updateTable('revision', 'rev_user_text', 'rev_user', 'rev_timestamp', $olduser, $newuser, $dbw);
		$this->updateTable('archive', 'ar_user_text', 'ar_user', 'ar_timestamp',  $olduser, $newuser, $dbw);
		$this->updateTable('logging', 'log_user_text', 'log_user', 'log_timestamp', $olduser, $newuser, $dbw);
		$this->updateTable('image', 'img_user_text', 'img_user', 'img_timestamp', $olduser, $newuser, $dbw);
		$this->updateTable('oldimage', 'oi_user_text', 'oi_user', 'oi_timestamp', $olduser, $newuser, $dbw);
# FIXME: updateTable('filearchive', 'fa_user_text','fa_user', 'fa_timestamp', $olduser, $newuser, $dbw);  (not indexed yet)
		print "Done!\n";
		exit(0);
	}

	public function updateTable($table,$usernamefield,$useridfield,$timestampfield,$olduser,$newuser,$dbw) {
		$doUid = 0;

		$contribs = $dbw->selectField( $table, 'count(*)', 
			array( $usernamefield => $olduser->getName(), $useridfield => $newuser->getId() ), __METHOD__ );
		if ($contribs == 0) {
			$contribs = $dbw->selectField( $table, 'count(*)', 
				array( $usernamefield => $olduser->getName(), $useridfield => 0 ), __METHOD__ );
			if ($contribs > 0) {
				print("Found $contribs edits to be re-attributed from table $table but the uid present is 0 (should be ".$newuser->getId().")\n");
				print("If you proceed, the uid field will be set to that of the new user name (i.e. ".$newuser->getId().") in these rows.\n");
				$doUid = 1;
			}
			else {
				print("No edits to be re-attributed from table $table\n");
				return(0);
			}
		}
		else {
			print("total number of edits to be re-attributed from table $table: $contribs\n");
		}
		print("proceed? [N/y]  ");
		$stdin = fopen ("php://stdin","rt");
		$line = fgets($stdin);
		fclose($stdin);
		if ( $line[0] != "Y" && $line[0] != "y" ) {
			print("skipping at user's request\n");
			return(0);
		}
		$selectConds = array( $usernamefield => $olduser->getName() );
		$updateFields = array( $usernamefield => $newuser->getName() );
		$updateConds = array( $usernamefield => $olduser->getName() );

		$extraConds = array( $useridfield => $newuser->getId() );
		$extraCondsNoUid = array( $useridfield => 0 );
		# uid in rows is set properly, use as cond to find rows, don't bother to update it
		if (! $doUid) {
			$selectConds = array_merge( $selectConds, $extraConds );
			$updateConds = 	array_merge( $updateConds, $extraConds );
		}
		# uid in edit rows is 0, we will set it and we will only update rows with 0 uid and the old user name
		else {
			$selectConds = array_merge( $selectConds, $extraCondsNoUid );
			$updateConds = 	array_merge( $updateConds, $extraCondsNoUid );
			$updateFields = array_merge( $updateFields, $extraConds );
		}

		while ($contribs > 0) {
			print("doing batch of up to approximately ".self::BATCH_SIZE."\n");
			print("do this batch? [N/y]  ");
			$stdin = fopen ("php://stdin","rt");
			$line = fgets($stdin);
			fclose($stdin);
			if ( $line[0] != "Y" && $line[0] != "y" ) {
				print("skipping at user's request\n");
				return(0);
			}
			$dbw->begin();
			$result = $dbw->select( $table, $timestampfield, $selectConds , __METHOD__, 
				array( 'ORDER BY' => $timestampfield.' DESC', 'LIMIT' => self::BATCH_SIZE ) );
			if (! $result) {
				print("There were rows for updating but now they are gone. Skipping.\n");
				$db->rollback();
				return(0);
			}
			$result->seek($result->numRows() -1 );
			$row = $result->fetchObject();
			$timestamp = $row->$timestampfield;
			$updateCondsWithTime = array_merge( $updateConds, array ("$timestampfield >= $timestamp") );
			$success = $dbw->update( $table, $updateFields, $updateCondsWithTime, __METHOD__ );
			if ($success) {
				$rowsDone = $dbw->affectedRows();
				$dbw->commit();
			}
			else {
				print("problem with the update, rolling back and exiting\n");
				$db->rollback();
				exit(1);
			}
			$contribs = User::edits( $olduser->getId() ); 
			$contribs = $dbw->selectField( $table, 'count(*)', $selectConds, __METHOD__ );
			print("updated $rowsDone edits; $contribs edits remaining to be re-attributed\n");
		}
		return(0);
	}

}

$maintClass = "RenameUserCleanup";
require_once( RUN_MAINTENANCE_IF_MAIN );
