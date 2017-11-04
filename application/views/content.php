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
        <h1>Selamat Datang di Web API Server SIMPEG</h1>
      </div>
      <p class="lead">Website ini hanya sekedar demo aplikasi untuk API Web Service SIMPEG</p>
	  <p>Cara penggunaan API memakai kode dibawah ini:</p>
<p><code><br>$table = 'nama_table_alias';<br>
$key = '123';//optional<br>
//API URL Single Value<br>
$url = "<?php echo site_url(); ?>api/$table/$key";<br>
//API URL All Value (limited)<br>
$start = 0; //default<br>
$limit = 25; //default<br>
$url = "<?php echo site_url(); ?>api/$table?start=$start&amp;limit=$limit";<br>
//API key<br>
$apiKey = 'Your API Key';<br>
//create a new cURL resource<br>
$ch = curl_init($url);<br>
curl_setopt($ch, CURLOPT_TIMEOUT, 30);<br>
curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);<br>
curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);<br>
curl_setopt($ch, CURLOPT_HTTPHEADER, array("X-API-KEY: " . $apiKey, "CLIENT: ".$_SERVER['SERVER_NAME']));<br>
$result = curl_exec($ch);<br>
//close cURL resource<br>
curl_close($ch);<br>
//response<br>
print_r($result);
</code></p>
      <p>Table yang saat ini dibuka adalah</p>
	  <ol>
	  	<li><pre>ref_dikfung as dikfung</pre></li>
		<li><pre>ref_dikstr as dikstr</pre></li>
		<li><pre>ref_diktek as diktek</pre></li>
		<li><pre>ref_dukpej as dukpej</pre></li>
		<li><pre>ref_dukpns as dukpns</pre></li>
		<li><pre>ref_eselon as eselon</pre></li>
		<li><pre>ref_gapok as gapok</pre></li>
		<li><pre>ref_geselon as geselon</pre></li>
		<li><pre>ref_ggolruang as ggolruang</pre></li>
		<li><pre>ref_golruang as golruang</pre></li>
		<li><pre>ref_hukumandis as hukumandis</pre></li>
		<li><pre>ref_insinduk as insinduk</pre></li>
		<li><pre>ref_jabfung as jabfung</pre></li>
		<li><pre>ref_jabneg as jabneg</pre></li>
		<li><pre>ref_jabnstr as jabnstr</pre></li>
		<li><pre>ref_jdiktek as jdiktek</pre></li>
		<li><pre>ref_jenis_cuti as jenis_cuti</pre></li>
		<li><pre>ref_jenisjab as jenisjab</pre></li>
		<li><pre>ref_jenispenghargaan as jenispenghargaan</pre></li>
		<li><pre>ref_jenistugas as jenistugas</pre></li>
		<li><pre>ref_jenpeg as jenpeg</pre></li>
		<li><pre>ref_jfu as jfu</pre></li>
		<li><pre>ref_jpensiun as jpensiun</pre></li>
		<li><pre>ref_jurpend as jurpend</pre></li>
		<li><pre>ref_kbayar as kbayar</pre></li>
		<li><pre>ref_keljfu as keljfu</pre></li>
		<li><pre>ref_klmpjab as klmpjab</pre></li>
		<li><pre>ref_klmpusia as klmpusia</pre></li>
		<li><pre>ref_kpe as kpe</pre></li>
		<li><pre>ref_ktua as ktua</pre></li>
		<li><pre>ref_kursus as kursus</pre></li>
		<li><pre>ref_lembagapend as lembagapend</pre></li>
		<li><pre>ref_loknegara as loknegara</pre></li>
		<li><pre>ref_marga as marga</pre></li>
		<li><pre>ref_matpel as matpel</pre></li>
		<li><pre>ref_naikpang as naikpang</pre></li>
		<li><pre>ref_pejabat as pejabat</pre></li>
		<li><pre>ref_pkerjaan as pkerjaan</pre></li>
		<li><pre>ref_seminar as seminar</pre></li>
		<li><pre>ref_sgoldar as sgoldar</pre></li>
		<li><pre>ref_tatar as tatar</pre></li>
		<li><pre>ref_tingpend as tingpend</pre></li>
		<li><pre>ref_tpu as tpu</pre></li>
		<li><pre>ref_uniturusan as uniturusan</pre></li>
		<li><pre>ref_unkerja as unkerja</pre></li>
		<li><pre>ref_wilayah as wilayah</pre></li>
		<li><pre>peg_identpeg as pegawai</pre></li>
		<li><pre>peg_tkerja as tkerja</pre></li>
		<li><pre>peg_jakhir as jakhir</pre></li>
	  </ol>
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
