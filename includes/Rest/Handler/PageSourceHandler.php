<?php

namespace MediaWiki\Rest\Handler;

use MediaWiki\Rest\LocalizedHttpException;
use MediaWiki\Rest\Response;
use MediaWiki\Revision\RevisionAccessException;
use MediaWiki\Revision\RevisionRecord;
use MediaWiki\Revision\SlotRecord;
use MediaWiki\Revision\SuppressedDataException;
use TextContent;
use Wikimedia\Message\MessageValue;

/**
 * Handler class for Core REST API Page Source endpoint
 */
class PageSourceHandler extends LatestPageContentHandler {
	private const MAX_AGE_200 = 5;

	// Default to main slot
	private function getRole(): string {
		return SlotRecord::MAIN;
	}

	/**
	 * @param string $slotRole
	 * @param RevisionRecord $revision
	 * @return TextContent $content
	 * @throws LocalizedHttpException slot content is not TextContent or Revision/Slot is inaccessible
	 */
	protected function getPageContent( string $slotRole, RevisionRecord $revision ): TextContent {
		try {
			$content = $revision
				->getSlot( $slotRole, RevisionRecord::FOR_THIS_USER, $this->user )
				->getContent()
				->convert( CONTENT_MODEL_TEXT );
			if ( !( $content instanceof TextContent ) ) {
				throw new LocalizedHttpException( MessageValue::new( 'rest-page-source-type-error' ), 400 );
			}
		} catch ( SuppressedDataException $e ) {
			throw new LocalizedHttpException(
				MessageValue::new( 'rest-permission-denied-revision' )->numParams( $revision->getId() ),
				403
			);
		} catch ( RevisionAccessException $e ) {
			throw new LocalizedHttpException(
				MessageValue::new( 'rest-nonexistent-revision' )->numParams( $revision->getId() ),
				404
			);
		}
		return $content;
	}

	/**
	 * @param string $title
	 * @return Response
	 * @throws LocalizedHttpException
	 */
	public function run( string $title ): Response {
		$titleObject = $this->getTitle();
		if ( !$titleObject || !$titleObject->getArticleID() ) {
			throw new LocalizedHttpException(
				MessageValue::new( 'rest-nonexistent-title' )->plaintextParams( $title ),
				404
			);
		}
		if ( !$this->isAccessible( $titleObject ) ) {
			throw new LocalizedHttpException(
				MessageValue::new( 'rest-permission-denied-title' )->plaintextParams( $title ),
				403
			);
		}
		$revision = $this->getLatestRevision();
		if ( !$revision ) {
			throw new LocalizedHttpException(
				MessageValue::new( 'rest-no-revision' ),
				404
			);
		}
		$content = $this->getPageContent( $this->getRole(), $revision );
		$body = $this->constructMetadata( $titleObject, $revision );
		$body['source'] = $content->getText();

		$response = $this->getResponseFactory()->createJson( $body );
		$response->setHeader( 'Cache-Control', 'max-age=' . self::MAX_AGE_200 );
		return $response;
	}

	/**
	 * Returns an ETag representing a page's source. The ETag assumes a page's source has changed
	 * if the latest revision of a page has been made private, un-readable for another reason,
	 * or a newer revision exists.
	 * @return string
	 */
	protected function getETag(): string {
		$revision = $this->getLatestRevision();
		$latestRevision = $revision ? $revision->getId() : 'e0';

		$isAccessible = $this->isAccessible( $this->getTitle() );
		$accessibleTag = $isAccessible ? 'a1' : 'a0';

		$revisionTag = $latestRevision . $accessibleTag;
		return '"' . sha1( "$revisionTag" ) . '"';
	}

	/**
	 * @return string|null
	 */
	protected function getLastModified(): ?string {
		$revision = $this->getLatestRevision();
		if ( $revision ) {
			return $revision->getTimestamp();
		}
		return null;
	}
}
