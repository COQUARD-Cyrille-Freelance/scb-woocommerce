import React, {useState, useEffect} from 'react';

function App({id = 0, ...props}) {
    const [state, setState] = useState({
        error: false,
        loading: false,
    });

    const fetchAPI = (id) => {
        fetch(`/module/scbpayment/status?orderId=${id}`, {
            mode: 'cors',
            headers: {
                'Content-Type': 'application/json',
                'content-type': 'application/json',
                Accept: 'application/json',
            },
            method: 'POST',
        })
            .then(this.checkStatus)
            .then(this.parseJSON).then(result => {
            if(result.status === "success") {
                const { data } = result;
                if(data.success)
                    window.location = `/module/scbpayment/confirm?orderId=${id}`;
            }
        });
    };

    useEffect(() => {
        const interval = setInterval(() => fetchAPI(id), 15000);
        return () => {
                clearInterval(interval);
        };
    }, );

    return <div></div>;
}

export default App;
