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
 * @author Kunal Mehta <legoktm@member.fsf.org>
 */
namespace MediaWiki\Linker;

use Html;
use HtmlArmor;
use LinkCache;
use MediaWiki\HookContainer\HookContainer;
use MediaWiki\HookContainer\HookRunner;
use MediaWiki\SpecialPage\SpecialPageFactory;
use NamespaceInfo;
use Sanitizer;
use Title;
use TitleFormatter;
use TitleValue;

/**
 * Class that generates HTML <a> links for pages.
 *
 * @see https://www.mediawiki.org/wiki/Manual:LinkRenderer
 * @since 1.28
 */
class LinkRenderer {

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
	 * @var int
	 */
	private $stubThreshold = 0;

	/**
	 * @var TitleFormatter
	 */
	private $titleFormatter;

	/**
	 * @var LinkCache
	 */
	private $linkCache;

	/**
	 * @var NamespaceInfo
	 */
	private $nsInfo;

	/** @var HookContainer */
	private $hookContainer;

	/** @var HookRunner */
	private $hookRunner;

	/**
	 * @var SpecialPageFactory
	 */
	private $specialPageFactory;

	/**
	 * @internal For use by LinkRendererFactory
	 * @param TitleFormatter $titleFormatter
	 * @param LinkCache $linkCache
	 * @param NamespaceInfo $nsInfo
	 * @param SpecialPageFactory $specialPageFactory
	 * @param HookContainer $hookContainer
	 */
	public function __construct(
		TitleFormatter $titleFormatter,
		LinkCache $linkCache,
		NamespaceInfo $nsInfo,
		SpecialPageFactory $specialPageFactory,
		HookContainer $hookContainer
	) {
		$this->titleFormatter = $titleFormatter;
		$this->linkCache = $linkCache;
		$this->nsInfo = $nsInfo;
		$this->specialPageFactory = $specialPageFactory;
		$this->hookContainer = $hookContainer;
		$this->hookRunner = new HookRunner( $hookContainer );
	}

	/**
	 * @param bool $force
	 */
	public function setForceArticlePath( $force ) {
		$this->forceArticlePath = $force;
	}

	/**
	 * @return bool
	 */
	public function getForceArticlePath() {
		return $this->forceArticlePath;
	}

	/**
	 * @param string|bool|int $expand A PROTO_* constant or false
	 */
	public function setExpandURLs( $expand ) {
		$this->expandUrls = $expand;
	}

	/**
	 * @return string|bool|int a PROTO_* constant or false
	 */
	public function getExpandURLs() {
		return $this->expandUrls;
	}

	/**
	 * @param int $threshold
	 */
	public function setStubThreshold( $threshold ) {
		$this->stubThreshold = $threshold;
	}

	/**
	 * @return int
	 */
	public function getStubThreshold() {
		return $this->stubThreshold;
	}

	/**
	 * @param LinkTarget $target
	 * @param string|HtmlArmor|null $text
	 * @param array $extraAttribs
	 * @param array $query
	 * @return string HTML
	 */
	public function makeLink(
		LinkTarget $target, $text = null, array $extraAttribs = [], array $query = []
	) {
		$title = Title::newFromLinkTarget( $target );
		if ( $title->isKnown() ) {
			return $this->makeKnownLink( $target, $text, $extraAttribs, $query );
		} else {
			return $this->makeBrokenLink( $target, $text, $extraAttribs, $query );
		}
	}

	private function runBeginHook( LinkTarget $target, &$text, &$extraAttribs, &$query, $isKnown ) {
		$ret = null;
		if ( !$this->hookRunner->onHtmlPageLinkRendererBegin(
			$this, $target, $text, $extraAttribs, $query, $ret )
		) {
			return $ret;
		}
	}

	/**
	 * If you have already looked up the proper CSS classes using LinkRenderer::getLinkClasses()
	 * or some other method, use this to avoid looking it up again.
	 *
	 * @param LinkTarget $target
	 * @param string|HtmlArmor|null $text
	 * @param string $classes CSS classes to add
	 * @param array $extraAttribs
	 * @param array $query
	 * @return string
	 */
	public function makePreloadedLink(
		LinkTarget $target, $text = null, $classes = '', array $extraAttribs = [], array $query = []
	) {
		// Run begin hook
		$ret = $this->runBeginHook( $target, $text, $extraAttribs, $query, true );
		if ( $ret !== null ) {
			return $ret;
		}
		$target = $this->normalizeTarget( $target );
		$url = $this->getLinkURL( $target, $query );
		$attribs = [ 'class' => $classes ];
		$prefixedText = $this->titleFormatter->getPrefixedText( $target );
		if ( $prefixedText !== '' ) {
			$attribs['title'] = $prefixedText;
		}

		$attribs = [
			'href' => $url,
		] + $this->mergeAttribs( $attribs, $extraAttribs );

		if ( $text === null ) {
			$text = $this->getLinkText( $target );
		}

		return $this->buildAElement( $target, $text, $attribs, true );
	}

	/**
	 * @param LinkTarget $target
	 * @param string|HtmlArmor|null $text
	 * @param array $extraAttribs
	 * @param array $query
	 * @return string HTML
	 */
	public function makeKnownLink(
		LinkTarget $target, $text = null, array $extraAttribs = [], array $query = []
	) {
		$classes = [];
		if ( $target->isExternal() ) {
			$classes[] = 'extiw';
		}
		$colour = $this->getLinkClasses( $target );
		if ( $colour !== '' ) {
			$classes[] = $colour;
		}

		return $this->makePreloadedLink(
			$target,
			$text,
			implode( ' ', $classes ),
			$extraAttribs,
			$query
		);
	}

	/**
	 * @param LinkTarget $target
	 * @param-taint $target none
	 * @param string|HtmlArmor|null $text
	 * @param array $extraAttribs
	 * @param array $query
	 * @return string
	 */
	public function makeBrokenLink(
		LinkTarget $target, $text = null, array $extraAttribs = [], array $query = []
	) {
		// Run legacy hook
		$ret = $this->runBeginHook( $target, $text, $extraAttribs, $query, false );
		if ( $ret !== null ) {
			return $ret;
		}

		# We don't want to include fragments for broken links, because they
		# generally make no sense.
		if ( $target->hasFragment() ) {
			$target = $target->createFragmentTarget( '' );
		}
		$target = $this->normalizeTarget( $target );

		if ( !isset( $query['action'] ) && $target->getNamespace() !== NS_SPECIAL ) {
			$query['action'] = 'edit';
			$query['redlink'] = '1';
		}

		$url = $this->getLinkURL( $target, $query );
		$attribs = [ 'class' => 'new' ];
		$prefixedText = $this->titleFormatter->getPrefixedText( $target );
		if ( $prefixedText !== '' ) {
			// This ends up in parser cache!
			$attribs['title'] = wfMessage( 'red-link-title', $prefixedText )
				->inContentLanguage()
				->text();
		}

		$attribs = [
			'href' => $url,
		] + $this->mergeAttribs( $attribs, $extraAttribs );

		if ( $text === null ) {
			$text = $this->getLinkText( $target );
		}

		return $this->buildAElement( $target, $text, $attribs, false );
	}

	/**
	 * Builds the final <a> element
	 *
	 * @param LinkTarget $target
	 * @param string|HtmlArmor $text
	 * @param array $attribs
	 * @param bool $isKnown
	 * @return null|string
	 */
	private function buildAElement( LinkTarget $target, $text, array $attribs, $isKnown ) {
		$ret = null;
		if ( !$this->hookRunner->onHtmlPageLinkRendererEnd(
			$this, $target, $isKnown, $text, $attribs, $ret )
		) {
			return $ret;
		}

		return Html::rawElement( 'a', $attribs, HtmlArmor::getHtml( $text ) );
	}

	/**
	 * @param LinkTarget $target
	 * @return string non-escaped text
	 */
	private function getLinkText( LinkTarget $target ) {
		$prefixedText = $this->titleFormatter->getPrefixedText( $target );
		// If the target is just a fragment, with no title, we return the fragment
		// text.  Otherwise, we return the title text itself.
		if ( $prefixedText === '' && $target->hasFragment() ) {
			return $target->getFragment();
		}

		return $prefixedText;
	}

	private function getLinkURL( LinkTarget $target, array $query = [] ) {
		// TODO: Use a LinkTargetResolver service instead of Title
		$title = Title::newFromLinkTarget( $target );
		if ( $this->forceArticlePath ) {
			$realQuery = $query;
			$query = [];
		} else {
			$realQuery = [];
		}
		$url = $title->getLinkURL( $query, false, $this->expandUrls );

		if ( $this->forceArticlePath && $realQuery ) {
			$url = wfAppendQuery( $url, $realQuery );
		}

		return $url;
	}

	/**
	 * Normalizes the provided target
	 *
	 * @internal For use by deprecated Linker & DummyLinker
	 *     ::normaliseSpecialPage() methods
	 * @param LinkTarget $target
	 * @return LinkTarget
	 */
	public function normalizeTarget( LinkTarget $target ) {
		if ( $target->getNamespace() === NS_SPECIAL && !$target->isExternal() ) {
			list( $name, $subpage ) = $this->specialPageFactory->resolveAlias(
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
	 * Merges two sets of attributes
	 *
	 * @param array $defaults
	 * @param array $attribs
	 *
	 * @return array
	 */
	private function mergeAttribs( $defaults, $attribs ) {
		if ( !$attribs ) {
			return $defaults;
		}
		# Merge the custom attribs with the default ones, and iterate
		# over that, deleting all "false" attributes.
		$ret = [];
		$merged = Sanitizer::mergeAttributes( $defaults, $attribs );
		foreach ( $merged as $key => $val ) {
			# A false value suppresses the attribute
			if ( $val !== false ) {
				$ret[$key] = $val;
			}
		}
		return $ret;
	}

	/**
	 * Return the CSS classes of a known link
	 *
	 * @param LinkTarget $target
	 * @return string CSS class
	 */
	public function getLinkClasses( LinkTarget $target ) {
		// Make sure the target is in the cache
		$id = $this->linkCache->addLinkObj( $target );
		if ( $id == 0 ) {
			// Doesn't exist
			return '';
		}

		if ( $this->linkCache->getGoodLinkFieldObj( $target, 'redirect' ) ) {
			# Page is a redirect
			return 'mw-redirect';
		} elseif (
			$this->stubThreshold > 0 && $this->nsInfo->isContent( $target->getNamespace() ) &&
			$this->linkCache->getGoodLinkFieldObj( $target, 'length' ) < $this->stubThreshold
		) {
			# Page is a stub
			return 'stub';
		}

		return '';
	}
}
