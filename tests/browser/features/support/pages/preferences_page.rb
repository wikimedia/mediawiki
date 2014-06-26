#
# This file is subject to the license terms in the LICENSE file found in the
# qa-browsertests top-level directory and at
# https://git.wikimedia.org/blob/qa%2Fbrowsertests/HEAD/LICENSE. No part of
# qa-browsertests, including this file, may be copied, modified, propagated, or
# distributed except according to the terms contained in the LICENSE file.
#
# Copyright 2012-2014 by the Mediawiki developers. See the CREDITS file in the
# qa-browsertests top-level directory and at
# https://git.wikimedia.org/blob/qa%2Fbrowsertests/HEAD/CREDITS
#
class PreferencesPage
  include PageObject

  include URL
  page_url URL.url("Special:Preferences")

  a(:appearance_link, text: "Appearance")
  a(:date_and_time_link, text: "Date and time")
  a(:editing_link, text: "Editing")
  a(:gadgets_link, text: "Gadgets")
  a(:misc_link, text: "Misc")
  a(:pending_changes_link, text: "Pending changes")
  a(:recent_changes_link, text: "Recent changes")
  a(:search_link, text: "Search")
  a(:user_profile_link, text: "User profile")
  a(:watchlist_link, text: "Watchlist")
end
