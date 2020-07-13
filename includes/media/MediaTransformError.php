<?php
/**
 * Base class for the output of file transformation methods.
 *
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
 * @ingroup Media
 */

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
	 * @param string $msg
	 * @param int $width
	 * @param int $height
	 * @param mixed ...$args
	 */
	public function __construct( $msg, $width, $height, ...$args ) {
		$this->msg = wfMessage( $msg )->params( $args );
		$this->width = intval( $width );
		$this->height = intval( $height );
		$this->url = false;
		$this->path = false;
	}

	public function toHtml( $options = [] ) {
		return "<div class=\"MediaTransformError\" style=\"" .
			"width: {$this->width}px; height: {$this->height}px; display:inline-block;\">" .
			$this->getHtmlMsg() .
			"</div>";
	}

	public function toText() {
		return $this->msg->text();
	}

	public function getHtmlMsg() {
		return $this->msg->escaped();
	}

	public function getMsg() {
		return $this->msg;
	}

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
