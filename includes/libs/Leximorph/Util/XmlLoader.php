<?php
/**
 * @license GPL-2.0-or-later
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
	 * Initializes the XmlLoader with a logger.
	 *
	 * @param LoggerInterface $logger
	 *
	 * @since 1.45
	 */
	public function __construct(
		private readonly LoggerInterface $logger,
	) {
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
