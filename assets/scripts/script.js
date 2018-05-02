const newTwtt = (atUsername, username, user_id, date, content) => {
    let twtt = document.createElement('div');
    let header = document.createElement('div');
    let twContent = document.createElement('div');
    twtt.classList.add('twtt');
    header.classList.add('tw-head');
    twContent.classList.add('tw-content');
    header.setAttribute('id', user_id);
    header.innerHTML = `<a href="?action=profile&profile_id=${user_id}">${username}@${atUsername}</a><span class="date">${date}</span>`;
    twContent.innerHTML = content;
    twtt.appendChild(header);
    twtt.appendChild(twContent);
    return twtt;
};

const newRtwtt = (atUsername, username, user_id, date, content, user_rt_id, atUsernameAuthor_rt, usernameAuthor_rt) => {
    let twtt = document.createElement('div');
    let header = document.createElement('div');
    let twContent = document.createElement('div');
    twtt.classList.add('div');
    header.classList.add('tw-head');
    twContent.classList.add('tw-content');
    header.setAttribute('id', user_id);
    header.innerHTML = `<a href="?action=profile&profile_id=${user_rt_id}">${usernameAuthor_rt}@${atUsernameAuthor_rt}</a> a retwtté <br><a href="?action=profile&profile_id=${user_id}">${username}@${atUsername}</a><span class="date">${date}</span>`;
    twContent.innerHTML = content;
    twtt.appendChild(header);
    twtt.appendChild(twContent);
    return twtt;
};