<?php
/**
 * API for MediaWiki 1.16+
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

/**
 * A query action to get image information from temporarily stashed files.
 *
 * @ingroup API
 */
class ApiQueryStashImageInfo extends ApiQueryImageInfo {

	public function __construct( $query, $moduleName ) {
		parent::__construct( $query, $moduleName, 'sii' );
	}

	public function execute() {
		$params = $this->extractRequestParams();
		$modulePrefix = $this->getModulePrefix();

		$prop = array_flip( $params['prop'] );

		$scale = $this->getScale( $params );

		$result = $this->getResult();

		try {
			$stash = RepoGroup::singleton()->getLocalRepo()->getUploadStash();

			foreach ( $params['sessionkey'] as $sessionkey ) {
				$file = $stash->getFile( $sessionkey );
				$finalThumbParam = $this->mergeThumbParams( $file, $scale, $params['urlparam'] );
				$imageInfo = ApiQueryImageInfo::getInfo( $file, $prop, $result, $finalThumbParam );
				$result->addValue( array( 'query', $this->getModuleName() ), null, $imageInfo );
				$result->setIndexedTagName_internal( array( 'query', $this->getModuleName() ), $modulePrefix );
			}

		} catch ( UploadStashNotAvailableException $e ) {
			$this->dieUsage( "Session not available: " . $e->getMessage(), "nosession" );
		} catch ( UploadStashFileNotFoundException $e ) {
			$this->dieUsage( "File not found: " . $e->getMessage(), "invalidsessiondata" );
		} catch ( UploadStashBadPathException $e ) {
			$this->dieUsage( "Bad path: " . $e->getMessage(), "invalidsessiondata" );
		}
	}

	/**
	 * Returns all valid parameters to siiprop
	 */
	public static function getPropertyNames() {
		return array(
			'timestamp',
			'url',
			'size',
			'dimensions', // For backwards compatibility with Allimages
			'sha1',
			'mime',
			'thumbmime',
			'metadata',
			'bitdepth',
		);
	}

	public function getAllowedParams() {
		return array(
			'sessionkey' => array(
				ApiBase::PARAM_ISMULTI => true,
				ApiBase::PARAM_REQUIRED => true,
				ApiBase::PARAM_DFLT => null
			),
			'prop' => array(
				ApiBase::PARAM_ISMULTI => true,
				ApiBase::PARAM_DFLT => 'timestamp|url',
				ApiBase::PARAM_TYPE => self::getPropertyNames()
			),
			'urlwidth' => array(
				ApiBase::PARAM_TYPE => 'integer',
				ApiBase::PARAM_DFLT => -1
			),
			'urlheight' => array(
				ApiBase::PARAM_TYPE => 'integer',
				ApiBase::PARAM_DFLT => -1
			),
			'urlparam' => array(
				ApiBase::PARAM_TYPE => 'string',
				ApiBase::PARAM_DFLT => '',
			),
		);
	}

	/**
	 * Return the API documentation for the parameters.
	 * @return {Array} parameter documentation.
	 */
	public function getParamDescription() {
		$p = $this->getModulePrefix();
		return array(
			'prop' => array(
				'What image information to get:',
				' timestamp    - Adds timestamp for the uploaded version',
				' url          - Gives URL to the image and the description page',
				' size         - Adds the size of the image in bytes and the height, width and page count (if applicable)',
				' dimensions   - Alias for size',
				' sha1         - Adds sha1 hash for the image',
				' mime         - Adds MIME of the image',
				' thumbmime    - Adds MIME of the image thumbnail (requires url)',
				' metadata     - Lists EXIF metadata for the version of the image',
				' bitdepth     - Adds the bit depth of the version',
			),
			'sessionkey' => 'Session key that identifies a previous upload that was stashed temporarily.',
			'urlwidth' => "If {$p}prop=url is set, a URL to an image scaled to this width will be returned.",
			'urlheight' => "Similar to {$p}urlwidth. Cannot be used without {$p}urlwidth",
			'urlparam' => array( "A handler specific parameter string. For example, pdf's ",
				"might use 'page15-100px'. {$p}urlwidth must be used and be consistent with {$p}urlparam" ),
		);
	}

	public function getDescription() {
		return 'Returns image information for stashed images';
	}

	protected function getExamples() {
		return array(
			'api.php?action=query&prop=stashimageinfo&siisessionkey=124sd34rsdf567',
			'api.php?action=query&prop=stashimageinfo&siisessionkey=b34edoe3|bceffd4&siiurlwidth=120&siiprop=url',
		);
	}

	public function getVersion() {
		return __CLASS__ . ': $Id$';
	}

}

