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
 * @ingroup Installer
 */

namespace MediaWiki\Installer;

use FixInconsistentRedirects;
use FixWrongPasswordPrefixes;
use MediaWiki\Maintenance\FixAutoblockLogTitles;
use MigrateExternallinks;
use MigrateRevisionActorTemp;
use MigrateRevisionCommentTemp;
use PopulateUserIsTemp;
use UpdateRestrictions;

/**
 * Class for handling updates to Sqlite databases.
 *
 * @ingroup Installer
 * @since 1.17
 * @property \Wikimedia\Rdbms\DatabaseSqlite $db
 */
class SqliteUpdater extends DatabaseUpdater {

	protected function getCoreUpdateList() {
		return [
			// 1.39
			[ 'addTable', 'user_autocreate_serial', 'patch-user_autocreate_serial.sql' ],
			[ 'modifyField', 'ipblocks_restrictions', 'ir_ipb_id', 'patch-ipblocks_restrictions-ir_ipb_id.sql' ],
			[ 'modifyField', 'ipblocks', 'ipb_id', 'patch-ipblocks-ipb_id.sql' ],
			[ 'modifyField', 'user', 'user_editcount', 'patch-user-user_editcount.sql' ],
			[ 'runMaintenance', MigrateRevisionActorTemp::class ],
			[ 'dropTable', 'revision_actor_temp' ],
			[ 'runMaintenance', UpdateRestrictions::class ],
			[ 'dropField', 'page', 'page_restrictions', 'patch-page-drop-page_restrictions.sql' ],
			[ 'migrateTemplatelinks' ],
			[ 'modifyField', 'templatelinks', 'tl_namespace', 'patch-templatelinks-tl_title-nullable.sql' ],
			[ 'dropField', 'templatelinks', 'tl_title', 'patch-templatelinks-drop-tl_title.sql' ],

			// 1.40
			[ 'addField', 'externallinks', 'el_to_path', 'patch-externallinks-el_to_path.sql' ],

			// 1.41
			[ 'addField', 'user', 'user_is_temp', 'patch-user-user_is_temp.sql' ],
			[ 'runMaintenance', MigrateRevisionCommentTemp::class ],
			[ 'dropTable', 'revision_comment_temp' ],
			[ 'runMaintenance', MigrateExternallinks::class ],
			[ 'modifyField', 'externallinks', 'el_to', 'patch-externallinks-el_to_default.sql' ],
			[ 'addField', 'pagelinks', 'pl_target_id', 'patch-pagelinks-target_id.sql' ],
			[ 'dropField', 'externallinks', 'el_to', 'patch-externallinks-drop-el_to.sql' ],
			[ 'runMaintenance', FixInconsistentRedirects::class ],
			[ 'modifyField', 'image', 'img_size', 'patch-image-img_size_to_bigint.sql' ],
			[ 'modifyField', 'filearchive', 'fa_size', 'patch-filearchive-fa_size_to_bigint.sql' ],
			[ 'modifyField', 'oldimage', 'oi_size', 'patch-oldimage-oi_size_to_bigint.sql' ],
			[ 'modifyField', 'uploadstash', 'us_size', 'patch-uploadstash-us_size_to_bigint.sql' ],

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
			[ 'modifyField', 'change_tag', 'ct_rc_id', 'patch-change_tag-ct_rc_id.sql' ],
			[ 'runMaintenance', \MigrateBlocks::class ],
			[ 'dropTable', 'ipblocks' ],
			[ 'dropField', 'pagelinks', 'pl_title', 'patch-pagelinks-drop-pl_title.sql' ],
			[ 'addPostDatabaseUpdateMaintenance', FixAutoblockLogTitles::class ],

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
			[ 'addIndex', 'categorylinks', 'cl_pkey', 'patch-categorylinks-pk.sql' ],
			[ 'addIndex', 'recentchanges', 'rc_source_name_timestamp',
				'patch-recentchanges-rc_source_name_timestamp.sql' ],
		];
	}

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
}
