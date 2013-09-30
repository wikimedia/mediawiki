<?php
/**
 * Checks LESS files in known resources for errors
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
 * @ingroup Maintenance
 */

require_once __DIR__ . '/Maintenance.php';

/**
 * @ingroup Maintenance
 */
class CheckLess extends Maintenance {
	public function __construct() {
		parent::__construct();
		$this->mDescription = 'Checks LESS files for errors';
	}

	public function execute() {
		$result = false;
		$resourceLoader = new ResourceLoader();
		foreach ( $resourceLoader->getModuleNames() as $name ) {
			/** @var ResourceLoaderFileModule $module */
			$module = $resourceLoader->getModule( $name );
			if ( !$module || !$module instanceof ResourceLoaderFileModule ) {
				continue;
			}

			$hadErrors = false;
			foreach ( $module->getAllStyleFiles() as $file ) {
				if ( $module->getStyleSheetLang( $file ) !== 'less' ) {
					continue;
				}
				try {
					$compiler = ResourceLoader::getLessCompiler();
					$compiler->compileFile( $file );
				} catch ( Exception $e ) {
					if ( !$hadErrors ) {
						$this->error( "Errors checking module $name:\n" );
						$hadErrors = true;
					}
					$this->error( $e->getMessage() . "\n" );
					$result = true;
				}
			}
		}
		if ( !$result ) {
			$this->output( "No errors found\n" );
		} else {
			die( 1 );
		}
	}
}

$maintClass = 'CheckLess';
require_once RUN_MAINTENANCE_IF_MAIN;
