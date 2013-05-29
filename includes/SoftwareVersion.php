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

/**
 * Version information about a piece of software, module, or resource
 */
class SoftwareVersion {
	protected $shortName;
	protected $name;
	protected $url;
	protected $version;
	protected $licenseName;
	protected $licenseUrl;
	protected $sourceUrl;
	protected $authors;
	protected $authorsUrl;

	/**
	 * @param string $shortName Short HTML ID safe identifier for the module
	 * @param string $name Friendly name of the module, e.g. jQuery
	 * @param string $url Friendly URL to module, e.g. //jquery.org
	 * @param string $version Version number of the module, e.g. 1.9.3
	 * @param string $licenseUrl URL to license text
	 * @param string $licenseName Friendly name of license, e.g. GPL 3
	 * @param string $sourceUrl URL to source repository
	 * @param string $authors Text to display for authors
	 * @param string $authorsUrl URL for authors file
	 */
	public function __construct( $shortName, $name, $url, $version, $licenseUrl, $licenseName,
		$sourceUrl, $authors, $authorsUrl
	) {
		$this->shortName = $shortName;
		$this->name = $name;
		$this->url = $url;
		$this->version = $version;
		$this->licenseName = $licenseName;
		$this->licenseUrl = $licenseUrl;
		$this->sourceUrl = $sourceUrl;
		$this->authors = $authors;
		$this->authorsUrl = $authorsUrl;
	}

	/**
	 * Factory for constructing a new SoftwareVersion object
	 *
	 * @param string $shortName Short HTML ID safe identifier for the module
	 * @param array  $info      Array of meta information. Valid keys are:
	 *     name         - Friendly name of the module, e.g. jQuery
	 *     url          - Friendly URL to module, e.g. //jquery.org
	 *     version      - Version number of the module, e.g. 1.9.3
	 *     licenseUrl   - URL to license text
	 *     licenseName  - Friendly name of license, e.g. GPL 3
	 *     licenseGroup - Name of grouping if this module is part of a bigger group of licensed modules
	 *     authors      - Text or URL to authors file of module
	 *     source       - URL to module source
	 *
	 * @return SoftwareVersion
	 */
	public static function create( $shortName, $info = array() ) {
		if ( !isset( $info['licenseGroup'] ) ) {
			$shortName = preg_replace( '/[^a-zA-Z0-9\-]+/', '_', $shortName );
		} else {
			$shortName = preg_replace( '/[^a-zA-Z0-9\-]+/', '_', $info['licenseGroup'] );
		}

		$name = isset( $info['name'] ) ? $info['name'] : $shortName;
		$url = isset( $info['url'] ) ? $info['url'] : '';
		$version = isset( $info['version'] ) ? $info['version'] : '-';
		$sourceUrl = isset( $info['source'] ) ? $info['source'] : '';
		$licenseName = isset( $info['licenseName'] ) ? $info['licenseName'] : '{{int:version-ext-license}}';
		$licenseUrl = isset( $info['licenseUrl'] ) ? $info['licenseUrl'] : '';

		if ( isset( $info['authors'] ) ) {
			if ( substr( $info['authors'], 0, 2 ) == '//' ) {
				$authors = '{{int:version-ext-authors}}';
				$authorsUrl = $info['authors'];
			} else {
				$authors = $info['authors'];
				$authorsUrl = '';
			}
		} else {
			$authors = '';
			$authorsUrl = '';
		}

		return new SoftwareVersion(
			$shortName,
			$name,
			$url,
			$version,
			$licenseUrl,
			$licenseName,
			$sourceUrl,
			$authors,
			$authorsUrl
		);
	}

	/**
	 * Obtains the HTML friendly ID for this software resource
	 *
	 * @return string
	 */
	public function getShortName() { return $this->shortName; }
	public function setShortName( $shortName ) {
		$this->shortName = preg_replace( '/[^a-zA-Z0-9\-]+/', '_', $shortName );
	}

	/**
	 * Human friendly name of the software resource
	 * @return string WikiText
	 */
	public function getName() { return $this->name; }

	/**
	 * Human friendly URL for this software resource, e.g. the project homepage
	 * @return string URL
	 */
	public function getUrl() { return $this->url; }

	/**
	 * Version string for this software resource
	 * @return string WikiText
	 */
	public function getVersion() { return $this->version; }

	/**
	 * URL to the source repository for this resource
	 * @return string URL
	 */
	public function getSourceURL() { return $this->sourceUrl; }

	/**
	 * Human friendly name of the license
	 * @return string WikiText
	 */
	public function getLicenseName() { return $this->licenseName; }

	/**
	 * URL to full license text
	 * @return string URL
	 */
	public function getLicenseURL() { return $this->licenseUrl; }

	/**
	 * Authors string for this resource
	 * @return string WikiText
	 */
	public function getAuthors() { return $this->authors; }

	/**
	 * URL to AUTHORS or CREDITS file
	 * @return string URL
	 */
	public function getAuthorsURL() { return $this->authorsUrl; }
}