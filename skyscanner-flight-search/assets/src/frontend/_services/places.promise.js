import axios from 'axios';
import { ApiConfigs } from './api.config';

export const placesPromise = inputValue =>
    new Promise(resolve => {
        axios.request({
            url: `https://skyscanner-skyscanner-flight-search-v1.p.rapidapi.com/apiservices/autosuggest/v1.0/${ApiConfigs.countries}/${ApiConfigs.currency}/${ApiConfigs.locale}/?query=${inputValue}`,
            method: "GET",
            headers: {
                "x-rapidapi-host":
                "skyscanner-skyscanner-flight-search-v1.p.rapidapi.com",
                "x-rapidapi-key": `${ApiConfigs.api_key}`
            }
        })
        .then(res => {
            resolve(
                res.data.Places.map(item => {
                    return {
                        value: item.PlaceId,
                        label: item.PlaceName,
                        place_name: item.PlaceName,
                        country_id: item.CountryId,
                        region_id: item.RegionId,
                        city_id: item.CityId,
                        country_name: item.CountryName,
                    };
                })
            );
        })
        .catch(err => console.log(err));
    });