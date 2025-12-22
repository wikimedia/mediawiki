<?php

namespace MediaWiki\Mail\ConfirmEmail;

/**
 * A service that is able to build a user email address confirmation e-mail.
 *
 * @see ConfirmEmailBuilderFactory Class responsible for deciding what builder should be used
 */
interface IConfirmEmailBuilder {

	public function buildEmailCreated( ConfirmEmailData $data ): ConfirmEmailContent;

	public function buildEmailChanged( ConfirmEmailData $data ): ConfirmEmailContent;

	public function buildEmailSet( ConfirmEmailData $data ): ConfirmEmailContent;
}
