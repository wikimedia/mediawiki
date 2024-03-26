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
 * @ingroup Installer
 */
use Wikimedia\Rdbms\MySQLField;

/**
 * Mysql update list and mysql-specific update functions.
 *
 * @ingroup Installer
 * @since 1.17
 * @property Wikimedia\Rdbms\DatabaseMysqlBase $db
 */
class MysqlUpdater extends DatabaseUpdater {
	protected function getCoreUpdateList() {
		return [
			// 1.35 but it must come first
			[ 'addField', 'revision', 'rev_actor', 'patch-revision-rev_actor.sql' ],

			// 1.31
			[ 'addField', 'image', 'img_description_id', 'patch-image-img_description_id.sql' ],
			[ 'migrateComments' ],

			[ 'addTable', 'slots', 'patch-slots.sql' ],
			[ 'addField', 'slots', 'slot_origin', 'patch-slot-origin.sql' ],
			[ 'addTable', 'content', 'patch-content.sql' ],
			[ 'addTable', 'slot_roles', 'patch-slot_roles.sql' ],
			[ 'addTable', 'content_models', 'patch-content_models.sql' ],
			[ 'migrateArchiveText' ],
			[ 'addTable', 'actor', 'patch-actor-table.sql' ],
			[ 'addField', 'archive', 'ar_actor', 'patch-archive-ar_actor.sql' ],
			[ 'addField', 'ipblocks', 'ipb_by_actor', 'patch-ipblocks-ipb_by_actor.sql' ],
			[ 'addField', 'image', 'img_actor', 'patch-image-img_actor.sql' ],
			[ 'addField', 'oldimage', 'oi_actor', 'patch-oldimage-oi_actor.sql' ],
			[ 'addField', 'filearchive', 'fa_actor', 'patch-filearchive-fa_actor.sql' ],
			[ 'addField', 'recentchanges', 'rc_actor', 'patch-recentchanges-rc_actor.sql' ],
			[ 'addField', 'logging', 'log_actor', 'patch-logging-log_actor.sql' ],
			[ 'migrateActors' ],

			// Adds a default value to the rev_text_id field to allow Multi Content
			// Revisions migration to happen where rows will have to be added to the
			// revision table with no rev_text_id.
			[ 'setDefault', 'revision', 'rev_text_id', 0 ],
			[ 'modifyTable', 'site_stats', 'patch-site_stats-modify.sql' ],
			[ 'populateArchiveRevId' ],
			[ 'addIndex', 'recentchanges', 'rc_namespace_title_timestamp',
				'patch-recentchanges-nttindex.sql' ],

			// 1.32
			[ 'addTable', 'change_tag_def', 'patch-change_tag_def.sql' ],
			[ 'populateExternallinksIndex60' ],
			[ 'dropDefault', 'externallinks', 'el_index_60' ],
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
			[ 'dropIndex', 'change_tag', 'change_tag_rc_tag', 'patch-change_tag-change_tag_rc_tag_id.sql' ],
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
			[ 'modifyTable', 'job', 'patch-job-params-mediumblob.sql' ],

			// 1.34
			[ 'dropIndex', 'archive', 'ar_usertext_timestamp',
				'patch-drop-archive-ar_usertext_timestamp.sql' ],
			[ 'dropIndex', 'archive', 'usertext_timestamp', 'patch-drop-archive-usertext_timestamp.sql' ],
			[ 'dropField', 'archive', 'ar_user', 'patch-drop-archive-user-fields.sql' ],
			[ 'dropField', 'ipblocks', 'ip_by', 'patch-drop-ipblocks-user-fields.sql' ],
			[ 'dropIndex', 'image', 'img_user_timestamp', 'patch-drop-image-img_user_timestamp.sql' ],
			[ 'dropField', 'image', 'img_user', 'patch-drop-image-user-fields.sql' ],
			[ 'dropField', 'oldimage', 'oi_user', 'patch-drop-oldimage-user-fields.sql' ],
			[ 'dropField', 'filearchive', 'fa_user', 'patch-drop-filearchive-user-fields.sql' ],
			[ 'dropField', 'recentchanges', 'rc_user', 'patch-drop-recentchanges-user-fields.sql' ],
			[ 'dropField', 'logging', 'log_user', 'patch-drop-logging-user-fields.sql' ],
			[ 'addIndex', 'user_newtalk', 'un_user_ip', 'patch-rename-mysql-user_newtalk-indexes.sql' ],

			// 1.35
			[ 'addTable', 'watchlist_expiry', 'patch-watchlist_expiry.sql' ],
			[ 'modifyField', 'page', 'page_restrictions', 'patch-page_restrictions-null.sql' ],
			[ 'renameIndex', 'ipblocks', 'ipb_address', 'ipb_address_unique', false,
				'patch-ipblocks-rename-ipb_address.sql' ],
			[ 'dropField', 'revision', 'rev_user', 'patch-revision-actor-comment-MCR.sql' ],
			[ 'dropField', 'archive', 'ar_text_id', 'patch-archive-MCR.sql' ],
			[ 'doLanguageLinksLengthSync' ],
			[ 'doFixIpbAddressUniqueIndex' ],
			[ 'modifyField', 'actor', 'actor_name', 'patch-actor-actor_name-varbinary.sql' ],
			[ 'modifyField', 'sites', 'site_global_key', 'patch-sites-site_global_key.sql' ],
			[ 'modifyField', 'iwlinks', 'iwl_prefix', 'patch-extend-iwlinks-iwl_prefix.sql' ],

			// 1.36
			[ 'modifyField', 'redirect', 'rd_title', 'patch-redirect-rd_title-varbinary.sql' ],
			[ 'modifyField', 'pagelinks', 'pl_title', 'patch-pagelinks-pl_title-varbinary.sql' ],
			[ 'modifyField', 'templatelinks', 'tl_title', 'patch-templatelinks-tl_title-varbinary.sql' ],
			[ 'modifyField', 'imagelinks', 'il_to', 'patch-imagelinks-il_to-varbinary.sql' ],
			[ 'modifyField', 'langlinks', 'll_title', 'patch-langlinks-ll_title-varbinary.sql' ],
			[ 'modifyField', 'iwlinks', 'iwl_title', 'patch-iwlinks-iwl_title-varbinary.sql' ],
			[ 'modifyField', 'category', 'cat_title', 'patch-category-cat_title-varbinary.sql' ],
			[ 'modifyField', 'querycache', 'qc_title', 'patch-querycache-qc_title-varbinary.sql' ],
			[ 'modifyField', 'querycachetwo', 'qcc_title', 'patch-querycachetwo-qcc_title-varbinary.sql' ],
			[ 'modifyField', 'watchlist', 'wl_title', 'patch-watchlist-wl_title-varbinary.sql' ],
			[ 'modifyField', 'user_newtalk', 'user_last_timestamp',
				'patch-user_newtalk-user_last_timestamp-binary.sql'
			],
			[ 'modifyField', 'protected_titles', 'pt_title', 'patch-protected_titles-pt_title-varbinary.sql' ],
			[ 'dropDefault', 'protected_titles', 'pt_expiry' ],
			[ 'dropDefault', 'ip_changes', 'ipc_rev_timestamp' ],
			[ 'modifyField', 'ipblocks_restrictions', 'ir_type', 'patch-ipblocks_restrictions-ir_type.sql' ],
			[ 'renameIndex', 'watchlist', 'namespace_title', 'wl_namespace_title', false,
				'patch-watchlist-namespace_title-rename-index.sql' ],
			[ 'modifyField', 'job', 'job_title', 'patch-job-job_title-varbinary.sql' ],
			[ 'modifyField', 'job', 'job_timestamp', 'patch-job_job_timestamp.sql' ],
			[ 'modifyField', 'job', 'job_token_timestamp', 'patch-job_job_token_timestamp.sql' ],
			[ 'modifyField', 'watchlist', 'wl_notificationtimestamp', 'patch-watchlist-wl_notificationtimestamp.sql' ],
			[ 'modifyField', 'slot_roles', 'role_id', 'patch-slot_roles-role_id.sql' ],
			[ 'modifyField', 'content_models', 'model_id', 'patch-content_models-model_id.sql' ],
			[ 'modifyField', 'categorylinks', 'cl_to', 'patch-categorylinks-cl_to-varbinary.sql' ],
			[ 'modifyField', 'logging', 'log_title', 'patch-logging-log_title-varbinary.sql' ],
			[ 'modifyField', 'uploadstash', 'us_timestamp', 'patch-uploadstash-us_timestamp.sql' ],
			[ 'renameIndex', 'user_properties', 'user_properties_property', 'up_property', false,
				'patch-user_properties-rename-index.sql' ],
			[ 'renameIndex', 'sites', 'sites_global_key', 'site_global_key', false, 'patch-sites-rename-indexes.sql' ],
			[ 'renameIndex', 'logging', 'type_time', 'log_type_time', false, 'patch-logging-rename-indexes.sql' ],
			[ 'modifyField', 'filearchive', 'fa_name', 'patch-filearchive-fa_name.sql' ],
			[ 'dropDefault', 'filearchive', 'fa_deleted_timestamp' ],
			[ 'dropDefault', 'filearchive', 'fa_timestamp' ],
			[ 'modifyField', 'oldimage', 'oi_name', 'patch-oldimage-oi_name-varbinary.sql' ],
			[ 'dropDefault', 'oldimage', 'oi_timestamp' ],
			[ 'modifyField', 'objectcache', 'exptime', 'patch-objectcache-exptime-notnull.sql' ],
			[ 'dropDefault', 'ipblocks', 'ipb_timestamp' ],
			[ 'dropDefault', 'ipblocks', 'ipb_expiry' ],
			[ 'renameIndex', 'archive', 'name_title_timestamp', 'ar_name_title_timestamp', false,
				'patch-archive-rename-name_title_timestamp-index.sql' ],
			[ 'modifyField', 'image', 'img_name', 'patch-image-img_name-varbinary.sql' ],
			[ 'dropDefault', 'image', 'img_timestamp' ],
			[ 'modifyField', 'image', 'img_timestamp', 'patch-image-img_timestamp.sql' ],
			[ 'renameIndex', 'site_identifiers', 'site_ids_key', 'si_key', false,
				'patch-site_identifiers-rename-indexes.sql' ],
			[ 'modifyField', 'recentchanges', 'rc_title', 'patch-recentchanges-rc_title-varbinary.sql' ],
			[ 'dropDefault', 'recentchanges', 'rc_timestamp' ],
			[ 'modifyField', 'recentchanges', 'rc_timestamp', 'patch-recentchanges-rc_timestamp.sql' ],
			[ 'modifyField', 'recentchanges', 'rc_id', 'patch-recentchanges-rc_id.sql' ],
			[ 'renameIndex', 'recentchanges', 'new_name_timestamp', 'rc_new_name_timestamp', false,
				'patch-recentchanges-rc_new_name_timestamp.sql' ],
			[ 'dropDefault', 'archive', 'ar_timestamp' ],
			[ 'modifyField', 'archive', 'ar_title', 'patch-archive-ar_title-varbinary.sql' ],
			[ 'modifyField', 'page', 'page_title', 'patch-page-page_title-varbinary.sql' ],
			[ 'dropDefault', 'page', 'page_touched' ],
			[ 'modifyField', 'user', 'user_name', 'patch-user_table-updates.sql' ],

			// 1.37
			[ 'renameIndex', 'revision', 'page_timestamp', 'rev_page_timestamp', false,
				'patch-revision-rename-index.sql' ],
			[ 'addField', 'objectcache', 'modtoken', 'patch-objectcache-modtoken.sql' ],
			[ 'dropDefault', 'revision', 'rev_timestamp' ],
			[ 'addIndex', 'oldimage', 'oi_timestamp', 'patch-oldimage-oi_timestamp.sql' ],
			[ 'renameIndex', 'page', 'name_title', 'page_name_title', false, 'patch-page-rename-name_title-index.sql' ],
			[ 'renameIndex', 'change_tag', 'change_tag_rc_tag_id', 'ct_rc_tag_id', false,
				'patch-change_tag-rename-indexes.sql' ],

			// 1.38
			[ 'doConvertDjvuMetadata' ],
			[ 'dropField', 'page_restrictions', 'pr_user', 'patch-drop-page_restrictions-pr_user.sql' ],
			[ 'modifyField', 'filearchive', 'fa_id', 'patch-filearchive-fa_id.sql' ],
			[ 'modifyField', 'image', 'img_major_mime', 'patch-image-img_major_mime-default.sql' ],
			[ 'addTable', 'linktarget', 'patch-linktarget.sql' ],
			[ 'dropIndex', 'revision', 'rev_page_id', 'patch-drop-rev_page_id.sql' ],
			[ 'modifyField', 'page_restrictions', 'pr_page', 'patch-page_restrictions-pr_page.sql' ],
			[ 'modifyField', 'page_props', 'pp_page', 'patch-page_props-pp_page.sql' ],
			[ 'modifyField', 'ipblocks_restrictions', 'ir_value', 'patch-ipblocks_restrictions-ir_value.sql' ],
			[ 'addField', 'templatelinks', 'tl_target_id', 'patch-templatelinks-target_id.sql' ],

			// 1.39
			[ 'addTable', 'user_autocreate_serial', 'patch-user_autocreate_serial.sql' ],
			[ 'modifyField', 'ipblocks_restrictions', 'ir_ipb_id', 'patch-ipblocks_restrictions-ir_ipb_id.sql' ],
			[ 'modifyField', 'ipblocks', 'ipb_id', 'patch-ipblocks-ipb_id.sql' ],
			[ 'modifyField', 'user', 'user_editcount', 'patch-user-user_editcount.sql' ],
			[ 'runMaintenance', MigrateRevisionActorTemp::class, 'maintenance/migrateRevisionActorTemp.php' ],
			[ 'dropTable', 'revision_actor_temp' ],
			[ 'runMaintenance', UpdateRestrictions::class, 'maintenance/updateRestrictions.php' ],
			[ 'dropField', 'page', 'page_restrictions', 'patch-page-drop-page_restrictions.sql' ],
			[ 'migrateTemplatelinks' ],
			[ 'modifyField', 'templatelinks', 'tl_namespace', 'patch-templatelinks-tl_title-nullable.sql' ],
			[ 'dropField', 'templatelinks', 'tl_title', 'patch-templatelinks-drop-tl_title.sql' ],
		];
	}

	/**
	 * MW 1.4 betas were missing the 'binary' marker from logging.log_title,
	 * which caused a MySQL collation mismatch error.
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

	protected function doLanguageLinksLengthSync() {
		$sync = [
			[ 'table' => 'l10n_cache', 'field' => 'lc_lang', 'file' => 'patch-l10n_cache-lc_lang-35.sql' ],
			[ 'table' => 'langlinks', 'field' => 'll_lang', 'file' => 'patch-langlinks-ll_lang-35.sql' ],
			[ 'table' => 'sites', 'field' => 'site_language', 'file' => 'patch-sites-site_language-35.sql' ],
		];

		foreach ( $sync as $s ) {
			$table = $this->db->tableName( $s['table'] );
			$field = $s['field'];
			$res = $this->db->query( "SHOW COLUMNS FROM $table LIKE '$field'", __METHOD__ );
			$row = $res->fetchObject();

			if ( $row && $row->Type !== "varbinary(35)" ) {
				$this->applyPatch(
					$s['file'],
					false,
					"Updating length of $field in $table"
				);
			} else {
				$this->output( "...$field is up-to-date.\n" );
			}
		}
	}

	protected function doFixIpbAddressUniqueIndex() {
		if ( !$this->doTable( 'ipblocks' ) ) {
			return;
		}

		if ( !$this->indexHasField( 'ipblocks', 'ipb_address_unique', 'ipb_anon_only' ) ) {
			$this->output( "...ipb_address_unique index up-to-date.\n" );
			return;
		}

		$this->applyPatch(
			'patch-ipblocks-fix-ipb_address_unique.sql',
			false,
			'Removing ipb_anon_only column from ipb_address_unique index'
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

	/**
	 * Drop a default value from a field
	 *
	 * @since 1.36
	 * @param string $table
	 * @param string $field
	 */
	protected function dropDefault( $table, $field ) {
		$updateKey = "$table-$field-dropDefault";

		if ( $this->updateRowExists( $updateKey ) ) {
			return;
		}

		$info = $this->db->fieldInfo( $table, $field );
		if ( $info && $info->defaultValue() !== false ) {
			$this->output( "Removing '$table.$field' default value.\n" );
			$table = $this->db->tableName( $table );
			$ret = $this->db->query( "ALTER TABLE $table ALTER COLUMN $field DROP DEFAULT", __METHOD__ );

			if ( $ret ) {
				$this->insertUpdateRow( $updateKey );
			}
		}
	}

	/**
	 * Set a default value for a field
	 *
	 * @since 1.36
	 * @param string $table
	 * @param string $field
	 * @param mixed $default
	 */
	protected function setDefault( $table, $field, $default ) {
		$info = $this->db->fieldInfo( $table, $field );
		if ( $info && $info->defaultValue() !== $default ) {
			$this->output( "Changing '$table.$field' default value.\n" );
			$table = $this->db->tableName( $table );
			$this->db->query(
				"ALTER TABLE $table ALTER COLUMN $field SET DEFAULT "
				. $this->db->addQuotes( $default ), __METHOD__
			);
		}
	}

}
