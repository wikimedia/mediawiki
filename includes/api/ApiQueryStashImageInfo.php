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

	public function __construct( ApiQuery $query, $moduleName ) {
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
			$this->logFeatureUsage( 'prop=stashimageinfo&siisessionkey' );
			$params['filekey'] = $params['sessionkey'];
		}

		try {
			$stash = RepoGroup::singleton()->getLocalRepo()->getUploadStash( $this->getUser() );

			foreach ( $params['filekey'] as $filekey ) {
				$file = $stash->getFile( $filekey );
				$finalThumbParam = $this->mergeThumbParams( $file, $scale, $params['urlparam'] );
				$imageInfo = ApiQueryImageInfo::getInfo( $file, $prop, $result, $finalThumbParam );
				$result->addValue( array( 'query', $this->getModuleName() ), null, $imageInfo );
				$result->addIndexedTagName( array( 'query', $this->getModuleName() ), $modulePrefix );
			}
		// @todo Update exception handling here to understand current getFile exceptions
		} catch ( UploadStashFileNotFoundException $e ) {
			$this->dieUsage( "File not found: " . $e->getMessage(), "invalidsessiondata" );
		} catch ( UploadStashBadPathException $e ) {
			$this->dieUsage( "Bad path: " . $e->getMessage(), "invalidsessiondata" );
		}
	}

	private $propertyFilter = array(
		'user', 'userid', 'comment', 'parsedcomment',
		'mediatype', 'archivename', 'uploadwarning',
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
				ApiBase::PARAM_TYPE => self::getPropertyNames( $this->propertyFilter ),
				ApiBase::PARAM_HELP_MSG => 'apihelp-query+imageinfo-param-prop',
				ApiBase::PARAM_HELP_MSG_PER_VALUE => self::getPropertyMessages( $this->propertyFilter )
			),
			'urlwidth' => array(
				ApiBase::PARAM_TYPE => 'integer',
				ApiBase::PARAM_DFLT => -1,
				ApiBase::PARAM_HELP_MSG => array(
					'apihelp-query+imageinfo-param-urlwidth',
					ApiQueryImageInfo::TRANSFORM_LIMIT,
				),
			),
			'urlheight' => array(
				ApiBase::PARAM_TYPE => 'integer',
				ApiBase::PARAM_DFLT => -1,
				ApiBase::PARAM_HELP_MSG => 'apihelp-query+imageinfo-param-urlheight',
			),
			'urlparam' => array(
				ApiBase::PARAM_TYPE => 'string',
				ApiBase::PARAM_DFLT => '',
				ApiBase::PARAM_HELP_MSG => 'apihelp-query+imageinfo-param-urlparam',
			),
		);
	}

	protected function getExamplesMessages() {
		return array(
			'action=query&prop=stashimageinfo&siifilekey=124sd34rsdf567'
				=> 'apihelp-query+stashimageinfo-example-simple',
			'action=query&prop=stashimageinfo&siifilekey=b34edoe3|bceffd4&' .
				'siiurlwidth=120&siiprop=url'
				=> 'apihelp-query+stashimageinfo-example-params',
		);
	}
}
