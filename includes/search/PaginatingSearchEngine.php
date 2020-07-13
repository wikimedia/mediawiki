<?php

/**
 * Marker class for search engines that can handle their own pagination, by
 * reporting in their ISearchResultSet when a next page is available. This
 * only applies to search{Title,Text} and not to completion search.
 *
 * SearchEngine implementations not implementing this interface will have
 * an over-fetch performed to determine next page availability.
 *
 * @stable to implement
 */
interface PaginatingSearchEngine {
}
