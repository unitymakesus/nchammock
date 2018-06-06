<?php
/**
 * The template for displaying the footer.
 *
 * Contains the closing of the #content div and all content after
 *
 * @package storefront
 */

?>

		</div><!-- .col-full -->
	</div><!-- #content -->

	<?php do_action( 'storefront_before_footer' ); ?>

	<footer id="colophon" class="site-footer" role="contentinfo">
			<?php
			/**
			 * Functions hooked in to storefront_footer action
			 *
			 * @hooked storefront_footer_widgets - 10
			 * @hooked storefront_credit         - 20
			 */
			do_action( 'storefront_footer' ); ?>

		<div class="footer-info">
			<h6>All Rights Reserved &copy; <?php echo date('Y'); ?> North Carolina Hammock Company</h6>

			<a href="https://www.unitymakes.us/" target="_blank" rel="noopener" class="unity-link">
			<?php echo file_get_contents(get_stylesheet_directory() . '/assets/images/made-with-unity.svg'); ?></a>
		</div>

	</footer><!-- #colophon -->
	<?php do_action( 'storefront_after_footer' ); ?>

</div><!-- #page -->

<?php wp_footer(); ?>

</body>
</html>
