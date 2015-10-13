<?php //-->
/**
 * This file is part of the Eden PHP Library.
 * (c) 2014-2016 Openovate Labs
 *
 * Copyright and license information can be found at LICENSE.txt
 * distributed with this package.
 */
 
class Eden_Mysql_Tests_Mysql_ModelTest extends \PHPUnit_Framework_TestCase
{
	public static $database;
	
	public function setUp() {
		date_default_timezone_set('GMT');
		self::$database = eden('mysql', '127.0.0.1', 'eden', 'travis', '');
		
		//Schema
		self::$database->query(
		"CREATE TABLE IF NOT EXISTS `unit_post` (
		  `post_id` INT UNSIGNED NOT NULL AUTO_INCREMENT ,
		  `post_slug` VARCHAR(255) NOT NULL ,
		  `post_title` VARCHAR(255) NULL ,
		  `post_detail` TEXT NOT NULL ,
		  `post_parent` INT UNSIGNED NOT NULL DEFAULT 0 ,
		  `post_left` INT UNSIGNED NOT NULL DEFAULT 0 ,
		  `post_right` INT UNSIGNED NOT NULL DEFAULT 0 ,
		  `post_active` INT(1) UNSIGNED NOT NULL DEFAULT 1 ,
		  `post_type` VARCHAR(255) NOT NULL DEFAULT 'post',
		  `post_status` VARCHAR(255) NOT NULL DEFAULT 'published',
		  `post_visibility` VARCHAR(255) NOT NULL DEFAULT 'public',
		  `post_flag` INT(1) UNSIGNED NOT NULL DEFAULT 0 ,
		  `post_published` DATE NULL ,
		  `post_created` DATETIME NOT NULL ,
		  `post_updated` DATETIME NOT NULL ,
		  PRIMARY KEY (`post_id`) ,
		  UNIQUE INDEX `post_slug` (`post_slug` ASC) ,
		  INDEX `post_title` (`post_title` ASC) ,
		  INDEX `post_parent` (`post_parent` ASC) ,
		  INDEX `post_left` (`post_left` ASC) ,
		  INDEX `post_right` (`post_right` ASC) ,
		  INDEX `post_active` (`post_active` ASC) ,
		  INDEX `post_status` (`post_status` ASC) ,
		  INDEX `post_visibility` (`post_visibility` ASC) ,
		  INDEX `post_published` (`post_published` ASC) ,
		  INDEX `post_updated` (`post_updated` ASC) )
		ENGINE = InnoDB DEFAULT CHARSET=latin1;");
	}
	
	/* FACTORY METHODS */
    public function testAlter() 
    {
		
    }
	
	
	public function testTearDown() {
		eden('mysql', '127.0.0.1', 'eden', 'travis', '')
		->query("DROP TABLE IF EXISTS `unit_post`");
	}
}
