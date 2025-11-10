<?php

namespace MediaWiki\Mail\ConfirmEmail;

use MediaWiki\Config\ServiceOptions;
use MediaWiki\Context\IContextSource;
use MediaWiki\MainConfigNames;
use MediaWiki\Utils\UrlUtils;
use Wikimedia\ObjectCache\BagOStuff;

class ConfirmEmailBuilderFactory {

	/**
	 * @internal only for use in ServiceWiring and in tests
	 */
	public const CONSTRUCTOR_OPTIONS = [
		MainConfigNames::UserEmailConfirmationUseHTML,
	];

	public function __construct(
		private readonly ServiceOptions $options,
		private readonly BagOStuff $cache,
		private readonly UrlUtils $urlUtils
	) {
		$this->options->assertRequiredOptions( self::CONSTRUCTOR_OPTIONS );
	}

	public function newFromContext( IContextSource $ctx ): IConfirmEmailBuilder {
		if ( $this->options->get( MainConfigNames::UserEmailConfirmationUseHTML ) ) {
			return new HTMLConfirmEmailBuilder(
				$ctx,
				$this->cache,
				$this->urlUtils
			);
		} else {
			return new PlaintextConfirmEmailBuilder( $ctx );
		}
	}
}
