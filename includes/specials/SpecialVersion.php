<?php
/**
 * Implements Special:Version
 *
 * Copyright © 2005 Ævar Arnfjörð Bjarmason
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
 * @ingroup SpecialPage
 */

/**
 * Give information about the version of MediaWiki, PHP, the DB and extensions
 *
 * @ingroup SpecialPage
 */
class SpecialVersion extends SpecialPage {
	
	protected $firstExtOpened = false;

	protected static $extensionTypes = false;
	
	protected static $viewvcUrls = array(
		'svn+ssh://svn.wikimedia.org/svnroot/mediawiki' => 'http://svn.wikimedia.org/viewvc/mediawiki',
		'http://svn.wikimedia.org/svnroot/mediawiki' => 'http://svn.wikimedia.org/viewvc/mediawiki',
		# Doesn't work at the time of writing but maybe some day: 
		'https://svn.wikimedia.org/viewvc/mediawiki' => 'http://svn.wikimedia.org/viewvc/mediawiki',
	);

	public function __construct(){
		parent::__construct( 'Version' );
	}

	/**
	 * main()
	 */
	public function execute( $par ) {
		global $wgOut, $wgSpecialVersionShowHooks, $wgContLang;
		
		$this->setHeaders();
		$this->outputHeader();

		$wgOut->addHTML( Xml::openElement( 'div',
			array( 'dir' => $wgContLang->getDir() ) ) );
		$text = 
			$this->getMediaWikiCredits() .
			$this->softwareInformation() .
			$this->getExtensionCredits();
		if ( $wgSpecialVersionShowHooks ) {
			$text .= $this->getWgHooks();
		}
		
		$wgOut->addWikiText( $text );
		$wgOut->addHTML( $this->IPInfo() );
		$wgOut->addHTML( '</div>' );
	}

	/**
	 * Returns wiki text showing the license information.
	 * 
	 * @return string
	 */
	private static function getMediaWikiCredits() {
		$ret = Xml::element( 'h2', array( 'id' => 'mw-version-license' ), wfMsg( 'version-license' ) );

		// This text is always left-to-right.
		$ret .= '<div>';
		$ret .= "__NOTOC__
		" . self::getCopyrightAndAuthorList() . "\n
		" . wfMsg( 'version-license-info' );
		$ret .= '</div>';

		return str_replace( "\t\t", '', $ret ) . "\n";
	}

	/**
	 * Get the "Mediawiki is copyright 2001-20xx by lots of cool guys" text
	 *
	 * @return String
	 */
	public static function getCopyrightAndAuthorList() {
		global $wgLang;

		$authorList = array( 'Magnus Manske', 'Brion Vibber', 'Lee Daniel Crocker',
			'Tim Starling', 'Erik Möller', 'Gabriel Wicke', 'Ævar Arnfjörð Bjarmason',
			'Niklas Laxström', 'Domas Mituzas', 'Rob Church', 'Yuri Astrakhan',
			'Aryeh Gregor', 'Aaron Schulz', 'Andrew Garrett', 'Raimond Spekking',
			'Alexandre Emsenhuber', 'Siebrand Mazeland', 'Chad Horohoe',
			wfMsg( 'version-poweredby-others' )
		);

		return wfMsg( 'version-poweredby-credits', date( 'Y' ),
			$wgLang->listToText( $authorList ) );
	}

	/**
	 * Returns wiki text showing the third party software versions (apache, php, mysql).
	 * 
	 * @return string
	 */
	static function softwareInformation() {
		$dbr = wfGetDB( DB_SLAVE );

		// Put the software in an array of form 'name' => 'version'. All messages should
		// be loaded here, so feel free to use wfMsg*() in the 'name'. Raw HTML or wikimarkup
		// can be used.
		$software = array();
		$software['[http://www.mediawiki.org/ MediaWiki]'] = self::getVersionLinked();
		$software['[http://www.php.net/ PHP]'] = phpversion() . " (" . php_sapi_name() . ")";
		$software[$dbr->getSoftwareLink()] = $dbr->getServerVersion();

		// Allow a hook to add/remove items.
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
	 * Return a string of the MediaWiki version with SVN revision if available.
	 *
	 * @return mixed
	 */
	public static function getVersion( $flags = '' ) {
		global $wgVersion, $IP;
		wfProfileIn( __METHOD__ );

		$info = self::getSvnInfo( $IP );
		if ( !$info ) {
			$version = $wgVersion;
		} elseif( $flags === 'nodb' ) {
			$version = "$wgVersion (r{$info['checkout-rev']})";
		} else {
			$version = $wgVersion . ' ' .
				wfMsg( 
					'version-svn-revision', 
					isset( $info['directory-rev'] ) ? $info['directory-rev'] : '',
					$info['checkout-rev']
				);
		}

		wfProfileOut( __METHOD__ );
		return $version;
	}
	
	/**
	 * Return a wikitext-formatted string of the MediaWiki version with a link to
	 * the SVN revision if available.
	 *
	 * @return mixed
	 */
	public static function getVersionLinked() {
		global $wgVersion, $IP;
		wfProfileIn( __METHOD__ );
		
		$info = self::getSvnInfo( $IP );
		
		if ( isset(  $info['checkout-rev'] ) ) {
			$linkText = wfMsg(
				'version-svn-revision',
				isset( $info['directory-rev'] ) ? $info['directory-rev'] : '',
				$info['checkout-rev']
			);
			
			if ( isset( $info['viewvc-url'] ) ) {
				$version = "$wgVersion [{$info['viewvc-url']} $linkText]";
			} else {
				$version = "$wgVersion $linkText";
			}
		} else {
			$version = $wgVersion;
		}
		
		wfProfileOut( __METHOD__ );
		return $version;
	}

	/**
	 * Returns an array with the base extension types.
	 * Type is stored as array key, the message as array value.
	 * 
	 * TODO: ideally this would return all extension types, including
	 * those added by SpecialVersionExtensionTypes. This is not possible
	 * since this hook is passing along $this though.
	 * 
	 * @since 1.17
	 * 
	 * @return array
	 */
	public static function getExtensionTypes() {
		if ( self::$extensionTypes === false ) {
			self::$extensionTypes = array(
				'specialpage' => wfMsg( 'version-specialpages' ),
				'parserhook' => wfMsg( 'version-parserhooks' ),
				'variable' => wfMsg( 'version-variables' ),
				'media' => wfMsg( 'version-mediahandlers' ),
				'other' => wfMsg( 'version-other' ),
			);
			
			wfRunHooks( 'ExtensionTypes', array( &self::$extensionTypes ) );
		}
		
		return self::$extensionTypes;
	}
	
	/**
	 * Returns the internationalized name for an extension type.
	 * 
	 * @since 1.17
	 * 
	 * @param $type String
	 * 
	 * @return string
	 */
	public static function getExtensionTypeName( $type ) {
		$types = self::getExtensionTypes();
		return $types[$type];
	}
	
	/**
	 * Generate wikitext showing extensions name, URL, author and description.
	 *
	 * @return String: Wikitext
	 */
	function getExtensionCredits() {
		global $wgExtensionCredits, $wgExtensionFunctions, $wgParser, $wgSkinExtensionFunctions;

		if ( !count( $wgExtensionCredits ) && !count( $wgExtensionFunctions ) && !count( $wgSkinExtensionFunctions ) ) {
			return '';
		}

		$extensionTypes = self::getExtensionTypes();
		
		/**
		 * @deprecated as of 1.17, use hook ExtensionTypes instead.
		 */
		wfRunHooks( 'SpecialVersionExtensionTypes', array( &$this, &$extensionTypes ) );

		$out = Xml::element( 'h2', array( 'id' => 'mw-version-ext' ), wfMsg( 'version-extensions' ) ) .
			Xml::openElement( 'table', array( 'class' => 'wikitable', 'id' => 'sv-ext' ) );

		// Make sure the 'other' type is set to an array. 
		if ( !array_key_exists( 'other', $wgExtensionCredits ) ) {
			$wgExtensionCredits['other'] = array();
		}
		
		// Find all extensions that do not have a valid type and give them the type 'other'.
		foreach ( $wgExtensionCredits as $type => $extensions ) {
			if ( !array_key_exists( $type, $extensionTypes ) ) {
				$wgExtensionCredits['other'] = array_merge( $wgExtensionCredits['other'], $extensions );
			}
		}
		
		// Loop through the extension categories to display their extensions in the list.
		foreach ( $extensionTypes as $type => $message ) {
			if ( $type != 'other' ) {
				$out .= $this->getExtensionCategory( $type, $message );
			}
		}
		
		// We want the 'other' type to be last in the list.
		$out .= $this->getExtensionCategory( 'other', $extensionTypes['other'] );

		if ( count( $wgExtensionFunctions ) ) {
			$out .= $this->openExtType( wfMsg( 'version-extension-functions' ), 'extension-functions' );
			$out .= '<tr><td colspan="4">' . $this->listToText( $wgExtensionFunctions ) . "</td></tr>\n";
		}

		if ( $cnt = count( $tags = $wgParser->getTags() ) ) {
			for ( $i = 0; $i < $cnt; ++$i )
				$tags[$i] = "&lt;{$tags[$i]}&gt;";
			$out .= $this->openExtType( wfMsg( 'version-parser-extensiontags' ), 'parser-tags' );
			$out .= '<tr><td colspan="4">' . $this->listToText( $tags ). "</td></tr>\n";
		}

		if( $cnt = count( $fhooks = $wgParser->getFunctionHooks() ) ) {
			$out .= $this->openExtType( wfMsg( 'version-parser-function-hooks' ), 'parser-function-hooks' );
			$out .= '<tr><td colspan="4">' . $this->listToText( $fhooks ) . "</td></tr>\n";
		}

		if ( count( $wgSkinExtensionFunctions ) ) {
			$out .= $this->openExtType( wfMsg( 'version-skin-extension-functions' ), 'skin-extension-functions' );
			$out .= '<tr><td colspan="4">' . $this->listToText( $wgSkinExtensionFunctions ) . "</td></tr>\n";
		}
		
		$out .= Xml::closeElement( 'table' );
		
		return $out;
	}
	
	/**
	 * Creates and returns the HTML for a single extension category.
	 * 
	 * @since 1.17
	 * 
	 * @param $type String
	 * @param $message String
	 * 
	 * @return string
	 */
	protected function getExtensionCategory( $type, $message ) {
		global $wgExtensionCredits; 
		
		$out = '';
		
		if ( array_key_exists( $type, $wgExtensionCredits ) && count( $wgExtensionCredits[$type] ) > 0 ) {
			$out .= $this->openExtType( $message, 'credits-' . $type );

			usort( $wgExtensionCredits[$type], array( $this, 'compare' ) );

			foreach ( $wgExtensionCredits[$type] as $extension ) {
				$out .= $this->getCreditsForExtension( $extension );
			}
		}

		return $out;
	}	

	/**
	 * Callback to sort extensions by type.
	 */
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

	/**
	 * Creates and formats the creidts for a single extension and returns this.
	 * 
	 * @param $extension Array
	 * 
	 * @return string
	 */
	function getCreditsForExtension( array $extension ) {
		$name = isset( $extension['name'] ) ? $extension['name'] : '[no name]';
		
		if ( isset( $extension['path'] ) ) {
			$svnInfo = self::getSvnInfo( dirname($extension['path']) );
			$directoryRev = isset( $svnInfo['directory-rev'] ) ? $svnInfo['directory-rev'] : null;
			$checkoutRev = isset( $svnInfo['checkout-rev'] ) ? $svnInfo['checkout-rev'] : null;
			$viewvcUrl = isset( $svnInfo['viewvc-url'] ) ? $svnInfo['viewvc-url'] : null;
		} else {
			$directoryRev = null;
			$checkoutRev = null;
			$viewvcUrl = null;
		}

		# Make main link (or just the name if there is no URL).
		if ( isset( $extension['url'] ) ) {
			$mainLink = "[{$extension['url']} $name]";
		} else {
			$mainLink = $name;
		}
		
		if ( isset( $extension['version'] ) ) {
			$versionText = '<span class="mw-version-ext-version">' . 
				wfMsg( 'version-version', $extension['version'] ) . 
				'</span>';
		} else {
			$versionText = '';
		}

		# Make subversion text/link.
		if ( $checkoutRev ) {
			$svnText = wfMsg( 'version-svn-revision', $directoryRev, $checkoutRev );
			$svnText = isset( $viewvcUrl ) ? "[$viewvcUrl $svnText]" : $svnText;
		} else {
			$svnText = false;
		}

		# Make description text.
		$description = isset ( $extension['description'] ) ? $extension['description'] : '';
		
		if( isset ( $extension['descriptionmsg'] ) ) {
			# Look for a localized description.
			$descriptionMsg = $extension['descriptionmsg'];
			
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

		if ( $svnText !== false ) {
			$extNameVer = "<tr>
				<td><em>$mainLink $versionText</em></td>
				<td><em>$svnText</em></td>";
		} else {
			$extNameVer = "<tr>
				<td colspan=\"2\"><em>$mainLink $versionText</em></td>";
		}
		
		$author = isset ( $extension['author'] ) ? $extension['author'] : array();
		$extDescAuthor = "<td>$description</td>
			<td>" . $this->listToText( (array)$author, false ) . "</td>
			</tr>\n";
		
		return $extNameVer . $extDescAuthor;
	}

	/**
	 * Generate wikitext showing hooks in $wgHooks.
	 *
	 * @return String: wikitext
	 */
	private function getWgHooks() {
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

	private function openExtType( $text, $name = null ) {
		$opt = array( 'colspan' => 4 );
		$out = '';

		if( $this->firstExtOpened ) {
			// Insert a spacing line
			$out .= '<tr class="sv-space">' . Html::element( 'td', $opt ) . "</tr>\n";
		}
		$this->firstExtOpened = true;
		
		if( $name ) {
			$opt['id'] = "sv-$name";
		}

		$out .= "<tr>" . Xml::element( 'th', $opt, $text ) . "</tr>\n";
		
		return $out;
	}

	/**
	 * Get information about client's IP address.
	 *
	 * @return String: HTML fragment
	 */
	private function IPInfo() {
		$ip =  str_replace( '--', ' - ', htmlspecialchars( wfGetIP() ) );
		return "<!-- visited from $ip -->\n" .
			"<span style='display:none'>visited from $ip</span>";
	}

	/**
	 * Convert an array of items into a list for display.
	 *
	 * @param $list Array of elements to display
	 * @param $sort Boolean: whether to sort the items in $list
	 * 
	 * @return String
	 */
	function listToText( $list, $sort = true ) {
		$cnt = count( $list );

		if ( $cnt == 1 ) {
			// Enforce always returning a string
			return (string)self::arrayToString( $list[0] );
		} elseif ( $cnt == 0 ) {
			return '';
		} else {
			global $wgLang;
			if ( $sort ) {
				sort( $list );
			}
			return $wgLang->listToText( array_map( array( __CLASS__, 'arrayToString' ), $list ) );
		}
	}

	/**
	 * Convert an array or object to a string for display.
	 *
	 * @param $list Mixed: will convert an array to string if given and return
	 *              the paramater unaltered otherwise
	 *              
	 * @return Mixed
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
	 * Get an associative array of information about a given path, from its .svn 
	 * subdirectory. Returns false on error, such as if the directory was not 
	 * checked out with subversion.
	 *
	 * Returned keys are:
	 *    Required:
	 *        checkout-rev          The revision which was checked out
	 *    Optional:
	 *        directory-rev         The revision when the directory was last modified
	 *        url                   The subversion URL of the directory
	 *        repo-url              The base URL of the repository
	 *        viewvc-url            A ViewVC URL pointing to the checked-out revision
	 */
	public static function getSvnInfo( $dir ) {
		// http://svnbook.red-bean.com/nightly/en/svn.developer.insidewc.html
		$entries = $dir . '/.svn/entries';

		if( !file_exists( $entries ) ) {
			return false;
		}

		$lines = file( $entries );
		if ( !count( $lines ) ) {
			return false;
		}

		// check if file is xml (subversion release <= 1.3) or not (subversion release = 1.4)
		if( preg_match( '/^<\?xml/', $lines[0] ) ) {
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
							return array( 'checkout-rev' => intval( $entry['revision'] ) );
						}
					}
				}
			}
			
			return false;
		}

		// Subversion is release 1.4 or above.
		if ( count( $lines ) < 11 ) {
			return false;
		}
		
		$info = array(
			'checkout-rev' => intval( trim( $lines[3] ) ),
			'url' => trim( $lines[4] ),
			'repo-url' => trim( $lines[5] ),
			'directory-rev' => intval( trim( $lines[10] ) )
		);
		
		if ( isset( self::$viewvcUrls[$info['repo-url']] ) ) {
			$viewvc = str_replace( 
				$info['repo-url'], 
				self::$viewvcUrls[$info['repo-url']],
				$info['url']
			);
			
			$viewvc .= '/?pathrev=';
			$viewvc .= urlencode( $info['checkout-rev'] );
			$info['viewvc-url'] = $viewvc;
		}
		
		return $info;
	}

	/**
	 * Retrieve the revision number of a Subversion working directory.
	 *
	 * @param $dir String: directory of the svn checkout
	 * 
	 * @return Integer: revision number as int
	 */
	public static function getSvnRevision( $dir ) {
		$info = self::getSvnInfo( $dir );
		
		if ( $info === false ) {
			return false;
		} elseif ( isset( $info['checkout-rev'] ) ) {
			return $info['checkout-rev'];
		} else {
			return false;
		}
	}

}
