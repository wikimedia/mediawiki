<?php

declare( strict_types = 1 );

namespace MediaWiki\Tests\Unit;

use Language;
use LanguageQqx;
use Message;
use MessageLocalizer;

/**
 * A MessageLocalizer that does not make database/service calls, for use in unit tests
 *
 * To be used in a phpunit unit test like so:
 *
 * ```php
 * $output = $this->createMock( OutputPage::class );
 * $output->method( 'msg' )
 *     ->willReturnCallback( [ new FakeQqxMessageLocalizer(), 'msg' ] );
 * ```
 *
 * @since 1.40 (backported in 1.39.4)
 * @license GPL-2.0-or-later
 */
class FakeQqxMessageLocalizer implements MessageLocalizer {

	public function msg( $key, ...$params ): Message {
		$message = new class( $key ) extends Message {

			protected function fetchMessage(): string {
				return "($this->key$*)";
			}

			public function getLanguage(): Language {
				return new class() extends LanguageQqx {

					public function __construct() {
					}

					public function getCode(): string {
						return 'qqx';
					}
				};
			}

			protected function transformText( $string ): string {
				return $string;
			}
		};

		if ( $params ) {
			// we use ->params() instead of the $params constructor parameter
			// because ->params() supports some additional calling conventions,
			// which our callers might also have used
			$message->params( ...$params );
		}

		return $message;
	}
}
