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

/**
 * Exception used to abort API execution with an error
 *
 * If possible, use ApiBase::dieWithError() instead of throwing this directly.
 *
 * @ingroup API
 */
class ApiUsageException extends MWException implements ILocalizedException {

	protected $modulePath;
	protected $status;

	/**
	 * @param ApiBase|null $module API module responsible for the error, if known
	 * @param StatusValue $status Status holding errors
	 * @param int $httpCode HTTP error code to use
	 */
	public function __construct(
		ApiBase $module = null, StatusValue $status, $httpCode = 0
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
		parent::__construct( ApiErrorFormatter::stripMarkup( $enMsg->text() ), $httpCode );
	}

	/**
	 * @param ApiBase|null $module API module responsible for the error, if known
	 * @param string|array|Message $msg See ApiMessage::create()
	 * @param string|null $code See ApiMessage::create()
	 * @param array|null $data See ApiMessage::create()
	 * @param int $httpCode HTTP error code to use
	 * @return static
	 */
	public static function newWithMessage(
		ApiBase $module = null, $msg, $code = null, $data = null, $httpCode = 0
	) {
		return new static(
			$module,
			StatusValue::newFatal( ApiMessage::create( $msg, $code, $data ) ),
			$httpCode
		);
	}

	/**
	 * @return ApiMessage
	 */
	private function getApiMessage() {
		$errors = $this->status->getErrorsByType( 'error' );
		if ( !$errors ) {
			$errors = $this->status->getErrors();
		}
		if ( !$errors ) {
			$msg = new ApiMessage( 'apierror-unknownerror-nocode', 'unknownerror' );
		} else {
			$msg = ApiMessage::create( $errors[0] );
		}
		return $msg;
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
			. "Stack trace:\n{$this->getTraceAsString()}";
	}

}
