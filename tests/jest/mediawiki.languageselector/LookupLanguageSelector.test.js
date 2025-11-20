const VueTestUtils = require( '@vue/test-utils' );

// Mock the CdxLookup component
jest.mock( '../../../resources/src/mediawiki.languageselector/codex.js', () => ( {
	CdxLookup: {
		name: 'CdxLookup',
		template: '<div class="cdx-lookup"><slot name="menu-item" :menu-item="{ label: \'test\', value: \'test\' }"></slot><slot name="no-results"></slot></div>',
		props: [ 'selected', 'inputValue', 'menuItems', 'menuConfig' ]
	}
} ), { virtual: true } );

// Mock supportedLanguages.json
jest.mock( '../../../resources/src/mediawiki.languageselector/supportedLanguages.json', () => ( {
	en: 'English',
	fr: 'Français',
	de: 'Deutsch'
} ), { virtual: true } );

const LookupLanguageSelector = require( '../../../resources/src/mediawiki.languageselector/LookupLanguageSelector.vue' );

const defaultProps = {
	searchApiUrl: 'https://en.wikipedia.org/w/api.php'
};

const mount = ( /** @type {Object} */ customProps, /** @type {Object} */ extraOptions = {} ) => VueTestUtils.mount( LookupLanguageSelector, {
	...extraOptions,
	props: Object.assign( {}, defaultProps, customProps ),
	global: {
		mocks: {
			$i18n: ( /** @type {string} */ str ) => ( {
				text: () => str
			} )
		}
	}
} );

describe( 'LookupLanguageSelector', () => {
	it( 'renders a lookup language selector component', () => {
		const wrapper = mount();
		expect( wrapper.findComponent( { name: 'CdxLookup' } ).exists() ).toBe( true );
	} );

	it( 'computes menu items for all languages when no search query', () => {
		const wrapper = mount();
		const languages = { en: 'English', fr: 'French', de: 'German' };

		const menuItems = wrapper.vm.computeMenuItems( '', [], languages );

		expect( menuItems.length ).toBe( 3 );
		expect( menuItems[ 0 ] ).toEqual( { label: 'English', value: 'en' } );
		expect( menuItems[ 1 ] ).toEqual( { label: 'French', value: 'fr' } );
		expect( menuItems[ 2 ] ).toEqual( { label: 'German', value: 'de' } );
	} );

	it( 'computes menu items for search results when query exists', () => {
		const wrapper = mount();
		const languages = { en: 'English', 'en-gb': 'British English', fr: 'French' };
		const searchResults = [ 'en', 'en-gb' ];

		const menuItems = wrapper.vm.computeMenuItems( 'en', searchResults, languages );

		expect( menuItems.length ).toBe( 2 );
		expect( menuItems[ 0 ] ).toEqual( { label: 'English', value: 'en' } );
		expect( menuItems[ 1 ] ).toEqual( { label: 'British English', value: 'en-gb' } );
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

	it( 'passes selected prop to LanguageSelector', () => {
		const wrapper = mount( { selected: 'fr' } );
		const languageSelector = wrapper.findComponent( { name: 'LanguageSelector' } );

		expect( languageSelector.props( 'selected' ) ).toBe( 'fr' );
	} );

	it( 'emits update:selected when language is selected', async () => {
		const wrapper = mount();
		const cdxLookup = wrapper.findComponent( { name: 'CdxLookup' } );

		await cdxLookup.vm.$emit( 'update:selected', 'fr' );

		expect( wrapper.emitted( 'update:selected' ) ).toBeTruthy();
		expect( wrapper.emitted( 'update:selected' )[ 0 ] ).toEqual( [ 'fr' ] );
	} );
} );
