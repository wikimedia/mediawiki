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
use MediaWiki\SpecialPage\SpecialPageFactory;
use MediaWiki\Title\TitleFormatter;

/**
 * Factory to create LinkRender objects
 * @since 1.28
 */
class LinkRendererFactory {

	/**
	 * @var TitleFormatter
	 */
	private $titleFormatter;

	/**
	 * @var LinkCache
	 */
	private $linkCache;

	/**
	 * @var HookContainer
	 */
	private $hookContainer;

	/**
	 * @var SpecialPageFactory
	 */
	private $specialPageFactory;

	/**
	 * @internal For use by core ServiceWiring
	 * @param TitleFormatter $titleFormatter
	 * @param LinkCache $linkCache
	 * @param SpecialPageFactory $specialPageFactory
	 * @param HookContainer $hookContainer
	 */
	public function __construct(
		TitleFormatter $titleFormatter,
		LinkCache $linkCache,
		SpecialPageFactory $specialPageFactory,
		HookContainer $hookContainer
	) {
		$this->titleFormatter = $titleFormatter;
		$this->linkCache = $linkCache;
		$this->specialPageFactory = $specialPageFactory;
		$this->hookContainer = $hookContainer;
	}

	/**
	 * @param array $options optional flags for rendering
	 *  - 'renderForComment': set to true if the created LinkRenderer will be used for
	 *    links in an edit summary or log comments. An instance with renderForComment
	 *    enabled must not be used for other links.
	 *
	 * @return LinkRenderer
	 */
	public function create( array $options = [ 'renderForComment' => false ] ) {
		return new LinkRenderer(
			$this->titleFormatter, $this->linkCache, $this->specialPageFactory,
			$this->hookContainer,
			new ServiceOptions( LinkRenderer::CONSTRUCTOR_OPTIONS, $options )
		);
	}

	/**
	 * @param array $options
	 * @return LinkRenderer
	 */
	public function createFromLegacyOptions( array $options ) {
		$linkRenderer = $this->create();

		if ( in_array( 'forcearticlepath', $options, true ) ) {
			$linkRenderer->setForceArticlePath( true );
		}

		if ( in_array( 'http', $options, true ) ) {
			$linkRenderer->setExpandURLs( PROTO_HTTP );
		} elseif ( in_array( 'https', $options, true ) ) {
			$linkRenderer->setExpandURLs( PROTO_HTTPS );
		}

		return $linkRenderer;
	}
}
