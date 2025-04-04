function setupLikeButtons() {
    document.querySelectorAll(".like-button").forEach(button => {
        button.addEventListener("click", (ev) => {
            ev.stopPropagation();
            const tweetId = button.dataset.tweetId || null;
            const commentId = button.dataset.commentId || null;

            fetch("Likes", {
                method: "POST",
                headers: { "Content-Type": "application/json" },
                body: JSON.stringify({ tweet_id: tweetId, comment_id: commentId })
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === "liked") {
                    button.classList.add("liked");
                    button.querySelector("strong").textContent = ` ${parseInt(button.querySelector("strong").textContent) + 1}`;
                } else if (data.status === "unliked") {
                    button.classList.remove("liked");
                    button.querySelector("strong").textContent = ` ${parseInt(button.querySelector("strong").textContent) - 1}`;
                }
            })
            .catch(error => console.error("Erreur:", error));
        });
    });
}

setupLikeButtons()
