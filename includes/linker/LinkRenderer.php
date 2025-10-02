<?php
/**
 * @license GPL-2.0-or-later
 * @file
 * @author Kunal Mehta <legoktm@debian.org>
 */
namespace MediaWiki\Linker;

use MediaWiki\Cache\LinkCache;
use MediaWiki\Config\ServiceOptions;
use MediaWiki\HookContainer\HookContainer;
use MediaWiki\HookContainer\HookRunner;
use MediaWiki\Html\Html;
use MediaWiki\Language\Language;
use MediaWiki\Linker\LinkTarget as MWLinkTarget;
use MediaWiki\Message\Message;
use MediaWiki\Page\PageReference;
use MediaWiki\Parser\Parser;
use MediaWiki\SpecialPage\SpecialPageFactory;
use MediaWiki\Title\Title;
use MediaWiki\Title\TitleFormatter;
use MediaWiki\Title\TitleValue;
use Wikimedia\Assert\Assert;
use Wikimedia\HtmlArmor\HtmlArmor;
use Wikimedia\Parsoid\Core\LinkTarget;

/**
 * Class that generates HTML for internal links.
 * See the Linker class for other kinds of links.
 *
 * @see https://www.mediawiki.org/wiki/Manual:LinkRenderer
 * @since 1.28
 */
class LinkRenderer {

	public const CONSTRUCTOR_OPTIONS = [
		'renderForComment',
	];

	/**
	 * Whether to force the pretty article path
	 *
	 * @var bool
	 */
	private $forceArticlePath = false;

	/**
	 * A PROTO_* constant or false
	 *
	 * @var string|bool|int
	 */
	private $expandUrls = false;

	/**
	 * Whether links are being rendered for comments.
	 *
	 * @var bool
	 */
	private $comment = false;

	/**
	 * @var TitleFormatter
	 */
	private $titleFormatter;

	/**
	 * @var LinkCache
	 */
	private $linkCache;

	/** @var HookRunner */
	private $hookRunner;

	/**
	 * @var SpecialPageFactory
	 */
	private $specialPageFactory;

	/**
	 * @internal For use by LinkRendererFactory
	 *
	 * @param TitleFormatter $titleFormatter
	 * @param LinkCache $linkCache
	 * @param SpecialPageFactory $specialPageFactory
	 * @param HookContainer $hookContainer
	 * @param ServiceOptions $options
	 */
	public function __construct(
		TitleFormatter $titleFormatter,
		LinkCache $linkCache,
		SpecialPageFactory $specialPageFactory,
		HookContainer $hookContainer,
		ServiceOptions $options
	) {
		$options->assertRequiredOptions( self::CONSTRUCTOR_OPTIONS );
		$this->comment = $options->get( 'renderForComment' );

		$this->titleFormatter = $titleFormatter;
		$this->linkCache = $linkCache;
		$this->specialPageFactory = $specialPageFactory;
		$this->hookRunner = new HookRunner( $hookContainer );
	}

	/**
	 * Whether to force the link to use the article path ($wgArticlePath) even if
	 * a query string is present, resulting in URLs like /wiki/Main_Page?action=foobar.
	 *
	 * @param bool $force
	 */
	public function setForceArticlePath( $force ) {
		$this->forceArticlePath = $force;
	}

	/**
	 * @return bool
	 * @see setForceArticlePath()
	 */
	public function getForceArticlePath() {
		return $this->forceArticlePath;
	}

	/**
	 * Whether/how to expand URLs.
	 *
	 * @param string|bool|int $expand A PROTO_* constant or false for no expansion
	 * @see UrlUtils::expand()
	 */
	public function setExpandURLs( $expand ) {
		$this->expandUrls = $expand;
	}

	/**
	 * @return string|bool|int a PROTO_* constant or false for no expansion
	 * @see setExpandURLs()
	 */
	public function getExpandURLs() {
		return $this->expandUrls;
	}

	/**
	 * True when the links will be rendered in an edit summary or log comment.
	 */
	public function isForComment(): bool {
		// This option only exists to power a hack in Wikibase's onHtmlPageLinkRendererEnd hook.
		return $this->comment;
	}

	/**
	 * Render a wikilink.
	 * Will call makeKnownLink() or makeBrokenLink() as appropriate.
	 *
	 * @param LinkTarget|PageReference $target Page that will be visited when the user clicks on the link.
	 * @param-taint $target none
	 * @param string|HtmlArmor|null $text Text that the user can click on to visit the link.
	 * @param-taint $text escapes_html
	 * @param array $extraAttribs Attributes you would like to add to the <a> tag. For example, if
	 * you would like to add title="Text when hovering!", you would set this to [ 'title' => 'Text
	 * when hovering!' ]
	 * @param-taint $extraAttribs none
	 * @param array $query Parameters you would like to add to the URL. For example, to
	 * add `?redirect=no&debug=true`, you would pass `[ 'redirect' => 'no', 'debug' => 'true' ]`
	 * @param-taint $query none
	 * @return string HTML
	 * @return-taint escaped
	 */
	public function makeLink(
		$target, $text = null, array $extraAttribs = [], array $query = []
	) {
		Assert::parameterType( [ LinkTarget::class, PageReference::class ], $target, '$target' );
		if ( $this->castToTitle( $target )->isKnown() ) {
			return $this->makeKnownLink( $target, $text, $extraAttribs, $query );
		} else {
			return $this->makeBrokenLink( $target, $text, $extraAttribs, $query );
		}
	}

	/**
	 * @param LinkTarget $target $target
	 * @param string|HtmlArmor|null &$text
	 * @param array &$extraAttribs
	 * @param array &$query
	 * @return string|null|void
	 */
	private function runBeginHook( $target, &$text, array &$extraAttribs, array &$query ) {
		$ret = null;
		if ( !$this->hookRunner->onHtmlPageLinkRendererBegin(
			// @phan-suppress-next-line PhanTypeMismatchArgument Type mismatch on pass-by-ref args
			$this, $this->castToTitle( $target ), $text, $extraAttribs, $query, $ret )
		) {
			return $ret;
		}
	}

	/**
	 * Make a link that's styled as if the target page exists (a "blue link"), with a specified
	 * class attribute.
	 *
	 * Usually you should use makeLink() or makeKnownLink() instead, which will select the CSS
	 * classes automatically. Use this method if the exact styling doesn't matter and you want
	 * to ensure no extra DB lookup happens, e.g. for links generated by the skin.
	 *
	 * @param LinkTarget|PageReference $target Page that will be visited when the user clicks on the link.
	 * @param-taint $target none
	 * @param string|HtmlArmor|null $text Text that the user can click on to visit the link.
	 * @param-taint $text escapes_html
	 * @param string|array $classes CSS classes to add
	 * @param-taint $classes none
	 * @param array $extraAttribs Attributes you would like to add to the <a> tag. For example, if
	 * you would like to add title="Text when hovering!", you would set this to [ 'title' => 'Text
	 * when hovering!' ]
	 * @param-taint $extraAttribs none
	 * @param array $query Parameters you would like to add to the URL. For example, to
	 * add `?redirect=no&debug=true`, you would pass `[ 'redirect' => 'no', 'debug' => 'true' ]`
	 * @param-taint $query none
	 * @return string
	 * @return-taint escaped
	 */
	public function makePreloadedLink(
		$target, $text = null, $classes = [], array $extraAttribs = [], array $query = []
	) {
		Assert::parameterType( [ LinkTarget::class, PageReference::class ], $target, '$target' );

		// Run begin hook
		$ret = $this->runBeginHook( $target, $text, $extraAttribs, $query );
		if ( $ret !== null ) {
			return $ret;
		}
		$target = $this->normalizeTarget( $target );
		$url = $this->getLinkURL( $target, $query );
		// Define empty attributes here for consistent order in the output
		$attribs = [ 'href' => null, 'class' => [], 'title' => null ];

		$prefixedText = $this->titleFormatter->getPrefixedText( $target );
		if ( $prefixedText !== '' ) {
			$attribs['title'] = $prefixedText;
		}

		$attribs = array_merge( $attribs, $extraAttribs, [ 'href' => $url ] );

		Html::addClass( $classes, Html::expandClassList( $extraAttribs['class'] ?? [] ) );
		// Stringify attributes for hook compatibility
		$attribs['class'] = Html::expandClassList( $classes );

		$text ??= $this->getLinkText( $target );

		return $this->buildAElement( $target, $text, $attribs, true );
	}

	/**
	 * Make a link that's styled as if the target page exists (usually a "blue link", although the
	 * styling might depend on e.g. whether the target is a redirect).
	 *
	 * This will result in a DB lookup if the title wasn't cached yet. If you want to avoid that,
	 * and don't care about matching the exact styling of links within page content, you can use
	 * makePreloadedLink() instead.
	 *
	 * @param LinkTarget|PageReference $target Page that will be visited when the user clicks on the link.
	 * @param-taint $target none
	 * @param string|HtmlArmor|null $text Text that the user can click on to visit the link.
	 * @param-taint $text escapes_html
	 * @param array $extraAttribs Attributes you would like to add to the <a> tag. For example, if
	 * you would like to add title="Text when hovering!", you would set this to [ 'title' => 'Text
	 * when hovering!' ]
	 * @param-taint $extraAttribs none
	 * @param array $query Parameters you would like to add to the URL. For example, to
	 * add `?redirect=no&debug=true`, you would pass `[ 'redirect' => 'no', 'debug' => 'true' ]`
	 * @param-taint $query none
	 * @return string HTML
	 * @return-taint escaped
	 */
	public function makeKnownLink(
		$target, $text = null, array $extraAttribs = [], array $query = []
	) {
		Assert::parameterType( [ LinkTarget::class, PageReference::class ], $target, '$target' );
		if ( $target instanceof LinkTarget ) {
			$isExternal = $target->isExternal();
		} else {
			// $target instanceof PageReference
			// treat all PageReferences as local for now
			$isExternal = false;
		}
		$classes = [];
		if ( $isExternal ) {
			$classes[] = 'extiw';
		}
		$colour = $this->getLinkClasses( $target );
		if ( $colour !== '' ) {
			$classes[] = $colour;
		}

		return $this->makePreloadedLink(
			$target,
			$text,
			$classes,
			$extraAttribs,
			$query
		);
	}

	/**
	 * Make a link that's styled as if the target page doesn't exist (a "red link").
	 *
	 * @param LinkTarget|PageReference $target Page that will be visited when the user clicks on the link.
	 * @param-taint $target none
	 * @param string|HtmlArmor|null $text Text that the user can click on to visit the link.
	 * @param-taint $text escapes_html
	 * @param array $extraAttribs Attributes you would like to add to the <a> tag. For example, if
	 * you would like to add title="Text when hovering!", you would set this to [ 'title' => 'Text
	 * when hovering!' ]
	 * @param-taint $extraAttribs none
	 * @param array $query Parameters you would like to add to the URL. For example, to
	 * add `?redirect=no&debug=true`, you would pass `[ 'redirect' => 'no', 'debug' => 'true' ]`
	 * @param-taint $query none
	 * @return string
	 * @return-taint escaped
	 */
	public function makeBrokenLink(
		$target, $text = null, array $extraAttribs = [], array $query = []
	) {
		Assert::parameterType( [ LinkTarget::class, PageReference::class ], $target, '$target' );
		// Run legacy hook
		$ret = $this->runBeginHook( $target, $text, $extraAttribs, $query );
		if ( $ret !== null ) {
			return $ret;
		}

		if ( $target instanceof LinkTarget ) {
			# We don't want to include fragments for broken links, because they
			# generally make no sense.
			if ( $target->hasFragment() ) {
				$target = $target->createFragmentTarget( '' );
			}
		}
		$target = $this->normalizeTarget( $target );

		if ( !isset( $query['action'] ) && $target->getNamespace() !== NS_SPECIAL ) {
			$query['action'] = 'edit';
			$query['redlink'] = '1';
		}

		$url = $this->getLinkURL( $target, $query );
		// Define empty attributes here for consistent order in the output
		$attribs = [ 'href' => null, 'class' => [], 'title' => null ];

		$prefixedText = $this->titleFormatter->getPrefixedText( $target );
		if ( $prefixedText !== '' ) {
			// This ends up in parser cache!
			$attribs['title'] = wfMessage( 'red-link-title', $prefixedText )
				->inContentLanguage()
				->text();
		}

		$attribs = array_merge( $attribs, $extraAttribs, [ 'href' => $url ] );

		Html::addClass( $attribs['class'], 'new' );
		// Stringify attributes for hook compatibility
		$attribs['class'] = Html::expandClassList( $attribs['class'] ?? [] );

		$text ??= $this->getLinkText( $target );

		return $this->buildAElement( $target, $text, $attribs, false );
	}

	/**
	 * Make an external link
	 *
	 * @since 1.43
	 * @param string $url URL to link to
	 * @param-taint $url escapes_html
	 * @param string|HtmlArmor|Message $text Text of link; will be escaped if
	 *  a string.
	 * @param-taint $text escapes_html
	 * @param LinkTarget|PageReference $title Where the link is being rendered, used for title specific link attributes
	 * @param-taint $title none
	 * @param string $linktype Type of external link. Gets added to the classes
	 * @param-taint $linktype escapes_html
	 * @param array $attribs Array of extra attributes to <a>
	 * @param-taint $attribs escapes_html
	 * @return string
	 */
	public function makeExternalLink(
		string $url, $text, $title, $linktype = '', $attribs = []
	) {
		$attribs['class'] ??= [];
		Html::addClass( $attribs['class'], 'external' );
		if ( $linktype ) {
			Html::addClass( $attribs['class'], $linktype );
		}
		// Stringify attributes for hook compatibility
		$attribs['class'] = Html::expandClassList( $attribs['class'] );

		if ( $text instanceof Message ) {
			$text = $text->escaped();
		} else {
			$text = HtmlArmor::getHtml( $text );
			// Tell phan that $text is non-null after ::getHtml()
			'@phan-var string $text';
		}

		$newRel = Parser::getExternalLinkRel( $url, $title );
		if ( $newRel !== null ) {
			$attribs['rel'] ??= [];
			Html::addClass( $attribs['rel'], $newRel );
			$attribs['rel'] = Html::expandClassList( $attribs['rel'] );
		}
		$link = '';
		$success = $this->hookRunner->onLinkerMakeExternalLink(
			$url, $text, $link, $attribs, $linktype );
		if ( !$success ) {
			wfDebug( "Hook LinkerMakeExternalLink changed the output of link "
				. "with url {$url} and text {$text} to {$link}" );
			return $link;
		}
		$attribs['href'] = $url;
		return Html::rawElement( 'a', $attribs, $text );
	}

	/**
	 * Return the HTML for the top of a redirect page
	 *
	 * Chances are you should just be using the ParserOutput from
	 * WikitextContent::getParserOutput (which will have this header added
	 * automatically) instead of calling this for redirects.
	 *
	 * If creating your own redirect-alike, please use return value of
	 * this method to set the 'core:redirect-header' extension data field
	 * in your ParserOutput, rather than concatenating HTML directly.
	 * See WikitextContentHandler::fillParserOutput().
	 *
	 * @since 1.41
	 * @param Language $lang
	 * @param Title $target Destination to redirect
	 * @param bool $forceKnown Should the image be shown as a bluelink regardless of existence?
	 * @param bool $addLinkTag Should a <link> tag be added?
	 * @return string Containing HTML with redirect link
	 */
	public function makeRedirectHeader(
		Language $lang, Title $target,
		bool $forceKnown = false, bool $addLinkTag = false
	) {
		$html = '<ul class="redirectText">';
		if ( $forceKnown ) {
			$link = $this->makeKnownLink(
				$target,
				$target->getFullText(),
				[],
				// Make sure wiki page redirects are not followed
				$target->isRedirect() ? [ 'redirect' => 'no' ] : []
			);
		} else {
			$link = $this->makeLink(
				$target,
				$target->getFullText(),
				[],
				// Make sure wiki page redirects are not followed
				$target->isRedirect() ? [ 'redirect' => 'no' ] : []
			);
		}

		$redirectToText = wfMessage( 'redirectto' )->inLanguage( $lang )->escaped();
		$linkTag = '';
		if ( $addLinkTag ) {
			$linkTag = Html::rawElement( 'link', [
				'rel' => 'mw:PageProp/redirect',
				'href' => $this->getLinkURL( $this->normalizeTarget( $target ) ),
			] );
		}

		return Html::rawElement(
			'div', [ 'class' => 'redirectMsg' ],
			Html::rawElement( 'p', [], $redirectToText ) .
			Html::rawElement( 'ul', [ 'class' => 'redirectText' ],
				Html::rawElement( 'li', [], $link ) )
		) . $linkTag;
	}

	/**
	 * Builds the final <a> element
	 *
	 * @param LinkTarget|PageReference $target Page that will be visited when the user clicks on the link.
	 * @param-taint $target none
	 * @param string|HtmlArmor $text
	 * @param-taint $text escapes_html
	 * @param array $attribs
	 * @param-taint $attribs none
	 * @param bool $isKnown
	 * @param-taint $isKnown none
	 * @return null|string
	 * @return-taint escaped
	 */
	private function buildAElement( $target, $text, array $attribs, $isKnown ) {
		$ret = null;
		if ( !$this->hookRunner->onHtmlPageLinkRendererEnd(
			// @phan-suppress-next-line PhanTypeMismatchArgument Type mismatch on pass-by-ref args
			$this, $this->castToLinkTarget( $target ), $isKnown, $text, $attribs, $ret )
		) {
			return $ret;
		}

		return Html::rawElement( 'a', $attribs, HtmlArmor::getHtml( $text ) );
	}

	/**
	 * @param LinkTarget|PageReference $target Page that will be visited when the user clicks on the link.
	 * @return string
	 */
	private function getLinkText( $target ) {
		$prefixedText = $this->titleFormatter->getPrefixedText( $target );
		// If the target is just a fragment, with no title, we return the fragment
		// text.  Otherwise, we return the title text itself.
		if ( $prefixedText === '' && $target instanceof LinkTarget && $target->hasFragment() ) {
			return $target->getFragment();
		}

		return $prefixedText;
	}

	/**
	 * @param LinkTarget|PageReference $target Page that will be visited when the user clicks on the link.
	 * @param array $query Parameters you would like to add to the URL. For example, to
	 * add `?redirect=no&debug=true`, you would pass `[ 'redirect' => 'no', 'debug' => 'true' ]`
	 * @return string non-escaped text
	 */
	private function getLinkURL( $target, $query = [] ) {
		if ( $this->forceArticlePath ) {
			$realQuery = $query;
			$query = [];
		} else {
			$realQuery = [];
		}
		$url = $this->castToTitle( $target )->getLinkURL( $query, false, $this->expandUrls );

		if ( $this->forceArticlePath && $realQuery ) {
			$url = wfAppendQuery( $url, $realQuery );
		}

		return $url;
	}

	/**
	 * Normalizes the provided target
	 *
	 * @internal For use by Linker::getImageLinkMTOParams()
	 * @param LinkTarget|PageReference $target Page that will be visited when the user clicks on the link.
	 * @return MWLinkTarget
	 */
	public function normalizeTarget( $target ): MWLinkTarget {
		$target = $this->castToLinkTarget( $target );
		if ( $target->getNamespace() === NS_SPECIAL && !$target->isExternal() ) {
			[ $name, $subpage ] = $this->specialPageFactory->resolveAlias(
				$target->getDBkey()
			);
			if ( $name ) {
				return new TitleValue(
					NS_SPECIAL,
					$this->specialPageFactory->getLocalNameFor( $name, $subpage ),
					$target->getFragment()
				);
			}
		}

		return $target;
	}

	/**
	 * Returns CSS classes to add to a known link.
	 *
	 * Note that most CSS classes set on wikilinks are actually handled elsewhere (e.g. in
	 * makeKnownLink() or in LinkHolderArray).
	 *
	 * @param LinkTarget|PageReference $target Page that will be visited when the user clicks on the link.
	 * @return string CSS class
	 */
	public function getLinkClasses( $target ) {
		Assert::parameterType( [ LinkTarget::class, PageReference::class ], $target, '$target' );
		$target = $this->castToLinkTarget( $target );
		// Don't call LinkCache if the target is "non-proper"
		if ( $target->isExternal() || $target->getText() === '' || $target->getNamespace() < 0 ) {
			return '';
		}
		// Make sure the target is in the cache
		$id = $this->linkCache->addLinkObj( $target );
		if ( $id == 0 ) {
			// Doesn't exist
			return '';
		}

		if ( $this->linkCache->getGoodLinkFieldObj( $target, 'redirect' ) ) {
			# Page is a redirect
			return 'mw-redirect';
		}

		return '';
	}

	/**
	 * @param LinkTarget|PageReference $target Page that will be visited when the user clicks on the link.
	 * @return Title
	 */
	private function castToTitle( $target ): Title {
		if ( $target instanceof LinkTarget ) {
			return Title::newFromLinkTarget( $target );
		}
		// $target instanceof PageReference
		return Title::newFromPageReference( $target );
	}

	/**
	 * @param LinkTarget|PageReference $target Page that will be visited when the user clicks on the link.
	 * @return MWLinkTarget
	 */
	private function castToLinkTarget( $target ): MWLinkTarget {
		if ( $target instanceof PageReference ) {
			return Title::newFromPageReference( $target );
		}
		if ( $target instanceof MWLinkTarget ) {
			return $target;
		}
		return TitleValue::newFromLinkTarget( $target );
	}
}
