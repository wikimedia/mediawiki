<?php
/**
 * A basic extension that's used by the parser tests to test whether input and
 * arguments are passed to extensions properly.
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

class ParserTestParserHook {

	static function setup( &$parser ) {
		$parser->setHook( 'tag', array( __CLASS__, 'hook' ) );

		return true;
	}

	static function hook( $in, $argv ) {
		ob_start();
		var_dump(
			$in,
			$argv
		);
		$ret = ob_get_clean();

		return "<pre>\n$ret</pre>";
	}
}
