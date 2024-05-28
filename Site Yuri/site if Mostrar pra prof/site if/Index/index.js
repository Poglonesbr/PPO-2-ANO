let elem_preloader = document.getElementById("preloader");
let elem_loader = document.getElementById("loader");
let circle = document.getElementById("circle");
let elem_shadow = document.getElementById("shadow");
console.log("Testing... Ok");

setTimeout(function() {
  elem_preloader.classList.remove("preloader");
  elem_loader.classList.remove("loader");
  circle.style.display = "none"; 
  circle2.style.display = "none"; 
  circle3.style.display = "none"; 
  elem_shadow.style.display = "none"; 
}, 2500);
