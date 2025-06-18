// JavaScript functions for toggling forms
function toggleEditForm() {
    var form = document.getElementById("editForm");
    form.style.display = form.style.display === "block" ? "none" : "block";
}

function toggleDeleteForm() {
    var form = document.getElementById("deleteForm");
    form.style.display = form.style.display === "block" ? "none" : "block";
}

// Confirmation for delete
function confirmDelete() {
    return confirm("Are you sure you want to delete this student record?");
}