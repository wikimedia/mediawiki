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
		return array(
			# rename tables 1.7.3
			# r15791 Change reserved word table names "user" and "text"
			array( 'renameTable', 'user', 'mwuser' ),
			array( 'renameTable', 'text', 'pagecontent' ),
			array( 'renameIndex', 'mwuser', 'user_pkey', 'mwuser_pkey'),
			array( 'renameIndex', 'mwuser', 'user_user_name_key', 'mwuser_user_name_key' ),
			array( 'renameIndex', 'pagecontent','text_pkey', 'pagecontent_pkey' ),

			# renamed sequences
			array( 'renameSequence', 'ipblocks_ipb_id_val', 'ipblocks_ipb_id_seq'         ),
			array( 'renameSequence', 'rev_rev_id_val',      'revision_rev_id_seq'         ),
			array( 'renameSequence', 'text_old_id_val',     'text_old_id_seq'             ),
			array( 'renameSequence', 'rc_rc_id_seq',        'recentchanges_rc_id_seq'     ),
			array( 'renameSequence', 'log_log_id_seq',      'logging_log_id_seq'          ),
			array( 'renameSequence', 'pr_id_val',           'page_restrictions_pr_id_seq' ),
			array( 'renameSequence', 'us_id_seq',           'uploadstash_us_id_seq' ),

			# since r58263
			array( 'renameSequence', 'category_id_seq', 'category_cat_id_seq'),

			# new sequences if not renamed above
			array( 'addSequence', 'logging', false, 'logging_log_id_seq' ),
			array( 'addSequence', 'page_restrictions', false, 'page_restrictions_pr_id_seq' ),
			array( 'addSequence', 'filearchive', 'fa_id', 'filearchive_fa_id_seq' ),

			# new tables
			array( 'addTable', 'category',          'patch-category.sql' ),
			array( 'addTable', 'page',              'patch-page.sql' ),
			array( 'addTable', 'querycachetwo',     'patch-querycachetwo.sql' ),
			array( 'addTable', 'page_props',        'patch-page_props.sql' ),
			array( 'addTable', 'page_restrictions', 'patch-page_restrictions.sql' ),
			array( 'addTable', 'profiling',         'patch-profiling.sql' ),
			array( 'addTable', 'protected_titles',  'patch-protected_titles.sql' ),
			array( 'addTable', 'redirect',          'patch-redirect.sql' ),
			array( 'addTable', 'updatelog',         'patch-updatelog.sql' ),
			array( 'addTable', 'change_tag',        'patch-change_tag.sql' ),
			array( 'addTable', 'tag_summary',       'patch-tag_summary.sql' ),
			array( 'addTable', 'valid_tag',         'patch-valid_tag.sql' ),
			array( 'addTable', 'user_properties',   'patch-user_properties.sql' ),
			array( 'addTable', 'log_search',        'patch-log_search.sql' ),
			array( 'addTable', 'l10n_cache',        'patch-l10n_cache.sql' ),
			array( 'addTable', 'iwlinks',           'patch-iwlinks.sql' ),
			array( 'addTable', 'msg_resource',      'patch-msg_resource.sql' ),
			array( 'addTable', 'msg_resource_links','patch-msg_resource_links.sql' ),
			array( 'addTable', 'module_deps',       'patch-module_deps.sql' ),
			array( 'addTable', 'uploadstash',       'patch-uploadstash.sql' ),
			array( 'addTable', 'user_former_groups','patch-user_former_groups.sql' ),
			array( 'addTable', 'external_user',     'patch-external_user.sql' ),

			# Needed before new field
			array( 'convertArchive2' ),

			# new fields
			array( 'addPgField', 'updatelog',     'ul_value',             'TEXT' ),
			array( 'addPgField', 'archive',       'ar_deleted',           'SMALLINT NOT NULL DEFAULT 0' ),
			array( 'addPgField', 'archive',       'ar_len',               'INTEGER' ),
			array( 'addPgField', 'archive',       'ar_page_id',           'INTEGER' ),
			array( 'addPgField', 'archive',       'ar_parent_id',         'INTEGER' ),
			array( 'addPgField', 'categorylinks', 'cl_sortkey_prefix',    "TEXT NOT NULL DEFAULT ''"),
			array( 'addPgField', 'categorylinks', 'cl_collation',         "TEXT NOT NULL DEFAULT 0"),
			array( 'addPgField', 'categorylinks', 'cl_type',              "TEXT NOT NULL DEFAULT 'page'"),
			array( 'addPgField', 'image',         'img_sha1',             "TEXT NOT NULL DEFAULT ''" ),
			array( 'addPgField', 'ipblocks',      'ipb_allow_usertalk',   'SMALLINT NOT NULL DEFAULT 0' ),
			array( 'addPgField', 'ipblocks',      'ipb_anon_only',        'SMALLINT NOT NULL DEFAULT 0' ),
			array( 'addPgField', 'ipblocks',      'ipb_by_text',          "TEXT NOT NULL DEFAULT ''" ),
			array( 'addPgField', 'ipblocks',      'ipb_block_email',      'SMALLINT NOT NULL DEFAULT 0' ),
			array( 'addPgField', 'ipblocks',      'ipb_create_account',   'SMALLINT NOT NULL DEFAULT 1' ),
			array( 'addPgField', 'ipblocks',      'ipb_deleted',          'SMALLINT NOT NULL DEFAULT 0' ),
			array( 'addPgField', 'ipblocks',      'ipb_enable_autoblock', 'SMALLINT NOT NULL DEFAULT 1' ),
			array( 'addPgField', 'ipblocks',      'ipb_parent_block_id',            'INTEGER DEFAULT NULL REFERENCES ipblocks(ipb_id) ON DELETE SET NULL DEFERRABLE INITIALLY DEFERRED' ),
			array( 'addPgField', 'filearchive',   'fa_deleted',           'SMALLINT NOT NULL DEFAULT 0' ),
			array( 'addPgField', 'logging',       'log_deleted',          'SMALLINT NOT NULL DEFAULT 0' ),
			array( 'addPgField', 'logging',       'log_id',               "INTEGER NOT NULL PRIMARY KEY DEFAULT nextval('logging_log_id_seq')" ),
			array( 'addPgField', 'logging',       'log_params',           'TEXT' ),
			array( 'addPgField', 'mwuser',        'user_editcount',       'INTEGER' ),
			array( 'addPgField', 'mwuser',        'user_newpass_time',    'TIMESTAMPTZ' ),
			array( 'addPgField', 'oldimage',      'oi_deleted',           'SMALLINT NOT NULL DEFAULT 0' ),
			array( 'addPgField', 'oldimage',      'oi_major_mime',        "TEXT NOT NULL DEFAULT 'unknown'" ),
			array( 'addPgField', 'oldimage',      'oi_media_type',        'TEXT' ),
			array( 'addPgField', 'oldimage',      'oi_metadata',          "BYTEA NOT NULL DEFAULT ''" ),
			array( 'addPgField', 'oldimage',      'oi_minor_mime',        "TEXT NOT NULL DEFAULT 'unknown'" ),
			array( 'addPgField', 'oldimage',      'oi_sha1',              "TEXT NOT NULL DEFAULT ''" ),
			array( 'addPgField', 'page_restrictions', 'pr_id',            "INTEGER NOT NULL UNIQUE DEFAULT nextval('page_restrictions_pr_id_seq')" ),
			array( 'addPgField', 'profiling',     'pf_memory',            'NUMERIC(18,10) NOT NULL DEFAULT 0' ),
			array( 'addPgField', 'recentchanges', 'rc_deleted',           'SMALLINT NOT NULL DEFAULT 0' ),
			array( 'addPgField', 'recentchanges', 'rc_log_action',        'TEXT' ),
			array( 'addPgField', 'recentchanges', 'rc_log_type',          'TEXT' ),
			array( 'addPgField', 'recentchanges', 'rc_logid',             'INTEGER NOT NULL DEFAULT 0' ),
			array( 'addPgField', 'recentchanges', 'rc_new_len',           'INTEGER' ),
			array( 'addPgField', 'recentchanges', 'rc_old_len',           'INTEGER' ),
			array( 'addPgField', 'recentchanges', 'rc_params',            'TEXT' ),
			array( 'addPgField', 'redirect',      'rd_interwiki',         'TEXT NULL' ),
			array( 'addPgField', 'redirect',      'rd_fragment',          'TEXT NULL' ),
			array( 'addPgField', 'revision',      'rev_deleted',          'SMALLINT NOT NULL DEFAULT 0' ),
			array( 'addPgField', 'revision',      'rev_len',              'INTEGER' ),
			array( 'addPgField', 'revision',      'rev_parent_id',        'INTEGER DEFAULT NULL' ),
			array( 'addPgField', 'site_stats',    'ss_active_users',      "INTEGER DEFAULT '-1'" ),
			array( 'addPgField', 'user_newtalk',  'user_last_timestamp',  'TIMESTAMPTZ' ),
			array( 'addPgField', 'logging',       'log_user_text',        "TEXT NOT NULL DEFAULT ''" ),
			array( 'addPgField', 'logging',       'log_page',             'INTEGER' ),
			array( 'addPgField', 'interwiki',     'iw_api',               "TEXT NOT NULL DEFAULT ''"),
			array( 'addPgField', 'interwiki',     'iw_wikiid',            "TEXT NOT NULL DEFAULT ''"),
			array( 'addPgField', 'revision',      'rev_sha1',             "TEXT NOT NULL DEFAULT ''" ),
			array( 'addPgField', 'archive',       'ar_sha1',              "TEXT NOT NULL DEFAULT ''" ),
			array( 'addPgField', 'uploadstash',   'us_chunk_inx',         "INTEGER NULL" ),
			array( 'addPgField', 'job',           'job_timestamp',        "TIMESTAMPTZ" ),

			# type changes
			array( 'changeField', 'archive',       'ar_deleted',      'smallint', '' ),
			array( 'changeField', 'archive',       'ar_minor_edit',   'smallint', 'ar_minor_edit::smallint DEFAULT 0' ),
			array( 'changeField', 'filearchive',   'fa_deleted',      'smallint', '' ),
			array( 'changeField', 'filearchive',   'fa_height',       'integer',  '' ),
			array( 'changeField', 'filearchive',   'fa_metadata',     'bytea',    "decode(fa_metadata,'escape')" ),
			array( 'changeField', 'filearchive',   'fa_size',         'integer',  '' ),
			array( 'changeField', 'filearchive',   'fa_width',        'integer',  '' ),
			array( 'changeField', 'filearchive',   'fa_storage_group', 'text',     '' ),
			array( 'changeField', 'filearchive',   'fa_storage_key',  'text',     '' ),
			array( 'changeField', 'image',         'img_metadata',    'bytea',    "decode(img_metadata,'escape')" ),
			array( 'changeField', 'image',         'img_size',        'integer',  '' ),
			array( 'changeField', 'image',         'img_width',       'integer',  '' ),
			array( 'changeField', 'image',         'img_height',      'integer',  '' ),
			array( 'changeField', 'interwiki',     'iw_local',        'smallint', 'iw_local::smallint' ),
			array( 'changeField', 'interwiki',     'iw_trans',        'smallint', 'iw_trans::smallint DEFAULT 0' ),
			array( 'changeField', 'ipblocks',      'ipb_auto',        'smallint', 'ipb_auto::smallint DEFAULT 0' ),
			array( 'changeField', 'ipblocks',      'ipb_anon_only',   'smallint', "CASE WHEN ipb_anon_only=' ' THEN 0 ELSE ipb_anon_only::smallint END DEFAULT 0" ),
			array( 'changeField', 'ipblocks',      'ipb_create_account', 'smallint', "CASE WHEN ipb_create_account=' ' THEN 0 ELSE ipb_create_account::smallint END DEFAULT 1" ),
			array( 'changeField', 'ipblocks',      'ipb_enable_autoblock', 'smallint', "CASE WHEN ipb_enable_autoblock=' ' THEN 0 ELSE ipb_enable_autoblock::smallint END DEFAULT 1" ),
			array( 'changeField', 'ipblocks',      'ipb_block_email', 'smallint', "CASE WHEN ipb_block_email=' ' THEN 0 ELSE ipb_block_email::smallint END DEFAULT 0" ),
			array( 'changeField', 'ipblocks',      'ipb_address',     'text',     'ipb_address::text' ),
			array( 'changeField', 'ipblocks',      'ipb_deleted',     'smallint', 'ipb_deleted::smallint DEFAULT 0' ),
			array( 'changeField', 'mwuser',        'user_token',      'text',     '' ),
			array( 'changeField', 'mwuser',        'user_email_token', 'text',     '' ),
			array( 'changeField', 'objectcache',   'keyname',         'text',     '' ),
			array( 'changeField', 'oldimage',      'oi_height',       'integer',  '' ),
			array( 'changeField', 'oldimage',      'oi_metadata',     'bytea',    "decode(img_metadata,'escape')" ),
			array( 'changeField', 'oldimage',      'oi_size',         'integer',  '' ),
			array( 'changeField', 'oldimage',      'oi_width',        'integer',  '' ),
			array( 'changeField', 'page',          'page_is_redirect', 'smallint', 'page_is_redirect::smallint DEFAULT 0' ),
			array( 'changeField', 'page',          'page_is_new',     'smallint', 'page_is_new::smallint DEFAULT 0' ),
			array( 'changeField', 'querycache',    'qc_value',        'integer',  '' ),
			array( 'changeField', 'querycachetwo', 'qcc_value',       'integer',  '' ),
			array( 'changeField', 'recentchanges', 'rc_bot',          'smallint', 'rc_bot::smallint DEFAULT 0' ),
			array( 'changeField', 'recentchanges', 'rc_deleted',      'smallint', '' ),
			array( 'changeField', 'recentchanges', 'rc_minor',        'smallint', 'rc_minor::smallint DEFAULT 0' ),
			array( 'changeField', 'recentchanges', 'rc_new',          'smallint', 'rc_new::smallint DEFAULT 0' ),
			array( 'changeField', 'recentchanges', 'rc_type',         'smallint', 'rc_type::smallint DEFAULT 0' ),
			array( 'changeField', 'recentchanges', 'rc_patrolled',    'smallint', 'rc_patrolled::smallint DEFAULT 0' ),
			array( 'changeField', 'revision',      'rev_deleted',     'smallint', 'rev_deleted::smallint DEFAULT 0' ),
			array( 'changeField', 'revision',      'rev_minor_edit',  'smallint', 'rev_minor_edit::smallint DEFAULT 0' ),
			array( 'changeField', 'templatelinks', 'tl_namespace',    'smallint', 'tl_namespace::smallint' ),
			array( 'changeField', 'user_newtalk',  'user_ip',         'text',     'host(user_ip)' ),
			array( 'changeField', 'uploadstash',   'us_image_bits',   'smallint', '' ),

			# null changes
			array( 'changeNullableField', 'oldimage', 'oi_bits',       'NULL' ),
			array( 'changeNullableField', 'oldimage', 'oi_timestamp',  'NULL' ),
			array( 'changeNullableField', 'oldimage', 'oi_major_mime', 'NULL' ),
			array( 'changeNullableField', 'oldimage', 'oi_minor_mime', 'NULL' ),
			array( 'changeNullableField', 'image', 'img_metadata', 'NOT NULL'),
			array( 'changeNullableField', 'filearchive', 'fa_metadata', 'NOT NULL'),
			array( 'changeNullableField', 'recentchanges', 'rc_cur_id', 'NULL' ),

			array( 'checkOiDeleted' ),

			# New indexes
			array( 'addPgIndex', 'archive',       'archive_user_text',      '(ar_user_text)' ),
			array( 'addPgIndex', 'image',         'img_sha1',               '(img_sha1)' ),
			array( 'addPgIndex', 'ipblocks',      'ipb_parent_block_id',              '(ipb_parent_block_id)' ),
			array( 'addPgIndex', 'oldimage',      'oi_sha1',                '(oi_sha1)' ),
			array( 'addPgIndex', 'page',          'page_mediawiki_title',   '(page_title) WHERE page_namespace = 8' ),
			array( 'addPgIndex', 'pagelinks',     'pagelinks_title',        '(pl_title)' ),
			array( 'addPgIndex', 'revision',      'rev_text_id_idx',        '(rev_text_id)' ),
			array( 'addPgIndex', 'recentchanges', 'rc_timestamp_bot',       '(rc_timestamp) WHERE rc_bot = 0' ),
			array( 'addPgIndex', 'templatelinks', 'templatelinks_from',     '(tl_from)' ),
			array( 'addPgIndex', 'watchlist',     'wl_user',                '(wl_user)' ),
			array( 'addPgIndex', 'logging',       'logging_user_type_time', '(log_user, log_type, log_timestamp)' ),
			array( 'addPgIndex', 'logging',       'logging_page_id_time',   '(log_page,log_timestamp)' ),
			array( 'addPgIndex', 'iwlinks',       'iwl_prefix_title_from',  '(iwl_prefix, iwl_title, iwl_from)' ),
			array( 'addPgIndex', 'job',           'job_timestamp_idx',      '(job_timestamp)' ),

			array( 'checkIndex', 'pagelink_unique', array(
				array('pl_from', 'int4_ops', 'btree', 0),
				array('pl_namespace', 'int2_ops', 'btree', 0),
				array('pl_title', 'text_ops', 'btree', 0),
			),
			'CREATE UNIQUE INDEX pagelink_unique ON pagelinks (pl_from,pl_namespace,pl_title)' ),
			array( 'checkIndex', 'cl_sortkey', array(
				array('cl_to', 'text_ops', 'btree', 0),
				array('cl_sortkey', 'text_ops', 'btree', 0),
				array('cl_from', 'int4_ops', 'btree', 0),
			),
			'CREATE INDEX cl_sortkey ON "categorylinks" USING "btree" ("cl_to", "cl_sortkey", "cl_from")' ),
			array( 'checkIndex', 'logging_times', array(
				array('log_timestamp', 'timestamptz_ops', 'btree', 0),
			),
			'CREATE INDEX "logging_times" ON "logging" USING "btree" ("log_timestamp")' ),
			array( 'dropIndex', 'oldimage', 'oi_name' ),
			array( 'checkIndex', 'oi_name_archive_name', array(
				array('oi_name', 'text_ops', 'btree', 0),
				array('oi_archive_name', 'text_ops', 'btree', 0),
			),
			'CREATE INDEX "oi_name_archive_name" ON "oldimage" USING "btree" ("oi_name", "oi_archive_name")' ),
			array( 'checkIndex', 'oi_name_timestamp', array(
				array('oi_name', 'text_ops', 'btree', 0),
				array('oi_timestamp', 'timestamptz_ops', 'btree', 0),
			),
			'CREATE INDEX "oi_name_timestamp" ON "oldimage" USING "btree" ("oi_name", "oi_timestamp")' ),
			array( 'checkIndex', 'page_main_title', array(
				array('page_title', 'text_pattern_ops', 'btree', 0),
			),
			'CREATE INDEX "page_main_title" ON "page" USING "btree" ("page_title" "text_pattern_ops") WHERE ("page_namespace" = 0)' ),
			array( 'checkIndex', 'page_mediawiki_title', array(
				array('page_title', 'text_pattern_ops', 'btree', 0),
			),
			'CREATE INDEX "page_mediawiki_title" ON "page" USING "btree" ("page_title" "text_pattern_ops") WHERE ("page_namespace" = 8)' ),
			array( 'checkIndex', 'page_project_title', array(
				array('page_title', 'text_pattern_ops', 'btree', 0),
			),
			'CREATE INDEX "page_project_title" ON "page" USING "btree" ("page_title" "text_pattern_ops") WHERE ("page_namespace" = 4)' ),
			array( 'checkIndex', 'page_talk_title', array(
				array('page_title', 'text_pattern_ops', 'btree', 0),
			),
			'CREATE INDEX "page_talk_title" ON "page" USING "btree" ("page_title" "text_pattern_ops") WHERE ("page_namespace" = 1)' ),
			array( 'checkIndex', 'page_user_title', array(
				array('page_title', 'text_pattern_ops', 'btree', 0),
			),
			'CREATE INDEX "page_user_title" ON "page" USING "btree" ("page_title" "text_pattern_ops") WHERE ("page_namespace" = 2)' ),
			array( 'checkIndex', 'page_utalk_title', array(
				array('page_title', 'text_pattern_ops', 'btree', 0),
			),
			'CREATE INDEX "page_utalk_title" ON "page" USING "btree" ("page_title" "text_pattern_ops") WHERE ("page_namespace" = 3)' ),
			array( 'checkIndex', 'ts2_page_text', array(
				array('textvector', 'tsvector_ops', 'gist', 0),
			),
			'CREATE INDEX "ts2_page_text" ON "pagecontent" USING "gist" ("textvector")' ),
			array( 'checkIndex', 'ts2_page_title', array(
				array('titlevector', 'tsvector_ops', 'gist', 0),
			),
			'CREATE INDEX "ts2_page_title" ON "page" USING "gist" ("titlevector")' ),

			array( 'checkOiNameConstraint' ),
			array( 'checkPageDeletedTrigger' ),
			array( 'checkRevUserFkey' ),
			array( 'dropIndex', 'ipblocks', 'ipb_address'),
			array( 'checkIndex', 'ipb_address_unique', array(
				array('ipb_address', 'text_ops', 'btree', 0),
				array('ipb_user',    'int4_ops', 'btree', 0),
				array('ipb_auto',    'int2_ops', 'btree', 0),
				array('ipb_anon_only', 'int2_ops', 'btree', 0),
			),
			'CREATE UNIQUE INDEX ipb_address_unique ON ipblocks (ipb_address,ipb_user,ipb_auto,ipb_anon_only)' ),

			array( 'checkIwlPrefix' ),

			# All FK columns should be deferred
			array( 'changeFkeyDeferrable', 'archive',          'ar_user',         'mwuser(user_id) ON DELETE SET NULL' ),
			array( 'changeFkeyDeferrable', 'categorylinks',    'cl_from',         'page(page_id) ON DELETE CASCADE' ),
			array( 'changeFkeyDeferrable', 'externallinks',    'el_from',         'page(page_id) ON DELETE CASCADE' ),
			array( 'changeFkeyDeferrable', 'filearchive',      'fa_deleted_user', 'mwuser(user_id) ON DELETE SET NULL' ),
			array( 'changeFkeyDeferrable', 'filearchive',      'fa_user',         'mwuser(user_id) ON DELETE SET NULL' ),
			array( 'changeFkeyDeferrable', 'image',            'img_user',        'mwuser(user_id) ON DELETE SET NULL' ),
			array( 'changeFkeyDeferrable', 'imagelinks',       'il_from',         'page(page_id) ON DELETE CASCADE' ),
			array( 'changeFkeyDeferrable', 'ipblocks',         'ipb_by',          'mwuser(user_id) ON DELETE CASCADE' ),
			array( 'changeFkeyDeferrable', 'ipblocks',         'ipb_user',        'mwuser(user_id) ON DELETE SET NULL' ),
			array( 'changeFkeyDeferrable', 'ipblocks',         'ipb_parent_block_id',       'ipblocks(ipb_id) ON DELETE SET NULL' ),
			array( 'changeFkeyDeferrable', 'langlinks',        'll_from',         'page(page_id) ON DELETE CASCADE' ),
			array( 'changeFkeyDeferrable', 'logging',          'log_user',        'mwuser(user_id) ON DELETE SET NULL' ),
			array( 'changeFkeyDeferrable', 'oldimage',         'oi_name',         'image(img_name) ON DELETE CASCADE ON UPDATE CASCADE' ),
			array( 'changeFkeyDeferrable', 'oldimage',         'oi_user',         'mwuser(user_id) ON DELETE SET NULL' ),
			array( 'changeFkeyDeferrable', 'pagelinks',        'pl_from',         'page(page_id) ON DELETE CASCADE' ),
			array( 'changeFkeyDeferrable', 'page_props',       'pp_page',         'page (page_id) ON DELETE CASCADE' ),
			array( 'changeFkeyDeferrable', 'page_restrictions', 'pr_page',         'page(page_id) ON DELETE CASCADE' ),
			array( 'changeFkeyDeferrable', 'protected_titles', 'pt_user',         'mwuser(user_id) ON DELETE SET NULL' ),
			array( 'changeFkeyDeferrable', 'recentchanges',    'rc_cur_id',       'page(page_id) ON DELETE SET NULL' ),
			array( 'changeFkeyDeferrable', 'recentchanges',    'rc_user',         'mwuser(user_id) ON DELETE SET NULL' ),
			array( 'changeFkeyDeferrable', 'redirect',         'rd_from',         'page(page_id) ON DELETE CASCADE' ),
			array( 'changeFkeyDeferrable', 'revision',         'rev_page',        'page (page_id) ON DELETE CASCADE' ),
			array( 'changeFkeyDeferrable', 'revision',         'rev_user',        'mwuser(user_id) ON DELETE RESTRICT' ),
			array( 'changeFkeyDeferrable', 'templatelinks',    'tl_from',         'page(page_id) ON DELETE CASCADE' ),
			array( 'changeFkeyDeferrable', 'user_groups',      'ug_user',         'mwuser(user_id) ON DELETE CASCADE' ),
			array( 'changeFkeyDeferrable', 'user_newtalk',     'user_id',         'mwuser(user_id) ON DELETE CASCADE' ),
			array( 'changeFkeyDeferrable', 'user_properties',  'up_user',         'mwuser(user_id) ON DELETE CASCADE' ),
			array( 'changeFkeyDeferrable', 'watchlist',        'wl_user',         'mwuser(user_id) ON DELETE CASCADE' ),

			# r81574
			array( 'addInterwikiType' ),
			# end
			array( 'tsearchFixes' ),
		);
	}

	protected function getOldGlobalUpdates() {
		global $wgExtNewTables, $wgExtPGNewFields, $wgExtPGAlteredFields, $wgExtNewIndexes;

		$updates = array();

		# Add missing extension tables
		foreach ( $wgExtNewTables as $tableRecord ) {
			$updates[] = array(
				'addTable', $tableRecord[0], $tableRecord[1], true
			);
		}

		# Add missing extension fields
		foreach ( $wgExtPGNewFields as $fieldRecord ) {
			$updates[] = array(
					'addPgField', $fieldRecord[0], $fieldRecord[1],
					$fieldRecord[2]
				);
		}

		# Change altered columns
		foreach ( $wgExtPGAlteredFields as $fieldRecord ) {
			$updates[] = array(
					'changeField', $fieldRecord[0], $fieldRecord[1],
					$fieldRecord[2]
				);
		}

		# Add missing extension indexes
		foreach ( $wgExtNewIndexes as $fieldRecord ) {
			$updates[] = array(
					'addPgExtIndex', $fieldRecord[0], $fieldRecord[1],
					$fieldRecord[2]
				);
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

		$cols = array();
		foreach ( $res as $r ) {
			$cols[] = array(
					"name" => $r[0],
					"ord" => $r[1],
				);
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
		if ( !( $r = $this->db->fetchRow( $res ) ) ) {
			return null;
		}

		$indkey = $r[0];
		$relid = intval( $r[1] );
		$indkeys = explode( ' ', $indkey );

		$colnames = array();
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
			if ( !( $row2 = $this->db->fetchRow( $r2 ) ) ) {
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
		if ( !( $row = $this->db->fetchRow( $r ) ) ) {
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
			$this->db->query( "CREATE SEQUENCE $ns" );
			if( $pkey !== false ) {
				$this->setDefault( $table,  $pkey, '"nextval"(\'"' . $ns . '"\'::"regclass")' );
			}
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

	protected function renameTable( $old, $new, $patch = false ) {
		if ( $this->db->tableExists( $old ) ) {
			$this->output( "Renaming table $old to $new\n" );
			$old = $this->db->realTableName( $old, "quoted" );
			$new = $this->db->realTableName( $new, "quoted" );
			$this->db->query( "ALTER TABLE $old RENAME TO $new" );
			if( $patch !== false ) {
				$this->applyPatch( $patch );
			}
		}
	}

	protected function renameIndex( $table, $old, $new ) {
		if ( $this->db->indexExists( $table, $old ) ) {
			$this->output( "Renaming index $old to $new\n" );
			$this->db->query( "ALTER INDEX $old RENAME TO $new" );
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

		if ( $fi->type() === $newtype )
			$this->output( "...column '$table.$field' is already of type '$newtype'\n" );
		else {
			$this->output( "Changing column type of '$table.$field' from '{$fi->type()}' to '$newtype'\n" );
			$sql = "ALTER TABLE $table ALTER $field TYPE $newtype";
			if ( strlen( $default ) ) {
				$res = array();
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
			$this->db->query( "ALTER TABLE $table ALTER $field SET DEFAULT " . $default );
		}
	}

	protected function changeNullableField( $table, $field, $null) {
		$fi = $this->db->fieldInfo( $table, $field );
		if ( is_null( $fi ) ) {
			$this->output( "...ERROR: expected column $table.$field to exist\n" );
			exit( 1 );
		}
		if ( $fi->isNullable() ) {
			# # It's NULL - does it need to be NOT NULL?
			if ( 'NOT NULL' === $null ) {
				$this->output( "Changing '$table.$field' to not allow NULLs\n" );
				$this->db->query( "ALTER TABLE $table ALTER $field SET NOT NULL" );
			} else {
				$this->output( "...column '$table.$field' is already set as NULL\n" );
			}
		} else {
			# # It's NOT NULL - does it need to be NULL?
			if ( 'NULL' === $null ) {
				$this->output( "Changing '$table.$field' to allow NULLs\n" );
				$this->db->query( "ALTER TABLE $table ALTER $field DROP NOT NULL" );
			}
			else {
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

	protected function changeFkeyDeferrable( $table, $field, $clause ) {
		$fi = $this->db->fieldInfo( $table, $field );
		if ( is_null( $fi ) ) {
			$this->output( "WARNING! Column '$table.$field' does not exist but it should! Please report this.\n" );
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
			$this->output( "Column '$table.$field' does not have a foreign key constraint, will be added\n" );
			$conclause = "";
		}
		$command = "ALTER TABLE $table ADD $conclause FOREIGN KEY ($field) REFERENCES $clause DEFERRABLE INITIALLY DEFERRED";
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
			$this->applyPatch( 'patch-remove-archive2.sql', false, "Converting 'archive2' back to normal archive table" );
		} else {
			$this->output( "...obsolete table 'archive2' does not exist\n" );
		}
	}

	protected function checkOiDeleted() {
		if ( $this->db->fieldInfo( 'oldimage', 'oi_deleted' )->type() !== 'smallint' ) {
			$this->output( "Changing 'oldimage.oi_deleted' to type 'smallint'\n" );
			$this->db->query( "ALTER TABLE oldimage ALTER oi_deleted DROP DEFAULT" );
			$this->db->query( "ALTER TABLE oldimage ALTER oi_deleted TYPE SMALLINT USING (oi_deleted::smallint)" );
			$this->db->query( "ALTER TABLE oldimage ALTER oi_deleted SET DEFAULT 0" );
		} else {
			$this->output( "...column 'oldimage.oi_deleted' is already of type 'smallint'\n" );
		}
	}

	protected function checkOiNameConstraint() {
		if ( $this->db->hasConstraint( "oldimage_oi_name_fkey_cascaded" ) ) {
			$this->output( "...table 'oldimage' has correct cascading delete/update foreign key to image\n" );
		} else {
			if ( $this->db->hasConstraint( "oldimage_oi_name_fkey" ) ) {
				$this->db->query( "ALTER TABLE oldimage DROP CONSTRAINT oldimage_oi_name_fkey" );
			}
			if ( $this->db->hasConstraint( "oldimage_oi_name_fkey_cascade" ) ) {
				$this->db->query( "ALTER TABLE oldimage DROP CONSTRAINT oldimage_oi_name_fkey_cascade" );
			}
			$this->output( "Making foreign key on table 'oldimage' (to image) a cascade delete/update\n" );
			$this->db->query( "ALTER TABLE oldimage ADD CONSTRAINT oldimage_oi_name_fkey_cascaded " .
				"FOREIGN KEY (oi_name) REFERENCES image(img_name) ON DELETE CASCADE ON UPDATE CASCADE" );
		}
	}

	protected function checkPageDeletedTrigger() {
		if ( !$this->db->triggerExists( 'page', 'page_deleted' ) ) {
			$this->applyPatch( 'patch-page_deleted.sql', false, "Adding function and trigger 'page_deleted' to table 'page'" );
		} else {
			$this->output( "...table 'page' has 'page_deleted' trigger\n" );
		}
	}

	protected function dropIndex( $table, $index, $patch = '', $fullpath = false ) {
		if ( $this->db->indexExists( $table, $index ) ) {
			$this->output( "Dropping obsolete index '$index'\n" );
			$this->db->query( "DROP INDEX \"". $index ."\"" );
		}
	}

	protected function checkIndex( $index, $should_be, $good_def ) {
		$pu = $this->db->indexAttributes( $index );
		if ( !empty( $pu ) && $pu != $should_be ) {
			$this->output( "Dropping obsolete version of index '$index'\n" );
			$this->db->query( "DROP INDEX \"". $index ."\"" );
			$pu = array();
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
			$this->applyPatch( 'patch-revision_rev_user_fkey.sql', false, "Changing constraint 'revision_rev_user_fkey' to ON DELETE RESTRICT" );
		}
	}

	protected function checkIwlPrefix() {
		if ( $this->db->indexExists( 'iwlinks', 'iwl_prefix' ) ) {
			$this->applyPatch( 'patch-rename-iwl_prefix.sql', false, "Replacing index 'iwl_prefix' with 'iwl_prefix_from_title'" );
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
}
