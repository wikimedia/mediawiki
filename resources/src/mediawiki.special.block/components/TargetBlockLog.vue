<template>
	<cdx-accordion>
		<template #title>
			{{ $i18n( 'block-user-previous-blocks' ).text() }}
		</template>
		<cdx-table
			class="mw-block-previous-blocks"
			:caption="$i18n( 'block-user-previous-blocks' ).text()"
			:columns="!!logEntries.length ? columns : []"
			:data="logEntries"
			:use-row-headers="true"
			:hide-caption="true"
		>
			<template #empty-state>
				{{ $i18n( 'block-user-no-previous-blocks' ).text() }}
			</template>
			<template #item-timestamp="{ item }">
				<a
					:href="mw.util.getUrl( 'Special:Log', { logid: item.logid } )"
				>
					{{ util.formatTimestamp( item.timestamp ) }}
				</a>
			</template>
			<template #item-type="{ item }">
				{{ util.getBlockActionMessage( item ) }}
			</template>
			<template #item-expiry="{ item }">
				<div v-if="item.type === 'unblock'" class="mw-block-nodata">
					—
				</div>
				<span v-else>
					{{ util.formatTimestamp( item.expires, item.duration ) }}
				</span>
			</template>
			<template #item-blockedby="{ item }">
				<!-- eslint-disable-next-line vue/no-v-html -->
				<span v-html="$i18n( 'userlink-with-contribs', item ).parse()"></span>
			</template>
			<template #item-parameters="{ item }">
				<div v-if="!item" class="mw-block-nodata mw-block-params-hyphen">
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
					class="mw-block-nodata"
					:aria-label="$i18n( 'block-user-no-reason-given-aria-details' ).text()"
				>
					{{ $i18n( 'block-user-no-reason-given' ).text() }}
				</div>
				<span v-else>
					{{ item }}
				</span>
			</template>
		</cdx-table>
		<div v-if="moreBlocks" class="mw-block-fulllog">
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
const { CdxAccordion, CdxTable } = require( '@wikimedia/codex' );
const { storeToRefs } = require( 'pinia' );
const useBlockStore = require( '../stores/block.js' );

module.exports = exports = defineComponent( {
	name: 'TargetBlockLog',
	components: { CdxAccordion, CdxTable },
	setup() {
		const { targetUser } = storeToRefs( useBlockStore() );
		const columns = [
			{ id: 'timestamp', label: mw.message( 'blocklist-timestamp' ).text(), minWidth: '112px' },
			{ id: 'type', label: mw.message( 'blocklist-type-header' ).text(), minWidth: '112px' },
			{ id: 'expiry', label: mw.message( 'blocklist-expiry' ).text(), minWidth: '112px' },
			{ id: 'blockedby', label: mw.message( 'blocklist-by' ).text(), minWidth: '150px' },
			{ id: 'parameters', label: mw.message( 'blocklist-params' ).text(), minWidth: '160px' },
			{ id: 'reason', label: mw.message( 'blocklist-reason' ).text(), minWidth: '160px' }
		];
		const logEntries = ref( [] );
		const moreBlocks = ref( false );

		function getUserBlocks( searchTerm ) {
			const api = new mw.Api();
			const params = {
				action: 'query',
				format: 'json',
				formatversion: 2,
				list: 'logevents',
				lelimit: '10',
				letype: 'block',
				leprop: 'ids|title|type|user|timestamp|comment|details',
				letitle: 'User:' + searchTerm
			};
			return api.get( params )
				.then( ( response ) => response );
		}

		watch( targetUser, ( newValue ) => {
			if ( newValue ) {
				const newData = [];
				// Look up the block(s) for the target user in the log
				getUserBlocks( newValue ).then( ( data ) => {
					moreBlocks.value = !!data.continue;
					data = data.query;
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
			columns,
			logEntries,
			moreBlocks,
			targetUser
		};
	}
} );
</script>

<style lang="less">
@import 'mediawiki.skin.variables.less';

.mw-block-previous-blocks {
	word-break: auto-phrase;
}

.mw-block-params-hyphen {
	padding-left: @spacing-75;
}

.mw-block-fulllog {
	margin-top: @spacing-50;
}

.mw-block-nodata {
	color: @color-subtle;
	font-style: italic;
}
</style>
