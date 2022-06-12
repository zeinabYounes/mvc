<?php
namespace Core;
use \PDO;
use Core\{DB, Request};
class Model{
  protected static $table ;
  protected static $primary_key ="id" ;
  protected static $model;
  protected  static $hidden =[] ,$fillable =[];
  public static $relation_method;
  public static function getClass(){
    if(!static::$model){
      static::$model = new static();
    }
    return static::$model;
  }
  //////////////////////////////////////////////////////////////////////////////////////////////////
  public static function getHidden(){
    return static::$hidden;
  }
  //////////////////////////////////////////////////////////////////////////////////////////////////
  public static function getFillable(){
    $fill = static::$fillable;
    array_unshift($fill,static::$primary_key);
    return $fill;
  }
  //////////////////////////////////////////////////////////////////////////////////////////////////
  protected static function getDB($isClass = false) {
    $db = DB::getInstance();
    if($isClass == true){
      $db->setClass(get_called_class());
      $db->setFetchType(PDO::FETCH_CLASS );
    }
    return $db;
  }
  //////////////////////////////////////////////////////////////////////////////////////////////////
  public static function create($values){
    $db = static::getDB();
    $db->_sql = null;
    $db->_bind = null;
    $res = $db->insert(static::$table,$values);
    return $res;
  }
  //////////////////////////////////////////////////////////////////////////////////////////////////
  public static function find($id){
    $db = static::getDB(true);
    $db->_sql = null;
    $db->_bind = null;
    $res = $db->selectFirst(static::$table)->where(static::$primary_key,$id)->get();
    return $res ;
  }
  //////////////////////////////////////////////////////////////////////////////////////////////////
  public static function findOrFail($id){
    $db = static::getDB(true);
    $db->_sql = null;
    $db->_bind = null;
    $res = $db->selectFirst(static::$table)->where(static::$primary_key,$id)->get();
    if(!$res){
      url_error();
    }
    return $res ;
  }
  //////////////////////////////////////////////////////////////////////////////////////////////////
  public function update(){
    $db = static::getDB(true);
    $data = $this;
    foreach ($data as $key => $value) {
      $values[$key] = $value;
    }
    $primary =static::$primary_key;
    array_shift($values);
    $res = $db->update(static::$table, $values,[$primary,$this->$primary]);
    return $db;
  }
 //////////////////////////////////////////////////////////////////////////////////////////////////
  public function save(){
    $db = static::getDB(true);
    $data = $this;
    foreach ($data as $key => $value) {
      $values[$key] = $value;
    }
    $res = $db->insert(static::$table,$values);
    return $res;
  }
  //////////////////////////////////////////////////////////////////////////////////////////////////
  public static function all(){
    $db = static::getDB(true);
    $db->_sql = null;
    $db->_bind = null;
    $res = $db->select(static::$table)->orderBy(static::$primary_key)->get();
    return $res ;
  }
  public static function where($attribute,$value ,$operator = "="){
    $db = static::getDB(true);
    $res = $db->select(static::$table)->where($attribute,$value,$operator);
    return $res ;
  }

  //////////////////////////////////////////////////////////////////////////////////////////////////
  public static function with(...$method_name){
     $keys = $data = $empty =[];
    foreach($method_name as $value){
      $model = static::getClass();
      static::$relation_method = $value;
      $data_value =$model->$value();
      if(!empty($data_value)){
        $keys[]=$value;
        $data[$value] =$data_value;
      }
      else{
        $empty[] =$value;
      }
    }
    foreach ($keys as $key => $relation) {
      $new_key = array_key_exists($key+1,$keys);

      if($new_key != false){
        $new_relation = $keys[$key+1];
        foreach ($data[$relation] as $key2 => $basic_obj) {
          if(array_key_exists($key2,$data[$new_relation])){
            $i =0;
            while(property_exists($data[$relation][$key2], $keys[$i])){
              if(!property_exists($data[$new_relation][$key2], $keys[$i])){
                $rel = $keys[$i];
                $data[$new_relation][$key2]->$rel = $data[$relation][$key2]->$rel;
                foreach ($empty as $empty_relation) {
                  $data[$new_relation][$key2]->$empty_relation = null;
                }
              }
             $i++;
            }
          }
        }
      }
    }

    $last_key =array_key_last($data);
    $last = $data[$last_key];
    // value array of Posts ,value2 object of first post
    //k3 attribute,k2

    return $last;
    // $model = static::getClass();
    // return $model->$method_name();
  }
  //////////////////////////////////////////////////////////////////////////////////////////////////
  public function delete(){
    $db = static::getDB(true);
    $db->_sql = null;
    $db->_bind = null;
    $primary =static::$primary_key;
    $res = $db->delete(static::$table,[$primary ,$this->$primary]);
    return $res ;
  }
  //////////////////////////////////////////relations///////////////////////////////////////////////
  public static function deleteAll(){
    $db = static::getDB(true);
    $db->_sql = null;
    $db->_bind = null;
    $res = $db->deleteAll(static::$table);
    return $db ;
  }
  /*
  return data of relation one to one lile
  select * from users leftjoin faces on users.id =faces.user_id
  return obj of this model contain data of relation model

  */
  public static function hasOne($model,$foreign_key){
    $db = static::getDB(true);
    $local_table = static::$table;
    $relation_table = $model::$table;
    $local_key = static::$primary_key;
    $relation_local_key = $model::$primary_key;
    $local_fillable = static::getFillable();
    $relation_fillble = $model::getFillable();
    $sql = " SELECT  * FROM {$local_table} LEFT JOIN  {$relation_table} ON {$local_table}.{$local_key} = {$relation_table}.{$foreign_key}   ";
    $select = $db->query($sql);
    return $this->handle($model,$select,$foreign_key,$local_key);
  }
  //////////////////////////////////////////////////////////////////////////////////////////////////
  /*
  return data of relation one to one and one to many
  return obj of this model contain data of relation model

  */
  public  function hasMany($model,$foreign_key){
    $db = static::getDB(true);
    $relation_table = $model::$table;
    $local_key = static::$primary_key;
    //dd((get_object_vars($this)));
    if(!empty(get_object_vars($this))){
      $id=$this->$local_key;
      $db->_sql = " SELECT  *  FROM  {$relation_table}  WHERE {$relation_table}.{$foreign_key} ={$id} ";
      $db->_result_type =1;
      $select = $db->query($db->_sql);
      $res= $select->get();
      return $res;
    }
    //dd($this);
    $local_table = static::$table;
    $relation_local_key = $model::$primary_key;
    $local_fillable = static::getFillable();
    $relation_fillble = $model::getFillable();
    $local_select =[];
    $relation_select =[];
    foreach ($local_fillable as $key => $value) {
      $local_select[]=" {$local_table}.{$value} ";
    }
    foreach ($relation_fillble as $key => $value) {
      $relation_select[] =" {$relation_table}.{$value} as {$relation_table}00{$value}  ";
    }
    $local_sel_str= implode(',',$local_select);
    $relation_sel_str= implode(',',$relation_select);
    $db->_sql = " SELECT {$local_sel_str } ,{$relation_sel_str} FROM {$local_table} LEFT JOIN  {$relation_table} ON {$local_table}.{$local_key} = {$relation_table}.{$foreign_key}   ";
    //dd($db->_sql);
    $db->_result_type =1;

    $select = $db->query($db->_sql);
//dd($select);
    return $this->handleHasMany($model,$select,$foreign_key,$local_key);
  }
  /////////////////////////////foreign_key in local/////////////////////////////////////////////////////////////////////
  public function belongsTo($model,$foreign_key){
    $db = static::getDB(true);
    $local_key = static::$primary_key;
    $relation_table = $model::$table;
    $relation_local_key = $model::$primary_key;
    $local_table = static::$table;

    if(!empty(get_object_vars($this))){
      $id=$this->$local_key;
      $db->_sql = " SELECT  * FROM {$relation_table} RIGHT JOIN  {$local_table} ON {$relation_table}.{$relation_local_key} = {$local_table}.{$foreign_key} WHERE {$local_table}.{$local_key} ={$id}";
      $select = $db->query($db->_sql);
      $res= $select->get();
      $hiddenLocal = static::getHidden();
      $hiddenRelation = $model::getHidden();
      if($hiddenLocal !=[])
      foreach ($hiddenLocal as $value) {
        unset($res->$value);
      }
      if($hiddenRelation !=[])
      foreach ($hiddenRelation as $value) {
        unset($res->$value);
      }
      return $res;
    }
    $local_table = static::$table;
    $relation_table = $model::$table;
    $local_key = static::$primary_key;
    $relation_local_key = $model::$primary_key;
    $local_fillable = static::getFillable();
    $relation_fillble = $model::getFillable();
    foreach ($local_fillable as $key => $value) {
      $local_select[] =" {$local_table}.{$value} ";
    }
    foreach ($relation_fillble as $key => $value) {
      $relation_select[] =" {$relation_table}.{$value} as {$relation_table}00{$value}  ";
    }
    $local_sel_str= implode(',',$local_select);
    $relation_sel_str= implode(',',$relation_select);
    $sql = " SELECT  {$local_sel_str} ,{$relation_sel_str}  FROM {$local_table} LEFT JOIN  {$relation_table} ON {$local_table}.{$foreign_key} = {$relation_table}.{$relation_local_key}   ";
    $db->_result_type =1;
    $select = $db->query($sql);
    //return $this->handleBelongsTo($model,$select,$foreign_key,$local_key);
    return $this->handleBelongsTo($model,$select,$foreign_key,$local_key);
  }
  //////////////////////////////////////////////////////////////////////////////////////////////////
  public  function belongsToMany($model,$middle_table,$foreign_key1,$foreign_key2){
    $db = static::getDB(true);
    $local_table = static::$table;
    $relation_table = $model::$table;
    $local_key = static::$primary_key;
    $relation_local_key = $model::$primary_key;
    $pivot = "{$middle_table}.{$foreign_key1} as pivot1 , {$middle_table}.{$foreign_key2} as pivot2";
    if(!empty(get_object_vars($this))){
      $id=$this->$local_key;
      $db->_sql = " SELECT  *  FROM  {$relation_table},{$middle_table}  WHERE {$middle_table}.{$foreign_key1} = {$id} AND {$middle_table}.{$foreign_key2} = {$relation_table}.{$relation_local_key} ";
      $db->_result_type =1;
      $select = $db->query($db->_sql);
      $res= $select->get();
      return $res;
    }
    $local_fillable = static::getFillable();
    $relation_fillble = $model::getFillable();
    foreach ($local_fillable as $key => $value) {
      $local_select[]=" {$local_table}.{$value} ";
    }
    foreach ($relation_fillble as $key => $value) {
      $relation_select[] =" {$relation_table}.{$value} as {$relation_table}00{$value}  ";
    }

    $local_sel_str= implode(',',$local_select);
    $relation_sel_str= implode(',',$relation_select);
    $sql = " SELECT  {$local_sel_str},{$relation_sel_str} , {$pivot} FROM {$local_table},{$relation_table},{$middle_table} WHERE  {$middle_table}.{$foreign_key1} ={$local_table}.{$local_key} AND {$middle_table}.{$foreign_key2} = {$relation_table}.{$relation_local_key} ";
     //dd($sql);
    $db->_result_type =1;
    $select = $db->query($sql);
   //dd($select);
    return $this->handleMany($model,$select,$foreign_key1,$foreign_key2,$local_key);
  }
  //////////////////////////////////////////////////////////////////////////////////////////////////
  public function handleBelongsTo($model,$select,$foreign_key,$local_key){
    $relation_obj = static::$relation_method;
    $relation_table = $model::$table;
    $relation_data = $results=[];
    $res= $select->get();
    foreach ($res as $key => $value) {
      foreach ($value as $obj_attribute => $obj_value) {
        $table = strtok($obj_attribute, "00");
        $attribute = strtok("00");
        // dd($value);
        if($table ==$relation_table){
          if($value->$foreign_key ==null){
            unset($res[$key]->$obj_attribute);
            $res[$key]->$relation_obj = null;
            $results[$value->$local_key]=$res[$key];
          }
          else{
            $relation_data[$attribute] = $obj_value;
            unset($res[$key]->$obj_attribute);
            $res[$key]->$relation_obj = (object)$relation_data;
            $results[$value->$local_key]=$res[$key];
          }
        }
      }
    }
    return $results;
  }
  //////////////////////////////////////////////////////////////////////////////////////////////////
  public function handleHasMany($model,$select,$foreign_key,$local_key){
    $relation_obj = static::$relation_method;
    $relation_data = [];
    $res = $select->get();
    $relation_table = $model::$table;
    $results =[];
    foreach ($res as $key => $value) {
      foreach ($value as $obj_attribute => $obj_value) {
          $table = strtok($obj_attribute, "00");
          $attribute = strtok("00");
          if($table ==$relation_table){
             $relation_data[$attribute] = $obj_value;
             unset($res[$key]->$obj_attribute);
          }
      }
      if($relation_data[$model::$primary_key]==null){
          $res[$key]->$relation_obj= [];

          $results[$value->$local_key]=$res[$key];

      }
      else{
        $obj_model =(object)$relation_data;
        if($obj_model->$foreign_key == $value->$local_key){
          $data[$value->$local_key][] = $obj_model;
          $res[$key]->$relation_obj= $data[$value->$local_key];
          for($prev_key=$key-1; $prev_key>=0 ;$prev_key--){
            if(array_key_exists($prev_key,$res) && $value->$local_key==$res[$prev_key]->$local_key){
               unset($res[$prev_key]);
             }
          }
          $results[$value->$local_key]=$res[$key];
        }
      }
    }
    return $results;
  }
  //////////////////////////////////////////////////////////////////////////////////////////////////
  public function handleMany($model,$select,$foreign_key1,$foreign_key2,$local_key){
    $relation_obj = static::$relation_method;
    $relation_data = $arr_pivot = [];
    $res = $select->get();
    $relation_table = $model::$table;
    $results =[];
    foreach ($res as $key => $value) {
      foreach ($value as $obj_attribute => $obj_value) {
          $table = strtok($obj_attribute, "00");
          $attribute = strtok("00");
          if($table ==$relation_table){
             $relation_data[$attribute] = $obj_value;
             unset($res[$key]->$obj_attribute);
          }
          $obj_model =(object)$relation_data;
          if($obj_attribute == "pivot1" ){
            $arr_pivot[$foreign_key1] = $obj_value;
            unset($res[$key]->$obj_attribute);

          }
          if($obj_attribute == "pivot2" ){
            $arr_pivot[$foreign_key2] = $obj_value;
            unset($res[$key]->$obj_attribute);
          }
      }
      $pivot =(object)$arr_pivot;
      $relation_data["pivot"] = $pivot;
      $obj_model =(object)$relation_data;
      $data[$value->$local_key][] = $obj_model;
      $res[$key]->$relation_obj= $data[$value->$local_key];
      for($prev_key = $key-1; $prev_key>=0 ;$prev_key--){
        if(array_key_exists($prev_key,$res) && $value->$local_key==$res[$prev_key]->$local_key){
          unset($res[$prev_key]);
        }
      }
      $results[$value->$local_key]=$res[$key];
    }
    return $results;
  }
  //////////////////////////////////////////////////////////////////////////////////////////////////


}
