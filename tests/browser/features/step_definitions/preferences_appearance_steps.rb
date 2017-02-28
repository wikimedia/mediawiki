When(/^I click Appearance$/) do
  visit(PreferencesPage).appearance_link_element.click
end

When(/^I navigate to Preferences$/) do
  visit(PreferencesPage)
end

Then(/^I can click Save$/) do
  expect(on(PreferencesPage).save_button_element).to exist
end

Then(/^I can restore default settings$/) do
  expect(on(PreferencesAppearancePage).restore_default_link_element).to exist
end

Then(/^I can see local time$/) do
  expect(on(PreferencesAppearancePage).local_time_span_element).to exist
end

Then(/^I can see time offset section$/) do
  expect(on(PreferencesAppearancePage).time_offset_table_element).to be_visible
end

Then(/^I can select date format$/) do
  on(PreferencesAppearancePage) do |page|
    expect(page.no_preference_radio_element).to exist
    expect(page.mo_day_year_radio_element).to exist
    expect(page.day_mo_year_radio_element).to exist
    expect(page.year_mo_day_radio_element).to exist
    expect(page.iso_8601_radio_element).to exist
  end
end

Then(/^I can select image size$/) do
  expect(on(PreferencesAppearancePage).size_select_element).to exist
end

Then(/^I can select my time zone$/) do
  on(PreferencesAppearancePage) do |page|
    expect(page.time_offset_select_element).to exist
    expect(page.other_offset_element).to exist
  end
end

Then(/^I can select skin Vector$/) do
  on(PreferencesAppearancePage) do |page|
    expect(page.vector_element).to exist
  end
end

Then(/^I can select Threshold for stub link$/) do
  expect(on(PreferencesAppearancePage).threshold_select_element).to exist
end

Then(/^I can select thumbnail size$/) do
  expect(on(PreferencesAppearancePage).thumb_select_element).to exist
end

Then(/^I can select underline preferences$/) do
  expect(on(PreferencesAppearancePage).underline_select_element).to exist
end

Then(/^I have advanced options checkboxes$/) do
  on(PreferencesAppearancePage) do |page|
    expect(page.hidden_categories_check_element).to exist
    expect(page.auto_number_check_element).to exist
  end
end
