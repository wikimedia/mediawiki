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

namespace MediaWiki\Logger\Monolog;

use Monolog\Formatter\LogstashFormatter as MonologLogstashFormatter;
use RuntimeException;

/**
 * Wrap the default Monolog formatter to prevent encoding errors from leaking
 * out.
 *
 * The upstream Formatter can fail with a RuntimError when encoding the log
 * event as a string when the event contains non-UTF-8 encoded strings. This
 * Formatter catches those errors and then attempts to recover by cleaning the
 * string encoding using either iconv() or htmlentities(). Iconv will be
 * preferred but only if mb_detect_encoding() is available.
 *
 * @since 1.27
 * @author Bryan Davis <bd808@wikimedia.org>
 * @copyright © 2015 Bryan Davis and Wikimedia Foundation.
 * @see https://phabricator.wikimedia.org/T118057
 */
class LogstashFormatter extends MonologLogstashFormatter {

	/**
	 * @var bool
	 */
	protected $useIconv;

	/**
	 * @param bool $useIconv
	 */
	public function useIconv( $useIconv ) {
		$this->useIconv = (bool)$useIconv;
	}

	/**
	 * {@inheritdoc}
	 */
	public function format( array $record ) {
		try {
			return parent::format( $record );
		} catch ( RuntimeException $e ) {
			return $this->handleFailedFormat( $record, $e );
		}
	}

	/**
	 * Handle a failure of our parent Formatter.
	 *
	 * We will try to clean up the log record and run it through
	 * parent::format again.
	 *
	 * @param array $record Record that failed
	 * @param RuntimeException $failure Cause of failure
	 * @return mixed The formatted record
	 */
	protected function handleFailedFormat(
		array $record,
		RuntimeException $failure
	) {
		if ( $this->useIconv === null ) {
			$this->useIconv = function_exists( 'mb_detect_encoding' );
		}

		$cleaner = $this->useIconv ? 'iconvCallback' : 'htmlentitiesCallback';
		array_walk_recursive( $record, array( $this, $cleaner ) );
		return parent::format( $record );
	}

	/**
	 * Clean strings using iconv's 'UTF-8//IGNORE' encoding.
	 *
	 * Valid UTF-8 input will be left unmodified, but strings containing
	 * non-UTF-8 codepoints will be reencoded as UtF-8 with an assumed
	 * original encoding of ISO-8859-1. This may make funky output if the real
	 * encoding is something else, but it will end up with clean UTF-8 output
	 * and not waste a lot of time checking a complex and fragile detection
	 * chain.
	 *
	 * @param mixed &$in Input
	 * @private
	 */
	public function iconvCallback( &$in ) {
		if ( is_string( $in ) &&
			mb_detect_encoding( $in, 'UTF-8', true ) === false
		) {
			\MediaWiki\suppressWarnings();
			$in = iconv( 'ISO-8859-1', 'UTF-8//IGNORE', $in );
			\MediaWiki\restoreWarnings();
		}
	}

	/**
	 * Clean strings using htmlentities.
	 *
	 * Codepoints that are not valid UTF-8 in input strings will be dropped.
	 *
	 * @param mixed &$in Input
	 * @private
	 */
	public function htmlentitiesCallback( &$in ) {
		if ( is_string( $in ) ) {
			$in = htmlentities( $in, ENT_NOQUOTES | ENT_IGNORE, 'UTF-8', false );
		}
	}
}
