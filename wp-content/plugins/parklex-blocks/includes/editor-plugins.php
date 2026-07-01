<?php
defined( 'ABSPATH' ) || exit;

class Bis_Blocks_Editor_Plugins {

	function __construct() {
		add_action( 'enqueue_block_editor_assets', [ $this, 'enqueue_editor_script' ] );
	}

	function enqueue_editor_script() {
		$asset_file = BIS_BLOCKS_DIR . 'build/index.asset.php';
		if ( ! file_exists( $asset_file ) ) {
			return;
		}
		$asset = require $asset_file;
		wp_enqueue_script(
			'bis-blocks-editor-plugins',
			BIS_BLOCKS_URL . 'build/index.js',
			$asset['dependencies'],
			$asset['version'],
			true
		);
	}
}

new Bis_Blocks_Editor_Plugins();
