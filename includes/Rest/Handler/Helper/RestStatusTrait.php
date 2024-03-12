<?php

namespace MediaWiki\Rest\Handler\Helper;

use MediaWiki\Logger\LoggerFactory;
use MediaWiki\Message\Converter;
use MediaWiki\Message\Message;
use MediaWiki\Rest\LocalizedHttpException;
use MessageSpecifier;
use StatusValue;
use Wikimedia\Message\MessageValue;

/**
 * Trait for handling Status objects in REST handlers.
 */
trait RestStatusTrait {

	private ?Converter $messageValueConverter = null;

	private function getMessageValueConverter(): Converter {
		if ( !$this->messageValueConverter ) {
			$this->messageValueConverter = new Converter();
		}
		return $this->messageValueConverter;
	}

	/**
	 * Extract the error messages from a Status, as MessageValue objects.
	 * @param StatusValue $status
	 * @return MessageValue[]
	 */
	private function convertStatusToMessageValues( StatusValue $status ): array {
		$conv = $this->getMessageValueConverter();
		return array_map( static function ( $error ) use ( $conv ) {
			// TODO: It should be possible to do this without going through a Message object,
			// but the internal format of parameters is different in MessageValue (T358779)
			return $conv->convertMessage(
				Message::newFromSpecifier( [ $error['message'], ...$error['params'], ] )
			);
		}, $status->getErrors() );
	}

	/**
	 * @param StatusValue $status
	 * @param string|MessageValue $msg
	 * @param int $code
	 * @param array $data
	 *
	 * @return never
	 * @throws LocalizedHttpException
	 */
	private function throwExceptionForStatus(
		StatusValue $status,
		$msg,
		int $code,
		array $data = []
	) {
		$data += [ 'error-keys' => $this->getStatusErrorKeys( $status ) ];

		if ( is_string( $msg ) ) {
			$msg = MessageValue::new( $msg )
				->semicolonListParams(
					$this->convertStatusToMessageValues( $status )
				);
		}

		throw new LocalizedHttpException( $msg, $code, $data );
	}

	private function getStatusErrorKeys( StatusValue $status ) {
		$keys = [];

		foreach ( $status->getErrors() as [ 'message' => $msg ] ) {
			if ( is_string( $msg ) ) {
				$keys[] = $msg;
			} elseif ( is_array( $msg ) ) {
				$keys[] = $msg[0];
			} elseif ( $msg instanceof MessageSpecifier ) {
				$keys[] = $msg->getKey();
			}
		}

		return array_unique( $keys );
	}

	private function logStatusError( StatusValue $status, string $message, string $channel ) {
		LoggerFactory::getInstance( $channel )->error(
			$message,
			[ 'reason' => $this->getStatusErrorKeys( $status ) ]
		);
	}

}
