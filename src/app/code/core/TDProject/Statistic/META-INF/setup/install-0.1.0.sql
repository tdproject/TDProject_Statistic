INSERT INTO `resource` (`resource_locale`, `key`, `message`) VALUES
('en_US', 'tab.label.user.performance', 'User Performance'),
('de_DE', 'tab.label.user.performance', 'Benutzer Performance'),
('en_US', 'page.navigation.root.user.performance', 'User Performance'),
('de_DE', 'page.navigation.root.user.performance', 'Benutzer Performance'),
('en_US', 'title.user.performance.overview', 'User Performance'),
('de_DE', 'title.user.performance.overview', 'Benutzer Performance');

CREATE TABLE `user_performance` (
    `user_performance_id` int(10) NOT NULL, 
    `user_id_fk` int(10) NOT NULL, 
    `month` int(10) NOT NULL, 
    `year` int(10) NOT NULL
) DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ENGINE=ndbcluster;
        
ALTER TABLE 
    `user_performance` 
ADD CONSTRAINT 
    user_performance_pk 
PRIMARY KEY (`user_performance_id`); 

ALTER TABLE 
    `user_performance` 
CHANGE 
    user_performance_id `user_performance_id` int(10) AUTO_INCREMENT;

CREATE INDEX 
    user_performance_idx_01 
ON 
    `user_performance` (`user_id_fk`);

ALTER TABLE 
    `user_performance` 
ADD CONSTRAINT 
    user_performance_fk_01 
FOREIGN KEY (`user_id_fk`) 
REFERENCES `user` (`user_id`);