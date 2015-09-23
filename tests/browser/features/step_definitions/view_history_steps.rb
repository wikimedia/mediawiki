When(/^I click View History$/) do
  on(ViewHistoryPage).view_history_link
end

Then(/^I should see a link to a previous version of the page$/) do
  expect(on(ViewHistoryPage).old_version_link_element).to be_visible
end
