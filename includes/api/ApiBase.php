<?php
/**
 *
 *
 * Created on Sep 5, 2006
 *
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
	// These constants allow modules to specify exactly how to treat incoming parameters.

	// Default value of the parameter
	const PARAM_DFLT = 0;
	// Boolean, do we accept more than one item for this parameter (e.g.: titles)?
	const PARAM_ISMULTI = 1;
	// Can be either a string type (e.g.: 'integer') or an array of allowed values
	const PARAM_TYPE = 2;
	// Max value allowed for a parameter. Only applies if TYPE='integer'
	const PARAM_MAX = 3;
	// Max value allowed for a parameter for bots and sysops. Only applies if TYPE='integer'
	const PARAM_MAX2 = 4;
	// Lowest value allowed for a parameter. Only applies if TYPE='integer'
	const PARAM_MIN = 5;
	// Boolean, do we allow the same value to be set more than once when ISMULTI=true
	const PARAM_ALLOW_DUPLICATES = 6;
	// Boolean, is the parameter deprecated (will show a warning)
	const PARAM_DEPRECATED = 7;
	/// @since 1.17
	const PARAM_REQUIRED = 8; // Boolean, is the parameter required?
	/// @since 1.17
	// Boolean, if MIN/MAX are set, enforce (die) these?
	// Only applies if TYPE='integer' Use with extreme caution
	const PARAM_RANGE_ENFORCE = 9;
	/// @since 1.25
	// Specify an alternative i18n message for this help parameter.
	// Value is $msg for ApiBase::makeMessage()
	const PARAM_HELP_MSG = 10;
	/// @since 1.25
	// Specify additional i18n messages to append to the normal message. Value
	// is an array of $msg for ApiBase::makeMessage()
	const PARAM_HELP_MSG_APPEND = 11;
	/// @since 1.25
	// Specify additional information tags for the parameter. Value is an array
	// of arrays, with the first member being the 'tag' for the info and the
	// remaining members being the values. In the help, this is formatted using
	// apihelp-{$path}-paraminfo-{$tag}, which is passed $1 = count, $2 =
	// comma-joined list of values, $3 = module prefix.
	const PARAM_HELP_MSG_INFO = 12;
	/// @since 1.25
	// When PARAM_TYPE is an array, this may be an array mapping those values
	// to page titles which will be linked in the help.
	const PARAM_VALUE_LINKS = 13;
	/// @since 1.25
	// When PARAM_TYPE is an array, this is an array mapping those values to
	// $msg for ApiBase::makeMessage(). Any value not having a mapping will use
	// apihelp-{$path}-paramvalue-{$param}-{$value} is used.
	const PARAM_HELP_MSG_PER_VALUE = 14;
	/// @since 1.26
	// When PARAM_TYPE is 'submodule', map parameter values to submodule paths.
	// Default is to use all modules in $this->getModuleManager() in the group
	// matching the parameter name.
	const PARAM_SUBMODULE_MAP = 15;
	/// @since 1.26
	// When PARAM_TYPE is 'submodule', used to indicate the 'g' prefix added by
	// ApiQueryGeneratorBase (and similar if anything else ever does that).
	const PARAM_SUBMODULE_PARAM_PREFIX = 16;

	const LIMIT_BIG1 = 500; // Fast query, std user limit
	const LIMIT_BIG2 = 5000; // Fast query, bot/sysop limit
	const LIMIT_SML1 = 50; // Slow query, std user limit
	const LIMIT_SML2 = 500; // Slow query, bot/sysop limit

	/**
	 * getAllowedParams() flag: When set, the result could take longer to generate,
	 * but should be more thorough. E.g. get the list of generators for ApiSandBox extension
	 * @since 1.21
	 */
	const GET_VALUES_FOR_HELP = 1;

	/** @var array Maps extension paths to info arrays */
	private static $extensionInfo = null;

	/** @var ApiMain */
	private $mMainModule;
	/** @var string */
	private $mModuleName, $mModulePrefix;
	private $mSlaveDB = null;
	private $mParamCache = array();
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
		// Fall back to old non-localised method
		$ret = array();

		$examples = $this->getExamples();
		if ( $examples ) {
			if ( !is_array( $examples ) ) {
				$examples = array( $examples );
			} elseif ( $examples && ( count( $examples ) & 1 ) == 0 &&
				array_keys( $examples ) === range( 0, count( $examples ) - 1 ) &&
				!preg_match( '/^\s*api\.php\?/', $examples[0] )
			) {
				// Fix up the ugly "even numbered elements are description, odd
				// numbered elemts are the link" format (see doc for self::getExamples)
				$tmp = array();
				for ( $i = 0; $i < count( $examples ); $i += 2 ) {
					$tmp[$examples[$i + 1]] = $examples[$i];
				}
				$examples = $tmp;
			}

			foreach ( $examples as $k => $v ) {
				if ( is_numeric( $k ) ) {
					$qs = $v;
					$msg = '';
				} else {
					$qs = $k;
					$msg = self::escapeWikiText( $v );
					if ( is_array( $msg ) ) {
						$msg = join( " ", $msg );
					}
				}

				$qs = preg_replace( '/^\s*api\.php\?/', '', $qs );
				$ret[$qs] = $this->msg( 'api-help-fallback-example', array( $msg ) );
			}
		}

		return $ret;
	}

	/**
	 * Return links to more detailed help pages about the module.
	 * @since 1.25, returning boolean false is deprecated
	 * @return string|array
	 */
	public function getHelpUrls() {
		return array();
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
		return array();
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
	 * @return string|boolean|null As described above, or null if no value is available.
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
		return $this->getMain()->getRequest()->getVal( 'callback' ) !== null;
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
	 * @throws UsageException
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
				$errorPath = join( '+', array_slice( $parts, 0, $i ) );
				$this->dieUsage( "The module \"$errorPath\" has no submodules", 'badmodule' );
			}
			$module = $manager->getModule( $parts[$i] );

			if ( $module === null ) {
				$errorPath = $i ? join( '+', array_slice( $parts, 0, $i ) ) : $parent->getModuleName();
				$this->dieUsage(
					"The module \"$errorPath\" does not have a submodule \"{$parts[$i]}\"",
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
			ApiBase::dieDebug( __METHOD__, 'base method was called on main module. ' );
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
			ApiBase::dieDebug( __METHOD__, 'base method was called on main module. ' );
		}

		return $this->getMain()->getErrorFormatter();
	}

	/**
	 * Gets a default slave database connection object
	 * @return DatabaseBase
	 */
	protected function getDB() {
		if ( !isset( $this->mSlaveDB ) ) {
			$this->mSlaveDB = wfGetDB( DB_SLAVE, 'api' );
		}

		return $this->mSlaveDB;
	}

	/**
	 * Get the continuation manager
	 * @return ApiContinuationManager|null
	 */
	public function getContinuationManager() {
		// Main module has getContinuationManager() method overridden
		// Safety - avoid infinite loop:
		if ( $this->isMain() ) {
			ApiBase::dieDebug( __METHOD__, 'base method was called on main module. ' );
		}

		return $this->getMain()->getContinuationManager();
	}

	/**
	 * Set the continuation manager
	 * @param ApiContinuationManager|null
	 */
	public function setContinuationManager( $manager ) {
		// Main module has setContinuationManager() method overridden
		// Safety - avoid infinite loop:
		if ( $this->isMain() ) {
			ApiBase::dieDebug( __METHOD__, 'base method was called on main module. ' );
		}

		$this->getMain()->setContinuationManager( $manager );
	}

	/**@}*/

	/************************************************************************//**
	 * @name   Parameter handling
	 * @{
	 */

	/**
	 * This method mangles parameter name based on the prefix supplied to the constructor.
	 * Override this method to change parameter name during runtime
	 * @param string $paramName Parameter name
	 * @return string Prefixed parameter name
	 */
	public function encodeParamName( $paramName ) {
		return $this->mModulePrefix . $paramName;
	}

	/**
	 * Using getAllowedParams(), this function makes an array of the values
	 * provided by the user, with key being the name of the variable, and
	 * value - validated value from user or default. limits will not be
	 * parsed if $parseLimit is set to false; use this when the max
	 * limit is not definitive yet, e.g. when getting revisions.
	 * @param bool $parseLimit True by default
	 * @return array
	 */
	public function extractRequestParams( $parseLimit = true ) {
		// Cache parameters, for performance and to avoid bug 24564.
		if ( !isset( $this->mParamCache[$parseLimit] ) ) {
			$params = $this->getFinalParams();
			$results = array();

			if ( $params ) { // getFinalParams() can return false
				foreach ( $params as $paramName => $paramSettings ) {
					$results[$paramName] = $this->getParameterFromSettings(
						$paramName, $paramSettings, $parseLimit );
				}
			}
			$this->mParamCache[$parseLimit] = $results;
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
		$params = $this->getFinalParams();
		$paramSettings = $params[$paramName];

		return $this->getParameterFromSettings( $paramName, $paramSettings, $parseLimit );
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
		$p = $this->getModulePrefix();

		$intersection = array_intersect( array_keys( array_filter( $params,
			array( $this, "parameterNotEmpty" ) ) ), $required );

		if ( count( $intersection ) > 1 ) {
			$this->dieUsage(
				"The parameters {$p}" . implode( ", {$p}", $intersection ) . ' can not be used together',
				'invalidparammix' );
		} elseif ( count( $intersection ) == 0 ) {
			$this->dieUsage(
				"One of the parameters {$p}" . implode( ", {$p}", $required ) . ' is required',
				'missingparam'
			);
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
		$p = $this->getModulePrefix();

		$intersection = array_intersect( array_keys( array_filter( $params,
			array( $this, "parameterNotEmpty" ) ) ), $required );

		if ( count( $intersection ) > 1 ) {
			$this->dieUsage(
				"The parameters {$p}" . implode( ", {$p}", $intersection ) . ' can not be used together',
				'invalidparammix'
			);
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
		$p = $this->getModulePrefix();

		$intersection = array_intersect(
			array_keys( array_filter( $params, array( $this, "parameterNotEmpty" ) ) ),
			$required
		);

		if ( count( $intersection ) == 0 ) {
			$this->dieUsage( "At least one of the parameters {$p}" .
				implode( ", {$p}", $required ) . ' is required', "{$p}missingparam" );
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
	 * @param array $params
	 * @param bool|string $load Whether load the object's state from the database:
	 *        - false: don't load (if the pageid is given, it will still be loaded)
	 *        - 'fromdb': load from a slave database
	 *        - 'fromdbmaster': load from the master database
	 * @return WikiPage
	 */
	public function getTitleOrPageId( $params, $load = false ) {
		$this->requireOnlyOneParameter( $params, 'title', 'pageid' );

		$pageObj = null;
		if ( isset( $params['title'] ) ) {
			$titleObj = Title::newFromText( $params['title'] );
			if ( !$titleObj || $titleObj->isExternal() ) {
				$this->dieUsageMsg( array( 'invalidtitle', $params['title'] ) );
			}
			if ( !$titleObj->canExist() ) {
				$this->dieUsage( "Namespace doesn't allow actual pages", 'pagecannotexist' );
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
				$this->dieUsageMsg( array( 'nosuchpageid', $params['pageid'] ) );
			}
		}

		return $pageObj;
	}

	/**
	 * Return true if we're to watch the page, false if not, null if no change.
	 * @param string $watchlist Valid values: 'watch', 'unwatch', 'preferences', 'nochange'
	 * @param Title $titleObj The page under consideration
	 * @param string $userOption The user option to consider when $watchlist=preferences.
	 *    If not set will use watchdefault always and watchcreations if $titleObj doesn't exist.
	 * @return bool
	 */
	protected function getWatchlistValue( $watchlist, $titleObj, $userOption = null ) {

		$userWatching = $this->getUser()->isWatched( $titleObj, WatchedItem::IGNORE_USER_RIGHTS );

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
	 * @param bool $parseLimit Parse limit?
	 * @return mixed Parameter value
	 */
	protected function getParameterFromSettings( $paramName, $paramSettings, $parseLimit ) {
		// Some classes may decide to change parameter names
		$encParamName = $this->encodeParamName( $paramName );

		if ( !is_array( $paramSettings ) ) {
			$default = $paramSettings;
			$multi = false;
			$type = gettype( $paramSettings );
			$dupes = false;
			$deprecated = false;
			$required = false;
		} else {
			$default = isset( $paramSettings[self::PARAM_DFLT] )
				? $paramSettings[self::PARAM_DFLT]
				: null;
			$multi = isset( $paramSettings[self::PARAM_ISMULTI] )
				? $paramSettings[self::PARAM_ISMULTI]
				: false;
			$type = isset( $paramSettings[self::PARAM_TYPE] )
				? $paramSettings[self::PARAM_TYPE]
				: null;
			$dupes = isset( $paramSettings[self::PARAM_ALLOW_DUPLICATES] )
				? $paramSettings[self::PARAM_ALLOW_DUPLICATES]
				: false;
			$deprecated = isset( $paramSettings[self::PARAM_DEPRECATED] )
				? $paramSettings[self::PARAM_DEPRECATED]
				: false;
			$required = isset( $paramSettings[self::PARAM_REQUIRED] )
				? $paramSettings[self::PARAM_REQUIRED]
				: false;

			// When type is not given, and no choices, the type is the same as $default
			if ( !isset( $type ) ) {
				if ( isset( $default ) ) {
					$type = gettype( $default );
				} else {
					$type = 'NULL'; // allow everything
				}
			}
		}

		if ( $type == 'boolean' ) {
			if ( isset( $default ) && $default !== false ) {
				// Having a default value of anything other than 'false' is not allowed
				ApiBase::dieDebug(
					__METHOD__,
					"Boolean param $encParamName's default is set to '$default'. " .
						"Boolean parameters must default to false."
				);
			}

			$value = $this->getMain()->getCheck( $encParamName );
		} elseif ( $type == 'upload' ) {
			if ( isset( $default ) ) {
				// Having a default value is not allowed
				ApiBase::dieDebug(
					__METHOD__,
					"File upload param $encParamName's default is set to " .
						"'$default'. File upload parameters may not have a default." );
			}
			if ( $multi ) {
				ApiBase::dieDebug( __METHOD__, "Multi-values not supported for $encParamName" );
			}
			$value = $this->getMain()->getUpload( $encParamName );
			if ( !$value->exists() ) {
				// This will get the value without trying to normalize it
				// (because trying to normalize a large binary file
				// accidentally uploaded as a field fails spectacularly)
				$value = $this->getMain()->getRequest()->unsetVal( $encParamName );
				if ( $value !== null ) {
					$this->dieUsage(
						"File upload param $encParamName is not a file upload; " .
							"be sure to use multipart/form-data for your POST and include " .
							"a filename in the Content-Disposition header.",
						"badupload_{$encParamName}"
					);
				}
			}
		} else {
			$value = $this->getMain()->getVal( $encParamName, $default );

			if ( isset( $value ) && $type == 'namespace' ) {
				$type = MWNamespace::getValidNamespaces();
			}
			if ( isset( $value ) && $type == 'submodule' ) {
				if ( isset( $paramSettings[self::PARAM_SUBMODULE_MAP] ) ) {
					$type = array_keys( $paramSettings[self::PARAM_SUBMODULE_MAP] );
				} else {
					$type = $this->getModuleManager()->getNames( $paramName );
				}
			}
		}

		if ( isset( $value ) && ( $multi || is_array( $type ) ) ) {
			$value = $this->parseMultiValue(
				$encParamName,
				$value,
				$multi,
				is_array( $type ) ? $type : null
			);
		}

		// More validation only when choices were not given
		// choices were validated in parseMultiValue()
		if ( isset( $value ) ) {
			if ( !is_array( $type ) ) {
				switch ( $type ) {
					case 'NULL': // nothing to do
						break;
					case 'string':
					case 'text':
					case 'password':
						if ( $required && $value === '' ) {
							$this->dieUsageMsg( array( 'missingparam', $paramName ) );
						}
						break;
					case 'integer': // Force everything using intval() and optionally validate limits
						$min = isset( $paramSettings[self::PARAM_MIN] ) ? $paramSettings[self::PARAM_MIN] : null;
						$max = isset( $paramSettings[self::PARAM_MAX] ) ? $paramSettings[self::PARAM_MAX] : null;
						$enforceLimits = isset( $paramSettings[self::PARAM_RANGE_ENFORCE] )
							? $paramSettings[self::PARAM_RANGE_ENFORCE] : false;

						if ( is_array( $value ) ) {
							$value = array_map( 'intval', $value );
							if ( !is_null( $min ) || !is_null( $max ) ) {
								foreach ( $value as &$v ) {
									$this->validateLimit( $paramName, $v, $min, $max, null, $enforceLimits );
								}
							}
						} else {
							$value = intval( $value );
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
							ApiBase::dieDebug(
								__METHOD__,
								"MAX1 or MAX2 are not defined for the limit $encParamName"
							);
						}
						if ( $multi ) {
							ApiBase::dieDebug( __METHOD__, "Multi-values not supported for $encParamName" );
						}
						$min = isset( $paramSettings[self::PARAM_MIN] ) ? $paramSettings[self::PARAM_MIN] : 0;
						if ( $value == 'max' ) {
							$value = $this->getMain()->canApiHighLimits()
								? $paramSettings[self::PARAM_MAX2]
								: $paramSettings[self::PARAM_MAX];
							$this->getResult()->addParsedLimit( $this->getModuleName(), $value );
						} else {
							$value = intval( $value );
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
							ApiBase::dieDebug( __METHOD__, "Multi-values not supported for $encParamName" );
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
					default:
						ApiBase::dieDebug( __METHOD__, "Param $encParamName's type is unknown - $type" );
				}
			}

			// Throw out duplicates if requested
			if ( !$dupes && is_array( $value ) ) {
				$value = array_unique( $value );
			}

			// Set a warning if a deprecated parameter has been passed
			if ( $deprecated && $value !== false ) {
				$this->setWarning( "The $encParamName parameter has been deprecated." );
			}
		} elseif ( $required ) {
			$this->dieUsageMsg( array( 'missingparam', $paramName ) );
		}

		return $value;
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
	 * @return string|string[] (allowMultiple ? an_array_of_values : a_single_value)
	 */
	protected function parseMultiValue( $valueName, $value, $allowMultiple, $allowedValues ) {
		if ( trim( $value ) === '' && $allowMultiple ) {
			return array();
		}

		// This is a bit awkward, but we want to avoid calling canApiHighLimits()
		// because it unstubs $wgUser
		$valuesList = explode( '|', $value, self::LIMIT_SML2 + 1 );
		$sizeLimit = count( $valuesList ) > self::LIMIT_SML1 && $this->mMainModule->canApiHighLimits()
			? self::LIMIT_SML2
			: self::LIMIT_SML1;

		if ( self::truncateArray( $valuesList, $sizeLimit ) ) {
			$this->setWarning( "Too many values supplied for parameter '$valueName': " .
				"the limit is $sizeLimit" );
		}

		if ( !$allowMultiple && count( $valuesList ) != 1 ) {
			// Bug 33482 - Allow entries with | in them for non-multiple values
			if ( in_array( $value, $allowedValues, true ) ) {
				return $value;
			}

			$possibleValues = is_array( $allowedValues )
				? "of '" . implode( "', '", $allowedValues ) . "'"
				: '';
			$this->dieUsage(
				"Only one $possibleValues is allowed for parameter '$valueName'",
				"multival_$valueName"
			);
		}

		if ( is_array( $allowedValues ) ) {
			// Check for unknown values
			$unknown = array_diff( $valuesList, $allowedValues );
			if ( count( $unknown ) ) {
				if ( $allowMultiple ) {
					$s = count( $unknown ) > 1 ? 's' : '';
					$vals = implode( ", ", $unknown );
					$this->setWarning( "Unrecognized value$s for parameter '$valueName': $vals" );
				} else {
					$this->dieUsage(
						"Unrecognized value for parameter '$valueName': {$valuesList[0]}",
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
	 * @param int $value Parameter value
	 * @param int|null $min Minimum value
	 * @param int|null $max Maximum value for users
	 * @param int $botMax Maximum value for sysops/bots
	 * @param bool $enforceLimits Whether to enforce (die) if value is outside limits
	 */
	protected function validateLimit( $paramName, &$value, $min, $max, $botMax = null, $enforceLimits = false ) {
		if ( !is_null( $min ) && $value < $min ) {
			$msg = $this->encodeParamName( $paramName ) . " may not be less than $min (set to $value)";
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
					$msg = $this->encodeParamName( $paramName ) .
						" may not be over $botMax (set to $value) for bots or sysops";
					$this->warnOrDie( $msg, $enforceLimits );
					$value = $botMax;
				}
			} else {
				$msg = $this->encodeParamName( $paramName ) . " may not be over $max (set to $value) for users";
				$this->warnOrDie( $msg, $enforceLimits );
				$value = $max;
			}
		}
	}

	/**
	 * Validate and normalize of parameters of type 'timestamp'
	 * @param string $value Parameter value
	 * @param string $encParamName Parameter name
	 * @return string Validated and normalized parameter
	 */
	protected function validateTimestamp( $value, $encParamName ) {
		// Confusing synonyms for the current time accepted by wfTimestamp()
		// (wfTimestamp() also accepts various non-strings and the string of 14
		// ASCII NUL bytes, but those can't get here)
		if ( !$value ) {
			$this->logFeatureUsage( 'unclear-"now"-timestamp' );
			$this->setWarning(
				"Passing '$value' for timestamp parameter $encParamName has been deprecated." .
					' If for some reason you need to explicitly specify the current time without' .
					' calculating it client-side, use "now".'
			);
			return wfTimestamp( TS_MW );
		}

		// Explicit synonym for the current time
		if ( $value === 'now' ) {
			return wfTimestamp( TS_MW );
		}

		$unixTimestamp = wfTimestamp( TS_UNIX, $value );
		if ( $unixTimestamp === false ) {
			$this->dieUsage(
				"Invalid value '$value' for timestamp parameter $encParamName",
				"badtimestamp_{$encParamName}"
			);
		}

		return wfTimestamp( TS_MW, $unixTimestamp );
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

		if ( $this->getUser()->matchEditToken(
			$token,
			$salts[$tokenType],
			$this->getRequest()
		) ) {
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
	 * Validate and normalize of parameters of type 'user'
	 * @param string $value Parameter value
	 * @param string $encParamName Parameter name
	 * @return string Validated and normalized parameter
	 */
	private function validateUser( $value, $encParamName ) {
		$title = Title::makeTitleSafe( NS_USER, $value );
		if ( $title === null ) {
			$this->dieUsage(
				"Invalid value '$value' for user parameter $encParamName",
				"baduser_{$encParamName}"
			);
		}

		return $title->getText();
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
	 * @param string $userOption The user option to consider when $watch=preferences
	 */
	protected function setWatch( $watch, $titleObj, $userOption = null ) {
		$value = $this->getWatchlistValue( $watch, $titleObj, $userOption );
		if ( $value === null ) {
			return;
		}

		WatchAction::doWatchOrUnwatch( $value, $titleObj, $this->getUser() );
	}

	/**
	 * Truncate an array to a certain length.
	 * @param array $arr Array to truncate
	 * @param int $limit Maximum length
	 * @return bool True if the array was truncated, false otherwise
	 */
	public static function truncateArray( &$arr, $limit ) {
		$modified = false;
		while ( count( $arr ) > $limit ) {
			array_pop( $arr );
			$modified = true;
		}

		return $modified;
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
				$this->dieUsage( 'Specified user does not exist', 'bad_wlowner' );
			}
			$token = $user->getOption( 'watchlisttoken' );
			if ( $token == '' || !hash_equals( $token, $params['token'] ) ) {
				$this->dieUsage(
					'Incorrect watchlist token provided -- please set a correct token in Special:Preferences',
					'bad_wltoken'
				);
			}
		} else {
			if ( !$this->getUser()->isLoggedIn() ) {
				$this->dieUsage( 'You must be logged-in to have a watchlist', 'notloggedin' );
			}
			if ( !$this->getUser()->isAllowed( 'viewmywatchlist' ) ) {
				$this->dieUsage( 'You don\'t have permission to view your watchlist', 'permissiondenied' );
			}
			$user = $this->getUser();
		}

		return $user;
	}

	/**
	 * A subset of wfEscapeWikiText for BC texts
	 *
	 * @since 1.25
	 * @param string|array $v
	 * @return string|array
	 */
	private static function escapeWikiText( $v ) {
		if ( is_array( $v ) ) {
			return array_map( 'self::escapeWikiText', $v );
		} else {
			return strtr( $v, array(
				'__' => '_&#95;', '{' => '&#123;', '}' => '&#125;',
				'[[Category:' => '[[:Category:',
				'[[File:' => '[[:File:', '[[Image:' => '[[:Image:',
			) );
		}
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
	 * @param array $params
	 * @return Message|null
	 */
	public static function makeMessage( $msg, IContextSource $context, array $params = null ) {
		if ( is_string( $msg ) ) {
			$msg = wfMessage( $msg );
		} elseif ( is_array( $msg ) ) {
			$msg = call_user_func_array( 'wfMessage', $msg );
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

	/**@}*/

	/************************************************************************//**
	 * @name   Warning and error reporting
	 * @{
	 */

	/**
	 * Set warning section for this module. Users should monitor this
	 * section to notice any changes in API. Multiple calls to this
	 * function will result in the warning messages being separated by
	 * newlines
	 * @param string $warning Warning message
	 */
	public function setWarning( $warning ) {
		$msg = new ApiRawMessage( $warning, 'warning' );
		$this->getErrorFormatter()->addWarning( $this->getModuleName(), $msg );
	}

	/**
	 * Adds a warning to the output, else dies
	 *
	 * @param string $msg Message to show as a warning, or error message if dying
	 * @param bool $enforceLimits Whether this is an enforce (die)
	 */
	private function warnOrDie( $msg, $enforceLimits = false ) {
		if ( $enforceLimits ) {
			$this->dieUsage( $msg, 'integeroutofrange' );
		}

		$this->setWarning( $msg );
	}

	/**
	 * Throw a UsageException, which will (if uncaught) call the main module's
	 * error handler and die with an error message.
	 *
	 * @param string $description One-line human-readable description of the
	 *   error condition, e.g., "The API requires a valid action parameter"
	 * @param string $errorCode Brief, arbitrary, stable string to allow easy
	 *   automated identification of the error, e.g., 'unknown_action'
	 * @param int $httpRespCode HTTP response code
	 * @param array $extradata Data to add to the "<error>" element; array in ApiResult format
	 * @throws UsageException
	 */
	public function dieUsage( $description, $errorCode, $httpRespCode = 0, $extradata = null ) {
		throw new UsageException(
			$description,
			$this->encodeParamName( $errorCode ),
			$httpRespCode,
			$extradata
		);
	}

	/**
	 * Get error (as code, string) from a Status object.
	 *
	 * @since 1.23
	 * @param Status $status
	 * @return array Array of code and error string
	 * @throws MWException
	 */
	public function getErrorFromStatus( $status ) {
		if ( $status->isGood() ) {
			throw new MWException( 'Successful status passed to ApiBase::dieStatus' );
		}

		$errors = $status->getErrorsArray();
		if ( !$errors ) {
			// No errors? Assume the warnings should be treated as errors
			$errors = $status->getWarningsArray();
		}
		if ( !$errors ) {
			// Still no errors? Punt
			$errors = array( array( 'unknownerror-nocode' ) );
		}

		// Cannot use dieUsageMsg() because extensions might return custom
		// error messages.
		if ( $errors[0] instanceof Message ) {
			$msg = $errors[0];
			$code = $msg->getKey();
		} else {
			$code = array_shift( $errors[0] );
			$msg = wfMessage( $code, $errors[0] );
		}
		if ( isset( ApiBase::$messageMap[$code] ) ) {
			// Translate message to code, for backwards compatibility
			$code = ApiBase::$messageMap[$code]['code'];
		}

		return array( $code, $msg->inLanguage( 'en' )->useDatabase( false )->plain() );
	}

	/**
	 * Throw a UsageException based on the errors in the Status object.
	 *
	 * @since 1.22
	 * @param Status $status
	 * @throws MWException
	 */
	public function dieStatus( $status ) {

		list( $code, $msg ) = $this->getErrorFromStatus( $status );
		$this->dieUsage( $msg, $code );
	}

	// @codingStandardsIgnoreStart Allow long lines. Cannot split these.
	/**
	 * Array that maps message keys to error messages. $1 and friends are replaced.
	 */
	public static $messageMap = array(
		// This one MUST be present, or dieUsageMsg() will recurse infinitely
		'unknownerror' => array( 'code' => 'unknownerror', 'info' => "Unknown error: \"\$1\"" ),
		'unknownerror-nocode' => array( 'code' => 'unknownerror', 'info' => 'Unknown error' ),

		// Messages from Title::getUserPermissionsErrors()
		'ns-specialprotected' => array(
			'code' => 'unsupportednamespace',
			'info' => "Pages in the Special namespace can't be edited"
		),
		'protectedinterface' => array(
			'code' => 'protectednamespace-interface',
			'info' => "You're not allowed to edit interface messages"
		),
		'namespaceprotected' => array(
			'code' => 'protectednamespace',
			'info' => "You're not allowed to edit pages in the \"\$1\" namespace"
		),
		'customcssprotected' => array(
			'code' => 'customcssprotected',
			'info' => "You're not allowed to edit custom CSS pages"
		),
		'customjsprotected' => array(
			'code' => 'customjsprotected',
			'info' => "You're not allowed to edit custom JavaScript pages"
		),
		'cascadeprotected' => array(
			'code' => 'cascadeprotected',
			'info' => "The page you're trying to edit is protected because it's included in a cascade-protected page"
		),
		'protectedpagetext' => array(
			'code' => 'protectedpage',
			'info' => "The \"\$1\" right is required to edit this page"
		),
		'protect-cantedit' => array(
			'code' => 'cantedit',
			'info' => "You can't protect this page because you can't edit it"
		),
		'deleteprotected' => array(
			'code' => 'cantedit',
			'info' => "You can't delete this page because it has been protected"
		),
		'badaccess-group0' => array(
			'code' => 'permissiondenied',
			'info' => "Permission denied"
		), // Generic permission denied message
		'badaccess-groups' => array(
			'code' => 'permissiondenied',
			'info' => "Permission denied"
		),
		'titleprotected' => array(
			'code' => 'protectedtitle',
			'info' => "This title has been protected from creation"
		),
		'nocreate-loggedin' => array(
			'code' => 'cantcreate',
			'info' => "You don't have permission to create new pages"
		),
		'nocreatetext' => array(
			'code' => 'cantcreate-anon',
			'info' => "Anonymous users can't create new pages"
		),
		'movenologintext' => array(
			'code' => 'cantmove-anon',
			'info' => "Anonymous users can't move pages"
		),
		'movenotallowed' => array(
			'code' => 'cantmove',
			'info' => "You don't have permission to move pages"
		),
		'confirmedittext' => array(
			'code' => 'confirmemail',
			'info' => "You must confirm your email address before you can edit"
		),
		'blockedtext' => array(
			'code' => 'blocked',
			'info' => "You have been blocked from editing"
		),
		'autoblockedtext' => array(
			'code' => 'autoblocked',
			'info' => "Your IP address has been blocked automatically, because it was used by a blocked user"
		),

		// Miscellaneous interface messages
		'actionthrottledtext' => array(
			'code' => 'ratelimited',
			'info' => "You've exceeded your rate limit. Please wait some time and try again"
		),
		'alreadyrolled' => array(
			'code' => 'alreadyrolled',
			'info' => "The page you tried to rollback was already rolled back"
		),
		'cantrollback' => array(
			'code' => 'onlyauthor',
			'info' => "The page you tried to rollback only has one author"
		),
		'readonlytext' => array(
			'code' => 'readonly',
			'info' => "The wiki is currently in read-only mode"
		),
		'sessionfailure' => array(
			'code' => 'badtoken',
			'info' => "Invalid token" ),
		'cannotdelete' => array(
			'code' => 'cantdelete',
			'info' => "Couldn't delete \"\$1\". Maybe it was deleted already by someone else"
		),
		'notanarticle' => array(
			'code' => 'missingtitle',
			'info' => "The page you requested doesn't exist"
		),
		'selfmove' => array( 'code' => 'selfmove', 'info' => "Can't move a page to itself"
		),
		'immobile_namespace' => array(
			'code' => 'immobilenamespace',
			'info' => "You tried to move pages from or to a namespace that is protected from moving"
		),
		'articleexists' => array(
			'code' => 'articleexists',
			'info' => "The destination article already exists and is not a redirect to the source article"
		),
		'protectedpage' => array(
			'code' => 'protectedpage',
			'info' => "You don't have permission to perform this move"
		),
		'hookaborted' => array(
			'code' => 'hookaborted',
			'info' => "The modification you tried to make was aborted by an extension hook"
		),
		'cantmove-titleprotected' => array(
			'code' => 'protectedtitle',
			'info' => "The destination article has been protected from creation"
		),
		'imagenocrossnamespace' => array(
			'code' => 'nonfilenamespace',
			'info' => "Can't move a file to a non-file namespace"
		),
		'imagetypemismatch' => array(
			'code' => 'filetypemismatch',
			'info' => "The new file extension doesn't match its type"
		),
		// 'badarticleerror' => shouldn't happen
		// 'badtitletext' => shouldn't happen
		'ip_range_invalid' => array( 'code' => 'invalidrange', 'info' => "Invalid IP range" ),
		'range_block_disabled' => array(
			'code' => 'rangedisabled',
			'info' => "Blocking IP ranges has been disabled"
		),
		'nosuchusershort' => array(
			'code' => 'nosuchuser',
			'info' => "The user you specified doesn't exist"
		),
		'badipaddress' => array( 'code' => 'invalidip', 'info' => "Invalid IP address specified" ),
		'ipb_expiry_invalid' => array( 'code' => 'invalidexpiry', 'info' => "Invalid expiry time" ),
		'ipb_already_blocked' => array(
			'code' => 'alreadyblocked',
			'info' => "The user you tried to block was already blocked"
		),
		'ipb_blocked_as_range' => array(
			'code' => 'blockedasrange',
			'info' => "IP address \"\$1\" was blocked as part of range \"\$2\". You can't unblock the IP individually, but you can unblock the range as a whole."
		),
		'ipb_cant_unblock' => array(
			'code' => 'cantunblock',
			'info' => "The block you specified was not found. It may have been unblocked already"
		),
		'mailnologin' => array(
			'code' => 'cantsend',
			'info' => "You are not logged in, you do not have a confirmed email address, or you are not allowed to send email to other users, so you cannot send email"
		),
		'ipbblocked' => array(
			'code' => 'ipbblocked',
			'info' => 'You cannot block or unblock users while you are yourself blocked'
		),
		'ipbnounblockself' => array(
			'code' => 'ipbnounblockself',
			'info' => 'You are not allowed to unblock yourself'
		),
		'usermaildisabled' => array(
			'code' => 'usermaildisabled',
			'info' => "User email has been disabled"
		),
		'blockedemailuser' => array(
			'code' => 'blockedfrommail',
			'info' => "You have been blocked from sending email"
		),
		'notarget' => array(
			'code' => 'notarget',
			'info' => "You have not specified a valid target for this action"
		),
		'noemail' => array(
			'code' => 'noemail',
			'info' => "The user has not specified a valid email address, or has chosen not to receive email from other users"
		),
		'rcpatroldisabled' => array(
			'code' => 'patroldisabled',
			'info' => "Patrolling is disabled on this wiki"
		),
		'markedaspatrollederror-noautopatrol' => array(
			'code' => 'noautopatrol',
			'info' => "You don't have permission to patrol your own changes"
		),
		'delete-toobig' => array(
			'code' => 'bigdelete',
			'info' => "You can't delete this page because it has more than \$1 revisions"
		),
		'movenotallowedfile' => array(
			'code' => 'cantmovefile',
			'info' => "You don't have permission to move files"
		),
		'userrights-no-interwiki' => array(
			'code' => 'nointerwikiuserrights',
			'info' => "You don't have permission to change user rights on other wikis"
		),
		'userrights-nodatabase' => array(
			'code' => 'nosuchdatabase',
			'info' => "Database \"\$1\" does not exist or is not local"
		),
		'nouserspecified' => array( 'code' => 'invaliduser', 'info' => "Invalid username \"\$1\"" ),
		'noname' => array( 'code' => 'invaliduser', 'info' => "Invalid username \"\$1\"" ),
		'summaryrequired' => array( 'code' => 'summaryrequired', 'info' => 'Summary required' ),
		'import-rootpage-invalid' => array(
			'code' => 'import-rootpage-invalid',
			'info' => 'Root page is an invalid title'
		),
		'import-rootpage-nosubpage' => array(
			'code' => 'import-rootpage-nosubpage',
			'info' => 'Namespace "$1" of the root page does not allow subpages'
		),

		// API-specific messages
		'readrequired' => array(
			'code' => 'readapidenied',
			'info' => "You need read permission to use this module"
		),
		'writedisabled' => array(
			'code' => 'noapiwrite',
			'info' => "Editing of this wiki through the API is disabled. Make sure the \$wgEnableWriteAPI=true; statement is included in the wiki's LocalSettings.php file"
		),
		'writerequired' => array(
			'code' => 'writeapidenied',
			'info' => "You're not allowed to edit this wiki through the API"
		),
		'missingparam' => array( 'code' => 'no$1', 'info' => "The \$1 parameter must be set" ),
		'invalidtitle' => array( 'code' => 'invalidtitle', 'info' => "Bad title \"\$1\"" ),
		'nosuchpageid' => array( 'code' => 'nosuchpageid', 'info' => "There is no page with ID \$1" ),
		'nosuchrevid' => array( 'code' => 'nosuchrevid', 'info' => "There is no revision with ID \$1" ),
		'nosuchuser' => array( 'code' => 'nosuchuser', 'info' => "User \"\$1\" doesn't exist" ),
		'invaliduser' => array( 'code' => 'invaliduser', 'info' => "Invalid username \"\$1\"" ),
		'invalidexpiry' => array( 'code' => 'invalidexpiry', 'info' => "Invalid expiry time \"\$1\"" ),
		'pastexpiry' => array( 'code' => 'pastexpiry', 'info' => "Expiry time \"\$1\" is in the past" ),
		'create-titleexists' => array(
			'code' => 'create-titleexists',
			'info' => "Existing titles can't be protected with 'create'"
		),
		'missingtitle-createonly' => array(
			'code' => 'missingtitle-createonly',
			'info' => "Missing titles can only be protected with 'create'"
		),
		'cantblock' => array( 'code' => 'cantblock',
			'info' => "You don't have permission to block users"
		),
		'canthide' => array(
			'code' => 'canthide',
			'info' => "You don't have permission to hide user names from the block log"
		),
		'cantblock-email' => array(
			'code' => 'cantblock-email',
			'info' => "You don't have permission to block users from sending email through the wiki"
		),
		'unblock-notarget' => array(
			'code' => 'notarget',
			'info' => "Either the id or the user parameter must be set"
		),
		'unblock-idanduser' => array(
			'code' => 'idanduser',
			'info' => "The id and user parameters can't be used together"
		),
		'cantunblock' => array(
			'code' => 'permissiondenied',
			'info' => "You don't have permission to unblock users"
		),
		'cannotundelete' => array(
			'code' => 'cantundelete',
			'info' => "Couldn't undelete: the requested revisions may not exist, or may have been undeleted already"
		),
		'permdenied-undelete' => array(
			'code' => 'permissiondenied',
			'info' => "You don't have permission to restore deleted revisions"
		),
		'createonly-exists' => array(
			'code' => 'articleexists',
			'info' => "The article you tried to create has been created already"
		),
		'nocreate-missing' => array(
			'code' => 'missingtitle',
			'info' => "The article you tried to edit doesn't exist"
		),
		'cantchangecontentmodel' => array(
			'code' => 'cantchangecontentmodel',
			'info' => "You don't have permission to change the content model of a page"
		),
		'nosuchrcid' => array(
			'code' => 'nosuchrcid',
			'info' => "There is no change with rcid \"\$1\""
		),
		'nosuchlogid' => array(
			'code' => 'nosuchlogid',
			'info' => "There is no log entry with ID \"\$1\""
		),
		'protect-invalidaction' => array(
			'code' => 'protect-invalidaction',
			'info' => "Invalid protection type \"\$1\""
		),
		'protect-invalidlevel' => array(
			'code' => 'protect-invalidlevel',
			'info' => "Invalid protection level \"\$1\""
		),
		'toofewexpiries' => array(
			'code' => 'toofewexpiries',
			'info' => "\$1 expiry timestamps were provided where \$2 were needed"
		),
		'cantimport' => array(
			'code' => 'cantimport',
			'info' => "You don't have permission to import pages"
		),
		'cantimport-upload' => array(
			'code' => 'cantimport-upload',
			'info' => "You don't have permission to import uploaded pages"
		),
		'importnofile' => array( 'code' => 'nofile', 'info' => "You didn't upload a file" ),
		'importuploaderrorsize' => array(
			'code' => 'filetoobig',
			'info' => 'The file you uploaded is bigger than the maximum upload size'
		),
		'importuploaderrorpartial' => array(
			'code' => 'partialupload',
			'info' => 'The file was only partially uploaded'
		),
		'importuploaderrortemp' => array(
			'code' => 'notempdir',
			'info' => 'The temporary upload directory is missing'
		),
		'importcantopen' => array(
			'code' => 'cantopenfile',
			'info' => "Couldn't open the uploaded file"
		),
		'import-noarticle' => array(
			'code' => 'badinterwiki',
			'info' => 'Invalid interwiki title specified'
		),
		'importbadinterwiki' => array(
			'code' => 'badinterwiki',
			'info' => 'Invalid interwiki title specified'
		),
		'import-unknownerror' => array(
			'code' => 'import-unknownerror',
			'info' => "Unknown error on import: \"\$1\""
		),
		'cantoverwrite-sharedfile' => array(
			'code' => 'cantoverwrite-sharedfile',
			'info' => 'The target file exists on a shared repository and you do not have permission to override it'
		),
		'sharedfile-exists' => array(
			'code' => 'fileexists-sharedrepo-perm',
			'info' => 'The target file exists on a shared repository. Use the ignorewarnings parameter to override it.'
		),
		'mustbeposted' => array(
			'code' => 'mustbeposted',
			'info' => "The \$1 module requires a POST request"
		),
		'show' => array(
			'code' => 'show',
			'info' => 'Incorrect parameter - mutually exclusive values may not be supplied'
		),
		'specialpage-cantexecute' => array(
			'code' => 'specialpage-cantexecute',
			'info' => "You don't have permission to view the results of this special page"
		),
		'invalidoldimage' => array(
			'code' => 'invalidoldimage',
			'info' => 'The oldimage parameter has invalid format'
		),
		'nodeleteablefile' => array(
			'code' => 'nodeleteablefile',
			'info' => 'No such old version of the file'
		),
		'fileexists-forbidden' => array(
			'code' => 'fileexists-forbidden',
			'info' => 'A file with name "$1" already exists, and cannot be overwritten.'
		),
		'fileexists-shared-forbidden' => array(
			'code' => 'fileexists-shared-forbidden',
			'info' => 'A file with name "$1" already exists in the shared file repository, and cannot be overwritten.'
		),
		'filerevert-badversion' => array(
			'code' => 'filerevert-badversion',
			'info' => 'There is no previous local version of this file with the provided timestamp.'
		),

		// ApiEditPage messages
		'noimageredirect-anon' => array(
			'code' => 'noimageredirect-anon',
			'info' => "Anonymous users can't create image redirects"
		),
		'noimageredirect-logged' => array(
			'code' => 'noimageredirect',
			'info' => "You don't have permission to create image redirects"
		),
		'spamdetected' => array(
			'code' => 'spamdetected',
			'info' => "Your edit was refused because it contained a spam fragment: \"\$1\""
		),
		'contenttoobig' => array(
			'code' => 'contenttoobig',
			'info' => "The content you supplied exceeds the article size limit of \$1 kilobytes"
		),
		'noedit-anon' => array( 'code' => 'noedit-anon', 'info' => "Anonymous users can't edit pages" ),
		'noedit' => array( 'code' => 'noedit', 'info' => "You don't have permission to edit pages" ),
		'wasdeleted' => array(
			'code' => 'pagedeleted',
			'info' => "The page has been deleted since you fetched its timestamp"
		),
		'blankpage' => array(
			'code' => 'emptypage',
			'info' => "Creating new, empty pages is not allowed"
		),
		'editconflict' => array( 'code' => 'editconflict', 'info' => "Edit conflict detected" ),
		'hashcheckfailed' => array( 'code' => 'badmd5', 'info' => "The supplied MD5 hash was incorrect" ),
		'missingtext' => array(
			'code' => 'notext',
			'info' => "One of the text, appendtext, prependtext and undo parameters must be set"
		),
		'emptynewsection' => array(
			'code' => 'emptynewsection',
			'info' => 'Creating empty new sections is not possible.'
		),
		'revwrongpage' => array(
			'code' => 'revwrongpage',
			'info' => "r\$1 is not a revision of \"\$2\""
		),
		'undo-failure' => array(
			'code' => 'undofailure',
			'info' => 'Undo failed due to conflicting intermediate edits'
		),
		'content-not-allowed-here' => array(
			'code' => 'contentnotallowedhere',
			'info' => 'Content model "$1" is not allowed at title "$2"'
		),

		// Messages from WikiPage::doEit()
		'edit-hook-aborted' => array(
			'code' => 'edit-hook-aborted',
			'info' => "Your edit was aborted by an ArticleSave hook"
		),
		'edit-gone-missing' => array(
			'code' => 'edit-gone-missing',
			'info' => "The page you tried to edit doesn't seem to exist anymore"
		),
		'edit-conflict' => array( 'code' => 'editconflict', 'info' => "Edit conflict detected" ),
		'edit-already-exists' => array(
			'code' => 'edit-already-exists',
			'info' => 'It seems the page you tried to create already exist'
		),

		// uploadMsgs
		'invalid-file-key' => array( 'code' => 'invalid-file-key', 'info' => 'Not a valid file key' ),
		'nouploadmodule' => array( 'code' => 'nouploadmodule', 'info' => 'No upload module set' ),
		'uploaddisabled' => array(
			'code' => 'uploaddisabled',
			'info' => 'Uploads are not enabled. Make sure $wgEnableUploads is set to true in LocalSettings.php and the PHP ini setting file_uploads is true'
		),
		'copyuploaddisabled' => array(
			'code' => 'copyuploaddisabled',
			'info' => 'Uploads by URL is not enabled. Make sure $wgAllowCopyUploads is set to true in LocalSettings.php.'
		),
		'copyuploadbaddomain' => array(
			'code' => 'copyuploadbaddomain',
			'info' => 'Uploads by URL are not allowed from this domain.'
		),
		'copyuploadbadurl' => array(
			'code' => 'copyuploadbadurl',
			'info' => 'Upload not allowed from this URL.'
		),

		'filename-tooshort' => array(
			'code' => 'filename-tooshort',
			'info' => 'The filename is too short'
		),
		'filename-toolong' => array( 'code' => 'filename-toolong', 'info' => 'The filename is too long' ),
		'illegal-filename' => array(
			'code' => 'illegal-filename',
			'info' => 'The filename is not allowed'
		),
		'filetype-missing' => array(
			'code' => 'filetype-missing',
			'info' => 'The file is missing an extension'
		),

		'mustbeloggedin' => array( 'code' => 'mustbeloggedin', 'info' => 'You must be logged in to $1.' )
	);
	// @codingStandardsIgnoreEnd

	/**
	 * Helper function for readonly errors
	 */
	public function dieReadOnly() {
		$parsed = $this->parseMsg( array( 'readonlytext' ) );
		$this->dieUsage( $parsed['info'], $parsed['code'], /* http error */ 0,
			array( 'readonlyreason' => wfReadOnlyReason() ) );
	}

	/**
	 * Output the error message related to a certain array
	 * @param array|string $error Element of a getUserPermissionsErrors()-style array
	 */
	public function dieUsageMsg( $error ) {
		# most of the time we send a 1 element, so we might as well send it as
		# a string and make this an array here.
		if ( is_string( $error ) ) {
			$error = array( $error );
		}
		$parsed = $this->parseMsg( $error );
		$this->dieUsage( $parsed['info'], $parsed['code'] );
	}

	/**
	 * Will only set a warning instead of failing if the global $wgDebugAPI
	 * is set to true. Otherwise behaves exactly as dieUsageMsg().
	 * @param array|string $error Element of a getUserPermissionsErrors()-style array
	 * @since 1.21
	 */
	public function dieUsageMsgOrDebug( $error ) {
		if ( $this->getConfig()->get( 'DebugAPI' ) !== true ) {
			$this->dieUsageMsg( $error );
		}

		if ( is_string( $error ) ) {
			$error = array( $error );
		}
		$parsed = $this->parseMsg( $error );
		$this->setWarning( '$wgDebugAPI: ' . $parsed['code'] . ' - ' . $parsed['info'] );
	}

	/**
	 * Die with the $prefix.'badcontinue' error. This call is common enough to
	 * make it into the base method.
	 * @param bool $condition Will only die if this value is true
	 * @since 1.21
	 */
	protected function dieContinueUsageIf( $condition ) {
		if ( $condition ) {
			$this->dieUsage(
				'Invalid continue param. You should pass the original value returned by the previous query',
				'badcontinue' );
		}
	}

	/**
	 * Return the error message related to a certain array
	 * @param array $error Element of a getUserPermissionsErrors()-style array
	 * @return array('code' => code, 'info' => info)
	 */
	public function parseMsg( $error ) {
		$error = (array)$error; // It seems strings sometimes make their way in here
		$key = array_shift( $error );

		// Check whether the error array was nested
		// array( array( <code>, <params> ), array( <another_code>, <params> ) )
		if ( is_array( $key ) ) {
			$error = $key;
			$key = array_shift( $error );
		}

		if ( isset( self::$messageMap[$key] ) ) {
			return array(
				'code' => wfMsgReplaceArgs( self::$messageMap[$key]['code'], $error ),
				'info' => wfMsgReplaceArgs( self::$messageMap[$key]['info'], $error )
			);
		}

		// If the key isn't present, throw an "unknown error"
		return $this->parseMsg( array( 'unknownerror', $key ) );
	}

	/**
	 * Internal code errors should be reported with this method
	 * @param string $method Method or function name
	 * @param string $message Error message
	 * @throws MWException
	 */
	protected static function dieDebug( $method, $message ) {
		throw new MWException( "Internal error in $method: $message" );
	}

	/**
	 * Write logging information for API features to a debug log, for usage
	 * analysis.
	 * @param string $feature Feature being used.
	 */
	protected function logFeatureUsage( $feature ) {
		$request = $this->getRequest();
		$s = '"' . addslashes( $feature ) . '"' .
			' "' . wfUrlencode( str_replace( ' ', '_', $this->getUser()->getName() ) ) . '"' .
			' "' . $request->getIP() . '"' .
			' "' . addslashes( $request->getHeader( 'Referer' ) ) . '"' .
			' "' . addslashes( $this->getMain()->getUserAgent() ) . '"';
		wfDebugLog( 'api-feature-usage', $s, 'private' );
	}

	/**@}*/

	/************************************************************************//**
	 * @name   Help message generation
	 * @{
	 */

	/**
	 * Return the description message.
	 *
	 * @return string|array|Message
	 */
	protected function getDescriptionMessage() {
		return "apihelp-{$this->getModulePath()}-description";
	}

	/**
	 * Get final module description, after hooks have had a chance to tweak it as
	 * needed.
	 *
	 * @since 1.25, returns Message[] rather than string[]
	 * @return Message[]
	 */
	public function getFinalDescription() {
		$desc = $this->getDescription();
		Hooks::run( 'APIGetDescription', array( &$this, &$desc ) );
		$desc = self::escapeWikiText( $desc );
		if ( is_array( $desc ) ) {
			$desc = join( "\n", $desc );
		} else {
			$desc = (string)$desc;
		}

		$msg = ApiBase::makeMessage( $this->getDescriptionMessage(), $this->getContext(), array(
			$this->getModulePrefix(),
			$this->getModuleName(),
			$this->getModulePath(),
		) );
		if ( !$msg->exists() ) {
			$msg = $this->msg( 'api-help-fallback-description', $desc );
		}
		$msgs = array( $msg );

		Hooks::run( 'APIGetDescriptionMessages', array( $this, &$msgs ) );

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
			$params = array();
		}

		if ( $this->needsToken() ) {
			$params['token'] = array(
				ApiBase::PARAM_TYPE => 'string',
				ApiBase::PARAM_REQUIRED => true,
				ApiBase::PARAM_HELP_MSG => array(
					'api-help-param-token',
					$this->needsToken(),
				),
			) + ( isset( $params['token'] ) ? $params['token'] : array() );
		}

		Hooks::run( 'APIGetAllowedParams', array( &$this, &$params, $flags ) );

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

		$desc = $this->getParamDescription();
		Hooks::run( 'APIGetParamDescription', array( &$this, &$desc ) );

		if ( !$desc ) {
			$desc = array();
		}
		$desc = self::escapeWikiText( $desc );

		$params = $this->getFinalParams( ApiBase::GET_VALUES_FOR_HELP );
		$msgs = array();
		foreach ( $params as $param => $settings ) {
			if ( !is_array( $settings ) ) {
				$settings = array();
			}

			$d = isset( $desc[$param] ) ? $desc[$param] : '';
			if ( is_array( $d ) ) {
				// Special handling for prop parameters
				$d = array_map( function ( $line ) {
					if ( preg_match( '/^\s+(\S+)\s+-\s+(.+)$/', $line, $m ) ) {
						$line = "\n;{$m[1]}:{$m[2]}";
					}
					return $line;
				}, $d );
				$d = join( ' ', $d );
			}

			if ( isset( $settings[ApiBase::PARAM_HELP_MSG] ) ) {
				$msg = $settings[ApiBase::PARAM_HELP_MSG];
			} else {
				$msg = $this->msg( "apihelp-{$path}-param-{$param}" );
				if ( !$msg->exists() ) {
					$msg = $this->msg( 'api-help-fallback-parameter', $d );
				}
			}
			$msg = ApiBase::makeMessage( $msg, $this->getContext(),
				array( $prefix, $param, $name, $path ) );
			if ( !$msg ) {
				$this->dieDebug( __METHOD__,
					'Value in ApiBase::PARAM_HELP_MSG is not valid' );
			}
			$msgs[$param] = array( $msg );

			if ( isset( $settings[ApiBase::PARAM_HELP_MSG_PER_VALUE] ) ) {
				if ( !is_array( $settings[ApiBase::PARAM_HELP_MSG_PER_VALUE] ) ) {
					$this->dieDebug( __METHOD__,
						'ApiBase::PARAM_HELP_MSG_PER_VALUE is not valid' );
				}
				if ( !is_array( $settings[ApiBase::PARAM_TYPE] ) ) {
					$this->dieDebug( __METHOD__,
						'ApiBase::PARAM_HELP_MSG_PER_VALUE may only be used when ' .
						'ApiBase::PARAM_TYPE is an array' );
				}

				$valueMsgs = $settings[ApiBase::PARAM_HELP_MSG_PER_VALUE];
				foreach ( $settings[ApiBase::PARAM_TYPE] as $value ) {
					if ( isset( $valueMsgs[$value] ) ) {
						$msg = $valueMsgs[$value];
					} else {
						$msg = "apihelp-{$path}-paramvalue-{$param}-{$value}";
					}
					$m = ApiBase::makeMessage( $msg, $this->getContext(),
						array( $prefix, $param, $name, $path, $value ) );
					if ( $m ) {
						$m = new ApiHelpParamValueMessage(
							$value,
							array( $m->getKey(), 'api-help-param-no-description' ),
							$m->getParams()
						);
						$msgs[$param][] = $m->setContext( $this->getContext() );
					} else {
						$this->dieDebug( __METHOD__,
							"Value in ApiBase::PARAM_HELP_MSG_PER_VALUE for $value is not valid" );
					}
				}
			}

			if ( isset( $settings[ApiBase::PARAM_HELP_MSG_APPEND] ) ) {
				if ( !is_array( $settings[ApiBase::PARAM_HELP_MSG_APPEND] ) ) {
					$this->dieDebug( __METHOD__,
						'Value for ApiBase::PARAM_HELP_MSG_APPEND is not an array' );
				}
				foreach ( $settings[ApiBase::PARAM_HELP_MSG_APPEND] as $m ) {
					$m = ApiBase::makeMessage( $m, $this->getContext(),
						array( $prefix, $param, $name, $path ) );
					if ( $m ) {
						$msgs[$param][] = $m;
					} else {
						$this->dieDebug( __METHOD__,
							'Value in ApiBase::PARAM_HELP_MSG_APPEND is not valid' );
					}
				}
			}
		}

		Hooks::run( 'APIGetParamDescriptionMessages', array( $this, &$msgs ) );

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
		$flags = array();

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
			self::$extensionInfo = array(
				realpath( __DIR__ ) ?: __DIR__ => array(
					'path' => $IP,
					'name' => 'MediaWiki',
					'license-name' => 'GPL-2.0+',
				),
				realpath( "$IP/extensions" ) ?: "$IP/extensions" => null,
			);
			$keep = array(
				'path' => null,
				'name' => null,
				'namemsg' => null,
				'license-name' => null,
			);
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

	/// @deprecated since 1.24
	const PROP_ROOT = 'ROOT';
	/// @deprecated since 1.24
	const PROP_LIST = 'LIST';
	/// @deprecated since 1.24
	const PROP_TYPE = 0;
	/// @deprecated since 1.24
	const PROP_NULLABLE = 1;

	/**
	 * Formerly returned a string that identifies the version of the extending
	 * class. Typically included the class name, the svn revision, timestamp,
	 * and last author. Usually done with SVN's Id keyword
	 *
	 * @deprecated since 1.21, version string is no longer supported
	 * @return string
	 */
	public function getVersion() {
		wfDeprecated( __METHOD__, '1.21' );
		return '';
	}

	/**
	 * Formerly used to fetch a list of possible properites in the result,
	 * somehow organized with respect to the prop parameter that causes them to
	 * be returned. The specific semantics of the return value was never
	 * specified. Since this was never possible to be accurately updated, it
	 * has been removed.
	 *
	 * @deprecated since 1.24
	 * @return array|bool
	 */
	protected function getResultProperties() {
		wfDeprecated( __METHOD__, '1.24' );
		return false;
	}

	/**
	 * @see self::getResultProperties()
	 * @deprecated since 1.24
	 * @return array|bool
	 */
	public function getFinalResultProperties() {
		wfDeprecated( __METHOD__, '1.24' );
		return array();
	}

	/**
	 * @see self::getResultProperties()
	 * @deprecated since 1.24
	 */
	protected static function addTokenProperties( &$props, $tokenFunctions ) {
		wfDeprecated( __METHOD__, '1.24' );
	}

	/**
	 * @see self::getPossibleErrors()
	 * @deprecated since 1.24
	 * @return array
	 */
	public function getRequireOnlyOneParameterErrorMessages( $params ) {
		wfDeprecated( __METHOD__, '1.24' );
		return array();
	}

	/**
	 * @see self::getPossibleErrors()
	 * @deprecated since 1.24
	 * @return array
	 */
	public function getRequireMaxOneParameterErrorMessages( $params ) {
		wfDeprecated( __METHOD__, '1.24' );
		return array();
	}

	/**
	 * @see self::getPossibleErrors()
	 * @deprecated since 1.24
	 * @return array
	 */
	public function getRequireAtLeastOneParameterErrorMessages( $params ) {
		wfDeprecated( __METHOD__, '1.24' );
		return array();
	}

	/**
	 * @see self::getPossibleErrors()
	 * @deprecated since 1.24
	 * @return array
	 */
	public function getTitleOrPageIdErrorMessage() {
		wfDeprecated( __METHOD__, '1.24' );
		return array();
	}

	/**
	 * This formerly attempted to return a list of all possible errors returned
	 * by the module. However, this was impossible to maintain in many cases
	 * since errors could come from other areas of MediaWiki and in some cases
	 * from arbitrary extension hooks. Since a partial list claiming to be
	 * comprehensive is unlikely to be useful, it was removed.
	 *
	 * @deprecated since 1.24
	 * @return array
	 */
	public function getPossibleErrors() {
		wfDeprecated( __METHOD__, '1.24' );
		return array();
	}

	/**
	 * @see self::getPossibleErrors()
	 * @deprecated since 1.24
	 * @return array
	 */
	public function getFinalPossibleErrors() {
		wfDeprecated( __METHOD__, '1.24' );
		return array();
	}

	/**
	 * @see self::getPossibleErrors()
	 * @deprecated since 1.24
	 * @return array
	 */
	public function parseErrors( $errors ) {
		wfDeprecated( __METHOD__, '1.24' );
		return array();
	}

	/**
	 * Returns the description string for this module
	 *
	 * Ignored if an i18n message exists for
	 * "apihelp-{$this->getModulePath()}-description".
	 *
	 * @deprecated since 1.25
	 * @return Message|string|array
	 */
	protected function getDescription() {
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
		return array();
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
		return false;
	}

	/**
	 * Generates help message for this module, or false if there is no description
	 * @deprecated since 1.25
	 * @return string|bool
	 */
	public function makeHelpMsg() {
		wfDeprecated( __METHOD__, '1.25' );
		static $lnPrfx = "\n  ";

		$msg = $this->getFinalDescription();

		if ( $msg !== false ) {

			if ( !is_array( $msg ) ) {
				$msg = array(
					$msg
				);
			}
			$msg = $lnPrfx . implode( $lnPrfx, $msg ) . "\n";

			$msg .= $this->makeHelpArrayToString( $lnPrfx, false, $this->getHelpUrls() );

			if ( $this->isReadMode() ) {
				$msg .= "\nThis module requires read rights";
			}
			if ( $this->isWriteMode() ) {
				$msg .= "\nThis module requires write rights";
			}
			if ( $this->mustBePosted() ) {
				$msg .= "\nThis module only accepts POST requests";
			}
			if ( $this->isReadMode() || $this->isWriteMode() ||
				$this->mustBePosted()
			) {
				$msg .= "\n";
			}

			// Parameters
			$paramsMsg = $this->makeHelpMsgParameters();
			if ( $paramsMsg !== false ) {
				$msg .= "Parameters:\n$paramsMsg";
			}

			$examples = $this->getExamples();
			if ( $examples ) {
				if ( !is_array( $examples ) ) {
					$examples = array(
						$examples
					);
				}
				$msg .= "Example" . ( count( $examples ) > 1 ? 's' : '' ) . ":\n";
				foreach ( $examples as $k => $v ) {
					if ( is_numeric( $k ) ) {
						$msg .= "  $v\n";
					} else {
						if ( is_array( $v ) ) {
							$msgExample = implode( "\n", array_map( array( $this, 'indentExampleText' ), $v ) );
						} else {
							$msgExample = "  $v";
						}
						$msgExample .= ":";
						$msg .= wordwrap( $msgExample, 100, "\n" ) . "\n    $k\n";
					}
				}
			}
		}

		return $msg;
	}

	/**
	 * @deprecated since 1.25
	 * @param string $item
	 * @return string
	 */
	private function indentExampleText( $item ) {
		return "  " . $item;
	}

	/**
	 * @deprecated since 1.25
	 * @param string $prefix Text to split output items
	 * @param string $title What is being output
	 * @param string|array $input
	 * @return string
	 */
	protected function makeHelpArrayToString( $prefix, $title, $input ) {
		wfDeprecated( __METHOD__, '1.25' );
		if ( $input === false ) {
			return '';
		}
		if ( !is_array( $input ) ) {
			$input = array( $input );
		}

		if ( count( $input ) > 0 ) {
			if ( $title ) {
				$msg = $title . ( count( $input ) > 1 ? 's' : '' ) . ":\n  ";
			} else {
				$msg = '  ';
			}
			$msg .= implode( $prefix, $input ) . "\n";

			return $msg;
		}

		return '';
	}

	/**
	 * Generates the parameter descriptions for this module, to be displayed in the
	 * module's help.
	 * @deprecated since 1.25
	 * @return string|bool
	 */
	public function makeHelpMsgParameters() {
		wfDeprecated( __METHOD__, '1.25' );
		$params = $this->getFinalParams( ApiBase::GET_VALUES_FOR_HELP );
		if ( $params ) {
			$paramsDescription = $this->getFinalParamDescription();
			$msg = '';
			$paramPrefix = "\n" . str_repeat( ' ', 24 );
			$descWordwrap = "\n" . str_repeat( ' ', 28 );
			foreach ( $params as $paramName => $paramSettings ) {
				$desc = isset( $paramsDescription[$paramName] ) ? $paramsDescription[$paramName] : '';
				if ( is_array( $desc ) ) {
					$desc = implode( $paramPrefix, $desc );
				}

				//handle shorthand
				if ( !is_array( $paramSettings ) ) {
					$paramSettings = array(
						self::PARAM_DFLT => $paramSettings,
					);
				}

				//handle missing type
				if ( !isset( $paramSettings[ApiBase::PARAM_TYPE] ) ) {
					$dflt = isset( $paramSettings[ApiBase::PARAM_DFLT] )
						? $paramSettings[ApiBase::PARAM_DFLT]
						: null;
					if ( is_bool( $dflt ) ) {
						$paramSettings[ApiBase::PARAM_TYPE] = 'boolean';
					} elseif ( is_string( $dflt ) || is_null( $dflt ) ) {
						$paramSettings[ApiBase::PARAM_TYPE] = 'string';
					} elseif ( is_int( $dflt ) ) {
						$paramSettings[ApiBase::PARAM_TYPE] = 'integer';
					}
				}

				if ( isset( $paramSettings[self::PARAM_DEPRECATED] )
					&& $paramSettings[self::PARAM_DEPRECATED]
				) {
					$desc = "DEPRECATED! $desc";
				}

				if ( isset( $paramSettings[self::PARAM_REQUIRED] )
					&& $paramSettings[self::PARAM_REQUIRED]
				) {
					$desc .= $paramPrefix . "This parameter is required";
				}

				$type = isset( $paramSettings[self::PARAM_TYPE] )
					? $paramSettings[self::PARAM_TYPE]
					: null;
				if ( isset( $type ) ) {
					$hintPipeSeparated = true;
					$multi = isset( $paramSettings[self::PARAM_ISMULTI] )
						? $paramSettings[self::PARAM_ISMULTI]
						: false;
					if ( $multi ) {
						$prompt = 'Values (separate with \'|\'): ';
					} else {
						$prompt = 'One value: ';
					}

					if ( $type === 'submodule' ) {
						if ( isset( $paramSettings[self::PARAM_SUBMODULE_MAP] ) ) {
							$type = array_keys( $paramSettings[self::PARAM_SUBMODULE_MAP] );
						} else {
							$type = $this->getModuleManager()->getNames( $paramName );
						}
						sort( $type );
					}
					if ( is_array( $type ) ) {
						$choices = array();
						$nothingPrompt = '';
						foreach ( $type as $t ) {
							if ( $t === '' ) {
								$nothingPrompt = 'Can be empty, or ';
							} else {
								$choices[] = $t;
							}
						}
						$desc .= $paramPrefix . $nothingPrompt . $prompt;
						$choicesstring = implode( ', ', $choices );
						$desc .= wordwrap( $choicesstring, 100, $descWordwrap );
						$hintPipeSeparated = false;
					} else {
						switch ( $type ) {
							case 'namespace':
								// Special handling because namespaces are
								// type-limited, yet they are not given
								$desc .= $paramPrefix . $prompt;
								$desc .= wordwrap( implode( ', ', MWNamespace::getValidNamespaces() ),
									100, $descWordwrap );
								$hintPipeSeparated = false;
								break;
							case 'limit':
								$desc .= $paramPrefix . "No more than {$paramSettings[self::PARAM_MAX]}";
								if ( isset( $paramSettings[self::PARAM_MAX2] ) ) {
									$desc .= " ({$paramSettings[self::PARAM_MAX2]} for bots)";
								}
								$desc .= ' allowed';
								break;
							case 'integer':
								$s = $multi ? 's' : '';
								$hasMin = isset( $paramSettings[self::PARAM_MIN] );
								$hasMax = isset( $paramSettings[self::PARAM_MAX] );
								if ( $hasMin || $hasMax ) {
									if ( !$hasMax ) {
										$intRangeStr = "The value$s must be no less than " .
											"{$paramSettings[self::PARAM_MIN]}";
									} elseif ( !$hasMin ) {
										$intRangeStr = "The value$s must be no more than " .
											"{$paramSettings[self::PARAM_MAX]}";
									} else {
										$intRangeStr = "The value$s must be between " .
											"{$paramSettings[self::PARAM_MIN]} and {$paramSettings[self::PARAM_MAX]}";
									}

									$desc .= $paramPrefix . $intRangeStr;
								}
								break;
							case 'upload':
								$desc .= $paramPrefix . "Must be posted as a file upload using multipart/form-data";
								break;
						}
					}

					if ( $multi ) {
						if ( $hintPipeSeparated ) {
							$desc .= $paramPrefix . "Separate values with '|'";
						}

						$isArray = is_array( $type );
						if ( !$isArray
							|| $isArray && count( $type ) > self::LIMIT_SML1
						) {
							$desc .= $paramPrefix . "Maximum number of values " .
								self::LIMIT_SML1 . " (" . self::LIMIT_SML2 . " for bots)";
						}
					}
				}

				$default = isset( $paramSettings[self::PARAM_DFLT] ) ? $paramSettings[self::PARAM_DFLT] : null;
				if ( !is_null( $default ) && $default !== false ) {
					$desc .= $paramPrefix . "Default: $default";
				}

				$msg .= sprintf( "  %-19s - %s\n", $this->encodeParamName( $paramName ), $desc );
			}

			return $msg;
		}

		return false;
	}

	/**
	 * @deprecated since 1.25, always returns empty string
	 * @param DatabaseBase|bool $db
	 * @return string
	 */
	public function getModuleProfileName( $db = false ) {
		wfDeprecated( __METHOD__, '1.25' );
		return '';
	}

	/**
	 * @deprecated since 1.25
	 */
	public function profileIn() {
		// No wfDeprecated() yet because extensions call this and might need to
		// keep doing so for BC.
	}

	/**
	 * @deprecated since 1.25
	 */
	public function profileOut() {
		// No wfDeprecated() yet because extensions call this and might need to
		// keep doing so for BC.
	}

	/**
	 * @deprecated since 1.25
	 */
	public function safeProfileOut() {
		wfDeprecated( __METHOD__, '1.25' );
	}

	/**
	 * @deprecated since 1.25, always returns 0
	 * @return float
	 */
	public function getProfileTime() {
		wfDeprecated( __METHOD__, '1.25' );
		return 0;
	}

	/**
	 * @deprecated since 1.25
	 */
	public function profileDBIn() {
		wfDeprecated( __METHOD__, '1.25' );
	}

	/**
	 * @deprecated since 1.25
	 */
	public function profileDBOut() {
		wfDeprecated( __METHOD__, '1.25' );
	}

	/**
	 * @deprecated since 1.25, always returns 0
	 * @return float
	 */
	public function getProfileDBTime() {
		wfDeprecated( __METHOD__, '1.25' );
		return 0;
	}

	/**
	 * Get the result data array (read-only)
	 * @deprecated since 1.25, use $this->getResult() methods instead
	 * @return array
	 */
	public function getResultData() {
		wfDeprecated( __METHOD__, '1.25' );
		return $this->getResult()->getData();
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

	/**@}*/
}

/**
 * For really cool vim folding this needs to be at the end:
 * vim: foldmarker=@{,@} foldmethod=marker
 */
