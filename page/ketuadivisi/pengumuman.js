function formatText(command) {
    document.execCommand(command, false, null);
}

function tambahLink() {
    let url = prompt("Masukkan URL:");
    if (url) document.execCommand("createLink", false, url);
}

function tambahYouTube() {
    let url = prompt("Masukkan URL YouTube:");
    if (url) {
        document.execCommand("insertHTML", false, `<iframe width="300" height="200" src="${url.replace('watch?v=', 'embed/')}" frameborder="0" allowfullscreen></iframe>`);
    }
}

function tambahGDrive() {
    let url = prompt("Masukkan URL Google Drive:");
    if (url) {
        document.execCommand("insertHTML", false, `<a href="${url}" target="_blank">Buka Google Drive</a>`);
    }
}
