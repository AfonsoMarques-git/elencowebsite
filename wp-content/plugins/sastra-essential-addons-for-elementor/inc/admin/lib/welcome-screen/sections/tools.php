<?php 

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

?>
<div class="spexo-addons_page_tmpcoder-tools-page">
    <div class="row">
        <div class="col-6">
            <div class="tmpcoderjs-clear-cache tmpcoder-clear-all-cache tmpcoder-section-info-wrap common-box-shadow tools-btn">
                <div class="tmpcoder-section-info">
                    <h4 style="margin-bottom: 15px !important;"><?php esc_html_e('Regenerate Asset Cache','sastra-essential-addons-for-elementor') ?></h4>
                    <p><?php esc_html_e('The asset for Spexo Addons are stored in the Uploads folder. Click to remove all previously generated files.','sastra-essential-addons-for-elementor') ?>
                    </p>
                </div>
                <a href="#" class="tmpcoder-btn tmpcoder-btn-enable active"><?php esc_html_e('Regenerate Cache','sastra-essential-addons-for-elementor') ?></a>
                
                <div class="tmpcoder-css-regenerated tmpcoder-settings-saved">
                    <span><?php esc_html_e('Assets Regenerated', 'sastra-essential-addons-for-elementor'); ?></span>
                    <span class="dashicons dashicons-smiley"></span>
                </div>
            </div>
        </div>
        <div class="col-6">
        <?php 

        // Include the popup HTML for Apply Global Fonts
        if ( function_exists( 'tmpcoder_add_global_option' ) ) {
            tmpcoder_add_global_option(1);
        }
        ?>
        </div>
    </div>

    <div class="welcome-backend-loader">
        <img src="<?php echo esc_url(TMPCODER_ADDONS_ASSETS_URL.'images/backend-loader.gif'); ?>" alt="" width="80" height="80" />
    </div>
</div>