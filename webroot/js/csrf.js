$.ajax({
    type: 'post',
    url: 'http://localhost/api_test/items/receivepost',
    headers: { 'X-XSRF-TOKEN' : $('input[name="_csrfToken"]').val() },
    beforeSend: function (xhr) {
        xhr.setRequestHeader('X-CSRF-Token', $('input[name="_csrfToken"]').val());
    },
    dataType: 'json',
    data: {
        "_csrfToken":$('input[name="_csrfToken"]').val()
    },
    async: true,
    cache: false,
}).then(function (data) {
})
