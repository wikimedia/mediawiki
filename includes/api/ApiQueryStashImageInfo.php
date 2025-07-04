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

namespace MediaWiki\Api;

use MediaWiki\FileRepo\RepoGroup;
use MediaWiki\Language\Language;
use MediaWiki\Page\File\BadFileLookup;
use UploadStashBadPathException;
use UploadStashFileNotFoundException;
use Wikimedia\ParamValidator\ParamValidator;

/**
 * A query action to get image information from temporarily stashed files.
 *
 * @ingroup API
 */
class ApiQueryStashImageInfo extends ApiQueryImageInfo {

	private RepoGroup $repoGroup;

	public function __construct(
		ApiQuery $query,
		string $moduleName,
		RepoGroup $repoGroup,
		Language $contentLanguage,
		BadFileLookup $badFileLookup
	) {
		parent::__construct(
			$query,
			$moduleName,
			'sii',
			$repoGroup,
			$contentLanguage,
			$badFileLookup
		);
		$this->repoGroup = $repoGroup;
	}

	public function execute() {
		if ( !$this->getUser()->isRegistered() ) {
			$this->dieWithError( 'apierror-mustbeloggedin-uploadstash', 'notloggedin' );
		}

		$params = $this->extractRequestParams();
		$modulePrefix = $this->getModulePrefix();

		$prop = array_fill_keys( $params['prop'], true );

		$scale = $this->getScale( $params );

		$result = $this->getResult();

		$this->requireAtLeastOneParameter( $params, 'filekey', 'sessionkey' );

		// Alias sessionkey to filekey, but give an existing filekey precedence.
		if ( !$params['filekey'] && $params['sessionkey'] ) {
			$params['filekey'] = $params['sessionkey'];
		}

		try {
			$stash = $this->repoGroup->getLocalRepo()->getUploadStash( $this->getUser() );

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

	private const PROPERTY_FILTER = [
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
		return parent::getPropertyNames( $filter ?? self::PROPERTY_FILTER );
	}

	/**
	 * Returns messages for all possible parameters to siiprop
	 *
	 * @param array|null $filter List of properties to filter out
	 * @return array
	 */
	public static function getPropertyMessages( $filter = null ) {
		return parent::getPropertyMessages( $filter ?? self::PROPERTY_FILTER );
	}

	/** @inheritDoc */
	public function getAllowedParams() {
		return [
			'filekey' => [
				ParamValidator::PARAM_ISMULTI => true,
			],
			'sessionkey' => [
				ParamValidator::PARAM_ISMULTI => true,
				ParamValidator::PARAM_DEPRECATED => true,
			],
			'prop' => [
				ParamValidator::PARAM_ISMULTI => true,
				ParamValidator::PARAM_DEFAULT => 'timestamp|url',
				ParamValidator::PARAM_TYPE => self::getPropertyNames(),
				ApiBase::PARAM_HELP_MSG => 'apihelp-query+imageinfo-param-prop',
				ApiBase::PARAM_HELP_MSG_PER_VALUE => self::getPropertyMessages()
			],
			'urlwidth' => [
				ParamValidator::PARAM_TYPE => 'integer',
				ParamValidator::PARAM_DEFAULT => -1,
				ApiBase::PARAM_HELP_MSG => [
					'apihelp-query+imageinfo-param-urlwidth',
					ApiQueryImageInfo::TRANSFORM_LIMIT,
				],
			],
			'urlheight' => [
				ParamValidator::PARAM_TYPE => 'integer',
				ParamValidator::PARAM_DEFAULT => -1,
				ApiBase::PARAM_HELP_MSG => 'apihelp-query+imageinfo-param-urlheight',
			],
			'urlparam' => [
				ParamValidator::PARAM_TYPE => 'string',
				ParamValidator::PARAM_DEFAULT => '',
				ApiBase::PARAM_HELP_MSG => 'apihelp-query+imageinfo-param-urlparam',
			],
		];
	}

	/** @inheritDoc */
	protected function getExamplesMessages() {
		return [
			'action=query&prop=stashimageinfo&siifilekey=124sd34rsdf567'
				=> 'apihelp-query+stashimageinfo-example-simple',
			'action=query&prop=stashimageinfo&siifilekey=b34edoe3|bceffd4&' .
				'siiurlwidth=120&siiprop=url'
				=> 'apihelp-query+stashimageinfo-example-params',
		];
	}

	/** @inheritDoc */
	public function getHelpUrls() {
		return 'https://www.mediawiki.org/wiki/Special:MyLanguage/API:Stashimageinfo';
	}
}

/** @deprecated class alias since 1.43 */
class_alias( ApiQueryStashImageInfo::class, 'ApiQueryStashImageInfo' );
