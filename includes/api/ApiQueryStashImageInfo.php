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

		if ( !$params['filekey'] && !$params['sessionkey'] ) {
			$this->dieUsage( "One of filekey or sessionkey must be supplied", 'nofilekey' );
		}

		// Alias sessionkey to filekey, but give an existing filekey precedence.
		if ( !$params['filekey'] && $params['sessionkey'] ) {
			$params['filekey'] = $params['sessionkey'];
		}

		try {
			$stash = RepoGroup::singleton()->getLocalRepo()->getUploadStash();

			foreach ( $params['filekey'] as $filekey ) {
				$file = $stash->getFile( $filekey );
				$finalThumbParam = $this->mergeThumbParams( $file, $scale, $params['urlparam'] );
				$imageInfo = ApiQueryImageInfo::getInfo( $file, $prop, $result, $finalThumbParam );
				$result->addValue( array( 'query', $this->getModuleName() ), null, $imageInfo );
				$result->setIndexedTagName_internal( array( 'query', $this->getModuleName() ), $modulePrefix );
			}
		//TODO: update exception handling here to understand current getFile exceptions
		} catch ( UploadStashNotAvailableException $e ) {
			$this->dieUsage( "Session not available: " . $e->getMessage(), "nosession" );
		} catch ( UploadStashFileNotFoundException $e ) {
			$this->dieUsage( "File not found: " . $e->getMessage(), "invalidsessiondata" );
		} catch ( UploadStashBadPathException $e ) {
			$this->dieUsage( "Bad path: " . $e->getMessage(), "invalidsessiondata" );
		}
	}

	private $propertyFilter = array(
		'user', 'userid', 'comment', 'parsedcomment',
		'mediatype', 'archivename',
	);

	public function getAllowedParams() {
		return array(
			'filekey' => array(
				ApiBase::PARAM_ISMULTI => true,
				ApiBase::PARAM_DFLT => null
			),
			'sessionkey' => array(
				ApiBase::PARAM_ISMULTI => true,
				ApiBase::PARAM_DEPRECATED => true,
				ApiBase::PARAM_DFLT => null
			),
			'prop' => array(
				ApiBase::PARAM_ISMULTI => true,
				ApiBase::PARAM_DFLT => 'timestamp|url',
				ApiBase::PARAM_TYPE => self::getPropertyNames( $this->propertyFilter )
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
	 * @return Array parameter documentation.
	 */
	public function getParamDescription() {
		$p = $this->getModulePrefix();
		return array(
			'prop' => self::getPropertyDescriptions( $this->propertyFilter, $p ),
			'filekey' => 'Key that identifies a previous upload that was stashed temporarily.',
			'sessionkey' => 'Alias for filekey, for backward compatibility.',
			'urlwidth' => "If {$p}prop=url is set, a URL to an image scaled to this width will be returned.",
			'urlheight' => "Similar to {$p}urlwidth. Cannot be used without {$p}urlwidth",
			'urlparam' => array( "A handler specific parameter string. For example, pdf's ",
				"might use 'page15-100px'. {$p}urlwidth must be used and be consistent with {$p}urlparam" ),
		);
	}

	public function getResultProperties() {
		return ApiQueryImageInfo::getResultPropertiesFiltered( $this->propertyFilter );
	}

	public function getDescription() {
		return 'Returns image information for stashed images';
	}

	public function getExamples() {
		return array(
			'api.php?action=query&prop=stashimageinfo&siifilekey=124sd34rsdf567',
			'api.php?action=query&prop=stashimageinfo&siifilekey=b34edoe3|bceffd4&siiurlwidth=120&siiprop=url',
		);
	}

}
