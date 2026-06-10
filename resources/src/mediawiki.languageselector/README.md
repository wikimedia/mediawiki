# LanguageSelector

A set of [Codex](https://doc.wikimedia.org/codex/latest/)-based building blocks for
letting users search for and select one or more languages. Languages are looked up
through the MediaWiki `languagesearch` API action, and the default language list is
derived from `LanguageNameUtils::getLanguageNames()`.

The module ships three things you can use depending on how much control you need:

1. **`LanguageSelector`** — a ready-made Vue component for single- or multi-select.
2. **`getLookupLanguageSelector` / `getMultiselectLookupLanguageSelector`** — factory
   functions that mount the component for you from plain JavaScript (no Vue knowledge
   required).
3. **`useLanguageSelector`** — a composable for building your own component.

## Modules

| ResourceLoader module | Exports | Use when |
| --- | --- | --- |
| `mediawiki.languageselector.lookup` | `LanguageSelector`, `getLookupLanguageSelector`, `getMultiselectLookupLanguageSelector` | You want the Codex UI (Vue component or JS factories). |
| `mediawiki.languageselector.core` | `useLanguageSelector` | You only need the headless selection/search logic. |

In most cases, depend on `mediawiki.languageselector.lookup` and require what you need from it.

## LanguageSelector (Vue component)

A single Codex component that renders a [`CdxLookup`](https://doc.wikimedia.org/codex/latest/components/demos/lookup.html)
for single selection or a [`CdxMultiselectLookup`](https://doc.wikimedia.org/codex/latest/components/demos/multiselect-lookup.html)
for multiple selection, wrapped in a `CdxField` so it can show validation messages.
Toggle between the two modes with the `isMultiple` prop.

```js
const { LanguageSelector } = require( 'mediawiki.languageselector.lookup' );
```

```vue
<template>
	<language-selector
		v-model:selected="selected"
		:is-multiple="false"
		:search-api-url="searchApiUrl"
		:selectable-languages="selectableLanguages"
		placeholder="Select a language"
	></language-selector>
</template>

<script>
const { ref } = require( 'vue' );
const { LanguageSelector } = require( 'mediawiki.languageselector.lookup' );

module.exports = {
	components: { LanguageSelector },
	setup() {
		return {
			selected: ref( null ),
			searchApiUrl: mw.util.wikiScript( 'api' ),
			selectableLanguages: { en: 'English', fr: 'français', de: 'Deutsch' }
		};
	}
};
</script>
```

### Props

| Prop | Type | Default | Description |
| --- | --- | --- | --- |
| `isMultiple` | `Boolean` | `false` | Render a multiselect lookup instead of a single lookup. |
| `searchApiUrl` | `String` | `mw.util.wikiScript( 'api' )` | URL of the MediaWiki API endpoint used for the `languagesearch` request. |
| `selected` | `String` | `Array` | `null` | The current selection. A language code (`String`) when `isMultiple` is `false`, or an array of codes when `true`. Use with `v-model:selected`. |
| `selectableLanguages` | `Object` | `null` | A map of `languageCode → languageName` to restrict the choices. When `null`, the search is disabled. |
| `debounceDelayMs` | `Number` | `300` | Delay applied to the search API request as the user types. |
| `menuConfig` | `Object` | `{}` | Passed straight through to the underlying Codex lookup's `menu-config`. |
| `placeholder` | `String` | `''` | Placeholder text for the input. |
| `inputId` | `String` | `''` | `id` attribute for the input, useful for associating an external `<label>`. |
| `disabled` | `Boolean` | `false` | Disable the input. |
| `required` | `Boolean` | `false` | Mark the field as required. |

### Events

| Event | Payload | Description |
| --- | --- | --- |
| `update:selected` | `String` | `String[]` | Emitted when the selection changes. The payload is a single language code in single-select mode, or an array of codes in multiselect mode. Enables `v-model:selected`. |

### Slots

| Slot | Slot props | Description |
| --- | --- | --- |
| `menu-item` | `menuItem`, `languageCode`, `languageName` | Customize how each result is rendered. Defaults to the language name. |
| `no-results` | `searchQuery` | Customize the empty-state message. Defaults to the `languageselector-no-results` message. |

## Using it from plain JavaScript

If you are not writing a Vue component, use the factory functions to create and mount a
selector. They return a Vue application instance; call `.mount()` with a selector or
element.

### Single selection — `getLookupLanguageSelector`

```js
const { getLookupLanguageSelector } = require( 'mediawiki.languageselector.lookup' );

getLookupLanguageSelector( {
	selectedLanguage: 'en',
	selectableLanguages: { en: 'English', fr: 'français', de: 'Deutsch' },
	placeholder: 'Select a language',
	onLanguageChange: ( code ) => {
		mw.log( 'Selected language:', code );
	}
} ).mount( '#my-language-selector' );
```

### Multiple selection — `getMultiselectLookupLanguageSelector`

```js
const { getMultiselectLookupLanguageSelector } = require( 'mediawiki.languageselector.lookup' );

getMultiselectLookupLanguageSelector( {
	selectedLanguage: [ 'en', 'fr' ],
	selectableLanguages: { en: 'English', fr: 'français', de: 'Deutsch' },
	onLanguageChange: ( codes ) => {
		mw.log( 'Selected languages:', codes );
	}
} ).mount( '#my-language-selector' );
```

### Configuration object

Both factories accept the same configuration object:

| Property | Type | Default | Description |
| --- | --- | --- | --- |
| `selectedLanguage` | `String` | `Array` | `null` | Initial selection. A language code for the single selector, or an array of codes for the multiselect. |
| `selectableLanguages` | `Object` | `null` | Map of `languageCode → languageName` to restrict the choices. When `null`, the search is disabled. |
| `debounceDelayMs` | `Number` | `300` | Delay applied to the search API request as the user types. |
| `apiUrl` | `String` | `mw.util.wikiScript( 'api' )` | API endpoint used for the language search. |
| `placeholder` | `String` | `null` | Placeholder text for the input. |
| `menuConfig` | `Object` | `{}` | Passed through to the Codex lookup's `menu-config`. |
| `menuItemSlot` | `Function` | `null` | A render function for the `menu-item` slot, to customize how results display. |
| `inputId` | `String` | `''` | `id` attribute for the input. |
| `disabled` | `Boolean` | `false` | Disable the input. |
| `required` | `Boolean` | `false` | Mark the field as required. |
| `onLanguageChange` | `Function` | `null` | Callback invoked with the new selection whenever it changes. |

## Build your own — `useLanguageSelector`

If neither the component nor the factories fit your needs, the `useLanguageSelector`
composable provides the underlying search, filtering, and selection state so you can
wire it into your own Vue component. It lives in the `mediawiki.languageselector.core`
module and has no Codex dependency.

```js
const { useLanguageSelector } = require( 'mediawiki.languageselector.core' );
const { ref, toRefs } = require( 'vue' );

const selectableLanguages = ref( { en: 'English', fr: 'français' } );
const selected = ref( null );

const {
	languages,
	search,
	searchResults,
	searchQuery,
	clearSearchQuery,
	selection,
	selectedValues,
	isSelectionUpdated
} = useLanguageSelector(
	selectableLanguages,
	selected,
	mw.util.wikiScript( 'api' ),
	300, // debounceDelayMs
	false // isMultiple
);
```

### Parameters

| Parameter | Type | Default | Description |
| --- | --- | --- | --- |
| `selectableLanguages` | `Ref<Object>` | `Object` | — | Map of `languageCode → languageName` to restrict the choices. Search results are filtered to this set when provided. |
| `selected` | `Ref<String | String[]>` | — | The current selection. |
| `searchApiUrl` | `String` | — | API endpoint used for the language search. |
| `debounceDelayMs` | `Number` | — | Delay applied to the search API request. |
| `isMultiple` | `Boolean` | `false` | Whether multiple selection is allowed. |

### Return value

| Key | Type | Description |
| --- | --- | --- |
| `languages` | `ComputedRef<Object>` | The resolved `selectableLanguages` map (empty object when none provided). |
| `search` | `Function` | Run a search for the given query string (debounced internally). |
| `searchQuery` | `Ref<String>` | The current search query. |
| `searchResults` | `Ref<String[]>` | Language codes matching the current query, filtered to `selectableLanguages`. |
| `searchQueryHits` | `Ref<Object>` | Raw `languagesearch` API response for the last query. |
| `isSearching` | `Ref<Boolean>` | Whether a search request is in flight. |
| `clearSearchQuery` | `Function` | Reset the query and cancel any pending search. |
| `selection` | `ComputedRef` | The selected item(s) as `{ value, label }` object(s). |
| `selectedValues` | `ComputedRef<String | String[]>` | The selected language code(s). |
| `isSelectionUpdated` | `Function` | Returns `true` if a candidate value differs from the current selection. |

## See also

* The [`languagesearch` API action](https://www.mediawiki.org/wiki/Special:ApiSandbox#action=languagesearch&format=json&search=fr&formatversion=2) that powers the search.
* [Codex Lookup](https://doc.wikimedia.org/codex/latest/components/demos/lookup.html) and [Multiselect Lookup](https://doc.wikimedia.org/codex/latest/components/demos/multiselect-lookup.html) components.
