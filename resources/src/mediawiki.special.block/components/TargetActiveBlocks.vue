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
				<!-- eslint-disable-next-line vue/no-v-html -->
				<span v-html="$i18n( 'userlink-with-contribs', item ).parse()"></span>
			</template>
			<template #item-expires="{ item }">
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
			<template #item-modify>
				<!-- TODO: Ensure dropdown menu uses Right-Top layout (https://w.wiki/BTaj) -->
				<cdx-menu-button
					v-model:selected="selection"
					:menu-items="menuItems"
					class="mw-block-active-blocks__menu"
					aria-label="Modify block"
					type="button"
				>
					<cdx-icon :icon="cdxIconEllipsis"></cdx-icon>
				</cdx-menu-button>
			</template>
		</cdx-table>
	</cdx-accordion>
</template>

<script>
const util = require( '../util.js' );
const { defineComponent, ref } = require( 'vue' );
const { CdxAccordion, CdxTable, CdxMenuButton, CdxIcon } = require( '@wikimedia/codex' );
const { cdxIconEllipsis, cdxIconEdit, cdxIconTrash } = require( '../icons.json' );

// @vue/component
module.exports = defineComponent( {
	name: 'TargetActiveBlocks',
	components: { CdxAccordion, CdxTable, CdxMenuButton, CdxIcon },
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
			{ id: 'reason', label: mw.message( 'blocklist-reason' ).text(), minWidth: '160px' },
			{ id: 'modify', label: '', minWidth: '100px' }
		];
		const menuItems = [
			{ label: mw.message( 'edit' ).text(), value: 'edit', icon: cdxIconEdit },
			{ label: mw.message( 'block-item-remove' ).text(), value: 'remove', icon: cdxIconTrash }
		];
		const selection = ref( null );

		return {
			columns,
			util,
			menuItems,
			selection,
			cdxIconEllipsis
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

.mw-block-active-blocks__menu {
	text-align: center;
}

.cdx-table__table-wrapper {
	overflow-x: visible;
}
</style>
