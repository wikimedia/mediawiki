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

/**
 * action=query&list=mystashedfiles module, gets all stashed files for
 * the current user.
 *
 * @ingroup API
 */
class ApiQueryMyStashedFiles extends ApiQueryBase {

	public function __construct( ApiQuery $query, $moduleName ) {
		parent::__construct( $query, $moduleName, 'msf' );
	}

	public function execute() {
		$user = $this->getUser();

		if ( $user->isAnon() ) {
			$this->dieUsage( 'The upload stash is only available to logged-in users.', 'stashnotloggedin' );
		}

		// Note: If user is logged in but cannot upload, they can still see
		// the list of stashed uploads...but it will probably be empty.

		$params = $this->extractRequestParams();

		$this->addTables( 'uploadstash' );

		$this->addFields( [ 'us_id', 'us_key', 'us_status' ] );

		$this->addWhere( [ 'us_user' => $user->getId() ] );

		if ( $params['continue'] !== null ) {
			$cont = explode( '|', $params['continue'] );
			$this->dieContinueUsageIf( count( $cont ) != 1 );
			$cont_from = (int)$cont[0];
			$this->dieContinueUsageIf( strval( $cont_from ) !== $cont[0] );
			$this->addWhere( "us_id >= $cont_from" );
		}

		$this->addOption( 'LIMIT', $params['limit'] + 1 );
		$this->addOption( 'ORDER BY', 'us_id' );

		$prop = array_flip( $params['prop'] );
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
				$item['size'] = (int) $row->us_size;
				$item['width'] = (int) $row->us_image_width;
				$item['height'] = (int) $row->us_image_height;
				$item['bits'] = (int) $row->us_image_bits;
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

	public function getAllowedParams() {
		return [
			'prop' => [
				ApiBase::PARAM_ISMULTI => true,
				ApiBase::PARAM_DFLT => '',
				ApiBase::PARAM_TYPE => [ 'size', 'type' ],
				ApiBase::PARAM_HELP_MSG_PER_VALUE => [],
			],

			'limit' => [
				ApiBase::PARAM_TYPE => 'limit',
				ApiBase::PARAM_DFLT => 10,
				ApiBase::PARAM_MIN => 1,
				ApiBase::PARAM_MAX => ApiBase::LIMIT_BIG1,
				ApiBase::PARAM_MAX2 => ApiBase::LIMIT_BIG2,
			],

			'continue' => [
				ApiBase::PARAM_HELP_MSG => 'api-help-param-continue',
			],
		];
	}

	protected function getExamplesMessages() {
		return [
			'action=query&list=mystashedfiles&msfprop=size'
				=> 'apihelp-query+mystashedfiles-example-simple',
		];
	}

	public function getHelpUrls() {
		return 'https://www.mediawiki.org/wiki/API:mystashedfiles';
	}
}
