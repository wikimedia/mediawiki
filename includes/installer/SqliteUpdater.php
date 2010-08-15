<?php

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
			array( 'add_field', 'site_stats',    'ss_active_users',  'patch-ss_active_users.sql' ),
			array( 'do_active_users_init' ),
			array( 'add_field', 'ipblocks',      'ipb_allow_usertalk', 'patch-ipb_allow_usertalk.sql' ),
			array( 'sqlite_initial_indexes' ),

			// 1.15
			array( 'add_table', 'change_tag',                        'patch-change_tag.sql' ),
			array( 'add_table', 'tag_summary',                       'patch-change_tag.sql' ),
			array( 'add_table', 'valid_tag',                         'patch-change_tag.sql' ),

			// 1.16
			array( 'add_table', 'user_properties',                   'patch-user_properties.sql' ),
			array( 'add_table', 'log_search',                        'patch-log_search.sql' ),
			array( 'do_log_search_population' ),
			array( 'add_field', 'logging',       'log_user_text',    'patch-log_user_text.sql' ),
			array( 'add_table', 'l10n_cache',                        'patch-l10n_cache.sql' ),
			array( 'add_table', 'external_user',                     'patch-external_user.sql' ),
			array( 'add_index', 'log_search',    'ls_field_val',     'patch-log_search-rename-index.sql' ),
			array( 'add_index', 'change_tag',    'change_tag_rc_tag', 'patch-change_tag-indexes.sql' ),
			array( 'add_field', 'redirect',      'rd_interwiki',     'patch-rd_interwiki.sql' ),
			array( 'do_update_transcache_field' ),
			array( 'sqlite_setup_searchindex' ),

			// 1.17
			array( 'add_table', 'iwlinks',                            'patch-iwlinks.sql' ),
			array( 'add_index', 'iwlinks',   'iwl_prefix_title_from', 'patch-rename-iwl_prefix.sql' ),
			array( 'add_field', 'updatelog', 'ul_value',              'patch-ul_value.sql' ),
			array( 'add_field', 'interwiki',     'iw_api',           'patch-iw_api_and_wikiid.sql' ),
			array( 'drop_index_if_exists', 'iwlinks', 'iwl_prefix',  'patch-kill-iwl_prefix.sql' ),
			array( 'drop_index_if_exists', 'iwlinks', 'iwl_prefix_from_title', 'patch-kill-iwl_pft.sql' ),
		);
	}
}
