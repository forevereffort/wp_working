import React from 'react';
import ReactDOM from 'react-dom';

import App from './App';

import WebFont from 'webfontloader';
import './assets/scss/style.scss';

WebFont.load({
    google: {
        families: ['Roboto:300,400,500', 'sans-serif']
    }
});

ReactDOM.render(<App /> , document.getElementById('sfs-main'));