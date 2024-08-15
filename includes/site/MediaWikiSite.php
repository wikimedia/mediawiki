<?php
/**
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

namespace MediaWiki\Site;

use MediaWiki\Title\Title;
use RuntimeException;

/**
 * Class representing a MediaWiki site.
 *
 * @since 1.21
 * @ingroup Site
 * @author John Erling Blad < jeblad@gmail.com >
 * @author Daniel Kinzler
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 */
class MediaWikiSite extends Site {
	/** The script path of a site, e.g. `/w/$1` related to $wgScriptPath */
	public const PATH_FILE = 'file_path';
	/** The article path of a site, e.g. `/wiki/$1` like $wgArticlePath */
	public const PATH_PAGE = 'page_path';

	/**
	 * @since 1.21
	 * @param string $type
	 */
	public function __construct( $type = self::TYPE_MEDIAWIKI ) {
		parent::__construct( $type );
	}

	/**
	 * Get the database form of the given title.
	 *
	 * @since 1.21
	 * @param string $title The target page's title, in normalized form.
	 * @return string
	 */
	public function toDBKey( $title ) {
		return str_replace( ' ', '_', $title );
	}

	/**
	 * Get the normalized form of the given page title.
	 *
	 * This uses to normalization rules of the given site. If $followRedirect is set to true
	 * and the given title is a redirect, the redirect will be resolved and
	 * the redirect target is returned.
	 * Only titles of existing pages will be returned.
	 *
	 * @note This actually makes an API request to the remote site, so beware
	 *   that this function is slow and depends on an external service.
	 *
	 * @note If MW_PHPUNIT_TEST is defined, the call to the external site is
	 *   skipped, and the title is normalized using the local normalization
	 *   rules as implemented by the Title class.
	 *
	 * @see Site::normalizePageName
	 * @since 1.21
	 * @since 1.37 Added $followRedirect
	 * @param string $pageName
	 * @param int $followRedirect either MediaWikiPageNameNormalizer::FOLLOW_REDIRECT or
	 *  MediaWikiPageNameNormalizer::NOFOLLOW_REDIRECT
	 * @return string|false The normalized form of the title,
	 *  or false to indicate an invalid title, a missing page,
	 *  or some other kind of error.
	 */
	public function normalizePageName( $pageName, $followRedirect = MediaWikiPageNameNormalizer::FOLLOW_REDIRECT ) {
		if ( defined( 'MW_PHPUNIT_TEST' ) || defined( 'MW_DEV_ENV' ) ) {
			// If the code is under test, don't call out to other sites, just
			// normalize locally.
			// Note: this may cause results to be inconsistent with the actual
			// normalization used by the respective remote site!

			$t = Title::newFromText( $pageName );
			return $t->getPrefixedText();
		} else {
			static $mediaWikiPageNameNormalizer = null;
			$mediaWikiPageNameNormalizer ??= new MediaWikiPageNameNormalizer();

			return $mediaWikiPageNameNormalizer->normalizePageName(
				$pageName,
				$this->getFileUrl( 'api.php' ),
				$followRedirect
			);
		}
	}

	/**
	 * Get the constant for getting or setting the script path.
	 *
	 * This configures how Site::setLinkPath() and Site::getLinkPath()
	 * will work internally in terms of Site::setPath() and Site::getPath().
	 *
	 * @see Site::getLinkPathType
	 * @since 1.21
	 * @return string
	 */
	public function getLinkPathType() {
		return self::PATH_PAGE;
	}

	/**
	 * Get the article path, as relative path only (without server).
	 *
	 * @since 1.21
	 * @return string
	 */
	public function getRelativePagePath() {
		return parse_url( $this->getPath( self::PATH_PAGE ), PHP_URL_PATH );
	}

	/**
	 * Get the script script, as relative path only (without server).
	 *
	 * @since 1.21
	 * @return string
	 */
	public function getRelativeFilePath() {
		return parse_url( $this->getPath( self::PATH_FILE ), PHP_URL_PATH );
	}

	/**
	 * Set the article path.
	 *
	 * @since 1.21
	 * @param string $path
	 */
	public function setPagePath( $path ) {
		$this->setPath( self::PATH_PAGE, $path );
	}

	/**
	 * Set the script path.
	 *
	 * @since 1.21
	 * @param string $path
	 */
	public function setFilePath( $path ) {
		$this->setPath( self::PATH_FILE, $path );
	}

	/**
	 * Get the full URL for the given page on the site.
	 *
	 * This implementation returns a URL constructed using the path returned by getLinkPath().
	 * In addition to the default behavior implemented by Site::getPageUrl(), this
	 * method converts the $pageName to DBKey-format by replacing spaces with underscores
	 * before using it in the URL.
	 *
	 * @see Site::getPageUrl
	 * @since 1.21
	 * @param string|false $pageName Page name or false (default: false)
	 * @return string|null
	 */
	public function getPageUrl( $pageName = false ) {
		$url = $this->getLinkPath();

		if ( $url === null ) {
			return null;
		}

		if ( $pageName !== false ) {
			$pageName = $this->toDBKey( trim( $pageName ) );
			$url = str_replace( '$1', wfUrlencode( $pageName ), $url );
		}

		return $url;
	}

	/**
	 * Get the full URL to an entry point under a wiki's script path.
	 *
	 * This is the equivalent of wfScript() for other sites.
	 *
	 * The path should go at the `$1` marker. If the $path
	 * argument is provided, the marker will be replaced by its value.
	 *
	 * @since 1.21
	 * @param string|false $path Not passing a string for this is deprecated since 1.40.
	 * @return string
	 */
	public function getFileUrl( $path = false ) {
		$filePath = $this->getPath( self::PATH_FILE );
		if ( $filePath === null ) {
			throw new RuntimeException( "getFileUrl called for {$this->getGlobalId()} while PATH_FILE is unset" );
		}

		if ( $path !== false ) {
			$filePath = str_replace( '$1', $path, $filePath );
		} else {
			wfDeprecatedMsg( __METHOD__ . ': omitting $path is deprecated', '1.40' );
		}

		return $filePath;
	}
}

/** @deprecated class alias since 1.42 */
class_alias( MediaWikiSite::class, 'MediaWikiSite' );
