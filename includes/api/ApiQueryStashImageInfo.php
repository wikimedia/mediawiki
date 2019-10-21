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
		if ( !$this->getUser()->isLoggedIn() ) {
			$this->dieWithError( 'apierror-mustbeloggedin-uploadstash', 'notloggedin' );
		}

		$params = $this->extractRequestParams();
		$modulePrefix = $this->getModulePrefix();

		$prop = array_flip( $params['prop'] );

		$scale = $this->getScale( $params );

		$result = $this->getResult();

		$this->requireAtLeastOneParameter( $params, 'filekey', 'sessionkey' );

		// Alias sessionkey to filekey, but give an existing filekey precedence.
		if ( !$params['filekey'] && $params['sessionkey'] ) {
			$params['filekey'] = $params['sessionkey'];
		}

		try {
			$stash = RepoGroup::singleton()->getLocalRepo()->getUploadStash( $this->getUser() );

			foreach ( $params['filekey'] as $filekey ) {
				$file = $stash->getFile( $filekey );
				$finalThumbParam = $this->mergeThumbParams( $file, $scale, $params['urlparam'] );
				$imageInfo = ApiQueryImageInfo::getInfo( $file, $prop, $result, $finalThumbParam );
				$result->addValue( [ 'query', $this->getModuleName() ], null, $imageInfo );
				$result->addIndexedTagName( [ 'query', $this->getModuleName() ], $modulePrefix );
			}
		// @todo Update exception handling here to understand current getFile exceptions
		} catch ( UploadStashFileNotFoundException $e ) {
			$this->dieWithException( $e, [ 'wrap' => 'apierror-stashedfilenotfound' ] );
		} catch ( UploadStashBadPathException $e ) {
			$this->dieWithException( $e, [ 'wrap' => 'apierror-stashpathinvalid' ] );
		}
	}

	private static $propertyFilter = [
		'user', 'userid', 'comment', 'parsedcomment',
		'mediatype', 'archivename', 'uploadwarning',
	];

	/**
	 * Returns all possible parameters to siiprop
	 *
	 * @param array|null $filter List of properties to filter out
	 * @return array
	 */
	public static function getPropertyNames( $filter = null ) {
		if ( $filter === null ) {
			$filter = self::$propertyFilter;
		}
		return parent::getPropertyNames( $filter );
	}

	/**
	 * Returns messages for all possible parameters to siiprop
	 *
	 * @param array|null $filter List of properties to filter out
	 * @return array
	 */
	public static function getPropertyMessages( $filter = null ) {
		if ( $filter === null ) {
			$filter = self::$propertyFilter;
		}
		return parent::getPropertyMessages( $filter );
	}

	public function getAllowedParams() {
		return [
			'filekey' => [
				ApiBase::PARAM_ISMULTI => true,
			],
			'sessionkey' => [
				ApiBase::PARAM_ISMULTI => true,
				ApiBase::PARAM_DEPRECATED => true,
			],
			'prop' => [
				ApiBase::PARAM_ISMULTI => true,
				ApiBase::PARAM_DFLT => 'timestamp|url',
				ApiBase::PARAM_TYPE => self::getPropertyNames(),
				ApiBase::PARAM_HELP_MSG => 'apihelp-query+imageinfo-param-prop',
				ApiBase::PARAM_HELP_MSG_PER_VALUE => self::getPropertyMessages()
			],
			'urlwidth' => [
				ApiBase::PARAM_TYPE => 'integer',
				ApiBase::PARAM_DFLT => -1,
				ApiBase::PARAM_HELP_MSG => [
					'apihelp-query+imageinfo-param-urlwidth',
					ApiQueryImageInfo::TRANSFORM_LIMIT,
				],
			],
			'urlheight' => [
				ApiBase::PARAM_TYPE => 'integer',
				ApiBase::PARAM_DFLT => -1,
				ApiBase::PARAM_HELP_MSG => 'apihelp-query+imageinfo-param-urlheight',
			],
			'urlparam' => [
				ApiBase::PARAM_TYPE => 'string',
				ApiBase::PARAM_DFLT => '',
				ApiBase::PARAM_HELP_MSG => 'apihelp-query+imageinfo-param-urlparam',
			],
		];
	}

	protected function getExamplesMessages() {
		return [
			'action=query&prop=stashimageinfo&siifilekey=124sd34rsdf567'
				=> 'apihelp-query+stashimageinfo-example-simple',
			'action=query&prop=stashimageinfo&siifilekey=b34edoe3|bceffd4&' .
				'siiurlwidth=120&siiprop=url'
				=> 'apihelp-query+stashimageinfo-example-params',
		];
	}

	public function getHelpUrls() {
		return 'https://www.mediawiki.org/wiki/Special:MyLanguage/API:Stashimageinfo';
	}
}
