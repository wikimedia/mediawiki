<?php
/**
 * API for MediaWiki 1.14+
 *
 * Copyright © 2008 Soxred93 soxred93@gmail.com,
 *
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\Api;

use MediaWiki\ChangeTags\ChangeTags;
use MediaWiki\RecentChanges\PatrolManager;
use MediaWiki\RecentChanges\RecentChangeLookup;
use MediaWiki\Revision\RevisionStore;
use Wikimedia\ParamValidator\ParamValidator;

/**
 * Allows user to patrol pages
 * @ingroup API
 */
class ApiPatrol extends ApiBase {
	private RevisionStore $revisionStore;
	private PatrolManager $patrolManager;
	private RecentChangeLookup $recentChangeLookup;

	public function __construct(
		ApiMain $main,
		string $action,
		RevisionStore $revisionStore,
		PatrolManager $patrolManager,
		RecentChangeLookup $recentChangeLookup
	) {
		parent::__construct( $main, $action );

		$this->revisionStore = $revisionStore;
		$this->patrolManager = $patrolManager;
		$this->recentChangeLookup = $recentChangeLookup;
	}

	/**
	 * Patrols the article or provides the reason the patrol failed.
	 */
	public function execute() {
		$params = $this->extractRequestParams();
		$this->requireOnlyOneParameter( $params, 'rcid', 'revid' );

		if ( isset( $params['rcid'] ) ) {
			$rc = $this->recentChangeLookup->getRecentChangeById( $params['rcid'] );
			if ( !$rc ) {
				$this->dieWithError( [ 'apierror-nosuchrcid', $params['rcid'] ] );
			}
		} else {
			$rev = $this->revisionStore->getRevisionById( $params['revid'] );
			if ( !$rev ) {
				$this->dieWithError( [ 'apierror-nosuchrevid', $params['revid'] ] );
			}
			$rc = $this->revisionStore->getRecentChange( $rev );
			if ( !$rc ) {
				$this->dieWithError( [ 'apierror-notpatrollable', $params['revid'] ] );
			}
		}

		$user = $this->getUser();
		$tags = $params['tags'];

		// Check if user can add tags
		if ( $tags !== null ) {
			$ableToTag = ChangeTags::canAddTagsAccompanyingChange( $tags, $this->getAuthority() );
			if ( !$ableToTag->isOK() ) {
				$this->dieStatus( $ableToTag );
			}
		}

		$status = $this->patrolManager->markPatrolled( $rc, $user, $tags );

		if ( !$status->isGood() ) {
			$this->dieStatus( $status );
		}

		$result = [ 'rcid' => (int)$rc->getAttribute( 'rc_id' ) ];
		ApiQueryBase::addTitleInfo( $result, $rc->getTitle() );
		$this->getResult()->addValue( null, $this->getModuleName(), $result );
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
	public function getAllowedParams() {
		return [
			'rcid' => [
				ParamValidator::PARAM_TYPE => 'integer'
			],
			'revid' => [
				ParamValidator::PARAM_TYPE => 'integer'
			],
			'tags' => [
				ParamValidator::PARAM_TYPE => 'tags',
				ParamValidator::PARAM_ISMULTI => true,
			],
		];
	}

	/** @inheritDoc */
	public function needsToken() {
		return 'patrol';
	}

	/** @inheritDoc */
	protected function getExamplesMessages() {
		return [
			'action=patrol&token=123ABC&rcid=230672766'
				=> 'apihelp-patrol-example-rcid',
			'action=patrol&token=123ABC&revid=230672766'
				=> 'apihelp-patrol-example-revid',
		];
	}

	/** @inheritDoc */
	public function getHelpUrls() {
		return 'https://www.mediawiki.org/wiki/Special:MyLanguage/API:Patrol';
	}
}

/** @deprecated class alias since 1.43 */
class_alias( ApiPatrol::class, 'ApiPatrol' );
