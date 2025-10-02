<?php

namespace MediaWiki\Rest\Handler;

use File;
use MediaFileTrait;
use MediaWiki\Page\ExistingPageRecord;
use MediaWiki\Page\PageLookup;
use MediaWiki\Rest\LocalizedHttpException;
use MediaWiki\Rest\Response;
use MediaWiki\Rest\SimpleHandler;
use RepoGroup;
use Wikimedia\Message\MessageValue;
use Wikimedia\ParamValidator\ParamValidator;

/**
 * Handler class for media meta-data
 */
class MediaFileHandler extends SimpleHandler {
	use MediaFileTrait;

	private RepoGroup $repoGroup;
	private PageLookup $pageLookup;

	/**
	 * @var ExistingPageRecord|false|null
	 */
	private $page = false;

	/**
	 * @var File|false|null
	 */
	private $file = false;

	public function __construct(
		RepoGroup $repoGroup,
		PageLookup $pageLookup
	) {
		$this->repoGroup = $repoGroup;
		$this->pageLookup = $pageLookup;
	}

	/**
	 * @return ExistingPageRecord|null
	 */
	private function getPage(): ?ExistingPageRecord {
		if ( $this->page === false ) {
			$this->page = $this->pageLookup->getExistingPageByText(
					$this->getValidatedParams()['title'], NS_FILE
				);
		}
		return $this->page;
	}

	/**
	 * @return File|null
	 */
	private function getFile(): ?File {
		if ( $this->file === false ) {
			$page = $this->getPage();
			$this->file =
				// @phan-suppress-next-line PhanTypeMismatchArgumentNullable
				$this->repoGroup->findFile( $page, [ 'private' => $this->getAuthority() ] ) ?: null;
		}
		return $this->file;
	}

	/**
	 * @param string $title
	 * @return Response
	 * @throws LocalizedHttpException
	 */
	public function run( $title ) {
		$page = $this->getPage();

		if ( !$page ) {
			throw new LocalizedHttpException(
				MessageValue::new( 'rest-nonexistent-title' )->plaintextParams( $title ),
				404
			);
		}

		if ( !$this->getAuthority()->authorizeRead( 'read', $page ) ) {
			throw new LocalizedHttpException(
				MessageValue::new( 'rest-permission-denied-title' )->plaintextParams( $title ),
				403
			);
		}

		$fileObj = $this->getFile();
		if ( !$fileObj || !$fileObj->exists() ) {
			throw new LocalizedHttpException(
				MessageValue::new( 'rest-cannot-load-file' )->plaintextParams( $title ),
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
	private function getResponse( File $file ): array {
		[ $maxWidth, $maxHeight ] = self::getImageLimitsFromOption(
			$this->getAuthority()->getUser(), 'imagesize'
		);
		[ $maxThumbWidth, $maxThumbHeight ] = self::getImageLimitsFromOption(
			$this->getAuthority()->getUser(), 'thumbsize'
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

		return $this->getFileInfo( $file, $this->getAuthority(), $transforms );
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
		return $file && $file->exists();
	}

	public function getResponseBodySchemaFileName( string $method ): ?string {
		return 'includes/Rest/Handler/Schema/MediaFile.json';
	}
}
