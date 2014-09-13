<?php
/**
 * Oracle-specific updater.
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
 * Class for handling updates to Oracle databases.
 *
 * @ingroup Deployment
 * @since 1.17
 */
class OracleUpdater extends DatabaseUpdater {

	/**
	 * Handle to the database subclass
	 *
	 * @var DatabaseOracle
	 */
	protected $db;

	protected function getCoreUpdateList() {
		return array(
			array( 'disableContentHandlerUseDB' ),

			// 1.17
			array( 'doNamespaceDefaults' ),
			array( 'doFKRenameDeferr' ),
			array( 'doFunctions17' ),
			array( 'doSchemaUpgrade17' ),
			array( 'doInsertPage0' ),
			array( 'doRemoveNotNullEmptyDefaults' ),
			array( 'addTable', 'user_former_groups', 'patch-user_former_groups.sql' ),

			//1.18
			array( 'addIndex', 'user', 'i02', 'patch-user_email_index.sql' ),
			array( 'modifyField', 'user_properties', 'up_property', 'patch-up_property.sql' ),
			array( 'addTable', 'uploadstash', 'patch-uploadstash.sql' ),
			array( 'doRecentchangesFK2Cascade' ),

			//1.19
			array( 'addIndex', 'logging', 'i05', 'patch-logging_type_action_index.sql' ),
			array( 'addField', 'revision', 'rev_sha1', 'patch-rev_sha1_field.sql' ),
			array( 'addField', 'archive', 'ar_sha1', 'patch-ar_sha1_field.sql' ),
			array( 'doRemoveNotNullEmptyDefaults2' ),
			array( 'addIndex', 'page', 'i03', 'patch-page_redirect_namespace_len.sql' ),
			array( 'addField', 'uploadstash', 'us_chunk_inx', 'patch-us_chunk_inx_field.sql' ),
			array( 'addField', 'job', 'job_timestamp', 'patch-job_timestamp_field.sql' ),
			array( 'addIndex', 'job', 'i02', 'patch-job_timestamp_index.sql' ),
			array( 'doPageRestrictionsPKUKFix' ),

			//1.20
			array( 'addIndex', 'ipblocks', 'i05', 'patch-ipblocks_i05_index.sql' ),
			array( 'addIndex', 'revision', 'i05', 'patch-revision_i05_index.sql' ),
			array( 'dropField', 'category', 'cat_hidden', 'patch-cat_hidden.sql' ),

			//1.21
			array( 'addField', 'revision', 'rev_content_format',
				'patch-revision-rev_content_format.sql' ),
			array( 'addField', 'revision', 'rev_content_model',
				'patch-revision-rev_content_model.sql' ),
			array( 'addField', 'archive', 'ar_content_format', 'patch-archive-ar_content_format.sql' ),
			array( 'addField', 'archive', 'ar_content_model', 'patch-archive-ar_content_model.sql' ),
			array( 'addField', 'archive', 'ar_id', 'patch-archive-ar_id.sql' ),
			array( 'addField', 'externallinks', 'el_id', 'patch-externallinks-el_id.sql' ),
			array( 'addField', 'page', 'page_content_model', 'patch-page-page_content_model.sql' ),
			array( 'enableContentHandlerUseDB' ),
			array( 'dropField', 'site_stats', 'ss_admins', 'patch-ss_admins.sql' ),
			array( 'dropField', 'recentchanges', 'rc_moved_to_title', 'patch-rc_moved.sql' ),
			array( 'addTable', 'sites', 'patch-sites.sql' ),
			array( 'addField', 'filearchive', 'fa_sha1', 'patch-fa_sha1.sql' ),
			array( 'addField', 'job', 'job_token', 'patch-job_token.sql' ),
			array( 'addField', 'job', 'job_attempts', 'patch-job_attempts.sql' ),
			array( 'addField', 'uploadstash', 'us_props', 'patch-uploadstash-us_props.sql' ),
			array( 'modifyField', 'user_groups', 'ug_group', 'patch-ug_group-length-increase-255.sql' ),
			array( 'modifyField', 'user_former_groups', 'ufg_group',
				'patch-ufg_group-length-increase-255.sql' ),

			//1.23
			array( 'addIndex', 'logging', 'i06', 'patch-logging_user_text_type_time_index.sql' ),
			array( 'addIndex', 'logging', 'i07', 'patch-logging_user_text_time_index.sql' ),
			array( 'addField', 'user', 'user_password_expires', 'patch-user_password_expire.sql' ),
			array( 'addField', 'page', 'page_links_updated', 'patch-page_links_updated.sql' ),
			array( 'addField', 'recentchanges', 'rc_source', 'patch-rc_source.sql' ),

			// KEEP THIS AT THE BOTTOM!!
			array( 'doRebuildDuplicateFunction' ),

		);
	}

	/**
	 * MySQL uses datatype defaults for NULL inserted into NOT NULL fields
	 * In namespace case that results into insert of 0 which is default namespace
	 * Oracle inserts NULL, so namespace fields should have a default value
	 */
	protected function doNamespaceDefaults() {
		$meta = $this->db->fieldInfo( 'page', 'page_namespace' );
		if ( $meta->defaultValue() != null ) {
			return;
		}

		$this->applyPatch(
			'patch_namespace_defaults.sql',
			false,
			'Altering namespace fields with default value'
		);
	}

	/**
	 * Uniform FK names + deferrable state
	 */
	protected function doFKRenameDeferr() {
		$meta = $this->db->query( '
			SELECT COUNT(*) cnt
			FROM user_constraints
			WHERE constraint_type = \'R\' AND deferrable = \'DEFERRABLE\''
		);
		$row = $meta->fetchRow();
		if ( $row && $row['cnt'] > 0 ) {
			return;
		}

		$this->applyPatch( 'patch_fk_rename_deferred.sql', false, "Altering foreign keys ... " );
	}

	/**
	 * Recreate functions to 17 schema layout
	 */
	protected function doFunctions17() {
		$this->applyPatch( 'patch_create_17_functions.sql', false, "Recreating functions" );
	}

	/**
	 * Schema upgrade 16->17
	 * there are no incremental patches prior to this
	 */
	protected function doSchemaUpgrade17() {
		// check if iwlinks table exists which was added in 1.17
		if ( $this->db->tableExists( 'iwlinks' ) ) {
			return;
		}
		$this->applyPatch( 'patch_16_17_schema_changes.sql', false, "Updating schema to 17" );
	}

	/**
	 * Insert page (page_id = 0) to prevent FK constraint violation
	 */
	protected function doInsertPage0() {
		$this->output( "Inserting page 0 if missing ... " );
		$row = array(
			'page_id' => 0,
			'page_namespace' => 0,
			'page_title' => ' ',
			'page_counter' => 0,
			'page_is_redirect' => 0,
			'page_is_new' => 0,
			'page_random' => 0,
			'page_touched' => $this->db->timestamp(),
			'page_latest' => 0,
			'page_len' => 0
		);
		$this->db->insert( 'page', $row, 'OracleUpdater:doInserPage0', array( 'IGNORE' ) );
		$this->output( "ok\n" );
	}

	/**
	 * Remove DEFAULT '' NOT NULL constraints from fields as '' is internally
	 * converted to NULL in Oracle
	 */
	protected function doRemoveNotNullEmptyDefaults() {
		$meta = $this->db->fieldInfo( 'categorylinks', 'cl_sortkey_prefix' );
		if ( $meta->isNullable() ) {
			return;
		}
		$this->applyPatch(
			'patch_remove_not_null_empty_defs.sql',
			false,
			'Removing not null empty constraints'
		);
	}

	protected function doRemoveNotNullEmptyDefaults2() {
		$meta = $this->db->fieldInfo( 'ipblocks', 'ipb_by_text' );
		if ( $meta->isNullable() ) {
			return;
		}
		$this->applyPatch(
			'patch_remove_not_null_empty_defs2.sql',
			false,
			'Removing not null empty constraints'
		);
	}

	/**
	 * Removed forcing of invalid state on recentchanges_fk2.
	 * cascading taken in account in the deleting function
	 */
	protected function doRecentchangesFK2Cascade() {
		$meta = $this->db->query( 'SELECT 1 FROM all_constraints WHERE owner = \'' .
			strtoupper( $this->db->getDBname() ) .
			'\' AND constraint_name = \'' .
			$this->db->tablePrefix() .
			'RECENTCHANGES_FK2\' AND delete_rule = \'CASCADE\''
		);
		$row = $meta->fetchRow();
		if ( $row ) {
			return;
		}

		$this->applyPatch( 'patch_recentchanges_fk2_cascade.sql', false, "Altering RECENTCHANGES_FK2" );
	}

	/**
	 * Fixed wrong PK, UK definition
	 */
	protected function doPageRestrictionsPKUKFix() {
		$this->output( "Altering PAGE_RESTRICTIONS keys ... " );

		$meta = $this->db->query( 'SELECT column_name FROM all_cons_columns WHERE owner = \'' .
			strtoupper( $this->db->getDBname() ) .
			'\' AND constraint_name = \'' .
			$this->db->tablePrefix() .
			'PAGE_RESTRICTIONS_PK\' AND rownum = 1'
		);
		$row = $meta->fetchRow();
		if ( $row['column_name'] == 'PR_ID' ) {
			$this->output( "seems to be up to date.\n" );

			return;
		}

		$this->applyPatch( 'patch-page_restrictions_pkuk_fix.sql', false );
		$this->output( "ok\n" );
	}

	/**
	 * rebuilding of the function that duplicates tables for tests
	 */
	protected function doRebuildDuplicateFunction() {
		$this->applyPatch( 'patch_rebuild_dupfunc.sql', false, "Rebuilding duplicate function" );
	}

	/**
	 * Overload: after this action field info table has to be rebuilt
	 *
	 * @param $what array
	 */
	public function doUpdates( $what = array( 'core', 'extensions', 'purge', 'stats' ) ) {
		parent::doUpdates( $what );

		$this->db->query( 'BEGIN fill_wiki_info; END;' );
	}

	/**
	 * Overload: because of the DDL_MODE tablename escaping is a bit dodgy
	 */
	public function purgeCache() {
		# We can't guarantee that the user will be able to use TRUNCATE,
		# but we know that DELETE is available to us
		$this->output( "Purging caches..." );
		$this->db->delete( '/*Q*/' . $this->db->tableName( 'objectcache' ), '*', __METHOD__ );
		$this->output( "done.\n" );
	}
}
