<?php

namespace FPiBB\Model;

class QueryBuilder extends \Prefab
{
    function createTable($table) {        
        $columns = array();
        foreach($table->COLUMNS as $c) {
            $columns[] = sprintf("`%1\$s` %2\$s %3\$s %4\$s %5\$s", $c->NAME, $this->dbTypeToString($c->TYPE, $c->LENGTH, $c->ENUM),
                $c->PRIMARY ? "PRIMARY KEY" : "", $c->AUTOINCREMENT ? "AUTO_INCREMENT" : "", $c->NOTNULL ? "NOT NULL" : "");
        }
        $uniques = array();
        foreach($table->COLUMNS as $c) {
            if($c->UNIQUE)
                $uniques[] = $c->NAME;
        }
		return sprintf("CREATE TABLE %1\$s (%2\$s %3\$s)", $this->escapeTableName($table->NAME), 
            (sizeof($columns) > 0) ? implode(", ", $columns) : "",
            (sizeof($uniques) > 0) ? sprintf(", UNIQUE(%1\$s)", implode(", ", $uniques)) : "");
    }
    
    function dbTypeToString($type, $length, $enum) {
        $len = isset($length) ? sprintf("(%d)", $length) : null;
        $len = (!isset($length) && $type == \MySqlDbType::VarChar) ? sprintf("(%d)", 255) : $len;
        $len = (!isset($length) && $type == \MySqlDbType::Int32) ? sprintf("(%d)", 11) : $len;
        $len = ($type == \MySqlDbType::Boolean) ? "" : $len;
        $len = ($type == \MySqlDbType::Enum) ? sprintf("('%s')", implode("', '", $enum)) : $len;
        return $type . $len;
    }
        
	function alterTable($from, $to) {
		$rstr = $this->generateRandomString(20);
		$escapedTable = $this->escapeTableName($from->NAME);
		$tmpTable = $this->escapeTableName(sprintf("%1\$s_%2\$s", $rstr, $from->NAME));
		$alter = $this->renameTable($escapedTable, $tmpTable);
		$create = $this->createTable($to);
		// combine all columns in the 'from' variable excluding ones that aren't in the 'to' variable.
		// exclude the ones that aren't in 'to' variable because if the column is deleted, why try to import the data?
		$columns = implode(", ", \FPiBB\Model\SqlTable::getColumnNames(\FPiBB\Model\SqlTable::getColumnsExclude($from, $to)));
		$insert = sprintf("INSERT INTO %1\$s (%2\$s) SELECT %2\$s FROM %3\$s", $escapedTable, $columns, $tmpTable);
		$drop = sprintf("DROP TABLE %1\$s", $tmpTable);
        $use = sprintf("USE %1\$s", $this->escapeTableName(DB_DATABASE));
		return sprintf(
            "%1\$s; 
            %2\$s; 
            %3\$s; 
            %4\$s; 
            %5\$s;", $use, $alter, $create, $insert, $drop);   
    }
    
	function updateValue($table, $values, $wheres) {
        if (sizeof($values) == 0)
			throw new \Exception("No values supplied");
		return sprintf("UPDATE %1\$s SET %2\$s %3\$s", $this->escapeTableName($table), implode(", ", \FPiBB\Model\SqlValue::implodeNameValue($values), $this->buildWhere($wheres)));
    }
    
	function insertValues($table, $values) {
        $names = "";
		$values = "";
		$count = 0;
		foreach ($values as $value) {
            $names .= $value->NAME;
			$values .= $value->VALUE;
			if ($count != sizeof($values) - 1) {
				$names .= ", ";
				$values .= ", ";
			}
			$count++;
		}
		return sprintf("INSERT INTO %1\$s (%2\$s) VALUES (%3\$s)", $this->escapeTableName($table), $names, $values);
    }
    
	function readColumn($table, $wheres) {
        return sprintf("SELECT * FROM %1\$s %2\$s", $this->escapeTableName($table), $this->buildWhere($wheres));
    }
    
	function deleteRow($table, $wheres) {
        return sprintf("DELETE FROM %1\$s %2\$s", $this->escapeTableName($table), $this->buildWhere($wheres));
    }
    
	function renameTable($from, $to) {
        return sprintf("RENAME TABLE %1\$s TO %2\$s",$from, $to);
    }
    
    function getColumns($table) {
        $colums = \Base::instance()->set('result', \Config::instance()->DB->exec(
            sprintf("SELECT `COLUMN_NAME` 
            FROM `INFORMATION_SCHEMA`.`COLUMNS` 
            WHERE `TABLE_SCHEMA`='%1\$s' 
            AND `TABLE_NAME`='%2\$s';", DB_DATABASE, $table)
        ));
        $ret = array();
        foreach($colums as $col)
            $ret[] = new SqlColumn($col['COLUMN_NAME'], \MySqlDbType::Text);
        return $ret;
    }
    
    function checkTableExists($table) {
        $check = \Base::instance()->set('result', \Config::instance()->DB->exec(
            sprintf("SELECT `TABLE_NAME`
            FROM information_schema.tables 
            WHERE `TABLE_SCHEMA`='%1\$s' 
            AND `TABLE_NAME`='%2\$s';", DB_DATABASE, $table)
        ));
        return (sizeof($check) == 1);
    }
    
    private function escapeTableName($table) {
		return sprintf("`%s`", $table);
	}
    
    private function generateRandomString($length = 10) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, strlen($characters) - 1)];
        }
        return $randomString;
    }
    
    private function buildWhere($wheres) {
		if(sizeof($wheres) == 0)
			return "";
		return sprintf("WHERE %s", implode(", ", \FPiBB\Model\SqlValue::implodeNameValue($wheres)));
	}
    
    /**
     * @return QueryBuilder
     */
    static function instance() {
		if (!\Registry::exists($class=get_called_class()))
			\Registry::set($class,new $class);
		return \Registry::get($class);
	}
}