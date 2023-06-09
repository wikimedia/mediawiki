const mustache = require( 'mustache' );
const fs = require( 'fs' );
const tableOfContentsTemplate = fs.readFileSync( 'includes/templates/TableOfContents.mustache', 'utf8' );
const tableOfContentsContentsTemplate = fs.readFileSync( 'includes/templates/TableOfContents__list.mustache', 'utf8' );
const tableOfContentsLineTemplate = fs.readFileSync( 'includes/templates/TableOfContents__line.mustache', 'utf8' );
const pinnableElementOpenTemplate = fs.readFileSync( 'includes/templates/PinnableElement/Open.mustache', 'utf8' );
const pinnableElementCloseTemplate = fs.readFileSync( 'includes/templates/PinnableElement/Close.mustache', 'utf8' );
const pinnableHeaderTemplate = fs.readFileSync( 'includes/templates/PinnableHeader.mustache', 'utf8' );
const initTableOfContents = require( '../../resources/skins.vector.js/tableOfContents.js' );

let /** @type {HTMLElement} */ container,
	/** @type {HTMLElement} */ fooSection,
	/** @type {HTMLElement} */ barSection,
	/** @type {HTMLElement} */ bazSection,
	/** @type {HTMLElement} */ quxSection,
	/** @type {HTMLElement} */ quuxSection;
const onHeadingClick = jest.fn();
const onHashChange = jest.fn();
const onToggleClick = jest.fn();
const onTogglePinned = jest.fn();

const SECTIONS = [
	{
		toclevel: 1,
		number: '1',
		line: 'foo',
		anchor: 'foo',
		linkAnchor: 'foo',
		'is-top-level-section': true,
		'is-parent-section': false,
		'array-sections': null
	}, {
		toclevel: 1,
		number: '2',
		line: 'bar',
		anchor: 'bar',
		linkAnchor: 'bar',
		'is-top-level-section': true,
		'is-parent-section': true,
		'vector-button-label': 'Toggle bar subsection',
		'array-sections': [ {
			toclevel: 2,
			number: '2.1',
			line: 'baz',
			anchor: 'baz',
			linkAnchor: 'baz',
			'is-top-level-section': false,
			'is-parent-section': true,
			'array-sections': [ {
				toclevel: 3,
				number: '2.1.1',
				line: 'qux',
				anchor: 'qux',
				linkAnchor: 'qux',
				'is-top-level-section': false,
				'is-parent-section': false,
				'array-sections': null
			} ]
		} ]
	}, {
		toclevel: 1,
		number: '3',
		line: 'quux',
		anchor: 'quux',
		linkAnchor: 'quux',
		'is-top-level-section': true,
		'is-parent-section': false,
		'array-sections': null
	}
];

/**
 * @param {Object} templateProps
 * @return {string}
 */
function render( templateProps = {} ) {
	const templateData = Object.assign( {
		'msg-vector-toc-beginning': 'Beginning',
		'vector-is-collapse-sections-enabled': false,
		'array-sections': SECTIONS,
		id: 'vector-toc',
		'data-pinnable-header': {
			'is-pinned': true,
			'data-feature-name': 'pinned',
			'data-pinnable-element-id': 'vector-toc',
			label: 'Contents',
			'label-tag-name': 'h2',
			'pin-label': 'move to sidebar',
			'unpin-label': 'hide'
		}
	}, templateProps );

	return mustache.render( tableOfContentsTemplate, templateData, {
		'PinnableElement/Open': pinnableElementOpenTemplate,
		'PinnableElement/Close': pinnableElementCloseTemplate,
		PinnableHeader: pinnableHeaderTemplate,
		TableOfContents__list: tableOfContentsContentsTemplate, // eslint-disable-line camelcase
		TableOfContents__line: tableOfContentsLineTemplate // eslint-disable-line camelcase
	} );
}

/**
 * @param {Object} templateProps
 * @return {module:TableOfContents~TableOfContents}
 */
function mount( templateProps = {} ) {
	document.body.innerHTML = render( templateProps );

	container = /** @type {HTMLElement} */ ( document.getElementById( 'vector-toc' ) );
	fooSection = /** @type {HTMLElement} */ ( document.getElementById( 'toc-foo' ) );
	barSection = /** @type {HTMLElement} */ ( document.getElementById( 'toc-bar' ) );
	bazSection = /** @type {HTMLElement} */ ( document.getElementById( 'toc-baz' ) );
	quxSection = /** @type {HTMLElement} */ ( document.getElementById( 'toc-qux' ) );
	quuxSection = /** @type {HTMLElement} */ ( document.getElementById( 'toc-quux' ) );

	return initTableOfContents( {
		container,
		onHeadingClick,
		onHashChange,
		onToggleClick,
		onTogglePinned
	} );
}

describe( 'Table of contents', () => {
	/**
	 * @type {module:TableOfContents~TableOfContents}
	 */
	let toc;

	beforeEach( () => {
		global.window.matchMedia = jest.fn( () => ( {} ) );
	} );

	afterEach( () => {
		if ( toc ) {
			toc.unmount();
			toc = undefined;
		}

		mw.util.getTargetFromFragment = undefined;
	} );

	describe( 'renders', () => {
		test( 'when `vector-is-collapse-sections-enabled` is false', () => {
			toc = mount();
			expect( document.body.innerHTML ).toMatchSnapshot();
			expect( barSection.classList.contains( toc.EXPANDED_SECTION_CLASS ) ).toEqual( true );
		} );
		test( 'when `vector-is-collapse-sections-enabled` is true', () => {
			toc = mount( { 'vector-is-collapse-sections-enabled': true } );
			expect( document.body.innerHTML ).toMatchSnapshot();
			expect( barSection.classList.contains( toc.EXPANDED_SECTION_CLASS ) ).toEqual( false );
		} );
		test( 'toggles for top level parent sections', () => {
			toc = mount();
			expect( fooSection.getElementsByClassName( toc.TOGGLE_CLASS ).length ).toEqual( 0 );
			expect( barSection.getElementsByClassName( toc.TOGGLE_CLASS ).length ).toEqual( 1 );
			expect( bazSection.getElementsByClassName( toc.TOGGLE_CLASS ).length ).toEqual( 0 );
			expect( quxSection.getElementsByClassName( toc.TOGGLE_CLASS ).length ).toEqual( 0 );
			expect( quuxSection.getElementsByClassName( toc.TOGGLE_CLASS ).length ).toEqual( 0 );
		} );
	} );

	describe( 'binds event listeners', () => {
		test( 'for onHeadingClick', () => {
			toc = mount();
			const heading = /** @type {HTMLElement} */ ( document.querySelector( `#toc-foo .${toc.LINK_CLASS}` ) );
			heading.click();

			expect( onToggleClick ).not.toBeCalled();
			expect( onHashChange ).not.toBeCalled();
			expect( onHeadingClick ).toBeCalled();
		} );
		test( 'for onToggleClick', () => {
			toc = mount();
			const toggle = /** @type {HTMLElement} */ ( document.querySelector( `#toc-bar .${toc.TOGGLE_CLASS}` ) );
			toggle.click();

			expect( onHeadingClick ).not.toBeCalled();
			expect( onHashChange ).not.toBeCalled();
			expect( onToggleClick ).toBeCalled();
		} );
		test( 'for onHashChange', () => {
			mw.util.getTargetFromFragment = jest.fn().mockImplementation( ( hash ) => {
				return hash === 'toc-foo' ? fooSection : null;
			} );
			mount();

			// Jest doesn't trigger a hashchange event when setting a hash location.
			location.hash = 'foo';
			window.dispatchEvent( new HashChangeEvent( 'hashchange', {
				oldURL: 'http://example.com',
				newURL: 'http://example.com#foo'
			} ) );

			expect( onHeadingClick ).not.toBeCalled();
			expect( onToggleClick ).not.toBeCalled();
			expect( onHashChange ).toBeCalled();
		} );
	} );

	describe( 'applies correct classes', () => {
		test( 'when changing active sections', () => {
			toc = mount( { 'vector-is-collapse-sections-enabled': true } );
			let activeSections;
			let activeTopSections;

			/**
			 * @param {string} id
			 * @param {HTMLElement} activeSection
			 * @param {HTMLElement} activeTopSection
			 */
			function testActiveClasses( id, activeSection, activeTopSection ) {
				toc.changeActiveSection( id );
				activeSections = container.querySelectorAll( `.${toc.ACTIVE_SECTION_CLASS}` );
				activeTopSections = container.querySelectorAll( `.${toc.ACTIVE_TOP_SECTION_CLASS}` );
				expect( activeSections.length ).toEqual( 1 );
				expect( activeTopSections.length ).toEqual( 1 );
				expect( activeSections[ 0 ] ).toEqual( activeSection );
				expect( activeTopSections[ 0 ] ).toEqual( activeTopSection );
			}

			testActiveClasses( 'toc-foo', fooSection, fooSection );
			testActiveClasses( 'toc-bar', barSection, barSection );
			testActiveClasses( 'toc-baz', bazSection, barSection );
			testActiveClasses( 'toc-qux', quxSection, barSection );
			testActiveClasses( 'toc-quux', quuxSection, quuxSection );
		} );

		test( 'when expanding sections', () => {
			toc = mount();
			toc.expandSection( 'toc-bar' );
			expect( barSection.classList.contains( toc.EXPANDED_SECTION_CLASS ) ).toEqual( true );
		} );

		test( 'when toggling sections', () => {
			toc = mount();
			toc.toggleExpandSection( 'toc-bar' );
			expect( barSection.classList.contains( toc.EXPANDED_SECTION_CLASS ) ).toEqual( false );
			toc.toggleExpandSection( 'toc-bar' );
			expect( barSection.classList.contains( toc.EXPANDED_SECTION_CLASS ) ).toEqual( true );
		} );
	} );

	describe( 'applies the correct aria attributes', () => {
		test( 'when initialized', () => {
			toc = mount();
			const toggleButton = /** @type {HTMLElement} */ ( barSection.querySelector( `.${toc.TOGGLE_CLASS}` ) );

			expect( toggleButton.getAttribute( 'aria-expanded' ) ).toEqual( 'true' );
		} );

		test( 'when expanding sections', () => {
			toc = mount();
			const toggleButton = /** @type {HTMLElement} */ ( barSection.querySelector( `.${toc.TOGGLE_CLASS}` ) );

			toc.expandSection( 'toc-bar' );
			expect( toggleButton.getAttribute( 'aria-expanded' ) ).toEqual( 'true' );
		} );

		test( 'when toggling sections', () => {
			toc = mount();
			const toggleButton = /** @type {HTMLElement} */ ( barSection.querySelector( `.${toc.TOGGLE_CLASS}` ) );

			toc.toggleExpandSection( 'toc-bar' );
			expect( toggleButton.getAttribute( 'aria-expanded' ) ).toEqual( 'false' );

			toc.toggleExpandSection( 'toc-bar' );
			expect( toggleButton.getAttribute( 'aria-expanded' ) ).toEqual( 'true' );
		} );
	} );

	describe( 'when the hash fragment changes', () => {
		test( 'expands and activates corresponding section', () => {
			mw.util.getTargetFromFragment = jest.fn().mockImplementation( ( hash ) => {
				return hash === 'toc-qux' ? quxSection : null;
			} );
			toc = mount( { 'vector-is-collapse-sections-enabled': true } );
			expect(
				quxSection.classList.contains( toc.ACTIVE_SECTION_CLASS )
			).toEqual( false );

			// Jest doesn't trigger a hashchange event when setting a hash location.
			location.hash = 'qux';
			window.dispatchEvent( new HashChangeEvent( 'hashchange', {
				oldURL: 'http://example.com',
				newURL: 'http://example.com#qux'
			} ) );

			const activeSections = container.querySelectorAll( `.${toc.ACTIVE_SECTION_CLASS}` );
			const activeTopSections = container.querySelectorAll( `.${toc.ACTIVE_TOP_SECTION_CLASS}` );
			expect( activeSections.length ).toEqual( 1 );
			expect( activeTopSections.length ).toEqual( 1 );
			expect(
				barSection.classList.contains( toc.ACTIVE_TOP_SECTION_CLASS )
			).toEqual( true );
			expect(
				barSection.classList.contains( toc.EXPANDED_SECTION_CLASS )
			).toEqual( true );
			expect(
				quxSection.classList.contains( toc.ACTIVE_SECTION_CLASS )
			).toEqual( true );
		} );
	} );

	describe( 'reloadTableOfContents', () => {
		test( 're-renders toc when wikipage.tableOfContents hook is fired with empty sections', async () => {
			toc = mount();
			await toc.reloadTableOfContents( [] );

			expect( document.body.innerHTML ).toMatchSnapshot();
		} );

		test( 're-renders toc when wikipage.tableOfContents hook is fired with sections', async () => {
			jest.spyOn( mw.loader, 'using' ).mockImplementation( () => Promise.resolve() );
			mw.template.getCompiler = () => {};
			jest.spyOn( mw, 'message' ).mockImplementation( ( msg ) => {
				const msgFactory = ( /** @type {string} */ text ) => {
					return {
						parse: () => '',
						plain: () => '',
						escaped: () => '',
						exists: () => true,
						text: () => text
					};
				};
				switch ( msg ) {
					case 'vector-toc-beginning':
						return msgFactory( 'Beginning' );
					default:
						return msgFactory( '' );
				}

			} );
			jest.spyOn( mw.template, 'getCompiler' ).mockImplementation( () => {
				return {
					compile: () => {
						return {
							render: ( /** @type {Object} */ data ) => {
								return {
									html: () => {
										return render( data );
									}
								};
							}
						};
					}
				};
			} );

			toc = mount();

			const toggleButton = /** @type {HTMLElement} */ ( barSection.querySelector( `.${toc.TOGGLE_CLASS}` ) );
			// Collapse section.
			toc.toggleExpandSection( 'toc-bar' );
			expect( toggleButton.getAttribute( 'aria-expanded' ) ).toEqual( 'false' );

			// wikipage.tableOfContents includes the nested sections in the top level
			// of the array.

			await toc.reloadTableOfContents( [
				// foo
				SECTIONS[ 0 ],
				// bar
				SECTIONS[ 1 ],
				// baz
				SECTIONS[ 1 ][ 'array-sections' ][ 0 ],
				// qux
				SECTIONS[ 1 ][ 'array-sections' ][ 0 ][ 'array-sections' ][ 0 ],
				// quux
				SECTIONS[ 2 ],
				// Add new section to see how the re-render performs.
				{
					toclevel: 1,
					number: '4',
					line: 'bat',
					anchor: 'bat',
					linkAnchor: 'bat',
					'is-top-level-section': true,
					'is-parent-section': false,
					'array-sections': null
				}
			] );

			const newToggleButton = /** @type {HTMLElement} */ ( document.querySelector( `#toc-bar .${toc.TOGGLE_CLASS}` ) );
			expect( newToggleButton ).not.toBeNull();
			// Check that the sections render in their expanded form.
			expect( newToggleButton.getAttribute( 'aria-expanded' ) ).toEqual( 'true' );

			// Verify newly rendered TOC html matches the expected html.
			expect( document.body.innerHTML ).toMatchSnapshot();
		} );
	} );
} );
