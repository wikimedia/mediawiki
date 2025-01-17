<?php

namespace MediaWiki\Api;

use MediaWiki\Request\WebRequest;
use MediaWiki\User\UserIdentity;
use Wikimedia\ParamValidator\ParamValidator;

/**
 * Methods needed by APIs that create a temporary user.
 *
 * This should only be added to classes that extend ApiBase.
 *
 * @ingroup API
 * @since 1.42
 */
trait ApiCreateTempUserTrait {
	/**
	 * Get any login redirect URL added by TempUserCreatedRedirectHook.
	 *
	 * @param array $params
	 * @param UserIdentity $savedTempUser
	 * @return string The redirect URL, or '' if none was added
	 */
	protected function getTempUserRedirectUrl(
		array $params,
		UserIdentity $savedTempUser
	): string {
		$returnToQuery = $params['returntoquery'];
		$returnToAnchor = $params['returntoanchor'];
		if ( str_starts_with( $returnToQuery, '?' ) ) {
			// Remove leading '?' if provided (both ways work, but this is more common elsewhere)
			$returnToQuery = substr( $returnToQuery, 1 );
		}
		if ( $returnToAnchor !== '' && !str_starts_with( $returnToAnchor, '#' ) ) {
			// Add leading '#' if missing (it's required)
			$returnToAnchor = '#' . $returnToAnchor;
		}
		$redirectUrl = '';
		$this->getHookRunner()->onTempUserCreatedRedirect(
			$this->getRequest()->getSession(),
			$savedTempUser,
			$params['returnto'] ?: '',
			$returnToQuery,
			$returnToAnchor,
			$redirectUrl
		);
		return $redirectUrl;
	}

	/**
	 * Add params needed for TempUserCreatedRedirectHook.
	 */
	protected function getCreateTempUserParams(): array {
		return [
			'returnto' => [
				ParamValidator::PARAM_TYPE => 'title',
				ApiBase::PARAM_HELP_MSG => 'apihelp-edit-param-returnto',
			],
			'returntoquery' => [
				ParamValidator::PARAM_TYPE => 'string',
				ParamValidator::PARAM_DEFAULT => '',
				ApiBase::PARAM_HELP_MSG => 'apihelp-edit-param-returntoquery',
			],
			'returntoanchor' => [
				ParamValidator::PARAM_TYPE => 'string',
				ParamValidator::PARAM_DEFAULT => '',
				ApiBase::PARAM_HELP_MSG => 'apihelp-edit-param-returntoanchor',
			],
		];
	}

	// region   Methods required from ApiBase
	/** @name   Methods required from ApiBase
	 * @{
	 */

	/**
	 * @see ApiBase::getHookRunner
	 * @return ApiHookRunner
	 */
	abstract protected function getHookRunner();

	/**
	 * @see IContextSource::getRequest
	 * @return WebRequest
	 */
	abstract public function getRequest();

	/** @} */
	// endregion -- end of methods required from ApiBase
}

/** @deprecated class alias since 1.43 */
class_alias( ApiCreateTempUserTrait::class, 'ApiCreateTempUserTrait' );
