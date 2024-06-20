window.addEventListener("load", () => {
    const inputVideos = document.getElementById("upload-video");
    const filewrapperVideos = document.getElementById("filewrapper-video");

    const handleFileUpload = (e, filewrapper) => {
        Array.from(e.target.files).forEach(file => {
            let filename = file.name;
            let filetype = file.type.split("/").pop();
            fileshow(filename, filetype, filewrapper);
        });
    };

    inputVideos.addEventListener("change", (e) => {
        handleFileUpload(e, filewrapperVideos);
    });

    const fileshow = (filename, filetype, filewrapper) => {
        const showfileboxElem = document.createElement("div");
        showfileboxElem.classList.add("showfilebox");

        const leftElem = document.createElement("div");
        leftElem.classList.add("left");

        const filetypeElem = document.createElement("span");
        filetypeElem.classList.add("filetype");
        filetypeElem.innerHTML = filetype;

        const filetitleElem = document.createElement("h3");
        filetitleElem.innerHTML = filename;

        const rightElem = document.createElement("div");
        rightElem.classList.add("right");

        const crossElem = document.createElement("span");
        crossElem.innerHTML = "&#215;";

        leftElem.append(filetypeElem);
        leftElem.append(filetitleElem);
        showfileboxElem.append(leftElem);
        showfileboxElem.append(rightElem);
        rightElem.append(crossElem);
        filewrapper.append(showfileboxElem);

        crossElem.addEventListener("click", () => {
            filewrapper.removeChild(showfileboxElem);
        });
    };
});