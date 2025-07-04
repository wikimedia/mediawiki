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
 */

namespace MediaWiki\Skin;

use MediaWiki\Html\HtmlHelper;
use Wikimedia\RemexHtml\Serializer\SerializerNode;

/**
 * SkinAuthenticationPopup is a "micro-skin" that omits most of the usual interface elements,
 * making the page suitable to be displayed in a small popup window.
 *
 * It should not ever be available for normal use. It's only used directly by special pages
 * related to authentication when this kind of interface is requested.
 */
class SkinAuthenticationPopup extends SkinMustache {

	private static function openLinksInNewWindow( string &$html ): void {
		$html = HtmlHelper::modifyElements(
			$html,
			static function ( SerializerNode $node ): bool {
				return $node->name === 'a'
					&& isset( $node->attrs['href'] )
					&& !str_contains( $node->attrs['class'] ?? '', 'mw-authentication-popup-link' );
			},
			static function ( SerializerNode $node ): SerializerNode {
				$node->attrs['target'] ??= '_blank';
				return $node;
			}
		);
	}

	/** @inheritDoc */
	public function getTemplateData() {
		$data = parent::getTemplateData();

		// This skin is intended to be shown in small popups, therefore open all links in new windows,
		// except those explicitly marked. The CSS class can also be added by extensions.
		self::openLinksInNewWindow( $data['html-body-content'] );
		foreach ( $data['data-footer'] as &$footerItems ) {
			foreach ( $footerItems['array-items'] as &$item ) {
				self::openLinksInNewWindow( $item['html'] );
			}
		}

		return $data;
	}

}

/** @deprecated class alias since 1.44 */
class_alias( SkinAuthenticationPopup::class, 'SkinAuthenticationPopup' );
