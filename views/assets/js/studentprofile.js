document.getElementById("studentUpdateForm").onsubmit = async function (e) {
    e.preventDefault();

    const email = document.getElementById("email").value.trim();
    const password = document.getElementById("password").value.trim();

    const response = await fetch("/student/update-profile", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ email, password }),
    });

    const result = await response.json();
    const message = document.getElementById("updateMessage");

    if (response.ok) {
        message.textContent = "Profile updated successfully!";
        message.style.color = "green";
    } else {
        message.textContent = result.message || "Failed to update profile.";
        message.style.color = "red";
    }
};
