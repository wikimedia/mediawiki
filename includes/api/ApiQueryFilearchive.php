<?php
/**
 * API for MediaWiki 1.12+
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

namespace MediaWiki\Api;

use MediaWiki\CommentFormatter\CommentFormatter;
use MediaWiki\CommentFormatter\CommentItem;
use MediaWiki\CommentStore\CommentStore;
use MediaWiki\FileRepo\File\ArchivedFile;
use MediaWiki\FileRepo\File\File;
use MediaWiki\Revision\RevisionRecord;
use MediaWiki\Title\Title;
use MediaWiki\Title\TitleValue;
use Wikimedia\ParamValidator\ParamValidator;
use Wikimedia\ParamValidator\TypeDef\IntegerDef;
use Wikimedia\Rdbms\IExpression;
use Wikimedia\Rdbms\LikeValue;

/**
 * Query module to enumerate all deleted files.
 *
 * @ingroup API
 */
class ApiQueryFilearchive extends ApiQueryBase {

	private CommentStore $commentStore;
	private CommentFormatter $commentFormatter;

	public function __construct(
		ApiQuery $query,
		string $moduleName,
		CommentStore $commentStore,
		CommentFormatter $commentFormatter
	) {
		parent::__construct( $query, $moduleName, 'fa' );
		$this->commentStore = $commentStore;
		$this->commentFormatter = $commentFormatter;
	}

	public function execute() {
		$user = $this->getUser();
		$db = $this->getDB();

		$params = $this->extractRequestParams();

		$prop = array_fill_keys( $params['prop'], true );
		$fld_sha1 = isset( $prop['sha1'] );
		$fld_timestamp = isset( $prop['timestamp'] );
		$fld_user = isset( $prop['user'] );
		$fld_size = isset( $prop['size'] );
		$fld_dimensions = isset( $prop['dimensions'] );
		$fld_description = isset( $prop['description'] ) || isset( $prop['parseddescription'] );
		$fld_parseddescription = isset( $prop['parseddescription'] );
		$fld_mime = isset( $prop['mime'] );
		$fld_mediatype = isset( $prop['mediatype'] );
		$fld_metadata = isset( $prop['metadata'] );
		$fld_bitdepth = isset( $prop['bitdepth'] );
		$fld_archivename = isset( $prop['archivename'] );

		if ( $fld_description && !$this->getAuthority()->isAllowed( 'deletedhistory' ) ) {
			$this->dieWithError( 'apierror-cantview-deleted-description', 'permissiondenied' );
		}
		if ( $fld_metadata && !$this->getAuthority()->isAllowedAny( 'deletedtext', 'undelete' ) ) {
			$this->dieWithError( 'apierror-cantview-deleted-metadata', 'permissiondenied' );
		}

		$fileQuery = ArchivedFile::getQueryInfo();
		$this->addTables( $fileQuery['tables'] );
		$this->addFields( $fileQuery['fields'] );
		$this->addJoinConds( $fileQuery['joins'] );

		if ( $params['continue'] !== null ) {
			$cont = $this->parseContinueParamOrDie( $params['continue'], [ 'string', 'timestamp', 'int' ] );
			$op = $params['dir'] == 'descending' ? '<=' : '>=';
			$this->addWhere( $db->buildComparison( $op, [
				'fa_name' => $cont[0],
				'fa_timestamp' => $db->timestamp( $cont[1] ),
				'fa_id' => $cont[2],
			] ) );
		}

		// Image filters
		$dir = ( $params['dir'] == 'descending' ? 'older' : 'newer' );
		$from = ( $params['from'] === null ? null : $this->titlePartToKey( $params['from'], NS_FILE ) );
		$to = ( $params['to'] === null ? null : $this->titlePartToKey( $params['to'], NS_FILE ) );
		$this->addWhereRange( 'fa_name', $dir, $from, $to );
		if ( isset( $params['prefix'] ) ) {
			$this->addWhere(
				$db->expr(
					'fa_name',
					IExpression::LIKE,
					new LikeValue( $this->titlePartToKey( $params['prefix'], NS_FILE ), $db->anyString() )
				)
			);
		}

		$sha1Set = isset( $params['sha1'] );
		$sha1base36Set = isset( $params['sha1base36'] );
		if ( $sha1Set || $sha1base36Set ) {
			$sha1 = false;
			if ( $sha1Set ) {
				$sha1 = strtolower( $params['sha1'] );
				if ( !$this->validateSha1Hash( $sha1 ) ) {
					$this->dieWithError( 'apierror-invalidsha1hash' );
				}
				$sha1 = \Wikimedia\base_convert( $sha1, 16, 36, 31 );
			} elseif ( $sha1base36Set ) {
				$sha1 = strtolower( $params['sha1base36'] );
				if ( !$this->validateSha1Base36Hash( $sha1 ) ) {
					$this->dieWithError( 'apierror-invalidsha1base36hash' );
				}
			}
			if ( $sha1 ) {
				$this->addWhereFld( 'fa_sha1', $sha1 );
				// Paranoia: avoid brute force searches (T19342)
				if ( !$this->getAuthority()->isAllowed( 'deletedtext' ) ) {
					$bitmask = File::DELETED_FILE;
				} elseif ( !$this->getAuthority()->isAllowedAny( 'suppressrevision', 'viewsuppressed' ) ) {
					$bitmask = File::DELETED_FILE | File::DELETED_RESTRICTED;
				} else {
					$bitmask = 0;
				}
				if ( $bitmask ) {
					$this->addWhere( $this->getDB()->bitAnd( 'fa_deleted', $bitmask ) . " != $bitmask" );
				}
			}
		}

		$limit = $params['limit'];
		$this->addOption( 'LIMIT', $limit + 1 );
		$sort = ( $params['dir'] == 'descending' ? ' DESC' : '' );
		$this->addOption( 'ORDER BY', [
			'fa_name' . $sort,
			'fa_timestamp' . $sort,
			'fa_id' . $sort,
		] );

		$res = $this->select( __METHOD__ );

		// Format descriptions in a batch
		$formattedDescriptions = [];
		$descriptions = [];
		if ( $fld_parseddescription ) {
			$commentItems = [];
			foreach ( $res as $row ) {
				$desc = $this->commentStore->getComment( 'fa_description', $row )->text;
				$descriptions[$row->fa_id] = $desc;
				$commentItems[$row->fa_id] = ( new CommentItem( $desc ) )
					->selfLinkTarget( new TitleValue( NS_FILE, $row->fa_name ) );
			}
			$formattedDescriptions = $this->commentFormatter->createBatch()
				->comments( $commentItems )
				->execute();
		}

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

			$exists = $row->fa_archive_name !== '';
			$canViewFile = RevisionRecord::userCanBitfield( $row->fa_deleted, File::DELETED_FILE, $user );

			$file = [];
			$file['id'] = (int)$row->fa_id;
			$file['name'] = $row->fa_name;
			$title = Title::makeTitle( NS_FILE, $row->fa_name );
			self::addTitleInfo( $file, $title );

			if ( $fld_description &&
				RevisionRecord::userCanBitfield( $row->fa_deleted, File::DELETED_COMMENT, $user )
			) {
				if ( isset( $prop['parseddescription'] ) ) {
					$file['parseddescription'] = $formattedDescriptions[$row->fa_id];
					$file['description'] = $descriptions[$row->fa_id];
				} else {
					$file['description'] = $this->commentStore->getComment( 'fa_description', $row )->text;
				}
			}
			if ( $fld_user &&
				RevisionRecord::userCanBitfield( $row->fa_deleted, File::DELETED_USER, $user )
			) {
				$file['userid'] = (int)$row->fa_user;
				$file['user'] = $row->fa_user_text;
			}
			if ( !$exists ) {
				$file['filemissing'] = true;
			}
			if ( $fld_sha1 && $canViewFile && $exists ) {
				$file['sha1'] = \Wikimedia\base_convert( $row->fa_sha1, 36, 16, 40 );
			}
			if ( $fld_timestamp ) {
				$file['timestamp'] = wfTimestamp( TS_ISO_8601, $row->fa_timestamp );
			}
			if ( ( $fld_size || $fld_dimensions ) && $canViewFile && $exists ) {
				$file['size'] = $row->fa_size;

				$pageCount = ArchivedFile::newFromRow( $row )->pageCount();
				if ( $pageCount !== false ) {
					$file['pagecount'] = $pageCount;
				}

				$file['height'] = $row->fa_height;
				$file['width'] = $row->fa_width;
			}
			if ( $fld_mediatype && $canViewFile && $exists ) {
				$file['mediatype'] = $row->fa_media_type;
			}
			if ( $fld_metadata && $canViewFile && $exists ) {
				$metadataArray = ArchivedFile::newFromRow( $row )->getMetadataArray();
				$file['metadata'] = $row->fa_metadata
					? ApiQueryImageInfo::processMetaData( $metadataArray, $result )
					: null;
			}
			if ( $fld_bitdepth && $canViewFile && $exists ) {
				$file['bitdepth'] = $row->fa_bits;
			}
			if ( $fld_mime && $canViewFile && $exists ) {
				$file['mime'] = "$row->fa_major_mime/$row->fa_minor_mime";
			}
			if ( $fld_archivename && $row->fa_archive_name !== null ) {
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

			$fit = $result->addValue( [ 'query', $this->getModuleName() ], null, $file );
			if ( !$fit ) {
				$this->setContinueEnumParameter(
					'continue', "$row->fa_name|$row->fa_timestamp|$row->fa_id"
				);
				break;
			}
		}

		$result->addIndexedTagName( [ 'query', $this->getModuleName() ], 'fa' );
	}

	/** @inheritDoc */
	public function getAllowedParams() {
		return [
			'from' => null,
			'to' => null,
			'prefix' => null,
			'dir' => [
				ParamValidator::PARAM_DEFAULT => 'ascending',
				ParamValidator::PARAM_TYPE => [
					'ascending',
					'descending'
				]
			],
			'sha1' => null,
			'sha1base36' => null,
			'prop' => [
				ParamValidator::PARAM_DEFAULT => 'timestamp',
				ParamValidator::PARAM_ISMULTI => true,
				ParamValidator::PARAM_TYPE => [
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
				],
				ApiBase::PARAM_HELP_MSG_PER_VALUE => [],
			],
			'limit' => [
				ParamValidator::PARAM_DEFAULT => 10,
				ParamValidator::PARAM_TYPE => 'limit',
				IntegerDef::PARAM_MIN => 1,
				IntegerDef::PARAM_MAX => ApiBase::LIMIT_BIG1,
				IntegerDef::PARAM_MAX2 => ApiBase::LIMIT_BIG2
			],
			'continue' => [
				ApiBase::PARAM_HELP_MSG => 'api-help-param-continue',
			],
		];
	}

	/** @inheritDoc */
	protected function getExamplesMessages() {
		return [
			'action=query&list=filearchive'
				=> 'apihelp-query+filearchive-example-simple',
		];
	}

	/** @inheritDoc */
	public function getHelpUrls() {
		return 'https://www.mediawiki.org/wiki/Special:MyLanguage/API:Filearchive';
	}
}

/** @deprecated class alias since 1.43 */
class_alias( ApiQueryFilearchive::class, 'ApiQueryFilearchive' );
