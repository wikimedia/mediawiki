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
When(/^I click Editing$/) do
  visit(PreferencesPage).editing_link_element.when_present.click
end

Then(/^I can select edit area font style$/) do
  expect(on(PreferencesEditingPage).edit_area_font_style_select_element.when_present).to exist
end

Then(/^I can select live preview$/) do
  expect(on(PreferencesEditingPage).live_preview_check_element.when_present).to exist
end

Then(/^I can select section editing by double clicking$/) do
  expect(on(PreferencesEditingPage).edit_section_double_click_check_element.when_present).to exist
end

Then(/^I can select section editing by right clicking$/) do
  expect(on(PreferencesEditingPage).edit_section_right_click_check_element.when_present).to exist
end

Then(/^I can select section editing via edit links$/) do
  expect(on(PreferencesEditingPage).edit_section_edit_link_element.when_present).to exist
end

Then(/^I can select show edit toolbar$/) do
  expect(on(PreferencesEditingPage).show_edit_toolbar_check_element.when_present).to exist
end

Then(/^I can select show preview before edit box$/) do
  expect(on(PreferencesEditingPage).preview_on_top_check_element.when_present).to exist
end

Then(/^I can select show preview on first edit$/) do
  expect(on(PreferencesEditingPage).preview_on_first_check_element.when_present).to exist
end

Then(/^I can select to prompt me when entering a blank edit summary$/) do
  expect(on(PreferencesEditingPage).forced_edit_summary_check_element.when_present).to exist
end

Then(/^I can select to warn me when I leave an edit page with unsaved changes$/) do
  expect(on(PreferencesEditingPage).unsaved_changes_check_element.when_present).to exist
end
