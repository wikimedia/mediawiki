<?php

namespace MediaWiki\Rest\Handler;

use MediaFileTrait;
use MediaWiki\Permissions\PermissionManager;
use MediaWiki\Rest\LocalizedHttpException;
use MediaWiki\Rest\Response;
use MediaWiki\Rest\SimpleHandler;
use RepoGroup;
use RequestContext;
use Title;
use User;
use Wikimedia\Message\MessageValue;
use Wikimedia\ParamValidator\ParamValidator;

/**
 * Handler class for Core REST API endpoints that perform operations on revisions
 */
class MediaFileHandler extends SimpleHandler {
	use MediaFileTrait;

	/** @var PermissionManager */
	private $permissionManager;

	/** @var RepoGroup */
	private $repoGroup;

	/** @var User */
	private $user;

	/**
	 * @param PermissionManager $permissionManager
	 * @param RepoGroup $repoGroup
	 */
	public function __construct(
		PermissionManager $permissionManager,
		RepoGroup $repoGroup
	) {
		$this->permissionManager = $permissionManager;
		$this->repoGroup = $repoGroup;

		// @todo Inject this, when there is a good way to do that
		$this->user = RequestContext::getMain()->getUser();
	}

	/**
	 * @param string $title
	 * @return Response
	 * @throws LocalizedHttpException
	 */
	public function run( $title ) {
		$titleObj = Title::newFromText( $title );
		if ( !$titleObj || !$titleObj->getArticleID() ) {
			throw new LocalizedHttpException(
				MessageValue::new( 'rest-nonexistent-title' )->plaintextParams( $title ),
				404
			);
		}

		if ( !$this->permissionManager->userCan( 'read', $this->user, $titleObj ) ) {
			throw new LocalizedHttpException(
				MessageValue::new( 'rest-permission-denied-title' )->plaintextParams( $title ),
				403
			);
		}

		$response = $this->getResponse( $titleObj );
		return $this->getResponseFactory()->createJson( $response );
	}

	/**
	 * @param Title $titleObj the title to load media links for
	 * @return array response data
	 */
	private function getResponse( Title $titleObj ) : array {
		$file = $this->repoGroup->findFile( $titleObj, [ 'private' => $this->user ] );
		list( $maxWidth, $maxHeight ) = self::getImageLimitsFromOption(
			$this->user, 'imagesize'
		);
		list( $maxThumbWidth, $maxThumbHeight ) = self::getImageLimitsFromOption(
			$this->user, 'thumbsize'
		);
		$transforms = [
			'preferred' => [
				'maxWidth' => $maxWidth,
				'maxHeight' => $maxHeight
			],
			'thumbnail' => [
				'maxWidth' => $maxThumbWidth,
				'maxHeight' => $maxThumbHeight
			]
		];

		return $this->getFileInfo( $file, $this->user, $transforms );
	}

	public function needsWriteAccess() {
		return false;
	}

	public function getParamSettings() {
		return [
			'title' => [
				self::PARAM_SOURCE => 'path',
				ParamValidator::PARAM_TYPE => 'string',
				ParamValidator::PARAM_REQUIRED => true,
			],
		];
	}
}
