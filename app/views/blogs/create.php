
<?php  $this->start('content');  ?>

<div class="row  p-5 p-md-2 ">
  <div class=" card  col-7  mt-5  p-5 pl-5  mx-auto "
  style=" border-left: 10px solid  #660066;  border-radius :20px;
">
   <?php $this->viewExtend("errors.errors"); ?>
    <div class="card-header w3-theme-d5">Create Post</div>
    <div class="card-body">
      <form class="" action="<?= url('blogs') ?>" method="post">
        <div class="form-group">
          <label for="title" class="w3-text-theme">Post title:</label>
          <input type="text" name="title" class="form-control <?= $class = array_key_exists("title",$errors)?'input-error':null ;?>" value="">
        </div>
        <div class="form-group">
          <label for="text" class="w3-text-theme">Post text:</label>
          <textarea name="text"  class="form-control <?= $class = array_key_exists("text",$errors)?'input-error':null ;?>" rows="4" cols="80"></textarea>
        </div>
        <div class="form-group">
          <button type="submit" class="btn w3-theme-d3 btn-block">Submit</button>
        </div>
      </form>
    </div>

  </div>
</div>
<?php $this->end();  $this->viewExtend("layouts.default"); ?>
