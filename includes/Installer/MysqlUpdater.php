<?php

/**
 * MySQL-specific updater.
 *
 * @license GPL-2.0-or-later
 * @file
 * @ingroup Installer
 */

namespace MediaWiki\Installer;

use CleanupWatchlistTemporaryAccountRows;
use FixWrongPasswordPrefixes;
use MediaWiki\Maintenance\FixAutoblockLogTitles;
use PopulateUserIsTemp;

/**
 * Mysql update list and mysql-specific update functions.
 *
 * @ingroup Installer
 * @since 1.17
 * @property \Wikimedia\Rdbms\DatabaseMySQL $db
 */
class MysqlUpdater extends DatabaseUpdater {
	/** @inheritDoc */
	protected function getCoreUpdateList() {
		return [
			// 1.42
			[ 'addField', 'user_autocreate_serial', 'uas_year', 'patch-user_autocreate_serial-uas_year.sql' ],
			[ 'addTable', 'block_target', 'patch-block_target.sql' ],
			[ 'dropIndex', 'categorylinks', 'cl_collation_ext', 'patch-drop-cl_collation_ext.sql' ],
			[ 'runMaintenance', PopulateUserIsTemp::class ],
			[ 'dropIndex', 'sites', 'site_type', 'patch-sites-drop_indexes.sql' ],
			[ 'dropIndex', 'iwlinks', 'iwl_prefix_from_title', 'patch-iwlinks-drop-iwl_prefix_from_title.sql' ],

			// 1.43
			[ 'migratePagelinks' ],
			[ 'modifyField', 'revision', 'rev_id', 'patch-revision-cleanup.sql' ],
			[ 'modifyField', 'recentchanges', 'rc_id', 'patch-recentchanges-rc_id-bigint.sql' ],
			[ 'modifyField', 'change_tag', 'ct_rc_id', 'patch-change_tag-ct_rc_id.sql' ],
			[ 'runMaintenance', \MigrateBlocks::class ],
			[ 'dropTable', 'ipblocks' ],
			[ 'dropField', 'pagelinks', 'pl_title', 'patch-pagelinks-drop-pl_title.sql' ],
			[ 'modifyField', 'page', 'page_links_updated', 'patch-page-page_links_updated-noinfinite.sql' ],
			[ 'addPostDatabaseUpdateMaintenance', FixAutoblockLogTitles::class ],
			[ 'changeTableOption', 'searchindex', 'CONVERT TO CHARACTER SET utf8mb4', 'utf8mb4' ],
			[ 'migrateSearchindex' ],

			// 1.44
			[ 'addTable', 'file', 'patch-file.sql' ],
			[ 'addField', 'categorylinks', 'cl_target_id', 'patch-categorylinks-target_id.sql' ],
			[ 'addTable', 'collation', 'patch-collation.sql' ],
			[ 'dropTable', 'module_deps' ],

			// 1.45
			[ 'addTable', 'existencelinks', 'patch-existencelinks.sql' ],
			[ 'runMaintenance', FixWrongPasswordPrefixes::class ],
			[ 'addIndex', 'categorylinks', 'cl_timestamp_id', 'patch-categorylinks-cl_timestamp_id.sql' ],
			[ 'migrateCategorylinks' ],
			[ 'normalizeCollation' ],
			[ 'modifyPrimaryKey', 'categorylinks', [ 'cl_from', 'cl_target_id' ], 'patch-categorylinks-pk.sql' ],
			[ 'addIndex', 'recentchanges', 'rc_source_name_timestamp',
				'patch-recentchanges-rc_source_name_timestamp.sql' ],
			[ 'addIndex', 'recentchanges', 'rc_name_source_patrolled_timestamp',
				'patch-recentchanges-rc_name_source_patrolled_timestamp.sql' ],
			[ 'dropField', 'recentchanges', 'rc_new', 'patch-recentchanges-drop-rc_new.sql' ],
			[ 'dropField', 'categorylinks', 'cl_to', 'patch-categorylinks-drop-cl_to-cl_collation.sql' ],

			// 1.46
			[ 'addTable', 'watchlist_label', 'patch-watchlist_label.sql' ],
			[ 'dropField', 'recentchanges', 'rc_type', 'patch-recentchanges-drop-rc_type.sql' ],
			[ 'dropField', 'archive', 'ar_sha1', 'patch-archive-drop-ar_sha1.sql' ],
			[ 'dropField', 'revision', 'rev_sha1', 'patch-revision-drop-rev_sha1.sql' ],
			[ 'dropField', 'objectcache', 'modtoken', 'patch-objectcache-drop-modtoken.sql' ],
			[ 'addField', 'imagelinks', 'il_target_id', 'patch-imagelinks-add-il_target_id.sql' ],
			[ 'migrateImagelinks' ],
			[ 'modifyPrimaryKey', 'imagelinks', [ 'il_from', 'il_target_id' ], 'patch-imagelinks-pk.sql' ],
			[ 'addMissingTalkPageWatchlistLabels' ],
			[ 'dropField', 'imagelinks', 'il_to', 'patch-imagelinks-drop-il_to.sql' ],

			// 1.47
			[ 'addField', 'site_stats', 'ss_temp_users', 'patch-site_stats-temp_users.sql' ],
			[ 'addIndex', 'file', 'file_type_name', 'patch-file-file_type_name.sql' ],

			// 1.48
			[ 'addTable', 'user_editcount', 'patch-user_editcount.sql' ],
			[ 'runMaintenance', CleanupWatchlistTemporaryAccountRows::class ],
		];
	}

	/** @inheritDoc */
	protected function getInitialUpdateKeys() {
		return [
			'filearchive-fa_major_mime-patch-fa_major_mime-chemical.sql',
			'image-img_major_mime-patch-img_major_mime-chemical.sql',
			'oldimage-oi_major_mime-patch-oi_major_mime-chemical.sql',
			'user_groups-ug_group-patch-ug_group-length-increase-255.sql',
			'user_former_groups-ufg_group-patch-ufg_group-length-increase-255.sql',
			'user_properties-up_property-patch-up_property.sql',
		];
	}

	/** @inheritDoc */
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
	 * Drops the default value from a field
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

	/**
	 * Change the table options of a table
	 *
	 * @since 1.43
	 * @param string $table
	 * @param string $tableOption Raw table option that should already have been escaped !!!!
	 * @param string $updateName
	 */
	protected function changeTableOption( string $table, string $tableOption, string $updateName ) {
		$updateKey = "$table-tableoption-$updateName";
		if ( $this->updateRowExists( $updateKey ) ) {
			return;
		}

		$this->output( "Changing table options of '$table'.\n" );
		$table = $this->db->tableName( $table );
		$ret = $this->db->query(
			"ALTER TABLE $table $tableOption",
			__METHOD__
		);

		if ( $ret ) {
			$this->insertUpdateRow( $updateKey );
		}
	}

	protected function migrateSearchindex() {
		$updateKey = 'searchindex-pk-titlelength';
		if ( !$this->tableExists( 'searchindex' ) ) {
			return;
		}

		$primaryIndexExists = $this->db->indexExists( 'searchindex', 'PRIMARY', __METHOD__ );
		if ( $this->updateRowExists( $updateKey ) || $primaryIndexExists ) {
			$this->outputApplied( "...searchindex table has already been migrated.\n" );
			if ( !$this->updateRowExists( $updateKey ) ) {
				$this->insertUpdateRow( $updateKey );
			}
			return;
		}

		$apply = $this->applyPatch( 'patch-searchindex-pk-titlelength.sql', false, '...migrating searchindex table' );

		if ( $apply ) {
			$this->insertUpdateRow( $updateKey );
		}
	}
}

/** @deprecated class alias since 1.42 */
class_alias( MysqlUpdater::class, 'MysqlUpdater' );
