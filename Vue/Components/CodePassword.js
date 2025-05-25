function Password() {
    document.getElementById("resetForm").style.display = "none";
    document.getElementById("codeInputs").style.display = "block";

    const codeInputs = document.querySelectorAll(".code-input");
    codeInputs.forEach((input, index) => {
        input.addEventListener("input", () => {
            if (input.value.length === 1 && index < codeInputs.length - 1) {
                codeInputs[index + 1].focus();
            }
        });

        input.addEventListener("keydown", (e) => {
            if (e.key === "Backspace" && input.value === "" && index > 0) {
                codeInputs[index - 1].focus();
            }
        });
    });
}

document.addEventListener("DOMContentLoaded", () => {
    const emailInput = document.getElementById("email");
    const sendButton = document.getElementById("sendButton");

    sendButton.disabled = true;

    emailInput.addEventListener("input", () => {
        if (emailInput.value.trim() !== "" && emailInput.checkValidity()) {
            sendButton.disabled = false;
        } else {
            sendButton.disabled = true;
        }
    });

    sendButton.addEventListener("click", (e) => {
        e.preventDefault();
        if (!sendButton.disabled) {
            CodePassword();
        }
    });
});