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

	/**
	 * @var ApiBase
	 */
	private $mParent;
	/**
	 * @var ApiBase[]
	 */
	private $mInstances = array();
	/**
	 * @var null[]
	 */
	private $mGroups = array();
	/**
	 * @var array[]
	 */
	private $mModules = array();

	/**
	 * Construct new module manager
	 * @param ApiBase $parentModule Parent module instance will be used during instantiation
	 */
	public function __construct( ApiBase $parentModule ) {
		$this->mParent = $parentModule;
	}

	/**
	 * Add a list of modules to the manager. Each module is described
	 * by a module spec.
	 *
	 * Each module spec is an associative array containing at least
	 * the 'class' key for the module's class, and optionally a
	 * 'factory' key for the factory function to use for the module.
	 *
	 * That factory function will be called with two parameters,
	 * the parent module (an instance of ApiBase, usually ApiMain)
	 * and the name the module was registered under. The return
	 * value must be an instance of the class given in the 'class'
	 * field.
	 *
	 * For backward compatibility, the module spec may also be a
	 * simple string containing the module's class name. In that
	 * case, the class' constructor will be called with the parent
	 * module and module name as parameters, as described above.
	 *
	 * Examples for defining module specs:
	 *
	 * @code
	 *  $modules['foo'] = 'ApiFoo';
	 *  $modules['bar'] = array(
	 *      'class' => 'ApiBar',
	 *      'factory' => function( $main, $name ) { ... }
	 *  );
	 *  $modules['xyzzy'] = array(
	 *      'class' => 'ApiXyzzy',
	 *      'factory' => array( 'XyzzyFactory', 'newApiModule' )
	 *  );
	 * @endcode
	 *
	 * @param array $modules A map of ModuleName => ModuleSpec; The ModuleSpec
	 *        is either a string containing the module's class name, or an associative
	 *        array (see above for details).
	 * @param string $group Which group modules belong to (action,format,...)
	 */
	public function addModules( array $modules, $group ) {

		foreach ( $modules as $name => $moduleSpec ) {
			if ( is_array( $moduleSpec ) ) {
				$class = $moduleSpec['class'];
				$factory = ( isset( $moduleSpec['factory'] ) ? $moduleSpec['factory'] : null );
			} else {
				$class = $moduleSpec;
				$factory = null;
			}

			$this->addModule( $name, $group, $class, $factory );
		}
	}

	/**
	 * Add or overwrite a module in this ApiMain instance. Intended for use by extending
	 * classes who wish to add their own modules to their lexicon or override the
	 * behavior of inherent ones.
	 *
	 * @param string $name The identifier for this module.
	 * @param string $group Name of the module group
	 * @param string $class The class where this module is implemented.
	 * @param callable|null $factory Callback for instantiating the module.
	 *
	 * @throws InvalidArgumentException
	 */
	public function addModule( $name, $group, $class, $factory = null ) {
		if ( !is_string( $name ) ) {
			throw new InvalidArgumentException( '$name must be a string' );
		}

		if ( !is_string( $group ) ) {
			throw new InvalidArgumentException( '$group must be a string' );
		}

		if ( !is_string( $class ) ) {
			throw new InvalidArgumentException( '$class must be a string' );
		}

		if ( $factory !== null && !is_callable( $factory ) ) {
			throw new InvalidArgumentException( '$factory must be a callable (or null)' );
		}

		$this->mGroups[$group] = null;
		$this->mModules[$name] = array( $group, $class, $factory );
	}

	/**
	 * Get module instance by name, or instantiate it if it does not exist
	 *
	 * @param string $moduleName Module name
	 * @param string $group Optionally validate that the module is in a specific group
	 * @param bool $ignoreCache If true, force-creates a new instance and does not cache it
	 *
	 * @return ApiBase|null The new module instance, or null if failed
	 */
	public function getModule( $moduleName, $group = null, $ignoreCache = false ) {
		if ( !isset( $this->mModules[$moduleName] ) ) {
			return null;
		}

		list( $moduleGroup, $moduleClass, $moduleFactory ) = $this->mModules[$moduleName];

		if ( $group !== null && $moduleGroup !== $group ) {
			return null;
		}

		if ( !$ignoreCache && isset( $this->mInstances[$moduleName] ) ) {
			// already exists
			return $this->mInstances[$moduleName];
		} else {
			// new instance
			$instance = $this->instantiateModule( $moduleName, $moduleClass, $moduleFactory );

			if ( !$ignoreCache ) {
				// cache this instance in case it is needed later
				$this->mInstances[$moduleName] = $instance;
			}

			return $instance;
		}
	}

	/**
	 * Instantiate the module using the given class or factory function.
	 *
	 * @param string $name The identifier for this module.
	 * @param string $class The class where this module is implemented.
	 * @param callable|null $factory Callback for instantiating the module.
	 *
	 * @throws MWException
	 * @return ApiBase
	 */
	private function instantiateModule( $name, $class, $factory = null ) {
		if ( $factory !== null ) {
			// create instance from factory
			$instance = call_user_func( $factory, $this->mParent, $name );

			if ( !$instance instanceof $class ) {
				throw new MWException( "The factory function for module $name did not return an instance of $class!" );
			}
		} else {
			// create instance from class name
			$instance = new $class( $this->mParent, $name );
		}

		return $instance;
	}

	/**
	 * Get an array of modules in a specific group or all if no group is set.
	 * @param string $group Optional group filter
	 * @return array List of module names
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
	 * @param string $group Name of the group to get or null for all
	 * @return array Name=>class map
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
	 * Returns the class name of the given module
	 *
	 * @param string $module Module name
	 * @return string|bool class name or false if the module does not exist
	 * @since 1.24
	 */
	public function getClassName( $module ) {
		if ( isset( $this->mModules[$module] ) ) {
			return $this->mModules[$module][1];
		}

		return false;
	}

	/**
	 * Returns true if the specific module is defined at all or in a specific group.
	 * @param string $moduleName Module name
	 * @param string $group Group name to check against, or null to check all groups,
	 * @return bool True if defined
	 */
	public function isDefined( $moduleName, $group = null ) {
		if ( isset( $this->mModules[$moduleName] ) ) {
			return $group === null || $this->mModules[$moduleName][0] === $group;
		}

		return false;
	}

	/**
	 * Returns the group name for the given module
	 * @param string $moduleName
	 * @return string Group name or null if missing
	 */
	public function getModuleGroup( $moduleName ) {
		if ( isset( $this->mModules[$moduleName] ) ) {
			return $this->mModules[$moduleName][0];
		}

		return null;
	}

	/**
	 * Get a list of groups this manager contains.
	 * @return array
	 */
	public function getGroups() {
		return array_keys( $this->mGroups );
	}
}
