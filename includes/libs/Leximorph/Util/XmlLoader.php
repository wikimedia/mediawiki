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

namespace Wikimedia\Leximorph\Util;

use DOMDocument;
use Psr\Log\LoggerInterface;

/**
 * XmlLoader
 *
 * Provides methods for loading and parsing XML files into DOMDocument.
 *
 * @since     1.45
 * @author    Doğu Abaris (abaris@null.net)
 * @license   https://www.gnu.org/copyleft/gpl.html GPL-2.0-or-later
 */
class XmlLoader {

	/**
	 * Logger instance used for logging errors.
	 */
	private LoggerInterface $logger;

	/**
	 * Initializes the XmlLoader with a logger.
	 *
	 * @param LoggerInterface $logger
	 *
	 * @since 1.45
	 */
	public function __construct( LoggerInterface $logger ) {
		$this->logger = $logger;
	}

	/**
	 * Loads an XML file into a DOMDocument instance.
	 *
	 * @param string $filePath The path to the XML file.
	 * @param string $context A short string to include in log messages.
	 * @param bool $allowMissing If true, suppress error logging for missing/unreadable files.
	 *
	 * @since 1.45
	 * @return ?DOMDocument Null on failure.
	 */
	public function load( string $filePath, string $context = '', bool $allowMissing = false ): ?DOMDocument {
		// Use file_get_contents instead of DOMDocument::load (T58439)
		// phpcs:ignore Generic.PHP.NoSilencedErrors.Discouraged
		$xml = @file_get_contents( $filePath );
		if ( !$xml ) {
			if ( !$allowMissing ) {
				$this->logger->error(
					'Unable to read XML file {filePath} for {context}',
					[
						'filePath' => $filePath,
						'context' => $context,
					]
				);
			}

			return null;
		}

		$doc = new DOMDocument();
		libxml_use_internal_errors( true );
		if ( !$doc->loadXML( $xml ) ) {
			$this->logger->error(
				'Invalid XML format in {filePath} for {context}',
				[
					'filePath' => $filePath,
					'context' => $context,
				]
			);
			libxml_clear_errors();

			return null;
		}
		libxml_clear_errors();

		return $doc;
	}
}
