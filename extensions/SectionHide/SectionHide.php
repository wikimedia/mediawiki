<?php
/**
 * SectionHide extension - implements a hide/show link on sections on any ordinary page.
 * @version 1.2.1 - 2013-11-16 - totally messed with by Brent Laabs to use a different hook
 * and move the [hide] link next to [edit].
 * version 1.2 - 2013/07/31
 * version 1.1 added a hide all/show all link for the entire article 
 * version 1.2 added an option on the hide all/show all link to show the initial text
 *
 * @link https://www.mediawiki.org/wiki/Extension:SectionHide Documentation
 *
 * @file
 * @ingroup Extensions
 * @package MediaWiki
 * @author Simon Oliver (amended by Brent Laabs)
 * @copyright © 2013 Simon Oliver (Hoggle42), Brent Laabs (Vorticity)
 * @licence http://www.gnu.org/copyleft/gpl.html GNU General Public Licence 2.0 or later
 *
 * add the following line to localsettinge.php to use
 * require_once("$IP/extensions/SectionHide/SectionHide.php");
 * // Set this option to 1 to show the text before the first section when hiding all
 * // Set to X to show the top x-1 sections (use with caution - some browsers may complain)
 * $wgSectionHideShowtop = 0; //default
 */
 
if( !defined( 'MEDIAWIKI' ) ) {
        echo( "This file is an extension to the MediaWiki software and cannot be used standalone.\n" );
        die( 1 );
}
 
$wgExtensionCredits['other'][] = array( 
        'name' => 'SectionHide', 
        'author' => 'Simon Oliver',
        'version' => '1.2.1',
        'url' => 'https://www.mediawiki.org/wiki/Extension:SectionHide',
        'descriptionmsg' => 'sectionhide-desc',
); 
 
# $wgHooks['ParserAfterParse'][] = 'SectionHideHooks::onParserAfterParse';
$wgHooks['ParserSectionCreate'][] = 'SectionHideHooks::onParserSectionCreate';
$wgHooks['DoEditSectionLink'][]   = 'SectionHideHooks::onDoEditSectionLink';
 
$wgAutoloadClasses[ 'SectionHideHooks' ] = __DIR__ . '/SectionHideHooks.php';
$wgExtensionMessagesFiles[ 'SectionHide' ] = __DIR__ . '/SectionHide.i18n.php';
$wgExtensionMessagesFiles[ 'SectionHideAlias' ] = __DIR__ . '/SectionHide.alias.php';
$wgMessagesDirs['SectionHide'] = __DIR__ . '/i18n';
