<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <title><?php echo $title; ?></title>

    <!-- Bootstrap core CSS -->
    <link href="<?php echo base_url(); ?>assets/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="<?php echo base_url(); ?>assets/css/signin.css" rel="stylesheet">
  </head>

  <body>

    <div class="container">

<div id="infoMessage"><?php echo $message;?></div>

<?php $attributes = array('class' => 'form-signin'); echo form_open("auth/login", $attributes);?>
<!--h1><?php echo lang('login_heading');?></h1>
<h2 class="form-signin-heading"><?php echo lang('login_subheading');?></h2-->
  <h2 class="form-signin-heading">Please sign in</h2>
  <?php echo lang('login_identity_label', 'identity');?>
  <?php echo form_input($identity);?>
  <?php echo lang('login_password_label', 'password');?>
  <?php echo form_input($password);?>
  <div class="checkbox">
  	<?php echo lang('login_remember_label', 'remember');?>
	<?php echo form_checkbox('remember', '1', FALSE, 'id="remember"');?>
</div>
  <?php $extra_button = 'class="btn btn-lg btn-primary btn-block"'; echo form_submit('submit', lang('login_submit_btn'), $extra_button);?>
  <p><a href="forgot_password"><?php echo lang('login_forgot_password');?></a></p>
  <?php echo form_close();?>
</div> <!-- /container -->
  </body>
</html>
