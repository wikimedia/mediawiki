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

	public function __construct( ApiQuery $query, $moduleName ) {
		parent::__construct( $query, $moduleName, 'fa' );
	}

	public function execute() {
		$user = $this->getUser();
		// Before doing anything at all, let's check permissions
		if ( !$user->isAllowed( 'deletedhistory' ) ) {
			$this->dieUsage(
				'You don\'t have permission to view deleted file information',
				'permissiondenied'
			);
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

		$this->addFields( ArchivedFile::selectFields() );
		$this->addFields( array( 'fa_id', 'fa_name', 'fa_timestamp', 'fa_deleted' ) );
		$this->addFieldsIf( 'fa_sha1', $fld_sha1 );
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
			$this->dieContinueUsageIf( count( $cont ) != 3 );
			$op = $params['dir'] == 'descending' ? '<' : '>';
			$cont_from = $db->addQuotes( $cont[0] );
			$cont_timestamp = $db->addQuotes( $db->timestamp( $cont[1] ) );
			$cont_id = (int)$cont[2];
			$this->dieContinueUsageIf( $cont[2] !== (string)$cont_id );
			$this->addWhere( "fa_name $op $cont_from OR " .
				"(fa_name = $cont_from AND " .
				"(fa_timestamp $op $cont_timestamp OR " .
				"(fa_timestamp = $cont_timestamp AND " .
				"fa_id $op= $cont_id )))"
			);
		}

		// Image filters
		$dir = ( $params['dir'] == 'descending' ? 'older' : 'newer' );
		$from = ( $params['from'] === null ? null : $this->titlePartToKey( $params['from'], NS_FILE ) );
		$to = ( $params['to'] === null ? null : $this->titlePartToKey( $params['to'], NS_FILE ) );
		$this->addWhereRange( 'fa_name', $dir, $from, $to );
		if ( isset( $params['prefix'] ) ) {
			$this->addWhere( 'fa_name' . $db->buildLike(
				$this->titlePartToKey( $params['prefix'], NS_FILE ),
				$db->anyString() ) );
		}

		$sha1Set = isset( $params['sha1'] );
		$sha1base36Set = isset( $params['sha1base36'] );
		if ( $sha1Set || $sha1base36Set ) {
			$sha1 = false;
			if ( $sha1Set ) {
				$sha1 = strtolower( $params['sha1'] );
				if ( !$this->validateSha1Hash( $sha1 ) ) {
					$this->dieUsage( 'The SHA1 hash provided is not valid', 'invalidsha1hash' );
				}
				$sha1 = wfBaseConvert( $sha1, 16, 36, 31 );
			} elseif ( $sha1base36Set ) {
				$sha1 = strtolower( $params['sha1base36'] );
				if ( !$this->validateSha1Base36Hash( $sha1 ) ) {
					$this->dieUsage( 'The SHA1Base36 hash provided is not valid', 'invalidsha1base36hash' );
				}
			}
			if ( $sha1 ) {
				$this->addWhereFld( 'fa_sha1', $sha1 );
			}
		}

		// Exclude files this user can't view.
		if ( !$user->isAllowed( 'deletedtext' ) ) {
			$bitmask = File::DELETED_FILE;
		} elseif ( !$user->isAllowedAny( 'suppressrevision', 'viewsuppressed' ) ) {
			$bitmask = File::DELETED_FILE | File::DELETED_RESTRICTED;
		} else {
			$bitmask = 0;
		}
		if ( $bitmask ) {
			$this->addWhere( $this->getDB()->bitAnd( 'fa_deleted', $bitmask ) . " != $bitmask" );
		}

		$limit = $params['limit'];
		$this->addOption( 'LIMIT', $limit + 1 );
		$sort = ( $params['dir'] == 'descending' ? ' DESC' : '' );
		$this->addOption( 'ORDER BY', array(
			'fa_name' . $sort,
			'fa_timestamp' . $sort,
			'fa_id' . $sort,
		) );

		$res = $this->select( __METHOD__ );

		$count = 0;
		$result = $this->getResult();
		foreach ( $res as $row ) {
			if ( ++$count > $limit ) {
				// We've reached the one extra which shows that there are
				// additional pages to be had. Stop here...
				$this->setContinueEnumParameter(
					'continue', "$row->fa_name|$row->fa_timestamp|$row->fa_id"
				);
				break;
			}

			$file = array();
			$file['id'] = (int)$row->fa_id;
			$file['name'] = $row->fa_name;
			$title = Title::makeTitle( NS_FILE, $row->fa_name );
			self::addTitleInfo( $file, $title );

			if ( $fld_description &&
				Revision::userCanBitfield( $row->fa_deleted, File::DELETED_COMMENT, $user )
			) {
				$file['description'] = $row->fa_description;
				if ( isset( $prop['parseddescription'] ) ) {
					$file['parseddescription'] = Linker::formatComment(
						$row->fa_description, $title );
				}
			}
			if ( $fld_user &&
				Revision::userCanBitfield( $row->fa_deleted, File::DELETED_USER, $user )
			) {
				$file['userid'] = (int)$row->fa_user;
				$file['user'] = $row->fa_user_text;
			}
			if ( $fld_sha1 ) {
				$file['sha1'] = wfBaseConvert( $row->fa_sha1, 36, 16, 40 );
			}
			if ( $fld_timestamp ) {
				$file['timestamp'] = wfTimestamp( TS_ISO_8601, $row->fa_timestamp );
			}
			if ( $fld_size || $fld_dimensions ) {
				$file['size'] = $row->fa_size;

				$pageCount = ArchivedFile::newFromRow( $row )->pageCount();
				if ( $pageCount !== false ) {
					$file['pagecount'] = $pageCount;
				}

				$file['height'] = $row->fa_height;
				$file['width'] = $row->fa_width;
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
				$file['filehidden'] = true;
			}
			if ( $row->fa_deleted & File::DELETED_COMMENT ) {
				$file['commenthidden'] = true;
			}
			if ( $row->fa_deleted & File::DELETED_USER ) {
				$file['userhidden'] = true;
			}
			if ( $row->fa_deleted & File::DELETED_RESTRICTED ) {
				// This file is deleted for normal admins
				$file['suppressed'] = true;
			}

			$fit = $result->addValue( array( 'query', $this->getModuleName() ), null, $file );
			if ( !$fit ) {
				$this->setContinueEnumParameter(
					'continue', "$row->fa_name|$row->fa_timestamp|$row->fa_id"
				);
				break;
			}
		}

		$result->addIndexedTagName( array( 'query', $this->getModuleName() ), 'fa' );
	}

	public function getAllowedParams() {
		return array(
			'from' => null,
			'to' => null,
			'prefix' => null,
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
				ApiBase::PARAM_HELP_MSG_PER_VALUE => array(),
			),
			'limit' => array(
				ApiBase::PARAM_DFLT => 10,
				ApiBase::PARAM_TYPE => 'limit',
				ApiBase::PARAM_MIN => 1,
				ApiBase::PARAM_MAX => ApiBase::LIMIT_BIG1,
				ApiBase::PARAM_MAX2 => ApiBase::LIMIT_BIG2
			),
			'continue' => array(
				ApiBase::PARAM_HELP_MSG => 'api-help-param-continue',
			),
		);
	}

	protected function getExamplesMessages() {
		return array(
			'action=query&list=filearchive'
				=> 'apihelp-query+filearchive-example-simple',
		);
	}

	public function getHelpUrls() {
		return 'https://www.mediawiki.org/wiki/API:Filearchive';
	}
}
