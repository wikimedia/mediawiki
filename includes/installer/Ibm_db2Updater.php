<?php
/**
 * IBM_DB2-specific updater.
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
 * Class for handling updates to IBM_DB2 databases.
 *
 * @ingroup Deployment
 * @since 1.17
 */
class Ibm_db2Updater extends DatabaseUpdater {

	/**
	 * Get the changes in the DB2 database scheme since MediaWiki 1.14
	 * @return array
	 */
	protected function getCoreUpdateList() {
		return array(
			// 1.14
			array( 'addField', 'site_stats',    'ss_active_users',  'patch-ss_active_users.sql' ),
			array( 'addField', 'ipblocks',      'ipb_allow_usertalk', 'patch-ipb_allow_usertalk.sql' ),

			// 1.15
			array( 'addTable', 'change_tag',                        'patch-change_tag.sql' ),
			array( 'addTable', 'tag_summary',                       'patch-change_tag_summary.sql' ),
			array( 'addTable', 'valid_tag',                         'patch-change_valid_tag.sql' ),

			// 1.16
			array( 'addTable', 'user_properties',                   'patch-user_properties.sql' ),
			array( 'addTable', 'log_search',                        'patch-log_search.sql' ),
			array( 'addField', 'logging',       'log_user_text',    'patch-log_user_text.sql' ),
			array( 'addTable', 'l10n_cache',                        'patch-l10n_cache.sql' ),
			array( 'addTable', 'external_user',                     'patch-external_user.sql' ),
			array( 'addIndex', 'log_search',    'ls_field_val',     'patch-log_search-rename-index.sql' ),
			array( 'addIndex', 'change_tag',    'change_tag_rc_tag', 'patch-change_tag-indexes.sql' ),
			array( 'addField', 'redirect',      'rd_interwiki',     'patch-rd_interwiki.sql' ),

			// 1.17
			array( 'addTable', 'iwlinks',                            'patch-iwlinks.sql' ),
			array( 'addField', 'updatelog',     'ul_value',          'patch-ul_value.sql' ),
			array( 'addField', 'interwiki',     'iw_api',            'patch-iw_api_and_wikiid.sql' ),
			array( 'addField', 'categorylinks', 'cl_collation',      'patch-categorylinks-better-collation.sql' ),
			array( 'addTable', 'msg_resource',                       'patch-msg_resource.sql' ),
			array( 'addTable', 'msg_resource_links',                 'patch-msg_resource_links.sql' ),
			array( 'addIndex', 'msg_resource_links', 'uq61_msg_resource_links', 'patch-uq_61_msg_resource_links.sql' ),
			array( 'addIndex', 'msg_resource',   'uq81_msg_resource', 'patch-uq_81_msg_resource.sql' ),
			array( 'addTable', 'module_deps',                        'patch-module_deps.sql' ),
			array( 'addIndex', 'module_deps',    'uq96_module_deps',  'patch-uq_96_module_deps.sql' ),
			array( 'addField', 'interwiki',      'iw_api',            'patch-iw_api-field.sql' ),
			array( 'addField', 'interwiki',      'iw_wikiid',         'patch-iw_wikiid-field.sql' ),
			array( 'addField', 'categorylinks',  'cl_sortkey_prefix', 'patch-cl_sortkey_prefix-field.sql' ),
			array( 'addField', 'categorylinks',  'cl_collation',      'patch-cl_collation-field.sql' ),
			array( 'addField', 'categorylinks',  'cl_type',           'patch-cl_type-field.sql' ),

			//1.18
			array( 'doUserNewTalkTimestampNotNull' ),
			array( 'addIndex', 'user',          'user_email',       'patch-user_email_index.sql' ),
			array( 'modifyField', 'user_properties', 'up_property', 'patch-up_property.sql' ),
			array( 'addTable', 'uploadstash',                       'patch-uploadstash.sql' ),
			array( 'addTable', 'user_former_groups',                'patch-user_former_groups.sql'),
			array( 'doRebuildLocalisationCache' ),

			// 1.19
			array( 'addIndex', 'logging',       'type_action',      'patch-logging-type-action-index.sql'),
			array( 'dropField', 'user',         'user_options', 'patch-drop-user_options.sql' ),
			array( 'addField', 'revision',      'rev_sha1',         'patch-rev_sha1.sql' ),
			array( 'addField', 'archive',       'ar_sha1',          'patch-ar_sha1.sql' ),

			// 1.20
		);
	}
}
