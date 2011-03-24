<?php
/**
 * API for MediaWiki 1.12+
 *
 * Created on May 10, 2010
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
 *
 * @file
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
		// Before doing anything at all, let's check permissions
		if ( !$wgUser->isAllowed( 'deletedhistory' ) ) {
			$this->dieUsage( 'You don\'t have permission to view deleted file information', 'permissiondenied' );
		}

		$db = $this->getDB();

		$params = $this->extractRequestParams();

		$prop = array_flip( $params['prop'] );
		$fld_sha1 = isset( $prop['sha1'] );
		$fld_timestamp = isset( $prop['timestamp'] );
		$fld_user = isset( $prop['user'] );
		$fld_size = isset( $prop['size'] );
		$fld_dimensions = isset( $prop['dimensions'] );
		$fld_description = isset( $prop['description'] ) || isset( $prop['parseddescription'] );
		$fld_mime = isset( $prop['mime'] );
		$fld_metadata = isset( $prop['metadata'] );
		$fld_bitdepth = isset( $prop['bitdepth'] );

		$this->addTables( 'filearchive' );

		$this->addFields( array( 'fa_name', 'fa_deleted' ) );
		$this->addFieldsIf( 'fa_storage_key', $fld_sha1 );
		$this->addFieldsIf( 'fa_timestamp', $fld_timestamp );

		if ( $fld_user ) {
			$this->addFields( array( 'fa_user', 'fa_user_text' ) );
		}

		if ( $fld_dimensions || $fld_size ) {
			$this->addFields( array( 'fa_height', 'fa_width', 'fa_size' ) );
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
		$to = ( is_null( $params['to'] ) ? null : $this->titlePartToKey( $params['to'] ) );
		$this->addWhereRange( 'fa_name', $dir, $from, $to );
		if ( isset( $params['prefix'] ) ) {
			$this->addWhere( 'fa_name' . $db->buildLike( $this->titlePartToKey( $params['prefix'] ), $db->anyString() ) );
		}

		if ( !$wgUser->isAllowed( 'suppressrevision' ) ) {
			// Filter out revisions that the user is not allowed to see. There
			// is no way to indicate that we have skipped stuff because the
			// continuation parameter is fa_name
			
			// Note that this field is unindexed. This should however not be
			// a big problem as files with fa_deleted are rare
			$this->addWhereFld( 'fa_deleted', 0 );
		}

		$limit = $params['limit'];
		$this->addOption( 'LIMIT', $limit + 1 );
		$this->addOption( 'ORDER BY', 'fa_name' .
						( $params['dir'] == 'descending' ? ' DESC' : '' ) );

		$res = $this->select( __METHOD__ );

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
			self::addTitleInfo( $file, Title::makeTitle( NS_FILE, $row->fa_name ) ); 

			if ( $fld_sha1 ) {
				$file['sha1'] = wfBaseConvert( LocalRepo::getHashFromKey( $row->fa_storage_key ), 36, 16, 40 );
			}
			if ( $fld_timestamp ) {
				$file['timestamp'] = wfTimestamp( TS_ISO_8601, $row->fa_timestamp );
			}
			if ( $fld_user ) {
				$file['userid'] = $row->fa_user;
				$file['user'] = $row->fa_user_text;
			}
			if ( $fld_size || $fld_dimensions ) {
				$file['size'] = $row->fa_size;

				$pageCount = ArchivedFile::newFromRow( $row )->pageCount();
				if ( $pageCount !== false ) {
					$vals['pagecount'] = $pageCount;
				}

				$file['height'] = $row->fa_height;
				$file['width'] = $row->fa_width;
			}
			if ( $fld_description ) {
				$file['description'] = $row->fa_description;
				if ( isset( $prop['parseddescription'] ) ) {
					$file['parseddescription'] = $wgUser->getSkin()->formatComment(
						$row->fa_description, $row->fa_name );
				}
			}
			if ( $fld_metadata ) {
				$file['metadata'] = $row->fa_metadata
						? ApiQueryImageInfo::processMetaData( unserialize( $row->fa_metadata ), $result )
						: null;
			}
			if ( $fld_bitdepth ) {
				$file['bitdepth'] = $row->fa_bits;
			}
			if ( $fld_mime ) {
				$file['mime'] = "$row->fa_major_mime/$row->fa_minor_mime";
			}
			
			if ( $row->fa_deleted & File::DELETED_FILE ) {
				$file['filehidden'] = '';
			}
			if ( $row->fa_deleted & File::DELETED_COMMENT ) {
				$file['commenthidden'] = '';
			}
			if ( $row->fa_deleted & File::DELETED_USER ) {
				$file['userhidden'] = '';
			}
			if ( $row->fa_deleted & File::DELETED_RESTRICTED ) {
				// This file is deleted for normal admins
				$file['suppressed'] = '';
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
			'to' => null,
			'prefix' => null,
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
					'parseddescription',
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
			'to' => 'The image title to stop enumerating at',
			'prefix' => 'Search for all image titles that begin with this value',
			'dir' => 'The direction in which to list',
			'limit' => 'How many images to return in total',
			'prop' => array(
				'What image information to get:',
				' sha1              - Adds SHA-1 hash for the image',
				' timestamp         - Adds timestamp for the uploaded version',
				' user              - Adds user who uploaded the image version',
				' size              - Adds the size of the image in bytes and the height, width and page count (if applicable)',
				' dimensions        - Alias for size',
				' description       - Adds description the image version',
				' parseddescription - Parse the description on the version',
				' mime              - Adds MIME of the image',
				' metadata          - Lists EXIF metadata for the version of the image',
				' bitdepth          - Adds the bit depth of the version',
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
