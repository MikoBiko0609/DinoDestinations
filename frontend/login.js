function openTab(evt, tabName) {
    var i, tabcontent, tablinks;
    tabcontent = document.getElementsByClassName("tab-content");
    for (i = 0; i < tabcontent.length; i++) {
        tabcontent[i].style.display = "none";
    }
    tablinks = document.getElementsByClassName("tab");
    for (i = 0; i < tablinks.length; i++) {
        tablinks[i].className = tablinks[i].className.replace(" active", "");
    }
    document.getElementById(tabName).style.display = "block";
    evt.currentTarget.className += " active";
}

function checkPasswords() {
    var password = document.getElementById('newPassword').value;
    var confirmPassword = document.getElementById('confirmPassword').value;
    if (password !== confirmPassword) {
        alert("Passwords do not match.");
        return false;
    }
    return true;
}

document.getElementById('signupForm').addEventListener('submit', function(event) {
    if (!checkPasswords()) {
        event.preventDefault(); 
    } else {
        // Collect user data to do something with it
        var username = document.getElementById('newUsername').value;
    }
});

function login() {
    window.location.href = 'mainPage.html';
}

document.addEventListener('DOMContentLoaded', (event) => {
    document.querySelector('.tab').click();
});
