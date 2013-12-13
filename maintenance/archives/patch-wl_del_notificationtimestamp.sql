-- Patch for email notification on page deletions

-- A new column 'wl_del_notificationtimestamp' is added to the table 'watchlist'.
-- When a page watched by a user X is deleted by someone else, an email is sent to the watching user X
-- if and only if the field 'wl_del_notificationtimestamp' is '0'. The time/date of sending the mail is then stored in that field.
-- Further pages deletions do not trigger new notification mails as long as user X has not re-visited that page.
-- The field is reset to '0' when user X re-visits the page or when he or she resets all notification timestamps
-- ("notification flags") at once by clicking the new button on his/her watchlist page.
-- Nathan Larson (Leucosticte) 13 December 2013

ALTER TABLE /*$wgDBprefix*/watchlist ADD (wl_del_notificationtimestamp varbinary(14));
