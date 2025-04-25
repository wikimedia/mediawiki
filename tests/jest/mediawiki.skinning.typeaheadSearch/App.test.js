const VueTestUtils = require( '@vue/test-utils' );
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

const mount = ( /** @type {Object} */ customProps ) => VueTestUtils.shallowMount( App, {
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

describe( 'App', () => {
	it( 'renders a typeahead search component', () => {
		const wrapper = mount();
		expect(
			wrapper.element
		).toMatchSnapshot();
	} );
} );
