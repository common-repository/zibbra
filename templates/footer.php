<?php defined("ZIBBRA_BASE_DIR") or die("Restricted access"); ?>

<?php if(get_option("zibbra_bootstrap_container","N")=="Y"): ?>

			</div>

<?php endif; ?>

<?php if(get_option("zibbra_wrapped_theme","N")=="N"): ?>
				
		</main>
	</div>

<?php get_footer(); ?>
		
<?php endif; ?>