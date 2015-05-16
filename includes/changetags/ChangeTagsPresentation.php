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
 * @ingroup Change tagging
 */

/**
 * Provides functions for displaying change tags in the user interface
 * @since 1.29
 */
class ChangeTagsPresentation {

	/** @var IContextSource */
	private $context;

	/**
	 * @param IContextSource $context
	 */
	public function __construct( IContextSource $context ) {
		$this->context = $context;
	}

	/**
	 * Get a short description for a tag.
	 *
	 * Checks if message key "tag-$tag" exists. If it does not,
	 * returns the HTML-escaped tag name. Uses the message if the message
	 * exists, provided it is not disabled. If the message is disabled,
	 * we consider the tag hidden, and return false.
	 *
	 * @param string $tag Tag name
	 * @return string|bool Tag description or false if tag is to be hidden.
	 * @since 1.25 Returns false if tag is to be hidden.
	 */
	public function tagDescription( $tag ) {
		$msg = $this->context->msg( "tag-$tag" );
		if ( !$msg->exists() ) {
			// No such message, so return the HTML-escaped tag name.
			return htmlspecialchars( $tag );
		}
		if ( $msg->isDisabled() ) {
			// The message exists but is disabled, hide the tag.
			return false;
		}

		// Message exists and isn't disabled, use it.
		return $msg->parse();
	}

	/**
	 * Extract text from tag description
	 * This is for drop down menus.
	 *
	 * @param string $tag Tag name
	 * @return string Escaped, decoded, tag-stripped html
	 * @since 1.29
	 */
	public function plainTagDescription( $tag ) {
		$html = $this->tagDescription( $tag );
		$plain = $html ? trim( html_entity_decode( strip_tags( $html ), ENT_QUOTES ) ) : '';
		return htmlspecialchars( $plain );
	}

	/**
	 * Build a drop-down menu to select a change tag
	 *
	 * @param string|false|null $selected Tag to select by default
	 * @param bool $formDescriptor Use a form descriptor instead of a label and selector
	 * @return array an array of (label, selector) or form descriptor
	 */
	public function buildTagFilterSelector( $selected = '', $formDescriptor = false ) {
		$usedTags = $this->getUsedTags();
		if ( !$usedTags ) {
			return [];
		}
		if ( !$selected ) {
			$selected = '';
		}

		// make drop down menu for tags - T27909
		// add tags
		$tags = [];
		foreach ( $usedTags as $tag ) {
			$name = $this->plainTagDescription( $tag );
			// tags with an empty description are not included in the drop down list
			if ( $name !== '' ) {
				$tags[$name] = $tag;
			}
		}
		ksort( $tags );
		$options = array_merge(
			[ $this->context->msg( 'tag-filter-none-selected' )->text() => '' ],
			$tags
		);

		if ( $formDescriptor ) {
			return $this->getSelectFormDescriptor( $options, $selected );
		} else {
			return $this->getHTMLSelector( $options, $selected );
		}
	}

	/**
	 * Tags in use on the wiki
	 * @return array
	 */
	private function getUsedTags() {
		if ( !$this->context->getConfig()->get( 'UseTagFilter' ) ) {
			return [];
		}
		return array_keys( ChangeTags::secondaryTagUsageStatistics() );
	}

	/**
	 * Build a form descriptor for a select field
	 *
	 * @param array $options Array of select options
	 * @param string $selected A selected option, or empty string
	 * @return array
	 */
	private function getSelectFormDescriptor( $options, $selected ) {
		return [
			'tagfilter' => [
				'type' => 'select',
				'name' => 'tagfilter',
				'id' => 'tagfilter',
				'cssclass' => 'mw-tagfilter-input',
				'label-message' => [ 'tag-filter', 'parse' ],
				'options' => $options,
				'default' => $selected
			],
		];
	}

	/**
	 * Build a HTML <select> element to select a change tag
	 *
	 * @param array $options Array of select options
	 * @param string $selected A selected option, or empty string
	 * @return array The first element is the matching <label> tag,
	 * and the second element is the <select> tag.
	 */
	private function getHTMLSelector( $options, $selected ) {
		$select = new XmlSelect( 'tagfilter', 'mw-tagfilter', $selected );
		$select->addOptions( $options );
		return [
			Html::rawElement(
				'label',
				[ 'for' => 'tagfilter' ],
				$this->context->msg( 'tag-filter' )->parse()
			),
			$select->getHTML()
		];
	}

	/**
	 * Creates HTML for the given tags
	 *
	 * @param string $tags Comma-separated list of tags
	 * @return array Array with two items: (html, classes)
	 *   for example: 'history', 'contributions' or 'newpages'
	 *   - html: String: HTML for displaying the tags (empty string when param $tags is empty)
	 *   - classes: Array of strings: CSS classes used in the generated html, one class for each tag
	 */
	public function formatSummaryRow( $tags ) {
		if ( !$tags ) {
			return [ '', [] ];
		}

		$tags = explode( ',', $tags );
		$displayTags = [];
		$classes = [];
		foreach ( $tags as $tag ) {
			if ( !$tag ) {
				continue;
			}

			$formattedDescription = $this->formattedTagDescription( $tag );
			if ( $formattedDescription === false ) {
				continue;
			}

			$displayTags[] = $formattedDescription;
			$classes[] = Sanitizer::escapeClass( "mw-tag-$tag" );
		}

		if ( !$displayTags ) {
			return [ '', [] ];
		}

		$markers = $this->getMarkers( $displayTags );

		return [ $markers, $classes ];
	}

	/**
	 * Tag description wrapped in a span
	 * @param string $tag Tag name
	 * @return array|bool
	 */
	private function formattedTagDescription( $tag ) {
			$description = $this->tagDescription( $tag );
			if ( $description === false ) {
				return false;
			}

			return Xml::tags(
				'span',
				[
					'class' => 'mw-tag-marker ' . Sanitizer::escapeClass( "mw-tag-marker-$tag" )
				],
				$description
			);
	}

	/**
	 * Get comma separated list of tags prefixed by Tag(s):
	 * @param array $formattedTags Array of formatted tags
	 * @return string
	 */
	private function getMarkers( $formattedTags ) {
		$markers = $this->context->msg( 'tag-list-wrapper' )
			->numParams( count( $formattedTags ) )
			->rawParams( $this->context->getLanguage()->commaList( $formattedTags ) )
			->parse();
		return Xml::tags( 'span', [ 'class' => 'mw-tag-markers' ], $markers );
	}
}
