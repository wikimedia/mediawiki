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

$cfg = require __DIR__ . '/../../vendor/mediawiki/mediawiki-phan-config/src/config.php';

$cfg['file_list'] = array_merge(
	$cfg['file_list'],
	function_exists( 'register_postsend_function' ) ? [] : [ 'tests/phan/stubs/hhvm.php' ],
	function_exists( 'wikidiff2_do_diff' ) ? [] : [ 'tests/phan/stubs/wikidiff.php' ],
	function_exists( 'tideways_enable' ) ? [] : [ 'tests/phan/stubs/tideways.php' ],
	class_exists( PEAR::class ) ? [] : [ 'tests/phan/stubs/mail.php' ],
	class_exists( Memcached::class ) ? [] : [ 'tests/phan/stubs/memcached.php' ],
	// Per composer.json, PHPUnit 6 is used for PHP 7.0+, PHPUnit 4 otherwise.
	// Load the interface for the version of PHPUnit that isn't installed.
	// Phan only supports PHP 7.0+ (and not HHVM), so we only need to stub PHPUnit 4.
	class_exists( PHPUnit_TextUI_Command::class ) ? [] : [ 'tests/phan/stubs/phpunit4.php' ],
	class_exists( ProfilerExcimer::class ) ? [] : [ 'tests/phan/stubs/excimer.php' ],
	[
		'maintenance/7zip.inc',
		'maintenance/cleanupTable.inc',
		'maintenance/CodeCleanerGlobalsPass.inc',
		'maintenance/commandLine.inc',
		'maintenance/sqlite.inc',
		'maintenance/userDupes.inc',
		'maintenance/language/checkLanguage.inc',
		'maintenance/language/languages.inc',
	]
);

$cfg['directory_list'] = [
	'includes/',
	'languages/',
	'maintenance/',
	'mw-config/',
	'resources/',
	'vendor/',
];

$cfg['exclude_analysis_directory_list'] = [
	'vendor/',
	'tests/phan/stubs/',
	// The referenced classes are not available in vendor, only when
	// included from composer.
	'includes/composer/',
	// Directly references classes that only exist in Translate extension
	'maintenance/language/',
	// External class
	'includes/libs/jsminplus.php',
];

$cfg['suppress_issue_types'] = array_merge( $cfg['suppress_issue_types'], [
	// approximate error count: 29
	"PhanCommentParamOnEmptyParamList",
	// approximate error count: 33
	"PhanCommentParamWithoutRealParam",
	// approximate error count: 17
	"PhanNonClassMethodCall",
	// approximate error count: 888
	"PhanParamSignatureMismatch",
	// approximate error count: 7
	"PhanParamSignatureMismatchInternal",
	// approximate error count: 1
	"PhanParamSignatureRealMismatchTooFewParameters",
	// approximate error count: 125
	"PhanParamTooMany",
	// approximate error count: 3
	"PhanParamTooManyInternal",
	// approximate error count: 2
	"PhanTraitParentReference",
	// approximate error count: 3
	"PhanTypeComparisonFromArray",
	// approximate error count: 2
	"PhanTypeComparisonToArray",
	// approximate error count: 218
	"PhanTypeMismatchArgument",
	// approximate error count: 13
	"PhanTypeMismatchArgumentInternal",
	// approximate error count: 5
	"PhanTypeMismatchDimAssignment",
	// approximate error count: 2
	"PhanTypeMismatchDimEmpty",
	// approximate error count: 1
	"PhanTypeMismatchDimFetch",
	// approximate error count: 14
	"PhanTypeMismatchForeach",
	// approximate error count: 56
	"PhanTypeMismatchProperty",
	// approximate error count: 74
	"PhanTypeMismatchReturn",
	// approximate error count: 5
	"PhanTypeNonVarPassByRef",
	// approximate error count: 32
	"PhanUndeclaredConstant",
	// approximate error count: 233
	"PhanUndeclaredMethod",
	// approximate error count: 1224
	"PhanUndeclaredProperty",
	// approximate error count: 58
	"PhanUndeclaredVariableDim",
] );

$cfg['ignore_undeclared_variables_in_global_scope'] = true;
$cfg['globals_type_map']['IP'] = 'string';

return $cfg;
