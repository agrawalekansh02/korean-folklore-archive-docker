<?php

/**
 * Qualified Query: Base class for SELECT, UPDATE, and DELETE queries
 * 
 * A query that may have a WHERE clause
 */

namespace QuB;

abstract class QualifiedQuery {

    private $first_condition = true,
            $group_first_condition = true
            ;

    protected $fields = array(),
            $tables = array(),
            $wheres = array(),
            $param_refs = array(),
            $params = array('types' => '',)
    ;

    protected function add_fields(array $fields) {
        $this->fields = array_merge($this->fields, $fields);
        return $this;
    }


    protected function add_tables(array $tables) {
        $this->tables = array_merge($this->tables, $tables);
        return $this;
    }

    protected function add_conditions(array $conditions) {
        $this->wheres = array_merge($this->wheres, $conditions);
        return $this;
    }

    protected function add_condition($conjunction = "AND", $condition, $param = null, $type = 's') {
        if ($this->first_condition || $this->group_first_condition) $conjunction = '';
        $this->first_condition = false;
        $this->group_first_condition = false;
        $this->wheres[] = "$conjunction $condition";
        if ($param) {
            $this->params['types'] .= $type;
            $this->param_refs[] = $param;
            $this->params[] = &$this->param_refs[sizeof($this->param_refs) - 1];
        }
        return $this;
    }

    public function copy_conditions() {
        return $this->wheres;
    }

    public function __call($name, $params) {
        $name = strtolower($name);
        switch ($name) {
            case 'where':
                $name = '';
            case 'and':
            case 'or':
                return call_user_func_array(
                    array($this, 'add_condition'), 
                    array_merge(array($name,), $params)
                );
            break;
            default:
                throw new \BadMethodCallException("Method $name not found on QualifiedQuery");
        }
    }

    public function open_group($qual = '') {
        $this->wheres[] = "$qual (";
        $this->group_first_condition = true;
        return $this;
    }

    public function close_group() {
        $this->wheres[] = ")";
        $this->group_first_condition = false;
        return $this;
    }

}
