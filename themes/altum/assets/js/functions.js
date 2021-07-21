'use strict';

const display_notifications = (messages, type, selector) => {

    let html = '';
    type = type == 'error' ? 'danger' : type;

    for(let message of messages) {

        html += `
            <div class="alert alert-${type} altum-animate altum-animate-fill-both altum-animate-fade-in">
                <button type="button" class="close" data-dismiss="alert">&times;</button>
                ${message}
            </div>`;

    }

    $(selector).html(html);

};

let build_url_query = data => {
    if (typeof (data) === 'string') return data;

    let query = [];
    for (let key in data) {
        if (data.hasOwnProperty(key)) {
            query.push(encodeURIComponent(key) + '=' + encodeURIComponent(data[key]));
        }
    }

    return query.join('&');
};

let build_form_data = data => {
    let form_data = new FormData();

    for(let key in data) {
        form_data.append(key, data[key]);
    }

    return form_data
}

const redirect = (url, full = false) => {
    /* Get the base url */
    let base_url = $('#url').val();

    window.location.href = full ? url : `${base_url}${url}`;
};

const number_format = (number, decimals, dec_point = '.', thousands_point = ',') => {

    if (number == null || !isFinite(number)) {
        throw new TypeError('number is not valid');
    }

    if(!decimals) {
        let len = number.toString().split('.').length;
        decimals = len > 1 ? len : 0;
    }

    number = parseFloat(number).toFixed(decimals);

    number = number.replace('.', dec_point);

    let splitNum = number.split(dec_point);
    splitNum[0] = splitNum[0].replace(/\B(?=(\d{3})+(?!\d))/g, thousands_point);
    number = splitNum.join(dec_point);

    return number;
};

const nr = (number, decimals = 0) => {
    return number_format(number, decimals, decimal_point, thousands_separator);
};

const get_cookie = name => {
    let v = document.cookie.match('(^|;) ?' + name + '=([^;]*)(;|$)');

    return v ? v[2] : null;
};

const set_cookie = (name, value, days, path) => {
    let d = new Date;
    d.setTime(d.getTime() + 24*60*60*1000*days);

    document.cookie = `${name}=${value};path=${path};expires=${d.toGMTString()}`;
};

let delete_cookie = (name, path) => {
    set_cookie(name, '', -1, path);
};
