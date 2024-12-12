<template>
	<cdx-accordion
		:class="`mw-block-log mw-block-log__type-${ blockLogType }`"
		:open="open"
	>
		<template #title>
			{{ title }}
			<cdx-info-chip :icon="infoChipIcon" :status="infoChipStatus">
				{{ logEntriesCount }}
			</cdx-info-chip>
		</template>
		<cdx-table
			:caption="title"
			:columns="!!logEntries.length ? columns : []"
			:data="logEntries"
			:use-row-headers="false"
			:hide-caption="true"
		>
			<template v-if="blockLogType === 'active'" #header>
				<cdx-button
					type="button"
					action="progressive"
					weight="primary"
					class="mw-block-log__create-button"
					@click="$emit( 'create-block' )"
				>
					<cdx-icon :icon="cdxIconCancel"></cdx-icon>
					{{ $i18n( 'block-create' ).text() }}
				</cdx-button>
			</template>
			<template #empty-state>
				{{ emptyState }}
			</template>
			<template #item-modify="{ item }">
				<a @click="$emit( 'edit-block', item )">
					{{ $i18n( 'block-item-edit' ).text() }}
				</a>
				<a
					:href="mw.util.getUrl( 'Special:Unblock/' + targetUser )"
				>
					{{ $i18n( 'block-item-remove' ).text() }}
				</a>
			</template>
			<template #item-hide="{ item }">
				<a
					:href="mw.util.getUrl( 'Special:RevisionDelete', { type: 'logging', ['ids[' + item + ']']: 1 } )"
				>
					{{ $i18n( 'block-change-visibility' ).text() }}
				</a>
			</template>
			<template #item-timestamp="{ item }">
				<a
					v-if="item.logid"
					:href="mw.util.getUrl( 'Special:Log', { logid: item.logid } )"
				>
					{{ util.formatTimestamp( item.timestamp ) }}
				</a>
				<a
					v-else-if="item.blockid"
					:href="mw.util.getUrl( 'Special:BlockList', { wpTarget: `#${ item.blockid }` } )"
				>
					{{ util.formatTimestamp( item.timestamp ) }}
				</a>
				<span v-else>
					{{ util.formatTimestamp( item.timestamp ) }}
				</span>
			</template>
			<template v-if="blockLogType !== 'active'" #item-type="{ item }">
				{{ util.getBlockActionMessage( item ) }}
			</template>
			<template v-if="blockLogType === 'active'" #item-target="{ item }">
				<!-- eslint-disable-next-line vue/no-v-html -->
				<span v-html="$i18n( 'userlink-with-contribs', item ).parse()"></span>
			</template>
			<template #item-expiry="{ item }">
				<span v-if="item.expires || item.duration">
					{{ util.formatTimestamp( item.expires || item.duration ) }}
				</span>
				<span v-else></span>
			</template>
			<template #item-blockedby="{ item }">
				<!-- eslint-disable-next-line vue/no-v-html -->
				<span v-html="$i18n( 'userlink-with-contribs', item ).parse()"></span>
			</template>
			<template #item-parameters="{ item }">
				<ul v-if="item && item.length">
					<li v-for="( parameter, index ) in item" :key="index">
						{{ util.getBlockFlagMessage( parameter ) }}
					</li>
				</ul>
				<span v-else></span>
			</template>
			<template #item-reason="{ item }">
				{{ item }}
			</template>
		</cdx-table>
		<div v-if="moreBlocks" class="mw-block-log-fulllog">
			<a
				:href="mw.util.getUrl( 'Special:Log', { page: 'User:' + targetUser, type: blockLogType === 'suppress' ? 'suppress' : 'block' } )"
			>
				{{ $i18n( 'log-fulllog' ).text() }}
			</a>
		</div>
	</cdx-accordion>
</template>

<script>
const util = require( '../util.js' );
const { computed, defineComponent, ref, watch } = require( 'vue' );
const { CdxAccordion, CdxTable, CdxButton, CdxIcon, CdxInfoChip } = require( '@wikimedia/codex' );
const { storeToRefs } = require( 'pinia' );
const useBlockStore = require( '../stores/block.js' );
const { cdxIconCancel, cdxIconClock, cdxIconAlert } = require( '../icons.json' );

module.exports = exports = defineComponent( {
	name: 'BlockLog',
	components: { CdxAccordion, CdxTable, CdxButton, CdxIcon, CdxInfoChip },
	props: {
		open: {
			type: Boolean,
			default: false
		},
		blockLogType: {
			type: String,
			default: 'recent'
		},
		canDeleteLogEntry: {
			type: Boolean,
			default: false
		}
	},
	emits: [
		'create-block',
		'edit-block'
	],
	setup( props ) {
		const store = useBlockStore();
		const { targetUser } = storeToRefs( store );

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
			...( props.blockLogType === 'active' || props.canDeleteLogEntry ?
				[ { id: props.blockLogType === 'active' ? 'modify' : 'hide', label: '', minWidth: '100px' } ] : [] ),
			{ id: 'timestamp', label: mw.message( 'blocklist-timestamp' ).text(), minWidth: '112px' },
			props.blockLogType === 'recent' || props.blockLogType === 'suppress' ?
				{ id: 'type', label: mw.message( 'blocklist-type-header' ).text(), minWidth: '112px' } :
				{ id: 'target', label: mw.message( 'blocklist-target' ).text(), minWidth: '200px' },
			{ id: 'expiry', label: mw.message( 'blocklist-expiry' ).text(), minWidth: '112px' },
			{ id: 'blockedby', label: mw.message( 'blocklist-by' ).text(), minWidth: '200px' },
			{ id: 'parameters', label: mw.message( 'blocklist-params' ).text(), minWidth: '160px' },
			{ id: 'reason', label: mw.message( 'blocklist-reason' ).text(), minWidth: '160px' }
		];

		const logEntries = ref( [] );
		const moreBlocks = ref( false );
		const FETCH_LIMIT = 10;

		const logEntriesCount = computed( () => {
			if ( moreBlocks.value ) {
				return mw.msg(
					'block-user-label-count-exceeds-limit',
					mw.language.convertNumber( FETCH_LIMIT )
				);
			}
			return mw.language.convertNumber( logEntries.value.length );
		} );

		const infoChipIcon = computed( () => props.blockLogType === 'active' ? cdxIconAlert : cdxIconClock );
		const infoChipStatus = computed( () => logEntries.value.length > 0 && props.blockLogType === 'active' ? 'warning' : 'notice' );

		/**
		 * Construct the data object needed for a template row, from a logentry API response.
		 *
		 * @param {Array} logevents
		 * @return {Array}
		 */
		function logentriesToRows( logevents ) {
			const rows = [];
			for ( let i = 0; i < logevents.length; i++ ) {
				const logevent = logevents[ i ];
				rows.push( {
					timestamp: {
						timestamp: logevent.timestamp,
						logid: logevent.logid
					},
					type: logevent.action,
					expiry: {
						expires: logevent.params.expiry,
						duration: logevent.params.duration,
						type: logevent.action
					},
					blockedby: logevent.user,
					parameters: logevent.params.flags,
					reason: logevent.comment,
					hide: logevent.logid
				} );
			}
			return rows;
		}

		watch( targetUser, ( newValue ) => {
			if ( newValue ) {
				store.getBlockLogData( props.blockLogType ).then( ( responses ) => {
					let newData = [];
					const data = responses[ 0 ].query;

					if ( props.blockLogType === 'recent' ) {
						// List of recent block entries.
						newData = logentriesToRows( data.logevents );
						moreBlocks.value = newData.length >= FETCH_LIMIT;

					} else if ( props.blockLogType === 'suppress' ) {
						// List of suppress/block or suppress/reblock log entries.
						newData.push( ...logentriesToRows( data.logevents ) );
						newData.push( ...logentriesToRows( responses[ 1 ].query.logevents ) );
						newData.sort( ( a, b ) => b.timestamp.logid - a.timestamp.logid );
						moreBlocks.value = newData.length >= FETCH_LIMIT;
						// Re-apply limit, as each may have been longer.
						newData = newData.slice( 0, FETCH_LIMIT );

					} else {
						// List of active blocks.
						for ( let i = 0; i < data.blocks.length; i++ ) {
							newData.push( {
								// Store the entire API response, for passing in when editing the block.
								modify: data.blocks[ i ],
								timestamp: {
									timestamp: data.blocks[ i ].timestamp,
									blockid: data.blocks[ i ].id
								},
								target: data.blocks[ i ].user,
								expiry: {
									expires: data.blocks[ i ].expiry,
									duration: mw.util.isInfinity( data.blocks[ i ].expiry ) ? 'infinity' : null
								},
								blockedby: data.blocks[ i ].by,
								parameters:
									[
										data.blocks[ i ].anononly ? 'anononly' : null,
										data.blocks[ i ].nocreate ? 'nocreate' : null,
										data.blocks[ i ].autoblock ? null : 'noautoblock',
										data.blocks[ i ].noemail ? 'noemail' : null,
										data.blocks[ i ].allowusertalk ? null : 'nousertalk',
										data.blocks[ i ].hidden ? 'hiddenname' : null
									].filter( ( e ) => e !== null ),
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
			cdxIconCancel,
			logEntries,
			moreBlocks,
			targetUser,
			logEntriesCount,
			infoChipIcon,
			infoChipStatus
		};
	}
} );
</script>

<style lang="less">
@import 'mediawiki.skin.variables.less';

.mw-block-log {
	word-break: auto-phrase;

	// Align the new-block button to the left, because there's no table caption.
	.cdx-table__header {
		justify-content: flex-start;
	}
}

.mw-block-log-fulllog {
	margin-top: @spacing-50;
}

.mw-block-active-blocks__menu {
	text-align: center;
}
</style>
