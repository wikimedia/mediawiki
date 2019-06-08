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

use Wikimedia\WrappedString;
use Wikimedia\WrappedStringList;

/**
 * New base template for a skin's template extended from QuickTemplate
 * this class features helper methods that provide common ways of interacting
 * with the data stored in the QuickTemplate
 */
abstract class BaseTemplate extends QuickTemplate {

	/**
	 * Get a Message object with its context set
	 *
	 * @param string $name Message name
	 * @param mixed $params,... Message params
	 * @return Message
	 */
	public function getMsg( $name /* ... */ ) {
		return $this->getSkin()->msg( ...func_get_args() );
	}

	function msg( $str ) {
		echo $this->getMsg( $str )->escaped();
	}

	/**
	 * @param string $str
	 * @warning You should never use this method. I18n messages should be escaped
	 * @deprecated 1.32 Use ->msg() or ->getMsg() instead.
	 * @suppress SecurityCheck-XSS
	 * @return-taint exec_html
	 */
	function msgHtml( $str ) {
		wfDeprecated( __METHOD__, '1.32' );
		echo $this->getMsg( $str )->text();
	}

	/**
	 * @deprecated since 1.33 Use ->msg() or ->getMsg() instead.
	 */
	function msgWiki( $str ) {
		// TODO: Add wfDeprecated( __METHOD__, '1.33' ) after 1.33 got released
		echo $this->getMsg( $str )->parseAsBlock();
	}

	/**
	 * Create an array of common toolbox items from the data in the quicktemplate
	 * stored by SkinTemplate.
	 * The resulting array is built according to a format intended to be passed
	 * through makeListItem to generate the html.
	 * @return array
	 */
	function getToolbox() {
		$toolbox = [];
		if ( isset( $this->data['nav_urls']['whatlinkshere'] )
			&& $this->data['nav_urls']['whatlinkshere']
		) {
			$toolbox['whatlinkshere'] = $this->data['nav_urls']['whatlinkshere'];
			$toolbox['whatlinkshere']['id'] = 't-whatlinkshere';
		}
		if ( isset( $this->data['nav_urls']['recentchangeslinked'] )
			&& $this->data['nav_urls']['recentchangeslinked']
		) {
			$toolbox['recentchangeslinked'] = $this->data['nav_urls']['recentchangeslinked'];
			$toolbox['recentchangeslinked']['msg'] = 'recentchangeslinked-toolbox';
			$toolbox['recentchangeslinked']['id'] = 't-recentchangeslinked';
			$toolbox['recentchangeslinked']['rel'] = 'nofollow';
		}
		if ( isset( $this->data['feeds'] ) && $this->data['feeds'] ) {
			$toolbox['feeds']['id'] = 'feedlinks';
			$toolbox['feeds']['links'] = [];
			foreach ( $this->data['feeds'] as $key => $feed ) {
				$toolbox['feeds']['links'][$key] = $feed;
				$toolbox['feeds']['links'][$key]['id'] = "feed-$key";
				$toolbox['feeds']['links'][$key]['rel'] = 'alternate';
				$toolbox['feeds']['links'][$key]['type'] = "application/{$key}+xml";
				$toolbox['feeds']['links'][$key]['class'] = 'feedlink';
			}
		}
		foreach ( [ 'contributions', 'log', 'blockip', 'emailuser',
			'userrights', 'upload', 'specialpages' ] as $special
		) {
			if ( isset( $this->data['nav_urls'][$special] ) && $this->data['nav_urls'][$special] ) {
				$toolbox[$special] = $this->data['nav_urls'][$special];
				$toolbox[$special]['id'] = "t-$special";
			}
		}
		if ( isset( $this->data['nav_urls']['print'] ) && $this->data['nav_urls']['print'] ) {
			$toolbox['print'] = $this->data['nav_urls']['print'];
			$toolbox['print']['id'] = 't-print';
			$toolbox['print']['rel'] = 'alternate';
			$toolbox['print']['msg'] = 'printableversion';
		}
		if ( isset( $this->data['nav_urls']['permalink'] ) && $this->data['nav_urls']['permalink'] ) {
			$toolbox['permalink'] = $this->data['nav_urls']['permalink'];
			$toolbox['permalink']['id'] = 't-permalink';
		}
		if ( isset( $this->data['nav_urls']['info'] ) && $this->data['nav_urls']['info'] ) {
			$toolbox['info'] = $this->data['nav_urls']['info'];
			$toolbox['info']['id'] = 't-info';
		}

		// Avoid PHP 7.1 warning from passing $this by reference
		$template = $this;
		Hooks::run( 'BaseTemplateToolbox', [ &$template, &$toolbox ] );
		return $toolbox;
	}

	/**
	 * Create an array of personal tools items from the data in the quicktemplate
	 * stored by SkinTemplate.
	 * The resulting array is built according to a format intended to be passed
	 * through makeListItem to generate the html.
	 * This is in reality the same list as already stored in personal_urls
	 * however it is reformatted so that you can just pass the individual items
	 * to makeListItem instead of hardcoding the element creation boilerplate.
	 * @return array
	 */
	function getPersonalTools() {
		$personal_tools = [];
		foreach ( $this->get( 'personal_urls' ) as $key => $plink ) {
			# The class on a personal_urls item is meant to go on the <a> instead
			# of the <li> so we have to use a single item "links" array instead
			# of using most of the personal_url's keys directly.
			$ptool = [
				'links' => [
					[ 'single-id' => "pt-$key" ],
				],
				'id' => "pt-$key",
			];
			if ( isset( $plink['active'] ) ) {
				$ptool['active'] = $plink['active'];
			}
			foreach ( [ 'href', 'class', 'text', 'dir', 'data', 'exists' ] as $k ) {
				if ( isset( $plink[$k] ) ) {
					$ptool['links'][0][$k] = $plink[$k];
				}
			}
			$personal_tools[$key] = $ptool;
		}
		return $personal_tools;
	}

	function getSidebar( $options = [] ) {
		// Force the rendering of the following portals
		$sidebar = $this->data['sidebar'];
		if ( !isset( $sidebar['SEARCH'] ) ) {
			$sidebar['SEARCH'] = true;
		}
		if ( !isset( $sidebar['TOOLBOX'] ) ) {
			$sidebar['TOOLBOX'] = true;
		}
		if ( !isset( $sidebar['LANGUAGES'] ) ) {
			$sidebar['LANGUAGES'] = true;
		}

		if ( !isset( $options['search'] ) || $options['search'] !== true ) {
			unset( $sidebar['SEARCH'] );
		}
		if ( isset( $options['toolbox'] ) && $options['toolbox'] === false ) {
			unset( $sidebar['TOOLBOX'] );
		}
		if ( isset( $options['languages'] ) && $options['languages'] === false ) {
			unset( $sidebar['LANGUAGES'] );
		}

		$boxes = [];
		foreach ( $sidebar as $boxName => $content ) {
			if ( $content === false ) {
				continue;
			}
			switch ( $boxName ) {
				case 'SEARCH':
					// Search is a special case, skins should custom implement this
					$boxes[$boxName] = [
						'id' => 'p-search',
						'header' => $this->getMsg( 'search' )->text(),
						'generated' => false,
						'content' => true,
					];
					break;
				case 'TOOLBOX':
					$msgObj = $this->getMsg( 'toolbox' );
					$boxes[$boxName] = [
						'id' => 'p-tb',
						'header' => $msgObj->exists() ? $msgObj->text() : 'toolbox',
						'generated' => false,
						'content' => $this->getToolbox(),
					];
					break;
				case 'LANGUAGES':
					if ( $this->data['language_urls'] !== false ) {
						$msgObj = $this->getMsg( 'otherlanguages' );
						$boxes[$boxName] = [
							'id' => 'p-lang',
							'header' => $msgObj->exists() ? $msgObj->text() : 'otherlanguages',
							'generated' => false,
							'content' => $this->data['language_urls'] ?: [],
						];
					}
					break;
				default:
					$msgObj = $this->getMsg( $boxName );
					$boxes[$boxName] = [
						'id' => "p-$boxName",
						'header' => $msgObj->exists() ? $msgObj->text() : $boxName,
						'generated' => true,
						'content' => $content,
					];
					break;
			}
		}

		// HACK: Compatibility with extensions still using SkinTemplateToolboxEnd
		$hookContents = null;
		if ( isset( $boxes['TOOLBOX'] ) ) {
			ob_start();
			// We pass an extra 'true' at the end so extensions using BaseTemplateToolbox
			// can abort and avoid outputting double toolbox links
			// Avoid PHP 7.1 warning from passing $this by reference
			$template = $this;
			Hooks::run( 'SkinTemplateToolboxEnd', [ &$template, true ] );
			$hookContents = ob_get_contents();
			ob_end_clean();
			if ( !trim( $hookContents ) ) {
				$hookContents = null;
			}
		}
		// END hack

		if ( isset( $options['htmlOnly'] ) && $options['htmlOnly'] === true ) {
			foreach ( $boxes as $boxName => $box ) {
				if ( is_array( $box['content'] ) ) {
					$content = '<ul>';
					foreach ( $box['content'] as $key => $val ) {
						$content .= "\n	" . $this->makeListItem( $key, $val );
					}
					// HACK, shove the toolbox end onto the toolbox if we're rendering itself
					if ( $hookContents ) {
						$content .= "\n	$hookContents";
					}
					// END hack
					$content .= "\n</ul>\n";
					$boxes[$boxName]['content'] = $content;
				}
			}
		} elseif ( $hookContents ) {
			$boxes['TOOLBOXEND'] = [
				'id' => 'p-toolboxend',
				'header' => $boxes['TOOLBOX']['header'],
				'generated' => false,
				'content' => "<ul>{$hookContents}</ul>",
			];
			// HACK: Make sure that TOOLBOXEND is sorted next to TOOLBOX
			$boxes2 = [];
			foreach ( $boxes as $key => $box ) {
				if ( $key === 'TOOLBOXEND' ) {
					continue;
				}
				$boxes2[$key] = $box;
				if ( $key === 'TOOLBOX' ) {
					$boxes2['TOOLBOXEND'] = $boxes['TOOLBOXEND'];
				}
			}
			$boxes = $boxes2;
			// END hack
		}

		return $boxes;
	}

	/**
	 * @param string $name
	 */
	protected function renderAfterPortlet( $name ) {
		echo $this->getAfterPortlet( $name );
	}

	/**
	 * Allows extensions to hook into known portlets and add stuff to them
	 *
	 * @param string $name
	 *
	 * @return string html
	 * @since 1.29
	 */
	protected function getAfterPortlet( $name ) {
		$html = '';
		$content = '';
		Hooks::run( 'BaseTemplateAfterPortlet', [ $this, $name, &$content ] );

		if ( $content !== '' ) {
			$html = Html::rawElement(
				'div',
				[ 'class' => [ 'after-portlet', 'after-portlet-' . $name ] ],
				$content
			);
		}

		return $html;
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
	 * used as a message name.
	 *
	 * If a "href" key is not present makeLink will just output htmlescaped text.
	 * The "href", "id", "class", "rel", and "type" keys are used as attributes
	 * for the link if present.
	 *
	 * If an "id" or "single-id" (if you don't want the actual id to be output
	 * on the link) is present it will be used to generate a tooltip and
	 * accesskey for the link.
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
	 * @param array $options Can be used to affect the output of a link.
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
	 * @return string
	 */
	function makeLink( $key, $item, $options = [] ) {
		$text = $item['text'] ?? wfMessage( $item['msg'] ?? $key )->text();

		$html = htmlspecialchars( $text );

		if ( isset( $options['text-wrapper'] ) ) {
			$wrapper = $options['text-wrapper'];
			if ( isset( $wrapper['tag'] ) ) {
				$wrapper = [ $wrapper ];
			}
			while ( count( $wrapper ) > 0 ) {
				$element = array_pop( $wrapper );
				$html = Html::rawElement( $element['tag'], $element['attributes'] ?? null, $html );
			}
		}

		if ( isset( $item['href'] ) || isset( $options['link-fallback'] ) ) {
			$attrs = $item;
			foreach ( [ 'single-id', 'text', 'msg', 'tooltiponly', 'context', 'primary',
				'tooltip-params', 'exists' ] as $k ) {
				unset( $attrs[$k] );
			}

			if ( isset( $attrs['data'] ) ) {
				foreach ( $attrs['data'] as $key => $value ) {
					$attrs[ 'data-' . $key ] = $value;
				}
				unset( $attrs[ 'data' ] );
			}

			if ( isset( $item['id'] ) && !isset( $item['single-id'] ) ) {
				$item['single-id'] = $item['id'];
			}

			$tooltipParams = [];
			if ( isset( $item['tooltip-params'] ) ) {
				$tooltipParams = $item['tooltip-params'];
			}

			if ( isset( $item['single-id'] ) ) {
				$tooltipOption = isset( $item['exists'] ) && $item['exists'] === false ? 'nonexisting' : null;

				if ( isset( $item['tooltiponly'] ) && $item['tooltiponly'] ) {
					$title = Linker::titleAttrib( $item['single-id'], $tooltipOption, $tooltipParams );
					if ( $title !== false ) {
						$attrs['title'] = $title;
					}
				} else {
					$tip = Linker::tooltipAndAccesskeyAttribs(
						$item['single-id'],
						$tooltipParams,
						$tooltipOption
					);
					if ( isset( $tip['title'] ) && $tip['title'] !== false ) {
						$attrs['title'] = $tip['title'];
					}
					if ( isset( $tip['accesskey'] ) && $tip['accesskey'] !== false ) {
						$attrs['accesskey'] = $tip['accesskey'];
					}
				}
			}
			if ( isset( $options['link-class'] ) ) {
				if ( isset( $attrs['class'] ) ) {
					$attrs['class'] .= " {$options['link-class']}";
				} else {
					$attrs['class'] = $options['link-class'];
				}
			}
			$html = Html::rawElement( isset( $attrs['href'] )
				? 'a'
				: $options['link-fallback'], $attrs, $html );
		}

		return $html;
	}

	/**
	 * Generates a list item for a navigation, portlet, portal, sidebar... list
	 *
	 * @param string $key Usually a key from the list you are generating this link from.
	 * @param array $item Array of list item data containing some of a specific set of keys.
	 * The "id", "class" and "itemtitle" keys will be used as attributes for the list item,
	 * if "active" contains a value of true a "active" class will also be appended to class.
	 *
	 * @param array $options
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
	 * @return string
	 */
	function makeListItem( $key, $item, $options = [] ) {
		if ( isset( $item['links'] ) ) {
			$links = [];
			foreach ( $item['links'] as $linkKey => $link ) {
				$links[] = $this->makeLink( $linkKey, $link, $options );
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
			$html = $this->makeLink( $key, $link, $options );
		}

		$attrs = [];
		foreach ( [ 'id', 'class' ] as $attr ) {
			if ( isset( $item[$attr] ) ) {
				$attrs[$attr] = $item[$attr];
			}
		}
		if ( isset( $item['active'] ) && $item['active'] ) {
			if ( !isset( $attrs['class'] ) ) {
				$attrs['class'] = '';
			}
			$attrs['class'] .= ' active';
			$attrs['class'] = trim( $attrs['class'] );
		}
		if ( isset( $item['itemtitle'] ) ) {
			$attrs['title'] = $item['itemtitle'];
		}
		return Html::rawElement( $options['tag'] ?? 'li', $attrs, $html );
	}

	function makeSearchInput( $attrs = [] ) {
		$realAttrs = [
			'type' => 'search',
			'name' => 'search',
			'placeholder' => wfMessage( 'searchsuggest-search' )->text(),
		];
		$realAttrs = array_merge( $realAttrs, Linker::tooltipAndAccesskeyAttribs( 'search' ), $attrs );
		return Html::element( 'input', $realAttrs );
	}

	function makeSearchButton( $mode, $attrs = [] ) {
		switch ( $mode ) {
			case 'go':
			case 'fulltext':
				$realAttrs = [
					'type' => 'submit',
					'name' => $mode,
					'value' => wfMessage( $mode == 'go' ? 'searcharticle' : 'searchbutton' )->text(),
				];
				$realAttrs = array_merge(
					$realAttrs,
					Linker::tooltipAndAccesskeyAttribs( "search-$mode" ),
					$attrs
				);
				return Html::element( 'input', $realAttrs );
			case 'image':
				$buttonAttrs = [
					'type' => 'submit',
					'name' => 'button',
				];
				$buttonAttrs = array_merge(
					$buttonAttrs,
					Linker::tooltipAndAccesskeyAttribs( 'search-fulltext' ),
					$attrs
				);
				unset( $buttonAttrs['src'] );
				unset( $buttonAttrs['alt'] );
				unset( $buttonAttrs['width'] );
				unset( $buttonAttrs['height'] );
				$imgAttrs = [
					'src' => $attrs['src'],
					'alt' => $attrs['alt'] ?? wfMessage( 'searchbutton' )->text(),
					'width' => $attrs['width'] ?? null,
					'height' => $attrs['height'] ?? null,
				];
				return Html::rawElement( 'button', $buttonAttrs, Html::element( 'img', $imgAttrs ) );
			default:
				throw new MWException( 'Unknown mode passed to BaseTemplate::makeSearchButton' );
		}
	}

	/**
	 * Returns an array of footerlinks trimmed down to only those footer links that
	 * are valid.
	 * If you pass "flat" as an option then the returned array will be a flat array
	 * of footer icons instead of a key/value array of footerlinks arrays broken
	 * up into categories.
	 * @param string|null $option
	 * @return array|mixed
	 */
	function getFooterLinks( $option = null ) {
		$footerlinks = $this->get( 'footerlinks' );

		// Reduce footer links down to only those which are being used
		$validFooterLinks = [];
		foreach ( $footerlinks as $category => $links ) {
			$validFooterLinks[$category] = [];
			foreach ( $links as $link ) {
				if ( isset( $this->data[$link] ) && $this->data[$link] ) {
					$validFooterLinks[$category][] = $link;
				}
			}
			if ( count( $validFooterLinks[$category] ) <= 0 ) {
				unset( $validFooterLinks[$category] );
			}
		}

		if ( $option == 'flat' ) {
			// fold footerlinks into a single array using a bit of trickery
			$validFooterLinks = array_merge( ...array_values( $validFooterLinks ) );
		}

		return $validFooterLinks;
	}

	/**
	 * Returns an array of footer icons filtered down by options relevant to how
	 * the skin wishes to display them.
	 * If you pass "icononly" as the option all footer icons which do not have an
	 * image icon set will be filtered out.
	 * If you pass "nocopyright" then MediaWiki's copyright icon will not be included
	 * in the list of footer icons. This is mostly useful for skins which only
	 * display the text from footericons instead of the images and don't want a
	 * duplicate copyright statement because footerlinks already rendered one.
	 * @param string|null $option
	 * @return array
	 */
	function getFooterIcons( $option = null ) {
		// Generate additional footer icons
		$footericons = $this->get( 'footericons' );

		if ( $option == 'icononly' ) {
			// Unset any icons which don't have an image
			foreach ( $footericons as &$footerIconsBlock ) {
				foreach ( $footerIconsBlock as $footerIconKey => $footerIcon ) {
					if ( !is_string( $footerIcon ) && !isset( $footerIcon['src'] ) ) {
						unset( $footerIconsBlock[$footerIconKey] );
					}
				}
			}
			// Redo removal of any empty blocks
			foreach ( $footericons as $footerIconsKey => &$footerIconsBlock ) {
				if ( count( $footerIconsBlock ) <= 0 ) {
					unset( $footericons[$footerIconsKey] );
				}
			}
		} elseif ( $option == 'nocopyright' ) {
			unset( $footericons['copyright']['copyright'] );
			if ( count( $footericons['copyright'] ) <= 0 ) {
				unset( $footericons['copyright'] );
			}
		}

		return $footericons;
	}

	/**
	 * Renderer for getFooterIcons and getFooterLinks
	 *
	 * @param string $iconStyle $option for getFooterIcons: "icononly", "nocopyright"
	 * @param string $linkStyle $option for getFooterLinks: "flat"
	 *
	 * @return string html
	 * @since 1.29
	 */
	protected function getFooter( $iconStyle = 'icononly', $linkStyle = 'flat' ) {
		$validFooterIcons = $this->getFooterIcons( $iconStyle );
		$validFooterLinks = $this->getFooterLinks( $linkStyle );

		$html = '';

		if ( count( $validFooterIcons ) + count( $validFooterLinks ) > 0 ) {
			$html .= Html::openElement( 'div', [
				'id' => 'footer-bottom',
				'role' => 'contentinfo',
				'lang' => $this->get( 'userlang' ),
				'dir' => $this->get( 'dir' )
			] );
			$footerEnd = Html::closeElement( 'div' );
		} else {
			$footerEnd = '';
		}
		foreach ( $validFooterIcons as $blockName => $footerIcons ) {
			$html .= Html::openElement( 'div', [
				'id' => Sanitizer::escapeIdForAttribute( "f-{$blockName}ico" ),
				'class' => 'footer-icons'
			] );
			foreach ( $footerIcons as $icon ) {
				$html .= $this->getSkin()->makeFooterIcon( $icon );
			}
			$html .= Html::closeElement( 'div' );
		}
		if ( count( $validFooterLinks ) > 0 ) {
			$html .= Html::openElement( 'ul', [ 'id' => 'f-list', 'class' => 'footer-places' ] );
			foreach ( $validFooterLinks as $aLink ) {
				$html .= Html::rawElement(
					'li',
					[ 'id' => Sanitizer::escapeIdForAttribute( $aLink ) ],
					$this->get( $aLink )
				);
			}
			$html .= Html::closeElement( 'ul' );
		}

		$html .= $this->getClear() . $footerEnd;

		return $html;
	}

	/**
	 * Get a div with the core visualClear class, for clearing floats
	 *
	 * @return string html
	 * @since 1.29
	 */
	protected function getClear() {
		return Html::element( 'div', [ 'class' => 'visualClear' ] );
	}

	/**
	 * Get the suggested HTML for page status indicators: icons (or short text snippets) usually
	 * displayed in the top-right corner of the page, outside of the main content.
	 *
	 * Your skin may implement this differently, for example by handling some indicator names
	 * specially with a different UI. However, it is recommended to use a `<div class="mw-indicator"
	 * id="mw-indicator-<id>" />` as a wrapper element for each indicator, for better compatibility
	 * with extensions and user scripts.
	 *
	 * The raw data is available in `$this->data['indicators']` as an associative array (keys:
	 * identifiers, values: contents) internally ordered by keys.
	 *
	 * @return string HTML
	 * @since 1.25
	 */
	public function getIndicators() {
		$out = "<div class=\"mw-indicators mw-body-content\">\n";
		foreach ( $this->data['indicators'] as $id => $content ) {
			$out .= Html::rawElement(
				'div',
				[
					'id' => Sanitizer::escapeIdForAttribute( "mw-indicator-$id" ),
					'class' => 'mw-indicator',
				],
				$content
			) . "\n";
		}
		$out .= "</div>\n";
		return $out;
	}

	/**
	 * Output getTrail
	 */
	function printTrail() {
		echo $this->getTrail();
	}

	/**
	 * Get the basic end-page trail including bottomscripts, reporttime, and
	 * debug stuff. This should be called right before outputting the closing
	 * body and html tags.
	 *
	 * @return string|WrappedStringList HTML
	 * @since 1.29
	 */
	public function getTrail() {
		return WrappedString::join( "\n", [
			MWDebug::getDebugHTML( $this->getSkin()->getContext() ),
			$this->get( 'bottomscripts' ),
			$this->get( 'reporttime' )
		] );
	}
}
