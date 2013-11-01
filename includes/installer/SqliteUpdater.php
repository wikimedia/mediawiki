<?php
/**
 * Sqlite-specific updater.
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
 * Class for handling updates to Sqlite databases.
 *
 * @ingroup Deployment
 * @since 1.17
 */
class SqliteUpdater extends DatabaseUpdater {

	protected function getCoreUpdateList() {
		return array(
			// 1.14
			array( 'addField', 'site_stats', 'ss_active_users', 'patch-ss_active_users.sql' ),
			array( 'doActiveUsersInit' ),
			array( 'addField', 'ipblocks', 'ipb_allow_usertalk', 'patch-ipb_allow_usertalk.sql' ),
			array( 'sqliteInitialIndexes' ),

			// 1.15
			array( 'addTable', 'change_tag', 'patch-change_tag.sql' ),
			array( 'addTable', 'tag_summary', 'patch-tag_summary.sql' ),
			array( 'addTable', 'valid_tag', 'patch-valid_tag.sql' ),

			// 1.16
			array( 'addTable', 'user_properties', 'patch-user_properties.sql' ),
			array( 'addTable', 'log_search', 'patch-log_search.sql' ),
			array( 'addField', 'logging', 'log_user_text', 'patch-log_user_text.sql' ),
			# listed separately from the previous update because 1.16 was released without this update
			array( 'doLogUsertextPopulation' ),
			array( 'doLogSearchPopulation' ),
			array( 'addTable', 'l10n_cache', 'patch-l10n_cache.sql' ),
			array( 'addIndex', 'log_search', 'ls_field_val', 'patch-log_search-rename-index.sql' ),
			array( 'addIndex', 'change_tag', 'change_tag_rc_tag', 'patch-change_tag-indexes.sql' ),
			array( 'addField', 'redirect', 'rd_interwiki', 'patch-rd_interwiki.sql' ),
			array( 'doUpdateTranscacheField' ),
			array( 'sqliteSetupSearchindex' ),

			// 1.17
			array( 'addTable', 'iwlinks', 'patch-iwlinks.sql' ),
			array( 'addIndex', 'iwlinks', 'iwl_prefix_title_from', 'patch-rename-iwl_prefix.sql' ),
			array( 'addField', 'updatelog', 'ul_value', 'patch-ul_value.sql' ),
			array( 'addField', 'interwiki', 'iw_api', 'patch-iw_api_and_wikiid.sql' ),
			array( 'dropIndex', 'iwlinks', 'iwl_prefix', 'patch-kill-iwl_prefix.sql' ),
			array( 'addField', 'categorylinks', 'cl_collation', 'patch-categorylinks-better-collation.sql' ),
			array( 'doCollationUpdate' ),
			array( 'addTable', 'msg_resource', 'patch-msg_resource.sql' ),
			array( 'addTable', 'module_deps', 'patch-module_deps.sql' ),
			array( 'dropIndex', 'archive', 'ar_page_revid', 'patch-archive_kill_ar_page_revid.sql' ),
			array( 'addIndex', 'archive', 'ar_revid', 'patch-archive_ar_revid.sql' ),

			// 1.18
			array( 'addIndex', 'user', 'user_email', 'patch-user_email_index.sql' ),
			array( 'addTable', 'uploadstash', 'patch-uploadstash.sql' ),
			array( 'addTable', 'user_former_groups', 'patch-user_former_groups.sql' ),

			// 1.19
			array( 'addIndex', 'logging', 'type_action', 'patch-logging-type-action-index.sql' ),
			array( 'doMigrateUserOptions' ),
			array( 'dropField', 'user', 'user_options', 'patch-drop-user_options.sql' ),
			array( 'addField', 'revision', 'rev_sha1', 'patch-rev_sha1.sql' ),
			array( 'addField', 'archive', 'ar_sha1', 'patch-ar_sha1.sql' ),
			array( 'addIndex', 'page', 'page_redirect_namespace_len',
				'patch-page_redirect_namespace_len.sql' ),
			array( 'addField', 'uploadstash', 'us_chunk_inx', 'patch-uploadstash_chunk.sql' ),
			array( 'addfield', 'job', 'job_timestamp', 'patch-jobs-add-timestamp.sql' ),

			// 1.20
			array( 'addIndex', 'revision', 'page_user_timestamp', 'patch-revision-user-page-index.sql' ),
			array( 'addField', 'ipblocks', 'ipb_parent_block_id', 'patch-ipb-parent-block-id.sql' ),
			array( 'addIndex', 'ipblocks', 'ipb_parent_block_id', 'patch-ipb-parent-block-id-index.sql' ),
			array( 'dropField', 'category', 'cat_hidden', 'patch-cat_hidden.sql' ),

			// 1.21
			array( 'addField', 'revision', 'rev_content_format', 'patch-revision-rev_content_format.sql' ),
			array( 'addField', 'revision', 'rev_content_model', 'patch-revision-rev_content_model.sql' ),
			array( 'addField', 'archive', 'ar_content_format', 'patch-archive-ar_content_format.sql' ),
			array( 'addField', 'archive', 'ar_content_model', 'patch-archive-ar_content_model.sql' ),
			array( 'addField', 'page', 'page_content_model', 'patch-page-page_content_model.sql' ),
			array( 'dropField', 'site_stats', 'ss_admins', 'patch-drop-ss_admins.sql' ),
			array( 'dropField', 'recentchanges', 'rc_moved_to_title', 'patch-rc_moved.sql' ),
			array( 'addTable', 'sites', 'patch-sites.sql' ),
			array( 'addField', 'filearchive', 'fa_sha1', 'patch-fa_sha1.sql' ),
			array( 'addField', 'job', 'job_token', 'patch-job_token.sql' ),
			array( 'addField', 'job', 'job_attempts', 'patch-job_attempts.sql' ),
			array( 'doEnableProfiling' ),
			array( 'addField', 'uploadstash', 'us_props', 'patch-uploadstash-us_props.sql' ),
			array( 'modifyField', 'user_groups', 'ug_group', 'patch-ug_group-length-increase-255.sql' ),
			array( 'modifyField', 'user_former_groups', 'ufg_group',
				'patch-ufg_group-length-increase-255.sql' ),
			array( 'addIndex', 'page_props', 'pp_propname_page',
				'patch-page_props-propname-page-index.sql' ),
			array( 'addIndex', 'image', 'img_media_mime', 'patch-img_media_mime-index.sql' ),
			array( 'addIndex', 'iwlinks', 'iwl_prefix_from_title', 'patch-iwlinks-from-title-index.sql' ),
			array( 'addField', 'archive', 'ar_id', 'patch-archive-ar_id.sql' ),
			array( 'addField', 'externallinks', 'el_id', 'patch-externallinks-el_id.sql' ),
		);
	}

	protected function sqliteInitialIndexes() {
		// initial-indexes.sql fails if the indexes are already present,
		// so we perform a quick check if our database is newer.
		if ( $this->updateRowExists( 'initial_indexes' ) ||
			$this->db->indexExists( 'user', 'user_name', __METHOD__ )
		) {
			$this->output( "...have initial indexes\n" );

			return;
		}
		$this->applyPatch( 'initial-indexes.sql', false, "Adding initial indexes" );
	}

	protected function sqliteSetupSearchindex() {
		$module = DatabaseSqlite::getFulltextSearchModule();
		$fts3tTable = $this->updateRowExists( 'fts3' );
		if ( $fts3tTable && !$module ) {
			$this->applyPatch(
				'searchindex-no-fts.sql',
				false,
				'PHP is missing FTS3 support, downgrading tables'
			);
		} elseif ( !$fts3tTable && $module == 'FTS3' ) {
			$this->applyPatch( 'searchindex-fts3.sql', false, "Adding FTS3 search capabilities" );
		} else {
			$this->output( "...fulltext search table appears to be in order.\n" );
		}
	}

	protected function doEnableProfiling() {
		global $wgProfileToDatabase;
		if ( $wgProfileToDatabase === true && !$this->db->tableExists( 'profiling', __METHOD__ ) ) {
			$this->applyPatch( 'patch-profiling.sql', false, 'Add profiling table' );
		}
	}
}
