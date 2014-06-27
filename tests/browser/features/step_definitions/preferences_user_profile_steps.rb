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
When(/^I click User profile$/) do
  visit(PreferencesPage).user_profile_link_element.when_present.click
end

Then(/^I can change my gender$/) do
  on(PreferencesUserProfilePage) do |page|
    page.gender_undefined_radio_element.should exist
    page.gender_male_radio_element.should exist
    page.gender_female_radio_element.should exist
  end
end

Then(/^I can change my language$/) do
  on(PreferencesUserProfilePage).lang_select_element.should exist
end

Then(/^I can change my signature$/) do
  on(PreferencesUserProfilePage).signature_field_element.should exist
end

Then(/^I can see my Basic informations$/) do
  on(PreferencesUserProfilePage).basic_info_table_element.should exist
end

Then(/^I can see my email$/) do
  on(PreferencesUserProfilePage).email_table_element.should exist
end

Then(/^I can see my signature$/) do
  on(PreferencesUserProfilePage).signature_table_element.should exist
end

