<?php
 /**
 * API Document URL
 * https://skyscanner.github.io/slate/
 */

if( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if( ! class_exists('SFS_API') ) :

class SFS_API {
    protected $api_key = '';

    /**
	 * The market/country your user is in (see docs for list of markets)
	 */
    protected $market_country = '';

    /**
	 * The locale you want the results in (ISO locale)
	 */
    protected $locale = 'en-US';

    /**
	 * The currency you want the prices in
	 */
    protected $currency = 'USD';

    /**
	 * The market countries that is Supported by skyscanner
	 */
    protected $countries = array();

    function __construct( $api_key = '', $market_country = '' ){
        $this->api_key = $api_key;
        $this->market_country = $market_country;
        // $this->api_key = 'c4e1dd0d7fmsh580c88899848ae1p18c980jsn711b53a73bf2';
    }

     /**
	 * Get the market countries that is Supported by skyscanner
	 */
    function get_countries(){
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://skyscanner-skyscanner-flight-search-v1.p.rapidapi.com/apiservices/reference/v1.0/countries/{$this->locale}",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => array(
                "x-rapidapi-host: skyscanner-skyscanner-flight-search-v1.p.rapidapi.com",
                "x-rapidapi-key: {$this->api_key}"
            ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            return failure( 'cURL Error #:' . $err );
        }

        $res = json_decode($response);

        if( isset($res->Countries) ){
            return $this->success($res->Countries);
        }

        return $this->failure($res->message);
    }

    /**
	 * Retrieve the cheapest routes from our cache prices
	 */
    function get_browseroutes($from, $to, $date){
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://skyscanner-skyscanner-flight-search-v1.p.rapidapi.com/apiservices/browseroutes/v1.0/{$this->market_country}/{$this->currency}/{$this->locale}/{$from}/{$to}/{$date}",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => array(
                "x-rapidapi-host: skyscanner-skyscanner-flight-search-v1.p.rapidapi.com",
                "x-rapidapi-key: {$this->api_key}"
            ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            return failure( 'cURL Error #:' . $err );
        }

        $res = json_decode($response);

        
        return $this->success($res);
    }

    function success( $data ){
        return array(
            'success' => true,
            'data' => $data
        );
    }

    function failure( $err ){
        return array(
            'success' => false,
            'err' => $err
        );
    }

    function get_locale(){
        return $this->locale;
    }

    function get_currency(){
        return $this->currency;
    }
}

endif; // class_exists check

?>