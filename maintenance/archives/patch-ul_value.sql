-- Add the ul_value column to updatelog

ALTER TABLE /*_*/updatelog
  add ul_value blob;
