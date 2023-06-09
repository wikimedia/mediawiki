<?php
namespace MediaWiki\Skins\Vector\Components;

use Config;
use MediaWiki\Skins\Vector\Constants;
use MediaWiki\Skins\Vector\FeatureManagement\FeatureManager;
use MessageLocalizer;

/**
 * VectorComponentTableOfContents component
 */
class VectorComponentTableOfContents implements VectorComponent {

	/** @var array */
	private $tocData;

	/** @var MessageLocalizer */
	private $localizer;

	/** @var bool */
	private $isPinned;

	/** @var Config */
	private $config;

	/** @var VectorComponentPinnableHeader */
	private $pinnableHeader;

	/** @var string */
	public const ID = 'vector-toc';

	/**
	 * @param array $tocData
	 * @param MessageLocalizer $localizer
	 * @param Config $config
	 * @param FeatureManager $featureManager
	 */
	public function __construct(
		array $tocData,
		MessageLocalizer $localizer,
		Config $config,
		FeatureManager $featureManager
	) {
		$this->tocData = $tocData;
		$this->localizer = $localizer;
		$this->isPinned = $featureManager->isFeatureEnabled( Constants::FEATURE_TOC_PINNED );
		$this->config = $config;
		$this->pinnableHeader = new VectorComponentPinnableHeader(
			$this->localizer,
			$this->isPinned,
			self::ID,
			'toc-pinned',
			false,
			'h2'
		);
	}

	/**
	 * @return bool
	 */
	public function isPinned(): bool {
		return $this->isPinned;
	}

	/**
	 * In tableOfContents.js we have tableOfContents::getTableOfContentsSectionsData(),
	 * that yields the same result as this function, please make sure to keep them in sync.
	 * @inheritDoc
	 */
	public function getTemplateData(): array {
		$sections = $this->tocData[ 'array-sections' ] ?? [];
		if ( empty( $sections ) ) {
			return [];
		}
		// Populate button labels for collapsible TOC sections
		foreach ( $sections as &$section ) {
			if ( $section['is-top-level-section'] && $section['is-parent-section'] ) {
				$section['vector-button-label'] =
					$this->localizer->msg( 'vector-toc-toggle-button-label', $section['line'] )->text();
			}
		}
		$this->tocData[ 'array-sections' ] = $sections;

		$pinnableElement = new VectorComponentPinnableElement( self::ID );

		return $pinnableElement->getTemplateData() +
			array_merge( $this->tocData, [
			'vector-is-collapse-sections-enabled' =>
				count( $this->tocData['array-sections'] ) > 3 &&
				$this->tocData[ 'number-section-count'] >= $this->config->get(
					'VectorTableOfContentsCollapseAtCount'
				),
			'data-pinnable-header' => $this->pinnableHeader->getTemplateData(),
		] );
	}
}
