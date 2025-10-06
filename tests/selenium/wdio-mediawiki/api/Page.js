/**
 * A small helper for reading, editing, and deleting MediaWiki pages via the API.
 *
 * It wraps common actions and handles CSRF tokens automatically when needed.
 */
export class Pages {
	constructor( { request, session, summary, auth } ) {
		this.request = request;
		this.session = session;
		this.defaultSummary = summary;
		this.auth = auth;
	}

	/**
	 * Read a page’s current wikitext.
	 *
	 * @param {string} title - Exact page title (e.g. "Project:Sandbox").
	 * @return {Promise<Object>} MediaWiki API response containing revision content.
	 */
	async read( title ) {
		const params = {
			action: 'query',
			prop: 'revisions',
			rvprop: 'content',
			rvslots: 'main',
			titles: title,
			redirects: 'true'
		};
		return this.request( params );
	}

	/**
	 * Create or edit a page.
	 *
	 * Automatically fetches a CSRF token if it's not available
	 *
	 * @param {string} title - Page title to edit.
	 * @param {string} text - Full wikitext to save.
	 * @param {string} [summary] - Edit summary. Falls back to the default summary.
	 * @return {Promise<Object>} MediaWiki API response for the edit action.
	 */
	async edit( title, text, summary ) {
		if ( !this.session.csrfToken ) {
			await this.auth.getEditToken();
		}
		return this.request(
			{
				action: 'edit',
				title,
				text,
				summary: summary || this.defaultSummary,
				bot: true,
				token: this.session.csrfToken
			}
		);
	}

	/**
	 * Delete a page.
	 *
	 * Automatically fetches a CSRF token if it's not available.
	 *
	 * @param {string} title - Page title to delete.
	 * @param {string} reason - Deletion reason. Falls back to the default summary.
	 * @return {Promise<Object>} MediaWiki API response for the delete action.
	 */
	async delete( title, reason ) {
		if ( !this.session.csrfToken ) {
			await this.auth.getEditToken();
		}
		return this.request(
			{
				action: 'delete',
				title,
				reason: reason || this.defaultSummary,
				token: this.session.csrfToken
			}
		);
	}
}
