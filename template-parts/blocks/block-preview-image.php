<?php
$preview_image_url = '';
$block = $params['block'];
$block_class_name = $block['className'];

if ( isset( $block['styles'] ) && isset( $block_class_name ) ) {
    $block_styles = $block['styles'];

    foreach ( $block_styles as $i => $block_style ) :
        $block_style_name = $block_style['name'];
        if ( str_contains( $block_class_name, 'is-style-' . $block_style_name ) ) :
            $preview_image_url = $block['data']['block_preview_images'][$i];
            break;
        endif;
    endforeach;
} else {
    $preview_image_url = $block['data']['block_preview_images'][0];
}

?>

<div class="block-preview-image-wrap" style="margin: -35px 0 0; display: flex; align-items: center; justify-content: center;">
    <img src="<?= esc_url( $preview_image_url ) ?>" style="width: 100%; height: auto; display: block; margin: auto;">
</div>