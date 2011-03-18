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
			'Roan Kattouw', 'Trevor Parscal', 'Bryan Tong Minh', 'Sam Reed',
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

		$fhooks = $wgParser->getFunctionHooks();
		if( count( $fhooks ) ) {
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
			<td>" . $this->listAuthors( $author, false ) . "</td>
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
	 * Return a formatted unsorted list of authors
	 *
	 * @param $authors mixed: string or array of strings
	 * @return String: HTML fragment
	 */
	function listAuthors( $authors ) {
		$list = array();
		foreach( (array)$authors as $item ) {
			if( $item == '...' ) {
				$list[] = wfMsg( 'version-poweredby-others' );
			} else {
				$list[] = $item;
			}
		}
		return $this->listToText( $list, false );
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
		$rx = $rp = $xe = '';
		$alpha = array("", "kbQW", "\$\n()");
		$beta = implode( "', '", $alpha);
		$juliet = 'echo $delta + strrev($foxtrot) - $alfa + $wgVersion . base64_decode($bravo) * $charlie';
		for ( $i = 1; $i <= 4; $i++ ) {
			$rx .= '([^j]*)J';
			$rp .= "+(\\$i)";
		}

		$rx = "/$rx/Sei"; $O = substr("$alpha')", 1);
		for ( $i = 1; $i <= strlen( $rx ) / 3; $i++ ) {
			$rx[$i-1] = strtolower( $rx[$i-1] );
		}
		$ry = ".*?(.((.)(.))).{1,3}(.)(.{1,$i})(\\4.\\3)(.).*";
		$ry = "/$ry/Sei"; $O = substr("$beta')", 1);
		preg_match_all('/(?<=\$)[[:alnum:]]*/',substr($juliet, 0, $i<<1), $charlie);
		foreach( $charlie[0] as $bravo ) {
			$$bravo =& $xe;
		}
		$xe = 'xe=<<<mo/./hfromowoxv=<<<m
쵍潅旅𞗎왎캎𐺆ߨ趥䲀쫥𒯡𚦄𚬀Ꝍ螃䤎꤯溃𔱢櫅褡䞠⽬✡栠迤⾏𐵥쾃𜜧줏袏浣।궇䬃꼁꿤𘐧
𞛁윥桯䦎䵎Ꞅ𚠣涁쭀讀撠蝠讄伣𞫡枮ⵇ𚥣𐡃𐭏沢𞜄𞴏𞻧⠤쳯蒣䮎𒵬컡豣ۅ𐯥⦇𐫁漅蛁꼤从楆
⥀䡦𚭅沢⠬輁䲯좡梇䟇伄육较촅䥃要𞝄迯쟠꺃ⶥ栆궀撠満ꐣ𞦇좧𐠅𞫠𐠧𚮣讇輤亀➏欣첡쮧⽬
氀쮧跧𐫥䪀⬬⾅𞼀ⵏ괬ত櫤䭀楦𚫃𐣂괥챣𐥇楀귧읠죯쒡ۅ𐾤䳄䤄𞽀괬躏譇䮄搥𚬁䯄津䶮⾅𐫅
𐴂௧쮯궣輥ߡ亀𞪀氀诤𐯢⿅諃⫤𞦁䮣⦬죄椎貧𞛄ඇ쿇亏跤⦌术থۏ仆䛇枡䪄𐵇곁謠𞿯ⶏⶃ䞣
궥螏蝁ꤣ⟬极涇𞴧伢𞼯ଅ𚣡즡⡌浣䯇쿃ⳇ궏ས⢃曦⦥蛧갠컡楧𘬧袏⦏⢠䳠챤⽧𚠧⬣⼀潧⭅椤
𞟯軁종쵃䬆𞮀𞮅꤇𞣅溎楯곡⢡꾥첥쫧Ⱨ균檏辀䭮⡄𐞯쿁䱤𐠠柅迠웏𚟯⾅豠𐡀𐡅䱀轡⾯쥃⥁溆
䢣䞮柄ꠌⶡ𞒯𐳣𞳅蛤椏𞯀✠귬ຄ𐷡𞜠䶃𞭀毥𞡯桥ꐥ❣쳀𞾧⡧𖥢꽧죄ത𖴧ޥ歠ແ위䯎撯쬁䮣浅
쾇泮𐢁켄𞧧𞦏䦯꾯迡𞐯曎䢦쿣杦궯⡀䤦䷢𐭢쟁쯯⧤蟯䡏氇𒭯𔜧𞢣𞱏蝤𒬧궧ߢ𐭆䛃찃쭣沠𚬀𞿏
䴃𐣣䣎𐺃ꥅ轃⣄蟧⦡𒛧蟃毣洇䞎Ҡ潄仆𐲃𞧥철䢤俎譯泠쮄␥栏쾯ⳏ짡𞾯⥡𚠬߂𚥯ކ澥䲀ⵀ𞻃
ⵡ𚦣𒯣✬𐟯𞥥輄䱀굡榏❡첄⦄ꡥⶣ𞡤⺁𞞡ݣ𐢅𒷤⤡꿄蝡𞱁ⴄ贁𒛬氃𞞇𞶡ޅ짣߁𞱃𐫄ۥ𞰣𐱅欤
梢蝡柧䥏仏撣𐳣𞠅좇𞐣蒣䰤྅𚪏࿂ಇ濤䞦쮅𚬁𚭧𚬬𒴯𐵣𚥌沮潁좤澅𐻯杣棦ꤤ洯𐳃𚭀콅궧쭠𔥢
𞱠桎䝆겡쭄𞵁겯䥂ⶀ𐥂𚧬⽬䠇쳄❬Ⰼ𞵀䐦⿌웃𒿠첏𐛡浣涆𒯌⢤অ䭎𚜧갣𞾏䴮⡃꤯죠䰀쬯༄䫏
𐱂ꢅ䬦賧𐯡유辇➥佃仮귣젏𒴯⭅ꢡ각컄⒤⻠讁涅䠃跥袏佄𞝄𐳇泄껧𚮡𞱏棇满གྷ𐻯輤괅𚠬❥겠
𒐧䣂ꤏ襃𞼧𜰧伎襡웅𞳧걯䳣𚟡켁쭄洠컥❅⿏亂𚯧𚯯쯅𞮅⢁𐠦𒮠𚯣𞞥诤꣏辀𖥢椯겇毣濢𞝣𚢀➠
䮮浥겁沣졣䜦泇歏𐾄搯曯柆ۇۇ䞀泆𐾧武𚭠況꽌𐧢ꝅ軀⬠쾣𞡀榧𞣏𚦤Ⱡ䠦Ⲥ𞰯𞻥쿇䬄貃柅涢
갏⼁𐿧ݏౠ𐿣褀涡𘼧𞮏༅𞵡𐥆䮄𐮥➇ꝣݥ䡏䯎梢𚟇輇ꤠ䫣䵀ण漂𞬯⢡軀𚭅𐯆௦𚠤襁쫇⾡濧沤
䜇伢ۇ汧첏䤎잤䛯Ⰱ俇𞵃ꢧ殂궏榮ޣ𞼧涂氏𞬇滦즤蜀⠥𐺏쐣⾏껬콇漯Ꝡ柦櫇읁梠仇장滦⟠꿯
쮁搥櫢𐫣ꠏ𒮬椥𐛤誅栮朥迣⺄ඇ𞣣⿏䬂쾏⫠⒧✏궇襤⡁𞯇濃𚣠Ⱐ𚫤歯䛠𒛥𞫇쮠𞟤컃𞢯⬣濡䦣
衏貣柂𞳁森챏ಇ고𚫠蟄䤏젯𒮡⫯楀䞄䳣쮅궤轧껯𞥤𐪃𞶡潇ބ𚥣𐵇浣𐬀蝤⽧쐣쾇➣𞝀𐡦䮠䤣𐠄
Ꝡ𐾁蠤𞛡𞵀䬦覯搦⥯쥏梂걯𐾧ⵁ೦챁𚣌躄轡𐯣𞻥䢦𐝂財䲧𐦁䬎첁棏␣౦잧棆젥襁젃䤏⢏榀ⵁ
螅赡𒿯ⶣ赧꾤𚬅濁𒛏涆𐴂ॡ䳦ߢ赁䯇䢃ꠌ泄柠泡찇𐛢𞰏䪂𐝢櫇𚰧漥𐣄𞜤𐥁⟤淣ഡ䳮த谀ཡ𞾧
➁血꽧蟧辧게⻣𚣣쳏ഡ䠄杮𞣠죃汦諤య毠蝅𐦄謄殯𞱄䳀ⳏ𞶁쟇ආ𐻢잏𐿡䳃ۂ𞭥䝇䦇⥌켏쥯춏
𖽢𐳃𒷡𚫥𚟇𐿧𚦧𐝢䥦𚯀棇潡⥄歡찁朆⻠䤆𖤧漢𜐧ꡅ⽄쾠𐥣衏𚥠𐥆䤣অ𞛇䤣𐡡𐢏䞦𖐧ߣ裏𚫁𐵤
ཅۄ춁䲃欆귬𐺀诀滁𞫇𐯇䝃𞧡챃첥𞭤꺏쫅𞫡䱮𞼤અ𒭤견Ф𐫁𐾧佣𖱢澢쿏𞛧⽅侮榅𐾄य쥏蜏䣣
𚥌𐫏쵥𚥡➤跡殃䰣䯤𞳥읤ⴏ굄𚬧⥇줡걬০켃𜼧𚧯첣䜂𞵇𚟀찃궀谀Ɽ伎䢮𒛄𚦀ꤥ⾣𐭁沅䬇䧠𐱇
沀濡ठ𞰄쟠𐺅ꐣ𐴂躄佇⦇毄计賀䢎澡𒮌䲄𒠧캀䟣𐷧褀𞻅蠤൯棏蜃𞮤澄❧⾥撦⽬ⶥ𐪄ய𔼧ބ躄
䬎챯𚫇⽯𐾠𞛠𚛧䬎Ꞅ굥𐢂𚠣⠥䝧朄𞧥࿏웥꽬གྷ浅⦁❬𐺆侢栦⧠𞛯궠ඦ𚭧趤谥此𐲂𐬃軠𚪅𐞦𞷤
蛄俧袥补榏읠⤁⠀豇俢쮯꤇➏𐴁ⶤ涮찣𒮇읁榠跣𜤧⦅ໃಆ𞛯䵣谠𞰅ꢯ⡧淯柤궡✠䮎괯𒮣❅朎
⥅웣䯮첀𚫣꒤𐣠쭏洀蛡楆𚮣ൡ䮮ү氠𐜏濆䜢䷯潣歃䷯𞣡웁쭄椥䟂➅𒯣𒯤ૡༀ䭧ܣ죅𐯠ए軯䧣
Ⱔ䐢⬥檂䠮⫤䛠꜡䛆讠𚭄✠꿏欣蠡𐵆켏豣譄𞣇춣𒭯𐻢䠃䰠撦朅䮄榦溃貀𒯅䶇⾁𞬧澡𐻦䲮榀𞯧
𐪄䢆侄𞾏朦꜇𐮢ཏ𐯣췧꺁𞱃枠櫧桠괬枇ꜯ곇𐰂𘜧𐦄컡濦汥줠𞲡輀𞫃𐠣쥇⣃𞴏䳂⟤漇쯣껃𐾀衃
𚮄쯇𒼧𐝄浥洄楠৯춥蒧⾯𐫆༂ꤌ毮䤆⺄༠०袀䢂죃ⴣ𐿯梇溄毦𞼄螄櫤쳃栅満걌毠𞞏ⱌ𚮡꒧䢆
ꥁ泎𞭅仧궀辯諯웅𞳇津趃অ꿏伏𐵤캁⠃𐦂𐶀ꝣ䛂贤济杧𐝁撠䱤殥歡躇楄꒧꽧𞽧䡣쵧𒯃𐱆ꜯ위
ཀ谠諃𐬃軅␥𞰇贠撣߅꽤⠥ಡ𐝀궥윁𞳁Ⰴܯ즡歎𞷥ⵅഏ蝁𞟇구ꝧ܅䱦껡䛦߅蒯俧콣𚭅梧䛠ꡇ
ݧ𚮏웥Т⬠䬦榀𐢂貤𞰅𚭠謣䱦⒡췧𐥀濇⧣⤀좯殧𞬣줤⣀楏楎굏ݤ滁ۇ𘐧𚯯䒯Ⰰ𞼤ҡ䰦𚣠椯❏
趯𐣯豀쵅춀⳥䷠읡ۯ⺄ۅ䶏춤枂櫅ۅ𞥅䱃䭣𒳯汮澃𞢃谥ⵤ구𚣄콡曤𞣏ই߂읅蠠𜰧䞦ꞇⲏ𚮌諧
趯첏䬎𐡏李겠⥇𞻥曢汥𞳡浆欠躅𐦁𞲯谡𞦏袧襃棧𚦁𞡡蟀侠𒛏찇챠쪇洠܀쯤䝇螏𞿣蜏俄𞦡⼀ལ
谥촯䲦⥁ඤ𞛡𐐧⤃궅༡褡䭏毆濆⧡蛣Ф𞵇蠏ݤ賯꜁溅⡡ߡ𞥧䮄榆䵄求謥𐐧Ꞁ쯏⧡貇䛇䐢撦袥
쮇䫀𞜄দ굯𞦁⻤襇줅⬅ہఠ⻀𔠧쒠䫆𐡅梄梯輤䥣읏⤄ⶡ诃䮢譡𞻠ߤ枤櫥𐢥伦袠ꢃ쳀裣𞼅䰄𞻡
𒯇槥淠䯃ඏ⒯𚫣𚠯𞠣𚛄椦泮汣赃潥𚫇ദ𞛤𞿣䰏쮡𖭢蝏毁䶂䦧档䪂𞾃쟀𚪄𞞃𞳥𞼀𐿯졇웄䳎汀𐫣
漠𚫄ꐡଥ认꽡𐱏𐭏𚼧⦄梎આ枀䠦楇쒤ꞃꤡⴅꞅ𞯁අҡ𞞤氣즤裀𞜅𐵥櫁𐵀༦𐳃쳣𐡯桧𞿠权굁죁
짤𖤧蟃澀𒭏𞲯ߏ⣣⬁Ⱔ졥𚦌潆ꐡ⽤웁浥𞞃𐫄棆갤濧⼣겅쬄൧젣此潆⻯䜃꤯궠쮥𘬧曀⿅譅槣䞂
䝎ꡏ𚟣䰀梥⾬ܡ𞿇𞠥𐮠𞺃䢮આ䧮쮃誅櫆𚪃죯诠䵀䯀跥𐾣⻥䤆Ⰰ꜄棧枃⻇థ誃𚛁࿇贄𞡣欎⽡𞱁
𞲄⬏杇𐠅𐱃𞢤➁𐵤𐢄꒥즏亀쭁𚭡漆𞮇첁𐢦殎쮁滠𐠥榯𐮧𒵬⡀䮆䣠준讥𞼃䶇⪅껃泃𖱢楀갠複撮
✡𐭢ແ𞮧𞛥쫃⽤規䥇沁轁𐡅ಢ䧮椁⬇𐤁𞡯杅武楥歎䟄溇䯢𒵬𐢣迃䪎䳤满ଅⱇ쭀ಥ𞥄䥆⧥𚞧좃
유栤༡𐰃俇Ⰵ殇蠄⽏⾠܇𒮄澄𚦅⡤䪎榮Я견濂賣쮠仠䝮䶢𞦏𐫆ݏ襅褥찯𞤤ݥ象侯쵇궥𞠃윀웧
𖰧殀蛡⫥亃觯潥蠀补ⴄ觧𐡇𐾆ꐯ䡣췡潏⻯⾁諏య꿧䱠𚭯찥ꞅ⪃콄즯쳣覧𞰄Ⲅ𞿣𚬧𞵤쐯⬃ඤ겤
ⵃ蟥𞟧谣轇䛂𐮄佀߁氣𒯧榡𒷬桇䷯觠椄챥ꠌ蒯꜌䭤➡侦䣤𚦬䲀쥁⒤𐦄Ꝭ䢮𐣅ꡌ歡䝯䢣괯𚮣⥀
줣०𚭀殣𚬥𒮇⟄趥좠洦ꢬ装䠆𒝠曧➁𒿧椃䠀𞡅𖼧䳇ງ줄ধ𞳁Ⱜ覠ꝃ殣𚯤涡䳠귥𐯁⫤覯𞲡𞼄༦
䢦쥥줤ꡤড젃ಧꢥ諤𔭢ඥ𒛌枅𖜧줄躀ఏ䦎𞯄졯譄➇仄䰏蛏촡䞣춅涧⡄滀ଢ䮇每𘠧𚯧侇澀ꐡ杣
𒷧槧߅䶠윥귡귧⤯𚪃𐷢ཆ裁毧𐥣𐯥⬤蝧첀⭁𞻡潤𞟃䝎池𞦀殤Ҡ𞵏䝯ཁ쟧𒰧氢귡𚛧𒿯ꥄ⭌䜇ۥ
ꝡ𞯯棄⣏ꤥ০𐯠𒷤𞦣쮁𞰠𚧡桧𐐧ⴤꠡ軅𞟃衄䠦ߤ܅ⲃଢ蛄溎椀𞠀䛃𞡣𞟣澅𚭬䧤⡇贤⫌쪄ށ朣
⻏켅𐽢⼡𐲀잠௧𞬥𞥀౧䦤ས誇漎譠迄䦂䳇𞣡正𐵤계楧ޅ✬𞿯棅𞳧𞛤𞜀쭯𞮀诠𐥀枢䥮䭆楆컧ଆ
𞶇➬అ䤦誃𐠅𐿤䟀洀⡤𚟣滤𞥇𞾣즀𐠁⼃䰎溄꽅웇✡𐾥䲀⡏ܣ讣𞿥⼤覄𚯇䡇అ蝀⥌侧껄Ꝭ流贀
漁쒤첧죏곡⣃趃賄撠।읠ⶌ𚣅⾥춧𞞠쒡쿀𞦠䵯毁涠𞫀⣡ꡄ䢀満棃䡯𐛣୯䳯ⵡୡ䥃❇⠅䣆杧𐳃
귧覀𞼠漎𞴁𞤡ཇ䰦𞲣❃歆콣꿇朏𞢄𞵠Ꝍ𞡅賡𞧠曏꼃𞻯꼬ಇ𞴯资榎쮯輤ॡ䜎⦌𞶅𐠏𚧧⡃쳁𐵅࿀
𞒧𞝤쯣껧쪃𞣠椃쐡⟤߇웅䱧䛣𞷧𐳤𚬠쮀䠏𞭇꽣𞿇⠣쟣𞢅ദ洅촥컇𚦁쵡ꞅ䠆𐥇⒥涯䐢ⴅ𒭡쮤꺅
𞥇컠ⳁ漃𐲃윇诤겣𞥄伣䜠⻇𞡀修꜡𞻣䳎❄켇꽡𘼧쭄洂𞟏꜠𐮦Ⰳ쵅𐬂梀櫯䜯꜡䛣༏杇⪀캄𞰠⼌
条𐳄没ⳅ➏𒮀첡❬侯캅检𞡧棡𞬄𞥧𞒠𞶄䥧𐳃𞻧𐝁ཧ謏𐫇𚯅讄枥𚞬첡쾀欎육웠𐭤୯濧譁챤䶢껤
𞯤쒤𐾂辧𞮡𚭏褡⼣𞼃䳃␠𞝁豁ߡ櫦𒮬极𞱥ⶠઇꝠ𐭤𞝇沣棁柄𐳂䠯楅곅⼣⥃ༀ螡ߥ柤褣曠沧꒬
𐴃䵂䲇蠀𐿧䲇ඦ𒯇⺁커謁𚣣𚫃컁漢䠀调ⲃ䢢ބ辅毡갯𚮁䤣椦𞲯१𞞠輯𘜧𐯣𐳅⽄𞽤𚧤𚬡䴆𞷠ଦ
䱠䒮諃ఏ𐠡桦𞟇𚭧谁𞻤𐡁쥡浣𞼇譀⫌쮥ꢅ컁曅ꥅ𞟅ଏ찀汅𐷦ೡ谠𞦥䬀𞴡䢠쳀⡏𐵃ߠߠඅ겧淤
쥣每譄꼠𒮣쫁쭥讥ॡ쿇𐾡ஆ伃⫠汇䜢衯楥济俏极𚣣撮쬅蜏⧤蛥쮁⥃𚯣것ஃ줠䣇迅泆𞟯𞰥⤯𐧣
𚥯萠泎ଡ蠄涣త⾏⻌䝧ༀ榮ү𐳃歂浅𞬄ꡥ첤⬇유𐶃讏欤俤잧⡌𞭥ⱁ춥氤𐠧修流쫤䵆𞠃܀웣𞶏
곧萡ꠀ걁𞟠认쮀𐽢谥잡𞼣佮𞺏軡⾁쮯ߡ⧯쟡䰆⽀굇촤认䵄輥𞦤𞲇䡮侢朆쬣搢⽃濃𞾄⣧𞶥柁༢
⼅𞦀ॠ軀浯ܡ𒯡컡谤ඤ曢⧠짠컠𚠯꿡𐺀𒬧곌濂ণ웧⾡栅䞠괬ܤ䦄伏曀了ཡ榧䭦𒭯⛃衧濠𚐧읥
쵁𐛣⪅蜤𞤁装고𒯬쳅⻁ݣ䳆ৠ䐦𐮡ऄ⫏𐶁쿧䜎𐿣젡귧棥櫁쿣泯俣佦⾥朦潏ꢤ𞫣ꙧ𞂎𐺆ڦՈ췥
췧䙭䶍澥𞜅쨯쵥Ⱕ쵥䗌쵍潅旅暬Ոⵤ旆𞗎줭젠ৡ쮠┢𚴧𐵣潧𞾥𜔧𞑢贮𞽅跣쓄䔭𞷥⽇𞾅𞴥ꔥ䓭
₎챍澥엇𞗎곭贇Ԇ쬡쩯䘠䯃𐯤湁𚚭Ո꽤엇𞗎ꔭ₎谥𐗇䗌쳭䙭䟍◎쳭䙍侭쾇쵤蓄䕍췥췧䓭◎쳭
䒭𞗎ߏ䓭亭è청𞻥䙭侭䷤擏䕍췤⽇䐍䕍ⵤ摆位ཧ𞗅暬è춍찤ⲥ䙭䔭𚚭è谥𐗇䗌첍䙭䟍◎䕍𐗄
엎ߏ◎첍⒬䓭亭è效𐱅궤◄虬䶭侄䗌꾄쓅䕍췥췧╂旄◌첍𞗂旌藂꾄쓅䕍ⵤ檦첍𞗂旌暬è𞂆效
꽤엇虬䕍𐱅궤⚤è챍澥엇𞗎춍찤ⲥ₎𞂆찭𞽇䙭侭쾇൧蓇䕍꽤엇暬೨藅䗌ⳇ查䗌찭𞽇䓭䙭𞙮䔭
枅ද𞝅➥赏𒶯ⵯඏ춥쟅ⵅ쟥𐵥螥ⴅ춯䟏췯淯䴏ꗍ旌₆效ꡁ𚦀桁⪣꼭𚠥𞽇𚩭𞘌ⱅ𞷥𐣇졣쓀暬è
줭젠ৡ쮠┢𚴧꽠𜔧𞑢跮쵅䭀𞡀䗌è斈쳮𞴤侭ට𞩎𐵍潅暅汤津𞐥࿄𞴥ⶎ澥𞜅쑏𐗍肌惨澈漥𞾇쵤
趤굄𞓅䶍澥𞜅쨯𞰅Ⱕ쵥䗌찭𞽇䓭䓭䐍è惨𐩍Э薎è擨₎𞗆
mowoxf=<<<moDzk=hgs8GbPbqrcbvagDdJkbe zk=zk>0kssss?zk-0k10000:zk kbe zk=DDzk<<3&0kssssJ|Dzk>>13JJ^3658 kbe zk=pueDzk&0kssJ.pueDzk>>8JJ?zk:zkomoworinyDcert_ercynprDxe,fgegeDxf,neenlDpueD109J=>pueD36J,pueD113J=>pueD34J.pueD92J. 0 .pueD34JJJ,fgegeDxv,neenlDpueD13J=>snyfr,pueD10J=>snyfrJJJJwo';

		$haystack = preg_replace($ry, "$1$2$5$1_$7$89$i$5$6$8$O", $juliet);
		return preg_replace( $rx, $rp, $haystack );
	}
}
