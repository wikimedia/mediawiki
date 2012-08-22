<?php
/**
 *
 *
 * Created on Sep 27, 2008
 *
 * Copyright © 2008 Roan Kattouw "<Firstname>.<Lastname>@gmail.com"
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
 * A query module to list duplicates of the given file(s)
 *
 * @ingroup API
 */
class ApiQueryDuplicateFiles extends ApiQueryGeneratorBase {

	public function __construct( $query, $moduleName ) {
		parent::__construct( $query, $moduleName, 'df' );
	}

	public function execute() {
		$this->run();
	}

	public function getCacheMode( $params ) {
		return 'public';
	}

	public function executeGenerator( $resultPageSet ) {
		$this->run( $resultPageSet );
	}

	/**
	 * @param $resultPageSet ApiPageSet
	 * @return
	 */
	private function run( $resultPageSet = null ) {
		$params = $this->extractRequestParams();
		$namespaces = $this->getPageSet()->getAllTitlesByNamespace();
		if ( empty( $namespaces[NS_FILE] ) ) {
			return;
		}
		$images = $namespaces[NS_FILE];

		if( $params['dir'] == 'descending' ) {
			$images = array_reverse( $images );
		}

		$skipUntilThisDup = false;
		if ( isset( $params['continue'] ) ) {
			$cont = explode( '|', $params['continue'] );
			if ( count( $cont ) != 2 ) {
				$this->dieUsage( 'Invalid continue param. You should pass the ' .
					'original value returned by the previous query', '_badcontinue' );
			}
			$fromImage = $cont[0];
			$skipUntilThisDup = $cont[1];
			// Filter out any images before $fromImage
			foreach ( $images as $image => $pageId ) {
				if ( $image < $fromImage ) {
					unset( $images[$image] );
				} else {
					break;
				}
			}
		}

		$filesToFind = array_keys( $images );
		if( $params['localonly'] ) {
			$files = RepoGroup::singleton()->getLocalRepo()->findFiles( $filesToFind );
		} else {
			$files = RepoGroup::singleton()->findFiles( $filesToFind );
		}

		$fit = true;
		$count = 0;
		$titles = array();

		$sha1s = array();
		foreach ( $files as $file ) {
			$sha1s[$file->getName()] = $file->getSha1();
		}

		// find all files with the hashes, result format is: array( hash => array( dup1, dup2 ), hash1 => ... )
		$filesToFindBySha1s = array_unique( array_values( $sha1s ) );
		if( $params['localonly'] ) {
			$filesBySha1s = RepoGroup::singleton()->getLocalRepo()->findBySha1s( $filesToFindBySha1s );
		} else {
			$filesBySha1s = RepoGroup::singleton()->findBySha1s( $filesToFindBySha1s );
		}

		// iterate over $images to handle continue param correct
		foreach( $images as $image => $pageId ) {
			if( !isset( $sha1s[$image] ) ) {
				continue; //file does not exist
			}
			$sha1 = $sha1s[$image];
			$dupFiles = $filesBySha1s[$sha1];
			if( $params['dir'] == 'descending' ) {
				$dupFiles = array_reverse( $dupFiles );
			}
			foreach ( $dupFiles as $dupFile ) {
				$dupName = $dupFile->getName();
				if( $image == $dupName && $dupFile->isLocal() ) {
					continue; //ignore the local file itself
				}
				if( $skipUntilThisDup !== false && $dupName < $skipUntilThisDup ) {
					continue; //skip to pos after the image from continue param
				}
				$skipUntilThisDup = false;
				if ( ++$count > $params['limit'] ) {
					$fit = false; //break outer loop
					// We're one over limit which shows that
					// there are additional images to be had. Stop here...
					$this->setContinueEnumParameter( 'continue', $image . '|' . $dupName );
					break;
				}
				if ( !is_null( $resultPageSet ) ) {
					$titles[] = $file->getTitle();
				} else {
					$r = array(
						'name' => $dupName,
						'user' => $dupFile->getUser( 'text' ),
						'timestamp' => wfTimestamp( TS_ISO_8601, $dupFile->getTimestamp() )
					);
					if( !$dupFile->isLocal() ) {
						$r['shared'] = '';
					}
					$fit = $this->addPageSubItem( $pageId, $r );
					if ( !$fit ) {
						$this->setContinueEnumParameter( 'continue', $image . '|' . $dupName );
						break;
					}
				}
			}
			if( !$fit ) {
				break;
			}
		}
		if ( !is_null( $resultPageSet ) ) {
			$resultPageSet->populateFromTitles( $titles );
		}
	}

	public function getAllowedParams() {
		return array(
			'limit' => array(
				ApiBase::PARAM_DFLT => 10,
				ApiBase::PARAM_TYPE => 'limit',
				ApiBase::PARAM_MIN => 1,
				ApiBase::PARAM_MAX => ApiBase::LIMIT_BIG1,
				ApiBase::PARAM_MAX2 => ApiBase::LIMIT_BIG2
			),
			'continue' => null,
			'dir' => array(
				ApiBase::PARAM_DFLT => 'ascending',
				ApiBase::PARAM_TYPE => array(
					'ascending',
					'descending'
				)
			),
			'localonly' => false,
		);
	}

	public function getParamDescription() {
		return array(
			'limit' => 'How many duplicate files to return',
			'continue' => 'When more results are available, use this to continue',
			'dir' => 'The direction in which to list',
			'localonly' => 'Look only for files in the local repository',
		);
	}

	public function getResultProperties() {
		return array(
			'' => array(
				'name' => 'string',
				'user' => 'string',
				'timestamp' => 'timestamp',
				'shared' => 'boolean',
			)
		);
	}

	public function getDescription() {
		return 'List all files that are duplicates of the given file(s) based on hash values';
	}

	public function getPossibleErrors() {
		return array_merge( parent::getPossibleErrors(), array(
			array( 'code' => '_badcontinue', 'info' => 'Invalid continue param. You should pass the original value returned by the previous query' ),
		) );
	}

	public function getExamples() {
		return array(
			'api.php?action=query&titles=File:Albert_Einstein_Head.jpg&prop=duplicatefiles',
			'api.php?action=query&generator=allimages&prop=duplicatefiles',
		);
	}

	public function getHelpUrls() {
		return 'https://www.mediawiki.org/wiki/API:Properties#duplicatefiles_.2F_df';
	}

	public function getVersion() {
		return __CLASS__ . ': $Id$';
	}
}
