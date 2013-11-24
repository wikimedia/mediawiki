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
		'https://svn.wikimedia.org/svnroot/mediawiki' => 'https://svn.wikimedia.org/viewvc/mediawiki',
	);

	public function __construct() {
		parent::__construct( 'Version' );
	}

	/**
	 * main()
	 */
	public function execute( $par ) {
		global $wgSpecialVersionShowHooks, $IP;

		$this->setHeaders();
		$this->outputHeader();
		$out = $this->getOutput();
		$out->allowClickjacking();

		if ( $par !== 'Credits' ) {
			$text =
				$this->getMediaWikiCredits() .
				$this->softwareInformation() .
				$this->getEntryPointInfo() .
				$this->getExtensionCredits();
			if ( $wgSpecialVersionShowHooks ) {
				$text .= $this->getWgHooks();
			}

			$out->addWikiText( $text );
			$out->addHTML( $this->IPInfo() );

			if ( $this->getRequest()->getVal( 'easteregg' ) ) {
				// TODO: put something interesting here
			}
		} else {
			// Credits sub page

			// Header
			$out->addHTML( wfMessage( 'version-credits-summary' )->parseAsBlock() );

			$wikiText = file_get_contents( $IP . '/CREDITS' );

			// Take everything from the first section onwards, to remove the (not localized) header
			$wikiText = substr( $wikiText, strpos( $wikiText, '==' ) );

			$out->addWikiText( $wikiText );
		}
	}

	/**
	 * Returns wiki text showing the license information.
	 *
	 * @return string
	 */
	private static function getMediaWikiCredits() {
		$ret = Xml::element( 'h2', array( 'id' => 'mw-version-license' ), wfMessage( 'version-license' )->text() );

		// This text is always left-to-right.
		$ret .= '<div class="plainlinks">';
		$ret .= "__NOTOC__
		" . self::getCopyrightAndAuthorList() . "\n
		" . wfMessage( 'version-license-info' )->text();
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

		if ( defined( 'MEDIAWIKI_INSTALL' ) ) {
			$othersLink = '[//www.mediawiki.org/wiki/Special:Version/Credits ' . wfMessage( 'version-poweredby-others' )->text() . ']';
		} else {
			$othersLink = '[[Special:Version/Credits|' . wfMessage( 'version-poweredby-others' )->text() . ']]';
		}

		$translatorsLink = '[//translatewiki.net/wiki/Translating:MediaWiki/Credits ' . wfMessage( 'version-poweredby-translators' )->text() . ']';

		$authorList = array(
			'Magnus Manske', 'Brion Vibber', 'Lee Daniel Crocker',
			'Tim Starling', 'Erik Möller', 'Gabriel Wicke', 'Ævar Arnfjörð Bjarmason',
			'Niklas Laxström', 'Domas Mituzas', 'Rob Church', 'Yuri Astrakhan',
			'Aryeh Gregor', 'Aaron Schulz', 'Andrew Garrett', 'Raimond Spekking',
			'Alexandre Emsenhuber', 'Siebrand Mazeland', 'Chad Horohoe',
			'Roan Kattouw', 'Trevor Parscal', 'Bryan Tong Minh', 'Sam Reed',
			'Victor Vasiliev', 'Rotem Liss', 'Platonides', 'Antoine Musso',
			'Timo Tijhof', 'Daniel Kinzler', 'Jeroen De Dauw', $othersLink,
			$translatorsLink
		);

		return wfMessage( 'version-poweredby-credits', MWTimestamp::getLocalInstance()->format( 'Y' ),
			$wgLang->listToText( $authorList ) )->text();
	}

	/**
	 * Returns wiki text showing the third party software versions (apache, php, mysql).
	 *
	 * @return string
	 */
	static function softwareInformation() {
		$dbr = wfGetDB( DB_SLAVE );

		// Put the software in an array of form 'name' => 'version'. All messages should
		// be loaded here, so feel free to use wfMessage in the 'name'. Raw HTML or
		// wikimarkup can be used.
		$software = array();
		$software['[https://www.mediawiki.org/ MediaWiki]'] = self::getVersionLinked();
		$software['[http://www.php.net/ PHP]'] = phpversion() . " (" . PHP_SAPI . ")";
		$software[$dbr->getSoftwareLink()] = $dbr->getServerInfo();

		// Allow a hook to add/remove items.
		wfRunHooks( 'SoftwareInfo', array( &$software ) );

		$out = Xml::element( 'h2', array( 'id' => 'mw-version-software' ), wfMessage( 'version-software' )->text() ) .
				Xml::openElement( 'table', array( 'class' => 'wikitable plainlinks', 'id' => 'sv-software' ) ) .
				"<tr>
					<th>" . wfMessage( 'version-software-product' )->text() . "</th>
					<th>" . wfMessage( 'version-software-version' )->text() . "</th>
				</tr>\n";

		foreach ( $software as $name => $version ) {
			$out .= "<tr>
					<td>" . $name . "</td>
					<td dir=\"ltr\">" . $version . "</td>
				</tr>\n";
		}

		return $out . Xml::closeElement( 'table' );
	}

	/**
	 * Return a string of the MediaWiki version with SVN revision if available.
	 *
	 * @param $flags String
	 * @return mixed
	 */
	public static function getVersion( $flags = '' ) {
		global $wgVersion, $IP;
		wfProfileIn( __METHOD__ );

		$gitInfo = self::getGitHeadSha1( $IP );
		$svnInfo = self::getSvnInfo( $IP );
		if ( !$svnInfo && !$gitInfo ) {
			$version = $wgVersion;
		} elseif ( $gitInfo && $flags === 'nodb' ) {
			$shortSha1 = substr( $gitInfo, 0, 7 );
			$version = "$wgVersion ($shortSha1)";
		} elseif ( $gitInfo ) {
			$shortSha1 = substr( $gitInfo, 0, 7 );
			$shortSha1 = wfMessage( 'parentheses' )->params( $shortSha1 )->escaped();
			$version = "$wgVersion $shortSha1";
		} elseif ( $flags === 'nodb' ) {
			$version = "$wgVersion (r{$svnInfo['checkout-rev']})";
		} else {
			$version = $wgVersion . ' ' .
				wfMessage(
					'version-svn-revision',
					isset( $info['directory-rev'] ) ? $info['directory-rev'] : '',
					$info['checkout-rev']
				)->text();
		}

		wfProfileOut( __METHOD__ );
		return $version;
	}

	/**
	 * Return a wikitext-formatted string of the MediaWiki version with a link to
	 * the SVN revision or the git SHA1 of head if available.
	 * Git is prefered over Svn
	 * The fallback is just $wgVersion
	 *
	 * @return mixed
	 */
	public static function getVersionLinked() {
		global $wgVersion;
		wfProfileIn( __METHOD__ );

		$gitVersion = self::getVersionLinkedGit();
		if ( $gitVersion ) {
			$v = $gitVersion;
		} else {
			$svnVersion = self::getVersionLinkedSvn();
			if ( $svnVersion ) {
				$v = $svnVersion;
			} else {
				$v = $wgVersion; // fallback
			}
		}

		wfProfileOut( __METHOD__ );
		return $v;
	}

	/**
	 * @return string wgVersion + a link to subversion revision of svn BASE
	 */
	private static function getVersionLinkedSvn() {
		global $IP;

		$info = self::getSvnInfo( $IP );
		if ( !isset( $info['checkout-rev'] ) ) {
			return false;
		}

		$linkText = wfMessage(
			'version-svn-revision',
			isset( $info['directory-rev'] ) ? $info['directory-rev'] : '',
			$info['checkout-rev']
		)->text();

		if ( isset( $info['viewvc-url'] ) ) {
			$version = "[{$info['viewvc-url']} $linkText]";
		} else {
			$version = $linkText;
		}

		return self::getwgVersionLinked() . " $version";
	}

	/**
	 * @return string
	 */
	private static function getwgVersionLinked() {
		global $wgVersion;
		$versionUrl = "";
		if ( wfRunHooks( 'SpecialVersionVersionUrl', array( $wgVersion, &$versionUrl ) ) ) {
			$versionParts = array();
			preg_match( "/^(\d+\.\d+)/", $wgVersion, $versionParts );
			$versionUrl = "https://www.mediawiki.org/wiki/MediaWiki_{$versionParts[1]}";
		}
		return "[$versionUrl $wgVersion]";
	}

	/**
	 * @since 1.22 Returns the HEAD date in addition to the sha1 and link
	 * @return bool|string wgVersion + HEAD sha1 stripped to the first 7 chars with link and date, or false on failure
	 */
	private static function getVersionLinkedGit() {
		global $IP, $wgLang;

		$gitInfo = new GitInfo( $IP );
		$headSHA1 = $gitInfo->getHeadSHA1();
		if ( !$headSHA1 ) {
			return false;
		}

		$shortSHA1 = '(' . substr( $headSHA1, 0, 7 ) . ')';

		$gitHeadUrl = $gitInfo->getHeadViewUrl();
		if ( $gitHeadUrl !== false ) {
			$shortSHA1 = "[$gitHeadUrl $shortSHA1]";
		}

		$gitHeadCommitDate = $gitInfo->getHeadCommitDate();
		if ( $gitHeadCommitDate ) {
			$shortSHA1 .= "<br/>" . $wgLang->timeanddate( $gitHeadCommitDate, true );
		}

		return self::getwgVersionLinked() . " $shortSHA1";
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
				'specialpage' => wfMessage( 'version-specialpages' )->text(),
				'parserhook' => wfMessage( 'version-parserhooks' )->text(),
				'variable' => wfMessage( 'version-variables' )->text(),
				'media' => wfMessage( 'version-mediahandlers' )->text(),
				'antispam' => wfMessage( 'version-antispam' )->text(),
				'skin' => wfMessage( 'version-skins' )->text(),
				'api' => wfMessage( 'version-api' )->text(),
				'other' => wfMessage( 'version-other' )->text(),
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
		global $wgExtensionCredits, $wgExtensionFunctions, $wgParser;

		if ( !count( $wgExtensionCredits ) && !count( $wgExtensionFunctions ) ) {
			return '';
		}

		$extensionTypes = self::getExtensionTypes();

		/**
		 * @deprecated as of 1.17, use hook ExtensionTypes instead.
		 */
		wfRunHooks( 'SpecialVersionExtensionTypes', array( &$this, &$extensionTypes ) );

		$out = Xml::element( 'h2', array( 'id' => 'mw-version-ext' ), $this->msg( 'version-extensions' )->text() ) .
			Xml::openElement( 'table', array( 'class' => 'wikitable plainlinks', 'id' => 'sv-ext' ) );

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

		$tags = $wgParser->getTags();
		$cnt = count( $tags );

		if ( $cnt ) {
			for ( $i = 0; $i < $cnt; ++$i ) {
				$tags[$i] = "&lt;{$tags[$i]}&gt;";
			}
			$out .= $this->openExtType( $this->msg( 'version-parser-extensiontags' )->text(), 'parser-tags' );
			$out .= '<tr><td colspan="4">' . $this->listToText( $tags ) . "</td></tr>\n";
		}

		$fhooks = $wgParser->getFunctionHooks();
		if ( count( $fhooks ) ) {
			$out .= $this->openExtType( $this->msg( 'version-parser-function-hooks' )->text(), 'parser-function-hooks' );
			$out .= '<tr><td colspan="4">' . $this->listToText( $fhooks ) . "</td></tr>\n";
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
	 * @param $a array
	 * @param $b array
	 * @return int
	 */
	function compare( $a, $b ) {
		if ( $a['name'] === $b['name'] ) {
			return 0;
		} else {
			return $this->getLanguage()->lc( $a['name'] ) > $this->getLanguage()->lc( $b['name'] )
				? 1
				: -1;
		}
	}

	/**
	 * Creates and formats the credits for a single extension and returns this.
	 *
	 * @param $extension Array
	 *
	 * @return string
	 */
	function getCreditsForExtension( array $extension ) {
		global $wgLang;

		$name = isset( $extension['name'] ) ? $extension['name'] : '[no name]';

		$vcsText = false;

		if ( isset( $extension['path'] ) ) {
			$gitInfo = new GitInfo( dirname( $extension['path'] ) );
			$gitHeadSHA1 = $gitInfo->getHeadSHA1();
			if ( $gitHeadSHA1 !== false ) {
				$vcsText = '(' . substr( $gitHeadSHA1, 0, 7 ) . ')';
				$gitViewerUrl = $gitInfo->getHeadViewUrl();
				if ( $gitViewerUrl !== false ) {
					$vcsText = "[$gitViewerUrl $vcsText]";
				}
				$gitHeadCommitDate = $gitInfo->getHeadCommitDate();
				if ( $gitHeadCommitDate ) {
					$vcsText .= "<br/>" . $wgLang->timeanddate( $gitHeadCommitDate, true );
				}
			} else {
				$svnInfo = self::getSvnInfo( dirname( $extension['path'] ) );
				# Make subversion text/link.
				if ( $svnInfo !== false ) {
					$directoryRev = isset( $svnInfo['directory-rev'] ) ? $svnInfo['directory-rev'] : null;
					$vcsText = $this->msg( 'version-svn-revision', $directoryRev, $svnInfo['checkout-rev'] )->text();
					$vcsText = isset( $svnInfo['viewvc-url'] ) ? '[' . $svnInfo['viewvc-url'] . " $vcsText]" : $vcsText;
				}
			}
		}

		# Make main link (or just the name if there is no URL).
		if ( isset( $extension['url'] ) ) {
			$mainLink = "[{$extension['url']} $name]";
		} else {
			$mainLink = $name;
		}

		if ( isset( $extension['version'] ) ) {
			$versionText = '<span class="mw-version-ext-version">' .
				$this->msg( 'version-version', $extension['version'] )->text() .
				'</span>';
		} else {
			$versionText = '';
		}

		# Make description text.
		$description = isset( $extension['description'] ) ? $extension['description'] : '';

		if ( isset( $extension['descriptionmsg'] ) ) {
			# Look for a localized description.
			$descriptionMsg = $extension['descriptionmsg'];

			if ( is_array( $descriptionMsg ) ) {
				$descriptionMsgKey = $descriptionMsg[0]; // Get the message key
				array_shift( $descriptionMsg ); // Shift out the message key to get the parameters only
				array_map( "htmlspecialchars", $descriptionMsg ); // For sanity
				$description = $this->msg( $descriptionMsgKey, $descriptionMsg )->text();
			} else {
				$description = $this->msg( $descriptionMsg )->text();
			}
		}

		if ( $vcsText !== false ) {
			$extNameVer = "<tr>
				<td><em>$mainLink $versionText</em></td>
				<td><em>$vcsText</em></td>";
		} else {
			$extNameVer = "<tr>
				<td colspan=\"2\"><em>$mainLink $versionText</em></td>";
		}

		$author = isset( $extension['author'] ) ? $extension['author'] : array();
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

			$ret = Xml::element( 'h2', array( 'id' => 'mw-version-hooks' ), $this->msg( 'version-hooks' )->text() ) .
				Xml::openElement( 'table', array( 'class' => 'wikitable', 'id' => 'sv-hooks' ) ) .
				"<tr>
					<th>" . $this->msg( 'version-hook-name' )->text() . "</th>
					<th>" . $this->msg( 'version-hook-subscribedby' )->text() . "</th>
				</tr>\n";

			foreach ( $myWgHooks as $hook => $hooks ) {
				$ret .= "<tr>
						<td>$hook</td>
						<td>" . $this->listToText( $hooks ) . "</td>
					</tr>\n";
			}

			$ret .= Xml::closeElement( 'table' );
			return $ret;
		} else {
			return '';
		}
	}

	private function openExtType( $text, $name = null ) {
		$opt = array( 'colspan' => 4 );
		$out = '';

		if ( $this->firstExtOpened ) {
			// Insert a spacing line
			$out .= '<tr class="sv-space">' . Html::element( 'td', $opt ) . "</tr>\n";
		}
		$this->firstExtOpened = true;

		if ( $name ) {
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
		$ip = str_replace( '--', ' - ', htmlspecialchars( $this->getRequest()->getIP() ) );
		return "<!-- visited from $ip -->\n<span style='display:none'>visited from $ip</span>";
	}

	/**
	 * Return a formatted unsorted list of authors
	 *
	 * @param $authors mixed: string or array of strings
	 * @return String: HTML fragment
	 */
	function listAuthors( $authors ) {
		$list = array();
		foreach ( (array)$authors as $item ) {
			if ( $item == '...' ) {
				$list[] = $this->msg( 'version-poweredby-others' )->text();
			} elseif ( substr( $item, -5 ) == ' ...]' ) {
				$list[] = substr( $item, 0, -4 ) . $this->msg( 'version-poweredby-others' )->text() . "]";
			} else {
				$list[] = $item;
			}
		}
		return $this->listToText( $list, false );
	}

	/**
	 * Convert an array of items into a list for display.
	 *
	 * @param array $list of elements to display
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
			if ( $sort ) {
				sort( $list );
			}
			return $this->getLanguage()->listToText( array_map( array( __CLASS__, 'arrayToString' ), $list ) );
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
	public static function arrayToString( $list ) {
		if ( is_array( $list ) && count( $list ) == 1 ) {
			$list = $list[0];
		}
		if ( is_object( $list ) ) {
			$class = wfMessage( 'parentheses' )->params( get_class( $list ) )->escaped();
			return $class;
		} elseif ( !is_array( $list ) ) {
			return $list;
		} else {
			if ( is_object( $list[0] ) ) {
				$class = get_class( $list[0] );
			} else {
				$class = $list[0];
			}
			return wfMessage( 'parentheses' )->params( "$class, {$list[1]}" )->escaped();
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
	 * @param $dir string
	 * @return array|bool
	 */
	public static function getSvnInfo( $dir ) {
		// http://svnbook.red-bean.com/nightly/en/svn.developer.insidewc.html
		$entries = $dir . '/.svn/entries';

		if ( !file_exists( $entries ) ) {
			return false;
		}

		$lines = file( $entries );
		if ( !count( $lines ) ) {
			return false;
		}

		// check if file is xml (subversion release <= 1.3) or not (subversion release = 1.4)
		if ( preg_match( '/^<\?xml/', $lines[0] ) ) {
			// subversion is release <= 1.3
			if ( !function_exists( 'simplexml_load_file' ) ) {
				// We could fall back to expat... YUCK
				return false;
			}

			// SimpleXml whines about the xmlns...
			wfSuppressWarnings();
			$xml = simplexml_load_file( $entries );
			wfRestoreWarnings();

			if ( $xml ) {
				foreach ( $xml->entry as $entry ) {
					if ( $xml->entry[0]['name'] == '' ) {
						// The directory entry should always have a revision marker.
						if ( $entry['revision'] ) {
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
	 * @param string $dir directory of the svn checkout
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

	/**
	 * @param string $dir directory of the git checkout
	 * @return bool|String sha1 of commit HEAD points to
	 */
	public static function getGitHeadSha1( $dir ) {
		$repo = new GitInfo( $dir );
		return $repo->getHeadSHA1();
	}

	/**
	 * Get the list of entry points and their URLs
	 * @return string Wikitext
	 */
	public function getEntryPointInfo() {
		global $wgArticlePath, $wgScriptPath;
		$scriptPath = $wgScriptPath ? $wgScriptPath : "/";
		$entryPoints = array(
			'version-entrypoints-articlepath' => $wgArticlePath,
			'version-entrypoints-scriptpath' => $scriptPath,
			'version-entrypoints-index-php' => wfScript( 'index' ),
			'version-entrypoints-api-php' => wfScript( 'api' ),
			'version-entrypoints-load-php' => wfScript( 'load' ),
		);

		$language = $this->getLanguage();
		$thAttribures = array(
			'dir' => $language->getDir(),
			'lang' => $language->getCode()
		);
		$out = Html::element( 'h2', array( 'id' => 'mw-version-entrypoints' ), $this->msg( 'version-entrypoints' )->text() ) .
			Html::openElement( 'table',
				array(
					'class' => 'wikitable plainlinks',
					'id' => 'mw-version-entrypoints-table',
					'dir' => 'ltr',
					'lang' => 'en'
				)
			) .
			Html::openElement( 'tr' ) .
			Html::element( 'th', $thAttribures, $this->msg( 'version-entrypoints-header-entrypoint' )->text() ) .
			Html::element( 'th', $thAttribures, $this->msg( 'version-entrypoints-header-url' )->text() ) .
			Html::closeElement( 'tr' );

		foreach ( $entryPoints as $message => $value ) {
			$url = wfExpandUrl( $value, PROTO_RELATIVE );
			$out .= Html::openElement( 'tr' ) .
				// ->text() looks like it should be ->parse(), but this function
				// returns wikitext, not HTML, boo
				Html::rawElement( 'td', array(), $this->msg( $message )->text() ) .
				Html::rawElement( 'td', array(), Html::rawElement( 'code', array(), "[$url $value]" ) ) .
				Html::closeElement( 'tr' );
		}

		$out .= Html::closeElement( 'table' );
		return $out;
	}

	protected function getGroupName() {
		return 'wiki';
	}

}
