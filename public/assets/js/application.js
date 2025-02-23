document.addEventListener("DOMContentLoaded", function () {
  const imagePreviewInput = document.getElementById("imagePreviewInput");
  const preview = document.getElementById("preview");
  const imagePreviewSubmit = document.getElementById("imagePreviewSubmit");

  if (!imagePreviewInput || !preview || !imagePreviewSubmit) return;

  imagePreviewInput.addEventListener("change", function (event) {
    const file = event.target.files[0];

    if (!file || !file.type.startsWith("image/")) {
      alert("Por favor, selecione uma imagem válida.");
      return;
    }

    const reader = new FileReader();
    reader.onload = function (e) {
      preview.src = e.target.result;
      imagePreviewSubmit.style.display = "block";
    };
    reader.readAsDataURL(file);
  });

  imagePreviewSubmit.addEventListener("click", function () {
    const file = imagePreviewInput.files[0];
    if (!file) return;

    const formData = new FormData();
    formData.append("image", file);

    fetch("/profile/upload", {
      method: "POST",
      body: formData,
    })
      .then((response) => response.json())
      .then((data) => {
        if (data.success) {
          preview.src = data.path;
          alert("Imagem enviada com sucesso!");
          imagePreviewSubmit.style.display = "none";
        } else {
          alert(data.message || "Erro ao enviar a imagem.");
        }
      })
      .catch(() => alert("Erro na requisição."));
  });
});
