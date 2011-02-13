<?php
/**
 *
 *
 * Created on Sep 5, 2006
 *
 * Copyright © 2006, 2010 Yuri Astrakhan <Firstname><Lastname>@gmail.com
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
 * 	which parameters to expect,h ow to parse and validate them.
 *
 * Profiling: various methods to allow keeping tabs on various tasks and their
 * 	time costs
 *
 * Self-documentation: code to allow the API to document its own state
 *
 * @ingroup API
 */
abstract class ApiBase {

	// These constants allow modules to specify exactly how to treat incoming parameters.

	const PARAM_DFLT = 0; // Default value of the parameter
	const PARAM_ISMULTI = 1; // Boolean, do we accept more than one item for this parameter (e.g.: titles)?
	const PARAM_TYPE = 2; // Can be either a string type (e.g.: 'integer') or an array of allowed values
	const PARAM_MAX = 3; // Max value allowed for a parameter. Only applies if TYPE='integer'
	const PARAM_MAX2 = 4; // Max value allowed for a parameter for bots and sysops. Only applies if TYPE='integer'
	const PARAM_MIN = 5; // Lowest value allowed for a parameter. Only applies if TYPE='integer'
	const PARAM_ALLOW_DUPLICATES = 6; // Boolean, do we allow the same value to be set more than once when ISMULTI=true
	const PARAM_DEPRECATED = 7; // Boolean, is the parameter deprecated (will show a warning)
	const PARAM_REQUIRED = 8; // Boolean, is the parameter required?
	const PARAM_RANGE_ENFORCE = 9; // Boolean, if MIN/MAX are set, enforce (die) these? Only applies if TYPE='integer' Use with extreme caution

	const LIMIT_BIG1 = 500; // Fast query, std user limit
	const LIMIT_BIG2 = 5000; // Fast query, bot/sysop limit
	const LIMIT_SML1 = 50; // Slow query, std user limit
	const LIMIT_SML2 = 500; // Slow query, bot/sysop limit

	private $mMainModule, $mModuleName, $mModulePrefix;
	private $mParamCache = array();

	/**
	 * Constructor
	 * @param $mainModule ApiMain object
	 * @param $moduleName string Name of this module
	 * @param $modulePrefix string Prefix to use for parameter names
	 */
	public function __construct( $mainModule, $moduleName, $modulePrefix = '' ) {
		$this->mMainModule = $mainModule;
		$this->mModuleName = $moduleName;
		$this->mModulePrefix = $modulePrefix;
	}

	/*****************************************************************************
	 * ABSTRACT METHODS                                                          *
	 *****************************************************************************/

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
	public abstract function execute();

	/**
	 * Returns a string that identifies the version of the extending class.
	 * Typically includes the class name, the svn revision, timestamp, and
	 * last author. Usually done with SVN's Id keyword
	 * @return string
	 */
	public abstract function getVersion();

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
	 * Get the name of the module as shown in the profiler log
	 * @return string
	 */
	public function getModuleProfileName( $db = false ) {
		if ( $db ) {
			return 'API:' . $this->mModuleName . '-DB';
		} else {
			return 'API:' . $this->mModuleName;
		}
	}

	/**
	 * Get the main module
	 * @return ApiMain object
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
	 * Get the result object
	 * @return ApiResult
	 */
	public function getResult() {
		// Main module has getResult() method overriden
		// Safety - avoid infinite loop:
		if ( $this->isMain() ) {
			ApiBase::dieDebug( __METHOD__, 'base method was called on main module. ' );
		}
		return $this->getMain()->getResult();
	}

	/**
	 * Get the result data array (read-only)
	 * @return array
	 */
	public function getResultData() {
		return $this->getResult()->getData();
	}

	/**
	 * Set warning section for this module. Users should monitor this
	 * section to notice any changes in API. Multiple calls to this
	 * function will result in the warning messages being separated by
	 * newlines
	 * @param $warning string Warning message
	 */
	public function setWarning( $warning ) {
		$data = $this->getResult()->getData();
		if ( isset( $data['warnings'][$this->getModuleName()] ) ) {
			// Don't add duplicate warnings
			$warn_regex = preg_quote( $warning, '/' );
			if ( preg_match( "/{$warn_regex}(\\n|$)/", $data['warnings'][$this->getModuleName()]['*'] ) ) {
				return;
			}
			$oldwarning = $data['warnings'][$this->getModuleName()]['*'];
			// If there is a warning already, append it to the existing one
			$warning = "$oldwarning\n$warning";
			$this->getResult()->unsetValue( 'warnings', $this->getModuleName() );
		}
		$msg = array();
		ApiResult::setContent( $msg, $warning );
		$this->getResult()->disableSizeCheck();
		$this->getResult()->addValue( 'warnings', $this->getModuleName(), $msg );
		$this->getResult()->enableSizeCheck();
	}

	/**
	 * If the module may only be used with a certain format module,
	 * it should override this method to return an instance of that formatter.
	 * A value of null means the default format will be used.
	 * @return mixed instance of a derived class of ApiFormatBase, or null
	 */
	public function getCustomPrinter() {
		return null;
	}

	/**
	 * Generates help message for this module, or false if there is no description
	 * @return mixed string or false
	 */
	public function makeHelpMsg() {
		static $lnPrfx = "\n  ";

		$msg = $this->getDescription();

		if ( $msg !== false ) {

			if ( !is_array( $msg ) ) {
				$msg = array(
					$msg
				);
			}
			$msg = $lnPrfx . implode( $lnPrfx, $msg ) . "\n";

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
					$this->mustBePosted() )
			{
				$msg .= "\n";
			}

			// Parameters
			$paramsMsg = $this->makeHelpMsgParameters();
			if ( $paramsMsg !== false ) {
				$msg .= "Parameters:\n$paramsMsg";
			}

			// Examples
			$examples = $this->getExamples();
			if ( $examples !== false ) {
				if ( !is_array( $examples ) ) {
					$examples = array(
						$examples
					);
				}

				if ( count( $examples ) > 0 ) {
					$msg .= 'Example' . ( count( $examples ) > 1 ? 's' : '' ) . ":\n  ";
					$msg .= implode( $lnPrfx, $examples ) . "\n";
				}
			}

			if ( $this->getMain()->getShowVersions() ) {
				$versions = $this->getVersion();
				$pattern = '/(\$.*) ([0-9a-z_]+\.php) (.*\$)/i';
				$callback = array( $this, 'makeHelpMsg_callback' );

				if ( is_array( $versions ) ) {
					foreach ( $versions as &$v ) {
						$v = preg_replace_callback( $pattern, $callback, $v );
					}
					$versions = implode( "\n  ", $versions );
				} else {
					$versions = preg_replace_callback( $pattern, $callback, $versions );
				}

				$msg .= "Version:\n  $versions\n";
			}
		}

		return $msg;
	}

	/**
	 * Generates the parameter descriptions for this module, to be displayed in the
	 * module's help.
	 * @return string
	 */
	public function makeHelpMsgParameters() {
		$params = $this->getFinalParams();
		if ( $params ) {

			$paramsDescription = $this->getFinalParamDescription();
			$msg = '';
			$paramPrefix = "\n" . str_repeat( ' ', 19 );
			foreach ( $params as $paramName => $paramSettings ) {
				$desc = isset( $paramsDescription[$paramName] ) ? $paramsDescription[$paramName] : '';
				if ( is_array( $desc ) ) {
					$desc = implode( $paramPrefix, $desc );
				}

				if ( !is_array( $paramSettings ) ) {
					$paramSettings = array(
						self::PARAM_DFLT => $paramSettings,
					);
				}

				$deprecated = isset( $paramSettings[self::PARAM_DEPRECATED] ) ?
					$paramSettings[self::PARAM_DEPRECATED] : false;
				if ( $deprecated ) {
					$desc = "DEPRECATED! $desc";
				}

				$required = isset( $paramSettings[self::PARAM_REQUIRED] ) ?
					$paramSettings[self::PARAM_REQUIRED] : false;
				if ( $required ) {
					$desc .= $paramPrefix . "This parameter is required";
				}

				$type = isset( $paramSettings[self::PARAM_TYPE] ) ? $paramSettings[self::PARAM_TYPE] : null;
				if ( isset( $type ) ) {
					if ( isset( $paramSettings[self::PARAM_ISMULTI] ) ) {
						$prompt = 'Values (separate with \'|\'): ';
					} else {
						$prompt = 'One value: ';
					}

					if ( is_array( $type ) ) {
						$choices = array();
						$nothingPrompt = false;
						foreach ( $type as $t ) {
							if ( $t === '' ) {
								$nothingPrompt = 'Can be empty, or ';
							} else {
								$choices[] =  $t;
							}
						}
						$desc .= $paramPrefix . $nothingPrompt . $prompt;
						$choicesstring = implode( ', ', $choices );
						$desc .= wordwrap( $choicesstring, 100, "\n                       " );
					} else {
						switch ( $type ) {
							case 'namespace':
								// Special handling because namespaces are type-limited, yet they are not given
								$desc .= $paramPrefix . $prompt . implode( ', ', MWNamespace::getValidNamespaces() );
								break;
							case 'limit':
								$desc .= $paramPrefix . "No more than {$paramSettings[self :: PARAM_MAX]}";
								if ( isset( $paramSettings[self::PARAM_MAX2] ) ) {
									$desc .= " ({$paramSettings[self::PARAM_MAX2]} for bots)";
								}
								$desc .= ' allowed';
								break;
							case 'integer':
								$hasMin = isset( $paramSettings[self::PARAM_MIN] );
								$hasMax = isset( $paramSettings[self::PARAM_MAX] );
								if ( $hasMin || $hasMax ) {
									if ( !$hasMax ) {
										$intRangeStr = "The value must be no less than {$paramSettings[self::PARAM_MIN]}";
									} elseif ( !$hasMin ) {
										$intRangeStr = "The value must be no more than {$paramSettings[self::PARAM_MAX]}";
									} else {
										$intRangeStr = "The value must be between {$paramSettings[self::PARAM_MIN]} and {$paramSettings[self::PARAM_MAX]}";
									}

									$desc .= $paramPrefix . $intRangeStr;
								}
								break;
						}

						if ( isset( $paramSettings[self::PARAM_ISMULTI] ) ) {
							$isArray = is_array( $paramSettings[self::PARAM_TYPE] );

							if ( !$isArray
									|| $isArray && count( $paramSettings[self::PARAM_TYPE] ) > self::LIMIT_SML1 ) {
								$desc .= $paramPrefix . "Maximum number of values " .
									self::LIMIT_SML1 . " (" . self::LIMIT_SML2 . " for bots)";
							}
						}
					}
				}

				$default = is_array( $paramSettings )
						? ( isset( $paramSettings[self::PARAM_DFLT] ) ? $paramSettings[self::PARAM_DFLT] : null )
						: $paramSettings;
				if ( !is_null( $default ) && $default !== false ) {
					$desc .= $paramPrefix . "Default: $default";
				}

				$msg .= sprintf( "  %-14s - %s\n", $this->encodeParamName( $paramName ), $desc );
			}
			return $msg;

		} else {
			return false;
		}
	}

	/**
	 * Callback for preg_replace_callback() call in makeHelpMsg().
	 * Replaces a source file name with a link to ViewVC
	 */
	public function makeHelpMsg_callback( $matches ) {
		global $wgAutoloadClasses, $wgAutoloadLocalClasses;
		if ( isset( $wgAutoloadLocalClasses[get_class( $this )] ) ) {
			$file = $wgAutoloadLocalClasses[get_class( $this )];
		} elseif ( isset( $wgAutoloadClasses[get_class( $this )] ) ) {
			$file = $wgAutoloadClasses[get_class( $this )];
		}

		// Do some guesswork here
		$path = strstr( $file, 'includes/api/' );
		if ( $path === false ) {
			$path = strstr( $file, 'extensions/' );
		} else {
			$path = 'phase3/' . $path;
		}

		// Get the filename from $matches[2] instead of $file
		// If they're not the same file, they're assumed to be in the
		// same directory
		// This is necessary to make stuff like ApiMain::getVersion()
		// returning the version string for ApiBase work
		if ( $path ) {
			return "{$matches[0]}\n   http://svn.wikimedia.org/" .
				"viewvc/mediawiki/trunk/" . dirname( $path ) .
				"/{$matches[2]}";
		}
		return $matches[0];
	}

	/**
	 * Returns the description string for this module
	 * @return mixed string or array of strings
	 */
	protected function getDescription() {
		return false;
	}

	/**
	 * Returns usage examples for this module. Return null if no examples are available.
	 * @return mixed string or array of strings
	 */
	protected function getExamples() {
		return false;
	}

	/**
	 * Returns an array of allowed parameters (parameter name) => (default
	 * value) or (parameter name) => (array with PARAM_* constants as keys)
	 * Don't call this function directly: use getFinalParams() to allow
	 * hooks to modify parameters as needed.
	 * @return array
	 */
	protected function getAllowedParams() {
		return false;
	}

	/**
	 * Returns an array of parameter descriptions.
	 * Don't call this functon directly: use getFinalParamDescription() to
	 * allow hooks to modify descriptions as needed.
	 * @return array
	 */
	protected function getParamDescription() {
		return false;
	}

	/**
	 * Get final list of parameters, after hooks have had a chance to
	 * tweak it as needed.
	 * @return array
	 */
	public function getFinalParams() {
		$params = $this->getAllowedParams();
		wfRunHooks( 'APIGetAllowedParams', array( &$this, &$params ) );
		return $params;
	}

	/**
	 * Get final description, after hooks have had a chance to tweak it as
	 * needed.
	 * @return array
	 */
	public function getFinalParamDescription() {
		$desc = $this->getParamDescription();
		wfRunHooks( 'APIGetParamDescription', array( &$this, &$desc ) );
		return $desc;
	}

	/**
	 * This method mangles parameter name based on the prefix supplied to the constructor.
	 * Override this method to change parameter name during runtime
	 * @param $paramName string Parameter name
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
	 * @param $parseLimit Boolean: true by default
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
	 * @param $paramName string Parameter name
	 * @param $parseLimit bool see extractRequestParams()
	 * @return mixed Parameter value
	 */
	protected function getParameter( $paramName, $parseLimit = true ) {
		$params = $this->getFinalParams();
		$paramSettings = $params[$paramName];
		return $this->getParameterFromSettings( $paramName, $paramSettings, $parseLimit );
	}

	/**
	 * Die if none or more than one of a certain set of parameters is set and not false.
	 * @param $params array of parameter names
	 */
	public function requireOnlyOneParameter( $params ) {
		$required = func_get_args();
		array_shift( $required );

		$intersection = array_intersect( array_keys( array_filter( $params,
				array( $this, "parameterNotEmpty" ) ) ), $required );

		if ( count( $intersection ) > 1 ) {
			$this->dieUsage( 'The parameters ' . implode( ', ', $intersection ) . ' can not be used together', 'invalidparammix' );
		} elseif ( count( $intersection ) == 0 ) {
			$this->dieUsage( 'One of the parameters ' . implode( ', ', $required ) . ' is required', 'missingparam' );
		}
	}

	/**
	 * Callback function used in requireOnlyOneParameter to check whether reequired parameters are set
	 *
	 * @param  $x object Parameter to check is not null/false
	 * @return bool
	 */
	private function parameterNotEmpty( $x ) {
		return !is_null( $x ) && $x !== false;
	}

	/**
	 * @deprecated use MWNamespace::getValidNamespaces()
	 */
	public static function getValidNamespaces() {
		return MWNamespace::getValidNamespaces();
	}

	/**
	 * Return true if we're to watch the page, false if not, null if no change.
	 * @param $watchlist String Valid values: 'watch', 'unwatch', 'preferences', 'nochange'
	 * @param $titleObj Title the page under consideration
	 * @param $userOption String The user option to consider when $watchlist=preferences.
	 * 	If not set will magically default to either watchdefault or watchcreations
	 * @returns mixed
	 */
	protected function getWatchlistValue ( $watchlist, $titleObj, $userOption = null ) {
		global $wgUser;
		switch ( $watchlist ) {
			case 'watch':
				return true;

			case 'unwatch':
				return false;

			case 'preferences':
				# If the user is already watching, don't bother checking
				if ( $titleObj->userIsWatching() ) {
					return null;
				}
				# If no user option was passed, use watchdefault or watchcreation
				if ( is_null( $userOption ) ) {
					$userOption = $titleObj->exists()
						? 'watchdefault' : 'watchcreations';
				}
				# If the corresponding user option is true, watch, else no change
				return $wgUser->getOption( $userOption ) ? true : null;

			case 'nochange':
				return null;

			default:
				return null;
		}
	}

	/**
	 * Set a watch (or unwatch) based the based on a watchlist parameter.
	 * @param $watch String Valid values: 'watch', 'unwatch', 'preferences', 'nochange'
	 * @param $titleObj Title the article's title to change
	 * @param $userOption String The user option to consider when $watch=preferences
	 */
	protected function setWatch ( $watch, $titleObj, $userOption = null ) {
		$value = $this->getWatchlistValue( $watch, $titleObj, $userOption );
		if ( $value === null ) {
			return;
		}

		$articleObj = new Article( $titleObj );
		if ( $value ) {
			$articleObj->doWatch();
		} else {
			$articleObj->doUnwatch();
		}
	}

	/**
	 * Using the settings determine the value for the given parameter
	 *
	 * @param $paramName String: parameter name
	 * @param $paramSettings Mixed: default value or an array of settings
	 *  using PARAM_* constants.
	 * @param $parseLimit Boolean: parse limit?
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
			$default = isset( $paramSettings[self::PARAM_DFLT] ) ? $paramSettings[self::PARAM_DFLT] : null;
			$multi = isset( $paramSettings[self::PARAM_ISMULTI] ) ? $paramSettings[self::PARAM_ISMULTI] : false;
			$type = isset( $paramSettings[self::PARAM_TYPE] ) ? $paramSettings[self::PARAM_TYPE] : null;
			$dupes = isset( $paramSettings[self::PARAM_ALLOW_DUPLICATES] ) ? $paramSettings[self::PARAM_ALLOW_DUPLICATES] : false;
			$deprecated = isset( $paramSettings[self::PARAM_DEPRECATED] ) ? $paramSettings[self::PARAM_DEPRECATED] : false;
			$required = isset( $paramSettings[self::PARAM_REQUIRED] ) ? $paramSettings[self::PARAM_REQUIRED] : false;

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
				// Having a default value of anything other than 'false' is pointless
				ApiBase::dieDebug( __METHOD__, "Boolean param $encParamName's default is set to '$default'" );
			}

			$value = $this->getMain()->getRequest()->getCheck( $encParamName );
		} else {
			$value = $this->getMain()->getRequest()->getVal( $encParamName, $default );

			if ( isset( $value ) && $type == 'namespace' ) {
				$type = MWNamespace::getValidNamespaces();
			}
		}

		if ( isset( $value ) && ( $multi || is_array( $type ) ) ) {
			$value = $this->parseMultiValue( $encParamName, $value, $multi, is_array( $type ) ? $type : null );
		}

		// More validation only when choices were not given
		// choices were validated in parseMultiValue()
		if ( isset( $value ) ) {
			if ( !is_array( $type ) ) {
				switch ( $type ) {
					case 'NULL': // nothing to do
						break;
					case 'string':
						if ( $required && $value === '' ) {
							$this->dieUsageMsg( array( 'missingparam', $paramName ) );
						}

						break;
					case 'integer': // Force everything using intval() and optionally validate limits
						$min = isset ( $paramSettings[self::PARAM_MIN] ) ? $paramSettings[self::PARAM_MIN] : null;
						$max = isset ( $paramSettings[self::PARAM_MAX] ) ? $paramSettings[self::PARAM_MAX] : null;
						$enforceLimits = isset ( $paramSettings[self::PARAM_RANGE_ENFORCE] )
								? $paramSettings[self::PARAM_RANGE_ENFORCE] : false;

						if ( !is_null( $min ) || !is_null( $max ) ) {
							if ( is_array( $value ) ) {
								$value = array_map( 'intval', $value );
								foreach ( $value as &$v ) {
									$this->validateLimit( $paramName, $v, $min, $max, null, $enforceLimits );
								}
							} else {
								$value = intval( $value );
								$this->validateLimit( $paramName, $value, $min, $max, null, $enforceLimits );
							}
						} else {
							$value = intval( $value );
						}
						break;
					case 'limit':
						if ( !$parseLimit ) {
							// Don't do any validation whatsoever
							break;
						}
						if ( !isset( $paramSettings[self::PARAM_MAX] ) || !isset( $paramSettings[self::PARAM_MAX2] ) ) {
							ApiBase::dieDebug( __METHOD__, "MAX1 or MAX2 are not defined for the limit $encParamName" );
						}
						if ( $multi ) {
							ApiBase::dieDebug( __METHOD__, "Multi-values not supported for $encParamName" );
						}
						$min = isset( $paramSettings[self::PARAM_MIN] ) ? $paramSettings[self::PARAM_MIN] : 0;
						if ( $value == 'max' ) {
							$value = $this->getMain()->canApiHighLimits() ? $paramSettings[self::PARAM_MAX2] : $paramSettings[self::PARAM_MAX];
							$this->getResult()->setParsedLimit( $this->getModuleName(), $value );
						} else {
							$value = intval( $value );
							$this->validateLimit( $paramName, $value, $min, $paramSettings[self::PARAM_MAX], $paramSettings[self::PARAM_MAX2] );
						}
						break;
					case 'boolean':
						if ( $multi ) {
							ApiBase::dieDebug( __METHOD__, "Multi-values not supported for $encParamName" );
						}
						break;
					case 'timestamp':
						if ( $multi ) {
							ApiBase::dieDebug( __METHOD__, "Multi-values not supported for $encParamName" );
						}
						$value = wfTimestamp( TS_UNIX, $value );
						if ( $value === 0 ) {
							$this->dieUsage( "Invalid value '$value' for timestamp parameter $encParamName", "badtimestamp_{$encParamName}" );
						}
						$value = wfTimestamp( TS_MW, $value );
						break;
					case 'user':
						if ( !is_array( $value ) ) {
							$value = array( $value );
						}

						foreach ( $value as $key => $val ) {
							$title = Title::makeTitleSafe( NS_USER, $val );
							if ( is_null( $title ) ) {
								$this->dieUsage( "Invalid value for user parameter $encParamName", "baduser_{$encParamName}" );
							}
							$value[$key] = $title->getText();
						}

						if ( !$multi ) {
							$value = $value[0];
						}
						break;
					default:
						ApiBase::dieDebug( __METHOD__, "Param $encParamName's type is unknown - $type" );
				}
			}

			// Throw out duplicates if requested
			if ( is_array( $value ) && !$dupes ) {
				$value = array_unique( $value );
			}

			// Set a warning if a deprecated parameter has been passed
			if ( $deprecated && $value !== false ) {
				$this->setWarning( "The $encParamName parameter has been deprecated." );
			}
		} else if ( $required ) {
			$this->dieUsageMsg( array( 'missingparam', $paramName ) );
		}

		return $value;
	}

	/**
	 * Return an array of values that were given in a 'a|b|c' notation,
	 * after it optionally validates them against the list allowed values.
	 *
	 * @param $valueName string The name of the parameter (for error
	 *  reporting)
	 * @param $value mixed The value being parsed
	 * @param $allowMultiple bool Can $value contain more than one value
	 *  separated by '|'?
	 * @param $allowedValues mixed An array of values to check against. If
	 *  null, all values are accepted.
	 * @return mixed (allowMultiple ? an_array_of_values : a_single_value)
	 */
	protected function parseMultiValue( $valueName, $value, $allowMultiple, $allowedValues ) {
		if ( trim( $value ) === '' && $allowMultiple ) {
			return array();
		}

		// This is a bit awkward, but we want to avoid calling canApiHighLimits() because it unstubs $wgUser
		$valuesList = explode( '|', $value, self::LIMIT_SML2 + 1 );
		$sizeLimit = count( $valuesList ) > self::LIMIT_SML1 && $this->mMainModule->canApiHighLimits() ?
			self::LIMIT_SML2 : self::LIMIT_SML1;

		if ( self::truncateArray( $valuesList, $sizeLimit ) ) {
			$this->setWarning( "Too many values supplied for parameter '$valueName': the limit is $sizeLimit" );
		}

		if ( !$allowMultiple && count( $valuesList ) != 1 ) {
			$possibleValues = is_array( $allowedValues ) ? "of '" . implode( "', '", $allowedValues ) . "'" : '';
			$this->dieUsage( "Only one $possibleValues is allowed for parameter '$valueName'", "multival_$valueName" );
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
					$this->dieUsage( "Unrecognized value for parameter '$valueName': {$valuesList[0]}", "unknown_$valueName" );
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
	 * @param $paramName string Parameter name
	 * @param $value int Parameter value
	 * @param $min int|null Minimum value
	 * @param $max int|null Maximum value for users
	 * @param $botMax int Maximum value for sysops/bots
	 * @param $enforceLimits Boolean Whether to enforce (die) if value is outside limits
	 */
	function validateLimit( $paramName, &$value, $min, $max, $botMax = null, $enforceLimits = false ) {
		if ( !is_null( $min ) && $value < $min ) {

			$msg = $this->encodeParamName( $paramName ) . " may not be less than $min (set to $value)";
			$this->warnOrDie( $msg, $enforceLimits );
			$value = $min;
		}

		// Minimum is always validated, whereas maximum is checked only if not running in internal call mode
		if ( $this->getMain()->isInternalMode() ) {
			return;
		}

		// Optimization: do not check user's bot status unless really needed -- skips db query
		// assumes $botMax >= $max
		if ( !is_null( $max ) && $value > $max ) {
			if ( !is_null( $botMax ) && $this->getMain()->canApiHighLimits() ) {
				if ( $value > $botMax ) {
					$msg = $this->encodeParamName( $paramName ) . " may not be over $botMax (set to $value) for bots or sysops";
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
	 * Adds a warning to the output, else dies
	 *
	 * @param  $msg String Message to show as a warning, or error message if dying
	 * @param  $enforceLimits Boolean Whether this is an enforce (die)
	 */
	private function warnOrDie( $msg, $enforceLimits = false ) {
		if ( $enforceLimits ) {
			$this->dieUsage( $msg, 'integeroutofrange' );
		} else {
			$this->setWarning( $msg );
		}
	}

	/**
	 * Truncate an array to a certain length.
	 * @param $arr array Array to truncate
	 * @param $limit int Maximum length
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
	 * Throw a UsageException, which will (if uncaught) call the main module's
	 * error handler and die with an error message.
	 *
	 * @param $description string One-line human-readable description of the
	 *   error condition, e.g., "The API requires a valid action parameter"
	 * @param $errorCode string Brief, arbitrary, stable string to allow easy
	 *   automated identification of the error, e.g., 'unknown_action'
	 * @param $httpRespCode int HTTP response code
	 * @param $extradata array Data to add to the <error> element; array in ApiResult format
	 */
	public function dieUsage( $description, $errorCode, $httpRespCode = 0, $extradata = null ) {
		wfProfileClose();
		throw new UsageException( $description, $this->encodeParamName( $errorCode ), $httpRespCode, $extradata );
	}

	/**
	 * Array that maps message keys to error messages. $1 and friends are replaced.
	 */
	public static $messageMap = array(
		// This one MUST be present, or dieUsageMsg() will recurse infinitely
		'unknownerror' => array( 'code' => 'unknownerror', 'info' => "Unknown error: ``\$1''" ),
		'unknownerror-nocode' => array( 'code' => 'unknownerror', 'info' => 'Unknown error' ),

		// Messages from Title::getUserPermissionsErrors()
		'ns-specialprotected' => array( 'code' => 'unsupportednamespace', 'info' => "Pages in the Special namespace can't be edited" ),
		'protectedinterface' => array( 'code' => 'protectednamespace-interface', 'info' => "You're not allowed to edit interface messages" ),
		'namespaceprotected' => array( 'code' => 'protectednamespace', 'info' => "You're not allowed to edit pages in the ``\$1'' namespace" ),
		'customcssjsprotected' => array( 'code' => 'customcssjsprotected', 'info' => "You're not allowed to edit custom CSS and JavaScript pages" ),
		'cascadeprotected' => array( 'code' => 'cascadeprotected', 'info' => "The page you're trying to edit is protected because it's included in a cascade-protected page" ),
		'protectedpagetext' => array( 'code' => 'protectedpage', 'info' => "The ``\$1'' right is required to edit this page" ),
		'protect-cantedit' => array( 'code' => 'cantedit', 'info' => "You can't protect this page because you can't edit it" ),
		'badaccess-group0' => array( 'code' => 'permissiondenied', 'info' => "Permission denied" ), // Generic permission denied message
		'badaccess-groups' => array( 'code' => 'permissiondenied', 'info' => "Permission denied" ),
		'titleprotected' => array( 'code' => 'protectedtitle', 'info' => "This title has been protected from creation" ),
		'nocreate-loggedin' => array( 'code' => 'cantcreate', 'info' => "You don't have permission to create new pages" ),
		'nocreatetext' => array( 'code' => 'cantcreate-anon', 'info' => "Anonymous users can't create new pages" ),
		'movenologintext' => array( 'code' => 'cantmove-anon', 'info' => "Anonymous users can't move pages" ),
		'movenotallowed' => array( 'code' => 'cantmove', 'info' => "You don't have permission to move pages" ),
		'confirmedittext' => array( 'code' => 'confirmemail', 'info' => "You must confirm your e-mail address before you can edit" ),
		'blockedtext' => array( 'code' => 'blocked', 'info' => "You have been blocked from editing" ),
		'autoblockedtext' => array( 'code' => 'autoblocked', 'info' => "Your IP address has been blocked automatically, because it was used by a blocked user" ),

		// Miscellaneous interface messages
		'actionthrottledtext' => array( 'code' => 'ratelimited', 'info' => "You've exceeded your rate limit. Please wait some time and try again" ),
		'alreadyrolled' => array( 'code' => 'alreadyrolled', 'info' => "The page you tried to rollback was already rolled back" ),
		'cantrollback' => array( 'code' => 'onlyauthor', 'info' => "The page you tried to rollback only has one author" ),
		'readonlytext' => array( 'code' => 'readonly', 'info' => "The wiki is currently in read-only mode" ),
		'sessionfailure' => array( 'code' => 'badtoken', 'info' => "Invalid token" ),
		'cannotdelete' => array( 'code' => 'cantdelete', 'info' => "Couldn't delete ``\$1''. Maybe it was deleted already by someone else" ),
		'notanarticle' => array( 'code' => 'missingtitle', 'info' => "The page you requested doesn't exist" ),
		'selfmove' => array( 'code' => 'selfmove', 'info' => "Can't move a page to itself" ),
		'immobile_namespace' => array( 'code' => 'immobilenamespace', 'info' => "You tried to move pages from or to a namespace that is protected from moving" ),
		'articleexists' => array( 'code' => 'articleexists', 'info' => "The destination article already exists and is not a redirect to the source article" ),
		'protectedpage' => array( 'code' => 'protectedpage', 'info' => "You don't have permission to perform this move" ),
		'hookaborted' => array( 'code' => 'hookaborted', 'info' => "The modification you tried to make was aborted by an extension hook" ),
		'cantmove-titleprotected' => array( 'code' => 'protectedtitle', 'info' => "The destination article has been protected from creation" ),
		'imagenocrossnamespace' => array( 'code' => 'nonfilenamespace', 'info' => "Can't move a file to a non-file namespace" ),
		'imagetypemismatch' => array( 'code' => 'filetypemismatch', 'info' => "The new file extension doesn't match its type" ),
		// 'badarticleerror' => shouldn't happen
		// 'badtitletext' => shouldn't happen
		'ip_range_invalid' => array( 'code' => 'invalidrange', 'info' => "Invalid IP range" ),
		'range_block_disabled' => array( 'code' => 'rangedisabled', 'info' => "Blocking IP ranges has been disabled" ),
		'nosuchusershort' => array( 'code' => 'nosuchuser', 'info' => "The user you specified doesn't exist" ),
		'badipaddress' => array( 'code' => 'invalidip', 'info' => "Invalid IP address specified" ),
		'ipb_expiry_invalid' => array( 'code' => 'invalidexpiry', 'info' => "Invalid expiry time" ),
		'ipb_already_blocked' => array( 'code' => 'alreadyblocked', 'info' => "The user you tried to block was already blocked" ),
		'ipb_blocked_as_range' => array( 'code' => 'blockedasrange', 'info' => "IP address ``\$1'' was blocked as part of range ``\$2''. You can't unblock the IP invidually, but you can unblock the range as a whole." ),
		'ipb_cant_unblock' => array( 'code' => 'cantunblock', 'info' => "The block you specified was not found. It may have been unblocked already" ),
		'mailnologin' => array( 'code' => 'cantsend', 'info' => "You are not logged in, you do not have a confirmed e-mail address, or you are not allowed to send e-mail to other users, so you cannot send e-mail" ),
		'ipbblocked' => array( 'code' => 'ipbblocked', 'info' => 'You cannot block or unblock users while you are yourself blocked' ),
		'ipbnounblockself' => array( 'code' => 'ipbnounblockself', 'info' => 'You are not allowed to unblock yourself' ),
		'usermaildisabled' => array( 'code' => 'usermaildisabled', 'info' => "User email has been disabled" ),
		'blockedemailuser' => array( 'code' => 'blockedfrommail', 'info' => "You have been blocked from sending e-mail" ),
		'notarget' => array( 'code' => 'notarget', 'info' => "You have not specified a valid target for this action" ),
		'noemail' => array( 'code' => 'noemail', 'info' => "The user has not specified a valid e-mail address, or has chosen not to receive e-mail from other users" ),
		'rcpatroldisabled' => array( 'code' => 'patroldisabled', 'info' => "Patrolling is disabled on this wiki" ),
		'markedaspatrollederror-noautopatrol' => array( 'code' => 'noautopatrol', 'info' => "You don't have permission to patrol your own changes" ),
		'delete-toobig' => array( 'code' => 'bigdelete', 'info' => "You can't delete this page because it has more than \$1 revisions" ),
		'movenotallowedfile' => array( 'code' => 'cantmovefile', 'info' => "You don't have permission to move files" ),
		'userrights-no-interwiki' => array( 'code' => 'nointerwikiuserrights', 'info' => "You don't have permission to change user rights on other wikis" ),
		'userrights-nodatabase' => array( 'code' => 'nosuchdatabase', 'info' => "Database ``\$1'' does not exist or is not local" ),
		'nouserspecified' => array( 'code' => 'invaliduser', 'info' => "Invalid username ``\$1''" ),
		'noname' => array( 'code' => 'invaliduser', 'info' => "Invalid username ``\$1''" ),
		'summaryrequired' => array( 'code' => 'summaryrequired', 'info' => 'Summary required' ),

		// API-specific messages
		'readrequired' => array( 'code' => 'readapidenied', 'info' => "You need read permission to use this module" ),
		'writedisabled' => array( 'code' => 'noapiwrite', 'info' => "Editing of this wiki through the API is disabled. Make sure the \$wgEnableWriteAPI=true; statement is included in the wiki's LocalSettings.php file" ),
		'writerequired' => array( 'code' => 'writeapidenied', 'info' => "You're not allowed to edit this wiki through the API" ),
		'missingparam' => array( 'code' => 'no$1', 'info' => "The \$1 parameter must be set" ),
		'invalidtitle' => array( 'code' => 'invalidtitle', 'info' => "Bad title ``\$1''" ),
		'nosuchpageid' => array( 'code' => 'nosuchpageid', 'info' => "There is no page with ID \$1" ),
		'nosuchrevid' => array( 'code' => 'nosuchrevid', 'info' => "There is no revision with ID \$1" ),
		'nosuchuser' => array( 'code' => 'nosuchuser', 'info' => "User ``\$1'' doesn't exist" ),
		'invaliduser' => array( 'code' => 'invaliduser', 'info' => "Invalid username ``\$1''" ),
		'invalidexpiry' => array( 'code' => 'invalidexpiry', 'info' => "Invalid expiry time ``\$1''" ),
		'pastexpiry' => array( 'code' => 'pastexpiry', 'info' => "Expiry time ``\$1'' is in the past" ),
		'create-titleexists' => array( 'code' => 'create-titleexists', 'info' => "Existing titles can't be protected with 'create'" ),
		'missingtitle-createonly' => array( 'code' => 'missingtitle-createonly', 'info' => "Missing titles can only be protected with 'create'" ),
		'cantblock' => array( 'code' => 'cantblock', 'info' => "You don't have permission to block users" ),
		'canthide' => array( 'code' => 'canthide', 'info' => "You don't have permission to hide user names from the block log" ),
		'cantblock-email' => array( 'code' => 'cantblock-email', 'info' => "You don't have permission to block users from sending e-mail through the wiki" ),
		'unblock-notarget' => array( 'code' => 'notarget', 'info' => "Either the id or the user parameter must be set" ),
		'unblock-idanduser' => array( 'code' => 'idanduser', 'info' => "The id and user parameters can't be used together" ),
		'cantunblock' => array( 'code' => 'permissiondenied', 'info' => "You don't have permission to unblock users" ),
		'cannotundelete' => array( 'code' => 'cantundelete', 'info' => "Couldn't undelete: the requested revisions may not exist, or may have been undeleted already" ),
		'permdenied-undelete' => array( 'code' => 'permissiondenied', 'info' => "You don't have permission to restore deleted revisions" ),
		'createonly-exists' => array( 'code' => 'articleexists', 'info' => "The article you tried to create has been created already" ),
		'nocreate-missing' => array( 'code' => 'missingtitle', 'info' => "The article you tried to edit doesn't exist" ),
		'nosuchrcid' => array( 'code' => 'nosuchrcid', 'info' => "There is no change with rcid ``\$1''" ),
		'protect-invalidaction' => array( 'code' => 'protect-invalidaction', 'info' => "Invalid protection type ``\$1''" ),
		'protect-invalidlevel' => array( 'code' => 'protect-invalidlevel', 'info' => "Invalid protection level ``\$1''" ),
		'toofewexpiries' => array( 'code' => 'toofewexpiries', 'info' => "\$1 expiry timestamps were provided where \$2 were needed" ),
		'cantimport' => array( 'code' => 'cantimport', 'info' => "You don't have permission to import pages" ),
		'cantimport-upload' => array( 'code' => 'cantimport-upload', 'info' => "You don't have permission to import uploaded pages" ),
		'nouploadmodule' => array( 'code' => 'nomodule', 'info' => 'No upload module set' ),
		'importnofile' => array( 'code' => 'nofile', 'info' => "You didn't upload a file" ),
		'importuploaderrorsize' => array( 'code' => 'filetoobig', 'info' => 'The file you uploaded is bigger than the maximum upload size' ),
		'importuploaderrorpartial' => array( 'code' => 'partialupload', 'info' => 'The file was only partially uploaded' ),
		'importuploaderrortemp' => array( 'code' => 'notempdir', 'info' => 'The temporary upload directory is missing' ),
		'importcantopen' => array( 'code' => 'cantopenfile', 'info' => "Couldn't open the uploaded file" ),
		'import-noarticle' => array( 'code' => 'badinterwiki', 'info' => 'Invalid interwiki title specified' ),
		'importbadinterwiki' => array( 'code' => 'badinterwiki', 'info' => 'Invalid interwiki title specified' ),
		'import-unknownerror' => array( 'code' => 'import-unknownerror', 'info' => "Unknown error on import: ``\$1''" ),
		'cantoverwrite-sharedfile' => array( 'code' => 'cantoverwrite-sharedfile', 'info' => 'The target file exists on a shared repository and you do not have permission to override it' ),
		'sharedfile-exists' => array( 'code' => 'fileexists-sharedrepo-perm', 'info' => 'The target file exists on a shared repository. Use the ignorewarnings parameter to override it.' ),
		'mustbeposted' => array( 'code' => 'mustbeposted', 'info' => "The \$1 module requires a POST request" ),
		'show' => array( 'code' => 'show', 'info' => 'Incorrect parameter - mutually exclusive values may not be supplied' ),
		'specialpage-cantexecute' => array( 'code' => 'specialpage-cantexecute', 'info' => "You don't have permission to view the results of this special page" ),

		// ApiEditPage messages
		'noimageredirect-anon' => array( 'code' => 'noimageredirect-anon', 'info' => "Anonymous users can't create image redirects" ),
		'noimageredirect-logged' => array( 'code' => 'noimageredirect', 'info' => "You don't have permission to create image redirects" ),
		'spamdetected' => array( 'code' => 'spamdetected', 'info' => "Your edit was refused because it contained a spam fragment: ``\$1''" ),
		'filtered' => array( 'code' => 'filtered', 'info' => "The filter callback function refused your edit" ),
		'contenttoobig' => array( 'code' => 'contenttoobig', 'info' => "The content you supplied exceeds the article size limit of \$1 kilobytes" ),
		'noedit-anon' => array( 'code' => 'noedit-anon', 'info' => "Anonymous users can't edit pages" ),
		'noedit' => array( 'code' => 'noedit', 'info' => "You don't have permission to edit pages" ),
		'wasdeleted' => array( 'code' => 'pagedeleted', 'info' => "The page has been deleted since you fetched its timestamp" ),
		'blankpage' => array( 'code' => 'emptypage', 'info' => "Creating new, empty pages is not allowed" ),
		'editconflict' => array( 'code' => 'editconflict', 'info' => "Edit conflict detected" ),
		'hashcheckfailed' => array( 'code' => 'badmd5', 'info' => "The supplied MD5 hash was incorrect" ),
		'missingtext' => array( 'code' => 'notext', 'info' => "One of the text, appendtext, prependtext and undo parameters must be set" ),
		'emptynewsection' => array( 'code' => 'emptynewsection', 'info' => 'Creating empty new sections is not possible.' ),
		'revwrongpage' => array( 'code' => 'revwrongpage', 'info' => "r\$1 is not a revision of ``\$2''" ),
		'undo-failure' => array( 'code' => 'undofailure', 'info' => 'Undo failed due to conflicting intermediate edits' ),

		// uploadMsgs
		'invalid-session-key' => array( 'code' => 'invalid-session-key', 'info' => 'Not a valid session key' ),
		'nouploadmodule' => array( 'code' => 'nouploadmodule', 'info' => 'No upload module set' ),
		'uploaddisabled' => array( 'code' => 'uploaddisabled', 'info' => 'Uploads are not enabled.  Make sure $wgEnableUploads is set to true in LocalSettings.php and the PHP ini setting file_uploads is true' ),
		'copyuploaddisabled' => array( 'code' => 'copyuploaddisabled', 'info' => 'Uploads by URL is not enabled.  Make sure $wgAllowCopyUploads is set to true in LocalSettings.php.' ),
	);

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
	 * @param $error array Element of a getUserPermissionsErrors()-style array
	 */
	public function dieUsageMsg( $error ) {
		$parsed = $this->parseMsg( $error );
		$this->dieUsage( $parsed['info'], $parsed['code'] );
	}

	/**
	 * Return the error message related to a certain array
	 * @param $error array Element of a getUserPermissionsErrors()-style array
	 * @return array('code' => code, 'info' => info)
	 */
	public function parseMsg( $error ) {
		$key = array_shift( $error );
		if ( isset( self::$messageMap[$key] ) ) {
			return array( 'code' =>
				wfMsgReplaceArgs( self::$messageMap[$key]['code'], $error ),
					'info' =>
				wfMsgReplaceArgs( self::$messageMap[$key]['info'], $error )
			);
		}
		// If the key isn't present, throw an "unknown error"
		return $this->parseMsg( array( 'unknownerror', $key ) );
	}

	/**
	 * Internal code errors should be reported with this method
	 * @param $method string Method or function name
	 * @param $message string Error message
	 */
	protected static function dieDebug( $method, $message ) {
		wfDebugDieBacktrace( "Internal error in $method: $message" );
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
		return false;
	}

	/**
	 * Returns whether this module requires a Token to execute
	 * @returns bool
	 */
	public function needsToken() {
		return false;
	}

	/**
	 * Returns the token salt if there is one, '' if the module doesn't require a salt, else false if the module doesn't need a token
	 * @returns bool
	 */
	public function getTokenSalt() {
		return false;
	}

	/**
	* Gets the user for whom to get the watchlist
	*
	* @returns User
	*/
	public function getWatchlistUser( $params ) {
		global $wgUser;
		if ( !is_null( $params['owner'] ) && !is_null( $params['token'] ) ) {
			$user = User::newFromName( $params['owner'], false );
			if ( !$user->getId() ) {
				$this->dieUsage( 'Specified user does not exist', 'bad_wlowner' );
			}
			$token = $user->getOption( 'watchlisttoken' );
			if ( $token == '' || $token != $params['token'] ) {
				$this->dieUsage( 'Incorrect watchlist token provided -- please set a correct token in Special:Preferences', 'bad_wltoken' );
			}
		} else {
			if ( !$wgUser->isLoggedIn() ) {
				$this->dieUsage( 'You must be logged-in to have a watchlist', 'notloggedin' );
			}
			$user = $wgUser;
		}
		return $user;
	}

	/**
	 * Returns a list of all possible errors returned by the module
	 * @return array in the format of array( key, param1, param2, ... ) or array( 'code' => ..., 'info' => ... )
	 */
	public function getPossibleErrors() {
		$ret = array();

		$params = $this->getFinalParams();
		if ( $params ) {
			foreach ( $params as $paramName => $paramSettings ) {
				if ( isset( $paramSettings[ApiBase::PARAM_REQUIRED] ) ) {
					$ret[] = array( 'missingparam', $paramName );
				}
			}
		}

		if ( $this->mustBePosted() ) {
			$ret[] = array( 'mustbeposted', $this->getModuleName() );
		}

		if ( $this->isReadMode() ) {
			$ret[] = array( 'readrequired' );
		}

		if ( $this->isWriteMode() ) {
			$ret[] = array( 'writerequired' );
			$ret[] = array( 'writedisabled' );
		}

		if ( $this->needsToken() ) {
			$ret[] = array( 'missingparam', 'token' );
			$ret[] = array( 'sessionfailure' );
		}

		return $ret;
	}

	/**
	 * Parses a list of errors into a standardised format
	 * @param $errors array List of errors. Items can be in the for array( key, param1, param2, ... ) or array( 'code' => ..., 'info' => ... )
	 * @return array Parsed list of errors with items in the form array( 'code' => ..., 'info' => ... )
	 */
	public function parseErrors( $errors ) {
		$ret = array();

		foreach ( $errors as $row ) {
			if ( isset( $row['code'] ) && isset( $row['info'] ) ) {
				$ret[] = $row;
			} else {
				$ret[] = $this->parseMsg( $row );
			}
		}
		return $ret;
	}

	/**
	 * Profiling: total module execution time
	 */
	private $mTimeIn = 0, $mModuleTime = 0;

	/**
	 * Start module profiling
	 */
	public function profileIn() {
		if ( $this->mTimeIn !== 0 ) {
			ApiBase::dieDebug( __METHOD__, 'called twice without calling profileOut()' );
		}
		$this->mTimeIn = microtime( true );
		wfProfileIn( $this->getModuleProfileName() );
	}

	/**
	 * End module profiling
	 */
	public function profileOut() {
		if ( $this->mTimeIn === 0 ) {
			ApiBase::dieDebug( __METHOD__, 'called without calling profileIn() first' );
		}
		if ( $this->mDBTimeIn !== 0 ) {
			ApiBase::dieDebug( __METHOD__, 'must be called after database profiling is done with profileDBOut()' );
		}

		$this->mModuleTime += microtime( true ) - $this->mTimeIn;
		$this->mTimeIn = 0;
		wfProfileOut( $this->getModuleProfileName() );
	}

	/**
	 * When modules crash, sometimes it is needed to do a profileOut() regardless
	 * of the profiling state the module was in. This method does such cleanup.
	 */
	public function safeProfileOut() {
		if ( $this->mTimeIn !== 0 ) {
			if ( $this->mDBTimeIn !== 0 ) {
				$this->profileDBOut();
			}
			$this->profileOut();
		}
	}

	/**
	 * Total time the module was executed
	 * @return float
	 */
	public function getProfileTime() {
		if ( $this->mTimeIn !== 0 ) {
			ApiBase::dieDebug( __METHOD__, 'called without calling profileOut() first' );
		}
		return $this->mModuleTime;
	}

	/**
	 * Profiling: database execution time
	 */
	private $mDBTimeIn = 0, $mDBTime = 0;

	/**
	 * Start module profiling
	 */
	public function profileDBIn() {
		if ( $this->mTimeIn === 0 ) {
			ApiBase::dieDebug( __METHOD__, 'must be called while profiling the entire module with profileIn()' );
		}
		if ( $this->mDBTimeIn !== 0 ) {
			ApiBase::dieDebug( __METHOD__, 'called twice without calling profileDBOut()' );
		}
		$this->mDBTimeIn = microtime( true );
		wfProfileIn( $this->getModuleProfileName( true ) );
	}

	/**
	 * End database profiling
	 */
	public function profileDBOut() {
		if ( $this->mTimeIn === 0 ) {
			ApiBase::dieDebug( __METHOD__, 'must be called while profiling the entire module with profileIn()' );
		}
		if ( $this->mDBTimeIn === 0 ) {
			ApiBase::dieDebug( __METHOD__, 'called without calling profileDBIn() first' );
		}

		$time = microtime( true ) - $this->mDBTimeIn;
		$this->mDBTimeIn = 0;

		$this->mDBTime += $time;
		$this->getMain()->mDBTime += $time;
		wfProfileOut( $this->getModuleProfileName( true ) );
	}

	/**
	 * Total time the module used the database
	 * @return float
	 */
	public function getProfileDBTime() {
		if ( $this->mDBTimeIn !== 0 ) {
			ApiBase::dieDebug( __METHOD__, 'called without calling profileDBOut() first' );
		}
		return $this->mDBTime;
	}

	/**
	 * Debugging function that prints a value and an optional backtrace
	 * @param $value mixed Value to print
	 * @param $name string Description of the printed value
	 * @param $backtrace bool If true, print a backtrace
	 */
	public static function debugPrint( $value, $name = 'unknown', $backtrace = false ) {
		print "\n\n<pre><b>Debugging value '$name':</b>\n\n";
		var_export( $value );
		if ( $backtrace ) {
			print "\n" . wfBacktrace();
		}
		print "\n</pre>\n";
	}

	/**
	 * Returns a string that identifies the version of this class.
	 * @return string
	 */
	public static function getBaseVersion() {
		return __CLASS__ . ': $Id$';
	}
}
