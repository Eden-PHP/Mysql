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
 * Generates create table query string syntax
 *
 * @vendor   Eden
 * @package  Mysql
 * @author   Christian Blanquera <cblanquera@openovate.com>
 * @standard PSR-2
 */
class Create extends \Eden\Sql\Query
{
    /**
     * @var string|null $name Name of table
     */
    protected $name = null;

    /**
     * @var string|null $comments Table comments
     */
    protected $comments = null;

    /**
     * @var array $fields List of fields
     */
    protected $fields = array();

    /**
     * @var array $keys List of key indexes
     */
    protected $keys = array();

    /**
     * @var array $uniqueKeys List of unique keys
     */
    protected $uniqueKeys = array();

    /**
     * @var array $primaryKeys List of primary keys
     */
    protected $primaryKeys = array();
    
    /**
     * Construct: Set the table, if any
     *
     * @param string|null $name Name of table
     */
    public function __construct($name = null)
    {
        if (is_string($name)) {
            $this->setName($name);
        }
    }
    
    /**
     * Adds a field in the table
     *
     * @param *string $name       Column name
     * @param *array  $attributes Column attributes
     *
     * @return Eden\Mysql\Create
     */
    public function addField($name, array $attributes)
    {
        //Argument 1 must be a string
        Argument::i()->test(1, 'string');
        
        $this->fields[$name] = $attributes;
        return $this;
    }
    
    /**
     * Adds an index key
     *
     * @param *string $name   Name of key
     * @param *array  $fields List of key fields
     *
     * @return Eden\Mysql\Create
     */
    public function addKey($name, array $fields)
    {
        //Argument 1 must be a string
        Argument::i()->test(1, 'string');
        
        $this->keys[$name] = $fields;
        return $this;
    }
    
    /**
     * Adds a primary key
     *
     * @param *string $name Name of key
     *
     * @return Eden\Mysql\Create
     */
    public function addPrimaryKey($name)
    {
        //Argument 1 must be a string
        Argument::i()->test(1, 'string');
        
        $this->primaryKeys[] = $name;
        return $this;
    }
    
    /**
     * Adds a unique key
     *
     * @param *string $name   Name of key
     * @param *array  $fields List of key fields
     *
     * @return Eden\Mysql\Create
     */
    public function addUniqueKey($name, array $fields)
    {
        //Argument 1 must be a string
        Argument::i()->test(1, 'string');
        
        $this->uniqueKeys[$name] = $fields;
        return $this;
    }
    
    /**
     * Returns the string version of the query
     *
     * @param bool $unbind Whether to unbind variables
     *
     * @return string
     */
    public function getQuery($unbind = false)
    {
        $table = '`'.$this->name.'`';
        
        $fields = array();
        foreach ($this->fields as $name => $attr) {
            $field = array('`'.$name.'`');
            if (isset($attr['type'])) {
                $field[] = isset($attr['length']) ?
                    $attr['type'] . '('.$attr['length'].')' :
                    $attr['type'];
            }
            
            if (isset($attr['attribute'])) {
                $field[] = $attr['attribute'];
            }
            
            if (isset($attr['null'])) {
                if ($attr['null'] == false) {
                    $field[] = 'NOT NULL';
                } else {
                    $field[] = 'DEFAULT NULL';
                }
            }
            
            if (isset($attr['default'])&& $attr['default'] !== false) {
                if (!isset($attr['null']) || $attr['null'] == false) {
                    if (is_string($attr['default'])) {
                        $field[] = 'DEFAULT \''.$attr['default'] . '\'';
                    } else if (is_numeric($attr['default'])) {
                        $field[] = 'DEFAULT '.$attr['default'];
                    }
                }
            }
            
            if (isset($attr['auto_increment']) && $attr['auto_increment'] == true) {
                $field[] = 'auto_increment';
            }
            
            $fields[] = implode(' ', $field);
        }
        
        $fields = !empty($fields) ? implode(', ', $fields) : '';
        
        $primary = !empty($this->primaryKeys) ?
            ', PRIMARY KEY (`'.implode('`, `', $this->primaryKeys).'`)' :
            '';
        
        $uniques = array();
        foreach ($this->uniqueKeys as $key => $value) {
            $uniques[] = 'UNIQUE KEY `'. $key .'` (`'.implode('`, `', $value).'`)';
        }
        
        $uniques = !empty($uniques) ? ', ' . implode(", \n", $uniques) : '';
        
        $keys = array();
        foreach ($this->keys as $key => $value) {
            $keys[] = 'KEY `'. $key .'` (`'.implode('`, `', $value).'`)';
        }
        
        $keys = !empty($keys) ? ', ' . implode(", \n", $keys) : '';
        
        return sprintf(
            'CREATE TABLE %s (%s%s%s%s)',
            $table,
            $fields,
            $primary,
            $unique,
            $keys
        );
    }
    
    /**
     * Sets comments
     *
     * @param *string $comments Table comments
     *
     * @return Eden\Mysql\Create
     */
    public function setComments($comments)
    {
        //Argument 1 must be a string
        Argument::i()->test(1, 'string');
        
        $this->comments = $comments;
        return $this;
    }
    
    /**
     * Sets a list of fields to the table
     *
     * @param *array $fields List of fields
     *
     * @return Eden\Mysql\Create
     */
    public function setFields(array $fields)
    {
        $this->fields = $fields;
        return $this;
    }
    
    /**
     * Sets a list of keys to the table
     *
     * @param *array $keys List of keys
     *
     * @return Eden\Mysql\Create
     */
    public function setKeys(array $keys)
    {
        $this->keys = $keys;
        return $this;
    }
    
    /**
     * Sets the name of the table you wish to create
     *
     * @param *string $name Table name
     *
     * @return Eden\Mysql\Create
     */
    public function setName($name)
    {
        //Argument 1 must be a string
        Argument::i()->test(1, 'string');
        
        $this->name = $name;
        return $this;
    }
    
    /**
     * Sets a list of primary keys to the table
     *
     * @param *array $primaryKeys List of primary keys
     *
     * @return Eden\Mysql\Create
     */
    public function setPrimaryKeys(array $primaryKeys)
    {
        $this->primaryKeys = $primaryKeys;
        return $this;
    }
    
    /**
     * Sets a list of unique keys to the table
     *
     * @param *array $uniqueKeys List of unique keys
     *
     * @return Eden\Mysql\Create
     */
    public function setUniqueKeys(array $uniqueKeys)
    {
        $this->uniqueKeys = $uniqueKeys;
        return $this;
    }
}
