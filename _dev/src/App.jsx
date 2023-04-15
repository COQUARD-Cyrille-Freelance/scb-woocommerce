import React, {useState, useEffect} from 'react';

const parseJSON = (response) => {
    if (response.status === 204 || response.status === 205) {
        return null;
    }
    return response.json();
}

/**
 * Checks if a network request came back fine, and throws an error if not
 *
 * @param  {object} response   A response from a network request
 *
 * @return {object|undefined} Returns either the response, or throws an error
 */
const checkStatus = (response) => {
    if (response.status >= 200 && response.status < 300) {
        return response;
    }

    const error = new Error(response.statusText);
    error.response = response;
    throw error;
}

function App({id = 0, ...props}) {
    const [state, setState] = useState({
        error: false,
        loading: false,
    });


    const fetchAPI = () => {
        fetch(scb_woocommerce_checkout_data.ajax_endpoint, {
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
                'Cache-Control': 'no-cache',
                Accept: 'application/json',
            },
            method: 'POST',
            body: new URLSearchParams({
                action: 'scb_woocommerce_check_payment',
                _wpnonce: scb_woocommerce_checkout_data.nonce
            })
        })
            .then(checkStatus)
            .then(parseJSON)
            .then(result => {
            if(result.status === "success") {
                const { data } = result;
               /* if(data.success)
                    window.location = `/module/scbpayment/confirm?orderId=${id}`;*/
            }
        });
    };

    useEffect(() => {
        const interval = setInterval(() => fetchAPI(), 15000);
        return () => {
                clearInterval(interval);
        };
    }, );

    return <div>
        <img src={`data:image/png;base64,${''}`}/>
    </div>;
}

export default App;
