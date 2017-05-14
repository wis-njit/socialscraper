/**
 * Created by adaka on 4/8/2017.
 */
function isObject(obj) {
    return obj === Object(obj);
}

function syntaxHighlight(json) {
    json = json.replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;');
    return json.replace(/("(\\u[a-zA-Z0-9]{4}|\\[^u]|[^\\"])*"(\s*:)?|\b(true|false|null)\b|-?\d+(?:\.\d*)?(?:[eE][+\-]?\d+)?)/g, function (match) {
        var cls = 'number';
        if (/^"/.test(match)) {
            if (/:$/.test(match)) {
                cls = 'key';
            } else {
                cls = 'string';
            }
        } else if (/true|false/.test(match)) {
            cls = 'boolean';
        } else if (/null/.test(match)) {
            cls = 'null';
        }
        return '<span class="' + cls + '">' + match + '</span>';
    });
}

function output(inp) {
    //document.body.appendChild(document.createElement('pre')).innerHTML = inp;
    var test = document.getElementsByClassName('panel-body');
    var pre = document.createElement('pre');
    pre.className = "responsebox";
    pre.innerHTML = inp;

    test[0].appendChild(pre);
}