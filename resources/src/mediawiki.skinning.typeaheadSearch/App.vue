<template>
	<typeahead-search-wrapper
		:class="containerClasses"
		:mobile-experience="useMobileExperience"
		@exit="onExit"
	>
		<cdx-typeahead-search
			:id="id"
			ref="searchForm"
			:class="rootClasses"
			:search-results-label="$i18n( 'searchresults' ).text()"
			:accesskey="searchAccessKey"
			:autocapitalize="autocapitalizeValue"
			:title="searchTitle"
			:placeholder="searchPlaceholder"
			:aria-label="searchPlaceholder"
			:initial-input-value="searchQuery"
			:button-label="searchButtonLabel"
			:form-action="action"
			:show-thumbnail="showThumbnail"
			:highlight-query="highlightQuery"
			:auto-expand-width="autoExpandWidth"
			:search-results="suggestions"
			:search-footer-url="searchFooterUrl"
			:visible-item-limit="visibleItemLimit"
			:use-button="!!searchButtonLabel"
			:show-empty-query-results="showEmptySearchRecommendations"
			@load-more="onLoadMore"
			@input="onInput"
			@search-result-click="instrumentation.onSuggestionClick"
			@submit="onSubmit"
			@focus="onFocus"
			@blur="onBlur"
		>
			<template #default>
				<input
					type="hidden"
					name="title"
					:value="searchPageTitle"
				>
				<input
					type="hidden"
					name="wprov"
					:value="wprov"
				>
			</template>
			<template #search-results-pending>
				{{ $i18n( 'search-loader' ).text() }}
			</template>
			<!-- eslint-disable-next-line vue/no-template-shadow -->
			<template #search-footer-text="{ searchQuery }">
				<span v-i18n-html:searchsuggest-containing-html="[ searchQuery ]"></span>
			</template>
		</cdx-typeahead-search>
	</typeahead-search-wrapper>
</template>

<script>
const TypeaheadSearchWrapper = require( './TypeaheadSearchWrapper.vue' );
const { CdxTypeaheadSearch } = require( 'mediawiki.codex.typeaheadSearch' ),
	{ defineComponent, nextTick, ref } = require( 'vue' ),
	instrumentation = require( './instrumentation.js' );

// @vue/component
module.exports = exports = defineComponent( {
	name: 'App',
	compilerOptions: {
		whitespace: 'condense'
	},
	components: {
		TypeaheadSearchWrapper,
		CdxTypeaheadSearch
	},
	props: {
		router: {
			type: Object
		},
		searchRoute: {
			type: RegExp,
			default: new RegExp( /\/search/ )
		},
		urlGenerator: {
			type: Object,
			required: true
		},
		restClient: {
			type: Object,
			required: true
		},
		prefixClass: {
			type: String,
			default: 'skin-'
		},
		id: {
			type: String,
			required: true
		},
		autocapitalizeValue: {
			type: String,
			default: undefined
		},
		searchPageTitle: {
			type: String,
			default: 'Special:Search'
		},
		searchButtonLabel: {
			type: String,
			default: mw.msg( 'searchbutton' )
		},
		autofocusInput: {
			type: Boolean,
			default: false
		},
		action: {
			type: String,
			default: ''
		},
		/** The keyboard shortcut to focus search. */
		searchAccessKey: {
			type: String,
			default: undefined
		},
		/** The access key informational tip for search. */
		searchTitle: {
			type: String,
			default: undefined
		},
		/** The ghost text shown when no search query is entered. */
		searchPlaceholder: {
			type: String,
			default: undefined
		},
		supportsMobileExperience: {
			type: Boolean,
			default: false
		},
		/**
		 * The search query string taken from the server-side rendered input immediately before
		 * client render.
		 */
		searchQuery: {
			type: String,
			default: undefined
		},
		showThumbnail: {
			type: Boolean,
			required: true,
			default: false
		},
		showDescription: {
			type: Boolean,
			default: false
		},
		highlightQuery: {
			type: Boolean,
			default: false
		},
		autoExpandWidth: {
			type: Boolean,
			default: false
		},
		showEmptySearchRecommendations: {
			type: Boolean,
			default: false
		}
	},
	setup( props ) {
		// max-width-breakpoint-mobile
		const mobileMedia = window.matchMedia ?
			window.matchMedia( '(max-width: 639px)' ) : {
				matches: false
			};
		const { clearAddressBar } = require( 'mediawiki.page.ready' );
		const useMobileExperience = ref( props.supportsMobileExperience && mobileMedia.matches );
		const router = props.router;
		const searchRoute = props.searchRoute;
		// Whether to apply a CSS class that disables the CSS transitions on the text input
		const disableTransitions = ref( props.autofocusInput );

		const exitSearchDialog = () => {
			useMobileExperience.value = false;
		};
		const onExit = () => {
			exitSearchDialog();
			if ( router ) {
				clearAddressBar( router, searchRoute );
			}
		};

		if ( props.supportsMobileExperience && router ) {
			router.addRoute( /.*$/, () => {
				exitSearchDialog();
			} );

			// replace existing route with one that toggles the dialog on.
			router.addRoute( searchRoute, () => {
				useMobileExperience.value = true;
				disableTransitions.value = true;
			} );

			// Only support on mobile resolutions
			mobileMedia.onchange = () => {
				if ( !mobileMedia.matches ) {
					exitSearchDialog();
					clearAddressBar( router, searchRoute );
				}
			};
		}

		return {
			useMobileExperience,
			disableTransitions,
			onExit
		};
	},
	data() {
		return {
			// -1 here is the default "active suggestion index".
			wprov: instrumentation.getWprovFromResultIndex( -1 ),

			// Suggestions to be shown in the TypeaheadSearch menu.
			suggestions: [],

			// Link to the search page for the current search query.
			searchFooterUrl: '',

			// The current search query. Used to detect whether a fetch response is stale.
			currentSearchQuery: '',

			instrumentation: instrumentation.listeners,

			isFocused: false
		};
	},
	computed: {
		containerClasses() {
			const prefix = this.prefixClass;
			return {
				[ `${ prefix }typeahead-search-wrapper` ]: true
			};
		},
		rootClasses() {
			const prefix = this.prefixClass;
			return {
				[ `${ prefix }typeahead-search` ]: true,
				[ `${ prefix }search-box-disable-transitions` ]: this.disableTransitions,
				[ `${ prefix }typeahead-search--active` ]: this.isFocused
			};
		},
		visibleItemLimit() {
			// if the search client supports loading more results,
			// show 7 out of 10 results at first (arbitrary number),
			// so that scroll events are fired and trigger onLoadMore()
			return this.restClient.loadMore ? 7 : null;
		}
	},
	methods: {
		/**
		 * Fetch suggestions when new input is received.
		 *
		 * @param {string} value
		 */
		onInput: function ( value ) {
			const query = value.trim();

			this.currentSearchQuery = query;

			if ( query === '' ) {
				this.loadEmptySearchRecommendations();
			} else {
				this.updateUIWithSearchClientResult(
					this.restClient.fetchByTitle( query, 10, this.showDescription ),
					true
				);
			}
		},

		/**
		 * Fetch additional suggestions.
		 *
		 * This should only be called if visibleItemLimit is non-null,
		 * i.e. if the search client supports loading more results.
		 */
		onLoadMore() {
			if ( !this.restClient.loadMore ) {
				mw.log.warn( 'onLoadMore() should not have been called for this search client' );
				return;
			}

			this.updateUIWithSearchClientResult(
				this.restClient.loadMore(
					this.currentSearchQuery,
					this.suggestions.length,
					10,
					this.showDescription
				),
				false
			);
		},

		/**
		 * @param {AbortableSearchFetch} search
		 * @param {boolean} replaceResults
		 */
		updateUIWithSearchClientResult( search, replaceResults ) {
			const query = this.currentSearchQuery;
			search.fetch
				.then( ( data ) => {
					// Only use these results if they're still relevant
					// If currentSearchQuery !== query, these results are for a previous search
					// and we shouldn't show them.
					if ( this.currentSearchQuery === query ) {
						if ( replaceResults ) {
							this.suggestions = [];
						}
						this.suggestions.push(
							...instrumentation.addWprovToSearchResultUrls(
								data.results, this.suggestions.length
							)
						);
						this.searchFooterUrl = this.urlGenerator.generateUrl( query );
					}

					const event = {
						numberOfResults: data.results.length,
						query: query
					};
					instrumentation.listeners.onFetchEnd( event );
				} )
				.catch( () => {
					// TODO: error handling
				} );
		},

		loadEmptySearchRecommendations() {
			const fetchRecommendations = this.restClient.fetchRecommendationByTitle;
			// Check empty search recommendations is enabled and the API supports recommendations
			if ( this.showEmptySearchRecommendations && fetchRecommendations ) {
				const currentTitle = mw.config.get( 'wgPageName' );
				this.updateUIWithSearchClientResult(
					this.restClient.fetchRecommendationByTitle( currentTitle, this.showDescription ),
					true
				);
			}
		},

		/**
		 * @param {SearchSubmitEvent} event
		 */
		onSubmit( event ) {
			this.wprov = instrumentation.getWprovFromResultIndex( event.index );

			instrumentation.listeners.onSubmit( event );
		},

		onFocus() {
			this.isFocused = true;
			if ( this.currentSearchQuery === '' ) {
				this.loadEmptySearchRecommendations();
			}
		},

		onBlur() {
			this.isFocused = false;
		},

		focus() {
			this.$refs.searchForm.focus();
			nextTick( () => {
				this.disableTransitions = false;
			} );
		}
	},
	updated() {
		if ( this.autofocusInput ) {
			nextTick( () => {
				this.focus();
			} );
		}
	},
	mounted() {
		if ( this.autofocusInput ) {
			nextTick( () => {
				this.focus();
			} );
		}
	}
} );
</script>

<style lang="less">
@import 'mediawiki.skin.variables.less';

@media screen {
	/* stylelint-disable-next-line selector-class-pattern */
	.cdx-dialog.skin-dialog-search {
		position: fixed;
		top: 0;
		left: 0;
		right: 0;
		bottom: 0;
		box-sizing: border-box;
		width: 100%;
		max-width: none;
		max-height: none;
		background: transparent;

		@media ( max-width: @max-width-breakpoint-mobile ) {
			background-color: @background-color-base;
		}

		.cdx-dialog__header {
			padding: 0;
			background-color: @background-color-interactive;
			display: flex;
			justify-content: center;
			flex-direction: column;
		}

		.cdx-dialog__header > div {
			display: flex;
			box-sizing: border-box;
			padding: 0 8px;
			align-items: center;
			height: 55px;

			.cdx-typeahead-search {
				flex-grow: 1;
			}
		}

		@media ( max-width: @max-width-breakpoint-mobile ) {
			.cdx-menu {
				top: 55px;
				left: 0;
				right: 0;
				position: fixed;
				bottom: 0;
				margin-top: -1px;
			}
		}
	}
}
</style>
