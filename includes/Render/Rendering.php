<?php

namespace MediaWiki\Render;

/**
 * FIXME Document
 *
 * @since 1.31
 */
interface Rendering {

	/**
	 * @return bool
	 */
	public function isCacheable();

	/**
	 * Get the cacheable text with <mw:editsection> markers still in it. The
	 * return value is suitable for writing back via setText() but is not valid
	 * for display to the user.
	 *
	 * @return string
	 * @since 1.27
	 */
	public function getRawText();

	/**
	 * Get the output HTML
	 *
	 * @param array $options (since 1.31) Transformations to apply to the HTML
	 *  - allowTOC: (bool) Show the TOC, assuming there were enough headings
	 *     to generate one and `__NOTOC__` wasn't used. Default is true,
	 *     but might be statefully overridden.
	 *  - enableSectionEditLinks: (bool) Include section edit links, assuming
	 *     section edit link tokens are present in the HTML. Default is true,
	 *     but might be statefully overridden.
	 *  - unwrap: (bool) Remove a wrapping mw-parser-output div. Default is false.
	 *  - deduplicateStyles: (bool) When true, which is the default, `<style>`
	 *    tags with the `data-mw-deduplicate` attribute set are deduplicated by
	 *    value of the attribute: all but the first will be replaced by `<link
	 *    rel="mw-deduplicated-inline-style" href="mw-data:..."/>` tags, where
	 *    the scheme-specific-part of the href is the (percent-encoded) value
	 *    of the `data-mw-deduplicate` attribute.
	 *
	 * @return string HTML
	 */
	public function getText( $options = [] );

	/**
	 * @deprecated since 1.31 Use getText() options.
	 */
	public function getEditSectionTokens();

	public function getFileSearchOptions();

	public function getHeadItems();

	public function getModules();

	public function getModuleScripts();

	public function getModuleStyles();

	/**
	 * @return array
	 * @since 1.23
	 */
	public function getJsConfigVars();

	public function getWarnings();

	public function getIndexPolicy();

	public function getTOCHTML();

	/**
	 * @return string|null TS_MW timestamp of the revision content
	 */
	public function getTimestamp();

	public function getLimitReportData();

	public function getEnableOOUI();

	/**
	 * Check whether the cache TTL was lowered due to dynamic content
	 *
	 * When content is determined by more than hard state (e.g. page edits),
	 * such as template/file transclusions based on the current timestamp or
	 * extension tags that generate lists based on queries, this return true.
	 *
	 * @return bool
	 * @since 1.25
	 */
	public function hasDynamicContent();

	public function getLanguageLinks();

	public function getInterwikiLinks();

	public function getCategoryLinks();

	public function getCategories();

	/**
	 * @return array
	 * @since 1.25
	 */
	public function getIndicators();

	public function getTitleText();

	public function getSections();

	public function getLinks();

	public function getTemplates();

	public function getTemplateIds();

	public function getImages();

	public function getExternalLinks();

	public function getNoGallery();

	public function getLimitReportJSData();

	/**
	 * @deprecated since 1.31 Use getText() options.
	 */
	public function getTOCEnabled();

	public function getHideNewSection();

	public function getNewSection();

	/**
	 * Get the title to be used for display.
	 *
	 * As per the contract of setDisplayTitle(), this is safe HTML,
	 * ready to be served to the client.
	 *
	 * @return string HTML
	 */
	public function getDisplayTitle();

	public function getFlag( $flag );

	/**
	 * @param string $name The property name to look up.
	 *
	 * @return mixed|bool The value previously set using setProperty(). False if null or no value
	 * was set for the given property name.
	 *
	 * @note You need to use getProperties() to check for boolean and null properties.
	 */
	public function getProperty( $name );

	public function getProperties();

	/**
	 * Gets extensions data previously attached to this ParserOutput using setExtensionData().
	 * Typically, such data would be set while parsing the page, e.g. by a parser function.
	 *
	 * @since 1.21
	 *
	 * @param string $key The key to look up.
	 *
	 * @return mixed|null The value previously set for the given key using setExtensionData()
	 *         or null if no value was set for this key.
	 */
	public function getExtensionData( $key );
}