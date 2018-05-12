document.querySelector('button[name="new-twtt"]').addEventListener("click", sendTwtt);

function loadActions(domEl) {
    let action = document.querySelector(domEl);
    let url_string = window.location.href;
    let getUrl = new URL(url_string);
    let profile_id = getUrl.searchParams.get("profile_id");
    let value = action.getAttribute("value");
    let rating = action.getAttribute("id");
    let re_twtt_id = action.getAttribute("re-twtt-id");
    action.addEventListener('click', () => {
        let manageRating = `?action=manageRatings`;
        fetch(manageRating, {
            method: 'post',
            headers: {
                "Content-type": "application/x-www-form-urlencoded; charset=UTF-8"
            },
            body: `twtt_id=${value}&rating=${rating}&profile_id=${profile_id}&re_twtt_id=${re_twtt_id}`,
            credentials: 'include'
        })
            .then(json)
            .then((data) => {
                console.log('Request succeeded with JSON response', data);
            })
            .catch((error) => {
                console.log('Request failed', error);
            });
    });
}

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
            let tl = document.querySelector('.tl');
            for (let a in data) {
                console.log(data[a]);
                if ('re_twtt' === data[a]['type']) {
                    tl.prepend(newRtwtt(data[a]['twtt']['user_id']['at_username'], data[a]['twtt']['user_id']['username'], data[a]['twtt']['user_id']['id'], data[a]['twtt']['creation'], data[a]['twtt']['content'], data[a]['user_id']['id'], data[a]['user_id']['at_username'], data[a]['user_id']['username'], data[a]['twtt_id'], data[a]['re_twtt_id'], data[a]['rt'], data[a]['fav']));
                } else {
                    tl.prepend(newTwtt(data[a]['user_id']['at_username'], data[a]['user_id']['username'], data[a]['user_id']['id'], data[a]['creation'], data[a]['content'], data[a]['twtt_id'], data[a]['rt'], data[a]['fav']));
                }

                loadActions('.rt');
                loadActions('.star');
            }
        })
        .catch(function (error) {
            console.log('Request failed', error);
        });
}

getTl();