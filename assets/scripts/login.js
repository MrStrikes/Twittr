function json(response){
    return response.json();
} 

window.addEventListener('load', () => {

    /***********
    * Register *
    ***********/
    var reg_btn = document.querySelector('#reg-btn');

    var firstname = document.querySelector('#firstname');
    var lastname = document.querySelector('#lastname');
    var username = document.querySelector('#username');
    var password = document.querySelector('#password');
    var passwordRepeat = document.querySelector('#password_repeat');
    var email = document.querySelector('#email');

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

    /********
    * LOGIN *
    ********/

    var log_btn = document.querySelector('#btn-login');

    var username = document.querySelector('#pseudo');
    var password = document.querySelector('#log-password');

    log_btn.addEventListener('click', () => {
        var url = '?action=login';
        fetch(url, {
            method: 'post',
            headers: {
            "Content-type": "application/x-www-form-urlencoded; charset=UTF-8"
            },
            body: `username=${username.value}&password=${password.value}`,
            credentials: 'include'
        })
        .then(json)
        .then((data) => {
            console.log('Request succeeded with JSON response', data);
            if(data.status === 'ok'){
                window.location.href = "http://localhost/Twittr/?action=home";
            } else {
                alert('There is an error in your credentials, check again please');
            }
        })
        .catch((error) => {
            console.log('Request failed', error);
        });
    });
});