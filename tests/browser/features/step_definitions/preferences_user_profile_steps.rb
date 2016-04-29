When(/^I click User profile$/) do
  visit(PreferencesPage).user_profile_link_element.when_present.click
end

Then(/^I can change my gender$/) do
  on(PreferencesUserProfilePage) do |page|
    expect(page.gender_undefined_radio_element).to exist
    expect(page.gender_male_radio_element).to exist
    expect(page.gender_female_radio_element).to exist
  end
end

Then(/^I can change my language$/) do
  expect(on(PreferencesUserProfilePage).lang_select_element).to exist
end

Then(/^I can change my signature$/) do
  expect(on(PreferencesUserProfilePage).signature_field_element).to exist
end

Then(/^I can see my Basic informations$/) do
  expect(on(PreferencesUserProfilePage).basic_info_table_element).to exist
end

Then(/^I can see my email$/) do
  expect(on(PreferencesUserProfilePage).email_table_element).to exist
end

Then(/^I can see my signature$/) do
  expect(on(PreferencesUserProfilePage).signature_table_element).to exist
end
