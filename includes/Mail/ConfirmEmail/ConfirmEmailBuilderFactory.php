<?php

namespace MediaWiki\Mail\ConfirmEmail;

use MediaWiki\Context\IContextSource;
use MediaWiki\Utils\UrlUtils;
use Wikimedia\ObjectCache\BagOStuff;

class ConfirmEmailBuilderFactory {

	public function __construct(
		private readonly BagOStuff $cache,
		private readonly UrlUtils $urlUtils
	) {
	}

	public function newFromContext( IContextSource $ctx ): IConfirmEmailBuilder {
		return new HTMLConfirmEmailBuilder(
			$ctx,
			$this->cache,
			$this->urlUtils
		);
	}
}
