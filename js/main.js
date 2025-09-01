window.addEventListener("DOMContentLoaded", () => {
    const openBtn = document.getElementById("openFormBtn");
    const closeBtn = document.getElementById("closeFormBtn");
    const popup = document.getElementById("popupForm");
  
    openBtn.addEventListener("click", () => {
      popup.hidden = false;
    });
  
    closeBtn.addEventListener("click", () => {
      popup.hidden = true;
    });
  });
  
  // Dropdown menu
function myFunction() {
  document.getElementById("myDropdown").classList.toggle("show");
}

// Close the dropdown if the user clicks outside of it
window.onclick = function (event) {
  if (!event.target.matches('.dropbtn')) {
      var dropdowns = document.getElementsByClassName("dropdown-content");
      var i;
      for (i = 0; i < dropdowns.length; i++) {
          var openDropdown = dropdowns[i];
          if (openDropdown.classList.contains('show')) {
              openDropdown.classList.remove('show');
          }
      }
  }
}