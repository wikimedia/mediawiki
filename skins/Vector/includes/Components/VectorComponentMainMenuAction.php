<?php
namespace MediaWiki\Skins\Vector\Components;

use Skin;

/**
 * VectorComponentMainMenuAction component
 */
class VectorComponentMainMenuAction implements VectorComponent {
	/** @var Skin */
	private $skin;
	/** @var array */
	private $htmlData;
	/** @var array */
	private $headingOptions;
	/** @var string */
	private $actionName;

	/**
	 * @param string $actionName identifier for the action. Used to add class
	 * @param Skin $skin
	 * @param array $htmlData data to make a link or raw html
	 * @param array $headingOptions optional heading for the html
	 */
	public function __construct( string $actionName, Skin $skin, array $htmlData, array $headingOptions ) {
		$this->skin = $skin;
		$this->htmlData = $htmlData;
		$this->headingOptions = $headingOptions;
		$this->actionName = $actionName;
	}

	/**
	 * Generate data needed to create MainMenuAction item.
	 * @param array $htmlData data to make a link or raw html
	 * @param array $headingOptions optional heading for the html
	 * @return array keyed data for the MainMenuAction template
	 */
	private function makeMainMenuActionData( array $htmlData = [], array $headingOptions = [] ): array {
		$skin = $this->skin;
		$htmlContent = '';
		// Populates the main menu as a standalone link or custom html.
		if ( array_key_exists( 'link', $htmlData ) ) {
			$htmlContent = $skin->makeLink( 'link', $htmlData['link'] );
		} elseif ( array_key_exists( 'html-content', $htmlData ) ) {
			$htmlContent = $htmlData['html-content'];
		}

		return $headingOptions + [
			'action' => $this->actionName,
			'html-content' => $htmlContent,
		];
	}

	/**
	 * @inheritDoc
	 */
	public function getTemplateData(): array {
		return $this->makeMainMenuActionData( $this->htmlData, $this->headingOptions );
	}
}
