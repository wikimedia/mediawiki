/*!
 * MediaWiki - CategorySearchType classes.
 *
 * @copyright 2011-2015 MediaWiki Widgets Team and others; see AUTHORS.txt
 * @license The MIT License (MIT); see LICENSE.txt
 */
( function ( $, mw ) {
	var NS_CATEGORY = mw.config.get( 'wgNamespaceIds' ).category;

	function CategorySearchType( config ) {
		config = config || {};
		this.api = config.api || new mw.Api();
	}
	CategorySearchType.prototype.mustBeTheOnlySearchType = false;

	function OpenSearchCategorySearchType( config ) {
		CategorySearchType.call( this, config );
	}
	OO.inheritClass( OpenSearchCategorySearchType, CategorySearchType );
	OpenSearchCategorySearchType.prototype.searchCategories = function ( input, limit ) {
		return this.api.get( {
			formatversion: 2,
			action: 'opensearch',
			namespace: NS_CATEGORY,
			limit: limit,
			search: input
		} ).then( function ( res ) {
			return res[ 1 ];
		} );
	};

	function InternalSearchCategorySearchType( config ) {
		CategorySearchType.call( this, config );
	}
	OO.inheritClass( InternalSearchCategorySearchType, CategorySearchType );
	InternalSearchCategorySearchType.prototype.searchCategories = function ( input, limit ) {
		return this.api.get( {
			formatversion: 2,
			action: 'query',
			list: 'allpages',
			apnamespace: NS_CATEGORY,
			aplimit: limit,
			apfrom: input,
			apprefix: input
		} ).then( function ( res ) {
			return res.query.allpages.map( function ( page ) {
				return page.title;
			} );
		} );
	};

	function ExistsCategorySearchType( config ) {
		CategorySearchType.call( this, config );
	}
	OO.inheritClass( ExistsCategorySearchType, CategorySearchType );
	ExistsCategorySearchType.prototype.searchCategories = function ( input ) {
		if ( input.indexOf( '|' ) > -1 ) {
			return $.Deferred().resolve( [] );
		}

		return this.api.get( {
			formatversion: 2,
			action: 'query',
			prop: 'info',
			titles: 'Category:' + input
		} ).then( function ( res ) {
			var categories = [];

			$.each( res.query.pages, function ( index, page ) {
				if ( !page.missing ) {
					categories.push( page.title );
				}
			} );

			return categories;
		} );
	};

	function SubCategoriesCategorySearchType( config ) {
		CategorySearchType.call( this, config );
	}
	OO.inheritClass( SubCategoriesCategorySearchType, CategorySearchType );
	SubCategoriesCategorySearchType.prototype.mustBeTheOnlySearchType = true;
	SubCategoriesCategorySearchType.prototype.searchCategories = function ( input, limit ) {
		if ( input.indexOf( '|' ) > -1 ) {
			return $.Deferred().resolve( [] );
		}

		return this.api.get( {
			formatversion: 2,
			action: 'query',
			list: 'categorymembers',
			cmtype: 'subcat',
			cmlimit: limit,
			cmtitle: 'Category:' + input
		} ).then( function ( res ) {
			return res.query.categorymembers.map( function ( category ) {
				return category.title;
			} );
		} );
	};

	function ParentCategoriesCategorySearchType( config ) {
		CategorySearchType.call( this, config );
	}
	OO.inheritClass( ParentCategoriesCategorySearchType, CategorySearchType );
	ParentCategoriesCategorySearchType.prototype.mustBeTheOnlySearchType = true;
	ParentCategoriesCategorySearchType.prototype.searchCategories = function ( input, limit ) {
		if ( input.indexOf( '|' ) > -1 ) {
			return $.Deferred().resolve( [] );
		}

		return this.api.get( {
			formatversion: 2,
			action: 'query',
			prop: 'categories',
			cllimit: limit,
			titles: 'Category:' + input
		} ).then( function ( res )  {
			var categories = [];

			$.each( res.query.pages, function ( index, page ) {
				if ( !page.missing ) {
					if ( $.isArray( page.categories ) ) {
						categories.push.apply( categories, page.categories.map( function ( category ) {
							return category.title;
						} ) );
					}
				}
			} );

			return categories;
		} );
	};

	module.exports = {
		CategorySearchType: CategorySearchType,
		OpenSearchCategorySearchType: OpenSearchCategorySearchType,
		InternalSearchCategorySearchType: InternalSearchCategorySearchType,
		ExistsCategorySearchType: ExistsCategorySearchType,
		SubCategoriesCategorySearchType: SubCategoriesCategorySearchType,
		ParentCategoriesCategorySearchType: ParentCategoriesCategorySearchType
	};
}( jQuery, mediaWiki ) );
