<?php
/**
 * Admin View: Page - Settings.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

?>
<div class="wrap">
    <?php settings_errors(); ?>

    <h2><?php echo __( 'OVISTO WooCommerce Plugin - Settings' ); ?></h2>


	<?php
	$active_tab = 'general';
	if( isset( $_GET[ 'tab' ] ) ) {
		$active_tab = $_GET[ 'tab' ];
	} // end if
	?>

    <h2 class="nav-tab-wrapper">
        <a href="?page=ovisto_settings&tab=general" class="nav-tab <?php echo $active_tab == 'general' ? 'nav-tab-active' : ''; ?>"><?php _e("General"); ?></a>
        <a href="?page=ovisto_settings&tab=display" class="nav-tab <?php echo $active_tab == 'display' ? 'nav-tab-active' : ''; ?>"><?php _e("Display Options"); ?></a>
        <a href="?page=ovisto_settings&tab=contact" class="nav-tab <?php echo $active_tab == 'contact' ? 'nav-tab-active' : ''; ?>"><?php _e("Contact"); ?></a>
    </h2>


	<?php self::show_messages(); ?>

	<form name="settings_form" method="post" action="<?php echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI'] ); ?>">
		<?php if( $active_tab == 'general' ) { ?>

        <?php echo "<h3>" . __( 'General Settings' ) . "</h3>"; ?>

		<input type="hidden" name="ovisto_banner_module_settings_hidden" value="Y">
		<table class="form-table">
			<tr>
				<th scope="row"><?php _e( "Enable Extension: " ); ?></th>
				<td>
					<select name="ovisto_active">
						<option <?php if ( $settings['ovisto_active'] == 1 ): ?> selected="selected" <?php endif; ?> value="1">
							Yes
						</option>
						<option <?php if ( $settings['ovisto_active'] == 0 ): ?> selected="selected" <?php endif; ?> value="0">
							No
						</option>
					</select>
				</td>
			</tr>
			<tr>
				<th scope="row"><?php _e( "Partner ID: " ); ?></th>
				<td>
					<input class="regular-text" type="text" name="ovisto_partner_id"
					       value="<?php echo $settings['ovisto_partner_id']; ?>" maxlength="8">
					<p><?php _e( "If you do not have a partner ID to enter here, please "); ?><a href="?page=ovisto_settings&tab=contact">contact</a> <?php _e( " your OVISTO account manager." ); ?></p>
				</td>
			</tr>
			<tr>
				<th scope="row"><?php _e( "Your Country: " ); ?></th>
				<td>
					<select name="ovisto_country">
						<option <?php if ( $settings['ovisto_country'] == 'de' ): ?> selected="selected" <?php endif; ?>
							value="de">Germany
						</option>
						<option <?php if ( $settings['ovisto_country'] == 'au' ): ?> selected="selected" <?php endif; ?>
							value="au">Australia
						</option>
					</select>
				</td>
			</tr>
			<tr>
				<th scope="row"><?php _e( "Target div id: " ); ?></th>
				<td>
					<input class="regular-text" type="text" name="ovisto_banner_target"
					       value="<?php echo $settings['ovisto_banner_target']; ?>" size="20">
					<p><?php _e( "Optional: Here, you may declare a target-div to display the banner in. If left blank,
                                the banner will be placed inside the default div with layout options below." ); ?></p>
				</td>
			</tr>
			<tr>
				<th scope="row"><?php _e( "Hide banner: " ); ?></th>
				<td>
					<select name="ovisto_banner_hide">
						<option <?php if ( $settings['ovisto_banner_hide'] == 1 ): ?> selected="selected" <?php endif; ?>
							value="1">Yes
						</option>
						<option <?php if ( $settings['ovisto_banner_hide'] == 0 ): ?> selected="selected" <?php endif; ?>
							value="0">No
						</option>
					</select>
					<p><?php _e( "This allows to hide the banner from your storefront, while still having the tracking enabled." ); ?></p>
				</td>
			</tr>
		</table>

		<?php } elseif( $active_tab == 'display' ) { ?>

		<?php echo "<h3>" . __( 'Banner Layout' ) . "</h3>"; ?>
		<table class="form-table">
			<tr>
				<th scope="row"><?php _e( "Banner heading: " ); ?></th>
				<td>
					<input class="regular-text" type="text" name="ovisto_banner_heading"
					       value="<?php echo $settings['ovisto_banner_heading']; ?>" size="20">
					<p><?php _e( " Heading for the default banner div." ); ?></p>
				</td>
			</tr>
			<tr>
				<th scope="row"><?php _e( "Banner heading - CSS: " ); ?></th>
				<td>
					<input class="regular-text" type="text" name="ovisto_banner_heading_css"
					       value="<?php echo $settings['ovisto_banner_heading_css']; ?>" size="20">
					<p><?php _e( "Enter \"display: none;\" to hide the heading." ); ?></p>
				</td>
			</tr>
			<tr>
				<th scope="row"><?php _e( "Banner div - CSS: " ); ?></th>
				<td>
					<input class="regular-text" type="text" name="ovisto_banner_css"
					       value="<?php echo $settings['ovisto_banner_css']; ?>" size="20">
					<p><?php _e( "CSS for the default div." ); ?></p>
				</td>
			</tr>
		</table>

		<?php } elseif( $active_tab == 'contact' ) { ?>

		<?php echo "<h3>" . __( 'Contact' ) . "</h3>"; ?>
        <table class="form-table">
            <tr>
                <th scope="row"><?php _e( "Fullname: " ); ?></th>
                <td>
                    <input class="regular-text" type="text" name="ovisto_contact_fullname"
                           value="<?php echo $settings['ovisto_contact_fullname']; ?>" size="20">
                </td>
            </tr>
            <tr>
                <th scope="row"><?php _e( "Email: " ); ?></th>
                <td>
                    <input class="regular-text" type="text" name="ovisto_contact_email"
                           value="<?php echo $settings['ovisto_contact_email']; ?>" size="20">
                </td>
            </tr>
            <tr>
                <th scope="row"><?php _e( "Shop URL: " ); ?></th>
                <td>
                    <input class="regular-text" type="text" name="ovisto_contact_shop_url"
                           value="<?php echo $settings['ovisto_contact_shop_url']; ?>" size="20">
                </td>
            </tr>
            <tr>
                <th scope="row"><?php _e( "Company: " ); ?></th>
                <td>
                    <input class="regular-text" type="text" name="ovisto_contact_company"
                           value="<?php echo $settings['ovisto_contact_company']; ?>" size="20">
                </td>
            </tr>

            <tr>
                <th scope="row"><?php _e( "Telephone: " ); ?></th>
                <td>
                    <input class="regular-text" type="text" name="ovisto_contact_tel"
                           value="<?php echo $settings['ovisto_contact_tel']; ?>" size="20">
                </td>
            </tr>
            <tr>
                <th scope="row"><?php _e( "Message: " ); ?></th>
                <td>
                    <textarea style="width:350px; height: 75px;" class="" name="ovisto_contact_message" placeholder="Message"><?php echo $settings['ovisto_contact_message']; ?></textarea>
                </td>
            </tr>
        </table>

		<?php } ?>

		<?php wp_nonce_field('ovisto_settings_updated') ?>

        <p class="submit">
            <input class="button button-primary" type="submit" name="Submit"
                   value="<?php
                   if( $active_tab == 'contact' ) {
                       _e( 'Send mail to Ovisto', 'ovisto_send' );
                   } else {
                       _e( 'Save Changes', 'ovisto_trdom' );
                   } ?>"/>
        </p>

        </form>
</div>
