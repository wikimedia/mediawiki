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

// This value should match the PHP version specified in composer.json,
// PHPVersionCheck.php, and ScopeStructureTest.php
$cfg['minimum_target_php_version'] = '8.1.0';

$cfg['file_list'] = array_merge(
	$cfg['file_list'],
	class_exists( AllowDynamicProperties::class ) ? [] : [ '.phan/stubs/AllowDynamicProperties.php' ],
	[
		// This makes constants and globals known to Phan before processing all other files.
		// You can check the parser order with --dump-parsed-file-list
		'includes/Defines.php',
		// @todo This isn't working yet, see globals_type_map below
		// 'includes/Setup.php',
		'tests/phpunit/MediaWikiIntegrationTestCase.php',
		'tests/phpunit/includes/TestUser.php',
	]
);

$cfg['exclude_file_list'] = array_merge(
	$cfg['exclude_file_list'],
	[
		// Avoid microsoft/tolerant-php-parser dependency
		'maintenance/findDeprecated.php',
		'maintenance/CodeCleanerGlobalsPass.php',
		// Avoid nikic/php-parser dependency
		'maintenance/shell.php',
	]
);

$cfg['autoload_internal_extension_signatures'] = [
	'excimer' => '.phan/internal_stubs/excimer.phan_php',
	'imagick' => '.phan/internal_stubs/imagick.phan_php',
	'memcached' => '.phan/internal_stubs/memcached.phan_php',
	'pcntl' => '.phan/internal_stubs/pcntl.phan_php',
	'pgsql' => '.phan/internal_stubs/pgsql.phan_php',
	'redis' => '.phan/internal_stubs/redis.phan_php',
	'sockets' => '.phan/internal_stubs/sockets.phan_php',
	'tideways_xhprof' => '.phan/internal_stubs/tideways_xhprof.phan_php',
	'wikidiff2' => '.phan/internal_stubs/wikidiff.php'
];

$cfg['directory_list'] = [
	'includes/',
	'languages/',
	'maintenance/',
	'mw-config/',
	'resources/',
	'tests/common/',
	'tests/parser/',
	'tests/phan',
	'tests/phpunit/mocks/',
	// Do NOT add .phan/stubs/ here: stubs are conditionally loaded in file_list
];

// Include only direct production dependencies in vendor/
// Omit dev dependencies and most indirect dependencies

$composerJson = json_decode(
	file_get_contents( __DIR__ . '/../composer.json' ),
	true
);

$directDeps = [];
foreach ( $composerJson['require'] as $dep => $version ) {
	$parts = explode( '/', $dep );
	if ( count( $parts ) === 2 ) {
		$directDeps[] = $dep;
	}
}

// This is a list of all composer packages that are referenced by core but
// are not listed as requirements in composer.json.
$indirectDeps = [
	'composer/spdx-licenses',
	'doctrine/dbal',
	'doctrine/sql-formatter',
	'guzzlehttp/psr7',
	'pear/net_url2',
	'pear/pear-core-minimal',
	'phpunit/phpunit',
	'psr/http-client',
	'psr/http-factory',
	'psr/http-message',
	'seld/jsonlint',
	'wikimedia/testing-access-wrapper',
	'wikimedia/zest-css',
];

foreach ( [ ...$directDeps, ...$indirectDeps ] as $dep ) {
	$cfg['directory_list'][] = "vendor/$dep";
}

$cfg['exclude_analysis_directory_list'] = [
	'vendor/',
	'.phan/',
	'tests/phpunit/',
	// The referenced classes are not available in vendor, only when
	// included from composer.
	'includes/composer/',
	// Directly references classes that only exist in Translate extension
	'maintenance/language/',
	// External class
	'includes/libs/objectcache/utils/MemcachedClient.php',
	// File may be valid, but may contain numerous "errors" such as iterating over an
	// empty array due to the version checking in T246594 not being currently used.
	'includes/PHPVersionCheck.php',
];

// TODO: Ideally we'd disable this in core, given we don't need backwards compatibility here and aliases
// should not be used. However, that would have unwanted side effects such as being unable to test
// taint-check (T321806).
$cfg['enable_class_alias_support'] = true;
// Exclude Parsoid's src/DOM in favor of .phan/stubs/DomImpl.php
$cfg['exclude_file_list'] = array_merge(
	$cfg['exclude_file_list'],
	array_map( static fn ( $f ) => "vendor/wikimedia/parsoid/src/DOM/{$f}.php", [
		'Attr', 'CharacterData', 'Comment', 'Document', 'DocumentFragment',
		'DocumentType', 'Element', 'Node', 'ProcessingInstruction', 'Text',
	] )
);
$cfg['file_list'][] = '.phan/stubs/DomImpl.php';

$cfg['ignore_undeclared_variables_in_global_scope'] = true;
// @todo It'd be great if we could just make phan read these from config-schema.php, to avoid
// duplicating the types. config-schema.php has JSON types though, not PHP types.
// @todo As we are removing access to global variables from the code base,
// remove them from here as well, so phan complains when something tries to use them.
$cfg['globals_type_map'] = array_merge( $cfg['globals_type_map'], [
	'IP' => 'string',
	'wgTitle' => \MediaWiki\Title\Title::class,
	'wgGalleryOptions' => 'array',
	'wgDirectoryMode' => 'int',
	'wgDummyLanguageCodes' => 'string[]',
	'wgNamespaceProtection' => 'array<int,string|string[]>',
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
	'wgLocalInterwikis' => 'string[]',
	'wgDebugLogGroups' => 'string|false|array{destination:string,sample?:int,level:int}',
	'wgCookiePrefix' => 'string|false',
	'wgOut' => \MediaWiki\Output\OutputPage::class,
	'wgExtraNamespaces' => 'string[]',
	'wgRequest' => \MediaWiki\Request\WebRequest::class,
] );

// Include a local config file if it exists
if ( file_exists( __DIR__ . '/local-config.php' ) ) {
	require __DIR__ . '/local-config.php';
}

return $cfg;
