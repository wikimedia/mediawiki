<?php
/**
 * Copyright © 2016 Wikimedia Foundation and contributors
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along
 * with this program; if not, write to the Free Software Foundation, Inc.,
 * 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA.
 * http://www.gnu.org/copyleft/gpl.html
 *
 * @file
 */

use MediaWiki\MainConfigNames;
use MediaWiki\ParamValidator\TypeDef\UserDef;
use Wikimedia\ParamValidator\ParamValidator;

/**
 * Reset password, with AuthManager
 *
 * @ingroup API
 */
class ApiResetPassword extends ApiBase {

	/** @var PasswordReset */
	private $passwordReset;

	/**
	 * @param ApiMain $main
	 * @param string $action
	 * @param PasswordReset $passwordReset
	 */
	public function __construct(
		ApiMain $main,
		$action,
		PasswordReset $passwordReset
	) {
		parent::__construct( $main, $action );

		$this->passwordReset = $passwordReset;
	}

	/** @var bool */
	private $hasAnyRoutes = null;

	/**
	 * Determine whether any reset routes are available.
	 * @return bool
	 */
	private function hasAnyRoutes() {
		if ( $this->hasAnyRoutes === null ) {
			$resetRoutes = $this->getConfig()->get( MainConfigNames::PasswordResetRoutes );
			$this->hasAnyRoutes = !empty( $resetRoutes['username'] ) || !empty( $resetRoutes['email'] );
		}
		return $this->hasAnyRoutes;
	}

	/** @inheritDoc */
	protected function getExtendedDescription() {
		if ( !$this->hasAnyRoutes() ) {
			return 'apihelp-resetpassword-extended-description-noroutes';
		}
		return parent::getExtendedDescription();
	}

	/** @inheritDoc */
	public function execute() {
		if ( !$this->hasAnyRoutes() ) {
			$this->dieWithError( 'apihelp-resetpassword-description-noroutes', 'moduledisabled' );
		}

		$params = $this->extractRequestParams() + [
			// Make sure the keys exist even if getAllowedParams didn't define them
			'user' => null,
			'email' => null,
		];

		$status = $this->passwordReset->isAllowed( $this->getUser() );
		if ( !$status->isOK() ) {
			$this->dieStatus( Status::wrap( $status ) );
		}

		$status = $this->passwordReset->execute(
			$this->getUser(), $params['user'], $params['email']
		);
		if ( !$status->isOK() ) {
			$status->value = null;
			$this->dieStatus( Status::wrap( $status ) );
		}

		$result = $this->getResult();
		$result->addValue( [ 'resetpassword' ], 'status', 'success' );
	}

	public function isWriteMode() {
		return $this->hasAnyRoutes();
	}

	public function needsToken() {
		if ( !$this->hasAnyRoutes() ) {
			return false;
		}
		return 'csrf';
	}

	/** @inheritDoc */
	public function getAllowedParams() {
		if ( !$this->hasAnyRoutes() ) {
			return [];
		}

		$ret = [
			'user' => [
				ParamValidator::PARAM_TYPE => 'user',
				UserDef::PARAM_ALLOWED_USER_TYPES => [ 'name' ],
			],
			'email' => [
				ParamValidator::PARAM_TYPE => 'string',
			],
		];

		$resetRoutes = $this->getConfig()->get( MainConfigNames::PasswordResetRoutes );
		if ( empty( $resetRoutes['username'] ) ) {
			unset( $ret['user'] );
		}
		if ( empty( $resetRoutes['email'] ) ) {
			unset( $ret['email'] );
		}

		return $ret;
	}

	/** @inheritDoc */
	protected function getExamplesMessages() {
		$ret = [];
		$resetRoutes = $this->getConfig()->get( MainConfigNames::PasswordResetRoutes );

		if ( !empty( $resetRoutes['username'] ) ) {
			$ret['action=resetpassword&user=Example&token=123ABC'] = 'apihelp-resetpassword-example-user';
		}
		if ( !empty( $resetRoutes['email'] ) ) {
			$ret['action=resetpassword&user=user@example.com&token=123ABC'] =
				'apihelp-resetpassword-example-email';
		}

		return $ret;
	}

	/** @inheritDoc */
	public function getHelpUrls() {
		return 'https://www.mediawiki.org/wiki/Special:MyLanguage/API:Manage_authentication_data';
	}
}
