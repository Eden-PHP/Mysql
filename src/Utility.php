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
 * Generates utility query strings
 *
 * @vendor   Eden
 * @package  Mysql
 * @author   Christian Blanquera <cblanquera@openovate.com>
 * @standard PSR-2
 */
class Utility extends \Eden\Sql\Query
{
    /**
     * @var string|null $query The query string
     */
    protected $query = null;
    
    /**
     * Query for dropping a table
     *
     * @param *string $table The name of the table
     *
     * @return Eden\Mysql\Utility
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
     * @param *string $table The name of the table
     * @param *string $name  The new name of the table
     *
     * @return Eden\Mysql\Utility
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
     * @param *string      $table The name of the table
     * @param *string|null $where Filter/s
     *
     * @return Eden\Mysql\Utility
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
     * @param string|null $like The like pattern
     *
     * @return Eden\Mysql\Utility
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
     * @param *string $table The name of the table
     *
     * @return Eden\Mysql\Utility
     */
    public function truncate($table)
    {
        //Argument 1 must be a string
        Argument::i()->test(1, 'string');
        
        $this->query = 'TRUNCATE `' . $table .'`';
        return $this;
    }
}
