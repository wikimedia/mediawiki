<?php
/**
 * Migrate revision hidden with the Oversight extension to revdel.
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
 * @file
 * @ingroup Maintenance
 */

require_once( dirname( __FILE__ ) . '/Maintenance.php' );

/**
 * Maintenance script that re-assigns users from an old group to a new one.
 *
 * @ingroup Maintenance
 */
class MigrateOversightRevisions extends Maintenance {
	public function __construct() {
		parent::__construct();
		$this->mDescription = "Migrate revision hidden with the Oversight extension to revdel.";
		$this->setBatchSize( 200 );
	}

	public function execute() {
		$this->output( "Migrating oversighted revisions to suppressed revisions. This will not remove the oversighted versions and will not add anything to recentchanges.\n" );
		$count = 0;
		$dbw = wfGetDB( DB_MASTER );
		$hiddenRows = $dbw->select( 'hidden', '*' );
		foreach ( $hiddenRows as $hiddenRow ) {
			$insertData = array();

			$fieldPrefix = 'ar_';
			$tableName = 'archive';	// Below we'll check to see if the page exists. If it does, we'll use revision instead.

			$pageExistsQuery = $dbw->select(
				'page',
				'page_id',
				array( 'page_id' => $hiddenRow->hidden_page )
			);

			foreach ( $pageExistsQuery as $pageRow ) {
				// The page exists, so we use the revision table.
				$fieldPrefix = 'rev_';
				$tableName = 'revision';
			}

			$revIdFieldName = ( $tableName == 'archive' ? 'ar_' : '' ) . 'rev_id';
			$pageIdFieldName = $fieldPrefix . 'page' . ( $tableName == 'archive' ? '_id' : '' );

			$insertData[$pageIdFieldName] = $hiddenRow->hidden_page;
			$insertData[$revIdFieldName] = $hiddenRow->hidden_rev_id;
			$insertData[$fieldPrefix . 'text_id'] = $hiddenRow->hidden_text_id;
			$insertData[$fieldPrefix . 'comment'] = $hiddenRow->hidden_comment;
			$insertData[$fieldPrefix . 'user'] = $hiddenRow->hidden_user;
			$insertData[$fieldPrefix . 'user_text'] = $hiddenRow->hidden_user_text;
			$insertData[$fieldPrefix . 'timestamp'] = $hiddenRow->hidden_timestamp;
			$insertData[$fieldPrefix . 'minor_edit'] = $hiddenRow->hidden_minor_edit;
			$insertData[$fieldPrefix . 'deleted'] = 15; // Hide revision text, edit summary, editor's username/IP, even from admins.

			$textRow = $dbw->selectField( 'text', 'old_text', array ( 'old_id' => $hiddenRow->hidden_text_id ) );
			$revText = Revision::getRevisionText( $textRow );

			$insertData[$fieldPrefix . 'len'] = strlen( $revText );
			$insertData[$fieldPrefix . 'sha1'] = Revision::base36Sha1( $revText );

			$insertData[$fieldPrefix . 'parent_id'] = $dbw->selectField(
				$tableName,
				$revIdFieldName,
				array( $pageIdFieldName => $hiddenRow->hidden_page, $revIdFieldName . ' < ' . $hiddenRow->hidden_rev_id ),
				$options = array( 'LIMIT' => 1, 'ORDER BY' => $revIdFieldName )
			);

			if ( $tableName == 'archive' ) {
				$insertData['ar_namespace'] = $hiddenRow->hidden_namespace;
				$insertData['ar_title'] = $hiddenRow->hidden_title;
			}

			$dbw->insert( $tableName, $insertData, __METHOD__ );

			$insertLogData = array();
			$insertLogData['log_type'] = 'suppress';
			$insertLogData['log_action'] = 'revision';
			$insertLogData['log_timestamp'] = $hiddenRow->hidden_on_timestamp;
			$insertLogData['log_user'] = $hiddenRow->hidden_by_user;
			$insertLogData['log_user_text'] = User::newFromId( $hiddenRow->hidden_by_user )->getName();
			$insertLogData['log_namespace'] = $hiddenRow->hidden_namespace;
			$insertLogData['log_title'] = $hiddenRow->hidden_title;
			$insertLogData['log_page'] = $hiddenRow->hidden_page;
			$insertLogData['log_comment'] = $hiddenRow->hidden_reason . " [imported from Oversight]";
			$insertLogData['log_params'] = "revision\n" . $hiddenRow->hidden_rev_id . "\nofield=" . $hiddenRow->hidden_deleted . "\nnfield=15"; //'revision', rev_id, old bits, new bits
			$dbw->insert( 'logging', $insertLogData, __METHOD__ );
			$count++;
		}
		$this->output( "Done! $count oversighted revision(s) are now converted to suppressed revisions.\n" );
	}
}

$maintClass = "MigrateOversightRevisions";
require_once( RUN_MAINTENANCE_IF_MAIN );
