<?php
/**
 * A basic extension that's used by the parser tests to test whether input and
 * arguments are passed to extensions properly.
 *
 * @file
 * @ingroup Maintenance
 *
 * @author Ævar Arnfjörð Bjarmason <avarab@gmail.com>
 * @copyright Copyright © 2005, 2006 Ævar Arnfjörð Bjarmason
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License 2.0 or later
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
