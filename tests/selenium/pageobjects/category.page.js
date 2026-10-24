import Page from 'wdio-mediawiki/Page.js';

class CategoryPage extends Page {
	get heading() {
		return $( '#firstHeading' );
	}

	get generatedList() {
		return $( '.mw-category-generated' );
	}

	get subcategories() {
		return $( '#mw-subcategories' );
	}

	get pages() {
		return $( '#mw-pages' );
	}

	get categoryLinks() {
		return $( '#catlinks' );
	}

	subcategory( name ) {
		return this.subcategories.$( `=${ name }` );
	}

	page( name ) {
		return this.pages.$( `=${ name }` );
	}

	categoryLink( name ) {
		return this.categoryLinks.$( `=${ name }` );
	}

	async open( name ) {
		return super.openTitle( `Category:${ name }` );
	}
}

export default new CategoryPage();
