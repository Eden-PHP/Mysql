<?php //-->
/*
 * This file is part of the Mysql package of the Eden PHP Library.
 * (c) 2013-2014 Openovate Labs
 *
 * Copyright and license information can be found at LICENSE
 * distributed with this package.
 */

namespace Eden\Mysql;

/**
 * Generates subselect query string syntax
 *
 * @vendor   Eden
 * @package  mysql
 * @author   Christian Blanquera <cblanquera@openovate.com>
 * @standard PSR-2
 */
class Subselect extends Base
{
    protected $parentQuery;
    
    /**
     * Construct: Set Parent Query and Column
     *
     * @param SqlSelect
     * @param string
     */
    public function __construct(\Eden\Sql\Select $parentQuery, $select = '*')
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
     *
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
     *
     * @return this
     */
    public function setParentQuery(\Eden\Sql\Select $parentQuery)
    {
        $this->parentQuery = $parentQuery;
        return $this;
    }
}
