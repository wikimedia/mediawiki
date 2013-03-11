<?php
/**
 *
 *
 * Created on Dec 27, 2012
 *
 * Copyright © 2012 Yuri Astrakhan "<Firstname><Lastname>@gmail.com"
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
 * @since 1.21
 */

/**
 * This class holds a list of modules and handles instantiation
 *
 * @since 1.21
 * @ingroup API
 */
class ApiModuleManager extends ContextSource {

	private $mParent;
	private $mInstances = array();
	private $mGroups = array();
	private $mModules = array();

	/**
	 * Construct new module manager
	 * @param ApiBase $parentModule Parent module instance will be used during instantiation
	 */
	public function __construct( ApiBase $parentModule ) {
		$this->mParent = $parentModule;
	}

	/**
	 * Add a list of modules to the manager
	 * @param array $modules A map of ModuleName => ModuleClass
	 * @param string $group Which group modules belong to (action,format,...)
	 */
	public function addModules( array $modules, $group ) {
		foreach ( $modules as $name => $class ) {
			$this->addModule( $name, $group, $class );
		}
	}

	/**
	 * Add or overwrite a module in this ApiMain instance. Intended for use by extending
	 * classes who wish to add their own modules to their lexicon or override the
	 * behavior of inherent ones.
	 *
	 * @param string $group Name of the module group
	 * @param string $name The identifier for this module.
	 * @param string $class The class where this module is implemented.
	 */
	public function addModule( $name, $group, $class ) {
		$this->mGroups[$group] = null;
		$this->mModules[$name] = array( $group, $class );
	}

	/**
	 * Get module instance by name, or instantiate it if it does not exist
	 * @param string $moduleName module name
	 * @param string $group optionally validate that the module is in a specific group
	 * @param bool $ignoreCache if true, force-creates a new instance and does not cache it
	 * @return mixed the new module instance, or null if failed
	 */
	public function getModule( $moduleName, $group = null, $ignoreCache = false ) {
		if ( !isset( $this->mModules[$moduleName] ) ) {
			return null;
		}
		$grpCls = $this->mModules[$moduleName];
		if ( $group !== null && $grpCls[0] !== $group ) {
			return null;
		}
		if ( !$ignoreCache && isset( $this->mInstances[$moduleName] ) ) {
			// already exists
			return $this->mInstances[$moduleName];
		} else {
			// new instance
			$class = $grpCls[1];
			$instance = new $class ( $this->mParent, $moduleName );
			if ( !$ignoreCache ) {
				// cache this instance in case it is needed later
				$this->mInstances[$moduleName] = $instance;
			}
			return $instance;
		}
	}

	/**
	 * Get an array of modules in a specific group or all if no group is set.
	 * @param string $group optional group filter
	 * @return array list of module names
	 */
	public function getNames( $group = null ) {
		if ( $group === null ) {
			return array_keys( $this->mModules );
		}
		$result = array();
		foreach ( $this->mModules as $name => $grpCls ) {
			if ( $grpCls[0] === $group ) {
				$result[] = $name;
			}
		}
		return $result;
	}

	/**
	 * Create an array of (moduleName => moduleClass) for a specific group or for all.
	 * @param string $group name of the group to get or null for all
	 * @return array name=>class map
	 */
	public function getNamesWithClasses( $group = null ) {
		$result = array();
		foreach ( $this->mModules as $name => $grpCls ) {
			if ( $group === null || $grpCls[0] === $group ) {
				$result[$name] = $grpCls[1];
			}
		}
		return $result;
	}

	/**
	 * Returns true if the specific module is defined at all or in a specific group.
	 * @param string $moduleName module name
	 * @param string $group group name to check against, or null to check all groups,
	 * @return boolean true if defined
	 */
	public function isDefined( $moduleName, $group = null ) {
		if ( isset( $this->mModules[$moduleName] ) ) {
			return $group === null || $this->mModules[$moduleName][0] === $group;
		} else {
			return false;
		}
	}

	/**
	 * Returns the group name for the given module
	 * @param string $moduleName
	 * @return string group name or null if missing
	 */
	public function getModuleGroup( $moduleName ) {
		if ( isset( $this->mModules[$moduleName] ) ) {
			return $this->mModules[$moduleName][0];
		} else {
			return null;
		}
	}

	/**
	 * Get a list of groups this manager contains.
	 * @return array
	 */
	public function getGroups() {
		return array_keys( $this->mGroups );
	}
}
