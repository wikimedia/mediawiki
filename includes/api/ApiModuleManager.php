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
 */

/**
 * This class holds a list of modules and handles versioning and instantiation
 *
 * @ingroup API
 */
class ApiModuleManager extends ContextSource {

	private $mParent, $mGroups, $mModules, $mTopVersions;

	/**
	 * Construct new module manager
	 * @param ApiBase $parentModule Parent module instance will be used during instantiation
	 */
	public function __construct( $parentModule ) {
		$this->mParent = $parentModule;
	}

	/**
	 * Add a list of modules to the manager
	 * @param array $modules A map of ModuleName => ModuleClass
	 * @param string $group Which group modules belong to (action,format,...)
	 */
	public function addModules( $modules, $group ) {
		foreach ( $modules as $name => $class )
			$this->addModule( $name, $group, $class );
	}

	/**
	 * Add or overwrite a module in this ApiMain instance. Intended for use by extending
	 * classes who wish to add their own modules to their lexicon or override the
	 * behavior of inherent ones.
	 * There are two types - versioned (name1, name3) and unversioned (name)
	 * Module list is formed by first adding built-in modules, followed by any extensions.
	 *
	 * @param $group string Name of the module group
	 * @param $name string The identifier for this module.
	 * @param $class string The class where this module is implemented.
	 */
	public function addModule( $name, $group, $class ) {
		$parsed = self::parseVersionedValue( $name );
		if ( is_null( $parsed ) ) {
			ApiBase::dieDebug( __METHOD__, 'Invalid module name "' . $name
			. '". Must be in format <string><optionalPositiveNumber>' );
		}

		$this->mGroups[$group] = null;
		$this->mModules[$name] = array( $group, $class );

		if ( !isset( $this->mTopVersions[ $parsed[0] ] )
				|| $this->mTopVersions[ $parsed[0] ] < $parsed[1] ) {
			$this->mTopVersions[ $parsed[0] ] = $parsed[1];
		}
	}

	/**
	 * Instantiate specific module
	 * @param unknown $moduleName module name
	 * @param string $group optionally validate that the module is in a specific group
	 * @return mixed the new module instance, or null if failed
	 */
	public function instantiateModule( $moduleName, $group = null ) {
		$nameVer = self::parseVersionedValue( $moduleName );
		if ( is_null($nameVer) || !isset($this->mModules[ $moduleName ]) ) {
			return null;
		}

		$grpCls = $this->mModules[ $moduleName ];
		if ( !is_null( $group ) && $grpCls[0] !== $group ) {
			return null;
		}

		$class = $grpCls[1];
		$instance = new $class ( $this->mParent, $nameVer[0], $nameVer[1] );
		$instance->setModuleVersionInternal( $nameVer[1] );
		return $instance;
	}

	/**
	 * Get an array of modules in a specific group or all if no group is set.
	 * @param string $group optional group filter
	 * @return array list of module names
	 */
	public function getNames( $group = null ) {
		if ( is_null( $group ) ) {
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
	 * Get an array of (moduleName => moduleClass) for a specific group or for all.
	 * Must be used for compatibility only
	 * @deprecated
	 * @param string $group name of the group to get or null for all
	 * @return array name=>class map
	 */
	public function getNamesWithClasses( $group = null ) {
		$result = array();
		foreach ( $this->mModules as $name => $grpCls ) {
			if ( is_null( $group ) || $grpCls[0] === $group ) {
				$result[$name] = $grpCls[1];
			}
		}
		return $result;
	}

	/**
	 * Get the top version number for the given module. Will fail on incorrect name.
	 * @param string $moduleName module name
	 * @return int version number
	 */
	public function getTopVersion( $moduleName ) {
		return $this->mTopVersions[ $moduleName ];
	}

	/**
	 * Returns true if the specific module is defined at all or in a specific group.
	 * @param string $moduleName module name
	 * @param string $group group name to check against, or null to check all groups,
	 * @return boolean true if defined
	 */
	public function isDefined( $moduleName, $group = null ) {
		if ( isset( $this->mModules[$moduleName] ) ) {
			return is_null( $group ) || $this->mModules[$moduleName][0] === $group;
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
	 * @return multitype:
	 */
	public function getGroups() {
		return array_keys( $this->mGroups );
	}

	/**
	 * Validates module name against "[a-z]+[0-9]{0,5}" pattern.
	 * Returns two element array( non-versioned-name, integer-version ),
	 * or null if the validation fails.
	 * Missing version is treated as 0, but version 0 is not allowed.
	 * @param string $name versioned module name
	 * @return array array(name, version) or null on error
	 */
	private static function parseVersionedValue( $name ) {

		if ( !is_string( $name ) ||
				1 !== preg_match( "/^([a-zA-Z]+)(\\d+){0,5}$/", $name, $matches ) ) {
			return null;
		}
		if ( count( $matches ) === 3 ) {
			$ver = intval( $matches[2] );
			if ( $ver === 0 ) {
				return null;
			}
			return array( $matches[1], $ver );
		}
		return array( $matches[1], 0 );
	}
}