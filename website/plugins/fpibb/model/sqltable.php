<?php

namespace FPiBB\Model;

class SqlTable
{
    public
    $COLUMNS,
    $NAME;
    
    function __construct($name, $columns) {
        $this->NAME = $name;
        $this->COLUMNS = is_array($columns) ? $columns : array();
    }
    
    function ensureExists() {
        if(QueryBuilder::instance()->checkTableExists($this->NAME)) {
            $columns = QueryBuilder::instance()->getColumns($this->NAME);
            $diff = false;
            foreach($this->COLUMNS as $c1) {
                $exists = false;
                foreach($columns as $c2) {
                    if($c1->NAME == $c2->NAME) {
                        $exists = true;
                        break;
                    }
                }
                if(!$exists) {
                    $diff = true;
                    break;
                }
            }
            foreach($columns as $c1) {
                $exists = false;
                foreach($this->COLUMNS as $c2) {
                    if($c1->NAME == $c2->NAME) {
                        $exists = true;
                        break;
                    }
                }
                if(!$exists) {
                    $diff = true;
                    break;
                }
            }
            if(!$diff) {
                return;
            }            
            $from = new SqlTable($this->NAME, $columns);
            $db = \Config::instance()->DB;
            
            foreach(explode(";", QueryBuilder::instance()->alterTable($from, $this)) as $qry)
                $db->query($qry);
        }
        else {
            \Config::instance()->DB->query(QueryBuilder::instance()->createTable($this));
        }
    }
    
    static function getColumnsExclude(SqlTable $include, SqlTable $exclude) {
        $ret = array();        
        foreach($include->COLUMNS as $c1) {
            $inBoth = false;
            foreach($exclude->COLUMNS as $c2) {
                if($c1->NAME == $c2->NAME) {
                    $inBoth = true;
                    break;
                }
            }
            if($inBoth) {
                $ret[] = $c1;
            }
        }
        return $ret;
    }
    
    static function getColumnNames($columns) {
        $ret = array();
        foreach($columns as $c) {
            $ret[] = $c->NAME;
        }        
        return $ret;
    }
}
