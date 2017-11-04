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
              <a class="nav-link" href="<?php echo site_url('create'); ?>">API Key</a>
            </li>
			<li class="nav-item">
              <a class="nav-link" href="<?php echo site_url('docs'); ?>">Documentation</a>
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
        <h1>API Key</h1>
      </div>
	  <?php echo ($this->session->flashdata('error')) ? error_msg($this->session->flashdata('error')) : ''; ?>
	  <?php echo ($this->session->flashdata('success')) ? success_msg($this->session->flashdata('success')) : ''; ?>
	  <?php
	  $keys = $this->key->find_all("user_id = $user->id");
	  if($keys){
	  /*
      <p class="lead">Your API Key is <code><?php echo $key->key; ?></code></p>
	  */
	  ?>
	  <table class="table table-bordered">
	  	<thead>
			<tr>
				<th>Client</th>
				<th>API Key</th>
				<th class="text-center">Limit Per Days</th>
				<th class="text-center">Request This Day</th>
				<th class="text-center">Delete</th>
			</tr>
		</thead>
		<tbody>
			<?php
				$today = date('Y-m-d', time());
			foreach($keys as $key){
				$logs = $this->access->find_count("api_key = '$key->key' AND DATE_FORMAT(created_at, '%Y-%m-%d') = CURDATE()");
				//$test = $this->access->find("api_key = '$key->key'");
				//echo date('Y-m-d H:i:s', $test->time);
				//test($test);
				//echo date('Y-m-d H:i:s', time());
			?>
	  		<tr>
				<td><?php echo $key->domain; ?></td>
				<td><?php echo $key->key; ?></td>
				<td class="text-center"><?php echo $key->ignore_limits; ?></td>
				<td class="text-center"><?php echo $logs; ?></td>
				<td class="text-center"><a class="btn btn-danger btn-sm" href="<?php echo site_url('delete_key/'.$key->id); ?>">Delete</a></td>
			</tr>
			<?php } ?>
		</tbody>
	  </table>
	  <p><a href="<?php echo site_url('create/api_key'); ?>" class="btn btn-success btn-block">Create API Key</a></p>
	  <?php } else { ?>
	  <p><a href="<?php echo site_url('create/api_key'); ?>" class="btn btn-success btn-block">Create API Key</a></p>
	  <?php } ?>
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
