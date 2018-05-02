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
            let tl = document.querySelector('.tl-profile');
            for (let a in data){
                if ('twtt' === data[a]['type']){
                    tl.prepend(newTwtt(data[a]['author']['at_username'], data[a]['author']['username'], data[a]['author']['id'], data[a]['creation'], data[a]['content']));
                } else if ('retwtt' === data[a]['type']){
                    tl.prepend(newRtwtt(data[a]['author']['at_username'], data[a]['author']['username'], data[a]['author']['id'], data[a]['creation'], data[a]['content'], data[a]['rt/fav_author_id'], data[a]['author_rt']['at_username'], data[a]['author_rt']['username']));

                }
            }
    })
    .catch((error) => {
        console.log('Request failed', error);
    });
};