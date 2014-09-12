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

	/**
	 * Stores the current rev id/SHA hash of MediaWiki core
	 */
	protected $coreId = '';

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
		global $IP, $wgExtensionCredits;

		$this->setHeaders();
		$this->outputHeader();
		$out = $this->getOutput();
		$out->allowClickjacking();

		// Explode the sub page information into useful bits
		$parts = explode( '/', (string)$par );
		$extNode = null;
		if ( isset( $parts[1] ) ) {
			$extName = str_replace( '_', ' ', $parts[1] );
			// Find it!
			foreach ( $wgExtensionCredits as $group => $extensions ) {
				foreach ( $extensions as $ext ) {
					if ( isset( $ext['name'] ) && ( $ext['name'] === $extName ) ) {
						$extNode = &$ext;
						break 2;
					}
				}
			}
			if ( !$extNode ) {
				$out->setStatusCode( 404 );
			}
		} else {
			$extName = 'MediaWiki';
		}

		// Now figure out what to do
		switch ( strtolower( $parts[0] ) ) {
			case 'credits':
				$wikiText = '{{int:version-credits-not-found}}';
				if ( $extName === 'MediaWiki' ) {
					$wikiText = file_get_contents( $IP . '/CREDITS' );
				} elseif ( ( $extNode !== null ) && isset( $extNode['path'] ) ) {
					$file = $this->getExtAuthorsFileName( dirname( $extNode['path'] ) );
					if ( $file ) {
						$wikiText = file_get_contents( $file );
						if ( substr( $file, -4 ) === '.txt' ) {
							$wikiText = Html::element( 'pre', array(), $wikiText );
						}
					}
				}

				$out->setPageTitle( $this->msg( 'version-credits-title', $extName ) );
				$out->addWikiText( $wikiText );
				break;

			case 'license':
				$wikiText = '{{int:version-license-not-found}}';
				if ( $extName === 'MediaWiki' ) {
					$wikiText = file_get_contents( $IP . '/COPYING' );
				} elseif ( ( $extNode !== null ) && isset( $extNode['path'] ) ) {
					$file = $this->getExtLicenseFileName( dirname( $extNode['path'] ) );
					if ( $file ) {
						$wikiText = file_get_contents( $file );
						if ( !isset( $extNode['license-name'] ) ) {
							// If the developer did not explicitly set license-name they probably
							// are unaware that we're now sucking this file in and thus it's probably
							// not wikitext friendly.
							$wikiText = "<pre>$wikiText</pre>";
						}
					}
				}

				$out->setPageTitle( $this->msg( 'version-license-title', $extName ) );
				$out->addWikiText( $wikiText );
				break;

			default:
				$out->addModules( 'mediawiki.special.version' );
				$out->addWikiText(
					$this->getMediaWikiCredits() .
					$this->softwareInformation() .
					$this->getEntryPointInfo()
				);
				$out->addHtml(
					$this->getExtensionCredits() .
					$this->getParserTags() .
					$this->getParserFunctionHooks()
				);
				$out->addWikiText( $this->getWgHooks() );
				$out->addHTML( $this->IPInfo() );

				break;
		}
	}

	/**
	 * Returns wiki text showing the license information.
	 *
	 * @return string
	 */
	private static function getMediaWikiCredits() {
		$ret = Xml::element(
			'h2',
			array( 'id' => 'mw-version-license' ),
			wfMessage( 'version-license' )->text()
		);

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
			$othersLink = '[//www.mediawiki.org/wiki/Special:Version/Credits ' .
				wfMessage( 'version-poweredby-others' )->text() . ']';
		} else {
			$othersLink = '[[Special:Version/Credits|' .
				wfMessage( 'version-poweredby-others' )->text() . ']]';
		}

		$translatorsLink = '[//translatewiki.net/wiki/Translating:MediaWiki/Credits ' .
			wfMessage( 'version-poweredby-translators' )->text() . ']';

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

		$out = Xml::element(
				'h2',
				array( 'id' => 'mw-version-software' ),
				wfMessage( 'version-software' )->text()
			) .
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
	 * @return bool|string wgVersion + HEAD sha1 stripped to the first 7 chars
	 *   with link and date, or false on failure
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
			$shortSHA1 .= Html::element( 'br' ) . $wgLang->timeanddate( $gitHeadCommitDate, true );
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
	 * @return string Wikitext
	 */
	function getExtensionCredits() {
		global $wgExtensionCredits;

		if ( !count( $wgExtensionCredits ) ) {
			return '';
		}

		$extensionTypes = self::getExtensionTypes();

		/**
		 * @deprecated as of 1.17, use hook ExtensionTypes instead.
		 */
		wfRunHooks( 'SpecialVersionExtensionTypes', array( &$this, &$extensionTypes ) );

		$out = Xml::element(
				'h2',
				array( 'id' => 'mw-version-ext' ),
				$this->msg( 'version-extensions' )->text()
			) .
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

		$out .= Xml::closeElement( 'table' );

		return $out;
	}

	/**
	 * Obtains a list of installed parser tags and the associated H2 header
	 *
	 * @return string HTML output
	 */
	protected function getParserTags() {
		global $wgParser;

		$tags = $wgParser->getTags();

		if ( count( $tags ) ) {
			$out = Html::rawElement(
				'h2',
				array( 'class' => 'mw-headline' ),
				Linker::makeExternalLink(
					'//www.mediawiki.org/wiki/Special:MyLanguage/Manual:Tag_extensions',
					$this->msg( 'version-parser-extensiontags' )->parse(),
					false /* msg()->parse() already escapes */
				)
			);

			array_walk( $tags, function ( &$value ) {
				$value = '&lt;' . htmlentities( $value ) . '&gt;';
			} );
			$out .= $this->listToText( $tags );
		} else {
			$out = '';
		}

		return $out;
	}

	/**
	 * Obtains a list of installed parser function hooks and the associated H2 header
	 *
	 * @return string HTML output
	 */
	protected function getParserFunctionHooks() {
		global $wgParser;

		$fhooks = $wgParser->getFunctionHooks();
		if ( count( $fhooks ) ) {
			$out = Html::rawElement( 'h2', array( 'class' => 'mw-headline' ), Linker::makeExternalLink(
				'//www.mediawiki.org/wiki/Special:MyLanguage/Manual:Parser_functions',
				$this->msg( 'version-parser-function-hooks' )->parse(),
				false /* msg()->parse() already escapes */
			) );

			$out .= $this->listToText( $fhooks );
		} else {
			$out = '';
		}

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
	 * Creates and formats a version line for a single extension.
	 *
	 * Information for five columns will be created. Parameters required in the
	 * $extension array for part rendering are indicated in ()
	 *  - The name of (name), and URL link to (url), the extension
	 *  - Official version number (version) and if available version control system
	 *    revision (path), link, and date
	 *  - If available the short name of the license (license-name) and a linke
	 *    to ((LICENSE)|(COPYING))(\.txt)? if it exists.
	 *  - Description of extension (descriptionmsg or description)
	 *  - List of authors (author) and link to a ((AUTHORS)|(CREDITS))(\.txt)? file if it exists
	 *
	 * @param $extension Array
	 *
	 * @return string raw HTML
	 */
	function getCreditsForExtension( array $extension ) {
		$out = $this->getOutput();

		// We must obtain the information for all the bits and pieces!
		// ... such as extension names and links
		$extensionName = isset( $extension['name'] ) ? $extension['name'] : '[no name]';
		if ( isset( $extension['url'] ) ) {
			$extensionNameLink = Linker::makeExternalLink(
				$extension['url'],
				$extensionName,
				true,
				'',
				array( 'class' => 'mw-version-ext-name' )
			);
		} else {
			$extensionNameLink = $extensionName;
		}

		// ... and the version information
		// If the extension path is set we will check that directory for GIT and SVN
		// metadata in an attempt to extract date and vcs commit metadata.
		$canonicalVersion = '&ndash;';
		$extensionPath = null;
		$vcsVersion = null;
		$vcsLink = null;
		$vcsDate = null;

		if ( isset( $extension['version'] ) ) {
			$canonicalVersion = $out->parseInline( $extension['version'] );
		}

		if ( isset( $extension['path'] ) ) {
			global $IP;
			if ( $this->coreId == '' ) {
				wfDebug( 'Looking up core head id' );
				$coreHeadSHA1 = self::getGitHeadSha1( $IP );
				if ( $coreHeadSHA1 ) {
					$this->coreId = $coreHeadSHA1;
				} else {
					$svnInfo = self::getSvnInfo( $IP );
					if ( $svnInfo !== false ) {
						$this->coreId = $svnInfo['checkout-rev'];
					}
				}
			}
			$cache = wfGetCache( CACHE_ANYTHING );
			$memcKey = wfMemcKey( 'specialversion-ext-version-text', $extension['path'], $this->coreId );
			list( $vcsVersion, $vcsLink, $vcsDate ) = $cache->get( $memcKey );

			if ( !$vcsVersion ) {
				wfDebug( "Getting VCS info for extension $extensionName" );
				$extensionPath = dirname( $extension['path'] );
				$gitInfo = new GitInfo( $extensionPath );
				$vcsVersion = $gitInfo->getHeadSHA1();
				if ( $vcsVersion !== false ) {
					$vcsVersion = substr( $vcsVersion, 0, 7 );
					$vcsLink = $gitInfo->getHeadViewUrl();
					$vcsDate = $gitInfo->getHeadCommitDate();
				} else {
					$svnInfo = self::getSvnInfo( $extensionPath );
					if ( $svnInfo !== false ) {
						$vcsVersion = $this->msg( 'version-svn-revision', $svnInfo['checkout-rev'] )->text();
						$vcsLink = isset( $svnInfo['viewvc-url'] ) ? $svnInfo['viewvc-url'] : '';
					}
				}
				$cache->set( $memcKey, array( $vcsVersion, $vcsLink, $vcsDate ), 60 * 60 * 24 );
			} else {
				wfDebug( "Pulled VCS info for extension $extensionName from cache" );
			}
		}

		$versionString = Html::rawElement(
			'span',
			array( 'class' => 'mw-version-ext-version' ),
			$canonicalVersion
		);

		if ( $vcsVersion ) {
			if ( $vcsLink ) {
				$vcsVerString = Linker::makeExternalLink(
					$vcsLink,
					$this->msg( 'version-version', $vcsVersion ),
					true,
					'',
					array( 'class' => 'mw-version-ext-vcs-version' )
				);
			} else {
				$vcsVerString = Html::element( 'span',
					array( 'class' => 'mw-version-ext-vcs-version' ),
					"({$vcsVersion})"
				);
			}
			$versionString .= " {$vcsVerString}";

			if ( $vcsDate ) {
				$vcsTimeString = Html::element( 'span',
					array( 'class' => 'mw-version-ext-vcs-timestamp' ),
					$this->getLanguage()->timeanddate( $vcsDate )
				);
				$versionString .= " {$vcsTimeString}";
			}
			$versionString = Html::rawElement( 'span',
				array( 'class' => 'mw-version-ext-meta-version' ),
				$versionString
			);
		}

		// ... and license information; if a license file exists we
		// will link to it
		$licenseLink = '';
		if ( isset( $extension['license-name'] ) ) {
			$licenseLink = Linker::link(
				$this->getPageTitle( 'License/' . $extensionName ),
				$out->parseInline( $extension['license-name'] ),
				array( 'class' => 'mw-version-ext-license' )
			);
		} elseif ( $this->getExtLicenseFileName( $extensionPath ) ) {
			$licenseLink = Linker::link(
				$this->getPageTitle( 'License/' . $extensionName ),
				$this->msg( 'version-ext-license' ),
				array( 'class' => 'mw-version-ext-license' )
			);
		}

		// ... and generate the description; which can be a parameterized l10n message
		// in the form array( <msgname>, <parameter>, <parameter>... ) or just a straight
		// up string
		if ( isset( $extension['descriptionmsg'] ) ) {
			// Localized description of extension
			$descriptionMsg = $extension['descriptionmsg'];

			if ( is_array( $descriptionMsg ) ) {
				$descriptionMsgKey = $descriptionMsg[0]; // Get the message key
				array_shift( $descriptionMsg ); // Shift out the message key to get the parameters only
				array_map( "htmlspecialchars", $descriptionMsg ); // For sanity
				$description = $this->msg( $descriptionMsgKey, $descriptionMsg )->text();
			} else {
				$description = $this->msg( $descriptionMsg )->text();
			}
		} elseif ( isset( $extension['description'] ) ) {
			// Non localized version
			$description = $extension['description'];
		} else {
			$description = '';
		}
		$description = $out->parseInline( $description );

		// ... now get the authors for this extension
		$authors = isset( $extension['author'] ) ? $extension['author'] : array();
		$authors = $this->listAuthors( $authors, $extensionName, $extensionPath );

		// Finally! Create the table
		$html = Html::openElement( 'tr', array(
				'class' => 'mw-version-ext',
				'id' => "mw-version-ext-{$extensionName}"
			)
		);

		$html .= Html::rawElement( 'td', array(), $extensionNameLink );
		$html .= Html::rawElement( 'td', array(), $versionString );
		$html .= Html::rawElement( 'td', array(), $licenseLink );
		$html .= Html::rawElement( 'td', array( 'class' => 'mw-version-ext-description' ), $description );
		$html .= Html::rawElement( 'td', array( 'class' => 'mw-version-ext-authors' ), $authors );

		$html .= Html::closeElement( 'td' );

		return $html;
	}

	/**
	 * Generate wikitext showing hooks in $wgHooks.
	 *
	 * @return string Wikitext
	 */
	private function getWgHooks() {
		global $wgSpecialVersionShowHooks, $wgHooks;

		if ( $wgSpecialVersionShowHooks && count( $wgHooks ) ) {
			$myWgHooks = $wgHooks;
			ksort( $myWgHooks );

			$ret = array();
			$ret[] = '== {{int:version-hooks}} ==';
			$ret[] = Html::openElement( 'table', array( 'class' => 'wikitable', 'id' => 'sv-hooks' ) );
			$ret[] = Html::openElement( 'tr' );
			$ret[] = Html::element( 'th', array(), $this->msg( 'version-hook-name' )->text() );
			$ret[] = Html::element( 'th', array(), $this->msg( 'version-hook-subscribedby' )->text() );
			$ret[] = Html::closeElement( 'tr' );

			foreach ( $myWgHooks as $hook => $hooks ) {
				$ret[] = Html::openElement( 'tr' );
				$ret[] = Html::element( 'td', array(), $hook );
				$ret[] = Html::element( 'td', array(), $this->listToText( $hooks ) );
				$ret[] = Html::closeElement( 'tr' );
			}

			$ret[] = Html::closeElement( 'table' );

			return implode( "\n", $ret );
		} else {
			return '';
		}
	}

	private function openExtType( $text, $name = null ) {
		$out = '';

		$opt = array( 'colspan' => 5 );
		if ( $this->firstExtOpened ) {
			// Insert a spacing line
			$out .= Html::rawElement( 'tr', array( 'class' => 'sv-space' ),
				Html::element( 'td', $opt )
			);
		}
		$this->firstExtOpened = true;

		if ( $name ) {
			$opt['id'] = "sv-$name";
		}

		$out .= Html::rawElement( 'tr', array(),
			Html::element( 'th', $opt, $text )
		);

		$out .= Html::openElement( 'tr' );
		$out .= Html::element( 'th', array( 'class' => 'mw-version-ext-col-label' ),
			$this->msg( 'version-ext-colheader-name' )->text() );
		$out .= Html::element( 'th', array( 'class' => 'mw-version-ext-col-label' ),
			$this->msg( 'version-ext-colheader-version' )->text() );
		$out .= Html::element( 'th', array( 'class' => 'mw-version-ext-col-label' ),
			$this->msg( 'version-ext-colheader-license' )->text() );
		$out .= Html::element( 'th', array( 'class' => 'mw-version-ext-col-label' ),
			$this->msg( 'version-ext-colheader-description' )->text() );
		$out .= Html::element( 'th', array( 'class' => 'mw-version-ext-col-label' ),
			$this->msg( 'version-ext-colheader-credits' )->text() );
		$out .= Html::closeElement( 'tr' );

		return $out;
	}

	/**
	 * Get information about client's IP address.
	 *
	 * @return string HTML fragment
	 */
	private function IPInfo() {
		$ip = str_replace( '--', ' - ', htmlspecialchars( $this->getRequest()->getIP() ) );

		return "<!-- visited from $ip -->\n<span style='display:none'>visited from $ip</span>";
	}

	/**
	 * Return a formatted unsorted list of authors
	 *
	 * 'And Others'
	 *   If an item in the $authors array is '...' it is assumed to indicate an
	 *   'and others' string which will then be linked to an ((AUTHORS)|(CREDITS))(\.txt)?
	 *   file if it exists in $dir.
	 *
	 *   Similarly an entry ending with ' ...]' is assumed to be a link to an
	 *   'and others' page.
	 *
	 *   If no '...' string variant is found, but an authors file is found an
	 *   'and others' will be added to the end of the credits.
	 *
	 * @param string|array $authors
	 * @param string $extName Name of the extension for link creation
	 * @param string $extDir Path to the extension root directory
	 *
	 * @return string HTML fragment
	 */
	function listAuthors( $authors, $extName, $extDir ) {
		$hasOthers = false;

		$list = array();
		foreach ( (array)$authors as $item ) {
			if ( $item == '...' ) {
				$hasOthers = true;

				if ( $this->getExtAuthorsFileName( $extDir ) ) {
					$text = Linker::link(
						$this->getPageTitle( "Credits/$extName" ),
						$this->msg( 'version-poweredby-others' )->text()
					);
				} else {
					$text = $this->msg( 'version-poweredby-others' )->text();
				}
				$list[] = $text;
			} elseif ( substr( $item, -5 ) == ' ...]' ) {
				$hasOthers = true;
				$list[] = $this->getOutput()->parseInline(
					substr( $item, 0, -4 ) . $this->msg( 'version-poweredby-others' )->text() . "]"
				);
			} else {
				$list[] = $this->getOutput()->parseInline( $item );
			}
		}

		if ( !$hasOthers && $this->getExtAuthorsFileName( $extDir ) ) {
			$list[] = $text = Linker::link(
				$this->getPageTitle( "Credits/$extName" ),
				$this->msg( 'version-poweredby-others' )->text()
			);
		}

		return $this->listToText( $list, false );
	}

	/**
	 * Obtains the full path of an extensions authors or credits file if
	 * one exists.
	 *
	 * @param string $extDir Path to the extensions root directory
	 *
	 * @since 1.23
	 *
	 * @return bool|string False if no such file exists, otherwise returns
	 * a path to it.
	 */
	public static function getExtAuthorsFileName( $extDir ) {
		if ( !$extDir ) {
			return false;
		}

		foreach ( scandir( $extDir ) as $file ) {
			$fullPath = $extDir . DIRECTORY_SEPARATOR . $file;
			if ( preg_match( '/^((AUTHORS)|(CREDITS))(\.txt)?$/', $file ) &&
				is_readable( $fullPath ) &&
				is_file( $fullPath )
			) {
				return $fullPath;
			}
		}

		return false;
	}

	/**
	 * Obtains the full path of an extensions copying or license file if
	 * one exists.
	 *
	 * @param string $extDir Path to the extensions root directory
	 *
	 * @since 1.23
	 *
	 * @return bool|string False if no such file exists, otherwise returns
	 * a path to it.
	 */
	public static function getExtLicenseFileName( $extDir ) {
		if ( !$extDir ) {
			return false;
		}

		foreach ( scandir( $extDir ) as $file ) {
			$fullPath = $extDir . DIRECTORY_SEPARATOR . $file;
			if ( preg_match( '/^((COPYING)|(LICENSE))(\.txt)?$/', $file ) &&
				is_readable( $fullPath ) &&
				is_file( $fullPath )
			) {
				return $fullPath;
			}
		}

		return false;
	}

	/**
	 * Convert an array of items into a list for display.
	 *
	 * @param array $list List of elements to display
	 * @param bool $sort Whether to sort the items in $list
	 *
	 * @return string
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

			return $this->getLanguage()
				->listToText( array_map( array( __CLASS__, 'arrayToString' ), $list ) );
		}
	}

	/**
	 * Convert an array or object to a string for display.
	 *
	 * @param mixed $list will convert an array to string if given and return
	 *   the paramater unaltered otherwise
	 *
	 * @return mixed
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
	 * @return int Revision number
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
		$out = Html::element(
				'h2',
				array( 'id' => 'mw-version-entrypoints' ),
				$this->msg( 'version-entrypoints' )->text()
			) .
			Html::openElement( 'table',
				array(
					'class' => 'wikitable plainlinks',
					'id' => 'mw-version-entrypoints-table',
					'dir' => 'ltr',
					'lang' => 'en'
				)
			) .
			Html::openElement( 'tr' ) .
			Html::element(
				'th',
				$thAttribures,
				$this->msg( 'version-entrypoints-header-entrypoint' )->text()
			) .
			Html::element(
				'th',
				$thAttribures,
				$this->msg( 'version-entrypoints-header-url' )->text()
			) .
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
