<?php

namespace MediaWiki\Linker;

class HtmlPageLinkRenderer {

	/**
	 * Whether to force the pretty article path
	 *
	 * @var bool
	 */
	private $forceArticlePath;

	/**
	 * A PROTO_* constant or false
	 *
	 * @var string|bool|int
	 */
	private $expandUrls;

	/**
	 * Whether extra classes should be added
	 *
	 * @var bool
	 */
	private $noClasses;

	/**
	 * @var int
	 */
	private $stubThreshold;

	/**
	 * @var array
	 */
	private $attribs;

	/**
	 * @param array $options
	 */
	public function __construct( array $options = [] ) {
		// Set defaults
		$options += [
			'forcearticlepath' => false,
			'expandurls' => false,
			'noclasses' => false,
			'attribs' => [],
		];

		if ( !isset( $options['stubThreshold'] ) ) {
			// FIXME: This should not be here
			global $wgUser;
			$options['stubThreshold'] = $wgUser->getStubThreshold();
		}

		$this->forceArticlePath = $options['forcearticlepath'];
		$this->expandUrls = $options['expandurls'];
		$this->noClasses = $options['noclasses'];
		$this->stubThreshold = $options['stubThreshold'];
		$this->attribs = $options['attribs'];
	}

	/**
	 * @param LinkTarget $target
	 * @param string|null $html
	 * @param array $query
	 * @return string
	 */
	public function makeLink( LinkTarget $target, $html = null, array $query = [] ) {
		$title = \Title::newFromLinkTarget( $target );
		if ( $title->isKnown() ) {
			return $this->makeKnownLink( $target, $html, $query );
		} else {
			return $this->makeBrokenLink( $target, $html, $query );
		}
	}

	/**
	 * @param LinkTarget $target
	 * @param string|null $html
	 * @param array $query
	 * @return string
	 */
	public function makeKnownLink( LinkTarget $target, $html = null, array $query = [] ) {
		$target = $this->normalizeTarget( $target );
		$url = $this->getLinkURL( $target, $query );
		$attribs = [];
		if ( !$this->noClasses ) {
			$classes = [];
			$title = \Title::newFromLinkTarget( $target );
			if ( $title->isExternal() ) {
				$classes[] = 'extiw';
			}
			$colour = \Linker::getLinkColour( $title, $this->stubThreshold );
			if ( $colour !== '' ) {
				$classes[] = $colour;
			}
			if ( $classes ) {
				$attribs['class'] = implode( ' ', $classes );
			}
		}

		$titleFormatter = $this->getTitleFormatter();
		$prefixedText = $titleFormatter->getPrefixedText( $target );
		if ( $prefixedText !== '' ) {
			$attribs['title'] = $prefixedText;
		}

		$attribs = [
			'href' => $url,
		] + $this->mergeAttribs( $attribs, $this->attribs );

		if ( $html === null ) {
			$html = $this->getLinkText( $target );
		}

		return \Html::rawElement( 'a', $attribs, $html );
	}

	/**
	 * @param LinkTarget $target
	 * @param string|null $html
	 * @param array $query
	 * @return string
	 */
	public function makeBrokenLink( LinkTarget $target, $html = null, array $query = [] ) {
		# We don't want to include fragments for broken links, because they
		# generally make no sense.
		$target = $target->createFragmentTarget( '' );
		$target = $this->normalizeTarget( $target );

		if ( !isset( $query['action'] ) && $target->getNamespace() !== NS_SPECIAL ) {
			$query['action'] = 'edit';
			$query['redlink'] = '1';
		}

		$url = $this->getLinkURL( $target, $query );
		$attribs = $this->noClasses ? [] : [ 'class' => 'new' ];
		$titleFormatter = $this->getTitleFormatter();
		$prefixedText = $titleFormatter->getPrefixedText( $target );
		if ( $prefixedText !== '' ) {
			// This ends up in parser cache!
			$attribs['title'] = wfMessage( 'red-link-title', $prefixedText )
				->inContentLanguage()
				->text();
		}

		$attribs = [
			'href' => $url,
		] + $this->mergeAttribs( $attribs, $this->attribs );

		if ( $html === null ) {
			$html = $this->getLinkText( $target );
		}

		return \Html::rawElement( 'a', $attribs, $html );
	}

	/**
	 * @param LinkTarget $target
	 * @return string Already-escaped HTML
	 */
	private function getLinkText( LinkTarget $target ) {
		$prefixedText = $this->getTitleFormatter()->getPrefixedText( $target );
		// If the target is just a fragment, with no title, we return the fragment
		// text.  Otherwise, we return the title text itself.
		if ( $prefixedText === '' && $target->hasFragment() ) {
			return htmlspecialchars( $target->getFragment() );
		}

		return htmlspecialchars( $prefixedText );
	}

	private function getLinkURL( LinkTarget $target, array $query = [] ) {
		$title = \Title::newFromLinkTarget( $target );
		$proto = $this->expandUrls !== false
			? $this->expandUrls
			: PROTO_RELATIVE;
		if ( $this->forceArticlePath ) {
			$realQuery = $query;
			$query = [];
		} else {
			$realQuery = [];
		}
		$url = $title->getLinkURL( $query, false, $proto );

		if ( $this->forceArticlePath && $realQuery ) {
			$url = wfAppendQuery( $url, $realQuery );
		}

		return $url;
	}

	/**
	 * Normalizes the provided target
	 *
	 * @todo move the code from Linker actually here
	 * @param LinkTarget $target
	 * @return LinkTarget
	 */
	private function normalizeTarget( LinkTarget $target ) {
		return \Linker::normaliseSpecialPage( $target );
	}

	/**
	 * @todo inject this somehow
	 * @return \TitleFormatter
	 */
	private function getTitleFormatter() {
		static $formatter = null;
		if ( $formatter === null ) {
			global $wgContLang, $wgLocalInterwikis;
			$formatter = new \MediaWikiTitleCodec(
				$wgContLang,
				\GenderCache::singleton(),
				$wgLocalInterwikis
			);
		}

		return $formatter;
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
		$merged = \Sanitizer::mergeAttributes( $defaults, $attribs );
		foreach ( $merged as $key => $val ) {
			# A false value suppresses the attribute
			if ( $val !== false ) {
				$ret[$key] = $val;
			}
		}
		return $ret;
	}

}
