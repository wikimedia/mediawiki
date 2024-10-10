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
		<div v-if="moreblocks" class="mw-block-fulllog">
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
			{ id: 'type', label: mw.message( 'blocklist-type-header' ).text(), minWidth: '112px' },
			{ id: 'expiry', label: mw.message( 'blocklist-expiry' ).text(), minWidth: '112px' },
			{ id: 'blockedby', label: mw.message( 'blocklist-by' ).text(), minWidth: '150px' },
			{ id: 'parameters', label: mw.message( 'blocklist-params' ).text(), minWidth: '160px' },
			{ id: 'reason', label: mw.message( 'blocklist-reason' ).text(), minWidth: '160px' }
		];

		return {
			columns,
			util,
			mw
		};
	},
	data() {
		return {
			data: [],
			moreblocks: false
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
				lelimit: '10',
				letype: 'block',
				leprop: 'ids|title|type|user|timestamp|comment|details',
				letitle: 'User:' + searchTerm
			};

			return api.get( params )
				.then( ( response ) => response );
		}
	},
	watch: {
		targetUser: {
			handler( newValue ) {
				if ( newValue ) {
					this.data = [];
					// Look up the block(s) for the target user in the log
					this.getUserBlocks( newValue ).then( ( data ) => {
						this.moreblocks = !!data.continue;
						data = data.query;
						// The fallback is only necessary for Jest tests.
						data = data || { logevents: [] };
						for ( let i = 0; i < data.logevents.length; i++ ) {
							this.data.push( {
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
