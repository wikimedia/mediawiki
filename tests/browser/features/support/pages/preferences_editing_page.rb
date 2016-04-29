class PreferencesEditingPage
  include PageObject

  page_url 'Special:Preferences#mw-prefsection-rendering'

  select_list(:edit_area_font_style_select, id: 'mw-input-wpeditfont')
  checkbox(:edit_section_double_click_check, id: 'mw-input-wpeditondblclick')
  checkbox(:edit_section_edit_link, id: 'mw-input-wpeditsectiononrightclick')
  checkbox(:edit_section_right_click_check, id: 'mw-input-wpeditsectiononrightclick')
  checkbox(:forced_edit_summary_check, id: 'mw-input-wpforceeditsummary')
  checkbox(:live_preview_check, id: 'mw-input-wpuselivepreview')
  checkbox(:preview_on_first_check, id: 'mw-input-wppreviewonfirst')
  checkbox(:preview_on_top_check, id: 'mw-input-wppreviewontop')
  checkbox(:show_edit_toolbar_check, id: 'mw-input-wpshowtoolbar')
  checkbox(:unsaved_changes_check, id: 'mw-input-wpuseeditwarning')
end
