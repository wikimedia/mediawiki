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
 * @author Aaron Schulz
 */

use MediaWiki\Exception\Renderer;
use MediaWiki\MediaWikiServices;

/**
 * B/C wrapper for Renderer
 * @deprecated since 1.30, use Renderer instead.
 * @since 1.28
 */
class MWExceptionRenderer {
	const AS_RAW = 1; // show as text
	const AS_PRETTY = 2; // show as HTML

	/**
	 * @param Exception|Throwable $e Original exception
	 * @param integer $mode MWExceptionExposer::AS_* constant
	 * @param Exception|Throwable|null $eNew New exception from attempting to show the first
	 * @deprecated since 1.30, use Renderer instead.
	 */
	public static function output( $e, $mode, $eNew = null ) {
		self::getRenderer()->output( $e, $mode, $eNew );
	}

	/**
	 * If $wgShowExceptionDetails is true, return a HTML message with a
	 * backtrace to the error, otherwise show a message to ask to set it to true
	 * to show that information.
	 *
	 * @param Exception|Throwable $e
	 * @return string Html to output
	 * @deprecated since 1.30, use Renderer instead.
	 */
	public static function getHTML( $e ) {
		return self::getRenderer()->getHTML( $e );
	}

	/**
	 * @return Renderer
	 */
	private static function getRenderer() {
		return MediaWikiServices::getInstance()->getExceptionRenderer();
	}
}
