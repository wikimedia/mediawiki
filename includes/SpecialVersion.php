<?php
/**#@+
 * Give information about the version of MediaWiki, PHP, the DB and extensions
 *
 * @package MediaWiki
 * @subpackage SpecialPage
 *
 * @author Ævar Arnfjörð Bjarmason <avarab@gmail.com>
 * @copyright Copyright © 2005, Ævar Arnfjörð Bjarmason
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License 2.0 or later
 */

/**
 * constructor
 */
function wfSpecialVersion() {
	$version = new SpecialVersion;
	$version->execute();
}

class SpecialVersion {
	/**
	 * @var object
	 * @access private
	 */
	var $langObj;
	
	/**
	 * Constructor
	 */
	function SpecialVersion() {
		// English motherfucker, do you speak it?
		$this->langObj = setupLangObj( 'LanguageEn' );
		$this->langObj->initEncoding();
	}

	/**
	 * main()
	 */
	function execute() {
		global $wgOut;
		
		$wgOut->addWikiText(
			$this->MediaWikiCredits() .
			$this->extensionCredits() .
			$this->wgHooks()
		);
		$wgOut->addHTML( $this->IPInfo() );
	}

	/**#@+
	 * @access private
	 */
	
	/**
	 * @static
	 */
	function MediaWikiCredits() {
		global $wgVersion;
		
		$dbr =& wfGetDB( DB_SLAVE );
		
		$ret =
		"__NOTOC__
		<div dir='ltr'>
		This wiki is powered by '''[http://www.mediawiki.org/ MediaWiki]''',  
		copyright (C) 2001-2005 Magnus Manske, Brion Vibber, Lee Daniel Crocker,
		Tim Starling, Erik Möller, and others.
		 
		MediaWiki is free software; you can redistribute it and/or modify
		it under the terms of the GNU General Public License as published by
		the Free Software Foundation; either version 2 of the License, or
		(at your option) any later version.
		 
		MediaWiki is distributed in the hope that it will be useful,
		but WITHOUT ANY WARRANTY; without even the implied warranty of
		MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
		GNU General Public License for more details.
		
		You should have received [{{SERVER}}{{SCRIPTPATH}}/COPYING a copy of the GNU General Public License]
		along with this program; if not, write to the Free Software
		Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA 02111-1307, USA.
		or [http://www.gnu.org/copyleft/gpl.html read it online]
		
		* [http://www.mediawiki.org/ MediaWiki]: $wgVersion
		* [http://www.php.net/ PHP]: " . phpversion() . " (" . php_sapi_name() . ")
		* " . $dbr->getSoftwareLink() . ": " . $dbr->getServerVersion() . "
		</div>";

		return str_replace( "\t\t", '', $ret );
	}

	function extensionCredits() {
		global $wgExtensionCredits, $wgExtensionFunctions, $wgSkinExtensionFunction;
		
		if ( ! count( $wgExtensionCredits ) && ! count( $wgExtensionFunctions ) && ! count( $wgSkinExtensionFunction ) )
			return '';

		$extensionTypes = array(
			'specialpage' => 'Special pages',
			'parserhook' => 'Parser hooks',
			'variable' => 'Variables',
			'other' => 'Other',
		);
		wfRunHooks( 'SpecialVersionExtensionTypes', array( &$this, &$extensionTypes ) );
		
		$out = "\n* Extensions:\n";
		foreach ( $extensionTypes as $type => $text ) {
			if ( count( @$wgExtensionCredits[$type] ) ) {
				$out .= "** $text:\n";
				
				usort( $wgExtensionCredits[$type], array( $this, 'compare' ) );
				
				foreach ( $wgExtensionCredits[$type] as $extension ) {
					wfSuppressWarnings();
					$out .= $this->formatCredits(
						$extension['name'],
						$extension['version'],
						$extension['author'],
						$extension['url'],
						$extension['description']
					);
					wfRestoreWarnings();
				}
			}
		}

		if ( count( $wgExtensionFunctions ) ) {
			$out .= "** Extension functions:\n";
			$out .= '***' . $this->langObj->listToText( $wgExtensionFunctions ) . "\n";
		}

		if ( count( $wgSkinExtensionFunction ) ) {
			$out .= "** Skin extension functions:\n";
			$out .= '***' . $this->langObj->listToText( $wgSkinExtensionFunction ) . "\n";
		}

		return $out;
	}

	function compare( $a, $b ) {
		if ( $a['name'] === $b['name'] )
			return 0;
		else
			return $this->langObj->lc( $a['name'] ) > $this->langObj->lc( $b['name'] ) ? 1 : -1;
	}

	function formatCredits( $name, $version = null, $author = null, $url = null, $description = null) {
		$ret = '*** ';
		if ( isset( $url ) )
			$ret .= "[$url ";
		$ret .= "''$name";
		if ( isset( $version ) )
			$ret .= " (version $version)";
		$ret .= "''";
		if ( isset( $url ) )
			$ret .= ']';
		if ( isset( $description ) )
			$ret .= ', ' . $description;
		if ( isset( $description ) && isset( $author ) )
			$ret .= ', ';
		if ( isset( $author ) )
			$ret .= ' by ' . $this->langObj->listToText( (array)$author );

		return "$ret\n";
	}

	function wgHooks() {
		global $wgHooks;

		$myWgHooks = $wgHooks;
		ksort( $myWgHooks );

		$ret = "* Hooks:\n";
		foreach ($myWgHooks as $hook => $hooks)
			$ret .= "** $hook:" . $this->langObj->listToText( $hooks ) . "\n";
		
		return $ret;
	}

	/**
	 * @static
	 */
	function IPInfo() {
		$ip =  str_replace( '--', ' - ', htmlspecialchars( wfGetIP() ) );
		return "<!-- visited from $ip -->\n";
	}
	
	/**#@-*/
}

/**#@-*/
?>
