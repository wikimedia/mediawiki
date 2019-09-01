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

$cfg = require __DIR__ . '/../vendor/mediawiki/mediawiki-phan-config/src/config.php';

$cfg['file_list'] = array_merge(
	$cfg['file_list'],
	function_exists( 'register_postsend_function' ) ? [] : [ '.phan/stubs/hhvm.php' ],
	function_exists( 'wikidiff2_do_diff' ) ? [] : [ '.phan/stubs/wikidiff.php' ],
	class_exists( PEAR::class ) ? [] : [ '.phan/stubs/mail.php' ],
	defined( 'PASSWORD_ARGON2I' ) ? [] : [ '.phan/stubs/password.php' ],
	// Per composer.json, PHPUnit 6 is used for PHP 7.0+, PHPUnit 4 otherwise.
	// Load the interface for the version of PHPUnit that isn't installed.
	// Phan only supports PHP 7.0+ (and not HHVM), so we only need to stub PHPUnit 4.
	class_exists( PHPUnit_TextUI_Command::class ) ? [] : [ '.phan/stubs/phpunit4.php' ],
	class_exists( ProfilerExcimer::class ) ? [] : [ '.phan/stubs/excimer.php' ],
	[
		'maintenance/cleanupTable.inc',
		'maintenance/CodeCleanerGlobalsPass.inc',
		'maintenance/commandLine.inc',
		'maintenance/sqlite.inc',
		'maintenance/userDupes.inc',
		'maintenance/language/checkLanguage.inc',
		'maintenance/language/languages.inc',
	]
);

$cfg['autoload_internal_extension_signatures'] = [
	'imagick' => '.phan/internal_stubs/imagick.phan_php',
	'memcached' => '.phan/internal_stubs/memcached.phan_php',
	'oci8' => '.phan/internal_stubs/oci8.phan_php',
	'pcntl' => '.phan/internal_stubs/pcntl.phan_php',
	'redis' => '.phan/internal_stubs/redis.phan_php',
	'sockets' => '.phan/internal_stubs/sockets.phan_php',
	'sqlsrv' => '.phan/internal_stubs/sqlsrv.phan_php',
	'tideways' => '.phan/internal_stubs/tideways.phan_php',
];

$cfg['directory_list'] = [
	'includes/',
	'languages/',
	'maintenance/',
	'mw-config/',
	'resources/',
	'vendor/',
	'.phan/stubs/',
];

$cfg['exclude_analysis_directory_list'] = [
	'vendor/',
	'.phan/stubs/',
	// The referenced classes are not available in vendor, only when
	// included from composer.
	'includes/composer/',
	// Directly references classes that only exist in Translate extension
	'maintenance/language/',
	// External class
	'includes/libs/jsminplus.php',
];

$cfg['suppress_issue_types'] = array_merge( $cfg['suppress_issue_types'], [
	// approximate error count: 19
	"PhanParamReqAfterOpt", // False positives with nullables, ref phan issue #3159
	// approximate error count: 110
	"PhanParamTooMany", // False positives with variargs. Unsuppress after dropping HHVM

	// approximate error count: 22
	"PhanAccessMethodInternal",
	// approximate error count: 36
	"PhanUndeclaredConstant",
	// approximate error count: 60
	"PhanTypeMismatchArgument",
	// approximate error count: 219
	"PhanUndeclaredMethod",
	// approximate error count: 752
	"PhanUndeclaredProperty",
] );

$cfg['ignore_undeclared_variables_in_global_scope'] = true;
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
] );

return $cfg;
