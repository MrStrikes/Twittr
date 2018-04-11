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
    reg_btn.addEventListener('click', () => {
        var url = '?action=register';
        fetch(url, {
            method: 'post',
            headers: {
            "Content-type": "application/x-www-form-urlencoded; charset=UTF-8"
            },
            body: 'content='+JSON.stringify($('#text-content').val()),
            credentials: 'include'
        })
        .then(json)
        .then(function (data) {
            console.log('Request succeeded with JSON response', data);
        })
        .catch(function (error) {
            console.log('Request failed', error);
        });
    });
});