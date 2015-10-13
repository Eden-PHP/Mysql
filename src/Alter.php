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
 * Generates alter query string syntax
 *
 * @vendor   Eden
 * @package  Mysql
 * @author   Christian Blanquera <cblanquera@openovate.com>
 * @standard PSR-2
 */
class Alter extends \Eden\Sql\Query
{
    /**
     * @var string|null $name Name of table
     */
    protected $name = null;

    /**
     * @var array $changeFields List of fields to change
     */
    protected $changeFields = array();

    /**
     * @var array $addFields List of fields to add
     */
    protected $addFields = array();

    /**
     * @var array $removeFields List of fields to remove
     */
    protected $removeFields = array();

    /**
     * @var array $addKeys List of keys to add
     */
    protected $addKeys = array();

    /**
     * @var array $removeKeys List of keys to remove
     */
    protected $removeKeys = array();

    /**
     * @var array $addUniqueKeys List of unique keys to add
     */
    protected $addUniqueKeys = array();

    /**
     * @var array $removeUniqueKeys List of unique keys to remove
     */
    protected $removeUniqueKeys = array();

    /**
     * @var array $addPrimaryKeys List of primary keys to add
     */
    protected $addPrimaryKeys = array();

    /**
     * @var array $removePrimaryKeys List of primary keys to remove
     */
    protected $removePrimaryKeys = array();
    
    /**
     * Construct: Set the table, if any
     *
     * @param string|null $name Table name
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
     * @return Eden\Mysql\Alter
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
     * @param *string $name Name of key
     *
     * @return Eden\Mysql\Alter
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
     * @param *string $name Name of key
     *
     * @return Eden\Mysql\Alter
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
     * @param *string $name Name of key
     *
     * @return Eden\Mysql\Alter
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
     * @param *string $name       Column name
     * @param *array  $attributes Column attributes
     *
     * @return Eden\Mysql\Alter
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
     * @param bool $unbind Whether to unbind variables
     *
     * @return string
     */
    public function getQuery($unbind = false)
    {
        $fields = array();
        $table = '`'.$this->name.'`';
        
        foreach ($this->removeFields as $name) {
            $fields[] = 'DROP `'.$name.'`';
        }
        
        foreach ($this->addFields as $name => $attr) {
            $field = array('ADD `'.$name.'`');
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
        
        foreach ($this->changeFields as $name => $attr) {
            $field = array('CHANGE `'.$name.'`  `'.$name.'`');
            
            if (isset($attr['name'])) {
                $field = array('CHANGE `'.$name.'`  `'.$attr['name'].'`');
            }
            
            if (isset($attr['type'])) {
                $field[] = isset($attr['length']) ? $attr['type'] . '('.$attr['length'].')' : $attr['type'];
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
        
        foreach ($this->removeKeys as $key) {
            $fields[] = 'DROP INDEX `'.$key.'`';
        }
        
        if (!empty($this->addKeys)) {
            $fields[] = 'ADD INDEX ('.implode(', ', $this->addKeys).')';
        }
        
        foreach ($this->removeUniqueKeys as $key) {
            $fields[] = 'DROP INDEX `'.$key.'`';
        }
        
        if (!empty($this->addUniqueKeys)) {
            $fields[] = 'ADD UNIQUE ('.implode(', ', $this->addUniqueKeys).')';
        }
        
        foreach ($this->removePrimaryKeys as $key) {
            $fields[] = 'DROP PRIMARY KEY `'.$key.'`';
        }
        
        if (!empty($this->addPrimaryKeys)) {
            $fields[] = 'ADD PRIMARY KEY ('.implode(', ', $this->addPrimaryKeys).')';
        }
        
        $fields = implode(", \n", $fields);
        
        return sprintf(
            'ALTER TABLE %s %s;',
            $table,
            $fields
        );
    }
    
    /**
     * Removes a field
     *
     * @param *string $name Column name
     *
     * @return Eden\Mysql\Alter
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
     * @param *string $name Name of key
     *
     * @return Eden\Mysql\Alter
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
     * @param *string $name Name of key
     *
     * @return Eden\Mysql\Alter
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
     * @param *string $name Name of key
     *
     * @return Eden\Mysql\Alter
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
     * @param *string $name Table name
     *
     * @return Eden\Mysql\Alter
     */
    public function setName($name)
    {
        //Argument 1 must be a string
        Argument::i()->test(1, 'string');
        
        $this->name = $name;
        return $this;
    }
}
