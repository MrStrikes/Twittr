function json(response){
    return response.json();
}

window.onload = function () {
    var url_string = window.location.href;
    var url = new URL(url_string);
    var profile_id = url.searchParams.get("profile_id");
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
        console.log('Request succeeded with JSON response', data);
        alert('Welcome to Twittr');
    })
    .catch((error) => {
        console.log('Request failed', error);
    });
};