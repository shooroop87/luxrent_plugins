<?php
/*
 * Template Name: Portfolio Listings Single Page Template
 */
?>

<?php get_header('wdt'); ?>

	<?php
	/**
	* wdt_before_main_content hook.
	*/
	do_action( 'wdt_before_main_content' );
	?>

		<?php
		/**
		* wdt_before_content hook.
		*/
		do_action( 'wdt_before_content' );
		?>

			<?php
			if( have_posts() ):
				while( have_posts() ):
				the_post();

					the_content();

				endwhile;
			endif;
			?>

		<?php
		/**
		* wdt_after_content hook.
		*/
		do_action( 'wdt_after_content' );
		?>

	<?php
	/**
	* wdt_after_main_content hook.
	*/
	do_action( 'wdt_after_main_content' );
	?>

<?php get_footer('wdt'); ?>