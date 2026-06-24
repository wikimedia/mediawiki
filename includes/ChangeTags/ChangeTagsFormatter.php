<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\ChangeTags;

use MediaWiki\Config\ServiceOptions;
use MediaWiki\Context\IContextSource;
use MediaWiki\Html\Html;
use MediaWiki\Language\LanguageFactory;
use MediaWiki\Language\LocalizationContext;
use MediaWiki\Language\MessageLocalizer;
use MediaWiki\Language\RawMessage;
use MediaWiki\MainConfigNames;
use MediaWiki\Message\Message;
use MediaWiki\Parser\Sanitizer;
use MediaWiki\Permissions\Authority;
use MediaWiki\Skin\Skin;
use OOUI\ComboBoxInputWidget;
use Wikimedia\ObjectCache\WANObjectCache;

/**
 * Formats change tags for display in HTML and use filter dropdown menus.
 *
 * @since 1.47
 * @ingroup ChangeTags
 */
class ChangeTagsFormatter {

	/** @internal Only for use by ServiceWiring.php */
	public const array CONSTRUCTOR_OPTIONS = [
		MainConfigNames::UseTagFilter,
	];

	/**
	 * Maximum length of a tag description in UTF-8 characters.
	 * Longer descriptions will be truncated.
	 */
	private const int TAG_DESC_CHARACTER_LIMIT = 120;

	public function __construct(
		private readonly ServiceOptions $options,
		private readonly ChangeTagsStore $changeTagsStore,
		private readonly WANObjectCache $cache,
		private readonly LanguageFactory $languageFactory,
	) {
		$this->options->assertRequiredOptions( self::CONSTRUCTOR_OPTIONS );
	}

	/**
	 * Formats the provided tags into HTML for display to a user.
	 *
	 * @since 1.47
	 * @param string|null $tags Comma-separated list of tags (as returned by a database query)
	 * @param MessageLocalizer $localizer
	 * @param Authority $authority
	 * @return array{0:string,1:string[]} Array with two items: (html, classes)
	 *   - html: String: HTML for displaying the tags (empty string when param $tags is empty or
	 *       all tags have no description)
	 *   - classes: Array of strings: CSS classes to be appended to the parent element that this HTML
	 *       is appended to, one class per tag provided
	 * @return-taint onlysafefor_htmlnoent
	 */
	public function formatTagsAsSummaryList(
		?string $tags,
		MessageLocalizer $localizer,
		Authority $authority
	): array {
		if ( $tags === '' || $tags === null ) {
			return [ '', [] ];
		}

		$classes = [];

		$tags = explode( ',', $tags );
		$tags = $this->changeTagsStore->filterViewableTags( $tags, $authority );
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

	/**
	 * Build a text box to select a change tag. The tag set can be customized via the $activeOnly
	 * and $useAllTags parameters, and defaults to all active tags.
	 *
	 * @since 1.47
	 * @param string $selected Tag to select by default
	 * @param string $format One of the following formats:
	 *   - `'ooui'`: Use an OOUI {@link ComboBoxInputWidget}. You need to call {@link OutputPage::enableOOUI()}
	 *       yourself.
	 *   - `'codex'`: Use a Codex CSS-only Lookup input.
	 *   - otherwise: Plain HTML select box.
	 * @param IContextSource $context
	 * @param bool $activeOnly If `true`, only show tags which have been used at least once
	 * @param bool $useAllTags If `true`, use all known tags. If `false`, use only tags defined by MediaWiki core
	 *   (excluding tags defined by extensions, users, or site config)
	 * @return array{0:string,1:string|ComboBoxInputWidget}|null Two chunks of HTML (label, and dropdown menu)
	 *   or null if disabled
	 */
	public function buildTagFilter(
		string $selected,
		string $format,
		IContextSource $context,
		bool $activeOnly = true,
		bool $useAllTags = true
	): ?array {
		if (
			!$this->options->get( MainConfigNames::UseTagFilter ) ||
			!count( $this->changeTagsStore->listDefinedTags() )
		) {
			return null;
		}

		$tags = $this->getChangeTagList(
			$context,
			$context->getAuthority(),
			$activeOnly,
			$useAllTags,
			true
		);

		$autocomplete = [];
		foreach ( $tags as $tagInfo ) {
			$autocomplete[ $tagInfo['label'] ] = $tagInfo['name'];
		}

		$data = [];
		$data[0] = Html::rawElement(
			'label',
			[ 'for' => 'tagfilter' ],
			$context->msg( 'tag-filter' )->parse()
		);

		if ( $format === 'ooui' ) {
			$options = Html::listDropdownOptionsOoui( $autocomplete );

			$data[1] = new ComboBoxInputWidget( [
				'id' => 'tagfilter',
				'name' => 'tagfilter',
				'value' => $selected,
				'classes' => 'mw-tagfilter-input',
				'options' => $options,
			] );
		} else {
			$optionsHtml = '';
			foreach ( $autocomplete as $label => $name ) {
				$optionsHtml .= Html::element( 'option', [ 'value' => $name ], $label );
			}
			$datalistHtml = Html::rawElement( 'datalist', [ 'id' => 'tagfilter-datalist' ], $optionsHtml );

			$data[1] = Html::input(
				'tagfilter',
				$selected,
				'text',
				[
					'class' => [ 'mw-tagfilter-input', 'cdx-text-input__input' => $format === 'codex' ],
					'size' => 20,
					'id' => 'tagfilter',
					'list' => 'tagfilter-datalist',
				]
			);
			if ( $format === 'codex' ) {
				$data[1] = Html::rawElement( 'div', [ 'class' => 'cdx-text-input' ], $data[1] );
			}
			$data[1] .= $datalistHtml;
		}

		return $data;
	}

	/**
	 * Get information about change tags, without parsing messages, for tag filter dropdown menus.
	 * By default, this will return explicitly-defined and software-defined tags that are currently active (have hits)
	 *
	 * Message contents are the raw values (->plain()), because parsing messages is expensive.
	 * Even though we're not parsing messages, building a data structure with the contents of
	 * hundreds of i18n messages is still not cheap (see T223260#5370610), so this function
	 * caches its output in WANCache for up to 24 hours.
	 *
	 * Returns an array of associative arrays with information about each tag:
	 * - name: Tag name (string)
	 * - labelMsg: Whether the short description message exists and is enabled (boolean)
	 * - label: Short description message (raw message contents), or the tag name if
	 *     the short description message was disabled or did not exist
	 * - descriptionMsg: Whether the long description message exists and is enabled (boolean)
	 * - description: Long description message (raw message contents)
	 * - cssClass: CSS class to use for RC entries with this tag
	 * - helpLink: Link to a help page describing this tag (string or null)
	 *
	 * This data is consumed by the `mediawiki.rcfilters.filters.ui` module,
	 * specifically `mw.rcfilters.dm.FilterGroup` and `mw.rcfilters.dm.FilterItem`.
	 *
	 * @since 1.47
	 * @param LocalizationContext $localizationContext
	 * @param Authority $authority
	 * @param bool $activeOnly If `true`, only show tags which have been used at least once
	 * @param bool $useAllTags If `true`, use all known tags. If `false`, use only tags defined by MediaWiki core
	 *   (excluding tags defined by extensions, users, or site config)
	 * @return array[] Information about each tag
	 */
	public function getChangeTagListSummary(
		LocalizationContext $localizationContext,
		Authority $authority,
		bool $activeOnly = true,
		bool $useAllTags = true
	): array {
		if ( $useAllTags ) {
			$tagKeys = $this->changeTagsStore->listDefinedTags();
			$cacheKey = 'tags-list-summary';
		} else {
			$tagKeys = $this->changeTagsStore->getCoreDefinedTags();
			$cacheKey = 'core-software-tags-summary';
		}

		// if $tagHitCounts exists, check against it later to determine whether or not to omit tags
		$tagHitCounts = null;
		if ( $activeOnly ) {
			$tagHitCounts = $this->changeTagsStore->tagUsageStatistics();
		} else {
			// The full set of tags should use a different cache key than the subset
			$cacheKey .= '-all';
		}

		$summary = $this->cache->getWithSetCallback(
			$this->cache->makeKey( $cacheKey, strtolower( $localizationContext->getLanguageCode()->toBcp47Code() ) ),
			WANObjectCache::TTL_DAY,
			function () use ( $localizationContext, $tagKeys, $tagHitCounts ) {
				$result = [];
				foreach ( $tagKeys as $tagName ) {
					// Only list tags that are still actively defined
					if ( $tagHitCounts !== null ) {
						// Only list tags with more than 0 hits
						$hits = $tagHitCounts[$tagName] ?? 0;
						if ( $hits <= 0 ) {
							continue;
						}
					}

					$labelMsg = $this->tagShortDescriptionMessage( $tagName, $localizationContext );
					$helpLink = $this->tagHelpLink( $tagName, $localizationContext );
					$descriptionMsg = $localizationContext->msg( "tag-$tagName-description" );
					// Don't cache the message object, use the correct MessageLocalizer to parse later.
					$result[] = [
						'name' => $tagName,
						'labelMsg' => !$labelMsg->isDisabled(),
						'label' => !$labelMsg->isDisabled() ? $labelMsg->plain() : $tagName,
						'descriptionMsg' => !$descriptionMsg->isDisabled(),
						'description' => !$descriptionMsg->isDisabled() ? $descriptionMsg->plain() : '',
						'helpLink' => $helpLink,
						'cssClass' => Sanitizer::escapeClass( 'mw-tag-' . $tagName ),
					];
				}
				return $result;
			}
		);

		// Filter out tags that the user cannot see before returning this (the cache assumes the user can see all tags
		// to avoid splitting it by user)
		$viewable = array_fill_keys(
			$this->changeTagsStore->filterViewableTags( array_column( $summary, 'name' ), $authority ),
			true
		);
		return array_values( array_filter(
			$summary,
			static fn ( array $tagInfo ) => isset( $viewable[ $tagInfo['name'] ] )
		) );
	}

	/**
	 * Get information about change tags for tag filter dropdown menus.
	 *
	 * This manipulates the label and description of each tag, which are parsed, stripped
	 * and (in the case of description) truncated versions of these messages. Message
	 * parsing is expensive, so to detect whether the tag list has changed, use
	 * {@link ChangeTagsFormatter::getChangeTagListSummary()} instead.
	 *
	 * @since 1.47
	 * @param LocalizationContext $localizationContext
	 * @param Authority $authority
	 * @param bool $activeOnly If `true`, only show tags which have been used at least once
	 * @param bool $useAllTags If `true`, use all known tags. If `false`, use only tags defined by MediaWiki core
	 *   (excluding tags defined by extensions, users, or site config)
	 * @param bool $labelsOnly Do not parse descriptions and omit 'description' in the result
	 * @return array[] Same as {@link ChangeTagsFormatter::getChangeTagListSummary()}, with messages parsed,
	 *   stripped and truncated
	 */
	public function getChangeTagList(
		LocalizationContext $localizationContext,
		Authority $authority,
		bool $activeOnly = true,
		bool $useAllTags = true,
		bool $labelsOnly = false
	): array {
		$tags = $this->getChangeTagListSummary( $localizationContext, $authority, $activeOnly, $useAllTags );

		$language = $this->languageFactory->getLanguage( $localizationContext->getLanguageCode() );
		foreach ( $tags as &$tagInfo ) {
			if ( $tagInfo['labelMsg'] ) {
				// Optimization: Skip the parsing if the label contains only plain text (T344352)
				if ( wfEscapeWikiText( $tagInfo['label'] ) !== $tagInfo['label'] ) {
					// Use localizer with the correct page title to parse plain message from the cache.
					$labelMsg = new RawMessage( $tagInfo['label'] );
					$tagInfo['label'] = Sanitizer::stripAllTags( $localizationContext->msg( $labelMsg )->parse() );
				}
			} else {
				$tagInfo['label'] = $localizationContext->msg( 'tag-hidden', $tagInfo['name'] )->text();
			}
			// Optimization: Skip parsing the descriptions if not needed by the caller (T344352)
			if ( $labelsOnly ) {
				unset( $tagInfo['description'] );
			} elseif ( $tagInfo['descriptionMsg'] ) {
				// Optimization: Skip the parsing if the description contains only plain text (T344352)
				if ( wfEscapeWikiText( $tagInfo['description'] ) !== $tagInfo['description'] ) {
					$descriptionMsg = new RawMessage( $tagInfo['description'] );
					$tagInfo['description'] = Sanitizer::stripAllTags(
						$localizationContext->msg( $descriptionMsg )->parse()
					);
				}
				$tagInfo['description'] = $language->truncateForVisual( $tagInfo['description'],
					self::TAG_DESC_CHARACTER_LIMIT );
			}
			unset( $tagInfo['labelMsg'] );
			unset( $tagInfo['descriptionMsg'] );
		}

		// Instead of sorting by hit count (disabled for now), sort by display name
		usort( $tags, static function ( $a, $b ) {
			return strcasecmp( $a['label'], $b['label'] );
		} );
		return $tags;
	}
}
