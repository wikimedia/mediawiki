<?php
/**
 * Copyright © 2008 Roan Kattouw <roan.kattouw@gmail.com>
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

use MediaWiki\FileRepo\File\File;
use MediaWiki\FileRepo\RepoGroup;
use Wikimedia\ParamValidator\ParamValidator;
use Wikimedia\ParamValidator\TypeDef\IntegerDef;

/**
 * A query module to list duplicates of the given file(s)
 *
 * @ingroup API
 */
class ApiQueryDuplicateFiles extends ApiQueryGeneratorBase {

	private RepoGroup $repoGroup;

	public function __construct(
		ApiQuery $query,
		string $moduleName,
		RepoGroup $repoGroup
	) {
		parent::__construct( $query, $moduleName, 'df' );
		$this->repoGroup = $repoGroup;
	}

	public function execute() {
		$this->run();
	}

	/** @inheritDoc */
	public function getCacheMode( $params ) {
		return 'public';
	}

	/** @inheritDoc */
	public function executeGenerator( $resultPageSet ) {
		$this->run( $resultPageSet );
	}

	/**
	 * @param ApiPageSet|null $resultPageSet
	 */
	private function run( $resultPageSet = null ) {
		$params = $this->extractRequestParams();
		$namespaces = $this->getPageSet()->getGoodAndMissingTitlesByNamespace();
		if ( empty( $namespaces[NS_FILE] ) ) {
			return;
		}
		$images = $namespaces[NS_FILE];

		if ( $params['dir'] == 'descending' ) {
			$images = array_reverse( $images );
		}

		$skipUntilThisDup = false;
		if ( isset( $params['continue'] ) ) {
			$cont = $this->parseContinueParamOrDie( $params['continue'], [ 'string', 'string' ] );
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
		if ( $params['localonly'] ) {
			$files = $this->repoGroup->getLocalRepo()->findFiles( $filesToFind );
		} else {
			$files = $this->repoGroup->findFiles( $filesToFind );
		}

		$fit = true;
		$count = 0;
		$titles = [];

		$sha1s = [];
		foreach ( $files as $file ) {
			/** @var File $file */
			$sha1s[$file->getName()] = $file->getSha1();
		}

		// find all files with the hashes, result format is:
		// [ hash => [ dup1, dup2 ], hash1 => ... ]
		$filesToFindBySha1s = array_unique( array_values( $sha1s ) );
		if ( $params['localonly'] ) {
			$filesBySha1s = $this->repoGroup->getLocalRepo()->findBySha1s( $filesToFindBySha1s );
		} else {
			$filesBySha1s = $this->repoGroup->findBySha1s( $filesToFindBySha1s );
		}

		// iterate over $images to handle continue param correct
		foreach ( $images as $image => $pageId ) {
			if ( !isset( $sha1s[$image] ) ) {
				continue; // file does not exist
			}
			$sha1 = $sha1s[$image];
			$dupFiles = $filesBySha1s[$sha1];
			if ( $params['dir'] == 'descending' ) {
				$dupFiles = array_reverse( $dupFiles );
			}
			/** @var File $dupFile */
			foreach ( $dupFiles as $dupFile ) {
				$dupName = $dupFile->getName();
				if ( $image == $dupName && $dupFile->isLocal() ) {
					continue; // ignore the local file itself
				}
				if ( $skipUntilThisDup !== false && $dupName < $skipUntilThisDup ) {
					continue; // skip to pos after the image from continue param
				}
				$skipUntilThisDup = false;
				if ( ++$count > $params['limit'] ) {
					$fit = false; // break outer loop
					// We're one over limit which shows that
					// there are additional images to be had. Stop here...
					$this->setContinueEnumParameter( 'continue', $image . '|' . $dupName );
					break;
				}
				if ( $resultPageSet !== null ) {
					$titles[] = $dupFile->getTitle();
				} else {
					$r = [
						'name' => $dupName,
						'timestamp' => wfTimestamp( TS_ISO_8601, $dupFile->getTimestamp() ),
						'shared' => !$dupFile->isLocal(),
					];
					$uploader = $dupFile->getUploader( File::FOR_PUBLIC );
					if ( $uploader ) {
						$r['user'] = $uploader->getName();
					}
					$fit = $this->addPageSubItem( $pageId, $r );
					if ( !$fit ) {
						$this->setContinueEnumParameter( 'continue', $image . '|' . $dupName );
						break;
					}
				}
			}
			if ( !$fit ) {
				break;
			}
		}
		if ( $resultPageSet !== null ) {
			$resultPageSet->populateFromTitles( $titles );
		}
	}

	/** @inheritDoc */
	public function getAllowedParams() {
		return [
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
			'dir' => [
				ParamValidator::PARAM_DEFAULT => 'ascending',
				ParamValidator::PARAM_TYPE => [
					'ascending',
					'descending'
				]
			],
			'localonly' => false,
		];
	}

	/** @inheritDoc */
	protected function getExamplesMessages() {
		return [
			'action=query&titles=File:Albert_Einstein_Head.jpg&prop=duplicatefiles'
				=> 'apihelp-query+duplicatefiles-example-simple',
			'action=query&generator=allimages&prop=duplicatefiles'
				=> 'apihelp-query+duplicatefiles-example-generated',
		];
	}

	/** @inheritDoc */
	public function getHelpUrls() {
		return 'https://www.mediawiki.org/wiki/Special:MyLanguage/API:Duplicatefiles';
	}
}

/** @deprecated class alias since 1.43 */
class_alias( ApiQueryDuplicateFiles::class, 'ApiQueryDuplicateFiles' );
