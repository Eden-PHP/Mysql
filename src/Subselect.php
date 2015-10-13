<?php //-->
/**
 * This file is part of the Eden PHP Library.
 * (c) 2014-2016 Openovate Labs
 *
 * Copyright and license information can be found at LICENSE.txt
 * distributed with this package.
 */

namespace Eden\Mysql;

/**
 * Generates subselect query string syntax
 *
 * @vendor   Eden
 * @package  Mysql
 * @author   Christian Blanquera <cblanquera@openovate.com>
 * @standard PSR-2
 */
class Subselect extends Base
{
    protected $parentQuery;
    
    /**
     * Construct: Set Parent Query and Column
     *
     * @param SqlSelect $parentQuery Main select query
     * @param string    $select      List of columns
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
     * @return string
     */
    public function getQuery()
    {
        return '('.substr(parent::getQuery(), 0, -1).')';
    }
    
    /**
     * Sets the parent Query
     *
     * @param $parentQuery Main select query
     *
     * @return Eden\Mysql\Subselect
     */
    public function setParentQuery(\Eden\Sql\Select $parentQuery)
    {
        $this->parentQuery = $parentQuery;
        return $this;
    }
}
