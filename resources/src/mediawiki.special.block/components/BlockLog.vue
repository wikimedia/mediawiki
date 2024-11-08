<template>
	<cdx-accordion
		:class="`mw-block-log mw-block-log__type-${blockLogType}`"
	>
		<template #title>
			{{ title }}
		</template>
		<cdx-table
			:caption="title"
			:columns="!!logEntries.length ? columns : []"
			:data="logEntries"
			:use-row-headers="true"
			:hide-caption="true"
		>
			<template #empty-state>
				{{ emptyState }}
			</template>
			<template #item-timestamp="{ item }">
				<a
					v-if="item.logid"
					:href="mw.util.getUrl( 'Special:Log', { logid: item.logid } )"
				>
					{{ util.formatTimestamp( item.timestamp ) }}
				</a>
				<span v-else>
					{{ util.formatTimestamp( item.timestamp ) }}
				</span>
			</template>
			<template v-if="blockLogType === 'recent'" #item-type="{ item }">
				{{ util.getBlockActionMessage( item ) }}
			</template>
			<template v-if="blockLogType === 'active'" #item-target="{ item }">
				<!-- eslint-disable-next-line vue/no-v-html -->
				<span v-html="$i18n( 'userlink-with-contribs', item ).parse()"></span>
			</template>
			<template #item-expiry="{ item }">
				<span v-if="item.expires">
					{{ util.formatTimestamp( item.expires, item.duration ) }}
				</span>
				<span v-else class="mw-block-log-nodata">
					—
				</span>
			</template>
			<template #item-blockedby="{ item }">
				<!-- eslint-disable-next-line vue/no-v-html -->
				<span v-html="$i18n( 'userlink-with-contribs', item ).parse()"></span>
			</template>
			<template #item-parameters="{ item }">
				<div v-if="!item" class="mw-block-log-params mw-block-log-nodata">
					—
				</div>
				<ul v-else>
					<li v-for="( parameter, index ) in item" :key="index">
						{{ util.getBlockFlagMessage( parameter ) }}
					</li>
				</ul>
			</template>
			<template #item-reason="{ item }">
				<div
					v-if="!item"
					class="mw-block-log-nodata"
					:aria-label="$i18n( 'block-user-no-reason-given-aria-details' ).text()"
				>
					{{ $i18n( 'block-user-no-reason-given' ).text() }}
				</div>
				<span v-else>
					{{ item }}
				</span>
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
		<div v-if="moreBlocks" class="mw-block-log-fulllog">
			<a
				:href="mw.util.getUrl( 'Special:Log', { page: targetUser, type: 'block' } )"
			>
				{{ $i18n( 'log-fulllog' ).text() }}
			</a>
		</div>
	</cdx-accordion>
</template>

<script>
const util = require( '../util.js' );
const { defineComponent, ref, watch } = require( 'vue' );
const { CdxAccordion, CdxTable, CdxMenuButton, CdxIcon } = require( '@wikimedia/codex' );
const { storeToRefs } = require( 'pinia' );
const useBlockStore = require( '../stores/block.js' );
const { cdxIconEllipsis, cdxIconEdit, cdxIconTrash } = require( '../icons.json' );

module.exports = exports = defineComponent( {
	name: 'BlockLog',
	components: { CdxAccordion, CdxTable, CdxMenuButton, CdxIcon },
	props: {
		blockLogType: {
			type: String,
			default: 'recent'
		}
	},
	setup( props ) {
		const { targetUser } = storeToRefs( useBlockStore() );

		let title = mw.message( 'block-user-previous-blocks' ).text();
		let emptyState = mw.message( 'block-user-no-previous-blocks' ).text();
		if ( props.blockLogType === 'active' ) {
			title = mw.message( 'block-user-active-blocks' ).text();
			emptyState = mw.message( 'block-user-no-active-blocks' ).text();
		} else if ( props.blockLogType === 'suppress' ) {
			title = mw.message( 'block-user-suppressed-blocks' ).text();
			emptyState = mw.message( 'block-user-no-suppressed-blocks' ).text();
		}

		const columns = [
			{ id: 'timestamp', label: mw.message( 'blocklist-timestamp' ).text(), minWidth: '112px' },
			props.blockLogType === 'recent' ?
				{ id: 'type', label: mw.message( 'blocklist-type-header' ).text(), minWidth: '112px' } :
				{ id: 'target', label: mw.message( 'blocklist-target' ).text(), minWidth: '200px' },
			{ id: 'expiry', label: mw.message( 'blocklist-expiry' ).text(), minWidth: '112px' },
			{ id: 'blockedby', label: mw.message( 'blocklist-by' ).text(), minWidth: '200px' },
			{ id: 'parameters', label: mw.message( 'blocklist-params' ).text(), minWidth: '160px' },
			{ id: 'reason', label: mw.message( 'blocklist-reason' ).text(), minWidth: '160px' },
			{ id: 'modify', label: '', minWidth: '100px' }
		];
		const menuItems = [
			{ label: mw.message( 'block-item-edit' ).text(), value: 'edit', url: mw.util.getUrl( 'Special:Block/' + targetUser.value ), icon: cdxIconEdit },
			{ label: mw.message( 'block-item-remove' ).text(), value: 'remove', url: mw.util.getUrl( 'Special:Unblock/' + targetUser.value ), icon: cdxIconTrash }
		];
		const selection = ref( null );

		const logEntries = ref( [] );
		const moreBlocks = ref( false );

		function getUserBlocks( searchTerm ) {
			const api = new mw.Api();
			const params = {
				action: 'query',
				format: 'json',
				formatversion: 2
			};
			if ( props.blockLogType === 'recent' || props.blockLogType === 'suppress' ) {
				params.list = 'logevents';
				params.lelimit = '10';
				params.letype = props.blockLogType === 'suppress' ? 'suppress' : 'block';
				params.leprop = 'ids|title|type|user|timestamp|comment|details';
				params.letitle = 'User:' + searchTerm;
			} else {
				params.list = 'blocks';
				params.bklimit = '10';
				params.bkprop = 'id|user|by|timestamp|expiry|reason|range|flags';
				params.bkusers = searchTerm;
			}
			return api.get( params )
				.then( ( response ) => response );
		}

		watch( targetUser, ( newValue ) => {
			if ( newValue ) {
				// Update the URLs for the menu items
				menuItems[ 0 ].url = mw.util.getUrl( 'Special:Block/' + newValue );
				menuItems[ 1 ].url = mw.util.getUrl( 'Special:Unblock/' + newValue );
				const newData = [];
				// Look up the block(s) for the target user in the log
				getUserBlocks( newValue ).then( ( response ) => {
					moreBlocks.value = !!response.continue;
					let data = response.query;

					if ( props.blockLogType === 'recent' || props.blockLogType === 'suppress' ) {
						// List of block and supress log entries.
						// The fallback is only necessary for Jest tests.
						data = data || { logevents: [] };
						for ( let i = 0; i < data.logevents.length; i++ ) {
							newData.push( {
								timestamp: {
									timestamp: data.logevents[ i ].timestamp,
									logid: data.logevents[ i ].logid
								},
								type: data.logevents[ i ].action,
								expiry: {
									expires: data.logevents[ i ].params.expiry,
									duration: data.logevents[ i ].params.duration,
									type: data.logevents[ i ].action
								},
								blockedby: data.logevents[ i ].user,
								parameters: data.logevents[ i ].params.flags,
								reason: data.logevents[ i ].comment
							} );
						}
					} else {
						// List of active blocks.
						for ( let i = 0; i < data.blocks.length; i++ ) {
							newData.push( {
								timestamp: {
									timestamp: data.blocks[ i ].timestamp
								},
								target: data.blocks[ i ].user,
								expiry: {
									expires: data.blocks[ i ].expiry,
									duration: data.blocks[ i ].expiry === 'infinity' ? 'infinity' : null
								},
								blockedby: data.blocks[ i ].by,
								// parameters: data.blocks[ i ].range,
								reason: data.blocks[ i ].reason
							} );
						}
					}
					logEntries.value = newData;
				} );
			} else {
				moreBlocks.value = false;
				logEntries.value = [];
			}
		}, { immediate: true } );

		return {
			mw,
			util,
			title,
			emptyState,
			columns,
			menuItems,
			selection,
			cdxIconEllipsis,
			logEntries,
			moreBlocks,
			targetUser
		};
	}
} );
</script>

<style lang="less">
@import 'mediawiki.skin.variables.less';

.mw-block-log {
	word-break: auto-phrase;
}

.mw-block-log-nodata {
	color: @color-subtle;
	font-style: italic;

	&.mw-block-log-params {
		padding-left: @spacing-75;
	}
}

.mw-block-log-fulllog {
	margin-top: @spacing-50;
}

.mw-block-active-blocks__menu {
	text-align: center;
}

.cdx-table__table-wrapper {
	overflow-x: visible;
}
</style>
