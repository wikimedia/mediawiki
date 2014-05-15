When(/^I click View History$/) do
  on(ViewHistoryPage).view_history_link
end

Then(/^I should see a link to a previous version of the page$/) do
  on(ViewHistoryPage).old_version_link_element.should be_visible
end

