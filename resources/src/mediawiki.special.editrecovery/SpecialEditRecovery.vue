<template>
	<p v-if="pages.length">
		{{ $i18n( 'edit-recovery-special-intro', pages.length ) }}
	</p>
	<p v-else>
		{{ $i18n( 'edit-recovery-special-intro-empty' ) }}
	</p>
	<ul>
		<li v-for="page in pages" :key="page">
			<a :href="page.url">{{ page.title }}</a>
			<span v-if="page.section"> &ndash; {{ page.section }}</span>
			<span>
				<a :href="page.editUrl">{{ $i18n( 'parentheses', $i18n( 'editlink' ) ) }}</a>
			</span>
		</li>
	</ul>
</template>

<script>
const { ref } = require( 'vue' );
// @vue/component
module.exports = {
	setup() {
		const pages = ref( [] );
		const storage = require( '../mediawiki.editRecovery/storage.js' );
		storage.openDatabase().then( () => {
			storage.loadAllData().then( ( allData ) => {
				allData.forEach( ( d ) => {
					const title = new mw.Title( d.pageName );
					const editParams = { action: 'edit' };
					if ( d.section ) {
						editParams.section = d.section;
					}
					pages.value.push( {
						title: title.getPrefixedText(),
						url: title.getUrl(),
						editUrl: title.getUrl( editParams ),
						section: d.section,
						sectionLabel: d.section ? mw.msg( 'parentheses', mw.msg( 'search-section', d.section ) ) : ''
					} );
				} );
			} );
		} );
		return {
			pages
		};
	}
};
// eslint-disable-next-line vue/dot-location
</script>
