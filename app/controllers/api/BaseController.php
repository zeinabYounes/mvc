<?php

namespace App\Controllers\Api;
use Core\DB;
use Core\Controller as Controller;
use Illuminate\Http\Request;
use App\Models\UserToken;

class BaseController extends Controller
{
  /**
  *function to show response in error status take two param error number and message
  * @return response in json object
  */
  public function ReturnError($msg,$errorNum){
    echo json_encode([
      'data'=>null,
      'message'=>$msg,
      'status'=>$errorNum,
    ]);
    die();
  }
  /**
  *function to show response in successfull status
  * @return response in json object
  */
  public function ReturnSuccess($msg="",$errorNum="200"){
    echo json_encode([
      'data'=>null,
      'message'=>$msg,
      'status'=>$errorNum,
    ]);
    die();
  }
  /**
  *function to show response in  successfull status with data take tree param key,value and message
  * @return data in json object
  */
  public function ReturnData($key,$value,$msg=""){
    echo json_encode([
      $key=>$value,
      'message'=>$msg,
      'status'=>$errorNum,
      'status'=>200,
    ]);
    die();
  }

}
