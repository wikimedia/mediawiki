<?php
/**
 * API for MediaWiki 1.12+
 *
 * Created on May 10, 2010
 *
 * Copyright © 2010 Sam Reed
 * Copyright © 2008 Vasiliev Victor vasilvv@gmail.com,
 * based on ApiQueryAllPages.php
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
 * Query module to enumerate all deleted files.
 *
 * @ingroup API
 */
class ApiQueryFilearchive extends ApiQueryBase {

	public function __construct( $query, $moduleName ) {
		parent::__construct( $query, $moduleName, 'fa' );
	}

	public function execute() {
		$user = $this->getUser();
		// Before doing anything at all, let's check permissions
		if ( !$user->isAllowed( 'deletedhistory' ) ) {
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
		$fld_mediatype = isset( $prop['mediatype'] );
		$fld_metadata = isset( $prop['metadata'] );
		$fld_bitdepth = isset( $prop['bitdepth'] );
		$fld_archivename = isset( $prop['archivename'] );

		$this->addTables( 'filearchive' );

		$this->addFields( array( 'fa_name', 'fa_deleted' ) );
		$this->addFieldsIf( 'fa_storage_key', $fld_sha1 );
		$this->addFieldsIf( 'fa_timestamp', $fld_timestamp );
		$this->addFieldsIf( array( 'fa_user', 'fa_user_text' ), $fld_user );
		$this->addFieldsIf( array( 'fa_height', 'fa_width', 'fa_size' ), $fld_dimensions || $fld_size );
		$this->addFieldsIf( 'fa_description', $fld_description );
		$this->addFieldsIf( array( 'fa_major_mime', 'fa_minor_mime' ), $fld_mime );
		$this->addFieldsIf( 'fa_media_type', $fld_mediatype );
		$this->addFieldsIf( 'fa_metadata', $fld_metadata );
		$this->addFieldsIf( 'fa_bits', $fld_bitdepth );
		$this->addFieldsIf( 'fa_archive_name', $fld_archivename );

		if ( !is_null( $params['continue'] ) ) {
			$cont = explode( '|', $params['continue'] );
			if ( count( $cont ) != 1 ) {
				$this->dieUsage( "Invalid continue param. You should pass the " .
					"original value returned by the previous query", "_badcontinue" );
			}
			$op = $params['dir'] == 'descending' ? '<' : '>';
			$cont_from = $db->addQuotes( $cont[0] );
			$this->addWhere( "fa_name $op= $cont_from" );
		}

		// Image filters
		$dir = ( $params['dir'] == 'descending' ? 'older' : 'newer' );
		$from = ( is_null( $params['from'] ) ? null : $this->titlePartToKey( $params['from'] ) );
		if ( !is_null( $params['continue'] ) ) {
			$from = $params['continue'];
		}
		$to = ( is_null( $params['to'] ) ? null : $this->titlePartToKey( $params['to'] ) );
		$this->addWhereRange( 'fa_name', $dir, $from, $to );
		if ( isset( $params['prefix'] ) ) {
			$this->addWhere( 'fa_name' . $db->buildLike( $this->titlePartToKey( $params['prefix'] ), $db->anyString() ) );
		}

		$sha1Set = isset( $params['sha1'] );
		$sha1base36Set = isset( $params['sha1base36'] );
		if ( $sha1Set || $sha1base36Set ) {
			global $wgMiserMode;
			if ( $wgMiserMode  ) {
				$this->dieUsage( 'Search by hash disabled in Miser Mode', 'hashsearchdisabled' );
			}

			$sha1 = false;
			if ( $sha1Set ) {
				if ( !$this->validateSha1Hash( $params['sha1'] ) ) {
					$this->dieUsage( 'The SHA1 hash provided is not valid', 'invalidsha1hash' );
				}
				$sha1 = wfBaseConvert( $params['sha1'], 16, 36, 31 );
			} elseif ( $sha1base36Set ) {
				if ( !$this->validateSha1Base36Hash( $params['sha1base36'] ) ) {
					$this->dieUsage( 'The SHA1Base36 hash provided is not valid', 'invalidsha1base36hash' );
				}
				$sha1 = $params['sha1base36'];
			}
			if ( $sha1 ) {
				$this->addWhere( 'fa_storage_key ' . $db->buildLike( "{$sha1}.", $db->anyString() ) );
			}
		}

		if ( !$user->isAllowed( 'suppressrevision' ) ) {
			// Filter out revisions that the user is not allowed to see. There
			// is no way to indicate that we have skipped stuff because the
			// continuation parameter is fa_name

			// Note that this field is unindexed. This should however not be
			// a big problem as files with fa_deleted are rare
			$this->addWhereFld( 'fa_deleted', 0 );
		}

		$limit = $params['limit'];
		$this->addOption( 'LIMIT', $limit + 1 );
		$sort = ( $params['dir'] == 'descending' ? ' DESC' : '' );
		$this->addOption( 'ORDER BY', 'fa_name' . $sort );

		$res = $this->select( __METHOD__ );

		$count = 0;
		$result = $this->getResult();
		foreach ( $res as $row ) {
			if ( ++$count > $limit ) {
				// We've reached the one extra which shows that there are additional pages to be had. Stop here...
				$this->setContinueEnumParameter( 'continue', $row->fa_name );
				break;
			}

			$file = array();
			$file['name'] = $row->fa_name;
			$title = Title::makeTitle( NS_FILE, $row->fa_name );
			self::addTitleInfo( $file, $title );

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
					$file['parseddescription'] = Linker::formatComment(
						$row->fa_description, $title );
				}
			}
			if ( $fld_mediatype ) {
				$file['mediatype'] = $row->fa_media_type;
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
			if ( $fld_archivename && !is_null( $row->fa_archive_name ) ) {
				$file['archivename'] = $row->fa_archive_name;
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
				$this->setContinueEnumParameter( 'continue', $row->fa_name );
				break;
			}
		}

		$result->setIndexedTagName_internal( array( 'query', $this->getModuleName() ), 'fa' );
	}

	public function getAllowedParams() {
		return array (
			'from' => null,
			'continue' => null,
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
					'parseddescription',
					'mime',
					'mediatype',
					'metadata',
					'bitdepth',
					'archivename',
				),
			),
		);
	}

	public function getParamDescription() {
		return array(
			'from' => 'The image title to start enumerating from',
			'continue' => 'When more results are available, use this to continue',
			'to' => 'The image title to stop enumerating at',
			'prefix' => 'Search for all image titles that begin with this value',
			'dir' => 'The direction in which to list',
			'limit' => 'How many images to return in total',
			'sha1' => "SHA1 hash of image. Overrides {$this->getModulePrefix()}sha1base36. Disabled in Miser Mode",
			'sha1base36' => 'SHA1 hash of image in base 36 (used in MediaWiki). Disabled in Miser Mode',
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
				' mediatype         - Adds the media type of the image',
				' metadata          - Lists EXIF metadata for the version of the image',
				' bitdepth          - Adds the bit depth of the version',
				' archivename       - Adds the file name of the archive version for non-latest versions'
			),
		);
	}

	public function getResultProperties() {
		return array(
			'' => array(
				'name' => 'string',
				'ns' => 'namespace',
				'title' => 'string',
				'filehidden' => 'boolean',
				'commenthidden' => 'boolean',
				'userhidden' => 'boolean',
				'suppressed' => 'boolean'
			),
			'sha1' => array(
				'sha1' => 'string'
			),
			'timestamp' => array(
				'timestamp' => 'timestamp'
			),
			'user' => array(
				'userid' => 'integer',
				'user' => 'string'
			),
			'size' => array(
				'size' => 'integer',
				'pagecount' => array(
					ApiBase::PROP_TYPE => 'integer',
					ApiBase::PROP_NULLABLE => true
				),
				'height' => 'integer',
				'width' => 'integer'
			),
			'dimensions' => array(
				'size' => 'integer',
				'pagecount' => array(
					ApiBase::PROP_TYPE => 'integer',
					ApiBase::PROP_NULLABLE => true
				),
				'height' => 'integer',
				'width' => 'integer'
			),
			'description' => array(
				'description' => 'string'
			),
			'parseddescription' => array(
				'description' => 'string',
				'parseddescription' => 'string'
			),
			'metadata' => array(
				'metadata' => 'string'
			),
			'bitdepth' => array(
				'bitdepth' => 'integer'
			),
			'mime' => array(
				'mime' => 'string'
			),
			'mediatype' => array(
				'mediatype' => 'string'
			),
			'archivename' => array(
				'archivename' => 'string'
			),
		);
	}

	public function getDescription() {
		return 'Enumerate all deleted files sequentially';
	}

	public function getPossibleErrors() {
		return array_merge( parent::getPossibleErrors(), array(
			array( 'code' => 'permissiondenied', 'info' => 'You don\'t have permission to view deleted file information' ),
			array( 'code' => 'hashsearchdisabled', 'info' => 'Search by hash disabled in Miser Mode' ),
			array( 'code' => 'invalidsha1hash', 'info' => 'The SHA1 hash provided is not valid' ),
			array( 'code' => 'invalidsha1base36hash', 'info' => 'The SHA1Base36 hash provided is not valid' ),
			array( 'code' => '_badcontinue', 'info' => 'Invalid continue param. You should pass the original value returned by the previous query' ),
		) );
	}

	public function getExamples() {
		return array(
			'api.php?action=query&list=filearchive' => array(
				'Simple Use',
				'Show a list of all deleted files',
			),
		);
	}

	public function getVersion() {
		return __CLASS__ . ': $Id$';
	}
}
