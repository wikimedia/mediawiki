<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\Exception;

use MediaWiki\Message\Message;
use Wikimedia\Message\MessageSpecifier;

/**
 * An error page which can definitely be safely rendered using the OutputPage.
 *
 * @newable
 * @stable to extend
 *
 * @since 1.7
 * @ingroup Exception
 */
class ErrorPageError extends MWException implements ILocalizedException {
	public const SEND_OUTPUT = 0;
	public const STAGE_OUTPUT = 1;

	/** @var string|MessageSpecifier */
	public $title;
	/** @var string|MessageSpecifier */
	public $msg;
	public array $params;

	/**
	 * Note: these arguments are keys into wfMessage(), not text!
	 *
	 * @stable to call
	 *
	 * @param string|MessageSpecifier $title Message key (string) for page title, or a MessageSpecifier
	 * @param string|MessageSpecifier $msg Message key (string) for error text, or a MessageSpecifier
	 * @param array $params Array with parameters to wfMessage()
	 */
	public function __construct( $title, $msg, $params = [] ) {
		$this->title = $title;
		$this->msg = $msg;
		$this->params = $params;

		// T46111: Messages in the log files should be in English and not
		// customized by the local wiki. So get the default English version for
		// passing to the parent constructor. Our overridden report() below
		// makes sure that the page shown to the user is not forced to English.
		$enMsg = $this->getMessageObject();
		$enMsg->inLanguage( 'en' )->useDatabase( false );
		parent::__construct( $enMsg->text() );
	}

	/**
	 * Return a Message object for this exception
	 * @since 1.29
	 * @return Message
	 */
	public function getMessageObject() {
		if ( $this->msg instanceof Message ) {
			return clone $this->msg;
		}
		return wfMessage( $this->msg, $this->params );
	}

	/**
	 * @stable to override
	 * @param int $action
	 *
	 * @throws FatalError
	 * @throws MWException
	 */
	public function report( $action = self::SEND_OUTPUT ) {
		if ( self::isCommandLine() || defined( 'MW_API' ) ) {
			MWExceptionRenderer::output( $this, MWExceptionRenderer::AS_PRETTY );
		} else {
			global $wgOut;
			$wgOut->showErrorPage( $this->title, $this->msg, $this->params );
			// Allow skipping of the final output step, so that web-based page views
			// from MediaWiki.php, can inspect the staged OutputPage state, and perform
			// graceful shutdown via prepareForOutput() first, just like for regular
			// output when there isn't an error page.
			if ( $action === self::SEND_OUTPUT ) {
				$wgOut->output();
			}
		}
	}
}

/** @deprecated class alias since 1.44 */
class_alias( ErrorPageError::class, 'ErrorPageError' );
