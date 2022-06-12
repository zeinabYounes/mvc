<?php
namespace Core;

use \PDO;
use \Exception;
use Core\{Config, H};

class DB {
  protected $_dbh, $_results, $_lastInsertId, $_rowCount = 0;
  protected $_fetchType = PDO::FETCH_OBJ, $_class, $_error = false;
  protected $_stmt ;
  public  $_sql = null,$_bind = null,$_result_type =1;
  protected static $_db;

  public function __construct($json = null) {
    $host = Config::get('db_host');
    $name = Config::get('db_name');
    $user = Config::get('db_user');
    $pass = Config::get('db_password');
    $options = [
      PDO::ATTR_EMULATE_PREPARES => false,
      PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
      PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ
    ];
    try{
      $this->_dbh = new PDO("mysql:host={$host};dbname={$name}", $user, $pass, $options);
    } catch (Exception $e) {
      if($json !==null){
        echo json_encode([
          'status'=>false,
          'errorNum'=>500,
          'msg'=>$e->getMessage()
        ]);
        die();
      }
      throw new Exception($e->getMessage());
    }
  }

  public static function getInstance($json = null){
    if(!self::$_db ){
      self::$_db = new self($json);
    }
    return self::$_db;
  }

  public function execute($sql, $bind=[]){
    $this->_results = null;
    $this->_lastInsertId = null;
    $this->_error = false;
    $this->_stmt = $this->_dbh->prepare($sql);
  //  dd($this);
    if(!$this->_stmt->execute($bind)) {
      $this->_error = true;
    } else {
      $this->_lastInsertId = $this->_dbh->lastInsertId();
    }
    //dd($this);
    return $this;
  }

  public function query($sql, $bind=[]) {
    $this->execute($sql, $bind);
    if(!$this->_error) {
      $this->_rowCount = $this->_stmt->rowCount();
      if($this->_fetchType === PDO::FETCH_CLASS) {
        if($this->_result_type == 1){
          $this->_results = $this->_stmt->fetchAll($this->_fetchType, $this->_class);
        }
        else{
          $this->_results = $this->_stmt->fetchObject($this->_class);
        }
      } else {
        if($this->_result_type ==1){
          $this->_results = $this->_stmt->fetchAll($this->_fetchType);
        }
        else{
          $this->_results = $this->_stmt->fetchObject();
        }
      }
    }
    return $this;
  }
//INSERT INTO table_namَ('column1','column2','column3') VALUES (:column1,:column2,:column3)
  public function insert($table, $values) {
    $this->_sql = null;
    $this->_bind = null;
    $fields = [];
    $binds = [];
    foreach($values as $key => $value) {
      $fields[] =$key;//$this->_dbh->quote($key);//'"'.$key.'"';
      $binds[] = ":{$key}";
    }
    $fieldStr = implode(',', $fields);
    $bindStr = implode(', ', $binds);
    $this->_sql = "INSERT INTO {$table} ({$fieldStr}) VALUES ({$bindStr})";
    $this->_bind = $values;
    $this->execute($this->_sql , $this->_bind);
    if($this->_error===false){
      return $this->_lastInsertId;
    }
    return !$this->_error;
  }
// UPDATE table_name SET "field1 = :field1 ,field2 = :field2,field3 = :field3 WHERE option1 = :option1 AND option1 = :option1 ";
  public function update($table, $values, $id) {
    $this->_sql = null;
    $this->_bind = null;
    foreach($values as $key => $value) {
      $fields[] =$key ."= :{$key}";
    }
    $fieldStr = implode(',', $fields);
    $this->_sql = "UPDATE  {$table} SET {$fieldStr} WHERE {$id[0]} = :{$id[0]} ";
    $values = array_merge($values,[$id[0]=>$id[1]]);
    $this->_bind = $values;
    $this->execute($this->_sql , $this->_bind);
    return $this;
  }
  //////////////////////////////////////////////////////////////////////////////////////////////////
  public function delete($table,$id){
    $this->_sql = null;
    $this->_bind = null;
   $this->_sql = " DELETE FROM {$table} WHERE {$id[0]} = :{$id[0]} ";
   $values = [$id[0]=>$id[1]];
   $this->_bind = $values;
   $this->execute($this->_sql , $this->_bind);
   return !$this->_error;
  }
  //////////////////////////////////////////////////////////////////////////////////////////////////
  public function deleteAll($table){
    $this->_sql = null;
    $this->_bind = null;
    $this->_sql = "DELETE FROM {$table} ";
    $this->_bind = $values;
    $this->execute($this->_sql , $this->_bind);
    return $this;
  }
  //////////////////////////////////////////////////////////////////////////////////////////////////
  public function select($table,$columns=['*']){
    $this->_sql = null;
    $this->_bind = null;
    $columstr = implode(',', $columns);
    $this->_sql = " SELECT {$columstr} FROM {$table} WHERE 1=1 ";
    $this->query($this->_sql , $this->_bind);
    return $this;
  }
  //////////////////////////////////////////////////////////////////////////////////////////////////
  public function selectFirst($table,$columns=["*"]){
    $this->_sql = null;
    $this->_bind = null;
    $columstr = implode(',', $columns);
    $this->_sql = " SELECT {$columstr} FROM {$table} WHERE 1=1 ";
    $this->_result_type = 0;
    $this->query($this->_sql , $this->_bind);
    return $this;
  }
  //////////////////////////////////////////////////////////////////////////////////////////////////
  public function join($baseTable,$othertable,$condtions,$columns){
    $this->_sql = null;
    $this->_bind = null;
    $columstr = implode(',', $columns);
    $this->_sql = "SELECT {$columstr} FROM {$baseTable} INNER JOIN {$othertable} ON 1=1 $condtions[0]";
    $this->_bind = $conditions[1];
    $this->query($this->_sql , $this->_bind);
    return $this;
  }
  //////////////////////////////////////////////////////////////////////////////////////////////////
  public function leftJoin($baseTable,$othertable,$condtions,$columns=[]){
    $this->_sql = null;
    $this->_bind = null;
    $columstr = implode(',', $columns);
    $this->_sql = "SELECT {$columstr} FROM {$baseTable} LEFT JOIN {$othertable} ON 1=1 $condtions[0]";
    $this->_bind = $conditions[1];
    $this->query($this->_sql , $this->_bind);
    return $this;
  }
  //////////////////////////////////////////////////////////////////////////////////////////////////
  //['user_id'=>['=',5,"AND"]]
  //////////////////////////////////////////////////////////////////////////////////////////////////
  public function makeCodition($conditions){
    $conds=" ";
    foreach ($conditions as $key => $value) {
      $cond = " {$value[2]} {$key} = : {$key} ";
      $conds .=$cond;
      $bind[$key]=$value[1];
    }
    return[$conds,$bind];
  }
  //////////////////////////////////////////////////////////////////////////////////////////////////
  public function max($column,$table,$conditions=[]){
    if($conditions == [])
    {
      $conditions[0] = null;
      $conditions[1] = null;
    }
    $this->_sql = "SELECT MAX({$column}) FROM {$table} WHERE 1=1 $conditions[0]";
    $this->_bind = $conditions[1];
    $this->query($this->_sql , $this->_bind);
    return $this;
  }
  //////////////////////////////////////////////////////////////////////////////////////////////////
  public function min($column,$table,$conditions=[]){
    if($conditions == [])
    {
      $conditions[0] = null;
      $conditions[1] = null;
    }
    $this->_sql = "SELECT MIN({$column}) FROM {$table} WHERE 1=1 $conditions[0]";
    $this->_bind = $conditions[1];
    $this->query($this->_sql , $this->_bind);
    return $this;
  }
  //////////////////////////////////////////////////////////////////////////////////////////////////
  public function get(){
    return $this->_results;
  }
  //////////////////////////////////////////////////////////////////////////////////////////////////
  public function limit($num){
    $this->_sql .= " LIMIT {$num}";
    $this->query($this->_sql , $this->_bind);
    return $this;
  }
  //////////////////////////////////////////////////////////////////////////////////////////////////
  public function orderBy($column ,$sort="ASC"){
    $this->_sql .= " ORDER BY {$column} {$sort}";
    $this->query($this->_sql , $this->_bind);
    return $this;
  }
  //////////////////////////////////////////////////////////////////////////////////////////////////
  public function where($attribute,$value,$operator = "="){
    $this->_sql .= "  AND {$attribute} {$operator} :{$attribute} ";
    $this->_bind[$attribute] = $value;
    $this->query($this->_sql , $this->_bind);
    //dd($this);

    return $this;
  }
  //////////////////////////////////////////////////////////////////////////////////////////////////
  public function orWhere($attribute,$value,$operator="="){
    $this->_sql .= "  OR {$attribute} {$operator} :{$attribute} ";
    $this->_bind[$attribute] = $value;
    $this->query($this->_sql , $this->_bind);
    return $this;
  }
  public function count() {
    return $this->_rowCount;
  }

  public function lastInsertId(){
    return $this->_lastInsertId;
  }

  public function setClass($class) {
    $this->_class = $class;
  }

  public function getClass(){
      return $this->_class;
  }

  public function setFetchType($type) {
    $this->_fetchType = $type;
  }

  public function getFetchType(){
    return $this->_fetchType;
  }
}
