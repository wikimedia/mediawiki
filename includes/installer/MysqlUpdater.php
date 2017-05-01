<?php
/**
 * MySQL-specific updater.
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
use Wikimedia\Rdbms\Field;
use Wikimedia\Rdbms\MySQLField;
use MediaWiki\MediaWikiServices;

/**
 * Mysql update list and mysql-specific update functions.
 *
 * @ingroup Deployment
 * @since 1.17
 */
class MysqlUpdater extends DatabaseUpdater {
	protected function getCoreUpdateList() {
		return [
			[ 'disableContentHandlerUseDB' ],

			// 1.2
			[ 'addField', 'ipblocks', 'ipb_id', 'patch-ipblocks.sql' ],
			[ 'addField', 'ipblocks', 'ipb_expiry', 'patch-ipb_expiry.sql' ],
			[ 'doInterwikiUpdate' ],
			[ 'doIndexUpdate' ],
			[ 'addField', 'recentchanges', 'rc_type', 'patch-rc_type.sql' ],
			[ 'addIndex', 'recentchanges', 'new_name_timestamp', 'patch-rc-newindex.sql' ],

			// 1.3
			[ 'addField', 'user', 'user_real_name', 'patch-user-realname.sql' ],
			[ 'addTable', 'querycache', 'patch-querycache.sql' ],
			[ 'addTable', 'objectcache', 'patch-objectcache.sql' ],
			[ 'addTable', 'categorylinks', 'patch-categorylinks.sql' ],
			[ 'doOldLinksUpdate' ],
			[ 'doFixAncientImagelinks' ],
			[ 'addField', 'recentchanges', 'rc_ip', 'patch-rc_ip.sql' ],

			// 1.4
			[ 'addIndex', 'image', 'PRIMARY', 'patch-image_name_primary.sql' ],
			[ 'addField', 'recentchanges', 'rc_id', 'patch-rc_id.sql' ],
			[ 'addField', 'recentchanges', 'rc_patrolled', 'patch-rc-patrol.sql' ],
			[ 'addTable', 'logging', 'patch-logging.sql' ],
			[ 'addField', 'user', 'user_token', 'patch-user_token.sql' ],
			[ 'addField', 'watchlist', 'wl_notificationtimestamp', 'patch-email-notification.sql' ],
			[ 'doWatchlistUpdate' ],
			[ 'dropField', 'user', 'user_emailauthenticationtimestamp',
				'patch-email-authentication.sql' ],

			// 1.5
			[ 'doSchemaRestructuring' ],
			[ 'addField', 'logging', 'log_params', 'patch-log_params.sql' ],
			[ 'checkBin', 'logging', 'log_title', 'patch-logging-title.sql', ],
			[ 'addField', 'archive', 'ar_rev_id', 'patch-archive-rev_id.sql' ],
			[ 'addField', 'page', 'page_len', 'patch-page_len.sql' ],
			[ 'dropField', 'revision', 'inverse_timestamp', 'patch-inverse_timestamp.sql' ],
			[ 'addField', 'revision', 'rev_text_id', 'patch-rev_text_id.sql' ],
			[ 'addField', 'revision', 'rev_deleted', 'patch-rev_deleted.sql' ],
			[ 'addField', 'image', 'img_width', 'patch-img_width.sql' ],
			[ 'addField', 'image', 'img_metadata', 'patch-img_metadata.sql' ],
			[ 'addField', 'user', 'user_email_token', 'patch-user_email_token.sql' ],
			[ 'addField', 'archive', 'ar_text_id', 'patch-archive-text_id.sql' ],
			[ 'doNamespaceSize' ],
			[ 'addField', 'image', 'img_media_type', 'patch-img_media_type.sql' ],
			[ 'doPagelinksUpdate' ],
			[ 'dropField', 'image', 'img_type', 'patch-drop_img_type.sql' ],
			[ 'doUserUniqueUpdate' ],
			[ 'doUserGroupsUpdate' ],
			[ 'addField', 'site_stats', 'ss_total_pages', 'patch-ss_total_articles.sql' ],
			[ 'addTable', 'user_newtalk', 'patch-usernewtalk2.sql' ],
			[ 'addTable', 'transcache', 'patch-transcache.sql' ],
			[ 'addField', 'interwiki', 'iw_trans', 'patch-interwiki-trans.sql' ],

			// 1.6
			[ 'doWatchlistNull' ],
			[ 'addIndex', 'logging', 'times', 'patch-logging-times-index.sql' ],
			[ 'addField', 'ipblocks', 'ipb_range_start', 'patch-ipb_range_start.sql' ],
			[ 'doPageRandomUpdate' ],
			[ 'addField', 'user', 'user_registration', 'patch-user_registration.sql' ],
			[ 'doTemplatelinksUpdate' ],
			[ 'addTable', 'externallinks', 'patch-externallinks.sql' ],
			[ 'addTable', 'job', 'patch-job.sql' ],
			[ 'addField', 'site_stats', 'ss_images', 'patch-ss_images.sql' ],
			[ 'addTable', 'langlinks', 'patch-langlinks.sql' ],
			[ 'addTable', 'querycache_info', 'patch-querycacheinfo.sql' ],
			[ 'addTable', 'filearchive', 'patch-filearchive.sql' ],
			[ 'addField', 'ipblocks', 'ipb_anon_only', 'patch-ipb_anon_only.sql' ],
			[ 'addIndex', 'recentchanges', 'rc_ns_usertext', 'patch-recentchanges-utindex.sql' ],
			[ 'addIndex', 'recentchanges', 'rc_user_text', 'patch-rc_user_text-index.sql' ],

			// 1.9
			[ 'addField', 'user', 'user_newpass_time', 'patch-user_newpass_time.sql' ],
			[ 'addTable', 'redirect', 'patch-redirect.sql' ],
			[ 'addTable', 'querycachetwo', 'patch-querycachetwo.sql' ],
			[ 'addField', 'ipblocks', 'ipb_enable_autoblock', 'patch-ipb_optional_autoblock.sql' ],
			[ 'doBacklinkingIndicesUpdate' ],
			[ 'addField', 'recentchanges', 'rc_old_len', 'patch-rc_len.sql' ],
			[ 'addField', 'user', 'user_editcount', 'patch-user_editcount.sql' ],

			// 1.10
			[ 'doRestrictionsUpdate' ],
			[ 'addField', 'logging', 'log_id', 'patch-log_id.sql' ],
			[ 'addField', 'revision', 'rev_parent_id', 'patch-rev_parent_id.sql' ],
			[ 'addField', 'page_restrictions', 'pr_id', 'patch-page_restrictions_sortkey.sql' ],
			[ 'addField', 'revision', 'rev_len', 'patch-rev_len.sql' ],
			[ 'addField', 'recentchanges', 'rc_deleted', 'patch-rc_deleted.sql' ],
			[ 'addField', 'logging', 'log_deleted', 'patch-log_deleted.sql' ],
			[ 'addField', 'archive', 'ar_deleted', 'patch-ar_deleted.sql' ],
			[ 'addField', 'ipblocks', 'ipb_deleted', 'patch-ipb_deleted.sql' ],
			[ 'addField', 'filearchive', 'fa_deleted', 'patch-fa_deleted.sql' ],
			[ 'addField', 'archive', 'ar_len', 'patch-ar_len.sql' ],

			// 1.11
			[ 'addField', 'ipblocks', 'ipb_block_email', 'patch-ipb_emailban.sql' ],
			[ 'doCategorylinksIndicesUpdate' ],
			[ 'addField', 'oldimage', 'oi_metadata', 'patch-oi_metadata.sql' ],
			[ 'addIndex', 'archive', 'usertext_timestamp', 'patch-archive-user-index.sql' ],
			[ 'addIndex', 'image', 'img_usertext_timestamp', 'patch-image-user-index.sql' ],
			[ 'addIndex', 'oldimage', 'oi_usertext_timestamp', 'patch-oldimage-user-index.sql' ],
			[ 'addField', 'archive', 'ar_page_id', 'patch-archive-page_id.sql' ],
			[ 'addField', 'image', 'img_sha1', 'patch-img_sha1.sql' ],

			// 1.12
			[ 'addTable', 'protected_titles', 'patch-protected_titles.sql' ],

			// 1.13
			[ 'addField', 'ipblocks', 'ipb_by_text', 'patch-ipb_by_text.sql' ],
			[ 'addTable', 'page_props', 'patch-page_props.sql' ],
			[ 'addTable', 'updatelog', 'patch-updatelog.sql' ],
			[ 'addTable', 'category', 'patch-category.sql' ],
			[ 'doCategoryPopulation' ],
			[ 'addField', 'archive', 'ar_parent_id', 'patch-ar_parent_id.sql' ],
			[ 'addField', 'user_newtalk', 'user_last_timestamp', 'patch-user_last_timestamp.sql' ],
			[ 'doPopulateParentId' ],
			[ 'checkBin', 'protected_titles', 'pt_title', 'patch-pt_title-encoding.sql', ],
			[ 'doMaybeProfilingMemoryUpdate' ],
			[ 'doFilearchiveIndicesUpdate' ],

			// 1.14
			[ 'addField', 'site_stats', 'ss_active_users', 'patch-ss_active_users.sql' ],
			[ 'doActiveUsersInit' ],
			[ 'addField', 'ipblocks', 'ipb_allow_usertalk', 'patch-ipb_allow_usertalk.sql' ],

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
			[ 'doUpdateMimeMinorField' ],

			// 1.17
			[ 'addTable', 'iwlinks', 'patch-iwlinks.sql' ],
			[ 'addIndex', 'iwlinks', 'iwl_prefix_title_from', 'patch-rename-iwl_prefix.sql' ],
			[ 'addField', 'updatelog', 'ul_value', 'patch-ul_value.sql' ],
			[ 'addField', 'interwiki', 'iw_api', 'patch-iw_api_and_wikiid.sql' ],
			[ 'dropIndex', 'iwlinks', 'iwl_prefix', 'patch-kill-iwl_prefix.sql' ],
			[ 'addField', 'categorylinks', 'cl_collation', 'patch-categorylinks-better-collation.sql' ],
			[ 'doClFieldsUpdate' ],
			[ 'addTable', 'module_deps', 'patch-module_deps.sql' ],
			[ 'dropIndex', 'archive', 'ar_page_revid', 'patch-archive_kill_ar_page_revid.sql' ],
			[ 'addIndex', 'archive', 'ar_revid', 'patch-archive_ar_revid.sql' ],
			[ 'doLangLinksLengthUpdate' ],

			// 1.18
			[ 'doUserNewTalkTimestampNotNull' ],
			[ 'addIndex', 'user', 'user_email', 'patch-user_email_index.sql' ],
			[ 'modifyField', 'user_properties', 'up_property', 'patch-up_property.sql' ],
			[ 'addTable', 'uploadstash', 'patch-uploadstash.sql' ],
			[ 'addTable', 'user_former_groups', 'patch-user_former_groups.sql' ],

			// 1.19
			[ 'addIndex', 'logging', 'type_action', 'patch-logging-type-action-index.sql' ],
			[ 'addField', 'revision', 'rev_sha1', 'patch-rev_sha1.sql' ],
			[ 'doMigrateUserOptions' ],
			[ 'dropField', 'user', 'user_options', 'patch-drop-user_options.sql' ],
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
			[ 'doIwlinksIndexNonUnique' ],
			[ 'addIndex', 'iwlinks', 'iwl_prefix_from_title',
				'patch-iwlinks-from-title-index.sql' ],
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
			[ 'addField', 'page', 'page_lang', 'patch-page_lang.sql' ],
			[ 'addField', 'pagelinks', 'pl_from_namespace', 'patch-pl_from_namespace.sql' ],
			[ 'addField', 'templatelinks', 'tl_from_namespace', 'patch-tl_from_namespace.sql' ],
			[ 'addField', 'imagelinks', 'il_from_namespace', 'patch-il_from_namespace.sql' ],
			[ 'modifyField', 'image', 'img_major_mime',
				'patch-img_major_mime-chemical.sql' ],
			[ 'modifyField', 'oldimage', 'oi_major_mime',
				'patch-oi_major_mime-chemical.sql' ],
			[ 'modifyField', 'filearchive', 'fa_major_mime',
				'patch-fa_major_mime-chemical.sql' ],

			// 1.25
			[ 'doUserNewTalkUseridUnsigned' ],
			// note this patch covers other _comment and _description fields too
			[ 'modifyField', 'recentchanges', 'rc_comment', 'patch-editsummary-length.sql' ],

			// 1.26
			[ 'dropTable', 'hitcounter' ],
			[ 'dropField', 'site_stats', 'ss_total_views', 'patch-drop-ss_total_views.sql' ],
			[ 'dropField', 'page', 'page_counter', 'patch-drop-page_counter.sql' ],

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
			[ 'doRevisionPageRevIndexNonUnique' ],
			[ 'doNonUniquePlTlIl' ],
			[ 'addField', 'change_tag', 'ct_id', 'patch-change_tag-ct_id.sql' ],
			[ 'addField', 'tag_summary', 'ts_id', 'patch-tag_summary-ts_id.sql' ],
			[ 'modifyField', 'recentchanges', 'rc_ip', 'patch-rc_ip_modify.sql' ],
			[ 'addIndex', 'archive', 'usertext_timestamp', 'patch-rename-ar_usertext_timestamp.sql' ],

			// 1.29
			[ 'addField', 'externallinks', 'el_index_60', 'patch-externallinks-el_index_60.sql' ],
			[ 'dropIndex', 'user_groups', 'ug_user_group', 'patch-user_groups-primary-key.sql' ],
			[ 'addField', 'user_groups', 'ug_expiry', 'patch-user_groups-ug_expiry.sql' ],
			[ 'addIndex', 'image', 'img_user_timestamp', 'patch-image-user-index-2.sql' ],
		];
	}

	/**
	 * 1.4 betas were missing the 'binary' marker from logging.log_title,
	 * which causes a collation mismatch error on joins in MySQL 4.1.
	 *
	 * @param string $table Table name
	 * @param string $field Field name to check
	 * @param string $patchFile Path to the patch to correct the field
	 * @return bool
	 */
	protected function checkBin( $table, $field, $patchFile ) {
		if ( !$this->doTable( $table ) ) {
			return true;
		}

		/** @var MySQLField $fieldInfo */
		$fieldInfo = $this->db->fieldInfo( $table, $field );
		if ( $fieldInfo->isBinary() ) {
			$this->output( "...$table table has correct $field encoding.\n" );
		} else {
			$this->applyPatch( $patchFile, false, "Fixing $field encoding on $table table" );
		}
	}

	/**
	 * Check whether an index contain a field
	 *
	 * @param string $table Table name
	 * @param string $index Index name to check
	 * @param string $field Field that should be in the index
	 * @return bool
	 */
	protected function indexHasField( $table, $index, $field ) {
		if ( !$this->doTable( $table ) ) {
			return true;
		}

		$info = $this->db->indexInfo( $table, $index, __METHOD__ );
		if ( $info ) {
			foreach ( $info as $row ) {
				if ( $row->Column_name == $field ) {
					$this->output( "...index $index on table $table includes field $field.\n" );

					return true;
				}
			}
		}
		$this->output( "...index $index on table $table has no field $field; added.\n" );

		return false;
	}

	/**
	 * Check that interwiki table exists; if it doesn't source it
	 */
	protected function doInterwikiUpdate() {
		global $IP;

		if ( !$this->doTable( 'interwiki' ) ) {
			return true;
		}

		if ( $this->db->tableExists( "interwiki", __METHOD__ ) ) {
			$this->output( "...already have interwiki table\n" );

			return;
		}

		$this->applyPatch( 'patch-interwiki.sql', false, 'Creating interwiki table' );
		$this->applyPatch(
			"$IP/maintenance/interwiki.sql",
			true,
			'Adding default interwiki definitions'
		);
	}

	/**
	 * Check that proper indexes are in place
	 */
	protected function doIndexUpdate() {
		$meta = $this->db->fieldInfo( 'recentchanges', 'rc_timestamp' );
		if ( $meta === false ) {
			throw new MWException( 'Missing rc_timestamp field of recentchanges table. Should not happen.' );
		}
		if ( $meta->isMultipleKey() ) {
			$this->output( "...indexes seem up to 20031107 standards.\n" );

			return;
		}

		$this->applyPatch( 'patch-indexes.sql', true, "Updating indexes to 20031107" );
	}

	protected function doOldLinksUpdate() {
		$cl = $this->maintenance->runChild( 'ConvertLinks' );
		$cl->execute();
	}

	protected function doFixAncientImagelinks() {
		$info = $this->db->fieldInfo( 'imagelinks', 'il_from' );
		if ( !$info || $info->type() !== 'string' ) {
			$this->output( "...il_from OK\n" );

			return;
		}

		$applied = $this->applyPatch(
			'patch-fix-il_from.sql',
			false,
			'Fixing ancient broken imagelinks table.'
		);

		if ( $applied ) {
			$this->output( "NOTE: you will have to run maintenance/refreshLinks.php after this." );
		}
	}

	/**
	 * Check if we need to add talk page rows to the watchlist
	 */
	function doWatchlistUpdate() {
		$talk = $this->db->selectField( 'watchlist', 'count(*)', 'wl_namespace & 1', __METHOD__ );
		$nontalk = $this->db->selectField(
			'watchlist',
			'count(*)',
			'NOT (wl_namespace & 1)',
			__METHOD__
		);
		if ( $talk == $nontalk ) {
			$this->output( "...watchlist talk page rows already present.\n" );

			return;
		}

		$this->output( "Adding missing watchlist talk page rows... " );
		$this->db->insertSelect( 'watchlist', 'watchlist',
			[
				'wl_user' => 'wl_user',
				'wl_namespace' => 'wl_namespace | 1',
				'wl_title' => 'wl_title',
				'wl_notificationtimestamp' => 'wl_notificationtimestamp'
			], [ 'NOT (wl_namespace & 1)' ], __METHOD__, 'IGNORE' );
		$this->output( "done.\n" );

		$this->output( "Adding missing watchlist subject page rows... " );
		$this->db->insertSelect( 'watchlist', 'watchlist',
			[
				'wl_user' => 'wl_user',
				'wl_namespace' => 'wl_namespace & ~1',
				'wl_title' => 'wl_title',
				'wl_notificationtimestamp' => 'wl_notificationtimestamp'
			], [ 'wl_namespace & 1' ], __METHOD__, 'IGNORE' );
		$this->output( "done.\n" );
	}

	function doSchemaRestructuring() {
		if ( $this->db->tableExists( 'page', __METHOD__ ) ) {
			$this->output( "...page table already exists.\n" );

			return;
		}

		$this->output( "...converting from cur/old to page/revision/text DB structure.\n" );
		$this->output( wfTimestamp( TS_DB ) );
		$this->output( "......checking for duplicate entries.\n" );

		list( $cur, $old, $page, $revision, $text ) = $this->db->tableNamesN(
			'cur',
			'old',
			'page',
			'revision',
			'text'
		);

		$rows = $this->db->query( "
			SELECT cur_title, cur_namespace, COUNT(cur_namespace) AS c
			FROM $cur
			GROUP BY cur_title, cur_namespace
			HAVING c>1",
			__METHOD__
		);

		if ( $rows->numRows() > 0 ) {
			$this->output( wfTimestamp( TS_DB ) );
			$this->output( "......<b>Found duplicate entries</b>\n" );
			$this->output( sprintf( "<b>      %-60s %3s %5s</b>\n", 'Title', 'NS', 'Count' ) );
			$duplicate = [];
			foreach ( $rows as $row ) {
				if ( !isset( $duplicate[$row->cur_namespace] ) ) {
					$duplicate[$row->cur_namespace] = [];
				}

				$duplicate[$row->cur_namespace][] = $row->cur_title;
				$this->output( sprintf(
					"      %-60s %3s %5s\n",
					$row->cur_title, $row->cur_namespace,
					$row->c
				) );
			}
			$sql = "SELECT cur_title, cur_namespace, cur_id, cur_timestamp FROM $cur WHERE ";
			$firstCond = true;
			foreach ( $duplicate as $ns => $titles ) {
				if ( $firstCond ) {
					$firstCond = false;
				} else {
					$sql .= ' OR ';
				}
				$sql .= "( cur_namespace = {$ns} AND cur_title in (";
				$first = true;
				foreach ( $titles as $t ) {
					if ( $first ) {
						$sql .= $this->db->addQuotes( $t );
						$first = false;
					} else {
						$sql .= ', ' . $this->db->addQuotes( $t );
					}
				}
				$sql .= ") ) \n";
			}
			# By sorting descending, the most recent entry will be the first in the list.
			# All following entries will be deleted by the next while-loop.
			$sql .= 'ORDER BY cur_namespace, cur_title, cur_timestamp DESC';

			$rows = $this->db->query( $sql, __METHOD__ );

			$prev_title = $prev_namespace = false;
			$deleteId = [];

			foreach ( $rows as $row ) {
				if ( $prev_title == $row->cur_title && $prev_namespace == $row->cur_namespace ) {
					$deleteId[] = $row->cur_id;
				}
				$prev_title = $row->cur_title;
				$prev_namespace = $row->cur_namespace;
			}
			$sql = "DELETE FROM $cur WHERE cur_id IN ( " . implode( ',', $deleteId ) . ')';
			$this->db->query( $sql, __METHOD__ );
			$this->output( wfTimestamp( TS_DB ) );
			$this->output( "......<b>Deleted</b> " . $this->db->affectedRows() . " records.\n" );
		}

		$this->output( wfTimestamp( TS_DB ) );
		$this->output( "......Creating tables.\n" );
		$this->db->query( "CREATE TABLE $page (
			page_id int(8) unsigned NOT NULL auto_increment,
			page_namespace int NOT NULL,
			page_title varchar(255) binary NOT NULL,
			page_restrictions tinyblob NOT NULL,
			page_is_redirect tinyint(1) unsigned NOT NULL default '0',
			page_is_new tinyint(1) unsigned NOT NULL default '0',
			page_random real unsigned NOT NULL,
			page_touched char(14) binary NOT NULL default '',
			page_latest int(8) unsigned NOT NULL,
			page_len int(8) unsigned NOT NULL,

			PRIMARY KEY page_id (page_id),
			UNIQUE INDEX name_title (page_namespace,page_title),
			INDEX (page_random),
			INDEX (page_len)
			) ENGINE=InnoDB", __METHOD__ );
		$this->db->query( "CREATE TABLE $revision (
			rev_id int(8) unsigned NOT NULL auto_increment,
			rev_page int(8) unsigned NOT NULL,
			rev_comment tinyblob NOT NULL,
			rev_user int(5) unsigned NOT NULL default '0',
			rev_user_text varchar(255) binary NOT NULL default '',
			rev_timestamp char(14) binary NOT NULL default '',
			rev_minor_edit tinyint(1) unsigned NOT NULL default '0',
			rev_deleted tinyint(1) unsigned NOT NULL default '0',
			rev_len int(8) unsigned,
			rev_parent_id int(8) unsigned default NULL,
			PRIMARY KEY rev_page_id (rev_page, rev_id),
			UNIQUE INDEX rev_id (rev_id),
			INDEX rev_timestamp (rev_timestamp),
			INDEX page_timestamp (rev_page,rev_timestamp),
			INDEX user_timestamp (rev_user,rev_timestamp),
			INDEX usertext_timestamp (rev_user_text,rev_timestamp)
			) ENGINE=InnoDB", __METHOD__ );

		$this->output( wfTimestamp( TS_DB ) );
		$this->output( "......Locking tables.\n" );
		$this->db->query(
			"LOCK TABLES $page WRITE, $revision WRITE, $old WRITE, $cur WRITE",
			__METHOD__
		);

		$maxold = intval( $this->db->selectField( 'old', 'max(old_id)', '', __METHOD__ ) );
		$this->output( wfTimestamp( TS_DB ) );
		$this->output( "......maxold is {$maxold}\n" );

		$this->output( wfTimestamp( TS_DB ) );
		global $wgLegacySchemaConversion;
		if ( $wgLegacySchemaConversion ) {
			// Create HistoryBlobCurStub entries.
			// Text will be pulled from the leftover 'cur' table at runtime.
			$this->output( "......Moving metadata from cur; using blob references to text in cur table.\n" );
			$cur_text = "concat('O:18:\"historyblobcurstub\":1:{s:6:\"mCurId\";i:',cur_id,';}')";
			$cur_flags = "'object'";
		} else {
			// Copy all cur text in immediately: this may take longer but avoids
			// having to keep an extra table around.
			$this->output( "......Moving text from cur.\n" );
			$cur_text = 'cur_text';
			$cur_flags = "''";
		}
		$this->db->query(
			"INSERT INTO $old (old_namespace, old_title, old_text, old_comment, old_user,
				old_user_text, old_timestamp, old_minor_edit, old_flags)
			SELECT cur_namespace, cur_title, $cur_text, cur_comment, cur_user, cur_user_text,
				cur_timestamp, cur_minor_edit, $cur_flags
			FROM $cur",
			__METHOD__
		);

		$this->output( wfTimestamp( TS_DB ) );
		$this->output( "......Setting up revision table.\n" );
		$this->db->query(
			"INSERT INTO $revision (rev_id, rev_page, rev_comment, rev_user,
				rev_user_text, rev_timestamp, rev_minor_edit)
			SELECT old_id, cur_id, old_comment, old_user, old_user_text,
				old_timestamp, old_minor_edit
			FROM $old,$cur WHERE old_namespace=cur_namespace AND old_title=cur_title",
			__METHOD__
		);

		$this->output( wfTimestamp( TS_DB ) );
		$this->output( "......Setting up page table.\n" );
		$this->db->query(
			"INSERT INTO $page (page_id, page_namespace, page_title,
				page_restrictions, page_is_redirect, page_is_new, page_random,
				page_touched, page_latest, page_len)
			SELECT cur_id, cur_namespace, cur_title, cur_restrictions,
				cur_is_redirect, cur_is_new, cur_random, cur_touched, rev_id, LENGTH(cur_text)
			FROM $cur,$revision
			WHERE cur_id=rev_page AND rev_timestamp=cur_timestamp AND rev_id > {$maxold}",
			__METHOD__
		);

		$this->output( wfTimestamp( TS_DB ) );
		$this->output( "......Unlocking tables.\n" );
		$this->db->query( "UNLOCK TABLES", __METHOD__ );

		$this->output( wfTimestamp( TS_DB ) );
		$this->output( "......Renaming old.\n" );
		$this->db->query( "ALTER TABLE $old RENAME TO $text", __METHOD__ );

		$this->output( wfTimestamp( TS_DB ) );
		$this->output( "...done.\n" );
	}

	protected function doNamespaceSize() {
		$tables = [
			'page' => 'page',
			'archive' => 'ar',
			'recentchanges' => 'rc',
			'watchlist' => 'wl',
			'querycache' => 'qc',
			'logging' => 'log',
		];
		foreach ( $tables as $table => $prefix ) {
			$field = $prefix . '_namespace';

			$tablename = $this->db->tableName( $table );
			$result = $this->db->query( "SHOW COLUMNS FROM $tablename LIKE '$field'", __METHOD__ );
			$info = $this->db->fetchObject( $result );

			if ( substr( $info->Type, 0, 3 ) == 'int' ) {
				$this->output( "...$field is already a full int ($info->Type).\n" );
			} else {
				$this->output( "Promoting $field from $info->Type to int... " );
				$this->db->query( "ALTER TABLE $tablename MODIFY $field int NOT NULL", __METHOD__ );
				$this->output( "done.\n" );
			}
		}
	}

	protected function doPagelinksUpdate() {
		if ( $this->db->tableExists( 'pagelinks', __METHOD__ ) ) {
			$this->output( "...already have pagelinks table.\n" );

			return;
		}

		$this->applyPatch(
			'patch-pagelinks.sql',
			false,
			'Converting links and brokenlinks tables to pagelinks'
		);

		global $wgContLang;
		foreach ( $wgContLang->getNamespaces() as $ns => $name ) {
			if ( $ns == 0 ) {
				continue;
			}

			$this->output( "Cleaning up broken links for namespace $ns... " );
			$this->db->update( 'pagelinks',
				[
					'pl_namespace' => $ns,
					"pl_title = TRIM(LEADING {$this->db->addQuotes( "$name:" )} FROM pl_title)",
				],
				[
					'pl_namespace' => 0,
					'pl_title' . $this->db->buildLike( "$name:", $this->db->anyString() ),
				],
				__METHOD__
			);
			$this->output( "done.\n" );
		}
	}

	protected function doUserUniqueUpdate() {
		if ( !$this->doTable( 'user' ) ) {
			return true;
		}

		$duper = new UserDupes( $this->db, [ $this, 'output' ] );
		if ( $duper->hasUniqueIndex() ) {
			$this->output( "...already have unique user_name index.\n" );

			return;
		}

		if ( !$duper->clearDupes() ) {
			$this->output( "WARNING: This next step will probably fail due to unfixed duplicates...\n" );
		}
		$this->applyPatch( 'patch-user_nameindex.sql', false, "Adding unique index on user_name" );
	}

	protected function doUserGroupsUpdate() {
		if ( !$this->doTable( 'user_groups' ) ) {
			return true;
		}

		if ( $this->db->tableExists( 'user_groups', __METHOD__ ) ) {
			$info = $this->db->fieldInfo( 'user_groups', 'ug_group' );
			if ( $info->type() == 'int' ) {
				$oldug = $this->db->tableName( 'user_groups' );
				$newug = $this->db->tableName( 'user_groups_bogus' );
				$this->output( "user_groups table exists but is in bogus intermediate " .
					"format. Renaming to $newug... " );
				$this->db->query( "ALTER TABLE $oldug RENAME TO $newug", __METHOD__ );
				$this->output( "done.\n" );

				$this->applyPatch( 'patch-user_groups.sql', false, "Re-adding fresh user_groups table" );

				$this->output( "***\n" );
				$this->output( "*** WARNING: You will need to manually fix up user " .
					"permissions in the user_groups\n" );
				$this->output( "*** table. Old 1.5 alpha versions did some pretty funky stuff...\n" );
				$this->output( "***\n" );
			} else {
				$this->output( "...user_groups table exists and is in current format.\n" );
			}

			return;
		}

		$this->applyPatch( 'patch-user_groups.sql', false, "Adding user_groups table" );

		if ( !$this->db->tableExists( 'user_rights', __METHOD__ ) ) {
			if ( $this->db->fieldExists( 'user', 'user_rights', __METHOD__ ) ) {
				$this->applyPatch(
					'patch-user_rights.sql',
					false,
					'Upgrading from a 1.3 or older database? Breaking out user_rights for conversion'
				);
			} else {
				$this->output( "*** WARNING: couldn't locate user_rights table or field for upgrade.\n" );
				$this->output( "*** You may need to manually configure some sysops by manipulating\n" );
				$this->output( "*** the user_groups table.\n" );

				return;
			}
		}

		$this->output( "Converting user_rights table to user_groups... " );
		$result = $this->db->select( 'user_rights',
			[ 'ur_user', 'ur_rights' ],
			[ "ur_rights != ''" ],
			__METHOD__ );

		foreach ( $result as $row ) {
			$groups = array_unique(
				array_map( 'trim',
					explode( ',', $row->ur_rights ) ) );

			foreach ( $groups as $group ) {
				$this->db->insert( 'user_groups',
					[
						'ug_user' => $row->ur_user,
						'ug_group' => $group ],
					__METHOD__ );
			}
		}
		$this->output( "done.\n" );
	}

	/**
	 * Make sure wl_notificationtimestamp can be NULL,
	 * and update old broken items.
	 */
	protected function doWatchlistNull() {
		$info = $this->db->fieldInfo( 'watchlist', 'wl_notificationtimestamp' );
		if ( !$info ) {
			return;
		}
		if ( $info->isNullable() ) {
			$this->output( "...wl_notificationtimestamp is already nullable.\n" );

			return;
		}

		$this->applyPatch(
			'patch-watchlist-null.sql',
			false,
			'Making wl_notificationtimestamp nullable'
		);
	}

	/**
	 * Set page_random field to a random value where it is equals to 0.
	 *
	 * @see T5946
	 */
	protected function doPageRandomUpdate() {
		$page = $this->db->tableName( 'page' );
		$this->db->query( "UPDATE $page SET page_random = RAND() WHERE page_random = 0", __METHOD__ );
		$rows = $this->db->affectedRows();

		if ( $rows ) {
			$this->output( "Set page_random to a random value on $rows rows where it was set to 0\n" );
		} else {
			$this->output( "...no page_random rows needed to be set\n" );
		}
	}

	protected function doTemplatelinksUpdate() {
		if ( $this->db->tableExists( 'templatelinks', __METHOD__ ) ) {
			$this->output( "...templatelinks table already exists\n" );

			return;
		}

		$this->applyPatch( 'patch-templatelinks.sql', false, "Creating templatelinks table" );

		$this->output( "Populating...\n" );
		if ( wfGetLB()->getServerCount() > 1 ) {
			// Slow, replication-friendly update
			$res = $this->db->select( 'pagelinks', [ 'pl_from', 'pl_namespace', 'pl_title' ],
				[ 'pl_namespace' => NS_TEMPLATE ], __METHOD__ );
			$count = 0;
			foreach ( $res as $row ) {
				$count = ( $count + 1 ) % 100;
				if ( $count == 0 ) {
					$lbFactory = MediaWikiServices::getInstance()->getDBLoadBalancerFactory();
					$lbFactory->waitForReplication( [ 'wiki' => wfWikiID() ] );
				}
				$this->db->insert( 'templatelinks',
					[
						'tl_from' => $row->pl_from,
						'tl_namespace' => $row->pl_namespace,
						'tl_title' => $row->pl_title,
					], __METHOD__
				);
			}
		} else {
			// Fast update
			$this->db->insertSelect( 'templatelinks', 'pagelinks',
				[
					'tl_from' => 'pl_from',
					'tl_namespace' => 'pl_namespace',
					'tl_title' => 'pl_title'
				], [
					'pl_namespace' => 10
				], __METHOD__
			);
		}
		$this->output( "Done. Please run maintenance/refreshLinks.php for a more " .
			"thorough templatelinks update.\n" );
	}

	protected function doBacklinkingIndicesUpdate() {
		if ( !$this->indexHasField( 'pagelinks', 'pl_namespace', 'pl_from' ) ||
			!$this->indexHasField( 'templatelinks', 'tl_namespace', 'tl_from' ) ||
			!$this->indexHasField( 'imagelinks', 'il_to', 'il_from' )
		) {
			$this->applyPatch( 'patch-backlinkindexes.sql', false, "Updating backlinking indices" );
		}
	}

	/**
	 * Adding page_restrictions table, obsoleting page.page_restrictions.
	 * Migrating old restrictions to new table
	 * -- Andrew Garrett, January 2007.
	 */
	protected function doRestrictionsUpdate() {
		if ( $this->db->tableExists( 'page_restrictions', __METHOD__ ) ) {
			$this->output( "...page_restrictions table already exists.\n" );

			return;
		}

		$this->applyPatch(
			'patch-page_restrictions.sql',
			false,
			'Creating page_restrictions table (1/2)'
		);
		$this->applyPatch(
			'patch-page_restrictions_sortkey.sql',
			false,
			'Creating page_restrictions table (2/2)'
		);
		$this->output( "done.\n" );

		$this->output( "Migrating old restrictions to new table...\n" );
		$task = $this->maintenance->runChild( 'UpdateRestrictions' );
		$task->execute();
	}

	protected function doCategorylinksIndicesUpdate() {
		if ( !$this->indexHasField( 'categorylinks', 'cl_sortkey', 'cl_from' ) ) {
			$this->applyPatch( 'patch-categorylinksindex.sql', false, "Updating categorylinks Indices" );
		}
	}

	protected function doCategoryPopulation() {
		if ( $this->updateRowExists( 'populate category' ) ) {
			$this->output( "...category table already populated.\n" );

			return;
		}

		$this->output(
			"Populating category table, printing progress markers. " .
			"For large databases, you\n" .
			"may want to hit Ctrl-C and do this manually with maintenance/\n" .
			"populateCategory.php.\n"
		);
		$task = $this->maintenance->runChild( 'PopulateCategory' );
		$task->execute();
		$this->output( "Done populating category table.\n" );
	}

	protected function doPopulateParentId() {
		if ( !$this->updateRowExists( 'populate rev_parent_id' ) ) {
			$this->output(
				"Populating rev_parent_id fields, printing progress markers. For large\n" .
				"databases, you may want to hit Ctrl-C and do this manually with\n" .
				"maintenance/populateParentId.php.\n" );

			$task = $this->maintenance->runChild( 'PopulateParentId' );
			$task->execute();
		}
	}

	protected function doMaybeProfilingMemoryUpdate() {
		if ( !$this->doTable( 'profiling' ) ) {
			return true;
		}

		if ( !$this->db->tableExists( 'profiling', __METHOD__ ) ) {
			return true;
		} elseif ( $this->db->fieldExists( 'profiling', 'pf_memory', __METHOD__ ) ) {
			$this->output( "...profiling table has pf_memory field.\n" );

			return true;
		}

		return $this->applyPatch(
			'patch-profiling-memory.sql',
			false,
			'Adding pf_memory field to table profiling'
		);
	}

	protected function doFilearchiveIndicesUpdate() {
		$info = $this->db->indexInfo( 'filearchive', 'fa_user_timestamp', __METHOD__ );
		if ( !$info ) {
			$this->applyPatch( 'patch-filearchive-user-index.sql', false, "Updating filearchive indices" );
		}

		return true;
	}

	protected function doNonUniquePlTlIl() {
		$info = $this->db->indexInfo( 'pagelinks', 'pl_namespace' );
		if ( is_array( $info ) && $info[0]->Non_unique ) {
			$this->output( "...pl_namespace, tl_namespace, il_to indices are already non-UNIQUE.\n" );

			return true;
		}
		if ( $this->skipSchema ) {
			$this->output( "...skipping schema change (making pl_namespace, tl_namespace " .
				"and il_to indices non-UNIQUE).\n" );

			return false;
		}

		return $this->applyPatch(
			'patch-pl-tl-il-nonunique.sql',
			false,
			'Making pl_namespace, tl_namespace and il_to indices non-UNIQUE'
		);
	}

	protected function doUpdateMimeMinorField() {
		if ( $this->updateRowExists( 'mime_minor_length' ) ) {
			$this->output( "...*_mime_minor fields are already long enough.\n" );

			return;
		}

		$this->applyPatch(
			'patch-mime_minor_length.sql',
			false,
			'Altering all *_mime_minor fields to 100 bytes in size'
		);
	}

	protected function doClFieldsUpdate() {
		if ( $this->updateRowExists( 'cl_fields_update' ) ) {
			$this->output( "...categorylinks up-to-date.\n" );

			return;
		}

		$this->applyPatch(
			'patch-categorylinks-better-collation2.sql',
			false,
			'Updating categorylinks (again)'
		);
	}

	protected function doLangLinksLengthUpdate() {
		$langlinks = $this->db->tableName( 'langlinks' );
		$res = $this->db->query( "SHOW COLUMNS FROM $langlinks LIKE 'll_lang'" );
		$row = $this->db->fetchObject( $res );

		if ( $row && $row->Type == "varbinary(10)" ) {
			$this->applyPatch(
				'patch-langlinks-ll_lang-20.sql',
				false,
				'Updating length of ll_lang in langlinks'
			);
		} else {
			$this->output( "...ll_lang is up-to-date.\n" );
		}
	}

	protected function doUserNewTalkTimestampNotNull() {
		if ( !$this->doTable( 'user_newtalk' ) ) {
			return true;
		}

		$info = $this->db->fieldInfo( 'user_newtalk', 'user_last_timestamp' );
		if ( $info === false ) {
			return;
		}
		if ( $info->isNullable() ) {
			$this->output( "...user_last_timestamp is already nullable.\n" );

			return;
		}

		$this->applyPatch(
			'patch-user-newtalk-timestamp-null.sql',
			false,
			'Making user_last_timestamp nullable'
		);
	}

	protected function doIwlinksIndexNonUnique() {
		$info = $this->db->indexInfo( 'iwlinks', 'iwl_prefix_title_from' );
		if ( is_array( $info ) && $info[0]->Non_unique ) {
			$this->output( "...iwl_prefix_title_from index is already non-UNIQUE.\n" );

			return true;
		}
		if ( $this->skipSchema ) {
			$this->output( "...skipping schema change (making iwl_prefix_title_from index non-UNIQUE).\n" );

			return false;
		}

		return $this->applyPatch(
			'patch-iwl_prefix_title_from-non-unique.sql',
			false,
			'Making iwl_prefix_title_from index non-UNIQUE'
		);
	}

	protected function doUserNewTalkUseridUnsigned() {
		if ( !$this->doTable( 'user_newtalk' ) ) {
			return true;
		}

		$info = $this->db->fieldInfo( 'user_newtalk', 'user_id' );
		if ( $info === false ) {
			return true;
		}
		if ( $info->isUnsigned() ) {
			$this->output( "...user_id is already unsigned int.\n" );

			return true;
		}

		return $this->applyPatch(
			'patch-user-newtalk-userid-unsigned.sql',
			false,
			'Making user_id unsigned int'
		);
	}

	protected function doRevisionPageRevIndexNonUnique() {
		if ( !$this->doTable( 'revision' ) ) {
			return true;
		} elseif ( !$this->db->indexExists( 'revision', 'rev_page_id' ) ) {
			$this->output( "...rev_page_id index not found on revision.\n" );
			return true;
		}

		if ( !$this->db->indexUnique( 'revision', 'rev_page_id' ) ) {
			$this->output( "...rev_page_id index already non-unique.\n" );
			return true;
		}

		return $this->applyPatch(
			'patch-revision-page-rev-index-nonunique.sql',
			false,
			'Making rev_page_id index non-unique'
		);
	}

	public function getSchemaVars() {
		global $wgDBTableOptions;

		$vars = [];
		$vars['wgDBTableOptions'] = str_replace( 'TYPE', 'ENGINE', $wgDBTableOptions );
		$vars['wgDBTableOptions'] = str_replace(
			'CHARSET=mysql4',
			'CHARSET=binary',
			$vars['wgDBTableOptions']
		);

		return $vars;
	}
}
