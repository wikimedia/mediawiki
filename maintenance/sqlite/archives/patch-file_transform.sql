-- Pending server-side file transformations such as rotations, crops, trims
-- This table lists pending operations, which are triggered via the job queue.
CREATE TABLE /*_*/file_transform (
  -- primary key of this transform operation
  ft_id int unsigned NOT NULL PRIMARY KEY AUTO_INCREMENT,

  -- Time this transform operation was originally scheduled
  ft_timestamp binary(14) NOT NULL,

  -- Key on image.img_name
  ft_img_name varchar(255) binary NOT NULL,

  -- Timestamp of the file at time the job was queued, to detect upload conflicts
  ft_img_timestamp binary(14) NOT NULL,

  -- Key on user.user_id; user who triggered this operation.
  -- Will be 'uploader' of the updated file; edit/upload permissions are checked.
  ft_user_id int unsigned NOT NULL,

  -- What sort of transform operation are we doing?
  -- Ability to actually perform these depends on MediaHandler support.
  ft_op varchar(16) NOT NULL,

  -- JSON blob with parameters specific to the op
  ft_params blob

) /*$wgDBTableOptions*/;

CREATE INDEX /*i*/ft_name_timestamp_id ON /*_*/file_transform (ft_img_name, ft_timestamp, ft_id);
