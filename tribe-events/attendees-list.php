<?php
/**
 * Renders the attendee list for an event
 *
 * Override this template in your own theme by creating a file at:
 *
 *     [your-theme]/tribe-events/attendees-list.php
 *
 * @version 4.3.5
 *
 */

$attendees = Tribe__Tickets__Tickets::get_event_attendees( $event->ID );

$full_list = array();

foreach($attendees as $attendee) {
	if ( isset( $attendee['optout'] ) && false !== $attendee['optout'] ) {
		continue;
	}

	// Skip folks who've RSVPed as "Not Going".
	if ( 'no' === $attendee['order_status'] ) {
		continue;
	}

	// Skip "Failed" orders
	if ( 'failed' === $attendee['order_status'] ) {
		continue;
	}

	$object = new stdClass();
	$object->name = $attendee['attendee_meta']['name']['value'];
	$object->company = $attendee['attendee_meta']['company']['value'];

	$full_list[] = $object;
}

?>
<div class='tribe-attendees-list-container'>
	<h2 class="tribe-attendees-list-title"><?php esc_html_e( 'Who\'s Attending', 'event-tickets-plus' ) ?></h2>
	<p><?php echo esc_html( sprintf( _n( 'One person is attending %2$s', '%d people are attending %s', $attendees_total, 'event-tickets-plus' ), $attendees_total, get_the_title( $event->ID ) ) ); ?></p>

	<div class="row">
	<?php foreach ( $full_list as $attendee ) { ?>
	  <?php
			if(empty($attendee->name)){
				continue;
			}
		?>

		<div class="col-sm-6">
			<div class="attendee">
				<h3 class="name">
					<?php echo $attendee->name; ?>
				</h3>

				<?php if(!empty($attendee->company)): ?>
					<h4 class="company">
						<i class="fas fa-briefcase"></i> <?php echo $attendee->company; ?></span>
					</h4>
				<?php endif ?>
			</div>
		</div>
	<?php } ?>
	</div>
</div>