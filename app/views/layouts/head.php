<meta charset="utf-8">
<title><?= $this->getSiteTitle(); ?></title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<link rel="stylesheet" type="text/css" href="<?= get_public('/css/bootstrap4.min.css') ?>">
<link rel="stylesheet" type="text/css" href="<?= get_public('/css/w3.css' )?>">
<link rel="stylesheet" type="text/css" href="<?= get_public('/css/my_style.css') ?>">
<link rel="stylesheet" type="text/css" href="<?= get_public('/css/all.css') ?>">
<link rel="stylesheet" type="text/css" href="<?= get_public('/css/w3-purple-theme.css') ?>">

<link rel="stylesheet" type="text/css" href="<?= get_public('/css/fontawesome.min.css') ?>">

<link rel="stylesheet" type="text/css" href="<?= get_public('/css/side_style.css') ?>">
<script type="text/javascript" src="<?= get_public('/js/jquery-3.4.1.min.js') ?>" ></script>

<script type="text/javascript" src="<?= get_public('/js/bootstrap4.min.js') ?>" ></script>
<style>
 .w3-btn {
    background-color:#4CAF50 ;margin-bottom:4px;
 }
  body{
    background-image:url("<?= get_public('/uploads/images/bg3.jpg') ; ?>") ;
    height: 100%;
    background-position: center;
    background-repeat: no-repeat;
    background-size: cover;
    background-attachment: fixed;
    background-color: #cc99ff;
  }
  .input-error{
    border-color:red;


  }
  .container-fluid{
    width:110%;
    background-color: rgba(255,255,255,0.7);
    background-size: cover;
    min-height:2400px;
  }
  .footer{
    bottom: 0;
    position: relative ;
    width: 110%;
    color: black;
    font-size : 20px;
    text-align: center;
    padding-top :2%;
    padding-bottom :2%;
    background-color: rgba(255,255,255,0.7);
  }
  .content{
    width:100%;
    min-height:700px;
  }
  @media  screen and (max-width:1024px) {
    body{
      background-image: url("<?= PUBLIC_R.'/uploads/images/bg5.jpg' ; ?>") ;
      height: 100%;
      background-position: center;
      background-repeat: no-repeat;
      background-size: cover;
      background-color: #cc99ff;
    }
    .container-fluid{
      width:100%;
      min-height:1400px;
    }
    .content{
      margin-top:100px; min-height:1200px; bottom:0; padding:1%;
    }
  }
  @media  screen and (max-width:600px) {
    .content{
      margin-top:100px; min-height:700px; bottom:0; padding:1%;
    }
  }
</style>
<?= $this->contents('head'); // any thing wrote in start method with key head printed here ?>
