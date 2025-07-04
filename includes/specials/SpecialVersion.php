<?php
/**
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
 */

namespace MediaWiki\Specials;

use Closure;
use MediaWiki\Config\Config;
use MediaWiki\HookContainer\HookRunner;
use MediaWiki\Html\Html;
use MediaWiki\Language\Language;
use MediaWiki\Language\RawMessage;
use MediaWiki\MainConfigNames;
use MediaWiki\MediaWikiServices;
use MediaWiki\Message\Message;
use MediaWiki\Parser\ParserFactory;
use MediaWiki\Parser\Sanitizer;
use MediaWiki\Registration\ExtensionRegistry;
use MediaWiki\SpecialPage\SpecialPage;
use MediaWiki\Utils\ExtensionInfo;
use MediaWiki\Utils\GitInfo;
use MediaWiki\Utils\MWTimestamp;
use MediaWiki\Utils\UrlUtils;
use Symfony\Component\Yaml\Yaml;
use Wikimedia\Composer\ComposerInstalled;
use Wikimedia\HtmlArmor\HtmlArmor;
use Wikimedia\Parsoid\Core\SectionMetadata;
use Wikimedia\Parsoid\Core\TOCData;
use Wikimedia\Rdbms\IConnectionProvider;

/**
 * Version information about MediaWiki (core, extensions, libs), PHP, and the database.
 *
 * @ingroup SpecialPage
 */
class SpecialVersion extends SpecialPage {

	/**
	 * @var string The current rev id/SHA hash of MediaWiki core
	 */
	protected $coreId = '';

	/**
	 * @var string[]|false Lazy initialized key/value with message content
	 */
	protected static $extensionTypes = false;

	/** @var TOCData */
	protected $tocData;

	/** @var int */
	protected $tocIndex;

	/** @var int */
	protected $tocSection;

	/** @var int */
	protected $tocSubSection;

	private ParserFactory $parserFactory;
	private UrlUtils $urlUtils;
	private IConnectionProvider $dbProvider;

	public function __construct(
		ParserFactory $parserFactory,
		UrlUtils $urlUtils,
		IConnectionProvider $dbProvider
	) {
		parent::__construct( 'Version' );
		$this->parserFactory = $parserFactory;
		$this->urlUtils = $urlUtils;
		$this->dbProvider = $dbProvider;
	}

	/**
	 * @since 1.35
	 * @param ExtensionRegistry $reg
	 * @param Config $conf For additional entries from $wgExtensionCredits.
	 * @return array[]
	 * @see $wgExtensionCredits
	 */
	public static function getCredits( ExtensionRegistry $reg, Config $conf ): array {
		$credits = $conf->get( MainConfigNames::ExtensionCredits );
		foreach ( $reg->getAllThings() as $credit ) {
			$credits[$credit['type']][] = $credit;
		}
		return $credits;
	}

	/**
	 * @param string|null $par
	 */
	public function execute( $par ) {
		$config = $this->getConfig();
		$credits = self::getCredits( ExtensionRegistry::getInstance(), $config );

		$this->setHeaders();
		$this->outputHeader();
		$out = $this->getOutput();
		$out->getMetadata()->setPreventClickjacking( false );

		// Explode the subpage information into useful bits
		$parts = explode( '/', (string)$par );
		$extNode = null;
		if ( isset( $parts[1] ) ) {
			$extName = str_replace( '_', ' ', $parts[1] );
			// Find it!
			foreach ( $credits as $extensions ) {
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
				$out->addModuleStyles( 'mediawiki.special' );

				$wikiText = '{{int:version-credits-not-found}}';
				if ( $extName === 'MediaWiki' ) {
					$wikiText = file_get_contents( MW_INSTALL_PATH . '/CREDITS' );
					// Put the contributor list into columns
					$wikiText = str_replace(
						[ '<!-- BEGIN CONTRIBUTOR LIST -->', '<!-- END CONTRIBUTOR LIST -->' ],
						[ '<div class="mw-version-credits">', '</div>' ],
						$wikiText
					);
				} elseif ( ( $extNode !== null ) && isset( $extNode['path'] ) ) {
					$file = ExtensionInfo::getAuthorsFileName( dirname( $extNode['path'] ) );
					if ( $file ) {
						$wikiText = file_get_contents( $file );
						if ( str_ends_with( $file, '.txt' ) ) {
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

				$out->setPageTitleMsg( $this->msg( 'version-credits-title' )->plaintextParams( $extName ) );
				$out->addWikiTextAsInterface( $wikiText );
				break;

			case 'license':
				$out->setPageTitleMsg( $this->msg( 'version-license-title' )->plaintextParams( $extName ) );

				$licenseFound = false;

				if ( $extName === 'MediaWiki' ) {
					$out->addWikiTextAsInterface(
						file_get_contents( MW_INSTALL_PATH . '/COPYING' )
					);
					$licenseFound = true;
				} elseif ( ( $extNode !== null ) && isset( $extNode['path'] ) ) {
					$files = ExtensionInfo::getLicenseFileNames( dirname( $extNode['path'] ) );
					if ( $files ) {
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
				$out->addModuleStyles( 'mediawiki.special' );

				$out->addHTML( $this->getMediaWikiCredits() );

				$this->tocData = new TOCData();
				$this->tocIndex = 0;
				$this->tocSection = 0;
				$this->tocSubSection = 0;

				// Build the page contents (this also fills in TOCData)
				$sections = [
					$this->softwareInformation(),
					$this->getEntryPointInfo(),
					$this->getSkinCredits( $credits ),
					$this->getExtensionCredits( $credits ),
					$this->getLibraries( $credits ),
					$this->getParserTags(),
					$this->getParserFunctionHooks(),
					$this->getParsoidModules(),
					$this->getHooks(),
					$this->IPInfo(),
				];

				// Insert TOC first
				$out->addTOCPlaceholder( $this->tocData );

				// Insert contents
				foreach ( $sections as $content ) {
					$out->addHTML( $content );
				}

				break;
		}
	}

	/**
	 * Add a section to the table of contents. This doesn't add the heading to the actual page.
	 * Assumes the IDs don't use non-ASCII characters.
	 *
	 * @param string $labelMsg Message key to use for the label
	 * @param string $id
	 */
	private function addTocSection( $labelMsg, $id ) {
		$this->tocIndex++;
		$this->tocSection++;
		$this->tocSubSection = 0;
		$this->tocData->addSection( new SectionMetadata(
			1,
			2,
			$this->msg( $labelMsg )->escaped(),
			$this->getLanguage()->formatNum( $this->tocSection ),
			(string)$this->tocIndex,
			null,
			null,
			$id,
			$id
		) );
	}

	/**
	 * Add a sub-section to the table of contents. This doesn't add the heading to the actual page.
	 * Assumes the IDs don't use non-ASCII characters.
	 *
	 * @param string $label Text of the label
	 * @param string $id
	 */
	private function addTocSubSection( $label, $id ) {
		$this->tocIndex++;
		$this->tocSubSection++;
		$this->tocData->addSection( new SectionMetadata(
			2,
			3,
			htmlspecialchars( $label ),
			// See Parser::localizeTOC
			$this->getLanguage()->formatNum( $this->tocSection ) . '.' .
				$this->getLanguage()->formatNum( $this->tocSubSection ),
			(string)$this->tocIndex,
			null,
			null,
			$id,
			$id
		) );
	}

	/**
	 * Returns HTML showing the license information.
	 *
	 * @return string HTML
	 */
	private function getMediaWikiCredits() {
		// No TOC entry for this heading, we treat it like the lede section

		$ret = Html::element(
			'h2',
			[ 'id' => 'mw-version-license' ],
			$this->msg( 'version-license' )->text()
		);

		$ret .= Html::rawElement( 'div', [ 'class' => 'plainlinks' ],
			$this->msg( new RawMessage( self::getCopyrightAndAuthorList() ) )->parseAsBlock() .
			Html::rawElement( 'div', [ 'class' => 'mw-version-license-info' ],
				$this->msg( 'version-license-info' )->parseAsBlock()
			)
		);

		return $ret;
	}

	/**
	 * Get the "MediaWiki is copyright 2001-20xx by lots of cool folks" text
	 *
	 * @internal For use by WebInstallerWelcome
	 * @return string Wikitext
	 */
	public static function getCopyrightAndAuthorList() {
		if ( defined( 'MEDIAWIKI_INSTALL' ) ) {
			$othersLink = '[https://www.mediawiki.org/wiki/Special:Version/Credits ' .
				wfMessage( 'version-poweredby-others' )->plain() . ']';
		} else {
			$othersLink = '[[Special:Version/Credits|' .
				wfMessage( 'version-poweredby-others' )->plain() . ']]';
		}

		$translatorsLink = '[https://translatewiki.net/wiki/Translating:MediaWiki/Credits ' .
			wfMessage( 'version-poweredby-translators' )->plain() . ']';

		$authorList = [
			'Magnus Manske', 'Brooke Vibber', 'Lee Daniel Crocker',
			'Tim Starling', 'Erik Möller', 'Gabriel Wicke', 'Ævar Arnfjörð Bjarmason',
			'Niklas Laxström', 'Domas Mituzas', 'Rob Church', 'Yuri Astrakhan',
			'Aryeh Gregor', 'Aaron Schulz', 'Andrew Garrett', 'Raimond Spekking',
			'Alexandre Emsenhuber', 'Siebrand Mazeland', 'Chad Horohoe',
			'Roan Kattouw', 'Trevor Parscal', 'Bryan Tong Minh', 'Sam Reed',
			'Victor Vasiliev', 'Rotem Liss', 'Platonides', 'Antoine Musso',
			'Timo Tijhof', 'Daniel Kinzler', 'Jeroen De Dauw', 'Brad Jorsch',
			'Bartosz Dziewoński', 'Ed Sanders', 'Moriel Schottlender',
			'Kunal Mehta', 'James D. Forrester', 'Brian Wolff', 'Adam Shorland',
			'DannyS712', 'Ori Livneh', 'Max Semenik', 'Amir Sarabadani',
			'Derk-Jan Hartman', 'Petr Pchelko', 'Umherirrender', 'C. Scott Ananian',
			'fomafix', 'Thiemo Kreuz', 'Gergő Tisza', 'Volker E.',
			'Jack Phoenix', 'Isarra Yos',
			$othersLink, $translatorsLink
		];

		return wfMessage( 'version-poweredby-credits', MWTimestamp::getLocalInstance()->format( 'Y' ),
			Message::listParam( $authorList ) )->plain();
	}

	/**
	 * Helper for self::softwareInformation().
	 * @since 1.34
	 * @return string[] Array of wikitext strings keyed by wikitext strings
	 */
	private function getSoftwareInformation() {
		$dbr = $this->dbProvider->getReplicaDatabase();

		// Put the software in an array of form 'name' => 'version'. All messages should
		// be loaded here, so feel free to use wfMessage in the 'name'. Wikitext
		// can be used both in the name and value.
		$software = [
			'[https://www.mediawiki.org/ MediaWiki]' => self::getVersionLinked(),
			'[https://php.net/ PHP]' => PHP_VERSION . " (" . PHP_SAPI . ")",
			'[https://icu.unicode.org/ ICU]' => INTL_ICU_VERSION,
			$dbr->getSoftwareLink() => $dbr->getServerInfo(),
		];

		// T339915: If wikidiff2 is installed, show version
		if ( phpversion( "wikidiff2" ) ) {
			$software[ '[https://www.mediawiki.org/wiki/Wikidiff2 wikidiff2]' ] = phpversion( "wikidiff2" );
		}

		// Allow a hook to add/remove items.
		$this->getHookRunner()->onSoftwareInfo( $software );

		return $software;
	}

	/**
	 * Returns HTML showing the third party software versions (apache, php, mysql).
	 *
	 * @return string HTML
	 */
	private function softwareInformation() {
		$this->addTocSection( 'version-software', 'mw-version-software' );

		$out = Html::element(
			'h2',
			[ 'id' => 'mw-version-software' ],
			$this->msg( 'version-software' )->text()
		);

		$out .= Html::openElement( 'table', [ 'class' => 'wikitable plainlinks', 'id' => 'sv-software' ] );

		$out .= $this->getTableHeaderHtml( [
			$this->msg( 'version-software-product' )->text(),
			$this->msg( 'version-software-version' )->text()
		] );

		foreach ( $this->getSoftwareInformation() as $name => $version ) {
			$out .= Html::rawElement(
				'tr',
				[],
				Html::rawElement( 'td', [], $this->msg( new RawMessage( $name ) )->parse() ) .
					Html::rawElement( 'td', [ 'dir' => 'ltr' ], $this->msg( new RawMessage( $version ) )->parse() )
			);
		}

		$out .= Html::closeElement( 'table' );

		return $out;
	}

	/**
	 * Return a string of the MediaWiki version with Git revision if available.
	 *
	 * @param string $flags If set to 'nodb', the language-specific parantheses are not used.
	 * @param Language|string|null $lang Language in which to render the version; ignored if
	 *   $flags is set to 'nodb'.
	 * @return string A version string, as wikitext. This should be parsed
	 *   (unless `nodb` is set) and escaped before being inserted as HTML.
	 */
	public static function getVersion( $flags = '', $lang = null ) {
		$gitInfo = GitInfo::repo()->getHeadSHA1();
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
			$shortSha1 = $msg->params( $shortSha1 )->text();
			$version = MW_VERSION . ' ' . $shortSha1;
		}

		return $version;
	}

	/**
	 * Return a wikitext-formatted string of the MediaWiki version with a link to
	 * the Git SHA1 of head if available.
	 * The fallback is just MW_VERSION.
	 *
	 * @return string
	 */
	public static function getVersionLinked() {
		return self::getVersionLinkedGit() ?: MW_VERSION;
	}

	/**
	 * @return string
	 */
	private static function getMWVersionLinked() {
		$versionUrl = "";
		$hookRunner = new HookRunner( MediaWikiServices::getInstance()->getHookContainer() );
		if ( $hookRunner->onSpecialVersionVersionUrl( MW_VERSION, $versionUrl ) ) {
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
		global $wgLang;

		$gitInfo = new GitInfo( MW_INSTALL_PATH );
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
			$shortSHA1 .= Html::element( 'br' ) . $wgLang->timeanddate( (string)$gitHeadCommitDate, true );
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
	 * @return string[]
	 */
	public static function getExtensionTypes(): array {
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

			( new HookRunner( MediaWikiServices::getInstance()->getHookContainer() ) )
				->onExtensionTypes( self::$extensionTypes );
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
	 * Generate HTML showing the name, URL, author and description of each extension.
	 *
	 * @param array $credits
	 * @return string HTML
	 */
	private function getExtensionCredits( array $credits ) {
		$extensionTypes = self::getExtensionTypes();

		$this->addTocSection( 'version-extensions', 'mw-version-ext' );

		$out = Html::element(
			'h2',
			[ 'id' => 'mw-version-ext' ],
			$this->msg( 'version-extensions' )->text()
		);

		if (
			!$credits ||
			// Skins are displayed separately, see getSkinCredits()
			( count( $credits ) === 1 && isset( $credits['skin'] ) )
		) {
			$out .= Html::element(
				'p',
				[],
				$this->msg( 'version-extensions-no-ext' )->text()
			);

			return $out;
		}

		// Find all extensions that do not have a valid type and give them the type 'other'.
		$credits['other'] ??= [];
		foreach ( $credits as $type => $extensions ) {
			if ( !array_key_exists( $type, $extensionTypes ) ) {
				$credits['other'] = array_merge( $credits['other'], $extensions );
			}
		}

		// Loop through the extension categories to display their extensions in the list.
		foreach ( $extensionTypes as $type => $text ) {
			// Skins have a separate section
			if ( $type !== 'other' && $type !== 'skin' ) {
				$out .= $this->getExtensionCategory( $type, $text, $credits[$type] ?? [] );
			}
		}

		// We want the 'other' type to be last in the list.
		$out .= $this->getExtensionCategory( 'other', $extensionTypes['other'], $credits['other'] );

		return $out;
	}

	/**
	 * Generate HTML showing the name, URL, author and description of each skin.
	 *
	 * @param array $credits
	 * @return string HTML
	 */
	private function getSkinCredits( array $credits ) {
		$this->addTocSection( 'version-skins', 'mw-version-skin' );

		$out = Html::element(
			'h2',
			[ 'id' => 'mw-version-skin' ],
			$this->msg( 'version-skins' )->text()
		);

		if ( !isset( $credits['skin'] ) || !$credits['skin'] ) {
			$out .= Html::element(
				'p',
				[],
				$this->msg( 'version-skins-no-skin' )->text()
			);

			return $out;
		}
		$out .= $this->getExtensionCategory( 'skin', null, $credits['skin'] );

		return $out;
	}

	/**
	 * Generate the section for installed external libraries
	 *
	 * @param array $credits
	 * @return string
	 */
	protected function getLibraries( array $credits ) {
		$this->addTocSection( 'version-libraries', 'mw-version-libraries' );

		$out = Html::element(
			'h2',
			[ 'id' => 'mw-version-libraries' ],
			$this->msg( 'version-libraries' )->text()
		);

		return $out
			. $this->getExternalLibraries( $credits )
			. $this->getClientSideLibraries();
	}

	/**
	 * @internal
	 * @since 1.44
	 * @param array $credits
	 * @return array
	 */
	public static function parseComposerInstalled( array $credits ) {
		$paths = [
			MW_INSTALL_PATH . '/vendor/composer/installed.json'
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

		ksort( $dependencies );
		return $dependencies;
	}

	/**
	 * Generate an HTML table for external libraries that are installed
	 *
	 * @param array $credits
	 * @return string
	 */
	protected function getExternalLibraries( array $credits ) {
		$dependencies = self::parseComposerInstalled( $credits );
		if ( $dependencies === [] ) {
			return '';
		}

		$this->addTocSubSection( $this->msg( 'version-libraries-server' )->text(), 'mw-version-libraries-server' );

		$out = Html::element(
			'h3',
			[ 'id' => 'mw-version-libraries-server' ],
			$this->msg( 'version-libraries-server' )->text()
		);
		$out .= Html::openElement(
			'table',
			[ 'class' => 'wikitable plainlinks mw-installed-software', 'id' => 'sv-libraries' ]
		);

		$out .= $this->getTableHeaderHtml( [
			$this->msg( 'version-libraries-library' )->text(),
			$this->msg( 'version-libraries-version' )->text(),
			$this->msg( 'version-libraries-license' )->text(),
			$this->msg( 'version-libraries-description' )->text(),
			$this->msg( 'version-libraries-authors' )->text(),
		] );

		foreach ( $dependencies as $name => $info ) {
			if ( !is_array( $info ) || str_starts_with( $info['type'], 'mediawiki-' ) ) {
				// Skip any extensions or skins since they'll be listed
				// in their proper section
				continue;
			}
			$authors = array_map( static function ( $arr ) {
				return new HtmlArmor( isset( $arr['homepage'] ) ?
					Html::element( 'a', [ 'href' => $arr['homepage'] ], $arr['name'] ) :
					htmlspecialchars( $arr['name'] )
				);
			}, $info['authors'] );
			$authors = $this->listAuthors( $authors, false, MW_INSTALL_PATH . "/vendor/$name" );

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
					$this->getLinkRenderer()->makeExternalLink(
						"https://packagist.org/packages/$name",
						$name,
						$this->getFullTitle(),
						'',
						[ 'class' => 'mw-version-library-name' ]
					)
				)
				. Html::element( 'td', [ 'dir' => 'auto' ], $info['version'] )
				// @phan-suppress-next-line SecurityCheck-DoubleEscaped See FIXME in listToText
				. Html::element( 'td', [ 'dir' => 'auto' ], $this->listToText( $info['licenses'] ) )
				. Html::element( 'td', [ 'lang' => 'en', 'dir' => 'ltr' ], $info['description'] )
				. Html::rawElement( 'td', [], $authors )
				. Html::closeElement( 'tr' );
		}
		$out .= Html::closeElement( 'table' );

		return $out;
	}

	/**
	 * @internal
	 * @since 1.42
	 * @return array
	 */
	public static function parseForeignResources() {
		$registryDirs = [ 'MediaWiki' => MW_INSTALL_PATH . '/resources/lib' ]
			+ ExtensionRegistry::getInstance()->getAttribute( 'ForeignResourcesDir' );

		$modules = [];
		foreach ( $registryDirs as $source => $registryDir ) {
			$foreignResources = Yaml::parseFile( "$registryDir/foreign-resources.yaml" );
			foreach ( $foreignResources as $name => $module ) {
				$key = $name . $module['version'];
				if ( isset( $modules[$key] ) ) {
					$modules[$key]['source'][] = $source;
					continue;
				}
				$modules[$key] = $module + [ 'name' => $name, 'source' => [ $source ] ];
			}
		}
		ksort( $modules );
		return $modules;
	}

	/**
	 * Generate an HTML table for client-side libraries that are installed
	 *
	 * @return string HTML output
	 */
	private function getClientSideLibraries() {
		$this->addTocSubSection( $this->msg( 'version-libraries-client' )->text(), 'mw-version-libraries-client' );

		$out = Html::element(
			'h3',
			[ 'id' => 'mw-version-libraries-client' ],
			$this->msg( 'version-libraries-client' )->text()
		);
		$out .= Html::openElement(
			'table',
			[ 'class' => 'wikitable plainlinks mw-installed-software', 'id' => 'sv-libraries-client' ]
		);

		$out .= $this->getTableHeaderHtml( [
			$this->msg( 'version-libraries-library' )->text(),
			$this->msg( 'version-libraries-version' )->text(),
			$this->msg( 'version-libraries-license' )->text(),
			$this->msg( 'version-libraries-authors' )->text(),
			$this->msg( 'version-libraries-source' )->text()
		] );

		foreach ( self::parseForeignResources() as $name => $info ) {
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
					$this->getLinkRenderer()->makeExternalLink(
						$info['homepage'],
						$info['name'],
						$this->getFullTitle(),
						'',
						[ 'class' => 'mw-version-library-name' ]
					)
				)
				. Html::element( 'td', [ 'dir' => 'auto' ], $info['version'] )
				. Html::element( 'td', [ 'dir' => 'auto' ], $info['license'] )
				. Html::element( 'td', [ 'dir' => 'auto' ], $info['authors'] ?? '—' )
				// @phan-suppress-next-line SecurityCheck-DoubleEscaped See FIXME in listToText
				. Html::element( 'td', [ 'dir' => 'auto' ], $this->listToText( $info['source'] ) )
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
		$tags = $this->parserFactory->getMainInstance()->getTags();
		if ( !$tags ) {
			return '';
		}

		$this->addTocSection( 'version-parser-extensiontags', 'mw-version-parser-extensiontags' );

		$out = Html::rawElement(
			'h2',
			[ 'id' => 'mw-version-parser-extensiontags' ],
			Html::rawElement(
				'span',
				[ 'class' => 'plainlinks' ],
				$this->getLinkRenderer()->makeExternalLink(
					'https://www.mediawiki.org/wiki/Special:MyLanguage/Manual:Tag_extensions',
					$this->msg( 'version-parser-extensiontags' ),
					$this->getFullTitle()
				)
			)
		);

		array_walk( $tags, static function ( &$value ) {
			// Bidirectional isolation improves readability in RTL wikis
			$value = Html::rawElement(
				'bdi',
				// Prevent < and > from slipping to another line
				[
					'style' => 'white-space: nowrap;',
				],
				Html::element( 'code', [], "<$value>" )
			);
		} );

		$out .= $this->listToText( $tags );

		return $out;
	}

	/**
	 * Obtains a list of installed parser function hooks and the associated H2 header
	 *
	 * @return string HTML output
	 */
	protected function getParserFunctionHooks() {
		$funcHooks = $this->parserFactory->getMainInstance()->getFunctionHooks();
		if ( !$funcHooks ) {
			return '';
		}

		$this->addTocSection( 'version-parser-function-hooks', 'mw-version-parser-function-hooks' );

		$out = Html::rawElement(
			'h2',
			[ 'id' => 'mw-version-parser-function-hooks' ],
			Html::rawElement(
				'span',
				[ 'class' => 'plainlinks' ],
				$this->getLinkRenderer()->makeExternalLink(
					'https://www.mediawiki.org/wiki/Special:MyLanguage/Manual:Parser_functions',
					$this->msg( 'version-parser-function-hooks' ),
					$this->getFullTitle()
				)
			)
		);

		$funcSynonyms = $this->parserFactory->getMainInstance()->getFunctionSynonyms();
		// This will give us the preferred synonyms in the content language, as if
		// we used MagicWord::getSynonym( 0 ), because they appear first in the arrays.
		// We can't use MagicWord directly, because only Parser knows whether a function
		// uses the leading "#" or not. Case-sensitive functions ("1") win over
		// case-insensitive ones ("0"), like in Parser::callParserFunction().
		// There should probably be a better API for this.
		$preferredSynonyms = array_flip( array_reverse( $funcSynonyms[1] + $funcSynonyms[0] ) );
		array_walk( $funcHooks, static function ( &$value ) use ( $preferredSynonyms ) {
			$value = $preferredSynonyms[$value];
		} );
		$legacyHooks = array_flip( $funcHooks );

		// Sort case-insensitively, ignoring the leading '#' if present
		$cmpHooks = static function ( $a, $b ) {
			return strcasecmp( ltrim( $a, '#' ), ltrim( $b, '#' ) );
		};
		usort( $funcHooks, $cmpHooks );

		$formatHooks = static function ( &$value ) {
			// Bidirectional isolation ensures it displays as {{#ns}} and not {{ns#}} in RTL wikis
			$value = Html::rawElement(
				'bdi',
				[],
				Html::element( 'code', [], '{{' . $value . '}}' )
			);
		};
		array_walk( $funcHooks, $formatHooks );

		$out .= $this->getLanguage()->listToText( $funcHooks );

		# Get a list of parser functions from Parsoid as well.
		$parsoidHooks = [];
		$services = MediaWikiServices::getInstance();
		$siteConfig = $services->getParsoidSiteConfig();
		$magicWordFactory = $services->getMagicWordFactory();
		foreach ( $siteConfig->getPFragmentHandlerKeys() as $key ) {
			$config = $siteConfig->getPFragmentHandlerConfig( $key );
			if ( !( $config['options']['parserFunction'] ?? false ) ) {
				continue;
			}
			$mw = $magicWordFactory->get( $key );
			foreach ( $mw->getSynonyms() as $local ) {
				if ( !( $config['options']['nohash'] ?? false ) ) {
					$local = '#' . $local;
				}
				// Skip hooks already present in legacy hooks (they will
				// also work in parsoid)
				if ( isset( $legacyHooks[$local] ) ) {
					continue;
				}
				$parsoidHooks[] = $local;
			}
		}
		if ( $parsoidHooks ) {
			$out .= Html::element(
				'h3',
				[ 'id' => 'mw-version-parser-function-hooks-parsoid' ],
				$this->msg( 'version-parser-function-hooks-parsoid' )->text()
			);
			usort( $parsoidHooks, $cmpHooks );
			array_walk( $parsoidHooks, $formatHooks );
			$out .= $this->getLanguage()->listToText( $parsoidHooks );
		}

		return $out;
	}

	/**
	 * Obtains a list of installed Parsoid Modules and the associated H2 header
	 *
	 * @return string HTML output
	 */
	protected function getParsoidModules() {
		$siteConfig = MediaWikiServices::getInstance()->getParsoidSiteConfig();
		$modules = $siteConfig->getExtensionModules();

		if ( !$modules ) {
			return '';
		}

		$this->addTocSection( 'version-parsoid-modules', 'mw-version-parsoid-modules' );

		$out = Html::rawElement(
			'h2',
			[ 'id' => 'mw-version-parsoid-modules' ],
			Html::rawElement(
				'span',
				[ 'class' => 'plainlinks' ],
				$this->getLinkRenderer()->makeExternalLink(
					'https://www.mediawiki.org/wiki/Special:MyLanguage/Parsoid',
					$this->msg( 'version-parsoid-modules' ),
					$this->getFullTitle()
				)
			)
		);

		$moduleNames = array_map(
			static fn ( $m )=>Html::element( 'code', [
				'title' => $m->getConfig()['extension-name'] ?? null,
			], $m->getConfig()['name'] ),
			$modules
		);

		$out .= $this->getLanguage()->listToText( $moduleNames );

		return $out;
	}

	/**
	 * Creates and returns the HTML for a single extension category.
	 *
	 * @since 1.17
	 * @param string $type
	 * @param string|null $text
	 * @param array $creditsGroup
	 * @return string
	 */
	protected function getExtensionCategory( $type, ?string $text, array $creditsGroup ) {
		$out = '';

		if ( $creditsGroup ) {
			$out .= $this->openExtType( $text, 'credits-' . $type );

			usort( $creditsGroup, $this->compare( ... ) );

			foreach ( $creditsGroup as $extension ) {
				$out .= $this->getCreditsForExtension( $type, $extension );
			}

			$out .= Html::closeElement( 'table' );
		}

		return $out;
	}

	/**
	 * Callback to sort extensions by type.
	 * @param array $a
	 * @param array $b
	 * @return int
	 */
	private function compare( $a, $b ) {
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
			$extensionNameLink = $this->getLinkRenderer()->makeExternalLink(
				$extension['url'],
				$extensionName,
				$this->getFullTitle(),
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
			$extensionPath = dirname( $extension['path'] );
			if ( $this->coreId == '' ) {
				wfDebug( 'Looking up core head id' );
				$coreHeadSHA1 = GitInfo::repo()->getHeadSHA1();
				if ( $coreHeadSHA1 ) {
					$this->coreId = $coreHeadSHA1;
				}
			}
			$cache = MediaWikiServices::getInstance()->getObjectCacheFactory()->getInstance( CACHE_ANYTHING );
			$memcKey = $cache->makeKey(
				'specialversion-ext-version-text', $extension['path'], $this->coreId
			);
			[ $vcsVersion, $vcsLink, $vcsDate ] = $cache->get( $memcKey );

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
				$vcsVerString = $this->getLinkRenderer()->makeExternalLink(
					$vcsLink,
					$this->msg( 'version-version', $vcsVersion ),
					$this->getFullTitle(),
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
			} elseif ( $extensionPath !== null && ExtensionInfo::getLicenseFileNames( $extensionPath ) ) {
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
				$descriptionMsgKey = array_shift( $descriptionMsg );
				$descriptionMsg = array_map( 'htmlspecialchars', $descriptionMsg );
				$description = $this->msg( $descriptionMsgKey, ...$descriptionMsg )->text();
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
		// @phan-suppress-next-line PhanTypeMismatchArgumentNullable path is set when there is a name
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
	 * Generate HTML showing hooks in $wgHooks.
	 *
	 * @return string HTML
	 */
	private function getHooks() {
		if ( !$this->getConfig()->get( MainConfigNames::SpecialVersionShowHooks ) ) {
			return '';
		}

		$hookContainer = MediaWikiServices::getInstance()->getHookContainer();
		$hookNames = $hookContainer->getHookNames();

		if ( !$hookNames ) {
			return '';
		}

		sort( $hookNames );

		$ret = [];
		$this->addTocSection( 'version-hooks', 'mw-version-hooks' );
		$ret[] = Html::element(
			'h2',
			[ 'id' => 'mw-version-hooks' ],
			$this->msg( 'version-hooks' )->text()
		);
		$ret[] = Html::openElement( 'table', [ 'class' => 'wikitable', 'id' => 'sv-hooks' ] );
		$ret[] = Html::openElement( 'tr' );
		$ret[] = Html::element( 'th', [], $this->msg( 'version-hook-name' )->text() );
		$ret[] = Html::element( 'th', [], $this->msg( 'version-hook-subscribedby' )->text() );
		$ret[] = Html::closeElement( 'tr' );

		foreach ( $hookNames as $name ) {
			$handlers = $hookContainer->getHandlerDescriptions( $name );

			$ret[] = Html::openElement( 'tr' );
			$ret[] = Html::element( 'td', [], $name );
			// @phan-suppress-next-line SecurityCheck-DoubleEscaped See FIXME in listToText
			$ret[] = Html::element( 'td', [], $this->listToText( $handlers ) );
			$ret[] = Html::closeElement( 'tr' );
		}

		$ret[] = Html::closeElement( 'table' );

		return implode( "\n", $ret );
	}

	private function openExtType( ?string $text = null, ?string $name = null ): string {
		$out = '';

		$opt = [ 'class' => 'wikitable plainlinks mw-installed-software' ];

		if ( $name ) {
			$opt['id'] = "sv-$name";
		}

		$out .= Html::openElement( 'table', $opt );

		if ( $text !== null ) {
			$out .= Html::element( 'caption', [], $text );
		}

		if ( $name && $text !== null ) {
			$this->addTocSubSection( $text, "sv-$name" );
		}

		$firstHeadingMsg = ( $name === 'credits-skin' )
			? 'version-skin-colheader-name'
			: 'version-ext-colheader-name';

		$out .= $this->getTableHeaderHtml( [
			$this->msg( $firstHeadingMsg )->text(),
			$this->msg( 'version-ext-colheader-version' )->text(),
			$this->msg( 'version-ext-colheader-license' )->text(),
			$this->msg( 'version-ext-colheader-description' )->text(),
			$this->msg( 'version-ext-colheader-credits' )->text()
		] );

		return $out;
	}

	/**
	 * Return HTML for a table header with given texts in header cells
	 *
	 * Includes thead element and scope="col" attribute for improved accessibility
	 *
	 * @param string|array $headers
	 * @return string HTML
	 */
	private function getTableHeaderHtml( $headers ): string {
		$out = '';
		$out .= Html::openElement( 'thead' );
		$out .= Html::openElement( 'tr' );
		foreach ( $headers as $header ) {
			$out .= Html::element( 'th', [ 'scope' => 'col' ], $header );
		}
		$out .= Html::closeElement( 'tr' );
		$out .= Html::closeElement( 'thead' );
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
	 * @return string HTML fragment
	 */
	public function listAuthors( $authors, $extName, $extDir ): string {
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
			if ( $item instanceof HtmlArmor ) {
				$list[] = HtmlArmor::getHtml( $item );
			} elseif ( $item === '...' ) {
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
			} elseif ( str_ends_with( $item, ' ...]' ) ) {
				$hasOthers = true;
				$list[] = $this->getOutput()->parseInlineAsInterface(
					substr( $item, 0, -4 ) . $this->msg( 'version-poweredby-others' )->text() . "]"
				);
			} else {
				$list[] = $this->getOutput()->parseInlineAsInterface( $item );
			}
		}

		if ( $extName && !$hasOthers && ExtensionInfo::getAuthorsFileName( $extDir ) ) {
			$list[] = $linkRenderer->makeLink(
				$this->getPageTitle( "Credits/$extName" ),
				$this->msg( 'version-poweredby-others' )->text()
			);
		}

		return $this->listToText( $list, false );
	}

	/**
	 * Convert an array of items into a list for display.
	 *
	 * @param array $list List of elements to display
	 * @param bool $sort Whether to sort the items in $list
	 * @return string
	 * @fixme This method does not handle escaping consistently. Language::listToText expects all list elements to be
	 * already escaped. However, self::arrayToString escapes some elements, but not others.
	 */
	private function listToText( array $list, bool $sort = true ): string {
		if ( !$list ) {
			return '';
		}
		if ( $sort ) {
			sort( $list );
		}

		return $this->getLanguage()
			->listToText( array_map( [ self::class, 'arrayToString' ], $list ) );
	}

	/**
	 * Convert an array or object to a string for display.
	 *
	 * @internal For use by ApiQuerySiteinfo (TODO: Turn into more stable method)
	 * @param mixed $list Will convert an array to string if given and return
	 *   the parameter unaltered otherwise
	 * @return mixed
	 * @fixme This should handle escaping more consistently, see FIXME in listToText
	 */
	public static function arrayToString( $list ) {
		if ( is_array( $list ) && count( $list ) == 1 ) {
			$list = $list[0];
		}
		if ( $list instanceof Closure ) {
			// Don't output stuff like "Closure$;1028376090#8$48499d94fe0147f7c633b365be39952b$"
			return 'Closure';
		} elseif ( is_object( $list ) ) {
			return wfMessage( 'parentheses' )->params( get_class( $list ) )->escaped();
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
	 * @deprecated since 1.41 Use GitInfo::repo() for MW_INSTALL_PATH, or new GitInfo otherwise.
	 * @param string $dir Directory of the git checkout
	 * @return string|false Sha1 of commit HEAD points to
	 */
	public static function getGitHeadSha1( $dir ) {
		wfDeprecated( __METHOD__, '1.41' );
		return ( new GitInfo( $dir ) )->getHeadSHA1();
	}

	/**
	 * Get the list of entry points and their URLs
	 * @return string HTML
	 */
	public function getEntryPointInfo() {
		$config = $this->getConfig();
		$scriptPath = $config->get( MainConfigNames::ScriptPath ) ?: '/';

		$entryPoints = [
			'version-entrypoints-articlepath' => $config->get( MainConfigNames::ArticlePath ),
			'version-entrypoints-scriptpath' => $scriptPath,
			'version-entrypoints-index-php' => wfScript( 'index' ),
			'version-entrypoints-api-php' => wfScript( 'api' ),
			'version-entrypoints-rest-php' => wfScript( 'rest' ),
		];

		$language = $this->getLanguage();
		$thAttributes = [
			'dir' => $language->getDir(),
			'lang' => $language->getHtmlCode(),
			'scope' => 'col'
		];

		$this->addTocSection( 'version-entrypoints', 'mw-version-entrypoints' );

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
			Html::openElement( 'thead' ) .
			Html::openElement( 'tr' ) .
			Html::element(
				'th',
				$thAttributes,
				$this->msg( 'version-entrypoints-header-entrypoint' )->text()
			) .
			Html::element(
				'th',
				$thAttributes,
				$this->msg( 'version-entrypoints-header-url' )->text()
			) .
			Html::closeElement( 'tr' ) .
			Html::closeElement( 'thead' );

		foreach ( $entryPoints as $message => $value ) {
			$url = $this->urlUtils->expand( $value, PROTO_RELATIVE );
			$out .= Html::openElement( 'tr' ) .
				Html::rawElement( 'td', [], $this->msg( $message )->parse() ) .
				Html::rawElement( 'td', [],
					Html::rawElement(
						'code',
						[],
						$this->msg( new RawMessage( "[$url $value]" ) )->parse()
					)
				) .
				Html::closeElement( 'tr' );
		}

		$out .= Html::closeElement( 'table' );

		return $out;
	}

	/** @inheritDoc */
	protected function getGroupName() {
		return 'wiki';
	}
}

/**
 * Retain the old class name for backwards compatibility.
 * @deprecated since 1.41
 */
class_alias( SpecialVersion::class, 'SpecialVersion' );
