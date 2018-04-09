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
 * @ingroup Deployment
 */

use Wikimedia\Rdbms\DatabasePostgres;

/**
 * Class for handling updates to Postgres databases.
 *
 * @ingroup Deployment
 * @since 1.17
 */
class PostgresUpdater extends DatabaseUpdater {

	/**
	 * @var DatabasePostgres
	 */
	protected $db;

	/**
	 * @todo FIXME: Postgres should use sequential updates like Mysql, Sqlite
	 * and everybody else. It never got refactored like it should've.
	 * @return array
	 */
	protected function getCoreUpdateList() {
		return [
			# rename tables 1.7.3
			# r15791 Change reserved word table names "user" and "text"
			[ 'renameTable', 'user', 'mwuser' ],
			[ 'renameTable', 'text', 'pagecontent' ],
			[ 'renameIndex', 'mwuser', 'user_pkey', 'mwuser_pkey' ],
			[ 'renameIndex', 'mwuser', 'user_user_name_key', 'mwuser_user_name_key' ],
			[ 'renameIndex', 'pagecontent', 'text_pkey', 'pagecontent_pkey' ],

			# renamed sequences
			[ 'renameSequence', 'ipblocks_ipb_id_val', 'ipblocks_ipb_id_seq' ],
			[ 'renameSequence', 'rev_rev_id_val', 'revision_rev_id_seq' ],
			[ 'renameSequence', 'text_old_id_val', 'text_old_id_seq' ],
			[ 'renameSequence', 'rc_rc_id_seq', 'recentchanges_rc_id_seq' ],
			[ 'renameSequence', 'log_log_id_seq', 'logging_log_id_seq' ],
			[ 'renameSequence', 'pr_id_val', 'page_restrictions_pr_id_seq' ],
			[ 'renameSequence', 'us_id_seq', 'uploadstash_us_id_seq' ],

			# since r58263
			[ 'renameSequence', 'category_id_seq', 'category_cat_id_seq' ],

			# new sequences if not renamed above
			[ 'addSequence', 'logging', false, 'logging_log_id_seq' ],
			[ 'addSequence', 'page_restrictions', false, 'page_restrictions_pr_id_seq' ],
			[ 'addSequence', 'filearchive', 'fa_id', 'filearchive_fa_id_seq' ],
			[ 'addSequence', 'archive', false, 'archive_ar_id_seq' ],
			[ 'addSequence', 'externallinks', false, 'externallinks_el_id_seq' ],
			[ 'addSequence', 'watchlist', false, 'watchlist_wl_id_seq' ],
			[ 'addSequence', 'change_tag', false, 'change_tag_ct_id_seq' ],
			[ 'addSequence', 'tag_summary', false, 'tag_summary_ts_id_seq' ],

			# new tables
			[ 'addTable', 'category', 'patch-category.sql' ],
			[ 'addTable', 'page', 'patch-page.sql' ],
			[ 'addTable', 'querycachetwo', 'patch-querycachetwo.sql' ],
			[ 'addTable', 'page_props', 'patch-page_props.sql' ],
			[ 'addTable', 'page_restrictions', 'patch-page_restrictions.sql' ],
			[ 'addTable', 'profiling', 'patch-profiling.sql' ],
			[ 'addTable', 'protected_titles', 'patch-protected_titles.sql' ],
			[ 'addTable', 'redirect', 'patch-redirect.sql' ],
			[ 'addTable', 'updatelog', 'patch-updatelog.sql' ],
			[ 'addTable', 'change_tag', 'patch-change_tag.sql' ],
			[ 'addTable', 'tag_summary', 'patch-tag_summary.sql' ],
			[ 'addTable', 'valid_tag', 'patch-valid_tag.sql' ],
			[ 'addTable', 'user_properties', 'patch-user_properties.sql' ],
			[ 'addTable', 'log_search', 'patch-log_search.sql' ],
			[ 'addTable', 'l10n_cache', 'patch-l10n_cache.sql' ],
			[ 'addTable', 'iwlinks', 'patch-iwlinks.sql' ],
			[ 'addTable', 'module_deps', 'patch-module_deps.sql' ],
			[ 'addTable', 'uploadstash', 'patch-uploadstash.sql' ],
			[ 'addTable', 'user_former_groups', 'patch-user_former_groups.sql' ],
			[ 'addTable', 'sites', 'patch-sites.sql' ],
			[ 'addTable', 'bot_passwords', 'patch-bot_passwords.sql' ],

			# Needed before new field
			[ 'convertArchive2' ],

			# new fields
			[ 'addPgField', 'updatelog', 'ul_value', 'TEXT' ],
			[ 'addPgField', 'archive', 'ar_deleted', 'SMALLINT NOT NULL DEFAULT 0' ],
			[ 'addPgField', 'archive', 'ar_len', 'INTEGER' ],
			[ 'addPgField', 'archive', 'ar_page_id', 'INTEGER' ],
			[ 'addPgField', 'archive', 'ar_parent_id', 'INTEGER' ],
			[ 'addPgField', 'archive', 'ar_content_model', 'TEXT' ],
			[ 'addPgField', 'archive', 'ar_content_format', 'TEXT' ],
			[ 'addPgField', 'categorylinks', 'cl_sortkey_prefix', "TEXT NOT NULL DEFAULT ''" ],
			[ 'addPgField', 'categorylinks', 'cl_collation', "TEXT NOT NULL DEFAULT 0" ],
			[ 'addPgField', 'categorylinks', 'cl_type', "TEXT NOT NULL DEFAULT 'page'" ],
			[ 'addPgField', 'image', 'img_sha1', "TEXT NOT NULL DEFAULT ''" ],
			[ 'addPgField', 'ipblocks', 'ipb_allow_usertalk', 'SMALLINT NOT NULL DEFAULT 0' ],
			[ 'addPgField', 'ipblocks', 'ipb_anon_only', 'SMALLINT NOT NULL DEFAULT 0' ],
			[ 'addPgField', 'ipblocks', 'ipb_by_text', "TEXT NOT NULL DEFAULT ''" ],
			[ 'addPgField', 'ipblocks', 'ipb_block_email', 'SMALLINT NOT NULL DEFAULT 0' ],
			[ 'addPgField', 'ipblocks', 'ipb_create_account', 'SMALLINT NOT NULL DEFAULT 1' ],
			[ 'addPgField', 'ipblocks', 'ipb_deleted', 'SMALLINT NOT NULL DEFAULT 0' ],
			[ 'addPgField', 'ipblocks', 'ipb_enable_autoblock', 'SMALLINT NOT NULL DEFAULT 1' ],
			[ 'addPgField', 'ipblocks', 'ipb_parent_block_id',
				'INTEGER DEFAULT NULL REFERENCES ipblocks(ipb_id) ' .
				'ON DELETE SET NULL DEFERRABLE INITIALLY DEFERRED' ],
			[ 'addPgField', 'filearchive', 'fa_deleted', 'SMALLINT NOT NULL DEFAULT 0' ],
			[ 'addPgField', 'filearchive', 'fa_sha1', "TEXT NOT NULL DEFAULT ''" ],
			[ 'addPgField', 'logging', 'log_deleted', 'SMALLINT NOT NULL DEFAULT 0' ],
			[ 'addPgField', 'logging', 'log_id',
				"INTEGER NOT NULL PRIMARY KEY DEFAULT nextval('logging_log_id_seq')" ],
			[ 'addPgField', 'logging', 'log_params', 'TEXT' ],
			[ 'addPgField', 'mwuser', 'user_editcount', 'INTEGER' ],
			[ 'addPgField', 'mwuser', 'user_newpass_time', 'TIMESTAMPTZ' ],
			[ 'addPgField', 'oldimage', 'oi_deleted', 'SMALLINT NOT NULL DEFAULT 0' ],
			[ 'addPgField', 'oldimage', 'oi_major_mime', "TEXT NOT NULL DEFAULT 'unknown'" ],
			[ 'addPgField', 'oldimage', 'oi_media_type', 'TEXT' ],
			[ 'addPgField', 'oldimage', 'oi_metadata', "BYTEA NOT NULL DEFAULT ''" ],
			[ 'addPgField', 'oldimage', 'oi_minor_mime', "TEXT NOT NULL DEFAULT 'unknown'" ],
			[ 'addPgField', 'oldimage', 'oi_sha1', "TEXT NOT NULL DEFAULT ''" ],
			[ 'addPgField', 'page', 'page_content_model', 'TEXT' ],
			[ 'addPgField', 'page_restrictions', 'pr_id',
				"INTEGER NOT NULL UNIQUE DEFAULT nextval('page_restrictions_pr_id_seq')" ],
			[ 'addPgField', 'profiling', 'pf_memory', 'NUMERIC(18,10) NOT NULL DEFAULT 0' ],
			[ 'addPgField', 'recentchanges', 'rc_deleted', 'SMALLINT NOT NULL DEFAULT 0' ],
			[ 'addPgField', 'recentchanges', 'rc_log_action', 'TEXT' ],
			[ 'addPgField', 'recentchanges', 'rc_log_type', 'TEXT' ],
			[ 'addPgField', 'recentchanges', 'rc_logid', 'INTEGER NOT NULL DEFAULT 0' ],
			[ 'addPgField', 'recentchanges', 'rc_new_len', 'INTEGER' ],
			[ 'addPgField', 'recentchanges', 'rc_old_len', 'INTEGER' ],
			[ 'addPgField', 'recentchanges', 'rc_params', 'TEXT' ],
			[ 'addPgField', 'redirect', 'rd_interwiki', 'TEXT NULL' ],
			[ 'addPgField', 'redirect', 'rd_fragment', 'TEXT NULL' ],
			[ 'addPgField', 'revision', 'rev_deleted', 'SMALLINT NOT NULL DEFAULT 0' ],
			[ 'addPgField', 'revision', 'rev_len', 'INTEGER' ],
			[ 'addPgField', 'revision', 'rev_parent_id', 'INTEGER DEFAULT NULL' ],
			[ 'addPgField', 'revision', 'rev_content_model', 'TEXT' ],
			[ 'addPgField', 'revision', 'rev_content_format', 'TEXT' ],
			[ 'addPgField', 'site_stats', 'ss_active_users', "INTEGER DEFAULT '-1'" ],
			[ 'addPgField', 'user_newtalk', 'user_last_timestamp', 'TIMESTAMPTZ' ],
			[ 'addPgField', 'logging', 'log_user_text', "TEXT NOT NULL DEFAULT ''" ],
			[ 'addPgField', 'logging', 'log_page', 'INTEGER' ],
			[ 'addPgField', 'interwiki', 'iw_api', "TEXT NOT NULL DEFAULT ''" ],
			[ 'addPgField', 'interwiki', 'iw_wikiid', "TEXT NOT NULL DEFAULT ''" ],
			[ 'addPgField', 'revision', 'rev_sha1', "TEXT NOT NULL DEFAULT ''" ],
			[ 'addPgField', 'archive', 'ar_sha1', "TEXT NOT NULL DEFAULT ''" ],
			[ 'addPgField', 'uploadstash', 'us_chunk_inx', "INTEGER NULL" ],
			[ 'addPgField', 'job', 'job_timestamp', "TIMESTAMPTZ" ],
			[ 'addPgField', 'job', 'job_random', "INTEGER NOT NULL DEFAULT 0" ],
			[ 'addPgField', 'job', 'job_attempts', "INTEGER NOT NULL DEFAULT 0" ],
			[ 'addPgField', 'job', 'job_token', "TEXT NOT NULL DEFAULT ''" ],
			[ 'addPgField', 'job', 'job_token_timestamp', "TIMESTAMPTZ" ],
			[ 'addPgField', 'job', 'job_sha1', "TEXT NOT NULL DEFAULT ''" ],
			[ 'addPgField', 'archive', 'ar_id',
				"INTEGER NOT NULL PRIMARY KEY DEFAULT nextval('archive_ar_id_seq')" ],
			[ 'addPgField', 'externallinks', 'el_id',
				"INTEGER NOT NULL PRIMARY KEY DEFAULT nextval('externallinks_el_id_seq')" ],
			[ 'addPgField', 'uploadstash', 'us_props', "BYTEA" ],

			# type changes
			[ 'changeField', 'archive', 'ar_deleted', 'smallint', '' ],
			[ 'changeField', 'archive', 'ar_minor_edit', 'smallint',
				'ar_minor_edit::smallint DEFAULT 0' ],
			[ 'changeField', 'filearchive', 'fa_deleted', 'smallint', '' ],
			[ 'changeField', 'filearchive', 'fa_height', 'integer', '' ],
			[ 'changeField', 'filearchive', 'fa_metadata', 'bytea', "decode(fa_metadata,'escape')" ],
			[ 'changeField', 'filearchive', 'fa_size', 'integer', '' ],
			[ 'changeField', 'filearchive', 'fa_width', 'integer', '' ],
			[ 'changeField', 'filearchive', 'fa_storage_group', 'text', '' ],
			[ 'changeField', 'filearchive', 'fa_storage_key', 'text', '' ],
			[ 'changeField', 'image', 'img_metadata', 'bytea', "decode(img_metadata,'escape')" ],
			[ 'changeField', 'image', 'img_size', 'integer', '' ],
			[ 'changeField', 'image', 'img_width', 'integer', '' ],
			[ 'changeField', 'image', 'img_height', 'integer', '' ],
			[ 'changeField', 'interwiki', 'iw_local', 'smallint', 'iw_local::smallint' ],
			[ 'changeField', 'interwiki', 'iw_trans', 'smallint', 'iw_trans::smallint DEFAULT 0' ],
			[ 'changeField', 'ipblocks', 'ipb_auto', 'smallint', 'ipb_auto::smallint DEFAULT 0' ],
			[ 'changeField', 'ipblocks', 'ipb_anon_only', 'smallint',
				"CASE WHEN ipb_anon_only=' ' THEN 0 ELSE ipb_anon_only::smallint END DEFAULT 0" ],
			[ 'changeField', 'ipblocks', 'ipb_create_account', 'smallint',
				"CASE WHEN ipb_create_account=' ' THEN 0 ELSE ipb_create_account::smallint END DEFAULT 1" ],
			[ 'changeField', 'ipblocks', 'ipb_enable_autoblock', 'smallint',
				"CASE WHEN ipb_enable_autoblock=' ' THEN 0 ELSE ipb_enable_autoblock::smallint END DEFAULT 1" ],
			[ 'changeField', 'ipblocks', 'ipb_block_email', 'smallint',
				"CASE WHEN ipb_block_email=' ' THEN 0 ELSE ipb_block_email::smallint END DEFAULT 0" ],
			[ 'changeField', 'ipblocks', 'ipb_address', 'text', 'ipb_address::text' ],
			[ 'changeField', 'ipblocks', 'ipb_deleted', 'smallint', 'ipb_deleted::smallint DEFAULT 0' ],
			[ 'changeField', 'mwuser', 'user_token', 'text', '' ],
			[ 'changeField', 'mwuser', 'user_email_token', 'text', '' ],
			[ 'changeField', 'objectcache', 'keyname', 'text', '' ],
			[ 'changeField', 'oldimage', 'oi_height', 'integer', '' ],
			[ 'changeField', 'oldimage', 'oi_metadata', 'bytea', "decode(img_metadata,'escape')" ],
			[ 'changeField', 'oldimage', 'oi_size', 'integer', '' ],
			[ 'changeField', 'oldimage', 'oi_width', 'integer', '' ],
			[ 'changeField', 'page', 'page_is_redirect', 'smallint',
				'page_is_redirect::smallint DEFAULT 0' ],
			[ 'changeField', 'page', 'page_is_new', 'smallint', 'page_is_new::smallint DEFAULT 0' ],
			[ 'changeField', 'querycache', 'qc_value', 'integer', '' ],
			[ 'changeField', 'querycachetwo', 'qcc_value', 'integer', '' ],
			[ 'changeField', 'recentchanges', 'rc_bot', 'smallint', 'rc_bot::smallint DEFAULT 0' ],
			[ 'changeField', 'recentchanges', 'rc_deleted', 'smallint', '' ],
			[ 'changeField', 'recentchanges', 'rc_minor', 'smallint', 'rc_minor::smallint DEFAULT 0' ],
			[ 'changeField', 'recentchanges', 'rc_new', 'smallint', 'rc_new::smallint DEFAULT 0' ],
			[ 'changeField', 'recentchanges', 'rc_type', 'smallint', 'rc_type::smallint DEFAULT 0' ],
			[ 'changeField', 'recentchanges', 'rc_patrolled', 'smallint',
				'rc_patrolled::smallint DEFAULT 0' ],
			[ 'changeField', 'revision', 'rev_deleted', 'smallint', 'rev_deleted::smallint DEFAULT 0' ],
			[ 'changeField', 'revision', 'rev_minor_edit', 'smallint',
				'rev_minor_edit::smallint DEFAULT 0' ],
			[ 'changeField', 'templatelinks', 'tl_namespace', 'smallint', 'tl_namespace::smallint' ],
			[ 'changeField', 'user_newtalk', 'user_ip', 'text', 'host(user_ip)' ],
			[ 'changeField', 'uploadstash', 'us_image_bits', 'smallint', '' ],
			[ 'changeField', 'profiling', 'pf_time', 'float', '' ],
			[ 'changeField', 'profiling', 'pf_memory', 'float', '' ],

			# null changes
			[ 'changeNullableField', 'oldimage', 'oi_bits', 'NULL' ],
			[ 'changeNullableField', 'oldimage', 'oi_timestamp', 'NULL' ],
			[ 'changeNullableField', 'oldimage', 'oi_major_mime', 'NULL' ],
			[ 'changeNullableField', 'oldimage', 'oi_minor_mime', 'NULL' ],
			[ 'changeNullableField', 'image', 'img_metadata', 'NOT NULL' ],
			[ 'changeNullableField', 'filearchive', 'fa_metadata', 'NOT NULL' ],
			[ 'changeNullableField', 'recentchanges', 'rc_cur_id', 'NULL' ],
			[ 'changeNullableField', 'recentchanges', 'rc_cur_time', 'NULL' ],

			[ 'checkOiDeleted' ],

			# New indexes
			[ 'addPgIndex', 'archive', 'archive_user_text', '(ar_user_text)' ],
			[ 'addPgIndex', 'image', 'img_sha1', '(img_sha1)' ],
			[ 'addPgIndex', 'ipblocks', 'ipb_parent_block_id', '(ipb_parent_block_id)' ],
			[ 'addPgIndex', 'oldimage', 'oi_sha1', '(oi_sha1)' ],
			[ 'addPgIndex', 'page', 'page_mediawiki_title', '(page_title) WHERE page_namespace = 8' ],
			[ 'addPgIndex', 'pagelinks', 'pagelinks_title', '(pl_title)' ],
			[ 'addPgIndex', 'page_props', 'pp_propname_page', '(pp_propname, pp_page)' ],
			[ 'addPgIndex', 'revision', 'rev_text_id_idx', '(rev_text_id)' ],
			[ 'addPgIndex', 'recentchanges', 'rc_timestamp_bot', '(rc_timestamp) WHERE rc_bot = 0' ],
			[ 'addPgIndex', 'templatelinks', 'templatelinks_from', '(tl_from)' ],
			[ 'addPgIndex', 'watchlist', 'wl_user', '(wl_user)' ],
			[ 'addPgIndex', 'watchlist', 'wl_user_notificationtimestamp',
				'(wl_user, wl_notificationtimestamp)' ],
			[ 'addPgIndex', 'logging', 'logging_user_type_time',
				'(log_user, log_type, log_timestamp)' ],
			[ 'addPgIndex', 'logging', 'logging_page_id_time', '(log_page,log_timestamp)' ],
			[ 'addPgIndex', 'iwlinks', 'iwl_prefix_from_title', '(iwl_prefix, iwl_from, iwl_title)' ],
			[ 'addPgIndex', 'iwlinks', 'iwl_prefix_title_from', '(iwl_prefix, iwl_title, iwl_from)' ],
			[ 'addPgIndex', 'job', 'job_timestamp_idx', '(job_timestamp)' ],
			[ 'addPgIndex', 'job', 'job_sha1', '(job_sha1)' ],
			[ 'addPgIndex', 'job', 'job_cmd_token', '(job_cmd, job_token, job_random)' ],
			[ 'addPgIndex', 'job', 'job_cmd_token_id', '(job_cmd, job_token, job_id)' ],
			[ 'addPgIndex', 'filearchive', 'fa_sha1', '(fa_sha1)' ],
			[ 'addPgIndex', 'logging', 'logging_user_text_type_time',
				'(log_user_text, log_type, log_timestamp)' ],
			[ 'addPgIndex', 'logging', 'logging_user_text_time', '(log_user_text, log_timestamp)' ],

			[ 'checkIndex', 'pagelink_unique', [
				[ 'pl_from', 'int4_ops', 'btree', 0 ],
				[ 'pl_namespace', 'int2_ops', 'btree', 0 ],
				[ 'pl_title', 'text_ops', 'btree', 0 ],
			],
				'CREATE UNIQUE INDEX pagelink_unique ON pagelinks (pl_from,pl_namespace,pl_title)' ],
			[ 'checkIndex', 'cl_sortkey', [
				[ 'cl_to', 'text_ops', 'btree', 0 ],
				[ 'cl_sortkey', 'text_ops', 'btree', 0 ],
				[ 'cl_from', 'int4_ops', 'btree', 0 ],
			],
				'CREATE INDEX cl_sortkey ON "categorylinks" ' .
					'USING "btree" ("cl_to", "cl_sortkey", "cl_from")' ],
			[ 'checkIndex', 'iwl_prefix_title_from', [
				[ 'iwl_prefix', 'text_ops', 'btree', 0 ],
				[ 'iwl_title', 'text_ops', 'btree', 0 ],
				[ 'iwl_from', 'int4_ops', 'btree', 0 ],
			],
			'CREATE INDEX iwl_prefix_title_from ON "iwlinks" ' .
				'USING "btree" ("iwl_prefix", "iwl_title", "iwl_from")' ],
			[ 'checkIndex', 'logging_times', [
				[ 'log_timestamp', 'timestamptz_ops', 'btree', 0 ],
			],
			'CREATE INDEX "logging_times" ON "logging" USING "btree" ("log_timestamp")' ],
			[ 'dropPgIndex', 'oldimage', 'oi_name' ],
			[ 'checkIndex', 'oi_name_archive_name', [
				[ 'oi_name', 'text_ops', 'btree', 0 ],
				[ 'oi_archive_name', 'text_ops', 'btree', 0 ],
			],
			'CREATE INDEX "oi_name_archive_name" ON "oldimage" ' .
				'USING "btree" ("oi_name", "oi_archive_name")' ],
			[ 'checkIndex', 'oi_name_timestamp', [
				[ 'oi_name', 'text_ops', 'btree', 0 ],
				[ 'oi_timestamp', 'timestamptz_ops', 'btree', 0 ],
			],
			'CREATE INDEX "oi_name_timestamp" ON "oldimage" ' .
				'USING "btree" ("oi_name", "oi_timestamp")' ],
			[ 'checkIndex', 'page_main_title', [
				[ 'page_title', 'text_pattern_ops', 'btree', 0 ],
			],
			'CREATE INDEX "page_main_title" ON "page" ' .
				'USING "btree" ("page_title" "text_pattern_ops") WHERE ("page_namespace" = 0)' ],
			[ 'checkIndex', 'page_mediawiki_title', [
				[ 'page_title', 'text_pattern_ops', 'btree', 0 ],
			],
			'CREATE INDEX "page_mediawiki_title" ON "page" ' .
				'USING "btree" ("page_title" "text_pattern_ops") WHERE ("page_namespace" = 8)' ],
			[ 'checkIndex', 'page_project_title', [
				[ 'page_title', 'text_pattern_ops', 'btree', 0 ],
			],
			'CREATE INDEX "page_project_title" ON "page" ' .
				'USING "btree" ("page_title" "text_pattern_ops") ' .
				'WHERE ("page_namespace" = 4)' ],
			[ 'checkIndex', 'page_talk_title', [
				[ 'page_title', 'text_pattern_ops', 'btree', 0 ],
			],
			'CREATE INDEX "page_talk_title" ON "page" ' .
				'USING "btree" ("page_title" "text_pattern_ops") ' .
				'WHERE ("page_namespace" = 1)' ],
			[ 'checkIndex', 'page_user_title', [
				[ 'page_title', 'text_pattern_ops', 'btree', 0 ],
			],
			'CREATE INDEX "page_user_title" ON "page" ' .
				'USING "btree" ("page_title" "text_pattern_ops") WHERE ' .
				'("page_namespace" = 2)' ],
			[ 'checkIndex', 'page_utalk_title', [
				[ 'page_title', 'text_pattern_ops', 'btree', 0 ],
			],
			'CREATE INDEX "page_utalk_title" ON "page" ' .
				'USING "btree" ("page_title" "text_pattern_ops") ' .
				'WHERE ("page_namespace" = 3)' ],
			[ 'checkIndex', 'ts2_page_text', [
				[ 'textvector', 'tsvector_ops', 'gist', 0 ],
			],
			'CREATE INDEX "ts2_page_text" ON "pagecontent" USING "gist" ("textvector")' ],
			[ 'checkIndex', 'ts2_page_title', [
				[ 'titlevector', 'tsvector_ops', 'gist', 0 ],
			],
			'CREATE INDEX "ts2_page_title" ON "page" USING "gist" ("titlevector")' ],

			[ 'checkOiNameConstraint' ],
			[ 'checkPageDeletedTrigger' ],
			[ 'checkRevUserFkey' ],
			[ 'dropPgIndex', 'ipblocks', 'ipb_address' ],
			[ 'checkIndex', 'ipb_address_unique', [
				[ 'ipb_address', 'text_ops', 'btree', 0 ],
				[ 'ipb_user', 'int4_ops', 'btree', 0 ],
				[ 'ipb_auto', 'int2_ops', 'btree', 0 ],
				[ 'ipb_anon_only', 'int2_ops', 'btree', 0 ],
			],
			'CREATE UNIQUE INDEX ipb_address_unique ' .
				'ON ipblocks (ipb_address,ipb_user,ipb_auto,ipb_anon_only)' ],

			[ 'checkIwlPrefix' ],

			# All FK columns should be deferred
			[ 'changeFkeyDeferrable', 'archive', 'ar_user', 'mwuser(user_id) ON DELETE SET NULL' ],
			[ 'changeFkeyDeferrable', 'categorylinks', 'cl_from', 'page(page_id) ON DELETE CASCADE' ],
			[ 'changeFkeyDeferrable', 'externallinks', 'el_from', 'page(page_id) ON DELETE CASCADE' ],
			[ 'changeFkeyDeferrable', 'filearchive', 'fa_deleted_user',
				'mwuser(user_id) ON DELETE SET NULL' ],
			[ 'changeFkeyDeferrable', 'filearchive', 'fa_user', 'mwuser(user_id) ON DELETE SET NULL' ],
			[ 'changeFkeyDeferrable', 'image', 'img_user', 'mwuser(user_id) ON DELETE SET NULL' ],
			[ 'changeFkeyDeferrable', 'imagelinks', 'il_from', 'page(page_id) ON DELETE CASCADE' ],
			[ 'changeFkeyDeferrable', 'ipblocks', 'ipb_by', 'mwuser(user_id) ON DELETE CASCADE' ],
			[ 'changeFkeyDeferrable', 'ipblocks', 'ipb_user', 'mwuser(user_id) ON DELETE SET NULL' ],
			[ 'changeFkeyDeferrable', 'ipblocks', 'ipb_parent_block_id',
				'ipblocks(ipb_id) ON DELETE SET NULL' ],
			[ 'changeFkeyDeferrable', 'langlinks', 'll_from', 'page(page_id) ON DELETE CASCADE' ],
			[ 'changeFkeyDeferrable', 'logging', 'log_user', 'mwuser(user_id) ON DELETE SET NULL' ],
			[ 'changeFkeyDeferrable', 'oldimage', 'oi_name',
				'image(img_name) ON DELETE CASCADE ON UPDATE CASCADE' ],
			[ 'changeFkeyDeferrable', 'oldimage', 'oi_user', 'mwuser(user_id) ON DELETE SET NULL' ],
			[ 'changeFkeyDeferrable', 'pagelinks', 'pl_from', 'page(page_id) ON DELETE CASCADE' ],
			[ 'changeFkeyDeferrable', 'page_props', 'pp_page', 'page (page_id) ON DELETE CASCADE' ],
			[ 'changeFkeyDeferrable', 'page_restrictions', 'pr_page',
				'page(page_id) ON DELETE CASCADE' ],
			[ 'changeFkeyDeferrable', 'protected_titles', 'pt_user',
				'mwuser(user_id) ON DELETE SET NULL' ],
			[ 'changeFkeyDeferrable', 'recentchanges', 'rc_user',
				'mwuser(user_id) ON DELETE SET NULL' ],
			[ 'changeFkeyDeferrable', 'redirect', 'rd_from', 'page(page_id) ON DELETE CASCADE' ],
			[ 'changeFkeyDeferrable', 'revision', 'rev_page', 'page (page_id) ON DELETE CASCADE' ],
			[ 'changeFkeyDeferrable', 'revision', 'rev_user', 'mwuser(user_id) ON DELETE RESTRICT' ],
			[ 'changeFkeyDeferrable', 'templatelinks', 'tl_from', 'page(page_id) ON DELETE CASCADE' ],
			[ 'changeFkeyDeferrable', 'user_groups', 'ug_user', 'mwuser(user_id) ON DELETE CASCADE' ],
			[ 'changeFkeyDeferrable', 'user_newtalk', 'user_id', 'mwuser(user_id) ON DELETE CASCADE' ],
			[ 'changeFkeyDeferrable', 'user_properties', 'up_user',
				'mwuser(user_id) ON DELETE CASCADE' ],
			[ 'changeFkeyDeferrable', 'watchlist', 'wl_user', 'mwuser(user_id) ON DELETE CASCADE' ],

			# r81574
			[ 'addInterwikiType' ],
			# end
			[ 'tsearchFixes' ],

			// 1.23
			[ 'addPgField', 'recentchanges', 'rc_source', "TEXT NOT NULL DEFAULT ''" ],
			[ 'addPgField', 'page', 'page_links_updated', "TIMESTAMPTZ NULL" ],
			[ 'addPgField', 'mwuser', 'user_password_expires', 'TIMESTAMPTZ NULL' ],
			[ 'changeFieldPurgeTable', 'l10n_cache', 'lc_value', 'bytea',
				"replace(lc_value,'\','\\\\')::bytea" ],
			// 1.23.9
			[ 'rebuildTextSearch' ],

			// 1.24
			[ 'addPgField', 'page_props', 'pp_sortkey', 'float NULL' ],
			[ 'addPgIndex', 'page_props', 'pp_propname_sortkey_page',
					'( pp_propname, pp_sortkey, pp_page ) WHERE ( pp_sortkey IS NOT NULL )' ],
			[ 'addPgField', 'page', 'page_lang', 'TEXT default NULL' ],
			[ 'addPgField', 'pagelinks', 'pl_from_namespace', 'INTEGER NOT NULL DEFAULT 0' ],
			[ 'addPgField', 'templatelinks', 'tl_from_namespace', 'INTEGER NOT NULL DEFAULT 0' ],
			[ 'addPgField', 'imagelinks', 'il_from_namespace', 'INTEGER NOT NULL DEFAULT 0' ],

			// 1.25
			[ 'dropTable', 'hitcounter' ],
			[ 'dropField', 'site_stats', 'ss_total_views', 'patch-drop-ss_total_views.sql' ],
			[ 'dropField', 'page', 'page_counter', 'patch-drop-page_counter.sql' ],
			[ 'dropFkey', 'recentchanges', 'rc_cur_id' ],

			// 1.27
			[ 'dropTable', 'msg_resource_links' ],
			[ 'dropTable', 'msg_resource' ],
			[
				'addPgField', 'watchlist', 'wl_id',
				"INTEGER NOT NULL PRIMARY KEY DEFAULT nextval('watchlist_wl_id_seq')"
			],

			// 1.28
			[ 'addPgIndex', 'recentchanges', 'rc_name_type_patrolled_timestamp',
				'( rc_namespace, rc_type, rc_patrolled, rc_timestamp )' ],
			[ 'addPgField', 'change_tag', 'ct_id',
				"INTEGER NOT NULL PRIMARY KEY DEFAULT nextval('change_tag_ct_id_seq')" ],
			[ 'addPgField', 'tag_summary', 'ts_id',
				"INTEGER NOT NULL PRIMARY KEY DEFAULT nextval('tag_summary_ts_id_seq')" ],

			// 1.29
			[ 'addPgField', 'externallinks', 'el_index_60', "BYTEA NOT NULL DEFAULT ''" ],
			[ 'addPgIndex', 'externallinks', 'el_index_60', '( el_index_60, el_id )' ],
			[ 'addPgIndex', 'externallinks', 'el_from_index_60', '( el_from, el_index_60, el_id )' ],
			[ 'addPgField', 'user_groups', 'ug_expiry', "TIMESTAMPTZ NULL" ],
			[ 'addPgIndex', 'user_groups', 'user_groups_expiry', '( ug_expiry )' ],

			// 1.30
			[ 'addPgEnumValue', 'media_type', '3D' ],
			[ 'setDefault', 'revision', 'rev_comment', '' ],
			[ 'changeNullableField', 'revision', 'rev_comment', 'NOT NULL', true ],
			[ 'setDefault', 'archive', 'ar_comment', '' ],
			[ 'changeNullableField', 'archive', 'ar_comment', 'NOT NULL', true ],
			[ 'addPgField', 'archive', 'ar_comment_id', 'INTEGER NOT NULL DEFAULT 0' ],
			[ 'setDefault', 'ipblocks', 'ipb_reason', '' ],
			[ 'addPgField', 'ipblocks', 'ipb_reason_id', 'INTEGER NOT NULL DEFAULT 0' ],
			[ 'setDefault', 'image', 'img_description', '' ],
			[ 'setDefault', 'oldimage', 'oi_description', '' ],
			[ 'changeNullableField', 'oldimage', 'oi_description', 'NOT NULL', true ],
			[ 'addPgField', 'oldimage', 'oi_description_id', 'INTEGER NOT NULL DEFAULT 0' ],
			[ 'setDefault', 'filearchive', 'fa_deleted_reason', '' ],
			[ 'changeNullableField', 'filearchive', 'fa_deleted_reason', 'NOT NULL', true ],
			[ 'addPgField', 'filearchive', 'fa_deleted_reason_id', 'INTEGER NOT NULL DEFAULT 0' ],
			[ 'setDefault', 'filearchive', 'fa_description', '' ],
			[ 'addPgField', 'filearchive', 'fa_description_id', 'INTEGER NOT NULL DEFAULT 0' ],
			[ 'setDefault', 'recentchanges', 'rc_comment', '' ],
			[ 'changeNullableField', 'recentchanges', 'rc_comment', 'NOT NULL', true ],
			[ 'addPgField', 'recentchanges', 'rc_comment_id', 'INTEGER NOT NULL DEFAULT 0' ],
			[ 'setDefault', 'logging', 'log_comment', '' ],
			[ 'changeNullableField', 'logging', 'log_comment', 'NOT NULL', true ],
			[ 'addPgField', 'logging', 'log_comment_id', 'INTEGER NOT NULL DEFAULT 0' ],
			[ 'setDefault', 'protected_titles', 'pt_reason', '' ],
			[ 'changeNullableField', 'protected_titles', 'pt_reason', 'NOT NULL', true ],
			[ 'addPgField', 'protected_titles', 'pt_reason_id', 'INTEGER NOT NULL DEFAULT 0' ],
			[ 'addTable', 'comment', 'patch-comment-table.sql' ],

			// This field was added in 1.31, but is put here so it can be used by 'migrateComments'
			[ 'addPgField', 'image', 'img_description_id', 'INTEGER NOT NULL DEFAULT 0' ],

			[ 'migrateComments' ],
			[ 'addIndex', 'site_stats', 'site_stats_pkey', 'patch-site_stats-pk.sql' ],
			[ 'addTable', 'ip_changes', 'patch-ip_changes.sql' ],

			// 1.31
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
			[ 'addPgIndex', 'archive', 'archive_actor', '( ar_actor )' ],
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
			[ 'setSequenceOwner', 'mwuser', 'user_id', 'user_user_id_seq' ],
			[ 'setSequenceOwner', 'actor', 'actor_id', 'actor_actor_id_seq' ],
			[ 'setSequenceOwner', 'page', 'page_id', 'page_page_id_seq' ],
			[ 'setSequenceOwner', 'revision', 'rev_id', 'revision_rev_id_seq' ],
			[ 'setSequenceOwner', 'ip_changes', 'ipc_rev_id', 'ip_changes_ipc_rev_id_seq' ],
			[ 'setSequenceOwner', 'pagecontent', 'old_id', 'text_old_id_seq' ],
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
			[ 'setSequenceOwner', 'tag_summary', 'ts_id', 'tag_summary_ts_id_seq' ],
			[ 'setSequenceOwner', 'sites', 'site_id', 'sites_site_id_seq' ],
		];
	}

	protected function getOldGlobalUpdates() {
		global $wgExtNewTables, $wgExtPGNewFields, $wgExtPGAlteredFields, $wgExtNewIndexes;

		$updates = [];

		# Add missing extension tables
		foreach ( $wgExtNewTables as $tableRecord ) {
			$updates[] = [
				'addTable', $tableRecord[0], $tableRecord[1], true
			];
		}

		# Add missing extension fields
		foreach ( $wgExtPGNewFields as $fieldRecord ) {
			$updates[] = [
				'addPgField', $fieldRecord[0], $fieldRecord[1],
				$fieldRecord[2]
			];
		}

		# Change altered columns
		foreach ( $wgExtPGAlteredFields as $fieldRecord ) {
			$updates[] = [
				'changeField', $fieldRecord[0], $fieldRecord[1],
				$fieldRecord[2]
			];
		}

		# Add missing extension indexes
		foreach ( $wgExtNewIndexes as $fieldRecord ) {
			$updates[] = [
				'addPgExtIndex', $fieldRecord[0], $fieldRecord[1],
				$fieldRecord[2]
			];
		}

		return $updates;
	}

	protected function describeTable( $table ) {
		$q = <<<END
SELECT attname, attnum FROM pg_namespace, pg_class, pg_attribute
	WHERE pg_class.relnamespace = pg_namespace.oid
		AND attrelid=pg_class.oid AND attnum > 0
		AND relname=%s AND nspname=%s
END;
		$res = $this->db->query( sprintf( $q,
			$this->db->addQuotes( $table ),
			$this->db->addQuotes( $this->db->getCoreSchema() ) ) );
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

	function describeIndex( $idx ) {
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
			)
		);
		if ( !$res ) {
			return null;
		}
		$r = $this->db->fetchRow( $res );
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
			$r2 = $this->db->query( sprintf( $query, $rid ) );
			if ( !$r2 ) {
				return null;
			}
			$row2 = $this->db->fetchRow( $r2 );
			if ( !$row2 ) {
				return null;
			}
			$colnames[] = $row2[0];
		}

		return $colnames;
	}

	function fkeyDeltype( $fkey ) {
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
			)
		);
		$row = $this->db->fetchRow( $r );
		if ( !$row ) {
			return null;
		}

		return $row[0];
	}

	function ruleDef( $table, $rule ) {
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
			)
		);
		$row = $this->db->fetchRow( $r );
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
				$this->db->query( "CREATE SEQUENCE $ns OWNED BY $table.$pkey" );
				$this->setDefault( $table, $pkey, '"nextval"(\'"' . $ns . '"\'::"regclass")' );
			} else {
				$this->db->query( "CREATE SEQUENCE $ns" );
			}
		}
	}

	protected function dropSequence( $table, $ns ) {
		if ( $this->db->sequenceExists( $ns ) ) {
			$this->output( "Dropping sequence $ns\n" );
			$this->db->query( "DROP SEQUENCE $ns CASCADE" );
		}
	}

	protected function renameSequence( $old, $new ) {
		if ( $this->db->sequenceExists( $new ) ) {
			$this->output( "...sequence $new already exists.\n" );

			return;
		}
		if ( $this->db->sequenceExists( $old ) ) {
			$this->output( "Renaming sequence $old to $new\n" );
			$this->db->query( "ALTER SEQUENCE $old RENAME TO $new" );
		}
	}

	protected function setSequenceOwner( $table, $pkey, $seq ) {
		if ( $this->db->sequenceExists( $seq ) ) {
			$this->output( "Setting sequence $seq owner to $table.$pkey\n" );
			$this->db->query( "ALTER SEQUENCE $seq OWNED BY $table.$pkey" );
		}
	}

	protected function renameTable( $old, $new, $patch = false ) {
		if ( $this->db->tableExists( $old ) ) {
			$this->output( "Renaming table $old to $new\n" );
			$old = $this->db->realTableName( $old, "quoted" );
			$new = $this->db->realTableName( $new, "quoted" );
			$this->db->query( "ALTER TABLE $old RENAME TO $new" );
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

			return;
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

			return;
		}

		// Third requirement: the old index must exist
		if ( !$this->db->indexExists( $table, $old, __METHOD__ ) ) {
			$this->output( "...skipping: index $old doesn't exist.\n" );

			return;
		}

		$this->db->query( "ALTER INDEX $old RENAME TO $new" );
	}

	protected function dropPgField( $table, $field ) {
		$fi = $this->db->fieldInfo( $table, $field );
		if ( is_null( $fi ) ) {
			$this->output( "...$table table does not contain $field field.\n" );

			return;
		} else {
			$this->output( "Dropping column '$table.$field'\n" );
			$this->db->query( "ALTER TABLE $table DROP COLUMN $field" );
		}
	}

	protected function addPgField( $table, $field, $type ) {
		$fi = $this->db->fieldInfo( $table, $field );
		if ( !is_null( $fi ) ) {
			$this->output( "...column '$table.$field' already exists\n" );

			return;
		} else {
			$this->output( "Adding column '$table.$field'\n" );
			$this->db->query( "ALTER TABLE $table ADD $field $type" );
		}
	}

	protected function changeField( $table, $field, $newtype, $default ) {
		$fi = $this->db->fieldInfo( $table, $field );
		if ( is_null( $fi ) ) {
			$this->output( "...ERROR: expected column $table.$field to exist\n" );
			exit( 1 );
		}

		if ( $fi->type() === $newtype ) {
			$this->output( "...column '$table.$field' is already of type '$newtype'\n" );
		} else {
			$this->output( "Changing column type of '$table.$field' from '{$fi->type()}' to '$newtype'\n" );
			$sql = "ALTER TABLE $table ALTER $field TYPE $newtype";
			if ( strlen( $default ) ) {
				$res = [];
				if ( preg_match( '/DEFAULT (.+)/', $default, $res ) ) {
					$sqldef = "ALTER TABLE $table ALTER $field SET DEFAULT $res[1]";
					$this->db->query( $sqldef );
					$default = preg_replace( '/\s*DEFAULT .+/', '', $default );
				}
				$sql .= " USING $default";
			}
			$this->db->query( $sql );
		}
	}

	protected function changeFieldPurgeTable( $table, $field, $newtype, $default ) {
		# # For a cache table, empty it if the field needs to be changed, because the old contents
		# # may be corrupted.  If the column is already the desired type, refrain from purging.
		$fi = $this->db->fieldInfo( $table, $field );
		if ( is_null( $fi ) ) {
			$this->output( "...ERROR: expected column $table.$field to exist\n" );
			exit( 1 );
		}

		if ( $fi->type() === $newtype ) {
			$this->output( "...column '$table.$field' is already of type '$newtype'\n" );
		} else {
			$this->output( "Purging data from cache table '$table'\n" );
			$this->db->query( "DELETE from $table" );
			$this->output( "Changing column type of '$table.$field' from '{$fi->type()}' to '$newtype'\n" );
			$sql = "ALTER TABLE $table ALTER $field TYPE $newtype";
			if ( strlen( $default ) ) {
				$res = [];
				if ( preg_match( '/DEFAULT (.+)/', $default, $res ) ) {
					$sqldef = "ALTER TABLE $table ALTER $field SET DEFAULT $res[1]";
					$this->db->query( $sqldef );
					$default = preg_replace( '/\s*DEFAULT .+/', '', $default );
				}
				$sql .= " USING $default";
			}
			$this->db->query( $sql );
		}
	}

	protected function setDefault( $table, $field, $default ) {
		$info = $this->db->fieldInfo( $table, $field );
		if ( $info->defaultValue() !== $default ) {
			$this->output( "Changing '$table.$field' default value\n" );
			$this->db->query( "ALTER TABLE $table ALTER $field SET DEFAULT "
				. $this->db->addQuotes( $default ) );
		}
	}

	protected function changeNullableField( $table, $field, $null, $update = false ) {
		$fi = $this->db->fieldInfo( $table, $field );
		if ( is_null( $fi ) ) {
			$this->output( "...ERROR: expected column $table.$field to exist\n" );
			exit( 1 );
		}
		if ( $fi->isNullable() ) {
			# # It's NULL - does it need to be NOT NULL?
			if ( 'NOT NULL' === $null ) {
				$this->output( "Changing '$table.$field' to not allow NULLs\n" );
				if ( $update ) {
					$this->db->query( "UPDATE $table SET $field = DEFAULT WHERE $field IS NULL" );
				}
				$this->db->query( "ALTER TABLE $table ALTER $field SET NOT NULL" );
			} else {
				$this->output( "...column '$table.$field' is already set as NULL\n" );
			}
		} else {
			# # It's NOT NULL - does it need to be NULL?
			if ( 'NULL' === $null ) {
				$this->output( "Changing '$table.$field' to allow NULLs\n" );
				$this->db->query( "ALTER TABLE $table ALTER $field DROP NOT NULL" );
			} else {
				$this->output( "...column '$table.$field' is already set as NOT NULL\n" );
			}
		}
	}

	public function addPgIndex( $table, $index, $type ) {
		if ( $this->db->indexExists( $table, $index ) ) {
			$this->output( "...index '$index' on table '$table' already exists\n" );
		} else {
			$this->output( "Creating index '$index' on table '$table' $type\n" );
			$this->db->query( "CREATE INDEX $index ON $table $type" );
		}
	}

	public function addPgExtIndex( $table, $index, $type ) {
		if ( $this->db->indexExists( $table, $index ) ) {
			$this->output( "...index '$index' on table '$table' already exists\n" );
		} else {
			if ( preg_match( '/^\(/', $type ) ) {
				$this->output( "Creating index '$index' on table '$table'\n" );
				$this->db->query( "CREATE INDEX $index ON $table $type" );
			} else {
				$this->applyPatch( $type, true, "Creating index '$index' on table '$table'" );
			}
		}
	}

	/**
	 * Add a value to an existing PostgreSQL enum type
	 * @since 1.31
	 * @param string $type Type name. Must be in the core schema.
	 * @param string $value Value to add.
	 */
	public function addPgEnumValue( $type, $value ) {
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
			$this->db->query( "ALTER TYPE $etype ADD VALUE $evalue" );
		}
	}

	protected function dropFkey( $table, $field ) {
		$fi = $this->db->fieldInfo( $table, $field );
		if ( is_null( $fi ) ) {
			$this->output( "WARNING! Column '$table.$field' does not exist but it should! " .
				"Please report this.\n" );
			return;
		}
		$conname = $fi->conname();
		if ( $fi->conname() ) {
			$this->output( "Dropping foreign key constraint on '$table.$field'\n" );
			$conclause = "CONSTRAINT \"$conname\"";
			$command = "ALTER TABLE $table DROP CONSTRAINT $conname";
			$this->db->query( $command );
		} else {
			$this->output( "...foreign key constraint on '$table.$field' already does not exist\n" );
		};
	}

	protected function changeFkeyDeferrable( $table, $field, $clause ) {
		$fi = $this->db->fieldInfo( $table, $field );
		if ( is_null( $fi ) ) {
			$this->output( "WARNING! Column '$table.$field' does not exist but it should! " .
				"Please report this.\n" );

			return;
		}
		if ( $fi->is_deferred() && $fi->is_deferrable() ) {
			return;
		}
		$this->output( "Altering column '$table.$field' to be DEFERRABLE INITIALLY DEFERRED\n" );
		$conname = $fi->conname();
		if ( $fi->conname() ) {
			$conclause = "CONSTRAINT \"$conname\"";
			$command = "ALTER TABLE $table DROP CONSTRAINT $conname";
			$this->db->query( $command );
		} else {
			$this->output( "Column '$table.$field' does not have a foreign key " .
				"constraint, will be added\n" );
			$conclause = "";
		}
		$command =
			"ALTER TABLE $table ADD $conclause " .
			"FOREIGN KEY ($field) REFERENCES $clause DEFERRABLE INITIALLY DEFERRED";
		$this->db->query( $command );
	}

	protected function convertArchive2() {
		if ( $this->db->tableExists( "archive2" ) ) {
			if ( $this->db->ruleExists( 'archive', 'archive_insert' ) ) {
				$this->output( "Dropping rule 'archive_insert'\n" );
				$this->db->query( 'DROP RULE archive_insert ON archive' );
			}
			if ( $this->db->ruleExists( 'archive', 'archive_delete' ) ) {
				$this->output( "Dropping rule 'archive_delete'\n" );
				$this->db->query( 'DROP RULE archive_delete ON archive' );
			}
			$this->applyPatch(
				'patch-remove-archive2.sql',
				false,
				"Converting 'archive2' back to normal archive table"
			);
		} else {
			$this->output( "...obsolete table 'archive2' does not exist\n" );
		}
	}

	protected function checkOiDeleted() {
		if ( $this->db->fieldInfo( 'oldimage', 'oi_deleted' )->type() !== 'smallint' ) {
			$this->output( "Changing 'oldimage.oi_deleted' to type 'smallint'\n" );
			$this->db->query( "ALTER TABLE oldimage ALTER oi_deleted DROP DEFAULT" );
			$this->db->query(
				"ALTER TABLE oldimage ALTER oi_deleted TYPE SMALLINT USING (oi_deleted::smallint)" );
			$this->db->query( "ALTER TABLE oldimage ALTER oi_deleted SET DEFAULT 0" );
		} else {
			$this->output( "...column 'oldimage.oi_deleted' is already of type 'smallint'\n" );
		}
	}

	protected function checkOiNameConstraint() {
		if ( $this->db->hasConstraint( "oldimage_oi_name_fkey_cascaded" ) ) {
			$this->output( "...table 'oldimage' has correct cascading delete/update " .
				"foreign key to image\n" );
		} else {
			if ( $this->db->hasConstraint( "oldimage_oi_name_fkey" ) ) {
				$this->db->query(
					"ALTER TABLE oldimage DROP CONSTRAINT oldimage_oi_name_fkey" );
			}
			if ( $this->db->hasConstraint( "oldimage_oi_name_fkey_cascade" ) ) {
				$this->db->query(
					"ALTER TABLE oldimage DROP CONSTRAINT oldimage_oi_name_fkey_cascade" );
			}
			$this->output( "Making foreign key on table 'oldimage' (to image) a cascade delete/update\n" );
			$this->db->query(
				"ALTER TABLE oldimage ADD CONSTRAINT oldimage_oi_name_fkey_cascaded " .
				"FOREIGN KEY (oi_name) REFERENCES image(img_name) " .
				"ON DELETE CASCADE ON UPDATE CASCADE" );
		}
	}

	protected function checkPageDeletedTrigger() {
		if ( !$this->db->triggerExists( 'page', 'page_deleted' ) ) {
			$this->applyPatch(
				'patch-page_deleted.sql',
				false,
				"Adding function and trigger 'page_deleted' to table 'page'"
			);
		} else {
			$this->output( "...table 'page' has 'page_deleted' trigger\n" );
		}
	}

	protected function dropPgIndex( $table, $index ) {
		if ( $this->db->indexExists( $table, $index ) ) {
			$this->output( "Dropping obsolete index '$index'\n" );
			$this->db->query( "DROP INDEX \"" . $index . "\"" );
		}
	}

	protected function checkIndex( $index, $should_be, $good_def ) {
		$pu = $this->db->indexAttributes( $index );
		if ( !empty( $pu ) && $pu != $should_be ) {
			$this->output( "Dropping obsolete version of index '$index'\n" );
			$this->db->query( "DROP INDEX \"" . $index . "\"" );
			$pu = [];
		} else {
			$this->output( "...no need to drop index '$index'\n" );
		}

		if ( empty( $pu ) ) {
			$this->output( "Creating index '$index'\n" );
			$this->db->query( $good_def );
		} else {
			$this->output( "...index '$index' exists\n" );
		}
	}

	protected function checkRevUserFkey() {
		if ( $this->fkeyDeltype( 'revision_rev_user_fkey' ) == 'r' ) {
			$this->output( "...constraint 'revision_rev_user_fkey' is ON DELETE RESTRICT\n" );
		} else {
			$this->applyPatch(
				'patch-revision_rev_user_fkey.sql',
				false,
				"Changing constraint 'revision_rev_user_fkey' to ON DELETE RESTRICT"
			);
		}
	}

	protected function checkIwlPrefix() {
		if ( $this->db->indexExists( 'iwlinks', 'iwl_prefix' ) ) {
			$this->applyPatch(
				'patch-rename-iwl_prefix.sql',
				false,
				"Replacing index 'iwl_prefix' with 'iwl_prefix_title_from'"
			);
		}
	}

	protected function addInterwikiType() {
		$this->applyPatch( 'patch-add_interwiki.sql', false, "Refreshing add_interwiki()" );
	}

	protected function tsearchFixes() {
		# Tweak the page_title tsearch2 trigger to filter out slashes
		# This is create or replace, so harmless to call if not needed
		$this->applyPatch( 'patch-ts2pagetitle.sql', false, "Refreshing ts2_page_title()" );

		# If the server is 8.3 or higher, rewrite the tsearch2 triggers
		# in case they have the old 'default' versions
		# Gather version numbers in case we need them
		if ( $this->db->getServerVersion() >= 8.3 ) {
			$this->applyPatch( 'patch-tsearch2funcs.sql', false, "Rewriting tsearch2 triggers" );
		}
	}

	protected function rebuildTextSearch() {
		if ( $this->updateRowExists( 'patch-textsearch_bug66650.sql' ) ) {
			$this->output( "...T68650 already fixed or not applicable.\n" );
			return;
		};
		$this->applyPatch( 'patch-textsearch_bug66650.sql', false,
			'Rebuilding text search for T68650' );
	}
}
