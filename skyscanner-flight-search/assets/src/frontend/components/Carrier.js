import React from 'react';

class Carrier extends React.Component {

    render() {
        const { Name } = this.props.info;

        return (
            <div>{ Name }</div>
        );
    }
}

export default Carrier;