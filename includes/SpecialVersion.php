<?php
/**#@+
 * Give information about the version of MediaWiki, PHP, the DB and extensions
 *
 * @package MediaWiki
 * @subpackage SpecialPage
 *
 * @bug 2019, 4531
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
	 * main()
	 */
	function execute() {
		global $wgOut;

		$wgOut->addHTML( '<div dir="ltr">' );
		$wgOut->addWikiText(
			$this->MediaWikiCredits() .
			$this->extensionCredits() .
			$this->wgHooks()
		);
		$wgOut->addHTML( $this->IPInfo() );
		$wgOut->addHTML( '</div>' );
	}

	/**#@+
	 * @private
	 */

	/**
	 * @static
	 */
	function MediaWikiCredits() {
		$version = $this->getVersion();
		$dbr =& wfGetDB( DB_SLAVE );

		$ret =
		"__NOTOC__
		This wiki is powered by '''[http://www.mediawiki.org/ MediaWiki]''',
		copyright (C) 2001-2006 Magnus Manske, Brion Vibber, Lee Daniel Crocker,
		Tim Starling, Erik Möller, Gabriel Wicke, Ævar Arnfjörð Bjarmason,
		Niklas Laxström, Domas Mituzas, Rob Church and others.

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
		Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA.
		or [http://www.gnu.org/copyleft/gpl.html read it online]

		* [http://www.mediawiki.org/ MediaWiki]: $version
		* [http://www.php.net/ PHP]: " . phpversion() . " (" . php_sapi_name() . ")
		* " . $dbr->getSoftwareLink() . ": " . $dbr->getServerVersion();

		return str_replace( "\t\t", '', $ret );
	}
	
	function getVersion() {
		global $wgVersion, $IP;
		$svn = $this->getSvnRevision( $IP );
		return $svn ? "$wgVersion (r$svn)" : $wgVersion;
	}

	function extensionCredits() {
		global $wgExtensionCredits, $wgExtensionFunctions, $wgParser, $wgSkinExtensionFunction;

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
			$out .= '***' . $this->listToText( $wgExtensionFunctions ) . "\n";
		}

		if ( $cnt = count( $tags = $wgParser->getTags() ) ) {
			for ( $i = 0; $i < $cnt; ++$i )
				$tags[$i] = "&lt;{$tags[$i]}&gt;";
			$out .= "** Parser extension tags:\n";
			$out .= '***' . $this->listToText( $tags ). "\n";
		}

		if ( count( $wgSkinExtensionFunction ) ) {
			$out .= "** Skin extension functions:\n";
			$out .= '***' . $this->listToText( $wgSkinExtensionFunction ) . "\n";
		}

		return $out;
	}

	function compare( $a, $b ) {
		if ( $a['name'] === $b['name'] )
			return 0;
		else
			return LanguageUtf8::lc( $a['name'] ) > LanguageUtf8::lc( $b['name'] ) ? 1 : -1;
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
			$ret .= ' by ' . $this->listToText( (array)$author );

		return "$ret\n";
	}

	/**
	 * @return string
	 */
	function wgHooks() {
		global $wgHooks;

		if ( count( $wgHooks ) ) {
			$myWgHooks = $wgHooks;
			ksort( $myWgHooks );
			
			$ret = "* Hooks:\n";
			foreach ($myWgHooks as $hook => $hooks)
				$ret .= "** $hook: " . $this->listToText( $hooks ) . "\n";
			
			return $ret;
		} else
			return '';
	}

	/**
	 * @static
	 *
	 * @return string
	 */
	function IPInfo() {
		$ip =  str_replace( '--', ' - ', htmlspecialchars( wfGetIP() ) );
		return "<!-- visited from $ip -->\n" .
			"<span style='display:none'>visited from $ip</span>";
	}

	/**
	 * @param array $list
	 * @return string
	 */
	function listToText( $list ) {
		$cnt = count( $list );

	    if ( $cnt == 1 )
			// Enforce always returning a string
			return (string)$this->arrayToString( $list[0] );
	    else {
			$t = array_slice( $list, 0, $cnt - 1 );
			$one = array_map( array( &$this, 'arrayToString' ), $t );
			$two = $this->arrayToString( $list[$cnt - 1] );
			
			return implode( ', ', $one ) . " and $two";
	    }
	}

	/**
	 * @static
	 *
	 * @param mixed $list Will convert an array to string if given and return
	 *                    the paramater unaltered otherwise
	 * @return mixed
	 */
	function arrayToString( $list ) {
		if ( ! is_array( $list ) )
			return $list;
		else {
			$class = get_class( $list[0] );
			return "($class, {$list[1]})";
		}
	}

	/**
	 * Retrieve the revision number of a Subversion working directory.
	 *
	 * @param string $dir
	 * @return mixed revision number as int, or false if not a SVN checkout
	 */
	function getSvnRevision( $dir ) {
		if( !function_exists( 'simplexml_load_file' ) ) {
			// We could fall back to expat... YUCK
			return false;
		}
		
		// http://svnbook.red-bean.com/nightly/en/svn.developer.insidewc.html
		$entries = $dir . '/.svn/entries';
		
		// SimpleXml whines about the xmlns...
		wfSuppressWarnings();
		$xml = simplexml_load_file( $entries );
		wfRestoreWarnings();
		
		if( $xml ) {
			foreach( $xml->entry as $entry ) {
				if( $xml->entry[0]['name'] == '' ) {
					// The directory entry should always have a revision marker.
					if( $entry['revision'] ) {
						return intval( $entry['revision'] );
					}
				}
			}
		}
		return false;
	}

	/**#@-*/
}

/**#@-*/
?>
