<?php
/**
 * Invoice file list — shared between the request's first render and the upload/remove
 * AJAX responses (see Bis_Core_Lunch_Learn_Legacy::render_invoice_file_list()).
 *
 * @var int   $request_id
 * @var array $invoices_files
 * @var bool  $distributor_approved
 */
defined( 'ABSPATH' ) || exit;

if ( empty( $invoices_files ) ) {
	return;
}
?>
<table class="shop_table c-lunch-learn-my-account__files-table">
	<?php foreach ( $invoices_files as $invoice_file ) :
		if ( empty( $invoice_file['file'] ) ) {
			continue;
		}
		$url = wp_get_attachment_url( $invoice_file['file'] );
		?>
		<tr>
			<td><a href="<?php echo esc_url( $url ); ?>" target="_blank"><?php echo esc_html( basename( $url ) ); ?></a></td>
			<?php if ( ! $distributor_approved ) : ?>
				<td>
					<button type="button" class="c-lunch-learn-my-account__remove-file" data-role="remove-file" data-attachment-id="<?php echo (int) $invoice_file['file']; ?>" aria-label="<?php esc_attr_e( 'Remove file', 'parklex' ); ?>">&times;</button>
				</td>
			<?php endif; ?>
		</tr>
	<?php endforeach; ?>
</table>
