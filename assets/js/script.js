function openNav() {
    document.getElementById("mySidebar").style.width = "250px";
}

function closeNav() {
    document.getElementById("mySidebar").style.width = "0";
}

window.onclick = function (event) {
    if (!event.target.matches('.logo-container') && !event.target.matches('.logo-container *') && !event.target.matches('.sidebar') && !event.target.matches('.sidebar *')) {
        // closeNav();
    }
}
