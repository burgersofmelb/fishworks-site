<?php
/**
 * Visit block: address, hours, bookings and contact.
 */
$fw_phone = fw_mod( 'fw_phone' );
$fw_email = fw_mod( 'fw_email' );
$fw_insta = fw_mod( 'fw_instagram' );
?>
<section class="visit wrap" id="visit">
	<p class="label">Visit</p>
	<div class="cols">
		<div>
			<h3>Address</h3>
			<p><a href="<?php echo esc_url( fw_mod( 'fw_maps_url' ) ); ?>" target="_blank" rel="noopener"><?php echo nl2br( esc_html( fw_mod( 'fw_address' ) ) ); ?></a></p>
		</div>
		<div>
			<h3>Hours</h3>
			<p><?php echo nl2br( esc_html( fw_mod( 'fw_hours' ) ) ); ?></p>
		</div>
		<div>
			<h3>Bookings &amp; contact</h3>
			<p>
				<a href="<?php echo esc_url( 'https://bookings.obeeapp.com/' . rawurlencode( fw_mod( 'fw_obee_account' ) ) ); ?>" target="_blank" rel="noopener">Reserve online</a>
				<?php if ( $fw_phone ) : ?><br><a href="tel:<?php echo esc_attr( preg_replace( '/[^0-9+]/', '', $fw_phone ) ); ?>"><?php echo esc_html( $fw_phone ); ?></a><?php endif; ?>
				<?php if ( $fw_email ) : ?><br><a href="mailto:<?php echo esc_attr( antispambot( $fw_email ) ); ?>"><?php echo esc_html( antispambot( $fw_email ) ); ?></a><?php endif; ?>
				<?php if ( $fw_insta ) : ?><br><a href="<?php echo esc_url( $fw_insta ); ?>" target="_blank" rel="noopener">Instagram</a><?php endif; ?>
			</p>
		</div>
	</div>
</section>
