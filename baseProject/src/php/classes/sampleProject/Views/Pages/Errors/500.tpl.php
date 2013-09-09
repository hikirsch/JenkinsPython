<?php
/**
 * Sample 500 Internal Server Error Page
 */
?>
<p>
	An internal server error has occured! This page shows using a separate <em>BaseErrorPage</em> template and
	can be configured to show the exception via the <em>APP_SHOW_EXCEPTION</em> config option.
</p>

<p>
	You probably want to <a href="/">return to the homepage</a>.
</p>

<?php if( APP_SHOW_EXCEPTION ) : ?>
	<div class="error-detail">
		<h2>Exception Details</h2>

		<p class="error-message">
			<strong>Message:</strong> <?php echo $this->exception->getMessage(); ?>
		</p>

		<p class="error-stack">
			<strong>Stack</strong><br />
			<span class="stack-trace" style="white-space: pre;"><?php echo $this->exception->getTraceAsString(); ?></span>
		</p>

		<?php if( isset( $this->innerException ) ) : ?>
			<h3>Inner Exception</h3>

			<p class="error-message">
				<strong>Message:</strong> <?php echo $this->innerException->getMessage(); ?>
			</p>

			<p class="error-stack">
				<strong>Stack</strong><br />
				<span class="stack-trace" style="white-space: pre;"><?php echo $this->innerException->getTraceAsString(); ?></span>
			</p>
		<?php endif; ?>
	</div>
<?php endif; ?>
