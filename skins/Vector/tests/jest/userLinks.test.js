const { userLinksHTML } = require( './userLinksData.js' );

beforeEach( () => {
	document.body.innerHTML = userLinksHTML;
} );

test( 'UserLinks renders', () => {
	expect( document.body.innerHTML ).toMatchSnapshot();
} );
