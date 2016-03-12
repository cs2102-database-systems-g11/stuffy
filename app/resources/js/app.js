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

function copy_input_values(srcId, dstId) {
    var src = document.getElementById(srcId);
    var dst = document.getElementById(dstId);
    dst.value = src.value;
}

function toggle_advanced_search(e) {
    e.preventDefault();
    $('.advanced-search').slideToggle(100, function() {
        if ($(".advanced-search").is(':hidden')) {
            $('.adv-search-text > a').text('Show advanced search');
        } else {
            $('.adv-search-text > a').text('Hide advanced search');
        }
    });
}
