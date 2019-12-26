import axios from 'axios';

export const sfsService = {
    browseRoutes
};

function browseRoutes(){
    axios.request({
        url: `https://skyscanner-skyscanner-flight-search-v1.p.rapidapi.com/apiservices/browseroutes/v1.0/${ApiConfigs.countries}/${ApiConfigs.currency}/${ApiConfigs.locale}/${sfs_from}/${sfs_to}/${sfs_date}`,
        // url: `https://skyscanner-skyscanner-flight-search-v1.p.rapidapi.com/apiservices/browseroutes/v1.0/US/USD/en-US/SFO-sky/NYCA-sky/2019-12-28`,
        "method": "GET",
        "headers": {
            "x-rapidapi-host": "skyscanner-skyscanner-flight-search-v1.p.rapidapi.com",
            "x-rapidapi-key": `${ApiConfigs.api_key}`
        }
    })
    .then(res => {
        this.setState({
            sfs_result : res.data,
            sfs_loading : false,
            sfs_error: false,
        });
    })
    .catch(err => {
        this.setState({
            sfs_result : [],
            sfs_loading : false,
            sfs_error: true,
        });
    });
}