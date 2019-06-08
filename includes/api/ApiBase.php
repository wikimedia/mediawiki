<?php
/**
 * Copyright © 2006, 2010 Yuri Astrakhan "<Firstname><Lastname>@gmail.com"
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
 */

use Wikimedia\Rdbms\IDatabase;

/**
 * This abstract class implements many basic API functions, and is the base of
 * all API classes.
 * The class functions are divided into several areas of functionality:
 *
 * Module parameters: Derived classes can define getAllowedParams() to specify
 *    which parameters to expect, how to parse and validate them.
 *
 * Self-documentation: code to allow the API to document its own state
 *
 * @ingroup API
 */
abstract class ApiBase extends ContextSource {

	/**
	 * @name Constants for ::getAllowedParams() arrays
	 * These constants are keys in the arrays returned by ::getAllowedParams()
	 * and accepted by ::getParameterFromSettings() that define how the
	 * parameters coming in from the request are to be interpreted.
	 * @{
	 */

	/** (null|boolean|integer|string) Default value of the parameter. */
	const PARAM_DFLT = 0;

	/** (boolean) Accept multiple pipe-separated values for this parameter (e.g. titles)? */
	const PARAM_ISMULTI = 1;

	/**
	 * (string|string[]) Either an array of allowed value strings, or a string
	 * type as described below. If not specified, will be determined from the
	 * type of PARAM_DFLT.
	 *
	 * Supported string types are:
	 * - boolean: A boolean parameter, returned as false if the parameter is
	 *   omitted and true if present (even with a falsey value, i.e. it works
	 *   like HTML checkboxes). PARAM_DFLT must be boolean false, if specified.
	 *   Cannot be used with PARAM_ISMULTI.
	 * - integer: An integer value. See also PARAM_MIN, PARAM_MAX, and
	 *   PARAM_RANGE_ENFORCE.
	 * - limit: An integer or the string 'max'. Default lower limit is 0 (but
	 *   see PARAM_MIN), and requires that PARAM_MAX and PARAM_MAX2 be
	 *   specified. Cannot be used with PARAM_ISMULTI.
	 * - namespace: An integer representing a MediaWiki namespace. Forces PARAM_ALL = true to
	 *   support easily specifying all namespaces.
	 * - NULL: Any string.
	 * - password: Any non-empty string. Input value is private or sensitive.
	 *   <input type="password"> would be an appropriate HTML form field.
	 * - string: Any non-empty string, not expected to be very long or contain newlines.
	 *   <input type="text"> would be an appropriate HTML form field.
	 * - submodule: The name of a submodule of this module, see PARAM_SUBMODULE_MAP.
	 * - tags: A string naming an existing, explicitly-defined tag. Should usually be
	 *   used with PARAM_ISMULTI.
	 * - text: Any non-empty string, expected to be very long or contain newlines.
	 *   <textarea> would be an appropriate HTML form field.
	 * - timestamp: A timestamp in any format recognized by MWTimestamp, or the
	 *   string 'now' representing the current timestamp. Will be returned in
	 *   TS_MW format.
	 * - user: A MediaWiki username or IP. Will be returned normalized but not canonicalized.
	 * - upload: An uploaded file. Will be returned as a WebRequestUpload object.
	 *   Cannot be used with PARAM_ISMULTI.
	 */
	const PARAM_TYPE = 2;

	/** (integer) Max value allowed for the parameter, for PARAM_TYPE 'integer' and 'limit'. */
	const PARAM_MAX = 3;

	/**
	 * (integer) Max value allowed for the parameter for users with the
	 * apihighlimits right, for PARAM_TYPE 'limit'.
	 */
	const PARAM_MAX2 = 4;

	/** (integer) Lowest value allowed for the parameter, for PARAM_TYPE 'integer' and 'limit'. */
	const PARAM_MIN = 5;

	/** (boolean) Allow the same value to be set more than once when PARAM_ISMULTI is true? */
	const PARAM_ALLOW_DUPLICATES = 6;

	/** (boolean) Is the parameter deprecated (will show a warning)? */
	const PARAM_DEPRECATED = 7;

	/**
	 * (boolean) Is the parameter required?
	 * @since 1.17
	 */
	const PARAM_REQUIRED = 8;

	/**
	 * (boolean) For PARAM_TYPE 'integer', enforce PARAM_MIN and PARAM_MAX?
	 * @since 1.17
	 */
	const PARAM_RANGE_ENFORCE = 9;

	/**
	 * (string|array|Message) Specify an alternative i18n documentation message
	 * for this parameter. Default is apihelp-{$path}-param-{$param}.
	 * @since 1.25
	 */
	const PARAM_HELP_MSG = 10;

	/**
	 * ((string|array|Message)[]) Specify additional i18n messages to append to
	 * the normal message for this parameter.
	 * @since 1.25
	 */
	const PARAM_HELP_MSG_APPEND = 11;

	/**
	 * (array) Specify additional information tags for the parameter. Value is
	 * an array of arrays, with the first member being the 'tag' for the info
	 * and the remaining members being the values. In the help, this is
	 * formatted using apihelp-{$path}-paraminfo-{$tag}, which is passed
	 * $1 = count, $2 = comma-joined list of values, $3 = module prefix.
	 * @since 1.25
	 */
	const PARAM_HELP_MSG_INFO = 12;

	/**
	 * (string[]) When PARAM_TYPE is an array, this may be an array mapping
	 * those values to page titles which will be linked in the help.
	 * @since 1.25
	 */
	const PARAM_VALUE_LINKS = 13;

	/**
	 * ((string|array|Message)[]) When PARAM_TYPE is an array, this is an array
	 * mapping those values to $msg for ApiBase::makeMessage(). Any value not
	 * having a mapping will use apihelp-{$path}-paramvalue-{$param}-{$value}.
	 * Specify an empty array to use the default message key for all values.
	 * @since 1.25
	 */
	const PARAM_HELP_MSG_PER_VALUE = 14;

	/**
	 * (string[]) When PARAM_TYPE is 'submodule', map parameter values to
	 * submodule paths. Default is to use all modules in
	 * $this->getModuleManager() in the group matching the parameter name.
	 * @since 1.26
	 */
	const PARAM_SUBMODULE_MAP = 15;

	/**
	 * (string) When PARAM_TYPE is 'submodule', used to indicate the 'g' prefix
	 * added by ApiQueryGeneratorBase (and similar if anything else ever does that).
	 * @since 1.26
	 */
	const PARAM_SUBMODULE_PARAM_PREFIX = 16;

	/**
	 * (boolean|string) When PARAM_TYPE has a defined set of values and PARAM_ISMULTI is true,
	 * this allows for an asterisk ('*') to be passed in place of a pipe-separated list of
	 * every possible value. If a string is set, it will be used in place of the asterisk.
	 * @since 1.29
	 */
	const PARAM_ALL = 17;

	/**
	 * (int[]) When PARAM_TYPE is 'namespace', include these as additional possible values.
	 * @since 1.29
	 */
	const PARAM_EXTRA_NAMESPACES = 18;

	/**
	 * (boolean) Is the parameter sensitive? Note 'password'-type fields are
	 * always sensitive regardless of the value of this field.
	 * @since 1.29
	 */
	const PARAM_SENSITIVE = 19;

	/**
	 * (array) When PARAM_TYPE is an array, this indicates which of the values are deprecated.
	 * Keys are the deprecated parameter values, values define the warning
	 * message to emit: either boolean true (to use a default message) or a
	 * $msg for ApiBase::makeMessage().
	 * @since 1.30
	 */
	const PARAM_DEPRECATED_VALUES = 20;

	/**
	 * (integer) Maximum number of values, for normal users. Must be used with PARAM_ISMULTI.
	 * @since 1.30
	 */
	const PARAM_ISMULTI_LIMIT1 = 21;

	/**
	 * (integer) Maximum number of values, for users with the apihighimits right.
	 * Must be used with PARAM_ISMULTI.
	 * @since 1.30
	 */
	const PARAM_ISMULTI_LIMIT2 = 22;

	/**
	 * (integer) Maximum length of a string in bytes (in UTF-8 encoding).
	 * @since 1.31
	 */
	const PARAM_MAX_BYTES = 23;

	/**
	 * (integer) Maximum length of a string in characters (unicode codepoints).
	 * @since 1.31
	 */
	const PARAM_MAX_CHARS = 24;

	/**
	 * (array) Indicate that this is a templated parameter, and specify replacements. Keys are the
	 * placeholders in the parameter name and values are the names of (unprefixed) parameters from
	 * which the replacement values are taken.
	 *
	 * For example, a parameter "foo-{ns}-{title}" could be defined with
	 * PARAM_TEMPLATE_VARS => [ 'ns' => 'namespaces', 'title' => 'titles' ]. Then a query for
	 * namespaces=0|1&titles=X|Y would support parameters foo-0-X, foo-0-Y, foo-1-X, and foo-1-Y.
	 *
	 * All placeholders must be present in the parameter's name. Each target parameter must have
	 * PARAM_ISMULTI true. If a target is itself a templated parameter, its PARAM_TEMPLATE_VARS must
	 * be a subset of the referring parameter's, mapping the same placeholders to the same targets.
	 * A parameter cannot target itself.
	 *
	 * @since 1.32
	 */
	const PARAM_TEMPLATE_VARS = 25;

	/**@}*/

	const ALL_DEFAULT_STRING = '*';

	/** Fast query, standard limit. */
	const LIMIT_BIG1 = 500;
	/** Fast query, apihighlimits limit. */
	const LIMIT_BIG2 = 5000;
	/** Slow query, standard limit. */
	const LIMIT_SML1 = 50;
	/** Slow query, apihighlimits limit. */
	const LIMIT_SML2 = 500;

	/**
	 * getAllowedParams() flag: When set, the result could take longer to generate,
	 * but should be more thorough. E.g. get the list of generators for ApiSandBox extension
	 * @since 1.21
	 */
	const GET_VALUES_FOR_HELP = 1;

	/** @var array Maps extension paths to info arrays */
	private static $extensionInfo = null;

	/** @var int[][][] Cache for self::filterIDs() */
	private static $filterIDsCache = [];

	/** $var array Map of web UI block messages to corresponding API messages and codes */
	private static $blockMsgMap = [
		'blockedtext' => [ 'apierror-blocked', 'blocked' ],
		'blockedtext-partial' => [ 'apierror-blocked', 'blocked' ],
		'autoblockedtext' => [ 'apierror-autoblocked', 'autoblocked' ],
		'systemblockedtext' => [ 'apierror-systemblocked', 'blocked' ],
	];

	/** @var ApiMain */
	private $mMainModule;
	/** @var string */
	private $mModuleName, $mModulePrefix;
	private $mReplicaDB = null;
	private $mParamCache = [];
	/** @var array|null|bool */
	private $mModuleSource = false;

	/**
	 * @param ApiMain $mainModule
	 * @param string $moduleName Name of this module
	 * @param string $modulePrefix Prefix to use for parameter names
	 */
	public function __construct( ApiMain $mainModule, $moduleName, $modulePrefix = '' ) {
		$this->mMainModule = $mainModule;
		$this->mModuleName = $moduleName;
		$this->mModulePrefix = $modulePrefix;

		if ( !$this->isMain() ) {
			$this->setContext( $mainModule->getContext() );
		}
	}

	/************************************************************************//**
	 * @name   Methods to implement
	 * @{
	 */

	/**
	 * Evaluates the parameters, performs the requested query, and sets up
	 * the result. Concrete implementations of ApiBase must override this
	 * method to provide whatever functionality their module offers.
	 * Implementations must not produce any output on their own and are not
	 * expected to handle any errors.
	 *
	 * The execute() method will be invoked directly by ApiMain immediately
	 * before the result of the module is output. Aside from the
	 * constructor, implementations should assume that no other methods
	 * will be called externally on the module before the result is
	 * processed.
	 *
	 * The result data should be stored in the ApiResult object available
	 * through getResult().
	 */
	abstract public function execute();

	/**
	 * Get the module manager, or null if this module has no sub-modules
	 * @since 1.21
	 * @return ApiModuleManager
	 */
	public function getModuleManager() {
		return null;
	}

	/**
	 * If the module may only be used with a certain format module,
	 * it should override this method to return an instance of that formatter.
	 * A value of null means the default format will be used.
	 * @note Do not use this just because you don't want to support non-json
	 * formats. This should be used only when there is a fundamental
	 * requirement for a specific format.
	 * @return mixed Instance of a derived class of ApiFormatBase, or null
	 */
	public function getCustomPrinter() {
		return null;
	}

	/**
	 * Returns usage examples for this module.
	 *
	 * Return value has query strings as keys, with values being either strings
	 * (message key), arrays (message key + parameter), or Message objects.
	 *
	 * Do not call this base class implementation when overriding this method.
	 *
	 * @since 1.25
	 * @return array
	 */
	protected function getExamplesMessages() {
		return [];
	}

	/**
	 * Return links to more detailed help pages about the module.
	 * @since 1.25, returning boolean false is deprecated
	 * @return string|array
	 */
	public function getHelpUrls() {
		return [];
	}

	/**
	 * Returns an array of allowed parameters (parameter name) => (default
	 * value) or (parameter name) => (array with PARAM_* constants as keys)
	 * Don't call this function directly: use getFinalParams() to allow
	 * hooks to modify parameters as needed.
	 *
	 * Some derived classes may choose to handle an integer $flags parameter
	 * in the overriding methods. Callers of this method can pass zero or
	 * more OR-ed flags like GET_VALUES_FOR_HELP.
	 *
	 * @return array
	 */
	protected function getAllowedParams( /* $flags = 0 */ ) {
		// int $flags is not declared because it causes "Strict standards"
		// warning. Most derived classes do not implement it.
		return [];
	}

	/**
	 * Indicates if this module needs maxlag to be checked
	 * @return bool
	 */
	public function shouldCheckMaxlag() {
		return true;
	}

	/**
	 * Indicates whether this module requires read rights
	 * @return bool
	 */
	public function isReadMode() {
		return true;
	}

	/**
	 * Indicates whether this module requires write mode
	 *
	 * This should return true for modules that may require synchronous database writes.
	 * Modules that do not need such writes should also not rely on master database access,
	 * since only read queries are needed and each master DB is a single point of failure.
	 * Additionally, requests that only need replica DBs can be efficiently routed to any
	 * datacenter via the Promise-Non-Write-API-Action header.
	 *
	 * @return bool
	 */
	public function isWriteMode() {
		return false;
	}

	/**
	 * Indicates whether this module must be called with a POST request
	 * @return bool
	 */
	public function mustBePosted() {
		return $this->needsToken() !== false;
	}

	/**
	 * Indicates whether this module is deprecated
	 * @since 1.25
	 * @return bool
	 */
	public function isDeprecated() {
		return false;
	}

	/**
	 * Indicates whether this module is "internal"
	 * Internal API modules are not (yet) intended for 3rd party use and may be unstable.
	 * @since 1.25
	 * @return bool
	 */
	public function isInternal() {
		return false;
	}

	/**
	 * Returns the token type this module requires in order to execute.
	 *
	 * Modules are strongly encouraged to use the core 'csrf' type unless they
	 * have specialized security needs. If the token type is not one of the
	 * core types, you must use the ApiQueryTokensRegisterTypes hook to
	 * register it.
	 *
	 * Returning a non-falsey value here will force the addition of an
	 * appropriate 'token' parameter in self::getFinalParams(). Also,
	 * self::mustBePosted() must return true when tokens are used.
	 *
	 * In previous versions of MediaWiki, true was a valid return value.
	 * Returning true will generate errors indicating that the API module needs
	 * updating.
	 *
	 * @return string|false
	 */
	public function needsToken() {
		return false;
	}

	/**
	 * Fetch the salt used in the Web UI corresponding to this module.
	 *
	 * Only override this if the Web UI uses a token with a non-constant salt.
	 *
	 * @since 1.24
	 * @param array $params All supplied parameters for the module
	 * @return string|array|null
	 */
	protected function getWebUITokenSalt( array $params ) {
		return null;
	}

	/**
	 * Returns data for HTTP conditional request mechanisms.
	 *
	 * @since 1.26
	 * @param string $condition Condition being queried:
	 *  - last-modified: Return a timestamp representing the maximum of the
	 *    last-modified dates for all resources involved in the request. See
	 *    RFC 7232 § 2.2 for semantics.
	 *  - etag: Return an entity-tag representing the state of all resources involved
	 *    in the request. Quotes must be included. See RFC 7232 § 2.3 for semantics.
	 * @return string|bool|null As described above, or null if no value is available.
	 */
	public function getConditionalRequestData( $condition ) {
		return null;
	}

	/**@}*/

	/************************************************************************//**
	 * @name   Data access methods
	 * @{
	 */

	/**
	 * Get the name of the module being executed by this instance
	 * @return string
	 */
	public function getModuleName() {
		return $this->mModuleName;
	}

	/**
	 * Get parameter prefix (usually two letters or an empty string).
	 * @return string
	 */
	public function getModulePrefix() {
		return $this->mModulePrefix;
	}

	/**
	 * Get the main module
	 * @return ApiMain
	 */
	public function getMain() {
		return $this->mMainModule;
	}

	/**
	 * Returns true if this module is the main module ($this === $this->mMainModule),
	 * false otherwise.
	 * @return bool
	 */
	public function isMain() {
		return $this === $this->mMainModule;
	}

	/**
	 * Get the parent of this module
	 * @since 1.25
	 * @return ApiBase|null
	 */
	public function getParent() {
		return $this->isMain() ? null : $this->getMain();
	}

	/**
	 * Returns true if the current request breaks the same-origin policy.
	 *
	 * For example, json with callbacks.
	 *
	 * https://en.wikipedia.org/wiki/Same-origin_policy
	 *
	 * @since 1.25
	 * @return bool
	 */
	public function lacksSameOriginSecurity() {
		// Main module has this method overridden
		// Safety - avoid infinite loop:
		if ( $this->isMain() ) {
			self::dieDebug( __METHOD__, 'base method was called on main module.' );
		}

		return $this->getMain()->lacksSameOriginSecurity();
	}

	/**
	 * Get the path to this module
	 *
	 * @since 1.25
	 * @return string
	 */
	public function getModulePath() {
		if ( $this->isMain() ) {
			return 'main';
		} elseif ( $this->getParent()->isMain() ) {
			return $this->getModuleName();
		} else {
			return $this->getParent()->getModulePath() . '+' . $this->getModuleName();
		}
	}

	/**
	 * Get a module from its module path
	 *
	 * @since 1.25
	 * @param string $path
	 * @return ApiBase|null
	 * @throws ApiUsageException
	 */
	public function getModuleFromPath( $path ) {
		$module = $this->getMain();
		if ( $path === 'main' ) {
			return $module;
		}

		$parts = explode( '+', $path );
		if ( count( $parts ) === 1 ) {
			// In case the '+' was typed into URL, it resolves as a space
			$parts = explode( ' ', $path );
		}

		$count = count( $parts );
		for ( $i = 0; $i < $count; $i++ ) {
			$parent = $module;
			$manager = $parent->getModuleManager();
			if ( $manager === null ) {
				$errorPath = implode( '+', array_slice( $parts, 0, $i ) );
				$this->dieWithError( [ 'apierror-badmodule-nosubmodules', $errorPath ], 'badmodule' );
			}
			$module = $manager->getModule( $parts[$i] );

			if ( $module === null ) {
				$errorPath = $i ? implode( '+', array_slice( $parts, 0, $i ) ) : $parent->getModuleName();
				$this->dieWithError(
					[ 'apierror-badmodule-badsubmodule', $errorPath, wfEscapeWikiText( $parts[$i] ) ],
					'badmodule'
				);
			}
		}

		return $module;
	}

	/**
	 * Get the result object
	 * @return ApiResult
	 */
	public function getResult() {
		// Main module has getResult() method overridden
		// Safety - avoid infinite loop:
		if ( $this->isMain() ) {
			self::dieDebug( __METHOD__, 'base method was called on main module. ' );
		}

		return $this->getMain()->getResult();
	}

	/**
	 * Get the error formatter
	 * @return ApiErrorFormatter
	 */
	public function getErrorFormatter() {
		// Main module has getErrorFormatter() method overridden
		// Safety - avoid infinite loop:
		if ( $this->isMain() ) {
			self::dieDebug( __METHOD__, 'base method was called on main module. ' );
		}

		return $this->getMain()->getErrorFormatter();
	}

	/**
	 * Gets a default replica DB connection object
	 * @return IDatabase
	 */
	protected function getDB() {
		if ( !isset( $this->mReplicaDB ) ) {
			$this->mReplicaDB = wfGetDB( DB_REPLICA, 'api' );
		}

		return $this->mReplicaDB;
	}

	/**
	 * Get the continuation manager
	 * @return ApiContinuationManager|null
	 */
	public function getContinuationManager() {
		// Main module has getContinuationManager() method overridden
		// Safety - avoid infinite loop:
		if ( $this->isMain() ) {
			self::dieDebug( __METHOD__, 'base method was called on main module. ' );
		}

		return $this->getMain()->getContinuationManager();
	}

	/**
	 * Set the continuation manager
	 * @param ApiContinuationManager|null $manager
	 */
	public function setContinuationManager( ApiContinuationManager $manager = null ) {
		// Main module has setContinuationManager() method overridden
		// Safety - avoid infinite loop:
		if ( $this->isMain() ) {
			self::dieDebug( __METHOD__, 'base method was called on main module. ' );
		}

		$this->getMain()->setContinuationManager( $manager );
	}

	/**@}*/

	/************************************************************************//**
	 * @name   Parameter handling
	 * @{
	 */

	/**
	 * Indicate if the module supports dynamically-determined parameters that
	 * cannot be included in self::getAllowedParams().
	 * @return string|array|Message|null Return null if the module does not
	 *  support additional dynamic parameters, otherwise return a message
	 *  describing them.
	 */
	public function dynamicParameterDocumentation() {
		return null;
	}

	/**
	 * This method mangles parameter name based on the prefix supplied to the constructor.
	 * Override this method to change parameter name during runtime
	 * @param string|string[] $paramName Parameter name
	 * @return string|string[] Prefixed parameter name
	 * @since 1.29 accepts an array of strings
	 */
	public function encodeParamName( $paramName ) {
		if ( is_array( $paramName ) ) {
			return array_map( function ( $name ) {
				return $this->mModulePrefix . $name;
			}, $paramName );
		} else {
			return $this->mModulePrefix . $paramName;
		}
	}

	/**
	 * Using getAllowedParams(), this function makes an array of the values
	 * provided by the user, with key being the name of the variable, and
	 * value - validated value from user or default. limits will not be
	 * parsed if $parseLimit is set to false; use this when the max
	 * limit is not definitive yet, e.g. when getting revisions.
	 * @param bool|array $options If a boolean, uses that as the value for 'parseLimit'
	 *  - parseLimit: (bool, default true) Whether to parse the 'max' value for limit types
	 *  - safeMode: (bool, default false) If true, avoid throwing for parameter validation errors.
	 *    Returned parameter values might be ApiUsageException instances.
	 * @return array
	 */
	public function extractRequestParams( $options = [] ) {
		if ( is_bool( $options ) ) {
			$options = [ 'parseLimit' => $options ];
		}
		$options += [
			'parseLimit' => true,
			'safeMode' => false,
		];

		$parseLimit = (bool)$options['parseLimit'];

		// Cache parameters, for performance and to avoid T26564.
		if ( !isset( $this->mParamCache[$parseLimit] ) ) {
			$params = $this->getFinalParams() ?: [];
			$results = [];
			$warned = [];

			// Process all non-templates and save templates for secondary
			// processing.
			$toProcess = [];
			foreach ( $params as $paramName => $paramSettings ) {
				if ( isset( $paramSettings[self::PARAM_TEMPLATE_VARS] ) ) {
					$toProcess[] = [ $paramName, $paramSettings[self::PARAM_TEMPLATE_VARS], $paramSettings ];
				} else {
					try {
						$results[$paramName] = $this->getParameterFromSettings(
							$paramName, $paramSettings, $parseLimit
						);
					} catch ( ApiUsageException $ex ) {
						$results[$paramName] = $ex;
					}
				}
			}

			// Now process all the templates by successively replacing the
			// placeholders with all client-supplied values.
			// This bit duplicates JavaScript logic in
			// ApiSandbox.PageLayout.prototype.updateTemplatedParams().
			// If you update this, see if that needs updating too.
			while ( $toProcess ) {
				list( $name, $targets, $settings ) = array_shift( $toProcess );

				foreach ( $targets as $placeholder => $target ) {
					if ( !array_key_exists( $target, $results ) ) {
						// The target wasn't processed yet, try the next one.
						// If all hit this case, the parameter has no expansions.
						continue;
					}
					if ( !is_array( $results[$target] ) || !$results[$target] ) {
						// The target was processed but has no (valid) values.
						// That means it has no expansions.
						break;
					}

					// Expand this target in the name and all other targets,
					// then requeue if there are more targets left or put in
					// $results if all are done.
					unset( $targets[$placeholder] );
					$placeholder = '{' . $placeholder . '}';
					// @phan-suppress-next-line PhanTypeNoAccessiblePropertiesForeach
					foreach ( $results[$target] as $value ) {
						if ( !preg_match( '/^[^{}]*$/', $value ) ) {
							// Skip values that make invalid parameter names.
							$encTargetName = $this->encodeParamName( $target );
							if ( !isset( $warned[$encTargetName][$value] ) ) {
								$warned[$encTargetName][$value] = true;
								$this->addWarning( [
									'apiwarn-ignoring-invalid-templated-value',
									wfEscapeWikiText( $encTargetName ),
									wfEscapeWikiText( $value ),
								] );
							}
							continue;
						}

						$newName = str_replace( $placeholder, $value, $name );
						if ( !$targets ) {
							try {
								$results[$newName] = $this->getParameterFromSettings( $newName, $settings, $parseLimit );
							} catch ( ApiUsageException $ex ) {
								$results[$newName] = $ex;
							}
						} else {
							$newTargets = [];
							foreach ( $targets as $k => $v ) {
								$newTargets[$k] = str_replace( $placeholder, $value, $v );
							}
							$toProcess[] = [ $newName, $newTargets, $settings ];
						}
					}
					break;
				}
			}

			$this->mParamCache[$parseLimit] = $results;
		}

		$ret = $this->mParamCache[$parseLimit];
		if ( !$options['safeMode'] ) {
			foreach ( $ret as $v ) {
				if ( $v instanceof ApiUsageException ) {
					throw $v;
				}
			}
		}

		return $this->mParamCache[$parseLimit];
	}

	/**
	 * Get a value for the given parameter
	 * @param string $paramName Parameter name
	 * @param bool $parseLimit See extractRequestParams()
	 * @return mixed Parameter value
	 */
	protected function getParameter( $paramName, $parseLimit = true ) {
		$ret = $this->extractRequestParams( [
			'parseLimit' => $parseLimit,
			'safeMode' => true,
		] )[$paramName];
		if ( $ret instanceof ApiUsageException ) {
			throw $ret;
		}
		return $ret;
	}

	/**
	 * Die if none or more than one of a certain set of parameters is set and not false.
	 *
	 * @param array $params User provided set of parameters, as from $this->extractRequestParams()
	 * @param string $required,... Names of parameters of which exactly one must be set
	 */
	public function requireOnlyOneParameter( $params, $required /*...*/ ) {
		$required = func_get_args();
		array_shift( $required );

		$intersection = array_intersect( array_keys( array_filter( $params,
			[ $this, 'parameterNotEmpty' ] ) ), $required );

		if ( count( $intersection ) > 1 ) {
			$this->dieWithError( [
				'apierror-invalidparammix',
				Message::listParam( array_map(
					function ( $p ) {
						return '<var>' . $this->encodeParamName( $p ) . '</var>';
					},
					array_values( $intersection )
				) ),
				count( $intersection ),
			] );
		} elseif ( count( $intersection ) == 0 ) {
			$this->dieWithError( [
				'apierror-missingparam-one-of',
				Message::listParam( array_map(
					function ( $p ) {
						return '<var>' . $this->encodeParamName( $p ) . '</var>';
					},
					array_values( $required )
				) ),
				count( $required ),
			], 'missingparam' );
		}
	}

	/**
	 * Die if more than one of a certain set of parameters is set and not false.
	 *
	 * @param array $params User provided set of parameters, as from $this->extractRequestParams()
	 * @param string $required,... Names of parameters of which at most one must be set
	 */
	public function requireMaxOneParameter( $params, $required /*...*/ ) {
		$required = func_get_args();
		array_shift( $required );

		$intersection = array_intersect( array_keys( array_filter( $params,
			[ $this, 'parameterNotEmpty' ] ) ), $required );

		if ( count( $intersection ) > 1 ) {
			$this->dieWithError( [
				'apierror-invalidparammix',
				Message::listParam( array_map(
					function ( $p ) {
						return '<var>' . $this->encodeParamName( $p ) . '</var>';
					},
					array_values( $intersection )
				) ),
				count( $intersection ),
			] );
		}
	}

	/**
	 * Die if none of a certain set of parameters is set and not false.
	 *
	 * @since 1.23
	 * @param array $params User provided set of parameters, as from $this->extractRequestParams()
	 * @param string $required,... Names of parameters of which at least one must be set
	 */
	public function requireAtLeastOneParameter( $params, $required /*...*/ ) {
		$required = func_get_args();
		array_shift( $required );

		$intersection = array_intersect(
			array_keys( array_filter( $params, [ $this, 'parameterNotEmpty' ] ) ),
			$required
		);

		if ( count( $intersection ) == 0 ) {
			$this->dieWithError( [
				'apierror-missingparam-at-least-one-of',
				Message::listParam( array_map(
					function ( $p ) {
						return '<var>' . $this->encodeParamName( $p ) . '</var>';
					},
					array_values( $required )
				) ),
				count( $required ),
			], 'missingparam' );
		}
	}

	/**
	 * Die if any of the specified parameters were found in the query part of
	 * the URL rather than the post body.
	 * @since 1.28
	 * @param string[] $params Parameters to check
	 * @param string $prefix Set to 'noprefix' to skip calling $this->encodeParamName()
	 */
	public function requirePostedParameters( $params, $prefix = 'prefix' ) {
		// Skip if $wgDebugAPI is set or we're in internal mode
		if ( $this->getConfig()->get( 'DebugAPI' ) || $this->getMain()->isInternalMode() ) {
			return;
		}

		$queryValues = $this->getRequest()->getQueryValues();
		$badParams = [];
		foreach ( $params as $param ) {
			if ( $prefix !== 'noprefix' ) {
				$param = $this->encodeParamName( $param );
			}
			if ( array_key_exists( $param, $queryValues ) ) {
				$badParams[] = $param;
			}
		}

		if ( $badParams ) {
			$this->dieWithError(
				[ 'apierror-mustpostparams', implode( ', ', $badParams ), count( $badParams ) ]
			);
		}
	}

	/**
	 * Callback function used in requireOnlyOneParameter to check whether required parameters are set
	 *
	 * @param object $x Parameter to check is not null/false
	 * @return bool
	 */
	private function parameterNotEmpty( $x ) {
		return !is_null( $x ) && $x !== false;
	}

	/**
	 * Get a WikiPage object from a title or pageid param, if possible.
	 * Can die, if no param is set or if the title or page id is not valid.
	 *
	 * @param array $params User provided set of parameters, as from $this->extractRequestParams()
	 * @param bool|string $load Whether load the object's state from the database:
	 *        - false: don't load (if the pageid is given, it will still be loaded)
	 *        - 'fromdb': load from a replica DB
	 *        - 'fromdbmaster': load from the master database
	 * @return WikiPage
	 */
	public function getTitleOrPageId( $params, $load = false ) {
		$this->requireOnlyOneParameter( $params, 'title', 'pageid' );

		$pageObj = null;
		if ( isset( $params['title'] ) ) {
			$titleObj = Title::newFromText( $params['title'] );
			if ( !$titleObj || $titleObj->isExternal() ) {
				$this->dieWithError( [ 'apierror-invalidtitle', wfEscapeWikiText( $params['title'] ) ] );
			}
			if ( !$titleObj->canExist() ) {
				$this->dieWithError( 'apierror-pagecannotexist' );
			}
			$pageObj = WikiPage::factory( $titleObj );
			if ( $load !== false ) {
				$pageObj->loadPageData( $load );
			}
		} elseif ( isset( $params['pageid'] ) ) {
			if ( $load === false ) {
				$load = 'fromdb';
			}
			$pageObj = WikiPage::newFromID( $params['pageid'], $load );
			if ( !$pageObj ) {
				$this->dieWithError( [ 'apierror-nosuchpageid', $params['pageid'] ] );
			}
		}

		return $pageObj;
	}

	/**
	 * Get a Title object from a title or pageid param, if possible.
	 * Can die, if no param is set or if the title or page id is not valid.
	 *
	 * @since 1.29
	 * @param array $params User provided set of parameters, as from $this->extractRequestParams()
	 * @return Title
	 */
	public function getTitleFromTitleOrPageId( $params ) {
		$this->requireOnlyOneParameter( $params, 'title', 'pageid' );

		$titleObj = null;
		if ( isset( $params['title'] ) ) {
			$titleObj = Title::newFromText( $params['title'] );
			if ( !$titleObj || $titleObj->isExternal() ) {
				$this->dieWithError( [ 'apierror-invalidtitle', wfEscapeWikiText( $params['title'] ) ] );
			}
			return $titleObj;
		} elseif ( isset( $params['pageid'] ) ) {
			$titleObj = Title::newFromID( $params['pageid'] );
			if ( !$titleObj ) {
				$this->dieWithError( [ 'apierror-nosuchpageid', $params['pageid'] ] );
			}
		}

		return $titleObj;
	}

	/**
	 * Return true if we're to watch the page, false if not, null if no change.
	 * @param string $watchlist Valid values: 'watch', 'unwatch', 'preferences', 'nochange'
	 * @param Title $titleObj The page under consideration
	 * @param string|null $userOption The user option to consider when $watchlist=preferences.
	 *    If not set will use watchdefault always and watchcreations if $titleObj doesn't exist.
	 * @return bool
	 */
	protected function getWatchlistValue( $watchlist, $titleObj, $userOption = null ) {
		$userWatching = $this->getUser()->isWatched( $titleObj, User::IGNORE_USER_RIGHTS );

		switch ( $watchlist ) {
			case 'watch':
				return true;

			case 'unwatch':
				return false;

			case 'preferences':
				# If the user is already watching, don't bother checking
				if ( $userWatching ) {
					return true;
				}
				# If no user option was passed, use watchdefault and watchcreations
				if ( is_null( $userOption ) ) {
					return $this->getUser()->getBoolOption( 'watchdefault' ) ||
						$this->getUser()->getBoolOption( 'watchcreations' ) && !$titleObj->exists();
				}

				# Watch the article based on the user preference
				return $this->getUser()->getBoolOption( $userOption );

			case 'nochange':
				return $userWatching;

			default:
				return $userWatching;
		}
	}

	/**
	 * Using the settings determine the value for the given parameter
	 *
	 * @param string $paramName Parameter name
	 * @param array|mixed $paramSettings Default value or an array of settings
	 *  using PARAM_* constants.
	 * @param bool $parseLimit Whether to parse and validate 'limit' parameters
	 * @return mixed Parameter value
	 */
	protected function getParameterFromSettings( $paramName, $paramSettings, $parseLimit ) {
		// Some classes may decide to change parameter names
		$encParamName = $this->encodeParamName( $paramName );

		// Shorthand
		if ( !is_array( $paramSettings ) ) {
			$paramSettings = [
				self::PARAM_DFLT => $paramSettings,
			];
		}

		$default = $paramSettings[self::PARAM_DFLT] ?? null;
		$multi = $paramSettings[self::PARAM_ISMULTI] ?? false;
		$multiLimit1 = $paramSettings[self::PARAM_ISMULTI_LIMIT1] ?? null;
		$multiLimit2 = $paramSettings[self::PARAM_ISMULTI_LIMIT2] ?? null;
		$type = $paramSettings[self::PARAM_TYPE] ?? null;
		$dupes = $paramSettings[self::PARAM_ALLOW_DUPLICATES] ?? false;
		$deprecated = $paramSettings[self::PARAM_DEPRECATED] ?? false;
		$deprecatedValues = $paramSettings[self::PARAM_DEPRECATED_VALUES] ?? [];
		$required = $paramSettings[self::PARAM_REQUIRED] ?? false;
		$allowAll = $paramSettings[self::PARAM_ALL] ?? false;

		// When type is not given, and no choices, the type is the same as $default
		if ( !isset( $type ) ) {
			if ( isset( $default ) ) {
				$type = gettype( $default );
			} else {
				$type = 'NULL'; // allow everything
			}
		}

		if ( $type == 'password' || !empty( $paramSettings[self::PARAM_SENSITIVE] ) ) {
			$this->getMain()->markParamsSensitive( $encParamName );
		}

		if ( $type == 'boolean' ) {
			if ( isset( $default ) && $default !== false ) {
				// Having a default value of anything other than 'false' is not allowed
				self::dieDebug(
					__METHOD__,
					"Boolean param $encParamName's default is set to '$default'. " .
						'Boolean parameters must default to false.'
				);
			}

			$value = $this->getMain()->getCheck( $encParamName );
			$provided = $value;
		} elseif ( $type == 'upload' ) {
			if ( isset( $default ) ) {
				// Having a default value is not allowed
				self::dieDebug(
					__METHOD__,
					"File upload param $encParamName's default is set to " .
						"'$default'. File upload parameters may not have a default." );
			}
			if ( $multi ) {
				self::dieDebug( __METHOD__, "Multi-values not supported for $encParamName" );
			}
			$value = $this->getMain()->getUpload( $encParamName );
			$provided = $value->exists();
			if ( !$value->exists() ) {
				// This will get the value without trying to normalize it
				// (because trying to normalize a large binary file
				// accidentally uploaded as a field fails spectacularly)
				$value = $this->getMain()->getRequest()->unsetVal( $encParamName );
				if ( $value !== null ) {
					$this->dieWithError(
						[ 'apierror-badupload', $encParamName ],
						"badupload_{$encParamName}"
					);
				}
			}
		} else {
			$value = $this->getMain()->getVal( $encParamName, $default );
			$provided = $this->getMain()->getCheck( $encParamName );

			if ( isset( $value ) && $type == 'namespace' ) {
				$type = MWNamespace::getValidNamespaces();
				if ( isset( $paramSettings[self::PARAM_EXTRA_NAMESPACES] ) &&
					is_array( $paramSettings[self::PARAM_EXTRA_NAMESPACES] )
				) {
					$type = array_merge( $type, $paramSettings[self::PARAM_EXTRA_NAMESPACES] );
				}
				// Namespace parameters allow ALL_DEFAULT_STRING to be used to
				// specify all namespaces irrespective of PARAM_ALL.
				$allowAll = true;
			}
			if ( isset( $value ) && $type == 'submodule' ) {
				if ( isset( $paramSettings[self::PARAM_SUBMODULE_MAP] ) ) {
					$type = array_keys( $paramSettings[self::PARAM_SUBMODULE_MAP] );
				} else {
					$type = $this->getModuleManager()->getNames( $paramName );
				}
			}

			$request = $this->getMain()->getRequest();
			$rawValue = $request->getRawVal( $encParamName );
			if ( $rawValue === null ) {
				$rawValue = $default;
			}

			// Preserve U+001F for self::parseMultiValue(), or error out if that won't be called
			if ( isset( $value ) && substr( $rawValue, 0, 1 ) === "\x1f" ) {
				if ( $multi ) {
					// This loses the potential checkTitleEncoding() transformation done by
					// WebRequest for $_GET. Let's call that a feature.
					$value = implode( "\x1f", $request->normalizeUnicode( explode( "\x1f", $rawValue ) ) );
				} else {
					$this->dieWithError( 'apierror-badvalue-notmultivalue', 'badvalue_notmultivalue' );
				}
			}

			// Check for NFC normalization, and warn
			if ( $rawValue !== $value ) {
				$this->handleParamNormalization( $paramName, $value, $rawValue );
			}
		}

		$allSpecifier = ( is_string( $allowAll ) ? $allowAll : self::ALL_DEFAULT_STRING );
		if ( $allowAll && $multi && is_array( $type ) && in_array( $allSpecifier, $type, true ) ) {
			self::dieDebug(
				__METHOD__,
				"For param $encParamName, PARAM_ALL collides with a possible value" );
		}
		if ( isset( $value ) && ( $multi || is_array( $type ) ) ) {
			$value = $this->parseMultiValue(
				$encParamName,
				$value,
				$multi,
				is_array( $type ) ? $type : null,
				$allowAll ? $allSpecifier : null,
				$multiLimit1,
				$multiLimit2
			);
		}

		if ( isset( $value ) ) {
			// More validation only when choices were not given
			// choices were validated in parseMultiValue()
			if ( !is_array( $type ) ) {
				switch ( $type ) {
					case 'NULL': // nothing to do
						break;
					case 'string':
					case 'text':
					case 'password':
						if ( $required && $value === '' ) {
							$this->dieWithError( [ 'apierror-missingparam', $encParamName ] );
						}
						break;
					case 'integer': // Force everything using intval() and optionally validate limits
						$min = $paramSettings[self::PARAM_MIN] ?? null;
						$max = $paramSettings[self::PARAM_MAX] ?? null;
						$enforceLimits = $paramSettings[self::PARAM_RANGE_ENFORCE] ?? false;

						if ( is_array( $value ) ) {
							$value = array_map( 'intval', $value );
							if ( !is_null( $min ) || !is_null( $max ) ) {
								foreach ( $value as &$v ) {
									$this->validateLimit( $paramName, $v, $min, $max, null, $enforceLimits );
								}
							}
						} else {
							$value = (int)$value;
							if ( !is_null( $min ) || !is_null( $max ) ) {
								$this->validateLimit( $paramName, $value, $min, $max, null, $enforceLimits );
							}
						}
						break;
					case 'limit':
						if ( !$parseLimit ) {
							// Don't do any validation whatsoever
							break;
						}
						if ( !isset( $paramSettings[self::PARAM_MAX] )
							|| !isset( $paramSettings[self::PARAM_MAX2] )
						) {
							self::dieDebug(
								__METHOD__,
								"MAX1 or MAX2 are not defined for the limit $encParamName"
							);
						}
						if ( $multi ) {
							self::dieDebug( __METHOD__, "Multi-values not supported for $encParamName" );
						}
						$min = $paramSettings[self::PARAM_MIN] ?? 0;
						if ( $value == 'max' ) {
							$value = $this->getMain()->canApiHighLimits()
								? $paramSettings[self::PARAM_MAX2]
								: $paramSettings[self::PARAM_MAX];
							$this->getResult()->addParsedLimit( $this->getModuleName(), $value );
						} else {
							$value = (int)$value;
							$this->validateLimit(
								$paramName,
								$value,
								$min,
								$paramSettings[self::PARAM_MAX],
								$paramSettings[self::PARAM_MAX2]
							);
						}
						break;
					case 'boolean':
						if ( $multi ) {
							self::dieDebug( __METHOD__, "Multi-values not supported for $encParamName" );
						}
						break;
					case 'timestamp':
						if ( is_array( $value ) ) {
							foreach ( $value as $key => $val ) {
								$value[$key] = $this->validateTimestamp( $val, $encParamName );
							}
						} else {
							$value = $this->validateTimestamp( $value, $encParamName );
						}
						break;
					case 'user':
						if ( is_array( $value ) ) {
							foreach ( $value as $key => $val ) {
								$value[$key] = $this->validateUser( $val, $encParamName );
							}
						} else {
							$value = $this->validateUser( $value, $encParamName );
						}
						break;
					case 'upload': // nothing to do
						break;
					case 'tags':
						// If change tagging was requested, check that the tags are valid.
						if ( !is_array( $value ) && !$multi ) {
							$value = [ $value ];
						}
						$tagsStatus = ChangeTags::canAddTagsAccompanyingChange( $value );
						if ( !$tagsStatus->isGood() ) {
							$this->dieStatus( $tagsStatus );
						}
						break;
					default:
						self::dieDebug( __METHOD__, "Param $encParamName's type is unknown - $type" );
				}
			}

			// Throw out duplicates if requested
			if ( !$dupes && is_array( $value ) ) {
				$value = array_unique( $value );
			}

			if ( in_array( $type, [ 'NULL', 'string', 'text', 'password' ], true ) ) {
				foreach ( (array)$value as $val ) {
					if ( isset( $paramSettings[self::PARAM_MAX_BYTES] )
						&& strlen( $val ) > $paramSettings[self::PARAM_MAX_BYTES]
					) {
						$this->dieWithError( [ 'apierror-maxbytes', $encParamName,
							$paramSettings[self::PARAM_MAX_BYTES] ] );
					}
					if ( isset( $paramSettings[self::PARAM_MAX_CHARS] )
						&& mb_strlen( $val, 'UTF-8' ) > $paramSettings[self::PARAM_MAX_CHARS]
					) {
						$this->dieWithError( [ 'apierror-maxchars', $encParamName,
							$paramSettings[self::PARAM_MAX_CHARS] ] );
					}
				}
			}

			// Set a warning if a deprecated parameter has been passed
			if ( $deprecated && $provided ) {
				$feature = $encParamName;
				$m = $this;
				while ( !$m->isMain() ) {
					$p = $m->getParent();
					$name = $m->getModuleName();
					$param = $p->encodeParamName( $p->getModuleManager()->getModuleGroup( $name ) );
					$feature = "{$param}={$name}&{$feature}";
					$m = $p;
				}
				$this->addDeprecation( [ 'apiwarn-deprecation-parameter', $encParamName ], $feature );
			}

			// Set a warning if a deprecated parameter value has been passed
			$usedDeprecatedValues = $deprecatedValues && $provided
				? array_intersect( array_keys( $deprecatedValues ), (array)$value )
				: [];
			if ( $usedDeprecatedValues ) {
				$feature = "$encParamName=";
				$m = $this;
				while ( !$m->isMain() ) {
					$p = $m->getParent();
					$name = $m->getModuleName();
					$param = $p->encodeParamName( $p->getModuleManager()->getModuleGroup( $name ) );
					$feature = "{$param}={$name}&{$feature}";
					$m = $p;
				}
				foreach ( $usedDeprecatedValues as $v ) {
					$msg = $deprecatedValues[$v];
					if ( $msg === true ) {
						$msg = [ 'apiwarn-deprecation-parameter', "$encParamName=$v" ];
					}
					$this->addDeprecation( $msg, "$feature$v" );
				}
			}
		} elseif ( $required ) {
			$this->dieWithError( [ 'apierror-missingparam', $encParamName ] );
		}

		return $value;
	}

	/**
	 * Handle when a parameter was Unicode-normalized
	 * @since 1.28
	 * @param string $paramName Unprefixed parameter name
	 * @param string $value Input that will be used.
	 * @param string $rawValue Input before normalization.
	 */
	protected function handleParamNormalization( $paramName, $value, $rawValue ) {
		$encParamName = $this->encodeParamName( $paramName );
		$this->addWarning( [ 'apiwarn-badutf8', $encParamName ] );
	}

	/**
	 * Split a multi-valued parameter string, like explode()
	 * @since 1.28
	 * @param string $value
	 * @param int $limit
	 * @return string[]
	 */
	protected function explodeMultiValue( $value, $limit ) {
		if ( substr( $value, 0, 1 ) === "\x1f" ) {
			$sep = "\x1f";
			$value = substr( $value, 1 );
		} else {
			$sep = '|';
		}

		return explode( $sep, $value, $limit );
	}

	/**
	 * Return an array of values that were given in a 'a|b|c' notation,
	 * after it optionally validates them against the list allowed values.
	 *
	 * @param string $valueName The name of the parameter (for error
	 *  reporting)
	 * @param mixed $value The value being parsed
	 * @param bool $allowMultiple Can $value contain more than one value
	 *  separated by '|'?
	 * @param string[]|null $allowedValues An array of values to check against. If
	 *  null, all values are accepted.
	 * @param string|null $allSpecifier String to use to specify all allowed values, or null
	 *  if this behavior should not be allowed
	 * @param int|null $limit1 Maximum number of values, for normal users.
	 * @param int|null $limit2 Maximum number of values, for users with the apihighlimits right.
	 * @return string|string[] (allowMultiple ? an_array_of_values : a_single_value)
	 */
	protected function parseMultiValue( $valueName, $value, $allowMultiple, $allowedValues,
		$allSpecifier = null, $limit1 = null, $limit2 = null
	) {
		if ( ( $value === '' || $value === "\x1f" ) && $allowMultiple ) {
			return [];
		}
		$limit1 = $limit1 ?: self::LIMIT_SML1;
		$limit2 = $limit2 ?: self::LIMIT_SML2;

		// This is a bit awkward, but we want to avoid calling canApiHighLimits()
		// because it unstubs $wgUser
		$valuesList = $this->explodeMultiValue( $value, $limit2 + 1 );
		$sizeLimit = count( $valuesList ) > $limit1 && $this->mMainModule->canApiHighLimits()
			? $limit2
			: $limit1;

		if ( $allowMultiple && is_array( $allowedValues ) && $allSpecifier &&
			count( $valuesList ) === 1 && $valuesList[0] === $allSpecifier
		) {
			return $allowedValues;
		}

		if ( count( $valuesList ) > $sizeLimit ) {
			$this->dieWithError(
				[ 'apierror-toomanyvalues', $valueName, $sizeLimit ],
				"too-many-$valueName"
			);
		}

		if ( !$allowMultiple && count( $valuesList ) != 1 ) {
			// T35482 - Allow entries with | in them for non-multiple values
			if ( in_array( $value, $allowedValues, true ) ) {
				return $value;
			}

			$values = array_map( function ( $v ) {
				return '<kbd>' . wfEscapeWikiText( $v ) . '</kbd>';
			}, $allowedValues );
			$this->dieWithError( [
				'apierror-multival-only-one-of',
				$valueName,
				Message::listParam( $values ),
				count( $values ),
			], "multival_$valueName" );
		}

		if ( is_array( $allowedValues ) ) {
			// Check for unknown values
			$unknown = array_map( 'wfEscapeWikiText', array_diff( $valuesList, $allowedValues ) );
			if ( count( $unknown ) ) {
				if ( $allowMultiple ) {
					$this->addWarning( [
						'apiwarn-unrecognizedvalues',
						$valueName,
						Message::listParam( $unknown, 'comma' ),
						count( $unknown ),
					] );
				} else {
					$this->dieWithError(
						[ 'apierror-unrecognizedvalue', $valueName, wfEscapeWikiText( $valuesList[0] ) ],
						"unknown_$valueName"
					);
				}
			}
			// Now throw them out
			$valuesList = array_intersect( $valuesList, $allowedValues );
		}

		return $allowMultiple ? $valuesList : $valuesList[0];
	}

	/**
	 * Validate the value against the minimum and user/bot maximum limits.
	 * Prints usage info on failure.
	 * @param string $paramName Parameter name
	 * @param int &$value Parameter value
	 * @param int|null $min Minimum value
	 * @param int|null $max Maximum value for users
	 * @param int|null $botMax Maximum value for sysops/bots
	 * @param bool $enforceLimits Whether to enforce (die) if value is outside limits
	 */
	protected function validateLimit( $paramName, &$value, $min, $max, $botMax = null,
		$enforceLimits = false
	) {
		if ( !is_null( $min ) && $value < $min ) {
			$msg = ApiMessage::create(
				[ 'apierror-integeroutofrange-belowminimum',
					$this->encodeParamName( $paramName ), $min, $value ],
				'integeroutofrange',
				[ 'min' => $min, 'max' => $max, 'botMax' => $botMax ?: $max ]
			);
			$this->warnOrDie( $msg, $enforceLimits );
			$value = $min;
		}

		// Minimum is always validated, whereas maximum is checked only if not
		// running in internal call mode
		if ( $this->getMain()->isInternalMode() ) {
			return;
		}

		// Optimization: do not check user's bot status unless really needed -- skips db query
		// assumes $botMax >= $max
		if ( !is_null( $max ) && $value > $max ) {
			if ( !is_null( $botMax ) && $this->getMain()->canApiHighLimits() ) {
				if ( $value > $botMax ) {
					$msg = ApiMessage::create(
						[ 'apierror-integeroutofrange-abovebotmax',
							$this->encodeParamName( $paramName ), $botMax, $value ],
						'integeroutofrange',
						[ 'min' => $min, 'max' => $max, 'botMax' => $botMax ?: $max ]
					);
					$this->warnOrDie( $msg, $enforceLimits );
					$value = $botMax;
				}
			} else {
				$msg = ApiMessage::create(
					[ 'apierror-integeroutofrange-abovemax',
						$this->encodeParamName( $paramName ), $max, $value ],
					'integeroutofrange',
					[ 'min' => $min, 'max' => $max, 'botMax' => $botMax ?: $max ]
				);
				$this->warnOrDie( $msg, $enforceLimits );
				$value = $max;
			}
		}
	}

	/**
	 * Validate and normalize parameters of type 'timestamp'
	 * @param string $value Parameter value
	 * @param string $encParamName Parameter name
	 * @return string Validated and normalized parameter
	 */
	protected function validateTimestamp( $value, $encParamName ) {
		// Confusing synonyms for the current time accepted by wfTimestamp()
		// (wfTimestamp() also accepts various non-strings and the string of 14
		// ASCII NUL bytes, but those can't get here)
		if ( !$value ) {
			$this->addDeprecation(
				[ 'apiwarn-unclearnowtimestamp', $encParamName, wfEscapeWikiText( $value ) ],
				'unclear-"now"-timestamp'
			);
			return wfTimestamp( TS_MW );
		}

		// Explicit synonym for the current time
		if ( $value === 'now' ) {
			return wfTimestamp( TS_MW );
		}

		$timestamp = wfTimestamp( TS_MW, $value );
		if ( $timestamp === false ) {
			$this->dieWithError(
				[ 'apierror-badtimestamp', $encParamName, wfEscapeWikiText( $value ) ],
				"badtimestamp_{$encParamName}"
			);
		}

		return $timestamp;
	}

	/**
	 * Validate the supplied token.
	 *
	 * @since 1.24
	 * @param string $token Supplied token
	 * @param array $params All supplied parameters for the module
	 * @return bool
	 * @throws MWException
	 */
	final public function validateToken( $token, array $params ) {
		$tokenType = $this->needsToken();
		$salts = ApiQueryTokens::getTokenTypeSalts();
		if ( !isset( $salts[$tokenType] ) ) {
			throw new MWException(
				"Module '{$this->getModuleName()}' tried to use token type '$tokenType' " .
					'without registering it'
			);
		}

		$tokenObj = ApiQueryTokens::getToken(
			$this->getUser(), $this->getRequest()->getSession(), $salts[$tokenType]
		);
		if ( $tokenObj->match( $token ) ) {
			return true;
		}

		$webUiSalt = $this->getWebUITokenSalt( $params );
		if ( $webUiSalt !== null && $this->getUser()->matchEditToken(
			$token,
			$webUiSalt,
			$this->getRequest()
		) ) {
			return true;
		}

		return false;
	}

	/**
	 * Validate and normalize parameters of type 'user'
	 * @param string $value Parameter value
	 * @param string $encParamName Parameter name
	 * @return string Validated and normalized parameter
	 */
	private function validateUser( $value, $encParamName ) {
		if ( ExternalUserNames::isExternal( $value ) && User::newFromName( $value, false ) ) {
			return $value;
		}

		$name = User::getCanonicalName( $value, 'valid' );
		if ( $name !== false ) {
			return $name;
		}

		if (
			// We allow ranges as well, for blocks.
			IP::isIPAddress( $value ) ||
			// See comment for User::isIP.  We don't just call that function
			// here because it also returns true for things like
			// 300.300.300.300 that are neither valid usernames nor valid IP
			// addresses.
			preg_match(
				'/^' . RE_IP_BYTE . '\.' . RE_IP_BYTE . '\.' . RE_IP_BYTE . '\.xxx$/',
				$value
			)
		) {
			return IP::sanitizeIP( $value );
		}

		$this->dieWithError(
			[ 'apierror-baduser', $encParamName, wfEscapeWikiText( $value ) ],
			"baduser_{$encParamName}"
		);
	}

	/**@}*/

	/************************************************************************//**
	 * @name   Utility methods
	 * @{
	 */

	/**
	 * Set a watch (or unwatch) based the based on a watchlist parameter.
	 * @param string $watch Valid values: 'watch', 'unwatch', 'preferences', 'nochange'
	 * @param Title $titleObj The article's title to change
	 * @param string|null $userOption The user option to consider when $watch=preferences
	 */
	protected function setWatch( $watch, $titleObj, $userOption = null ) {
		$value = $this->getWatchlistValue( $watch, $titleObj, $userOption );
		if ( $value === null ) {
			return;
		}

		WatchAction::doWatchOrUnwatch( $value, $titleObj, $this->getUser() );
	}

	/**
	 * Gets the user for whom to get the watchlist
	 *
	 * @param array $params
	 * @return User
	 */
	public function getWatchlistUser( $params ) {
		if ( !is_null( $params['owner'] ) && !is_null( $params['token'] ) ) {
			$user = User::newFromName( $params['owner'], false );
			if ( !( $user && $user->getId() ) ) {
				$this->dieWithError(
					[ 'nosuchusershort', wfEscapeWikiText( $params['owner'] ) ], 'bad_wlowner'
				);
			}
			$token = $user->getOption( 'watchlisttoken' );
			if ( $token == '' || !hash_equals( $token, $params['token'] ) ) {
				$this->dieWithError( 'apierror-bad-watchlist-token', 'bad_wltoken' );
			}
		} else {
			if ( !$this->getUser()->isLoggedIn() ) {
				$this->dieWithError( 'watchlistanontext', 'notloggedin' );
			}
			$this->checkUserRightsAny( 'viewmywatchlist' );
			$user = $this->getUser();
		}

		return $user;
	}

	/**
	 * Create a Message from a string or array
	 *
	 * A string is used as a message key. An array has the message key as the
	 * first value and message parameters as subsequent values.
	 *
	 * @since 1.25
	 * @param string|array|Message $msg
	 * @param IContextSource $context
	 * @param array|null $params
	 * @return Message|null
	 */
	public static function makeMessage( $msg, IContextSource $context, array $params = null ) {
		if ( is_string( $msg ) ) {
			$msg = wfMessage( $msg );
		} elseif ( is_array( $msg ) ) {
			$msg = wfMessage( ...$msg );
		}
		if ( !$msg instanceof Message ) {
			return null;
		}

		$msg->setContext( $context );
		if ( $params ) {
			$msg->params( $params );
		}

		return $msg;
	}

	/**
	 * Turn an array of message keys or key+param arrays into a Status
	 * @since 1.29
	 * @param array $errors
	 * @param User|null $user
	 * @return Status
	 */
	public function errorArrayToStatus( array $errors, User $user = null ) {
		if ( $user === null ) {
			$user = $this->getUser();
		}

		$status = Status::newGood();
		foreach ( $errors as $error ) {
			if ( !is_array( $error ) ) {
				$error = [ $error ];
			}
			if ( is_string( $error[0] ) && isset( self::$blockMsgMap[$error[0]] ) && $user->getBlock() ) {
				list( $msg, $code ) = self::$blockMsgMap[$error[0]];
				$status->fatal( ApiMessage::create( $msg, $code,
					[ 'blockinfo' => ApiQueryUserInfo::getBlockInfo( $user->getBlock() ) ]
				) );
			} else {
				$status->fatal( ...$error );
			}
		}
		return $status;
	}

	/**
	 * Add block info to block messages in a Status
	 * @since 1.33
	 * @param StatusValue $status
	 * @param User|null $user
	 */
	public function addBlockInfoToStatus( StatusValue $status, User $user = null ) {
		if ( $user === null ) {
			$user = $this->getUser();
		}

		foreach ( self::$blockMsgMap as $msg => list( $apiMsg, $code ) ) {
			if ( $status->hasMessage( $msg ) && $user->getBlock() ) {
				$status->replaceMessage( $msg, ApiMessage::create( $apiMsg, $code,
					[ 'blockinfo' => ApiQueryUserInfo::getBlockInfo( $user->getBlock() ) ]
				) );
			}
		}
	}

	/**
	 * Call wfTransactionalTimeLimit() if this request was POSTed
	 * @since 1.26
	 */
	protected function useTransactionalTimeLimit() {
		if ( $this->getRequest()->wasPosted() ) {
			wfTransactionalTimeLimit();
		}
	}

	/**
	 * Filter out-of-range values from a list of positive integer IDs
	 * @since 1.33
	 * @param array $fields Array of pairs of table and field to check
	 * @param (string|int)[] $ids IDs to filter. Strings in the array are
	 *  expected to be stringified ints.
	 * @return (string|int)[] Filtered IDs.
	 */
	protected function filterIDs( $fields, array $ids ) {
		$min = INF;
		$max = 0;
		foreach ( $fields as list( $table, $field ) ) {
			if ( isset( self::$filterIDsCache[$table][$field] ) ) {
				$row = self::$filterIDsCache[$table][$field];
			} else {
				$row = $this->getDB()->selectRow(
					$table,
					[
						'min_id' => "MIN($field)",
						'max_id' => "MAX($field)",
					],
					'',
					__METHOD__
				);
				self::$filterIDsCache[$table][$field] = $row;
			}
			$min = min( $min, $row->min_id );
			$max = max( $max, $row->max_id );
		}
		return array_filter( $ids, function ( $id ) use ( $min, $max ) {
			return ( is_int( $id ) && $id >= 0 || ctype_digit( $id ) )
				&& $id >= $min && $id <= $max;
		} );
	}

	/**@}*/

	/************************************************************************//**
	 * @name   Warning and error reporting
	 * @{
	 */

	/**
	 * Add a warning for this module.
	 *
	 * Users should monitor this section to notice any changes in API. Multiple
	 * calls to this function will result in multiple warning messages.
	 *
	 * If $msg is not an ApiMessage, the message code will be derived from the
	 * message key by stripping any "apiwarn-" or "apierror-" prefix.
	 *
	 * @since 1.29
	 * @param string|array|Message $msg See ApiErrorFormatter::addWarning()
	 * @param string|null $code See ApiErrorFormatter::addWarning()
	 * @param array|null $data See ApiErrorFormatter::addWarning()
	 */
	public function addWarning( $msg, $code = null, $data = null ) {
		$this->getErrorFormatter()->addWarning( $this->getModulePath(), $msg, $code, $data );
	}

	/**
	 * Add a deprecation warning for this module.
	 *
	 * A combination of $this->addWarning() and $this->logFeatureUsage()
	 *
	 * @since 1.29
	 * @param string|array|Message $msg See ApiErrorFormatter::addWarning()
	 * @param string|null $feature See ApiBase::logFeatureUsage()
	 * @param array|null $data See ApiErrorFormatter::addWarning()
	 */
	public function addDeprecation( $msg, $feature, $data = [] ) {
		$data = (array)$data;
		if ( $feature !== null ) {
			$data['feature'] = $feature;
			$this->logFeatureUsage( $feature );
		}
		$this->addWarning( $msg, 'deprecation', $data );

		// No real need to deduplicate here, ApiErrorFormatter does that for
		// us (assuming the hook is deterministic).
		$msgs = [ $this->msg( 'api-usage-mailinglist-ref' ) ];
		Hooks::run( 'ApiDeprecationHelp', [ &$msgs ] );
		if ( count( $msgs ) > 1 ) {
			$key = '$' . implode( ' $', range( 1, count( $msgs ) ) );
			$msg = ( new RawMessage( $key ) )->params( $msgs );
		} else {
			$msg = reset( $msgs );
		}
		$this->getMain()->addWarning( $msg, 'deprecation-help' );
	}

	/**
	 * Add an error for this module without aborting
	 *
	 * If $msg is not an ApiMessage, the message code will be derived from the
	 * message key by stripping any "apiwarn-" or "apierror-" prefix.
	 *
	 * @note If you want to abort processing, use self::dieWithError() instead.
	 * @since 1.29
	 * @param string|array|Message $msg See ApiErrorFormatter::addError()
	 * @param string|null $code See ApiErrorFormatter::addError()
	 * @param array|null $data See ApiErrorFormatter::addError()
	 */
	public function addError( $msg, $code = null, $data = null ) {
		$this->getErrorFormatter()->addError( $this->getModulePath(), $msg, $code, $data );
	}

	/**
	 * Add warnings and/or errors from a Status
	 *
	 * @note If you want to abort processing, use self::dieStatus() instead.
	 * @since 1.29
	 * @param StatusValue $status
	 * @param string[] $types 'warning' and/or 'error'
	 * @param string[] $filter Message keys to filter out (since 1.33)
	 */
	public function addMessagesFromStatus(
		StatusValue $status, $types = [ 'warning', 'error' ], array $filter = []
	) {
		$this->getErrorFormatter()->addMessagesFromStatus(
			$this->getModulePath(), $status, $types, $filter
		);
	}

	/**
	 * Abort execution with an error
	 *
	 * If $msg is not an ApiMessage, the message code will be derived from the
	 * message key by stripping any "apiwarn-" or "apierror-" prefix.
	 *
	 * @since 1.29
	 * @param string|array|Message $msg See ApiErrorFormatter::addError()
	 * @param string|null $code See ApiErrorFormatter::addError()
	 * @param array|null $data See ApiErrorFormatter::addError()
	 * @param int|null $httpCode HTTP error code to use
	 * @throws ApiUsageException always
	 */
	public function dieWithError( $msg, $code = null, $data = null, $httpCode = null ) {
		throw ApiUsageException::newWithMessage( $this, $msg, $code, $data, $httpCode );
	}

	/**
	 * Abort execution with an error derived from an exception
	 *
	 * @since 1.29
	 * @param Exception|Throwable $exception See ApiErrorFormatter::getMessageFromException()
	 * @param array $options See ApiErrorFormatter::getMessageFromException()
	 * @throws ApiUsageException always
	 */
	public function dieWithException( $exception, array $options = [] ) {
		$this->dieWithError(
			$this->getErrorFormatter()->getMessageFromException( $exception, $options )
		);
	}

	/**
	 * Adds a warning to the output, else dies
	 *
	 * @param ApiMessage $msg Message to show as a warning, or error message if dying
	 * @param bool $enforceLimits Whether this is an enforce (die)
	 */
	private function warnOrDie( ApiMessage $msg, $enforceLimits = false ) {
		if ( $enforceLimits ) {
			$this->dieWithError( $msg );
		} else {
			$this->addWarning( $msg );
		}
	}

	/**
	 * Throw an ApiUsageException, which will (if uncaught) call the main module's
	 * error handler and die with an error message including block info.
	 *
	 * @since 1.27
	 * @param Block $block The block used to generate the ApiUsageException
	 * @throws ApiUsageException always
	 */
	public function dieBlocked( Block $block ) {
		// Die using the appropriate message depending on block type
		if ( $block->getType() == Block::TYPE_AUTO ) {
			$this->dieWithError(
				'apierror-autoblocked',
				'autoblocked',
				[ 'blockinfo' => ApiQueryUserInfo::getBlockInfo( $block ) ]
			);
		} elseif ( !$block->isSitewide() ) {
			$this->dieWithError(
				'apierror-blocked-partial',
				'blocked',
				[ 'blockinfo' => ApiQueryUserInfo::getBlockInfo( $block ) ]
			);
		} else {
			$this->dieWithError(
				'apierror-blocked',
				'blocked',
				[ 'blockinfo' => ApiQueryUserInfo::getBlockInfo( $block ) ]
			);
		}
	}

	/**
	 * Throw an ApiUsageException based on the Status object.
	 *
	 * @since 1.22
	 * @since 1.29 Accepts a StatusValue
	 * @param StatusValue $status
	 * @throws ApiUsageException always
	 */
	public function dieStatus( StatusValue $status ) {
		if ( $status->isGood() ) {
			throw new MWException( 'Successful status passed to ApiBase::dieStatus' );
		}

		// ApiUsageException needs a fatal status, but this method has
		// historically accepted any non-good status. Convert it if necessary.
		$status->setOK( false );
		if ( !$status->getErrorsByType( 'error' ) ) {
			$newStatus = Status::newGood();
			foreach ( $status->getErrorsByType( 'warning' ) as $err ) {
				$newStatus->fatal( $err['message'], ...$err['params'] );
			}
			if ( !$newStatus->getErrorsByType( 'error' ) ) {
				$newStatus->fatal( 'unknownerror-nocode' );
			}
			$status = $newStatus;
		}

		$this->addBlockInfoToStatus( $status );
		throw new ApiUsageException( $this, $status );
	}

	/**
	 * Helper function for readonly errors
	 *
	 * @throws ApiUsageException always
	 */
	public function dieReadOnly() {
		$this->dieWithError(
			'apierror-readonly',
			'readonly',
			[ 'readonlyreason' => wfReadOnlyReason() ]
		);
	}

	/**
	 * Helper function for permission-denied errors
	 * @since 1.29
	 * @param string|string[] $rights
	 * @param User|null $user
	 * @throws ApiUsageException if the user doesn't have any of the rights.
	 *  The error message is based on $rights[0].
	 */
	public function checkUserRightsAny( $rights, $user = null ) {
		if ( !$user ) {
			$user = $this->getUser();
		}
		$rights = (array)$rights;
		if ( !$user->isAllowedAny( ...$rights ) ) {
			$this->dieWithError( [ 'apierror-permissiondenied', $this->msg( "action-{$rights[0]}" ) ] );
		}
	}

	/**
	 * Helper function for permission-denied errors
	 * @since 1.29
	 * @since 1.33 Changed the third parameter from $user to $options.
	 * @param Title $title
	 * @param string|string[] $actions
	 * @param array $options Additional options
	 *   - user: (User) User to use rather than $this->getUser()
	 *   - autoblock: (bool, default false) Whether to spread autoblocks
	 *  For compatibility, passing a User object is treated as the value for the 'user' option.
	 * @throws ApiUsageException if the user doesn't have all of the rights.
	 */
	public function checkTitleUserPermissions( Title $title, $actions, $options = [] ) {
		if ( !is_array( $options ) ) {
			wfDeprecated( '$user as the third parameter to ' . __METHOD__, '1.33' );
			$options = [ 'user' => $options ];
		}
		$user = $options['user'] ?? $this->getUser();

		$errors = [];
		foreach ( (array)$actions as $action ) {
			$errors = array_merge( $errors, $title->getUserPermissionsErrors( $action, $user ) );
		}

		if ( $errors ) {
			if ( !empty( $options['autoblock'] ) ) {
				$user->spreadAnyEditBlock();
			}

			$this->dieStatus( $this->errorArrayToStatus( $errors, $user ) );
		}
	}

	/**
	 * Will only set a warning instead of failing if the global $wgDebugAPI
	 * is set to true. Otherwise behaves exactly as self::dieWithError().
	 *
	 * @since 1.29
	 * @param string|array|Message $msg
	 * @param string|null $code
	 * @param array|null $data
	 * @param int|null $httpCode
	 * @throws ApiUsageException
	 */
	public function dieWithErrorOrDebug( $msg, $code = null, $data = null, $httpCode = null ) {
		if ( $this->getConfig()->get( 'DebugAPI' ) !== true ) {
			$this->dieWithError( $msg, $code, $data, $httpCode );
		} else {
			$this->addWarning( $msg, $code, $data );
		}
	}

	/**
	 * Die with the 'badcontinue' error.
	 *
	 * This call is common enough to make it into the base method.
	 *
	 * @param bool $condition Will only die if this value is true
	 * @throws ApiUsageException
	 * @since 1.21
	 */
	protected function dieContinueUsageIf( $condition ) {
		if ( $condition ) {
			$this->dieWithError( 'apierror-badcontinue' );
		}
	}

	/**
	 * Internal code errors should be reported with this method
	 * @param string $method Method or function name
	 * @param string $message Error message
	 * @throws MWException always
	 */
	protected static function dieDebug( $method, $message ) {
		throw new MWException( "Internal error in $method: $message" );
	}

	/**
	 * Write logging information for API features to a debug log, for usage
	 * analysis.
	 * @note Consider using $this->addDeprecation() instead to both warn and log.
	 * @param string $feature Feature being used.
	 */
	public function logFeatureUsage( $feature ) {
		static $loggedFeatures = [];

		// Only log each feature once per request. We can get multiple calls from calls to
		// extractRequestParams() with different values for 'parseLimit', for example.
		if ( isset( $loggedFeatures[$feature] ) ) {
			return;
		}
		$loggedFeatures[$feature] = true;

		$request = $this->getRequest();
		$ctx = [
			'feature' => $feature,
			// Spaces to underscores in 'username' for historical reasons.
			'username' => str_replace( ' ', '_', $this->getUser()->getName() ),
			'ip' => $request->getIP(),
			'referer' => (string)$request->getHeader( 'Referer' ),
			'agent' => $this->getMain()->getUserAgent(),
		];

		// Text string is deprecated. Remove (or replace with just $feature) in MW 1.34.
		$s = '"' . addslashes( $ctx['feature'] ) . '"' .
			' "' . wfUrlencode( $ctx['username'] ) . '"' .
			' "' . $ctx['ip'] . '"' .
			' "' . addslashes( $ctx['referer'] ) . '"' .
			' "' . addslashes( $ctx['agent'] ) . '"';

		wfDebugLog( 'api-feature-usage', $s, 'private', $ctx );
	}

	/**@}*/

	/************************************************************************//**
	 * @name   Help message generation
	 * @{
	 */

	/**
	 * Return the summary message.
	 *
	 * This is a one-line description of the module, suitable for display in a
	 * list of modules.
	 *
	 * @since 1.30
	 * @return string|array|Message
	 */
	protected function getSummaryMessage() {
		return "apihelp-{$this->getModulePath()}-summary";
	}

	/**
	 * Return the extended help text message.
	 *
	 * This is additional text to display at the top of the help section, below
	 * the summary.
	 *
	 * @since 1.30
	 * @return string|array|Message
	 */
	protected function getExtendedDescription() {
		return [ [
			"apihelp-{$this->getModulePath()}-extended-description",
			'api-help-no-extended-description',
		] ];
	}

	/**
	 * Get final module summary
	 *
	 * @since 1.30
	 * @return Message
	 */
	public function getFinalSummary() {
		$msg = self::makeMessage( $this->getSummaryMessage(), $this->getContext(), [
			$this->getModulePrefix(),
			$this->getModuleName(),
			$this->getModulePath(),
		] );
		return $msg;
	}

	/**
	 * Get final module description, after hooks have had a chance to tweak it as
	 * needed.
	 *
	 * @since 1.25, returns Message[] rather than string[]
	 * @return Message[]
	 */
	public function getFinalDescription() {
		$summary = self::makeMessage( $this->getSummaryMessage(), $this->getContext(), [
			$this->getModulePrefix(),
			$this->getModuleName(),
			$this->getModulePath(),
		] );
		$extendedDescription = self::makeMessage(
			$this->getExtendedDescription(), $this->getContext(), [
				$this->getModulePrefix(),
				$this->getModuleName(),
				$this->getModulePath(),
			]
		);

		$msgs = [ $summary, $extendedDescription ];

		Hooks::run( 'APIGetDescriptionMessages', [ $this, &$msgs ] );

		return $msgs;
	}

	/**
	 * Get final list of parameters, after hooks have had a chance to
	 * tweak it as needed.
	 *
	 * @param int $flags Zero or more flags like GET_VALUES_FOR_HELP
	 * @return array|bool False on no parameters
	 * @since 1.21 $flags param added
	 */
	public function getFinalParams( $flags = 0 ) {
		$params = $this->getAllowedParams( $flags );
		if ( !$params ) {
			$params = [];
		}

		if ( $this->needsToken() ) {
			$params['token'] = [
				self::PARAM_TYPE => 'string',
				self::PARAM_REQUIRED => true,
				self::PARAM_SENSITIVE => true,
				self::PARAM_HELP_MSG => [
					'api-help-param-token',
					$this->needsToken(),
				],
			] + ( $params['token'] ?? [] );
		}

		// Avoid PHP 7.1 warning of passing $this by reference
		$apiModule = $this;
		Hooks::run( 'APIGetAllowedParams', [ &$apiModule, &$params, $flags ] );

		return $params;
	}

	/**
	 * Get final parameter descriptions, after hooks have had a chance to tweak it as
	 * needed.
	 *
	 * @since 1.25, returns array of Message[] rather than array of string[]
	 * @return array Keys are parameter names, values are arrays of Message objects
	 */
	public function getFinalParamDescription() {
		$prefix = $this->getModulePrefix();
		$name = $this->getModuleName();
		$path = $this->getModulePath();

		$params = $this->getFinalParams( self::GET_VALUES_FOR_HELP );
		$msgs = [];
		foreach ( $params as $param => $settings ) {
			if ( !is_array( $settings ) ) {
				$settings = [];
			}

			if ( isset( $settings[self::PARAM_HELP_MSG] ) ) {
				$msg = $settings[self::PARAM_HELP_MSG];
			} else {
				$msg = $this->msg( "apihelp-{$path}-param-{$param}" );
			}
			$msg = self::makeMessage( $msg, $this->getContext(),
				[ $prefix, $param, $name, $path ] );
			if ( !$msg ) {
				self::dieDebug( __METHOD__,
					'Value in ApiBase::PARAM_HELP_MSG is not valid' );
			}
			$msgs[$param] = [ $msg ];

			if ( isset( $settings[self::PARAM_TYPE] ) &&
				$settings[self::PARAM_TYPE] === 'submodule'
			) {
				if ( isset( $settings[self::PARAM_SUBMODULE_MAP] ) ) {
					$map = $settings[self::PARAM_SUBMODULE_MAP];
				} else {
					$prefix = $this->isMain() ? '' : ( $this->getModulePath() . '+' );
					$map = [];
					foreach ( $this->getModuleManager()->getNames( $param ) as $submoduleName ) {
						$map[$submoduleName] = $prefix . $submoduleName;
					}
				}
				ksort( $map );
				$submodules = [];
				$deprecatedSubmodules = [];
				foreach ( $map as $v => $m ) {
					$arr = &$submodules;
					$isDeprecated = false;
					$summary = null;
					try {
						$submod = $this->getModuleFromPath( $m );
						if ( $submod ) {
							$summary = $submod->getFinalSummary();
							$isDeprecated = $submod->isDeprecated();
							if ( $isDeprecated ) {
								$arr = &$deprecatedSubmodules;
							}
						}
					} catch ( ApiUsageException $ex ) {
						// Ignore
					}
					if ( $summary ) {
						$key = $summary->getKey();
						$params = $summary->getParams();
					} else {
						$key = 'api-help-undocumented-module';
						$params = [ $m ];
					}
					$m = new ApiHelpParamValueMessage( "[[Special:ApiHelp/$m|$v]]", $key, $params, $isDeprecated );
					$arr[] = $m->setContext( $this->getContext() );
				}
				$msgs[$param] = array_merge( $msgs[$param], $submodules, $deprecatedSubmodules );
			} elseif ( isset( $settings[self::PARAM_HELP_MSG_PER_VALUE] ) ) {
				if ( !is_array( $settings[self::PARAM_HELP_MSG_PER_VALUE] ) ) {
					self::dieDebug( __METHOD__,
						'ApiBase::PARAM_HELP_MSG_PER_VALUE is not valid' );
				}
				if ( !is_array( $settings[self::PARAM_TYPE] ) ) {
					self::dieDebug( __METHOD__,
						'ApiBase::PARAM_HELP_MSG_PER_VALUE may only be used when ' .
						'ApiBase::PARAM_TYPE is an array' );
				}

				$valueMsgs = $settings[self::PARAM_HELP_MSG_PER_VALUE];
				$deprecatedValues = $settings[self::PARAM_DEPRECATED_VALUES] ?? [];

				foreach ( $settings[self::PARAM_TYPE] as $value ) {
					if ( isset( $valueMsgs[$value] ) ) {
						$msg = $valueMsgs[$value];
					} else {
						$msg = "apihelp-{$path}-paramvalue-{$param}-{$value}";
					}
					$m = self::makeMessage( $msg, $this->getContext(),
						[ $prefix, $param, $name, $path, $value ] );
					if ( $m ) {
						$m = new ApiHelpParamValueMessage(
							$value,
							[ $m->getKey(), 'api-help-param-no-description' ],
							$m->getParams(),
							isset( $deprecatedValues[$value] )
						);
						$msgs[$param][] = $m->setContext( $this->getContext() );
					} else {
						self::dieDebug( __METHOD__,
							"Value in ApiBase::PARAM_HELP_MSG_PER_VALUE for $value is not valid" );
					}
				}
			}

			if ( isset( $settings[self::PARAM_HELP_MSG_APPEND] ) ) {
				if ( !is_array( $settings[self::PARAM_HELP_MSG_APPEND] ) ) {
					self::dieDebug( __METHOD__,
						'Value for ApiBase::PARAM_HELP_MSG_APPEND is not an array' );
				}
				foreach ( $settings[self::PARAM_HELP_MSG_APPEND] as $m ) {
					$m = self::makeMessage( $m, $this->getContext(),
						[ $prefix, $param, $name, $path ] );
					if ( $m ) {
						$msgs[$param][] = $m;
					} else {
						self::dieDebug( __METHOD__,
							'Value in ApiBase::PARAM_HELP_MSG_APPEND is not valid' );
					}
				}
			}
		}

		Hooks::run( 'APIGetParamDescriptionMessages', [ $this, &$msgs ] );

		return $msgs;
	}

	/**
	 * Generates the list of flags for the help screen and for action=paraminfo
	 *
	 * Corresponding messages: api-help-flag-deprecated,
	 * api-help-flag-internal, api-help-flag-readrights,
	 * api-help-flag-writerights, api-help-flag-mustbeposted
	 *
	 * @return string[]
	 */
	protected function getHelpFlags() {
		$flags = [];

		if ( $this->isDeprecated() ) {
			$flags[] = 'deprecated';
		}
		if ( $this->isInternal() ) {
			$flags[] = 'internal';
		}
		if ( $this->isReadMode() ) {
			$flags[] = 'readrights';
		}
		if ( $this->isWriteMode() ) {
			$flags[] = 'writerights';
		}
		if ( $this->mustBePosted() ) {
			$flags[] = 'mustbeposted';
		}

		return $flags;
	}

	/**
	 * Returns information about the source of this module, if known
	 *
	 * Returned array is an array with the following keys:
	 * - path: Install path
	 * - name: Extension name, or "MediaWiki" for core
	 * - namemsg: (optional) i18n message key for a display name
	 * - license-name: (optional) Name of license
	 *
	 * @return array|null
	 */
	protected function getModuleSourceInfo() {
		global $IP;

		if ( $this->mModuleSource !== false ) {
			return $this->mModuleSource;
		}

		// First, try to find where the module comes from...
		$rClass = new ReflectionClass( $this );
		$path = $rClass->getFileName();
		if ( !$path ) {
			// No path known?
			$this->mModuleSource = null;
			return null;
		}
		$path = realpath( $path ) ?: $path;

		// Build map of extension directories to extension info
		if ( self::$extensionInfo === null ) {
			$extDir = $this->getConfig()->get( 'ExtensionDirectory' );
			self::$extensionInfo = [
				realpath( __DIR__ ) ?: __DIR__ => [
					'path' => $IP,
					'name' => 'MediaWiki',
					'license-name' => 'GPL-2.0-or-later',
				],
				realpath( "$IP/extensions" ) ?: "$IP/extensions" => null,
				realpath( $extDir ) ?: $extDir => null,
			];
			$keep = [
				'path' => null,
				'name' => null,
				'namemsg' => null,
				'license-name' => null,
			];
			foreach ( $this->getConfig()->get( 'ExtensionCredits' ) as $group ) {
				foreach ( $group as $ext ) {
					if ( !isset( $ext['path'] ) || !isset( $ext['name'] ) ) {
						// This shouldn't happen, but does anyway.
						continue;
					}

					$extpath = $ext['path'];
					if ( !is_dir( $extpath ) ) {
						$extpath = dirname( $extpath );
					}
					self::$extensionInfo[realpath( $extpath ) ?: $extpath] =
						array_intersect_key( $ext, $keep );
				}
			}
			foreach ( ExtensionRegistry::getInstance()->getAllThings() as $ext ) {
				$extpath = $ext['path'];
				if ( !is_dir( $extpath ) ) {
					$extpath = dirname( $extpath );
				}
				self::$extensionInfo[realpath( $extpath ) ?: $extpath] =
					array_intersect_key( $ext, $keep );
			}
		}

		// Now traverse parent directories until we find a match or run out of
		// parents.
		do {
			if ( array_key_exists( $path, self::$extensionInfo ) ) {
				// Found it!
				$this->mModuleSource = self::$extensionInfo[$path];
				return $this->mModuleSource;
			}

			$oldpath = $path;
			$path = dirname( $path );
		} while ( $path !== $oldpath );

		// No idea what extension this might be.
		$this->mModuleSource = null;
		return null;
	}

	/**
	 * Called from ApiHelp before the pieces are joined together and returned.
	 *
	 * This exists mainly for ApiMain to add the Permissions and Credits
	 * sections. Other modules probably don't need it.
	 *
	 * @param string[] &$help Array of help data
	 * @param array $options Options passed to ApiHelp::getHelp
	 * @param array &$tocData If a TOC is being generated, this array has keys
	 *   as anchors in the page and values as for Linker::generateTOC().
	 */
	public function modifyHelp( array &$help, array $options, array &$tocData ) {
	}

	/**@}*/

	/************************************************************************//**
	 * @name   Deprecated
	 * @{
	 */

	/**
	 * Returns the description string for this module
	 *
	 * Ignored if an i18n message exists for
	 * "apihelp-{$this->getModulePath()}-description".
	 *
	 * @deprecated since 1.25
	 * @return Message|string|array|false
	 */
	protected function getDescription() {
		wfDeprecated( __METHOD__, '1.25' );
		return false;
	}

	/**
	 * Returns an array of parameter descriptions.
	 *
	 * For each parameter, ignored if an i18n message exists for the parameter.
	 * By default that message is
	 * "apihelp-{$this->getModulePath()}-param-{$param}", but it may be
	 * overridden using ApiBase::PARAM_HELP_MSG in the data returned by
	 * self::getFinalParams().
	 *
	 * @deprecated since 1.25
	 * @return array|bool False on no parameter descriptions
	 */
	protected function getParamDescription() {
		wfDeprecated( __METHOD__, '1.25' );
		return [];
	}

	/**
	 * Returns usage examples for this module.
	 *
	 * Return value as an array is either:
	 *  - numeric keys with partial URLs ("api.php?" plus a query string) as
	 *    values
	 *  - sequential numeric keys with even-numbered keys being display-text
	 *    and odd-numbered keys being partial urls
	 *  - partial URLs as keys with display-text (string or array-to-be-joined)
	 *    as values
	 * Return value as a string is the same as an array with a numeric key and
	 * that value, and boolean false means "no examples".
	 *
	 * @deprecated since 1.25, use getExamplesMessages() instead
	 * @return bool|string|array
	 */
	protected function getExamples() {
		wfDeprecated( __METHOD__, '1.25' );
		return false;
	}

	/**
	 * Return the description message.
	 *
	 * This is additional text to display on the help page after the summary.
	 *
	 * @deprecated since 1.30
	 * @return string|array|Message
	 */
	protected function getDescriptionMessage() {
		wfDeprecated( __METHOD__, '1.30' );
		return [ [
			"apihelp-{$this->getModulePath()}-description",
			"apihelp-{$this->getModulePath()}-summary",
		] ];
	}

	/**
	 * Truncate an array to a certain length.
	 * @deprecated since 1.32, no replacement
	 * @param array &$arr Array to truncate
	 * @param int $limit Maximum length
	 * @return bool True if the array was truncated, false otherwise
	 */
	public static function truncateArray( &$arr, $limit ) {
		wfDeprecated( __METHOD__, '1.32' );
		$modified = false;
		while ( count( $arr ) > $limit ) {
			array_pop( $arr );
			$modified = true;
		}

		return $modified;
	}

	/**@}*/
}

/**
 * For really cool vim folding this needs to be at the end:
 * vim: foldmarker=@{,@} foldmethod=marker
 */
