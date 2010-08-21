<?php
/**
 * Sqlite-specific updater.
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
			array( 'addField', 'site_stats',    'ss_active_users',  'patch-ss_active_users.sql' ),
			array( 'do_active_users_init' ),
			array( 'addField', 'ipblocks',      'ipb_allow_usertalk', 'patch-ipb_allow_usertalk.sql' ),
			array( 'sqlite_initial_indexes' ),

			// 1.15
			array( 'addTable', 'change_tag',                        'patch-change_tag.sql' ),
			array( 'addTable', 'tag_summary',                       'patch-change_tag.sql' ),
			array( 'addTable', 'valid_tag',                         'patch-change_tag.sql' ),

			// 1.16
			array( 'addTable', 'user_properties',                   'patch-user_properties.sql' ),
			array( 'addTable', 'log_search',                        'patch-log_search.sql' ),
			array( 'do_log_search_population' ),
			array( 'addField', 'logging',       'log_user_text',    'patch-log_user_text.sql' ),
			array( 'addTable', 'l10n_cache',                        'patch-l10n_cache.sql' ),
			array( 'addTable', 'external_user',                     'patch-external_user.sql' ),
			array( 'addIndex', 'log_search',    'ls_field_val',     'patch-log_search-rename-index.sql' ),
			array( 'addIndex', 'change_tag',    'change_tag_rc_tag', 'patch-change_tag-indexes.sql' ),
			array( 'addField', 'redirect',      'rd_interwiki',     'patch-rd_interwiki.sql' ),
			array( 'do_update_transcache_field' ),
			array( 'sqlite_setup_searchindex' ),

			// 1.17
			array( 'addTable', 'iwlinks',                            'patch-iwlinks.sql' ),
			array( 'addIndex', 'iwlinks',   'iwl_prefix_title_from', 'patch-rename-iwl_prefix.sql' ),
			array( 'addField', 'updatelog', 'ul_value',              'patch-ul_value.sql' ),
			array( 'addField', 'interwiki',     'iw_api',           'patch-iw_api_and_wikiid.sql' ),
			array( 'drop_index_if_exists', 'iwlinks', 'iwl_prefix',  'patch-kill-iwl_prefix.sql' ),
			array( 'drop_index_if_exists', 'iwlinks', 'iwl_prefix_from_title', 'patch-kill-iwl_pft.sql' ),
		);
	}
}
