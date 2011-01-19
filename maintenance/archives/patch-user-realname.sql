-- Add a 'real name' field where users can specify the name they want
-- used for author attribution or other places that real names matter.

ALTER TABLE user 
        ADD (user_real_name varbinary(255) NOT NULL default '');
