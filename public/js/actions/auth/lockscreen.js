export function initLockscreen() {
    if (!document.querySelector('.lockscreen-main-container')) {
        return;
    }

    history.pushState(null, null, location.href);

    window.onpopstate = function () {
        history.go(1);
    };
}