<?php
/**
 * MySQL-specific updater.
 *
 * @file
 * @ingroup Deployment
 */

/**
 * Mysql update list and mysql-specific update functions.
 * 
 * @ingroup Deployment
 * @since 1.17
 */
class MysqlUpdater extends DatabaseUpdater {
	
	protected function getCoreUpdateList() {
		return array(
			// 1.2
			array( 'addField', 'ipblocks',      'ipb_id',           'patch-ipblocks.sql' ),
			array( 'addField', 'ipblocks',      'ipb_expiry',       'patch-ipb_expiry.sql' ),
			array( 'do_interwiki_update' ),
			array( 'do_index_update' ),
			array( 'addTable', 'hitcounter',                        'patch-hitcounter.sql' ),
			array( 'addField', 'recentchanges', 'rc_type',          'patch-rc_type.sql' ),

			// 1.3
			array( 'addField', 'user',          'user_real_name',   'patch-user-realname.sql' ),
			array( 'addTable', 'querycache',                        'patch-querycache.sql' ),
			array( 'addTable', 'objectcache',                       'patch-objectcache.sql' ),
			array( 'addTable', 'categorylinks',                     'patch-categorylinks.sql' ),
			array( 'do_old_links_update' ),
			array( 'fix_ancient_imagelinks' ),
			array( 'addField', 'recentchanges', 'rc_ip',            'patch-rc_ip.sql' ),

			// 1.4
			array( 'do_image_name_unique_update' ),
			array( 'addField', 'recentchanges', 'rc_id',            'patch-rc_id.sql' ),
			array( 'addField', 'recentchanges', 'rc_patrolled',     'patch-rc-patrol.sql' ),
			array( 'addTable', 'logging',                           'patch-logging.sql' ),
			array( 'addField', 'user',          'user_token',       'patch-user_token.sql' ),
			array( 'do_watchlist_update' ),
			array( 'dropField', 'user',         'user_emailauthenticationtimestamp', 'patch-email-authentication.sql' ),

			// 1.5
			array( 'do_schema_restructuring' ),
			array( 'addField', 'logging',       'log_params',       'patch-log_params.sql' ),
			array( 'check_bin', 'logging',      'log_title',        'patch-logging-title.sql', ),
			array( 'addField', 'archive',       'ar_rev_id',        'patch-archive-rev_id.sql' ),
			array( 'addField', 'page',          'page_len',         'patch-page_len.sql' ),
			array( 'dropField', 'revision',     'inverse_timestamp', 'patch-inverse_timestamp.sql' ),
			array( 'addField', 'revision',      'rev_text_id',      'patch-rev_text_id.sql' ),
			array( 'addField', 'revision',      'rev_deleted',      'patch-rev_deleted.sql' ),
			array( 'addField', 'image',         'img_width',        'patch-img_width.sql' ),
			array( 'addField', 'image',         'img_metadata',     'patch-img_metadata.sql' ),
			array( 'addField', 'user',          'user_email_token', 'patch-user_email_token.sql' ),
			array( 'addField', 'archive',       'ar_text_id',       'patch-archive-text_id.sql' ),
			array( 'doNamespaceSize' ),
			array( 'addField', 'image',         'img_media_type',   'patch-img_media_type.sql' ),
			array( 'do_pagelinks_update' ),
			array( 'dropField', 'image',        'img_type',         'patch-drop_img_type.sql' ),
			array( 'do_user_unique_update' ),
			array( 'do_user_groups_update' ),
			array( 'addField', 'site_stats',    'ss_total_pages',   'patch-ss_total_articles.sql' ),
			array( 'addTable', 'user_newtalk',                      'patch-usernewtalk2.sql' ),
			array( 'addTable', 'transcache',                        'patch-transcache.sql' ),
			array( 'addField', 'interwiki',     'iw_trans',         'patch-interwiki-trans.sql' ),
			array( 'addTable', 'trackbacks',                        'patch-trackbacks.sql' ),

			// 1.6
			array( 'do_watchlist_null' ),
			array( 'addIndex', 'logging',         'times',            'patch-logging-times-index.sql' ),
			array( 'addField', 'ipblocks',        'ipb_range_start',  'patch-ipb_range_start.sql' ),
			array( 'do_page_random_update' ),
			array( 'addField', 'user',            'user_registration', 'patch-user_registration.sql' ),
			array( 'do_templatelinks_update' ),
			array( 'addTable', 'externallinks',                       'patch-externallinks.sql' ),
			array( 'addTable', 'job',                                 'patch-job.sql' ),
			array( 'addField', 'site_stats',      'ss_images',        'patch-ss_images.sql' ),
			array( 'addTable', 'langlinks',                           'patch-langlinks.sql' ),
			array( 'addTable', 'querycache_info',                     'patch-querycacheinfo.sql' ),
			array( 'addTable', 'filearchive',                         'patch-filearchive.sql' ),
			array( 'addField', 'ipblocks',        'ipb_anon_only',    'patch-ipb_anon_only.sql' ),
			array( 'do_rc_indices_update' ),

			// 1.9
			array( 'addField', 'user',          'user_newpass_time', 'patch-user_newpass_time.sql' ),
			array( 'addTable', 'redirect',                           'patch-redirect.sql' ),
			array( 'addTable', 'querycachetwo',                      'patch-querycachetwo.sql' ),
			array( 'addField', 'ipblocks',      'ipb_enable_autoblock', 'patch-ipb_optional_autoblock.sql' ),
			array( 'do_backlinking_indices_update' ),
			array( 'addField', 'recentchanges', 'rc_old_len',        'patch-rc_len.sql' ),
			array( 'addField', 'user',          'user_editcount',    'patch-user_editcount.sql' ),

			// 1.10
			array( 'do_restrictions_update' ),
			array( 'addField', 'logging',       'log_id',           'patch-log_id.sql' ),
			array( 'addField', 'revision',      'rev_parent_id',    'patch-rev_parent_id.sql' ),
			array( 'addField', 'page_restrictions', 'pr_id',        'patch-page_restrictions_sortkey.sql' ),
			array( 'addField', 'revision',      'rev_len',          'patch-rev_len.sql' ),
			array( 'addField', 'recentchanges', 'rc_deleted',       'patch-rc_deleted.sql' ),
			array( 'addField', 'logging',       'log_deleted',      'patch-log_deleted.sql' ),
			array( 'addField', 'archive',       'ar_deleted',       'patch-ar_deleted.sql' ),
			array( 'addField', 'ipblocks',      'ipb_deleted',      'patch-ipb_deleted.sql' ),
			array( 'addField', 'filearchive',   'fa_deleted',       'patch-fa_deleted.sql' ),
			array( 'addField', 'archive',       'ar_len',           'patch-ar_len.sql' ),

			// 1.11
			array( 'addField', 'ipblocks',      'ipb_block_email',  'patch-ipb_emailban.sql' ),
			array( 'do_categorylinks_indices_update' ),
			array( 'addField', 'oldimage',      'oi_metadata',      'patch-oi_metadata.sql' ),
			array( 'addIndex', 'archive',       'usertext_timestamp', 'patch-archive-user-index.sql' ),
			array( 'addIndex', 'image',         'img_usertext_timestamp', 'patch-image-user-index.sql' ),
			array( 'addIndex', 'oldimage',      'oi_usertext_timestamp', 'patch-oldimage-user-index.sql' ),
			array( 'addField', 'archive',       'ar_page_id',       'patch-archive-page_id.sql' ),
			array( 'addField', 'image',         'img_sha1',         'patch-img_sha1.sql' ),

			// 1.12
			array( 'addTable', 'protected_titles',                  'patch-protected_titles.sql' ),

			// 1.13
			array( 'addField', 'ipblocks',      'ipb_by_text',      'patch-ipb_by_text.sql' ),
			array( 'addTable', 'page_props',                        'patch-page_props.sql' ),
			array( 'addTable', 'updatelog',                         'patch-updatelog.sql' ),
			array( 'addTable', 'category',                          'patch-category.sql' ),
			array( 'do_category_population' ),
			array( 'addField', 'archive',       'ar_parent_id',     'patch-ar_parent_id.sql' ),
			array( 'addField', 'user_newtalk',  'user_last_timestamp', 'patch-user_last_timestamp.sql' ),
			array( 'do_populate_parent_id' ),
			array( 'check_bin', 'protected_titles', 'pt_title',      'patch-pt_title-encoding.sql', ),
			array( 'maybe_do_profiling_memory_update' ),
			array( 'do_filearchive_indices_update' ),

			// 1.14
			array( 'addField', 'site_stats',    'ss_active_users',  'patch-ss_active_users.sql' ),
			array( 'do_active_users_init' ),
			array( 'addField', 'ipblocks',      'ipb_allow_usertalk', 'patch-ipb_allow_usertalk.sql' ),

			// 1.15
			array( 'do_unique_pl_tl_il' ),
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
			array( 'rename_eu_wiki_id' ),
			array( 'do_update_mime_minor_field' ),
			array( 'do_populate_rev_len' ),

			// 1.17
			array( 'addTable', 'iwlinks',                           'patch-iwlinks.sql' ),
			array( 'addIndex', 'iwlinks', 'iwl_prefix_title_from',  'patch-rename-iwl_prefix.sql' ),
			array( 'addField', 'updatelog', 'ul_value',              'patch-ul_value.sql' ),
			array( 'addField', 'interwiki',     'iw_api',           'patch-iw_api_and_wikiid.sql' ),
			array( 'dropIndex', 'iwlinks', 'iwl_prefix',  'patch-kill-iwl_prefix.sql' ),
			array( 'dropIndex', 'iwlinks', 'iwl_prefix_from_title', 'patch-kill-iwl_pft.sql' ),
			array( 'addField', 'categorylinks', 'cl_collation', 'patch-categorylinks-better-collation.sql' ),
			array( 'do_cl_fields_update' ),
			array( 'do_collation_update' ),
			array( 'addTable', 'msg_resource',                      'patch-msg_resource.sql' ),
			array( 'addTable', 'module_deps',                       'patch-module_deps.sql' ),
		);
	}

	protected function doNamespaceSize() {
		$tables = array(
			'page'          => 'page',
			'archive'       => 'ar',
			'recentchanges' => 'rc',
			'watchlist'     => 'wl',
			'querycache'    => 'qc',
			'logging'       => 'log',
		);
		foreach ( $tables as $table => $prefix ) {
			$field = $prefix . '_namespace';

			$tablename = $this->db->tableName( $table );
			$result = $this->db->query( "SHOW COLUMNS FROM $tablename LIKE '$field'" );
			$info = $this->db->fetchObject( $result );

			if ( substr( $info->Type, 0, 3 ) == 'int' ) {
				wfOut( "...$field is already a full int ($info->Type).\n" );
			} else {
				wfOut( "Promoting $field from $info->Type to int... " );

				$sql = "ALTER TABLE $tablename MODIFY $field int NOT NULL";
				$this->db->query( $sql );

				wfOut( "ok\n" );
			}
		}
	}
}
