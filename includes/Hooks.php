<?php
/**
 * Hooks.php -- a tool for running hook functions
 * Copyright 2004, Evan Prodromou <evan@wikitravel.org>.
 *
 *  This program is free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 2 of the License, or
 *  (at your option) any later version.
 *
 *  This program is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  You should have received a copy of the GNU General Public License
 *  along with this program; if not, write to the Free Software
 *  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
 *
 * @author <evan@wikitravel.org>
 * @package MediaWiki
 * @seealso hooks.doc
 */

if (defined('MEDIAWIKI')) {
	
	/* 
	 * Because programmers assign to $wgHooks, we need to be very
	 * careful about its contents. So, there's a lot more error-checking
	 * in here than would normally be necessary.
	 */
	
	function wfRunHooks() {
		
		global $wgHooks;

		if (!is_array($wgHooks)) {
			wfDieDebugBacktrace("Global hooks array is not an array!\n");
			return false;
		}

		$args = func_get_args();

		if (count($args) < 1) {
			wfDieDebugBacktrace("No event name given for wfRunHooks().\n");
			return false;
		}

		$event = array_shift($args);

		if (!array_key_exists($event, $wgHooks)) {
			return true;
		}

		if (!is_array($wgHooks[$event])) {
			wfDieDebugBacktrace("Hooks array for event '$event' is not an array!\n");
			return false;
		}

		foreach ($wgHooks[$event] as $hook) {
			
			$object = NULL;
			$method = NULL;
			$func = NULL;
			$data = NULL;
			$have_data = false;

			/* $hook can be: a function, an object, an array of $function and $data,
			 * an array of just a function, an array of object and method, or an
			 * array of object, method, and data.
			 */
			
			if (is_array($hook)) {
				if (count($hook) < 1) {
					wfDieDebugBacktrace("Empty array in hooks for " . $event . "\n");
				} else if (is_object($hook[0])) {
					$object = $hook[0];
					if (count($hook) < 2) {
						$method = "on" . $event;
					} else {
						$method = $hook[1];
						if (count($hook) > 2) {
							$data = $hook[2];
							$have_data = true;
						}
					}
				} else if (is_string($hook[0])) {
					$func = $hook[0];
					if (count($hook) > 1) {
						$data = $hook[1];
						$have_data = true;
					}
				} else {
					wfDieDebugBacktrace("Unknown datatype in hooks for " . $event . "\n");
				}
			} else if (is_string($hook)) { # functions look like strings, too
				$func = $hook;
			} else if (is_object($hook)) {
				$object = $hook;
				$method = "on" . $event;
			} else {
				wfDieDebugBacktrace("Unknown datatype in hooks for " . $event . "\n");
			}

			if ($have_data) {
				$hook_args = array_merge(array($data), $args);
			} else {
				$hook_args = $args;
			}
			
			if ($object) {
				$retval = call_user_func_array(array($object, $method), $hook_args);
			} else {
				$retval = call_user_func_array($func, $hook_args);
			}
			
			if (is_string($retval)) {
				global $wgOut;
				$wgOut->fatalError($retval);
				return false;
			} else if (!$retval) {
				return false;
			}
		}
		
		return true;
	}
}

?>
