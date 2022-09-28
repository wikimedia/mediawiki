<?php

namespace MediaWiki\Skin;

use Html;
use Linker;
use Message;
use MessageLocalizer;
use Sanitizer;

/**
 * @internal for use inside Skin and SkinTemplate classes only
 * @unstable
 */
class SkinComponentMenu implements SkinComponent {
	/** @var string */
	private $name;
	/** @var array */
	private $items;
	/** @var MessageLocalizer */
	private $localizer;
	/** @var string */
	private $content;
	/** @var array */
	private $linkOptions;

	/**
	 * @param string $name
	 * @param array $items
	 * @param MessageLocalizer $localizer
	 * @param string $content Shown at end of portlet.
	 * @param array $linkOptions
	 */
	public function __construct(
		string $name,
		array $items,
		MessageLocalizer $localizer,
		string $content = '',
		array $linkOptions = []
	) {
		$this->name = $name;
		$this->items = $items;
		$this->localizer = $localizer;
		$this->content = $content;
		$this->linkOptions = $linkOptions;
	}

	/**
	 * @param string $key
	 * @return Message
	 */
	private function msg( string $key ): Message {
		return $this->localizer->msg( $key );
	}

	/**
	 * @param string $name of the menu e.g. p-personal the name is personal.
	 * @return string that is human-readable corresponding to the menu.
	 */
	private function getMenuLabel( $name ) {
		// For historic reasons for some menu items, there is no language key corresponding
		// with its menu key.
		$mappings = [
			'tb' => 'toolbox',
			'personal' => 'personaltools',
			'lang' => 'otherlanguages',
		];
		$msgObj = $this->msg( $mappings[ $name ] ?? $name );
		// If no message exists fallback to plain text (T252727)
		return $msgObj->exists() ? $msgObj->text() : $name;
	}

	/**
	 * @inheritDoc
	 */
	public function getTemplateData(): array {
		$name = $this->name;
		$items = $this->items;
		// Monobook and Vector historically render this portal as an element with ID p-cactions.
		// To ensure compatibility with gadgets, it is renamed accordingly.
		// @todo Port p-#cactions to #p-actions and drop these conditionals.
		if ( $name === 'actions' ) {
			$name = 'cactions';
		}

		// The new personal tools without the notifications is user-menu.
		// A lot of user code and gadgets relies on it being named personal.
		// This allows it to function as a drop-in replacement.
		if ( $name === 'user-menu' ) {
			$name = 'personal';
		}

		if ( strpos( $name, 'footer' ) === 0 ) {
			// Retain footer IDs.
			$id = $name;
		} else {
			$id = Sanitizer::escapeIdForAttribute( "p-$name" );
		}

		$data = [
			'id' => $id,
			'class' => 'mw-portlet ' . Sanitizer::escapeClass( "mw-portlet-$name" ),
			'html-tooltip' => Linker::tooltip( $id ),
			'html-items' => '',
			// Will be populated by SkinAfterPortlet hook.
			'html-after-portal' => '',
			'html-before-portal' => '',
		];
		// Run the SkinAfterPortlet hook and if content is added, append it to the html-after-portal
		// for output. In production this currently supports the Wikibase 'edit' link.
		$content = $this->content;
		if ( $content !== '' ) {
			$data['html-after-portal'] = Html::rawElement(
				'div',
				[
					'class' => [
						'after-portlet',
						Sanitizer::escapeClass( "after-portlet-$name" ),
					],
				],
				$content
			);
		}

		$html = '';
		$arrayItems = [];
		foreach ( $items as $key => $item ) {
			$item = new SkinComponentListItem( $key, $item, $this->localizer, [], $this->linkOptions );
			$itemData = $item->getTemplateData();
			$html .= $itemData['html-item'];
			$arrayItems[] = $itemData;
		}
		$data['html-items'] = $html;
		$data['array-items'] = $arrayItems;

		$data['label'] = $this->getMenuLabel( $name );
		$data['class'] .= ( count( $items ) === 0 && $content === '' )
			? ' emptyPortlet' : '';
		return $data;
	}
}
