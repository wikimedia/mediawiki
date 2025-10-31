<?php
/**
 * Copyright © 2012 Yuri Astrakhan "<Firstname><Lastname>@gmail.com"
 *
 * @license GPL-2.0-or-later
 * @file
 * @since 1.21
 */

namespace MediaWiki\Api;

use InvalidArgumentException;
use MediaWiki\Context\ContextSource;
use MediaWiki\MediaWikiServices;
use UnexpectedValueException;
use Wikimedia\ObjectFactory\ObjectFactory;

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
	private $mInstances = [];
	/**
	 * @var null[]
	 */
	private $mGroups = [];
	/**
	 * @var array[]
	 */
	private $mModules = [];
	/**
	 * @var ObjectFactory
	 */
	private $objectFactory;

	/**
	 * Construct new module manager
	 *
	 * @param ApiBase $parentModule Parent module instance will be used during instantiation
	 * @param ObjectFactory|null $objectFactory Object factory to use when instantiating modules
	 */
	public function __construct( ApiBase $parentModule, ?ObjectFactory $objectFactory = null ) {
		$this->mParent = $parentModule;
		$this->objectFactory = $objectFactory ?? MediaWikiServices::getInstance()->getObjectFactory();
	}

	/**
	 * Add a list of modules to the manager. Each module is described
	 * by an ObjectFactory spec.
	 *
	 * This simply calls `addModule()` for each module in `$modules`.
	 *
	 * @see ApiModuleManager::addModule()
	 * @param array $modules A map of ModuleName => ModuleSpec
	 * @param string $group Which group modules belong to (action,format,...)
	 */
	public function addModules( array $modules, $group ) {
		foreach ( $modules as $name => $moduleSpec ) {
			$this->addModule( $name, $group, $moduleSpec );
		}
	}

	/**
	 * Add or overwrite a module in this ApiMain instance. Intended for use by extending
	 * classes who wish to add their own modules to their lexicon or override the
	 * behavior of inherent ones.
	 *
	 * ObjectFactory is used to instantiate the module when needed. The parent module
	 * (`$parentModule` from `__construct()`) and the `$name` are passed as extraArgs.
	 *
	 * @since 1.34, accepts an ObjectFactory spec as the third parameter. The old calling convention,
	 *  passing a class name as parameter #3 and an optional factory callable as parameter #4, is
	 *  deprecated.
	 * @param string $name The identifier for this module.
	 * @param string $group Name of the module group
	 * @param string|array $spec The ObjectFactory spec for instantiating the module,
	 *  or a class name to instantiate.
	 * @param callable|null $factory Callback for instantiating the module (deprecated).
	 */
	public function addModule( string $name, string $group, $spec, $factory = null ) {
		if ( is_string( $spec ) ) {
			$spec = [
				'class' => $spec
			];

			if ( is_callable( $factory ) ) {
				wfDeprecated( __METHOD__ . ' with $class and $factory', '1.34' );
				$spec['factory'] = $factory;
			}
		} elseif ( !is_array( $spec ) ) {
			throw new InvalidArgumentException( '$spec must be a string or an array' );
		} elseif ( !isset( $spec['class'] ) ) {
			throw new InvalidArgumentException( '$spec must define a class name' );
		}

		$this->mGroups[$group] = null;
		$this->mModules[$name] = [ $group, $spec ];
	}

	/**
	 * Get module instance by name, or instantiate it if it does not exist
	 *
	 * @param string $moduleName
	 * @param string|null $group Optionally validate that the module is in a specific group
	 * @param bool $ignoreCache If true, force-creates a new instance and does not cache it
	 *
	 * @return ApiBase|null The new module instance, or null if failed
	 */
	public function getModule( $moduleName, $group = null, $ignoreCache = false ) {
		if ( !isset( $this->mModules[$moduleName] ) ) {
			return null;
		}

		[ $moduleGroup, $spec ] = $this->mModules[$moduleName];

		if ( $group !== null && $moduleGroup !== $group ) {
			return null;
		}

		if ( !$ignoreCache && isset( $this->mInstances[$moduleName] ) ) {
			// already exists
			return $this->mInstances[$moduleName];
		} else {
			// new instance
			$instance = $this->instantiateModule( $moduleName, $spec );

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
	 * @param array $spec The ObjectFactory spec for instantiating the module.
	 *
	 * @throws UnexpectedValueException
	 * @return ApiBase
	 */
	private function instantiateModule( $name, $spec ) {
		return $this->objectFactory->createObject(
			$spec,
			[
				'extraArgs' => [
					$this->mParent,
					$name
				],
				'assertClass' => $spec['class']
			]
		);
	}

	/**
	 * Get an array of modules in a specific group or all if no group is set.
	 * @param string|null $group Optional group filter
	 * @return string[] List of module names
	 */
	public function getNames( $group = null ) {
		if ( $group === null ) {
			return array_keys( $this->mModules );
		}
		$result = [];
		foreach ( $this->mModules as $name => $groupAndSpec ) {
			if ( $groupAndSpec[0] === $group ) {
				$result[] = $name;
			}
		}

		return $result;
	}

	/**
	 * Create an array of (moduleName => moduleClass) for a specific group or for all.
	 * @param string|null $group Name of the group to get or null for all
	 * @return array Name=>class map
	 */
	public function getNamesWithClasses( $group = null ) {
		$result = [];
		foreach ( $this->mModules as $name => $groupAndSpec ) {
			if ( $group === null || $groupAndSpec[0] === $group ) {
				$result[$name] = $groupAndSpec[1]['class'];
			}
		}

		return $result;
	}

	/**
	 * Returns the class name of the given module
	 *
	 * @param string $module Module name
	 * @return string|false class name or false if the module does not exist
	 * @since 1.24
	 */
	public function getClassName( $module ) {
		if ( isset( $this->mModules[$module] ) ) {
			return $this->mModules[$module][1]['class'];
		}

		return false;
	}

	/**
	 * Returns true if the specific module is defined at all or in a specific group.
	 * @param string $moduleName
	 * @param string|null $group Group name to check against, or null to check all groups,
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
	 * @return string|null Group name or null if missing
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

/** @deprecated class alias since 1.43 */
class_alias( ApiModuleManager::class, 'ApiModuleManager' );
