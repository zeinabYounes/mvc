
<?php $this->start('content'); ?>

<div class =" row p-md-5 pt-5 p-md-2 ml-md-5">
  <?php $this->viewExtend("errors.errors"); ?>

    <div class="col-12 col-md-10 col-xl-10   mt-3 pt-5 p-md-5  login " >
      <form class="w3-container formd" action="<?= url("register")  ?>" method="POST" >
        <div class="w3-container w3-text-white loghead"  >
          <b style="text-shadow: 2px 2px 4px black, 0 0 5px blue, 0 0 3px darkblue; " class="loghead">REGISTER</b>
        </div>
        <div class="form-group ">
          <label for="w_username" class="" style="color:#72495a;" ><b>Username  </b></label>
          <input id="w_username" type="text" class="w3-input <?= $class = array_key_exists("username",$errors)?'input-error':null ;?>" name="username" value="" style="background-color: #ffffff;" placeholder="please insert username here !">
        </div>
        <br>
        <div class="form-group ">
          <label for="w_mail" class="" style="color:#72495a;" ><b>E-mail  </b></label>
          <input id="w_mail" type="text" class="w3-input <?= $class = array_key_exists("email",$errors)?'input-error':null ;?>" name="email" value="" style="background-color: #ffffff;" placeholder="please insert Email here !">
        </div>
        <br>
        <div class="form-group">
          <label for="w_password" style="color:#72495a; "><b>Password  </b></label>
          <input id="w_password" type="password" class="w3-input <?= $class = array_key_exists("password",$errors)?'input-error':null ;?>" name="password" style="background-color: #ffffff;" placeholder="please insert your password here !">
        </div>
        <br>
        <div class="form-group ">
          <label for="w_confirmpass" class="" style="color:#72495a;" ><b>Confirm password  </b></label>
          <input id="w_confirmpass" type="password" class="w3-input <?= $class = array_key_exists("confirm_pass",$errors)?'input-error':null ;?>" name="confirm_pass" value="" style="background-color: #ffffff;" placeholder="please confirm password here !">
        </div>

        <br><br>
        <button type="submit" class="w3-btn  w3-block" style="background-color:rgba(153, 255, 51,0.5)">
          <i class="fa fa-btn fa-sign-in"></i> SIGN UP
        </button>
      </form>
    </div><!-- /************************************************************* -->
</div>
<?php $this->end(); ?>
<?php $this->viewExtend("layouts.default");?>
