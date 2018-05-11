function json(response) {
    return response.json();
}

/**
 * @param {domEl} domEl string
 */
function loadActions(domEl) {
    let action = document.querySelector(domEl);
    let url_string = window.location.href;
    let getUrl = new URL(url_string);
    let profile_id = getUrl.searchParams.get("profile_id");
    let value = action.getAttribute("value");
    let rating = action.getAttribute("id");
    action.addEventListener('click', () => {
        let manageRating = `?action=manageRatings`;
        fetch(manageRating, {
            method: 'post',
            headers: {
                "Content-type": "application/x-www-form-urlencoded; charset=UTF-8"
            },
            body: `twtt_id=${value}&rating=${rating}&profile_id=${profile_id}`,
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

window.onload = function () {
    let url_string = window.location.href;
    let url = new URL(url_string);
    let profile_id = url.searchParams.get("profile_id");
    fetch("?action=getTlProfile", {
        method: 'post',
        headers: {
            "Content-type": "application/x-www-form-urlencoded; charset=UTF-8"
        },
        body: `profile_id=${profile_id}`,
        credentials: 'include'
    })
        .then(json)
        .then((data) => {
            let tl = document.querySelector('.tl-profile');
            for (let a in data) {
                console.log(data[a]);
                if ('re_twtt' === data[a]['type']) {
                    tl.prepend(newRtwtt(data[a]['twtt']['user_id']['at_username'], data[a]['twtt']['user_id']['username'], data[a]['twtt']['user_id']['id'], data[a]['twtt']['creation'], data[a]['twtt']['content'], data[a]['user_id']['id'], data[a]['user_id']['at_username'], data[a]['user_id']['username'], data[a]['twtt_id']));
                } else {
                    tl.prepend(newTwtt(data[a]['user_id']['at_username'], data[a]['user_id']['username'], data[a]['user_id']['id'], data[a]['creation'], data[a]['content'], data[a]['twtt_id']));
                }

                loadActions('.rt');
                loadActions('.star');
            }
        })
        .catch((error) => {
            console.log('Request failed', error);
        });
};