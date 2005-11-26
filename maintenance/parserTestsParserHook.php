<?php
if (!defined('MEDIAWIKI')) die();
/**
 * A basic extension that's used by the parser tests to test whether input and
 * arguments are passed to extensions properly.
 *
 * @package MediaWiki
 * @subpackage Maintenance
 *
 * @author Ævar Arnfjörð Bjarmason <avarab@gmail.com>
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License 2.0 or later
 */

$wgHooks['ParserTestParser'][] = 'wfParserTestSetup';

function wfParserTestSetup( &$parser ) {
	$parser->setHook( 'tag', 'wfParserTestHook' );
}
	
function wfParserTestHook( $in, $argv ) {
	if ( count( $argv ) ) 
		return "<pre>\n" . print_r( $argv, true ) . '</pre>';
	else
		return $in;
}
