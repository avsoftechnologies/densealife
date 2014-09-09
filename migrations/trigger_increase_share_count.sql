-- Increase share count as a new row is inserted into default_shares. 
DELIMITER $$
CREATE TRIGGER increase_share_count
AFTER INSERT ON `default_shares` FOR EACH ROW
begin
       
       
           UPDATE
                  default_comments
           SET 
                  share_count = (share_count +1)
           WHERE
                  id = NEW.fk_comment_id;
END;
$$
DELIMITER ;