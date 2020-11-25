<?php

namespace MediaWiki\Rest\Handler;

use Config;
use MediaWiki\Permissions\PermissionManager;
use MediaWiki\Rest\SimpleHandler;
use MediaWiki\Revision\RevisionLookup;
use MediaWiki\Revision\RevisionRecord;
use RequestContext;
use Title;
use TitleFactory;
use TitleFormatter;
use User;
use Wikimedia\ParamValidator\ParamValidator;

abstract class LatestPageContentHandler extends SimpleHandler {

	/** @var Config */
	protected $config;

	/** @var PermissionManager */
	protected $permissionManager;

	/** @var RevisionLookup */
	protected $revisionLookup;

	/** @var TitleFormatter */
	protected $titleFormatter;

	/** @var User */
	protected $user;

	/** @var RevisionRecord|bool */
	private $latestRevision;

	/** @var Title|bool */
	private $titleObject;

	/** @var TitleFactory */
	private $titleFactory;

	/**
	 * @param Config $config
	 * @param PermissionManager $permissionManager
	 * @param RevisionLookup $revisionLookup
	 * @param TitleFormatter $titleFormatter
	 * @param TitleFactory $titleFactory
	 */
	public function __construct(
		Config $config,
		PermissionManager $permissionManager,
		RevisionLookup $revisionLookup,
		TitleFormatter $titleFormatter,
		TitleFactory $titleFactory
	) {
		$this->config = $config;
		$this->permissionManager = $permissionManager;
		$this->revisionLookup = $revisionLookup;
		$this->titleFormatter = $titleFormatter;

		// @todo Inject this, when there is a good way to do that
		$this->user = RequestContext::getMain()->getUser();
		$this->titleFactory = $titleFactory;
	}

	/**
	 * @return Title|bool Title or false if unable to retrieve title
	 */
	protected function getTitle() {
		if ( $this->titleObject === null ) {
			$this->titleObject =
				$this->titleFactory->newFromText( $this->getValidatedParams()['title'] ) ?? false;
		}
		return $this->titleObject;
	}

	/**
	 * @return RevisionRecord|bool latest revision or false if unable to retrieve revision
	 */
	protected function getLatestRevision() {
		if ( $this->latestRevision === null ) {
			$title = $this->getTitle();
			if ( $title && $title->getArticleID() ) {
				$this->latestRevision = $this->revisionLookup->getRevisionByTitle( $title );
			} else {
				$this->latestRevision = false;
			}
		}
		return $this->latestRevision;
	}

	protected function isAccessible( $titleObject ): bool {
		return $this->permissionManager->userCan( 'read', $this->user, $titleObject );
	}

	protected function constructMetadata(
		Title $titleObject,
		RevisionRecord $revision
	): array {
		return [
			'id' => $titleObject->getArticleID(),
			'key' => $this->titleFormatter->getPrefixedDBkey( $titleObject ),
			'title' => $this->titleFormatter->getPrefixedText( $titleObject ),
			'latest' => [
				'id' => $revision->getId(),
				'timestamp' => wfTimestampOrNull( TS_ISO_8601, $revision->getTimestamp() )
			],
			'content_model' => $titleObject->getContentModel(),
			'license' => [
				'url' => $this->config->get( 'RightsUrl' ),
				'title' => $this->config->get( 'RightsText' )
			],
		];
	}

	public function needsWriteAccess(): bool {
		return false;
	}

	public function getParamSettings(): array {
		return [
			'title' => [
				self::PARAM_SOURCE => 'path',
				ParamValidator::PARAM_TYPE => 'string',
				ParamValidator::PARAM_REQUIRED => true,
			],
		];
	}
}
