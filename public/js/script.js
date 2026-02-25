// ovo koristim za prikazivanje slika kada se pretisne na thumbnail konkretnog proizvoda
function showFullImage(src) {
    document.getElementById("imageModal").style.display = "block";
    document.getElementById("fullImage").src = src;
}

function hideFullImage() {
    document.getElementById("imageModal").style.display = "none";
}
