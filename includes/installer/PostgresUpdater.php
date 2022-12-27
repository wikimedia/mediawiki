<?php
/**
 * PostgreSQL-specific updater.
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
 * @ingroup Installer
 */

use Wikimedia\Rdbms\DatabasePostgres;

/**
 * Class for handling updates to Postgres databases.
 *
 * @ingroup Installer
 * @since 1.17
 */
class PostgresUpdater extends DatabaseUpdater {

	/**
	 * @var DatabasePostgres
	 */
	protected $db;

	/**
	 * @return array
	 */
	protected function getCoreUpdateList() {
		return [
			// 1.35 but must come first
			[ 'addPgField', 'revision', 'rev_actor', 'INTEGER NOT NULL DEFAULT 0' ],
			[ 'addPgIndex', 'revision', 'rev_actor_timestamp', '(rev_actor,rev_timestamp,rev_id)' ],
			[ 'addPgIndex', 'revision', 'rev_page_actor_timestamp', '(rev_page,rev_actor,rev_timestamp)' ],

			// Exception to the sequential updates. Renaming pagecontent and mwuser.
			// Introduced in 1.36.
			[ 'renameTable', 'pagecontent', 'text' ],
			// Introduced in 1.37.
			[ 'renameTable', 'mwuser', 'user' ],

			// 1.31
			[ 'addPgField', 'image', 'img_description_id', 'INTEGER NOT NULL DEFAULT 0' ],
			[ 'migrateComments' ],

			[ 'addTable', 'slots', 'patch-slots-table.sql' ],
			[ 'dropPgIndex', 'slots', 'slot_role_inherited' ],
			[ 'dropPgField', 'slots', 'slot_inherited' ],
			[ 'addPgField', 'slots', 'slot_origin', 'INTEGER NOT NULL' ],
			[
				'addPgIndex',
				'slots',
				'slot_revision_origin_role',
				'( slot_revision_id, slot_origin, slot_role_id )',
			],
			[ 'addTable', 'content', 'patch-content-table.sql' ],
			[ 'addTable', 'content_models', 'patch-content_models-table.sql' ],
			[ 'addTable', 'slot_roles', 'patch-slot_roles-table.sql' ],
			[ 'migrateArchiveText' ],
			[ 'addTable', 'actor', 'patch-actor-table.sql' ],
			[ 'setDefault', 'revision', 'rev_user', 0 ],
			[ 'setDefault', 'revision', 'rev_user_text', '' ],
			[ 'setDefault', 'archive', 'ar_user', 0 ],
			[ 'changeNullableField', 'archive', 'ar_user', 'NOT NULL', true ],
			[ 'setDefault', 'archive', 'ar_user_text', '' ],
			[ 'addPgField', 'archive', 'ar_actor', 'INTEGER NOT NULL DEFAULT 0' ],
			[ 'setDefault', 'ipblocks', 'ipb_by', 0 ],
			[ 'addPgField', 'ipblocks', 'ipb_by_actor', 'INTEGER NOT NULL DEFAULT 0' ],
			[ 'setDefault', 'image', 'img_user', 0 ],
			[ 'changeNullableField', 'image', 'img_user', 'NOT NULL', true ],
			[ 'setDefault', 'image', 'img_user_text', '' ],
			[ 'addPgField', 'image', 'img_actor', 'INTEGER NOT NULL DEFAULT 0' ],
			[ 'setDefault', 'oldimage', 'oi_user', 0 ],
			[ 'changeNullableField', 'oldimage', 'oi_user', 'NOT NULL', true ],
			[ 'setDefault', 'oldimage', 'oi_user_text', '' ],
			[ 'addPgField', 'oldimage', 'oi_actor', 'INTEGER NOT NULL DEFAULT 0' ],
			[ 'setDefault', 'filearchive', 'fa_user', 0 ],
			[ 'changeNullableField', 'filearchive', 'fa_user', 'NOT NULL', true ],
			[ 'setDefault', 'filearchive', 'fa_user_text', '' ],
			[ 'addPgField', 'filearchive', 'fa_actor', 'INTEGER NOT NULL DEFAULT 0' ],
			[ 'setDefault', 'recentchanges', 'rc_user', 0 ],
			[ 'changeNullableField', 'recentchanges', 'rc_user', 'NOT NULL', true ],
			[ 'setDefault', 'recentchanges', 'rc_user_text', '' ],
			[ 'addPgField', 'recentchanges', 'rc_actor', 'INTEGER NOT NULL DEFAULT 0' ],
			[ 'setDefault', 'logging', 'log_user', 0 ],
			[ 'changeNullableField', 'logging', 'log_user', 'NOT NULL', true ],
			[ 'addPgField', 'logging', 'log_actor', 'INTEGER NOT NULL DEFAULT 0' ],
			[ 'addPgIndex', 'logging', 'logging_actor_time_backwards', '( log_timestamp, log_actor )' ],
			[ 'addPgIndex', 'logging', 'logging_actor_type_time', '( log_actor, log_type, log_timestamp )' ],
			[ 'addPgIndex', 'logging', 'logging_actor_time', '( log_actor, log_timestamp )' ],
			[ 'migrateActors' ],
			[ 'modifyTable', 'site_stats', 'patch-site_stats-modify.sql' ],
			[ 'populateArchiveRevId' ],
			[ 'dropPgIndex', 'recentchanges', 'rc_namespace_title' ],
			[
				'addPgIndex',
				'recentchanges',
				'rc_namespace_title_timestamp', '( rc_namespace, rc_title, rc_timestamp )'
			],
			[ 'setSequenceOwner', 'user', 'user_id', 'user_user_id_seq' ],
			[ 'setSequenceOwner', 'actor', 'actor_id', 'actor_actor_id_seq' ],
			[ 'setSequenceOwner', 'page', 'page_id', 'page_page_id_seq' ],
			[ 'setSequenceOwner', 'revision', 'rev_id', 'revision_rev_id_seq' ],
			[ 'setSequenceOwner', 'ip_changes', 'ipc_rev_id', 'ip_changes_ipc_rev_id_seq' ],
			[ 'setSequenceOwner', 'text', 'old_id', 'text_old_id_seq' ],
			[ 'setSequenceOwner', 'comment', 'comment_id', 'comment_comment_id_seq' ],
			[ 'setSequenceOwner', 'page_restrictions', 'pr_id', 'page_restrictions_pr_id_seq' ],
			[ 'setSequenceOwner', 'archive', 'ar_id', 'archive_ar_id_seq' ],
			[ 'setSequenceOwner', 'content', 'content_id', 'content_content_id_seq' ],
			[ 'setSequenceOwner', 'slot_roles', 'role_id', 'slot_roles_role_id_seq' ],
			[ 'setSequenceOwner', 'content_models', 'model_id', 'content_models_model_id_seq' ],
			[ 'setSequenceOwner', 'externallinks', 'el_id', 'externallinks_el_id_seq' ],
			[ 'setSequenceOwner', 'ipblocks', 'ipb_id', 'ipblocks_ipb_id_seq' ],
			[ 'setSequenceOwner', 'filearchive', 'fa_id', 'filearchive_fa_id_seq' ],
			[ 'setSequenceOwner', 'uploadstash', 'us_id', 'uploadstash_us_id_seq' ],
			[ 'setSequenceOwner', 'recentchanges', 'rc_id', 'recentchanges_rc_id_seq' ],
			[ 'setSequenceOwner', 'watchlist', 'wl_id', 'watchlist_wl_id_seq' ],
			[ 'setSequenceOwner', 'logging', 'log_id', 'logging_log_id_seq' ],
			[ 'setSequenceOwner', 'job', 'job_id', 'job_job_id_seq' ],
			[ 'setSequenceOwner', 'category', 'cat_id', 'category_cat_id_seq' ],
			[ 'setSequenceOwner', 'change_tag', 'ct_id', 'change_tag_ct_id_seq' ],
			[ 'setSequenceOwner', 'sites', 'site_id', 'sites_site_id_seq' ],

			// 1.32
			[ 'addTable', 'change_tag_def', 'patch-change_tag_def.sql' ],
			[ 'populateExternallinksIndex60' ],
			[ 'dropDefault', 'externallinks', 'el_index_60' ],
			[ 'runMaintenance', DeduplicateArchiveRevId::class, 'maintenance/deduplicateArchiveRevId.php' ],
			[ 'addPgField', 'change_tag', 'ct_tag_id', 'INTEGER NULL' ],
			[
				'addPgIndex',
				'change_tag',
				'change_tag_tag_id_id',
				'( ct_tag_id, ct_rc_id, ct_rev_id, ct_log_id )'
			],
			[ 'addPgIndex', 'archive', 'ar_revid_uniq', '(ar_rev_id)', 'unique' ],
			[ 'dropPgIndex', 'archive', 'ar_revid' ], // Probably doesn't exist, but do it anyway.
			[ 'populateContentTables' ],
			[ 'addPgIndex', 'logging', 'log_type_action', '( log_type, log_action, log_timestamp )' ],
			[ 'dropPgIndex', 'page_props', 'page_props_propname' ],
			[ 'addIndex', 'interwiki', 'interwiki_pkey', 'patch-interwiki-pk.sql' ],
			[ 'addIndex', 'protected_titles', 'protected_titles_pkey', 'patch-protected_titles-pk.sql' ],
			[ 'addIndex', 'site_identifiers', 'site_identifiers_pkey', 'patch-site_identifiers-pk.sql' ],
			[ 'addPgIndex', 'recentchanges', 'rc_this_oldid', '(rc_this_oldid)' ],
			[ 'dropTable', 'transcache' ],
			[ 'runMaintenance', PopulateChangeTagDef::class, 'maintenance/populateChangeTagDef.php' ],
			[ 'dropIndex', 'change_tag', 'change_tag_rc_tag', 'patch-change_tag-change_tag_rc_tag_id.sql' ],
			[ 'addPgField', 'ipblocks', 'ipb_sitewide', 'SMALLINT NOT NULL DEFAULT 1' ],
			[ 'addTable', 'ipblocks_restrictions', 'patch-ipblocks_restrictions-table.sql' ],
			[ 'migrateImageCommentTemp' ],
			[ 'dropPgField', 'category', 'cat_hidden' ],
			[ 'dropPgField', 'site_stats', 'ss_admins' ],
			[ 'dropPgField', 'recentchanges', 'rc_cur_time' ],

			// 1.33
			[ 'dropField', 'change_tag', 'ct_tag', 'patch-drop-ct_tag.sql' ],
			[ 'dropTable', 'valid_tag' ],
			[ 'dropTable', 'tag_summary' ],
			[ 'dropPgField', 'archive', 'ar_comment' ],
			[ 'dropDefault', 'archive', 'ar_comment_id' ],
			[ 'dropPgField', 'ipblocks', 'ipb_reason' ],
			[ 'dropDefault', 'ipblocks', 'ipb_reason_id' ],
			[ 'dropPgField', 'image', 'img_description' ],
			[ 'dropDefault', 'image', 'img_description_id' ],
			[ 'dropPgField', 'oldimage', 'oi_description' ],
			[ 'dropDefault', 'oldimage', 'oi_description_id' ],
			[ 'dropPgField', 'filearchive', 'fa_deleted_reason' ],
			[ 'dropDefault', 'filearchive', 'fa_deleted_reason_id' ],
			[ 'dropPgField', 'filearchive', 'fa_description' ],
			[ 'dropDefault', 'filearchive', 'fa_description_id' ],
			[ 'dropPgField', 'recentchanges', 'rc_comment' ],
			[ 'dropDefault', 'recentchanges', 'rc_comment_id' ],
			[ 'dropPgField', 'logging', 'log_comment' ],
			[ 'dropDefault', 'logging', 'log_comment_id' ],
			[ 'dropPgField', 'protected_titles', 'pt_reason' ],
			[ 'dropDefault', 'protected_titles', 'pt_reason_id' ],

			// 1.34
			[ 'dropPgIndex', 'archive', 'archive_user_text' ],
			[ 'dropPgField', 'archive', 'ar_user' ],
			[ 'dropPgField', 'archive', 'ar_user_text' ],
			[ 'dropDefault', 'archive', 'ar_actor' ],
			[ 'dropPgField', 'ipblocks', 'ipb_by' ],
			[ 'dropPgField', 'ipblocks', 'ipb_by_text' ],
			[ 'dropDefault', 'ipblocks', 'ipb_by_actor' ],
			[ 'dropPgField', 'image', 'img_user' ],
			[ 'dropPgField', 'image', 'img_user_text' ],
			[ 'dropDefault', 'image', 'img_actor' ],
			[ 'dropPgField', 'oldimage', 'oi_user' ],
			[ 'dropPgField', 'oldimage', 'oi_user_text' ],
			[ 'dropDefault', 'oldimage', 'oi_actor' ],
			[ 'dropPgField', 'filearchive', 'fa_user' ],
			[ 'dropPgField', 'filearchive', 'fa_user_text' ],
			[ 'dropDefault', 'filearchive', 'fa_actor' ],
			[ 'dropPgField', 'recentchanges', 'rc_user' ],
			[ 'dropPgField', 'recentchanges', 'rc_user_text' ],
			[ 'dropDefault', 'recentchanges', 'rc_actor' ],
			[ 'dropPgIndex', 'logging', 'logging_user_time' ],
			[ 'dropPgIndex', 'logging', 'logging_user_type_time' ],
			[ 'dropPgIndex', 'logging', 'logging_user_text_type_time' ],
			[ 'dropPgIndex', 'logging', 'logging_user_text_time' ],
			[ 'dropPgField', 'logging', 'log_user' ],
			[ 'dropPgField', 'logging', 'log_user_text' ],
			[ 'dropDefault', 'logging', 'log_actor' ],

			// 1.35
			[ 'addIndex', 'redirect', 'redirect_pkey', 'patch-redirect-pk.sql' ],
			[ 'addTable', 'watchlist_expiry', 'patch-watchlist_expiry.sql' ],
			[ 'setSequenceOwner', 'watchlist_expiry', 'we_item', 'watchlist_expiry_we_item_seq' ],
			[ 'setDefault', 'user_newtalk', 'user_ip', '' ],
			[ 'changeNullableField', 'user_newtalk', 'user_ip', 'NOT NULL', true ],
			[ 'setDefault', 'user_newtalk', 'user_id', 0 ],
			[ 'dropPgIndex', 'revision', 'rev_user_idx' ],
			[ 'dropPgIndex', 'revision', 'rev_user_text_idx' ],
			[ 'dropPgIndex', 'revision', 'rev_text_id_idx' ],
			[ 'dropPgField', 'revision', 'rev_user' ],
			[ 'dropPgField', 'revision', 'rev_user_text' ],
			[ 'dropPgField', 'revision', 'rev_comment' ],
			[ 'dropPgField', 'revision', 'rev_text_id' ],
			[ 'dropPgField', 'revision', 'rev_content_model' ],
			[ 'dropPgField', 'revision', 'rev_content_format' ],
			[ 'addPgField', 'revision', 'rev_comment_id', 'INTEGER NOT NULL DEFAULT 0' ],
			[ 'dropPgField', 'archive', 'ar_text_id' ],
			[ 'dropPgField', 'archive', 'ar_content_model' ],
			[ 'dropPgField', 'archive', 'ar_content_format' ],
			[ 'changeField', 'updatelog', 'ul_key', 'varchar(255)', '' ],
			[ 'changeField', 'updatelog', 'ul_value', 'TEXT', '' ],
			[ 'changeField', 'site_identifiers', 'si_type', 'TEXT', '' ],
			[ 'changeField', 'site_identifiers', 'si_key', 'TEXT', '' ],
			[ 'changeField', 'actor', 'actor_id', 'BIGINT', '' ],
			[ 'changeField', 'actor', 'actor_name', 'TEXT', '' ],
			[ 'changeField', 'user_former_groups', 'ufg_group', 'TEXT', '' ],
			[ 'dropFkey', 'user_former_groups', 'ufg_user' ],
			[ 'checkIndex', 'ipb_address_unique', [
				[ 'ipb_address', 'text_ops', 'btree', 0 ],
				[ 'ipb_user', 'int4_ops', 'btree', 0 ],
				[ 'ipb_auto', 'int2_ops', 'btree', 0 ],
			],
				'CREATE UNIQUE INDEX ipb_address_unique ' .
				'ON ipblocks (ipb_address,ipb_user,ipb_auto)' ],

			// 1.36
			[ 'setDefault', 'bot_passwords', 'bp_token', '' ],
			[ 'changeField', 'comment', 'comment_id', 'BIGINT', '' ],
			[ 'changeField', 'slots', 'slot_revision_id', 'BIGINT', '' ],
			[ 'changeField', 'slots', 'slot_content_id', 'BIGINT', '' ],
			[ 'changeField', 'slots', 'slot_origin', 'BIGINT', '' ],
			[ 'changeField', 'site_stats', 'ss_total_edits', 'BIGINT', '' ],
			[ 'changeField', 'site_stats', 'ss_good_articles', 'BIGINT', '' ],
			[ 'changeField', 'site_stats', 'ss_total_pages', 'BIGINT', '' ],
			[ 'changeField', 'site_stats', 'ss_users', 'BIGINT', '' ],
			[ 'changeField', 'site_stats', 'ss_active_users', 'BIGINT', '' ],
			[ 'changeField', 'site_stats', 'ss_images', 'BIGINT', '' ],
			[ 'dropFkey', 'user_properties', 'up_user' ],
			[ 'addIndex', 'user_properties', 'user_properties_pkey', 'patch-user_properties-pk.sql' ],
			[ 'changeField', 'log_search', 'ls_value', 'VARCHAR(255)', '' ],
			[ 'changeField', 'content', 'content_id', 'BIGINT', '' ],
			[ 'changeField', 'l10n_cache', 'lc_value', 'TEXT', '' ],
			[ 'changeField', 'l10n_cache', 'lc_key', 'VARCHAR(255)', '' ],
			[ 'addIndex', 'l10n_cache', 'l10n_cache_pkey', 'patch-l10n_cache-pk.sql' ],
			[ 'addIndex', 'module_deps', 'module_deps_pkey', 'patch-module_deps-pk.sql' ],
			[ 'changeField', 'redirect', 'rd_namespace', 'INT', 'rd_namespace::INT DEFAULT 0' ],
			[ 'setDefault', 'redirect', 'rd_title', '' ],
			[ 'setDefault', 'redirect', 'rd_from', 0 ],
			[ 'dropFkey', 'redirect', 'rd_from' ],
			[ 'changeField', 'redirect', 'rd_interwiki', 'VARCHAR(32)', '' ],
			[ 'dropFkey', 'pagelinks', 'pl_from' ],
			[ 'changeField', 'pagelinks', 'pl_namespace', 'INT', 'pl_namespace::INT DEFAULT 0' ],
			[ 'setDefault', 'pagelinks', 'pl_title', '' ],
			[ 'addPgIndex', 'pagelinks', 'pl_namespace', '(pl_namespace,pl_title,pl_from)' ],
			[ 'addPgIndex', 'pagelinks', 'pl_backlinks_namespace',
				'(pl_from_namespace,pl_namespace,pl_title,pl_from)' ],
			[ 'dropPgIndex', 'pagelinks', 'pagelink_unique' ],
			[ 'dropPgIndex', 'pagelinks', 'pagelinks_title' ],
			[ 'dropFkey', 'templatelinks', 'tl_from' ],
			[ 'dropPgIndex', 'templatelinks', 'templatelinks_unique' ],
			[ 'dropPgIndex', 'templatelinks', 'templatelinks_from' ],
			[ 'dropFkey', 'imagelinks', 'il_from' ],
			[ 'setDefault', 'imagelinks', 'il_to', '' ],
			[ 'addPgIndex', 'imagelinks', 'il_to', '(il_to, il_from)' ],
			[ 'addPgIndex', 'imagelinks', 'il_backlinks_namespace',
				'(il_from_namespace, il_to, il_from)' ],
			[ 'dropPgIndex', 'imagelinks', 'il_from' ],
			[ 'dropFkey', 'langlinks', 'll_from' ],
			[ 'addIndex', 'langlinks', 'langlinks_pkey', 'patch-langlinks-pk.sql' ],
			[ 'renameIndex', 'langlinks', 'langlinks_lang_title', 'll_lang' ],
			[ 'setDefault', 'langlinks', 'll_lang', '' ],
			[ 'setDefault', 'langlinks', 'll_from', 0 ],
			[ 'setDefault', 'langlinks', 'll_title', '' ],
			[ 'changeNullableField', 'langlinks', 'll_lang', 'NOT NULL', true ],
			[ 'changeNullableField', 'langlinks', 'll_title', 'NOT NULL', true ],
			[ 'addIndex', 'iwlinks', 'iwlinks_pkey', 'patch-iwlinks-pk.sql' ],
			[ 'renameIndex', 'category', 'category_title', 'cat_title' ],
			[ 'renameIndex', 'category', 'category_pages', 'cat_pages' ],
			[ 'dropSequence', 'watchlist_expiry', 'watchlist_expiry_we_item_seq' ],
			[ 'changeField', 'change_tag_def', 'ctd_count', 'BIGINT', 'ctd_count::BIGINT DEFAULT 0' ],
			[ 'dropDefault', 'change_tag_def', 'ctd_user_defined' ],
			[ 'dropFkey', 'ipblocks_restrictions', 'ir_ipb_id' ],
			[ 'setDefault', 'querycache', 'qc_value', 0 ],
			[ 'changeField', 'querycache', 'qc_namespace', 'INT', 'qc_namespace::INT DEFAULT 0' ],
			[ 'setDefault', 'querycache', 'qc_title', '' ],
			[ 'renameIndex', 'querycache', 'querycache_type_value', 'qc_type' ],
			[ 'renameIndex', 'querycachetwo', 'querycachetwo_type_value', 'qcc_type' ],
			[ 'renameIndex', 'querycachetwo', 'querycachetwo_title', 'qcc_title' ],
			[ 'renameIndex', 'querycachetwo', 'querycachetwo_titletwo', 'qcc_titletwo' ],
			[ 'dropFkey', 'page_restrictions', 'pr_page' ],
			[ 'addPgIndex', 'page_restrictions', 'pr_pagetype', '(pr_page, pr_type)', true ],
			[ 'addPgIndex', 'page_restrictions', 'pr_typelevel', '(pr_type, pr_level)' ],
			[ 'addPgIndex', 'page_restrictions', 'pr_level', '(pr_level)' ],
			[ 'addPgIndex', 'page_restrictions', 'pr_cascade', '(pr_cascade)' ],
			[ 'changePrimaryKey', 'page_restrictions', [ 'pr_id' ], 'page_restrictions_pk' ] ,
			[ 'changeNullableField', 'page_restrictions', 'pr_page', 'NOT NULL', true ],
			[ 'dropFkey', 'user_groups', 'ug_user' ],
			[ 'setDefault', 'user_groups', 'ug_user', 0 ],
			[ 'setDefault', 'user_groups', 'ug_group', '' ],
			[ 'renameIndex', 'user_groups', 'user_groups_group', 'ug_group' ],
			[ 'renameIndex', 'user_groups', 'user_groups_expiry', 'ug_expiry' ],
			[ 'setDefault', 'querycache_info', 'qci_type', '' ],
			[ 'setDefault', 'querycache_info', 'qci_timestamp', '1970-01-01 00:00:00+00' ],
			[ 'changeNullableField', 'querycache_info', 'qci_type', 'NOT NULL', true ],
			[ 'changeNullableField', 'querycache_info', 'qci_timestamp', 'NOT NULL', true ],
			[ 'addIndex', 'querycache_info', 'querycache_info_pkey', 'patch-querycache_info-pk.sql' ],
			[ 'setDefault', 'watchlist', 'wl_title', '' ],
			[ 'changeField', 'watchlist', 'wl_namespace', 'INT', 'wl_namespace::INT DEFAULT 0' ],
			[ 'dropFkey', 'watchlist', 'wl_user' ],
			[ 'dropPgIndex', 'watchlist', 'wl_user_namespace_title' ],
			[ 'addPgIndex', 'watchlist', 'namespace_title', '(wl_namespace, wl_title)' ],
			[ 'checkIndex', 'wl_user', [
				[ 'wl_user', 'text_ops', 'btree', 1 ],
				[ 'wl_namespace', 'int4_ops', 'btree', 1 ],
				[ 'wl_title', 'text_ops', 'btree', 1 ],
			],
				'CREATE UNIQUE INDEX "wl_user" ON "watchlist" (wl_user, wl_namespace, wl_title) ' ],
			[ 'changeField', 'sites', 'site_domain', 'VARCHAR(255)', '' ],
			[ 'renameIndex', 'sites', 'site_global_key', 'sites_global_key' ],
			[ 'renameIndex', 'sites', 'site_type', 'sites_type' ],
			[ 'renameIndex', 'sites', 'site_group', 'sites_group' ],
			[ 'renameIndex', 'sites', 'site_source', 'sites_source' ],
			[ 'renameIndex', 'sites', 'site_language', 'sites_language' ],
			[ 'renameIndex', 'sites', 'site_protocol', 'sites_protocol' ],
			[ 'renameIndex', 'sites', 'site_domain', 'sites_domain' ],
			[ 'renameIndex', 'sites', 'site_forward', 'sites_forward' ],
			[ 'dropFkey', 'user_newtalk', 'user_id' ],
			[ 'renameIndex', 'user_newtalk', 'user_newtalk_id', 'un_user_id' ],
			[ 'renameIndex', 'user_newtalk', 'user_newtalk_ip', 'un_user_ip' ],
			[ 'changeField', 'interwiki', 'iw_prefix', 'VARCHAR(32)', '' ],
			[ 'changeField', 'interwiki', 'iw_wikiid', 'VARCHAR(64)', '' ],
			[ 'dropFkey', 'protected_titles', 'pt_user' ],
			[ 'changeNullableField', 'protected_titles', 'pt_user', 'NOT NULL', true ],
			[ 'changeNullableField', 'protected_titles', 'pt_expiry', 'NOT NULL', true ],
			[ 'changeField', 'protected_titles', 'pt_reason_id', 'BIGINT', '' ],
			[ 'dropDefault', 'protected_titles', 'pt_create_perm' ],
			[ 'dropFkey', 'externallinks', 'el_from' ],
			[ 'setDefault', 'externallinks', 'el_from', 0 ],
			[ 'changeField', 'externallinks', 'el_index_60', 'TEXT', '' ],
			[ 'renameIndex', 'externallinks', 'externallinks_from_to', 'el_from' ],
			[ 'renameIndex', 'externallinks', 'externallinks_index', 'el_index' ],
			[ 'addPgIndex', 'externallinks', 'el_to', '(el_to, el_from)' ],
			[ 'dropSequence', 'ip_changes', 'ip_changes_ipc_rev_id_seq' ],
			[ 'changeField', 'ip_changes', 'ipc_hex', 'TEXT', "ipc_hex::TEXT DEFAULT ''" ],
			[ 'setDefault', 'ip_changes', 'ipc_rev_id', 0 ],
			[ 'changeField', 'revision_comment_temp', 'revcomment_comment_id', 'BIGINT', '' ],
			[ 'renameIndex', 'watchlist', 'namespace_title', 'wl_namespace_title' ],
			[ 'dropFkey', 'page_props', 'pp_page' ],
			// page_props primary key change moved from the Schema SQL file to here in 1.36
			[ 'changePrimaryKey', 'page_props', [ 'pp_page', 'pp_propname' ], 'page_props_pk' ],
			[ 'setDefault','job', 'job_cmd', '' ],
			[ 'changeField', 'job', 'job_namespace', 'INTEGER', '' ],
			[ 'dropPgIndex', 'job', 'job_cmd_namespace_title' ],
			[ 'addPgIndex', 'job', 'job_cmd', '(job_cmd, job_namespace, job_title, job_params)' ],
			[ 'renameIndex', 'job', 'job_timestamp_idx', 'job_timestamp' ],
			[ 'changeField', 'slot_roles', 'role_id', 'INTEGER', '' ],
			[ 'changeField', 'content_models', 'model_id', 'INTEGER', '' ],
			[ 'renameIndex', 'page', 'page_len_idx', 'page_len' ],
			[ 'renameIndex', 'page', 'page_random_idx', 'page_random' ],
			[ 'renameIndex', 'page', 'page_unique_name', 'page_name_title' ],
			[ 'addPGIndex', 'page', 'page_redirect_namespace_len', '(page_is_redirect, page_namespace, page_len)' ],
			[ 'dropFkey', 'categorylinks', 'cl_from' ],
			[ 'setDefault','categorylinks', 'cl_from', 0 ],
			[ 'setDefault','categorylinks', 'cl_to', '' ],
			[ 'setDefault','categorylinks', 'cl_sortkey', '' ],
			[ 'setDefault','categorylinks', 'cl_collation', '' ],
			[ 'changeNullableField', 'categorylinks', 'cl_sortkey', 'NOT NULL', true ],
			[ 'addIndex', 'categorylinks', 'categorylinks_pkey', 'patch-categorylinks-pk.sql' ],
			[ 'addPgIndex', 'categorylinks', 'cl_timestamp', '(cl_to, cl_timestamp)' ],
			[ 'addPgIndex', 'categorylinks', 'cl_collation_ext', '(cl_collation, cl_to, cl_type, cl_from)' ],
			[ 'checkIndex', 'cl_sortkey', [
				[ 'cl_to', 'text_ops', 'btree', 1 ],
				[ 'cl_type', 'text_ops', 'btree', 1 ],
				[ 'cl_sortkey', 'text_ops', 'btree', 1 ],
				[ 'cl_from', 'text_ops', 'btree', 1 ],
			],
				'CREATE INDEX cl_sortkey ON categorylinks (cl_to, cl_type, cl_sortkey, cl_from)' ],
			[ 'renameIndex', 'logging', 'logging_type_name', 'type_time' ],
			[ 'renameIndex', 'logging', 'logging_actor_time_backwards',	'actor_time' ],
			[ 'renameIndex', 'logging', 'logging_page_time', 'page_time' ],
			[ 'renameIndex', 'logging', 'logging_times', 'times' ],
			[ 'renameIndex', 'logging', 'logging_actor_type_time', 'log_actor_type_time' ],
			[ 'renameIndex', 'logging', 'logging_page_id_time', 'log_page_id_time' ],
			[ 'renameIndex', 'logging', 'logging_type_action', 'log_type_action' ],
			[ 'changeNullableField', 'logging', 'log_params', 'NOT NULL', true ],
			[ 'setDefault', 'logging', 'log_action', '' ],
			[ 'setDefault', 'logging', 'log_type', '' ],
			[ 'setDefault', 'logging', 'log_title', '' ],
			[ 'setDefault', 'logging', 'log_timestamp', '1970-01-01 00:00:00+00' ],
			[ 'changeField', 'logging', 'log_actor', 'BIGINT', '' ],
			[ 'changeField', 'logging', 'log_comment_id', 'BIGINT', '' ],
			[ 'changeField', 'logging', 'log_namespace', 'INT', 'log_namespace::INT DEFAULT 0' ],
			[ 'dropPgIndex', 'logging', 'logging_actor_time' ],
			[ 'changeField', 'uploadstash', 'us_key', 'VARCHAR(255)', '' ],
			[ 'changeField', 'uploadstash', 'us_orig_path', 'VARCHAR(255)', '' ],
			[ 'changeField', 'uploadstash', 'us_path', 'VARCHAR(255)', '' ],
			[ 'changeField', 'uploadstash', 'us_source_type', 'VARCHAR(50)', '' ],
			[ 'changeField', 'uploadstash', 'us_props', 'TEXT', '' ],
			[ 'changeField', 'uploadstash', 'us_status', 'VARCHAR(50)', '' ],
			[ 'changeField', 'uploadstash', 'us_sha1', 'VARCHAR(31)', '' ],
			[ 'changeField', 'uploadstash', 'us_mime', 'VARCHAR(255)', '' ],
			[ 'changeNullableField', 'uploadstash', 'us_key', 'NOT NULL', true ],
			[ 'changeNullableField', 'uploadstash', 'us_user', 'NOT NULL', true ],
			[ 'changeNullableField', 'uploadstash', 'us_orig_path', 'NOT NULL', true ],
			[ 'changeNullableField', 'uploadstash', 'us_path', 'NOT NULL', true ],
			[ 'changeNullableField', 'uploadstash', 'us_timestamp', 'NOT NULL', true ],
			[ 'changeNullableField', 'uploadstash', 'us_status', 'NOT NULL', true ],
			[ 'changeNullableField', 'uploadstash', 'us_size', 'NOT NULL', true ],
			[ 'changeNullableField', 'uploadstash', 'us_sha1', 'NOT NULL', true ],
			[ 'renameIndex', 'uploadstash', 'us_user_idx', 'us_user' ],
			[ 'renameIndex', 'uploadstash', 'us_key_idx', 'us_key' ],
			[ 'renameIndex', 'uploadstash', 'us_timestamp_idx', 'us_timestamp' ],
			[ 'renameIndex', 'user_properties', 'user_properties_property', 'up_property' ],
			[ 'renameIndex', 'sites', 'sites_global_key', 'site_global_key' ],
			[ 'renameIndex', 'sites', 'sites_type', 'site_type' ],
			[ 'renameIndex', 'sites', 'sites_group, ', 'site_group' ],
			[ 'renameIndex', 'sites', 'sites_source', 'site_source' ],
			[ 'renameIndex', 'sites', 'sites_language', 'site_language' ],
			[ 'renameIndex', 'sites', 'sites_protocol', 'site_protocol' ],
			[ 'renameIndex', 'sites', 'sites_domain', 'site_domain' ],
			[ 'renameIndex', 'sites', 'sites_forward', 'site_forward' ],
			[ 'renameIndex', 'logging', 'type_name', 'log_type_time' ],
			[ 'renameIndex', 'logging', 'actor_time', 'log_actor_time' ],
			[ 'renameIndex', 'logging', 'page_time', 'log_page_time' ],
			[ 'renameIndex', 'logging', 'times', 'log_times' ],
			[ 'setDefault', 'filearchive', 'fa_name', '' ],
			[ 'setDefault', 'filearchive', 'fa_archive_name', '' ],
			[ 'setDefault', 'filearchive', 'fa_storage_key', '' ],
			[ 'dropFkey', 'filearchive', 'fa_deleted_user' ],
			[ 'changeField', 'filearchive', 'fa_deleted_reason_id', 'BIGINT', '' ],
			[ 'changeField', 'filearchive', 'fa_metadata', 'TEXT', '' ],
			[ 'changeField', 'filearchive', 'fa_bits', 'INTEGER', '' ],
			[ 'changeField', 'filearchive', 'fa_description_id', 'BIGINT', '' ],
			[ 'changeField', 'filearchive', 'fa_actor', 'BIGINT', '' ],
			[ 'renameIndex', 'filearchive', 'fa_name_time', 'fa_name' ],
			[ 'renameIndex', 'filearchive', 'fa_dupe', 'fa_storage_group' ],
			[ 'renameIndex', 'filearchive', 'fa_notime', 'fa_deleted_timestamp' ],
			[ 'dropPgIndex', 'filearchive', 'fa_nouser' ],
			[ 'addPgIndex', 'filearchive', 'fa_actor_timestamp', '(fa_actor, fa_timestamp)' ],
			[ 'addPgIndex', 'ipblocks', 'ipb_expiry', '(ipb_expiry)' ],
			[ 'addPgIndex', 'ipblocks', 'ipb_timestamp', '(ipb_timestamp)' ],
			[ 'renameIndex', 'text', 'pagecontent_pkey', 'text_pkey' ],
			[ 'changeNullableField', 'text', 'old_text', 'NOT NULL', true ],
			[ 'changeNullableField', 'text', 'old_flags', 'NOT NULL', true ],
			[ 'setDefault', 'oldimage', 'oi_name', '' ],
			[ 'setDefault', 'oldimage', 'oi_archive_name', '' ],
			[ 'setDefault', 'oldimage', 'oi_size', 0 ],
			[ 'setDefault', 'oldimage', 'oi_width', 0 ],
			[ 'setDefault', 'oldimage', 'oi_height', 0 ],
			[ 'setDefault', 'oldimage', 'oi_bits', 0 ],
			[ 'setDefault', 'oldimage', 'oi_name', '' ],
			[ 'changeField', 'oldimage', 'oi_bits', 'INTEGER', '' ],
			[ 'changeField', 'oldimage', 'oi_description_id', 'BIGINT', '' ],
			[ 'changeField', 'oldimage', 'oi_actor', 'BIGINT', '' ],
			[ 'changeField', 'oldimage', 'oi_metadata', 'TEXT', '' ],
			[ 'dropDefault', 'oldimage', 'oi_metadata' ],
			[ 'changeNullableField', 'oldimage', 'oi_minor_mime', 'NOT NULL', true ],
			[ 'changeNullableField', 'oldimage', 'oi_minor_mime', 'NOT NULL', true ],
			[ 'dropFkey', 'oldimage', 'oi_name' ],
			[ 'addPgIndex', 'oldimage', 'oi_actor_timestamp', '(oi_actor, oi_timestamp)' ],
			[ 'dropPgIndex', 'recentchanges', 'rc_timestamp_bot' ],
			[ 'addPgIndex', 'recentchanges', 'rc_ns_actor', '(rc_namespace, rc_actor)' ],
			[ 'addPgIndex', 'recentchanges', 'rc_actor', '(rc_actor, rc_timestamp)' ],
			[ 'dropIndex', 'objectcache', 'keyname', 'patch-objectcache_keyname-pk.sql' ],
			[ 'changeField', 'objectcache', 'value', 'TEXT', '' ],
			[ 'changeNullableField', 'objectcache', 'value', 'NULL', true ],
			[ 'dropFkey', 'ipblocks', 'ipb_user' ],
			[ 'dropFkey', 'ipblocks', 'ipb_parent_block_id' ],
			[ 'setDefault', 'ipblocks', 'ipb_user', 0 ],
			[ 'changeNullableField', 'ipblocks', 'ipb_user', 'NOT NULL', true ],
			[ 'changeNullableField', 'ipblocks', 'ipb_range_start', 'NOT NULL', true ],
			[ 'changeNullableField', 'ipblocks', 'ipb_range_end', 'NOT NULL', true ],
			[ 'changeField', 'ipblocks', 'ipb_by_actor', 'BIGINT', '' ],
			[ 'changeField', 'ipblocks', 'ipb_reason_id', 'BIGINT', '' ],
			[ 'renameIndex', 'archive', 'archive_name_title_timestamp', 'ar_name_title_timestamp' ],
			[ 'dropPgIndex', 'archive', 'archive_actor' ],
			[ 'addPgIndex', 'archive', 'ar_actor_timestamp', '(ar_actor,ar_timestamp)' ],
			[ 'setDefault', 'image', 'img_name', '' ],
			[ 'setDefault', 'image', 'img_size', 0 ],
			[ 'setDefault', 'image', 'img_width', 0 ],
			[ 'setDefault', 'image', 'img_height', 0 ],
			[ 'setDefault', 'image', 'img_bits', 0 ],
			[ 'changeField', 'image', 'img_bits', 'INTEGER', '' ],
			[ 'changeField', 'image', 'img_description_id', 'BIGINT', '' ],
			[ 'changeField', 'image', 'img_actor', 'BIGINT', '' ],
			[ 'changeField', 'image', 'img_metadata', 'TEXT', '' ],
			[ 'dropDefault', 'image', 'img_metadata' ],
			[ 'changeNullableField', 'image', 'img_major_mime', 'NOT NULL', true ],
			[ 'changeNullableField', 'image', 'img_minor_mime', 'NOT NULL', true ],
			[ 'changeNullableField', 'image', 'img_timestamp', 'NOT NULL', true ],
			[ 'renameIndex', 'image', 'img_size_idx', 'img_size' ],
			[ 'renameIndex', 'image', 'img_timestamp_idx', 'img_timestamp' ],
			[ 'addPgIndex', 'image', 'img_actor_timestamp', '(img_actor, img_timestamp)' ],
			[ 'addPgIndex', 'image', 'img_media_mime', '(img_media_type, img_major_mime, img_minor_mime)' ],
			[ 'renameIndex', 'site_identifiers', 'site_ids_site', 'si_site' ],
			[ 'renameIndex', 'site_identifiers', 'site_ids_key', 'si_key' ],
			[ 'changeField', 'recentchanges', 'rc_actor', 'BIGINT', '' ],
			[ 'changeField', 'recentchanges', 'rc_comment_id', 'BIGINT', '' ],
			[ 'changeField', 'recentchanges', 'rc_ip', 'TEXT', '' ],
			[ 'changeField', 'recentchanges', 'rc_namespace', 'INTEGER', '' ],
			[ 'setDefault', 'recentchanges', 'rc_title', '' ],
			[ 'setDefault', 'recentchanges', 'rc_source', '' ],
			[ 'setDefault', 'recentchanges', 'rc_ip', '' ],
			[ 'setDefault', 'recentchanges', 'rc_namespace', 0 ],
			[ 'setDefault', 'recentchanges', 'rc_cur_id', 0 ],
			[ 'setDefault', 'recentchanges', 'rc_this_oldid', 0 ],
			[ 'setDefault', 'recentchanges', 'rc_last_oldid', 0 ],
			[ 'changeNullableField', 'recentchanges', 'rc_cur_id', 'NOT NULL', true ],
			[ 'changeNullableField', 'recentchanges', 'rc_ip', 'NOT NULL', true ],
			[ 'renameIndex', 'recentchanges', 'new_name_timestamp', 'rc_new_name_timestamp', false,
				'patch-recentchanges-rc_new_name_timestamp.sql' ],
			[ 'changeField', 'archive', 'ar_namespace', 'INTEGER', '' ],
			[ 'setDefault', 'archive', 'ar_namespace', 0 ],
			[ 'setDefault', 'archive', 'ar_title', '' ],
			[ 'changeField', 'archive', 'ar_comment_id', 'BIGINT', '' ],
			[ 'changeField', 'archive', 'ar_actor', 'BIGINT', '' ],
			[ 'renameIndex', 'user', 'user_email_token_idx', 'user_email_token' ],
			[ 'addPgIndex', 'user', 'user_email', '(user_email)' ],
			[ 'addPgIndex', 'user', 'user_name', '(user_name)', true ],
			[ 'changeField', 'page', 'page_namespace', 'INTEGER', '' ],
			[ 'changeNullableField', 'page', 'page_touched', 'NOT NULL', true ],
			[ 'changeField', 'page', 'page_random', 'FLOAT', '' ],
			[ 'renameIndex', 'revision', 'revision_unique', 'rev_page_id' ],
			[ 'renameIndex', 'revision', 'rev_timestamp_idx', 'rev_timestamp' ],
			[ 'addPgIndex', 'revision', 'rev_page_timestamp', '(rev_page,rev_timestamp)' ],
			[ 'changeNullableField', 'mwuser', 'user_touched', 'NOT NULL', true ],

			// 1.37
			[ 'changeNullableField', 'user', 'user_token', 'NOT NULL', true ],
			[ 'changeNullableField', 'user', 'user_real_name', 'NOT NULL', true ],
			[ 'changeNullableField', 'user', 'user_email', 'NOT NULL', true ],
			[ 'changeNullableField', 'user', 'user_newpassword', 'NOT NULL', true ],
			[ 'changeNullableField', 'user', 'user_password', 'NOT NULL', true ],
			[ 'setDefault', 'user', 'user_name', '' ],
			[ 'setDefault', 'user', 'user_token', '' ],
			[ 'setDefault', 'user', 'user_real_name', '' ],
			[ 'dropConstraint', 'user', 'user_name', 'unique' ],
			[ 'addField', 'objectcache', 'modtoken', 'patch-objectcache-modtoken.sql' ],
			[ 'dropFkey', 'revision', 'rev_page' ],
			[ 'changeNullableField', 'revision', 'rev_page', 'NOT NULL', true ],
			[ 'changeField', 'revision', 'rev_comment_id', 'BIGINT', 'rev_comment_id::BIGINT DEFAULT 0' ],
			[ 'changeField', 'revision', 'rev_actor', 'BIGINT', 'rev_actor::BIGINT DEFAULT 0' ],
			[ 'checkIndex', 'rev_page_id', [
				[ 'rev_page', 'int4_ops', 'btree', 1 ],
				[ 'rev_id', 'int4_ops', 'btree', 1 ],
			],
				'CREATE INDEX rev_page_id ON revision (rev_page,rev_id)' ],
			[ 'addTable', 'searchindex', 'patch-searchindex-table.sql' ],
			[ 'addPgIndex', 'oldimage', 'oi_timestamp', '(oi_timestamp)' ],
			[ 'renameIndex', 'page', 'name_title', 'page_name_title' ],
			[ 'renameIndex', 'change_tag', 'change_tag_rc_tag_id', 'ct_rc_tag_id' ],
			[ 'renameIndex', 'change_tag', 'change_tag_log_tag_id', 'ct_log_tag_id' ],
			[ 'renameIndex', 'change_tag', 'change_tag_rev_tag_id', 'ct_rev_tag_id' ],
			[ 'renameIndex', 'change_tag', 'change_tag_tag_id_id', 'ct_tag_id_id' ],

			// 1.38
			[ 'doConvertDjvuMetadata' ],
			[ 'dropPgField', 'page_restrictions', 'pr_user' ],
			[ 'addTable', 'linktarget', 'patch-linktarget.sql' ],
			[ 'dropIndex', 'revision', 'rev_page_id', 'patch-drop-rev_page_id.sql' ],
			[ 'addField', 'templatelinks', 'tl_target_id', 'patch-templatelinks-target_id.sql' ],

			// 1.39
			[ 'addTable', 'user_autocreate_serial', 'patch-user_autocreate_serial.sql' ],
			[ 'runMaintenance', MigrateRevisionActorTemp::class, 'maintenance/migrateRevisionActorTemp.php' ],
			[ 'dropTable', 'revision_actor_temp' ],
			[ 'runMaintenance', UpdateRestrictions::class, 'maintenance/updateRestrictions.php' ],
			[ 'dropPgField', 'page', 'page_restrictions' ],
			[ 'migrateTemplatelinks' ],
			[ 'changeNullableField', 'templatelinks', 'tl_target_id', 'NOT NULL', true ],
			[ 'changePrimaryKey', 'templatelinks', [ 'tl_from', 'tl_target_id' ] ],
			[ 'dropField', 'templatelinks', 'tl_title', 'patch-templatelinks-drop-tl_title.sql' ],
		];
	}

	protected function describeTable( $table ) {
		$q = <<<END
SELECT attname, attnum FROM pg_namespace, pg_class, pg_attribute
	WHERE pg_class.relnamespace = pg_namespace.oid
		AND attrelid=pg_class.oid AND attnum > 0
		AND relname=%s AND nspname=%s
END;
		$res = $this->db->query(
			sprintf( $q,
				$this->db->addQuotes( $table ),
				$this->db->addQuotes( $this->db->getCoreSchema() )
			),
			__METHOD__
		);
		if ( !$res ) {
			return null;
		}

		$cols = [];
		foreach ( $res as $r ) {
			$cols[] = [
				"name" => $r[0],
				"ord" => $r[1],
			];
		}

		return $cols;
	}

	protected function describeIndex( $idx ) {
		// first fetch the key (which is a list of columns ords) and
		// the table the index applies to (an oid)
		$q = <<<END
SELECT indkey, indrelid FROM pg_namespace, pg_class, pg_index
	WHERE nspname=%s
		AND pg_class.relnamespace = pg_namespace.oid
		AND relname=%s
		AND indexrelid=pg_class.oid
END;
		$res = $this->db->query(
			sprintf(
				$q,
				$this->db->addQuotes( $this->db->getCoreSchema() ),
				$this->db->addQuotes( $idx )
			),
			__METHOD__
		);
		if ( !$res ) {
			return null;
		}
		$r = $res->fetchRow();
		if ( !$r ) {
			return null;
		}

		$indkey = $r[0];
		$relid = intval( $r[1] );
		$indkeys = explode( ' ', $indkey );

		$colnames = [];
		foreach ( $indkeys as $rid ) {
			$query = <<<END
SELECT attname FROM pg_class, pg_attribute
	WHERE attrelid=$relid
		AND attnum=%d
		AND attrelid=pg_class.oid
END;
			$r2 = $this->db->query( sprintf( $query, $rid ), __METHOD__ );
			if ( !$r2 ) {
				return null;
			}
			$row2 = $r2->fetchRow();
			if ( !$row2 ) {
				return null;
			}
			$colnames[] = $row2[0];
		}

		return $colnames;
	}

	protected function fkeyDeltype( $fkey ) {
		$q = <<<END
SELECT confdeltype FROM pg_constraint, pg_namespace
	WHERE connamespace=pg_namespace.oid
		AND nspname=%s
		AND conname=%s;
END;
		$r = $this->db->query(
			sprintf(
				$q,
				$this->db->addQuotes( $this->db->getCoreSchema() ),
				$this->db->addQuotes( $fkey )
			),
			__METHOD__
		);
		$row = $r->fetchRow();
		if ( !$row ) {
			return null;
		}

		return $row[0];
	}

	protected function ruleDef( $table, $rule ) {
		$q = <<<END
SELECT definition FROM pg_rules
	WHERE schemaname = %s
		AND tablename = %s
		AND rulename = %s
END;
		$r = $this->db->query(
			sprintf(
				$q,
				$this->db->addQuotes( $this->db->getCoreSchema() ),
				$this->db->addQuotes( $table ),
				$this->db->addQuotes( $rule )
			),
			__METHOD__
		);
		$row = $r->fetchRow();
		if ( !$row ) {
			return null;
		}
		$d = $row[0];

		return $d;
	}

	protected function addSequence( $table, $pkey, $ns ) {
		if ( !$this->db->sequenceExists( $ns ) ) {
			$this->output( "Creating sequence $ns\n" );
			if ( $pkey !== false ) {
				$table = $this->db->addIdentifierQuotes( $table );
				$this->db->query( "CREATE SEQUENCE $ns OWNED BY $table.$pkey", __METHOD__ );
				$this->setDefault( $table, $pkey, '"nextval"(\'"' . $ns . '"\'::"regclass")' );
			} else {
				$this->db->query( "CREATE SEQUENCE $ns", __METHOD__ );
			}
		}
	}

	protected function dropSequence( $table, $ns ) {
		if ( $this->db->sequenceExists( $ns ) ) {
			$this->output( "Dropping sequence $ns\n" );
			$this->db->query( "DROP SEQUENCE $ns CASCADE", __METHOD__ );
		}
	}

	protected function renameSequence( $old, $new ) {
		if ( $this->db->sequenceExists( $new ) ) {
			$this->output( "...sequence $new already exists.\n" );

			return;
		}
		if ( $this->db->sequenceExists( $old ) ) {
			$this->output( "Renaming sequence $old to $new\n" );
			$this->db->query( "ALTER SEQUENCE $old RENAME TO $new", __METHOD__ );
		}
	}

	protected function setSequenceOwner( $table, $pkey, $seq ) {
		if ( $this->db->sequenceExists( $seq ) ) {
			$this->output( "Setting sequence $seq owner to $table.$pkey\n" );
			$table = $this->db->addIdentifierQuotes( $table );
			$this->db->query( "ALTER SEQUENCE $seq OWNED BY $table.$pkey", __METHOD__ );
		}
	}

	protected function renameTable( $old, $new, $patch = false ) {
		if ( $this->db->tableExists( $old, __METHOD__ ) ) {
			$this->output( "Renaming table $old to $new\n" );
			$old = $this->db->addIdentifierQuotes( $old );
			$new = $this->db->addIdentifierQuotes( $new );
			$this->db->query( "ALTER TABLE $old RENAME TO $new", __METHOD__ );
			if ( $patch !== false ) {
				$this->applyPatch( $patch );
			}
		}
	}

	protected function renameIndex(
		$table, $old, $new, $skipBothIndexExistWarning = false, $a = false, $b = false
	) {
		// First requirement: the table must exist
		if ( !$this->db->tableExists( $table, __METHOD__ ) ) {
			$this->output( "...skipping: '$table' table doesn't exist yet.\n" );

			return true;
		}

		// Second requirement: the new index must be missing
		if ( $this->db->indexExists( $table, $new, __METHOD__ ) ) {
			$this->output( "...index $new already set on $table table.\n" );
			if ( !$skipBothIndexExistWarning
				&& $this->db->indexExists( $table, $old, __METHOD__ )
			) {
				$this->output( "...WARNING: $old still exists, despite it has been " .
					"renamed into $new (which also exists).\n" .
					"            $old should be manually removed if not needed anymore.\n" );
			}

			return true;
		}

		// Third requirement: the old index must exist
		if ( !$this->db->indexExists( $table, $old, __METHOD__ ) ) {
			$this->output( "...skipping: index $old doesn't exist.\n" );

			return true;
		}

		$this->db->query( "ALTER INDEX $old RENAME TO $new", __METHOD__ );
		return true;
	}

	protected function dropPgField( $table, $field ) {
		$fi = $this->db->fieldInfo( $table, $field );
		if ( $fi === null ) {
			$this->output( "...$table table does not contain $field field.\n" );
		} else {
			$this->output( "Dropping column '$table.$field'\n" );
			$table = $this->db->addIdentifierQuotes( $table );
			$this->db->query( "ALTER TABLE $table DROP COLUMN $field", __METHOD__ );
		}
	}

	protected function addPgField( $table, $field, $type ) {
		$fi = $this->db->fieldInfo( $table, $field );
		if ( $fi !== null ) {
			$this->output( "...column '$table.$field' already exists\n" );
		} else {
			$this->output( "Adding column '$table.$field'\n" );
			$table = $this->db->addIdentifierQuotes( $table );
			$this->db->query( "ALTER TABLE $table ADD $field $type", __METHOD__ );
		}
	}

	protected function changeField( $table, $field, $newtype, $default ) {
		$fi = $this->db->fieldInfo( $table, $field );
		if ( $fi === null ) {
			$this->output( "...ERROR: expected column $table.$field to exist\n" );
			exit( 1 );
		}

		if ( $fi->type() === strtolower( $newtype ) ) {
			$this->output( "...column '$table.$field' is already of type '$newtype'\n" );
		} else {
			$this->output( "Changing column type of '$table.$field' from '{$fi->type()}' to '$newtype'\n" );
			$table = $this->db->addIdentifierQuotes( $table );
			$sql = "ALTER TABLE $table ALTER $field TYPE $newtype";
			if ( strlen( $default ) ) {
				$res = [];
				if ( preg_match( '/DEFAULT (.+)/', $default, $res ) ) {
					$sqldef = "ALTER TABLE $table ALTER $field SET DEFAULT $res[1]";
					$this->db->query( $sqldef, __METHOD__ );
					$default = preg_replace( '/\s*DEFAULT .+/', '', $default );
				}
				$sql .= " USING $default";
			}
			$this->db->query( $sql, __METHOD__ );
		}
	}

	protected function changeFieldPurgeTable( $table, $field, $newtype, $default ) {
		# # For a cache table, empty it if the field needs to be changed, because the old contents
		# # may be corrupted.  If the column is already the desired type, refrain from purging.
		$fi = $this->db->fieldInfo( $table, $field );
		if ( $fi === null ) {
			$this->output( "...ERROR: expected column $table.$field to exist\n" );
			exit( 1 );
		}

		if ( $fi->type() === $newtype ) {
			$this->output( "...column '$table.$field' is already of type '$newtype'\n" );
		} else {
			$this->output( "Purging data from cache table '$table'\n" );
			$table = $this->db->addIdentifierQuotes( $table );
			$this->db->query( "DELETE from $table", __METHOD__ );
			$this->output( "Changing column type of '$table.$field' from '{$fi->type()}' to '$newtype'\n" );
			$sql = "ALTER TABLE $table ALTER $field TYPE $newtype";
			if ( strlen( $default ) ) {
				$res = [];
				if ( preg_match( '/DEFAULT (.+)/', $default, $res ) ) {
					$sqldef = "ALTER TABLE $table ALTER $field SET DEFAULT $res[1]";
					$this->db->query( $sqldef, __METHOD__ );
					$default = preg_replace( '/\s*DEFAULT .+/', '', $default );
				}
				$sql .= " USING $default";
			}
			$this->db->query( $sql, __METHOD__ );
		}
	}

	protected function setDefault( $table, $field, $default ) {
		$info = $this->db->fieldInfo( $table, $field );
		if ( $info && $info->defaultValue() !== $default ) {
			$this->output( "Changing '$table.$field' default value\n" );
			$table = $this->db->addIdentifierQuotes( $table );
			$this->db->query( "ALTER TABLE $table ALTER $field SET DEFAULT "
				. $this->db->addQuotes( $default ), __METHOD__ );
		}
	}

	/**
	 * Drop a default value from a field
	 * @since 1.32
	 * @param string $table
	 * @param string $field
	 */
	protected function dropDefault( $table, $field ) {
		$info = $this->db->fieldInfo( $table, $field );
		if ( $info->defaultValue() !== false ) {
			$this->output( "Removing '$table.$field' default value\n" );
			$table = $this->db->addIdentifierQuotes( $table );
			$this->db->query( "ALTER TABLE $table ALTER $field DROP DEFAULT", __METHOD__ );
		}
	}

	protected function changeNullableField( $table, $field, $null, $update = false ) {
		$fi = $this->db->fieldInfo( $table, $field );
		if ( $fi === null ) {
			return;
		}
		if ( $fi->isNullable() ) {
			# # It's NULL - does it need to be NOT NULL?
			if ( $null === 'NOT NULL' ) {
				$this->output( "Changing '$table.$field' to not allow NULLs\n" );
				$table = $this->db->addIdentifierQuotes( $table );
				if ( $update ) {
					$this->db->query( "UPDATE $table SET $field = DEFAULT WHERE $field IS NULL", __METHOD__ );
				}
				$this->db->query( "ALTER TABLE $table ALTER $field SET NOT NULL", __METHOD__ );
			} else {
				$this->output( "...column '$table.$field' is already set as NULL\n" );
			}
		} else {
			# # It's NOT NULL - does it need to be NULL?
			if ( $null === 'NULL' ) {
				$this->output( "Changing '$table.$field' to allow NULLs\n" );
				$table = $this->db->addIdentifierQuotes( $table );
				$this->db->query( "ALTER TABLE $table ALTER $field DROP NOT NULL", __METHOD__ );
			} else {
				$this->output( "...column '$table.$field' is already set as NOT NULL\n" );
			}
		}
	}

	protected function addPgIndex( $table, $index, $type, $unique = false ) {
		if ( $this->db->indexExists( $table, $index, __METHOD__ ) ) {
			$this->output( "...index '$index' on table '$table' already exists\n" );
		} else {
			$this->output( "Creating index '$index' on table '$table' $type\n" );
			$table = $this->db->addIdentifierQuotes( $table );
			$unique = $unique ? 'UNIQUE' : '';
			$this->db->query( "CREATE $unique INDEX $index ON $table $type", __METHOD__ );
		}
	}

	protected function addPgExtIndex( $table, $index, $type ) {
		if ( $this->db->indexExists( $table, $index, __METHOD__ ) ) {
			$this->output( "...index '$index' on table '$table' already exists\n" );
		} elseif ( preg_match( '/^\(/', $type ) ) {
			$this->output( "Creating index '$index' on table '$table'\n" );
			$table = $this->db->addIdentifierQuotes( $table );
			$this->db->query( "CREATE INDEX $index ON $table $type", __METHOD__ );
		} else {
			$this->applyPatch( $type, true, "Creating index '$index' on table '$table'" );
		}
	}

	/**
	 * Add a value to an existing PostgreSQL enum type
	 * @since 1.31
	 * @param string $type Type name. Must be in the core schema.
	 * @param string $value Value to add.
	 */
	protected function addPgEnumValue( $type, $value ) {
		$row = $this->db->selectRow(
			[
				't' => 'pg_catalog.pg_type',
				'n' => 'pg_catalog.pg_namespace',
				'e' => 'pg_catalog.pg_enum',
			],
			[ 't.typname', 't.typtype', 'e.enumlabel' ],
			[
				't.typname' => $type,
				'n.nspname' => $this->db->getCoreSchema(),
			],
			__METHOD__,
			[],
			[
				'n' => [ 'JOIN', 't.typnamespace = n.oid' ],
				'e' => [ 'LEFT JOIN', [ 'e.enumtypid = t.oid', 'e.enumlabel' => $value ] ],
			]
		);

		if ( !$row ) {
			$this->output( "...Type $type does not exist, skipping modify enum.\n" );
		} elseif ( $row->typtype !== 'e' ) {
			$this->output( "...Type $type does not seem to be an enum, skipping modify enum.\n" );
		} elseif ( $row->enumlabel === $value ) {
			$this->output( "...Enum type $type already contains value '$value'.\n" );
		} else {
			$this->output( "...Adding value '$value' to enum type $type.\n" );
			$etype = $this->db->addIdentifierQuotes( $type );
			$evalue = $this->db->addQuotes( $value );
			$this->db->query( "ALTER TYPE $etype ADD VALUE $evalue", __METHOD__ );
		}
	}

	protected function dropFkey( $table, $field ) {
		$fi = $this->db->fieldInfo( $table, $field );
		if ( $fi === null ) {
			$this->output( "WARNING! Column '$table.$field' does not exist but it should! " .
				"Please report this.\n" );
			return;
		}

		if ( $this->dropConstraint( $table, $field, 'foreignkey', $fi->conname() ) ) {
			$this->output( "Dropping foreign key constraint on '$table.$field'\n" );
		} else {
			$this->output( "...foreign key constraint on '$table.$field' already does not exist\n" );
		}
	}

	protected function changeFkeyDeferrable( $table, $field, $clause ) {
		$fi = $this->db->fieldInfo( $table, $field );
		if ( $fi === null ) {
			$this->output( "WARNING! Column '$table.$field' does not exist but it should! " .
				"Please report this.\n" );

			return;
		}
		if ( $fi->is_deferred() && $fi->is_deferrable() ) {
			return;
		}
		$this->output( "Altering column '$table.$field' to be DEFERRABLE INITIALLY DEFERRED\n" );

		$conname = $fi->conname();
		$conclause = "CONSTRAINT \"$conname\"";

		if ( !$this->dropConstraint( $table, $field, 'foreignkey', $conname ) ) {
			$this->output( "Column '$table.$field' does not have a foreign key " .
				"constraint, will be added\n" );
			$conclause = "";
		}

		$command =
			"ALTER TABLE $table ADD $conclause " .
			"FOREIGN KEY ($field) REFERENCES $clause DEFERRABLE INITIALLY DEFERRED";
		$this->db->query( $command, __METHOD__ );
	}

	protected function dropPgIndex( $table, $index ) {
		if ( $this->db->indexExists( $table, $index, __METHOD__ ) ) {
			$this->output( "Dropping obsolete index '$index'\n" );
			$this->db->query( "DROP INDEX \"" . $index . "\"", __METHOD__ );
		}
	}

	protected function checkIndex( $index, $should_be, $good_def ) {
		$pu = $this->db->indexAttributes( $index );
		if ( !empty( $pu ) && $pu != $should_be ) {
			$this->output( "Dropping obsolete version of index '$index'\n" );
			$this->db->query( "DROP INDEX \"" . $index . "\"", __METHOD__ );
			$pu = [];
		} else {
			$this->output( "...no need to drop index '$index'\n" );
		}

		if ( empty( $pu ) ) {
			$this->output( "Creating index '$index'\n" );
			$this->db->query( $good_def, __METHOD__ );
		} else {
			$this->output( "...index '$index' exists\n" );
		}
	}

	protected function changePrimaryKey( $table, $shouldBe, $constraintName = null ) {
		// https://wiki.postgresql.org/wiki/Retrieve_primary_key_columns
		$result = $this->db->query(
			"SELECT a.attname as column " .
				"FROM pg_index i " .
				"JOIN pg_attribute a ON a.attrelid = i.indrelid " .
				"AND a.attnum = ANY(i.indkey) " .
				"WHERE i.indrelid = '\"$table\"'::regclass " .
				"AND i.indisprimary",
			__METHOD__
		);
		$currentColumns = [];
		foreach ( $result as $row ) {
			$currentColumns[] = $row->column;
		}

		if ( $currentColumns == $shouldBe ) {
			$this->output( "...no need to change primary key of '$table'\n" );
			return true;
		}

		$this->dropConstraint( $table, '', 'primary', $constraintName );

		$table = $this->db->addIdentifierQuotes( $table );
		$this->db->query(
			"ALTER TABLE $table" .
			" ADD PRIMARY KEY (" . implode( ',', $shouldBe ) . ');',
			__METHOD__
		);
	}

	/**
	 * Drop generic constraint. If the constraint was created with a custom name,
	 * then the name must be queried and supplied as $conname, otherwise standard
	 * system suffixes and format would be assumed.
	 *
	 * @param string $table
	 * @param string $field
	 * @param string $type
	 * @param string|null $conname
	 * @return bool
	 */
	protected function dropConstraint( $table, $field, $type, $conname = null ) {
		if ( $conname === null ) {
			if ( $type == 'primary' ) {
				$conname = "{$table}_pkey";
			} else {
				$map = [ 'unique' => 'key', 'check' => 'check', 'foreignkey' => 'fkey' ];
				$conname = "{$table}_{$field}_{$map[$type]}";
			}
		}

		if ( $this->db->constraintExists( $table, $conname ) ) {
			$table = $this->db->addIdentifierQuotes( $table );
			$this->db->query(
				"ALTER TABLE $table DROP CONSTRAINT $conname;",
				__METHOD__
			);

			return true;
		}

		return false;
	}
}
