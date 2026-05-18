const VueTestUtils = require( '@vue/test-utils' );
const { CdxTypeaheadSearch } = require( '@wikimedia/codex' );
const App = require( '../../../resources/src/mediawiki.skinning.typeaheadSearch/App.vue' );
const urlGeneratorFn = require( '../../../resources/src/mediawiki.skinning.typeaheadSearch/urlGenerator.js' );
const scriptPath = '/w/index.php';
const urlGenerator = urlGeneratorFn( scriptPath );

const defaultProps = {
	prefixClass: 'vector-',
	id: 'searchform',
	searchAccessKey: 'f',
	searchTitle: 'search',
	showThumbnail: true,
	showDescription: true,
	highlightQuery: true,
	urlGenerator,
	restClient: {
		loadMore: () => Promise.resolve(),
		fetchByTitle: () => Promise.resolve()
	},
	searchPlaceholder: 'Search MediaWiki',
	searchQuery: ''
};

const mount = ( /** @type {Object} */ customProps ) => VueTestUtils.mount( App, {
	props: Object.assign( {}, defaultProps, customProps ),
	global: {
		mocks: {
			$i18n: ( /** @type {string} */ str ) => ( {
				text: () => str
			} )
		},
		directives: {
			'i18n-html': ( el, binding ) => {
				el.innerHTML = `${ binding.arg } (${ binding.value })`;
			}
		}
	}
} );

let matchMediaResult;

describe( 'App', () => {
	beforeEach( () => {
		matchMediaResult = ( {
			matches: false
		} );
		window.matchMedia = jest.fn( () => matchMedia );
	} );
	it( 'renders a typeahead search component', () => {
		const wrapper = mount();
		expect(
			wrapper.element
		).toMatchSnapshot();
	} );
} );

describe( 'App (mobile mode)', () => {
	beforeEach( () => {
		matchMediaResult = ( {
			matches: true
		} );
		window.matchMedia = jest.fn( () => matchMediaResult );
	} );

	it( 'renders a typeahead search component in a dialog for mobile mode', () => {
		const wrapper = mount( {
			supportsMobileExperience: true,
			router: {
				addRoute: jest.fn(),
				on: jest.fn()
			}
		} );
		expect(
			wrapper.element
		).toMatchSnapshot();

		// resize the window (but keep as desktop)
		matchMediaResult.onchange();
		expect(
			wrapper.element
		).toMatchSnapshot();
	} );
} );

const fetchByTitleMock = jest.fn( () => ( { fetch: Promise.resolve(), abort: Promise.resolve() } ) );
const mockRestClient = {
	fetchByTitle: fetchByTitleMock
};

describe( 'App (restSearchClient interaction)', () => {
	it( 'runs restSearchClient.fetchByTitle with a user query surrounded with spaces', async () => {
		const wrapper = mount( {
			restClient: mockRestClient
		} );
		await wrapper.findComponent( CdxTypeaheadSearch ).vm.$emit( 'input', ' data ' );
		expect( fetchByTitleMock.mock.calls ).toHaveLength( 1 );
		expect( fetchByTitleMock.mock.calls[ 0 ][ 0 ] ).toBe( 'data ' );
	} );

	it( 'do not run restSearchClient.fetchByTitle with a user query with spaces', async () => {
		const wrapper = mount( {
			restClient: mockRestClient
		} );
		await wrapper.findComponent( CdxTypeaheadSearch ).vm.$emit( 'input', ' ' );
		expect( fetchByTitleMock.mock.calls ).toHaveLength( 0 );
	} );
} );
