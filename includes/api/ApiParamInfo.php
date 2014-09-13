<?php
/**
 *
 *
 * Created on Dec 01, 2007
 *
 * Copyright © 2008 Roan Kattouw "<Firstname>.<Lastname>@gmail.com"
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
 * @ingroup API
 */
class ApiParamInfo extends ApiBase {

	/**
	 * @var ApiQuery
	 */
	protected $queryObj;

	public function __construct( $main, $action ) {
		parent::__construct( $main, $action );
		$this->queryObj = new ApiQuery( $this->getMain(), 'query' );
	}

	public function execute() {
		// Get parameters
		$params = $this->extractRequestParams();
		$resultObj = $this->getResult();

		$res = array();

		$this->addModulesInfo( $params, 'modules', $res, $resultObj );

		$this->addModulesInfo( $params, 'querymodules', $res, $resultObj );

		if ( $params['mainmodule'] ) {
			$res['mainmodule'] = $this->getClassInfo( $this->getMain() );
		}

		if ( $params['pagesetmodule'] ) {
			$pageSet = new ApiPageSet( $this->queryObj );
			$res['pagesetmodule'] = $this->getClassInfo( $pageSet );
		}

		$this->addModulesInfo( $params, 'formatmodules', $res, $resultObj );

		$resultObj->addValue( null, $this->getModuleName(), $res );
	}

	/**
	 * If the type is requested in parameters, adds a section to res with module info.
	 * @param array $params user parameters array
	 * @param string $type parameter name
	 * @param array $res store results in this array
	 * @param ApiResult $resultObj results object to set indexed tag.
	 */
	private function addModulesInfo( $params, $type, &$res, $resultObj ) {
		if ( !is_array( $params[$type] ) ) {
			return;
		}
		$isQuery = ( $type === 'querymodules' );
		if ( $isQuery ) {
			$mgr = $this->queryObj->getModuleManager();
		} else {
			$mgr = $this->getMain()->getModuleManager();
		}
		$res[$type] = array();
		foreach ( $params[$type] as $mod ) {
			if ( !$mgr->isDefined( $mod ) ) {
				$res[$type][] = array( 'name' => $mod, 'missing' => '' );
				continue;
			}
			$obj = $mgr->getModule( $mod );
			$item = $this->getClassInfo( $obj );
			$item['name'] = $mod;
			if ( $isQuery ) {
				$item['querytype'] = $mgr->getModuleGroup( $mod );
			}
			$res[$type][] = $item;
		}
		$resultObj->setIndexedTagName( $res[$type], 'module' );
	}

	/**
	 * @param $obj ApiBase
	 * @return ApiResult
	 */
	private function getClassInfo( $obj ) {
		$result = $this->getResult();
		$retval['classname'] = get_class( $obj );
		$retval['description'] = implode( "\n", (array)$obj->getFinalDescription() );
		$retval['examples'] = '';

		// version is deprecated since 1.21, but needs to be returned for v1
		$retval['version'] = '';
		$retval['prefix'] = $obj->getModulePrefix();

		if ( $obj->isReadMode() ) {
			$retval['readrights'] = '';
		}
		if ( $obj->isWriteMode() ) {
			$retval['writerights'] = '';
		}
		if ( $obj->mustBePosted() ) {
			$retval['mustbeposted'] = '';
		}
		if ( $obj instanceof ApiQueryGeneratorBase ) {
			$retval['generator'] = '';
		}

		$allowedParams = $obj->getFinalParams( ApiBase::GET_VALUES_FOR_HELP );
		if ( !is_array( $allowedParams ) ) {
			return $retval;
		}

		$retval['helpurls'] = (array)$obj->getHelpUrls();
		if ( isset( $retval['helpurls'][0] ) && $retval['helpurls'][0] === false ) {
			$retval['helpurls'] = array();
		}
		$result->setIndexedTagName( $retval['helpurls'], 'helpurl' );

		$examples = $obj->getExamples();
		$retval['allexamples'] = array();
		if ( $examples !== false ) {
			if ( is_string( $examples ) ) {
				$examples = array( $examples );
			}
			foreach ( $examples as $k => $v ) {
				if ( strlen( $retval['examples'] ) ) {
					$retval['examples'] .= ' ';
				}
				$item = array();
				if ( is_numeric( $k ) ) {
					$retval['examples'] .= $v;
					ApiResult::setContent( $item, $v );
				} else {
					if ( !is_array( $v ) ) {
						$item['description'] = $v;
					} else {
						$item['description'] = implode( $v, "\n" );
					}
					$retval['examples'] .= $item['description'] . ' ' . $k;
					ApiResult::setContent( $item, $k );
				}
				$retval['allexamples'][] = $item;
			}
		}
		$result->setIndexedTagName( $retval['allexamples'], 'example' );

		$retval['parameters'] = array();
		$paramDesc = $obj->getFinalParamDescription();
		foreach ( $allowedParams as $n => $p ) {
			$a = array( 'name' => $n );
			if ( isset( $paramDesc[$n] ) ) {
				$a['description'] = implode( "\n", (array)$paramDesc[$n] );
			}

			//handle shorthand
			if ( !is_array( $p ) ) {
				$p = array(
					ApiBase::PARAM_DFLT => $p,
				);
			}

			//handle missing type
			if ( !isset( $p[ApiBase::PARAM_TYPE] ) ) {
				$dflt = isset( $p[ApiBase::PARAM_DFLT] ) ? $p[ApiBase::PARAM_DFLT] : null;
				if ( is_bool( $dflt ) ) {
					$p[ApiBase::PARAM_TYPE] = 'boolean';
				} elseif ( is_string( $dflt ) || is_null( $dflt ) ) {
					$p[ApiBase::PARAM_TYPE] = 'string';
				} elseif ( is_int( $dflt ) ) {
					$p[ApiBase::PARAM_TYPE] = 'integer';
				}
			}

			if ( isset( $p[ApiBase::PARAM_DEPRECATED] ) && $p[ApiBase::PARAM_DEPRECATED] ) {
				$a['deprecated'] = '';
			}
			if ( isset( $p[ApiBase::PARAM_REQUIRED] ) && $p[ApiBase::PARAM_REQUIRED] ) {
				$a['required'] = '';
			}

			if ( isset( $p[ApiBase::PARAM_DFLT] ) ) {
				$type = $p[ApiBase::PARAM_TYPE];
				if ( $type === 'boolean' ) {
					$a['default'] = ( $p[ApiBase::PARAM_DFLT] ? 'true' : 'false' );
				} elseif ( $type === 'string' ) {
					$a['default'] = strval( $p[ApiBase::PARAM_DFLT] );
				} elseif ( $type === 'integer' ) {
					$a['default'] = intval( $p[ApiBase::PARAM_DFLT] );
				} else {
					$a['default'] = $p[ApiBase::PARAM_DFLT];
				}
			}
			if ( isset( $p[ApiBase::PARAM_ISMULTI] ) && $p[ApiBase::PARAM_ISMULTI] ) {
				$a['multi'] = '';
				$a['limit'] = $this->getMain()->canApiHighLimits() ?
					ApiBase::LIMIT_SML2 :
					ApiBase::LIMIT_SML1;
				$a['lowlimit'] = ApiBase::LIMIT_SML1;
				$a['highlimit'] = ApiBase::LIMIT_SML2;
			}

			if ( isset( $p[ApiBase::PARAM_ALLOW_DUPLICATES] ) && $p[ApiBase::PARAM_ALLOW_DUPLICATES] ) {
				$a['allowsduplicates'] = '';
			}

			if ( isset( $p[ApiBase::PARAM_TYPE] ) ) {
				$a['type'] = $p[ApiBase::PARAM_TYPE];
				if ( is_array( $a['type'] ) ) {
					// To prevent sparse arrays from being serialized to JSON as objects
					$a['type'] = array_values( $a['type'] );
					$result->setIndexedTagName( $a['type'], 't' );
				}
			}
			if ( isset( $p[ApiBase::PARAM_MAX] ) ) {
				$a['max'] = $p[ApiBase::PARAM_MAX];
			}
			if ( isset( $p[ApiBase::PARAM_MAX2] ) ) {
				$a['highmax'] = $p[ApiBase::PARAM_MAX2];
			}
			if ( isset( $p[ApiBase::PARAM_MIN] ) ) {
				$a['min'] = $p[ApiBase::PARAM_MIN];
			}
			$retval['parameters'][] = $a;
		}
		$result->setIndexedTagName( $retval['parameters'], 'param' );

		$props = $obj->getFinalResultProperties();
		$listResult = null;
		if ( $props !== false ) {
			$retval['props'] = array();

			foreach ( $props as $prop => $properties ) {
				$propResult = array();
				if ( $prop == ApiBase::PROP_LIST ) {
					$listResult = $properties;
					continue;
				}
				if ( $prop != ApiBase::PROP_ROOT ) {
					$propResult['name'] = $prop;
				}
				$propResult['properties'] = array();

				foreach ( $properties as $name => $p ) {
					$propertyResult = array();

					$propertyResult['name'] = $name;

					if ( !is_array( $p ) ) {
						$p = array( ApiBase::PROP_TYPE => $p );
					}

					$propertyResult['type'] = $p[ApiBase::PROP_TYPE];

					if ( is_array( $propertyResult['type'] ) ) {
						$propertyResult['type'] = array_values( $propertyResult['type'] );
						$result->setIndexedTagName( $propertyResult['type'], 't' );
					}

					$nullable = null;
					if ( isset( $p[ApiBase::PROP_NULLABLE] ) ) {
						$nullable = $p[ApiBase::PROP_NULLABLE];
					}

					if ( $nullable === true ) {
						$propertyResult['nullable'] = '';
					}

					$propResult['properties'][] = $propertyResult;
				}

				$result->setIndexedTagName( $propResult['properties'], 'property' );
				$retval['props'][] = $propResult;
			}

			// default is true for query modules, false for other modules, overridden by ApiBase::PROP_LIST
			if ( $listResult === true || ( $listResult !== false && $obj instanceof ApiQueryBase ) ) {
				$retval['listresult'] = '';
			}

			$result->setIndexedTagName( $retval['props'], 'prop' );
		}

		// Errors
		$retval['errors'] = $this->parseErrors( $obj->getFinalPossibleErrors() );
		$result->setIndexedTagName( $retval['errors'], 'error' );

		return $retval;
	}

	public function isReadMode() {
		return false;
	}

	public function getAllowedParams() {
		$modules = $this->getMain()->getModuleManager()->getNames( 'action' );
		sort( $modules );
		$querymodules = $this->queryObj->getModuleManager()->getNames();
		sort( $querymodules );
		$formatmodules = $this->getMain()->getModuleManager()->getNames( 'format' );
		sort( $formatmodules );

		return array(
			'modules' => array(
				ApiBase::PARAM_ISMULTI => true,
				ApiBase::PARAM_TYPE => $modules,
			),
			'querymodules' => array(
				ApiBase::PARAM_ISMULTI => true,
				ApiBase::PARAM_TYPE => $querymodules,
			),
			'mainmodule' => false,
			'pagesetmodule' => false,
			'formatmodules' => array(
				ApiBase::PARAM_ISMULTI => true,
				ApiBase::PARAM_TYPE => $formatmodules,
			)
		);
	}

	public function getParamDescription() {
		return array(
			'modules' => 'List of module names (value of the action= parameter)',
			'querymodules' => 'List of query module names (value of prop=, meta= or list= parameter)',
			'mainmodule' => 'Get information about the main (top-level) module as well',
			'pagesetmodule' => 'Get information about the pageset module ' .
				'(providing titles= and friends) as well',
			'formatmodules' => 'List of format module names (value of format= parameter)',
		);
	}

	public function getDescription() {
		return 'Obtain information about certain API parameters and errors.';
	}

	public function getExamples() {
		return array(
			'api.php?action=paraminfo&modules=parse&querymodules=allpages|siteinfo'
		);
	}

	public function getHelpUrls() {
		return 'https://www.mediawiki.org/wiki/API:Parameter_information';
	}
}
