<?php
/**
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
 */

$disableTaintCheck = true;
$cfg = require __DIR__ . '/../vendor/mediawiki/mediawiki-phan-config/src/config.php';

$cfg['file_list'] = array_merge(
	$cfg['file_list'],
	class_exists( PEAR::class ) ? [] : [ '.phan/stubs/mail.php' ],
	defined( 'PASSWORD_ARGON2ID' ) ? [] : [ '.phan/stubs/password.php' ],
	class_exists( ValueError::class ) ? [] : [ '.phan/stubs/ValueError.php' ],
	[
		// This makes constants and globals known to Phan before processing all other files.
		// You can check the parser order with --dump-parsed-file-list
		'includes/Defines.php',
		// @todo This isn't working yet, see globals_type_map below
		// 'includes/DefaultSettings.php',
		// 'includes/Setup.php',
	]
);

$cfg['exclude_file_list'] = array_merge(
	$cfg['exclude_file_list'],
	[
		// This file has invalid PHP syntax
		'vendor/squizlabs/php_codesniffer/src/Standards/PSR2/Tests/Methods/MethodDeclarationUnitTest.inc',
	]
);

$cfg['analyzed_file_extensions'] = array_merge(
	$cfg['analyzed_file_extensions'] ?? [ 'php' ],
	[ 'inc' ]
);

$cfg['autoload_internal_extension_signatures'] = [
	'dom' => '.phan/internal_stubs/dom.phan_php',
	'excimer' => '.phan/internal_stubs/excimer.php',
	'imagick' => '.phan/internal_stubs/imagick.phan_php',
	'intl' => '.phan/internal_stubs/intl.phan_php',
	'memcached' => '.phan/internal_stubs/memcached.phan_php',
	'oci8' => '.phan/internal_stubs/oci8.phan_php',
	'pcntl' => '.phan/internal_stubs/pcntl.phan_php',
	'pgsql' => '.phan/internal_stubs/pgsql.phan_php',
	'redis' => '.phan/internal_stubs/redis.phan_php',
	'sockets' => '.phan/internal_stubs/sockets.phan_php',
	'sqlsrv' => '.phan/internal_stubs/sqlsrv.phan_php',
	'tideways' => '.phan/internal_stubs/tideways.phan_php',
	'wikidiff2' => '.phan/internal_stubs/wikidiff.php'
];

$cfg['directory_list'] = [
	'includes/',
	'languages/',
	'maintenance/',
	'mw-config/',
	'resources/',
	'vendor/',
	// Do NOT add .phan/stubs/ here: stubs are conditionally loaded in file_list
];

$cfg['exclude_analysis_directory_list'] = [
	'vendor/',
	'.phan/',
	// The referenced classes are not available in vendor, only when
	// included from composer.
	'includes/composer/',
	// Directly references classes that only exist in Translate extension
	'maintenance/language/',
	// External class
	'includes/libs/jsminplus.php',
	// External class
	'includes/libs/objectcache/utils/MemcachedClient.php',
];

// These are too spammy for now. TODO enable
$cfg['null_casts_as_any_type'] = true;
$cfg['scalar_implicit_cast'] = true;
$cfg['suppress_issue_types'][] = 'PhanTypePossiblyInvalidDimOffset';
$cfg['suppress_issue_types'][] = 'PhanPossiblyUndeclaredVariable';

// Do not use aliases in core.
// Use the correct name, because we don't need backward compatibility
$cfg['enable_class_alias_support'] = false;

$cfg['ignore_undeclared_variables_in_global_scope'] = true;
// @todo It'd be great if we could just make phan read these from DefaultSettings, to avoid
// duplicating the types.
$cfg['globals_type_map'] = array_merge( $cfg['globals_type_map'], [
	'IP' => 'string',
	'wgGalleryOptions' => 'array',
	'wgDummyLanguageCodes' => 'string[]',
	'wgNamespaceProtection' => 'array<string,string|string[]>',
	'wgNamespaceAliases' => 'array<string,int>',
	'wgLockManagers' => 'array[]',
	'wgForeignFileRepos' => 'array[]',
	'wgDefaultUserOptions' => 'array',
	'wgSkipSkins' => 'string[]',
	'wgLogTypes' => 'string[]',
	'wgLogNames' => 'array<string,string>',
	'wgLogHeaders' => 'array<string,string>',
	'wgLogActionsHandlers' => 'array<string,class-string>',
	'wgPasswordPolicy' => 'array<string,array<string,string|array>>',
	'wgVirtualRestConfig' => 'array<string,array>',
	'wgWANObjectCaches' => 'array[]',
	'wgLocalInterwikis' => 'string[]',
	'wgDebugLogGroups' => 'string|false|array{destination:string,sample?:int,level:int}',
	'wgCookiePrefix' => 'string|false',
	'wgOut' => 'OutputPage',
	'wgExtraNamespaces' => 'string[]',
] );

return $cfg;
