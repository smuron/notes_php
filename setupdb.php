<?php
	// DEBUG FILE

	// in a real app, this wouldn't face public

	// file for testing including the db class file

	include './db.php';

	$db = new Db();

	$res = $db->query("SELECT * FROM Note");

	while ($obj = $res->fetch_array()) {
	        print($obj['title']."<br/>");
	    }

	    /* for the sake of example
	    obviously this would be internal in a real app

		CREATE TABLE `Note` (
		  `id` int(11) NOT NULL AUTO_INCREMENT,
		  `title` varchar(255) DEFAULT NULL,
		  `ownerName` varchar(255) DEFAULT NULL,
		  `contents` text,
		  `reminder` timestamp NULL DEFAULT NULL,
		  `created` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
		  `updated` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
		  PRIMARY KEY (`id`)
		) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;


		INSERT INTO Note (title, ownerName, contents) VALUES ('debug2', 'Default', '123asbc');
	    */
?>