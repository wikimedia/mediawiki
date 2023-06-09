window.matchMedia = window.matchMedia || function () {
	return {
		matches: false,
		onchange: () => {}
	};
};

const { test } = require( '../../../resources/skins.vector.js/setupIntersectionObservers.js' );
const {
	STICKY_HEADER_EXPERIMENT_NAME
} = require( '../../../resources/skins.vector.js/stickyHeader.js' );
describe( 'main.js', () => {
	it( 'getHeadingIntersectionHandler', () => {
		const section = document.createElement( 'div' );
		section.setAttribute( 'class', 'mw-body-content' );
		section.setAttribute( 'id', 'mw-content-text' );
		const heading = document.createElement( 'h2' );
		const headline = document.createElement( 'span' );
		headline.classList.add( 'mw-headline' );
		headline.setAttribute( 'id', 'headline' );
		heading.appendChild( headline );
		section.appendChild(
			heading
		);

		[
			[ section, 'toc-mw-content-text' ],
			[ heading, 'toc-headline' ]
		].forEach( ( testCase ) => {
			const node = /** @type {HTMLElement} */ ( testCase[ 0 ] );
			const fn = jest.fn();
			const handler = test.getHeadingIntersectionHandler( fn );
			handler( node );
			expect( fn ).toHaveBeenCalledWith( testCase[ 1 ] );
		} );
	} );

	it( 'initStickyHeaderABTests', () => {
		const STICKY_HEADER_AB = {
			name: STICKY_HEADER_EXPERIMENT_NAME,
			enabled: true
		};
		[
			{
				abConfig: STICKY_HEADER_AB,
				isEnabled: false, // sticky header unavailable
				isUserInTreatmentBucket: false, // not in treatment bucket
				expectedResult: {
					showStickyHeader: false
				}
			},
			{
				abConfig: STICKY_HEADER_AB,
				isEnabled: true, // sticky header available
				isUserInTreatmentBucket: false, // not in treatment bucket
				expectedResult: {
					showStickyHeader: false
				}
			},
			{
				abConfig: STICKY_HEADER_AB,
				isEnabled: false, // sticky header is not available
				isUserInTreatmentBucket: true, // but the user is in the treatment bucket
				expectedResult: {
					showStickyHeader: false
				}
			},
			{
				abConfig: STICKY_HEADER_AB,
				isEnabled: true,
				isUserInTreatmentBucket: true,
				expectedResult: {
					showStickyHeader: true
				}
			}
		].forEach(
			( {
				abConfig,
				isEnabled,
				isUserInTreatmentBucket,
				expectedResult
			} ) => {
				document.documentElement.classList.add( 'vector-sticky-header-enabled' );
				const result = test.initStickyHeaderABTests(
					abConfig,
					// isStickyHeaderFeatureAllowed
					isEnabled,
					( experiment ) => ( {
						name: experiment.name,
						isInBucket: () => true,
						isInSample: () => true,
						getBucket: () => 'bucket',
						isInTreatmentBucket: () => {
							return isUserInTreatmentBucket;
						}
					} )
				);
				expect( result ).toMatchObject( expectedResult );
				// Check that there are no side effects
				expect(
					document.documentElement.classList.contains( 'vector-sticky-header-enabled' )
				).toBe( true );
			} );
	} );
} );

const sectionObserverFn = () => ( {
	pause: () => {},
	resume: () => {},
	mount: () => {},
	unmount: () => {},
	setElements: () => {},
	calcIntersection: () => {}
} );

describe( 'Table of contents re-rendering', () => {
	const mockMwHook = () => {
		/** @type {Object.<string, Function>} */
		const callback = {};
		jest.spyOn( mw, 'hook' ).mockImplementation( ( name ) => {

			return {
				add: function ( fn ) {
					callback[ name ] = fn;

					return this;
				},
				fire: ( data ) => {
					if ( callback[ name ] ) {
						callback[ name ]( data );
					}
				}
			};
		} );
	};

	afterEach( () => {
		jest.restoreAllMocks();
	} );

	it( 'listens to wikipage.tableOfContents hook when mounted', () => {
		mockMwHook();
		const spy = jest.spyOn( mw, 'hook' );
		const tocElement = document.createElement( 'div' );
		const bodyContent = document.createElement( 'article' );
		const toc = test.setupTableOfContents( tocElement, bodyContent, sectionObserverFn );
		expect( toc ).not.toBe( null );
		expect( spy ).toHaveBeenCalledWith( 'wikipage.tableOfContents' );
		expect( spy ).not.toHaveBeenCalledWith( 'wikipage.tableOfContents.vector' );
	} );

	it( 'Firing wikipage.tableOfContents triggers reloadTableOfContents', async () => {
		mockMwHook();
		const tocElement = document.createElement( 'div' );
		const bodyContent = document.createElement( 'article' );
		const toc = test.setupTableOfContents( tocElement, bodyContent, sectionObserverFn );
		if ( !toc ) {
			// something went wrong
			expect( true ).toBe( false );
			return;
		}
		const spy = jest.spyOn( toc, 'reloadTableOfContents' ).mockImplementation( () => Promise.resolve() );

		mw.hook( 'wikipage.tableOfContents' ).fire( [
			// Add new section to see how the re-render performs.
			{
				toclevel: 1,
				number: '4',
				line: 'bat',
				anchor: 'bat',
				'is-top-level-section': true,
				'is-parent-section': false,
				'array-sections': null
			}
		] );

		expect( spy ).toHaveBeenCalled();
	} );
} );
