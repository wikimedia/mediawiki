const VueTestUtils = require( '@vue/test-utils' );

// Mock the CdxMultiselectLookup component
jest.mock( '../../../resources/src/mediawiki.languageselector/codex.js', () => ( {
	CdxMultiselectLookup: {
		name: 'CdxMultiselectLookup',
		template: `
			<div class="cdx-multiselect-lookup">
				<div v-for="element in menuItems" :key="element.value" class="mock-menu-item">
					<slot name="menu-item" :menu-item="element"></slot>
				</div>
				<div class="mock-no-results">
					<slot name="no-results"></slot>
				</div>
			</div>
		`,
		props: [ 'selected', 'inputValue', 'inputChips', 'menuItems', 'menuConfig' ]
	}
} ), { virtual: true } );

// Mock supportedLanguages.json
jest.mock( '../../../resources/src/mediawiki.languageselector/supportedLanguages.json', () => ( {
	en: 'English',
	fr: 'Français',
	de: 'Deutsch'
} ), { virtual: true } );

const MultiselectLookupLanguageSelector = require( '../../../resources/src/mediawiki.languageselector/MultiselectLookupLanguageSelector.vue' );

const defaultProps = {
	searchApiUrl: 'https://en.wikipedia.org/w/api.php'
};

const mount = ( customProps, options = {} ) => VueTestUtils.mount( MultiselectLookupLanguageSelector, {
	props: Object.assign( {}, defaultProps, customProps ),
	...options
} );

describe( 'MultiselectLookupLanguageSelector', () => {
	it( 'renders a multiselect lookup language selector component', () => {
		const wrapper = mount();
		expect( wrapper.findComponent( { name: 'CdxMultiselectLookup' } ).exists() ).toBe( true );
	} );

	it( 'sets selectable languages when no search query is specified', () => {
		const languages = { en: 'English', fr: 'French', de: 'German' };
		const wrapper = mount( { selectableLanguages: languages } );

		const menuItems = wrapper.vm.menuItems;

		expect( menuItems.length ).toBe( 3 );
		expect( menuItems[ 0 ] ).toEqual( { label: 'English', value: 'en' } );
		expect( menuItems[ 1 ] ).toEqual( { label: 'French', value: 'fr' } );
		expect( menuItems[ 2 ] ).toEqual( { label: 'German', value: 'de' } );
	} );

	it( 'updates input value on input', async () => {
		const wrapper = mount();
		const cdxMultiselectLookup = wrapper.findComponent( { name: 'CdxMultiselectLookup' } );

		await cdxMultiselectLookup.vm.$emit( 'update:input-value', 'fr' );

		expect( wrapper.vm.inputValue ).toBe( 'fr' );
	} );

	it( 'resets input value on selection', async () => {
		const wrapper = mount();
		const cdxMultiselectLookup = wrapper.findComponent( { name: 'CdxMultiselectLookup' } );

		wrapper.vm.inputValue = 'fr';
		await cdxMultiselectLookup.vm.$emit( 'update:selected', [ 'fr' ] );

		expect( wrapper.vm.inputValue ).toBe( '' );
	} );

	it( 'resets input value on input chips update', async () => {
		const wrapper = mount();
		const cdxMultiselectLookup = wrapper.findComponent( { name: 'CdxMultiselectLookup' } );

		wrapper.vm.inputValue = 'fr';
		await cdxMultiselectLookup.vm.$emit( 'update:input-chips', [ { value: 'fr', label: 'French' } ] );

		expect( wrapper.vm.inputValue ).toBe( '' );
	} );

	it( 'passes menuConfig prop to CdxMultiselectLookup', () => {
		const menuConfig = { visibleItemLimit: 8 };
		const wrapper = mount( { menuConfig } );

		const cdxMultiselectLookup = wrapper.findComponent( { name: 'CdxMultiselectLookup' } );
		expect( cdxMultiselectLookup.props( 'menuConfig' ) ).toEqual( menuConfig );
	} );

	it( 'emits update:selected when language is selected via update:selected', async () => {
		const wrapper = mount();
		const cdxMultiselectLookup = wrapper.findComponent( { name: 'CdxMultiselectLookup' } );

		await cdxMultiselectLookup.vm.$emit( 'update:selected', [ 'fr' ] );

		expect( wrapper.emitted( 'update:selected' ) ).toBeTruthy();
		expect( wrapper.emitted( 'update:selected' )[ 0 ] ).toEqual( [ [ 'fr' ] ] );
	} );

	it( 'emits update:selected when language is selected via update:input-chips', async () => {
		const wrapper = mount();
		const cdxMultiselectLookup = wrapper.findComponent( { name: 'CdxMultiselectLookup' } );

		await cdxMultiselectLookup.vm.$emit( 'update:input-chips', [ { value: 'fr', label: 'French' } ] );

		expect( wrapper.emitted( 'update:selected' ) ).toBeTruthy();
		expect( wrapper.emitted( 'update:selected' )[ 0 ] ).toEqual( [ [ 'fr' ] ] );
	} );

	it( 'renders the #menu-item slot with correct data', () => {
		const wrapper = mount( {}, {
			slots: {
				'menu-item': `
					<template #menu-item="{ menuItem, languageCode, languageName }">
						<div class="custom-menu-item">
							{{ menuItem.label }} ({{ languageCode }}) - {{ languageName }}
						</div>
					</template>
				`
			}
		} );

		const items = wrapper.findAll( '.custom-menu-item' );
		expect( items.length ).toBe( 3 );
		expect( items[ 0 ].text() ).toBe( 'English (en) - English' );
		expect( items[ 1 ].text() ).toBe( 'Français (fr) - Français' );
		expect( items[ 2 ].text() ).toBe( 'Deutsch (de) - Deutsch' );
	} );

	it( 'renders the #no-results slot', () => {
		const wrapper = mount( {}, {
			slots: {
				'no-results': '<div class="custom-no-results">No results found</div>'
			}
		} );

		expect( wrapper.find( '.custom-no-results' ).exists() ).toBe( true );
		expect( wrapper.find( '.custom-no-results' ).text() ).toBe( 'No results found' );
	} );
} );
