<?php
/**
 * Base class for the output of file transformation methods.
 *
 * @license GPL-2.0-or-later
 * @file
 * @ingroup Media
 */

use MediaWiki\Message\Message;
use Wikimedia\Message\MessageParam;
use Wikimedia\Message\MessageSpecifier;

/**
 * Basic media transform error class
 *
 * @newable
 * @stable to extend
 * @ingroup Media
 */
class MediaTransformError extends MediaTransformOutput {
	/** @var Message */
	private $msg;

	/**
	 * @stable to call
	 *
	 * @param string|MessageSpecifier $msg
	 * @param int $width
	 * @param int $height
	 * @param MessageParam|MessageSpecifier|string|int|float ...$args
	 */
	public function __construct( $msg, $width, $height, ...$args ) {
		$this->msg = wfMessage( $msg )->params( $args );
		$this->width = (int)$width;
		$this->height = (int)$height;
		$this->url = false;
		$this->path = false;
	}

	/** @inheritDoc */
	public function toHtml( $options = [] ) {
		return "<div class=\"MediaTransformError\" style=\"" .
			"width: {$this->width}px; height: {$this->height}px; display:inline-block;\">" .
			$this->getHtmlMsg() .
			"</div>";
	}

	/**
	 * @return string
	 */
	public function toText() {
		return $this->msg->text();
	}

	/**
	 * @return string
	 */
	public function getHtmlMsg() {
		return $this->msg->escaped();
	}

	/**
	 * @return Message
	 */
	public function getMsg() {
		return $this->msg;
	}

	/** @inheritDoc */
	public function isError() {
		return true;
	}

	/**
	 * @stable to override
	 *
	 * @return int
	 */
	public function getHttpStatusCode() {
		return 500;
	}
}
