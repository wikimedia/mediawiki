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

use MediaWiki\ExtensionInfo;
use MediaWiki\MediaWikiServices;

/**
 * Give information about the version of MediaWiki, PHP, the DB and extensions
 *
 * @ingroup SpecialPage
 */
class SpecialVersion extends SpecialPage {

	/**
	 * @var bool
	 */
	protected $firstExtOpened = false;

	/**
	 * @var string The current rev id/SHA hash of MediaWiki core
	 */
	protected $coreId = '';

	/**
	 * @var string[]|false Lazy initialized key/value with message content
	 */
	protected static $extensionTypes = false;

	public function __construct() {
		parent::__construct( 'Version' );
	}

	/**
	 * @since 1.35
	 * @param ExtensionRegistry $reg
	 * @param Config $conf For additional entries from $wgExtensionCredits.
	 * @return array[]
	 * @see $wgExtensionCredits
	 */
	public static function getCredits( ExtensionRegistry $reg, Config $conf ) : array {
		$credits = $conf->get( 'ExtensionCredits' );
		foreach ( $reg->getAllThings() as $name => $credit ) {
			$credits[$credit['type']][] = $credit;
		}
		return $credits;
	}

	/**
	 * main()
	 * @param string|null $par
	 */
	public function execute( $par ) {
		global $IP;
		$config = $this->getConfig();
		$credits = self::getCredits( ExtensionRegistry::getInstance(), $config );

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
			foreach ( $credits as $group => $extensions ) {
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
				$out->addModuleStyles( 'mediawiki.special.version' );

				$wikiText = '{{int:version-credits-not-found}}';
				if ( $extName === 'MediaWiki' ) {
					$wikiText = file_get_contents( $IP . '/CREDITS' );
					// Put the contributor list into columns
					$wikiText = str_replace(
						[ '<!-- BEGIN CONTRIBUTOR LIST -->', '<!-- END CONTRIBUTOR LIST -->' ],
						[ '<div class="mw-version-credits">', '</div>' ],
						$wikiText );
				} elseif ( ( $extNode !== null ) && isset( $extNode['path'] ) ) {
					$file = ExtensionInfo::getAuthorsFileName( dirname( $extNode['path'] ) );
					if ( $file ) {
						$wikiText = file_get_contents( $file );
						if ( substr( $file, -4 ) === '.txt' ) {
							$wikiText = Html::element(
								'pre',
								[
									'lang' => 'en',
									'dir' => 'ltr',
								],
								$wikiText
							);
						}
					}
				}

				$out->setPageTitle( $this->msg( 'version-credits-title', $extName ) );
				$out->addWikiTextAsInterface( $wikiText );
				break;

			case 'license':
				$out->setPageTitle( $this->msg( 'version-license-title', $extName ) );

				$licenseFound = false;

				if ( $extName === 'MediaWiki' ) {
					$out->addWikiTextAsInterface(
						file_get_contents( $IP . '/COPYING' )
					);
					$licenseFound = true;
				} elseif ( ( $extNode !== null ) && isset( $extNode['path'] ) ) {
					$files = ExtensionInfo::getLicenseFileNames( dirname( $extNode['path'] ) );

					if ( count( $files ) ) {
						$licenseFound = true;
						foreach ( $files as $file ) {
							$out->addWikiTextAsInterface(
								Html::element(
									'pre',
									[
										'lang' => 'en',
										'dir' => 'ltr',
									],
									file_get_contents( $file )
								)
							);
						}
					}
				}
				if ( !$licenseFound ) {
					$out->addWikiTextAsInterface( '{{int:version-license-not-found}}' );
				}
				break;
			default:
				$out->addModuleStyles( 'mediawiki.special.version' );
				$out->addWikiTextAsInterface(
					self::getMediaWikiCredits() .
					self::softwareInformation() .
					$this->getEntryPointInfo()
				);
				$out->addHTML(
					$this->getSkinCredits( $credits ) .
					$this->getExtensionCredits( $credits ) .
					$this->getExternalLibraries( $credits ) .
					$this->getParserTags() .
					$this->getParserFunctionHooks()
				);
				$out->addWikiTextAsInterface( $this->getWgHooks() );
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
			[ 'id' => 'mw-version-license' ],
			wfMessage( 'version-license' )->text()
		);

		// This text is always left-to-right.
		$ret .= '<div class="plainlinks">';
		$ret .= "__NOTOC__
		" . self::getCopyrightAndAuthorList() . "\n
		" . '<div class="mw-version-license-info">' .
		wfMessage( 'version-license-info' )->text() .
		'</div>';
		$ret .= '</div>';

		return str_replace( "\t\t", '', $ret ) . "\n";
	}

	/**
	 * Get the "MediaWiki is copyright 2001-20xx by lots of cool folks" text
	 *
	 * @return string
	 */
	public static function getCopyrightAndAuthorList() {
		global $wgLang;

		if ( defined( 'MEDIAWIKI_INSTALL' ) ) {
			$othersLink = '[https://www.mediawiki.org/wiki/Special:Version/Credits ' .
				wfMessage( 'version-poweredby-others' )->text() . ']';
		} else {
			$othersLink = '[[Special:Version/Credits|' .
				wfMessage( 'version-poweredby-others' )->text() . ']]';
		}

		$translatorsLink = '[https://translatewiki.net/wiki/Translating:MediaWiki/Credits ' .
			wfMessage( 'version-poweredby-translators' )->text() . ']';

		$authorList = [
			'Magnus Manske', 'Brion Vibber', 'Lee Daniel Crocker',
			'Tim Starling', 'Erik Möller', 'Gabriel Wicke', 'Ævar Arnfjörð Bjarmason',
			'Niklas Laxström', 'Domas Mituzas', 'Rob Church', 'Yuri Astrakhan',
			'Aryeh Gregor', 'Aaron Schulz', 'Andrew Garrett', 'Raimond Spekking',
			'Alexandre Emsenhuber', 'Siebrand Mazeland', 'Chad Horohoe',
			'Roan Kattouw', 'Trevor Parscal', 'Bryan Tong Minh', 'Sam Reed',
			'Victor Vasiliev', 'Rotem Liss', 'Platonides', 'Antoine Musso',
			'Timo Tijhof', 'Daniel Kinzler', 'Jeroen De Dauw', 'Brad Jorsch',
			'Bartosz Dziewoński', 'Ed Sanders', 'Moriel Schottlender',
			'Kunal Mehta', 'James D. Forrester', 'Brian Wolff', 'Adam Shorland',
			$othersLink, $translatorsLink
		];

		return wfMessage( 'version-poweredby-credits', MWTimestamp::getLocalInstance()->format( 'Y' ),
			$wgLang->listToText( $authorList ) )->text();
	}

	/**
	 * @since 1.34
	 *
	 * @return array
	 */
	public static function getSoftwareInformation() {
		$dbr = wfGetDB( DB_REPLICA );

		// Put the software in an array of form 'name' => 'version'. All messages should
		// be loaded here, so feel free to use wfMessage in the 'name'. Raw HTML or
		// wikimarkup can be used.
		$software = [
			'[https://www.mediawiki.org/ MediaWiki]' => self::getVersionLinked(),
			'[https://php.net/ PHP]' => PHP_VERSION . " (" . PHP_SAPI . ")",
			$dbr->getSoftwareLink() => $dbr->getServerInfo(),
		];

		if ( defined( 'INTL_ICU_VERSION' ) ) {
			$software['[http://site.icu-project.org/ ICU]'] = INTL_ICU_VERSION;
		}

		// Allow a hook to add/remove items.
		Hooks::runner()->onSoftwareInfo( $software );

		return $software;
	}

	/**
	 * Returns HTML showing the third party software versions (apache, php, mysql).
	 *
	 * @return string HTML table
	 */
	public static function softwareInformation() {
		$out = Xml::element(
				'h2',
				[ 'id' => 'mw-version-software' ],
				wfMessage( 'version-software' )->text()
			) .
			Xml::openElement( 'table', [ 'class' => 'wikitable plainlinks', 'id' => 'sv-software' ] ) .
			"<tr>
				<th>" . wfMessage( 'version-software-product' )->text() . "</th>
				<th>" . wfMessage( 'version-software-version' )->text() . "</th>
			</tr>\n";

		foreach ( self::getSoftwareInformation() as $name => $version ) {
			$out .= "<tr>
					<td>" . $name . "</td>
					<td dir=\"ltr\">" . $version . "</td>
				</tr>\n";
		}

		return $out . Xml::closeElement( 'table' );
	}

	/**
	 * Return a string of the MediaWiki version with Git revision if available.
	 *
	 * @param string $flags
	 * @param Language|string|null $lang
	 * @return mixed
	 */
	public static function getVersion( $flags = '', $lang = null ) {
		global $IP;

		$gitInfo = self::getGitHeadSha1( $IP );
		if ( !$gitInfo ) {
			$version = MW_VERSION;
		} elseif ( $flags === 'nodb' ) {
			$shortSha1 = substr( $gitInfo, 0, 7 );
			$version = MW_VERSION . " ($shortSha1)";
		} else {
			$shortSha1 = substr( $gitInfo, 0, 7 );
			$msg = wfMessage( 'parentheses' );
			if ( $lang !== null ) {
				$msg->inLanguage( $lang );
			}
			$shortSha1 = $msg->params( $shortSha1 )->escaped();
			$version = MW_VERSION . ' ' . $shortSha1;
		}

		return $version;
	}

	/**
	 * Return a wikitext-formatted string of the MediaWiki version with a link to
	 * the Git SHA1 of head if available.
	 * The fallback is just MW_VERSION.
	 *
	 * @return mixed
	 */
	public static function getVersionLinked() {
		$gitVersion = self::getVersionLinkedGit();
		if ( $gitVersion ) {
			$v = $gitVersion;
		} else {
			$v = MW_VERSION; // fallback
		}

		return $v;
	}

	/**
	 * @return string
	 */
	private static function getMWVersionLinked() {
		$versionUrl = "";
		if ( Hooks::runner()->onSpecialVersionVersionUrl( MW_VERSION, $versionUrl ) ) {
			$versionParts = [];
			preg_match( "/^(\d+\.\d+)/", MW_VERSION, $versionParts );
			$versionUrl = "https://www.mediawiki.org/wiki/MediaWiki_{$versionParts[1]}";
		}

		return '[' . $versionUrl . ' ' . MW_VERSION . ']';
	}

	/**
	 * @since 1.22 Includes the date of the Git HEAD commit
	 * @return bool|string MW version and Git HEAD (SHA1 stripped to the first 7 chars)
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

		return self::getMWVersionLinked() . " $shortSHA1";
	}

	/**
	 * Returns an array with the base extension types.
	 * Type is stored as array key, the message as array value.
	 *
	 * TODO: ideally this would return all extension types.
	 *
	 * @since 1.17
	 *
	 * @return string[]
	 */
	public static function getExtensionTypes() {
		if ( self::$extensionTypes === false ) {
			self::$extensionTypes = [
				'specialpage' => wfMessage( 'version-specialpages' )->text(),
				'editor' => wfMessage( 'version-editors' )->text(),
				'parserhook' => wfMessage( 'version-parserhooks' )->text(),
				'variable' => wfMessage( 'version-variables' )->text(),
				'media' => wfMessage( 'version-mediahandlers' )->text(),
				'antispam' => wfMessage( 'version-antispam' )->text(),
				'skin' => wfMessage( 'version-skins' )->text(),
				'api' => wfMessage( 'version-api' )->text(),
				'other' => wfMessage( 'version-other' )->text(),
			];

			Hooks::runner()->onExtensionTypes( self::$extensionTypes );
		}

		return self::$extensionTypes;
	}

	/**
	 * Returns the internationalized name for an extension type.
	 *
	 * @since 1.17
	 *
	 * @param string $type
	 *
	 * @return string
	 */
	public static function getExtensionTypeName( $type ) {
		$types = self::getExtensionTypes();

		return $types[$type] ?? $types['other'];
	}

	/**
	 * Generate wikitext showing the name, URL, author and description of each extension.
	 *
	 * @param array $credits
	 * @return string Wikitext
	 */
	private function getExtensionCredits( array $credits ) {
		if (
			!$credits ||
			// Skins are displayed separately, see getSkinCredits()
			( count( $credits ) === 1 && isset( $credits['skin'] ) )
		) {
			return '';
		}

		$extensionTypes = self::getExtensionTypes();

		$out = Xml::element(
				'h2',
				[ 'id' => 'mw-version-ext' ],
				$this->msg( 'version-extensions' )->text()
			) .
			Xml::openElement( 'table', [ 'class' => 'wikitable plainlinks', 'id' => 'sv-ext' ] );

		// Make sure the 'other' type is set to an array.
		if ( !array_key_exists( 'other', $credits ) ) {
			$credits['other'] = [];
		}

		// Find all extensions that do not have a valid type and give them the type 'other'.
		foreach ( $credits as $type => $extensions ) {
			if ( !array_key_exists( $type, $extensionTypes ) ) {
				$credits['other'] = array_merge( $credits['other'], $extensions );
			}
		}

		$this->firstExtOpened = false;
		// Loop through the extension categories to display their extensions in the list.
		foreach ( $extensionTypes as $type => $message ) {
			// Skins have a separate section
			if ( $type !== 'other' && $type !== 'skin' ) {
				$out .= $this->getExtensionCategory( $type, $message, $credits[$type] ?? [] );
			}
		}

		// We want the 'other' type to be last in the list.
		$out .= $this->getExtensionCategory( 'other', $extensionTypes['other'], $credits['other'] );

		$out .= Xml::closeElement( 'table' );

		return $out;
	}

	/**
	 * Generate wikitext showing the name, URL, author and description of each skin.
	 *
	 * @param array $credits
	 * @return string Wikitext
	 */
	private function getSkinCredits( array $credits ) {
		if ( !isset( $credits['skin'] ) || count( $credits['skin'] ) === 0 ) {
			return '';
		}

		$out = Xml::element(
				'h2',
				[ 'id' => 'mw-version-skin' ],
				$this->msg( 'version-skins' )->text()
			) .
			Xml::openElement( 'table', [ 'class' => 'wikitable plainlinks', 'id' => 'sv-skin' ] );

		$this->firstExtOpened = false;
		$out .= $this->getExtensionCategory( 'skin', null, $credits['skin'] );

		$out .= Xml::closeElement( 'table' );

		return $out;
	}

	/**
	 * Generate an HTML table for external libraries that are installed
	 *
	 * @param array $credits
	 * @return string
	 */
	protected function getExternalLibraries( array $credits ) {
		global $IP;
		$paths = [
			"$IP/vendor/composer/installed.json"
		];

		$extensionTypes = self::getExtensionTypes();
		foreach ( $extensionTypes as $type => $message ) {
			if ( !isset( $credits[$type] ) || $credits[$type] === [] ) {
				continue;
			}
			foreach ( $credits[$type] as $extension ) {
				if ( !isset( $extension['path'] ) ) {
					continue;
				}
				$paths[] = dirname( $extension['path'] ) . '/vendor/composer/installed.json';
			}
		}

		$dependencies = [];

		foreach ( $paths as $path ) {
			if ( !file_exists( $path ) ) {
				continue;
			}

			$installed = new ComposerInstalled( $path );

			$dependencies += $installed->getInstalledDependencies();
		}

		if ( $dependencies === [] ) {
			return '';
		}

		ksort( $dependencies );

		$out = Html::element(
			'h2',
			[ 'id' => 'mw-version-libraries' ],
			$this->msg( 'version-libraries' )->text()
		);
		$out .= Html::openElement(
			'table',
			[ 'class' => 'wikitable plainlinks', 'id' => 'sv-libraries' ]
		);
		$out .= Html::openElement( 'tr' )
			. Html::element( 'th', [], $this->msg( 'version-libraries-library' )->text() )
			. Html::element( 'th', [], $this->msg( 'version-libraries-version' )->text() )
			. Html::element( 'th', [], $this->msg( 'version-libraries-license' )->text() )
			. Html::element( 'th', [], $this->msg( 'version-libraries-description' )->text() )
			. Html::element( 'th', [], $this->msg( 'version-libraries-authors' )->text() )
			. Html::closeElement( 'tr' );

		foreach ( $dependencies as $name => $info ) {
			if ( !is_array( $info ) || strpos( $info['type'], 'mediawiki-' ) === 0 ) {
				// Skip any extensions or skins since they'll be listed
				// in their proper section
				continue;
			}
			$authors = array_map( function ( $arr ) {
				// If a homepage is set, link to it
				if ( isset( $arr['homepage'] ) ) {
					return "[{$arr['homepage']} {$arr['name']}]";
				}
				return $arr['name'];
			}, $info['authors'] );
			$authors = $this->listAuthors( $authors, false, "$IP/vendor/$name" );

			// We can safely assume that the libraries' names and descriptions
			// are written in English and aren't going to be translated,
			// so set appropriate lang and dir attributes
			$out .= Html::openElement( 'tr', [
				// Add an anchor so docs can link easily to the version of
				// this specific library
				'id' => Sanitizer::escapeIdForAttribute(
					"mw-version-library-$name"
				) ] )
				. Html::rawElement(
					'td',
					[],
					Linker::makeExternalLink(
						"https://packagist.org/packages/$name", $name,
						true, '',
						[ 'class' => 'mw-version-library-name' ]
					)
				)
				. Html::element( 'td', [ 'dir' => 'auto' ], $info['version'] )
				. Html::element( 'td', [ 'dir' => 'auto' ], $this->listToText( $info['licenses'] ) )
				. Html::element( 'td', [ 'lang' => 'en', 'dir' => 'ltr' ], $info['description'] )
				. Html::rawElement( 'td', [], $authors )
				. Html::closeElement( 'tr' );
		}
		$out .= Html::closeElement( 'table' );

		return $out;
	}

	/**
	 * Obtains a list of installed parser tags and the associated H2 header
	 *
	 * @return string HTML output
	 */
	protected function getParserTags() {
		$tags = MediaWikiServices::getInstance()->getParser()->getTags();

		if ( count( $tags ) ) {
			$out = Html::rawElement(
				'h2',
				[
					'class' => 'mw-headline plainlinks',
					'id' => 'mw-version-parser-extensiontags',
				],
				Linker::makeExternalLink(
					'https://www.mediawiki.org/wiki/Special:MyLanguage/Manual:Tag_extensions',
					$this->msg( 'version-parser-extensiontags' )->parse(),
					false /* msg()->parse() already escapes */
				)
			);

			array_walk( $tags, function ( &$value ) {
				// Bidirectional isolation improves readability in RTL wikis
				$value = Html::element(
					'bdi',
					// Prevent < and > from slipping to another line
					[
						'style' => 'white-space: nowrap;',
					],
					"<$value>"
				);
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
		$fhooks = MediaWikiServices::getInstance()->getParser()->getFunctionHooks();
		if ( count( $fhooks ) ) {
			$out = Html::rawElement(
				'h2',
				[
					'class' => 'mw-headline plainlinks',
					'id' => 'mw-version-parser-function-hooks',
				],
				Linker::makeExternalLink(
					'https://www.mediawiki.org/wiki/Special:MyLanguage/Manual:Parser_functions',
					$this->msg( 'version-parser-function-hooks' )->parse(),
					false /* msg()->parse() already escapes */
				)
			);

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
	 * @param string $type
	 * @param string|null $message
	 * @param array $creditsGroup
	 * @return string
	 */
	protected function getExtensionCategory( $type, $message, array $creditsGroup ) {
		$config = $this->getConfig();
		$credits = $config->get( 'ExtensionCredits' );

		$out = '';

		if ( $creditsGroup ) {
			$out .= $this->openExtType( $message, 'credits-' . $type );

			usort( $creditsGroup, [ $this, 'compare' ] );

			foreach ( $creditsGroup as $extension ) {
				$out .= $this->getCreditsForExtension( $type, $extension );
			}
		}

		return $out;
	}

	/**
	 * Callback to sort extensions by type.
	 * @param array $a
	 * @param array $b
	 * @return int
	 */
	public function compare( $a, $b ) {
		return $this->getLanguage()->lc( $a['name'] ) <=> $this->getLanguage()->lc( $b['name'] );
	}

	/**
	 * Creates and formats a version line for a single extension.
	 *
	 * Information for five columns will be created. Parameters required in the
	 * $extension array for part rendering are indicated in ()
	 *  - The name of (name), and URL link to (url), the extension
	 *  - Official version number (version) and if available version control system
	 *    revision (path), link, and date
	 *  - If available the short name of the license (license-name) and a link
	 *    to ((LICENSE)|(COPYING))(\.txt)? if it exists.
	 *  - Description of extension (descriptionmsg or description)
	 *  - List of authors (author) and link to a ((AUTHORS)|(CREDITS))(\.txt)? file if it exists
	 *
	 * @param string $type Category name of the extension
	 * @param array $extension
	 *
	 * @return string Raw HTML
	 */
	public function getCreditsForExtension( $type, array $extension ) {
		$out = $this->getOutput();

		// We must obtain the information for all the bits and pieces!
		// ... such as extension names and links
		if ( isset( $extension['namemsg'] ) ) {
			// Localized name of extension
			$extensionName = $this->msg( $extension['namemsg'] )->text();
		} elseif ( isset( $extension['name'] ) ) {
			// Non localized version
			$extensionName = $extension['name'];
		} else {
			$extensionName = $this->msg( 'version-no-ext-name' )->text();
		}

		if ( isset( $extension['url'] ) ) {
			$extensionNameLink = Linker::makeExternalLink(
				$extension['url'],
				$extensionName,
				true,
				'',
				[ 'class' => 'mw-version-ext-name' ]
			);
		} else {
			$extensionNameLink = htmlspecialchars( $extensionName );
		}

		// ... and the version information
		// If the extension path is set we will check that directory for GIT
		// metadata in an attempt to extract date and vcs commit metadata.
		$canonicalVersion = '&ndash;';
		$extensionPath = null;
		$vcsVersion = null;
		$vcsLink = null;
		$vcsDate = null;

		if ( isset( $extension['version'] ) ) {
			$canonicalVersion = $out->parseInlineAsInterface( $extension['version'] );
		}

		if ( isset( $extension['path'] ) ) {
			global $IP;
			$extensionPath = dirname( $extension['path'] );
			if ( $this->coreId == '' ) {
				wfDebug( 'Looking up core head id' );
				$coreHeadSHA1 = self::getGitHeadSha1( $IP );
				if ( $coreHeadSHA1 ) {
					$this->coreId = $coreHeadSHA1;
				}
			}
			$cache = wfGetCache( CACHE_ANYTHING );
			$memcKey = $cache->makeKey(
				'specialversion-ext-version-text', $extension['path'], $this->coreId
			);
			list( $vcsVersion, $vcsLink, $vcsDate ) = $cache->get( $memcKey );

			if ( !$vcsVersion ) {
				wfDebug( "Getting VCS info for extension {$extension['name']}" );
				$gitInfo = new GitInfo( $extensionPath );
				$vcsVersion = $gitInfo->getHeadSHA1();
				if ( $vcsVersion !== false ) {
					$vcsVersion = substr( $vcsVersion, 0, 7 );
					$vcsLink = $gitInfo->getHeadViewUrl();
					$vcsDate = $gitInfo->getHeadCommitDate();
				}
				$cache->set( $memcKey, [ $vcsVersion, $vcsLink, $vcsDate ], 60 * 60 * 24 );
			} else {
				wfDebug( "Pulled VCS info for extension {$extension['name']} from cache" );
			}
		}

		$versionString = Html::rawElement(
			'span',
			[ 'class' => 'mw-version-ext-version' ],
			$canonicalVersion
		);

		if ( $vcsVersion ) {
			if ( $vcsLink ) {
				$vcsVerString = Linker::makeExternalLink(
					$vcsLink,
					$this->msg( 'version-version', $vcsVersion ),
					true,
					'',
					[ 'class' => 'mw-version-ext-vcs-version' ]
				);
			} else {
				$vcsVerString = Html::element( 'span',
					[ 'class' => 'mw-version-ext-vcs-version' ],
					"({$vcsVersion})"
				);
			}
			$versionString .= " {$vcsVerString}";

			if ( $vcsDate ) {
				$versionString .= ' ' . Html::element( 'span', [
					'class' => 'mw-version-ext-vcs-timestamp',
					'dir' => $this->getLanguage()->getDir(),
				], $this->getLanguage()->timeanddate( $vcsDate, true ) );
			}
			$versionString = Html::rawElement( 'span',
				[ 'class' => 'mw-version-ext-meta-version' ],
				$versionString
			);
		}

		// ... and license information; if a license file exists we
		// will link to it
		$licenseLink = '';
		if ( isset( $extension['name'] ) ) {
			$licenseName = null;
			if ( isset( $extension['license-name'] ) ) {
				$licenseName = new HtmlArmor( $out->parseInlineAsInterface( $extension['license-name'] ) );
			} elseif ( ExtensionInfo::getLicenseFileNames( $extensionPath ) ) {
				$licenseName = $this->msg( 'version-ext-license' )->text();
			}
			if ( $licenseName !== null ) {
				$licenseLink = $this->getLinkRenderer()->makeLink(
					$this->getPageTitle( 'License/' . $extension['name'] ),
					$licenseName,
					[
						'class' => 'mw-version-ext-license',
						'dir' => 'auto',
					]
				);
			}
		}

		// ... and generate the description; which can be a parameterized l10n message
		// in the form [ <msgname>, <parameter>, <parameter>... ] or just a straight
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
		$description = $out->parseInlineAsInterface( $description );

		// ... now get the authors for this extension
		$authors = $extension['author'] ?? [];
		$authors = $this->listAuthors( $authors, $extension['name'], $extensionPath );

		// Finally! Create the table
		$html = Html::openElement( 'tr', [
				'class' => 'mw-version-ext',
				'id' => Sanitizer::escapeIdForAttribute( 'mw-version-ext-' . $type . '-' . $extension['name'] )
			]
		);

		$html .= Html::rawElement( 'td', [], $extensionNameLink );
		$html .= Html::rawElement( 'td', [], $versionString );
		$html .= Html::rawElement( 'td', [], $licenseLink );
		$html .= Html::rawElement( 'td', [ 'class' => 'mw-version-ext-description' ], $description );
		$html .= Html::rawElement( 'td', [ 'class' => 'mw-version-ext-authors' ], $authors );

		$html .= Html::closeElement( 'tr' );

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

			$ret = [];
			$ret[] = '== {{int:version-hooks}} ==';
			$ret[] = Html::openElement( 'table', [ 'class' => 'wikitable', 'id' => 'sv-hooks' ] );
			$ret[] = Html::openElement( 'tr' );
			$ret[] = Html::element( 'th', [], $this->msg( 'version-hook-name' )->text() );
			$ret[] = Html::element( 'th', [], $this->msg( 'version-hook-subscribedby' )->text() );
			$ret[] = Html::closeElement( 'tr' );

			foreach ( $myWgHooks as $hook => $hooks ) {
				$ret[] = Html::openElement( 'tr' );
				$ret[] = Html::element( 'td', [], $hook );
				$ret[] = Html::element( 'td', [], $this->listToText( $hooks ) );
				$ret[] = Html::closeElement( 'tr' );
			}

			$ret[] = Html::closeElement( 'table' );

			return implode( "\n", $ret );
		}

		return '';
	}

	private function openExtType( $text = null, $name = null ) {
		$out = '';

		$opt = [ 'colspan' => 5 ];
		if ( $this->firstExtOpened ) {
			// Insert a spacing line
			$out .= Html::rawElement( 'tr', [ 'class' => 'sv-space' ],
				Html::element( 'td', $opt )
			);
		}
		$this->firstExtOpened = true;

		if ( $name ) {
			$opt['id'] = "sv-$name";
		}

		if ( $text !== null ) {
			$out .= Html::rawElement( 'tr', [],
				Html::element( 'th', $opt, $text )
			);
		}

		$firstHeadingMsg = ( $name === 'credits-skin' )
			? 'version-skin-colheader-name'
			: 'version-ext-colheader-name';
		$out .= Html::openElement( 'tr' );
		$out .= Html::element( 'th', [ 'class' => 'mw-version-ext-col-label' ],
			$this->msg( $firstHeadingMsg )->text() );
		$out .= Html::element( 'th', [ 'class' => 'mw-version-ext-col-label' ],
			$this->msg( 'version-ext-colheader-version' )->text() );
		$out .= Html::element( 'th', [ 'class' => 'mw-version-ext-col-label' ],
			$this->msg( 'version-ext-colheader-license' )->text() );
		$out .= Html::element( 'th', [ 'class' => 'mw-version-ext-col-label' ],
			$this->msg( 'version-ext-colheader-description' )->text() );
		$out .= Html::element( 'th', [ 'class' => 'mw-version-ext-col-label' ],
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
	 * @param string|bool $extName Name of the extension for link creation,
	 *   false if no links should be created
	 * @param string $extDir Path to the extension root directory
	 *
	 * @return string HTML fragment
	 */
	public function listAuthors( $authors, $extName, $extDir ) {
		$hasOthers = false;
		$linkRenderer = $this->getLinkRenderer();

		$list = [];
		$authors = (array)$authors;

		// Special case: if the authors array has only one item and it is "...",
		// it should not be rendered as the "version-poweredby-others" i18n msg,
		// but rather as "version-poweredby-various" i18n msg instead.
		if ( count( $authors ) === 1 && $authors[0] === '...' ) {
			// Link to the extension's or skin's AUTHORS or CREDITS file, if there is
			// such a file; otherwise just return the i18n msg as-is
			if ( $extName && ExtensionInfo::getAuthorsFileName( $extDir ) ) {
				return $linkRenderer->makeLink(
					$this->getPageTitle( "Credits/$extName" ),
					$this->msg( 'version-poweredby-various' )->text()
				);
			} else {
				return $this->msg( 'version-poweredby-various' )->escaped();
			}
		}

		// Otherwise, if we have an actual array that has more than one item,
		// process each array item as usual
		foreach ( $authors as $item ) {
			if ( $item == '...' ) {
				$hasOthers = true;

				if ( $extName && ExtensionInfo::getAuthorsFileName( $extDir ) ) {
					$text = $linkRenderer->makeLink(
						$this->getPageTitle( "Credits/$extName" ),
						$this->msg( 'version-poweredby-others' )->text()
					);
				} else {
					$text = $this->msg( 'version-poweredby-others' )->escaped();
				}
				$list[] = $text;
			} elseif ( substr( $item, -5 ) == ' ...]' ) {
				$hasOthers = true;
				$list[] = $this->getOutput()->parseInlineAsInterface(
					substr( $item, 0, -4 ) . $this->msg( 'version-poweredby-others' )->text() . "]"
				);
			} else {
				$list[] = $this->getOutput()->parseInlineAsInterface( $item );
			}
		}

		if ( $extName && !$hasOthers && ExtensionInfo::getAuthorsFileName( $extDir ) ) {
			$list[] = $text = $linkRenderer->makeLink(
				$this->getPageTitle( "Credits/$extName" ),
				$this->msg( 'version-poweredby-others' )->text()
			);
		}

		return $this->listToText( $list, false );
	}

	/**
	 * Obtains the full path of an extensions AUTHORS or CREDITS file if
	 * one exists.
	 *
	 * @param string $extDir Path to the extensions root directory
	 *
	 * @since 1.23
	 * @deprecated since 1.35 Use MediaWiki\ExtensionInfo::getAuthorsFileName()
	 *
	 * @return bool|string False if no such file exists, otherwise returns
	 * a path to it.
	 */
	public static function getExtAuthorsFileName( $extDir ) {
		wfDeprecated( __METHOD__, '1.35' );
		return ExtensionInfo::getAuthorsFileName( $extDir );
	}

	/**
	 * Obtains the full path of an extensions COPYING or LICENSE file if
	 * one exists.
	 *
	 * @param string $extDir Path to the extensions root directory
	 *
	 * @since 1.23
	 * @deprecated since 1.35 Use MediaWiki\ExtensionInfo::getLicenseFileNames()
	 *
	 * @return bool|string False if no such file exists, otherwise returns
	 * a path to it.
	 */
	public static function getExtLicenseFileName( $extDir ) {
		wfDeprecated( __METHOD__, '1.35' );
		$licenses = ExtensionInfo::getLicenseFileNames( $extDir );
		if ( count( $licenses ) === 0 ) {
			return false;
		}
		return $licenses[0];
	}

	/**
	 * Convert an array of items into a list for display.
	 *
	 * @param array $list List of elements to display
	 * @param bool $sort Whether to sort the items in $list
	 *
	 * @return string
	 */
	public function listToText( $list, $sort = true ) {
		if ( !count( $list ) ) {
			return '';
		}
		if ( $sort ) {
			sort( $list );
		}

		return $this->getLanguage()
			->listToText( array_map( [ __CLASS__, 'arrayToString' ], $list ) );
	}

	/**
	 * Convert an array or object to a string for display.
	 *
	 * @param mixed $list Will convert an array to string if given and return
	 *   the parameter unaltered otherwise
	 *
	 * @return mixed
	 */
	public static function arrayToString( $list ) {
		if ( is_array( $list ) && count( $list ) == 1 ) {
			$list = $list[0];
		}
		if ( $list instanceof Closure ) {
			// Don't output stuff like "Closure$;1028376090#8$48499d94fe0147f7c633b365be39952b$"
			return 'Closure';
		} elseif ( is_object( $list ) ) {
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
	 * @param string $dir Directory of the git checkout
	 * @return bool|string Sha1 of commit HEAD points to
	 */
	public static function getGitHeadSha1( $dir ) {
		$repo = new GitInfo( $dir );

		return $repo->getHeadSHA1();
	}

	/**
	 * @param string $dir Directory of the git checkout
	 * @return bool|string Branch currently checked out
	 */
	public static function getGitCurrentBranch( $dir ) {
		$repo = new GitInfo( $dir );
		return $repo->getCurrentBranch();
	}

	/**
	 * Get the list of entry points and their URLs
	 * @return string Wikitext
	 */
	public function getEntryPointInfo() {
		$config = $this->getConfig();
		$scriptPath = $config->get( 'ScriptPath' ) ?: '/';

		$entryPoints = [
			'version-entrypoints-articlepath' => $config->get( 'ArticlePath' ),
			'version-entrypoints-scriptpath' => $scriptPath,
			'version-entrypoints-index-php' => wfScript( 'index' ),
			'version-entrypoints-api-php' => wfScript( 'api' ),
			'version-entrypoints-rest-php' => wfScript( 'rest' ),
		];

		$language = $this->getLanguage();
		$thAttribures = [
			'dir' => $language->getDir(),
			'lang' => $language->getHtmlCode()
		];
		$out = Html::element(
				'h2',
				[ 'id' => 'mw-version-entrypoints' ],
				$this->msg( 'version-entrypoints' )->text()
			) .
			Html::openElement( 'table',
				[
					'class' => 'wikitable plainlinks',
					'id' => 'mw-version-entrypoints-table',
					'dir' => 'ltr',
					'lang' => 'en'
				]
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
				// ->plain() looks like it should be ->parse(), but this function
				// returns wikitext, not HTML, boo
				Html::rawElement( 'td', [], $this->msg( $message )->plain() ) .
				Html::rawElement( 'td', [], Html::rawElement( 'code', [], "[$url $value]" ) ) .
				Html::closeElement( 'tr' );
		}

		$out .= Html::closeElement( 'table' );

		return $out;
	}

	protected function getGroupName() {
		return 'wiki';
	}
}
