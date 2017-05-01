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

use Wikimedia\Rdbms\DatabaseSqlite;

/**
 * Class for handling updates to Sqlite databases.
 *
 * @ingroup Deployment
 * @since 1.17
 */
class SqliteUpdater extends DatabaseUpdater {

	protected function getCoreUpdateList() {
		return [
			[ 'disableContentHandlerUseDB' ],

			// 1.14
			[ 'addField', 'site_stats', 'ss_active_users', 'patch-ss_active_users.sql' ],
			[ 'doActiveUsersInit' ],
			[ 'addField', 'ipblocks', 'ipb_allow_usertalk', 'patch-ipb_allow_usertalk.sql' ],
			[ 'sqliteInitialIndexes' ],

			// 1.15
			[ 'addTable', 'change_tag', 'patch-change_tag.sql' ],
			[ 'addTable', 'tag_summary', 'patch-tag_summary.sql' ],
			[ 'addTable', 'valid_tag', 'patch-valid_tag.sql' ],

			// 1.16
			[ 'addTable', 'user_properties', 'patch-user_properties.sql' ],
			[ 'addTable', 'log_search', 'patch-log_search.sql' ],
			[ 'addField', 'logging', 'log_user_text', 'patch-log_user_text.sql' ],
			# listed separately from the previous update because 1.16 was released without this update
			[ 'doLogUsertextPopulation' ],
			[ 'doLogSearchPopulation' ],
			[ 'addTable', 'l10n_cache', 'patch-l10n_cache.sql' ],
			[ 'addIndex', 'log_search', 'ls_field_val', 'patch-log_search-rename-index.sql' ],
			[ 'addIndex', 'change_tag', 'change_tag_rc_tag', 'patch-change_tag-indexes.sql' ],
			[ 'addField', 'redirect', 'rd_interwiki', 'patch-rd_interwiki.sql' ],
			[ 'doUpdateTranscacheField' ],
			[ 'sqliteSetupSearchindex' ],

			// 1.17
			[ 'addTable', 'iwlinks', 'patch-iwlinks.sql' ],
			[ 'addIndex', 'iwlinks', 'iwl_prefix_title_from', 'patch-rename-iwl_prefix.sql' ],
			[ 'addField', 'updatelog', 'ul_value', 'patch-ul_value.sql' ],
			[ 'addField', 'interwiki', 'iw_api', 'patch-iw_api_and_wikiid.sql' ],
			[ 'dropIndex', 'iwlinks', 'iwl_prefix', 'patch-kill-iwl_prefix.sql' ],
			[ 'addField', 'categorylinks', 'cl_collation', 'patch-categorylinks-better-collation.sql' ],
			[ 'addTable', 'module_deps', 'patch-module_deps.sql' ],
			[ 'dropIndex', 'archive', 'ar_page_revid', 'patch-archive_kill_ar_page_revid.sql' ],
			[ 'addIndex', 'archive', 'ar_revid', 'patch-archive_ar_revid.sql' ],

			// 1.18
			[ 'addIndex', 'user', 'user_email', 'patch-user_email_index.sql' ],
			[ 'addTable', 'uploadstash', 'patch-uploadstash.sql' ],
			[ 'addTable', 'user_former_groups', 'patch-user_former_groups.sql' ],

			// 1.19
			[ 'addIndex', 'logging', 'type_action', 'patch-logging-type-action-index.sql' ],
			[ 'doMigrateUserOptions' ],
			[ 'dropField', 'user', 'user_options', 'patch-drop-user_options.sql' ],
			[ 'addField', 'revision', 'rev_sha1', 'patch-rev_sha1.sql' ],
			[ 'addField', 'archive', 'ar_sha1', 'patch-ar_sha1.sql' ],
			[ 'addIndex', 'page', 'page_redirect_namespace_len',
				'patch-page_redirect_namespace_len.sql' ],
			[ 'addField', 'uploadstash', 'us_chunk_inx', 'patch-uploadstash_chunk.sql' ],
			[ 'addfield', 'job', 'job_timestamp', 'patch-jobs-add-timestamp.sql' ],

			// 1.20
			[ 'addIndex', 'revision', 'page_user_timestamp', 'patch-revision-user-page-index.sql' ],
			[ 'addField', 'ipblocks', 'ipb_parent_block_id', 'patch-ipb-parent-block-id.sql' ],
			[ 'addIndex', 'ipblocks', 'ipb_parent_block_id', 'patch-ipb-parent-block-id-index.sql' ],
			[ 'dropField', 'category', 'cat_hidden', 'patch-cat_hidden.sql' ],

			// 1.21
			[ 'addField', 'revision', 'rev_content_format', 'patch-revision-rev_content_format.sql' ],
			[ 'addField', 'revision', 'rev_content_model', 'patch-revision-rev_content_model.sql' ],
			[ 'addField', 'archive', 'ar_content_format', 'patch-archive-ar_content_format.sql' ],
			[ 'addField', 'archive', 'ar_content_model', 'patch-archive-ar_content_model.sql' ],
			[ 'addField', 'page', 'page_content_model', 'patch-page-page_content_model.sql' ],
			[ 'enableContentHandlerUseDB' ],

			[ 'dropField', 'site_stats', 'ss_admins', 'patch-drop-ss_admins.sql' ],
			[ 'dropField', 'recentchanges', 'rc_moved_to_title', 'patch-rc_moved.sql' ],
			[ 'addTable', 'sites', 'patch-sites.sql' ],
			[ 'addField', 'filearchive', 'fa_sha1', 'patch-fa_sha1.sql' ],
			[ 'addField', 'job', 'job_token', 'patch-job_token.sql' ],
			[ 'addField', 'job', 'job_attempts', 'patch-job_attempts.sql' ],
			[ 'doEnableProfiling' ],
			[ 'addField', 'uploadstash', 'us_props', 'patch-uploadstash-us_props.sql' ],
			[ 'modifyField', 'user_groups', 'ug_group', 'patch-ug_group-length-increase-255.sql' ],
			[ 'modifyField', 'user_former_groups', 'ufg_group',
				'patch-ufg_group-length-increase-255.sql' ],
			[ 'addIndex', 'page_props', 'pp_propname_page',
				'patch-page_props-propname-page-index.sql' ],
			[ 'addIndex', 'image', 'img_media_mime', 'patch-img_media_mime-index.sql' ],

			// 1.22
			[ 'addIndex', 'iwlinks', 'iwl_prefix_from_title', 'patch-iwlinks-from-title-index.sql' ],
			[ 'addField', 'archive', 'ar_id', 'patch-archive-ar_id.sql' ],
			[ 'addField', 'externallinks', 'el_id', 'patch-externallinks-el_id.sql' ],

			// 1.23
			[ 'addField', 'recentchanges', 'rc_source', 'patch-rc_source.sql' ],
			[ 'addIndex', 'logging', 'log_user_text_type_time',
				'patch-logging_user_text_type_time_index.sql' ],
			[ 'addIndex', 'logging', 'log_user_text_time', 'patch-logging_user_text_time_index.sql' ],
			[ 'addField', 'page', 'page_links_updated', 'patch-page_links_updated.sql' ],
			[ 'addField', 'user', 'user_password_expires', 'patch-user_password_expire.sql' ],

			// 1.24
			[ 'addField', 'page_props', 'pp_sortkey', 'patch-pp_sortkey.sql' ],
			[ 'dropField', 'recentchanges', 'rc_cur_time', 'patch-drop-rc_cur_time.sql' ],
			[ 'addIndex', 'watchlist', 'wl_user_notificationtimestamp',
				'patch-watchlist-user-notificationtimestamp-index.sql' ],
			[ 'addField', 'page', 'page_lang', 'patch-page-page_lang.sql' ],
			[ 'addField', 'pagelinks', 'pl_from_namespace', 'patch-pl_from_namespace.sql' ],
			[ 'addField', 'templatelinks', 'tl_from_namespace', 'patch-tl_from_namespace.sql' ],
			[ 'addField', 'imagelinks', 'il_from_namespace', 'patch-il_from_namespace.sql' ],

			// 1.25
			[ 'dropTable', 'hitcounter' ],
			[ 'dropField', 'site_stats', 'ss_total_views', 'patch-drop-ss_total_views.sql' ],
			[ 'dropField', 'page', 'page_counter', 'patch-drop-page_counter.sql' ],
			[ 'modifyField', 'filearchive', 'fa_deleted_reason', 'patch-editsummary-length.sql' ],

			// 1.27
			[ 'dropTable', 'msg_resource_links' ],
			[ 'dropTable', 'msg_resource' ],
			[ 'addTable', 'bot_passwords', 'patch-bot_passwords.sql' ],
			[ 'addField', 'watchlist', 'wl_id', 'patch-watchlist-wl_id.sql' ],
			[ 'dropIndex', 'categorylinks', 'cl_collation', 'patch-kill-cl_collation_index.sql' ],
			[ 'addIndex', 'categorylinks', 'cl_collation_ext',
				'patch-add-cl_collation_ext_index.sql' ],
			[ 'doCollationUpdate' ],

			// 1.28
			[ 'addIndex', 'recentchanges', 'rc_name_type_patrolled_timestamp',
				'patch-add-rc_name_type_patrolled_timestamp_index.sql' ],
			[ 'addField', 'change_tag', 'ct_id', 'patch-change_tag-ct_id.sql' ],
			[ 'addField', 'tag_summary', 'ts_id', 'patch-tag_summary-ts_id.sql' ],

			// 1.29
			[ 'addField', 'externallinks', 'el_index_60', 'patch-externallinks-el_index_60.sql' ],
			[ 'addField', 'user_groups', 'ug_expiry', 'patch-user_groups-ug_expiry.sql' ],
			[ 'addIndex', 'image', 'img_user_timestamp', 'patch-image-user-index-2.sql' ],
		];
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
}
