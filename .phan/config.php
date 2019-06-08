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

$cfg['autoload_internal_extension_signatures'] = [
	'memcached' => '.phan/internal_stubs/memcached.phan_php',
	'oci8' => '.phan/internal_stubs/oci8.phan_php',
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
	// approximate error count: 18
	"PhanAccessMethodInternal",
	// approximate error count: 17
	"PhanCommentParamOnEmptyParamList",
	// approximate error count: 29
	"PhanCommentParamWithoutRealParam",
	// approximate error count: 2
	"PhanCompatibleNegativeStringOffset",
	// approximate error count: 21
	"PhanParamReqAfterOpt",
	// approximate error count: 26
	"PhanParamSignatureMismatch",
	// approximate error count: 4
	"PhanParamSignatureMismatchInternal",
	// approximate error count: 127
	"PhanParamTooMany",
	// approximate error count: 2
	"PhanTraitParentReference",
	// approximate error count: 30
	"PhanTypeArraySuspicious",
	// approximate error count: 27
	"PhanTypeArraySuspiciousNullable",
	// approximate error count: 26
	"PhanTypeComparisonFromArray",
	// approximate error count: 63
	"PhanTypeInvalidDimOffset",
	// approximate error count: 7
	"PhanTypeInvalidLeftOperandOfIntegerOp",
	// approximate error count: 2
	"PhanTypeInvalidRightOperandOfIntegerOp",
	// approximate error count: 154
	"PhanTypeMismatchArgument",
	// approximate error count: 27
	"PhanTypeMismatchArgumentInternal",
	// approximate error count: 2
	"PhanTypeMismatchDimEmpty",
	// approximate error count: 27
	"PhanTypeMismatchDimFetch",
	// approximate error count: 10
	"PhanTypeMismatchForeach",
	// approximate error count: 77
	"PhanTypeMismatchProperty",
	// approximate error count: 84
	"PhanTypeMismatchReturn",
	// approximate error count: 12
	"PhanTypeObjectUnsetDeclaredProperty",
	// approximate error count: 9
	"PhanTypeSuspiciousNonTraversableForeach",
	// approximate error count: 3
	"PhanTypeSuspiciousStringExpression",
	// approximate error count: 22
	"PhanUndeclaredConstant",
	// approximate error count: 3
	"PhanUndeclaredInvokeInCallable",
	// approximate error count: 237
	"PhanUndeclaredMethod",
	// approximate error count: 846
	"PhanUndeclaredProperty",
	// approximate error count: 2
	"PhanUndeclaredVariableAssignOp",
	// approximate error count: 55
	"PhanUndeclaredVariableDim",
] );

$cfg['ignore_undeclared_variables_in_global_scope'] = true;
$cfg['globals_type_map']['IP'] = 'string';

return $cfg;
