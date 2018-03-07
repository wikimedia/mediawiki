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
		return [
			[ 'disableContentHandlerUseDB' ],

			// 1.17
			[ 'doNamespaceDefaults' ],
			[ 'doFKRenameDeferr' ],
			[ 'doFunctions17' ],
			[ 'doSchemaUpgrade17' ],
			[ 'doInsertPage0' ],
			[ 'doRemoveNotNullEmptyDefaults' ],
			[ 'addTable', 'user_former_groups', 'patch-user_former_groups.sql' ],

			// 1.18
			[ 'addIndex', 'user', 'i02', 'patch-user_email_index.sql' ],
			[ 'modifyField', 'user_properties', 'up_property', 'patch-up_property.sql' ],
			[ 'addTable', 'uploadstash', 'patch-uploadstash.sql' ],
			[ 'doRecentchangesFK2Cascade' ],

			// 1.19
			[ 'addIndex', 'logging', 'i05', 'patch-logging_type_action_index.sql' ],
			[ 'addField', 'revision', 'rev_sha1', 'patch-rev_sha1_field.sql' ],
			[ 'addField', 'archive', 'ar_sha1', 'patch-ar_sha1_field.sql' ],
			[ 'doRemoveNotNullEmptyDefaults2' ],
			[ 'addIndex', 'page', 'i03', 'patch-page_redirect_namespace_len.sql' ],
			[ 'addField', 'uploadstash', 'us_chunk_inx', 'patch-us_chunk_inx_field.sql' ],
			[ 'addField', 'job', 'job_timestamp', 'patch-job_timestamp_field.sql' ],
			[ 'addIndex', 'job', 'i02', 'patch-job_timestamp_index.sql' ],
			[ 'doPageRestrictionsPKUKFix' ],

			// 1.20
			[ 'addIndex', 'ipblocks', 'i05', 'patch-ipblocks_i05_index.sql' ],
			[ 'addIndex', 'revision', 'i05', 'patch-revision_i05_index.sql' ],
			[ 'dropField', 'category', 'cat_hidden', 'patch-cat_hidden.sql' ],

			// 1.21
			[ 'addField', 'revision', 'rev_content_format',
				'patch-revision-rev_content_format.sql' ],
			[ 'addField', 'revision', 'rev_content_model',
				'patch-revision-rev_content_model.sql' ],
			[ 'addField', 'archive', 'ar_content_format', 'patch-archive-ar_content_format.sql' ],
			[ 'addField', 'archive', 'ar_content_model', 'patch-archive-ar_content_model.sql' ],
			[ 'addField', 'archive', 'ar_id', 'patch-archive-ar_id.sql' ],
			[ 'addField', 'externallinks', 'el_id', 'patch-externallinks-el_id.sql' ],
			[ 'addField', 'page', 'page_content_model', 'patch-page-page_content_model.sql' ],
			[ 'enableContentHandlerUseDB' ],
			[ 'dropField', 'site_stats', 'ss_admins', 'patch-ss_admins.sql' ],
			[ 'dropField', 'recentchanges', 'rc_moved_to_title', 'patch-rc_moved.sql' ],
			[ 'addTable', 'sites', 'patch-sites.sql' ],
			[ 'addField', 'filearchive', 'fa_sha1', 'patch-fa_sha1.sql' ],
			[ 'addField', 'job', 'job_token', 'patch-job_token.sql' ],
			[ 'addField', 'job', 'job_attempts', 'patch-job_attempts.sql' ],
			[ 'addField', 'uploadstash', 'us_props', 'patch-uploadstash-us_props.sql' ],
			[ 'modifyField', 'user_groups', 'ug_group', 'patch-ug_group-length-increase-255.sql' ],
			[ 'modifyField', 'user_former_groups', 'ufg_group',
				'patch-ufg_group-length-increase-255.sql' ],

			// 1.23
			[ 'addIndex', 'logging', 'i06', 'patch-logging_user_text_type_time_index.sql' ],
			[ 'addIndex', 'logging', 'i07', 'patch-logging_user_text_time_index.sql' ],
			[ 'addField', 'user', 'user_password_expires', 'patch-user_password_expire.sql' ],
			[ 'addField', 'page', 'page_links_updated', 'patch-page_links_updated.sql' ],
			[ 'addField', 'recentchanges', 'rc_source', 'patch-rc_source.sql' ],

			// 1.24
			[ 'addField', 'page', 'page_lang', 'patch-page-page_lang.sql' ],

			// 1.25
			[ 'dropTable', 'hitcounter' ],
			[ 'dropField', 'site_stats', 'ss_total_views', 'patch-drop-ss_total_views.sql' ],
			[ 'dropField', 'page', 'page_counter', 'patch-drop-page_counter.sql' ],

			// 1.27
			[ 'dropTable', 'msg_resource_links' ],
			[ 'dropTable', 'msg_resource' ],
			[ 'addField', 'watchlist', 'wl_id', 'patch-watchlist-wl_id.sql' ],

			// 1.28
			[ 'addIndex', 'recentchanges', 'rc_name_type_patrolled_timestamp',
				'patch-add-rc_name_type_patrolled_timestamp_index.sql' ],
			[ 'addField', 'change_tag', 'ct_id', 'patch-change_tag-ct_id.sql' ],
			[ 'addField', 'tag_summary', 'ts_id', 'patch-tag_summary-ts_id.sql' ],

			// 1.29
			[ 'addField', 'externallinks', 'el_index_60', 'patch-externallinks-el_index_60.sql' ],
			[ 'addField', 'user_groups', 'ug_expiry', 'patch-user_groups-ug_expiry.sql' ],

			// 1.30
			[ 'doAutoIncrementTriggers' ],
			[ 'addIndex', 'site_stats', 'PRIMARY', 'patch-site_stats-pk.sql' ],

			// Should have been in 1.30
			[ 'addTable', 'comment', 'patch-comment-table.sql' ],
			// This field was added in 1.31, but is put here so it can be used by 'migrateComments'
			[ 'addField', 'image', 'img_description_id', 'patch-image-img_description_id.sql' ],
			// Should have been in 1.30
			[ 'migrateComments' ],

			// 1.31
			[ 'addTable', 'slots', 'patch-slots.sql' ],
			[ 'addField', 'slots', 'slot_origin', 'patch-slot-origin.sql' ],
			[ 'addTable', 'content', 'patch-content.sql' ],
			[ 'addTable', 'slot_roles', 'patch-slot_roles.sql' ],
			[ 'addTable', 'content_models', 'patch-content_models.sql' ],
			[ 'migrateArchiveText' ],
			[ 'addTable', 'actor', 'patch-actor-table.sql' ],
			[ 'migrateActors' ],
			[ 'modifyTable', 'site_stats', 'patch-site_stats-modify.sql' ],
			[ 'populateArchiveRevId' ],
			[ 'addIndex', 'recentchanges', 'rc_namespace_title_timestamp',
				'patch-recentchanges-nttindex.sql' ],

			// 1.32
			[ 'addTable', 'change_tag_def', 'patch-change_tag_def.sql' ],
			[ 'populateExternallinksIndex60' ],
			[ 'runMaintenance', DeduplicateArchiveRevId::class, 'maintenance/deduplicateArchiveRevId.php' ],
			[ 'addField', 'change_tag', 'ct_tag_id', 'patch-change_tag-tag_id.sql' ],
			[ 'addIndex', 'archive', 'ar_revid_uniq', 'patch-archive-ar_rev_id-unique.sql' ],
			[ 'populateContentTables' ],
			[ 'addIndex', 'recentchanges', 'rc_this_oldid', 'patch-recentchanges-rc_this_oldid-index.sql' ],
			[ 'dropTable', 'transcache' ],
			[ 'runMaintenance', PopulateChangeTagDef::class, 'maintenance/populateChangeTagDef.php' ],
			[ 'addIndex', 'change_tag', 'change_tag_i03',
				'patch-change_tag-change_tag_rc_tag_id.sql' ],
			[ 'addField', 'ipblocks', 'ipb_sitewide', 'patch-ipb_sitewide.sql' ],
			[ 'addTable', 'ipblocks_restrictions', 'patch-ipblocks_restrictions-table.sql' ],
			[ 'migrateImageCommentTemp' ],

			// KEEP THIS AT THE BOTTOM!!
			[ 'doRebuildDuplicateFunction' ],

		];
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
		$row = [
			'page_id' => 0,
			'page_namespace' => 0,
			'page_title' => ' ',
			'page_is_redirect' => 0,
			'page_is_new' => 0,
			'page_random' => 0,
			'page_touched' => $this->db->timestamp(),
			'page_latest' => 0,
			'page_len' => 0
		];
		$this->db->insert( 'page', $row, 'OracleUpdater:doInserPage0', [ 'IGNORE' ] );
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
	 * Add auto-increment triggers
	 */
	protected function doAutoIncrementTriggers() {
		$this->output( "Adding auto-increment triggers ... " );

		$meta = $this->db->query( 'SELECT trigger_name FROM user_triggers WHERE table_owner = \'' .
			strtoupper( $this->db->getDBname() ) .
			'\' AND trigger_name = \'' .
			$this->db->tablePrefix() .
			'PAGE_DEFAULT_PAGE_ID\''
		);
		$row = $meta->fetchRow();
		if ( $row['column_name'] ) {
			$this->output( "seems to be up to date.\n" );

			return;
		}

		$this->applyPatch( 'patch-auto_increment_triggers.sql', false );

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
	 * @param array $what
	 */
	public function doUpdates( array $what = [ 'core', 'extensions', 'purge', 'stats' ] ) {
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
