<?php

namespace MediaWiki\Rest\Handler;

use MediaWiki\ParamValidator\TypeDef\UserDef;
use MediaWiki\Rest\LocalizedHttpException;
use MediaWiki\Rest\ResponseInterface;
use MediaWiki\Revision\ContributionsSegment;
use MediaWiki\User\UserIdentity;
use Wikimedia\ParamValidator\ParamValidator;
use Wikimedia\ParamValidator\TypeDef\IntegerDef;

/**
 * @since 1.35
 */
class UserContributionsHandler extends AbstractContributionHandler {

	/**
	 * @return array|ResponseInterface
	 * @throws LocalizedHttpException
	 */
	public function execute() {
		$target = $this->getTargetUser();
		$limit = $this->getValidatedParams()['limit'];
		$segment = $this->getValidatedParams()['segment'];
		$tag = $this->getValidatedParams()['tag'];
		$contributionsSegment =
			$this->contributionsLookup->getContributions( $target, $limit, $this->getAuthority(), $segment, $tag );

		$contributions = $this->getContributionsList( $contributionsSegment );
		$urls = $this->constructURLs( $contributionsSegment );

		$response = $urls + [ 'contributions' => $contributions ];

		return $response;
	}

	/**
	 * Returns list of revisions
	 *
	 * @param ContributionsSegment $segment
	 *
	 * @return array[]
	 */
	private function getContributionsList( ContributionsSegment $segment ): array {
		$revisionsData = [];
		foreach ( $segment->getRevisions() as $revision ) {
			$id = $revision->getId();
			$tags = [];
			foreach ( $segment->getTagsForRevision( $id ) as $tag => $message ) {
				$tags[] = [ 'name' => $tag, 'description' => $message->parse() ];
			}
			$revisionsData[] = [
				"id" => $id,
				"comment" => $revision->getComment()->text,
				"timestamp" => wfTimestamp( TS_ISO_8601, $revision->getTimestamp() ),
				"delta" => $segment->getDeltaForRevision( $id ) ,
				"size" => $revision->getSize(),
				"tags" => $tags,
				// Contribution type will always be MediaWiki revisions,
				// until we can reliably include contributions from other sources. See T257839.
				"type" => 'revision',
				"page" => [
					"id" => $revision->getPageId(),
					"key" => $revision->getPageAsLinkTarget()->getDBkey(),
					"title" => $revision->getPageAsLinkTarget()->getText()
				]
			];
		}
		return $revisionsData;
	}

	/**
	 * @param ContributionsSegment $segment
	 *
	 * @return string[]
	 */
	private function constructURLs( ContributionsSegment $segment ): array {
		$limit = $this->getValidatedParams()['limit'];
		$tag = $this->getValidatedParams()['tag'];
		/** @var UserIdentity $user */
		$user = $this->getValidatedParams()['user'] ?? null;
		$name = $user ? $user->getName() : null;

		$urls = [];
		$query = [ 'limit' => $limit, 'tag' => $tag ];
		$pathParams = [ 'user' => $name ];

		if ( $segment->isOldest() ) {
			$urls['older'] = null;
		} else {
			$urls['older'] = $this->getRouteUrl( $pathParams, $query + [ 'segment' => $segment->getBefore() ] );
		}

		$urls['newer'] = $this->getRouteUrl( $pathParams, $query + [ 'segment' => $segment->getAfter() ] );
		$urls['latest'] = $this->getRouteUrl( $pathParams, $query );
		return $urls;
	}

	public function getParamSettings() {
		$settings = [
			'limit' => [
				self::PARAM_SOURCE => 'query',
				ParamValidator::PARAM_TYPE => 'integer',
				ParamValidator::PARAM_REQUIRED => false,
				ParamValidator::PARAM_DEFAULT => self::MAX_LIMIT,
				IntegerDef::PARAM_MIN => 1,
				IntegerDef::PARAM_MAX => self::MAX_LIMIT
			],
			'segment' => [
				self::PARAM_SOURCE => 'query',
				ParamValidator::PARAM_TYPE => 'string',
				ParamValidator::PARAM_REQUIRED => false,
				ParamValidator::PARAM_DEFAULT => ''
			],
			'tag' => [
				self::PARAM_SOURCE => 'query',
				ParamValidator::PARAM_TYPE => 'string',
				ParamValidator::PARAM_REQUIRED => false,
				ParamValidator::PARAM_DEFAULT => null
			],
		];
		if ( $this->me === false ) {
			$settings['user'] = [
				self::PARAM_SOURCE => 'path',
				ParamValidator::PARAM_REQUIRED => true,
				ParamValidator::PARAM_TYPE => 'user',
				UserDef::PARAM_RETURN_OBJECT => true,
				UserDef::PARAM_ALLOWED_USER_TYPES => [ 'name', 'ip' ],
			];
		}
		return $settings;
	}

}
