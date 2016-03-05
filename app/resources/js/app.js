function redirect(url) {
    window.location.href = url;
}

// type: success, info, warning, danger
function notify(type, text) {
    var alert = document.querySelector('.alert');
    if (alert) {
        alert.className = 'alert alert-' + type;
        alert.innerHTML = text;
    } else {
        var node = document.querySelector('.content');
        if (node) {
            node.insertAdjacentHTML('afterbegin', "<div class='alert alert-" + type + "' role='alert'>" + text + "</div>");
        }
    }
}
