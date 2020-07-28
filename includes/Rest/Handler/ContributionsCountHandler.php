<?php

namespace MediaWiki\Rest\Handler;

use MediaWiki\Rest\LocalizedHttpException;
use MediaWiki\Rest\ResponseInterface;
use RequestContext;
use Wikimedia\ParamValidator\ParamValidator;

/**
 * @since 1.35
 */
class ContributionsCountHandler extends AbstractContributionHandler {

	/**
	 * @return array|ResponseInterface
	 * @throws LocalizedHttpException
	 */
	public function execute() {
		$performer = RequestContext::getMain()->getUser();
		$target = $this->getTargetUser();
		$tag = $this->getValidatedParams()['tag'];
		$count = $this->contributionsLookup->getContributionCount( $target, $performer, $tag );
		$response = [ 'count' => $count ];
		return $response;
	}

	public function getParamSettings() {
		return [
			'tag' => [
				self::PARAM_SOURCE => 'query',
				ParamValidator::PARAM_TYPE => 'string',
				ParamValidator::PARAM_REQUIRED => false,
				ParamValidator::PARAM_DEFAULT => null,
			],
			'name' => [
				self::PARAM_SOURCE => 'path',
				ParamValidator::PARAM_TYPE => 'string',
				ParamValidator::PARAM_REQUIRED => $this->me === false
			],
		];
	}

}
