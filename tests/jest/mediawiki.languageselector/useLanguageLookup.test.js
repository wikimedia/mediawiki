const { defineComponent, ref } = require( 'vue' );
const VueTestUtils = require( '@vue/test-utils' );
const useLanguageLookup = require( '../../../resources/src/mediawiki.languageselector/useLanguageLookup.js' );

const TestComponent = defineComponent( {
	props: {
		isMultiple: {
			type: Boolean,
			default: false
		}
	},
	setup( props, { emit } ) {
		const selection = ref( props.isMultiple ? [] : { value: null, label: '' } );
		const selectedValues = ref( props.isMultiple ? [] : null );
		const languages = ref( { en: 'English', fr: 'French' } );
		const searchQuery = ref( '' );
		const searchResults = ref( [] );
		const search = jest.fn( ( q ) => {
			searchQuery.value = q;
		} );
		const clearSearchQuery = jest.fn( () => {
			searchQuery.value = '';
		} );
		const isSelectionUpdated = jest.fn( () => true );

		const lookup = useLanguageLookup( {
			selection,
			selectedValues,
			languages,
			searchQuery,
			searchResults,
			search,
			clearSearchQuery,
			isSelectionUpdated,
			emit,
			isMultiple: props.isMultiple
		} );

		return {
			...lookup,
			selection,
			selectedValues,
			searchQuery,
			searchResults,
			search,
			clearSearchQuery
		};
	},
	template: '<div></div>'
} );

describe( 'useLanguageLookup', () => {
	it( 'initializes inputValue from selection (single)', async () => {
		const wrapper = VueTestUtils.mount( TestComponent, {
			props: { isMultiple: false }
		} );
		wrapper.vm.selection = { value: 'en', label: 'English' };
		await wrapper.vm.$nextTick();
		expect( wrapper.vm.inputValue ).toBe( 'English' );
	} );

	it( 'does not initialize inputValue from selection (multiple)', async () => {
		const wrapper = VueTestUtils.mount( TestComponent, {
			props: { isMultiple: true }
		} );
		wrapper.vm.selection = [ { value: 'en', label: 'English' } ];
		await wrapper.vm.$nextTick();
		expect( wrapper.vm.inputValue ).toBe( '' );
	} );

	it( 'computes menuItems from languages when no search query', async () => {
		const wrapper = VueTestUtils.mount( TestComponent );
		await wrapper.vm.$nextTick();
		expect( wrapper.vm.menuItems ).toEqual( [
			{ value: 'en', label: 'English' },
			{ value: 'fr', label: 'French' }
		] );
	} );

	it( 'computes menuItems from searchResults when search query exists', async () => {
		const wrapper = VueTestUtils.mount( TestComponent );
		wrapper.vm.searchQuery = 'en';
		wrapper.vm.searchResults = [ 'en' ];
		await wrapper.vm.$nextTick();
		expect( wrapper.vm.menuItems ).toEqual( [
			{ value: 'en', label: 'English' }
		] );
	} );

	it( 'clears search query when input is emptied', () => {
		const wrapper = VueTestUtils.mount( TestComponent );
		wrapper.vm.onUpdateInputValue( '' );
		expect( wrapper.vm.clearSearchQuery ).toHaveBeenCalled();
	} );

	it( 'triggers search on input update', () => {
		const wrapper = VueTestUtils.mount( TestComponent );
		wrapper.vm.onUpdateInputValue( 'fra' );
		expect( wrapper.vm.search ).toHaveBeenCalledWith( 'fra' );
	} );

	it( 'does not search when input matches the current single selection label', async () => {
		const wrapper = VueTestUtils.mount( TestComponent, {
			props: { isMultiple: false }
		} );
		wrapper.vm.selection = { value: 'en', label: 'English' };
		await wrapper.vm.$nextTick();

		wrapper.vm.onUpdateInputValue( 'English' );

		expect( wrapper.vm.search ).not.toHaveBeenCalled();
	} );

	it( 'searches when input differs from the current single selection label', async () => {
		const wrapper = VueTestUtils.mount( TestComponent, {
			props: { isMultiple: false }
		} );
		wrapper.vm.selection = { value: 'en', label: 'English' };
		await wrapper.vm.$nextTick();

		wrapper.vm.onUpdateInputValue( 'Eng' );

		expect( wrapper.vm.search ).toHaveBeenCalledWith( 'Eng' );
	} );

	it( 'searches in multiple mode even when input matches a selection label', async () => {
		const wrapper = VueTestUtils.mount( TestComponent, {
			props: { isMultiple: true }
		} );
		wrapper.vm.selection = [ { value: 'en', label: 'English' } ];
		await wrapper.vm.$nextTick();

		wrapper.vm.onUpdateInputValue( 'English' );

		expect( wrapper.vm.search ).toHaveBeenCalledWith( 'English' );
	} );

	it( 'emits update:selected and clears search on selection update', () => {
		const wrapper = VueTestUtils.mount( TestComponent );
		wrapper.vm.onUpdateSelected( 'fr' );
		expect( wrapper.emitted( 'update:selected' )[ 0 ] ).toEqual( [ 'fr' ] );
		expect( wrapper.vm.clearSearchQuery ).toHaveBeenCalled();
	} );

	it( 'emits the remaining selection when a chip is removed', () => {
		const wrapper = VueTestUtils.mount( TestComponent, {
			props: { isMultiple: true }
		} );

		// Codex emits the remaining chips after the removed one is dropped.
		wrapper.vm.onUpdateInputChips( [ { value: 'fr', label: 'French' } ] );

		expect( wrapper.emitted( 'update:selected' )[ 0 ] ).toEqual( [ [ 'fr' ] ] );
	} );

	it( 'sets warning on blur with invalid input', async () => {
		const wrapper = VueTestUtils.mount( TestComponent );
		wrapper.vm.inputValue = 'invalid';
		wrapper.vm.searchResults = [];
		wrapper.vm.searchQuery = 'invalid';
		await wrapper.vm.$nextTick();

		wrapper.vm.onBlur();
		expect( wrapper.vm.status ).toBe( 'warning' );
		expect( wrapper.vm.statusMessages.warning ).toBeDefined();
	} );

	it( 'attempts selection on blur with valid input', async () => {
		const wrapper = VueTestUtils.mount( TestComponent );
		wrapper.vm.inputValue = 'English';
		wrapper.vm.searchResults = [ 'en' ];
		wrapper.vm.searchQuery = 'English';
		await wrapper.vm.$nextTick();

		wrapper.vm.onBlur();
		expect( wrapper.vm.status ).toBe( 'default' );
		expect( wrapper.emitted( 'update:selected' )[ 0 ] ).toEqual( [ 'en' ] );
	} );

	it( 'attempts selection on blur (multiple)', async () => {
		const wrapper = VueTestUtils.mount( TestComponent, {
			props: { isMultiple: true }
		} );
		wrapper.vm.inputValue = 'French';
		wrapper.vm.searchResults = [ 'fr' ];
		wrapper.vm.searchQuery = 'French';
		wrapper.vm.selectedValues = [ 'en' ];
		await wrapper.vm.$nextTick();

		wrapper.vm.onBlur();
		expect( wrapper.emitted( 'update:selected' )[ 0 ] ).toEqual( [ [ 'en', 'fr' ] ] );
		expect( wrapper.vm.inputValue ).toBe( '' );
	} );

	it( 'menuItems does not flash empty when searchQuery changes but searchResults has not yet', async () => {
		const wrapper = VueTestUtils.mount( TestComponent );
		// Initially show all
		expect( wrapper.vm.menuItems.length ).toBe( 2 );

		// Simulate typing something
		wrapper.vm.searchQuery = 'en';
		// At this point searchResults is still []
		await wrapper.vm.$nextTick();

		// EXPECTATION: menuItems SHOULD NOT be empty. It should ideally still show old results
		// or at least not flash "no results".
		expect( wrapper.vm.menuItems.length ).toBeGreaterThan( 0 );
	} );
} );
