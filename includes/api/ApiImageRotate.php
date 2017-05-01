<?php
/**
 *
 * Created on January 3rd, 2013
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

class ApiImageRotate extends ApiBase {
	private $mPageSet = null;

	public function execute() {
		$this->useTransactionalTimeLimit();

		$params = $this->extractRequestParams();
		$rotation = $params['rotation'];

		$continuationManager = new ApiContinuationManager( $this, [], [] );
		$this->setContinuationManager( $continuationManager );

		$pageSet = $this->getPageSet();
		$pageSet->execute();

		$result = [];

		$result = $pageSet->getInvalidTitlesAndRevisions( [
			'invalidTitles', 'special', 'missingIds', 'missingRevIds', 'interwikiTitles',
		] );

		// Check if user can add tags
		if ( count( $params['tags'] ) ) {
			$ableToTag = ChangeTags::canAddTagsAccompanyingChange( $params['tags'], $this->getUser() );
			if ( !$ableToTag->isOK() ) {
				$this->dieStatus( $ableToTag );
			}
		}

		foreach ( $pageSet->getTitles() as $title ) {
			$r = [];
			$r['id'] = $title->getArticleID();
			ApiQueryBase::addTitleInfo( $r, $title );
			if ( !$title->exists() ) {
				$r['missing'] = true;
				if ( $title->isKnown() ) {
					$r['known'] = true;
				}
			}

			$file = wfFindFile( $title, [ 'latest' => true ] );
			if ( !$file ) {
				$r['result'] = 'Failure';
				$r['errors'] = $this->getErrorFormatter()->arrayFromStatus(
					Status::newFatal( 'apierror-filedoesnotexist' )
				);
				$result[] = $r;
				continue;
			}
			$handler = $file->getHandler();
			if ( !$handler || !$handler->canRotate() ) {
				$r['result'] = 'Failure';
				$r['errors'] = $this->getErrorFormatter()->arrayFromStatus(
					Status::newFatal( 'apierror-filetypecannotberotated' )
				);
				$result[] = $r;
				continue;
			}

			// Check whether we're allowed to rotate this file
			$permError = $this->checkTitleUserPermissions( $file->getTitle(), [ 'edit', 'upload' ] );
			if ( $permError ) {
				$r['result'] = 'Failure';
				$r['errors'] = $this->getErrorFormatter()->arrayFromStatus(
					$this->errorArrayToStatus( $permError )
				);
				$result[] = $r;
				continue;
			}

			$srcPath = $file->getLocalRefPath();
			if ( $srcPath === false ) {
				$r['result'] = 'Failure';
				$r['errors'] = $this->getErrorFormatter()->arrayFromStatus(
					Status::newFatal( 'apierror-filenopath' )
				);
				$result[] = $r;
				continue;
			}
			$ext = strtolower( pathinfo( "$srcPath", PATHINFO_EXTENSION ) );
			$tmpFile = TempFSFile::factory( 'rotate_', $ext, wfTempDir() );
			$dstPath = $tmpFile->getPath();
			$err = $handler->rotate( $file, [
				'srcPath' => $srcPath,
				'dstPath' => $dstPath,
				'rotation' => $rotation
			] );
			if ( !$err ) {
				$comment = wfMessage(
					'rotate-comment'
				)->numParams( $rotation )->inContentLanguage()->text();
				$status = $file->upload(
					$dstPath,
					$comment,
					$comment,
					0,
					false,
					false,
					$this->getUser(),
					$params['tags'] ?: []
				);
				if ( $status->isGood() ) {
					$r['result'] = 'Success';
				} else {
					$r['result'] = 'Failure';
					$r['errors'] = $this->getErrorFormatter()->arrayFromStatus( $status );
				}
			} else {
				$r['result'] = 'Failure';
				$r['errors'] = $this->getErrorFormatter()->arrayFromStatus(
					Status::newFatal( ApiMessage::create( $err->getMsg() ) )
				);
			}
			$result[] = $r;
		}
		$apiResult = $this->getResult();
		ApiResult::setIndexedTagName( $result, 'page' );
		$apiResult->addValue( null, $this->getModuleName(), $result );

		$this->setContinuationManager( null );
		$continuationManager->setContinuationIntoResult( $apiResult );
	}

	/**
	 * Get a cached instance of an ApiPageSet object
	 * @return ApiPageSet
	 */
	private function getPageSet() {
		if ( $this->mPageSet === null ) {
			$this->mPageSet = new ApiPageSet( $this, 0, NS_FILE );
		}

		return $this->mPageSet;
	}

	public function mustBePosted() {
		return true;
	}

	public function isWriteMode() {
		return true;
	}

	public function getAllowedParams( $flags = 0 ) {
		$result = [
			'rotation' => [
				ApiBase::PARAM_TYPE => [ '90', '180', '270' ],
				ApiBase::PARAM_REQUIRED => true
			],
			'continue' => [
				ApiBase::PARAM_HELP_MSG => 'api-help-param-continue',
			],
			'tags' => [
				ApiBase::PARAM_TYPE => 'tags',
				ApiBase::PARAM_ISMULTI => true,
			],
		];
		if ( $flags ) {
			$result += $this->getPageSet()->getFinalParams( $flags );
		}

		return $result;
	}

	public function needsToken() {
		return 'csrf';
	}

	protected function getExamplesMessages() {
		return [
			'action=imagerotate&titles=File:Example.jpg&rotation=90&token=123ABC'
				=> 'apihelp-imagerotate-example-simple',
			'action=imagerotate&generator=categorymembers&gcmtitle=Category:Flip&gcmtype=file&' .
				'rotation=180&token=123ABC'
				=> 'apihelp-imagerotate-example-generator',
		];
	}
}
