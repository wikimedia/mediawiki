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

use LanguageCode;
use MediaWiki\Page\PageIdentity;
use MediaWiki\Rest\ResponseInterface;
use MediaWiki\Title\Title;
use Message;
use ParserOutput;
use Wikimedia\Parsoid\Utils\ContentUtils;
use Wikimedia\Parsoid\Utils\DOMUtils;

/**
 * @since 1.40
 * @unstable
 */
class HtmlMessageOutputHelper implements HtmlOutputHelper {

	private PageIdentity $page;

	/**
	 * Initializes the helper with the given parameters like the page
	 * we're dealing with.
	 *
	 * @param PageIdentity $page
	 */
	public function init( PageIdentity $page ): void {
		$this->page = $page;
	}

	/**
	 * @return Message|null
	 */
	private function getDefaultSystemMessage(): ?Message {
		$title = Title::castFromPageIdentity( $this->page );

		return $title ? $title->getDefaultSystemMessage() : null;
	}

	/**
	 * @inheritDoc
	 */
	public function getHtml(): ParserOutput {
		$message = $this->getDefaultSystemMessage();

		// NOTE: This class should be used only for system messages,
		//      so failing hard here is fine if we're not dealing with one.
		$messageDom = DOMUtils::parseHTML( $message->parse() );
		DOMUtils::appendToHead( $messageDom, 'meta', [
			'http-equiv' => 'content-language',
			'content' => LanguageCode::bcp47( $message->getLanguage()->getCode() ),
		] );

		$messageDocHtml = ContentUtils::toXML( $messageDom );

		return new ParserOutput( $messageDocHtml );
	}

	/**
	 * @inheritDoc
	 */
	public function getETag( string $suffix = '' ): ?string {
		// XXX: We end up generating the HTML twice. Would be nice to avoid that.
		// But messages are small, and not hit a lot...
		$output = $this->getHtml();

		return '"message/' . sha1( $output->getRawText() ) . '/' . $suffix . '"';
	}

	/**
	 * @inheritDoc
	 *
	 * @note This is guaranteed to always return NULL since
	 *   proper system messages (with no DB entry) have no
	 *   revision, so they should have no last modified time.
	 */
	public function getLastModified(): ?string {
		return null;
	}

	/**
	 * @inheritDoc
	 */
	public function getParamSettings(): array {
		return [];
	}

	/**
	 * @inheritDoc
	 */
	public function setVariantConversionLanguage(
		$targetLanguage,
		$sourceLanguage = null
	): void {
		// TODO: Set language in the response headers.
	}

	public function putHeaders(
		ResponseInterface $response,
		bool $forHtml = true
	): void {
		// TODO: Set language in the response headers.
	}

}

/** @deprecated since 1.40, remove in 1.41 */
class_alias( HtmlMessageOutputHelper::class, "MediaWiki\\Rest\\Handler\\HtmlOutputHelper" );
