<?php
/**
 * Lunch & Learn requests — My Account list (woocommerce_account_lunch-learn_endpoint).
 *
 * @var int[] $request_ids
 */
defined( 'ABSPATH' ) || exit;

$submit_page_id = bis_theme_get_page_by_template( Bis_Core_Lunch_Learn::SUBMIT_PAGE_TEMPLATE );
?>

<div class="c-lunch-learn-my-account">
	<?php if ( $intro = get_field( 'text_lunch_learn', 'option' ) ) : ?>
		<div class="c-lunch-learn-my-account__intro"><?php echo wp_kses_post( $intro ); ?></div>
	<?php endif; ?>

	<?php if ( $submit_page_id ) : ?>
		<div class="c-lunch-learn-my-account__new">
			<a class="btn btn-dark" href="<?php echo esc_url( get_permalink( $submit_page_id ) ); ?>"><?php esc_html_e( 'New request', 'parklex' ); ?></a>
		</div>
	<?php endif; ?>

	<table class="woocommerce-orders-table shop_table shop_table_responsive c-lunch-learn-my-account__table">
		<thead>
			<tr>
				<th><?php esc_html_e( 'Request name', 'parklex' ); ?></th>
				<th><?php esc_html_e( 'Request ID', 'parklex' ); ?></th>
				<th><?php esc_html_e( 'Architectural / Interior Firm', 'parklex' ); ?></th>
				<th><?php esc_html_e( 'Status', 'parklex' ); ?></th>
				<th>&nbsp;</th>
			</tr>
		</thead>
		<tbody>
			<?php if ( empty( $request_ids ) ) : ?>
				<tr>
					<td colspan="5"><?php esc_html_e( 'There are no requests in your account at this time.', 'parklex' ); ?></td>
				</tr>
			<?php else : ?>
				<?php foreach ( $request_ids as $request_id ) :
					$approved  = get_field( 'distributor_approved', $request_id );
					$link      = add_query_arg( array( 'lrequest-id' => $request_id ), wc_get_endpoint_url( 'lunch-learn', '', wc_get_page_permalink( 'myaccount' ) ) );
					?>
					<tr>
						<td><?php echo esc_html( get_the_title( $request_id ) ); ?></td>
						<td>#<?php echo (int) $request_id; ?></td>
						<td><?php echo esc_html( get_field( 'design-firm-request', $request_id ) ); ?></td>
						<td><?php echo $approved ? esc_html__( 'Sent', 'parklex' ) : esc_html__( 'Pending', 'parklex' ); ?></td>
						<td><a class="btn btn-dark" href="<?php echo esc_url( $link ); ?>"><?php echo $approved ? esc_html__( 'View', 'parklex' ) : esc_html__( 'Edit', 'parklex' ); ?></a></td>
					</tr>
				<?php endforeach; ?>
			<?php endif; ?>
		</tbody>
	</table>
</div>
