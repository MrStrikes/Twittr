function json(response) {
    return response.json();
}

window.addEventListener("load", () => {

    var url = window.location.href;
    var id = new URL(url);
    var follower_id = document.querySelector('.session_id').value;
    var followed_id = id.searchParams.get("profile_id");

    document.querySelector('.unfollow-btn').addEventListener('click', (e) => {
        e.preventDefault();
        var url = '?action=unfollow';
        fetch(url, {
            method: 'post',
            headers: {
                "Content-type": "application/x-www-form-urlencoded; charset=UTF-8"
            },
            body: `follower_id=${follower_id}&followed_id=${followed_id}`,
            credentials: 'include'
        })
            .then(json)
            .then((data) => {
            console.log('Request succeeded with JSON response', data);
        })
        .catch((error) => {
            console.log('Request failed', error);
        });
    })
});