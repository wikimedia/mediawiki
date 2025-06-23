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

namespace MediaWiki\Api;

use InvalidArgumentException;
use LogicException;
use MediaWiki\Api\Validator\SubmoduleDef;
use MediaWiki\Block\Block;
use MediaWiki\Context\ContextSource;
use MediaWiki\Context\IContextSource;
use MediaWiki\Exception\MWException;
use MediaWiki\HookContainer\HookContainer;
use MediaWiki\Language\RawMessage;
use MediaWiki\MainConfigNames;
use MediaWiki\MediaWikiServices;
use MediaWiki\Message\Message;
use MediaWiki\Page\PageIdentity;
use MediaWiki\Page\WikiPage;
use MediaWiki\ParamValidator\TypeDef\NamespaceDef;
use MediaWiki\Permissions\PermissionManager;
use MediaWiki\Permissions\PermissionStatus;
use MediaWiki\Registration\ExtensionRegistry;
use MediaWiki\Specials\SpecialVersion;
use MediaWiki\Status\Status;
use MediaWiki\Title\Title;
use MediaWiki\User\User;
use MediaWiki\User\UserRigorOptions;
use ReflectionClass;
use StatusValue;
use stdClass;
use Throwable;
use Wikimedia\Message\MessageSpecifier;
use Wikimedia\ParamValidator\ParamValidator;
use Wikimedia\ParamValidator\TypeDef\EnumDef;
use Wikimedia\ParamValidator\TypeDef\IntegerDef;
use Wikimedia\ParamValidator\TypeDef\StringDef;
use Wikimedia\Rdbms\IReadableDatabase;
use Wikimedia\Timestamp\TimestampException;

/**
 * This abstract class implements many basic API functions, and is the base of
 * all API classes.
 *
 * The class functions are divided into several areas of functionality:
 *
 * Module parameters: Derived classes can define getAllowedParams() to specify
 *  which parameters to expect, how to parse and validate them.
 *
 * Self-documentation: code to allow the API to document its own state
 *
 * @stable to extend
 *
 * @ingroup API
 */
abstract class ApiBase extends ContextSource {

	/** @var HookContainer */
	private $hookContainer;

	/** @var ApiHookRunner */
	private $hookRunner;

	/**
	 * @name   Old constants for ::getAllowedParams() arrays
	 * @{
	 */

	/**
	 * @deprecated since 1.35, use ParamValidator::PARAM_DEFAULT instead
	 */
	public const PARAM_DFLT = ParamValidator::PARAM_DEFAULT;
	/**
	 * @deprecated since 1.35, use ParamValidator::PARAM_ISMULTI instead
	 */
	public const PARAM_ISMULTI = ParamValidator::PARAM_ISMULTI;
	/**
	 * @deprecated since 1.35, use ParamValidator::PARAM_TYPE instead
	 */
	public const PARAM_TYPE = ParamValidator::PARAM_TYPE;
	/**
	 * @deprecated since 1.35, use IntegerDef::PARAM_MAX instead
	 */
	public const PARAM_MAX = IntegerDef::PARAM_MAX;
	/**
	 * @deprecated since 1.35, use IntegerDef::PARAM_MAX2 instead
	 */
	public const PARAM_MAX2 = IntegerDef::PARAM_MAX2;
	/**
	 * @deprecated since 1.35, use IntegerDef::PARAM_MIN instead
	 */
	public const PARAM_MIN = IntegerDef::PARAM_MIN;
	/**
	 * @deprecated since 1.35, use ParamValidator::PARAM_ALLOW_DUPLICATES instead
	 */
	public const PARAM_ALLOW_DUPLICATES = ParamValidator::PARAM_ALLOW_DUPLICATES;
	/**
	 * @deprecated since 1.35, use ParamValidator::PARAM_DEPRECATED instead
	 */
	public const PARAM_DEPRECATED = ParamValidator::PARAM_DEPRECATED;
	/**
	 * @deprecated since 1.35, use ParamValidator::PARAM_REQUIRED instead
	 */
	public const PARAM_REQUIRED = ParamValidator::PARAM_REQUIRED;
	/**
	 * @deprecated since 1.35, use SubmoduleDef::PARAM_SUBMODULE_MAP instead
	 */
	public const PARAM_SUBMODULE_MAP = SubmoduleDef::PARAM_SUBMODULE_MAP;
	/**
	 * @deprecated since 1.35, use SubmoduleDef::PARAM_SUBMODULE_PARAM_PREFIX instead
	 */
	public const PARAM_SUBMODULE_PARAM_PREFIX = SubmoduleDef::PARAM_SUBMODULE_PARAM_PREFIX;
	/**
	 * @deprecated since 1.35, use ParamValidator::PARAM_ALL instead
	 */
	public const PARAM_ALL = ParamValidator::PARAM_ALL;
	/**
	 * @deprecated since 1.35, use NamespaceDef::PARAM_EXTRA_NAMESPACES instead
	 */
	public const PARAM_EXTRA_NAMESPACES = NamespaceDef::PARAM_EXTRA_NAMESPACES;
	/**
	 * @deprecated since 1.35, use ParamValidator::PARAM_SENSITIVE instead
	 */
	public const PARAM_SENSITIVE = ParamValidator::PARAM_SENSITIVE;
	/**
	 * @deprecated since 1.35, use EnumDef::PARAM_DEPRECATED_VALUES instead
	 */
	public const PARAM_DEPRECATED_VALUES = EnumDef::PARAM_DEPRECATED_VALUES;
	/**
	 * @deprecated since 1.35, use ParamValidator::PARAM_ISMULTI_LIMIT1 instead
	 */
	public const PARAM_ISMULTI_LIMIT1 = ParamValidator::PARAM_ISMULTI_LIMIT1;
	/**
	 * @deprecated since 1.35, use ParamValidator::PARAM_ISMULTI_LIMIT2 instead
	 */
	public const PARAM_ISMULTI_LIMIT2 = ParamValidator::PARAM_ISMULTI_LIMIT2;
	/**
	 * @deprecated since 1.35, use StringDef::PARAM_MAX_BYTES instead
	 */
	public const PARAM_MAX_BYTES = StringDef::PARAM_MAX_BYTES;
	/**
	 * @deprecated since 1.35, use StringDef::PARAM_MAX_CHARS instead
	 */
	public const PARAM_MAX_CHARS = StringDef::PARAM_MAX_CHARS;
	/** @} */

	/**
	 * (boolean) Inverse of IntegerDef::PARAM_IGNORE_RANGE
	 * @deprecated since 1.35
	 */
	public const PARAM_RANGE_ENFORCE = 'api-param-range-enforce';

	// region   API-specific constants for ::getAllowedParams() arrays
	/** @name   API-specific constants for ::getAllowedParams() arrays */

	/**
	 * (string|array|Message) Specify an alternative i18n documentation message
	 * for this parameter. Default is apihelp-{$path}-param-{$param}.
	 * See Message::newFromSpecifier() for a description of allowed values.
	 * @since 1.25
	 */
	public const PARAM_HELP_MSG = 'api-param-help-msg';

	/**
	 * ((string|array|Message)[]) Specify additional i18n messages to append to
	 * the normal message for this parameter.
	 * See Message::newFromSpecifier() for a description of allowed values.
	 * @since 1.25
	 */
	public const PARAM_HELP_MSG_APPEND = 'api-param-help-msg-append';

	/**
	 * (array) Specify additional information tags for the parameter.
	 * The value is an array of arrays, with the first member being the 'tag' for the info
	 * and the remaining members being the values. In the help, this is
	 * formatted using apihelp-{$path}-paraminfo-{$tag}, which is passed
	 * $1 = count, $2 = comma-joined list of values, $3 = module prefix.
	 * @since 1.25
	 */
	public const PARAM_HELP_MSG_INFO = 'api-param-help-msg-info';

	/**
	 * Deprecated and unused.
	 * @since 1.25
	 * @deprecated since 1.35
	 */
	public const PARAM_VALUE_LINKS = 'api-param-value-links';

	/**
	 * ((string|array|Message)[]) When PARAM_TYPE is an array, or 'string'
	 * with PARAM_ISMULTI, this is an array mapping parameter values to help messages.
	 * See Message::newFromSpecifier() for a description of allowed values.
	 *
	 * When PARAM_TYPE is an array, any value not having a mapping will use
	 * the apihelp-{$path}-paramvalue-{$param}-{$value} message. (This means
	 * you can use an empty array to use the default message key for all
	 * values.)
	 *
	 * @since 1.25
	 * @note Use with PARAM_TYPE = 'string' is allowed since 1.40.
	 */
	public const PARAM_HELP_MSG_PER_VALUE = 'api-param-help-msg-per-value';

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
	public const PARAM_TEMPLATE_VARS = 'param-template-vars';

	// endregion -- end of API-specific constants for ::getAllowedParams() arrays

	public const ALL_DEFAULT_STRING = '*';

	/** Fast query, standard limit. */
	public const LIMIT_BIG1 = 500;
	/** Fast query, apihighlimits limit. */
	public const LIMIT_BIG2 = 5000;
	/** Slow query, standard limit. */
	public const LIMIT_SML1 = 50;
	/** Slow query, apihighlimits limit. */
	public const LIMIT_SML2 = 500;

	/**
	 * getAllowedParams() flag: When this is set, the result could take longer to generate,
	 * but should be more thorough. E.g. get the list of generators for ApiSandBox extension
	 * @since 1.21
	 */
	public const GET_VALUES_FOR_HELP = 1;

	/** @var array Maps extension paths to info arrays */
	private static $extensionInfo = null;

	/** @var stdClass[][] Cache for self::filterIDs() */
	private static $filterIDsCache = [];

	/** @var array Map of web UI block messages to corresponding API messages and codes */
	private const MESSAGE_CODE_MAP = [
		'actionthrottled' => [ 'apierror-ratelimited', 'ratelimited' ],
		'actionthrottledtext' => [ 'apierror-ratelimited', 'ratelimited' ],
	];

	/** @var ApiMain */
	private $mMainModule;

	// Adding inline type hints for these two fields is non-trivial because
	// of tests that create mocks for ApiBase subclasses and use
	// disableOriginalConstructor(): in those cases the constructor here is never
	// hit and thus these will be empty and any uses will raise a "Typed property
	// must not be accessed before initialization" error.
	/** @var string */
	private $mModuleName;
	/** @var string */
	private $mModulePrefix;

	/** @var IReadableDatabase|null */
	private $mReplicaDB = null;
	/**
	 * @var array
	 */
	private $mParamCache = [];
	/** @var array|null|false */
	private $mModuleSource = false;

	/**
	 * @stable to call
	 * @param ApiMain $mainModule
	 * @param string $moduleName Name of this module
	 * @param string $modulePrefix Prefix to use for parameter names
	 */
	public function __construct( ApiMain $mainModule, string $moduleName, string $modulePrefix = '' ) {
		$this->mMainModule = $mainModule;
		$this->mModuleName = $moduleName;
		$this->mModulePrefix = $modulePrefix;

		if ( !$this->isMain() ) {
			$this->setContext( $mainModule->getContext() );
		}
	}

	/***************************************************************************/
	// region   Methods to implement
	/** @name   Methods to implement */

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
	 * Get the module manager, or null if this module has no submodules.
	 *
	 * @since 1.21
	 * @stable to override
	 * @return ApiModuleManager|null
	 */
	public function getModuleManager() {
		return null;
	}

	/**
	 * If the module may only be used with a certain format module,
	 * it should override this method to return an instance of that formatter.
	 * A value of null means the default format will be used.
	 *
	 * @note Do not use this just because you don't want to support non-json
	 * formats. This should be used only when there is a fundamental
	 * requirement for a specific format.
	 *
	 * @stable to override
	 * @return ApiFormatBase|null An instance of a class derived from ApiFormatBase, or null
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
	 * @stable to override
	 * @return array
	 */
	protected function getExamplesMessages() {
		return [];
	}

	/**
	 * Return links to more detailed help pages about the module.
	 *
	 * @since 1.25, returning boolean false is deprecated
	 * @stable to override
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
	 * @stable to override
	 * @return array
	 */
	protected function getAllowedParams( /* $flags = 0 */ ) {
		// $flags is not declared because it causes "Strict standards"
		// warning. Most derived classes do not implement it.
		return [];
	}

	/**
	 * Indicates if this module needs maxlag to be checked.
	 *
	 * @stable to override
	 * @return bool
	 */
	public function shouldCheckMaxlag() {
		return true;
	}

	/**
	 * Indicates whether this module requires read rights.
	 *
	 * @stable to override
	 * @return bool
	 */
	public function isReadMode() {
		return true;
	}

	/**
	 * Indicates whether this module requires write access to the wiki.
	 *
	 * API modules must override this method to return true if the operation they will
	 * perform is not "safe" per RFC 7231 section 4.2.1. A module's operation is "safe"
	 * if it is essentially read-only, i.e. the client does not request nor expect any
	 * state change that would be observable in the responses to future requests.
	 *
	 * Implementations of this method must always return the same value, regardless of
	 * the parameters passed to the constructor or system state.
	 *
	 * Modules that do not require POST requests should only perform "safe" operations.
	 * Note that some modules might require POST requests because they need to support
	 * large input parameters and not because they perform non-"safe" operations.
	 *
	 * The information provided by this method is used to perform authorization checks.
	 * It can also be used to enforce proper routing of supposedly "safe" POST requests
	 * to the closest datacenter via the Promise-Non-Write-API-Action header.
	 *
	 * @see mustBePosted()
	 * @see needsToken()
	 *
	 * @stable to override
	 * @return bool
	 */
	public function isWriteMode() {
		return false;
	}

	/**
	 * Indicates whether this module must be called with a POST request.
	 *
	 * Implementations of this method must always return the same value,
	 * regardless of the parameters passed to the constructor or system state.
	 *
	 * @stable to override
	 * @return bool
	 */
	public function mustBePosted() {
		return $this->needsToken() !== false;
	}

	/**
	 * Indicates whether this module is deprecated.
	 *
	 * @since 1.25
	 * @stable to override
	 * @return bool
	 */
	public function isDeprecated() {
		return false;
	}

	/**
	 * Indicates whether this module is considered to be "internal".
	 *
	 * Internal API modules are not (yet) intended for 3rd party use and may be unstable.
	 *
	 * @since 1.25
	 * @stable to override
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
	 * @stable to override
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
	 * @stable to override
	 * @return string|array|null
	 */
	protected function getWebUITokenSalt( array $params ) {
		return null;
	}

	/**
	 * Returns data for HTTP conditional request mechanisms.
	 *
	 * @since 1.26
	 * @stable to override
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

	// endregion -- end of methods to implement

	/***************************************************************************/
	// region   Data access methods
	/** @name   Data access methods */

	/**
	 * Get the name of the module being executed by this instance.
	 *
	 * @return string
	 */
	public function getModuleName() {
		return $this->mModuleName;
	}

	/**
	 * Get parameter prefix (usually two letters or an empty string).
	 *
	 * @return string
	 */
	public function getModulePrefix() {
		return $this->mModulePrefix;
	}

	/**
	 * Get the main module.
	 *
	 * @return ApiMain
	 */
	public function getMain() {
		return $this->mMainModule;
	}

	/**
	 * Returns true if this module is the main module ($this === $this->mMainModule),
	 * false otherwise.
	 *
	 * @return bool
	 */
	public function isMain() {
		return $this === $this->mMainModule;
	}

	/**
	 * Get the parent of this module.
	 *
	 * @stable to override
	 * @since 1.25
	 * @return ApiBase|null
	 */
	public function getParent() {
		return $this->isMain() ? null : $this->getMain();
	}

	/**
	 * Used to avoid infinite loops - the ApiMain class should override some
	 * methods, if it doesn't and uses the default ApiBase implementation, which
	 * just calls the same method for the ApiMain instance, it'll lead to an infinite loop
	 *
	 * @param string $methodName used for debug messages
	 */
	private function dieIfMain( string $methodName ) {
		if ( $this->isMain() ) {
			self::dieDebug( $methodName, 'base method was called on main module.' );
		}
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
		// The Main module has this method overridden, avoid infinite loops
		$this->dieIfMain( __METHOD__ );

		return $this->getMain()->lacksSameOriginSecurity();
	}

	/**
	 * Get the path to this module.
	 *
	 * @since 1.25
	 * @return string
	 */
	public function getModulePath() {
		if ( $this->isMain() ) {
			return 'main';
		}

		if ( $this->getParent()->isMain() ) {
			return $this->getModuleName();
		}

		return $this->getParent()->getModulePath() . '+' . $this->getModuleName();
	}

	/**
	 * Get a module from its module path.
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

		foreach ( $parts as $i => $v ) {
			$parent = $module;
			$manager = $parent->getModuleManager();
			if ( $manager === null ) {
				$errorPath = implode( '+', array_slice( $parts, 0, $i ) );
				$this->dieWithError( [ 'apierror-badmodule-nosubmodules', $errorPath ], 'badmodule' );
			}
			$module = $manager->getModule( $v );

			if ( $module === null ) {
				$errorPath = $i
					? implode( '+', array_slice( $parts, 0, $i ) )
					: $parent->getModuleName();
				$this->dieWithError(
					[ 'apierror-badmodule-badsubmodule', $errorPath, wfEscapeWikiText( $v ) ],
					'badmodule'
				);
			}
		}

		return $module;
	}

	/**
	 * Get the result object.
	 *
	 * @return ApiResult
	 */
	public function getResult() {
		// The Main module has this method overridden, avoid infinite loops
		$this->dieIfMain( __METHOD__ );

		return $this->getMain()->getResult();
	}

	/**
	 * @stable to override
	 * @return ApiErrorFormatter
	 */
	public function getErrorFormatter() {
		// The Main module has this method overridden, avoid infinite loops
		$this->dieIfMain( __METHOD__ );

		return $this->getMain()->getErrorFormatter();
	}

	/**
	 * Gets a default replica DB connection object.
	 *
	 * @stable to override
	 * @return IReadableDatabase
	 */
	protected function getDB() {
		if ( !$this->mReplicaDB ) {
			$this->mReplicaDB = MediaWikiServices::getInstance()
				->getConnectionProvider()
				->getReplicaDatabase( false, 'api' );
		}

		return $this->mReplicaDB;
	}

	/**
	 * @return ApiContinuationManager|null
	 */
	public function getContinuationManager() {
		// The Main module has this method overridden, avoid infinite loops
		$this->dieIfMain( __METHOD__ );

		return $this->getMain()->getContinuationManager();
	}

	/**
	 * @param ApiContinuationManager|null $manager
	 */
	public function setContinuationManager( ?ApiContinuationManager $manager = null ) {
		// The Main module has this method overridden, avoid infinite loops
		$this->dieIfMain( __METHOD__ );

		$this->getMain()->setContinuationManager( $manager );
	}

	/**
	 * Obtain a PermissionManager instance that subclasses may use in their authorization checks.
	 *
	 * @since 1.34
	 * @return PermissionManager
	 */
	protected function getPermissionManager(): PermissionManager {
		return MediaWikiServices::getInstance()->getPermissionManager();
	}

	/**
	 * Get a HookContainer, for running extension hooks or for hook metadata.
	 *
	 * @since 1.35
	 * @return HookContainer
	 */
	protected function getHookContainer() {
		if ( !$this->hookContainer ) {
			$this->hookContainer = MediaWikiServices::getInstance()->getHookContainer();
		}
		return $this->hookContainer;
	}

	/**
	 * Get an ApiHookRunner for running core API hooks.
	 *
	 * @internal This is for use by core only. Hook interfaces may be removed
	 *  without notice.
	 * @since 1.35
	 * @return ApiHookRunner
	 */
	protected function getHookRunner() {
		if ( !$this->hookRunner ) {
			$this->hookRunner = new ApiHookRunner( $this->getHookContainer() );
		}
		return $this->hookRunner;
	}

	// endregion -- end of data access methods

	/***************************************************************************/
	// region   Parameter handling
	/** @name   Parameter handling */

	/**
	 * Indicate if the module supports dynamically-determined parameters that
	 * cannot be included in self::getAllowedParams().
	 * @stable to override
	 * @return string|array|Message|null Return null if the module does not
	 *  support additional dynamic parameters, otherwise return a message
	 *  describing them.
	 *  See Message::newFromSpecifier() for a description of allowed values.
	 */
	public function dynamicParameterDocumentation() {
		return null;
	}

	/**
	 * This method mangles parameter name based on the prefix supplied to the constructor.
	 * Override this method to change parameter name during runtime.
	 *
	 * @param string|string[] $paramName Parameter name
	 * @return string|string[] Prefixed parameter name
	 * @since 1.29 accepts an array of strings
	 */
	public function encodeParamName( $paramName ) {
		if ( is_array( $paramName ) ) {
			return array_map( function ( $name ) {
				return $this->mModulePrefix . $name;
			}, $paramName );
		}

		return $this->mModulePrefix . $paramName;
	}

	/**
	 * Using getAllowedParams(), this function makes an array of the values
	 * provided by the user, with the key being the name of the variable, and
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

		// @phan-suppress-next-line PhanTypePossiblyInvalidDimOffset False positive
		$parseLimit = (bool)$options['parseLimit'];
		$cacheKey = (int)$parseLimit;

		// Cache parameters, for performance and to avoid T26564.
		if ( !isset( $this->mParamCache[$cacheKey] ) ) {
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
				[ $name, $targets, $settings ] = array_shift( $toProcess );

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
								$results[$newName] = $this->getParameterFromSettings(
									$newName,
									$settings,
									$parseLimit
								);
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

			$this->mParamCache[$cacheKey] = $results;
		}

		$ret = $this->mParamCache[$cacheKey];
		if ( !$options['safeMode'] ) {
			foreach ( $ret as $v ) {
				if ( $v instanceof ApiUsageException ) {
					throw $v;
				}
			}
		}

		return $this->mParamCache[$cacheKey];
	}

	/**
	 * Get a value for the given parameter.
	 *
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
	 * Die if 0 or more than one of a certain set of parameters is set and not false.
	 *
	 * @param array $params User provided parameter set, as from $this->extractRequestParams()
	 * @param string ...$required Names of parameters of which exactly one must be set
	 */
	public function requireOnlyOneParameter( $params, ...$required ) {
		$intersection = array_intersect(
			array_keys( array_filter( $params, $this->parameterNotEmpty( ... ) ) ),
			$required
		);

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
					$required
				) ),
				count( $required ),
			], 'missingparam' );
		}
	}

	/**
	 * Dies if more than one parameter from a certain set of parameters are set and not false.
	 *
	 * @param array $params User provided parameters set, as from $this->extractRequestParams()
	 * @param string ...$required Parameter names that cannot have more than one set
	 */
	public function requireMaxOneParameter( $params, ...$required ) {
		$intersection = array_intersect(
			array_keys( array_filter( $params, $this->parameterNotEmpty( ... ) ) ),
			$required
		);

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
	 * Die if 0 of a certain set of parameters is set and not false.
	 *
	 * @since 1.23
	 * @param array $params User provided parameters set, as from $this->extractRequestParams()
	 * @param string ...$required Names of parameters of which at least one must be set
	 */
	public function requireAtLeastOneParameter( $params, ...$required ) {
		$intersection = array_intersect(
			array_keys( array_filter( $params, $this->parameterNotEmpty( ... ) ) ),
			$required
		);

		if ( count( $intersection ) == 0 ) {
			$this->dieWithError( [
				'apierror-missingparam-at-least-one-of',
				Message::listParam( array_map(
					function ( $p ) {
						return '<var>' . $this->encodeParamName( $p ) . '</var>';
					},
					$required
				) ),
				count( $required ),
			], 'missingparam' );
		}
	}

	/**
	 * Die with an "invalid param mix" error if the parameters contain the trigger parameter and
	 * any of the conflicting parameters.
	 *
	 * @since 1.44
	 *
	 * @param array $params User provided parameters set, as from $this->extractRequestParams()
	 * @param string $trigger The name of the trigger parameter
	 * @param string|string[] $conflicts The conflicting parameter or a list
	 *   of conflicting parameters
	 */
	public function requireNoConflictingParameters( $params, $trigger, $conflicts ) {
		$triggerValue = $params[$trigger] ?? null;
		if ( $triggerValue === null || $triggerValue === false ) {
			return;
		}
		$intersection = array_intersect(
			array_keys( array_filter( $params, $this->parameterNotEmpty( ... ) ) ),
			(array)$conflicts
		);
		if ( count( $intersection ) ) {
			$this->dieWithError( [
				'apierror-invalidparammix-cannotusewith',
				Message::listParam( array_map(
					function ( $p ) {
						return '<var>' . $this->encodeParamName( $p ) . '</var>';
					},
					array_values( $intersection )
				) ),
				$trigger,
			] );
		}
	}

	/**
	 * Die if any of the specified parameters were found in the query part of
	 * the URL rather than the HTTP post body contents.
	 *
	 * @since 1.28
	 * @param string[] $params Parameters to check
	 * @param string $prefix Set to 'noprefix' to skip calling $this->encodeParamName()
	 */
	public function requirePostedParameters( $params, $prefix = 'prefix' ) {
		if ( !$this->mustBePosted() ) {
			// In order to allow client code to choose the correct method (GET or POST) depending *only*
			// on mustBePosted(), make sure that the module requires posting if any of its potential
			// parameters require posting.

			// TODO: Uncomment this
			// throw new LogicException( 'mustBePosted() must be true when using requirePostedParameters()' );

			// This seems to already be the case in all modules in practice, but deprecate it first just
			// in case.
			wfDeprecatedMsg( 'mustBePosted() must be true when using requirePostedParameters()',
				'1.42' );
		}

		// Skip if $wgDebugAPI is set, or if we're in internal mode
		if ( $this->getConfig()->get( MainConfigNames::DebugAPI ) ||
		$this->getMain()->isInternalMode() ) {
			return;
		}

		$queryValues = $this->getRequest()->getQueryValuesOnly();
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
	 * Callback function used in requireOnlyOneParameter to check whether required parameters are set.
	 *
	 * @param mixed $x Parameter to check is not null/false
	 * @return bool
	 */
	private function parameterNotEmpty( $x ) {
		return $x !== null && $x !== false;
	}

	/**
	 * Attempts to load a WikiPage object from a title or pageid parameter, if possible.
	 * It can die if no param is set or if the title or page ID is not valid.
	 *
	 * @param array $params User provided parameter set, as from $this->extractRequestParams()
	 * @param string|false $load Whether load the object's state from the database:
	 *  - false: don't load (if the pageid is given, it will still be loaded)
	 *  - 'fromdb': load from a replica DB
	 *  - 'fromdbmaster': load from the primary database
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
			// @phan-suppress-next-line PhanTypeMismatchArgumentNullable T240141
			$pageObj = MediaWikiServices::getInstance()->getWikiPageFactory()->newFromTitle( $titleObj );
			if ( $load !== false ) {
				$pageObj->loadPageData( $load );
			}
		} elseif ( isset( $params['pageid'] ) ) {
			if ( $load === false ) {
				$load = 'fromdb';
			}
			$pageObj = MediaWikiServices::getInstance()->getWikiPageFactory()->newFromID( $params['pageid'], $load );
			if ( !$pageObj ) {
				$this->dieWithError( [ 'apierror-nosuchpageid', $params['pageid'] ] );
			}
		}

		// @phan-suppress-next-line PhanTypeMismatchReturnNullable requireOnlyOneParameter guard it is always set
		return $pageObj;
	}

	/**
	 * Get a Title object from a title or pageid param, if it is possible.
	 * It can die if no param is set or if the title or page ID is not valid.
	 *
	 * @since 1.29
	 * @param array $params User provided parameter set, as from $this->extractRequestParams()
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
			// @phan-suppress-next-line PhanTypeMismatchReturnNullable T240141
			return $titleObj;
		}

		if ( isset( $params['pageid'] ) ) {
			$titleObj = Title::newFromID( $params['pageid'] );
			if ( !$titleObj ) {
				$this->dieWithError( [ 'apierror-nosuchpageid', $params['pageid'] ] );
			}
		}

		// @phan-suppress-next-line PhanTypeMismatchReturnNullable requireOnlyOneParameter guard it is always set
		return $titleObj;
	}

	/**
	 * Using the settings, determine the value for the given parameter.
	 *
	 * @param string $name Parameter name
	 * @param array|mixed $settings Default value or an array of settings
	 *  using PARAM_* constants.
	 * @param bool $parseLimit Whether to parse and validate 'limit' parameters
	 * @return mixed Parameter value
	 */
	protected function getParameterFromSettings( $name, $settings, $parseLimit ) {
		$validator = $this->getMain()->getParamValidator();
		$value = $validator->getValue( $this, $name, $settings, [
			'parse-limit' => $parseLimit,
			'raw' => ( $settings[ParamValidator::PARAM_TYPE] ?? '' ) === 'raw',
		] );

		// @todo Deprecate and remove this, if possible.
		if ( $parseLimit && isset( $settings[ParamValidator::PARAM_TYPE] ) &&
			$settings[ParamValidator::PARAM_TYPE] === 'limit' &&
			$this->getMain()->getVal( $this->encodeParamName( $name ) ) === 'max'
		) {
			$this->getResult()->addParsedLimit( $this->getModuleName(), $value );
		}

		return $value;
	}

	/**
	 * Handle when a parameter was Unicode-normalized.
	 *
	 * @since 1.28
	 * @since 1.35 $paramName is prefixed
	 * @internal For overriding by subclasses and use by ApiParamValidatorCallbacks only.
	 * @param string $paramName Prefixed parameter name
	 * @param string $value Input that will be used.
	 * @param string $rawValue Input before normalization.
	 */
	public function handleParamNormalization( $paramName, $value, $rawValue ) {
		$this->addWarning( [ 'apiwarn-badutf8', $paramName ] );
	}

	/**
	 * Validate the supplied token.
	 *
	 * @since 1.24
	 * @param string $token Supplied token
	 * @param array $params All supplied parameters for the module
	 * @return bool
	 */
	final public function validateToken( $token, array $params ) {
		$tokenType = $this->needsToken();
		$salts = ApiQueryTokens::getTokenTypeSalts();
		if ( !isset( $salts[$tokenType] ) ) {
			throw new LogicException(
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

		return $webUiSalt !== null && $this->getUser()->matchEditToken(
			$token, $webUiSalt, $this->getRequest()
		);
	}

	// endregion -- end of parameter handling

	/***************************************************************************/
	// region   Utility methods
	/** @name   Utility methods */

	/**
	 * Gets the user for whom to get the watchlist
	 *
	 * @param array $params
	 * @return User
	 */
	public function getWatchlistUser( $params ) {
		if ( $params['owner'] !== null && $params['token'] !== null ) {
			$services = MediaWikiServices::getInstance();
			$user = $services->getUserFactory()->newFromName( $params['owner'], UserRigorOptions::RIGOR_NONE );
			if ( !$user || !$user->isRegistered() ) {
				$this->dieWithError(
					[ 'nosuchusershort', wfEscapeWikiText( $params['owner'] ) ], 'bad_wlowner'
				);
			}
			// @phan-suppress-next-line PhanTypeMismatchArgumentNullable T240141
			$token = $services->getUserOptionsLookup()->getOption( $user, 'watchlisttoken' );
			if ( $token == '' || !hash_equals( $token, $params['token'] ) ) {
				$this->dieWithError( 'apierror-bad-watchlist-token', 'bad_wltoken' );
			}
		} else {
			$user = $this->getUser();
			if ( !$user->isRegistered() ) {
				$this->dieWithError( 'watchlistanontext', 'notloggedin' );
			}
			$this->checkUserRightsAny( 'viewmywatchlist' );
		}

		// @phan-suppress-next-line PhanTypeMismatchReturnNullable T240141
		return $user;
	}

	/**
	 * Create a Message from a string or array
	 *
	 * A string is used as a message key. An array has the message key as the
	 * first value and message parameters as subsequent values.
	 *
	 * @since 1.25
	 * @deprecated since 1.43, use ApiBase::msg()
	 * @param string|array|Message $msg
	 * @phan-param string|non-empty-array|Message $msg
	 * @param IContextSource $context
	 * @param array|null $params
	 * @return Message|null
	 */
	public static function makeMessage( $msg, IContextSource $context, ?array $params = null ) {
		wfDeprecated( __METHOD__, '1.43' );
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
	 * Call wfTransactionalTimeLimit() if this request was POSTed.
	 *
	 * @since 1.26
	 */
	protected function useTransactionalTimeLimit() {
		if ( $this->getRequest()->wasPosted() ) {
			wfTransactionalTimeLimit();
		}
	}

	/**
	 * Reset static caches of database state.
	 *
	 * @internal For testing only
	 */
	public static function clearCacheForTest(): void {
		if ( !defined( 'MW_PHPUNIT_TEST' ) ) {
			throw new LogicException( 'Not allowed outside tests' );
		}
		self::$filterIDsCache = [];
	}

	/**
	 * Filter out-of-range values from a list of positive integer IDs
	 *
	 * @since 1.33
	 * @param string[][] $fields Array of table and field pairs to check
	 * @param (string|int)[] $ids IDs to filter. Strings in the array are
	 *  expected to be stringified integers.
	 * @return (string|int)[] Filtered IDs.
	 */
	protected function filterIDs( $fields, array $ids ) {
		$min = INF;
		$max = 0;
		foreach ( $fields as [ $table, $field ] ) {
			if ( isset( self::$filterIDsCache[$table][$field] ) ) {
				$row = self::$filterIDsCache[$table][$field];
			} else {
				$row = $this->getDB()->newSelectQueryBuilder()
					->select( [ 'min_id' => "MIN($field)", 'max_id' => "MAX($field)" ] )
					->from( $table )
					->caller( __METHOD__ )->fetchRow();
				self::$filterIDsCache[$table][$field] = $row;
			}
			$min = min( $min, $row->min_id );
			$max = max( $max, $row->max_id );
		}
		return array_filter( $ids, static function ( $id ) use ( $min, $max ) {
			return ( ( is_int( $id ) && $id >= 0 ) || ctype_digit( (string)$id ) )
				&& $id >= $min && $id <= $max;
		} );
	}

	// endregion -- end of utility methods

	/***************************************************************************/
	// region   Warning and error reporting
	/** @name   Warning and error reporting */

	/**
	 * Add a warning for this module.
	 *
	 * Users should monitor this section to notice any changes in the API.
	 *
	 * Multiple calls to this function will result in multiple warning messages.
	 *
	 * If $msg is not an ApiMessage, the message code will be derived from the
	 * message key by stripping any "apiwarn-" or "apierror-" prefix.
	 *
	 * @since 1.29
	 * @param string|array|MessageSpecifier $msg See ApiErrorFormatter::addWarning()
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
	 * @param string|array|MessageSpecifier $msg See ApiErrorFormatter::addWarning()
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
		$this->getHookRunner()->onApiDeprecationHelp( $msgs );
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
	 * @param string|array|MessageSpecifier $msg See ApiErrorFormatter::addError()
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
	 * @param string|array|MessageSpecifier $msg See ApiErrorFormatter::addError()
	 * @param string|null $code See ApiErrorFormatter::addError()
	 * @param array|null $data See ApiErrorFormatter::addError()
	 * @param int $httpCode HTTP error code to use
	 * @throws ApiUsageException always
	 * @return never
	 */
	public function dieWithError( $msg, $code = null, $data = null, $httpCode = 0 ): never {
		throw ApiUsageException::newWithMessage( $this, $msg, $code, $data, $httpCode );
	}

	/**
	 * Abort execution with an error derived from a throwable
	 *
	 * @since 1.29
	 * @param Throwable $exception See ApiErrorFormatter::getMessageFromException()
	 * @param array $options See ApiErrorFormatter::getMessageFromException()
	 * @throws ApiUsageException always
	 * @return never
	 */
	public function dieWithException( Throwable $exception, array $options = [] ): never {
		$this->dieWithError(
			$this->getErrorFormatter()->getMessageFromException( $exception, $options )
		);
	}

	/**
	 * Throw an ApiUsageException, which will (if uncaught) call the main module's
	 * error handler and die with an error message including block info.
	 *
	 * @since 1.27
	 * @param Block $block The block used to generate the ApiUsageException
	 * @throws ApiUsageException always
	 * @return never
	 */
	public function dieBlocked( Block $block ): never {
		$blockErrorFormatter = MediaWikiServices::getInstance()->getFormatterFactory()
			->getBlockErrorFormatter( $this->getContext() );

		$msg = $blockErrorFormatter->getMessage(
			$block,
			$this->getUser(),
			null,
			$this->getRequest()->getIP()
		);

		$this->dieWithError( $msg );
	}

	/**
	 * Throw an ApiUsageException based on the Status object.
	 *
	 * @since 1.22
	 * @since 1.29 Accepts a StatusValue
	 * @param StatusValue $status
	 * @throws ApiUsageException always
	 * @return never
	 */
	public function dieStatus( StatusValue $status ): never {
		if ( $status->isGood() ) {
			throw new InvalidArgumentException( 'Successful status passed to ApiBase::dieStatus' );
		}

		foreach ( self::MESSAGE_CODE_MAP as $msg => [ $apiMsg, $code ] ) {
			if ( $status->hasMessage( $msg ) ) {
				$status->replaceMessage( $msg, ApiMessage::create( $apiMsg, $code ) );
			}
		}

		if (
			$status instanceof PermissionStatus
			&& $status->isRateLimitExceeded()
			&& !$status->hasMessage( 'apierror-ratelimited' )
		) {
			$status->fatal( ApiMessage::create( 'apierror-ratelimited', 'ratelimited' ) );
		}

		// ApiUsageException needs a fatal status, but this method has
		// historically accepted any non-good status. Convert it if necessary.
		$status->setOK( false );
		if ( !$status->getMessages( 'error' ) ) {
			$newStatus = Status::newGood();
			foreach ( $status->getMessages( 'warning' ) as $err ) {
				$newStatus->fatal( $err );
			}
			if ( !$newStatus->getMessages( 'error' ) ) {
				$newStatus->fatal( 'unknownerror-nocode' );
			}
			$status = $newStatus;
		}

		throw new ApiUsageException( $this, $status );
	}

	/**
	 * Helper function for readonly errors.
	 *
	 * @throws ApiUsageException always
	 * @return never
	 */
	public function dieReadOnly(): never {
		$this->dieWithError(
			'apierror-readonly',
			'readonly',
			[ 'readonlyreason' => MediaWikiServices::getInstance()->getReadOnlyMode()->getReason() ]
		);
	}

	/**
	 * Helper function for permission-denied errors.
	 *
	 * @since 1.29
	 * @param string|string[] $rights
	 * @throws ApiUsageException if the user doesn't have any of the rights.
	 *  The error message is based on $rights[0].
	 */
	public function checkUserRightsAny( $rights ) {
		$rights = (array)$rights;
		if ( !$this->getAuthority()->isAllowedAny( ...$rights ) ) {
			$this->dieWithError( [ 'apierror-permissiondenied', $this->msg( "action-{$rights[0]}" ) ] );
		}
	}

	/**
	 * Helper function for permission-denied errors.
	 *
	 * @param PageIdentity $pageIdentity
	 * @param string|string[] $actions
	 * @param array $options Additional options
	 *  - user: (User) User to use rather than $this->getUser().
	 *  - autoblock: (bool, default false) Whether to spread autoblocks.
	 * @phan-param array{user?:User,autoblock?:bool} $options
	 *
	 * @throws ApiUsageException if the user doesn't have all the necessary rights.
	 *
	 * @since 1.29
	 * @since 1.33 Changed the third parameter from $user to $options.
	 * @since 1.36 deprecated passing LinkTarget as first parameter
	 */
	public function checkTitleUserPermissions(
		PageIdentity $pageIdentity,
		$actions,
		array $options = []
	) {
		$authority = $options['user'] ?? $this->getAuthority();
		$status = new PermissionStatus();
		foreach ( (array)$actions as $action ) {
			if ( $this->isWriteMode() ) {
				$authority->authorizeWrite( $action, $pageIdentity, $status );
			} else {
				$authority->authorizeRead( $action, $pageIdentity, $status );
			}
		}
		if ( !$status->isGood() ) {
			if ( !empty( $options['autoblock'] ) ) {
				$this->getUser()->spreadAnyEditBlock();
			}
			$this->dieStatus( $status );
		}
	}

	/**
	 * Will only set a warning instead of failing if the global $wgDebugAPI
	 * is set to true.
	 *
	 * Otherwise, it behaves exactly as self::dieWithError().
	 *
	 * @since 1.29
	 * @param string|array|Message $msg Message definition, see Message::newFromSpecifier()
	 * @param string|null $code
	 * @param array|null $data
	 * @param int|null $httpCode
	 * @throws ApiUsageException
	 */
	public function dieWithErrorOrDebug( $msg, $code = null, $data = null, $httpCode = null ) {
		if ( $this->getConfig()->get( MainConfigNames::DebugAPI ) !== true ) {
			$this->dieWithError( $msg, $code, $data, $httpCode ?? 0 );
		} else {
			$this->addWarning( $msg, $code, $data );
		}
	}

	/**
	 * Parse the 'continue' parameter in the usual format and validate the types of each part,
	 * or die with the 'badcontinue' error if the format, types, or the number of parts is wrong.
	 *
	 * @param string $continue Value of 'continue' parameter obtained from extractRequestParams()
	 * @param string[] $types Types of the expected parts in order, 'string', 'int' or 'timestamp'
	 * @return mixed[] Array containing strings, integers or timestamps
	 * @throws ApiUsageException
	 * @since 1.40
	 */
	protected function parseContinueParamOrDie( string $continue, array $types ): array {
		$cont = explode( '|', $continue );
		$this->dieContinueUsageIf( count( $cont ) != count( $types ) );

		foreach ( $cont as $i => &$value ) {
			switch ( $types[$i] ) {
				case 'string':
					// Do nothing
					break;
				case 'int':
					$this->dieContinueUsageIf( $value !== (string)(int)$value );
					$value = (int)$value;
					break;
				case 'timestamp':
					try {
						$dbTs = $this->getDB()->timestamp( $value );
					} catch ( TimestampException ) {
						$dbTs = false;
					}
					$this->dieContinueUsageIf( $value !== $dbTs );
					break;
				default:
					throw new InvalidArgumentException( "Unknown type '{$types[$i]}'" );
			}
		}

		return $cont;
	}

	/**
	 * Die with the 'badcontinue' error.
	 *
	 * This call is common enough to make it into the base method.
	 *
	 * @param bool $condition Will only die if this value is true
	 * @throws ApiUsageException
	 * @since 1.21
	 * @phan-assert-false-condition $condition
	 */
	protected function dieContinueUsageIf( $condition ) {
		if ( $condition ) {
			$this->dieWithError( 'apierror-badcontinue' );
		}
	}

	/**
	 * Internal code errors should be reported with this method.
	 *
	 * @param string $method Method or function name
	 * @param string $message Error message
	 * @return never
	 */
	protected static function dieDebug( $method, $message ): never {
		throw new MWException( "Internal error in $method: $message" );
	}

	/**
	 * Write logging information for API features to a debug log, for usage
	 * analysis.
	 *
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
			// Replace spaces with underscores in 'username' for historical reasons.
			'username' => str_replace( ' ', '_', $this->getUser()->getName() ),
			'clientip' => $request->getIP(),
			'referer' => (string)$request->getHeader( 'Referer' ),
			'agent' => $this->getMain()->getUserAgent(),
		];

		// Text string is deprecated. Remove (or replace with just $feature) in MW 1.34.
		$s = '"' . addslashes( $ctx['feature'] ) . '"' .
			' "' . wfUrlencode( $ctx['username'] ) . '"' .
			' "' . $ctx['clientip'] . '"' .
			' "' . addslashes( $ctx['referer'] ) . '"' .
			' "' . addslashes( $ctx['agent'] ) . '"';

		wfDebugLog( 'api-feature-usage', $s, 'private', $ctx );

		$this->getHookRunner()->onApiLogFeatureUsage(
			$feature,
			[
				'userName' => $this->getUser()->getName(),
				'userAgent' => $this->getMain()->getUserAgent(),
				'ipAddress' => $request->getIP()
			]
		);
	}

	// endregion -- end of warning and error reporting

	/***************************************************************************/
	// region   Help message generation
	/** @name   Help message generation */

	/**
	 * Return the summary message.
	 *
	 * This is a one-line description of the module, suitable for display in a
	 * list of modules.
	 *
	 * @since 1.30
	 * @stable to override
	 * @return string|array|Message Message definition, see Message::newFromSpecifier()
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
	 * @stable to override
	 * @return string|array|Message Message definition, see Message::newFromSpecifier().
	 *   When returning an array, the definition may also specify fallback keys.
	 */
	protected function getExtendedDescription() {
		return [ [
			"apihelp-{$this->getModulePath()}-extended-description",
			'api-help-no-extended-description',
		] ];
	}

	/**
	 * Get the final module summary
	 *
	 * @since 1.30
	 * @stable to override
	 * @return Message
	 */
	public function getFinalSummary() {
		return $this->msg(
			Message::newFromSpecifier( $this->getSummaryMessage() ),
			$this->getModulePrefix(),
			$this->getModuleName(),
			$this->getModulePath(),
		);
	}

	/**
	 * Get the final module description, after hooks have had a chance to tweak it as
	 * needed.
	 *
	 * @since 1.25, returns Message[] rather than string[]
	 * @return Message[]
	 */
	public function getFinalDescription() {
		$summary = $this->msg(
			Message::newFromSpecifier( $this->getSummaryMessage() ),
			$this->getModulePrefix(),
			$this->getModuleName(),
			$this->getModulePath(),
		);
		$extendedDesc = $this->getExtendedDescription();
		if ( is_array( $extendedDesc ) && is_array( $extendedDesc[0] ) ) {
			// The definition in getExtendedDescription() may also specify fallback keys. This is weird,
			// and it was never needed for other API doc messages, so it's only supported here.
			$extendedDesc = Message::newFallbackSequence( $extendedDesc[0] )
				->params( array_slice( $extendedDesc, 1 ) );
		}
		$extendedDesc = $this->msg(
			Message::newFromSpecifier( $extendedDesc ),
			$this->getModulePrefix(),
			$this->getModuleName(),
			$this->getModulePath(),
		);

		$msgs = [ $summary, $extendedDesc ];

		$this->getHookRunner()->onAPIGetDescriptionMessages( $this, $msgs );

		return $msgs;
	}

	/**
	 * Get the final list of parameters, after hooks have had a chance to
	 * tweak it as needed.
	 *
	 * @param int $flags Zero or more flags like GET_VALUES_FOR_HELP
	 * @return array
	 * @since 1.21 $flags param added
	 */
	public function getFinalParams( $flags = 0 ) {
		// @phan-suppress-next-line PhanParamTooMany
		$params = $this->getAllowedParams( $flags );
		if ( !$params ) {
			$params = [];
		}

		if ( $this->needsToken() ) {
			$params['token'] = [
				ParamValidator::PARAM_TYPE => 'string',
				ParamValidator::PARAM_REQUIRED => true,
				ParamValidator::PARAM_SENSITIVE => true,
				self::PARAM_HELP_MSG => [
					'api-help-param-token',
					$this->needsToken(),
				],
			] + ( $params['token'] ?? [] );
		}

		$this->getHookRunner()->onAPIGetAllowedParams( $this, $params, $flags );

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

			$msg = isset( $settings[self::PARAM_HELP_MSG] )
				? Message::newFromSpecifier( $settings[self::PARAM_HELP_MSG] )
				: Message::newFallbackSequence( [ "apihelp-$path-param-$param", 'api-help-param-no-description' ] );
			$msg = $this->msg( $msg, $prefix, $param, $name, $path );
			$msgs[$param] = [ $msg ];

			if ( isset( $settings[ParamValidator::PARAM_TYPE] ) &&
				$settings[ParamValidator::PARAM_TYPE] === 'submodule'
			) {
				if ( isset( $settings[SubmoduleDef::PARAM_SUBMODULE_MAP] ) ) {
					$map = $settings[SubmoduleDef::PARAM_SUBMODULE_MAP];
				} else {
					$prefix = $this->isMain() ? '' : ( $this->getModulePath() . '+' );
					$map = [];
					foreach ( $this->getModuleManager()->getNames( $param ) as $submoduleName ) {
						$map[$submoduleName] = $prefix . $submoduleName;
					}
				}

				$submodules = [];
				$submoduleFlags = []; // for sorting: higher flags are sorted later
				$submoduleNames = []; // for sorting: lexicographical, ascending
				foreach ( $map as $v => $m ) {
					$isDeprecated = false;
					$isInternal = false;
					$summary = null;
					try {
						$submod = $this->getModuleFromPath( $m );
						if ( $submod ) {
							$summary = $submod->getFinalSummary();
							$isDeprecated = $submod->isDeprecated();
							$isInternal = $submod->isInternal();
						}
					} catch ( ApiUsageException ) {
						// Ignore
					}
					if ( $summary ) {
						$key = $summary->getKey();
						$params = $summary->getParams();
					} else {
						$key = 'api-help-undocumented-module';
						$params = [ $m ];
					}
					$m = new ApiHelpParamValueMessage(
						"[[Special:ApiHelp/$m|$v]]",
						$key,
						$params,
						$isDeprecated,
						$isInternal
					);
					$submodules[] = $m->setContext( $this->getContext() );
					$submoduleFlags[] = ( $isDeprecated ? 1 : 0 ) | ( $isInternal ? 2 : 0 );
					$submoduleNames[] = $v;
				}
				// sort $submodules by $submoduleFlags and $submoduleNames
				array_multisort( $submoduleFlags, $submoduleNames, $submodules );
				$msgs[$param] = array_merge( $msgs[$param], $submodules );
			} elseif ( isset( $settings[self::PARAM_HELP_MSG_PER_VALUE] ) ) {
				// ! keep these checks in sync with \MediaWiki\Api\Validator\ApiParamValidator::checkSettings
				if ( !is_array( $settings[self::PARAM_HELP_MSG_PER_VALUE] ) ) {
					self::dieDebug( __METHOD__,
						'ApiBase::PARAM_HELP_MSG_PER_VALUE is not valid' );
				}
				$isArrayOfStrings = is_array( $settings[ParamValidator::PARAM_TYPE] )
					|| (
						$settings[ParamValidator::PARAM_TYPE] === 'string'
						&& ( $settings[ParamValidator::PARAM_ISMULTI] ?? false )
					);
				if ( !$isArrayOfStrings ) {
					self::dieDebug( __METHOD__,
						'ApiBase::PARAM_HELP_MSG_PER_VALUE may only be used when ' .
						'ParamValidator::PARAM_TYPE is an array or it is \'string\' and ' .
						'ParamValidator::PARAM_ISMULTI is true' );
				}

				$values = is_array( $settings[ParamValidator::PARAM_TYPE] ) ?
					$settings[ParamValidator::PARAM_TYPE] :
					array_keys( $settings[self::PARAM_HELP_MSG_PER_VALUE] );
				$valueMsgs = $settings[self::PARAM_HELP_MSG_PER_VALUE];
				$deprecatedValues = $settings[EnumDef::PARAM_DEPRECATED_VALUES] ?? [];

				foreach ( $values as $value ) {
					$msg = Message::newFromSpecifier( $valueMsgs[$value] ?? "apihelp-$path-paramvalue-$param-$value" );
					$m = $this->msg( $msg, [ $prefix, $param, $name, $path, $value ] );
					$m = new ApiHelpParamValueMessage(
						$value,
						// @phan-suppress-next-line PhanTypeMismatchArgumentProbablyReal
						[ $m->getKey(), 'api-help-param-no-description' ],
						$m->getParams(),
						isset( $deprecatedValues[$value] )
					);
					$msgs[$param][] = $m->setContext( $this->getContext() );
				}
			}

			if ( isset( $settings[self::PARAM_HELP_MSG_APPEND] ) ) {
				if ( !is_array( $settings[self::PARAM_HELP_MSG_APPEND] ) ) {
					self::dieDebug( __METHOD__,
						'Value for ApiBase::PARAM_HELP_MSG_APPEND is not an array' );
				}
				foreach ( $settings[self::PARAM_HELP_MSG_APPEND] as $m ) {
					$m = $this->msg( Message::newFromSpecifier( $m ), [ $prefix, $param, $name, $path ] );
					$msgs[$param][] = $m;
				}
			}
		}

		$this->getHookRunner()->onAPIGetParamDescriptionMessages( $this, $msgs );

		return $msgs;
	}

	/**
	 * Generates the list of flags for the help screen and for action=paraminfo.
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
	 *  - path: Install path
	 *  - name: Extension name, or "MediaWiki" for core
	 *  - namemsg: (optional) i18n message key for a display name
	 *  - license-name: (optional) Name of license
	 *
	 * @return array|null
	 */
	protected function getModuleSourceInfo() {
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

		// Build a map of extension directories to extension info
		if ( self::$extensionInfo === null ) {
			$extDir = $this->getConfig()->get( MainConfigNames::ExtensionDirectory );
			$baseDir = MW_INSTALL_PATH;
			self::$extensionInfo = [
				realpath( __DIR__ ) ?: __DIR__ => [
					'path' => $baseDir,
					'name' => 'MediaWiki',
					'license-name' => 'GPL-2.0-or-later',
				],
				realpath( "$baseDir/extensions" ) ?: "$baseDir/extensions" => null,
				realpath( $extDir ) ?: $extDir => null,
			];
			$keep = [
				'path' => null,
				'name' => null,
				'namemsg' => null,
				'license-name' => null,
			];
			$credits = SpecialVersion::getCredits( ExtensionRegistry::getInstance(), $this->getConfig() );
			foreach ( $credits as $group ) {
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
		}

		// Now traverse parent directories until we find a match or run out of parents.
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
	 * @stable to override
	 * @param string[] &$help Array of help data
	 * @param array $options Options passed to ApiHelp::getHelp
	 * @param array &$tocData If a TOC is being generated, this array has keys
	 *   as anchors in the page and values as for SectionMetadata::fromLegacy().
	 */
	public function modifyHelp( array &$help, array $options, array &$tocData ) {
	}

	// endregion -- end of help message generation

}

/*
 * This file uses VisualStudio style region/endregion fold markers which are
 * recognised by PHPStorm. If modelines are enabled, the following editor
 * configuration will also enable folding in vim, if it is in the last 5 lines
 * of the file. We also use "@name" which creates sections in Doxygen.
 *
 * vim: foldmarker=//\ region,//\ endregion foldmethod=marker
 */

/** @deprecated class alias since 1.43 */
class_alias( ApiBase::class, 'ApiBase' );
