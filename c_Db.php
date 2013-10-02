<?php

class DB {

    public static $link;

    public static function isExist($table, $column, $data, $limit = 1){
        $sql = "SELECT * FROM $table WHERE $column = '$data' LIMIT 1";
        $result=mysqli_query(DB::$link, $sql)or super_die(array(mysql_error(), $sql, __METHOD__));
        $row=mysqli_fetch_row($result);
        return $row[0];
    }

    public static function getMinEmptyId($table_name, $column_name) {
        $recos = DB::getTable($table_name, $column = array($column_name));
        $nums = array();
        if(!$recos[0]) return 0;
        foreach($recos as $r)
            $nums[] = $r[$column_name];
        sort($nums);
        $i = 1;
        while(!empty($nums[$i]) && $nums[$i] == $i)$i++;
        return $i;
    }

    public static function insert($table_name, $parameter) {
        $sql_head = "INSERT INTO `{$table_name}`(";
        $sql_foot = "VALUES(";
        foreach ($parameter as $key => $value ) {
            $sql_head .= sprintf("`%s`, ", is_sql($key));
            if($value === 'NOW()')
                $sql_foot .= "NOW(), ";
            else
                $sql_foot .= sprintf("'%s', ", is_sql($value));
        }
        $sql_head = substr($sql_head, 0, -2) . ")";
        $sql_foot = substr($sql_foot, 0, -2) . ")";
        $sql = $sql_head.$sql_foot;
        return $result = mysqli_query(DB::$link, $sql) or super_die(array(mysql_error(), $sql, __METHOD__));
    }

    public static function update($table_name, $parameter, $condition = null, $limit = null) {
        $sql = "UPDATE `denpa_knot_lecture` SET";
        foreach($parameter as $key => $value) {
            $sql .= sprintf(" `%s` = '%s',", is_sql($key), is_sql($value));
        }
        $sql = substr($sql, 0, -1);
        if(!empty($condition)) {
            $sql .= " WHERE";
            foreach($condition as $key => $value) {
                $sql .= sprintf(" `%s` = '%s' AND", is_sql($key), is_sql($value));
            }
            $sql = substr($sql, 0, -3);
        }
        if(!empty($limit)) $sql .= "LIMIT $limit";
        return $result = mysqli_query(DB::$link, $sql)or super_die(array(mysql_error(), $sql, __METHOD__));
    }

    public static function getData($table_name, $column_name, $condition) {
        $rec = DB::getTable($table_name, array($column_name), $condition, 1) ;
        return $rec[0][$column_name];
    }



    public static function query($query, $table_name, $column, $condition, $limit) {
    }


    public static function getTable($table_name, $column = null, $condition = null, $limit = null) {
        $sql = 'SELECT';
        if(!empty($column)) {
            foreach($column as $value) {
                $sql .= sprintf(" %s,", $value);
            }
            $sql = substr($sql, 0, -1);
        } else $sql .= " *";
        $sql .= " FROM `$table_name`";
        if(!empty($condition)) {
            $sql .= " WHERE";
            foreach($condition as $key => $value) {
                $sql .= sprintf(" `%s` = '%s' AND", is_sql($key), is_sql($value));
            }
            $sql = substr($sql, 0, -3);
        }
        if(!empty($limit)) $sql .= "LIMIT $limit";

        if(isset($_GET['sql'])) echo $sql.PHP_EOL;
        $result = mysqli_query(DB::$link, $sql)or super_die(array(mysql_error(), $sql, __METHOD__, $query, $result));
        $datas = array();
        //        while($rec = mysqli_fetch_assoc($result)) {
        while($rec = mysqli_fetch_assoc($result)) {
            $datas[] = $rec;
        }
        return $datas;
    }

    public static function delete($table_name, $condition, $limit) {
        if(empty($condition)) return false;                    //念のため
        $sql = 'DELETE FROM';
        $sql .= " FROM `$table_name`";
        $sql .= " WHERE";
        foreach($condition as $key => $value) {
            $sql .= sprintf(" `%s` = '%s' AND", is_sql($key), is_sql($value));
        }
        $sql = substr($sql, 0, -3);
        if(!empty($limit)) $sql .= "LIMIT $limit";
        return $result = mysqli_query(DB::$link, $sql)or super_die(array(mysql_error(), $sql, __METHOD__, $query, $result));
    }

    public static function getRow($table_name, $condition = null, $limit = null) {
        return DB::getTable($table_name, null, $condition, $limit);
    }
}

?>