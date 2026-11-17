<?php
/**
 * Private dining enquiries: form shortcode, submission handler, email and
 * a copy of every enquiry saved under Dashboard → Enquiries.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/** Saved copies of enquiries (admin only, never public). */
add_action(
	'init',
	function () {
		register_post_type(
			'fw_enquiry',
			array(
				'labels'          => array(
					'name'          => 'Enquiries',
					'singular_name' => 'Enquiry',
					'menu_name'     => 'Enquiries',
					'all_items'     => 'All enquiries',
					'edit_item'     => 'Enquiry',
				),
				'public'          => false,
				'show_ui'         => true,
				'show_in_menu'    => true,
				'show_in_rest'    => false,
				'menu_icon'       => 'dashicons-email-alt',
				'menu_position'   => 25,
				'supports'        => array( 'title', 'editor' ),
				'capability_type' => 'post',
				'capabilities'    => array( 'create_posts' => 'do_not_allow' ),
				'map_meta_cap'    => true,
			)
		);
	}
);

function fw_enquiry_occasions() {
	return array( 'Birthday', 'Anniversary', 'Engagement', 'Corporate event', 'Celebration', 'Other' );
}

/** The form. Use [fishworks_enquiry] on any page. */
function fw_enquiry_form() {
	wp_enqueue_script( 'fw-enquiry' );
	$today = wp_date( 'Y-m-d' );
	ob_start();
	?>
	<form class="enquiry" id="enquire" method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" data-ajax="<?php echo esc_url( admin_url( 'admin-ajax.php' ) ); ?>" novalidate>
		<input type="hidden" name="action" value="fw_enquiry">
		<input type="hidden" name="fw_ts" value="<?php echo esc_attr( time() ); ?>">
		<input type="hidden" name="fw_back" value="<?php echo esc_url( get_permalink() ); ?>">
		<div class="hp" aria-hidden="true"><label>Leave empty <input type="text" name="fw_website" tabindex="-1" autocomplete="off"></label></div>

		<fieldset>
			<legend>Guest details</legend>
			<div class="row">
				<label class="full">Full name <span class="req">*</span>
					<input type="text" name="fw_name" autocomplete="name" required maxlength="120">
				</label>
			</div>
			<div class="row two">
				<label>Phone number <span class="req">*</span>
					<input type="tel" name="fw_phone" autocomplete="tel" required maxlength="40">
				</label>
				<label>Email <span class="req">*</span>
					<input type="email" name="fw_email" autocomplete="email" required maxlength="160">
				</label>
			</div>
		</fieldset>

		<fieldset>
			<legend>Booking information</legend>
			<div class="row three">
				<label>Date <span class="req">*</span>
					<input type="date" name="fw_date" min="<?php echo esc_attr( $today ); ?>" required>
				</label>
				<label>Time <span class="req">*</span>
					<input type="time" name="fw_time" step="900" required>
				</label>
				<label>Group size <span class="req">*</span>
					<input type="number" name="fw_guests" min="1" max="500" inputmode="numeric" required>
				</label>
			</div>
			<div class="row">
				<label class="full">Occasion <span class="opt">(optional)</span>
					<select name="fw_occasion">
						<option value="">Select an occasion</option>
						<?php foreach ( fw_enquiry_occasions() as $o ) : ?>
							<option><?php echo esc_html( $o ); ?></option>
						<?php endforeach; ?>
					</select>
				</label>
			</div>
			<div class="row">
				<label class="full">Message <span class="opt">(optional)</span>
					<textarea name="fw_message" rows="5" maxlength="3000" placeholder="Dietary requirements, menus, anything we should know"></textarea>
				</label>
			</div>
		</fieldset>

		<p class="form-error" role="alert" hidden></p>
		<button type="submit" class="btn btn-green">Send enquiry</button>
	</form>

	<div class="fw-modal" id="fw-sent" role="dialog" aria-modal="true" aria-labelledby="fw-sent-title" hidden>
		<div class="fw-modal-box">
			<svg class="tick" viewBox="0 0 52 52" aria-hidden="true"><circle cx="26" cy="26" r="24" fill="none"/><path fill="none" d="M15 27l7 7 15-16"/></svg>
			<h2 id="fw-sent-title" class="serif">Thank you</h2>
			<p><?php echo esc_html( fw_mod( 'fw_enquiry_thanks' ) ); ?></p>
			<button type="button" class="btn" data-close>Close</button>
		</div>
	</div>
	<?php
	return ob_get_clean();
}
add_shortcode( 'fishworks_enquiry', 'fw_enquiry_form' );

/** Validate a submission. Returns array( data, error ). */
function fw_enquiry_validate( $src ) {
	$v = function ( $k ) use ( $src ) {
		return isset( $src[ $k ] ) ? trim( wp_unslash( $src[ $k ] ) ) : '';
	};

	// Spam traps: honeypot filled, or sent faster than a person could type.
	if ( '' !== $v( 'fw_website' ) || ( time() - (int) $v( 'fw_ts' ) ) < 3 ) {
		return array( null, 'spam' );
	}

	$d = array(
		'name'     => sanitize_text_field( $v( 'fw_name' ) ),
		'phone'    => sanitize_text_field( $v( 'fw_phone' ) ),
		'email'    => sanitize_email( $v( 'fw_email' ) ),
		'date'     => sanitize_text_field( $v( 'fw_date' ) ),
		'time'     => sanitize_text_field( $v( 'fw_time' ) ),
		'guests'   => absint( $v( 'fw_guests' ) ),
		'occasion' => sanitize_text_field( $v( 'fw_occasion' ) ),
		'message'  => sanitize_textarea_field( $v( 'fw_message' ) ),
	);

	if ( '' === $d['name'] || '' === $d['phone'] || '' === $d['date'] || '' === $d['time'] || $d['guests'] < 1 ) {
		return array( null, 'Please fill in all the required fields.' );
	}
	if ( ! is_email( $d['email'] ) ) {
		return array( null, 'Please enter a valid email address.' );
	}
	if ( ! preg_match( '/^\d{4}-\d{2}-\d{2}$/', $d['date'] ) || ! preg_match( '/^\d{2}:\d{2}/', $d['time'] ) ) {
		return array( null, 'Please choose a valid date and time.' );
	}
	if ( $d['occasion'] && ! in_array( $d['occasion'], fw_enquiry_occasions(), true ) ) {
		$d['occasion'] = '';
	}
	return array( $d, null );
}

/** Save a copy and email the team. Returns true if either worked. */
function fw_enquiry_send( $d ) {
	$date_nice = wp_date( 'l j F Y', strtotime( $d['date'] . ' 12:00' ) );
	$time_nice = gmdate( 'g:ia', strtotime( '2000-01-01 ' . $d['time'] ) );

	$lines = array(
		'GUEST DETAILS',
		'Name:       ' . $d['name'],
		'Phone:      ' . $d['phone'],
		'Email:      ' . $d['email'],
		'',
		'BOOKING INFORMATION',
		'Date:       ' . $date_nice,
		'Time:       ' . $time_nice,
		'Group size: ' . $d['guests'],
		'Occasion:   ' . ( $d['occasion'] ? $d['occasion'] : '-' ),
		'',
		'MESSAGE',
		$d['message'] ? $d['message'] : '-',
		'',
		'—',
		'Sent from the private dining form at ' . home_url( '/' ),
		'Reply to this email to respond to the guest directly.',
	);
	$body    = implode( "\n", $lines );
	$subject = sprintf( 'Private dining enquiry: %s, %d guests, %s', $d['name'], $d['guests'], $date_nice );

	$saved = wp_insert_post(
		array(
			'post_type'    => 'fw_enquiry',
			'post_status'  => 'private',
			'post_title'   => $subject,
			'post_content' => $body,
		)
	);

	$to      = fw_mod( 'fw_enquiry_email' );
	$headers = array( 'Reply-To: ' . $d['name'] . ' <' . $d['email'] . '>' );
	$sent    = wp_mail( $to, $subject, $body, $headers );

	if ( $saved && ! is_wp_error( $saved ) ) {
		update_post_meta( $saved, '_fw_emailed', $sent ? 'yes' : 'no' );
	}
	return $sent || ( $saved && ! is_wp_error( $saved ) );
}

/** Basic flood protection: max 5 enquiries per hour from one address. */
function fw_enquiry_rate_ok() {
	$ip  = isset( $_SERVER['REMOTE_ADDR'] ) ? sanitize_text_field( wp_unslash( $_SERVER['REMOTE_ADDR'] ) ) : 'x';
	$key = 'fw_enq_' . md5( $ip );
	$n   = (int) get_transient( $key );
	if ( $n >= 5 ) {
		return false;
	}
	set_transient( $key, $n + 1, HOUR_IN_SECONDS );
	return true;
}

function fw_enquiry_process( $src ) {
	list( $d, $err ) = fw_enquiry_validate( $src );
	if ( 'spam' === $err ) {
		return array( true, '' ); // Pretend success to bots.
	}
	if ( $err ) {
		return array( false, $err );
	}
	if ( ! fw_enquiry_rate_ok() ) {
		return array( false, 'Too many enquiries from this connection. Please call us instead.' );
	}
	if ( ! fw_enquiry_send( $d ) ) {
		return array( false, 'Sorry, something went wrong sending your enquiry. Please try again or email us.' );
	}
	return array( true, '' );
}

/* JavaScript submission (admin-ajax). */
function fw_enquiry_ajax() {
	list( $ok, $msg ) = fw_enquiry_process( $_POST ); // phpcs:ignore WordPress.Security.NonceVerification
	if ( $ok ) {
		wp_send_json_success();
	}
	wp_send_json_error( array( 'message' => $msg ), 400 );
}
add_action( 'wp_ajax_fw_enquiry', 'fw_enquiry_ajax' );
add_action( 'wp_ajax_nopriv_fw_enquiry', 'fw_enquiry_ajax' );

/* No-JavaScript fallback (admin-post). */
function fw_enquiry_post() {
	list( $ok, $msg ) = fw_enquiry_process( $_POST ); // phpcs:ignore WordPress.Security.NonceVerification
	$back = isset( $_POST['fw_back'] ) ? wp_validate_redirect( esc_url_raw( wp_unslash( $_POST['fw_back'] ) ), home_url( '/' ) ) : home_url( '/' ); // phpcs:ignore
	$back = add_query_arg( $ok ? array( 'enquiry' => 'sent' ) : array( 'enquiry' => 'error' ), $back );
	wp_safe_redirect( $back . '#enquire' );
	exit;
}
add_action( 'admin_post_fw_enquiry', 'fw_enquiry_post' );
add_action( 'admin_post_nopriv_fw_enquiry', 'fw_enquiry_post' );

/** Show whether the email went out in the Enquiries list. */
add_filter(
	'manage_fw_enquiry_posts_columns',
	function ( $cols ) {
		$cols['fw_emailed'] = 'Emailed';
		return $cols;
	}
);
add_action(
	'manage_fw_enquiry_posts_custom_column',
	function ( $col, $id ) {
		if ( 'fw_emailed' === $col ) {
			echo 'yes' === get_post_meta( $id, '_fw_emailed', true ) ? 'Yes' : 'No (check email setup)';
		}
	},
	10,
	2
);
