<?php

/**
 * Copyright 2014
 *
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\Skin;

use InvalidArgumentException;
use Wikimedia\ObjectFactory\ObjectFactory;

/**
 * Factory class to create Skin objects
 *
 * @since 1.24
 */
class SkinFactory {
	private const SKIP_BY_SITECONFIG = 1;
	private const SKIP_BY_REGISTER = 2;

	/**
	 * Map of skin name to object factory spec or factory function.
	 *
	 * @var array<string,array|callable>
	 */
	private $factoryFunctions = [];
	/**
	 * Map of name => fallback human-readable name, used when the 'skinname-<skin>' message is not
	 * available
	 *
	 * @var array
	 */
	private $displayNames = [];
	/**
	 * @var ObjectFactory
	 */
	private $objectFactory;

	/**
	 * Array of skins that should not be presented in the list of
	 * available skins in user preferences, while they're still installed.
	 *
	 * @var array<string,int>
	 */
	private $skipSkins;

	/**
	 * @internal For ServiceWiring only
	 *
	 * @param ObjectFactory $objectFactory
	 * @param string[] $skipSkins
	 */
	public function __construct( ObjectFactory $objectFactory, array $skipSkins ) {
		$this->objectFactory = $objectFactory;
		$this->skipSkins = array_fill_keys( $skipSkins, self::SKIP_BY_SITECONFIG );
	}

	/**
	 * Register a new skin.
	 *
	 * This will replace any previously registered skin by the same name.
	 *
	 * @param string $name Internal skin name. See also Skin::__construct.
	 * @param string $displayName For backwards-compatibility with old skin loading system. This is
	 *   the text used as skin's human-readable name when the 'skinname-<skin>' message is not
	 *   available.
	 * @param array|callable $spec ObjectFactory spec to construct a Skin object,
	 *   or callback that takes a skin name and returns a Skin object.
	 *   See Skin::__construct for the constructor arguments.
	 * @param true|null $skippable Whether the skin is skippable and should be hidden
	 *   from user preferences. By default, this is determined based by $wgSkipSkins.
	 */
	public function register( $name, $displayName, $spec, ?bool $skippable = null ) {
		if ( !is_callable( $spec ) ) {
			if ( is_array( $spec ) ) {
				if ( !isset( $spec['args'] ) ) {
					// make sure name option is set:
					$spec['args'] = [
						[ 'name' => $name ]
					];
				}
			} else {
				throw new InvalidArgumentException( 'Invalid callback provided' );
			}
		}
		$this->factoryFunctions[$name] = $spec;
		$this->displayNames[$name] = $displayName;

		// If skipped by site config, leave as-is.
		if ( ( $this->skipSkins[$name] ?? null ) !== self::SKIP_BY_SITECONFIG ) {
			if ( $skippable === true ) {
				$this->skipSkins[$name] = self::SKIP_BY_REGISTER;
			} else {
				// Make sure the register() call is unaffected by previous calls.
				unset( $this->skipSkins[$name] );
			}
		}
	}

	/**
	 * Create a given Skin using the registered callback for $name.
	 *
	 * @param string $name Name of the skin you want
	 * @throws SkinException If a factory function isn't registered for $name
	 * @return Skin
	 */
	public function makeSkin( $name ) {
		if ( !isset( $this->factoryFunctions[$name] ) ) {
			throw new SkinException( "No registered builder available for $name." );
		}

		return $this->objectFactory->createObject(
			$this->factoryFunctions[$name],
			[
				'allowCallable' => true,
				'assertClass' => Skin::class,
			]
		);
	}

	/**
	 * Get the list of user-selectable skins.
	 *
	 * Useful for Special:Preferences and other places where you
	 * only want to show skins users _can_ select from preferences page,
	 * thus excluding those as configured by $wgSkipSkins.
	 *
	 * @return string[]
	 * @since 1.36
	 */
	public function getAllowedSkins() {
		$skins = $this->getInstalledSkins();

		foreach ( $this->skipSkins as $name => $_ ) {
			unset( $skins[$name] );
		}

		return $skins;
	}

	/**
	 * Get the list of installed skins.
	 *
	 * Returns an associative array of skin name => human readable name
	 *
	 * @return string[]
	 * @since 1.37
	 */
	public function getInstalledSkins() {
		return $this->displayNames;
	}

	/**
	 * Return options provided for a given skin name
	 *
	 * For documentation about keys you can expect to exist,
	 * and their default values, refer to the Skin constructor.
	 *
	 * @since 1.38
	 * @param string $name Name of the skin you want options from
	 * @return array
	 */
	public function getSkinOptions( string $name ): array {
		$skin = $this->makeSkin( $name );
		$options = $skin->getOptions();
		return $options;
	}
}

/** @deprecated class alias since 1.44 */
class_alias( SkinFactory::class, 'SkinFactory' );
