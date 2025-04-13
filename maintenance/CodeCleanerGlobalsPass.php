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
 * Prefix the real command with a 'global $VAR, $VAR2, ...;' command, where $VAR etc.
 * are the current global variables. This will make the shell behave as if it was running
 * in the global scope (almost; variables created in the shell won't become global if no
 * global variable by that name existed before) so debugging MediaWikis globals-based
 * configuration settings is less cumbersome, and behavior is closer to that of eval.php.
 */
class CodeCleanerGlobalsPass extends \Psy\CodeCleaner\CodeCleanerPass {
	private const SUPERGLOBALS = [
		'GLOBALS', '_SERVER', '_ENV', '_FILES', '_COOKIE', '_POST', '_GET', '_SESSION'
	];

	public function beforeTraverse( array $nodes ): array {
		$globalVars = array_diff( array_keys( $GLOBALS ), self::SUPERGLOBALS );
		$validGlobalVars = array_filter( $globalVars, static function ( string $name ) {
			// https://www.php.net/manual/en/language.variables.basics.php
			return preg_match( '/^[a-zA-Z_\x80-\xff][a-zA-Z0-9_\x80-\xff]*$/', $name );
		} );

		if ( $validGlobalVars ) {
			$globalCommand = new \PhpParser\Node\Stmt\Global_( array_map( static function ( string $name ) {
				return new \PhpParser\Node\Expr\Variable( $name );
			}, $validGlobalVars ) );
			array_unshift( $nodes, $globalCommand );
		}

		return $nodes;
	}
}
