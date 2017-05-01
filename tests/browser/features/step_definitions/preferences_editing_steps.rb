When(/^I click Editing$/) do
  visit(PreferencesPage).editing_link_element.click
end

Then(/^I can select edit area font style$/) do
  expect(on(PreferencesEditingPage).edit_area_font_style_select_element).to exist
end

Then(/^I can select live preview$/) do
  expect(on(PreferencesEditingPage).live_preview_check_element).to exist
end

Then(/^I can select section editing by double clicking$/) do
  expect(on(PreferencesEditingPage).edit_section_double_click_check_element).to exist
end

Then(/^I can select section editing by right clicking$/) do
  expect(on(PreferencesEditingPage).edit_section_right_click_check_element).to exist
end

Then(/^I can select section editing via edit links$/) do
  expect(on(PreferencesEditingPage).edit_section_edit_link_element).to exist
end

Then(/^I can select show edit toolbar$/) do
  expect(on(PreferencesEditingPage).show_edit_toolbar_check_element).to exist
end

Then(/^I can select show preview before edit box$/) do
  expect(on(PreferencesEditingPage).preview_on_top_check_element).to exist
end

Then(/^I can select show preview on first edit$/) do
  expect(on(PreferencesEditingPage).preview_on_first_check_element).to exist
end

Then(/^I can select to prompt me when entering a blank edit summary$/) do
  expect(on(PreferencesEditingPage).forced_edit_summary_check_element).to exist
end

Then(/^I can select to warn me when I leave an edit page with unsaved changes$/) do
  expect(on(PreferencesEditingPage).unsaved_changes_check_element).to exist
end
