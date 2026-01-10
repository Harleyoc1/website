window.copyToClipboard = function (text) {
    navigator.clipboard.writeText(text.replaceAll(' ', '%20'));
}
