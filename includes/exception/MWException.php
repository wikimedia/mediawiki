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

namespace MediaWiki\Exception;

use Exception;
use LocalisationCache;
use MediaWiki\Debug\MWDebug;
use Wikimedia\Message\MessageParam;
use Wikimedia\Message\MessageSpecifier;

/**
 * MediaWiki exception
 *
 * @newable
 * @stable to extend
 *
 * @ingroup Exception
 * @deprecated since 1.40, use native exceptions instead (either directly, or defining subclasses when appropriate)
 */
class MWException extends Exception {

	/**
	 * Whether to log this exception in the exception debug log.
	 *
	 * @stable to override
	 *
	 * @since 1.23
	 * @return bool
	 */
	public function isLoggable() {
		return true;
	}

	/**
	 * Can the extension use the Message class/wfMessage to get i18n-ed messages?
	 *
	 * @stable to override
	 *
	 * @return bool
	 */
	public function useMessageCache() {
		foreach ( $this->getTrace() as $frame ) {
			if ( isset( $frame['class'] ) && $frame['class'] === LocalisationCache::class ) {
				return false;
			}
		}
		return true;
	}

	/**
	 * Get a message from i18n
	 *
	 * @param string $key Message name
	 * @param string $fallback Default message if the message cache can't be
	 *                  called by the exception
	 * @phpcs:ignore Generic.Files.LineLength
	 * @param MessageParam|MessageSpecifier|string|int|float|list<MessageParam|MessageSpecifier|string|int|float> ...$params
	 *   See Message::params()
	 * @return string Message with arguments replaced
	 */
	public function msg( $key, $fallback, ...$params ) {
		// NOTE: Keep logic in sync with MWExceptionRenderer::msg.
		$res = false;
		if ( $this->useMessageCache() ) {
			try {
				$res = wfMessage( $key, ...$params )->text();
			} catch ( Exception ) {
			}
		}
		if ( $res === false ) {
			// Fallback to static message text and generic sitename.
			// Avoid live config as this must work before Setup/MediaWikiServices finish.
			$res = wfMsgReplaceArgs( $fallback, $params );
			$res = strtr( $res, [
				'{{SITENAME}}' => 'MediaWiki',
			] );
		}
		return $res;
	}

	/**
	 * Output a report about the exception and takes care of formatting.
	 * It will be either HTML or plain text based on isCommandLine().
	 *
	 * @stable to override
	 */
	public function report() {
		// May be overridden by subclasses to replace the whole error page
		MWExceptionRenderer::output( $this, MWExceptionRenderer::AS_PRETTY );
	}

	/**
	 * @internal
	 */
	final public function hasOverriddenHandler(): bool {
		return MWDebug::detectDeprecatedOverride( $this, __CLASS__, 'report' );
	}

	/**
	 * Check whether we are in command line mode or not to report the exception
	 * in the correct format.
	 *
	 * @return bool
	 */
	public static function isCommandLine() {
		return MW_ENTRY_POINT === 'cli';
	}

}

/** @deprecated class alias since 1.44 */
class_alias( MWException::class, 'MWException' );
