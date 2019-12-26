import React from 'react';
import axios from 'axios';
import AsyncSelect from 'react-select/async';
import DatePicker from 'react-datepicker';
import { placesPromise, dateToYYYMMDD } from '../_services';
import FlightQuote from './FlightQuote';

import 'react-datepicker/dist/react-datepicker.css';

import { ApiConfigs } from '../_services/api.config';

const CustomOption = ({ innerProps, data }) => {
    return (
        <div className="sfs-place-item" {...innerProps}>
            <div className="sfs-place-name">{ data.place_name } ({ data.value.replace('-sky','') })</div>
            <div className="sfs-country-name">{ data.country_name }</div>
        </div>
    )
};

class Form extends React.Component {

	constructor(props) {
        super(props);

        this.state = {
            sfs_from : '',
            sfs_to : '',
            sfs_date : new Date(),
            sfs_result : [],
            sfs_loading: false,
            sfs_error: false,
            
        };
        
        this.handleDateChange = this.handleDateChange.bind(this);
        this.handleFromChange = this.handleFromChange.bind(this);
        this.handleToChange = this.handleToChange.bind(this);
        this.handleSubmit = this.handleSubmit.bind(this);
    }

    handleSubmit(e){
        e.preventDefault();

        let { sfs_from, sfs_to, sfs_date } = this.state;
        
        sfs_date = dateToYYYMMDD( sfs_date );

        if( sfs_from != '' && sfs_to != '' && sfs_date != '' ){

            this.setState({ 
                sfs_result : [],
                sfs_loading : true,
                sfs_error: false,
            });

            axios.request({
                url : ApiConfigs.wp_ajax_url,
                'method': 'POST',
                headers: { 'content-type': 'application/x-www-form-urlencoded' },
                data : `action=sfs_browse_routes_ajax_func&nonce=${ApiConfigs.wp_ajax_nounce}&sfs_from=${sfs_from}&sfs_to=${sfs_to}&sfs_date=${sfs_date}`
            })
                .then(res => {

                    if( res.data.success == true ){
                        this.setState({
                            sfs_result : res.data.data,
                            sfs_loading : false,
                            sfs_error: false,
                        });
                    } else {
                        this.setState({
                            sfs_result : [],
                            sfs_loading : false,
                            sfs_error: true,
                        });
                    }
                })
                .catch(err => {
                    this.setState({
                        sfs_result : [],
                        sfs_loading : false,
                        sfs_error: true,
                    });
                });
        } else {
            this.setState({
                sfs_result : [],
                sfs_loading : false,
                sfs_error: true,
            });
        }
	}
    
    handleDateChange(date) {
        this.setState({ sfs_date : date });
    }

    handleFromChange(e) {
        let value = e && e.value ? e.value : '';
        
        this.setState({ sfs_from: value });
    }

    handleToChange(e) {
        let value = e && e.value ? e.value : '';

        this.setState({ sfs_to: value });
    }

	render() {

        const { sfs_from, sfs_to, sfs_date, sfs_result, sfs_loading, sfs_error } = this.state;

		return (
            <>
                <div className="sfs-form">
                    <form onSubmit={ this.handleSubmit }>
                        <div className="sfs-form-group">
                            <div className="sfs-form-location">
                                <div className="sfs-form-label">
                                    <label htmlFor="">From</label>
                                </div>
                                <div className="sfs-form-input">
                                    <AsyncSelect
                                        isClearable
                                        onChange={ this.handleFromChange }
                                        loadOptions={ placesPromise }
                                        components={{ Option: CustomOption }}
                                    />
                                </div>
                            </div>
                            <div className="sfs-form-location">
                                <div className="sfs-form-label">
                                    <label htmlFor="">To</label>
                                </div>
                                <div className="sfs-form-input">
                                    <AsyncSelect
                                        isClearable
                                        onChange={ this.handleToChange }
                                        loadOptions={ placesPromise }
                                        components={{ Option: CustomOption }}
                                    />
                                </div>
                            </div>
                            <div className="sfs-form-depart">
                                <div className="sfs-form-label">
                                    <label htmlFor="">Depart</label>
                                </div>
                                <div className="sfs-form-input">
                                    <DatePicker
                                        selected={ sfs_date }
                                        onChange={ this.handleDateChange }
                                    />
                                </div>
                            </div>
                        </div>
                        <div className="sfs-form-submit-row">
                            <div className="sfs-form-input">
                                <button className="sfs-form-submit-btn">Search Flights</button>
                            </div>
                        </div>
                    </form>
                </div>
                { sfs_error &&
                    <div className="sfs-error">Something is wrong. Please check you input fields.</div>
                }
                { sfs_loading &&
                    <div className="sfs-ajax-spin"></div>
                }
                { sfs_result.Quotes && sfs_result.Quotes.length > 0 && 
                    sfs_result.Quotes.map((quote, i) => <FlightQuote key={i} quote={quote} places={sfs_result.Places} carriers={sfs_result.Carriers} /> )
                }
            </>
		);
	}
}

export default Form;