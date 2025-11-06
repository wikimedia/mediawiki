<?php

namespace MediaWiki\Skin;

use MediaWiki\Html\Html;
use MediaWiki\Linker\Linker;
use MediaWiki\Message\Message;
use MediaWiki\Parser\Sanitizer;
use MessageLocalizer;

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

	/** @var string */
	private $htmlAfterContent;

	/** @var string */
	private $htmlBeforeContent;

	/**
	 * @param string $name
	 * @param array $items
	 * @param MessageLocalizer $localizer
	 * @param string $content
	 * @param array $linkOptions
	 * @param string $htmlAfterContent
	 * @param string $htmlBeforeContent
	 */
	public function __construct(
		string $name,
		array $items,
		MessageLocalizer $localizer,
		string $content = '',
		array $linkOptions = [],
		string $htmlAfterContent = '',
		string $htmlBeforeContent = ''
	) {
		$this->name = $name;
		$this->items = $items;
		$this->localizer = $localizer;
		$this->content = $content;
		$this->linkOptions = $linkOptions;
		$this->htmlAfterContent = $htmlAfterContent;
		$this->htmlBeforeContent = $htmlBeforeContent;
	}

	private function msg( string $key ): Message {
		return $this->localizer->msg( $key );
	}

	/**
	 * @param string $name of the menu e.g. p-personal the name is personal.
	 *
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

		if ( str_starts_with( $name, 'footer' ) ) {
			// Retain footer IDs.
			$id = $name;
		} else {
			$id = Sanitizer::escapeIdForAttribute( "p-$name" );
		}

		$isEmptyContent = !$this->content;
		$isEmptyAfterContent = !$this->htmlAfterContent;
		$isEmptyBeforeContent = !$this->htmlBeforeContent;
		$isEmptyItems = count( $this->items ) === 0;
		$isEmptyPortlet = ( $isEmptyContent && $isEmptyAfterContent && $isEmptyBeforeContent && $isEmptyItems );
		$data = [
			'id' => $id,
			// Any changes to these classes should be synced with resources/src/mediawiki.util/util.js
			'class' => 'mw-portlet ' . Sanitizer::escapeClass( "mw-portlet-$name" ),
			'html-tooltip' => Linker::tooltip( $id ),
			'html-items' => '',
			// Will be populated by SkinAfterPortlet hook.
			'html-after-portal' => '',
			'html-before-portal' => '',
			'is-empty' => $isEmptyPortlet,
		];

		// for output. In production this currently supports the Wikibase 'edit' link.
		if ( !$isEmptyAfterContent ) {
			$data['html-after-portal'] = Html::rawElement(
				'div',
				[
					'class' => [
						'after-portlet',
						Sanitizer::escapeClass( "after-portlet-$name" ),
					],
				],
				$this->htmlAfterContent
			);
		}

		if ( !$isEmptyBeforeContent ) {
			$data['html-before-portal'] = Html::rawElement(
				'div',
				[
					'class' => [
						'before-portlet',
						Sanitizer::escapeClass( "before-portlet-$name" ),
					],
				],
				$this->htmlBeforeContent
			);
		}

		$html = '';
		$arrayItems = [];
		foreach ( $this->items as $key => $item ) {
			$item = new SkinComponentListItem( $key, $item, $this->localizer, [], $this->linkOptions );
			$itemData = $item->getTemplateData();
			$html .= $itemData['html-item'];
			$arrayItems[] = $itemData;
		}
		$data['html-items'] = $html;
		$data['array-items'] = $arrayItems;

		$data['label'] = $this->getMenuLabel( $name );
		$data['class'] .= $isEmptyPortlet ? ' emptyPortlet' : '';
		return $data;
	}
}
