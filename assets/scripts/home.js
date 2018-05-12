document.querySelector('button[name="new-twtt"]').addEventListener("click", sendTwtt);

function sendTwtt() {
    var twtt = document.querySelector('textarea[name="new-twtt"]').value;
    if (0 !== twtt.length){
        fetch("?action=newTwtt", {
            method: 'post',
            headers: {
                "Content-type": "application/x-www-form-urlencoded; charset=UTF-8"
            },
            body: `content=+${JSON.stringify(twtt)}`,
            credentials: 'include'
        })
            .then(json)
            .then(function (data) {
                console.log('Request succeeded with JSON response', data);
            })
            .catch(function (error) {
                console.log('Request failed', error);
            });
    }
}

function json(response) {
    return response.json();
}

function getTl() {
    fetch("?action=getMainTl", {
        method: 'get',
        headers: {
            "Content-type": "application/x-www-form-urlencoded; charset=UTF-8"
        },
        credentials: 'include'
    })
        .then(json)
        .then(function (data) {
            console.log('Request succeeded with JSON response', data);
        })
        .catch(function (error) {
            console.log('Request failed', error);
        });
}

getTl();