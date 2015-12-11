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
 * action=query&list=allstashedfiles module, gets all stashed files for
 * the current user.
 *
 * @ingroup API
 */
class ApiQueryAllStashedFiles extends ApiQueryBase {

	public function __construct( ApiQuery $query, $moduleName ) {
		parent::__construct( $query, $moduleName, 'asf' );
	}

	public function execute() {
		$user = $this->getUser();

		if ( $user->isAnon() ) {
			$this->dieUsage( 'The upload stash is only available to logged-in users.' );
		}

		if ( !$user->isAllowed( 'upload' ) ) {
			$this->dieUsage( 'This API method requires the upload permission.' );
		}

		$db = $this->getDB();
		$params = $this->extractRequestParams();

		$this->addTables( 'uploadstash' );

		$this->addWhere( array( 'us_user' => $user->getId() ) );

		if ( !is_null( $params[ 'continue' ] ) ) {
			$cont = explode( '|', $params[ 'continue' ] );
			$this->dieContinueUsageIf( count( $cont ) != 1 );
			$op = $params[ 'dir' ] == 'descending' ? '<' : '>';
			$cont_from = $db->addQuotes( $cont[0] );
			$this->addWhere( "us_key $op= $cont_from" );
		}

		$dir = ( $params[ 'dir' ] == 'descending' ? 'older' : 'newer' );
		$from = $params[ 'from' ];
		$to = $params[ 'to' ];
		$this->addWhereRange( 'us_key', $dir, $from, $to );

		$this->addOption( 'LIMIT', $params[ 'limit' ] + 1 );
		$sort = ( $params[ 'dir' ] == 'descending' ? ' DESC' : '' );
		$this->addOption( 'ORDER BY', 'us_key' . $sort );

		$prop = array_flip( $params[ 'prop' ] );
		$this->addFieldsIf( array( 'us_key' ), isset( $prop[ 'filekey' ] ) );
		$this->addFieldsIf( array( 'us_size', 'us_image_width', 'us_image_height', 'us_image_bits' ), isset( $prop[ 'size' ] ) );
		$this->addFieldsIf( array( 'us_mime', 'us_media_type' ), isset( $prop[ 'type' ] ) );

		$res = $this->select( __METHOD__ );
		$files = array();
		$result = $this->getResult();
		$count = 0;

		foreach ( $res as $row ) {
			if ( ++$count > $params['limit'] ) {
				// We've reached the one extra which shows that there are
				// additional files to be had. Stop here...
				$this->setContinueEnumParameter( 'continue', $row->us_key );
				break;
			}

			$item = array();

			if ( isset( $prop[ 'filekey' ] ) ) {
				$item[ 'filekey' ] = $row->us_key;
			}

			if ( isset( $prop[ 'size' ] ) ) {
				$item[ 'size' ] = $row->us_size;
				$item[ 'width' ] = $row->us_image_width;
				$item[ 'height' ] = $row->us_image_height;
				$item[ 'bits' ] = $row->us_image_bits;
			}

			if ( isset( $prop[ 'type' ] ) ) {
				$item[ 'mimetype' ] = $row->us_mime;
				$item[ 'mediatype' ] = $row->us_media_type;
			}

			$fit = $result->addValue( array( 'query', $this->getModuleName() ), null, $item );

			if ( !$fit ) {
				$this->setContinueEnumParameter( 'continue', $row->us_key );
			}
		}

		$result->addIndexedTagName( array( 'query', $this->getModuleName() ), 'file' );
	}

	public function getAllowedParams() {
		return array(
			'prop' => array(
				ApiBase::PARAM_ISMULTI => true,
				ApiBase::PARAM_DFLT => 'filekey',
				ApiBase::PARAM_TYPE => array( 'filekey', 'size', 'type' ),
			),

			'limit' => array(
				ApiBase::PARAM_TYPE => 'integer',
				ApiBase::PARAM_DFLT => 20,
				ApiBase::PARAM_MIN => 0,
				ApiBase::PARAM_MAX => 50,
				ApiBase::PARAM_MAX2 => 500,
				ApiBase::PARAM_RANGE_ENFORCE => true,
			),
		);
	}

	protected function getExamplesMessages() {
		return array(
			'action=query&list=allstashedfiles&asfprop=filekey|size'
				=> 'apihelp-query+stashimageinfo-example-simple',
		);
	}

	public function getHelpUrls() {
		return 'https://www.mediawiki.org/wiki/API:Allstashedfiles';
	}
}
