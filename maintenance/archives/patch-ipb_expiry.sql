-- Adds the ipb_expiry field to ipblocks

ALTER TABLE ipblocks ADD ipb_expiry char(14) binary NOT NULL default '';

-- All IP blocks have one day expiry
UPDATE ipblocks SET ipb_expiry = date_format(date_add(ipb_timestamp,INTERVAL 1 DAY),"%Y%m%d%H%i%s") WHERE ipb_user = 0;

-- Null string is fine for user blocks, since this indicates infinity
