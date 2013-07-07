<?php
/**
 * Class for optimizing the Resource Loader module registrations.
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
 * @author Marius Hoch < hoo@online.de >
 */
class ResourceLoaderRegistrations {
	/**
	 * @var array
	 */
	protected $modules;

	/**
	 * @param array $registrations
	 */
	public function __construct( $registrations ) {
		$this->modules = $registrations;
	}

	/**
	 * Optimize the dependency tree in $this->modules and return it.
	 *
	 * The optimization basically works like this:
	 *	Given we have module A with the dependencies B and C
	 *		and module B with the dependency C.
	 *	Now we don't have to tell the client to explicitly fetch module
	 *		C as that's already included in module B.
	 *
	 * This way we can reasonably reduce the amout of module registration
	 * data send to the client.
	 *
	 * @return array
	 */
	public function optimizeDependencies() {
		foreach ( $this->modules as &$module ) {
			if ( !isset( $module[2] ) || !is_array( $module[2] ) ) {
				// The given module doesn't have any dependencies assigned
				continue;
			}
			$dependencies = $module[2];
			foreach ( $module[2] as $dependency ) {
				$implicitDependencies = $this->getImplicitDependencies( $dependency );
				$dependencies = array_diff( $dependencies, $implicitDependencies );
			}
			// Rebuild the keys
			$module[2] = array_values( $dependencies );
		}

		return $this->modules;
	}

	/**
	 * Recursively get all explicit and implicit dependencies for to the given module.
	 *
	 * @param string $moduleName
	 * @retun array
	 */
	protected function getImplicitDependencies( $moduleName ) {
		static $dependencies = array();
		// The list of implicit dependencies wont be altered, so we can
		// cache them without having to worry.
		if ( isset( $dependencies[$moduleName] ) ) {
			return $dependencies[$moduleName];
		}

		$dependencies[$moduleName] = array();
		foreach ( $this->modules as $module ) {
			if ( $module[0] !== $moduleName ) {
				continue;
			}
			if ( !isset( $module[2] ) || !is_array( $module[2] ) ) {
				// The given module doesn't have any dependencies assigned
				continue;
			}

			$dependencies[$moduleName] = array_merge( $dependencies[$moduleName], $module[2] );
			foreach ( $module[2] as $dependency ) {
				// Recursively get the dependencies of the dependencies
				$dependencies[$moduleName] = array_merge(
					$dependencies[$moduleName],
					$this->getImplicitDependencies( $dependency )
				);
			}

			break;
		}

		return $dependencies[$moduleName];
	}
}
