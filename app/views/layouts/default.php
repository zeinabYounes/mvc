<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <?php $this->viewExtend("layouts.head"); ?>
  </head>
  <body>
    <div  class="container-fluid" >
      <div class="row">
        <nav class="navbar navbar-expand-sm fixed-top col-12 mb-5" id="topNav" style=" background-color:#e3c5c9;  ">
          <div class="navbar-nav float-right" style="float:right;">
             <?php
             if(Core\Auth::check()){
               $this->viewExtend("layouts.nav");
             }
             else{
               $this->viewExtend("auth.nav");
             }
             ?>
          </div>
        </nav>
        <?php
          $status = $this->getStatus();
          if($status !== "guest" && Core\Auth::check()){
            $this->viewExtend("layouts.section");
          }
        ?>
        <div class =" content col-12 mt-5 "  >
          <?=  $this->contents('content');  ?>
        </div>
      </div>
    </div>
    <div class="footer ">
      All Right Reserved for Agbanyat@2021
    </div>
    <script type="text/javascript" src="<?= get_public('/js/script.js') ?>"> </script>

  </body>
</html>
