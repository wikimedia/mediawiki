When(/^I click View History$/) do
  on(ViewHistoryPage).view_history_link
end

Then(/^I should see a link to a previous version of the page$/) do
  on(ViewHistoryPage).old_version_link_element.should be_visible
end

When(/^I go to a random page$/) do
  pending # express the regexp above with the code you wish you had
end

Then(/^View history link should be present$/) do
  pending # express the regexp above with the code you wish you had
end

