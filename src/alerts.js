const alertPlaceholder = document.getElementById('liveAlertPlaceholder')
const appendAlert = (message, type) => {
    const wrapper = document.createElement('div')
    wrapper.innerHTML = [
        `<div class="alert alert-${type} alert-dismissible" role="alert">`,
        `   <div>${message}</div>`,
        '   <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>',
        '</div>'
    ].join('')

    alertPlaceholder.append(wrapper)
}

const searchParams = new URLSearchParams(window.location.search)
if (searchParams.has('thread_removed')){
    appendAlert('スレッドは正常に削除されました。', 'success');
}
if (searchParams.has('thread_posted')){
    appendAlert('投稿内容は正常に投稿されました。', 'success');
}