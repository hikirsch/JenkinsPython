<?php
	use sampleProject\RoutesTable;
?>

<h1>
	Sample Dynamic Page
</h1>

<p>
	This is a sample of the page. It simply shows that you added <strong><?php echo $this->x; ?></strong> as the
	2nd path argument in the URL.
</p>

<p>
	Go ahead! Give it a shot. It won't break. If it does, tell Kirsch so he can fix it!
	You can also go to a <a href="<?php RoutesTable::Url("SampleDynamic", rand(0, 100) ); ?>">random page</a> .
</p>