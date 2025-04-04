
const searchInput = document.getElementById('searchContact');
const resultsList = document.getElementById('searchResults');

if (searchInput) {
  searchInput.addEventListener('input', async () => {
    const query = searchInput.value.trim();
    if (!query) {
      resultsList.innerHTML = '';
      return;
    }
    try {
      const res = await fetch("Messages?query=" + encodeURIComponent(query));
      if (!res.ok) throw new Error("Erreur lors de la recherche");
      const users = await res.json();
      resultsList.innerHTML = '';
      users.forEach(user => {
        const li = document.createElement('li');
        li.classList.add("contact-item");
        li.textContent = user;
        li.addEventListener('click', () => {
          window.location.href = "messages?contact=" + encodeURIComponent(user);
        });
        resultsList.appendChild(li);
      });
    } catch (error) {
      console.error(error);
    }
  });
}

