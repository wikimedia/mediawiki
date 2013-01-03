<?php
/**
 *
 *
 * Created on Sep 7, 2006
 *
 * Copyright © 2006,2013 Yuri Astrakhan "<Firstname><Lastname>@gmail.com"
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
 * This is the main query class. It behaves similar to ApiMain: based on the
 * parameters given, it will create a list of titles to work on (an ApiPageSet
 * object), instantiate and execute various property/list/meta modules, and
 * assemble all resulting data into a single ApiResult object.
 *
 * In generator mode, a generator will be executed first to populate a second
 * ApiPageSet object, and that object will be used for all subsequent modules.
 *
 * @ingroup API
 */
class ApiQuery extends ApiBase {

	const BadContinueMsg =
		'Invalid continue parameter. All original request parameters must be merged with the query-continue values returned by the previous query';

	private $mPropModuleNames, $mListModuleNames, $mMetaModuleNames;

	/**
	 * @var ApiPageSet
	 */
	private $mPageSet;

	private $params, $redirects, $convertTitles, $iwUrl;

	/**
	 * List of Api Query prop modules
	 * @var array
	 */
	private $mQueryPropModules = array(
		'categories' => 'ApiQueryCategories',
		'categoryinfo' => 'ApiQueryCategoryInfo',
		'duplicatefiles' => 'ApiQueryDuplicateFiles',
		'extlinks' => 'ApiQueryExternalLinks',
		'images' => 'ApiQueryImages',
		'imageinfo' => 'ApiQueryImageInfo',
		'info' => 'ApiQueryInfo',
		'links' => 'ApiQueryLinks',
		'iwlinks' => 'ApiQueryIWLinks',
		'langlinks' => 'ApiQueryLangLinks',
		'pageprops' => 'ApiQueryPageProps',
		'revisions' => 'ApiQueryRevisions',
		'stashimageinfo' => 'ApiQueryStashImageInfo',
		'templates' => 'ApiQueryLinks',
	);

	/**
	 * List of Api Query list modules
	 * @var array
	 */
	private $mQueryListModules = array(
		'allcategories' => 'ApiQueryAllCategories',
		'allimages' => 'ApiQueryAllImages',
		'alllinks' => 'ApiQueryAllLinks',
		'allpages' => 'ApiQueryAllPages',
		'alltransclusions' => 'ApiQueryAllLinks',
		'allusers' => 'ApiQueryAllUsers',
		'backlinks' => 'ApiQueryBacklinks',
		'blocks' => 'ApiQueryBlocks',
		'categorymembers' => 'ApiQueryCategoryMembers',
		'deletedrevs' => 'ApiQueryDeletedrevs',
		'embeddedin' => 'ApiQueryBacklinks',
		'exturlusage' => 'ApiQueryExtLinksUsage',
		'filearchive' => 'ApiQueryFilearchive',
		'imageusage' => 'ApiQueryBacklinks',
		'iwbacklinks' => 'ApiQueryIWBacklinks',
		'langbacklinks' => 'ApiQueryLangBacklinks',
		'logevents' => 'ApiQueryLogEvents',
		'protectedtitles' => 'ApiQueryProtectedTitles',
		'querypage' => 'ApiQueryQueryPage',
		'random' => 'ApiQueryRandom',
		'recentchanges' => 'ApiQueryRecentChanges',
		'search' => 'ApiQuerySearch',
		'tags' => 'ApiQueryTags',
		'usercontribs' => 'ApiQueryContributions',
		'users' => 'ApiQueryUsers',
		'watchlist' => 'ApiQueryWatchlist',
		'watchlistraw' => 'ApiQueryWatchlistRaw',
	);

	/**
	 * List of Api Query meta modules
	 * @var array
	 */
	private $mQueryMetaModules = array(
		'allmessages' => 'ApiQueryAllMessages',
		'siteinfo' => 'ApiQuerySiteinfo',
		'userinfo' => 'ApiQueryUserInfo',
	);

	/**
	 * List of Api Query generator modules
	 * Defined in code, rather than being derived at runtime,
	 * due to performance reasons
	 * @var array
	 */
	private $mQueryGenerators = array(
		'allcategories' => 'ApiQueryAllCategories',
		'allimages' => 'ApiQueryAllImages',
		'alllinks' => 'ApiQueryAllLinks',
		'allpages' => 'ApiQueryAllPages',
		'alltransclusions' => 'ApiQueryAllLinks',
		'backlinks' => 'ApiQueryBacklinks',
		'categories' => 'ApiQueryCategories',
		'categorymembers' => 'ApiQueryCategoryMembers',
		'duplicatefiles' => 'ApiQueryDuplicateFiles',
		'embeddedin' => 'ApiQueryBacklinks',
		'exturlusage' => 'ApiQueryExtLinksUsage',
		'images' => 'ApiQueryImages',
		'imageusage' => 'ApiQueryBacklinks',
		'iwbacklinks' => 'ApiQueryIWBacklinks',
		'langbacklinks' => 'ApiQueryLangBacklinks',
		'links' => 'ApiQueryLinks',
		'protectedtitles' => 'ApiQueryProtectedTitles',
		'querypage' => 'ApiQueryQueryPage',
		'random' => 'ApiQueryRandom',
		'recentchanges' => 'ApiQueryRecentChanges',
		'search' => 'ApiQuerySearch',
		'templates' => 'ApiQueryLinks',
		'watchlist' => 'ApiQueryWatchlist',
		'watchlistraw' => 'ApiQueryWatchlistRaw',
	);

	private $mSlaveDB = null;
	private $mNamedDB = array();

	protected $mAllowedGenerators;

	/**
	 * @param $main ApiMain
	 * @param $action string
	 */
	public function __construct( $main, $action ) {
		parent::__construct( $main, $action );

		// Allow custom modules to be added in LocalSettings.php
		global $wgAPIPropModules, $wgAPIListModules, $wgAPIMetaModules, $wgAPIGeneratorModules;
		self::appendUserModules( $this->mQueryPropModules, $wgAPIPropModules );
		self::appendUserModules( $this->mQueryListModules, $wgAPIListModules );
		self::appendUserModules( $this->mQueryMetaModules, $wgAPIMetaModules );
		self::appendUserModules( $this->mQueryGenerators, $wgAPIGeneratorModules );

		$this->mPropModuleNames = array_keys( $this->mQueryPropModules );
		$this->mListModuleNames = array_keys( $this->mQueryListModules );
		$this->mMetaModuleNames = array_keys( $this->mQueryMetaModules );
		$this->mAllowedGenerators = array_keys( $this->mQueryGenerators );
	}

	/**
	 * Helper function to append any add-in modules to the list
	 * @param $modules array Module array
	 * @param $newModules array Module array to add to $modules
	 */
	private static function appendUserModules( &$modules, $newModules ) {
		if ( is_array( $newModules ) ) {
			foreach ( $newModules as $moduleName => $moduleClass ) {
				$modules[$moduleName] = $moduleClass;
			}
		}
	}

	/**
	 * Gets a default slave database connection object
	 * @return DatabaseBase
	 */
	public function getDB() {
		if ( !isset( $this->mSlaveDB ) ) {
			$this->profileDBIn();
			$this->mSlaveDB = wfGetDB( DB_SLAVE, 'api' );
			$this->profileDBOut();
		}
		return $this->mSlaveDB;
	}

	/**
	 * Get the query database connection with the given name.
	 * If no such connection has been requested before, it will be created.
	 * Subsequent calls with the same $name will return the same connection
	 * as the first, regardless of the values of $db and $groups
	 * @param $name string Name to assign to the database connection
	 * @param $db int One of the DB_* constants
	 * @param $groups array Query groups
	 * @return DatabaseBase
	 */
	public function getNamedDB( $name, $db, $groups ) {
		if ( !array_key_exists( $name, $this->mNamedDB ) ) {
			$this->profileDBIn();
			$this->mNamedDB[$name] = wfGetDB( $db, $groups );
			$this->profileDBOut();
		}
		return $this->mNamedDB[$name];
	}

	/**
	 * Gets the set of pages the user has requested (or generated)
	 * @return ApiPageSet
	 */
	public function getPageSet() {
		return $this->mPageSet;
	}

	/**
	 * Get the array mapping module names to class names
	 * @return array array(modulename => classname)
	 */
	public function getModules() {
		return array_merge( $this->mQueryPropModules, $this->mQueryListModules, $this->mQueryMetaModules );
	}

	/**
	 * Get the generators array mapping module names to class names
	 * @return array array(modulename => classname)
	 */
	public function getGenerators() {
		return $this->mQueryGenerators;
	}

	/**
	 * Get whether the specified module is a prop, list or a meta query module
	 * @param $moduleName string Name of the module to find type for
	 * @return mixed string or null
	 */
	function getModuleType( $moduleName ) {
		if ( isset( $this->mQueryPropModules[$moduleName] ) ) {
			return 'prop';
		}

		if ( isset( $this->mQueryListModules[$moduleName] ) ) {
			return 'list';
		}

		if ( isset( $this->mQueryMetaModules[$moduleName] ) ) {
			return 'meta';
		}

		return null;
	}

	/**
	 * @return ApiFormatRaw|null
	 */
	public function getCustomPrinter() {
		// If &exportnowrap is set, use the raw formatter
		if ( $this->getParameter( 'export' ) &&
				$this->getParameter( 'exportnowrap' ) )
		{
			return new ApiFormatRaw( $this->getMain(),
				$this->getMain()->createPrinterByName( 'xml' ) );
		} else {
			return null;
		}
	}

	/**
	 * Query execution happens in the following steps:
	 * #1 Create a PageSet object with any pages requested by the user
	 * #2 If using a generator, execute it to get a new ApiPageSet object
	 * #3 Instantiate all requested modules.
	 *    This way the PageSet object will know what shared data is required,
	 *    and minimize DB calls.
	 * #4 Output all normalization and redirect resolution information
	 * #5 Execute all requested modules
	 *
	 * In the 'smart continue' mode, all 'continue' values are set in such a way
	 * as to minimize client logic needed to optimally iterate over the resultset.
	 * Assuming the client has the param=>value dict with the original request
	 * parameters, client will only need to perform this operation in their language:
	 *   $request = array_merge( $request, $result['query-continue'] );
	 * before the next api call. The client should not be making any additional
	 * request parameter modifications. The query is complete when the result has
	 * no query-continue section.
	 *
	 *
	 * Smart-continue Implementation Summary:
	 * - continue= param lists all currently enumerating submodules
	 * - Without generator, treat each submodule iteration independently, and if one of
	 * them finishes before others, remove it from the 'continue=' parameter, thus
	 * skipping its execution in the next calls.
	 * - With generator, do not advance generator until all prop= submodules have
	 * finished, than advance generator once, reset all prop= parameters to original
	 * state (of the first request), and repeat. Non prop= submodules are treated
	 * independently just like there is no generator.
	 *
	 */
	public function execute() {
		$this->params = $this->extractRequestParams();
		$this->redirects = $this->params['redirects'];
		$this->convertTitles = $this->params['converttitles'];
		$this->iwUrl = $this->params['iwurl'];
		$this->mUseContinue = isset( $this->params['continue'] );
		$this->mUseGenerator = isset( $this->params['generator'] );

		// Create PageSet
		$this->mPageSet = new ApiPageSet( $this, $this->redirects, $this->convertTitles );

		// Instantiate requested modules
		$this->instantiateModules( 'prop', $this->mQueryPropModules );
		$this->instantiateModules( 'list', $this->mQueryListModules );
		$this->instantiateModules( 'meta', $this->mQueryMetaModules );

		$cacheMode = 'public';

		if ( $this->mUseContinue ) {
			$continue = $this->prepareSmartContinue( $this->params['continue'] );
		}

		// If given, execute generator to substitute user supplied data with generated data.
		if ( $this->mUseGenerator ) {
			$genName = $this->params['generator'];
			$generator = $this->newGenerator( $genName );
			$params = $generator->extractRequestParams();
			if ( !$this->mUseContinue || !$this->mSkipGenerator ) {
				$cacheMode = $this->mergeCacheMode( $cacheMode, $generator->getCacheMode( $params ) );
				$this->executeGeneratorModule( $generator );
			} // else we are done with generator+properties, complete other things like lists and meta.
		} else {
			// Append custom fields and populate page/revision information
			$this->addCustomFldsToPageSet( $this->mPageSet );
			$this->mPageSet->execute();
			$generator = null;
		}

		// Record page information (title, namespace, if exists, etc)
		$this->outputGeneralPageInfo();

		// Execute all requested modules
		foreach ( $this->mActiveModules as $module ) {
			$params = $module->extractRequestParams();
			$cacheMode = $this->mergeCacheMode(
				$cacheMode, $module->getCacheMode( $params ) );
			$module->profileIn();
			$module->execute();
			wfRunHooks( 'APIQueryAfterExecute', array( &$module ) );
			$module->profileOut();
		}

		if ( $this->mUseContinue ) {
			$this->finishSmartContinue( $continue, $generator );
		}

		// Set the cache mode
		$this->getMain()->setCacheMode( $cacheMode );
	}

	/**
	 * List of all module instances created for this request
	 * @var array
	 */
	private $mActiveModules = array();

	/**
	 * All modules with their parameters that have completed for the currently
	 * generated pageset. Will be used to resume on the next pageset.
	 * @var array moduleName=>array(params)
	 */
	private $mIgnoredProps = array();

	/**
	 * True if the request includes 'continue' parameter.
	 * @var boolean
	 */
	private $mUseContinue = false;

	/**
	 * True if the request includes 'generator' parameter.
	 * @var boolean
	 */
	private $mUseGenerator = false;

	/**
	 * True if there is no generator or if generator has finished.
	 * @var boolean
	 */
	private $mSkipGenerator = false;

	/**
	 * In generator + smart continue mode, will hold generator's 'continue' values
	 * until they are ready to be added to the result.
	 * @var array
	 */
	private $mGeneratorContinue = null;

	/**
	 * Parse 'continue' parameter, filter modules list, reset properties request values
	 *
	 * Smart-Continue Implementation Details:
	 *
	 * An empty 'continue' specifies the first call
	 *
	 * After initial call, continue parameter contains the '||' separated list
	 * of all unfinished submodules except generator. All submodules are always
	 * instantiated and their extractRequestParams() called to avoid warnings,
	 * but only those on this list are actually executed: 'continue=prop1||prop2||list1'
	 *
	 * In generator mode, the generator and prop= submodules get additional handling.
	 * The prop values in the 'continue' string become '|' separated list of values:
	 *   'continue=  prop1|prm1-1 || prop2|prm2-1|prm2-2 || list1'
	 *
	 * If any of the prop submodules call base::setContinueEnumParameter(),
	 * none of the generator's query-continue values are added to the result.
	 * Without them, the next client call will contain the same generator parameters
	 * as in the current call, producing the same result from the generator.
	 *
	 * When processing a generated pageset, some prop submodules may finish before
	 * others, and will need to suspend until the rest of the props finish. An early
	 * finished prop changes is preceded with an empty value in 'continue':
	 *    'continue= ... || |prop2|prm2-1|prm2-2 || ... ' (spaces added for clarity)
	 *
	 * Once all props are done, the generator continuations are added to the result,
	 * and all prop submodules must need to be restarted with the original parameters
	 * and without any prop-continues. To allow for prop restarts, we need to track
	 * any parameters added or changed by the prop submodule in the 'query-continue' section.
	 *
	 * If, after the prop's execution, a new 'param=value' is added to
	 * query-continue, the prop's value in the 'continue' parameter changes to
	 * 'continue=...||prop1|param||...',  which means that on restart this param has to
	 * be removed from the request's parameter list.
	 *
	 * If, instead, the prop changes an existing parameter to a new value, in addition
	 * to adding 'param' to the 'continue', a new parameter is added to the query-continue
	 * section: '__param=originalvalue' so that it can be later restored on reset.
	 *
	 * If all props are done, the next call must unset all parameters added by previous
	 * continue iterations, and restore the changed ones. All changed are simply re-added
	 * to the 'continue' section from the saved '__param' parameters. The notification of
	 * unsetting is done by changing 'continue=... || prop1|*prm1|*prm2 ||'.
	 *
	 * If the generator and all properties finish before other list or meta modules,
	 * the continue string will start with an empty element: 'continue=||list1||meta1||...'
	 *
	 * In the very special case of ApiQueryRandom generator which always creates a
	 * different pagesets, override getGeneratorPageRepeatValue() to adjust 'g__continue'.
	 *
	 * @param string $cont user 'continue' string parameter
	 */
	private function prepareSmartContinue( $cont ) {
		if ( $cont === '' ) {
			// Very first run, initialize continue to be array( 'modulename' => null )
			$cont = array_map( function( $v ) { return $v->getModuleName(); }, $this->mActiveModules );
			$continue = array_flip( $cont );
			array_walk( $continue, function( &$v, $k ) { $v = array(); } );
			$this->mSkipGenerator = !$this->mUseGenerator;
			return $continue;
		}

		// break 'continue' param into 'module'=>'values' array
		$cont = explode( '||', $cont );
		$this->mSkipGenerator = ( $cont[0] === '' ); // true=no more generator
		$this->mUnsetParameters = array();
		if ( $this->mSkipGenerator ) {
			array_shift( $cont ); // remove empty first element
		} elseif ( !$this->mUseGenerator ) {
			// Error: There is no generator, how can we not skip it
			$this->dieUsage( self::BadContinueMsg, "badcontinue" );
		}

		// Each string is in format 'module|val1|val2'. Put into ignore if starts with '|'.
		// Populate $continue and $this->mIgnoredProps with 'module' => 'val1|val2'
		// mPropResetVals is an array of paramName => original_request_value or null if added by continue
		$this->mPropResetVals = array();
		$continue = array();
		$unsetParams = false;
		foreach ( $cont as $str ) {
			$vals = explode( '|', $str );
			$ignoreProp = ( $vals[0] === '' );
			if ( $ignoreProp ) {
				array_shift( $vals );
				if ( empty( $vals ) ) {
					$this->dieUsage( self::BadContinueMsg, "badcontinue" );
				}
			}
			$name = array_shift( $vals );
			if ( isset( $continue[ $name ] ) || isset( $this->mIgnoredProps[ $name ] ) ) {
				$this->dieUsage( self::BadContinueMsg, "badcontinue" );
			}
			if ( $ignoreProp ) {
				$this->mIgnoredProps[ $name ] = $vals;
			} else {
				$continue[ $name ] = $vals;
			}
			foreach ( $vals as $v ) {
				if ( $v[0] === '*' ) {
					// if param name begins with '*' - original request did not have this param,
					// continue added it, and now we need to remove it from request to simulate prop restart
					if ( !is_null( $this->getRequest()->getVal( $v ) ) ) {
						// unsetting property shouldn't have a saved value
						$this->dieUsage( self::BadContinueMsg, "badcontinue" );
					}
					$this->getRequest()->unsetVal( substr( $v, 1 ) );
					$this->mPropResetVals[ $v ] = null;
				} else {
					// if original param was changed by continue, find the original value, otherwise null
					$this->mPropResetVals[ $v ] = $this->getMain()->getVal( '__' . $v );
				}
			}
		}

		// Filter modules to only the ones in $continue
		$this->mActiveModules = array_filter( $this->mActiveModules,
				function ( $m ) use ( $continue ) { return isset( $continue[ $m->getModuleName() ] ); } );
		if ( empty( $this->mActiveModules ) ) {
			$this->dieUsage( self::BadContinueMsg, "badcontinue" );
		}

		return $continue;
	}

	/**
	 * Do post-execution correction of the 'query-continue' section.
	 * @see self::prepareSmartContinue() for detailed description.
	 * @param array $continue parsed continue parameter
	 * @param ApiQueryGenatorBase|null $generator generator instance
	 */
	private function finishSmartContinue( $continue, $generator ) {
		// Reformat query-continue result section
		$result = $this->getResult();
		$queryContinue = $result->getData();
		if ( isset( $queryContinue[ 'query-continue' ] ) ) {
			$queryContinue = $queryContinue[ 'query-continue' ];
			$result->unsetValue( null, 'query-continue' );
		} elseif ( !is_null( $this->mGeneratorContinue ) ) {
			$queryContinue = array();
		} else {
			// no more 'continue's, we are done!
			return;
		}

		$newContinue = array();
		$newQueryContinue = array();
		$doneContinues = array_diff_key( $continue, $queryContinue ); // which continues are not in qc
		$continue = array_intersect_key( $continue, $queryContinue ); // still working on these

		if ( !$this->mSkipGenerator ) {
			// Still using generator
			// Store finished prop continues for later restart
			$this->mIgnoredProps = $this->mIgnoredProps + array_intersect_key( $doneContinues, $this->mQueryPropModules );

			$propsLeft = false;
			foreach ( $queryContinue as $moduleName => $moduleQc ) {
				if ( !isset( $this->mQueryPropModules[ $moduleName ] ) ) {
					continue; // not a prop, not interested
				}
				$propsLeft = true;
				$cont = &$continue[ $moduleName ];
				foreach ( $moduleQc as $paramName => $paramValue ) {
					if ( !in_array( $paramName, $cont ) ) {
						$pos = array_search( '*' . $paramName, $cont );
						if ( $pos !== false ) {
							// We were reseting parameter, now it is being supplied by continue. remove '*'
							$cont[$pos] = $paramName;
						} else {
							// New query-continue param for this property
							$cont[] = $paramName;
							$reqValue = $this->getRequest()->getVal( $paramName );
							if ( !is_null( $reqValue ) ) {
								// This parameter was part of the original user request, save it
								$newQueryContinue[ '__' . $paramName ] = $reqValue;
							}
						}
					}
				}
				unset( $cont );
			}
			if ( !$propsLeft ) {
				if ( is_null( $this->mGeneratorContinue ) ) {
					$newContinue[] = ''; // Done with the generator
				} else {
					// Done with generated pageset, reset props' arguments & let generator continue
					$continue = array_merge( $continue, $this->mIgnoredProps );
					$this->mIgnoredProps = array();
					$newQueryContinue += $this->mGeneratorContinue;

					// reseting prop= request by reseting changed params to initial value, and marking new parameters for deletion
					foreach ( $continue as $moduleName => $vals) {
						if ( isset( $this->mQueryPropModules[ $moduleName ] ) ) {
							foreach ( $vals as &$paramName ) {
								$paramValue = $this->mPropResetVals[ $paramName ];
								if ( !is_null( $paramValue ) ) {
									$newQueryContinue[ $paramName ] = $paramValue;
								} elseif ( $paramName[0] !== '*' ) {
									$paramName = '*' . $paramName;
								} // else we are already unsetting this param
							}
						}
					}
				}
			} else {
				// continue the currently generated page, ignoring all generator's continues
				// in case the generator is aware of smart continue, it can change its value
				// generator must be using only one value to continue
				if ( count( $this->mGeneratorContinue ) <= 1 ) {
					$genRpt = $generator->getGeneratorPageRepeatValue();
					if ( !is_null( $genRpt ) ) {
						$newQueryContinue[$generator->encodeParamName( 'continue' )] = $genRpt;
					}
				}
			}
		} else {
			$newContinue[] = ''; // Done with the generator
		}

		// Form continue settings
		foreach ( $continue as $moduleName => $paramValue ) {
			$newContinue[] = self::keyValueToValues( false, $moduleName, $paramValue );
		}
		foreach ( $this->mIgnoredProps as $moduleName => $paramValue ) {
			$newContinue[] = self::keyValueToValues( true, $moduleName, $paramValue );
		}
		$newQueryContinue['continue'] = implode( '||', $newContinue );

		// Merge all query continues into one array
		foreach ( $queryContinue as $moduleName => $paramValue ) {
			$newQueryContinue += $paramValue;
		}
		$this->getResult()->addValue( null, 'query-continue', $newQueryContinue );
	}

	/**
	 * Helper function to generate 'continue' value for one module
	 * @param boolean $insertEmpty prepend empty first element
	 * @param string $moduleName prepend the name of the module
	 * @param array $values the rest of values to implode
	 * @return string resulting '|'-separated string
	 */
	private static function keyValueToValues( $insertEmpty, $moduleName, $values ) {
		array_unshift( $values, $moduleName );
		if ( $insertEmpty ) {
			array_unshift( $values, '' );
		}
		return implode( '|', $values );
	}

	/**
	 * True if the client requested smart query continuation
	 * @return boolean
	 */
	public function useSmartContinue() {
		return $this->mUseContinue;
	}

	/**
	 * This method is called by the generator base when generator in the smart-continue
	 * mode tries to set 'query-continue' value. ApiQuery stores those values separately
	 * until the post-processing when it is known if the generation should continue or repeat.
	 * @param string $paramName
	 * @param mixed $paramValue
	 */
	public function setGeneratorContinue( $paramName, $paramValue ) {
		if ( is_null( $this->mGeneratorContinue ) ) {
			$this->mGeneratorContinue = array();
		}
		$this->mGeneratorContinue[ $paramName ] = $paramValue;
	}

	/**
	 * Update a cache mode string, applying the cache mode of a new module to it.
	 * The cache mode may increase in the level of privacy, but public modules
	 * added to private data do not decrease the level of privacy.
	 *
	 * @param $cacheMode string
	 * @param $modCacheMode string
	 * @return string
	 */
	protected function mergeCacheMode( $cacheMode, $modCacheMode ) {
		if ( $modCacheMode === 'anon-public-user-private' ) {
			if ( $cacheMode !== 'private' ) {
				$cacheMode = 'anon-public-user-private';
			}
		} elseif ( $modCacheMode === 'public' ) {
			// do nothing, if it's public already it will stay public
		} else { // private
			$cacheMode = 'private';
		}
		return $cacheMode;
	}

	/**
	 * Query modules may optimize data requests through the $this->getPageSet() object
	 * by adding extra fields from the page table.
	 * This function will gather all the extra request fields from the modules.
	 * @param $pageSet ApiPageSet
	 */
	private function addCustomFldsToPageSet( $pageSet ) {
		// Query all requested modules.
		/**
		 * @var $module ApiQueryBase
		 */
		foreach ( $this->mActiveModules as $module ) {
			$module->requestExtraData( $pageSet );
		}
	}

	/**
	 * Create instances of all modules requested by the client
	 * @param $param string Parameter name to read modules from
	 * @param $moduleList Array array(modulename => classname)
	 */
	private function instantiateModules( $param, $moduleList ) {
		if ( isset( $this->params[$param] ) ) {
			foreach ( $this->params[$param] as $moduleName ) {
				$instance = new $moduleList[$moduleName] ( $this, $moduleName );
				$this->mActiveModules[] = $instance;
			}
		}
	}

	/**
	 * Appends an element for each page in the current pageSet with the
	 * most general information (id, title), plus any title normalizations
	 * and missing or invalid title/pageids/revids.
	 */
	private function outputGeneralPageInfo() {
		$pageSet = $this->getPageSet();
		$result = $this->getResult();

		// We don't check for a full result set here because we can't be adding
		// more than 380K. The maximum revision size is in the megabyte range,
		// and the maximum result size must be even higher than that.

		// Title normalizations
		$normValues = array();
		foreach ( $pageSet->getNormalizedTitles() as $rawTitleStr => $titleStr ) {
			$normValues[] = array(
				'from' => $rawTitleStr,
				'to' => $titleStr
			);
		}

		if ( count( $normValues ) ) {
			$result->setIndexedTagName( $normValues, 'n' );
			$result->addValue( 'query', 'normalized', $normValues );
		}

		// Title conversions
		$convValues = array();
		foreach ( $pageSet->getConvertedTitles() as $rawTitleStr => $titleStr ) {
			$convValues[] = array(
				'from' => $rawTitleStr,
				'to' => $titleStr
			);
		}

		if ( count( $convValues ) ) {
			$result->setIndexedTagName( $convValues, 'c' );
			$result->addValue( 'query', 'converted', $convValues );
		}

		// Interwiki titles
		$intrwValues = array();
		foreach ( $pageSet->getInterwikiTitles() as $rawTitleStr => $interwikiStr ) {
			$item = array(
				'title' => $rawTitleStr,
				'iw' => $interwikiStr,
			);
			if ( $this->iwUrl ) {
				$title = Title::newFromText( $rawTitleStr );
				$item['url'] = wfExpandUrl( $title->getFullURL(), PROTO_CURRENT );
			}
			$intrwValues[] = $item;
		}

		if ( count( $intrwValues ) ) {
			$result->setIndexedTagName( $intrwValues, 'i' );
			$result->addValue( 'query', 'interwiki', $intrwValues );
		}

		// Show redirect information
		$redirValues = array();
		/**
		 * @var $titleTo Title
		 */
		foreach ( $pageSet->getRedirectTitles() as $titleStrFrom => $titleTo ) {
			$r = array(
				'from' => strval( $titleStrFrom ),
				'to' => $titleTo->getPrefixedText(),
			);
			if ( $titleTo->getFragment() !== '' ) {
				$r['tofragment'] = $titleTo->getFragment();
			}
			$redirValues[] = $r;
		}

		if ( count( $redirValues ) ) {
			$result->setIndexedTagName( $redirValues, 'r' );
			$result->addValue( 'query', 'redirects', $redirValues );
		}

		// Missing revision elements
		$missingRevIDs = $pageSet->getMissingRevisionIDs();
		if ( count( $missingRevIDs ) ) {
			$revids = array();
			foreach ( $missingRevIDs as $revid ) {
				$revids[$revid] = array(
					'revid' => $revid
				);
			}
			$result->setIndexedTagName( $revids, 'rev' );
			$result->addValue( 'query', 'badrevids', $revids );
		}

		// Page elements
		$pages = array();

		// Report any missing titles
		foreach ( $pageSet->getMissingTitles() as $fakeId => $title ) {
			$vals = array();
			ApiQueryBase::addTitleInfo( $vals, $title );
			$vals['missing'] = '';
			$pages[$fakeId] = $vals;
		}
		// Report any invalid titles
		foreach ( $pageSet->getInvalidTitles() as $fakeId => $title ) {
			$pages[$fakeId] = array( 'title' => $title, 'invalid' => '' );
		}
		// Report any missing page ids
		foreach ( $pageSet->getMissingPageIDs() as $pageid ) {
			$pages[$pageid] = array(
				'pageid' => $pageid,
				'missing' => ''
			);
		}
		// Report special pages
		foreach ( $pageSet->getSpecialTitles() as $fakeId => $title ) {
			$vals = array();
			ApiQueryBase::addTitleInfo( $vals, $title );
			$vals['special'] = '';
			if ( $title->isSpecialPage() &&
					!SpecialPageFactory::exists( $title->getDbKey() ) ) {
				$vals['missing'] = '';
			} elseif ( $title->getNamespace() == NS_MEDIA &&
					!wfFindFile( $title ) ) {
				$vals['missing'] = '';
			}
			$pages[$fakeId] = $vals;
		}

		// Output general page information for found titles
		foreach ( $pageSet->getGoodTitles() as $pageid => $title ) {
			$vals = array();
			$vals['pageid'] = $pageid;
			ApiQueryBase::addTitleInfo( $vals, $title );
			$pages[$pageid] = $vals;
		}

		if ( count( $pages ) ) {
			if ( $this->params['indexpageids'] ) {
				$pageIDs = array_keys( $pages );
				// json treats all map keys as strings - converting to match
				$pageIDs = array_map( 'strval', $pageIDs );
				$result->setIndexedTagName( $pageIDs, 'id' );
				$result->addValue( 'query', 'pageids', $pageIDs );
			}

			$result->setIndexedTagName( $pages, 'page' );
			$result->addValue( 'query', 'pages', $pages );
		}
		if ( $this->params['export'] ) {
			$this->doExport( $pageSet, $result );
		}
	}

	/**
	 * @param  $pageSet ApiPageSet Pages to be exported
	 * @param  $result ApiResult Result to output to
	 */
	private function doExport( $pageSet, $result ) {
		$exportTitles = array();
		$titles = $pageSet->getGoodTitles();
		if ( count( $titles ) ) {
			$user = $this->getUser();
			foreach ( $titles as $title ) {
				if ( $title->userCan( 'read', $user ) ) {
					$exportTitles[] = $title;
				}
			}
		}

		$exporter = new WikiExporter( $this->getDB() );
		// WikiExporter writes to stdout, so catch its
		// output with an ob
		ob_start();
		$exporter->openStream();
		foreach ( $exportTitles as $title ) {
			$exporter->pageByTitle( $title );
		}
		$exporter->closeStream();
		$exportxml = ob_get_contents();
		ob_end_clean();

		// Don't check the size of exported stuff
		// It's not continuable, so it would cause more
		// problems than it'd solve
		$result->disableSizeCheck();
		if ( $this->params['exportnowrap'] ) {
			$result->reset();
			// Raw formatter will handle this
			$result->addValue( null, 'text', $exportxml );
			$result->addValue( null, 'mime', 'text/xml' );
		} else {
			$r = array();
			ApiResult::setContent( $r, $exportxml );
			$result->addValue( 'query', 'export', $r );
		}
		$result->enableSizeCheck();
	}

	/**
	 * Create a generator object of the given type and return it
	 * @param $generatorName string Module name
	 * @return ApiQueryGeneratorBase
	 */
	public function newGenerator( $generatorName ) {
		// Find class that implements requested generator
		if ( isset( $this->mQueryListModules[$generatorName] ) ) {
			$className = $this->mQueryListModules[$generatorName];
		} elseif ( isset( $this->mQueryPropModules[$generatorName] ) ) {
			$className = $this->mQueryPropModules[$generatorName];
		} else {
			ApiBase::dieDebug( __METHOD__, "Unknown generator=$generatorName" );
		}
		$generator = new $className ( $this, $generatorName );
		if ( !$generator instanceof ApiQueryGeneratorBase ) {
			$this->dieUsage( "Module $generatorName cannot be used as a generator", 'badgenerator' );
		}
		$generator->setGeneratorMode();
		return $generator;
	}

	/**
	 * For generator mode, execute generator, and use its output as new
	 * ApiPageSet
	 * @param $generator ApiQueryGeneratorBase Generator Module
	 */
	private function executeGeneratorModule( $generator ) {
		// Generator results
		$resultPageSet = new ApiPageSet( $this, $this->redirects, $this->convertTitles );

		// Add any additional fields modules may need
		$generator->requestExtraData( $this->mPageSet );
		$this->addCustomFldsToPageSet( $resultPageSet );

		// Populate page information with the original user input
		$this->mPageSet->execute();

		// populate resultPageSet with the generator output
		$generator->profileIn();
		$generator->executeGenerator( $resultPageSet );
		wfRunHooks( 'APIQueryGeneratorAfterExecute', array( &$generator, &$resultPageSet ) );
		$resultPageSet->finishPageSetGeneration();
		$generator->profileOut();

		// Swap the resulting pageset back in
		$this->mPageSet = $resultPageSet;
	}

	public function getAllowedParams() {
		return array(
			'prop' => array(
				ApiBase::PARAM_ISMULTI => true,
				ApiBase::PARAM_TYPE => $this->mPropModuleNames
			),
			'list' => array(
				ApiBase::PARAM_ISMULTI => true,
				ApiBase::PARAM_TYPE => $this->mListModuleNames
			),
			'meta' => array(
				ApiBase::PARAM_ISMULTI => true,
				ApiBase::PARAM_TYPE => $this->mMetaModuleNames
			),
			'generator' => array(
				ApiBase::PARAM_TYPE => $this->mAllowedGenerators
			),
			'redirects' => false,
			'converttitles' => false,
			'indexpageids' => false,
			'export' => false,
			'exportnowrap' => false,
			'iwurl' => false,
			'continue' => null,
		);
	}

	/**
	 * Override the parent to generate help messages for all available query modules.
	 * @return string
	 */
	public function makeHelpMsg() {
		// Make sure the internal object is empty
		// (just in case a sub-module decides to optimize during instantiation)
		$this->mPageSet = null;

		$querySeparator = str_repeat( '--- ', 12 );
		$moduleSeparator = str_repeat( '*** ', 14 );
		$msg = "\n$querySeparator Query: Prop  $querySeparator\n\n";
		$msg .= $this->makeHelpMsgHelper( $this->mQueryPropModules, 'prop' );
		$msg .= "\n$querySeparator Query: List  $querySeparator\n\n";
		$msg .= $this->makeHelpMsgHelper( $this->mQueryListModules, 'list' );
		$msg .= "\n$querySeparator Query: Meta  $querySeparator\n\n";
		$msg .= $this->makeHelpMsgHelper( $this->mQueryMetaModules, 'meta' );
		$msg .= "\n\n$moduleSeparator Modules: continuation  $moduleSeparator\n\n";

		// Use parent to make default message for the query module
		$msg = parent::makeHelpMsg() . $msg;

		return $msg;
	}

	/**
	 * For all modules in $moduleList, generate help messages and join them together
	 * @param $moduleList Array array(modulename => classname)
	 * @param $paramName string Parameter name
	 * @return string
	 */
	private function makeHelpMsgHelper( $moduleList, $paramName ) {
		$moduleDescriptions = array();

		foreach ( $moduleList as $moduleName => $moduleClass ) {
			/**
			 * @var $module ApiQueryBase
			 */
			$module = new $moduleClass( $this, $moduleName, null );

			$msg = ApiMain::makeHelpMsgHeader( $module, $paramName );
			$msg2 = $module->makeHelpMsg();
			if ( $msg2 !== false ) {
				$msg .= $msg2;
			}
			if ( $module instanceof ApiQueryGeneratorBase ) {
				$msg .= "Generator:\n  This module may be used as a generator\n";
			}
			$moduleDescriptions[] = $msg;
		}

		return implode( "\n", $moduleDescriptions );
	}

	/**
	 * Override to add extra parameters from PageSet
	 * @return string
	 */
	public function makeHelpMsgParameters() {
		$psModule = new ApiPageSet( $this );
		return $psModule->makeHelpMsgParameters() . parent::makeHelpMsgParameters();
	}

	public function shouldCheckMaxlag() {
		return true;
	}

	public function getParamDescription() {
		return array(
			'prop' => 'Which properties to get for the titles/revisions/pageids. Module help is available below',
			'list' => 'Which lists to get. Module help is available below',
			'meta' => 'Which metadata to get about the site. Module help is available below',
			'generator' => array( 'Use the output of a list as the input for other prop/list/meta items',
					'NOTE: generator parameter names must be prefixed with a \'g\', see examples' ),
			'redirects' => 'Automatically resolve redirects',
			'converttitles' => array( "Convert titles to other variants if necessary. Only works if the wiki's content language supports variant conversion.",
					'Languages that support variant conversion include ' . implode( ', ', LanguageConverter::$languagesWithVariants ) ),
			'indexpageids' => 'Include an additional pageids section listing all returned page IDs',
			'export' => 'Export the current revisions of all given or generated pages',
			'exportnowrap' => 'Return the export XML without wrapping it in an XML result (same format as Special:Export). Can only be used with export',
			'iwurl' => 'Whether to get the full URL if the title is an interwiki link',
			'continue' => '(Recommended) When present, ensures that all xxcontinue=xxx can be merged together without omitting data',
		);
	}

	public function getDescription() {
		return array(
			'Query API module allows applications to get needed pieces of data from the MediaWiki databases,',
			'and is loosely based on the old query.php interface.',
			'All data modifications will first have to use query to acquire a token to prevent abuse from malicious sites'
		);
	}

	public function getPossibleErrors() {
		return array_merge( parent::getPossibleErrors(), array(
			array(
					'code' => 'badgenerator',
					'info' => 'Module $generatorName cannot be used as a generator',
					'badcontinue' => self::BadContinueMsg,
				),
		) );
	}

	public function getExamples() {
		return array(
			'api.php?action=query&prop=revisions&meta=siteinfo&titles=Main%20Page&rvprop=user|comment&continue',
			'api.php?action=query&generator=allpages&gapprefix=API/&prop=revisions&continue',
		);
	}

	public function getHelpUrls() {
		return array(
			'https://www.mediawiki.org/wiki/API:Meta',
			'https://www.mediawiki.org/wiki/API:Properties',
			'https://www.mediawiki.org/wiki/API:Lists',
		);
	}

	public function getVersion() {
		$psModule = new ApiPageSet( $this );
		$vers = array();
		$vers[] = __CLASS__ . ': $Id$';
		$vers[] = $psModule->getVersion();
		return $vers;
	}
}
