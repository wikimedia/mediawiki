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
 * This exception will be thrown when dieUsage is called to stop module execution.
 *
 * @ingroup API
 * @deprecated since 1.29, use ApiUsageException instead
 */
class UsageException extends MWException {

	private $mCodestr;

	/**
	 * @var null|array
	 */
	private $mExtraData;

	/**
	 * @param string $message
	 * @param string $codestr
	 * @param int $code
	 * @param array|null $extradata
	 */
	public function __construct( $message, $codestr, $code = 0, $extradata = null ) {
		parent::__construct( $message, $code );
		$this->mCodestr = $codestr;
		$this->mExtraData = $extradata;

		if ( !$this instanceof ApiUsageException ) {
			wfDeprecated( __METHOD__, '1.29' );
		}

		// This should never happen, so throw an exception about it that will
		// hopefully get logged with a backtrace (T138585)
		if ( !is_string( $codestr ) || $codestr === '' ) {
			throw new InvalidArgumentException( 'Invalid $codestr, was ' .
				( $codestr === '' ? 'empty string' : gettype( $codestr ) )
			);
		}
	}

	/**
	 * @return string
	 */
	public function getCodeString() {
		wfDeprecated( __METHOD__, '1.29' );
		return $this->mCodestr;
	}

	/**
	 * @return array
	 */
	public function getMessageArray() {
		wfDeprecated( __METHOD__, '1.29' );
		$result = [
			'code' => $this->mCodestr,
			'info' => $this->getMessage()
		];
		if ( is_array( $this->mExtraData ) ) {
			$result = array_merge( $result, $this->mExtraData );
		}

		return $result;
	}

	/**
	 * @return string
	 */
	public function __toString() {
		return "{$this->getCodeString()}: {$this->getMessage()}";
	}
}

/**
 * Exception used to abort API execution with an error
 *
 * If possible, use ApiBase::dieWithError() instead of throwing this directly.
 *
 * @ingroup API
 * @note This currently extends UsageException for backwards compatibility, so
 *  all the existing code that catches UsageException won't break when stuff
 *  starts throwing ApiUsageException. Eventually UsageException will go away
 *  and this will (probably) extend MWException directly.
 */
class ApiUsageException extends UsageException implements ILocalizedException {

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
		parent::__construct(
			ApiErrorFormatter::stripMarkup( $enMsg->text() ),
			$enMsg->getApiCode(),
			$httpCode,
			$enMsg->getApiData()
		);
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
	 * @deprecated Do not use. This only exists here because UsageException is in
	 *  the inheritance chain for backwards compatibility.
	 * @inheritDoc
	 */
	public function getCodeString() {
		wfDeprecated( __METHOD__, '1.29' );
		return $this->getApiMessage()->getApiCode();
	}

	/**
	 * @deprecated Do not use. This only exists here because UsageException is in
	 *  the inheritance chain for backwards compatibility.
	 * @inheritDoc
	 */
	public function getMessageArray() {
		wfDeprecated( __METHOD__, '1.29' );
		$enMsg = clone $this->getApiMessage();
		$enMsg->inLanguage( 'en' )->useDatabase( false );

		return [
			'code' => $enMsg->getApiCode(),
			'info' => ApiErrorFormatter::stripMarkup( $enMsg->text() ),
		] + $enMsg->getApiData();
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
