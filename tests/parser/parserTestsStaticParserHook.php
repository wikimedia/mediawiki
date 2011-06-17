<?php
/**
 * A basic extension that's used by the parser tests to test whether the parser
 * calls extensions when they're called inside comments, it shouldn't do that
 *
 * Copyright © 2005, 2006 Ævar Arnfjörð Bjarmason
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
 * @ingroup Testing
 * @author Ævar Arnfjörð Bjarmason <avarab@gmail.com>
 */

class ParserTestStaticParserHook {
	static function setup( &$parser ) {
		$parser->setHook( 'statictag', array( __CLASS__, 'hook' ) );

		return true;
	}

	static function hook( $in, $argv, $parser ) {
		if ( ! count( $argv ) ) {
			$parser->static_tag_buf = $in;
			return '';
		} elseif ( count( $argv ) === 1 && isset( $argv['action'] )
			&& $argv['action'] === 'flush' && $in === null )
		{
			// Clear the buffer, we probably don't need to
			if ( isset( $parser->static_tag_buf ) ) {
				$tmp = $parser->static_tag_buf;
			} else {
				$tmp = '';
			}
			$parser->static_tag_buf = null;
			return $tmp;
		} else
			// wtf?
			return
				"\nCall this extension as <statictag>string</statictag> or as" .
				" <statictag action=flush/>, not in any other way.\n" .
				"text: " . var_export( $in, true ) . "\n" .
				"argv: " . var_export( $argv, true ) . "\n";
	}
}
