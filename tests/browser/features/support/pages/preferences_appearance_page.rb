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
class PreferencesAppearancePage
  include PageObject

  include URL
  page_url URL.url("Special:Preferences#mw-prefsection-rendering")

  checkbox(:auto_number_check, id: "mw-input-wpnumberheadings")
  checkbox(:collapse_sidebar_vector_check, id: "mw-input-wpvector-collapsiblenav")
  radio_button(:cologne_blue, id: "mw-input-wpskin-cologneblue")
  radio_button(:day_mo_year_radio, id: "mw-input-wpdate-dmy")
  checkbox(:dont_show_aft_check, id: "mw-input-wparticlefeedback-disable")
  checkbox(:exclude_from_experiments_check, id: "mw-input-wpvector-noexperiments")
  checkbox(:hidden_categories_check, id: "mw-input-wpshowhiddencats")
  radio_button(:iso_8601_radio, id: "mw-input-wpdate-ISO_8601")
  span(:local_time_span, id: "wpLocalTime")
  radio_button(:mo_day_year_radio, id: "mw-input-wpdate-mdy")
  radio_button(:modern, id: "mw-input-wpskin-modern")
  radio_button(:monobook, id: "mw-input-wpskin-monobook")
  radio_button(:no_preference_radio, id: "mw-input-wpdate-default")
  text_field(:other_offset, id: "mw-input-wptimecorrection-other")
  a(:restore_default_link, text:/Restore all default settings/)
  button(:save_button, text: "Save")
  select_list(:size_select, id: "mw-input-wpimagesize")
  select_list(:threshold_select, id: "mw-input-wpstubthreshold")
  select_list(:time_offset_select, id: "mw-input-wptimecorrection")
  select_list(:thumb_select, id: "mw-input-wpthumbsize")
  select_list(:underline_select, id: "mw-input-wpunderline")
  radio_button(:vector, id: "mw-input-wpskin-vector")
  radio_button(:year_mo_day_radio, id: "mw-input-wpdate-ymd")
end

