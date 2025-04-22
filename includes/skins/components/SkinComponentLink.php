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
 */

namespace MediaWiki\Skin;

use MediaWiki\Html\Html;
use MediaWiki\Linker\Linker;
use MediaWiki\Message\Message;
use MessageLocalizer;

/**
 * @internal for use inside Skin and SkinTemplate classes only
 * @unstable
 */
class SkinComponentLink implements SkinComponent {
	/** @var string */
	private $key;
	/** @var array */
	private $item;
	/** @var array */
	private $options;
	/** @var MessageLocalizer */
	private $localizer;

	/**
	 * @param string $key
	 * @param array $item
	 * @param MessageLocalizer $localizer
	 * @param array $options
	 */
	public function __construct( string $key, array $item, MessageLocalizer $localizer, array $options = [] ) {
		$this->key = $key;
		$this->item = $item;
		$this->localizer = $localizer;
		$this->options = $options;
	}

	private function msg( string $key ): Message {
		return $this->localizer->msg( $key );
	}

	/**
	 * Makes a link, usually used by makeListItem to generate a link for an item
	 * in a list used in navigation lists, portlets, portals, sidebars, etc...
	 *
	 * @param string $key Usually a key from the list you are generating this
	 * link from.
	 * @param array $item Contains some of a specific set of keys.
	 *
	 * The text of the link will be generated either from the contents of the
	 * "text" key in the $item array, if a "msg" key is present a message by
	 * that name will be used, and if neither of those are set the $key will be
	 * used as a message name. Escaping is handled by this method.
	 *
	 * If a "href" key is not present makeLink will just output htmlescaped text.
	 * The "href", "id", "class", "rel", and "type" keys are used as attributes
	 * for the link if present.
	 *
	 * If an "id" or "single-id" (if you don't want the actual id to be output
	 * on the link) is present it will be used to generate a tooltip and
	 * accesskey for the link.
	 *
	 * The 'link-html' key can be used to prepend additional HTML inside the link HTML.
	 * For example to prepend an icon.
	 *
	 * The keys "context" and "primary" are ignored; these keys are used
	 * internally by skins and are not supposed to be included in the HTML
	 * output.
	 *
	 * If you don't want an accesskey, set $item['tooltiponly'] = true;
	 *
	 * If a "data" key is present, it must be an array, where the keys represent
	 * the data-xxx properties with their provided values. For example,
	 *     $item['data'] = [
	 *       'foo' => 1,
	 *       'bar' => 'baz',
	 *     ];
	 * will render as element properties:
	 *     data-foo='1' data-bar='baz'
	 *
	 * The "class" key currently accepts both a string and an array of classes, but this will be
	 * changed to only accept an array in the future.
	 *
	 * @param array $options Can be used to affect the output of a link.
	 * Possible options are:
	 *   - 'class-as-property' key to specify that class attribute should be
	 *     not be included in array-attributes.
	 *   - 'text-wrapper' key to specify a list of elements to wrap the text of
	 *   a link in. This should be an array of arrays containing a 'tag' and
	 *   optionally an 'attributes' key. If you only have one element you don't
	 *   need to wrap it in another array. eg: To use <a><span>...</span></a>
	 *   in all links use [ 'text-wrapper' => [ 'tag' => 'span' ] ]
	 *   for your options.
	 *   - 'link-class' key can be used to specify additional classes to apply
	 *   to all links.
	 *   - 'link-fallback' can be used to specify a tag to use instead of "<a>"
	 *   if there is no link. eg: If you specify 'link-fallback' => 'span' than
	 *   any non-link will output a "<span>" instead of just text.
	 *
	 * @return array Associated array with the following keys:
	 * - html: HTML string
	 * - array-attributes: HTML attributes as array of objects:
	 * 		- key: Attribute name ex: 'href', 'class', 'id', ...
	 * 		- value: Attribute value
	 * 		NOTE: if options['class-as-property'] is set, class will not be included in the list.
	 * - text: Text of the link
	 * - class: Class of the link
	 */
	private function makeLink( $key, $item, $options = [] ) {
		$html = $item['html'] ?? null;
		$icon = $item['icon'] ?? null;
		if ( $html ) {
			return [
				'html' => $html
			];
		}
		$text = $item['text'] ?? $this->msg( $item['msg'] ?? $key )->text();

		$html = htmlspecialchars( $text );
		$isLink = isset( $item['href'] ) || isset( $options['link-fallback'] );

		if ( $html !== '' && isset( $options['text-wrapper'] ) ) {
			$wrapper = $options['text-wrapper'];
			if ( isset( $wrapper['tag'] ) ) {
				$wrapper = [ $wrapper ];
			}
			while ( count( $wrapper ) > 0 ) {
				$element = array_pop( $wrapper );
				'@phan-var array $element';

				$attrs = $element['attributes'] ?? [];
				// Apply title attribute to the outermost wrapper if there is
				// no link wrapper. No need for an accesskey.
				if ( count( $wrapper ) === 0 && !$isLink ) {
					$this->applyLinkTitleAttribs(
						$item,
						false,
						$attrs
					);
				}
				$html = Html::rawElement( $element['tag'], $attrs, $html );
			}
		}

		$attrs = [];
		$linkHtmlAttributes = [];
		$classAsProperty = $options['class-as-property'] ?? false;
		if ( $isLink ) {
			$attrs = $item;
			foreach ( [
				'single-id', 'text', 'msg', 'tooltiponly', 'context', 'primary',
				// These fields provide context for skins to modify classes.
				// They should not be outputted to skin.
				'icon', 'button',
				'tooltip-params', 'exists', 'link-html' ] as $k
			) {
				unset( $attrs[$k] );
			}

			if ( isset( $attrs['data'] ) ) {
				foreach ( $attrs['data'] as $key => $value ) {
					if ( $value === null ) {
						continue;
					}
					$attrs[ 'data-' . $key ] = $value;
				}
				unset( $attrs[ 'data' ] );
			}
			$this->applyLinkTitleAttribs( $item, true, $attrs );
			if ( isset( $options['link-class'] ) ) {
				Html::addClass( $attrs['class'], $options['link-class'] );
			}
			$attrs['class'] = Html::expandClassList( $attrs['class'] ?? [] );
			foreach ( $attrs as $key => $value ) {
				if ( $value === null ) {
					continue;
				}
				if ( $classAsProperty && $key === 'class' ) {
					continue;
				}
				$linkHtmlAttributes[] = [ 'key' => $key, 'value' => $value ];
			}

			if ( isset( $item['link-html'] ) ) {
				$html = $item['link-html'] . ' ' . $html;
			}

			$html = Html::rawElement( isset( $attrs['href'] )
				? 'a'
				: $options['link-fallback'], $attrs, $html );
		}
		$data = [
			'html' => $html,
			'icon' => $icon,
			'array-attributes' => count( $linkHtmlAttributes ) > 0 ? $linkHtmlAttributes : null,
			'text' => trim( $text ),
		];
		if ( $classAsProperty ) {
			$data['class'] = $attrs['class'];
		}
		return $data;
	}

	/**
	 * Helper for makeLink(). Add tooltip and accesskey attributes to $attrs
	 * according to the input item array.
	 *
	 * @param array $item
	 * @param bool $allowAccessKey
	 * @param array &$attrs
	 */
	private function applyLinkTitleAttribs( $item, $allowAccessKey, &$attrs ) {
		$tooltipId = $item['single-id'] ?? $item['id'] ?? null;
		if ( $tooltipId === null ) {
			return;
		}
		$tooltipParams = $item['tooltip-params'] ?? [];
		$tooltipOption = isset( $item['exists'] ) && $item['exists'] === false ? 'nonexisting' : null;

		if ( !$allowAccessKey || !empty( $item['tooltiponly'] ) ) {
			$title = Linker::titleAttrib( $tooltipId, $tooltipOption, $tooltipParams );
			if ( $title !== false ) {
				$attrs['title'] = $title;
			}
		} else {
			$tip = Linker::tooltipAndAccesskeyAttribs(
				$tooltipId,
				$tooltipParams,
				$tooltipOption,
				$this->localizer
			);
			if ( isset( $tip['title'] ) && $tip['title'] !== false ) {
				$attrs['title'] = $tip['title'];
			}
			if ( isset( $tip['accesskey'] ) && $tip['accesskey'] !== false ) {
				$attrs['accesskey'] = $tip['accesskey'];
			}
		}
	}

	/**
	 * @inheritDoc
	 */
	public function getTemplateData(): array {
		return $this->makeLink( $this->key, $this->item, $this->options );
	}
}
