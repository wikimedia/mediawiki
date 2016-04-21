<?php

namespace MediaWiki\Linker;

class HtmlPageLinkRenderer {

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
	 * Whether extra classes should be added
	 *
	 * @var bool
	 */
	private $noClasses = false;

	/**
	 * @var int
	 */
	private $stubThreshold = 0;

	/**
	 * @var array
	 */
	private $attribs = [];

	/**
	 * @var \TitleFormatter
	 */
	private $titleFormatter;

	/**
	 * @param \TitleFormatter $titleFormatter
	 */
	public function __construct( \TitleFormatter $titleFormatter ) {
		$this->titleFormatter = $titleFormatter;
	}

	/**
	 * @param bool $force
	 */
	public function setForceArticlePath( $force ) {
		$this->forceArticlePath = $force;
	}

	/**
	 * @param string|bool|int $expand A PROTO_* constant or false
	 */
	public function setExpandURLs( $expand ) {
		$this->expandUrls = $expand;
	}

	/**
	 * @param bool $no
	 */
	public function setNoClasses( $no ) {
		$this->noClasses = $no;
	}

	/**
	 * @param int $threshold
	 */
	public function setStubThreshold( $threshold ) {
		$this->stubThreshold = $threshold;
	}

	/**
	 * @param array $attribs
	 */
	public function setAttribs( array $attribs ) {
		$this->attribs = $attribs;
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
			if ( $target->isExternal() ) {
				$classes[] = 'extiw';
			}
			$title = \Title::newFromLinkTarget( $target );
			$colour = \Linker::getLinkColour( $title, $this->stubThreshold );
			if ( $colour !== '' ) {
				$classes[] = $colour;
			}
			if ( $classes ) {
				$attribs['class'] = implode( ' ', $classes );
			}
		}

		$prefixedText = $this->titleFormatter->getPrefixedText( $target );
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
		if ( $target->hasFragment() ) {
			$target = $target->createFragmentTarget( '' );
		}
		$target = $this->normalizeTarget( $target );

		if ( !isset( $query['action'] ) && $target->getNamespace() !== NS_SPECIAL ) {
			$query['action'] = 'edit';
			$query['redlink'] = '1';
		}

		$url = $this->getLinkURL( $target, $query );
		$attribs = $this->noClasses ? [] : [ 'class' => 'new' ];
		$prefixedText = $this->titleFormatter->getPrefixedText( $target );
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
		$prefixedText = $this->titleFormatter->getPrefixedText( $target );
		// If the target is just a fragment, with no title, we return the fragment
		// text.  Otherwise, we return the title text itself.
		if ( $prefixedText === '' && $target->hasFragment() ) {
			return htmlspecialchars( $target->getFragment() );
		}

		return htmlspecialchars( $prefixedText );
	}

	private function getLinkURL( LinkTarget $target, array $query = [] ) {
		// TODO: Use a LinkTargetResolver service instead of Title
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
