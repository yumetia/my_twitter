function confirmSending() {
    const box = document.querySelector(".commentInterface__answer-input");
    const inputTweet = document.querySelector(".send-tweet");

    inputTweet.addEventListener("input", () => {
        let existingButton = document.querySelector(".send-btn");
        if (inputTweet.value.trim().length >= 1) {
            if (!existingButton) {
                const button = document.createElement("button");
                button.classList.add("send-btn");
                button.style = "display:block;margin-left:auto;";
                button.textContent = "Envoyer";
                box.appendChild(button);
                button.addEventListener("click", () => {
                    sendComment(inputTweet.value);
                });
            }
        } else if (existingButton) {
            existingButton.remove();
        }
    });
}

function sendComment(commentContent) {
    let payload = {};
    // Utilise la variable isReply ou teste la classe sur le body
    if (document.body.classList.contains("reply-page")) {
        payload = { commentContent2: commentContent };
    } else {
        payload = { commentContent: commentContent };
    }

    fetch("Comments", {
        method: "POST",
        headers: { 'Content-Type': 'application/json;charset=utf-8' },
        body: JSON.stringify(payload)
    })
    .then(response => {
        if (!response.ok) throw new Error("ajax fail!");
        return response.json();
    })
    .then(data => {
        displayComment(commentContent);
    })
    .catch(error => console.error("Erreur:", error));
}

function displayComment(commentContent) {
    const noResponse = document.getElementById("no-response");
    const textArea = document.querySelector(".send-tweet");
    const sendButton = document.querySelector(".send-btn");
    const username = document.querySelector(".username").textContent.trim();
    const comments = document.querySelector(".commentInterface__section-comments");

    const commentDiv = document.createElement("div");
    commentDiv.classList.add("tweet");
    commentDiv.innerHTML = `
        <p><strong>${username}</strong> Il y a 2 secondes</p>
        <p>${commentContent}</p>
        <div class="icon">
            <i class="fa-regular fa-comment"><strong> 0</strong></i>
            <i class="fa-solid fa-retweet"><strong> 0</strong></i>
            <i class="fa-regular fa-heart"><strong> 0</strong></i>
        </div>
    `;

    if (sendButton) sendButton.remove();
    if (noResponse && noResponse.innerHTML.trim() !== "") {
        noResponse.innerHTML = "";
    }
    textArea.value = "";
    comments.appendChild(commentDiv);
}

confirmSending();
