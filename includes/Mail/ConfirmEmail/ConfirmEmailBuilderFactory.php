<?php

namespace MediaWiki\Mail\ConfirmEmail;

use MediaWiki\Context\IContextSource;

class ConfirmEmailBuilderFactory {

	public function __construct() {
	}

	public function newFromContext( IContextSource $ctx ): IConfirmEmailBuilder {
		return new PlaintextConfirmEmailBuilder( $ctx );
	}
}
