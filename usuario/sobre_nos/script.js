function toggleMenu() {
    var menu = document.getElementById("menu-links");

    if (menu.style.display === "flex") {
        menu.style.display = "none";
    } else {
        menu.style.display = "flex";
    }
}