<?php

namespace MediaWiki\Rest\Handler;

use MediaWiki\Rest\Handler;
use MediaWiki\Rest\LocalizedHttpException;
use MediaWiki\Rest\ResponseInterface;
use MediaWiki\Revision\RevisionRecord;
use MediaWiki\Revision\SlotRecord;
use Title;
use Wikimedia\Message\MessageValue;
use Wikimedia\ParamValidator\ParamValidator;

/**
 * @since 1.36
 */
class RevisionContentHelper extends PageContentHelper {

	/**
	 * @return int|null The ID of the target revision
	 */
	public function getRevisionId(): ?int {
		return isset( $this->parameters['id'] ) ? (int)$this->parameters['id'] : null;
	}

	/**
	 * @return string|null Title text or null if unable to retrieve title
	 */
	public function getTitleText(): ?string {
		$revision = $this->getTargetRevision();
		return $revision
			? $this->titleFormatter->getPrefixedText( $revision->getPageAsLinkTarget() )
			: null;
	}

	/**
	 * @return Title|bool Title or false if unable to retrieve title
	 */
	public function getTitle() {
		$revision = $this->getTargetRevision();
		return $revision
			? $this->titleFactory->castFromLinkTarget( $revision->getPageAsLinkTarget() )
			: false;
	}

	/**
	 * @return RevisionRecord|bool latest revision or false if unable to retrieve revision
	 */
	public function getTargetRevision() {
		if ( $this->targetRevision === null ) {
			$revId = $this->getRevisionId();
			if ( $revId ) {
				$this->targetRevision = $this->revisionLookup->getRevisionById( $revId ) ?: false;
			} else {
				$this->targetRevision = false;
			}
		}
		return $this->targetRevision;
	}

	/**
	 * @return bool
	 */
	public function isAccessible(): bool {
		if ( !parent::isAccessible() ) {
			return false;
		}

		$revision = $this->getTargetRevision();

		// TODO: allow authorized users to see suppressed content. Set cache control accordingly.

		if ( !$revision ||
			!$revision->audienceCan( RevisionRecord::DELETED_TEXT, RevisionRecord::FOR_PUBLIC )
		) {
			return false;
		}

		return true;
	}

	/**
	 * @return bool
	 */
	public function hasContent(): bool {
		return (bool)$this->getTargetRevision();
	}

	public function setCacheControl( ResponseInterface $response, int $expiry = null ) {
		$revision = $this->getTargetRevision();

		if ( $revision && $revision->getVisibility() !== 0 ) {
			// The revision is not public, so it's not cacheable!
			return;
		}

		parent::setCacheControl( $response, $expiry );
	}

	/**
	 * @return array
	 */
	public function constructMetadata(): array {
		$titleObject = $this->getTitle();
		$revision = $this->getTargetRevision();

		$mainSlot = $revision->getSlot( SlotRecord::MAIN, RevisionRecord::RAW );

		$metadata = [
			'id' => $revision->getId(),
			'size' => $revision->getSize(),
			'minor' => $revision->isMinor(),
			'timestamp' => wfTimestampOrNull( TS_ISO_8601, $revision->getTimestamp() ),
			'content_model' => $mainSlot->getModel(),
			'page' => [
				'id' => $titleObject->getArticleID(),
				'key' => $this->titleFormatter->getPrefixedDBkey( $titleObject ),
				'title' => $this->titleFormatter->getPrefixedText( $titleObject ),
			],
			'license' => [
				'url' => $this->config->get( 'RightsUrl' ),
				'title' => $this->config->get( 'RightsText' )
			],
		];

		$revUser = $revision->getUser( RevisionRecord::FOR_THIS_USER, $this->authority );
		if ( $revUser ) {
			$metadata['user'] = [
				'id' => $revUser->isRegistered() ? $revUser->getId() : null,
				'name' => $revUser->getName()
			];
		} else {
			$metadata['user'] = null;
		}

		$comment = $revision->getComment( RevisionRecord::FOR_THIS_USER, $this->authority );
		$metadata['comment'] = $comment ? $comment->text : null;

		$parent = $this->revisionLookup->getPreviousRevision( $revision );
		if ( $parent ) {
			$metadata['delta'] = $revision->getSize() - $parent->getSize();
		} else {
			$metadata['delta'] = null;
		}

		// FIXME: test fall fields
		return $metadata;
	}

	/**
	 * @return array[]
	 */
	public function getParamSettings(): array {
		return [
			'id' => [
				Handler::PARAM_SOURCE => 'path',
				ParamValidator::PARAM_TYPE => 'integer',
				ParamValidator::PARAM_REQUIRED => true,
			],
		];
	}

	/**
	 * @throws LocalizedHttpException if the content is not accessible
	 */
	public function checkAccess() {
		$revId = $this->getRevisionId() ?? '';

		if ( !$this->hasContent() ) {
			throw new LocalizedHttpException(
				MessageValue::new( 'rest-nonexistent-revision' )->plaintextParams( $revId ),
				404
			);
		}

		if ( !$this->authority->authorizeRead( 'read', $this->getTitle() ) ) {
			throw new LocalizedHttpException(
				MessageValue::new( 'rest-permission-denied-revision' )->plaintextParams( $revId ),
				403
			);
		}
	}

}
