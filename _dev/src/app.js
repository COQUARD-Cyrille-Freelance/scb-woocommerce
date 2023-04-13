import App from "./App";
import React from 'react';
import ReactDOM from "react-dom";

jQuery(function () {
    const root = document.getElementById('app');

    if(root){
        ReactDOM.render(
            <App />,
            root
        );
    }
});
