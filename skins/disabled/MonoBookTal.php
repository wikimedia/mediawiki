<?php
/**
 * This is for the old PHPTAL-based version of MonoBook.
 * The main MonoBook has been converted to straight PHP
 * to avoid the dependency on PHPTAL and, hopefully,
 * reduce the frequent problems users have with compiled
 * PHPTAL templates failing.
 *
 * You can still use MonoBook.pt as a sample, or copy it
 * to the parent directory to test with.
 *
 * @todo document
 * @package MediaWiki
 * @subpackage Skins
 */

if( !defined( 'MEDIAWIKI' ) )
	die();

/** */
require_once('includes/SkinPHPTal.php');

# Test if PHPTal is enabled. If not MediaWiki will load the 'standard' skin
# which doesnt use PHPTal
if( class_exists( 'SkinPHPTal' ) ) {
	/**
	 * Inherit everything from SkinPHPTal
	 * This is a dummy skin as MonoBook is the default PHPTal skin.
	 * @todo document
	 * @package MediaWiki
	 * @subpackage Skins
	 */
	class SkinMonoBookTal extends SkinPHPTal {
		/** Using monobook. */
		function initPage( &$out ) {
			SkinPHPTal::initPage( $out );
			$this->skinname  = 'monobooktal';
			$this->stylename = 'monobook';
			$this->template  = 'MonoBook';
		}
	}

}
?>
