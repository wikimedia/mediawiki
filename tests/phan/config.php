<?php

use \Phan\Config;

// If xdebug is enabled, we need to increase the nesting level for phan
ini_set( 'xdebug.max_nesting_level', 1000 );

/**
 * This configuration will be read and overlayed on top of the
 * default configuration. Command line arguments will be applied
 * after this file is read.
 *
 * @see src/Phan/Config.php
 * See Config for all configurable options.
 *
 * A Note About Paths
 * ==================
 *
 * Files referenced from this file should be defined as
 *
 * ```
 *   Config::projectPath('relative_path/to/file')
 * ```
 *
 * where the relative path is relative to the root of the
 * project which is defined as either the working directory
 * of the phan executable or a path passed in via the CLI
 * '-d' flag.
 */
return [
	/**
	 * A list of individual files to include in analysis
	 * with a path relative to the root directory of the
	 * project. directory_list won't find .inc files so
	 * we augment it here.
	 */
	'file_list' => array_merge(
		function_exists( 'register_postsend_function' ) ? [] : [ 'tests/phan/stubs/hhvm.php' ],
		function_exists( 'wikidiff2_do_diff' ) ? [] : [ 'tests/phan/stubs/wikidiff.php' ],
		function_exists( 'tideways_enable' ) ? [] : [ 'tests/phan/stubs/tideways.php' ],
		class_exists( PEAR::class ) ? [] : [ 'tests/phan/stubs/mail.php' ],
		class_exists( Memcached::class ) ? [] : [ 'tests/phan/stubs/memcached.php' ],
		[
			'maintenance/7zip.inc',
			'maintenance/backup.inc',
			'maintenance/backupPrefetch.inc',
			'maintenance/cleanupTable.inc',
			'maintenance/CodeCleanerGlobalsPass.inc',
			'maintenance/commandLine.inc',
			'maintenance/importImages.inc',
			'maintenance/sqlite.inc',
			'maintenance/userDupes.inc',
			'maintenance/userOptions.inc',
			'maintenance/language/checkLanguage.inc',
			'maintenance/language/languages.inc',
		]
	),

	/**
	 * A list of directories that should be parsed for class and
	 * method information. After excluding the directories
	 * defined in exclude_analysis_directory_list, the remaining
	 * files will be statically analyzed for errors.
	 *
	 * Thus, both first-party and third-party code being used by
	 * your application should be included in this list.
	 */
	'directory_list' => [
		'includes/',
		'languages/',
		'maintenance/',
		'mw-config/',
		'resources/',
		'skins/',
		'vendor/',
	],

	/**
	 * A file list that defines files that will be excluded
	 * from parsing and analysis and will not be read at all.
	 *
	 * This is useful for excluding hopelessly unanalyzable
	 * files that can't be removed for whatever reason.
	 */
	'exclude_file_list' => [],

	/**
	 * A list of directories holding code that we want
	 * to parse, but not analyze. Also works for individual
	 * files.
	 */
	"exclude_analysis_directory_list" => [
		'vendor/',
		'tests/phan/stubs/',
		// The referenced classes are not available in vendor, only when
		// included from composer.
		'includes/composer/',
		// Directly references classes that only exist in Translate extension
		'maintenance/language/',
		// External class
		'includes/libs/jsminplus.php',
		// separate repositories
		'skins/',
	],

	/**
	 * Backwards Compatibility Checking. This is slow
	 * and expensive, but you should consider running
	 * it before upgrading your version of PHP to a
	 * new version that has backward compatibility
	 * breaks.
	 */
	'backward_compatibility_checks' => false,

	/**
	 * A set of fully qualified class-names for which
	 * a call to parent::__construct() is required
	 */
	'parent_constructor_required' => [
	],

	/**
	 * Run a quick version of checks that takes less
	 * time at the cost of not running as thorough
	 * an analysis. You should consider setting this
	 * to true only when you wish you had more issues
	 * to fix in your code base.
	 *
	 * In quick-mode the scanner doesn't rescan a function
	 * or a method's code block every time a call is seen.
	 * This means that the problem here won't be detected:
	 *
	 * ```php
	 * <?php
	 * function test($arg):int {
	 *    return $arg;
	 * }
	 * test("abc");
	 * ```
	 *
	 * This would normally generate:
	 *
	 * ```sh
	 * test.php:3 TypeError return string but `test()` is declared to return int
	 * ```
	 *
	 * The initial scan of the function's code block has no
	 * type information for `$arg`. It isn't until we see
	 * the call and rescan test()'s code block that we can
	 * detect that it is actually returning the passed in
	 * `string` instead of an `int` as declared.
	 */
	'quick_mode' => false,

	/**
	 * By default, Phan will not analyze all node types
	 * in order to save time. If this config is set to true,
	 * Phan will dig deeper into the AST tree and do an
	 * analysis on all nodes, possibly finding more issues.
	 *
	 * See \Phan\Analysis::shouldVisit for the set of skipped
	 * nodes.
	 */
	'should_visit_all_nodes' => true,

	/**
	 * If enabled, check all methods that override a
	 * parent method to make sure its signature is
	 * compatible with the parent's. This check
	 * can add quite a bit of time to the analysis.
	 */
	'analyze_signature_compatibility' => true,

	// Emit all issues. They are then suppressed via
	// suppress_issue_types, rather than a minimum
	// severity.
	"minimum_severity" => 0,

	/**
	 * If true, missing properties will be created when
	 * they are first seen. If false, we'll report an
	 * error message if there is an attempt to write
	 * to a class property that wasn't explicitly
	 * defined.
	 */
	'allow_missing_properties' => false,

	/**
	 * Allow null to be cast as any type and for any
	 * type to be cast to null. Setting this to false
	 * will cut down on false positives.
	 */
	'null_casts_as_any_type' => true,

	/**
	 * If enabled, scalars (int, float, bool, string, null)
	 * are treated as if they can cast to each other.
	 *
	 * MediaWiki is pretty lax and uses many scalar
	 * types interchangably.
	 */
	'scalar_implicit_cast' => true,

	/**
	 * If true, seemingly undeclared variables in the global
	 * scope will be ignored. This is useful for projects
	 * with complicated cross-file globals that you have no
	 * hope of fixing.
	 */
	'ignore_undeclared_variables_in_global_scope' => true,

	/**
	 * Set to true in order to attempt to detect dead
	 * (unreferenced) code. Keep in mind that the
	 * results will only be a guess given that classes,
	 * properties, constants and methods can be referenced
	 * as variables (like `$class->$property` or
	 * `$class->$method()`) in ways that we're unable
	 * to make sense of.
	 */
	'dead_code_detection' => false,

	/**
	 * If true, the dead code detection rig will
	 * prefer false negatives (not report dead code) to
	 * false positives (report dead code that is not
	 * actually dead) which is to say that the graph of
	 * references will create too many edges rather than
	 * too few edges when guesses have to be made about
	 * what references what.
	 */
	'dead_code_detection_prefer_false_negative' => true,

	/**
	 * If disabled, Phan will not read docblock type
	 * annotation comments (such as for @return, @param,
	 * @var, @suppress, @deprecated) and only rely on
	 * types expressed in code.
	 */
	'read_type_annotations' => true,

	/**
	 * If a file path is given, the code base will be
	 * read from and written to the given location in
	 * order to attempt to save some work from being
	 * done. Only changed files will get analyzed if
	 * the file is read
	 */
	'stored_state_file_path' => null,

	/**
	 * Set to true in order to ignore issue suppression.
	 * This is useful for testing the state of your code, but
	 * unlikely to be useful outside of that.
	 */
	'disable_suppression' => false,

	/**
	 * If set to true, we'll dump the AST instead of
	 * analyzing files
	 */
	'dump_ast' => false,

	/**
	 * If set to a string, we'll dump the fully qualified lowercase
	 * function and method signatures instead of analyzing files.
	 */
	'dump_signatures_file' => null,

	/**
	 * If true (and if stored_state_file_path is set) we'll
	 * look at the list of files passed in and expand the list
	 * to include files that depend on the given files
	 */
	'expand_file_list' => false,

	// Include a progress bar in the output
	'progress_bar' => false,

	/**
	 * The probability of actually emitting any progress
	 * bar update. Setting this to something very low
	 * is good for reducing network IO and filling up
	 * your terminal's buffer when running phan on a
	 * remote host.
	 */
	'progress_bar_sample_rate' => 0.005,

	/**
	 * The number of processes to fork off during the analysis
	 * phase.
	 */
	'processes' => 1,

	/**
	 * Add any issue types (such as 'PhanUndeclaredMethod')
	 * to this black-list to inhibit them from being reported.
	 */
	'suppress_issue_types' => [
		// approximate error count: 8
		"PhanDeprecatedClass",
		// approximate error count: 441
		"PhanDeprecatedFunction",
		// approximate error count: 24
		"PhanDeprecatedProperty",
		// approximate error count: 12
		"PhanParamReqAfterOpt",
		// approximate error count: 748
		"PhanParamSignatureMismatch",
		// approximate error count: 7
		"PhanParamSignatureMismatchInternal",
		// approximate error count: 308
		"PhanParamTooMany",
		// approximate error count: 3
		"PhanParamTooManyInternal",
		// approximate error count: 1
		"PhanRedefineFunctionInternal",
		// approximate error count: 2
		"PhanTraitParentReference",
		// approximate error count: 4
		"PhanTypeComparisonFromArray",
		// approximate error count: 3
		"PhanTypeInvalidRightOperand",
		// approximate error count: 563
		"PhanTypeMismatchArgument",
		// approximate error count: 39
		"PhanTypeMismatchArgumentInternal",
		// approximate error count: 16
		"PhanTypeMismatchForeach",
		// approximate error count: 63
		"PhanTypeMismatchProperty",
		// approximate error count: 95
		"PhanTypeMismatchReturn",
		// approximate error count: 11
		"PhanTypeMissingReturn",
		// approximate error count: 5
		"PhanTypeNonVarPassByRef",
		// approximate error count: 27
		"PhanUndeclaredConstant",
		// approximate error count: 185
		"PhanUndeclaredMethod",
		// approximate error count: 1342
		"PhanUndeclaredProperty",
		// approximate error count: 3
		"PhanUndeclaredStaticMethod",
	],

	/**
	 * If empty, no filter against issues types will be applied.
	 * If this white-list is non-empty, only issues within the list
	 * will be emitted by Phan.
	 */
	'whitelist_issue_types' => [
		// 'PhanAccessMethodPrivate',
		// 'PhanAccessMethodProtected',
		// 'PhanAccessNonStaticToStatic',
		// 'PhanAccessPropertyPrivate',
		// 'PhanAccessPropertyProtected',
		// 'PhanAccessSignatureMismatch',
		// 'PhanAccessSignatureMismatchInternal',
		// 'PhanAccessStaticToNonStatic',
		// 'PhanCompatibleExpressionPHP7',
		// 'PhanCompatiblePHP7',
		// 'PhanContextNotObject',
		// 'PhanDeprecatedClass',
		// 'PhanDeprecatedFunction',
		// 'PhanDeprecatedProperty',
		// 'PhanEmptyFile',
		// 'PhanNonClassMethodCall',
		// 'PhanNoopArray',
		// 'PhanNoopClosure',
		// 'PhanNoopConstant',
		// 'PhanNoopProperty',
		// 'PhanNoopVariable',
		// 'PhanParamRedefined',
		// 'PhanParamReqAfterOpt',
		// 'PhanParamSignatureMismatch',
		// 'PhanParamSignatureMismatchInternal',
		// 'PhanParamSpecial1',
		// 'PhanParamSpecial2',
		// 'PhanParamSpecial3',
		// 'PhanParamSpecial4',
		// 'PhanParamTooFew',
		// 'PhanParamTooFewInternal',
		// 'PhanParamTooMany',
		// 'PhanParamTooManyInternal',
		// 'PhanParamTypeMismatch',
		// 'PhanParentlessClass',
		// 'PhanRedefineClass',
		// 'PhanRedefineClassInternal',
		// 'PhanRedefineFunction',
		// 'PhanRedefineFunctionInternal',
		// 'PhanStaticCallToNonStatic',
		// 'PhanSyntaxError',
		// 'PhanTraitParentReference',
		// 'PhanTypeArrayOperator',
		// 'PhanTypeArraySuspicious',
		// 'PhanTypeComparisonFromArray',
		// 'PhanTypeComparisonToArray',
		// 'PhanTypeConversionFromArray',
		// 'PhanTypeInstantiateAbstract',
		// 'PhanTypeInstantiateInterface',
		// 'PhanTypeInvalidLeftOperand',
		// 'PhanTypeInvalidRightOperand',
		// 'PhanTypeMismatchArgument',
		// 'PhanTypeMismatchArgumentInternal',
		// 'PhanTypeMismatchDefault',
		// 'PhanTypeMismatchForeach',
		// 'PhanTypeMismatchProperty',
		// 'PhanTypeMismatchReturn',
		// 'PhanTypeMissingReturn',
		// 'PhanTypeNonVarPassByRef',
		// 'PhanTypeParentConstructorCalled',
		// 'PhanTypeVoidAssignment',
		// 'PhanUnanalyzable',
		// 'PhanUndeclaredClass',
		// 'PhanUndeclaredClassCatch',
		// 'PhanUndeclaredClassConstant',
		// 'PhanUndeclaredClassInstanceof',
		// 'PhanUndeclaredClassMethod',
		// 'PhanUndeclaredClassReference',
		// 'PhanUndeclaredConstant',
		// 'PhanUndeclaredExtendedClass',
		// 'PhanUndeclaredFunction',
		// 'PhanUndeclaredInterface',
		// 'PhanUndeclaredMethod',
		// 'PhanUndeclaredProperty',
		// 'PhanUndeclaredStaticMethod',
		// 'PhanUndeclaredStaticProperty',
		// 'PhanUndeclaredTrait',
		// 'PhanUndeclaredTypeParameter',
		// 'PhanUndeclaredTypeProperty',
		// 'PhanUndeclaredVariable',
		// 'PhanUnreferencedClass',
		// 'PhanUnreferencedConstant',
		// 'PhanUnreferencedMethod',
		// 'PhanUnreferencedProperty',
		// 'PhanVariableUseClause',
	],

	/**
	 * Override to hardcode existence and types of (non-builtin) globals in the global scope.
	 * Class names must be prefixed with '\\'.
	 * (E.g. ['_FOO' => '\\FooClass', 'page' => '\\PageClass', 'userId' => 'int'])
	 */
	'globals_type_map' => [
		'IP' => 'string',
	],

	// Emit issue messages with markdown formatting
	'markdown_issue_messages' => false,

	/**
	 * Enable or disable support for generic templated
	 * class types.
	 */
	'generic_types_enabled' => true,

	// A list of plugin files to execute
	'plugins' => [
	],
];
