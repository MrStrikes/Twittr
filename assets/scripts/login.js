function json(response){
    return response.json();
}

window.addEventListener('load', () => {
    var reg_btn = document.querySelector('#reg-btn');

    var firstname = document.querySelector('#firstname').value;
    var lastname = document.querySelector('#lastname').value;
    var username = document.querySelector('#username').value;
    var password = document.querySelector('#password').value;
    var passwordRepeat = document.querySelector('#password_repeat').value;
    var email = document.querySelector('#email').value;

    console.log('oui');
    reg_btn.addEventListener('submit', () => {
        var url = '?action=register';
        fetch(url, {
            method: 'post',
            headers: {
            "Content-type": "application/x-www-form-urlencoded; charset=UTF-8"
            },
            body: `firstname=${firstname}&lastname=${lastname}&username=${username}&password=${password}&password_repeat=${passwordRepeat}&email=${email}`,
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
});