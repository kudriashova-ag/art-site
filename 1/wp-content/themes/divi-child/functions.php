<?php
function enqueue_child_theme_styles() {
    wp_enqueue_style( 'parent-style', get_template_directory_uri() . '/style.css' );
    wp_enqueue_style( 'child-style', get_stylesheet_directory_uri() . '/style.css', array('parent-style'));
}
add_action( 'wp_enqueue_scripts', 'enqueue_child_theme_styles' );


function polylang_shortcode() {
	ob_start();
	pll_the_languages(array('show_flags'=>0,'show_names'=>1));
	$flags = ob_get_clean();
	return $flags;
}
add_shortcode( 'polylang', 'polylang_shortcode' );

add_action( 'wpcf7_before_send_mail', 'created_post_in_cf7' );
function created_post_in_cf7( $contact_form ) {

	$title   = $_POST['text-title-post'] && ! empty( $_POST['text-title-post'] ) ? sanitize_text_field( $_POST['text-title-post'] ) : '';
	$content = $_POST['text-contant-post'] && ! empty( $_POST['text-contant-post'] ) ? wp_strip_all_tags( $_POST['text-contant-post'] ) : '';

	$ars = [
		'post_type'    => 'post',
		'post_title'   => $title,
		'post_content' => $content,
		'post_status'  => 'pending',
		// 'meta_input'   => [
		// 	'text_meta_field' => $field,
		// ],
	];

	$post_id = wp_insert_post( $ars );

	$mail = $contact_form->prop('mail');

	if ( false !== $post_id ) {
		$mail['subject'] = $mail['subject'] .' Создана запись №'. $post_id;
	}

	$contact_form->set_properties(['mail' => $mail ]);
}




/* function process_divi_form() {
    if( isset( $_POST['submit_form'] ) ) {
        $post_title = sanitize_text_field( $_POST['post_title'] );
        $post_content = wp_kses_post( $_POST['post_content']);

        // Дополнительные поля, категории и т. д.

        $new_post = array(
            'post_title'   => $post_title,
            'post_content' => $post_content,
            'post_status'  => 'publish',
            'post_type'    => 'post', // Тип записи, например, 'post' или 'page'
        );

        // Создание записи
        $post_id = wp_insert_post( $new_post );

        if ( ! is_wp_error( $post_id ) ) {
            // Запись успешно создана, выполните дополнительные действия, если необходимо
        } else {
            // Обработка ошибок при создании записи
        }
    }
}

// Хук для обработки формы при отправке
add_action( 'init', 'process_divi_form' ); */
