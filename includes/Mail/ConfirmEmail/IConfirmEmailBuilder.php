<?php

namespace MediaWiki\Mail\ConfirmEmail;

interface IConfirmEmailBuilder {

	public function buildEmailCreated( ConfirmEmailData $data ): ConfirmEmailContent;

	public function buildEmailChanged( ConfirmEmailData $data ): ConfirmEmailContent;

	public function buildEmailSet( ConfirmEmailData $data ): ConfirmEmailContent;
}
