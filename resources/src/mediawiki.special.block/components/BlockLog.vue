<template>
	<cdx-accordion
		:class="`mw-block-log mw-block-log__type-${ blockLogType }`"
		:open="open || ( blockLogType === 'active' && alreadyBlocked )"
	>
		<template #title>
			{{ title }}
			<cdx-info-chip :icon="infoChip.icon" :status="infoChip.status">
				{{ logEntriesCount }}
			</cdx-info-chip>
		</template>
		<cdx-table
			v-if="blockLogType === 'active'"
			:caption="title"
			:columns="!!logEntries.length ? columns : []"
			:data="logEntries"
			:use-row-headers="false"
			:hide-caption="true"
		>
			<template #tbody>
				<tbody v-if="logEntries.length">
					<tr
						v-for="( item, index ) in logEntries"
						:key="index"
						:class="{ 'cdx-selected-block-row': item.modify.id === blockId }"
					>
						<td>
							<span v-if="item.expiry.expires || item.expiry.duration">
								{{ util.formatTimestamp( item.expiry.expires || item.expiry.duration ) }}
							</span>
							<span v-else></span>
						</td>
						<td>
							<ul v-if="item.parameters.flags && item.parameters.flags.length">
								<li v-if="item.parameters.restrictions">
									{{ mw.message( 'blocklist-editing' ).text() }}
									<ul>
										<li v-for="( parameter, restrictionType ) in item.parameters.restrictions" :key="restrictionType">
											<div v-if="restrictionType === 'pages'">
												{{ mw.message( 'blocklist-editing-page' ).text() }}
												<ul class="mw-block-parameters">
													<li v-for="page in parameter" :key="page">
														<a v-if="page.title" :href="mw.util.getUrl( page.title )">
															{{ page.title }}
														</a>
														<a v-else-if="page.page_title" :href="mw.util.getUrl( page.page_title )">
															{{ page.page_title }}
														</a>
													</li>
												</ul>
											</div>
											<div v-else-if="restrictionType === 'namespaces'">
												{{ mw.message( 'blocklist-editing-ns' ).text() }}
												<ul class="mw-block-parameters">
													<li v-for="namespace in parameter" :key="namespace">
														<a :href="mw.util.getUrl( 'Special:AllPages', { namespace: namespace } )">
															{{ mw.config.get( 'wgFormattedNamespaces' )[ namespace ] }}
														</a>
													</li>
												</ul>
											</div>
											<div v-else-if="restrictionType === 'actions'">
												{{ mw.message( 'blocklist-editing-action' ).text() }}
												<ul class="mw-block-parameters">
													<li v-for="action in parameter" :key="action">
														<!-- Potential messages: -->
														<!-- * ipb-action-create -->
														<!-- * ipb-action-move -->
														<!-- * ipb-action-upload -->
														{{ mw.message( 'ipb-action-' + action ).text() }}
													</li>
												</ul>
											</div>
										</li>
									</ul>
								</li>
								<li v-else>
									{{ mw.message( 'blocklist-editing-sitewide' ).text() }}
								</li>
								<li v-for="( parameter, indexFlag ) in item.parameters.flags" :key="indexFlag">
									{{ util.getBlockFlagMessage( parameter ) }}
								</li>
							</ul>
						</td>
						<td>
							<!-- eslint-disable-next-line vue/no-v-html -->
							<span v-html="item.reason"></span>
						</td>
						<td>
							<a :href="mw.Title.makeTitle( 2, item.blockedby ).getUrl()">
								{{ item.blockedby }}
							</a>
							<span class="mw-usertoollinks">
								{{ $i18n( 'parentheses-start' ).text() }}<a :href="mw.Title.makeTitle( 3, item.blockedby ).getUrl()">
									{{ $i18n( 'talkpagelinktext' ).text() }}
								</a>
								<span>
									{{ $i18n( 'pipe-separator' ).text() }}
									<a :href="mw.Title.makeTitle( 2, `Contributions/${ item.blockedby }` ).getUrl()">
										{{ $i18n( 'contribslink' ).text() }}
									</a>
								</span>{{ $i18n( 'parentheses-end' ).text() }}
							</span>
						</td>
						<td>
							<a
								:href="mw.util.getUrl( 'Special:BlockList', { wpTarget: `#${ item.timestamp.blockid }` } )"
							>
								{{ util.formatTimestamp( item.timestamp.timestamp ) }}
							</a>
						</td>
						<td>
							<span class="mw-blocklist-actions">
								<cdx-button
									type="button"
									:aria-label="$i18n( 'block-item-edit' ).text()"
									action="default"
									weight="quiet"
									data-test="edit-block-button"
									@click="$emit( 'edit-block', item.modify )"
								>
									<cdx-icon :icon="cdxIconEdit" :icon-label="$i18n( 'block-item-edit' ).text()"></cdx-icon>
								</cdx-button>
								<cdx-button
									type="button"
									:aria-label="$i18n( 'block-item-remove' ).text()"
									action="default"
									weight="quiet"
									@click="$emit( 'remove-block', item.modify.id )"
								>
									<cdx-icon :icon="cdxIconTrash" :icon-label="$i18n( 'block-item-remove' ).text()"></cdx-icon>
								</cdx-button>
							</span>
						</td>
					</tr>
				</tbody>
				<tbody v-else>
					<tr class="cdx-table__table__empty-state">
						<td colspan="0" class="cdx-table__table__empty-state-content">
							{{ emptyState }}
						</td>
					</tr>
				</tbody>
			</template>
		</cdx-table>
		<cdx-table
			v-else
			:caption="title"
			:columns="!!logEntries.length ? columns : []"
			:data="logEntries"
			:use-row-headers="false"
			:hide-caption="true"
		>
			<template #empty-state>
				{{ emptyState }}
			</template>
			<template #item-type="{ item }">
				{{ util.getBlockActionMessage( item ) }}
			</template>
			<template #item-expiry="{ item }">
				<span v-if="item.expires || item.duration">
					{{ util.formatTimestamp( item.expires || item.duration ) }}
				</span>
				<span v-else></span>
			</template>
			<template #item-parameters="{ item }">
				<ul v-if="item.flags && item.flags.length">
					<li v-if="item.restrictions">
						{{ mw.message( 'blocklist-editing' ).text() }}
						<ul>
							<li v-for="( parameter, index ) in item.restrictions" :key="index">
								<div v-if="index === 'pages'">
									{{ mw.message( 'blocklist-editing-page' ).text() }}
									<ul class="mw-block-parameters">
										<li v-for="page in parameter" :key="page">
											<a v-if="page.title" :href="mw.util.getUrl( page.title )">
												{{ page.title }}
											</a>
											<a v-else-if="page.page_title" :href="mw.util.getUrl( page.page_title )">
												{{ page.page_title }}
											</a>
										</li>
									</ul>
								</div>
								<div v-else-if="index === 'namespaces'">
									{{ mw.message( 'blocklist-editing-ns' ).text() }}
									<ul class="mw-block-parameters">
										<li v-for="namespace in parameter" :key="namespace">
											<a :href="mw.util.getUrl( 'Special:AllPages', { namespace: namespace } )">
												{{ mwNamespaces[ namespace ] }}
											</a>
										</li>
									</ul>
								</div>
								<div v-else-if="index === 'actions'">
									{{ mw.message( 'blocklist-editing-action' ).text() }}
									<ul class="mw-block-parameters">
										<li v-for="action in parameter" :key="action">
											<!-- Potential messages: -->
											<!-- * ipb-action-create -->
											<!-- * ipb-action-move -->
											<!-- * ipb-action-upload -->
											{{ mw.message( 'ipb-action-' + action ).text() }}
										</li>
									</ul>
								</div>
							</li>
						</ul>
					</li>
					<li v-else>
						{{ mw.message( 'blocklist-editing-sitewide' ).text() }}
					</li>
					<li v-for="( parameter, index ) in item.flags" :key="index">
						{{ util.getBlockFlagMessage( parameter ) }}
					</li>
				</ul>
				<span v-else></span>
			</template>
			<template #item-reason="{ item }">
				<!-- eslint-disable-next-line vue/no-v-html -->
				<span v-html="item"></span>
			</template>
			<template #item-blockedby="{ item }">
				<a :href="mw.Title.makeTitle( 2, item ).getUrl()">
					{{ item }}
				</a>
				<span class="mw-usertoollinks">
					{{ $i18n( 'parentheses-start' ).text() }}<a :href="mw.Title.makeTitle( 3, item ).getUrl()">
						{{ $i18n( 'talkpagelinktext' ).text() }}
					</a>
					<span>
						{{ $i18n( 'pipe-separator' ).text() }}
						<a :href="mw.Title.makeTitle( 2, `Contributions/${ item }` ).getUrl()">
							{{ $i18n( 'contribslink' ).text() }}
						</a>
					</span>{{ $i18n( 'parentheses-end' ).text() }}
				</span>
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
			<template #item-hide="{ item }">
				<a
					class="mw-block-log__actions"
					:href="mw.util.getUrl( 'Special:RevisionDelete', { type: 'logging', ['ids[' + item + ']']: 1 } )"
				>
					{{ $i18n( 'block-change-visibility' ).text() }}
				</a>
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
const { CdxAccordion, CdxTable, CdxButton, CdxInfoChip, CdxIcon } = require( '@wikimedia/codex' );
const { storeToRefs } = require( 'pinia' );
const useBlockStore = require( '../stores/block.js' );
const { cdxIconInfoFilled, cdxIconAlert, cdxIconEdit, cdxIconTrash } = require( '../icons.json' );

module.exports = exports = defineComponent( {
	name: 'BlockLog',
	components: { CdxAccordion, CdxTable, CdxButton, CdxInfoChip, CdxIcon },
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
		'edit-block',
		'remove-block'
	],
	setup( props ) {
		const store = useBlockStore();
		const { alreadyBlocked, blockId, targetUser } = storeToRefs( store );
		let title = mw.message( 'block-user-previous-blocks' ).text();
		let emptyState = mw.message( 'block-user-no-previous-blocks' ).text();
		if ( props.blockLogType === 'active' ) {
			title = mw.message( 'block-user-active-blocks' ).text();
			emptyState = mw.message( 'block-user-no-active-blocks' ).text();
		} else if ( props.blockLogType === 'suppress' ) {
			title = mw.message( 'block-user-suppressed-blocks' ).text();
			emptyState = mw.message( 'block-user-no-suppressed-blocks' ).text();
		}

		const columns = [];
		if ( props.blockLogType === 'recent' || props.blockLogType === 'suppress' ) {
			columns.push(
				{ id: 'type', label: mw.message( 'blocklist-type-header' ).text() }
			);
		}
		columns.push(
			{ id: 'expiry', label: mw.message( 'blocklist-expiry' ).text(), width: '15%' },
			{ id: 'parameters', label: mw.message( 'blocklist-params' ).text() },
			{ id: 'reason', label: mw.message( 'blocklist-reason' ).text() },
			{ id: 'blockedby', label: mw.message( 'blocklist-by' ).text() },
			{ id: 'timestamp', label: mw.message( 'blocklist-timestamp' ).text(), width: '15%' },
			...( props.blockLogType === 'active' || props.canDeleteLogEntry ?
				[ { id: props.blockLogType === 'active' ? 'modify' : 'hide', label: mw.message( 'blocklist-actions-header' ) } ] : [] )
		);

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

		const infoChip = computed( () => ( logEntries.value.length > 0 ? { icon: cdxIconAlert, status: 'warning' } : { icon: cdxIconInfoFilled, status: 'notice' } ) );
		const mwNamespaces = Object.keys( mw.config.get( 'wgFormattedNamespaces' ) ).map( ( ns ) => {
			if ( ns === '0' ) {
				return mw.msg( 'blanknamespace' );
			}
			return mw.config.get( 'wgFormattedNamespaces' )[ ns ];
		} );

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
					parameters: {
						flags: logevent.params.flags,
						restrictions: logevent.params.restrictions ? logevent.params.restrictions : null
					},
					reason: logevent.parsedcomment,
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
								parameters: {
									flags: [
										data.blocks[ i ].anononly ? 'anononly' : null,
										data.blocks[ i ].nocreate ? 'nocreate' : null,
										data.blocks[ i ].autoblock ? null : 'noautoblock',
										data.blocks[ i ].noemail ? 'noemail' : null,
										data.blocks[ i ].allowusertalk ? null : 'nousertalk',
										data.blocks[ i ].hidden ? 'hiddenname' : null
									].filter( ( e ) => e !== null ),
									restrictions: Object.keys( data.blocks[ i ].restrictions ).length ? data.blocks[ i ].restrictions : null
								},
								reason: data.blocks[ i ].parsedreason
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
			cdxIconEdit,
			cdxIconTrash,
			logEntries,
			moreBlocks,
			alreadyBlocked,
			blockId,
			targetUser,
			logEntriesCount,
			infoChip,
			mwNamespaces
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

	table ul {
		margin: 0;
	}

	.cdx-accordion__content {
		font-size: unset;
	}

	.mw-usertoollinks {
		white-space: nowrap;
	}

	table ul.mw-block-parameters {
		margin-left: 1.7em;
	}

	tr.cdx-selected-block-row {
		background: @background-color-progressive-subtle--hover;
	}

	.mw-active-blocks__actions {
		display: flex;
	}
}

.mw-block-log-fulllog {
	margin-top: @spacing-50;
}
</style>
