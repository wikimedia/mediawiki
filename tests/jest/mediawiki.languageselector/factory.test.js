// Mock the CdxLookup component
jest.mock( '../../../resources/src/mediawiki.languageselector/codex.js', () => ( {
	CdxLookup: {
		name: 'CdxLookup',
		template: '<div class="cdx-lookup"><slot name="menu-item" :menu-item="{ label: \'test\', value: \'test\' }"></slot><slot name="no-results"></slot></div>',
		props: [ 'selected', 'inputValue', 'menuItems', 'menuConfig' ]
	}
} ), { virtual: true } );

jest.mock( '../../../resources/src/mediawiki.languageselector/supportedLanguages.json', () => ( {
	en: 'English',
	fr: 'Français',
	de: 'Deutsch'
} ), { virtual: true } );

const getLookupLanguageSelector = require( '../../../resources/src/mediawiki.languageselector/factory.js' );
const Vue = require( 'vue' );

// Mock mw global
global.mw = {
	util: {
		wikiScript: jest.fn().mockReturnValue( '/w/api.php' )
	}
};

// Mock Vue.createMwApp
Vue.createMwApp = jest.fn( ( options ) => ( {
	mockOptions: options,
	mount: jest.fn()
} ) );

describe( 'getLookupLanguageSelector', () => {
	it( 'calls onLanguageChange callback when language is selected', () => {
		const onLanguageChange = jest.fn();
		const app = getLookupLanguageSelector( { onLanguageChange } );
		const vm = app.mockOptions;
		const data = vm.data();
		const context = {
			...data
		};

		const vnode = vm.render.call( context );
		vnode.props[ 'onUpdate:selected' ]( 'fr' );

		expect( onLanguageChange ).toHaveBeenCalledWith( 'fr' );
		expect( context.selectedLanguage ).toBe( 'fr' );
	} );

	it( 'does not fail if onLanguageChange is not provided', () => {
		const app = getLookupLanguageSelector( {} );
		const vm = app.mockOptions;
		const data = vm.data();
		const context = {
			...data
		};

		const vnode = vm.render.call( context );
		vnode.props[ 'onUpdate:selected' ]( 'fr' );

		expect( context.selectedLanguage ).toBe( 'fr' );
	} );
} );
