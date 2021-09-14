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
	public $title, $msg, $params;

	/**
	 * Note: these arguments are keys into wfMessage(), not text!
	 *
	 * @stable to call
	 *
	 * @param string|Message $title Message key (string) for page title, or a Message object
	 * @param string|Message $msg Message key (string) for error text, or a Message object
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
			parent::report();
		} else {
			global $wgOut;
			$wgOut->showErrorPage( $this->title, $this->msg, $this->params );
			// Allow skipping of the final output step, so that web-based page views
			// from MediaWiki.php, can inspect the staged OutputPage state, and perform
			// graceful shutdown via doPreOutputCommit first, just like for regular
			// output when there isn't an error page.
			if ( $action === self::SEND_OUTPUT ) {
				$wgOut->output();
			}
		}
	}
}
