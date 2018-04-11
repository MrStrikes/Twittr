function json(response){
    return response.json();
}

window.addEventListener('load', () => {
    var reg_btn = document.querySelector('#reg-btn');

    var firstname = document.querySelector('#firstname');
    var lastname = document.querySelector('#lastname');
    var username = document.querySelector('#username');
    var password = document.querySelector('#password');
    var passwordRepeat = document.querySelector('#password_repeat');
    var email = document.querySelector('#email');

    console.log('oui');
    reg_btn.addEventListener('click', () => {
        var url = '?action=register';
        fetch(url, {
            method: 'post',
            headers: {
            "Content-type": "application/x-www-form-urlencoded; charset=UTF-8"
            },
            body: `firstname=${firstname.value}&lastname=${lastname.value}&username=${username.value}&password=${password.value}&password_repeat=${passwordRepeat.value}&email=${email.value}`,
            credentials: 'include'
        })
        .then(json)
        .then((data) => {
            console.log('Request succeeded with JSON response', data);
            alert(`Welcome to Twittr ${username.value}`);
        })
        .catch((error) => {
            console.log('Request failed', error);
        });
    });
});