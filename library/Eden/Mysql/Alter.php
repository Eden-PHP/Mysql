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
 * Generates alter query string syntax
 *
 * @vendor Eden
 * @package Mysql
 * @author Christian Blanquera cblanquera@openovate.com
 */
class Alter extends SqlQuery 
{
	protected $name = null;
	protected $changeFields = array();
	protected $addFields = array();
	protected $removeFields = array();
	protected $addKeys = array();
	protected $removeKeys = array();
	protected $addUniqueKeys = array();
	protected $removeUniqueKeys = array();
	protected $addPrimaryKeys = array();
	protected $removePrimaryKeys = array();
	
	/**
	 * Construct: Set the table, if any
	 *
	 * @param string|null
	 */
	public function __construct($name = null) 
	{
		if(is_string($name)) {
			$this->setName($name);
		}
	}
	
	/**
	 * Adds a field in the table
	 *
	 * @param string name
	 * @param array attributes
	 * @return this
	 */
	public function addField($name, array $attributes) 
	{
		//Argument 1 must be a string
		Argument::i()->test(1, 'string');
		
		$this->addFields[$name] = $attributes;
		return $this;
	}
	
	/**
	 * Adds an index key
	 *
	 * @param string name
	 * @return this
	 */
	public function addKey($name) 
	{
		//Argument 1 must be a string
		Argument::i()->test(1, 'string');
		
		$this->addKeys[] = '`'.$name.'`';
		return $this;
	}
	
	/**
	 * Adds a primary key
	 *
	 * @param string name
	 * @return this
	 */
	public function addPrimaryKey($name) 
	{
		//Argument 1 must be a string
		Argument::i()->test(1, 'string');
		
		$this->addPrimaryKeys[] = '`'.$name.'`';
		return $this;
	}
	
	/**
	 * Adds a unique key
	 *
	 * @param string name
	 * @return this
	 */
	public function addUniqueKey($name) 
	{
		//Argument 1 must be a string
		Argument::i()->test(1, 'string');
		
		$this->addUniqueKeys[] = '`'.$name.'`';
		return $this;
	}
	
	/**
	 * Changes attributes of the table given 
	 * the field name
	 *
	 * @param string name
	 * @param array attributes
	 * @return this
	 */
	public function changeField($name, array $attributes) 
	{
		//Argument 1 must be a string
		Argument::i()->test(1, 'string');
		
		$this->changeFields[$name] = $attributes;
		return $this;
	}
	
	/**
	 * Returns the string version of the query 
	 *
	 * @param  bool
	 * @return string
	 * @notes returns the query based on the registry
	 */
	public function getQuery($unbind = false) 
	{	
		$fields = array();
		$table = '`'.$this->name.'`';
		
		foreach($this->removeFields as $name) {
			$fields[] = 'DROP `'.$name.'`';
		}
		
		foreach($this->addFields as $name => $attr) {
			$field = array('ADD `'.$name.'`');
			if(isset($attr['type'])) {	
				$field[] = isset($attr['length']) ? 
					$attr['type'] . '('.$attr['length'].')' : 
					$attr['type'];
			}
			
			if(isset($attr['attribute'])) {
				$field[] = $attr['attribute'];
			}
			
			if(isset($attr['null'])) {
				if($attr['null'] == false) {
					$field[] = 'NOT NULL';
				} else {
					$field[] = 'DEFAULT NULL';
				}
			}
			
			if(isset($attr['default'])&& $attr['default'] !== false) {
				if(!isset($attr['null']) || $attr['null'] == false) {
					if(is_string($attr['default'])) {
						$field[] = 'DEFAULT \''.$attr['default'] . '\'';
					} else if(is_numeric($attr['default'])) {
						$field[] = 'DEFAULT '.$attr['default'];
					}
				}
			}
			
			if(isset($attr['auto_increment']) && $attr['auto_increment'] == true) {
				$field[] = 'auto_increment';
			}
			
			$fields[] = implode(' ', $field);
		}
		
		foreach($this->changeFields as $name => $attr) {
			$field = array('CHANGE `'.$name.'`  `'.$name.'`');
			
			if(isset($attr['name'])) {	
				$field = array('CHANGE `'.$name.'`  `'.$attr['name'].'`');
			}
			
			if(isset($attr['type'])) {	
				$field[] = isset($attr['length']) ? $attr['type'] . '('.$attr['length'].')' : $attr['type'];
			}
			
			if(isset($attr['attribute'])) {
				$field[] = $attr['attribute'];
			}
			
			if(isset($attr['null'])) {
				if($attr['null'] == false) {
					$field[] = 'NOT NULL';
				} else {
					$field[] = 'DEFAULT NULL';
				}
			}
			
			if(isset($attr['default'])&& $attr['default'] !== false) {
				if(!isset($attr['null']) || $attr['null'] == false) {
					if(is_string($attr['default'])) {
						$field[] = 'DEFAULT \''.$attr['default'] . '\'';
					} else if(is_numeric($attr['default'])) {
						$field[] = 'DEFAULT '.$attr['default'];
					}
				}
			}
			
			if(isset($attr['auto_increment']) && $attr['auto_increment'] == true) {
				$field[] = 'auto_increment';
			}
			
			$fields[] = implode(' ', $field);
		}
		
		foreach($this->removeKeys as $key) {
			$fields[] = 'DROP INDEX `'.$key.'`';
		}
		
		if(!empty($this->addKeys)) {
			$fields[] = 'ADD INDEX ('.implode(', ', $this->addKeys).')';
		}
		
		foreach($this->removeUniqueKeys as $key) {
			$fields[] = 'DROP INDEX `'.$key.'`';
		}
		
		if(!empty($this->addUniqueKeys)) {
			$fields[] = 'ADD UNIQUE ('.implode(', ', $this->addUniqueKeys).')';
		}
		
		foreach($this->removePrimaryKeys as $key) {
			$fields[] = 'DROP PRIMARY KEY `'.$key.'`';
		}
		
		if(!empty($this->addPrimaryKeys)) {
			$fields[] = 'ADD PRIMARY KEY ('.implode(', ', $this->addPrimaryKeys).')';
		}
		
		$fields = implode(", \n", $fields);
		
		return sprintf(
			'ALTER TABLE %s %s;',
			$table, $fields);
	}
	
	/**
	 * Removes a field
	 *
	 * @param string name
	 * @return this
	 */
	public function removeField($name) 
	{
		//Argument 1 must be a string
		Argument::i()->test(1, 'string');
		
		$this->removeFields[] = $name;
		return $this;
	}
	
	/**
	 * Removes an index key
	 *
	 * @param string name
	 * @return this
	 */
	public function removeKey($name) 
	{
		//Argument 1 must be a string
		Argument::i()->test(1, 'string');
		
		$this->removeKeys[] = $name;
		return $this;
	}
	
	/**
	 * Removes a primary key
	 *
	 * @param string name
	 * @return this
	 */
	public function removePrimaryKey($name) 
	{
		//Argument 1 must be a string
		Argument::i()->test(1, 'string');
		
		$this->removePrimaryKeys[] = $name;
		return $this;
	}
	
	/**
	 * Removes a unique key
	 *
	 * @param string name
	 * @return this
	 */
	public function removeUniqueKey($name) 
	{
		//Argument 1 must be a string
		Argument::i()->test(1, 'string');
		
		$this->removeUniqueKeys[] = $name;
		return $this;
	}
	
	/**
	 * Sets the name of the table you wish to create
	 *
	 * @param string name
	 * @return this
	 */
	public function setName($name) 
	{
		//Argument 1 must be a string
		Argument::i()->test(1, 'string');
		
		$this->name = $name;
		return $this;
	}
}