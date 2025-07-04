<?php
/**
 * API for MediaWiki 1.27+
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

use Wikimedia\ParamValidator\ParamValidator;
use Wikimedia\ParamValidator\TypeDef\IntegerDef;

/**
 * action=query&list=mystashedfiles module, gets all stashed files for
 * the current user.
 *
 * @ingroup API
 */
class ApiQueryMyStashedFiles extends ApiQueryBase {

	public function __construct( ApiQuery $query, string $moduleName ) {
		parent::__construct( $query, $moduleName, 'msf' );
	}

	public function execute() {
		$user = $this->getUser();

		if ( !$user->isRegistered() ) {
			$this->dieWithError( 'apierror-mustbeloggedin-uploadstash', 'stashnotloggedin' );
		}

		// Note: If user is logged in but cannot upload, they can still see
		// the list of stashed uploads...but it will probably be empty.

		$params = $this->extractRequestParams();

		$this->addTables( 'uploadstash' );

		$this->addFields( [ 'us_id', 'us_key', 'us_status' ] );

		$this->addWhere( [ 'us_user' => $user->getId() ] );

		if ( $params['continue'] !== null ) {
			$cont = $this->parseContinueParamOrDie( $params['continue'], [ 'int' ] );
			$this->addWhere( $this->getDB()->buildComparison( '>=', [
				'us_id' => (int)$cont[0],
			] ) );
		}

		$this->addOption( 'LIMIT', $params['limit'] + 1 );
		$this->addOption( 'ORDER BY', 'us_id' );

		$prop = array_fill_keys( $params['prop'], true );
		$this->addFieldsIf(
			[
				'us_size',
				'us_image_width',
				'us_image_height',
				'us_image_bits'
			],

			isset( $prop['size'] )
		);
		$this->addFieldsIf( [ 'us_mime', 'us_media_type' ], isset( $prop['type'] ) );

		$res = $this->select( __METHOD__ );
		$result = $this->getResult();
		$count = 0;

		foreach ( $res as $row ) {
			if ( ++$count > $params['limit'] ) {
				// We've reached the one extra which shows that there are
				// additional files to be had. Stop here...
				$this->setContinueEnumParameter( 'continue', $row->us_id );
				break;
			}

			$item = [
				'filekey' => $row->us_key,
				'status' => $row->us_status,
			];

			if ( isset( $prop['size'] ) ) {
				$item['size'] = (int)$row->us_size;
				$item['width'] = (int)$row->us_image_width;
				$item['height'] = (int)$row->us_image_height;
				$item['bits'] = (int)$row->us_image_bits;
			}

			if ( isset( $prop['type'] ) ) {
				$item['mimetype'] = $row->us_mime;
				$item['mediatype'] = $row->us_media_type;
			}

			$fit = $result->addValue( [ 'query', $this->getModuleName() ], null, $item );

			if ( !$fit ) {
				$this->setContinueEnumParameter( 'continue', $row->us_id );
				break;
			}
		}

		$result->addIndexedTagName( [ 'query', $this->getModuleName() ], 'file' );
	}

	/** @inheritDoc */
	public function getAllowedParams() {
		return [
			'prop' => [
				ParamValidator::PARAM_ISMULTI => true,
				ParamValidator::PARAM_DEFAULT => '',
				ParamValidator::PARAM_TYPE => [ 'size', 'type' ],
				ApiBase::PARAM_HELP_MSG_PER_VALUE => [],
			],

			'limit' => [
				ParamValidator::PARAM_TYPE => 'limit',
				ParamValidator::PARAM_DEFAULT => 10,
				IntegerDef::PARAM_MIN => 1,
				IntegerDef::PARAM_MAX => ApiBase::LIMIT_BIG1,
				IntegerDef::PARAM_MAX2 => ApiBase::LIMIT_BIG2,
			],

			'continue' => [
				ApiBase::PARAM_HELP_MSG => 'api-help-param-continue',
			],
		];
	}

	/** @inheritDoc */
	protected function getExamplesMessages() {
		return [
			'action=query&list=mystashedfiles&msfprop=size'
				=> 'apihelp-query+mystashedfiles-example-simple',
		];
	}

	/** @inheritDoc */
	public function getHelpUrls() {
		return 'https://www.mediawiki.org/wiki/Special:MyLanguage/API:mystashedfiles';
	}
}

/** @deprecated class alias since 1.43 */
class_alias( ApiQueryMyStashedFiles::class, 'ApiQueryMyStashedFiles' );
