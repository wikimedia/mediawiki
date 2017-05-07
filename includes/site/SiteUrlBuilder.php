<?php

namespace MediaWiki\Site;

use MediaWiki\Linker\LinkTarget;
use OutOfBoundsException;

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
 *
 * @license GNU GPL v2+
 */

/**
 * Service for constructing URLs to other sites based on a SiteInfoLookup.
 * This class encodes knowledge about the interpretation of the SiteInfoLookup::SITE_XXX_PATH fields.
 *
 * @todo add getMediaUrl, getThumbnailUrl, getStyleUrl, getResourceUrl...
 *
 * @since 1.30
 */
class SiteUrlBuilder {

	/**
	 * @var SiteInfoLookup
	 */
	private $siteInfoLookup;

	/**
	 * @var string see PROTO_XXX constants
	 */
	private $protocolExpansionMode = PROTO_CURRENT;

	/**
	 * SiteUrlBuilder constructor.
	 *
	 * @param SiteInfoLookup $siteInfoLookup
	 */
	public function __construct( SiteInfoLookup $siteInfoLookup ) {
		$this->siteInfoLookup = $siteInfoLookup;
	}

	/**
	 * @return string
	 */
	public function getProtocolExpansionMode() {
		return $this->protocolExpansionMode;
	}

	/**
	 * @param string $protocolExpansionMode use PROTO_XXX constants
	 */
	public function setProtocolExpansionMode( $protocolExpansionMode ) {
		$this->protocolExpansionMode = $protocolExpansionMode;
	}

	/**
	 * @see SiteInfoLookup::resolveLocalId
	 *
	 * @param string $scope
	 * @param string $id
	 *
	 * @return null|string
	 */
	public function resolveLocalId( $scope, $id ) {
		return $this->siteInfoLookup->resolveLocalId( $scope, $id );
	}

	/**
	 * Returns a full URL referring to the resources identified by $name on the site
	 * identified by $siteId, using the path stored in $field of the site info array.
	 *
	 * The placeholder '$1' is substituted by $name, if $name is given. If no placeholder
	 * is present in the path, $name is appended. URL encoding is applied to $name.
	 *
	 * Protocol expansion applies, see setProtocolExpansionMode().
	 *
	 * @param string $siteId The site to which the URL should refer
	 * @param string $field The URL path to use, see SiteInfoLookup::SITE_XXX_PATH.
	 * @param null|string $name The name of the resource to link to. If not given,
	 *        the resulting URL may contain '$1' as a placeholder. URL encoding applies.
	 *
	 * @throws OutOfBoundsException if $siteId is not a known site
	 * @return string|null The URL, or null if the field is not set on the given site.
	 */
	public function getUrl( $siteId, $field, $name = null ) {
		$info = $this->siteInfoLookup->getSiteInfo( $siteId );

		if ( !isset( $info[$field] ) ) {
			return null;
		}

		$path = $info[$field];

		if ( !preg_match( '!^(https?:)?//!', $path ) && isset( $info[SiteInfoLookup::SITE_BASE_URL] ) ) {
			$path = $info[SiteInfoLookup::SITE_BASE_URL] . $path;
		}

		if ( $name !== null ) {
			// TODO: we could apply normalization based on $info[SiteInfoLookup::SITE_TYPE] here:
			//       Uppercase first letter, replace ' ' by '_', etc. Maybe leave slashes unencoded.
			$name = urlencode( $name );

			$url = str_replace( '$1', $name, $path );

			if ( $url === $path ) {
				// no placeholder was replaced, so append the name
				$url = $path . $name;
			}
		} else {
			$url = $path;
		}

		// TODO: optionally, expand protocol relative URLs
		$url = wfExpandUrl( $url, $this->protocolExpansionMode );
		return $url;
	}

	/**
	 * Returns a link to a LinkTarget on another site.
	 *
	 * @param LinkTarget $target
	 *
	 * @return null|string The full URL of the link target, or null if a URL cannot be constructed.
	 */
	public function getLinkUrl( LinkTarget $target ) {
		$prefix = $target->getInterwiki();
		$siteId = $this->resolveLocalId( SiteInfoLookup::INTERWIKI_ID, $prefix );

		if ( $siteId === null ) {
			$siteId = $this->resolveLocalId( SiteInfoLookup::NAVIGATION_ID, $prefix );
		}

		if ( $siteId === null ) {
			return null;
		}

		$name = $target->getText();
		$url = $this->getUrl( $siteId, SiteInfoLookup::SITE_LINK_PATH, $name );

		if ( $url === null ) {
			return null;
		}

		if ( $target->getFragment() !== '' ) {
			$url .= '#' . $target->getFragment();
		}

		return $url;
	}

	/**
	 * @param string $siteId
	 * @param string $name
	 * @param string[] $parameters query parameters to encode in the URL
	 *
	 * @return null|string
	 */
	public function getScriptUrl( $siteId, $name, $parameters = [] ) {
		$url = $this->getUrl( $siteId, SiteInfoLookup::SITE_SCRIPT_PATH, $name );

		if ( $url !== null ) {
			$url = wfAppendQuery( $url, $parameters );
		}

		return $url;
	}

	/**
	 * @param string $siteId
	 * @param string[] $parameters query parameters to encode in the URL
	 *
	 * @return null|string
	 */
	public function getApiUrl( $siteId, $parameters = [] ) {
		// XXX: We are assuming the target is a mediawiki site. Check SITE_TYPE?
		return $this->getScriptUrl( $siteId, 'api.php', $parameters );
	}

}
