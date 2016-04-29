class PreferencesAppearancePage
  include PageObject

  page_url 'Special:Preferences#mw-prefsection-rendering'

  checkbox(:auto_number_check, id: 'mw-input-wpnumberheadings')
  radio_button(:day_mo_year_radio, id: 'mw-input-wpdate-dmy')
  checkbox(:dont_show_aft_check, id: 'mw-input-wparticlefeedback-disable')
  checkbox(:exclude_from_experiments_check, id: 'mw-input-wpvector-noexperiments')
  checkbox(:hidden_categories_check, id: 'mw-input-wpshowhiddencats')
  radio_button(:iso_8601_radio, id: 'mw-input-wpdate-ISO_8601')
  span(:local_time_span, id: 'wpLocalTime')
  radio_button(:mo_day_year_radio, id: 'mw-input-wpdate-mdy')
  radio_button(:no_preference_radio, id: 'mw-input-wpdate-default')
  text_field(:other_offset, id: 'mw-input-wptimecorrection-other')
  a(:restore_default_link, id: 'mw-prefs-restoreprefs')
  select_list(:size_select, id: 'mw-input-wpimagesize')
  select_list(:threshold_select, id: 'mw-input-wpstubthreshold')
  select_list(:time_offset_select, id: 'mw-input-wptimecorrection')
  table(:time_offset_table, id: 'mw-htmlform-timeoffset')
  select_list(:thumb_select, id: 'mw-input-wpthumbsize')
  select_list(:underline_select, id: 'mw-input-wpunderline')
  radio_button(:vector, id: 'mw-input-wpskin-vector')
  radio_button(:year_mo_day_radio, id: 'mw-input-wpdate-ymd')
end
