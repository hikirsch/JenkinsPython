<?php
/**
 * Base Error Page
 */
?>
<!doctype html>
<html>
	<head>
		<meta charset="UTF-8" />

		<link rel="stylesheet" href="/assets/css/screen.css" />
	</head>

	<body class="error-page">
		<div class="page-container">
			<div class="main-content">
				<h1>
					We're sorry!
				</h1>

				<?php echo $this->pageTemplate; ?>
			</div>
		</div>
	</body>
</html>
