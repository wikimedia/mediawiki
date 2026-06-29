<?php

namespace MediaWiki\Rest\Handler\Helper;

use MediaWiki\Logger\LoggerFactory;
use MediaWiki\Rest\LocalizedHttpException;
use StatusValue;
use Wikimedia\Message\MessageSpecifier;
use Wikimedia\Message\MessageValue;

/**
 * Trait for handling Status objects in REST handlers.
 */
trait RestStatusTrait {

	/**
	 * @param StatusValue $status
	 * @param string|MessageSpecifier $msg Prior to MediaWiki 1.47 the type was string|MessageValue
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
				->semicolonListParams( $status->getMessages() );
		}

		throw new LocalizedHttpException( $msg, $code, $data );
	}

	private function getStatusErrorKeys( StatusValue $status ): array {
		$keys = [];

		foreach ( $status->getMessages() as $msg ) {
			$keys[] = $msg->getKey();
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
