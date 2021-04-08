<?php

namespace MediaWiki\Rest\Handler;

use MediaWiki\ParamValidator\TypeDef\UserDef;
use MediaWiki\Rest\LocalizedHttpException;
use MediaWiki\Rest\ResponseInterface;
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
		$target = $this->getTargetUser();
		$tag = $this->getValidatedParams()['tag'];
		$count = $this->contributionsLookup->getContributionCount( $target, $this->getAuthority(), $tag );
		$response = [ 'count' => $count ];
		return $response;
	}

	public function getParamSettings() {
		$settings = [
			'tag' => [
				self::PARAM_SOURCE => 'query',
				ParamValidator::PARAM_TYPE => 'string',
				ParamValidator::PARAM_REQUIRED => false,
				ParamValidator::PARAM_DEFAULT => null,
			]
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
