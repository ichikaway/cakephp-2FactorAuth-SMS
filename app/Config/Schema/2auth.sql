-- Create syntax for TABLE 'authors'
	CREATE TABLE `authors` (
			`id` int(11) NOT NULL AUTO_INCREMENT,
			`name` varchar(45) NOT NULL,
			`created` datetime NOT NULL,
			`modified` datetime NOT NULL,
			PRIMARY KEY (`id`)
			) ENGINE=InnoDB DEFAULT CHARSET=utf8;

	-- Create syntax for TABLE 'posts'
	CREATE TABLE `posts` (
			`id` int(11) NOT NULL AUTO_INCREMENT,
			`title` varchar(45) NOT NULL,
			`body` text NOT NULL,
			`author_id` int(11) NOT NULL,
			`created` datetime NOT NULL,
			`modified` datetime NOT NULL,
			PRIMARY KEY (`id`),
			KEY `author_id` (`author_id`),
			CONSTRAINT `posts_ibfk_1` FOREIGN KEY (`author_id`) REFERENCES `authors` (`id`)
			) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

	-- Create syntax for TABLE 'users'
	CREATE TABLE `users` (
			`id` int(10) unsigned NOT NULL AUTO_INCREMENT,
			`username` varchar(50) DEFAULT NULL,
			`password` varchar(50) DEFAULT NULL,
			`tel` varchar(100) DEFAULT NULL,
			`created` datetime DEFAULT NULL,
			`modified` datetime DEFAULT NULL,
			PRIMARY KEY (`id`)
			) ENGINE=InnoDB DEFAULT CHARSET=utf8;
