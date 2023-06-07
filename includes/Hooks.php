<?php

/**
 * A tool for running hook functions.
 *
 * Copyright 2004, 2005 Evan Prodromou <evan@wikitravel.org>.
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA
 *
 * @author Evan Prodromou <evan@wikitravel.org>
 * @see hooks.txt
 * @file
 */

use MediaWiki\HookContainer\HookRunner;
use MediaWiki\MediaWikiServices;

/**
 * Hooks class.
 *
 * Legacy wrapper for HookContainer
 * Please use HookContainer instead.
 *
 * @since 1.18
 */
class Hooks {

	/**
	 * Attach an event handler to a given hook in both legacy and non-legacy hook systems
	 *
	 * @see HookContainer::register()
	 *
	 * @param string $name Name of hook
	 * @param mixed $handler Hooks handler to attay
	 * @deprecated since 1.35. use HookContainer::register() instead, emitting warnings since 1.40
	 * @since 1.18
	 */
	public static function register( $name, $handler ) {
		wfDeprecated( __METHOD__, '1.35' );
		$hookContainer = MediaWikiServices::getInstance()->getHookContainer();
		$hookContainer->register( $name, $handler );
	}

	/**
	 * Clears hooks registered via Hooks::register(). Does not touch $wgHooks.
	 * This is intended for use while testing and will fail if MW_PHPUNIT_TEST is not defined.
	 *
	 * @param string $name The name of the hook to clear.
	 *
	 * @since 1.21
	 * @deprecated since 1.35. Instead of using Hooks::register() and Hooks::clear(),
	 * use HookContainer::scopedRegister() instead to register a temporary hook, emitting warnings since 1.35
	 * @codeCoverageIgnore
	 */
	public static function clear( $name ) {
		wfDeprecated( __METHOD__, '1.35' );
		if ( !defined( 'MW_PHPUNIT_TEST' ) && !defined( 'MW_PARSER_TEST' ) ) {
			throw new BadMethodCallException( 'Cannot reset hooks in operation.' );
		}
		$hookContainer = MediaWikiServices::getInstance()->getHookContainer();
		$hookContainer->clear( $name );
	}

	/**
	 * Returns true if a hook has a function registered to it.
	 * The function may have been registered either via Hooks::register or in $wgHooks.
	 *
	 * @since 1.18
	 * @deprecated since 1.35. use HookContainer::isRegistered() instead, emitting warnings since 1.41
	 * @param string $name Name of hook
	 * @return bool True if the hook has a function registered to it
	 */
	public static function isRegistered( $name ) {
		wfDeprecated( __METHOD__, '1.35' );
		$hookContainer = MediaWikiServices::getInstance()->getHookContainer();
		return $hookContainer->isRegistered( $name );
	}

	/**
	 * Returns an array of all the event functions attached to a hook
	 * This combines functions registered via Hooks::register and with $wgHooks.
	 *
	 * @since 1.18
	 * @deprecated since 1.35, emitting warnings since 1.41
	 * @param string $name Name of the hook
	 * @return array
	 */
	public static function getHandlers( $name ) {
		wfDeprecated( __METHOD__, '1.35' );
		$hookContainer = MediaWikiServices::getInstance()->getHookContainer();
		return $hookContainer->getHandlerCallbacks( $name );
	}

	/**
	 * Call hook functions defined in Hooks::register and $wgHooks.
	 *
	 * For the given hook event, fetch the array of hook events and
	 * process them. Determine the proper callback for each hook and
	 * then call the actual hook using the appropriate arguments.
	 * Finally, process the return value and return/throw accordingly.
	 *
	 * For hook event that are not abortable through a handler's return value,
	 * use runWithoutAbort() instead.
	 *
	 * @param string $event Event name
	 * @param array $args Array of parameters passed to hook functions
	 * @param string|null $deprecatedVersion [optional] Mark hook as deprecated with version number
	 * @return bool True if no handler aborted the hook
	 *
	 * @throws Exception
	 * @since 1.22 A hook function is not required to return a value for
	 *   processing to continue. Not returning a value (or explicitly
	 *   returning null) is equivalent to returning true.
	 * @deprecated since 1.35 Use HookContainer::run() instead, emitting warnings since 1.41
	 */
	public static function run( $event, array $args = [], $deprecatedVersion = null ) {
		wfDeprecated( __METHOD__, '1.35' );
		$hookContainer = MediaWikiServices::getInstance()->getHookContainer();
		$options = $deprecatedVersion ? [ 'deprecatedVersion' => $deprecatedVersion ] : [];
		return $hookContainer->run( $event, $args, $options );
	}

	/**
	 * Call hook functions defined in Hooks::register and $wgHooks.
	 *
	 * @param string $event Event name
	 * @param array $args Array of parameters passed to hook functions
	 * @param string|null $deprecatedVersion [optional] Mark hook as deprecated with version number
	 * @return bool Always true
	 * @throws UnexpectedValueException callback returns an invalid value
	 * @since 1.30
	 * @deprecated since 1.35 Use HookContainer::run() with 'abortable' option instead, emitting warnings since 1.41
	 */
	public static function runWithoutAbort( $event, array $args = [], $deprecatedVersion = null ) {
		wfDeprecated( __METHOD__, '1.35' );
		$hookContainer = MediaWikiServices::getInstance()->getHookContainer();
		$options = $deprecatedVersion ? [ 'deprecatedVersion' => $deprecatedVersion ] : [];
		$options[ 'abortable' ] = false;
		return $hookContainer->run( $event, $args, $options );
	}

	/**
	 * Get a HookRunner instance for calling hooks using the new interfaces.
	 *
	 * Classes using dependency injection should instead receive a HookContainer
	 * and construct a private HookRunner from it.
	 *
	 * Classes without dependency injection may alternatively use
	 * ProtectedHookAccessorTrait, a trait which provides getHookRunner() as a
	 * protected method.
	 *
	 * @since 1.35
	 * @internal because HookRunner is intended for core only, see documentation on that class
	 * @deprecated since 1.41, emitting warnings since 1.41
	 *
	 * @return HookRunner
	 */
	public static function runner() {
		wfDeprecated( __METHOD__, '1.41' );
		return new HookRunner( MediaWikiServices::getInstance()->getHookContainer() );
	}
}
