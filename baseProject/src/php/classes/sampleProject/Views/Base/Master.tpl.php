<?php
/**
 * The main template file. This is parsed via Savant3.
 */
use baseProject\Managers\SEOManager;
	use sampleProject\RoutesTable;
?>
<!doctype html>
<html>
	<head>
		<meta charset="UTF-8" />

		<?php SEOManager::Show( $this->seo ); ?>

		<link rel="stylesheet" href="/assets/css/screen.css" />
	</head>

	<body>
		<?php echo SEOManager::ShowSEODebug( $this->seo ); ?>

		<div class="page-container">
			<div class="column-container">
				<div class="nav-container">
					<h4>Main Navigation</h4>

					<ul class="nav">
						<li>
							<a href="<?php RoutesTable::Url("Home"); ?>">Home</a>
						</li>

						<li>
							<a href="<?php RoutesTable::Url("SampleDynamic", "sample"); ?>">Dynamic Sample</a>
						</li>

						<li>
							<a href="<?php RoutesTable::Url("SampleError"); ?>">500 Error</a>
						</li>

						<li>
							<a href="/this-page-does-not-exist">404 Error</a>
						</li>

						<li>
							<a href="<?php RoutesTable::Url("SampleGone"); ?>">410 Gone</a>
						</li>
					</ul>
				</div>

				<div class="main-content">
					<?php echo $this->pageTemplate; ?>
				</div>
			</div>

			<footer>
				<p>
					<span id="my-div">
						JavaScript has either not loaded, disabled, in error,
						or a Plovr error is showing.
					</span>
				</p>
			</footer>
		</div>

		<script src="http://<?php echo $_SERVER["SERVER_NAME"]; ?>:9810/compile?id=main"></script>
	</body>
</html>
