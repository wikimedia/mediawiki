const { defineComponent, toRefs } = require( 'vue' );
const VueTestUtils = require( '@vue/test-utils' );

jest.mock( '../../../resources/src/mediawiki.languageselector/supportedLanguages.json', () => ( {
	en: 'English',
	fr: 'Français',
	de: 'Deutsch'
} ), { virtual: true } );

jest.mock( '../../../resources/src/mediawiki.languageselector/languageSearch.js', () => jest.fn() );

const useLanguageSelector = require( '../../../resources/src/mediawiki.languageselector/useLanguageSelector.js' );
const languageSearchClient = require( '../../../resources/src/mediawiki.languageselector/languageSearch.js' );

const TestComponent = defineComponent( {
	props: {
		selected: {
			type: [ String, Array ],
			default: null
		},
		selectableLanguages: {
			type: Object,
			default: () => null
		},
		searchApiUrl: {
			type: String,
			required: true
		},
		debounceDelayMs: {
			type: Number,
			default: 300
		},
		isMultiple: {
			type: Boolean,
			default: false
		}
	},
	setup( props ) {
		const { selectableLanguages, selected } = toRefs( props );
		return useLanguageSelector(
			selectableLanguages,
			selected,
			props.searchApiUrl,
			props.debounceDelayMs,
			props.isMultiple
		);
	},
	template: '<div></div>'
} );

const defaultProps = {
	searchApiUrl: 'https://en.wikipedia.org/w/api.php'
};

const mount = ( customProps ) => VueTestUtils.mount( TestComponent, {
	props: Object.assign( {}, defaultProps, customProps )
} );

describe( 'useLanguageSelector', () => {
	let mockSearchLanguages;

	beforeEach( () => {
		jest.clearAllMocks();
		mockSearchLanguages = jest.fn();
		languageSearchClient.mockReturnValue( {
			searchLanguages: mockSearchLanguages
		} );
	} );

	it( 'computes selected language label', async () => {
		const wrapper = mount( {
			selected: 'en',
			selectableLanguages: { en: 'English', fr: 'French' }
		} );
		await wrapper.vm.$nextTick();

		expect( wrapper.vm.selection.label ).toBe( 'English' );
	} );

	it( 'isSelectionUpdated returns false when selecting same language', async () => {
		const wrapper = mount( { selected: 'en' } );
		await wrapper.vm.$nextTick();

		const isUpdated = wrapper.vm.isSelectionUpdated( 'en' );

		expect( isUpdated ).toBe( false );
	} );

	it( 'clears search results for empty query', async () => {
		jest.useFakeTimers();
		const wrapper = mount();
		await wrapper.vm.$nextTick();

		wrapper.vm.searchResults = [ 'en', 'fr' ];
		wrapper.vm.search( '' );

		jest.advanceTimersByTime( 300 );
		await wrapper.vm.$nextTick();

		expect( wrapper.vm.searchResults ).toEqual( [] );
		jest.useRealTimers();
	} );

	it( 'debounces search calls', async () => {
		jest.useFakeTimers();
		mockSearchLanguages.mockReturnValue( Promise.resolve( { languagesearch: { en: 'English' } } ) );

		const wrapper = mount( { debounceDelayMs: 300 } );
		await wrapper.vm.$nextTick();

		wrapper.vm.search( 'en' );
		expect( mockSearchLanguages ).not.toHaveBeenCalled();

		jest.advanceTimersByTime( 300 );
		await wrapper.vm.$nextTick();

		expect( mockSearchLanguages ).toHaveBeenCalledWith( 'en' );

		jest.useRealTimers();
	} );

	it( 'updates search results on search', async () => {
		jest.useFakeTimers();
		mockSearchLanguages.mockReturnValue( Promise.resolve( { languagesearch: { en: 'English' } } ) );

		const wrapper = mount();
		await wrapper.vm.$nextTick();

		wrapper.vm.search( 'en' );

		jest.advanceTimersByTime( 300 );
		// Wait for debounce and async fetch
		await Promise.resolve();
		await wrapper.vm.$nextTick();

		expect( wrapper.vm.searchResults ).toEqual( [ 'en' ] );

		jest.useRealTimers();
	} );

	it( 'computes selected language labels for multiple selection', async () => {
		const wrapper = mount( {
			selected: [ 'en', 'fr' ],
			selectableLanguages: { en: 'English', fr: 'French', de: 'German' },
			isMultiple: true
		} );
		await wrapper.vm.$nextTick();

		expect( wrapper.vm.selection ).toEqual( [
			{ value: 'en', label: 'English' },
			{ value: 'fr', label: 'French' }
		] );
	} );

	it( 'isSelectionUpdated returns true when updating multiple selection', async () => {
		const wrapper = mount( {
			selected: [ 'en' ],
			isMultiple: true
		} );
		await wrapper.vm.$nextTick();

		const isUpdated = wrapper.vm.isSelectionUpdated( [ 'en', 'fr' ] );

		expect( isUpdated ).toBe( true );
	} );

	it( 'updates selectedValues when props change', async () => {
		const wrapper = mount( {
			selected: 'en',
			selectableLanguages: { en: 'English', fr: 'French' }
		} );
		await wrapper.vm.$nextTick();

		expect( wrapper.vm.selectedValues ).toBe( 'en' );

		await wrapper.setProps( { selected: 'fr' } );

		expect( wrapper.vm.selectedValues ).toBe( 'fr' );
	} );

	it( 'updates selectedValues when props change (multiple)', async () => {
		const wrapper = mount( {
			selected: [ 'en' ],
			selectableLanguages: { en: 'English', fr: 'French' },
			isMultiple: true
		} );
		await wrapper.vm.$nextTick();

		expect( wrapper.vm.selectedValues ).toEqual( [ 'en' ] );

		await wrapper.setProps( { selected: [ 'en', 'fr' ] } );

		expect( wrapper.vm.selectedValues ).toEqual( [ 'en', 'fr' ] );
	} );
} );
