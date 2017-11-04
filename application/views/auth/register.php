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
    <link href="<?php echo base_url(); ?>assets/css/sticky-footer-navbar.css" rel="stylesheet">
  </head>

  <body>

    <header>
      <!-- Fixed navbar -->
      <nav class="navbar navbar-expand-md navbar-dark fixed-top bg-dark">
        <a class="navbar-brand" href="#">&nbsp;</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarCollapse">
          <ul class="navbar-nav mr-auto">
            <li class="nav-item active">
              <a class="nav-link" href="<?php echo site_url(); ?>">Home <span class="sr-only">(current)</span></a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="#">Link</a>
            </li>
          </ul>
		  <?php if (!$this->ion_auth->logged_in()){ ?>
          <form class="form-inline mt-2 mt-md-0" action="<?php echo site_url('auth/login'); ?>" method="post">
            <input class="form-control mr-sm-2" type="text" name="identity" id="identity" placeholder="Username/Email">
			<input class="form-control mr-sm-2" type="password" name="password" id="password" placeholder="Password">
            <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Login</button>&nbsp;
			<a class="btn btn-success my-2 my-sm-0" href="<?php echo site_url('auth/register'); ?>">Register</a>
          </form>
		  <?php } else { ?>
		  <ul class="navbar-nav ml-auto">
		  	<li class="nav-item">
				<a class="nav-link" href="<?php echo site_url('auth/logout'); ?>">Logout</a>
			</li>
		</ul>
		<?php } ?>
        </div>
      </nav>
    </header>

    <!-- Begin page content -->
    <main role="main" class="container">
      <div class="mt-3">
	  	<h1><?php echo lang('create_user_heading');?></h1>
	  </div>
<div class="form-group row"><?php echo lang('create_user_subheading');?></div>

<?php echo ($message) ? '<div id="infoMessage"><div class="alert alert-danger" role="alert">'.$message.'</div></div>' : '';?>

<?php $attributes = array('class' => 'form-horizontal'); echo form_open("auth/register");?>

      <div class="form-group row">
            <?php 
			$attributes = array(
				'class' => 'col-2 col-form-label',
			);
			echo lang('create_user_fname_label', 'first_name', $attributes);?>
			<div class="col-5">
            <?php echo form_input($first_name);?>
			</div>
      </div>

      <div class="form-group row"> 
            <?php echo lang('create_user_lname_label', 'last_name', $attributes);?>
			<div class="col-5"> 
            <?php echo form_input($last_name);?>
			</div>
      </div>
      
      <?php
      if($identity_column!=='email') {
          echo '<div class="form-group row">';
          echo lang('create_user_identity_label', 'identity', $attributes);
          echo '<div class="col-5"> ';
          echo form_error('identity');
          echo form_input($identity);
          echo '</div>
      </div>';
      }
      ?>

      <div class="form-group row"> 
            <?php echo lang('create_user_company_label', 'company', $attributes);?>
			<div class="col-5">
            <?php echo form_input($company);?>			
			</div>
      </div>

      <div class="form-group row">
            <?php echo lang('create_user_email_label', 'email', $attributes);?>
			<div class="col-5"> 
            <?php echo form_input($email);?>			
			</div>
      </div>

      <div class="form-group row">
            <?php echo lang('create_user_phone_label', 'phone', $attributes);?>
			<div class="col-5">
            <?php echo form_input($phone);?>			
			</div>
      </div>

      <div class="form-group row">
            <?php echo lang('create_user_password_label', 'password', $attributes);?>
			<div class="col-5">
            <?php echo form_input($password);?>			
			</div>
      </div>

      <div class="form-group row"> 
            <?php echo lang('create_user_password_confirm_label', 'password_confirm', $attributes);?>
			<div class="col-5"> 
            <?php echo form_input($password_confirm);?>			
			</div>
      </div>


      <?php $extra_button = 'class="btn btn-lg btn-primary"'; echo form_submit('submit', 'Register', $extra_button);?>

<?php echo form_close();?>
</main>

    <footer class="footer">
      <div class="container">
        <span class="text-muted">Web API Service SIMPEG Demo.</span>
      </div>
    </footer>


    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="<?php echo base_url(); ?>assets/js/jquery-3.2.1.slim.min.js"></script>
    <script src="<?php echo base_url(); ?>assets/js/popper.min.js"></script>
    <script src="<?php echo base_url(); ?>assets/js/bootstrap.min.js"></script>
  </body>
</html>
