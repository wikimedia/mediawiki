const VueTestUtils = require( '@vue/test-utils' );
const App = require( '../../resources/skins.vector.search/App.vue' );

const defaultProps = {
	id: 'searchform',
	searchAccessKey: 'f',
	searchTitle: 'search',
	showThumbnail: true,
	showDescription: true,
	highlightQuery: true,
	searchPlaceholder: 'Search MediaWiki',
	searchQuery: ''
};

const mount = ( /** @type {Object} */ customProps ) => {
	return VueTestUtils.shallowMount( App, {
		props: Object.assign( {}, defaultProps, customProps ),
		global: {
			mocks: {
				$i18n: ( /** @type {string} */ str ) => ( {
					text: () => str
				} )
			},
			directives: {
				'i18n-html': ( el, binding ) => {
					el.innerHTML = `${binding.arg} (${binding.value})`;
				}
			}
		}
	} );
};

describe( 'App', () => {
	it( 'renders a typeahead search component', () => {
		const wrapper = mount();
		expect(
			wrapper.element
		).toMatchSnapshot();
	} );
} );
