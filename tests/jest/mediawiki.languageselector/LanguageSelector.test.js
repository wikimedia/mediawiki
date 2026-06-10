const VueTestUtils = require( '@vue/test-utils' );

// Mock the Codex components used by LanguageSelector.vue. The component renders
// CdxLookup for single selection and CdxMultiselectLookup when isMultiple is set.
jest.mock( '../../../resources/src/mediawiki.languageselector/codex.js', () => ( {
	CdxField: {
		name: 'CdxField',
		template: '<div class="cdx-field"><slot></slot></div>',
		props: [ 'status', 'messages', 'disabled' ]
	},
	CdxLookup: {
		name: 'CdxLookup',
		template: '<div class="cdx-lookup"><slot name="menu-item" :menu-item="{ label: \'test\', value: \'test\' }"></slot><slot name="no-results"></slot></div>',
		props: [ 'selected', 'inputValue', 'menuItems', 'menuConfig', 'placeholder', 'disabled', 'required' ]
	},
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
		props: [ 'selected', 'inputValue', 'inputChips', 'menuItems', 'menuConfig', 'placeholder', 'disabled', 'required' ]
	}
} ), { virtual: true } );

const LanguageSelector = require( '../../../resources/src/mediawiki.languageselector/LanguageSelector.vue' );

const defaultProps = {
	searchApiUrl: 'https://en.wikipedia.org/w/api.php',
	selectableLanguages: {
		en: 'English',
		fr: 'Français',
		de: 'Deutsch'
	}
};

const mount = ( /** @type {Object} */ customProps, /** @type {Object} */ extraOptions = {} ) => VueTestUtils.mount( LanguageSelector, {
	...extraOptions,
	props: Object.assign( {}, defaultProps, customProps ),
	global: Object.assign( {
		mocks: {
			$i18n: ( /** @type {string} */ str ) => ( {
				text: () => str
			} )
		}
	}, extraOptions.global || {} )
} );

describe( 'LanguageSelector (single selection)', () => {
	it( 'renders a lookup language selector component', () => {
		const wrapper = mount();
		expect( wrapper.findComponent( { name: 'CdxLookup' } ).exists() ).toBe( true );
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
		const cdxLookup = wrapper.findComponent( { name: 'CdxLookup' } );

		await cdxLookup.vm.$emit( 'update:input-value', 'fr' );

		expect( wrapper.vm.inputValue ).toBe( 'fr' );
	} );

	it( 'input value is retained on selection', async () => {
		const wrapper = mount();
		const cdxLookup = wrapper.findComponent( { name: 'CdxLookup' } );

		wrapper.vm.inputValue = 'fr';
		await cdxLookup.vm.$emit( 'update:selected', 'fr' );

		expect( wrapper.vm.inputValue ).toBe( 'fr' );
	} );

	it( 'passes menuConfig prop to CdxLookup', () => {
		const menuConfig = { visibleItemLimit: 8 };
		const wrapper = mount( { menuConfig } );

		const cdxLookup = wrapper.findComponent( { name: 'CdxLookup' } );
		expect( cdxLookup.props( 'menuConfig' ) ).toEqual( menuConfig );
	} );

	it( 'passes placeholder prop to CdxLookup', () => {
		const placeholder = 'Search for a language';
		const wrapper = mount( { placeholder } );

		const cdxLookup = wrapper.findComponent( { name: 'CdxLookup' } );
		expect( cdxLookup.props( 'placeholder' ) ).toBe( placeholder );
	} );

	it( 'renders custom menu-item slot content', () => {
		const wrapper = mount( {}, {
			slots: {
				'menu-item': '<div class="custom-item">Custom</div>'
			}
		} );

		expect( wrapper.find( '.custom-item' ).exists() ).toBe( true );
	} );

	it( 'renders custom no-results slot content', () => {
		const wrapper = mount( {}, {
			slots: {
				'no-results': '<div class="custom-no-results">Nothing found</div>'
			}
		} );

		expect( wrapper.find( '.custom-no-results' ).exists() ).toBe( true );
	} );

	it( 'provides default no-results message', () => {
		const wrapper = mount();

		// The component should render the i18n message
		expect( wrapper.html() ).toContain( 'languageselector-no-results' );
	} );

	it( 'emits update:selected when language is selected', async () => {
		const wrapper = mount();
		const cdxLookup = wrapper.findComponent( { name: 'CdxLookup' } );

		await cdxLookup.vm.$emit( 'update:selected', 'fr' );

		expect( wrapper.emitted( 'update:selected' ) ).toBeTruthy();
		expect( wrapper.emitted( 'update:selected' )[ 0 ] ).toEqual( [ 'fr' ] );
	} );

	it( 'passes disabled prop to CdxField and CdxLookup', () => {
		const wrapper = mount( { disabled: true } );

		const cdxField = wrapper.findComponent( { name: 'CdxField' } );
		expect( cdxField.props( 'disabled' ) ).toBe( true );

		const cdxLookup = wrapper.findComponent( { name: 'CdxLookup' } );
		expect( cdxLookup.props( 'disabled' ) ).toBe( true );
	} );

	it( 'passes required prop to CdxLookup', () => {
		const wrapper = mount( { required: true } );

		const cdxLookup = wrapper.findComponent( { name: 'CdxLookup' } );
		expect( cdxLookup.props( 'required' ) ).toBe( true );
	} );
} );

describe( 'LanguageSelector (multiple selection)', () => {
	const mountMultiple = ( customProps, extraOptions ) => mount(
		Object.assign( { isMultiple: true }, customProps ),
		extraOptions
	);

	it( 'renders a multiselect lookup language selector component', () => {
		const wrapper = mountMultiple();
		expect( wrapper.findComponent( { name: 'CdxMultiselectLookup' } ).exists() ).toBe( true );
	} );

	it( 'sets selectable languages when no search query is specified', () => {
		const languages = { en: 'English', fr: 'French', de: 'German' };
		const wrapper = mountMultiple( { selectableLanguages: languages } );

		const menuItems = wrapper.vm.menuItems;

		expect( menuItems.length ).toBe( 3 );
		expect( menuItems[ 0 ] ).toEqual( { label: 'English', value: 'en' } );
		expect( menuItems[ 1 ] ).toEqual( { label: 'French', value: 'fr' } );
		expect( menuItems[ 2 ] ).toEqual( { label: 'German', value: 'de' } );
	} );

	it( 'updates input value on input', async () => {
		const wrapper = mountMultiple();
		const cdxMultiselectLookup = wrapper.findComponent( { name: 'CdxMultiselectLookup' } );

		await cdxMultiselectLookup.vm.$emit( 'update:input-value', 'fr' );

		expect( wrapper.vm.inputValue ).toBe( 'fr' );
	} );

	it( 'resets input value on selection', async () => {
		const wrapper = mountMultiple();
		const cdxMultiselectLookup = wrapper.findComponent( { name: 'CdxMultiselectLookup' } );

		wrapper.vm.inputValue = 'fr';
		await cdxMultiselectLookup.vm.$emit( 'update:selected', [ 'fr' ] );

		expect( wrapper.vm.inputValue ).toBe( '' );
	} );

	it( 'passes menuConfig prop to CdxMultiselectLookup', () => {
		const menuConfig = { visibleItemLimit: 8 };
		const wrapper = mountMultiple( { menuConfig } );

		const cdxMultiselectLookup = wrapper.findComponent( { name: 'CdxMultiselectLookup' } );
		expect( cdxMultiselectLookup.props( 'menuConfig' ) ).toEqual( menuConfig );
	} );

	it( 'passes placeholder prop to CdxMultiselectLookup', () => {
		const placeholder = 'Search for a language';
		const wrapper = mountMultiple( { placeholder } );

		const cdxMultiselectLookup = wrapper.findComponent( { name: 'CdxMultiselectLookup' } );
		expect( cdxMultiselectLookup.props( 'placeholder' ) ).toBe( placeholder );
	} );

	it( 'emits update:selected when language is selected', async () => {
		const wrapper = mountMultiple();
		const cdxMultiselectLookup = wrapper.findComponent( { name: 'CdxMultiselectLookup' } );

		await cdxMultiselectLookup.vm.$emit( 'update:selected', [ 'fr' ] );

		expect( wrapper.emitted( 'update:selected' ) ).toBeTruthy();
		expect( wrapper.emitted( 'update:selected' )[ 0 ] ).toEqual( [ [ 'fr' ] ] );
	} );

	it( 'emits update:selected when a chip is removed (update:input-chips)', async () => {
		const wrapper = mountMultiple();
		const cdxMultiselectLookup = wrapper.findComponent( { name: 'CdxMultiselectLookup' } );

		await cdxMultiselectLookup.vm.$emit( 'update:input-chips', [ { value: 'fr', label: 'French' } ] );

		expect( wrapper.emitted( 'update:selected' ) ).toBeTruthy();
		expect( wrapper.emitted( 'update:selected' )[ 0 ] ).toEqual( [ [ 'fr' ] ] );
	} );

	it( 'renders the #menu-item slot with correct data', () => {
		const wrapper = mountMultiple( {}, {
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
		const wrapper = mountMultiple( {}, {
			slots: {
				'no-results': '<div class="custom-no-results">No results found</div>'
			}
		} );

		expect( wrapper.find( '.custom-no-results' ).exists() ).toBe( true );
		expect( wrapper.find( '.custom-no-results' ).text() ).toBe( 'No results found' );
	} );

	it( 'passes disabled prop to CdxField and CdxMultiselectLookup', () => {
		const wrapper = mountMultiple( { disabled: true } );

		const cdxField = wrapper.findComponent( { name: 'CdxField' } );
		expect( cdxField.props( 'disabled' ) ).toBe( true );

		const cdxMultiselectLookup = wrapper.findComponent( { name: 'CdxMultiselectLookup' } );
		expect( cdxMultiselectLookup.props( 'disabled' ) ).toBe( true );
	} );

	it( 'passes required prop to CdxMultiselectLookup', () => {
		const wrapper = mountMultiple( { required: true } );

		const cdxMultiselectLookup = wrapper.findComponent( { name: 'CdxMultiselectLookup' } );
		expect( cdxMultiselectLookup.props( 'required' ) ).toBe( true );
	} );
} );
