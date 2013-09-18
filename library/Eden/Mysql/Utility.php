<?php //-->
/*
 * This file is part of the Mysql package of the Eden PHP Library.
 * (c) 2013-2014 Openovate Labs
 *
 * Copyright and license information can be found at LICENSE
 * distributed with this package.
 */

namespace Eden\Mysql;

use Eden\Sql\Query as SqlQuery;

/**
 * Generates utility query strings
 *
 * @vendor Eden
 * @package Mysql
 * @author Christian Blanquera cblanquera@openovate.com
 */
class Utility extends SqlQuery
{
	protected $_query = NULL;
	
	/**
	 * Query for dropping a table
	 *
	 * @param string the name of the table
	 * @return this
	 */
	public function dropTable($table) 
	{
		//Argument 1 must be a string
		Argument::i()->test(1, 'string');
		
		$this->query = 'DROP TABLE `' . $table .'`';
		return $this;
	}
	
	/**
	 * Returns the string version of the query 
	 *
	 * @return string
	 */
	public function getQuery() 
	{
		return $this->query.';';
	}
	
	/**
	 * Query for renaming a table
	 *
	 * @param string the name of the table
	 * @param string the new name of the table
	 * @return this
	 */
	public function renameTable($table, $name) 
	{
		//Argument 1 must be a string, 2 must be string
		Argument::i()->test(1, 'string')->argument(2, 'string');
		
		$this->query = 'RENAME TABLE `' . $table . '` TO `' . $name . '`';
		return $this;
	}
	
	/**
	 * Query for showing all columns of a table
	 *
	 * @param string the name of the table
	 * @return this
	 */
	public function showColumns($table, $where = null) 
	{
		//Argument 1 must be a string, 2 must be string null
		Argument::i()->test(1, 'string')->test(2, 'string', 'null');
		
		$where = $where ? ' WHERE '.$where : null;
		$this->query = 'SHOW FULL COLUMNS FROM `' . $table .'`' . $where;
		return $this;
	}
	
	/**
	 * Query for showing all tables
	 *
	 * @param string like
	 * @return this
	 */
	public function showTables($like = null) 
	{
		Argument::i()->test(1, 'string', 'null');
		
		$like = $like ? ' LIKE '.$like : null;
		$this->query = 'SHOW TABLES'.$like;
		return $this;
	}
	
	/**
	 * Query for truncating a table
	 *
	 * @param string the name of the table
	 * @return this
	 */
	public function truncate($table) 
	{
		//Argument 1 must be a string
		Argument::i()->test(1, 'string');
		
		$this->query = 'TRUNCATE `' . $table .'`';
		return $this;
	}
}