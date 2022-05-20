const ifm = document.getElementById('ifm');

function pageY(elem) {
    return elem.offsetParent ? (elem.offsetTop + pageY(elem.offsetParent)) : elem.offsetTop;
}

function resizeIframe() {
    console.debug('resizeIframe()');

    const buffer = 0; //scroll bar buffer
    let height = document.documentElement.clientHeight;

    height -= pageY(ifm) + buffer;
    height = (height < 0) ? 0 : height;
    ifm.style.height = height + 'px';
}

$(document).ready(function () {
    window.onresize = resizeIframe;
    resizeIframe();
});

$(window).load(function () {
    let loginForm = ifm.contentDocument.getElementById('login-form');
    if (loginForm == null || loginForm._user == undefined) {
        loginForm = ifm.contentDocument.form;
        if (loginForm == undefined || loginForm._user == undefined) {
        return;
        }
    }
    var baseUrl = OC.generateUrl('/apps/rcconnect');

    $.ajax({
        url: baseUrl + '/rc',
        type: 'GET',
        contentType: 'application/json',
        dataType:"json",
    }).done(function (data) {
        // handle success
        if (data.username !== undefined) {
            loginForm._user.value = data.username;
            loginForm._pass.value = data.password;
            loginForm.submit();
        } 
    }).fail(function (data, code) {
        // handle failure
        console.log("Error: Failed to get data.");
        return false;
    });

});
