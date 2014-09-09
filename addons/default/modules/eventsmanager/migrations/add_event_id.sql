--Every event will be given a space assoicated with the event id in which different album will be created. 
ALTER TABLE  `default_file_folders` ADD  `event_id` INT NULL DEFAULT NULL COMMENT 'Any Event Will have a folder in which all the album will be associated.' AFTER  `hidden` ;
