document.addEventListener("DOMContentLoaded", function () {
  const prevButton = document.querySelector(".prev-button");
  const nextButton = document.querySelector(".next-button");
  const articles = document.querySelectorAll(".article");
  const totalArticles = articles.length;
  let currentIndex = 0;

  prevButton.addEventListener("click", () => {
      if (currentIndex > 0) {
          articles[currentIndex].classList.remove("active");
          currentIndex--;
          articles[currentIndex].classList.add("active");
      }
  });

  nextButton.addEventListener("click", () => {
      if (currentIndex < totalArticles - 1) {
          articles[currentIndex].classList.remove("active");
          currentIndex++;
          articles[currentIndex].classList.add("active");
      }
  });

  // Assurez-vous d'appeler cette fonction pour afficher le premier article au chargement de la page
  articles[currentIndex].classList.add("active");
});
