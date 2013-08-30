<?php
/**
 * RandomSelection -- randomly displays one of the given options.
 * Usage: <choose><option>A</option><option>B</option></choose>
 * Optional parameter: <option weight="3"> == 3x weight given
 *
 * @file RandomSelection.php
 * @ingroup Extensions
 * @version 2.1.6
 * @date 17 January 2010
 * @author Ross McClure <https://www.mediawiki.org/wiki/User:Algorithm>
 * @link https://www.mediawiki.org/wiki/Extension:RandomSelection Documentation
 */
 
if( !defined( 'MEDIAWIKI' ) ) {
        die( "This is not a valid entry point to MediaWiki.\n" );
}
 
$wgHooks['ParserFirstCallInit'][] = 'wfRandomSelection';
 
$wgExtensionCredits['parserhook'][] = array(
        'name' => 'RandomSelection',
        'version' => '2.1.6',
        'author' => 'Ross McClure',
        'description' => 'Displays a random option from the given set',
        'url' => 'https://www.mediawiki.org/wiki/Extension:RandomSelection',
);
 
function wfRandomSelection( &$parser ) {
        $parser->setHook( 'choose', 'renderChosen' );
        return true;
}
 
function renderChosen( $input, $argv, $parser ) {
        # Prevent caching
        $parser->disableCache();
 
        # Parse the options and calculate total weight
        $len = preg_match_all(
                "/<option(?:(?:\\s[^>]*?)?\\sweight=[\"']?([^\\s>]+))?"
                        . "(?:\\s[^>]*)?>([\\s\\S]*?)<\\/option>/",
                $input,
                $out
        );
 
        # Find any references to a surrounding template
        preg_match_all(
                "/<choicetemplate(?:(?:\\s[^>]*?)?\\sweight=[\"']?([^\\s>]+))?"
                        . "(?:\\s[^>]*)?>([\\s\\S]*?)<\\/choicetemplate>/",
                $input,
                $outTemplate
        );
        $r = 0;
        for( $i = 0; $i < $len; $i++ ) {
                if( strlen( $out[1][$i] ) == 0 ) {
                        $out[1][$i] = 1;
                } else {
                        $out[1][$i] = intval( $out[1][$i] );
                }
                $r += $out[1][$i];
        }
 
        # Choose an option at random
        if( $r <= 0 ) {
                return '';
        }
        $r = mt_rand( 1, $r );
        for( $i = 0; $i < $len; $i++ ) {
                $r -= $out[1][$i];
                if( $r <= 0 ) {
                        $input = $out[2][$i];
                        break;
                }
        }
 
        # Surround by template if applicable
        if ( isset ( $outTemplate[2][0] ) ) {
                $input = '{{' . $outTemplate[2][0] . '|' . $input . '}}';
        }
 
        # Parse tags and return
        return $parser->recursiveTagParse( $input );
}

