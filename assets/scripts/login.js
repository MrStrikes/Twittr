function json(response){
    return response.json();
} 

window.addEventListener('load', () => {
    $('#login-form-link').click(function(e) {
        $("#login-form").delay(100).fadeIn(100);
            $("#register-form").fadeOut(100);
        $('#register-form-link').removeClass('active');
        $(this).addClass('active');
        e.preventDefault();
    });
    $('#register-form-link').click(function(e) {
        $("#register-form").delay(100).fadeIn(100);
            $("#login-form").fadeOut(100);
        $('#login-form-link').removeClass('active');
        $(this).addClass('active');
        e.preventDefault();
    });
    

    /***********
    * Register *
    ***********/
    var register = document.forms.register;

    register.addEventListener('submit', (ev) => {
        ev.preventDefault();
        let firstname = register.elements.firstname;
        let lastname = register.elements.lastname;
        let user = register.elements.username;
        let pass = register.elements.password;
        let passwordRepeat = register.elements.confirmPassword;
        let email = register.elements.email;
        let url = '?action=register';
        fetch(url, {
            method: 'post',
            headers: {
            "Content-type": "application/x-www-form-urlencoded; charset=UTF-8"
            },
            body: `firstname=${firstname.value}&lastname=${lastname.value}&username=${user.value}&password=${pass.value}&password_repeat=${passwordRepeat.value}&email=${email.value}`,
            credentials: 'include'
        })
        .then(json)
        .then((data) => {
            console.log('Request succeeded with JSON response', data);
            if(data.status === "ok"){
                $("#login-form").delay(100).fadeIn(100);
                    $("#register-form").fadeOut(100);
                $('#register-form-link').removeClass('active');
                $(this).addClass('active');
                alert("You can now login into Twttr !");
            }
        })
        .catch((error) => {
            console.log('Request failed', error);
        });
    });

    /********
    * LOGIN *
    ********/

    let login = document.forms.login;

    login.addEventListener('submit', (ev) => {
        ev.preventDefault();
        let username = login.elements.username;
        let password = login.elements.password;
        let url = '?action=login';
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
                window.location.href = "?action=home";
            } else {
                alert('There is an error in your credentials, check again please');
            }
        })
        .catch((error) => {
            console.log('Request failed', error);
        });
    });
});