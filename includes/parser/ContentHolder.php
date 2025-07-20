<?php
declare( strict_types = 1 );

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

namespace MediaWiki\Parser;

use MediaWiki\Logger\LoggerFactory;
use MediaWiki\MediaWikiServices;
use MediaWiki\Parser\Parsoid\PageBundleParserOutputConverter;
use Wikimedia\Assert\Assert;
use Wikimedia\Parsoid\Core\DomPageBundle;
use Wikimedia\Parsoid\Core\HtmlPageBundle;
use Wikimedia\Parsoid\DOM\Document;
use Wikimedia\Parsoid\DOM\DocumentFragment;
use Wikimedia\Parsoid\Utils\ContentUtils;
use Wikimedia\Parsoid\Utils\DOMCompat;
use Wikimedia\Parsoid\Utils\DOMUtils;

/**
 * @internal
 * @unstable
 * @since 1.45
 *
 * A ContentHolder holds a map of fragments that can be HTML string or DOM fragments.
 * It should, as much as possible, be used in a consistent format and/or limit format switches, as conversions of
 * all the fragments happen on all accessors, which has a performance impact.
 *
 * ContentHolder currently makes no guarantee on the preservation of the document outside of the <body> tag. In
 * particular, if a full Parsoid document with a <head> tag is passed as a string, and converted to DOM, the
 * <head> content is lost. This must be taken into account in particular if we create an ExtractBody DOM pass, in
 * which case the <base> tag contained in the <head> must be handled before conversion. OutputTransform steps
 * after ExtractBody should however be unaffected.
 */
class ContentHolder {
	public const BODY_FRAGMENT = "body";

	private function __construct(
		private Document $ownerDocument,
		private ?HtmlPageBundle $pageBundle = null,
		/**
		 * Contains the string representation of the fragments.
		 * $htmlMap[BODY_FRAGMENT] might contain the full document
		 * @var array<string,string>
		 */
		private array $htmlMap = [],
		/**
		 * $domMap[BODY_FRAGMENT] does not contain the <body> tag
		 * @var array<string,DocumentFragment>
		 */
		private array $domMap = [],
		private bool $isParsoidContent = false,
		private bool $domFormat = false
	) {
	}

	/**
	 * Create a ContentHolder from a legacy body string, typically returned by the legacy parser
	 */
	public static function createFromLegacyString( string $s ): ContentHolder {
		$ch = new ContentHolder(
			ownerDocument: DOMUtils::parseHTML( '' ),
			htmlMap: [ self::BODY_FRAGMENT => $s ],
			isParsoidContent: false,
		);
		return $ch;
	}

	public static function createFromParsoidPageBundle( HtmlPageBundle $pb ): ContentHolder {
		$ch = new ContentHolder(
			ownerDocument: ContentUtils::createAndLoadDocument( '' ),
			pageBundle: $pb,
			htmlMap: [ self::BODY_FRAGMENT => $pb->html ],
			isParsoidContent: true,
		);
		return $ch;
	}

	/**
	 * Creates an empty ContentHolder that can be used as a placeholder.
	 */
	public static function createEmpty(): ContentHolder {
		return new ContentHolder(
			ownerDocument: DOMUtils::parseHTML( '' ),
			isParsoidContent: false,
		);
	}

	public function isParsoidContent(): bool {
		// Right now, this invariant feels worth keeping because it helps to make sure that we're doing what we
		// think we're doing; this can however be revisited if we decide to use parts of the pageBundle for legacy
		// content as well.
		Assert::invariant( $this->isParsoidContent === ( $this->pageBundle !== null ),
			'Inconsistency between parsoid status and bundle existence' );
		return $this->isParsoidContent;
	}

	/**
	 * Returns false if the designated fragment is not set in the ContentHolder
	 */
	public function has( string $fragmentName ): bool {
		return isset( $this->htmlMap[$fragmentName] ) || isset( $this->domMap[$fragmentName] );
	}

	/**
	 * Returns the HTML string version of the designated fragment, or null if it is not set.
	 * If a conversion is needed, all fragments of the document are converted to HtmlString.
	 */
	public function getAsHtmlString( string $fragmentName ): ?string {
		if ( $this->domFormat ) {
			$this->convertDomToHtml();
		}
		return $this->htmlMap[$fragmentName] ?? null;
	}

	/**
	 * Returns the DOM version of the designated fragment, or null if it is not set.
	 * If a conversion is needed, all fragments of the document are converted to DOM.
	 */
	public function getAsDom( string $fragmentName ): ?DocumentFragment {
		if ( !$this->domFormat ) {
			$this->convertHtmlToDom();
		}
		return $this->domMap[$fragmentName] ?? null;
	}

	/**
	 * Sets a fragment as a string.
	 * @param string $fragmentName name of the fragment to set
	 * @param string|null $text string of the fragment to set
	 * @return void
	 */
	public function setAsHtmlString( string $fragmentName, ?string $text ): void {
		// no need to convert the fragment that we're going to replace
		unset( $this->domMap[ $fragmentName ] );

		if ( $this->domFormat ) {
			$this->convertDomToHtml();
		}

		if ( $text === null ) {
			unset( $this->htmlMap[$fragmentName] );
		}

		$this->htmlMap[ $fragmentName ] = $text;
	}

	/**
	 * Sets a fragment as a document. Converts the ContentHolder to DOM format beforehand.
	 * @param string $fragmentName name of the fragment to set
	 * @param DocumentFragment|null $dom the fragment to set. It should not contain a body tag in the case of a
	 * 								body fragment.
	 * @return void
	 */
	public function setAsDom( string $fragmentName, ?DocumentFragment $dom ) {
		// no need to convert the fragment that we're going to replace
		unset( $this->htmlMap[ $fragmentName ] );

		if ( !$this->domFormat ) {
			$this->convertHtmlToDom();
		}

		if ( $dom === null ) {
			unset( $this->domMap[ $fragmentName ] );
			return;
		}
		Assert::invariant( $dom->ownerDocument === $this->ownerDocument,
			"Fragment not owned by the ContentHolder document." );

		$firstChild = $dom->firstElementChild;
		if ( $fragmentName === self::BODY_FRAGMENT && $firstChild ) {
			Assert::invariant( DOMCompat::nodeName( $firstChild ) !== "body",
				"Body fragment should not contain a body tag" );
		}

		$this->domMap[$fragmentName] = $dom;
	}

	public function createFragment( ?string $html = null ): DocumentFragment|false {
		if ( !$this->domFormat ) {
			$this->convertHtmlToDom();
		}
		if ( $html === null ) {
			return $this->ownerDocument->createDocumentFragment();
		}
		if ( $this->isParsoidContent() ) {
			return ContentUtils::createAndLoadDocumentFragment( $this->ownerDocument, $html );
		}
		return DOMUtils::parseHTMLToFragment( $this->ownerDocument, $html );
	}

	public function addFragment( string $name, ?string $html = null ): DocumentFragment|false {
		$frag = $this->createFragment( $html );
		$this->setAsDom( $name, $frag );
		return $frag;
	}

	/**
	 * Applies the PageBundle of the ContentHolder to the provided ParserOutput.
	 * @internal
	 */
	public function finalize( ParserOutput $po ): void {
		if ( !$this->isParsoidContent() ) {
			return;
		}

		if ( $this->domFormat ) {
			$this->convertDomToHtml();
		}

		Assert::invariant( $this->pageBundle !== null,
			"Could not apply page bundle from ContentHolder to ParserOutput" );
		PageBundleParserOutputConverter::applyPageBundleDataToParserOutput( $this->pageBundle, $po );
	}

	private function convertHtmlToDom() {
		if ( $this->domFormat ) {
			return;
		}
		if ( $this->isParsoidContent() && $this->has( self::BODY_FRAGMENT ) ) {
			$html = $this->htmlMap[ self::BODY_FRAGMENT ] ?? '';
			$this->pageBundle->html = $html;
			$dpb = DomPageBundle::fromHtmlPageBundle( $this->pageBundle );
			$this->ownerDocument = $dpb->toDom();
			$frag = $this->ownerDocument->createDocumentFragment();
			DOMUtils::migrateChildren( DOMCompat::getBody( $this->ownerDocument ), $frag );
			$this->domMap[ self::BODY_FRAGMENT ] = $frag;
			unset( $this->htmlMap[ self::BODY_FRAGMENT ] );
		}

		foreach ( $this->htmlMap as $name => $html ) {
			if ( !$this->isParsoidContent() ) {
				$this->domMap[$name] = DOMUtils::parseHTMLToFragment( $this->ownerDocument, $html );
			} else {
				// TODO we only handle body fragment for Parsoid for now
				// in Parsoid case, the only supported fragment type is body, and has been handled above.
				$logger = LoggerFactory::getInstance( 'ContentHolder' );
				$logger->warning( 'Fragment {fragname} not converted to DOM before being discarded' );
			}
		}
		$this->htmlMap = [];
		$this->domFormat = true;
	}

	private function convertDomToHtml() {
		if ( !$this->domFormat ) {
			return;
		}
		$services = MediaWikiServices::getInstance();
		foreach ( $this->domMap as $name => $dom ) {
			if ( $this->isParsoidContent() ) {
				if ( $name === self::BODY_FRAGMENT ) {
					DOMCompat::getBody( $this->ownerDocument )->appendChild( $dom );
					$this->pageBundle = HtmlPageBundle::fromDomPageBundle(
						DomPageBundle::fromLoadedDocument( $this->ownerDocument ), [
							'body_only' => true,
							'siteConfig' => $services->getParsoidSiteConfig(),
						]
					);
					$this->htmlMap[self::BODY_FRAGMENT] = $this->pageBundle->html;
				} else {
					// TODO we only handle body fragment for Parsoid for now
					$logger = LoggerFactory::getInstance( 'ContentHolder' );
					$logger->warning( 'Fragment {fragname} not converted to HTML string before being discarded' );
				}
			} else {
				$this->htmlMap[ $name ] = ContentUtils::toXML( $dom, [ 'innerXML' => true ] );
			}
		}
		$this->domMap = [];
		$this->domFormat = false;
	}
}
