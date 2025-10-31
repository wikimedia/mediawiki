<?php
/**
 * Copyright © 2015 Geoffrey Mon <geofbot@gmail.com>
 *
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\Api;

use MediaWiki\Page\MergeHistoryFactory;
use MediaWiki\Page\PageIdentity;
use MediaWiki\Status\Status;
use MediaWiki\Title\Title;
use Wikimedia\ParamValidator\ParamValidator;

/**
 * API Module to merge page histories
 * @ingroup API
 */
class ApiMergeHistory extends ApiBase {

	private MergeHistoryFactory $mergeHistoryFactory;

	public function __construct(
		ApiMain $mainModule,
		string $moduleName,
		MergeHistoryFactory $mergeHistoryFactory
	) {
		parent::__construct( $mainModule, $moduleName );
		$this->mergeHistoryFactory = $mergeHistoryFactory;
	}

	public function execute() {
		$this->useTransactionalTimeLimit();

		$params = $this->extractRequestParams();

		$this->requireOnlyOneParameter( $params, 'from', 'fromid' );
		$this->requireOnlyOneParameter( $params, 'to', 'toid' );

		// Get page objects (nonexistent pages get caught in MergeHistory::isValidMerge())
		if ( isset( $params['from'] ) ) {
			$fromTitle = Title::newFromText( $params['from'] );
			if ( !$fromTitle || $fromTitle->isExternal() ) {
				$this->dieWithError( [ 'apierror-invalidtitle', wfEscapeWikiText( $params['from'] ) ] );
			}
		} elseif ( isset( $params['fromid'] ) ) {
			$fromTitle = Title::newFromID( $params['fromid'] );
			if ( !$fromTitle ) {
				$this->dieWithError( [ 'apierror-nosuchpageid', $params['fromid'] ] );
			}
		}

		if ( isset( $params['to'] ) ) {
			$toTitle = Title::newFromText( $params['to'] );
			if ( !$toTitle || $toTitle->isExternal() ) {
				$this->dieWithError( [ 'apierror-invalidtitle', wfEscapeWikiText( $params['to'] ) ] );
			}
		} elseif ( isset( $params['toid'] ) ) {
			$toTitle = Title::newFromID( $params['toid'] );
			if ( !$toTitle ) {
				$this->dieWithError( [ 'apierror-nosuchpageid', $params['toid'] ] );
			}
		}

		$reason = $params['reason'];
		$timestamp = $params['timestamp'] ?? '';
		$startTimestamp = $params['starttimestamp'] ?? '';

		// Merge!
		// @phan-suppress-next-line PhanTypeMismatchArgumentNullable,PhanPossiblyUndeclaredVariable T240141
		$status = $this->merge( $fromTitle, $toTitle, $timestamp, $reason, $startTimestamp );
		if ( !$status->isOK() ) {
			$this->dieStatus( $status );
		}

		$r = [
			// @phan-suppress-next-line PhanPossiblyUndeclaredVariable T240141
			'from' => $fromTitle->getPrefixedText(),
			// @phan-suppress-next-line PhanPossiblyUndeclaredVariable T240141
			'to' => $toTitle->getPrefixedText(),
			'timestamp' => $params['timestamp'],
			'reason' => $params['reason']
		];
		$result = $this->getResult();

		$result->addValue( null, $this->getModuleName(), $r );
	}

	/**
	 * @param PageIdentity $from
	 * @param PageIdentity $to
	 * @param string $timestamp
	 * @param string $reason
	 * @param string $startTimestamp
	 * @return Status
	 */
	protected function merge( PageIdentity $from, PageIdentity $to, $timestamp, $reason, $startTimestamp ) {
		$mh = $this->mergeHistoryFactory->newMergeHistory( $from, $to, $timestamp, $startTimestamp );

		return $mh->merge( $this->getAuthority(), $reason );
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
			'from' => null,
			'fromid' => [
				ParamValidator::PARAM_TYPE => 'integer'
			],
			'to' => null,
			'toid' => [
				ParamValidator::PARAM_TYPE => 'integer'
			],
			// This can either be a timestamp or a timestamp-with-ID pair; don't reject the latter in validation
			'timestamp' => null,
			'reason' => '',
			'starttimestamp' => null
		];
	}

	/** @inheritDoc */
	public function needsToken() {
		return 'csrf';
	}

	/** @inheritDoc */
	protected function getExamplesMessages() {
		return [
			'action=mergehistory&from=Oldpage&to=Newpage&token=123ABC&' .
			'reason=Reason'
			=> 'apihelp-mergehistory-example-merge',
			'action=mergehistory&from=Oldpage&to=Newpage&token=123ABC&' .
			'reason=Reason&timestamp=2015-12-31T04%3A37%3A41Z' // TODO
			=> 'apihelp-mergehistory-example-merge-timestamp',
		];
	}

	/** @inheritDoc */
	public function getHelpUrls() {
		return 'https://www.mediawiki.org/wiki/Special:MyLanguage/API:Mergehistory';
	}
}

/** @deprecated class alias since 1.43 */
class_alias( ApiMergeHistory::class, 'ApiMergeHistory' );
