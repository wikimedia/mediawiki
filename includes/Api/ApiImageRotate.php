<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\Api;

use MediaWiki\ChangeTags\ChangeTags;
use MediaWiki\FileRepo\RepoGroup;
use MediaWiki\Status\Status;
use MediaWiki\Title\TitleFactory;
use Wikimedia\FileBackend\FSFile\TempFSFileFactory;
use Wikimedia\ParamValidator\ParamValidator;

/**
 * @ingroup API
 */
class ApiImageRotate extends ApiBase {
	/** @var ApiPageSet|null */
	private $mPageSet = null;

	private RepoGroup $repoGroup;
	private TempFSFileFactory $tempFSFileFactory;
	private TitleFactory $titleFactory;

	public function __construct(
		ApiMain $mainModule,
		string $moduleName,
		RepoGroup $repoGroup,
		TempFSFileFactory $tempFSFileFactory,
		TitleFactory $titleFactory
	) {
		parent::__construct( $mainModule, $moduleName );
		$this->repoGroup = $repoGroup;
		$this->tempFSFileFactory = $tempFSFileFactory;
		$this->titleFactory = $titleFactory;
	}

	public function execute() {
		$this->useTransactionalTimeLimit();

		$params = $this->extractRequestParams();
		$rotation = $params['rotation'];

		$continuationManager = new ApiContinuationManager( $this, [], [] );
		$this->setContinuationManager( $continuationManager );

		$pageSet = $this->getPageSet();
		$pageSet->execute();

		$result = $pageSet->getInvalidTitlesAndRevisions( [
			'invalidTitles', 'special', 'missingIds', 'missingRevIds', 'interwikiTitles',
		] );

		// Check if user can add tags
		if ( $params['tags'] ) {
			$ableToTag = ChangeTags::canAddTagsAccompanyingChange( $params['tags'], $this->getAuthority() );
			if ( !$ableToTag->isOK() ) {
				$this->dieStatus( $ableToTag );
			}
		}

		foreach ( $pageSet->getPages() as $page ) {
			$title = $this->titleFactory->newFromPageIdentity( $page );
			$r = [];
			$r['id'] = $title->getArticleID();
			ApiQueryBase::addTitleInfo( $r, $title );
			if ( !$title->exists() ) {
				$r['missing'] = true;
				if ( $title->isKnown() ) {
					$r['known'] = true;
				}
			}

			$file = $this->repoGroup->findFile( $title, [ 'latest' => true ] );
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
			$this->checkTitleUserPermissions( $file->getTitle(), [ 'edit', 'upload' ] );

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
			$tmpFile = $this->tempFSFileFactory->newTempFSFile( 'rotate_', $ext );
			$dstPath = $tmpFile->getPath();
			// @phan-suppress-next-line PhanUndeclaredMethod
			$err = $handler->rotate( $file, [
				'srcPath' => $srcPath,
				'dstPath' => $dstPath,
				'rotation' => $rotation
			] );
			if ( !$err ) {
				$comment = $this->msg(
					'rotate-comment'
				)->numParams( $rotation )->inContentLanguage()->text();
				// @phan-suppress-next-line PhanUndeclaredMethod
				$status = $file->upload(
					$dstPath,
					$comment,
					$comment,
					0,
					false,
					false,
					$this->getAuthority(),
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
		$this->mPageSet ??= new ApiPageSet( $this, 0, NS_FILE );

		return $this->mPageSet;
	}

	/** @inheritDoc */
	public function mustBePosted() {
		return true;
	}

	/** @inheritDoc */
	public function isWriteMode() {
		return true;
	}

	/** @inheritDoc */
	public function getAllowedParams( $flags = 0 ) {
		$result = [
			'rotation' => [
				ParamValidator::PARAM_TYPE => [ '90', '180', '270' ],
				ParamValidator::PARAM_REQUIRED => true
			],
			'continue' => [
				ApiBase::PARAM_HELP_MSG => 'api-help-param-continue',
			],
			'tags' => [
				ParamValidator::PARAM_TYPE => 'tags',
				ParamValidator::PARAM_ISMULTI => true,
			],
		];
		if ( $flags ) {
			$result += $this->getPageSet()->getFinalParams( $flags );
		}

		return $result;
	}

	/** @inheritDoc */
	public function needsToken() {
		return 'csrf';
	}

	/** @inheritDoc */
	protected function getExamplesMessages() {
		return [
			'action=imagerotate&titles=File:Example.jpg&rotation=90&token=123ABC'
				=> 'apihelp-imagerotate-example-simple',
			'action=imagerotate&generator=categorymembers&gcmtitle=Category:Flip&gcmtype=file&' .
				'rotation=180&token=123ABC'
				=> 'apihelp-imagerotate-example-generator',
		];
	}

	/** @inheritDoc */
	public function getHelpUrls() {
		return 'https://www.mediawiki.org/wiki/Special:MyLanguage/API:Imagerotate';
	}
}

/** @deprecated class alias since 1.43 */
class_alias( ApiImageRotate::class, 'ApiImageRotate' );
