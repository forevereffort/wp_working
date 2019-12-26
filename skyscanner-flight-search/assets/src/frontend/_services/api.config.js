let root_dom = document.getElementById('sfs-main');

export const ApiConfigs = {
    api_key: root_dom.getAttribute('data-api-key'),
    locale: root_dom.getAttribute('data-locale'),
    currency: root_dom.getAttribute('data-currency'),
    countries: root_dom.getAttribute('data-countries'),

    wp_ajax_nounce: root_dom.getAttribute('data-nonce'),
    wp_ajax_url : sfs_wp_ajax.ajax_url,
    wp_ajax_action : 'sfs_browse_routes_ajax_func',
};