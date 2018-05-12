function json(response) {
    return response.json();
}

window.addEventListener('load', () => {
    let searchUser = document.forms.searchUser;

    searchUser.addEventListener('submit', (ev) => {
        ev.preventDefault();
        let username = searchUser.elements.username;
        let url = `?action=searchUser`;
        fetch(url, {
            method: 'post',
            headers: {
            "Content-type": "application/x-www-form-urlencoded; charset=UTF-8"
            },
            body: `username=${username.value}`,
            credentials: 'include'
        })
        .then(json)
        .then((data) => {
            if(data.status == "Yup"){
                let link = window.location.href;
                let baseLink = link.substr(0, link.lastIndexOf('/'));
                window.location.replace(`${baseLink}/?action=profile&profile_id=${data.id}`);
            } else {
                console.log("Username not found");
            }
        })
        .catch((error) => {
            console.log('Request failed', error);
        });
    });
});