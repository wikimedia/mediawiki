<?php
if ( ! defined( 'MEDIAWIKI' ) )
	die();
/**
 * A basic extension that's used by the parser tests to test whether input and
 * arguments are passed to extensions properly.
 *
 * @package MediaWiki
 * @subpackage Maintenance
 *
 * @author Ævar Arnfjörð Bjarmason <avarab@gmail.com>
 * @copyright Copyright © 2005, 2006 Ævar Arnfjörð Bjarmason
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License 2.0 or later
 */

$wgHooks['ParserTestParser'][] = 'wfParserTestSetup';

function wfParserTestSetup( &$parser ) {
	$parser->setHook( 'tag', 'wfParserTestHook' );

	return true;
}

function wfParserTestHook( $in, $argv ) {
	ob_start();
	var_dump(
		$in,
		$argv
	);
	$ret = ob_get_clean();

	return "<pre>\n$ret</pre>";
}
