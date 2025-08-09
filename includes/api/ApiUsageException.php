<?php
/**
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along
 * with this program; if not, write to the Free Software Foundation, Inc.,
 * 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA.
 * http://www.gnu.org/copyleft/gpl.html
 *
 * @file
 */

namespace MediaWiki\Api;

use InvalidArgumentException;
use MediaWiki\Exception\ILocalizedException;
use MediaWiki\Exception\MWException;
use MediaWiki\Status\Status;
use StatusValue;
use Stringable;
use Throwable;
use Wikimedia\Message\MessageSpecifier;

/**
 * Exception used to abort API execution with an error
 *
 * If possible, use ApiBase::dieWithError() instead of throwing this directly.
 *
 * @newable
 * @ingroup API
 */
class ApiUsageException extends MWException implements Stringable, ILocalizedException {

	/** @var string|null */
	protected $modulePath;
	/** @var StatusValue */
	protected $status;

	/**
	 * @stable to call
	 * @param ApiBase|null $module API module responsible for the error, if known
	 * @param StatusValue $status Status holding errors
	 * @param int $httpCode HTTP error code to use
	 * @param Throwable|null $previous Previous exception
	 */
	public function __construct(
		?ApiBase $module, StatusValue $status, $httpCode = 0, ?Throwable $previous = null
	) {
		if ( $status->isOK() ) {
			throw new InvalidArgumentException( __METHOD__ . ' requires a fatal Status' );
		}

		$this->modulePath = $module ? $module->getModulePath() : null;
		$this->status = $status;

		// Bug T46111: Messages in the log files should be in English and not
		// customized by the local wiki.
		$enMsg = clone $this->getApiMessage();
		$enMsg->inLanguage( 'en' )->useDatabase( false );
		parent::__construct( ApiErrorFormatter::stripMarkup( $enMsg->text() ), $httpCode, $previous );
	}

	/**
	 * @param ApiBase|null $module API module responsible for the error, if known
	 * @param string|array|MessageSpecifier $msg See ApiMessage::create()
	 * @param string|null $code See ApiMessage::create()
	 * @param array|null $data See ApiMessage::create()
	 * @param int $httpCode HTTP error code to use
	 * @param Throwable|null $previous Previous exception
	 */
	public static function newWithMessage(
		?ApiBase $module, $msg, $code = null, $data = null, $httpCode = 0, ?Throwable $previous = null
	): static {
		return new static(
			$module,
			StatusValue::newFatal( ApiMessage::create( $msg, $code, $data ) ),
			$httpCode,
			$previous
		);
	}

	/**
	 * @return ApiMessage
	 */
	private function getApiMessage() {
		// Return the first error message, if any; or the first warning message, if any; or a generic message
		foreach ( $this->status->getMessages( 'error' ) as $msg ) {
			// @phan-suppress-next-line PhanTypeMismatchReturnSuperType
			return ApiMessage::create( $msg );
		}
		foreach ( $this->status->getMessages( 'warning' ) as $msg ) {
			// @phan-suppress-next-line PhanTypeMismatchReturnSuperType
			return ApiMessage::create( $msg );
		}
		return new ApiMessage( 'apierror-unknownerror-nocode', 'unknownerror' );
	}

	/**
	 * Fetch the responsible module name
	 * @return string|null
	 */
	public function getModulePath() {
		return $this->modulePath;
	}

	/**
	 * Fetch the error status
	 * @return StatusValue
	 */
	public function getStatusValue() {
		return $this->status;
	}

	/**
	 * @inheritDoc
	 */
	public function getMessageObject() {
		return Status::wrap( $this->status )->getMessage();
	}

	/**
	 * @return string
	 */
	public function __toString() {
		$enMsg = clone $this->getApiMessage();
		$enMsg->inLanguage( 'en' )->useDatabase( false );
		$text = ApiErrorFormatter::stripMarkup( $enMsg->text() );

		return get_class( $this ) . ": {$enMsg->getApiCode()}: {$text} "
			. "in {$this->getFile()}:{$this->getLine()}\n"
			. "Stack trace:\n{$this->getTraceAsString()}"
			. ( $this->getPrevious() ? "\n\nNext {$this->getPrevious()}" : "" );
	}

}

/** @deprecated class alias since 1.43 */
class_alias( ApiUsageException::class, 'ApiUsageException' );
