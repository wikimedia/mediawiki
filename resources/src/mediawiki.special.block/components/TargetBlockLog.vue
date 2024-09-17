<template>
	<cdx-accordion>
		<template #title>
			{{ $i18n( 'block-user-previous-blocks' ).text() }}
		</template>
		<cdx-table
			class="mw-block-previous-blocks"
			:caption="$i18n( 'block-user-previous-blocks' ).text()"
			:columns="columns"
			:data="data"
			:use-row-headers="true"
			:hide-caption="true"
		>
			<template #empty-state>
				{{ $i18n( 'block-user-no-previous-blocks' ).text() }}
			</template>
			<template #item-timestamp="{ item }">
				{{ util.formatTimestamp( item ) }}
			</template>
			<template #item-target="{ item }">
				<!-- eslint-disable-next-line vue/no-v-html -->
				<span v-html="$i18n( 'userlink-with-contribs', item ).parse()"></span>
			</template>
			<template #item-expiry="{ item }">
				{{ util.formatTimestamp( item ) }}
			</template>
			<template #item-blockedby="{ item }">
				<!-- eslint-disable-next-line vue/no-v-html -->
				<span v-html="$i18n( 'userlink-with-contribs', item ).parse()"></span>
			</template>
			<template #item-parameters="{ item }">
				<ul>
					<li v-for="( parameter, index ) in item" :key="index">
						{{ util.getBlockFlagMessage( parameter ) }}
					</li>
				</ul>
			</template>
			<template #item-reason="{ item }">
				{{ item ? item : $i18n( 'block-user-no-reason-given' ).text() }}
			</template>
		</cdx-table>
	</cdx-accordion>
</template>

<script>
const util = require( '../util.js' );
const { defineComponent } = require( 'vue' );
const { CdxAccordion, CdxTable } = require( '@wikimedia/codex' );

// @vue/component
module.exports = exports = defineComponent( {
	name: 'TargetBlockLog',
	components: { CdxAccordion, CdxTable },
	props: {
		targetUser: {
			type: [ Number, String, null ],
			required: true
		}
	},
	setup() {
		const columns = [
			{ id: 'timestamp', label: mw.message( 'blocklist-timestamp' ).text(), minWidth: '112px' },
			{ id: 'target', label: mw.message( 'blocklist-target' ).text(), minWidth: '150px' },
			{ id: 'expiry', label: mw.message( 'blocklist-expiry' ).text(), minWidth: '112px' },
			{ id: 'blockedby', label: mw.message( 'blocklist-by' ).text(), minWidth: '150px' },
			{ id: 'parameters', label: mw.message( 'blocklist-params' ).text(), minWidth: '160px' },
			{ id: 'reason', label: mw.message( 'blocklist-reason' ).text(), minWidth: '160px' }
		];

		return {
			columns,
			util
		};
	},
	data() {
		return {
			data: []
		};
	},
	methods: {
		getUserBlocks( searchTerm ) {
			const api = new mw.Api();

			const params = {
				action: 'query',
				format: 'json',
				formatversion: 2,
				list: 'logevents',
				aulimit: '10',
				letype: 'block',
				leprop: 'ids|title|type|user|timestamp|comment|details',
				letitle: 'User:' + searchTerm
			};

			return api.get( params )
				.then( ( response ) => response.query );
		}
	},
	watch: {
		targetUser: {
			handler( newValue ) {
				if ( newValue ) {
					this.data = [];
					// Look up the block(s) for the target user in the log
					this.getUserBlocks( newValue ).then( ( data ) => {
						// The fallback is only necessary for Jest tests.
						data = data || { logevents: [] };
						for ( let i = 0; i < data.logevents.length; i++ ) {
							this.data.push( {
								timestamp: data.logevents[ i ].timestamp,
								target: newValue,
								expiry: data.logevents[ i ].params.expiry,
								blockedby: data.logevents[ i ].user,
								parameters: data.logevents[ i ].params.flags,
								reason: data.logevents[ i ].comment
							} );
						}
					} );
				}
			},
			immediate: true
		}
	}
} );
</script>

<style lang="less">
@import 'mediawiki.skin.variables.less';

.mw-block-previous-blocks {
	word-break: auto-phrase;
}
</style>
