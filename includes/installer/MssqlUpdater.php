<?php
/**
 * Microsoft SQL Server-specific installer.
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
 * @ingroup Deployment
 */

/**
 * Class for setting up the MediaWiki database using Microsoft SQL Server.
 *
 * @ingroup Deployment
 * @since 1.23
 */

class MssqlUpdater extends DatabaseUpdater {

	/**
	 * @var DatabaseMssql
	 */
	protected $db;

	protected function getCoreUpdateList() {
		return array(
			// 1.23
			array( 'addField', 'mwuser', 'user_password_expires', 'patch-user_password_expires.sql' ),

			// 1.24
			array( 'addField', 'page', 'page_lang', 'patch-page-page_lang.sql'),

			// 1.25
			array( 'dropTable', 'hitcounter' ),
			array( 'dropField', 'site_stats', 'ss_total_views', 'patch-drop-ss_total_views.sql' ),
			array( 'dropField', 'page', 'page_counter', 'patch-drop-page_counter.sql' ),
			// Constraint updates
			array( 'updateConstraints', 'category_types', 'categorylinks', 'cl_type' ),
			array( 'updateConstraints', 'major_mime', 'filearchive', 'fa_major_mime' ),
			array( 'updateConstraints', 'media_type', 'filearchive', 'fa_media_type' ),
			array( 'updateConstraints', 'major_mime', 'oldimage', 'oi_major_mime' ),
			array( 'updateConstraints', 'media_type', 'oldimage', 'oi_media_type' ),
			array( 'updateConstraints', 'major_mime', 'image', 'img_major_mime' ),
			array( 'updateConstraints', 'media_type', 'image', 'img_media_type' ),
			array( 'updateConstraints', 'media_type', 'uploadstash', 'us_media_type' ),
			// END: Constraint updates

			array( 'modifyField', 'image', 'img_major_mime',
				'patch-img_major_mime-chemical.sql' ),
			array( 'modifyField', 'oldimage', 'oi_major_mime',
				'patch-oi_major_mime-chemical.sql' ),
			array( 'modifyField', 'filearchive', 'fa_major_mime',
				'patch-fa_major_mime-chemical.sql' ),
		);
	}

	/**
	 * Drops unnamed and creates named constraints following the pattern
	 * <column>_ckc
	 *
	 * @param string $constraintType
	 * @param string $table Name of the table to which the field belongs
	 * @param string $field Name of the field to modify
	 * @return bool False if patch is skipped.
	 */
	protected function updateConstraints( $constraintType, $table, $field ) {
		global $wgDBname, $wgDBmwschema;

		if ( !$this->doTable( $table ) ) {
			return true;
		}

		$this->output( "...updating constraints on [$table].[$field] ..." );
		$updateKey = "$field-$constraintType-ck";
		if ( !$this->db->tableExists( $table, __METHOD__ ) ) {
			$this->output( "...$table table does not exist, skipping modify field patch.\n" );
			return true;
		} elseif ( !$this->db->fieldExists( $table, $field, __METHOD__ ) ) {
			$this->output( "...$field field does not exist in $table table, " .
				"skipping modify field patch.\n" );
			return true;
		} elseif ( $this->updateRowExists( $updateKey ) ) {
			$this->output( "...$field in table $table already patched.\n" );
			return true;
		}

		# After all checks passed, start the update
		$this->insertUpdateRow( $updateKey );
		$path = 'named_constraints.sql';
		$constraintMap = array(
			'category_types' =>
				"($field in('page', 'subcat', 'file'))",
			'major_mime'     =>
				"($field in('unknown', 'application', 'audio', 'image', 'text', 'video'," .
				" 'message', 'model', 'multipart'))",
			'media_type'     =>
				"($field in('UNKNOWN', 'BITMAP', 'DRAWING', 'AUDIO', 'VIDEO', 'MULTIMEDIA'," .
				"'OFFICE', 'TEXT', 'EXECUTABLE', 'ARCHIVE'))"
		);
		$constraint = $constraintMap[$constraintType];

		# and hack-in those variables that should be replaced
		# in our template file right now
		$this->db->setSchemaVars( array(
			'tableName'       => $table,
			'fieldName'       => $field,
			'checkConstraint' => $constraint,
			'wgDBname'        => $wgDBname,
			'wgDBmwschema'    => $wgDBmwschema,
		) );

		# Full path from file name
		$path = $this->db->patchPath( $path );

		# No need for a cursor allowing result-iteration; just apply a patch
		# store old value for re-setting later
		$wasScrollable = $this->db->scrollableCursor( false );

		# Apply patch
		$this->db->sourceFile( $path );

		# Reset DB instance to have original state
		$this->db->setSchemaVars( false );
		$this->db->scrollableCursor( $wasScrollable );

		$this->output( "done.\n" );

		return true;
	}
}
