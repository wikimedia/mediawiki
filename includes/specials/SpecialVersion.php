<?php

/**
 * Give information about the version of MediaWiki, PHP, the DB and extensions
 *
 * @ingroup SpecialPage
 *
 * @author Ævar Arnfjörð Bjarmason <avarab@gmail.com>
 * @copyright Copyright © 2005, Ævar Arnfjörð Bjarmason
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License 2.0 or later
 */
class SpecialVersion extends SpecialPage {
	private $firstExtOpened = true;

	function __construct(){
		parent::__construct( 'Version' );	
	}

	/**
	 * main()
	 */
	function execute( $par ) {
		global $wgOut, $wgMessageCache, $wgSpecialVersionShowHooks, $wgContLang;
		$wgMessageCache->loadAllMessages();

		$this->setHeaders();
		$this->outputHeader();

		if( $wgContLang->isRTL() ) {
			$wgOut->addHTML( '<div dir="rtl">' );
		} else {
			$wgOut->addHTML( '<div dir="ltr">' );
		}
		$text = 
			$this->MediaWikiCredits() .
			$this->softwareInformation() .
			$this->extensionCredits();
		if ( $wgSpecialVersionShowHooks ) {
			$text .= $this->wgHooks();
		}
		$wgOut->addWikiText( $text );
		$wgOut->addHTML( $this->IPInfo() );
		$wgOut->addHTML( '</div>' );
	}

	/**#@+
	 * @private
	 */

	/**
	 * @return wiki text showing the license information
	 */
	static function MediaWikiCredits() {
		global $wgContLang;

		$ret = Xml::element( 'h2', array( 'id' => 'mw-version-license' ), wfMsg( 'version-license' ) );

		// This text is always left-to-right.
		$ret .= '<div dir="ltr">';
		$ret .= "__NOTOC__
		This wiki is powered by '''[http://www.mediawiki.org/ MediaWiki]''',
		copyright © 2001-2009 Magnus Manske, Brion Vibber, Lee Daniel Crocker,
		Tim Starling, Erik Möller, Gabriel Wicke, Ævar Arnfjörð Bjarmason,
		Niklas Laxström, Domas Mituzas, Rob Church, Yuri Astrakhan, Aryeh Gregor,
		Aaron Schulz, Andrew Garrett, Raimond Spekking, Alexandre Emsenhuber,
		Siebrand Mazeland, Chad Horohoe and others.

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
		Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA
		or [http://www.gnu.org/licenses/old-licenses/gpl-2.0.html read it online].
		";
		$ret .= '</div>';

		return str_replace( "\t\t", '', $ret ) . "\n";
	}

	/**
	 * @return wiki text showing the third party software versions (apache, php, mysql).
	 */
	static function softwareInformation() {
		$dbr = wfGetDB( DB_SLAVE );

		// Put the software in an array of form 'name' => 'version'. All messages should
		// be loaded here, so feel free to use wfMsg*() in the 'name'. Raw HTML or wikimarkup
		// can be used
		$software = array();
		$software['[http://www.mediawiki.org/ MediaWiki]'] = self::getVersionLinked();
		$software['[http://www.php.net/ PHP]'] = phpversion() . " (" . php_sapi_name() . ")";
		$software[$dbr->getSoftwareLink()] = $dbr->getServerVersion();

		// Allow a hook to add/remove items
		wfRunHooks( 'SoftwareInfo', array( &$software ) );

		$out = Xml::element( 'h2', array( 'id' => 'mw-version-software' ), wfMsg( 'version-software' ) ) .
			   Xml::openElement( 'table', array( 'class' => 'wikitable', 'id' => 'sv-software' ) ) .
				"<tr>
					<th>" . wfMsg( 'version-software-product' ) . "</th>
					<th>" . wfMsg( 'version-software-version' ) . "</th>
				</tr>\n";
		foreach( $software as $name => $version ) {
			$out .= "<tr>
					<td>" . $name . "</td>
					<td>" . $version . "</td>
				</tr>\n";
		}		
		return $out . Xml::closeElement( 'table' );
	}

	/**
	 * Return a string of the MediaWiki version with SVN revision if available
	 *
	 * @return mixed
	 */
	public static function getVersion( $flags = ''  ) {
		global $wgVersion, $IP;
		wfProfileIn( __METHOD__ );
		$svn = self::getSvnRevision( $IP, false, false , false );
		$svnCo = self::getSvnRevision( $IP, true, false , false );
		if ( !$svn ) {
			$version = $wgVersion;
		} elseif( $flags === 'nodb' ) {
			$version = "$wgVersion (r$svnCo)";
		} else {
			$version = $wgVersion . wfMsg( 'version-svn-revision', $svn, $svnCo );
		}
		wfProfileOut( __METHOD__ );
		return $version;
	}
	
	/**
	 * Return a string of the MediaWiki version with a link to SVN revision if
	 * available
	 *
	 * @return mixed
	 */
	public static function getVersionLinked() {
		global $wgVersion, $IP;
		wfProfileIn( __METHOD__ );
		$svn = self::getSvnRevision( $IP, false, false, false );
		$svnCo = self::getSvnRevision( $IP, true, false, false );
		$svnDir = self::getSvnRevision( $IP, true, false, true );
		$viewvcStart = 'http://svn.wikimedia.org/viewvc/mediawiki/';
		$viewvcEnd = '/?pathrev=';
		$viewvc = $viewvcStart . $svnDir .  $viewvcEnd;
		$version = $svn ? $wgVersion . " [{$viewvc}{$svnCo} " . wfMsg( 'version-svn-revision', $svn, $svnCo ) . ']' : $wgVersion;
		wfProfileOut( __METHOD__ );
		return $version;
	}

	/** Generate wikitext showing extensions name, URL, author and description */
	function extensionCredits() {
		global $wgExtensionCredits, $wgExtensionFunctions, $wgParser, $wgSkinExtensionFunctions;

		if ( ! count( $wgExtensionCredits ) && ! count( $wgExtensionFunctions ) && ! count( $wgSkinExtensionFunctions ) )
			return '';

		$extensionTypes = array(
			'specialpage' => wfMsg( 'version-specialpages' ),
			'parserhook' => wfMsg( 'version-parserhooks' ),
			'variable' => wfMsg( 'version-variables' ),
			'media' => wfMsg( 'version-mediahandlers' ),
			'other' => wfMsg( 'version-other' ),
		);
		wfRunHooks( 'SpecialVersionExtensionTypes', array( &$this, &$extensionTypes ) );

		$out = Xml::element( 'h2', array( 'id' => 'mw-version-ext' ), wfMsg( 'version-extensions' ) ) .
			Xml::openElement( 'table', array( 'class' => 'wikitable', 'id' => 'sv-ext' ) );

		foreach ( $extensionTypes as $type => $text ) {
			if ( isset ( $wgExtensionCredits[$type] ) && count ( $wgExtensionCredits[$type] ) ) {
				$out .= $this->openExtType( $text );

				usort( $wgExtensionCredits[$type], array( $this, 'compare' ) );

				foreach ( $wgExtensionCredits[$type] as $extension ) {
					$version = null;
					$subVersion = null;
					$subVersionCo = null;
					$viewvc = null;
					if ( isset( $extension['path'] ) ) {
						$subVersion = self::getSvnRevision(dirname($extension['path']), false, true, false);
						$subVersionCo = self::getSvnRevision(dirname($extension['path']), true, true, false);
						$subVersionDir = self::getSvnRevision(dirname($extension['path']), false, true, true);
						if ($subVersionDir)
							$viewvc = $subVersionDir . $subVersionCo;
					}
					if ( isset( $extension['version'] ) ) {
						$version = $extension['version'];
					}

					$out .= $this->formatCredits(
						isset ( $extension['name'] )           ? $extension['name']        : '',
						$version,
						$subVersion,
						$subVersionCo,
						$viewvc,
						isset ( $extension['author'] )         ? $extension['author']      : '',
						isset ( $extension['url'] )            ? $extension['url']         : null,
						isset ( $extension['description'] )    ? $extension['description'] : '',
						isset ( $extension['descriptionmsg'] ) ? $extension['descriptionmsg'] : null
					);
				}
			}
		}

		if ( count( $wgExtensionFunctions ) ) {
			$out .= $this->openExtType( wfMsg( 'version-extension-functions' ) );
			$out .= '<tr><td colspan="4">' . $this->listToText( $wgExtensionFunctions ) . "</td></tr>\n";
		}

		if ( $cnt = count( $tags = $wgParser->getTags() ) ) {
			for ( $i = 0; $i < $cnt; ++$i )
				$tags[$i] = "&lt;{$tags[$i]}&gt;";
			$out .= $this->openExtType( wfMsg( 'version-parser-extensiontags' ) );
			$out .= '<tr><td colspan="4">' . $this->listToText( $tags ). "</td></tr>\n";
		}

		if( $cnt = count( $fhooks = $wgParser->getFunctionHooks() ) ) {
			$out .= $this->openExtType( wfMsg( 'version-parser-function-hooks' ) );
			$out .= '<tr><td colspan="4">' . $this->listToText( $fhooks ) . "</td></tr>\n";
		}

		if ( count( $wgSkinExtensionFunctions ) ) {
			$out .= $this->openExtType( wfMsg( 'version-skin-extension-functions' ) );
			$out .= '<tr><td colspan="4">' . $this->listToText( $wgSkinExtensionFunctions ) . "</td></tr>\n";
		}
		$out .= Xml::closeElement( 'table' );
		return $out;
	}

	/** Callback to sort extensions by type */
	function compare( $a, $b ) {
		global $wgLang;
		if( $a['name'] === $b['name'] ) {
			return 0;
		} else {
			return $wgLang->lc( $a['name'] ) > $wgLang->lc( $b['name'] )
				? 1
				: -1;
		}
	}

	function formatCredits( $name, $version = null, $subVersion = null, $subVersionCo = null, $subVersionURL = null, $author = null, $url = null, $description = null, $descriptionMsg = null ) {
		$haveSubversion = $subVersion;
		$extension = isset( $url ) ? "[$url $name]" : $name;
		$version = isset( $version ) ? wfMsg( 'version-version', $version ) : '';
		$subVersion = isset( $subVersion ) ? wfMsg( 'version-svn-revision', $subVersion, $subVersionCo ) : '';
		$subVersion = isset( $subVersionURL ) ? "[$subVersionURL $subVersion]" : $subVersion;

		# Look for a localized description
		if( isset( $descriptionMsg ) ) {
			if( is_array( $descriptionMsg ) ) {
				$descriptionMsgKey = $descriptionMsg[0]; // Get the message key
				array_shift( $descriptionMsg ); // Shift out the message key to get the parameters only
				array_map( "htmlspecialchars", $descriptionMsg ); // For sanity
				$msg = wfMsg( $descriptionMsgKey, $descriptionMsg );
			} else {
				$msg = wfMsg( $descriptionMsg );
			}
 			if ( !wfEmptyMsg( $descriptionMsg, $msg ) && $msg != '' ) {
 				$description = $msg;
 			}
		}

		if ( $haveSubversion ) {
		$extNameVer = "<tr>
				<td><em>$extension $version</em></td>
				<td><em>$subVersion</em></td>";
		} else {
		$extNameVer = "<tr>
				<td colspan=\"2\"><em>$extension $version</em></td>";
		}
		$extDescAuthor = "<td>$description</td>
				  <td>" . $this->listToText( (array)$author ) . "</td>
			    </tr>\n";
		return $ret = $extNameVer . $extDescAuthor;
		return $ret;
	}

	/**
	 * @return string
	 */
	function wgHooks() {
		global $wgHooks;

		if ( count( $wgHooks ) ) {
			$myWgHooks = $wgHooks;
			ksort( $myWgHooks );

			$ret = Xml::element( 'h2', array( 'id' => 'mw-version-hooks' ), wfMsg( 'version-hooks' ) ) .
				Xml::openElement( 'table', array( 'class' => 'wikitable', 'id' => 'sv-hooks' ) ) .
				"<tr>
					<th>" . wfMsg( 'version-hook-name' ) . "</th>
					<th>" . wfMsg( 'version-hook-subscribedby' ) . "</th>
				</tr>\n";

			foreach ( $myWgHooks as $hook => $hooks )
				$ret .= "<tr>
						<td>$hook</td>
						<td>" . $this->listToText( $hooks ) . "</td>
					</tr>\n";

			$ret .= Xml::closeElement( 'table' );
			return $ret;
		} else
			return '';
	}

	private function openExtType($text, $name = null) {
		$opt = array( 'colspan' => 4 );
		$out = '';

		if(!$this->firstExtOpened) {
			// Insert a spacing line
			$out .= '<tr class="sv-space">' . Xml::element( 'td', $opt ) . "</tr>\n";
		}
		$this->firstExtOpened = false;

		if($name) { $opt['id'] = "sv-$name"; }

		$out .= "<tr>" . Xml::element( 'th', $opt, $text) . "</tr>\n";
		return $out;
	}

	/**
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

		if ( $cnt == 1 ) {
			// Enforce always returning a string
			return (string)self::arrayToString( $list[0] );
		} elseif ( $cnt == 0 ) {
			return '';
		} else {
			global $wgLang;
			sort( $list );
			return $wgLang->listToText( array_map( array( __CLASS__, 'arrayToString' ), $list ) );
		}
	}

	/**
	 * @param mixed $list Will convert an array to string if given and return
	 *                    the paramater unaltered otherwise
	 * @return mixed
	 */
	static function arrayToString( $list ) {
		if( is_array( $list ) && count( $list ) == 1 )
			$list = $list[0];
		if( is_object( $list ) ) {
			$class = get_class( $list );
			return "($class)";
		} elseif ( !is_array( $list ) ) {
			return $list;
		} else {
			if( is_object( $list[0] ) )
				$class = get_class( $list[0] );
			else 
				$class = $list[0];
			return "($class, {$list[1]})";
		}
	}

	/**
	 * Retrieve the revision number of a Subversion working directory.
	 *
	 * @param String $dir Directory of the svn checkout
	 * @param Boolean $coRev optional to return value whether is Last Modified
	 *                or Checkout revision number
	 * @param Boolean $extension optional to check the path whether is from
	 *                Wikimedia SVN server or not
	 * @param Boolean $relPath optional to get the end part of the checkout path
	 * @return mixed revision number as int, end part of the checkout path, 
	 *               or false if not a SVN checkout
	 */
	public static function getSvnRevision( $dir, $coRev = false, $extension = false, $relPath = false) {
		// http://svnbook.red-bean.com/nightly/en/svn.developer.insidewc.html
		$entries = $dir . '/.svn/entries';

		if( !file_exists( $entries ) ) {
			return false;
		}

		$content = file( $entries );

		// check if file is xml (subversion release <= 1.3) or not (subversion release = 1.4)
		if( preg_match( '/^<\?xml/', $content[0] ) ) {
			// subversion is release <= 1.3
			if( !function_exists( 'simplexml_load_file' ) ) {
				// We could fall back to expat... YUCK
				return false;
			}

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
		} else {
			// subversion is release 1.4 or above
			if ($relPath) {
				$endPath = strstr( $content[4], 'tags' );
				if (!$endPath) {
					$endPath = strstr( $content[4], 'branches' );
					if (!$endPath) {
						$endPath = strstr( $content[4], 'trunk' );
						if (!$endPath)
							return false;
					}
				}
				$endPath = trim ( $endPath );
				if ($extension) {
					$wmSvnPath = 'svn.wikimedia.org/svnroot/mediawiki';
					$isWMSvn = strstr($content[5],$wmSvnPath);
					if (!strcmp($isWMSvn,null)) {
						return false;
					} else {
						$viewvcStart = 'http://svn.wikimedia.org/viewvc/mediawiki/';
						if (strstr( $content[4], 'trunk' ))
							$viewvcEnd = '/?pathrev=';
						else
							// Avoids 404 error using pathrev when it does not found
							$viewvcEnd = '/?revision=';
						$viewvc = $viewvcStart . $endPath . $viewvcEnd;
						return $viewvc;
					}
				}
				return $endPath;
			}
			if ($coRev)
				// get the directory checkout revsion number
				return intval( $content[3]) ;
			else
				// get the directory last modified revision number
				return intval( $content[10] );
		}
	}

	/**#@-*/
}
