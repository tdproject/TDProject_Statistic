ALTER TABLE `user_performance` 
    ADD `billable_hours` INT(10) NOT NULL AFTER `performance`,
    ADD `turnover` INT(10) NOT NULL AFTER `billable_hours`,
    ADD `costs` INT(10) NOT NULL AFTER `turnover`;
    
TRUNCATE TABLE `user_performance`;