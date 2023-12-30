<script>
  //navigation
  function toggleMenu() {
    const dropdownMenu = document.getElementById("dropdownMenu");
    dropdownMenu.classList.toggle("show");
  }

  // Close the dropdown menu if the user clicks outside of it
  window.onclick = function(event) {
    if (!event.target.matches(".menu-icon")) {
      const dropdownMenu = document.getElementById("dropdownMenu");
      if (dropdownMenu?.classList?.contains("show")) {
        dropdownMenu.classList.remove("show");
      }
    }
  };
  //navigation
</script>