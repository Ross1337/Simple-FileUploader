<?php

function safeSession() {
	if (isset($_COOKIE[session_name()]) AND preg_match('/^[-,a-zA-Z0-9]{1,128}$/', $_COOKIE[session_name()])) {
					session_start();
			} elseif (isset($_COOKIE[session_name()])) {
					unset($_COOKIE[session_name()]);
					session_start(); 
			} else {
					session_start(); 
			}
	}
	
	safeSession();

?>

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
									<?php echo count(glob('upld/*')) - 1; ?> <!-- Here is use glob to know how many directories is there in the dir and count them-->
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

<br>
<div class="row">
		<div class="col-md-4"></div>
		<div class="col-md-4 col-md-offset-2">
			<h3>
						Your 3 last uploads.
			</h3>
			<table class="table">
				<thead>
					<tr>
						<th>
							Name
						</th>
						<th>
							Link ( click on it )
						</th>
						<th>
							Date
						</th>
					</tr>
				</thead>
				<tbody>
					<?php

						for($y = 0; $y < 3; $y++)
						{
							if(isset($_SESSION['upload'][$y]))
							{
								
									echo '
								<tr>
									<td>
										' . htmlspecialchars(strip_tags($_SESSION['upload'][$y]['name'])) . '
									</td>
									<td>
										<a href="' . htmlspecialchars($_SESSION['upload'][$y]['link']) . '">' . htmlspecialchars(strip_tags($_SESSION['upload'][$y]['name'])) . '</a>
									<td>
									' . htmlspecialchars($_SESSION['upload'][$y]['date']) . '
									</td>
								</tr>
								';
							}
						}

					?>
				</tbody>
			</table>
		</div>
	</div>

    <script src="js/jquery.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/scripts.js"></script>
  </body>
</html>

<?php

$parse_key = parse_ini_file('upld/encryption/key.ini'); 

if(empty($parse_key['encryption_key']))
{
		function generateRandomString($length = 10, $seeds = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz01234567890123456789')
				{
						$str = '';
						$seedsCount = strlen($seeds);
						list($usec, $sec) = explode(' ', microtime());
						$seed = (float) $sec + ((float) $usec * 100000);
						mt_srand($seed);
						for ($i = 0; $length > $i; $i++) {
								$str .= $seeds{mt_rand(0, $seedsCount - 1)};
						}
						return $str;
				}
		
		function set_encryption_key()
				{
						$begin    = '----- BEGIN ENCRYPTION KEY ----- ';
						$end      = ' ----- END ENCRYPTION KEY -----';
						$salt1 = generateRandomString(100);
						$salt2 = generateRandomString(100);
						$key = generateRandomString(100);
						$key = $begin . $salt1 . '.' . $key . '.' . $salt2 . $end;
						$key = base64_encode($key);

						$set_e_k = fopen('upld/encryption/key.ini', 'w');

						fputs($set_e_k, '[encryption_key]' . "\r\n");
						fputs($set_e_k, 'encryption_key = "' . $key . "\"");

						fclose($set_e_k);
				}

		set_encryption_key();
}

?>
