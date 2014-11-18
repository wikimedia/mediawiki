When(/^I click View History$/) do
  on(ViewHistoryPage).view_history_link
end

Then(/^I should see a link to a previous version of the page$/) do
  expect(on(ViewHistoryPage).old_version_link_element).to be_visible
end

Then(/^View history link should be present$/) do
  expect(on(RandomPage).view_history_link_element).to exist
end
