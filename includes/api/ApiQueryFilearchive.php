<?php

/**
 * Created on May 10, 2010
 *
 * API for MediaWiki 1.12+
 *
 * Copyright © 2010 Sam Reed
 * Copyright © 2008 Vasiliev Victor vasilvv@gmail.com,
 * based on ApiQueryAllpages.php
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
 */

if ( !defined( 'MEDIAWIKI' ) ) {
	// Eclipse helper - will be ignored in production
	require_once( 'ApiQueryBase.php' );
}

/**
 * Query module to enumerate all deleted files.
 *
 * @ingroup API
 */
class ApiQueryFilearchive extends ApiQueryBase {

	public function __construct( $query, $moduleName ) {
		parent::__construct( $query, $moduleName, 'fa' );
	}

	public function execute() {
		global $wgUser;
		$this->getMain()->setVaryCookie();
		// Before doing anything at all, let's check permissions
		if ( !$wgUser->isAllowed( 'deletedhistory' ) ) {
			$this->dieUsage( 'You don\'t have permission to view deleted file information', 'permissiondenied' );
		}
	
		$db = $this->getDB();

		$params = $this->extractRequestParams();
		
		$prop = array_flip( $params['prop'] );
		$fld_id = isset( $prop['id'] );
		$fld_sha1 = isset( $prop['sha1'] );
		$fld_timestamp = isset( $prop['timestamp'] );
		$fld_user = isset( $prop['user'] );
		$fld_size = isset( $prop['size'] );
		$fld_dimensions = isset( $prop['dimensions'] );
		$fld_description = isset( $prop['description'] );
		$fld_mime = isset( $prop['mime'] );
		$fld_metadata = isset( $prop['metadata'] );
		$fld_bitdepth = isset( $prop['bitdepth'] );
		
		$this->addTables( 'filearchive' );
		
		$this->addFields( 'fa_name' );
		$this->addFieldsIf( 'fa_storage_key', $fld_sha1 );
		$this->addFieldsIf( 'fa_timestamp', $fld_timestamp );
		$this->addFieldsIf( 'fa_user', $fld_user );
		$this->addFieldsIf( 'fa_size', $fld_size );

		if ( $fld_dimensions ) {
			$this->addFields( array( 'fa_height', 'fa_width' ) );
		}

		$this->addFieldsIf( 'fa_description', $fld_description );

		if ( $fld_mime ) {
			$this->addFields( array( 'fa_major_mime', 'fa_minor_mime' ) );
		}

		$this->addFieldsIf( 'fa_metadata', $fld_metadata );
		$this->addFieldsIf( 'fa_bits', $fld_bitdepth );

		// Image filters
		$dir = ( $params['dir'] == 'descending' ? 'older' : 'newer' );
		$from = ( is_null( $params['from'] ) ? null : $this->titlePartToKey( $params['from'] ) );
		$this->addWhereRange( 'fa_name', $dir, $from, null );
		if ( isset( $params['prefix'] ) )
			$this->addWhere( 'fa_name' . $db->buildLike( $this->titlePartToKey( $params['prefix'] ), $db->anyString() ) );

		if ( isset( $params['minsize'] ) ) {
			$this->addWhere( 'fa_size>=' . intval( $params['minsize'] ) );
		}

		if ( isset( $params['maxsize'] ) ) {
			$this->addWhere( 'fa_size<=' . intval( $params['maxsize'] ) );
		}

		$sha1 = false;
		if ( isset( $params['sha1'] ) ) {
			$sha1 = wfBaseConvert( $params['sha1'], 16, 36, 31 );
		} elseif ( isset( $params['sha1base36'] ) ) {
			$sha1 = $params['sha1base36'];
		}
		if ( $sha1 ) {
			$this->addWhere( 'fa_storage_key=' . $db->addQuotes( $sha1 ) );
		}

		$limit = $params['limit'];
		$this->addOption( 'LIMIT', $limit + 1 );
		$this->addOption( 'ORDER BY', 'fa_name' .
						( $params['dir'] == 'descending' ? ' DESC' : '' ) );

		$res = $this->select( __METHOD__ );

		$titles = array();
		$count = 0;
		$result = $this->getResult();
		foreach ( $res as $row ) {
			if ( ++$count > $limit ) {
				// We've reached the one extra which shows that there are additional pages to be had. Stop here...
				// TODO: Security issue - if the user has no right to view next title, it will still be shown
				$this->setContinueEnumParameter( 'from', $this->keyToTitle( $row->fa_name ) );
				break;
			}
			
			$file = array();
			$file['name'] = $row->fa_name;

			if ( $fld_sha1 ) {
				$file['sha1'] = wfBaseConvert( $row->fa_storage_key, 36, 16, 40 );
			}
			if ( $fld_timestamp ) {
				$file['timestamp'] = wfTimestamp( TS_ISO_8601, $row->fa_timestamp );
			}
			if ( $fld_user ) {
				$file['user'] = $row->fa_user;
			}
			if ( $fld_size ) {
				$file['size'] = $row->fa_size;
			}
			if ( $fld_dimensions ) {
				$file['height'] = $row->fa_height;
				$file['width'] = $row->fa_width;
			}
			if ( $fld_description ) {
				$file['description'] = $row->fa_description;
			}
			if ( $fld_metadata ) {
				$file['metadata'] = $row->fa_metadata ? ApiQueryImageInfo::processMetaData( unserialize( $row->fa_metadata ), $result ) : null;
			}
			if ( $fld_bitdepth ) {
				$file['bitdepth'] = $row->fa_bits;
			}
			if ( $fld_mime ) {
				$file['mime'] = "$row->fa_major_mime/$row->fa_minor_mime";
			}
			
			$fit = $result->addValue( array( 'query', $this->getModuleName() ), null, $file );
			if ( !$fit ) {
				$this->setContinueEnumParameter( 'from', $this->keyToTitle( $row->fa_name ) );
				break;
			}
		}

		$result->setIndexedTagName_internal( array( 'query', $this->getModuleName() ), 'fa' );
	}

	public function getAllowedParams() {
		return array (
			'from' => null,
			'prefix' => null,
			'minsize' => array(
				ApiBase::PARAM_TYPE => 'integer',
			),
			'maxsize' => array(
				ApiBase::PARAM_TYPE => 'integer',
			),
			'limit' => array(
				ApiBase::PARAM_DFLT => 10,
				ApiBase::PARAM_TYPE => 'limit',
				ApiBase::PARAM_MIN => 1,
				ApiBase::PARAM_MAX => ApiBase::LIMIT_BIG1,
				ApiBase::PARAM_MAX2 => ApiBase::LIMIT_BIG2
			),
			'dir' => array(
				ApiBase::PARAM_DFLT => 'ascending',
				ApiBase::PARAM_TYPE => array(
					'ascending',
					'descending'
				)
			),
			'sha1' => null,
			'sha1base36' => null,
			'prop' => array(
				ApiBase::PARAM_DFLT => 'timestamp',
				ApiBase::PARAM_ISMULTI => true,
				ApiBase::PARAM_TYPE => array(
					'sha1',
					'timestamp',
					'user',
					'size',
					'dimensions',
					'description',
					'mime',
					'metadata',
					'bitdepth'
				),
			),
		);
	}

	public function getParamDescription() {
		return array(
			'from' => 'The image title to start enumerating from',
			'prefix' => 'Search for all image titles that begin with this value',
			'dir' => 'The direction in which to list',
			'minsize' => 'Limit to images with at least this many bytes',
			'maxsize' => 'Limit to images with at most this many bytes',
			'limit' => 'How many total images to return',
			'sha1' => "SHA1 hash of image. Overrides {$this->getModulePrefix()}sha1base36",
			'sha1base36' => 'SHA1 hash of image in base 36 (used in MediaWiki)',
			'prop' => array(
				'What image information to get:',
				' sha1         - Adds sha1 hash for the image',
				' timestamp    - Adds timestamp for the uploaded version',
				' user         - Adds user for uploaded the image version',
				' size         - Adds the size of the image in bytes',
				' dimensions   - Adds the height and width of the image',
				' description  - Adds description the image version',
				' mime         - Adds MIME of the image',
				' metadata     - Lists EXIF metadata for the version of the image',
				' bitdepth     - Adds the bit depth of the version',
            ),
		);
	}

	public function getDescription() {
		return 'Enumerate all deleted files sequentially';
	}

	public function getPossibleErrors() {
		return array_merge( parent::getPossibleErrors(), array(
			array( 'code' => 'permissiondenied', 'info' => 'You don\'t have permission to view deleted file information' ),
		) );
	}

	protected function getExamples() {
		return array(
			'Simple Use',
			' Show a list of all deleted files',
			'  api.php?action=query&list=filearchive',
		);
	}

	public function getVersion() {
		return __CLASS__ . ': $Id$';
	}
}
