<?php

declare( strict_types = 1 );

namespace MediaWiki\Tests\Unit;

use LanguageQqx;
use MediaWiki\Language\Language;
use MediaWiki\Message\Message;
use MessageLocalizer;
use Wikimedia\Message\MessageSpecifier;

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

	/** @inheritDoc */
	public function msg( $key, ...$params ): Message {
		$message = new class( $key ) extends Message {

			protected function fetchMessage(): string {
				return "($this->key$*)";
			}

			/** @inheritDoc */
			public static function newFromSpecifier( $value ) {
				if ( $value instanceof MessageSpecifier ) {
					return new self( $value );
				}
				return parent::newFromSpecifier( $value );
			}

			public function getLanguage(): Language {
				return new class() extends LanguageQqx {

					public function __construct() {
					}

					/** @inheritDoc */
					public function getCode(): string {
						return 'qqx';
					}

					// Support using Message::numParam()
					public function formatNum( $number ): string {
						return (string)$number;
					}
				};
			}

			public function getLanguageCode(): string {
				return 'qqx';
			}

			public function inContentLanguage(): Message {
				return $this;
			}

			/** @inheritDoc */
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
