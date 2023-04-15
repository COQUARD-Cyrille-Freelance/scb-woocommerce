import App from "./App";
import React from 'react';
import ReactDOM from "react-dom";

jQuery(function () {
    const root = document.getElementById('scb_woocommerce_checkout');

    if(root){
        ReactDOM.render(
            <App />,
            root
        );
    }
});
