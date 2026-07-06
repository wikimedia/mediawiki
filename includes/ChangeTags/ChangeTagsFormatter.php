<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\ChangeTags;

use MediaWiki\Html\Html;
use MediaWiki\Language\MessageLocalizer;
use MediaWiki\Language\RawMessage;
use MediaWiki\Message\Message;
use MediaWiki\Parser\Sanitizer;
use MediaWiki\Skin\Skin;

/**
 * Formats change tags for display in HTML and use filter dropdown menus.
 *
 * @since 1.47
 * @ingroup ChangeTags
 */
class ChangeTagsFormatter {

	public function __construct(
		private readonly ChangeTagsStore $changeTagsStore,
	) {
	}

	/**
	 * Formats the provided tags into HTML for display to a user.
	 *
	 * @since 1.47
	 * @param string|null $tags Comma-separated list of tags (as returned by a database query)
	 * @param MessageLocalizer $localizer
	 * @return array{0:string,1:string[]} Array with two items: (html, classes)
	 *   - html: String: HTML for displaying the tags (empty string when param $tags is empty or
	 *       all tags have no description)
	 *   - classes: Array of strings: CSS classes to be appended to the parent element that this HTML
	 *       is appended to, one class per tag provided
	 * @return-taint onlysafefor_htmlnoent
	 */
	public function formatTagsAsSummaryList(
		?string $tags,
		MessageLocalizer $localizer
	): array {
		if ( $tags === '' || $tags === null ) {
			return [ '', [] ];
		}

		$classes = [];

		$tags = explode( ',', $tags );
		$order = array_flip( $this->changeTagsStore->listDefinedTags() );
		usort( $tags, static function ( $a, $b ) use ( $order ) {
			return ( $order[ $a ] ?? INF ) <=> ( $order[ $b ] ?? INF );
		} );

		$displayTags = [];
		foreach ( $tags as $tag ) {
			if ( $tag === '' ) {
				continue;
			}
			$classes[] = Sanitizer::escapeClass( "mw-tag-$tag" );
			$description = $this->getTagDescription( $tag, $localizer );
			if ( $description === '' ) {
				continue;
			}
			$displayTags[] = Html::rawElement(
				'span',
				[ 'class' => 'mw-tag-marker ' . Sanitizer::escapeClass( "mw-tag-marker-$tag" ) ],
				$description
			);
		}

		if ( !$displayTags ) {
			return [ '', $classes ];
		}

		$markers = $localizer->msg( 'tag-list-wrapper' )
			->numParams( count( $displayTags ) )
			->rawParams( implode( ' ', $displayTags ) )
			->parse();
		$markers = Html::rawElement( 'span', [ 'class' => 'mw-tag-markers' ], $markers );

		return [ $markers, $classes ];
	}

	/**
	 * Get a (short) description for a tag. The description includes the label for the tag along with
	 * a help link if defined. If the tag description is an empty string, the tag is considered hidden.
	 *
	 * @since 1.47
	 */
	public function getTagDescription( string $tag, MessageLocalizer $localizer ): string {
		$msg = $this->tagShortDescriptionMessage( $tag, $localizer );
		$link = $this->tagHelpLink( $tag, $localizer );
		if ( !$msg->isDisabled() && $link ) {
			$label = $msg->parse();
			// Avoid invalid HTML caused by link wrapping if the label already contains a link
			if ( !str_contains( $label, '<a ' ) ) {
				return Html::rawElement( 'a', [ 'href' => $link ], $label );
			}
		}
		return !$msg->isDisabled() ? $msg->parse() : '';
	}

	/**
	 * Get the {@link Message} for the tag's short description.
	 */
	private function tagShortDescriptionMessage( string $tag, MessageLocalizer $messageLocalizer ): Message {
		$msg = $messageLocalizer->msg( "tag-$tag" );
		if ( !$msg->exists() ) {
			// No such message
			// Pass through ->msg(), even though it seems redundant, to avoid requesting
			// the user's language from session-less entry points (T227233)
			return $messageLocalizer->msg( new RawMessage( '$1', [ Message::plaintextParam( $tag ) ] ) );
		}

		return $msg;
	}

	/**
	 * Get the tag's help link, or `null` if no help link could be generated.
	 */
	private function tagHelpLink( string $tag, MessageLocalizer $context ): ?string {
		$msg = $context->msg( "tag-$tag-helppage" )->inContentLanguage();
		if ( !$msg->isDisabled() ) {
			return Skin::makeInternalOrExternalUrl( $msg->text() ) ?: null;
		}
		return null;
	}
}
