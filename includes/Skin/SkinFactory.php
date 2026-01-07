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

	/**
	 * Map of skin name to object factory spec or factory function.
	 *
	 * @var array<string,array|callable>
	 */
	private array $factoryFunctions = [];
	/**
	 * Map of name => fallback human-readable name, used when the 'skinname-<skin>' message is not
	 * available
	 *
	 * @var array<string,string>
	 */
	private array $displayNames = [];

	/** @var array<string,true> */
	private array $markedAsSkippable = [];

	/**
	 * @internal For ServiceWiring only
	 *
	 * @param ObjectFactory $objectFactory
	 * @param string[] $skipSkins Skins that should not be presented in the list of available skins
	 *  in user preferences, while they're still installed.
	 */
	public function __construct(
		private readonly ObjectFactory $objectFactory,
		private readonly array $skipSkins
	) {
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
	 * @param bool $skippable Whether the skin is marked as `"skippable": true` and should be hidden
	 *   from user preferences. By default, this is determined based by $wgSkipSkins.
	 */
	public function register( string $name, string $displayName, $spec, bool $skippable = false ): void {
		if ( !is_callable( $spec ) ) {
			if ( !is_array( $spec ) ) {
				throw new InvalidArgumentException( 'Invalid callback provided' );
			}
			// make sure name option is set:
			$spec['args'] ??= [ [ 'name' => $name ] ];
		}
		$this->factoryFunctions[$name] = $spec;
		$this->displayNames[$name] = $displayName;

		if ( $skippable ) {
			$this->markedAsSkippable[$name] = true;
		} else {
			// Make sure the register() call is unaffected by previous calls.
			unset( $this->markedAsSkippable[$name] );
		}
	}

	/**
	 * Create a given Skin using the registered callback for $name.
	 *
	 * @param string $name Name of the skin you want
	 * @throws SkinException If a factory function isn't registered for $name
	 */
	public function makeSkin( string $name ): Skin {
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
	 * @return array<string,string> Map of internal skin name to human-readable label
	 * @since 1.36
	 */
	public function getAllowedSkins(): array {
		return array_diff_key( $this->getInstalledSkins(),
			array_flip( $this->skipSkins ),
			$this->markedAsSkippable
		);
	}

	/**
	 * @return array<string,string> Map of internal skin name to human-readable label
	 * @since 1.37
	 */
	public function getInstalledSkins(): array {
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
		return $this->makeSkin( $name )->getOptions();
	}
}

/** @deprecated class alias since 1.44 */
class_alias( SkinFactory::class, 'SkinFactory' );
