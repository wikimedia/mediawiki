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

/**
 * An API module that exposes a swagger.io spec with
 * the x-amples extension for testing individual modules.
 * It is intended to be used along with service-checker to
 * ensure the MediaWiki API is serving requests. Individual
 * modules should override ApiBase::getTestXAmples() to add
 * additional requests for testing. By default, each module
 * is called with no parameters and checked that it returns
 * a 200 status code.
 *
 * @since 1.28
 */
class ApiXAmples extends ApiBase {
	public function __construct( ApiMain $mainModule ) {
		parent::__construct( $mainModule, 'x-amples' );
	}

	public function execute() {
		$result = $this->getResult();
		$config = $this->getConfig();

		// Figure out the supported mime-types...
		$main = $this->getMain();
		$moduleMgr = $main->getModuleManager();
		$formats = $moduleMgr->getNames( 'format' );
		$mimeTypes = [];
		foreach ( $formats as $format ) {
			$mimeTypes[] = $main->createPrinterByName( $format )->getMimeType();
		}

		$parsedServer = wfParseUrl( $config->get( 'Server' ) );
		$host = $parsedServer['host'];
		if ( isset( $parsedServer['port'] ) ) {
			$host .= ":{$parsedServer['port']}";
		}

		if ( $parsedServer['scheme'] ) {
			$schemes = [ $parsedServer['scheme'] ];
		} else {
			// Proto-relative URL:
			$schemes = [ 'http', 'https' ];
		}

		$root = [
			'swagger' => '2.0',
			'host' => $host,
			'basePath' => $config->get( 'ScriptPath' ),
			'schemes' => $schemes,
			'consumes' => [ 'application/x-www-form-urlencoded', 'multipart/form-data' ],
			// Get unique mimetypes, and then reset keys
			'produces' => array_values( array_unique( $mimeTypes ) ),
			'info' => [
				'title' => 'MediaWiki',
				'description' => 'wiki engine',
				'version' => $config->get( 'Version' ),
				// Extensions can be under different licenses, but
				// this is the general license for MediaWiki
				'license' => [
					'name' => 'GNU General Public License 2.0+',
					'url' => 'https://www.mediawiki.org/w/COPYING'
				]
			],
			'x-default-query' => [
				'format' => 'json',
				'formatversion' => 2
			]
		];

		foreach ( $root as $name => $value ) {
			$result->addValue( null, $name, $value );
		}

		$help = wfAppendQuery( wfScript( 'api' ), [ 'action' => 'help' ] );

		$reqs = [
			'get' => [
				'responses' => [
					200 => [
						'description' => "See the [API documentation]($help) for more details",
						'schema' => [
							'type' => [ 'object', 'array', 'string' ],
						],
					],
				],
				'x-monitor' => true,
				'x-amples' => $this->getXAmples( $moduleMgr, [ 'action' ] ),
			],
			// 'post' => [],
		];

		$result->addValue( 'paths', '/api.php', $reqs );
	}

	private function getXAmples( ApiModuleManager $moduleMgr, array $groups ) {
		$xamples = [];
		foreach ( $groups as $group ) {
			foreach ( $moduleMgr->getNames( $group ) as $moduleName ) {
				$module = $moduleMgr->getModule( $moduleName, $group );
				if ( $module->mustBePosted() || $module->isWriteMode() ) {
					// TODO: Support these
					continue;
				}

				foreach ( $module->getTestXAmples( $group )  as $xample ) {
					// Set response.status = 200 if not already set
					$xample['response'] += [ 'status' => 200 ];
					$xamples[] = $xample;
				}

				$subModuleMgr = $module->getModuleManager();
				if ( $subModuleMgr ) {
					// If this module has submodules, get the xamples for
					// each individual submodule...
					$xamples = array_merge( $xamples, $this->getXAmples(
						$subModuleMgr,
						$subModuleMgr->getGroups()
					) );
				}
			}
		}

		return $xamples;
	}

	public function isInternal() {
		return true;
	}
}
