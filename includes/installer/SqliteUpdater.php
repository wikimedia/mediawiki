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

			// 1.16
			[ 'addTable', 'user_properties', 'patch-user_properties.sql' ],
			[ 'addTable', 'log_search', 'patch-log_search.sql' ],
			[ 'addField', 'logging', 'log_user_text', 'patch-log_user_text.sql' ],
			# listed separately from the previous update because 1.16 was released without this update
			[ 'doLogUsertextPopulation' ],
			[ 'doLogSearchPopulation' ],
			[ 'addTable', 'l10n_cache', 'patch-l10n_cache.sql' ],
			[ 'dropIndex', 'change_tag', 'ct_rc_id', 'patch-change_tag-indexes.sql' ],
			[ 'addField', 'redirect', 'rd_interwiki', 'patch-rd_interwiki.sql' ],
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
			[ 'addIndexIfNoneExist',
				'archive', [ 'ar_revid', 'ar_revid_uniq' ], 'patch-archive_ar_revid.sql' ],

			// 1.18
			[ 'addIndex', 'user', 'user_email', 'patch-user_email_index.sql' ],
			[ 'addTable', 'uploadstash', 'patch-uploadstash.sql' ],
			[ 'addTable', 'user_former_groups', 'patch-user_former_groups.sql' ],

			// 1.19
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

			// 1.29
			[ 'addField', 'externallinks', 'el_index_60', 'patch-externallinks-el_index_60.sql' ],
			[ 'addField', 'user_groups', 'ug_expiry', 'patch-user_groups-ug_expiry.sql' ],
			[ 'addIndex', 'image', 'img_user_timestamp', 'patch-image-user-index-2.sql' ],

			// 1.30
			[ 'modifyField', 'image', 'img_media_type', 'patch-add-3d.sql' ],
			[ 'addTable', 'ip_changes', 'patch-ip_changes.sql' ],
			[ 'renameIndex', 'categorylinks', 'cl_from', 'PRIMARY', false,
				'patch-categorylinks-fix-pk.sql' ],
			[ 'renameIndex', 'templatelinks', 'tl_from', 'PRIMARY', false,
				'patch-templatelinks-fix-pk.sql' ],
			[ 'renameIndex', 'pagelinks', 'pl_from', 'PRIMARY', false, 'patch-pagelinks-fix-pk.sql' ],
			[ 'renameIndex', 'text', 'old_id', 'PRIMARY', false, 'patch-text-fix-pk.sql' ],
			[ 'renameIndex', 'imagelinks', 'il_from', 'PRIMARY', false, 'patch-imagelinks-fix-pk.sql' ],
			[ 'renameIndex', 'iwlinks', 'iwl_from', 'PRIMARY', false, 'patch-iwlinks-fix-pk.sql' ],
			[ 'renameIndex', 'langlinks', 'll_from', 'PRIMARY', false, 'patch-langlinks-fix-pk.sql' ],
			[ 'renameIndex', 'log_search', 'ls_field_val', 'PRIMARY', false, 'patch-log_search-fix-pk.sql' ],
			[ 'renameIndex', 'module_deps', 'md_module_skin', 'PRIMARY', false,
				'patch-module_deps-fix-pk.sql' ],
			[ 'renameIndex', 'objectcache', 'keyname', 'PRIMARY', false, 'patch-objectcache-fix-pk.sql' ],
			[ 'renameIndex', 'querycache_info', 'qci_type', 'PRIMARY', false,
				'patch-querycache_info-fix-pk.sql' ],
			[ 'renameIndex', 'site_stats', 'ss_row_id', 'PRIMARY', false, 'patch-site_stats-fix-pk.sql' ],
			[ 'renameIndex', 'user_former_groups', 'ufg_user_group', 'PRIMARY', false,
				'patch-user_former_groups-fix-pk.sql' ],
			[ 'renameIndex', 'user_properties', 'user_properties_user_property', 'PRIMARY', false,
				'patch-user_properties-fix-pk.sql' ],
			[ 'addTable', 'comment', 'patch-comment-table.sql' ],

			// This field was added in 1.31, but is put here so it can be used by 'migrateComments'
			[ 'addField', 'image', 'img_description_id', 'patch-image-img_description_id.sql' ],
			[ 'addField', 'filearchive', 'fa_description_id', 'patch-filearchive-fa_description_id.sql' ],

			[ 'migrateComments' ],
			[ 'renameIndex', 'l10n_cache', 'lc_lang_key', 'PRIMARY', false,
				'patch-l10n_cache-primary-key.sql' ],

			// 1.31
			[ 'addTable', 'content', 'patch-content.sql' ],
			[ 'addTable', 'content_models', 'patch-content_models.sql' ],
			[ 'addTable', 'slots', 'patch-slots.sql' ],
			[ 'addField', 'slots', 'slot_origin', 'patch-slot-origin.sql' ],
			[ 'addTable', 'slot_roles', 'patch-slot_roles.sql' ],
			[ 'migrateArchiveText' ],
			[ 'addTable', 'actor', 'patch-actor-table.sql' ],
			[ 'addField', 'filearchive', 'fa_actor', 'patch-filearchive-fa_actor.sql' ],
			[ 'migrateActors' ],
			[ 'modifyField', 'revision', 'rev_text_id', 'patch-rev_text_id-default.sql' ],
			[ 'modifyTable', 'site_stats', 'patch-site_stats-modify.sql' ],
			[ 'populateArchiveRevId' ],
			[ 'addIndex', 'recentchanges', 'rc_namespace_title_timestamp',
				'patch-recentchanges-nttindex.sql' ],

			// 1.32
			[ 'addTable', 'change_tag_def', 'patch-change_tag_def.sql' ],
			[ 'populateExternallinksIndex60' ],
			[ 'modifyfield', 'externallinks', 'el_index_60',
				'patch-externallinks-el_index_60-drop-default.sql' ],
			[ 'runMaintenance', DeduplicateArchiveRevId::class, 'maintenance/deduplicateArchiveRevId.php' ],
			[ 'addField', 'change_tag', 'ct_tag_id', 'patch-change_tag-tag_id.sql' ],
			[ 'addIndex', 'archive', 'ar_revid_uniq', 'patch-archive-ar_rev_id-unique.sql' ],
			[ 'populateContentTables' ],
			[ 'addIndex', 'logging', 'log_type_action', 'patch-logging-log-type-action-index.sql' ],
			[ 'dropIndex', 'logging', 'type_action', 'patch-logging-drop-type-action-index.sql' ],
			[ 'renameIndex', 'interwiki', 'iw_prefix', 'PRIMARY', false, 'patch-interwiki-fix-pk.sql' ],
						[ 'renameIndex', 'page_props', 'pp_page_propname', 'PRIMARY', false,
				'patch-page_props-fix-pk.sql' ],
			[ 'renameIndex', 'protected_titles', 'pt_namespace_title', 'PRIMARY', false,
				'patch-protected_titles-fix-pk.sql' ],
			[ 'renameIndex', 'site_identifiers', 'site_ids_type', 'PRIMARY', false,
				'patch-site_identifiers-fix-pk.sql' ],
			[ 'addIndex', 'recentchanges', 'rc_this_oldid', 'patch-recentchanges-rc_this_oldid-index.sql' ],
			[ 'dropTable', 'transcache' ],
			[ 'runMaintenance', PopulateChangeTagDef::class, 'maintenance/populateChangeTagDef.php' ],
			[ 'addIndex', 'change_tag', 'change_tag_rc_tag_id',
				'patch-change_tag-change_tag_rc_tag_id.sql' ],
			[ 'addField', 'ipblocks', 'ipb_sitewide', 'patch-ipb_sitewide.sql' ],
			[ 'addTable', 'ipblocks_restrictions', 'patch-ipblocks_restrictions-table.sql' ],
			[ 'migrateImageCommentTemp' ],

			// 1.33
			[ 'dropField', 'change_tag', 'ct_tag', 'patch-drop-ct_tag.sql' ],
			[ 'dropTable', 'valid_tag' ],
			[ 'dropTable', 'tag_summary' ],
			[ 'dropField', 'archive', 'ar_comment', 'patch-archive-drop-ar_comment.sql' ],
			[ 'dropField', 'ipblocks', 'ipb_reason', 'patch-ipblocks-drop-ipb_reason.sql' ],
			[ 'dropField', 'image', 'img_description', 'patch-image-drop-img_description.sql' ],
			[ 'dropField', 'oldimage', 'oi_description', 'patch-oldimage-drop-oi_description.sql' ],
			[ 'dropField', 'filearchive', 'fa_description', 'patch-filearchive-drop-fa_description.sql' ],
			[ 'dropField', 'recentchanges', 'rc_comment', 'patch-recentchanges-drop-rc_comment.sql' ],
			[ 'dropField', 'logging', 'log_comment', 'patch-logging-drop-log_comment.sql' ],
			[ 'dropField', 'protected_titles', 'pt_reason', 'patch-protected_titles-drop-pt_reason.sql' ],
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
