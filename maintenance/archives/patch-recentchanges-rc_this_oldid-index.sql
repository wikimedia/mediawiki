-- Add an index to recentchanges on rc_this_oldid
--
-- T139012
--

CREATE INDEX /*i*/rc_this_oldid ON /*_*/recentchanges(rc_this_oldid);