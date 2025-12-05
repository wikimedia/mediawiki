<?php
/**
 * @license GPL-2.0-or-later
 * @file
 * @ingroup Upload
 */

namespace MediaWiki\Upload\Exception;

use MediaWiki\Message\Message;
use RuntimeException;

/**
 * @newable
 */
class UploadChunkVerificationException extends RuntimeException {
	/** @var Message */
	public $msg;

	/**
	 * @param array $res
	 *
	 * @phan-param non-empty-array $res
	 */
	public function __construct( array $res ) {
		$this->msg = wfMessage( ...$res );
		parent::__construct(
			wfMessage( ...$res )->inLanguage( 'en' )->useDatabase( false )->text()
		);
	}
}
