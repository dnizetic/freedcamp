Project can be setup by adjusting DB configuration in config/database.php.

If DB credentials are correct, check: 

http://localhost/{project_folder}/index.php/comments/1

Run following query to enable "users typing" functionality.

```CREATE TABLE IF NOT EXISTS `users_typing` (
`id` int(11) NOT NULL,
  `ip` varchar(255) NOT NULL,
  `typing` tinyint(4) NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;


ALTER TABLE `users_typing`
 ADD PRIMARY KEY (`id`);

ALTER TABLE `users_typing`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;```