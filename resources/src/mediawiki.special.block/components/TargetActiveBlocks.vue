<template>
	<cdx-accordion>
		<template #title>
			{{ $i18n( 'block-user-active-blocks' ).text() }}
		</template>
		<cdx-table
			class="mw-block-active-blocks"
			:caption="$i18n( 'block-user-active-blocks' ).text()"
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
				<!-- TODO: Is this safe? -->
				<!-- eslint-disable-next-line vue/no-v-html -->
				<span v-html="$i18n( 'userlink-with-contribs', item ).parse()"></span>
			</template>
			<template #item-expires="{ item }">
				{{ util.formatTimestamp( item ) }}
			</template>
			<template #item-blockedby="{ item }">
				<!-- TODO: Is this safe? -->
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
module.exports = defineComponent( {
	name: 'TargetActiveBlocks',
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
			{ id: 'target', label: mw.message( 'blocklist-target' ).text(), minWidth: '200px' },
			{ id: 'expires', label: mw.message( 'blocklist-expiry' ).text(), minWidth: '112px' },
			{ id: 'blockedby', label: mw.message( 'blocklist-by' ).text(), minWidth: '200px' },
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
				origin: '*',
				action: 'query',
				format: 'json',
				formatversion: 2,
				list: 'blocks',
				aulimit: '10',
				bkprop: 'id|user|by|timestamp|expiry|reason|range|flags',
				bkusers: searchTerm
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
					// Look up the current block(s) for the target user
					this.getUserBlocks( newValue ).then( ( data ) => {
						for ( let i = 0; i < data.blocks.length; i++ ) {
							this.data.push( {
								timestamp: data.blocks[ i ].timestamp,
								target: data.blocks[ i ].user,
								expires: data.blocks[ i ].expiry,
								blockedby: data.blocks[ i ].by,
								// parameters: data.blocks[ i ].range,
								reason: data.blocks[ i ].reason
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

.mw-block-active-blocks {
	word-break: auto-phrase;
}
</style>
