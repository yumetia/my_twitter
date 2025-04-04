function showRetweetPopup() {
    return new Promise((resolve, reject) => {
        let overlay = document.createElement("div");
        overlay.id = "retweet-overlay";
        
        let popup = document.createElement("div");
        popup.id = "retweet-popup";
        
        let title = document.createElement("h3");
        title.textContent = "Retweet";
        popup.appendChild(title);
        
        let textarea = document.createElement("textarea");
        textarea.placeholder = "Ajouter un message (facultatif)";
        popup.appendChild(textarea);
        
        let btnContainer = document.createElement("div");
        btnContainer.className = "btn-container";
        
        let cancelBtn = document.createElement("button");
        cancelBtn.textContent = "Annuler";
        btnContainer.appendChild(cancelBtn);
        
        let retweetBtn = document.createElement("button");
        retweetBtn.textContent = "Retweet";
        btnContainer.appendChild(retweetBtn);
        
        
        popup.appendChild(btnContainer);
        overlay.appendChild(popup);
        document.body.appendChild(overlay);
        
        retweetBtn.addEventListener("click", () => {
            let message = textarea.value;
            document.body.removeChild(overlay);
            resolve(message);
        });
        
        cancelBtn.addEventListener("click", () => {
            document.body.removeChild(overlay);
            resolve(null);
        });
    });
}

function setupRetweetButtons() {
    document.querySelectorAll(".retweet-button").forEach(button => {
        button.addEventListener("click", (ev) => {
            ev.stopPropagation();
            const tweetId = button.dataset.tweetId;
            const commentId = button.dataset.commentId;
            
            showRetweetPopup().then(retweetMessage => {
                if (retweetMessage === null) return;
                fetch("Retweets", {
                    method: "POST",
                    headers: { "Content-Type": "application/json" },
                    body: JSON.stringify({ tweet_id: tweetId, comment_id: commentId, retweet_message: retweetMessage })
                })
                .then(response => response.json())
                .then(data => {
                    const countElem = button.querySelector("strong");
                    let currentCount = parseInt(countElem.textContent);
                    if (data.status === "retweeted") {
                        button.classList.add("retweeted");
                        countElem.textContent = ` ${currentCount + 1}`;
                        alert("Retweet effectué !");
                    } else if (data.status === "unretweeted") {
                        button.classList.remove("retweeted");
                        countElem.textContent = ` ${currentCount - 1}`;
                        alert("Retweet retiré !");
                    } else if (data.status === "quote_retweeted") {
                        button.classList.add("retweeted");
                        countElem.textContent = ` ${currentCount + 1}`;
                        alert("Quote tweet créé !");
                    }
                })
                .catch(error => console.error("Erreur:", error));
            });
        });
    });
}

setupRetweetButtons();
