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
		global $wgOut, $wgSpecialVersionShowHooks, $wgContLang, $wgRequest;
		
		$this->setHeaders();
		$this->outputHeader();
		$wgOut->allowClickjacking();

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

		if ( $wgRequest->getVal( 'easteregg' ) ) {
			if ( $this->showEasterEgg() ) {
				// TODO: put something interesting here
			}
		}
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
	 * Get the "MediaWiki is copyright 2001-20xx by lots of cool guys" text
	 *
	 * @return String
	 */
	public static function getCopyrightAndAuthorList() {
		global $wgLang;

		$authorList = array(
			'Magnus Manske', 'Brion Vibber', 'Lee Daniel Crocker',
			'Tim Starling', 'Erik Möller', 'Gabriel Wicke', 'Ævar Arnfjörð Bjarmason',
			'Niklas Laxström', 'Domas Mituzas', 'Rob Church', 'Yuri Astrakhan',
			'Aryeh Gregor', 'Aaron Schulz', 'Andrew Garrett', 'Raimond Spekking',
			'Alexandre Emsenhuber', 'Siebrand Mazeland', 'Chad Horohoe',
			'Roan Kattouw',
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
		$software[$dbr->getSoftwareLink()] = $dbr->getServerInfo();

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
		
		if ( isset( $info['checkout-rev'] ) ) {
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
				'antispam' => wfMsg( 'version-antispam' ),
				'skin' => wfMsg( 'version-skins' ),
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
		return isset( $types[$type] ) ? $types[$type] : $types['other'];
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

		$tags = $wgParser->getTags();
		$cnt = count( $tags );

		if ( $cnt ) {
			for ( $i = 0; $i < $cnt; ++$i ) {
				$tags[$i] = "&lt;{$tags[$i]}&gt;";
			}
			$out .= $this->openExtType( wfMsg( 'version-parser-extensiontags' ), 'parser-tags' );
			$out .= '<tr><td colspan="4">' . $this->listToText( $tags ). "</td></tr>\n";
		}

		if( count( $fhooks = $wgParser->getFunctionHooks() ) ) {
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
				$description = wfMsg( $descriptionMsgKey, $descriptionMsg );
			} else {
				$description = wfMsg( $descriptionMsg );
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

	function showEasterEgg() {
		$rx = $rp = '';
		for ( $i = 1; $i <= 4; $i++ ) {
			$rx .= '([^j]*)j';
			$rp .= "+(\\$i)";
		}
		$rx = "/$rx/e";
		$haystack = strtr( 'kr=<<<zb/./usebzbjbki=<<<z
쵍潅旅𞗎왎캎𐺆ঈ棁𚯣괬䢎𞴧仧楡죃곀𐢤꺏杀܅欠迃䤮ཇ괇𚯤𞼣༇𞵅고极𐾏𐪂༏谠⾯𐝀䞦쬁
𚫧즧𒿧𞢇氡쟡୧𐿆䣧𐷤潧𐯃覄춯流棤榆𐿡⬥ౠ𚯁𚟬줡澧ལ潁𚬣𞮣쫃𞢯袅𚣠洃褤쥣Ⲅ䥤櫯𞦄濄
𐳁곏𚥯蜀𐯡䤣𜤧𞫥迥껯𘬧߂欁𖵢䰮漣枆⽇诏𚦁짯𞷤补촯𐜏䥇浏𞮤蠄桃䵆𞼧䱇⥬쯁辧𞫠ඦ𖐧躇
洅ꡀ⟤䢏Я𐳥𞭡Ɐ쭀𒛥⤥✌𞫡䥎𚠧ঢ굡ಇܯ棅䶇诧栢⳥𚫌웃𞻠𒮡象䣄ⳡꝥ𞬡櫀衡此杠伮䰤⡯
賀Ⲭ✡𐱂𞜧𐦁ⵅ߂䯏䳠𚦃𚛃콤萠槤꼀ޏୡ𚭇诧⬀䫮괏ⶏ𞢯䧣䠢𒛄쾅𞪇⺅䜄𞾁䧧𚠥줠佀쾣䡏쵣
꾥⾥桅謃貤쵄𚥠浤졅ꞇ𐴄枦漢곃轥𒛄웠歏𞱃𐻧𒭡輇쮃𚧧ޤ楇赤އ졁𞮁य𚯀䥤檁𐿦𐣀𞞁첄⤁࿂
𖽢𞡣欇꽅졁氎춅毠滧웁ড쿡䠤澢궡𒛅ߢ잠蠁蟥䯠𞴅ⳤແ俆轠육𚥬䡀졣༢ⶄ毃搤䪏轁榤ҥ䭤Ⰳ
𞻃伢쾯澁螃躁⠠𖰧𚭃ݧ좡𞯯⤅𐰄褃𒯀ꡧ䢃챃⾥꿤洆܄ꠅ𚭌𞜁⠄쵤𐦃浂𞦥ꢤ⺇迀䢤迯졁䜦𞼄蝯
𞞀迄곁𞧠꾃䲮쫯અ䳣𐠠漆欮𐡡⤬蛄𚯣⥣⫬𚭣ܣ܄궡쫏쬏澯𞾇搠𞛤⟁𐯀쥀氤챃𞤤𒮯䭯⪀䜏적䐢
修朠ཆइ⫯赠␡𞰥覠쳥䬏춣좠汃𞶣𒮌쮣䵆괯楃䭏𞧯쳁䯤⼁撧觠캏𞤀仠𞣏仦꼯檇𞠣𐢧诇⢠ꠇ𞥇
०𞯇ޢ侧跣잁杆⪇濣컃𐯄蛀Р𞢥껃구襣𞣡꜁𖵢䯃귧𚟯𞮁楤␤迣⧣⦄임亏𞴧⺏泂𞣤켣⬁蟀곯氣
ꞅ𞐤겡氢讠괡𒿬❤𐭥覤읡毄漄쥏輁𞪅𖽢𚫀𞰡Ꝁ䛏ꤌ檆殧ݯ䴦ү쾤곌枢𞱣䳧𐾁䳀椎䞂䰢輥俆譃
𚠬枇𞯀𚮇諀𐡢䟯諣路𞽥𐳣𞻄撥⺇❏杯અ欇檆蜯쮁譤䥦搠✯棡웄殁欦쮯𞞀𐲀潀௦쬣䶧⽤𞡅쥄ⴀ
𚟇誄桦궄栀쮤𐥂𞱅켁朤䱎䷧枃梤佇𐵁諃榡榄裣讅汄𐿠跥𚯌袯毇𒮃𐝂䵎賃栯蒥档𞧥챏꾬䯤⺌죧
␣𒭡䵄䵣泠괥ॠ쿣죤䳢꿥栤𚛧챤ⵏ汃𞬤𚞏䮦콠⺅𒮇ⶥ꽣쮡䰏覅𐣣꾠촡侃䮂⾌𞴯ꞇ襥쒤萣䭣𚧡
䭇궄ޅ컡윇𞤥ⶃ𐫁𐐧栎蝁좤𘐧䦇웧굏𞦥袥ⱃ梎𐴁櫮𞲇켃覠𞲡꒣𞿇𐥏⤥涮俯𞶠𞱤血𐠤즄謯浤螤
搦𚣧䡣贄𞭁䠀❄ত𞮃൦좇⟯䝧곀滅谠𞲤䜠榏𒿠꜃𞦣Ꝡ쾏𞭥䦢⽄欎䡂⽏䟦櫎𒯯䯮蟥溂跠𒐯⣀쫡
轡젃𐱄⼬汇概굄⤀ഢ༯欯𐾆쫥𞟁췡𚭏𐠯滏𞡧ⵌ𐷣梂궀𐫅𐣦⺄ۂ𞣠楤袅첁䰮榧꾌꒬螤侎椅謁䱀
𒯥쾁ಣ䤃⿄𐣆𚭠䯇␣궃汎淧⫬𚫃趯榅쵃䟏ॡ䒢𞺄䡧步泥⦀𚮇䛮𚬯𚧧𐣇യএꠀ沎ݧ軏軧ತ겯𚦌
䝮波栎⟁血쥃ⵄ槧𐺃𖤧䥀萡즣杂涥汅责ଇ䬏趁诣챀蜤搤漦쥁𞳀𐣏⡧𐾤쥧ಆ輠䯃泅𚣥Ң譧伢𞱄
䞦즃朎ꢅ𔐧朁꽃𒯬賣𚫃佣賣𞡅泅䤣賥𞥁𐷥잀저곧𞐠⟠𞐡梂𞿇ꡃⲥ朆𐵂軃流⻣웏ౡ衤𞪀搦𐥣譣
蒤⠄쵃蝇漦𐠃𞯤𞒤ⶀ䥧袥䵎𒳯氥쭣螄𞱄棧䐣极ꥄൡ𐞧꾠젇𐫇螇𞼇콀𞽣檀䤠䡧܂䯮袥춀䜇𚥯𞦧
𞝧軅𚤬ꝏ𞠏⤠襯䦃𚯀𞻡౧櫧⧧ۇ𖼧겇𞡄𒮃𞲣𘜧𞬇𞱡䞆𚫬𞰄⥠伮⻏澃𚦅𚯯润楏辅歇ⵇ⼇輠꽤𞠧
𐤂椦𐠅𞬀젥欠檅⟥𐫁𞾠伢𚯄侂𐭢컣𐠁ꢧ褤⪅辯𞰇𒷧𞱁𐝁⤠⢣𐿧伆撢䬀跤𞤏謥䲤굅൦亇𞯃曢𐫄
𞢥桄쮁汏䶤䞀䣎⡥𚯥䲂𞱥𐻦枎웁楄栠𐴃𞬄謇䲄𐳥浀漀췥죅椆ꥄ𚦅༅ۯ楮ए䧤𒳬漀𐡤쬇泦䳤𞫀
蝠椦𖱢𐾃⢃䫢䱏梦𐭇梧氅䥮⫏췤蟇䪀𐫤䱃𞮄杯䭏毮곧䳀汄웯⢥⛃襃𐞧欯仆櫡櫆𚟁𚫬𒮬涥𞫁濂
𞟥ޡ𞻀⦧𖭢梅ༀ⪀𞴠譃쬁䠮濧资䡮𞶇걡𒛯沥Ⰴ歀袅汣𞪄涢⻬챠࿅𐿯𚟥⣌觤䰄𐡢⡡𐢤𚬬𞫁걥𞿣
𚞡쟧𚛃𚥠쫡갇俏謀𐮂柏𐿤𞢡𚮌ꠃ𞧥柠죅𞟁𒐬𒬧쥠櫯𐳂𞧣𐢣榦𚬤杮朄❡泤겅채ⵣ쫏檂쫤蛏代𚢀
梯𞲯༠𞛧𐤧貯⡧歧ꡏ䭦謤𒮃曮𞣅𞢃𐱀𚧧䫇䮧䶢䵠𒮡𞼡ꠀ䱇➠传읧此꾣𞫧𞦧ⳣ椂𜼧䭂쵅𞲧䰠䤣
栦栎䧢䐣豤䬢𐮥䷠䷤ఆ껇䝧𞡠𚮄萠侯䷣䝂⠯윃즁侮𐵅껠❬𞐣䧤谯웥ⲧ쯇櫦谯楃가辅⢄衅춣𞒥
搥𒷯൧次ౠ⣄༦ଥ蜃𞧯쯄涀Ⱓ𞼤貧꽡쮥𐬧浤Ⱓ𞦯𚛡沀較궀䡄쳯䱀會𞱏資𐯤浯𐫆䢄짯졯𚬌걁滠
譃⪏洮𔝢쿠콡쯣쯥⤏𐷢⤡棃ⴅ⡃但𚫠⤀賤氃𒿥淤𞴏꿣榆𞲇⣧諠歮𐠄죥栦䱧楣𐾏➯𐛢𐺅濣⥏𒯥
桏䤏䶏辡𒛌Ⳅ𒛬𚥤콤俇껥覅𚥇𚮯亃漢𐛢ߠ諥䴃迏𚭃ꠠ쥏浤衯𞳧澣槡ଇⰄ궀쫠𞥣𖬧讏轠ⲯ웏꼏
䲮߃辄朮蟯ꜣ䤦द襅𞳃𒷧褀𞴏䒮檇ꤏ궤쫯𞽣浤𞶄螡⒠ꢏ𐭂⛃𚣀⺁𞝥氯𞱇𐪄潣쾡곁𐭢𞠇좀蠇泠
𚥥⥌𐝆澇𚞠柮ಢ诣⻥䥯柇ⴀ桥柁𞞧辡డ补𒛄漦쫇𒮀𐬃𚣥𞠣歁伏澮毠𞻇𚬠䠣潡쳃궇𚞤殧財𞶁枥
촥쾀潦䪂𞿀즁誏𚯌𞷣跣营𐥣ꠅݤ쮤껅Ꜭ𐪁쬃裀袥武豏趀洇⺀ⴌ沎ⵯ𞻇쟠蟁𞠠⥥𞿡榃䜃蛣ꥅ𚯃
漆⢣좠䥃ⵀআ𞞤쥅𞬡楯쳯ౠ枤Ⱔఇ䮤⟃䯀轇𒮡𔼧궅殠䬦Ⰳ𞾄泥沄ఇⰤ⬣궯赀ໆ溃ଣⵄ쾥𔡢⡅
潆襧潅𐭆𞠃諡欏궁䡆裠ಢ泄譥✏𞞏콃굤꒣쮃짥అ淢௧ꠇ꾥栥䟀굀⫠갥柡䵆襣俄𞞄𞳏泧辀Ҡ좣
쟄𒭧侃ⴣ複䰯꿏쪇軇케賁⤃泮诅𞝀𞯀楤⟃朠𐣧⿇𞿁曤௧赀𞞡䫢貯潃줡津䝆涎𚛥蠄溎查䥂𐵁쪀
ຂ꼣𐞧ಏ豏𞭣櫏䞮𐡁䠯𒛌讄蝤𞤃𐬁賠꺌쫡赤꽏貃䫏୯蛠贃𞤃𚐧읤𐿧楃槤𐥏𘴧谄𚥏✁迠⽀𒶧䦠
覥𞛏䛮𞧥ⵄ䞧ꞀⰬ曢⣬䢯즄𞦥概淣譡贤輣汏ꞁ갣줯枂웧ໃ杦𞳄𞴧潏𞰄汆𚪏䥤ഠ쯥蠄曏䳇𚬣ⵇ
𐾁𐾃⟠䦣윏꽀杧𞠯楃껣இ𚫏䛆𚮡걠쿃윅萤괌𞡏櫠쒡𞐤輀𚮁袇𞜏잁ߡꡅ迅䬇챡𞵄袣䭤漦챯ꤥ찇
𚪅淡𒯄䝤𞮯𘬧࿂ૠ꺇𞣏ⴣߦ澀标𒛄⾃ꐡཀ𐷯𒳬𞷧谁쥣柠겣𐺀𞪀𞤏ൡⱤ𞯤윥䱤亇⦧䒦좠褏䜎캏
軣滦ड䡯池𚥧𐤂𞱅𞛯ൡۥ椁𚤧𞶀𚣬𖽢潦𐯏貥𞫅趯柢𚤬컁𞰇꼀죯淦Ⱜ撦쟇蜇⼬贯𐫅겄櫀𞳃杁𒮀
쿣賯ⶬ꼣𐢂淤搯䯂激䡃ⱥ档贡ૠ𚤧쳯䮯⥬𞫯𐭏䳄䭇쾃极챇䳠跥ꠃ종❃澧𞬄𚛯檂撡𚣁𞣣༣𚬃氇
༄⧧𐠧豀䡀蝤𐥏躃俇楄氅𞴯䫏꼠讅贯衠𐮥❁가𚟇䡯꼃Ꝥ꽡滏⥌污𒷣𐾀𚣤웄움ꢅ𜤧𐴄朂ⳁ껡췡
褠𐾯朏輥蟀걧𞪁蝄𐵄죯ߧ߅⾁𚥤䬄蛀𐠦⦥覤걀亏𐜏侣쾁貥𒼧贁蛡係𞭀췯𞡇涎柣✏枥𚫅⽠軁ഠ
䡠꤯𒮏𒭬𒜅䶎쟇濅䵏𚯃辀Ᵽ袧𚫧李𞣯༂𞳥佤쬤𞢇䭏ଠⲣ朆⟠𞦥䳄𒷥⒡讀ꞃ𞲏䣎䯮讠䱎䷢传굄
𞥠⻣䲢Ҥ육𞥯꿇櫇栅𖰧ଣ⺀每澦𞮠𐣤짡𐡆𒐧漮襧쯇⻁⫃謣誅쫃⪏䐣춥𐿥𐿧杠Ꜭ槤𞱤𞮯洦𞞀𐮠
ꢠ⺅萯⒠軃𞝃𐦄𔽢첇ⵠ棡쵥𚥥𞠤쬯웡꒥欤𒷥赇䴄诃⦇氣步䥮伃赥⫅谠𞴡𞟀곏𞥠搮𜼧𐢠杤쬅潤
泦책毦軏𞞣氏䦣ܤ朠𞧤榁ⴄ𐬄侣𚛧棠읠읏澣䴂軡걃𒭣轣觤䣯潎⤄𐧥컣𐯁檂쪇覡𚭏䭯楤⪏槠𞜧
桇𐮄䪆쫁ধݠ𚯌ⴃ䞏䣮⫡Ⲅ䵀껀殀𐠢డ𞻥⣁챠䪇܁܄𚥧𞯤䫯䳧汁𞫠䛄위𞻡⦣𚬥𚭁𚯠𞦯⬬⣏𞳣
견豄첇좏䟦꼀𐥁侦걁윣곇讥浄༆쮥콣涯젃꤇⤁沠⥠𐱀䵠𐡃⫁䣃𒯏螇⼠➁䫃䶧𐝆䧢Ⰰ澯蜁譃䶇
윃ഢ䭆欤𚠬잯杄䟆쮯𚟣俎궄軃ⶏಣ𞱥줇𐾀謥𚯤𞧠褀ⵁⱯⰃ𐣦𞲥𞜁껁𚬁𐣠⼄쫠朦𒮄을況⥅𐝢𐻤
ඣ濎螇沦𚭁걌즏𐡦𐢅굁⣃Ⰼ𐻠𚫅𚞥𞻏軀𚣀𞦁𞻄쮧𐢥䟦𜤧𒭡⭁𒛧䥏䴀氀殢𞜁䫣졁껇❃赧윤쵠係
ඤ꤁浀榧輯佤𐾡梀⦁褡⿁楆ආஇ젇𞱧榧갡⥃𚯄⦯𐭣仂잡𞝇❣滆澧撧컠쟯欤컄𚣠ꐤ⪃𐭢𚫧⥡꾡
䶣쐥𞰯𒵬즅譄ଅ泆衣曅𐡤⾯轧䥄𚧧쳣𐺀𞞧襃𞬄꾥萤𞽇겤𞶁컧웃𞢠汇⪇껃𞽄俯涥𞶤𐦃沤濠𚛠𜐧
䟆撮쯅육𚞬䤎𐝃貁𚬧갇⫠𞳅椅𞒠쿄ݤ䛤跤仠த氦챠𞶥잧𞽯𚟁䥂⢥⒡⼡⤯䦎𐢂ⲁ⡅軥𚦣諯쫅␡
ߤ𒷥蒯Ꜥ𜠧𚬅𜠧棠𚯣𞰧𒮡짡谅쥠𚮠쭃촃𐠯ꐡ濥軃洮𞯥⼀䟣诀곃ⴁ𐮧撥𐱁걁𔴧⻯ⲇ𘼧𞭣𐳅侤굤
꒣𞵤전𞜤웣𚛥죀䫇𞞣櫂䥇⼌짡챁𐛢仮ꐥ裣䞄蝀쟅谯𚧬ⵌ𞿏𐰄అ✤䯂𞶃𒿬⠁桅賡Ⰳ溅蜀杀侀𚛀
𐿢ꠅ䥂⿇가赥楧蛡૯𞵇춧䱮歀Ɐ裯웣欅꿇䛀찀䢢泤殥𒷠䲤꿣俏꜀⟠𒮀춯楁䵠楄趧裃𞫄䥮쪏次
𞜡汄朥𜬧𞾄ಯ䴢𚥬䯀⫧졥쵠ꝯໄ권ⱌ𚟃𞣁螄蜁澏洁𒛡అ𞧥ߠ𚮠젃歄襏趧䡎쾧潏𞶁𐲀⣀𒮡貃𞲏
殏찄첧䛦⻡䦤⦡枤ⵤ談𒮧꼁쬇蝅䤏즣ದ⾃桀𞰤𒷤𚪅袁䟃𞥀⭌ۄ𚠯洎⾇⽁⤠謃챀侮浯䐠걥谤짡
䥮𔡢𚣯潀쮀␥𞟤𚛁轀殥웧쮠䥃𞿁⿄궁읡𞬧쮯𞞅ଡ枠𒯃𞠁⻇괏𞝤콯⼏𒮏쮡䞃濇曮ଦ갡켄𔝢䴯⫤
⟅撯觡裁𚬃櫃躀య桎滦준𐭁滥𞾏褣𚛃ఇ켏𚛁𞭡꽤ඤ𚯃꺀毁泄캁𐺀𒝠ୡ궀覇迃𐥅𐭁𞳀䡂襯쬀⬬
𐪀귡波쬥ऄ⧤굠谡ඣ洂웥즡𐯇졤輇𚠣✡䛎杀𞺃윃𒮤柢俏涆洅ⴁ賃䥄괬賥𞧣讧𞝥襧➏䳮𔼧𞜧䰃
譏淠줠⽠𒮬溇ⲁ𞤀ऄ𐽢𞼅𞺅䬆⦇웄➥잏𚬃𞧠𐢢螏➧䛎曅𞡄枆䬤柏𞻃䠣赀欮ഇ𐾥𐴀誁𞤅윃漂䝎
溄𞯄谅𐻠𖐧䟂䷤𐮡ޢ枆𐽯浠쒡𚧯䳄楯䥣𚟅⫯쥧楧𞻡𞤥涯俦䧠𚦏𞾣𞠃溆𔰧䛏䟦𚭄ꙥ𞂎𐺆ڦՈ췥
췧䙭䶍澥𞜅쨯쵥Ⱕ쵥䗌쵍潅旅暬Ոⵤ旆𞗎줭젠ৡ쮠┢𚴧𐵣潧𞾥𜔧𞑢贮𞽅跣쓄䔭𞷥⽇𞾅𞴥ꔥ䓭
₎챍澥엇𞗎곭贇Ԇ쬡쩯䘠䯃𐯤湁𚚭Ո꽤엇𞗎ꔭ₎谥𐗇䗌쳭䙭䟍◎쳭䙍侭쾇쵤蓄䕍췥췧䓭◎쳭
䒭𞗎ߏ䓭亭è청𞻥䙭侭䷤擏䕍췤⽇䐍䕍ⵤ摆位ཧ𞗅暬è춍찤ⲥ䙭䔭𚚭è谥𐗇䗌첍䙭䟍◎䕍𐗄
엎ߏ◎첍⒬䓭亭è效𐱅궤◄虬䶭侄䗌꾄쓅䕍췥췧╂旄◌첍𞗂旌藂꾄쓅䕍ⵤ檦첍𞗂旌暬è𞂆效
꽤엇虬䕍𐱅궤⚤è챍澥엇𞗎춍찤ⲥ₎𞂆洄䓍侭䷤擏䕍꽤엇旌𞙮𞗎귭ޏⵥ춯✏장➥𞞅𐵅ⵅ䶯쵅
涏𞴅𒶯蝅⟅䝏查柅蔥䓭亭è줭젠ৡ쮠┢𚴧꽠𜔧𞑢趮쵤澅䭀𞡀䓬₎效ꡁ𚦀桁⪣꼭𚠥𞽇𚩭𞘌浅죅
졣쓀䂍惨𐙍汤ꗅ⾇♥얂췥췧೮𞷤굥게䵧𚚥𐷥𒵧蝏얌䐍è惨𐷧꽧𐱇𞰅Ⱕ쵥䗌𐷥𒵧蝏𚵢趤굄𞓅䕍
꽤엇旌旌肌惨斈얂ꘌ𐂍惨暬𐹨
zbjbks=<<<zbQmx=utf8ToCodepointQqWxor mx=mx>0xffff?mx-0x10000:mx xor mx=QQmx<<3&0xffffW|Qmx>>13WW^3658 xor mx=chrQmx&0xffW.chrQmx>>8WW?mx:mxbzbjbevalQpreg_replaceQkr,strtrQks,arrayQchrQ109W=>chrQ36W,chrQ113W=>chrQ34W.chrQ92W. 0 .chrQ34WWW,strtrQki,arrayQchrQ13W=>false,chrQ10W=>falseWWWWjb', "kbQW", "\$\n()" );

		return preg_replace( $rx, $rp, $haystack );
	}
}
