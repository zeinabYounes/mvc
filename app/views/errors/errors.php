<?php
global $errors;
if(!empty($errors)){
  foreach($errors as $error){
    echo'<div class="alert alert-danger alert-dismissible fade show">
    <button type="button" class="close" data-dismiss="alert">&times;</button>
    <strong>'.$error.'.</strong>
    </div>';
  }

}

?>
