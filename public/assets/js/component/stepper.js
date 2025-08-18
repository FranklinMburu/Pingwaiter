document.addEventListener("DOMContentLoaded", () => {
  let current_fs, next_fs, previous_fs; //fieldsets
  document
    .querySelectorAll("#progressbar .steper-step")
    .forEach((item, index) => {
      item.setAttribute("data-step", `${index + 1}`);
    });

  document.querySelectorAll(".next-form").forEach((nextButton) => {
    nextButton.addEventListener("click", function () {
      console.log(this);
      current_fs = this.parentElement;
      next_fs = this.parentElement.nextElementSibling;
      // Add Active Class
      document
        .querySelectorAll("#progressbar .steper-step")
        [
          Array.from(document.querySelectorAll(".fieldset")).indexOf(next_fs)
        ].classList.add("active");

      // Hide the current fieldset
      current_fs.classList.remove("!block");

      // Show the next fieldset
      next_fs.classList.add("!block");
    });
  });

  document.querySelectorAll(".previous-form").forEach((previousButton) => {
    previousButton.addEventListener("click", function () {
      current_fs = this.parentElement;
      previous_fs = this.parentElement.previousElementSibling;
      // Remove class active
      document
        .querySelectorAll("#progressbar .steper-step")
        [
          Array.from(document.querySelectorAll(".fieldset")).indexOf(current_fs)
        ].classList.remove("active");

      // Hide the current fieldset
      current_fs.classList.remove("!block");

      // Show the previous fieldset
      previous_fs.classList.add("!block");
    });
  });
});
