<?php //-->
/*
 * This file is part of the Mysql package of the Eden PHP Library.
 * (c) 2013-2014 Openovate Labs
 *
 * Copyright and license information can be found at LICENSE
 * distributed with this package.
 */

namespace Eden\Mysql;

use Eden\Core\Base as CoreBase;
use Eden\Sql\Select as SqlSelect;

/**
 * Generates subselect query string syntax
 *
 * @vendor Eden
 * @package Mysql
 * @author Christian Blanquera cblanquera@openovate.com
 */
class Subselect extends CoreBase 
{
	protected $parentQuery;
	
	/**
	 * Construct: Set Parent Query and Column
	 *
	 * @param SqlSelect
	 * @param string
	 */
	public function __construct(SqlSelect $parentQuery, $select = '*') 
	{
		//Argument 2 must be a string
		Argument::i()->test(2, 'string');
		
		$this->setParentQuery($parentQuery);
		$this->select = is_array($select) ? implode(', ', $select) : $select;
	}
	
	/**
	 * Returns the string version of the query 
	 *
	 * @param  bool
	 * @return string
	 * @notes returns the query based on the registry
	 */
	public function getQuery() 
	{	
		return '('.substr(parent::getQuery(), 0, -1).')';
	}
	
	/**
	 * Sets the parent Query
	 *
	 * @param object usually the parent query object
	 * @return this
	 */
	public function setParentQuery(SqlSelect $parentQuery) 
	{
		$this->parentQuery = $parentQuery;
		return $this;
	}
}