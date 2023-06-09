// @ts-nocheck

const { addPortletLinkHandler } = require( '../../../resources/skins.vector.js/dropdownMenus.js' );

describe( 'addPortletLinkHandler', () => {
	beforeEach( () => {
		document.body.innerHTML = `
		<div id="p-views" class="vector-menu vector-menu-dropdown">
			<div class="vector-menu-content">
				<ul class="vector-menu-content-list">
					<li id="pt-userpage" class="mw-list-item">
						<a href="#bar">
							<span class="mw-ui-icon mw-ui-icon-userAvatar mw-ui-icon-wikimedia-userAvatar"></span>
							<span>Admin</span>
						</a>
					</li>
				</ul>
			</div>
		</div>

		<div id="p-personal" class="vector-menu vector-menu-dropdown">
			 <div class="vector-menu-content">
				<ul class="vector-menu-content-list">
					<li id="pt-userpage" class="mw-list-item">
						<a href="#bar">
							<span class="mw-ui-icon mw-ui-icon-userAvatar mw-ui-icon-wikimedia-userAvatar"></span>
							<span>Admin</span>
						</a>
					</li>
				</ul>
			</div>
		</div>`;
	} );

	afterEach( () => {
		jest.restoreAllMocks();
	} );

	test( 'Adds a span with icon class to dropdown menus', () => {

		// <li> element is the assumed HTML output of mw.util.addPortlet
		document.body.innerHTML = `
		<ul class="vector-menu vector-menu-dropdown">
			<li class="mw-list-item mw-list-item-js" id="test-id">
				<a href="#test-href">
					<span>
						test link content
					</span>
				</a>
			</li>
		</ul>`;

		const mockPortletItem = document.getElementById( 'test-id' );
		addPortletLinkHandler( mockPortletItem, { id: 'test-id' } );
		expect( document.body.innerHTML ).toMatchSnapshot();
	} );

	test( 'Does not add an icon when noicon class is present', () => {

		// <li> element is the assumed HTML output of mw.util.addPortlet
		document.body.innerHTML = `
		<ul class="vector-menu vector-menu-dropdown">
			<li class="mw-list-item mw-list-item-js" id="test-id">
				<a href="#test-href">
					<span>
						test link content
					</span>
				</a>
			</li>
		</ul>`;

		const mockPortletItem = document.getElementById( 'test-id' );
		addPortletLinkHandler( mockPortletItem, { id: 'test-id' } );
		expect( document.body.innerHTML ).toMatchSnapshot();
	} );

	test( 'JS portlet should be moved to more menu (#p-cactions) at narrow widths', () => {

		// <li> element is the assumed HTML output of mw.util.addPortlet
		document.body.innerHTML = `
		<div class="mw-article-toolbar-container" style="width:1000px">

			<div id="left-navigation">
				<nav>
					<div id="p-namespaces" style="width:1001px"></div>

					<div id="p-variants"></div>

					<div id="p-cactions">
						<ul>
						</ul>
					</div>
				</nav>
			</div>

			<div id="right-navigation">
				<ul id="p-views" class="vector-menu vector-menu-dropdown">
					<li class="mw-list-item mw-list-item-js" id="test-id">
						<a href="#test-href">
							<span>
								test link content
							</span>
						</a>
					</li>
				</ul>
			</div>
		</div>`;

		const mockPortletItem = document.getElementById( 'test-id' );
		addPortletLinkHandler( mockPortletItem, { id: 'test-id' } );

		expect( mockPortletItem.closest( '#p-cactions' ) ).toBeTruthy();
	} );

	test( 'addPortletLinkHandler only adds icon class to p-personal menu', () => {

		// Assumed output of mw.util.addPortletLink from core.
		const portletLinkItem = new DOMParser().parseFromString( `
		<li id="foo-id" class="mw-list-item mw-list-item-js">
			<a href="#foo">
				<span>foo</span>
			</a>
		</li>`, 'text/html' ).body.firstElementChild;

		// simulating addPortletLink appending to DOM from core.
		document
			.querySelector( '#p-personal .vector-menu-content-list' )
			.appendChild( portletLinkItem );

		// Running addPortletLink handler.
		addPortletLinkHandler( portletLinkItem, { id: 'foo-id' } );

		// Asserting that the icon classes were added.
		expect( portletLinkItem.querySelectorAll( 'span.mw-ui-icon.mw-ui-icon-vector-gadget-foo-id' ) ).toHaveLength( 1 );
	} );

	test( 'addPortletLinkHandler does not add icons to p-views menu', () => {

		// Assumed output of mw.util.addPortletLink from core.
		const portletLinkItem = new DOMParser().parseFromString( `
		<li id="foo-id" class="mw-list-item mw-list-item-js">
			<a href="#foo">
				<span>foo</span>
			</a>
		</li>`, 'text/html' ).body.firstElementChild;

		// simulating addPortletLink appending to DOM from core.
		document
			.querySelector( '#p-views .vector-menu-content-list' )
			.appendChild( portletLinkItem );

		// Running addPortletLink handler.
		addPortletLinkHandler( portletLinkItem, { id: 'foo-id' } );

		// Asserting that the icon classes were added.
		expect( portletLinkItem.querySelectorAll( 'span.mw-ui-icon.mw-ui-icon-vector-gadget-foo-id' ) ).toHaveLength( 0 );
	} );

	test( 'addPortletLinkHandler only adds one icon when an ID is specified - T327369', () => {

		// Assumed output of mw.util.addPortletLink from core.
		const portletLinkItem1 = new DOMParser().parseFromString( `
		<li class="mw-list-item mw-list-item-js">
			<a href="#foo">
				<span>foo</span>
			</a>
		</li>`, 'text/html' ).body.firstElementChild;

		const portletLinkItem2 = new DOMParser().parseFromString( `
		<li id="bar" class="mw-list-item mw-list-item-js">
			<a href="#bar">
				<span>bar</span>
			</a>
		</li>`, 'text/html' ).body.firstElementChild;

		const portletLinkItem3 = new DOMParser().parseFromString( `
		<li id="baz" class="mw-list-item mw-list-item-js">
			<a href="#baz">
				<span>baz</span>
			</a>
		</li>`, 'text/html' ).body.firstElementChild;

		// simulating addPortletLink appending to DOM from core.
		const contentList = document.querySelector( '#p-personal .vector-menu-content-list' );

		contentList.appendChild( portletLinkItem1 );
		contentList.appendChild( portletLinkItem2 );
		contentList.appendChild( portletLinkItem3 );

		// Running addPortletLink handler.
		addPortletLinkHandler( portletLinkItem1, { id: null } );
		addPortletLinkHandler( portletLinkItem2, { id: 'bar' } );
		addPortletLinkHandler( portletLinkItem3, { id: 'baz' } );
		// running portletLinkHandler on an item twice. Should be no-op.
		addPortletLinkHandler( portletLinkItem3, { id: 'baz' } );

		// Asserting that the icon classes were added where necessary
		// and that only one icon class was added per item.
		expect( portletLinkItem1.querySelectorAll( 'span.mw-ui-icon.mw-ui-icon-vector-gadget-foo' ) ).toHaveLength( 0 );
		expect( portletLinkItem2.querySelectorAll( 'span.mw-ui-icon.mw-ui-icon-vector-gadget-bar' ) ).toHaveLength( 1 );
		expect( portletLinkItem3.querySelectorAll( 'span.mw-ui-icon.mw-ui-icon-vector-gadget-baz' ) ).toHaveLength( 1 );
	} );
} );
