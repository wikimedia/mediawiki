<?php

namespace MediaWiki\Rest\Handler;

use File;
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
 * Handler class for media meta-data
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
	 * @var Title|bool|null
	 */
	private $title = null;

	/**
	 * @var File|bool|null
	 */
	private $file = null;

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
	 * @return Title|bool Title or false if unable to retrieve title
	 */
	private function getTitle() {
		if ( $this->title === null ) {
			$this->title =
				Title::newFromText( $this->getValidatedParams()['title'], NS_FILE ) ?? false;
		}
		return $this->title;
	}

	/**
	 * @return File|bool File or false if unable to retrieve file
	 */
	private function getFile() {
		if ( $this->file === null ) {
			$title = $this->getTitle();
			$this->file =
				$this->repoGroup->findFile( $title, [ 'private' => $this->user ] ) ?? false;
		}
		return $this->file;
	}

	/**
	 * @param string $title
	 * @return Response
	 * @throws LocalizedHttpException
	 */
	public function run( $title ) {
		$titleObj = $this->getTitle();
		$fileObj = $this->getFile();

		if ( !$titleObj || !$titleObj->exists() ) {
			throw new LocalizedHttpException(
				MessageValue::new( 'rest-nonexistent-title' )->plaintextParams(
					$titleObj->getPrefixedDBkey()
				),
				404
			);
		}

		if ( !$this->permissionManager->userCan( 'read', $this->user, $titleObj ) ) {
			throw new LocalizedHttpException(
				MessageValue::new( 'rest-permission-denied-title' )->plaintextParams(
					$titleObj->getPrefixedDBkey()
				),
				403
			);
		}

		if ( !$fileObj || !$fileObj->exists() ) {
			throw new LocalizedHttpException(
				MessageValue::new( 'rest-cannot-load-file' )->plaintextParams(
					$titleObj->getPrefixedDBkey()
				),
				404
			);
		}

		$response = $this->getResponse( $fileObj );
		return $this->getResponseFactory()->createJson( $response );
	}

	/**
	 * @param File $file the file to load media links for
	 * @return array response data
	 */
	private function getResponse( File $file ) : array {
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

	/**
	 * @return string|null
	 * @throws LocalizedHttpException
	 */
	protected function getETag(): ?string {
		$file = $this->getFile();
		if ( !$file || !$file->exists() ) {
			return null;
		}

		return '"' . $file->getSha1() . '"';
	}

	/**
	 * @return string|null
	 * @throws LocalizedHttpException
	 */
	protected function getLastModified(): ?string {
		$file = $this->getFile();
		if ( !$file || !$file->exists() ) {
			return null;
		}

		return $file->getTimestamp();
	}

	/**
	 * @return bool
	 */
	protected function hasRepresentation() {
		$file = $this->getFile();
		return $file ? $file->exists() : false;
	}
}
