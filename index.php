<?php include_once "includes/config.php"; ?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title>IMGO :: Image Optimazer</title>
	<link rel="icon" href="includes/favicon.png">

	<link rel="stylesheet" href="includes/css/corner-indicator.css">
	<!-- <link rel="stylesheet" href="includes/css/big-counter.css"> -->
	<link rel="stylesheet" href="includes/css/select2.min.css">
	<!-- <link rel="stylesheet" href="includes/css/basic.min.css"> -->
	<link rel="stylesheet" href="includes/css/dropzone.min.css">
	<link rel="stylesheet" href="includes/css/style.css">
</head>
<body>
	
	<header>
		<img src="includes/logo.svg" class="logo">
		<div class="menu">
				<a href="index.php" class="active">Banner Image</a>
				<a href="gallery.php">Gallery Image</a>
		</div>
	</header>

	<?php 
		$json = json_decode(file_get_contents('https://projects.propertypistol.com/imgo/ver.json'));
		$upVer = $json->ver;
		$upStable = $json->stable;
		if(($ver < $upVer) && ($upStable === true)){
	?>

	<div class="alert alert-update">
		<div>Hey! New verison of <b>IMGO</b> is ready. For download click here &xrarr;<form method="POST" action="index.php" style="display: inline-block;"><a class="alert-btn" href="https://projects.propertypistol.com/imgo/downloads/" target="_blank"> Update Now</a></form></div>
		<a href="https://projects.propertypistol.com/imgo/change-logs/" target="_blank" class="alert-link alert-close">(Click Here See Change Logs)</a>
	</div>

	<?php } if(isset($_GET['done'])){ ?>
	<div class="alert alert-success">
		<div>Done! Images generated successfully. To view generated images &xrarr;<form method="POST" action="index.php" style="display: inline-block;"><button class="alert-btn" name="openFileIMGO"> Open Images Folder</button></form></div>
		<a href="index.php" class="alert-close">&#10005;</a>
	</div>
	<script>
    var count = 5; var countdown = setInterval(function(){
      if (count == 0){ clearInterval(countdown); window.open('index.php', "_self");}
      count--;
    }, 1000);
  </script>
	<?php }
		function openFolderIMGO(){ 
			$OutputFolderName = "WebOptimazeImages";
			$uploadPath = exec('echo %SystemDrive%') . '\\Users\\' . get_current_user() . '\\Desktop\\'. $OutputFolderName .'\\';
			shell_exec('explorer ' . $uploadPath); }
		if(isset($_POST['openFileIMGO'])){ openFolderIMGO(); }
	?>

	<form id="imageUploadForm">
	<main>
		<div>
			<div class="action-box">
				<h4 class="title">Banner Image Settings</h4>

					<div class="input-group">
						<label for="imgHeight">Banner Size</label>
						<select class="select" name="imgHeight" id="imgHeight">
	              <option value="1" selected>Temp 2 [ 1400 X 800 ]</option>
	              <option value="2">Temp 1 [ 1400 X 680 ]</option>
	              <option value="3">Temp 3 [ 1400 X 575 ]</option>
	          </select>
          </div>

          <div class="input-group">
						<label for="imgOutSize">Output Size</label>
						<select class="select" name="imgOutSize" id="imgOutSize">
                <option value=sm>Small [ 320 ]</option>
                <option value="md">Mobile [ 768, 320 ]</option>
                <option value="lg" selected>Desktop [ 1400, 768, 320 ]</option>
            </select>
          </div>

          <div class="group-2">
	          <div class="input-group">
							<label for="imgQuality">JPG Quality</label>
							<select class="select" name="imgQuality" id="imgQuality">
                  <option value="40">40</option>
                  <option value="45">45</option>
                  <option value="50">50</option>
                  <option value="5">55</option>
                  <option value="60">60</option>
                  <option value="65">65</option>
                  <option value="70">70</option>
                  <option value="75">75</option>
                  <option value="80" selected>80</option>
                  <option value="85">85</option>
                  <option value="90">90</option>
                  <option value="95">95</option>
                  <option value="100">100</option>
              </select>
	          </div>

	          <div class="input-group">
							<label for="imgWpQuality">WP Quality</label>
							<select class="select" name="imgWpQuality" id="imgWpQuality">
                  <option value="40">40</option>
                  <option value="45">45</option>
                  <option value="50">50</option>
                  <option value="5">55</option>
                  <option value="60">60</option>
                  <option value="65">65</option>
                  <option value="70">70</option>
                  <option value="75">75</option>
                  <option value="80" selected>80</option>
                  <option value="85">85</option>
                  <option value="90">90</option>
                  <option value="95">95</option>
                  <option value="100">100</option>
              </select>
	          </div>
          </div>

          <div class="input-group">
						<button class="frm-btn" id="uploaderBtn">Generate Images</button>
          </div>

			</div>
		</div>

		<div id="imageUpload" class="dropzone"></div>
	</main>
	</form>
	<footer>
		<div>Ver <b><?= $ver ?></b> &diams; <a href="https://projects.propertypistol.com/imgo/downloads/" target="_blank">Downloads</a> &diams; <a href="https://projects.propertypistol.com/imgo/change-logs/" target="_blank">Change Logs</a></div>
		<div>&copy; <b>ROHIT KANADE</b></div>
	</footer>


	<script src="includes/js/jquery.js"></script>
	<script src="includes/js/pace.min.js"></script>
	<script src="includes/js/select2.min.js"></script>
	<!-- <script src="includes/js/dropzone-amd-module.min.js"></script> -->
	<script src="includes/js/dropzone.min.js"></script>
	<script src="includes/js/banner.js"></script>
</body>
</html>