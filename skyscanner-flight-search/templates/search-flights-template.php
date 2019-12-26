<?php
/*
 * Template Name: Search Flights
 */
get_header();

$sfs = new SFS();
$sfs_settings = $sfs->get_sfs_settings();
?>

<?php
if( isset($sfs_settings['service_available']) && $sfs_settings['service_available'] ){
?>
    <div 
        id="sfs-main" 
        data-api-key="<?php echo $sfs_settings['api_key']; ?>" 
        data-countries="<?php echo $sfs_settings['countries']; ?>" 
        data-service-available="<?php echo $sfs_settings['service_available']; ?>" 
        data-locale="<?php echo $sfs_settings['locale']; ?>" 
        data-currency="<?php echo $sfs_settings['currency']; ?>" 
        data-nonce="<?php echo wp_create_nonce("sfs_browse_routes_ajax_nonce"); ?>"
    >
    </div>
<?php
} else {
?>
    <div style="text-align:center;margin-top:50px;color:red;">Service currently unavailable</div>
<?php
}
?>

<?php get_footer(); ?>