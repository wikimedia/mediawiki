<?php
/**
 * API for MediaWiki 1.8+
 *
 * Created on Sep 6, 2006
 *
 * Copyright © 2006 Yuri Astrakhan <Firstname><Lastname>@gmail.com
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

if ( !defined( 'MEDIAWIKI' ) ) {
	// Eclipse helper - will be ignored in production
	require_once( 'ApiBase.php' );
}

/**
 * This is a simple class to handle action=help
 *
 * @ingroup API
 */
class ApiHelp extends ApiBase {

	public function __construct( $main, $action ) {
		parent::__construct( $main, $action );
	}

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
		$queryObj = new ApiQuery( $this->getMain(), 'query' );
		$r = array();
		if ( is_array( $params['modules'] ) ) {
			$modArr = $this->getMain()->getModules();

			foreach ( $params['modules'] as $m ) {
				if ( !isset( $modArr[$m] ) ) {
					$r[] = array( 'name' => $m, 'missing' => '' );
					continue;
				}
				$module = new $modArr[$m]( $this->getMain(), $m );

				$r[] = $this->buildModuleHelp( $module, 'action' );
			}
		}

		if ( is_array( $params['querymodules'] ) ) {
			$qmodArr = $queryObj->getModules();

			foreach ( $params['querymodules'] as $qm ) {
				if ( !isset( $qmodArr[$qm] ) ) {
					$r[] = array( 'name' => $qm, 'missing' => '' );
					continue;
				}
				$module = new $qmodArr[$qm]( $this, $qm );
				$type = $queryObj->getModuleType( $qm );

				if ( $type === null ) {
					$r[] = array( 'name' => $qm, 'missing' => '' );
					continue;
				}

				$r[] = $this->buildModuleHelp( $module, $type );
			}
		}
		$result->setIndexedTagName( $r, 'module' );
		$result->addValue( null, $this->getModuleName(), $r );
	}

	private function buildModuleHelp( $module, $type ) {
		$msg = ApiMain::makeHelpMsgHeader( $module, $type );

		$msg2 = $module->makeHelpMsg();
		if ( $msg2 !== false ) {
			$msg .= $msg2;
		}

		return $msg;
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
				ApiBase::PARAM_ISMULTI => true
			),
		);
	}

	public function getParamDescription() {
		return array(
			'modules' => 'List of module names (value of the action= parameter)',
			'querymodules' => 'List of query module names (value of prop=, meta= or list= parameter)',
		);
	}

	public function getDescription() {
		return 'Display this help screen. Or the help screen for the specified module';
	}

	protected function getExamples() {
		return array(
			'Whole help page:',
			'  api.php?action=help',
			'Module help page:',
			'  api.php?action=help&modules=protect',
			'Query modules help page:',
			'  api.php?action=help&querymodules=categorymembers',
		);
	}

	public function getVersion() {
		return __CLASS__ . ': $Id$';
	}
}
