<div class="wrap">
    <h1>Skyscanner Flight Search History</h1>
    <table class="wp-list-table widefat fixed striped posts">
        <thead>
            <tr>
                <td>Origin Place</td>
                <td>Destination Place</td>
                <td>Out Bound Partialdate</td>
            </tr>
        </thead>
        <tbody>
<?php
    $sfs_search_params = get_option( 'sfs_search_params' );

    foreach($sfs_search_params as $item){
?>
            <tr>
                <td><?php echo $item['sfs_from']; ?></td>
                <td><?php echo $item['sfs_to']; ?></td>
                <td><?php echo $item['sfs_date']; ?></td>
            </tr>
<?php
    }
?>
        </tbody>
    </table>
</div>