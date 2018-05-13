const newTwtt = (atUsername, username, user_id, date, content, twtt_id, rt, fav) => {
    let twtt = document.createElement('div');
    let header = document.createElement('div');
    let twContent = document.createElement('div');
    let userActions = document.createElement('div');
    userActions.classList.add('user-actions');
    twtt.classList.add('twtt');
    header.classList.add('tw-head');
    twContent.classList.add('tw-content');
    header.setAttribute('id', user_id);
    header.innerHTML = `<a href="?action=profile&profile_id=${user_id}">${username} @${atUsername}</a><span class="date"> ${date}</span>`;
    userActions.innerHTML = `${rt}<i id="rt" class="fas fa-retweet rt" value="${twtt_id}" re-twtt-id="not-a-rt"></i>&nbsp;${fav}<i id="star" class="fas fa-star star" value="${twtt_id}"></i>`;
    twContent.innerHTML = content;
    twtt.appendChild(header);
    twtt.appendChild(twContent);
    twtt.appendChild(userActions);
    return twtt;
};

const newRtwtt = (atUsername, username, user_id, date, content, user_rt_id, atUsernameAuthor_rt, usernameAuthor_rt, twtt_id, re_twtt_id, rt, fav) => {
    let twtt = document.createElement('div');
    let header = document.createElement('div');
    let twContent = document.createElement('div');
    let userActions = document.createElement('div');
    userActions.classList.add('user-actions');
    twtt.classList.add('retwtt');
    twtt.classList.add('twtt');
    header.classList.add('tw-head');
    twContent.classList.add('tw-content');
    header.setAttribute('id', user_id);
    header.innerHTML = `<em class="rtwtt-msg"><a class="rtwtt-msg" href="?action=profile&profile_id=${user_rt_id}">${usernameAuthor_rt} @${atUsernameAuthor_rt}</a> retwtted</em><br><a href="?action=profile&profile_id=${user_id}">${username} @${atUsername}</a><span class="date"> ${date}</span>`;
    userActions.innerHTML = `${rt}<i id="rt" class="fas fa-retweet rt" value="${twtt_id}" re-twtt-id="${re_twtt_id}"></i>&nbsp;${fav}<i id="star" class="fas fa-star star" value="${twtt_id}"></i>`;
    twContent.innerHTML = content;
    twtt.appendChild(header);
    twtt.appendChild(twContent);
    twtt.appendChild(userActions);
    return twtt;
};