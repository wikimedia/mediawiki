const VueTestUtils = require( '@vue/test-utils' );

jest.mock( '../../../resources/src/mediawiki.languageselector/supportedLanguages.json', () => ( {
	en: 'English',
	fr: 'Français',
	de: 'Deutsch'
} ), { virtual: true } );

jest.mock( '../../../resources/src/mediawiki.languageselector/languageSearch.js', () => jest.fn() );

const LanguageSelector = require( '../../../resources/src/mediawiki.languageselector/LanguageSelector.vue' );
const languageSearchClient = require( '../../../resources/src/mediawiki.languageselector/languageSearch.js' );

const defaultProps = {
	searchApiUrl: 'https://en.wikipedia.org/w/api.php'
};

const mount = ( customProps, scopedSlot ) => VueTestUtils.mount( LanguageSelector, {
	props: Object.assign( {}, defaultProps, customProps ),
	slots: {
		default: scopedSlot || ( `
			<template #default="props">
				<div>
					<div class="selected-code">{{ props.selection.value }}</div>
					<div class="selected-label">{{ props.selection.label }}</div>
					<div class="search-query">{{ props.searchQuery }}</div>
				</div>
			</template>
		` )
	}
} );

describe( 'LanguageSelector', () => {
	let mockSearchLanguages;

	beforeEach( () => {
		jest.clearAllMocks();
		mockSearchLanguages = jest.fn();
		languageSearchClient.mockReturnValue( {
			searchLanguages: mockSearchLanguages
		} );
	} );

	it( 'renders with slot content', () => {
		const wrapper = mount();
		expect( wrapper.find( '.selected-code' ).exists() ).toBe( true );
	} );

	it( 'computes selected language label', async () => {
		const wrapper = mount( {
			selected: 'en',
			selectableLanguages: { en: 'English', fr: 'French' }
		} );

		expect( wrapper.vm.selection.label ).toBe( 'English' );
	} );

	it( 'does not emit event when selecting same language', async () => {
		const wrapper = mount( { selected: 'en' } );
		wrapper.vm.onSelection( 'en' );

		expect( wrapper.emitted( 'update:selected' ) ).toBeFalsy();
	} );

	it( 'clears search results for empty query', async () => {
		jest.useFakeTimers();
		const wrapper = mount( { debounceDelayMs: 300 } );

		wrapper.vm.searchResults = [ 'en', 'fr' ];
		wrapper.vm.debouncedSearch( '' );
		jest.advanceTimersByTime( 300 );

		expect( wrapper.vm.searchResults ).toEqual( [] );
	} );

	it( 'debounces search calls', async () => {
		jest.useFakeTimers();
		mockSearchLanguages.mockReturnValue( Promise.resolve( { languagesearch: { en: 'English' } } ) );

		const wrapper = mount( { debounceDelayMs: 300 } );

		wrapper.vm.debouncedSearch( 'en' );
		expect( mockSearchLanguages ).not.toHaveBeenCalled();

		jest.advanceTimersByTime( 300 );
		await wrapper.vm.$nextTick();

		expect( mockSearchLanguages ).toHaveBeenCalledWith( 'en' );

		jest.useRealTimers();
	} );

	it( 'cleans up debounce timeout on unmount', () => {
		jest.useFakeTimers();
		const clearTimeoutSpy = jest.spyOn( global, 'clearTimeout' );

		const wrapper = mount();
		wrapper.vm.debouncedSearch( 'test' );
		wrapper.unmount();

		expect( clearTimeoutSpy ).toHaveBeenCalled();

		clearTimeoutSpy.mockRestore();
		jest.useRealTimers();
	} );

	it( 'sets initial language code from selected prop', async () => {
		const wrapper = mount( { selected: 'fr' } );
		expect( wrapper.vm.selection.value ).toBe( 'fr' );
	} );

	it( 'updates selectedLanguageCode when selected prop changes', async () => {
		const wrapper = mount( { selected: 'en' } );

		await wrapper.setProps( { selected: 'fr' } );
		expect( wrapper.vm.selection.value ).toBe( 'fr' );
	} );

	it( 'emits update:selected event when language is selected', async () => {
		const wrapper = mount( { selected: 'en' } );

		wrapper.vm.onSelection( 'fr' );

		expect( wrapper.emitted( 'update:selected' ) ).toBeTruthy();
		expect( wrapper.emitted( 'update:selected' )[ 0 ] ).toEqual( [ 'fr' ] );
	} );
} );
