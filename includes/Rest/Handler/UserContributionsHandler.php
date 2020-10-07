<?php

namespace MediaWiki\Rest\Handler;

use MediaWiki\Rest\Handler;
use MediaWiki\Rest\LocalizedHttpException;
use MediaWiki\Rest\ResponseInterface;
use MediaWiki\Revision\ContributionsLookup;
use MediaWiki\Revision\ContributionsSegment;
use MediaWiki\User\UserFactory;
use MediaWiki\User\UserIdentity;
use MediaWiki\User\UserNameUtils;
use RequestContext;
use User;
use Wikimedia\Assert\Assert;
use Wikimedia\Message\MessageValue;
use Wikimedia\ParamValidator\ParamValidator;
use Wikimedia\ParamValidator\TypeDef\IntegerDef;

/**
 * @since 1.35
 */
class UserContributionsHandler extends Handler {

	/**
	 * @var ContributionsLookup
	 */
	private $contributionsLookup;

	/**
	 * @var UserFactory
	 */
	private $userFactory;

	/** Hard limit results to 20 revisions */
	private const MAX_LIMIT = 20;

	/**
	 * @var bool User is requesting their own contributions
	 */
	private $me;

	/**
	 * @var UserNameUtils
	 */
	private $userNameUtils;

	/**
	 * @param ContributionsLookup $contributionsLookup
	 * @param UserFactory $userFactory
	 * @param UserNameUtils $userNameUtils
	 */
	public function __construct(
		ContributionsLookup $contributionsLookup,
		UserFactory $userFactory,
		UserNameUtils $userNameUtils
	) {
		$this->contributionsLookup = $contributionsLookup;
		$this->userFactory = $userFactory;
		$this->userNameUtils = $userNameUtils;
	}

	protected function postInitSetup() {
		$this->me = $this->getConfig()['mode'] === 'me';
	}

	/**
	 * Returns the user who's contributions we are requesting.
	 * Either me (requesting user) or another user.
	 *
	 * @return UserIdentity
	 * @throws LocalizedHttpException
	 */
	private function getTargetUser() {
		if ( $this->me ) {
			$user = RequestContext::getMain()->getUser();
			if ( $user->isAnon() ) {
				throw new LocalizedHttpException(
					new MessageValue( 'rest-permission-denied-anon' ), 401
				);
			}

			return $user;
		}

		$name = $this->getValidatedParams()['name'] ?? null;
		Assert::invariant( $name !== null, '"name" parameter must be given if mode is not "me"' );

		if ( $this->userNameUtils->isIP( $name ) ) {
			// Create an anonymous user instance for the given IP
			// NOTE: We can't use a UserIdentityValue, because we might need the actor ID
			// TODO: We should create UserFactory::newFromIpAddress() for this purpose (T257464)
			$user = new User();
			$user->setName( $name );
			return $user;
		}

		$user = $this->userFactory->newFromName( $name );
		if ( !$user ) {
			throw new LocalizedHttpException(
				new MessageValue( 'rest-invalid-user', [ $name ] ), 400
			);
		}

		if ( !$user->isRegistered() ) {
			throw new LocalizedHttpException(
				new MessageValue( 'rest-nonexistent-user', [ $user->getName() ] ), 404
			);
		}

		return $user;
	}

	/**
	 * @return array|ResponseInterface
	 * @throws LocalizedHttpException
	 */
	public function execute() {
		$performer = RequestContext::getMain()->getUser();
		$target = $this->getTargetUser();

		$limit = $this->getValidatedParams()['limit'];
		$segment = $this->getValidatedParams()['segment'];
		$tag = $this->getValidatedParams()['tag'];
		$contributionsSegment =
			$this->contributionsLookup->getContributions( $target, $limit, $performer, $segment, $tag );

		$revisions = $this->getRevisionsList( $contributionsSegment );
		$urls = $this->constructURLs( $contributionsSegment );

		$response = $urls + [ 'revisions' => $revisions ];

		return $response;
	}

	/**
	 * Returns list of revisions
	 *
	 * @param ContributionsSegment $segment
	 *
	 * @return array[]
	 */
	private function getRevisionsList( ContributionsSegment $segment ) : array {
		$revisionsData = [];
		foreach ( $segment->getRevisions() as $revision ) {
			$id = $revision->getId();
			$revisionsData[] = [
				"id" => $id,
				"comment" => $revision->getComment()->text,
				"timestamp" => wfTimestamp( TS_ISO_8601, $revision->getTimestamp() ),
				"delta" => $segment->getDeltaForRevision( $id ) ,
				"size" => $revision->getSize(),
				"tags" => $segment->getTagsForRevision( $id ),
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
	private function constructURLs( ContributionsSegment $segment ) {
		$limit = $this->getValidatedParams()['limit'];
		$tag = $this->getValidatedParams()['tag'];
		$name = $this->getValidatedParams()['name'];
		$urls = [];
		$query = [ 'limit' => $limit, 'tag' => $tag ];
		$pathParams = [ 'name' => $name ];

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
		return [
			'name' => [
				self::PARAM_SOURCE => 'path',
				ParamValidator::PARAM_TYPE => 'string',
				ParamValidator::PARAM_REQUIRED => false
			],
			'limit' => [
				self::PARAM_SOURCE => 'query',
				ParamValidator::PARAM_TYPE => 'integer',
				ParamValidator::PARAM_REQUIRED => false,
				ParamValidator::PARAM_DEFAULT => self::MAX_LIMIT,
				IntegerDef::PARAM_MIN => 1,
				IntegerDef::PARAM_MAX => self::MAX_LIMIT,
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
				ParamValidator::PARAM_DEFAULT => null,
			],
		];
	}

}
