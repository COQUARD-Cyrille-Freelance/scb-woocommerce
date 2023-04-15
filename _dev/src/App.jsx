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

const fetchAPI = (action, success = (result) => result) => {
    fetch(scb_woocommerce_checkout_data.ajax_endpoint, {
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
            'Cache-Control': 'no-cache',
            Accept: 'application/json',
        },
        method: 'POST',
        body: new URLSearchParams({
            action,
            _wpnonce: scb_woocommerce_checkout_data.nonce
        })
    })
        .then(checkStatus)
        .then(parseJSON)
        .then(success);
};

function App(props) {
    const [loading, setLoading] = useState(true);
    const [succeed, setSucceed] = useState(false);
    const [message, setMessage] = useState('');
    const [qrCode, setQrCode] = useState('');


    useEffect(() => {
        fetchAPI('scb_woocommerce_get_qr_code', result => {
            if(result.success) {
                setQrCode(result.image);
                setLoading(false);
            }
        })
        const interval = setInterval(() => fetchAPI('scb_woocommerce_check_payment', result => {
            if(result.success) {
                setSucceed(true);
                setMessage(result.message)
            }
        }), 15000);
        return () => {
                clearInterval(interval);
        };
    }, );

    return <div>
        {loading && <p>The QR code is loading</p>}
        {! loading && <img src={`data:image/png;base64,${qrCode}`}/>}
        {succeed && <p>{message}</p>}
    </div>;
}

export default App;
