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
namespace MediaWiki\Rest\Handler\Helper;

use MediaWiki\Rest\LocalizedHttpException;
use MediaWiki\Rest\ResponseInterface;
use ParserOutput;
use Wikimedia\Bcp47Code\Bcp47Code;
use Wikimedia\Parsoid\Core\ClientError;

/**
 * @since 1.40
 * @unstable
 */
interface HtmlOutputHelper {

	/**
	 * Fetch the HTML for rendering of a given page. If the rendering is
	 * available in parsoid parser cache, return that. Otherwise, perform
	 * a parse and return the result while caching it in the parser cache.
	 *
	 * NOTE: Caching can be explicitly disabled or a force parse action
	 *    can be issued. Stashing and rate limiting on stashing also applies
	 *    here if specified.
	 *
	 * @return ParserOutput a tuple with html and content-type
	 * @throws LocalizedHttpException
	 * @throws ClientError
	 */
	public function getHtml(): ParserOutput;

	/**
	 * Returns an ETag uniquely identifying the HTML output.
	 *
	 * @see Handler::getETag()
	 *
	 * @param string $suffix A suffix to attach to the etag.
	 *
	 * @return string|null We return null when there is no etag.
	 */
	public function getETag( string $suffix = '' ): ?string;

	/**
	 * Returns the time at which the HTML was rendered.
	 *
	 * @see Handler::getLastModified()
	 *
	 * @return string|null
	 */
	public function getLastModified(): ?string;

	/**
	 * Gets the request parameters of this request.
	 *
	 * @see Handler::getParamSettings()
	 *
	 * @return array
	 */
	public function getParamSettings(): array;

	/**
	 * Set the language to be used for variant conversion.
	 *
	 * @param Bcp47Code|string $targetLanguage
	 * @param Bcp47Code|string|null $sourceLanguage
	 */
	public function setVariantConversionLanguage(
		$targetLanguage,
		$sourceLanguage = null
	): void;

	/**
	 * Set the HTTP headers based on the response generated
	 *
	 * @param ResponseInterface $response
	 * @param bool $forHtml Whether the response will be HTML (rather than JSON)
	 *
	 * @return void
	 */
	public function putHeaders( ResponseInterface $response, bool $forHtml = true ): void;

}
