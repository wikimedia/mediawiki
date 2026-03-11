<?php

namespace MediaWiki\Mail\ConfirmEmail;

use MediaWiki\Config\ServiceOptions;
use MediaWiki\MainConfigNames;
use MediaWiki\Title\Title;
use MediaWiki\User\User;

/**
 * Decides whether to show the email confirmation banner to a given user.
 */
class EmailConfirmationBannerHandler {

	public const CONSTRUCTOR_OPTIONS = [
		MainConfigNames::EmailAuthentication,
		MainConfigNames::EmailConfirmationBanner,
	];

	public function __construct( private readonly ServiceOptions $options ) {
		$options->assertRequiredOptions( self::CONSTRUCTOR_OPTIONS );
	}

	/**
	 * Returns true if the email confirmation banner should be shown for
	 * the given user on the given page.
	 *
	 * @param User $user
	 * @param Title $title Current page title
	 * @return bool
	 */
	public function shouldShowBanner( User $user, Title $title ): bool {
		return $this->options->get( MainConfigNames::EmailConfirmationBanner )
			&& $this->options->get( MainConfigNames::EmailAuthentication )
			&& $user->isNamed()
			&& $user->getEmail() !== ''
			&& !$user->isEmailConfirmed()
			&& !$title->isSpecial( 'Confirmemail' );
	}
}
