Given(/^I open the main wiki URL$/) do
  visit(MainPage)
end

Then(/^I should see a link for View History$/) do
  expect(on(MainPage).view_history_link_element).to be_visible
end

Then(/^I should see a link for Edit$/) do
  expect(on(MainPage).edit_link_element).to be_visible
end

Then(/^I should see a link for Recent changes$/) do
  expect(on(MainPage).recent_changes_link_element).to be_visible
end

Then(/^I should see a link for Random page$/) do
  expect(on(MainPage).random_page_link_element).to be_visible
end

Then(/^I should see a link for Help$/) do
  expect(on(MainPage).help_link_element).to be_visible
end

Then(/^I should see a link for What links here$/) do
  expect(on(MainPage).what_links_here_link_element).to be_visible
end

Then(/^I should see a link for Related changes$/) do
  expect(on(MainPage).related_changes_link_element).to be_visible
end

Then(/^I should see a link for Special pages$/) do
  expect(on(MainPage).special_pages_link_element).to be_visible
end

Then(/^I should see a link for Printable version$/) do
  expect(on(MainPage).printable_version_link_element).to be_visible
end

Then(/^I should see a link for Permanent link$/) do
  expect(on(MainPage).permanent_link_link_element).to be_visible
end

Then(/^I should see a link for Page information$/) do
  expect(on(MainPage).page_information_link_element).to be_visible
end
