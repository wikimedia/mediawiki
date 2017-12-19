<?php

/**
 * Marker class for search engines that can handle their own pagination, by
 * reporting in their SearchResultSet when a next page is available.
 *
 * SearchEngine implementations not implementing this interface will have
 * an over-fetch performed to determine next page availability.
 */
interface PaginatingSearchEngine {
}
