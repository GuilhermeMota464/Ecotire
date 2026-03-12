function toggleMenu() {
    const menu = document.getElementById("menu-links");
    if (menu.style.display === "block") {
        menu.style.display = "none";
    } else {
        menu.style.display = "block";
    };

    if (menu.style.paddingBottom === "0px") {
        menu.style.paddingBottom = "10px";
    }else {
        menu.style.paddingBottom = "0px";
    };
    const icon = document.getElementById("icon");
    if (icon.style.backgroundColor === "var(--cor-primaria-escura)") {
        icon.style.backgroundColor = "var(--cor-primaria)";
    }else{
        icon.style.backgroundColor = "var(--cor-primaria-escura)"
    }   
}


document.getElementById("telefone").addEventListener("input", function () {

    this.value = this.value.replace(/\D/g, "");
    if (this.value.length > 11) {
        this.value = this.value.slice(0, 11);
    }
    let formattedValue = this.value;
    if (formattedValue.length > 0) {
        formattedValue = "(" + formattedValue;
    }
    if (formattedValue.length > 3) {
        formattedValue = formattedValue.slice(0, 3) + ") " + formattedValue.slice(3);
    }
    if (formattedValue.length > 10) {
        formattedValue = formattedValue.slice(0, 10) + "-" + formattedValue.slice(10);
    }
    this.value = formattedValue;
});
