<?php

namespace MediaWiki\Rest\Handler;

use MediaWiki\FileRepo\File\File;
use MediaWiki\FileRepo\RepoGroup;
use MediaWiki\Linker\Linker;
use MediaWiki\MainConfigNames;
use MediaWiki\MediaWikiServices;
use MediaWiki\Page\ExistingPageRecord;
use MediaWiki\Page\PageLookup;
use MediaWiki\Rest\Handler;
use MediaWiki\Rest\LocalizedHttpException;
use MediaWiki\Rest\Response;
use MediaWiki\Rest\SimpleHandler;
use Wikimedia\Message\MessageValue;
use Wikimedia\ParamValidator\ParamValidator;

/**
 * Handler for listing public thumbnail derivatives for a file.
 */
class MediaFileThumbnailsHandler extends SimpleHandler {
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
	 * Resolve the file page referenced by the request title.
	 *
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
	 * Resolve the file object referenced by the request title.
	 *
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

		// TODO: Consider creating a File info service
		// for MediaFileHandler and MediaFileThumbnailsHandler to use, to encapsulate the
		// logic of loading and checking the file and page. Since both handlers right now
		// repeat the same logic for loading the file and checking permissions, existence,
		// and thumbnailability.
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

		$file = $this->getFile();
		if ( !$file || !$file->exists() ) {
			throw new LocalizedHttpException(
				MessageValue::new( 'rest-cannot-load-file' )->plaintextParams( $title ),
				404
			);
		}

		if ( !$file->allowInlineDisplay() || !$file->getHandler() ) {
			throw new LocalizedHttpException(
				MessageValue::new( 'rest-file-not-thumbnailable' )->plaintextParams( $title ),
				400
			);
		}

		$response = $this->getResponseFactory()->createJson( $this->getResponseData( $file ) );

		// Set caching headers to one hour
		$response->setHeader(
			'Cache-Control',
			'max-age=3600'
		);
		return $response;
	}

	/**
	 * Build the JSON response payload for the thumbnail listing.
	 *
	 * @param File $file
	 * @return array
	 * @throws LocalizedHttpException
	 */
	private function getResponseData( File $file ): array {
		$urlUtils = MediaWikiServices::getInstance()->getUrlUtils();
		$thumbnails = [];

		foreach ( $this->getStandardThumbnailWidths( $file ) as $width ) {
			[ $thumbWidth, $thumbHeight ] = $file->getDisplayWidthHeight( $width, $width );
			$transform = $file->transform( [
				'width' => $thumbWidth,
				'height' => $thumbHeight,
			] );

			if ( !$transform || $transform->isError() || $transform->getUrl() === $file->getUrl() ) {
				continue;
			}

			$thumbnail = [
				'width' => (int)$transform->getWidth(),
				'height' => (int)$transform->getHeight(),
				'url' => (string)$urlUtils->expand( $transform->getUrl(), PROTO_RELATIVE ),
			];

			$handler = $file->getHandler();
			if ( $handler ) {
				[ , $mime ] = $handler->getThumbType(
					$transform->getExtension(),
					$file->getMimeType(),
					[ 'width' => $thumbWidth, 'height' => $thumbHeight ]
				);
				$thumbnail['mime'] = $mime;

				Linker::processResponsiveImages( $file, $transform, [
					'width' => $thumbnail['width'],
					'height' => $thumbnail['height'],
				] );
				foreach ( $transform->responsiveUrls as $density => $responsiveUrl ) {
					$thumbnail['responsive_urls'][$density] =
						(string)$urlUtils->expand( $responsiveUrl, PROTO_RELATIVE );
				}
			}

			$thumbnails[] = $thumbnail;
		}

		if ( !$thumbnails ) {
			throw new LocalizedHttpException(
				MessageValue::new( 'rest-file-not-thumbnailable' )
					->plaintextParams( $file->getTitle()->getText() ),
				400
			);
		}

		return [
			'title' => $file->getTitle()->getText(),
			'original' => [
				'mediatype' => $file->getMediaType(),
				'width' => $file->getWidth() ?: null,
				'height' => $file->getHeight() ?: null,
				'url' => (string)$urlUtils->expand( $file->getUrl(), PROTO_RELATIVE ),
			],
			'thumbnails' => $thumbnails,
		];
	}

	/**
	 * @param File $file
	 * @return int[]
	 */
	private function getStandardThumbnailWidths( File $file ): array {
		$config = MediaWikiServices::getInstance()->getMainConfig();
		$widths = $config->get( MainConfigNames::ThumbnailSteps );

		// Sanity check
		if ( !is_array( $widths ) || !$widths ) {
			$widths = [];
		}

		// Normalize values to ints, remove duplicates and falsy values, and sort
		$widths = array_values( array_unique( array_filter( array_map( 'intval', $widths ) ) ) );
		sort( $widths );

		$originalWidth = $file->getWidth();
		if ( $originalWidth > 0 ) {
			$widths = array_values( array_filter( $widths,
				static fn ( int $width ): bool => $width < $originalWidth
			) );
		}

		return $widths;
	}

	/** @inheritDoc */
	public function needsWriteAccess() {
		return false;
	}

	/** @inheritDoc */
	public function getParamSettings() {
		return [
			'title' => [
				self::PARAM_SOURCE => 'path',
				ParamValidator::PARAM_TYPE => 'string',
				ParamValidator::PARAM_REQUIRED => true,
				Handler::PARAM_DESCRIPTION => new MessageValue( 'rest-param-desc-media-file-title' ),
				Handler::PARAM_EXAMPLE => 'File:Fennec_Fox.jpg',
			],
		];
	}

	/** @inheritDoc */
	protected function getETag(): ?string {
		$file = $this->getFile();
		if ( !$file || !$file->exists() ) {
			return null;
		}

		return '"' . $file->getSha1() . '"';
	}

	/** @inheritDoc */
	protected function getLastModified(): ?string {
		$file = $this->getFile();
		if ( !$file || !$file->exists() ) {
			return null;
		}

		return $file->getTimestamp();
	}

	/** @inheritDoc */
	protected function hasRepresentation() {
		$file = $this->getFile();
		return $file && $file->exists();
	}

	/** @inheritDoc */
	public function getResponseBodySchemaFileName( string $method ): ?string {
		return __DIR__ . '/Schema/MediaFileThumbnails.json';
	}

	/** @inheritDoc */
	protected function generateResponseSpec( string $method ): array {
		$spec = parent::generateResponseSpec( $method );

		// Add error responses for the various failure cases
		$baseErrorSchema = [
			'type' => 'object',
			'required' => [ 'httpCode', 'errorKey' ],
			'properties' => [
				'httpCode' => [ 'type' => 'integer' ],
				'httpReason' => [ 'type' => 'string' ],
				'message' => [ 'type' => 'string' ],
				'messageTranslations' => [
					'type' => 'object',
					'additionalProperties' => [ 'type' => 'string' ],
				],
				'errorKey' => [ 'type' => 'string' ],
			],
		];

		$notThumbnailableSchema = [
			'allOf' => [
				$baseErrorSchema,
				[
					'type' => 'object',
					'properties' => [
						'httpCode' => [
							'type' => 'integer',
							'enum' => [ 400 ],
							'description' => 'HTTP response status code',
						],
						'errorKey' => [
							'type' => 'string',
							'enum' => [ 'rest-file-not-thumbnailable' ],
							'description' => 'HTTP response error key',
						],
						],
				],
			],
		];

		$permissionDeniedSchema = [
			'allOf' => [
				$baseErrorSchema,
				[
					'type' => 'object',
					'properties' => [
						'httpCode' => [
							'type' => 'integer',
							'enum' => [ 403 ],
							'description' => 'HTTP response status code',
						],
						'errorKey' => [
							'type' => 'string',
							'enum' => [ 'rest-permission-denied-title' ],
							'description' => 'HTTP response error key',
						],
					],
				],
			],
		];

		$notFoundSchema = [
			'oneOf' => [
				[
					'allOf' => [
						$baseErrorSchema,
						[
							'type' => 'object',
							'properties' => [
								'httpCode' => [
									'type' => 'integer',
									'enum' => [ 404 ],
									'description' => 'HTTP response status code',
								],
								'errorKey' => [
									'type' => 'string',
									'enum' => [ 'rest-nonexistent-title' ],
									'description' => 'HTTP response error key',
								],
							],
						],
					],
				],
				[
					'allOf' => [
						$baseErrorSchema,
						[
							'type' => 'object',
							'properties' => [
								'httpCode' => [
									'type' => 'integer',
									'enum' => [ 404 ],
									'description' => 'HTTP response status code',
								],
								'errorKey' => [
									'type' => 'string',
									'enum' => [ 'rest-cannot-load-file' ],
									'description' => 'HTTP response error key',
								],
							],
						],
					],
				],
			],
		];

		$spec['400'] = [
			self::OPENAPI_DESCRIPTION_KEY => 'Bad Request',
			'content' => [
				'application/json' => [
					'schema' => $notThumbnailableSchema,
					'example' => [
						'httpCode' => 400,
						'httpReason' => 'Bad Request',
						'message' => 'The file (File:Fennec_Fox.jpg) does not support public thumbnail generation.',
						'messageTranslations' => [
							'en' => 'The file (File:Fennec_Fox.jpg) does not support public thumbnail generation.'
						],
						'errorKey' => 'rest-file-not-thumbnailable',
					],
				],
			],
		];

		$spec['403'] = [
			self::OPENAPI_DESCRIPTION_KEY => 'Forbidden',
			'content' => [
				'application/json' => [
					'schema' => $permissionDeniedSchema,
					'example' => [
						'httpCode' => 403,
						'httpReason' => 'Forbidden',
						'message' => 'The user does not have rights to read title (File:Fennec_Fox.jpg)',
						'messageTranslations' => [
							'en' => 'The user does not have rights to read title (File:Fennec_Fox.jpg)'
						],
						'errorKey' => 'rest-permission-denied-title',
					],
				],
			],
		];

		$spec['404'] = [
			self::OPENAPI_DESCRIPTION_KEY => 'Not Found',
			'content' => [
				'application/json' => [
					'schema' => $notFoundSchema,
					'example' => [
						'httpCode' => 404,
						'httpReason' => 'Not Found',
						'message' => 'The specified page (File:Fennec_Fox.jpg) does not exist',
						'messageTranslations' => [
							'en' => 'The specified page (File:Fennec_Fox.jpg) does not exist'
						],
						'errorKey' => 'rest-nonexistent-title',
					],
				],
			],
		];

		return $spec;
	}
}
