<?php

/**
 * API for MediaWiki 1.12+
 *
 * Created on Mar 16, 2008
 *
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
 * Query module to enumerate all available pages.
 *
 * @ingroup API
 */
class ApiQueryAllImages extends ApiQueryGeneratorBase {
	protected $mRepo;

	public function __construct( ApiQuery $query, $moduleName ) {
		parent::__construct( $query, $moduleName, 'ai' );
		$this->mRepo = RepoGroup::singleton()->getLocalRepo();
	}

	/**
	 * Override parent method to make sure the repo's DB is used
	 * which may not necessarily be the same as the local DB.
	 *
	 * TODO: allow querying non-local repos.
	 * @return DatabaseBase
	 */
	protected function getDB() {
		return $this->mRepo->getSlaveDB();
	}

	public function execute() {
		$this->run();
	}

	public function getCacheMode( $params ) {
		return 'public';
	}

	/**
	 * @param ApiPageSet $resultPageSet
	 * @return void
	 */
	public function executeGenerator( $resultPageSet ) {
		if ( $resultPageSet->isResolvingRedirects() ) {
			$this->dieUsage(
				'Use "gaifilterredir=nonredirects" option instead of "redirects" ' .
					'when using allimages as a generator',
				'params'
			);
		}

		$this->run( $resultPageSet );
	}

	/**
	 * @param ApiPageSet $resultPageSet
	 * @return void
	 */
	private function run( $resultPageSet = null ) {
		$repo = $this->mRepo;
		if ( !$repo instanceof LocalRepo ) {
			$this->dieUsage(
				'Local file repository does not support querying all images',
				'unsupportedrepo'
			);
		}

		$prefix = $this->getModulePrefix();

		$db = $this->getDB();

		$params = $this->extractRequestParams();

		// Table and return fields
		$this->addTables( 'image' );

		$prop = array_flip( $params['prop'] );
		$this->addFields( LocalFile::selectFields() );

		$ascendingOrder = true;
		if ( $params['dir'] == 'descending' || $params['dir'] == 'older' ) {
			$ascendingOrder = false;
		}

		if ( $params['sort'] == 'name' ) {
			// Check mutually exclusive params
			$disallowed = array( 'start', 'end', 'user' );
			foreach ( $disallowed as $pname ) {
				if ( isset( $params[$pname] ) ) {
					$this->dieUsage(
						"Parameter '{$prefix}{$pname}' can only be used with {$prefix}sort=timestamp",
						'badparams'
					);
				}
			}
			if ( $params['filterbots'] != 'all' ) {
				$this->dieUsage(
					"Parameter '{$prefix}filterbots' can only be used with {$prefix}sort=timestamp",
					'badparams'
				);
			}

			// Pagination
			if ( !is_null( $params['continue'] ) ) {
				$cont = explode( '|', $params['continue'] );
				$this->dieContinueUsageIf( count( $cont ) != 1 );
				$op = ( $ascendingOrder ? '>' : '<' );
				$continueFrom = $db->addQuotes( $cont[0] );
				$this->addWhere( "img_name $op= $continueFrom" );
			}

			// Image filters
			$from = ( $params['from'] === null ? null : $this->titlePartToKey( $params['from'], NS_FILE ) );
			$to = ( $params['to'] === null ? null : $this->titlePartToKey( $params['to'], NS_FILE ) );
			$this->addWhereRange( 'img_name', ( $ascendingOrder ? 'newer' : 'older' ), $from, $to );

			if ( isset( $params['prefix'] ) ) {
				$this->addWhere( 'img_name' . $db->buildLike(
					$this->titlePartToKey( $params['prefix'], NS_FILE ),
					$db->anyString() ) );
			}
		} else {
			// Check mutually exclusive params
			$disallowed = array( 'from', 'to', 'prefix' );
			foreach ( $disallowed as $pname ) {
				if ( isset( $params[$pname] ) ) {
					$this->dieUsage(
						"Parameter '{$prefix}{$pname}' can only be used with {$prefix}sort=name",
						'badparams'
					);
				}
			}
			if ( !is_null( $params['user'] ) && $params['filterbots'] != 'all' ) {
				// Since filterbots checks if each user has the bot right, it
				// doesn't make sense to use it with user
				$this->dieUsage(
					"Parameters '{$prefix}user' and '{$prefix}filterbots' cannot be used together",
					'badparams'
				);
			}

			// Pagination
			$this->addTimestampWhereRange(
				'img_timestamp',
				$ascendingOrder ? 'newer' : 'older',
				$params['start'],
				$params['end']
			);
			// Include in ORDER BY for uniqueness
			$this->addWhereRange( 'img_name', $ascendingOrder ? 'newer' : 'older', null, null );

			if ( !is_null( $params['continue'] ) ) {
				$cont = explode( '|', $params['continue'] );
				$this->dieContinueUsageIf( count( $cont ) != 2 );
				$op = ( $ascendingOrder ? '>' : '<' );
				$continueTimestamp = $db->addQuotes( $db->timestamp( $cont[0] ) );
				$continueName = $db->addQuotes( $cont[1] );
				$this->addWhere( "img_timestamp $op $continueTimestamp OR " .
					"(img_timestamp = $continueTimestamp AND " .
					"img_name $op= $continueName)"
				);
			}

			// Image filters
			if ( !is_null( $params['user'] ) ) {
				$this->addWhereFld( 'img_user_text', $params['user'] );
			}
			if ( $params['filterbots'] != 'all' ) {
				$this->addTables( 'user_groups' );
				$this->addJoinConds( array( 'user_groups' => array(
					'LEFT JOIN',
					array(
						'ug_group' => User::getGroupsWithPermission( 'bot' ),
						'ug_user = img_user'
					)
				) ) );
				$groupCond = ( $params['filterbots'] == 'nobots' ? 'NULL' : 'NOT NULL' );
				$this->addWhere( "ug_group IS $groupCond" );
			}
		}

		// Filters not depending on sort
		if ( isset( $params['minsize'] ) ) {
			$this->addWhere( 'img_size>=' . intval( $params['minsize'] ) );
		}

		if ( isset( $params['maxsize'] ) ) {
			$this->addWhere( 'img_size<=' . intval( $params['maxsize'] ) );
		}

		$sha1 = false;
		if ( isset( $params['sha1'] ) ) {
			$sha1 = strtolower( $params['sha1'] );
			if ( !$this->validateSha1Hash( $sha1 ) ) {
				$this->dieUsage( 'The SHA1 hash provided is not valid', 'invalidsha1hash' );
			}
			$sha1 = wfBaseConvert( $sha1, 16, 36, 31 );
		} elseif ( isset( $params['sha1base36'] ) ) {
			$sha1 = strtolower( $params['sha1base36'] );
			if ( !$this->validateSha1Base36Hash( $sha1 ) ) {
				$this->dieUsage( 'The SHA1Base36 hash provided is not valid', 'invalidsha1base36hash' );
			}
		}
		if ( $sha1 ) {
			$this->addWhereFld( 'img_sha1', $sha1 );
		}

		if ( !is_null( $params['mime'] ) ) {
			if ( $this->getConfig()->get( 'MiserMode' ) ) {
				$this->dieUsage( 'MIME search disabled in Miser Mode', 'mimesearchdisabled' );
			}

			$mimeConds = array();
			foreach ( $params['mime'] as $mime ) {
				list( $major, $minor ) = File::splitMime( $mime );
				$mimeConds[] = $db->makeList(
					array(
						'img_major_mime' => $major,
						'img_minor_mime' => $minor,
					),
					LIST_AND
				);
			}
			// safeguard against internal_api_error_DBQueryError
			if ( count( $mimeConds ) > 0 ) {
				$this->addWhere( $db->makeList( $mimeConds, LIST_OR ) );
			} else {
				// no MIME types, no files
				$this->getResult()->addValue( 'query', $this->getModuleName(), array() );
				return;
			}
		}

		$limit = $params['limit'];
		$this->addOption( 'LIMIT', $limit + 1 );
		$sortFlag = '';
		if ( !$ascendingOrder ) {
			$sortFlag = ' DESC';
		}
		if ( $params['sort'] == 'timestamp' ) {
			$this->addOption( 'ORDER BY', 'img_timestamp' . $sortFlag );
			if ( !is_null( $params['user'] ) ) {
				$this->addOption( 'USE INDEX', array( 'image' => 'img_usertext_timestamp' ) );
			} else {
				$this->addOption( 'USE INDEX', array( 'image' => 'img_timestamp' ) );
			}
		} else {
			$this->addOption( 'ORDER BY', 'img_name' . $sortFlag );
		}

		$res = $this->select( __METHOD__ );

		$titles = array();
		$count = 0;
		$result = $this->getResult();
		foreach ( $res as $row ) {
			if ( ++$count > $limit ) {
				// We've reached the one extra which shows that there are
				// additional pages to be had. Stop here...
				if ( $params['sort'] == 'name' ) {
					$this->setContinueEnumParameter( 'continue', $row->img_name );
				} else {
					$this->setContinueEnumParameter( 'continue', "$row->img_timestamp|$row->img_name" );
				}
				break;
			}

			if ( is_null( $resultPageSet ) ) {
				$file = $repo->newFileFromRow( $row );
				$info = array_merge( array( 'name' => $row->img_name ),
					ApiQueryImageInfo::getInfo( $file, $prop, $result ) );
				self::addTitleInfo( $info, $file->getTitle() );

				$fit = $result->addValue( array( 'query', $this->getModuleName() ), null, $info );
				if ( !$fit ) {
					if ( $params['sort'] == 'name' ) {
						$this->setContinueEnumParameter( 'continue', $row->img_name );
					} else {
						$this->setContinueEnumParameter( 'continue', "$row->img_timestamp|$row->img_name" );
					}
					break;
				}
			} else {
				$titles[] = Title::makeTitle( NS_FILE, $row->img_name );
			}
		}

		if ( is_null( $resultPageSet ) ) {
			$result->addIndexedTagName( array( 'query', $this->getModuleName() ), 'img' );
		} else {
			$resultPageSet->populateFromTitles( $titles );
		}
	}

	public function getAllowedParams() {
		$ret = array(
			'sort' => array(
				ApiBase::PARAM_DFLT => 'name',
				ApiBase::PARAM_TYPE => array(
					'name',
					'timestamp'
				)
			),
			'dir' => array(
				ApiBase::PARAM_DFLT => 'ascending',
				ApiBase::PARAM_TYPE => array(
					// sort=name
					'ascending',
					'descending',
					// sort=timestamp
					'newer',
					'older'
				)
			),
			'from' => null,
			'to' => null,
			'continue' => array(
				ApiBase::PARAM_HELP_MSG => 'api-help-param-continue',
			),
			'start' => array(
				ApiBase::PARAM_TYPE => 'timestamp'
			),
			'end' => array(
				ApiBase::PARAM_TYPE => 'timestamp'
			),
			'prop' => array(
				ApiBase::PARAM_TYPE => ApiQueryImageInfo::getPropertyNames( $this->propertyFilter ),
				ApiBase::PARAM_DFLT => 'timestamp|url',
				ApiBase::PARAM_ISMULTI => true,
				ApiBase::PARAM_HELP_MSG => 'apihelp-query+imageinfo-param-prop',
				ApiBase::PARAM_HELP_MSG_PER_VALUE => ApiQueryImageInfo::getPropertyMessages( $this->propertyFilter ),
			),
			'prefix' => null,
			'minsize' => array(
				ApiBase::PARAM_TYPE => 'integer',
			),
			'maxsize' => array(
				ApiBase::PARAM_TYPE => 'integer',
			),
			'sha1' => null,
			'sha1base36' => null,
			'user' => array(
				ApiBase::PARAM_TYPE => 'user'
			),
			'filterbots' => array(
				ApiBase::PARAM_DFLT => 'all',
				ApiBase::PARAM_TYPE => array(
					'all',
					'bots',
					'nobots'
				)
			),
			'mime' => array(
				ApiBase::PARAM_DFLT => null,
				ApiBase::PARAM_ISMULTI => true,
			),
			'limit' => array(
				ApiBase::PARAM_DFLT => 10,
				ApiBase::PARAM_TYPE => 'limit',
				ApiBase::PARAM_MIN => 1,
				ApiBase::PARAM_MAX => ApiBase::LIMIT_BIG1,
				ApiBase::PARAM_MAX2 => ApiBase::LIMIT_BIG2
			),
		);

		if ( $this->getConfig()->get( 'MiserMode' ) ) {
			$ret['mime'][ApiBase::PARAM_HELP_MSG] = 'api-help-param-disabled-in-miser-mode';
		}

		return $ret;
	}

	private $propertyFilter = array( 'archivename', 'thumbmime', 'uploadwarning' );

	protected function getExamplesMessages() {
		return array(
			'action=query&list=allimages&aifrom=B'
				=> 'apihelp-query+allimages-example-B',
			'action=query&list=allimages&aiprop=user|timestamp|url&' .
				'aisort=timestamp&aidir=older'
				=> 'apihelp-query+allimages-example-recent',
			'action=query&list=allimages&aimime=image/png|image/gif'
				=> 'apihelp-query+allimages-example-mimetypes',
			'action=query&generator=allimages&gailimit=4&' .
				'gaifrom=T&prop=imageinfo'
				=> 'apihelp-query+allimages-example-generator',
		);
	}

	public function getHelpUrls() {
		return 'https://www.mediawiki.org/wiki/API:Allimages';
	}
}
