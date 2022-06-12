<?php $this->start('content'); ?>
<div class =" row p-md-5 pt-5 p-md-2 ml-md-5">
  <?php $this->viewExtend("errors.errors"); ?>

    <div class="col-12 col-md-10 col-xl-10   mt-3 pt-5 p-md-5  login " >
      <form class="w3-container formd" action="<?= url("login")  ?>" method="POST" >
        <div class="w3-container w3-text-white loghead"  >
          <b style="text-shadow: 2px 2px 4px black, 0 0 5px blue, 0 0 3px darkblue; " class="loghead">LOGIN</b>
        </div>
        <?= csrfToken(); ?>
        <div class="form-group ">
          <label for="w_username" class="" style="color:#72495a;" ><b>Username or E-mail  </b></label>
          <input id="w_username" type="text" class="w3-input <?= $class = array_key_exists("username",$errors)?'input-error':null ;?>" name="username" value="" style="background-color: #ffffff;" placeholder="please inser Username or Email here !">
        </div>
        <br>
        <div class="form-group">
          <label for="w_password" style="color:#72495a; "><b>Password  </b></label>
          <input id="w_password" type="password" class="w3-input <?= $class = array_key_exists("password",$errors)?'input-error':null ;?>" name="password" style="background-color: #ffffff;" placeholder="please insert your password here !">
        </div>
        <br>
        <div class="form-group">
          <label for="token" style="color:#72495a; "><b>Remember me   </b></label>
          <input class="w3-check" type="checkbox" checked="checked" name="token" >
        </div>
        <br>
        <br>
        <button type="submit" class="w3-btn  w3-block" style="background-color:rgba(153, 255, 51,0.5)">
          <i class="fa fa-btn fa-sign-in"></i> SIGN IN
        </button>
      </form>
    </div><!-- /************************************************************* -->
</div>
<?php $this->end(); ?>
<?php $this->viewExtend("layouts.default");?>
