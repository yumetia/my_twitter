function check() {
    const tweets = document.querySelectorAll(".tweet");

    tweets.forEach(tweet => {
        tweet.addEventListener("click", () => {
            // Si l'élément cliqué contient la classe "comment", c'est un commentaire sur lequel on souhaite répondre.
            if (tweet.classList.contains("comment")) {
                const commentId = parseInt(tweet.id);
                const username = tweet.querySelector(".tweet-username").textContent.trim();
                const date = tweet.querySelector(".tweet-date").textContent.trim();
                const content = tweet.querySelector(".tweet-content").textContent.trim();

                const commentInfos = {
                    commentId: commentId,
                    username: username,
                    date: date,
                    content: content
                };

                fetch("Comments", {
                    method: "POST",
                    headers: {
                        'Content-Type': 'application/json;charset=utf-8'
                    },
                    body: JSON.stringify({ "commentInfos": commentInfos })
                })
                .then(response => {
                    if (!response.ok) throw new Error("ajax fail!");
                    return response.json();
                })
                .then(() => {
                    // La page sera rechargée en mode réponse ($_SESSION["comment_id"] sera défini)
                    window.location.href = "comments";
                })
                .catch(error => console.error("Erreur:", error));
            } else {
                // Sinon, c'est un tweet sur lequel on souhaite afficher les commentaires.
                const tweetId = parseInt(tweet.id);
                const username = tweet.querySelector(".tweet-username").textContent.trim();
                const date = tweet.querySelector(".tweet-date").textContent.trim();
                const content = tweet.querySelector(".tweet-content").textContent.trim();

                const tweetInfos = {
                    tweetId: tweetId,
                    username: username,
                    date: date,
                    content: content
                };

                fetch("Comments", {
                    method: "POST",
                    headers: {
                        'Content-Type': 'application/json;charset=utf-8'
                    },
                    body: JSON.stringify({ "tweetInfos": tweetInfos })
                })
                .then(response => {
                    if (!response.ok) throw new Error("ajax fail!");
                    return response.json();
                })
                .then(() => {
                    window.location.href = "comments";
                })
                .catch(error => console.error("Erreur:", error));
            }
        });
    });
}

check();
