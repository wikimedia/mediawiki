<?php
/**
 * Implements Special:Version
 *
 * Copyright © 2017 Ævar Arnfjörð Bjarmason
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
class SpecialSystemInfo extends SpecialPage {
	protected $firstExtOpened = false;

	/**
	 * Stores the current rev id/SHA hash of MediaWiki core
	 */
	protected $coreId = '';

	protected static $extensionTypes = false;

	public function __construct() {
		parent::__construct( 'SystemInfo' );
	}

	/**
	 * main()
	 * @param string|null $par
	 */
	public function execute( $par ) {
		global $IP;

		$this->setHeaders();
		$this->outputHeader();
		$out = $this->getOutput();
		$out->allowClickjacking();

		// Explode the sub page information into useful bits
		$parts = explode( '/', (string)$par );
		$extName = 'MediaWiki';

		// Now figure out what to do
		switch ( strtolower( $parts[0] ) ) {
			default:
				$out->addModuleStyles( 'mediawiki.special.version' );
				$out->addWikiText(
					$this->softwareInformation() .
				);
				$out->addHTML( $this->IPInfo() );

				break;
		}
	}

	/**
	 * Returns wiki text showing the third party software versions (apache, php, mysql).
	 *
	 * @return string
	 */
	public static function softwareInformation() {
		$dbr = wfGetDB( DB_REPLICA );

		// Put the software in an array of form 'name' => 'version'. All messages should
		// be loaded here, so feel free to use wfMessage in the 'name'. Raw HTML or
		// wikimarkup can be used.
		$software = [];
		$software['[https://www.mediawiki.org/ MediaWiki]'] = self::getVersionLinked();
		if ( wfIsHHVM() ) {
			$software['[http://hhvm.com/ HHVM]'] = HHVM_VERSION . " (" . PHP_SAPI . ")";
		} else {
			$software['[https://php.net/ PHP]'] = PHP_VERSION . " (" . PHP_SAPI . ")";
		}
		$software[$dbr->getSoftwareLink()] = $dbr->getServerInfo();

		if ( IcuCollation::getICUVersion() ) {
			$software['[http://site.icu-project.org/ ICU]'] = IcuCollation::getICUVersion();
		}

		// Allow a hook to add/remove items.
		Hooks::run( 'SoftwareInfo', [ &$software ] );

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

		foreach ( $software as $name => $version ) {
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
		global $wgVersion, $IP;

		$gitInfo = self::getGitHeadSha1( $IP );
		if ( !$gitInfo ) {
			$version = $wgVersion;
		} elseif ( $flags === 'nodb' ) {
			$shortSha1 = substr( $gitInfo, 0, 7 );
			$version = "$wgVersion ($shortSha1)";
		} else {
			$shortSha1 = substr( $gitInfo, 0, 7 );
			$msg = wfMessage( 'parentheses' );
			if ( $lang !== null ) {
				$msg->inLanguage( $lang );
			}
			$shortSha1 = $msg->params( $shortSha1 )->escaped();
			$version = "$wgVersion $shortSha1";
		}

		return $version;
	}

	/**
	 * Return a wikitext-formatted string of the MediaWiki version with a link to
	 * the Git SHA1 of head if available.
	 * The fallback is just $wgVersion
	 *
	 * @return mixed
	 */
	public static function getVersionLinked() {
		global $wgVersion;

		$gitVersion = self::getVersionLinkedGit();
		if ( $gitVersion ) {
			$v = $gitVersion;
		} else {
			$v = $wgVersion; // fallback
		}

		return $v;
	}

	/**
	 * @return string
	 */
	private static function getwgVersionLinked() {
		global $wgVersion;
		$versionUrl = "";
		if ( Hooks::run( 'SpecialVersionVersionUrl', [ $wgVersion, &$versionUrl ] ) ) {
			$versionParts = [];
			preg_match( "/^(\d+\.\d+)/", $wgVersion, $versionParts );
			$versionUrl = "https://www.mediawiki.org/wiki/MediaWiki_{$versionParts[1]}";
		}

		return "[$versionUrl $wgVersion]";
	}

	/**
	 * @since 1.22 Returns the HEAD date in addition to the sha1 and link
	 * @return bool|string Global wgVersion + HEAD sha1 stripped to the first 7 chars
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
	 * Get information about client's IP address.
	 *
	 * @return string HTML fragment
	 */
	private function IPInfo() {
		$ip = str_replace( '--', ' - ', htmlspecialchars( $this->getRequest()->getIP() ) );

		return "<!-- visited from $ip -->\n<span style='display:none'>visited from $ip</span>";
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

	protected function getGroupName() {
		return 'wiki';
	}
}