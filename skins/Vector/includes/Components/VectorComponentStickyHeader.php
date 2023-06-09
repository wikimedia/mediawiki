<?php
namespace MediaWiki\Skins\Vector\Components;

use Message;
use MessageLocalizer;

/**
 * VectorComponentStickyHeader component
 */
class VectorComponentStickyHeader implements VectorComponent {
	private const TALK_ICON = [
		'icon' => 'speechBubbles',
		'id' => 'ca-talk-sticky-header',
		'event' => 'talk-sticky-header'
	];
	private const SUBJECT_ICON = [
		'icon' => 'article',
		'id' => 'ca-subject-sticky-header',
		'event' => 'subject-sticky-header'
	];
	private const HISTORY_ICON = [
		'icon' => 'wikimedia-history',
		'id' => 'ca-history-sticky-header',
		'event' => 'history-sticky-header',
	];
	// Event and icon will be updated depending on watchstar state
	private const WATCHSTAR_ICON = [
		'id' => 'ca-watchstar-sticky-header',
		'event' => 'watch-sticky-header',
		'icon' => 'wikimedia-star',
		'is-quiet' => true,
		'tabindex' => '-1',
		// With the original watchstar, this class is applied to the <li> element
		// thats the parent of the actual watchlink. In the sticky header we dont use
		// the same markup, so its directly applied to the watchlink element
		'class' => 'mw-watchlink'
	];
	private const EDIT_VE_ICON = [
		'id' => 'ca-ve-edit-sticky-header',
		'event' => 've-edit-sticky-header',
		'icon' => 'wikimedia-edit',
	];
	private const EDIT_WIKITEXT_ICON = [
		'id' => 'ca-edit-sticky-header',
		'event' => 'wikitext-edit-sticky-header',
		'icon' => 'wikimedia-wikiText',
	];
	private const EDIT_PROTECTED_ICON = [
		'href' => '#',
		'id' => 'ca-viewsource-sticky-header',
		'event' => 've-edit-protected-sticky-header',
		'icon' => 'wikimedia-editLock',
	];

	/** @var MessageLocalizer */
	private $localizer;
	/** @var VectorComponent */
	private $search;
	/** @var VectorComponent|null */
	private $langButton;
	/** @var bool */
	private $includeEditIcons;

	/**
	 * @param MessageLocalizer $localizer
	 * @param VectorComponent $searchBox
	 * @param VectorComponent|null $langButton
	 * @param bool $includeEditIcons whether to include edit icons in the result
	 */
	public function __construct(
		MessageLocalizer $localizer,
		VectorComponent $searchBox,
		$langButton = null,
		$includeEditIcons = false
	) {
		$this->search = $searchBox;
		$this->langButton = $langButton;
		$this->includeEditIcons = $includeEditIcons;
		$this->localizer = $localizer;
	}

	/**
	 * @param mixed $key
	 * @return Message
	 */
	private function msg( $key ): Message {
		return $this->localizer->msg( $key );
	}

	/**
	 * Creates array of Button components in the sticky header
	 *
	 * @return array
	 */
	private function getIconButtons() {
		$icons = [
			self::TALK_ICON,
			self::SUBJECT_ICON,
			self::HISTORY_ICON,
			self::WATCHSTAR_ICON,
		];
		if ( $this->includeEditIcons ) {
			$icons[] = self::EDIT_WIKITEXT_ICON;
			$icons[] = self::EDIT_PROTECTED_ICON;
			$icons[] = self::EDIT_VE_ICON;
		}
		$iconButtons = [];
		foreach ( $icons as $icon ) {
			$iconButtons[] = new VectorComponentButton(
				// Button labels will be populated in stickyHeader.js
				"",
				$icon[ 'icon' ],
				$icon[ 'id' ],
				$icon[ 'class' ] ?? '',
				[
					'tabindex' => '-1',
					'data-event-name' => $icon[ 'event' ],
				],
				'quiet',
				'default',
				true,
				'#'
			);
		}
		return $iconButtons;
	}

	/**
	 * Creates button data for the "Add section" button in the sticky header
	 *
	 * @return VectorComponentButton
	 */
	private function getAddSectionButton() {
		return new VectorComponentButton(
			$this->msg( [ 'vector-2022-action-addsection', 'skin-action-addsection' ] )->text(),
			'speechBubbleAdd-progressive',
			'ca-addsection-sticky-header',
			'',
			[
				'tabindex' => '-1',
				'data-event-name' => 'addsection-sticky-header'
			],
			'quiet',
			'progressive',
			false,
			'#'
		);
	}

	/**
	 * Creates button data for the "search" button in the sticky header
	 *
	 * @param array $searchBoxData
	 * @return VectorComponentButton
	 */
	private function getSearchButton( $searchBoxData ) {
		return new VectorComponentButton(
			$this->msg( 'search' ),
			'search',
			'',
			'vector-sticky-header-search-toggle',
			[
				'tabindex' => '-1',
				'data-event-name' => 'ui.' . $searchBoxData['form-id'] . '.icon'
			],
			'quiet',
			'default',
			true
		);
	}

	/**
	 * @inheritDoc
	 */
	public function getTemplateData(): array {
		$iconButtonData = array_map( static function ( $btn ) {
			return $btn->getTemplateData();
		}, $this->getIconButtons() );
		$buttonData = $this->langButton ? [ $this->langButton->getTemplateData() ] : [];
		$buttonData[] = $this->getAddSectionButton()->getTemplateData();
		$searchBoxData = $this->search->getTemplateData();
		$searchButtonData = $this->getSearchButton( $searchBoxData )->getTemplateData();
		return [
			'array-icon-buttons' => $iconButtonData,
			'array-buttons' => $buttonData,
			'data-button-start' => $searchButtonData,
			'data-search' => $searchBoxData,
		];
	}
}
