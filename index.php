<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Upload File(s)</title>

    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">

  </head>
  <body>

    <div class="container-fluid">
	<div class="row">
		<div class="col-md-12">
			<br />
			<h3 class="text-info text-center">
				UploadFiles
			</h3>
			<div class="row">
					<div class="col-md-4">
					</div>
					<div class="col-md-4 col-md-offset-2">
              <div class="alert alert-warning alert-dismissable">
									<strong>Total files uploaded : </strong> 
									<?php echo count(glob('upld/*')); ?> <!-- Here is use glob to know how many directories is there in the dir and count them-->
              </div>
          </div>
      </div>
			<nav>
				<ol class="breadcrumb">
					<li class="breadcrumb-item">
						<a href="index.php">Home</a>
					</li>
					<li class="breadcrumb-item">
						<a href="stats.php">Upload</a>
					</li>
					<li class="breadcrumb-item">
						<a href="stats.php">Files</a>
					</li>
				</ol>
			</nav>
		
			<?php
						// display alert error
            if(isset($_GET['error']) && !empty($_GET['error']) && is_string($_GET['error']))
						{ 
							echo '
							<div class="alert alert-danger alert-dismissable">
							<button type="button" class="close" data-dismiss="alert" aria-hidden="true">
								×
							</button>
							<strong>Warning !</strong> ' . htmlspecialchars(strip_tags($_GET['error'])) . '
						</div>'; 
						}

						// display succes alert
						if(isset($_GET['success']) && !empty($_GET['success']) && is_string($_GET['success']))
						{ 
							echo '
							<div class="alert alert-success alert-dismissable">
							<button type="button" class="close" data-dismiss="alert" aria-hidden="true">
								×
							</button>
							<strong>Warning !</strong> Upload successfull ! Download link : <input type="text" value="' . strip_tags($_GET['success']) . '">
						</div>'; 
						}
				?>
				
				

			<div class="text-center">
			<form role="form" method="POST" action="upload.php" enctype="multipart/form-data">
				<div class="form-group">
					
					<br />
					<input type="file" class="form-control-file" name="uploaded_file" style="text-align: center; display: inline-block;" required>
					<br /><br />
					<p class="help-block">
						Upload your file here. ( 20mo max ).
					</p>
				</div>
				<div class="checkbox">

				</div> 
				<button type="submit" class="btn btn-primary">
					Upload
				</button>
			</form>
			</div>
		</div>
	</div>
</div>

    <script src="js/jquery.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/scripts.js"></script>
  </body>
</html>