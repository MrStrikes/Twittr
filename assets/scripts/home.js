document.querySelector('button[name="new-twtt"]').addEventListener("click", sendTwtt);

function manageLiveRating(data){
    let tl = document.querySelector('.tl');
    tl.innerHTML = "";
    for (let a in data) {
        if ('re_twtt' === data[a].type) {
            tl.prepend(newRtwtt(data[a].twtt.user_id.at_username, data[a].twtt.user_id.username, data[a].twtt.user_id.id, data[a].twtt.creation, data[a].twtt.content, data[a].user_id.id, data[a].user_id.at_username, data[a].user_id.username, data[a].twtt_id, data[a].re_twtt_id, data[a].rt, data[a].fav));
        } else {
            tl.prepend(newTwtt(data[a].user_id.at_username, data[a].user_id.username, data[a].user_id.id, data[a].creation, data[a].content, data[a].twtt_id, data[a].rt, data[a].fav));
        }
        document.querySelector('textarea').value = ' ';
        loadActions('.rt');
        loadActions('.star');
        let rt = document.querySelector("#rt");
        let fav = document.querySelector("#star");
        let rtCounter = document.querySelector('.rt-counter');
        let rtc = parseInt(rtCounter.innerHTML);
        if(data[a].isRt == true){
            rt.style.color = "green";
        } else {
            rt.style.color = "inherit";
        }
        rt.addEventListener('click', () => {
            if(data[a].isRt == false){
                rt.style.color = "green";
                data[a].isRt = true;
                if(rtc === 0){
                    let newRtValue = rtc + 1;
                    rtCounter.innerHTML = newRtValue;
                } else {
                    let newRtValue = rtc;
                    rtCounter.innerHTML = newRtValue;
                }
            } else {
                rt.style.color = "inherit";
                data[a].isRt = false;
                if(rtc !== 0){
                    let newRtValue = rtc;
                    rtCounter.innerHTML = newRtValue - 1;
                } else {
                    let newRtValue = rtc;
                    rtCounter.innerHTML = newRtValue;
                }
            }
        });
        let favCounter = document.querySelector('.fav-counter');
        let favCount = parseInt(favCounter.innerHTML);
        if(data[a].isFav == true){
            fav.style.color = "orange";
        } else {
            fav.style.color = "inherit";
        }
        fav.addEventListener('click', () => {
            if(data[a].isFav == false){
                fav.style.color = "orange";
                data[a].isFav = true;
                if(favCount === 0){
                    let newFavValue = favCount + 1;
                    favCounter.innerHTML = newFavValue;
                } else {
                    let newFavValue = favCount;
                    favCounter.innerHTML = newFavValue;
                }
            } else {
                data[a].isFav = false;
                fav.style.color = "inherit";
                if(favCount !== 0){
                    let newFavValue = favCount - 1;
                    favCounter.innerHTML = newFavValue;
                } else {
                    let newFavValue = favCount;
                    favCounter.innerHTML = newFavValue;
                }
            }
        });
    }
}

function loadActions(domEl) {
    let action = document.querySelector(domEl);
    let url_string = window.location.href;
    let getUrl = new URL(url_string);
    let profile_id = getUrl.searchParams.get("profile_id");
    let value = action.getAttribute("value");
    let rating = action.getAttribute("id");
    let re_twtt_id = action.getAttribute("re-twtt-id");
    action.addEventListener('click', () => {
        let manageRating = `?action=manageRatings`;
        fetch(manageRating, {
            method: 'post',
            headers: {
                "Content-type": "application/x-www-form-urlencoded; charset=UTF-8"
            },
            body: `twtt_id=${value}&rating=${rating}&profile_id=${profile_id}&re_twtt_id=${re_twtt_id}`,
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

function sendTwtt() {
    var twtt = document.querySelector('textarea[name="new-twtt"]').value;
    if (0 !== twtt.length){
        fetch("?action=newTwtt", {
            method: 'post',
            headers: {
                "Content-type": "application/x-www-form-urlencoded; charset=UTF-8"
            },
            body: `content=+${JSON.stringify(twtt)}`,
            credentials: 'include'
        })
        .then(json)
        .then(function (data) {
            console.log('Request succeeded with JSON response', data);
            getTl();
        })
        .catch(function (error) {
            console.log('Request failed', error);
        });
    }
}

function json(response) {
    return response.json();
}

function getTl() {
    fetch("?action=getMainTl", {
        method: 'get',
        headers: {
            "Content-type": "application/x-www-form-urlencoded; charset=UTF-8"
        },
        credentials: 'include'
    })
        .then(json)
        .then(function (data) {
            manageLiveRating(data);
        })
        .catch(function (error) {
            console.log('Request failed', error);
        });
}

getTl();