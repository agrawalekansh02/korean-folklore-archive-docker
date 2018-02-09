<?php

/**
 * SelectQuery: Represents a SELECT Query
 */

namespace QuB;
class SelectQuery extends QualifiedQuery {

    protected $group_by = array()
        ;
    
    protected $limit = array()
        ;

    public function __construct($fields) {
        $this->add_fields($fields);
    }

    public function from() {
        $this->add_tables(func_get_args());
        return $this;
    }

    public function group_by() {
        $this->group_by = array_merge($this->group_by, func_get_args());
        return $this;
    }

    public function limit() {
        $this->limit = array_merge($this->limit, func_get_args());
        return $this;
    }

    public function __toString() {
        $query = "SELECT ".implode(', ', $this->fields)
                ." FROM ".implode(', ', $this->tables);
        if ($this->wheres) {
            $query .= " WHERE".implode(' ', $this->wheres);
        }
        if ($this->group_by) {
            $query .= " GROUP BY ".implode(', ', $this->group_by);
        }
        if ($this->limit) {
            $query .= " LIMIT ".implode(', ', $this->limit);
        }
        return $query;
    }

    public function params() {
        return $this->params;
    }
}
