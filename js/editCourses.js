document.addEventListener("DOMContentLoaded", function() {
    const filewrapperVideos = document.getElementById("filewrapper-videos");

    // Tambahkan event listener untuk setiap tombol silang
    filewrapperVideos.addEventListener("click", function(e) {
        if (e.target.classList.contains("remove-video")) {
            const videoUrl = e.target.getAttribute("data-video");
            
            e.target.closest(".showfilebox").remove();
        }
    });

    const inputVideos = document.getElementById("upload-videos");

    inputVideos.addEventListener("change", function(e) {
        handleFileUpload(e, filewrapperVideos);
    });

    function handleFileUpload(e, filewrapper) {
        Array.from(e.target.files).forEach(file => {
            let filename = file.name;
            let filetype = file.type.split("/").pop();
            fileshow(filename, filetype, filewrapper);
        });
    }

    function fileshow(filename, filetype, filewrapper) {
        const showfileboxElem = document.createElement("div");
        showfileboxElem.classList.add("showfilebox");

        const leftElem = document.createElement("div");
        leftElem.classList.add("left");

        const filetypeElem = document.createElement("span");
        filetypeElem.classList.add("filetype");
        filetypeElem.textContent = filetype;

        const filetitleElem = document.createElement("h3");
        filetitleElem.textContent = filename;

        const rightElem = document.createElement("div");
        rightElem.classList.add("right");

        const crossElem = document.createElement("span");
        crossElem.innerHTML = "&#215;";
        crossElem.classList.add("remove-video");
        crossElem.setAttribute("data-video", filename); // Ganti dengan videoUrl jika memungkinkan

        leftElem.appendChild(filetypeElem);
        leftElem.appendChild(filetitleElem);
        showfileboxElem.appendChild(leftElem);
        showfileboxElem.appendChild(rightElem);
        rightElem.appendChild(crossElem);
        filewrapper.appendChild(showfileboxElem);

        crossElem.addEventListener("click", () => {
            filewrapper.removeChild(showfileboxElem);
        });
    }
});
