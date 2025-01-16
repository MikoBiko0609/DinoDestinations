// API URL - replace with your Render backend URL when deployed
const API_URL = 'https://your-render-backend-url.onrender.com';

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
        document.getElementById('signupMessage').textContent = "Passwords do not match.";
        return false;
    }
    return true;
}

// Handle login form submission
document.getElementById('loginForm').addEventListener('submit', async function(event) {
    event.preventDefault();
    
    const formData = new FormData();
    formData.append('username', document.getElementById('username').value);
    formData.append('password', document.getElementById('password').value);

    try {
        const response = await fetch(`${API_URL}/login.php`, {
            method: 'POST',
            body: formData
        });

        const data = await response.json();

        if (data.success) {
            window.location.href = data.redirect;
        } else {
            document.getElementById('loginMessage').textContent = data.message;
        }
    } catch (error) {
        document.getElementById('loginMessage').textContent = 'An error occurred. Please try again.';
        console.error('Error:', error);
    }
});

// Handle signup form submission
document.getElementById('signupForm').addEventListener('submit', async function(event) {
    event.preventDefault();
    
    if (!checkPasswords()) {
        return;
    }

    const formData = new FormData();
    formData.append('username', document.getElementById('newUsername').value);
    formData.append('password', document.getElementById('newPassword').value);
    formData.append('confirmPassword', document.getElementById('confirmPassword').value);

    try {
        const response = await fetch(`${API_URL}/register.php`, {
            method: 'POST',
            body: formData
        });

        const data = await response.json();

        if (data.success) {
            document.getElementById('signupMessage').textContent = 'Registration successful! Redirecting to login...';
            setTimeout(() => {
                window.location.href = data.redirect;
            }, 2000);
        } else {
            document.getElementById('signupMessage').textContent = data.message;
        }
    } catch (error) {
        document.getElementById('signupMessage').textContent = 'An error occurred. Please try again.';
        console.error('Error:', error);
    }
});

// Open the login tab by default when the page loads
document.addEventListener('DOMContentLoaded', (event) => {
    document.querySelector('.tab').click();
});