<?php

namespace Woo\Manual;

class Metabox {
	/**
	 * Assign variables use to get single value of book meta
	 */
	private $manual;

	/**
	 * __construct
	 *
	 * @return void
	 */
	public function __construct() {
		add_action( 'add_meta_boxes', [ $this, 'woo_register_menual_metabox' ] );
		add_action( 'save_post', [ $this, 'woo_save_product_manual' ] );
        add_action( 'post_edit_form_tag', [ $this, 'update_edit_form' ] );
	}

	/**
	 * woo_register_menual_metabox
	 * Create box heading
	 * @return void
	 */
	public function woo_register_menual_metabox() {
		add_meta_box( 'product-id', 'Upload manual', [ $this, 'woo_product_upload_manual' ], 'product', 'side', 'high' );
        add_meta_box( 'product-download-id', 'Download manual', [ $this, 'woo_download_section' ], 'product', 'Below', 'high' );
	}

	/**
	 * wd_br_book_details
	 * Create html form to take input
	 * @param  mixed $post
	 * @return void
	 */
	public function woo_product_upload_manual() {
        $this_file = '';
		wp_nonce_field( plugin_basename(__FILE__), 'wp_custom_attachment_nonce' );
        $html = '<p class="description">Upload your PDF here.</p>';
        $html .= '<input id="wp_custom_attachment" name="wp_custom_attachment" size="25" type="file" value="" />';

        $filearray = get_post_meta( get_the_ID(), 'wp_custom_attachment', true );
        if( ! empty ( $filearray['url'] ) )
            $this_file = $filearray['url'];
        
        if ( $this_file != '' ) { 
            $html .= '<div><p>Current file: ' . $this_file . '</p></div>'; 
        }
        echo $html; 
	}
    public function woo_download_section() {
        echo "Download";
    }

	/**
	 * woo_save_product_manual
	 * Save meta info to database
	 * @param  mixed $post_id
	 * @return void
	 */
	public function woo_save_product_manual( $id ) {
        if ( ! empty( $_FILES['wp_custom_attachment']['name'] ) ) {
            $supported_types = array( 'application/pdf' );
            $arr_file_type = wp_check_filetype( basename( $_FILES['wp_custom_attachment']['name'] ) );
            $uploaded_type = $arr_file_type['type'];
    
            if ( in_array( $uploaded_type, $supported_types ) ) {
                $upload = wp_upload_bits($_FILES['wp_custom_attachment']['name'], null, file_get_contents($_FILES['wp_custom_attachment']['tmp_name']));
                if ( isset( $upload['error'] ) && $upload['error'] != 0 ) {
                    wp_die( 'There was an error uploading your file. The error is: ' . $upload['error'] );
                } else {
                    add_post_meta( $id, 'wp_custom_attachment', $upload );
                    update_post_meta( $id, 'wp_custom_attachment', $upload );
                }
            }
            else {
                wp_die( "The file type that you've uploaded is not a PDF." );
            }
        }
	}

    public function update_edit_form() {
        echo ' enctype="multipart/form-data"';
    }
}
