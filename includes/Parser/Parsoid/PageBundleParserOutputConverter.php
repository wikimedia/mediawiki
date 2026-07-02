<?php
declare( strict_types = 1 );

namespace MediaWiki\Parser\Parsoid;

use MediaWiki\Language\Language;
use MediaWiki\Language\LanguageCode;
use MediaWiki\MediaWikiServices;
use MediaWiki\Page\PageReference;
use MediaWiki\Parser\ContentHolder;
use MediaWiki\Parser\ParserOutput;
use MediaWiki\SpecialPage\SpecialPage;
use MediaWiki\Title\Title;
use MediaWiki\Utils\MWTimestamp;
use Wikimedia\Assert\Assert;
use Wikimedia\Bcp47Code\Bcp47CodeValue;
use Wikimedia\Parsoid\Config\SiteConfig;
use Wikimedia\Parsoid\Core\BasePageBundle;
use Wikimedia\Parsoid\Core\DOMCompat;
use Wikimedia\Parsoid\Core\DomPageBundle;
use Wikimedia\Parsoid\Core\HtmlPageBundle;
use Wikimedia\Parsoid\Core\LinkTarget as ParsoidLinkTarget;
use Wikimedia\Parsoid\DOM\Document;
use Wikimedia\Parsoid\DOM\Element;
use Wikimedia\Parsoid\Ext\DOMUtils;
use Wikimedia\Parsoid\Parsoid;
use Wikimedia\Timestamp\TimestampFormat;

/**
 * Provides methods for conversion between HtmlPageBundle and ParserOutput
 *
 * ParserOutput typically contains only the article content HTML (ie,
 * without `<body>` tags), while a HtmlPageBundle can contain an entire
 * document including `<html>` wrapper, metadata in the `<head>`, and
 * article content inside a `<body>` tag.
 *
 * @since 1.40
 * @internal
 */
final class PageBundleParserOutputConverter {
	/**
	 * @var string Key used to store parsoid page bundle data in ParserOutput
	 * @deprecated since 1.45; use ParserOutput::PARSOID_PAGE_BUNDLE_KEY
	 */
	public const PARSOID_PAGE_BUNDLE_KEY = ParserOutput::PARSOID_PAGE_BUNDLE_KEY;

	/**
	 * We do not want instances of this class to be created
	 * @return void
	 */
	private function __construct() {
	}

	/**
	 * Creates a ParserOutput object containing the relevant data from
	 * the given HtmlPageBundle object.
	 *
	 * We need to inject data-parsoid and other properties into the
	 * parser output object for caching, so we can use it for VE edits
	 * and transformations.
	 *
	 * @param HtmlPageBundle $pageBundle
	 * @param ?ParserOutput $originalParserOutput Any non-parsoid metadata
	 *  from $originalParserOutput will be copied into the new ParserOutput object.
	 * @param ParsoidLinkTarget|PageReference|null $title The given title will
	 *  be copied into the new ParserOutput object.
	 * @param ?SiteConfig $siteConfig
	 *
	 * @return ParserOutput
	 */
	public static function parserOutputFromPageBundle(
		HtmlPageBundle $pageBundle,
		?ParserOutput $originalParserOutput = null,
		// phpcs:ignore MediaWiki.Usage.NullableType.ExplicitNullableTypes
		ParsoidLinkTarget|PageReference|null $title = null,
		?SiteConfig $siteConfig = null,
	): ParserOutput {
		$siteConfig ??= MediaWikiServices::getInstance()->getParsoidSiteConfig();
		$parserOutput = new ParserOutput();
		$parserOutput->setContentHolder(
			ContentHolder::createFromParsoidPageBundle( $pageBundle, $siteConfig )
		);
		if ( $originalParserOutput ) {
			// Merging metadata from the original parser output will also
			// potentially transfer fragments from
			// $originalParserOutput->getContentHolder() to
			// $parserOutput->getContentHolder()
			$originalParserOutput->collectMetadata( $parserOutput );
		}
		if ( $title !== null ) {
			$parserOutput->setTitle( $title );
		}
		if ( isset( $pageBundle->headers['content-language'] ) ) {
			$lang = LanguageCode::normalizeNonstandardCodeAndWarn(
				// @phan-suppress-next-line PhanTypeArraySuspiciousNullable
				$pageBundle->headers['content-language']
			);
			$parserOutput->setLanguage( $lang );
		}
		if ( isset( $pageBundle->headers['x-mediawiki-render-id'] ) ) {
			$parserOutput->setRenderId(
				// @phan-suppress-next-line PhanTypeArraySuspiciousNullable
				$pageBundle->headers['x-mediawiki-render-id']
			);
		}
		return $parserOutput;
	}

	/**
	 * Returns a Parsoid HtmlPageBundle equivalent to the given ParserOutput.
	 * @param ParserOutput $parserOutput
	 *
	 * @return HtmlPageBundle
	 * @deprecated Use ::htmlPageBundleFromParserOutput
	 */
	public static function pageBundleFromParserOutput( ParserOutput $parserOutput ): HtmlPageBundle {
		wfDeprecated( __METHOD__, '1.46' );
		return self::htmlPageBundleFromParserOutput(
			$parserOutput,
			MediaWikiServices::getInstance()->getParsoidSiteConfig(),
			true,
		);
	}

	/**
	 * Returns a Parsoid HtmlPageBundle equivalent to the given ParserOutput.
	 * @param ParserOutput $parserOutput
	 * @param SiteConfig $siteConfig ParsoidSiteConfig service
	 * @param bool $bodyOnly If false, returns a full document with
	 *  metadata in the <head>.  If true, the `html` section of
	 *  the PageBundle returns the inner HTML of the <body> element
	 *  only.
	 * @return HtmlPageBundle
	 */
	public static function htmlPageBundleFromParserOutput(
		ParserOutput $parserOutput,
		SiteConfig $siteConfig,
		bool $bodyOnly = false,
	): HtmlPageBundle {
		$bpb = self::basePageBundleFromParserOutput( $parserOutput );
		$html = $parserOutput->getContentHolderText();
		if ( !$bodyOnly ) {
			$document = DOMCompat::newDocument();
			self::addMetadataToDocument( $parserOutput, $siteConfig, $bpb, $document );
			// Add selected header information from page bundle to the <head>
			foreach ( [ 'content-language', 'vary', 'x-mediawiki-render-id' ] as $h ) {
				if ( isset( $bpb->headers[$h] ) ) {
					self::appendToHead( $document, 'meta', [
						'http-equiv' => $h,
						// @phan-suppress-next-line PhanTypeArraySuspiciousNullable
						'content' => $bpb->headers[$h],
					] );
				}
			}
			// We don't want to parse the content holder to DOM just to
			// reserialize as HTML, so serialize just the full document
			// wrapper, and then use string concatenation to slip the
			// body HTML before the `</body>` tag.
			$fulldoc = HtmlPageBundle::fromDomPageBundle(
				DomPageBundle::newEmpty( $document ), [
					'addDoctype' => true,
				] )->html;
			$posEnd = strrpos( $fulldoc, '</body>' );
			Assert::invariant(
				$posEnd !== false, "should have <body>"
			);
			$html = substr( $fulldoc, 0, $posEnd ) .
				  $html .
				  substr( $fulldoc, $posEnd );
		}
		$pb = $bpb->withHtml( $html );
		// NOTE that the fragments from the ContentHolder are missing
		// from this page bundle.  It is assumed that the fragments
		// are referenced from other parts of the ParserOutput; aka that
		// they are loaded/saved as part of ParserOutput::$mIndicators
		return $pb;
	}

	private static function basePageBundleFromParserOutput( ParserOutput $parserOutput ): BasePageBundle {
		$contentHolder = $parserOutput->getContentHolder();
		$basePageBundle = $contentHolder->isParsoidContent() ?
			$contentHolder->getBasePageBundle() :
			new BasePageBundle(
				parsoid: [ 'ids' => [] ],
				headers: [],
				// It would be nice to have this be "null", but
				// ParsoidFormatHelper chokes on that: T325137.
				version: '0.0.0',
			);

		$lang = $parserOutput->getLanguage();
		if ( $lang ) {
			$basePageBundle->headers ??= [];
			$basePageBundle->headers['content-language'] = $lang->toBcp47Code();
		}

		$renderid = $parserOutput->getRenderId();
		if ( $renderid !== null ) {
			$basePageBundle->headers ??= [];
			$basePageBundle->headers['x-mediawiki-render-id'] = $renderid;
		}
		return $basePageBundle;
	}

	public static function hasPageBundle( ParserOutput $parserOutput ): bool {
		return $parserOutput->getContentHolder()->isParsoidContent();
	}

	private static function getMetadataMap( string $key ): ?array {
		static $map = null;
		$map ??= [
			'ns' => [
				'property' => 'mw:pageNamespace',
				'content' => '%d',
			],
			'id' => [
				'property' => 'mw:pageId',
				'content' => '%d',
			],

			// DO NOT ADD rev_user, rev_userid, and rev_comment (See T125266)
			// 'rev_revid' is used to set the overall subject of the document, we don't
			// need to add a specific <meta> or <link> element for it.

			'rev_parentid' => [
				'rel' => 'dc:replaces',
				'resource' => 'mwr:revision/%d',
			],
			'rev_timestamp' => [
				'property' => 'dc:modified',
				'content' => static fn ( $m ) =>
					# Convert from TS_MW ("mediawiki timestamp") format
					MWTimestamp::fromMW( $m['rev_timestamp'] )->
						getTimestamp( TimestampFormat::ISO_8601 ),
			],
			'rev_sha1' => [
				'property' => 'mw:revisionSHA1',
				'content' => '%s',
			]
		];
		return $map[$key] ?? null;
	}

	/**
	 * Add information to the document <head> corresponding to metadata
	 * stored in the ParserOutput.
	 */
	private static function addMetadataToDocument(
		ParserOutput $parserOutput, SiteConfig $siteConfig,
		BasePageBundle $pb, Document $document
	): void {
		// This method is a direct port/translation of the AddMetaData
		// DOM pipeline stage in Parsoid. The intention is for the Parsoid
		// stage to be eventually removed entirely in favor of this
		// implementation in core (T393925) so new feature development
		// (metadata additions to <head>) should happen here, not in Parsoid.
		Assert::invariant(
			DOMCompat::getHead( $document )?->firstChild === null,
			"head should be empty"
		);
		// Set the charset in the <head> first.
		// This also adds the <head> element if it was missing.
		self::appendToHead( $document, 'meta', [ 'charset' => 'utf-8' ] );

		// add mw: and mwr: RDFa prefixes
		$prefixes = [
			'dc: http://purl.org/dc/terms/',
			'mw: http://mediawiki.org/rdf/'
		];
		$document->documentElement->setAttribute( 'prefix', implode( ' ', $prefixes ) );

		$mwrPrefix = SpecialPage::getTitleFor( 'Redirect', '' )->getFullURL();
		( DOMCompat::getHead( $document ) )->setAttribute( 'prefix', 'mwr: ' . $mwrPrefix );

		// add <head> content based on page meta data:
		$revProps = [];
		$title = $parserOutput->getTitle();
		$revId = $parserOutput->getCacheRevisionId();
		$revRecord = null;
		if ( $revId ) {
			$revLookup = MediaWikiServices::getInstance()->getRevisionLookup();
			$revRecord = $revLookup->getRevisionById( $revId );
		}
		if ( $revRecord !== null ) {
			$revProps += [
				'rev_parentid' => $revRecord->getParentId(),
				'rev_revid' => $revRecord->getId(),
				'rev_sha1' => $revRecord->getSha1(),
				'rev_timestamp' => $revRecord->getTimestamp(),
			];
			// If both Revision ID and Title as provided; revision overrides
			// (never output contradictory title and revision information)
			$title = $revRecord->getPageAsLinkTarget();
		}
		if ( $title !== null ) {
			$title = Title::newFromLinkTarget( $title );
			if ( $title !== null ) {
				$revProps['ns'] = $title->getNamespace();
			}
			if ( $title?->canExist() ) {
				$revProps['id'] = $title->getId();
			}
		}
		$revProps['rev_revid'] ??= $parserOutput->getCacheRevisionId();
		$revProps['rev_timestamp'] ??= $parserOutput->getRevisionTimestamp();
		foreach ( $revProps as $key => $value ) {
			// generate proper attributes for the <meta> or <link> tag
			if ( $value === null || $value === '' || self::getMetadataMap( $key ) === null ) {
				continue;
			}

			$attrs = [];
			foreach ( self::getMetadataMap( $key ) as $k => $v ) {
				// evaluate a function, or perform sprintf-style formatting, or
				// use string directly, depending on value in metadataMap
				if ( $v instanceof \Closure ) {
					$v = $v( $revProps );
				} elseif ( str_contains( $v, '%' ) ) {
					$v = sprintf( $v, $value );
				}
				$attrs[$k] = $v;
			}

			// <link> is used if there's a resource or href attribute.
			self::appendToHead( $document,
				isset( $attrs['resource'] ) || isset( $attrs['href'] ) ? 'link' : 'meta',
				$attrs
			);
		}

		if ( $revProps['rev_revid'] ) {
			$document->documentElement->setAttribute(
				'about', $mwrPrefix . 'revision/' . $revProps['rev_revid']
			);
		}

		// Normalize before comparison
		if ( $title?->isSameLinkAs( Title::newMainPage() ) ) {
			self::appendToHead( $document, 'meta', [
				'property' => 'isMainPage',
				'content' => 'true' /* HTML attribute values should be strings */
			] );
		}

		if ( $parserOutput->getContentHolder()->isParsoidContent() ) {
			// Set the parsoid content-type strings
			$htmlVersion = $pb->version ??
				$parserOutput->getExtensionData( 'core:html-version' ) ??
				Parsoid::defaultHTMLVersion();
			// FIXME: Should we be using http-equiv for this?
			self::appendToHead( $document, 'meta', [
				'property' => 'mw:htmlVersion',
				'content' => $htmlVersion,
			] );
			// Temporary backward compatibility for clients
			// This could be skipped if we support a version downgrade path
			// with a major version bump.
			self::appendToHead( $document, 'meta', [
				'property' => 'mw:html:version',
				'content' => $htmlVersion,
			] );
		}

		if ( $title !== null ) {
			self::appendToHead( $document, 'link', [
				'rel' => 'dc:isVersionOf',
				'href' => $title->getFullURL(),
			] );
			// T324431: Note that this is *not* the displaytitle, and that
			// the title element contents are plaintext *not* HTML
			DOMCompat::setTitle( $document, $title->getPrefixedText() );
		}

		// Add base href pointing to the wiki root
		self::appendToHead( $document, 'base', [
			'href' => $siteConfig->baseURI()
		] );

		// Ensure there's a <body>
		if ( DOMCompat::getBody( $document ) === null ) {
			DOMCompat::append(
				$document->documentElement,
				$document->createElement( 'body' )
			);
		}

		// Set properties of <body>
		$lang = $parserOutput->getLanguage();
		if ( $lang !== null ) {
			$lang = MediaWikiServices::getInstance()->getLanguageFactory()
				->getLanguage( $lang );
		}
		self::updateBodyClasslist(
			DOMCompat::getBody( $document ), $lang, $parserOutput
		);

		$siteConfig->exportMetadataToHeadBcp47(
			$document, $parserOutput,
			( $title ?? Title::newMainPage() )->getPrefixedText(),
			$lang ?? new Bcp47CodeValue( 'en' )
		);
	}

	private static function updateBodyClasslist(
		Element $body, ?Language $lang, ParserOutput $parserOutput
	) {
		$bodyCL = DOMCompat::getClassList( $body );
		if ( $lang !== null ) {
			$dir = $lang->getDir();
			$bodyCL->add( 'mw-content-' . $dir );
			$bodyCL->add( 'sitedir-' . $dir );
			$bodyCL->add( $dir );
			$body->setAttribute( 'lang', $lang->toBcp47Code() );
			$body->setAttribute( 'dir', $dir );
		}

		// Set 'mw-body-content' directly on the body.
		// This is the designated successor for #bodyContent in core skins.
		$bodyCL->add( 'mw-body-content' );
		// Also, add the 'mediawiki' class.
		// Some MediaWiki:Common.css seem to target this selector.
		$bodyCL->add( 'mediawiki' );
		// Set 'mw-parser-output' directly on the body.
		// Templates target this class as part of the TemplateStyles RFC
		// FIXME: This isn't expected to be found on the same element as the
		// body class above, since some css targets it as a descendant.
		// In visual diff'ing, we migrate the body contents to a wrapper div
		// with this class to reduce visual differences.  Consider getting
		// rid of it.
		$bodyCL->add( 'mw-parser-output' );

		if ( $parserOutput->getContentHolder()->isParsoidContent() ) {
			// Set 'parsoid-body' to add the desired layout styling from Vector.
			$bodyCL->add( 'parsoid-body' );
			// Set the parsoid version on the body, for consistency with
			// the wrapper div.  Make this match the extension data in
			// case the content is coming from cache and was generated
			// with a different version.
			$body->setAttribute(
				'data-mw-parsoid-version',
				$parserOutput->getExtensionData( 'core:parsoid-version' ) ??
					Parsoid::version()
			);
			$body->setAttribute(
				'data-mw-html-version',
				$parserOutput->getExtensionData( 'core:html-version' ) ??
					Parsoid::defaultHTMLVersion()
			);
		}
	}

	/**
	 * Create an element in the document head with the given attrs.
	 * Creates the head element in the document if needed.
	 *
	 * @param Document $document
	 * @param string $tagName
	 * @param array $attrs
	 * @return Element The newly-appended Element
	 */
	private static function appendToHead(
		Document $document, string $tagName, array $attrs = []
	): Element {
		$elt = $document->createElement( $tagName );
		DOMUtils::addAttributes( $elt, $attrs );
		$head = DOMCompat::getHead( $document );
		if ( !$head ) {
			if ( !$document->documentElement ) {
				$document->appendChild( $document->createElement( 'html' ) );
			}
			$head = $document->createElement( 'head' );
			$document->documentElement->insertBefore(
				$head, DOMCompat::getBody( $document )
			);
		}
		$head->appendChild( $elt );
		return $elt;
	}
}
