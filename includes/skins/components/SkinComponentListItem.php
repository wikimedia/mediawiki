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
use MessageLocalizer;

class SkinComponentListItem implements SkinComponent {
	/** @var string */
	private $key;
	/** @var array */
	private $item;
	/** @var MessageLocalizer */
	private $localizer;
	/** @var array */
	private $options;
	/** @var array */
	private $defaultLinkOptions;

	/**
	 * @param string $key
	 * @param array $item
	 * @param MessageLocalizer $localizer
	 * @param array $options
	 * @param array $defaultLinkOptions
	 */
	public function __construct(
		string $key,
		array $item,
		MessageLocalizer $localizer,
		array $options = [],
		array $defaultLinkOptions = []
	) {
		$this->key = $key;
		$this->item = $item;
		$this->localizer = $localizer;
		$this->options = $options;
		$this->defaultLinkOptions = $defaultLinkOptions;
	}

	private function getMessageLocalizer(): MessageLocalizer {
		return $this->localizer;
	}

	/**
	 * Generates a list item for a navigation, portlet, portal, sidebar... list
	 *
	 * @param string $key Usually a key from the list you are generating this link from.
	 * @param array $item Array of list item data containing some of a specific set of keys.
	 * The "id", "class" and "itemtitle" keys will be used as attributes for the list item,
	 * if "active" contains a value of true a "active" class will also be appended to class.
	 * The "class" key currently accepts both a string and an array of classes, but this will be
	 * changed to only accept an array in the future.
	 * @phan-param array{id?:string,class?:string|string[],itemtitle?:string,active?:bool} $item
	 *
	 * @param array $options
	 * @phan-param array{tag?:string} $options
	 *
	 * If you want something other than a "<li>" you can pass a tag name such as
	 * "tag" => "span" in the $options array to change the tag used.
	 * link/content data for the list item may come in one of two forms
	 * A "links" key may be used, in which case it should contain an array with
	 * a list of links to include inside the list item, see makeLink for the
	 * format of individual links array items.
	 *
	 * Otherwise the relevant keys from the list item $item array will be passed
	 * to makeLink instead. Note however that "id" and "class" are used by the
	 * list item directly so they will not be passed to makeLink
	 * (however the link will still support a tooltip and accesskey from it)
	 * If you need an id or class on a single link you should include a "links"
	 * array with just one link item inside of it. You can also set "link-class" in
	 * $item to set a class on the link itself. If you want to add a title
	 * to the list item itself, you can set "itemtitle" to the value.
	 * $options is also passed on to makeLink calls
	 *
	 * @param array $linkOptions Can be used to affect the output of a link.
	 * Possible options are:
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
	 * @return array List item data:
	 * - tag: String HTML tag name for the list item
	 * - attrs: Array of attributes for the list item
	 * - html: String HTML for the list item
	 * - array-links: Array of link template data
	 * @since 1.35
	 */
	private function makeListItem(
		string $key,
		array $item,
		array $options = [],
		array $linkOptions = []
	) {
		// In case this is still set from SkinTemplate, we don't want it to appear in
		// the HTML output (normally removed in SkinTemplate::buildContentActionUrls())
		unset( $item['redundant'] );
		$iconData = [
			'icon' => $item['icon'] ?? null,
		];
		$linksArray = [];
		if ( isset( $this->item['links'] ) ) {
			$links = [];
			/* @var array $link */
			foreach ( $this->item['links'] as $link ) {
				// Note: links will have identical label unless 'msg' is set on $link
				$linkComponent = new SkinComponentLink(
					$key,
					$link + $iconData,
					$this->getMessageLocalizer(),
					$options + $linkOptions
				);
				$linkTemplateData = $linkComponent->getTemplateData();
				$links[] = $linkTemplateData['html'];
				unset( $linkTemplateData['html'] );
				if ( $linkTemplateData ) {
					$linksArray[] = $linkTemplateData;
				}
			}
			$html = implode( ' ', $links );
		} else {
			$link = $item;
			// These keys are used by makeListItem and shouldn't be passed on to the link
			foreach ( [ 'id', 'class', 'active', 'tag', 'itemtitle' ] as $k ) {
				unset( $link[$k] );
			}
			if ( isset( $item['id'] ) && !isset( $item['single-id'] ) ) {
				// The id goes on the <li> not on the <a> for single links
				// but makeSidebarLink still needs to know what id to use when
				// generating tooltips and accesskeys.
				$link['single-id'] = $item['id'];
			}
			if ( isset( $link['link-class'] ) ) {
				// link-class should be set on the <a> itself,
				// so pass it in as 'class'
				$link['class'] = $link['link-class'];
				unset( $link['link-class'] );
			}
			$linkComponent = new SkinComponentLink(
				$key,
				$link,
				$this->getMessageLocalizer(),
				$options + $linkOptions
			);
			$data = $linkComponent->getTemplateData();

			$html = $data['html'];
			unset( $data['html'] );
			// in the case of some links e.g. footer these may be HTML only so make sure not to add an empty object.
			if ( $data ) {
				$linksArray[] = $data;
			}
		}

		$attrs = [];
		foreach ( [ 'id', 'class' ] as $attr ) {
			if ( isset( $item[$attr] ) ) {
				$attrs[$attr] = $item[$attr];
			}
		}
		$attrs['class'] = SkinComponentUtils::addClassToClassList( $attrs['class'] ?? [], 'mw-list-item' );

		if ( isset( $item['active'] ) && $item['active'] ) {
			// In the future, this should accept an array of classes, not a string
			$attrs['class'] = SkinComponentUtils::addClassToClassList( $attrs['class'], 'active' );
		}
		if ( isset( $item['itemtitle'] ) ) {
			$attrs['title'] = $item['itemtitle'];
		}
		// Making sure we always have strings as class values
		$classes = Html::expandClassList( $attrs['class'] );
		return [
			'tag' => $options['tag'] ?? 'li',
			'attrs' => $attrs,
			'html' => $html,
			'class' => $classes,
			'array-links' => count( $linksArray ) > 0 ? $linksArray : null
		];
	}

	/**
	 * @inheritDoc
	 * @suppress SecurityCheck-DoubleEscaped
	 *
	 * @return array List item template data:
	 * - html-item: Full HTML for the list item with the content inside
	 * - name: Name/Key of the list item
	 * - html: String HTML for the list item content
	 * - id: ID of the list item
	 * - class: Classes for the list item
	 * - array-links: Array of link template data
	 */
	public function getTemplateData(): array {
		$item = $this->makeListItem( $this->key, $this->item, $this->options, $this->defaultLinkOptions );
		$html = $item['html'];
		return [
			'html-item' => Html::rawElement( $item['tag'], $item['attrs'], $html ),
			'name' => $this->key,
			'html' => $html,
			'id' => $this->item['id'] ?? null,
			'class' => $item['class'],
			'array-links' => $item['array-links']
		];
	}
}
