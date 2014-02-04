<?php
/**
 *
 *
 * Created on Sep 6, 2006
 *
 * Copyright © 2006 Yuri Astrakhan "<Firstname><Lastname>@gmail.com"
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
 * This is a simple class to handle action=help
 *
 * @ingroup API
 */
class ApiHelp extends ApiBase {
	/**
	 * Module for displaying help
	 */
	public function execute() {
		// Get parameters
		$params = $this->extractRequestParams();

		if ( !isset( $params['modules'] ) && !isset( $params['querymodules'] ) ) {
			$this->dieUsage( '', 'help' );
		}

		$this->getMain()->setHelp();
		$result = $this->getResult();

		if ( is_array( $params['modules'] ) ) {
			$modules = $params['modules'];
		} else {
			$modules = array();
		}

		if ( is_array( $params['querymodules'] ) ) {
			$queryModules = $params['querymodules'];
			foreach ( $queryModules as $m ) {
				$modules[] = 'query+' . $m;
			}
		} else {
			$queryModules = array();
		}

		$r = array();
		foreach ( $modules as $m ) {
			// sub-modules could be given in the form of "name[+name[+name...]]"
			$subNames = explode( '+', $m );
			if ( count( $subNames ) === 1 ) {
				// In case the '+' was typed into URL, it resolves as a space
				$subNames = explode( ' ', $m );
			}

			$module = $this->getMain();
			$subNamesCount = count( $subNames );
			for ( $i = 0; $i < $subNamesCount; $i++ ) {
				$subs = $module->getModuleManager();
				if ( $subs === null ) {
					$module = null;
				} else {
					$module = $subs->getModule( $subNames[$i] );
				}

				if ( $module === null ) {
					if ( count( $subNames ) === 2
						&& $i === 1
						&& $subNames[0] === 'query'
						&& in_array( $subNames[1], $queryModules )
					) {
						// Legacy: This is one of the renamed 'querymodule=...' parameters,
						// do not use '+' notation in the output, use submodule's name instead.
						$name = $subNames[1];
					} else {
						$name = implode( '+', array_slice( $subNames, 0, $i + 1 ) );
					}
					$r[] = array( 'name' => $name, 'missing' => '' );
					break;
				} else {
					$type = $subs->getModuleGroup( $subNames[$i] );
				}
			}

			if ( $module !== null ) {
				$r[ $module->getModuleName() ] = $this->buildModuleHelp( $module );
			}
		}

		$result->setIndexedTagName( $r, 'module' );
		$result->addValue( null, $this->getModuleName(), $r );
	}

	/**
	 * @param ApiBase $module
	 * @return array
	 */
	private function buildModuleHelp( $module ) {
		return array(
			'name' => $module->getModuleName(),
			'description' => $module->getDescription(),
			'helpmessage' => $module->makeHelpMsg(),
			'mustbeposted' => $module->mustBePosted(),
			'iswritemode' => $module->isWriteMode(),
			'needstoken' => $module->needsToken(),
			'params' => $this->buildModuleParams( $module )
		);
	}

	/**
	 * @param ApiBase $module
	 * @return array
	 */
	private function buildModuleParams( $module ) {
		$ret = array();
		$final = $module->getFinalParams();
		$descs = $module->getParamDescription();
		foreach( $final as $param => $details ) {
			$paramArray = array();

			if( array_key_exists( $param, $descs ) ) {
				$paramArray['description'] = $descs[$param];
			}

			if( is_array( $details ) ) {
				if( array_key_exists( APIBASE::PARAM_DFLT, $details ) ) {
					$paramArray['default'] = $details[APIBASE::PARAM_DFLT];
				}
				if( array_key_exists( APIBASE::PARAM_ISMULTI, $details ) ) {
					$paramArray['ismulti'] = $details[ APIBASE::PARAM_ISMULTI];
				}
				if( array_key_exists( APIBASE::PARAM_TYPE, $details ) ) {
					$paramArray['type'] = $details[APIBASE::PARAM_TYPE];
				}
				if( array_key_exists( APIBASE::PARAM_MAX, $details ) ) {
					$paramArray['max'] = $details[APIBASE::PARAM_MAX];
				}
				if( array_key_exists( APIBASE::PARAM_MAX2, $details ) ) {
					$paramArray['max2'] = $details[APIBASE::PARAM_MAX2];
				}
				if( array_key_exists( APIBASE::PARAM_MIN, $details ) ) {
					$paramArray['min'] = $details[APIBASE::PARAM_MIN];
				}
				if( array_key_exists( APIBASE::PARAM_ALLOW_DUPLICATES, $details ) ) {
					$paramArray['allowduplicates'] = $details[APIBASE::PARAM_ALLOW_DUPLICATES];
				}
				if( array_key_exists( APIBASE::PARAM_DEPRECATED, $details ) ) {
					$paramArray['deprecated'] = $details[APIBASE::PARAM_DEPRECATED];
				}
				if( array_key_exists( APIBASE::PARAM_REQUIRED, $details ) ) {
					$paramArray['required'] = $details[APIBASE::PARAM_REQUIRED];
				}
			}

			$ret[$param] = $paramArray;
		}
		return $ret;
	}

	public function shouldCheckMaxlag() {
		return false;
	}

	public function isReadMode() {
		return false;
	}

	public function getAllowedParams() {
		return array(
			'modules' => array(
				ApiBase::PARAM_ISMULTI => true
			),
			'querymodules' => array(
				ApiBase::PARAM_ISMULTI => true,
				ApiBase::PARAM_DEPRECATED => true
			),
		);
	}

	public function getParamDescription() {
		return array(
			'modules' => 'List of module names (value of the action= parameter). ' .
				'Can specify submodules with a \'+\'',
			'querymodules' => 'Use modules=query+value instead. List of query ' .
				'module names (value of prop=, meta= or list= parameter)',
		);
	}

	public function getDescription() {
		return 'Display this help screen. Or the help screen for the specified module';
	}

	public function getExamples() {
		return array(
			'api.php?action=help' => 'Whole help page',
			'api.php?action=help&modules=protect' => 'Module (action) help page',
			'api.php?action=help&modules=query+categorymembers'
				=> 'Help for the query/categorymembers module',
			'api.php?action=help&modules=login|query+info'
				=> 'Help for the login and query/info modules',
		);
	}

	public function getHelpUrls() {
		return array(
			'https://www.mediawiki.org/wiki/API:Main_page',
			'https://www.mediawiki.org/wiki/API:FAQ',
			'https://www.mediawiki.org/wiki/API:Quick_start_guide',
		);
	}
}
