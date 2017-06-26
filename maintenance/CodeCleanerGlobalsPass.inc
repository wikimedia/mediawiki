<?php
/**
 * Psy CodeCleaner to allow PHP super globals.
 *
 * https://github.com/bobthecow/psysh/issues/353
 *
 * Copyright © 2017 Justin Hileman <justin@justinhileman.info>
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
 * @ingroup Maintenance
 *
 * @author Justin Hileman <justin@justinhileman.info>
 */

/**
 * Prefix the real command with a bunch of 'global $VAR;' commands, one for each global.
 * This will make the shell behave as if it was running in the global scope (almost;
 * variables created in the shell won't become global if no global variable by that name
 * existed before).
 */
class CodeCleanerGlobalsPass extends \Psy\CodeCleaner\CodeCleanerPass {
	private static $superglobals = [
		'GLOBALS', '_SERVER', '_ENV', '_FILES', '_COOKIE', '_POST', '_GET', '_SESSION'
	];

	public function beforeTraverse( array $nodes ) {
		$names = [];
		foreach ( array_diff( array_keys( $GLOBALS ), self::$superglobals ) as $name ) {
			array_push( $names, new \PhpParser\Node\Expr\Variable( $name ) );
		}

		array_unshift( $nodes, new \PhpParser\Node\Stmt\Global_( $names ) );

		return $nodes;
	}
}
