<a class="" href="#" onclick="openNav()">&#9776; </a>
<a href="#" class="float-right"><?= Core\Auth::user()->username; ?></a>
<?php if(check_permissions('create_post')){?>
  <a href="<?= get_url('blogs/create')?>" class="float-right">Create Post</a>
<?php } ?>
<?php if(check_permissions('show_posts')){?>
  <a href="<?= get_url('blogs/index')?>" class="float-right">Posts</a>
<?php } ?>
