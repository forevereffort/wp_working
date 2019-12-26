import React from 'react';
import Carrier from './Carrier';
import { dateToYYYMMDD } from '../_services';

class FlightQuote extends React.Component {

	// constructor() {
	// 	super();
	// }

	render() {
        let { MinPrice } = this.props.quote;
        let { CarrierIds, OriginId, DestinationId, DepartureDate } = this.props.quote.OutboundLeg;
        let { places, carriers } = this.props;
        
        let originplace = places.filter( place => place.PlaceId == OriginId ? true : false );
        let destinationplace = places.filter( place => place.PlaceId == DestinationId ? true : false );
        let available_carriers = carriers.filter( carrier => CarrierIds.some(item => item === carrier.CarrierId ) );

		return (
			<div className="sfs-flight-quote">
                <div className="sfs-flight-info">
                    <div className="sfs-flight-carriers">
                        { available_carriers &&
                            available_carriers.map((carrier, i) => <Carrier key={i} info={carrier} />)
                        }
                    </div>
                    <div className="sfs-flight-place">
                        <span>{originplace[0].SkyscannerCode}</span>
                        <span className="sfs-flight-place-line"></span>
                        <span>{destinationplace[0].SkyscannerCode}</span>
                    </div>
                    <div className="sfs-flight-departure-date">{dateToYYYMMDD( DepartureDate )}</div>
                </div>
                <div className="sfs-flight-price">
                    <div>${MinPrice}</div>
                </div>
			</div>
		);
	}
}

export default FlightQuote;