<?php

/**
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
 * @ingroup Testing
 */

/**
 * A class to delay execution of a parser test hooks.
 */
class DelayedParserTest {

	/** Initialized on construction */
	private $hooks;
	private $fnHooks;
	private $transparentHooks;

	public function __construct() {
		$this->reset();
	}

	/**
	 * Init/reset or forgot about the current delayed test.
	 * Call to this will erase any hooks function that were pending.
	 */
	public function reset() {
		$this->hooks = [];
		$this->fnHooks = [];
		$this->transparentHooks = [];
	}

	/**
	 * Called whenever we actually want to run the hook.
	 * Should be the case if we found the parserTest is not disabled
	 * @param ParserTestRunner|ParserIntegrationTest $parserTest
	 * @return bool
	 * @throws MWException
	 */
	public function unleash( &$parserTest ) {
		if ( !( $parserTest instanceof ParserTestRunner
			|| $parserTest instanceof ParserIntegrationTest )
		) {
			throw new MWException( __METHOD__ . " must be passed an instance of " .
				"ParserTestRunner or ParserIntegrationTest classes\n" );
		}

		# Trigger delayed hooks. Any failure will make us abort
		foreach ( $this->hooks as $hook ) {
			$ret = $parserTest->requireHook( $hook );
			if ( !$ret ) {
				return false;
			}
		}

		# Trigger delayed function hooks. Any failure will make us abort
		foreach ( $this->fnHooks as $fnHook ) {
			$ret = $parserTest->requireFunctionHook( $fnHook );
			if ( !$ret ) {
				return false;
			}
		}

		# Trigger delayed transparent hooks. Any failure will make us abort
		foreach ( $this->transparentHooks as $hook ) {
			$ret = $parserTest->requireTransparentHook( $hook );
			if ( !$ret ) {
				return false;
			}
		}

		# Delayed execution was successful.
		return true;
	}

	/**
	 * Similar to ParserTestRunner object but does not run anything
	 * Use unleash() to really execute the hook
	 * @param string $hook
	 */
	public function requireHook( $hook ) {
		$this->hooks[] = $hook;
	}

	/**
	 * Similar to ParserTestRunner object but does not run anything
	 * Use unleash() to really execute the hook function
	 * @param string $fnHook
	 */
	public function requireFunctionHook( $fnHook ) {
		$this->fnHooks[] = $fnHook;
	}

	/**
	 * Similar to ParserTestRunner object but does not run anything
	 * Use unleash() to really execute the hook function
	 * @param string $hook
	 */
	public function requireTransparentHook( $hook ) {
		$this->transparentHooks[] = $hook;
	}

}

