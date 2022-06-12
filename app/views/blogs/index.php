
<?php  $this->start('content');  ?>

<div class="row  p-5 p-md-2 ">
  <?php foreach ($posts as $post):  ?>
    <div class=" card  col-7  mt-5  p-5 pl-5  mx-auto "
    style=" border-left: 10px solid  #660066;  border-radius :20px;">

      <div class="card-header w3-theme-d5"><?= $post->user->username ?>
        <span class="float-right">

          <?php if(check_permissions('update_post')){?>
             <a  href="<?= get_url('blogs/edit/'.$post->p_id)?>" class="btn btn-sm w3-theme-l4">&#9997;</a>
             <?php } ?>
             <?php if(check_permissions('delete_post')){?>
          <a  data-toggle="modal" data-target="#confirmDel<?=$post->p_id ?>" class ="btn btn-sm w3-theme-l4">&#10060;</a>
          <?php } ?>
        </span>
      </div>
      <div class="card-body">
       <h3 class="card-title"> <?= $post->p_title ?></h3>
       <br>
       <?= $post->p_text ?>

      </div>


      <!-- ************************************************************************************* -->
      <div class="modal fade" id="confirmDel<?=$post->p_id ?>">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-body">
              <h2> Are you sure to delete it &#10067;&#10067;</h2>
            </div>
            <div class="modal-footer">
              <span >
                <button type="button" class="btn btn-info" data-dismiss="modal"> Close </button>

                <a href="<?= get_url('blogs/delete/'.$post->p_id)?>" class ="btn btn-danger"> Sure </a>
              </span>
            </div>
          </div>
        </div>
      </div>
    </div>

<?php endforeach; ?>
</div>
<?php $this->end();  $this->viewExtend("layouts.default");?>
