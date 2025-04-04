


function confirmSending(){
    // check if the user wrote at least 1 character to tweet 
    // to create the send button
    const tweetBox = document.querySelector(".tweet-box")
    const inputTweet = document.querySelector(".send-tweet")
    
    inputTweet.addEventListener("input", () => { 
        let existingButton = document.querySelector(".send-btn");

        if (inputTweet.value.length >= 1) {
            if (!existingButton) {  
                const button = document.createElement("button");
                button.classList.add("send-btn");
                button.textContent = "Envoyer";
                tweetBox.appendChild(button);

                // check if the button send is pressed !
                button.addEventListener("click",()=>{
                    sendTweet(inputTweet.value)
                })
            }
        } else if(existingButton){
                existingButton.remove();
        }

    })

}

function sendTweet(tweetContent){
    // listening to the send button
    // process ajax request to Tweet.php (controller)
    console.log(tweetContent);

    fetch("Tweets", {
        method: "POST",
        headers: {
            'Content-Type': 'application/json;charset=utf-8'
        },
        body: JSON.stringify({tweetContent:tweetContent})
    })
    .then(response=>{
        if (!response.ok){
            throw new Error("ajax fail !")
        }
        return response.json()
    })
    .then(()=>{

        displayTweet(tweetContent)
    })
    .catch(error=>console.error("Erreur:",error));   

}

function displayTweet(tweetContent){
    // getting the user info (username)
    // generate html div tweet with the content 
    
    const username = document.querySelector(".username").textContent.trim();
    const tweets = document.querySelector(".tweets")

    const tweet = document.createElement("div")
        tweet.classList.add("tweet")
        tweet.innerHTML = `
        <p><strong>${username}</strong> Il y a 2 secondes</p>
        <p>${tweetContent}</p>
        <div class="icon">
            <i class="fa-regular fa-comment"><strong>0</strong></i>
            <i class="fa-solid fa-retweet"><strong>0</strong></i>
            <i class="fa-regular fa-heart"><strong>0</strong></i>
            <i class="fa-solid fa-arrow-up-from-bracket"></i>
        </div>
        `
        tweets.appendChild(tweet)

}

confirmSending()
