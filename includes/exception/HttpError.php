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

use MediaWiki\Logger\LoggerFactory;

/**
 * Show an error that looks like an HTTP server error.
 * Replacement for wfHttpError().
 *
 * @since 1.19
 * @ingroup Exception
 */
class HttpError extends MWException {
	private $httpCode, $header, $content;

	/**
	 * Constructor
	 *
	 * @param int $httpCode HTTP status code to send to the client
	 * @param string|Message $content Content of the message
	 * @param string|Message $header Content of the header (\<title\> and \<h1\>)
	 */
	public function __construct( $httpCode, $content, $header = null ) {
		parent::__construct( $content );
		$this->httpCode = (int)$httpCode;
		$this->header = $header;
		$this->content = $content;
	}

	/**
	 * We don't want the default exception logging as we got our own logging set
	 * up in self::report.
	 *
	 * @see MWException::isLoggable
	 *
	 * @since 1.24
	 * @return bool
	 */
	public function isLoggable() {
		return false;
	}

	/**
	 * Returns the HTTP status code supplied to the constructor.
	 *
	 * @return int
	 */
	public function getStatusCode() {
		return $this->httpCode;
	}

	/**
	 * Report and log the HTTP error.
	 * Sends the appropriate HTTP status code and outputs an
	 * HTML page with an error message.
	 */
	public function report() {
		$this->doLog();

		$httpMessage = HttpStatus::getMessage( $this->httpCode );

		header( "Status: {$this->httpCode} {$httpMessage}", true, $this->httpCode );
		header( 'Content-type: text/html; charset=utf-8' );

		print $this->getHTML();
	}

	private function doLog() {
		$logger = LoggerFactory::getInstance( 'HttpError' );
		$content = $this->content;

		if ( $content instanceof Message ) {
			$content = $content->text();
		}

		$context = array(
			'file' => $this->getFile(),
			'line' => $this->getLine(),
			'http_code' => $this->httpCode,
		);

		$logMsg = "$content ({http_code}) from {file}:{line}";

		if ( $this->getStatusCode() < 500 ) {
			$logger->info( $logMsg, $context );
		} else {
			$logger->error( $logMsg, $context );
		}
	}

	/**
	 * Returns HTML for reporting the HTTP error.
	 * This will be a minimal but complete HTML document.
	 *
	 * @return string HTML
	 */
	public function getHTML() {
		if ( $this->header === null ) {
			$header = HttpStatus::getMessage( $this->httpCode );
		} elseif ( $this->header instanceof Message ) {
			$header = $this->header->escaped();
		} else {
			$header = htmlspecialchars( $this->header );
		}

		if ( $this->content instanceof Message ) {
			$content = $this->content->escaped();
		} else {
			$content = htmlspecialchars( $this->content );
		}

		return "<!DOCTYPE html>\n" .
		"<html><head><title>$header</title></head>\n" .
		"<body><h1>$header</h1><p>$content</p></body></html>\n";
	}
}
