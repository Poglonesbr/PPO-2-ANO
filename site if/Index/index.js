document.addEventListener("DOMContentLoaded", function() {
  let elem_preloader = document.getElementById("preloader");
  let elem_loader = document.getElementById("loader");
  let circles = document.querySelectorAll(".circle");
  let shadows = document.querySelectorAll(".shadow");

  setTimeout(function() {
      elem_preloader.style.display = "none";
      elem_loader.style.display = "none";
      circles.forEach(circle => circle.style.display = "none");
      shadows.forEach(shadow => shadow.style.display = "none");
  }, 1500);
});


document.getElementById('color_mode').addEventListener('change', function() {
  if(this.checked) {
      document.body.classList.remove('light-mode');
      document.body.classList.add('dark-mode');
  } else {
      document.body.classList.remove('dark-mode');
      document.body.classList.add('light-mode');
  }
});
