<?php
declare( strict_types = 1 );

/**
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\Parser;

use MediaWiki\MediaWikiServices;
use Wikimedia\Assert\Assert;
use Wikimedia\JsonCodec\Hint;
use Wikimedia\JsonCodec\JsonCodecable;
use Wikimedia\JsonCodec\JsonCodecableTrait;
use Wikimedia\Parsoid\Config\SiteConfig;
use Wikimedia\Parsoid\Core\BasePageBundle;
use Wikimedia\Parsoid\Core\DOMCompat;
use Wikimedia\Parsoid\Core\DomPageBundle;
use Wikimedia\Parsoid\Core\HtmlPageBundle;
use Wikimedia\Parsoid\DOM\Document;
use Wikimedia\Parsoid\DOM\DocumentFragment;
use Wikimedia\Parsoid\Ext\DOMUtils;
use Wikimedia\Parsoid\Utils\ContentUtils;
use Wikimedia\Parsoid\Utils\DOMDataUtils;

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
class ContentHolder implements JsonCodecable {
	use JsonCodecableTrait;

	public const BODY_FRAGMENT = "body";

	private function __construct(
		private Document $ownerDocument,
		private ?BasePageBundle $pageBundle = null,
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
		private bool $domFormat = false,
		private ?SiteConfig $siteConfig = null,
	) {
	}

	/**
	 * Create a ContentHolder from a legacy body HTML string, typically
	 * returned by the legacy parser.
	 */
	public static function createFromLegacyString( string $html ): ContentHolder {
		$ch = new ContentHolder(
			ownerDocument: DOMCompat::newDocument(),
			htmlMap: [ self::BODY_FRAGMENT => $html ],
			isParsoidContent: false,
		);
		return $ch;
	}

	/**
	 * Create a ContentHolder from a Parsoid HtmlPageBundle.
	 */
	public static function createFromParsoidPageBundle(
		HtmlPageBundle $pb,
		?SiteConfig $siteConfig = null
	): ContentHolder {
		$siteConfig ??= MediaWikiServices::getInstance()->getParsoidSiteConfig();
		$htmlMap = [
			self::BODY_FRAGMENT => $pb->html,
		] + $pb->fragments;
		$ch = new ContentHolder(
			ownerDocument: ContentUtils::createAndLoadDocument(
				'', siteConfig: $siteConfig,
			),
			pageBundle: $pb->toBasePageBundle(),
			htmlMap: $htmlMap,
			isParsoidContent: true,
			siteConfig: $siteConfig,
		);
		return $ch;
	}

	/**
	 * Creates an empty ContentHolder that can be used as a placeholder.
	 *
	 * This does not contain any body content.
	 */
	public static function createEmpty(): ContentHolder {
		return new ContentHolder(
			ownerDocument: DOMCompat::newDocument(),
			isParsoidContent: false,
		);
	}

	/**
	 * Returns true if this ContentHolder contains Parsoid-generated
	 * content.
	 */
	public function isParsoidContent(): bool {
		// Right now, this invariant feels worth keeping because it helps to make sure that we're doing what we
		// think we're doing; this can however be revisited if we decide to use parts of the pageBundle for legacy
		// content as well.
		Assert::invariant( $this->isParsoidContent === ( $this->pageBundle !== null ),
			'Inconsistency between parsoid status and bundle existence' );
		return $this->isParsoidContent;
	}

	/**
	 * Returns false if the designated fragment is not present in the
	 * ContentHolder.
	 */
	public function has( string $fragmentName ): bool {
		return isset( $this->htmlMap[$fragmentName] ) || isset( $this->domMap[$fragmentName] );
	}

	/**
	 * Returns the designated fragment as an HTML string version, or null if
	 * it is not present.
	 *
	 * @note If a conversion is needed, at present all fragments of the
	 * document are converted to HTML strings.
	 */
	public function getAsHtmlString( string $fragmentName = self::BODY_FRAGMENT ): ?string {
		if ( $this->domFormat ) {
			$this->convertDomToHtml();
		}
		return $this->htmlMap[$fragmentName] ?? null;
	}

	/**
	 * Returns the designated fragment as a DOM DocumentFragment, or null if
	 * it is not present.
	 *
	 * @note If a conversion is needed, at present all fragments of the
	 * document are converted to DOM DocumentFragments.
	 */
	public function getAsDom( string $fragmentName = self::BODY_FRAGMENT ): ?DocumentFragment {
		if ( !$this->domFormat ) {
			$this->convertHtmlToDom();
		}
		return $this->domMap[$fragmentName] ?? null;
	}

	/**
	 * Sets or removes a fragment, provided as an HTML string.
	 * @param string $fragmentName name of the fragment to set
	 * @param string|null $html string of the fragment to set, or null to
	 *  remove a fragment.
	 * @param-taint $html exec_html
	 * @return void
	 *
	 * @note The self::BODY_FRAGMENT should not contain the top-level <body>
	 * tag.
	 * @note All fragments may be converted to HTML strings as a side-effect.
	 */
	public function setAsHtmlString( string $fragmentName = self::BODY_FRAGMENT, ?string $html = null ): void {
		// no need to convert the fragment that we're going to replace
		unset( $this->domMap[ $fragmentName ] );

		if ( $html === null ) {
			unset( $this->htmlMap[$fragmentName] );
			return;
		}

		if ( $this->domFormat ) {
			$this->convertDomToHtml();
		}

		if ( $fragmentName === self::BODY_FRAGMENT ) {
			Assert::invariant( !str_starts_with( $html, '<body' ),
							   "Body fragment should not contain a body tag" );
		}
		$this->htmlMap[ $fragmentName ] = $html;
	}

	/**
	 * Helper method to append the given HTML string to the
	 * named content fragment.
	 */
	public function appendHtmlString( string $html, string $fragmentName = self::BODY_FRAGMENT ): void {
		if ( $this->domFormat ) {
			$df = $this->createFragment( $html );
			$this->appendDom( $df, $fragmentName );
		} else {
			$prev = $this->htmlMap[ $fragmentName ] ?? '';
			$this->htmlMap[ $fragmentName ] = $prev . $html;
		}
	}

	/**
	 * Helper method to append the given DocumentFragment to the
	 * named content fragment.
	 */
	public function appendDom( DocumentFragment $df, string $fragmentName = self::BODY_FRAGMENT ) {
		if ( !$this->domFormat ) {
			$this->convertHtmlToDom();
		}
		$prev = $this->domMap[ $fragmentName ] ??
			  $this->ownerDocument->createDocumentFragment();
		DOMCompat::append( $prev, $df );
		$this->domMap[ $fragmentName ] = $prev;
	}

	/**
	 * Sets or removes a fragment, provided as a DOM DocumentFragment.
	 * @param string $fragmentName name of the fragment to set
	 * @param DocumentFragment|null $fragment the fragment to set, or null to
	 *   remove a fragment.
	 * @return void
	 *
	 * @note The self::BODY_FRAGMENT should not contain the top-level <body>
	 * tag.
	 * @note All fragments may be converted to DOM DocumentFragments as a
	 * side-effect.
	 */
	public function setAsDom( string $fragmentName = self::BODY_FRAGMENT, ?DocumentFragment $fragment = null ) {
		// no need to convert the fragment that we're going to replace
		unset( $this->htmlMap[ $fragmentName ] );

		if ( $fragment === null ) {
			unset( $this->domMap[ $fragmentName ] );
			return;
		}

		if ( !$this->domFormat ) {
			$this->convertHtmlToDom();
		}

		Assert::invariant( $fragment->ownerDocument === $this->ownerDocument,
			"Fragment not owned by the ContentHolder document." );

		$firstChild = $fragment->firstElementChild;
		if ( $fragmentName === self::BODY_FRAGMENT && $firstChild ) {
			Assert::invariant( DOMUtils::nodeName( $firstChild ) !== "body",
				"Body fragment should not contain a body tag" );
		}

		$this->domMap[$fragmentName] = $fragment;
	}

	public function createFragment( ?string $html = null ): DocumentFragment {
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

	public function addFragment( string $name = self::BODY_FRAGMENT, ?string $html = null ): DocumentFragment {
		$frag = $this->createFragment( $html );
		$this->setAsDom( $name, $frag );
		return $frag;
	}

	/**
	 * Return all the fragment names.  You can pass `bodyFirst: true` in
	 * cases where the order of fragments matters, which will ensure that
	 * the first element is self::BODY_FRAGMENT (if it exists in this
	 * ContentHolder).
	 * @param bool $bodyFirst Pass true to get a repeatable sorted list
	 *  of fragments with the body fragment first.
	 * @return list<string>
	 */
	public function getFragmentNames( bool $bodyFirst = false ): array {
		$names = array_keys( $this->domFormat ? $this->domMap : $this->htmlMap );
		// Just in case someone uses a numeric fragment name, make sure the
		// result is a string.
		$names = array_map( strval( ... ), $names );
		if ( $bodyFirst ) {
			// Sort names so that the fragment name order is reproducible
			sort( $names );
			// Now put BODY_FRAGMENT first, if it was present.
			$idx = array_search( self::BODY_FRAGMENT, $names );
			if ( $idx !== false ) {
				array_splice( $names, 0, 0, array_splice( $names, $idx, 1 ) );
			}
		}
		return $names;
	}

	/**
	 * Return the BasePageBundle of the ContentHolder.
	 * @internal
	 */
	public function getBasePageBundle(): BasePageBundle {
		Assert::invariant( $this->isParsoidContent(), 'getBasePageBundle called on non-Parsoid ContentHolder' );
		if ( $this->domFormat ) {
			// Ensure that data-parsoid and data-mw are serialized into
			// the page bundle.
			$this->convertDomToHtml();
		}
		$pb = $this->pageBundle;
		// Parsoid content implies page bundle is non-null
		'@phan-var BasePageBundle $pb';
		return $pb;
	}

	private function convertHtmlToDom() {
		if ( $this->domFormat ) {
			return;
		}
		if ( $this->isParsoidContent() ) {
			$hasBody = $this->has( self::BODY_FRAGMENT );
			$fragments = $this->htmlMap;
			$html = $fragments[ self::BODY_FRAGMENT ] ?? '';
			unset( $fragments[ self::BODY_FRAGMENT ] );
			$dpb = DomPageBundle::fromHtmlPageBundle(
				$this->pageBundle->withHtml( $html, $fragments )
			);
			$this->ownerDocument = $dpb->toDom(
				siteConfig: $this->siteConfig,
			);
			$this->domMap = $dpb->fragments;
			if ( $hasBody ) {
				$frag = $this->ownerDocument->createDocumentFragment();
				DOMUtils::migrateChildren( DOMCompat::getBody( $this->ownerDocument ), $frag );
				$this->domMap[ self::BODY_FRAGMENT ] = $frag;
			}
		} else {
			foreach ( $this->htmlMap as $name => $html ) {
				$this->domMap[$name] = DOMUtils::parseHTMLToFragment(
					$this->ownerDocument, $html
				);
			}
		}
		$this->htmlMap = [];
		$this->domFormat = true;
	}

	private function convertDomToHtml() {
		if ( !$this->domFormat ) {
			return;
		}
		if ( $this->isParsoidContent() ) {
			$body = $this->domMap[ self::BODY_FRAGMENT ] ?? null;
			unset( $this->domMap[ self::BODY_FRAGMENT ] );
			if ( $body !== null ) {
				DOMCompat::appendChild(
					DOMCompat::getBody( $this->ownerDocument ),
					$body
				);
			}
			$pb = HtmlPageBundle::fromDomPageBundle(
				DomPageBundle::fromLoadedDocument(
					$this->ownerDocument,
					// If isParsoidContent then siteConfig will be non-null.
					// @phan-suppress-next-line PhanTypeMismatchArgumentNullable
					siteConfig: $this->siteConfig,
					options: [ 'pageBundle' => $this->pageBundle, ],
					fragments: $this->domMap,
				), [
					'body_only' => true,
				]
			);
			$this->pageBundle = $pb->toBasePageBundle();
			$this->htmlMap = $pb->fragments;
			$this->htmlMap[self::BODY_FRAGMENT] = $pb->html;
		} else {
			foreach ( $this->domMap as $name => $df ) {
				$this->htmlMap[ $name ] = ContentUtils::toXML( $df, [ 'innerXML' => true ] );
			}
		}
		$this->domMap = [];
		$this->domFormat = false;
	}

	public function __clone() {
		$this->ownerDocument = DOMDataUtils::cloneDocument( $this->ownerDocument );
		foreach ( $this->domMap as $name => &$fragment ) {
			$fragment = $this->ownerDocument->importNode( $fragment, true );
		}
		if ( $this->pageBundle ) {
			$this->pageBundle = clone $this->pageBundle;
		}
	}

	/**
	 * Given a ContentHolderTransformStage that has two valid transform options, returns true if the state of the
	 * ContentHolder calls for a DOM transform, false if it calls for a text transform.
	 * Right now, this is strictly directed by whether the ContentHolder is in DOM format or in HTML format;
	 * this might change in the future if we maintain both formats in some cases.
	 */
	public function preferDom(): bool {
		return $this->domFormat;
	}

	public function hasContent(): bool {
		return $this->isParsoidContent ||
			$this->domMap ||
			$this->htmlMap ||
			( $this->pageBundle?->hasContent() );
	}

	public function toJsonArray(): array {
		$this->convertDomToHtml();
		$data = [];
		if ( $this->htmlMap ) {
			$data['htmlMap'] = $this->htmlMap;
		}
		if ( $this->isParsoidContent ) {
			$data['parsoid'] = $this->isParsoidContent;
		}
		if ( $this->pageBundle && $this->pageBundle?->hasContent() ) {
			$data['pageBundle'] = $this->pageBundle;
		}
		return $data;
	}

	public static function newFromJsonArray( array $json ): ContentHolder {
		$holder = self::createEmpty();
		$holder->isParsoidContent = $json['parsoid'] ?? false;
		if ( $holder->isParsoidContent ) {
			$holder->siteConfig = MediaWikiServices::getInstance()->getParsoidSiteConfig();
		}
		$holder->convertDomToHtml(); // ensure in html form
		$holder->pageBundle = $json['pageBundle'] ?? ( $holder->isParsoidContent ? new BasePageBundle() : null );
		if ( isset( $json['htmlMap'] ) ) {
			$holder->htmlMap = $json['htmlMap'];
		}
		return $holder;
	}

	/** @inheritDoc */
	public static function jsonClassHintFor( string $keyName ) {
		return match ( $keyName ) {
			'pageBundle' => Hint::build( BasePageBundle::class ),
			default => null,
		};
	}

	/** Helper for serialization compatibility testing. */
	private static function normalizeForObjectEquality(): array {
		return [ 'ownerDocument' => false, 'siteConfig' => false ];
	}
}
