<?php
/**
 * (C) 2019 Kunal Mehta <legoktm@member.fsf.org>
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

require_once __DIR__ . '/Maintenance.php';

/**
 * Checks dependencies for extensions, mostly without loading them
 *
 * @since 1.34
 */
class CheckDependencies extends Maintenance {

	private $checkDev;

	public function __construct() {
		parent::__construct();
		$this->addDescription( 'Check dependencies for extensions' );
		$this->addOption( 'extensions', 'Comma separated list of extensions to check', false, true );
		$this->addOption( 'skins', 'Comma separated list of skins to check', false, true );
		$this->addOption( 'json', 'Output in JSON' );
		$this->addOption( 'dev', 'Check development dependencies too' );
	}

	public function execute() {
		$this->checkDev = $this->hasOption( 'dev' );
		$extensions = $this->hasOption( 'extensions' )
			? explode( ',', $this->getOption( 'extensions' ) )
			: [];
		$skins = $this->hasOption( 'skins' )
			? explode( ',', $this->getOption( 'skins' ) )
			: [];

		$dependencies = [];
		// Note that we can only use the main registry if we are
		// not checking development dependencies.
		$registry = ExtensionRegistry::getInstance();
		foreach ( $extensions as $extension ) {
			if ( !$this->checkDev && $registry->isLoaded( $extension ) ) {
				// If it's already loaded, we know all the dependencies resolved.
				$this->addToDependencies( $dependencies, [ $extension ], [] );
				continue;
			}
			$this->loadThing( $dependencies, $extension, [ $extension ], [] );
		}

		foreach ( $skins as $skin ) {
			if ( !$this->checkDev && $registry->isLoaded( $skin ) ) {
				// If it's already loaded, we know all the dependencies resolved.
				$this->addToDependencies( $dependencies, [], [ $skin ] );
				continue;
			}
			$this->loadThing( $dependencies, $skin, [], [ $skin ] );
		}

		if ( $this->hasOption( 'json' ) ) {
			$this->output( json_encode( $dependencies ) . "\n" );
		} else {
			$this->output( $this->formatForHumans( $dependencies ) . "\n" );
		}
	}

	private function loadThing( &$dependencies, $name, $extensions, $skins ) {
		$extDir = $this->getConfig()->get( 'ExtensionDirectory' );
		$styleDir = $this->getConfig()->get( 'StyleDirectory' );
		$queue = [];
		$missing = false;
		foreach ( $extensions as $extension ) {
			$path = "$extDir/$extension/extension.json";
			if ( file_exists( $path ) ) {
				// 1 is ignored
				$queue[$path] = 1;
				$this->addToDependencies( $dependencies, [ $extension ], [], $name );
			} else {
				$missing = true;
				$this->addToDependencies( $dependencies, [ $extension ], [], $name, 'missing' );
			}
		}

		foreach ( $skins as $skin ) {
			$path = "$styleDir/$skin/skin.json";
			if ( file_exists( $path ) ) {
				$queue[$path] = 1;
				$this->addToDependencies( $dependencies, [], [ $skin ], $name );
			} else {
				$missing = true;
				$this->addToDependencies( $dependencies, [], [ $skin ], $name, 'missing' );
			}
		}

		if ( $missing ) {
			// Stuff is missing, give up
			return;
		}

		$registry = new ExtensionRegistry();
		$registry->setCheckDevRequires( $this->checkDev );
		try {
			$registry->readFromQueue( $queue );
		} catch ( ExtensionDependencyError $e ) {
			$reason = false;
			if ( $e->incompatibleCore ) {
				$reason = 'incompatible-core';
			} elseif ( $e->incompatibleSkins ) {
				$reason = 'incompatible-skins';
			} elseif ( $e->incompatibleExtensions ) {
				$reason = 'incompatible-extensions';
			} elseif ( $e->missingExtensions || $e->missingSkins ) {
				// There's an extension missing in the dependency tree,
				// so add those to the dependency list and try again
				return $this->loadThing(
					$dependencies,
					$name,
					array_merge( $extensions, $e->missingExtensions ),
					array_merge( $skins, $e->missingSkins )
				);
			} else {
				// missing-phpExtension
				// missing-ability
				// XXX: ???
				throw $e;
			}

			$this->addToDependencies( $dependencies, $extensions, $skins, $name, $reason, $e->getMessage() );
		}

		$this->addToDependencies( $dependencies, $extensions, $skins, $name );
	}

	private function addToDependencies( &$dependencies, $extensions, $skins,
		$why = null, $status = null, $message = null
	) {
		$mainRegistry = ExtensionRegistry::getInstance();
		$iter = [ 'extensions' => $extensions, 'skins' => $skins ];
		foreach ( $iter as $type => $things ) {
			foreach ( $things as $thing ) {
				$preStatus = $dependencies[$type][$thing]['status'] ?? false;
				if ( $preStatus !== 'loaded' ) {
					// OK to overwrite
					if ( $status ) {
						$tStatus = $status;
					} else {
						$tStatus = $mainRegistry->isLoaded( $thing ) ? 'loaded' : 'present';
					}
					$dependencies[$type][$thing]['status'] = $tStatus;
				}
				if ( $why !== null ) {
					$dependencies[$type][$thing]['why'][] = $why;
					// XXX: this is a bit messy
					$dependencies[$type][$thing]['why'] = array_unique(
						$dependencies[$type][$thing]['why'] );
				}

				if ( $message !== null ) {
					$dependencies[$type][$thing]['message'] = trim( $message );
				}

			}
		}
	}

	private function formatForHumans( $dependencies ) {
		$text = '';
		foreach ( $dependencies as $type => $things ) {
			$text .= ucfirst( $type ) . "\n" . str_repeat( '=', strlen( $type ) ) . "\n";
			foreach ( $things as $thing => $info ) {
				$why = $info['why'] ?? [];
				if ( $why ) {
					$whyText = '(because: ' . implode( ',', $why ) . ') ';
				} else {
					$whyText = '';
				}
				$msg = isset( $info['message'] ) ? ", {$info['message']}" : '';

				$text .= "$thing: {$info['status']}{$msg} $whyText\n";
			}
			$text .= "\n";
		}

		return trim( $text );
	}
}

$maintClass = CheckDependencies::class;
require_once RUN_MAINTENANCE_IF_MAIN;
