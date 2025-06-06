//Zobaer Ahmed
let currentPasswordValidation = () => {
  const password = document.getElementById("currentPassword").value.trim();
  const errorDiv = document.getElementById("currentPasswordError");
  if (password === "") {
    errorDiv.textContent = "Password is required";
    errorDiv.style.color = "red";
    return false;
  }
  if (password.length < 6) {
    errorDiv.textContent = "Password must be at least 6 characters.";
    return false;
  }

  errorDiv.textContent = "";
  return true;
};
let newPasswordValidation = () => {
  const password = document.getElementById("newPassword").value.trim();
  const errorDiv = document.getElementById("newPasswordError");
  if (password === "") {
    errorDiv.textContent = "Password is required";
    errorDiv.style.color = "red";
    return false;
  }
  if (password.length < 6) {
    errorDiv.textContent = "Password must be at least 6 characters.";
    return false;
  }

  errorDiv.textContent = "";
  return true;
};

let confirmPasswordValidation = () => {
    const currentPassword = document.getElementById("currentPassword").value.trim();
  const password = document.getElementById("newPassword").value.trim();
  const confirmPassword = document
    .getElementById("confirmPassword")
    .value.trim();
  const errorDiv = document.getElementById("confirmPasswordError");
  if(currentPassword === password){
    errorDiv.textContent = "Current password and new password should not be matched";
    errorDiv.style.color = "red";
    return false;
  }
  if (!confirmPassword) {
    errorDiv.textContent = "Confirm Password is required";
    errorDiv.style.color = "red";
    return false;
  }
  if (confirmPassword !== password) {
    errorDiv.textContent = "Passwords do not match.";
    errorDiv.style.color = "red";
    return false;
  }

  errorDiv.textContent = "";
  return true;
};
let changePasswordValidation = () => {
  return (
    currentPasswordValidation() &&
    newPasswordValidation() &&
    confirmPasswordValidation()
  );
};
let changePasswordForm = document.getElementById("changePasswordForm");
let cancelBtn = document.getElementById("cancel-btn");

if (changePasswordForm) {
  changePasswordForm.addEventListener("submit", (event) => {
    event.preventDefault();
    if (changePasswordValidation()) {
      changePasswordForm.submit();
    }
  });
}

if (cancelBtn) {
  cancelBtn.addEventListener("click", (event) => {
    event.preventDefault();
    window.location.href = "index.html";
  });
}
