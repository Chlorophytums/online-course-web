window.addEventListener("load", () => {
    const inputVideos = document.getElementById("upload-videos");
    const filewrapperVideos = document.getElementById("filewrapper-videos");

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


// document.addEventListener("DOMContentLoaded", function() {
//     const uploadImagesInput = document.getElementById('upload-images');
//     const uploadVideosInput = document.getElementById('upload-videos');

//     uploadImagesInput.addEventListener('change', handleFileSelect);
//     uploadVideosInput.addEventListener('change', handleFileSelect);

//     function handleFileSelect(event) {
//         const files = event.target.files;
//         const fileWrapperId = event.target.id === 'upload-images' ? 'filewrapper-images' : 'filewrapper-videos';
//         const fileWrapper = document.getElementById(fileWrapperId);
        
//         // Reset existing previews
//         fileWrapper.innerHTML = '';

//         for (let i = 0; i < files.length; i++) {
//             const file = files[i];
//             const fileType = file.type.split('/')[0]; // 'image' or 'video'
//             const reader = new FileReader();

//             reader.onload = function(e) {
//                 const previewElement = document.createElement(fileType === 'image' ? 'img' : 'video');
//                 previewElement.classList.add('preview-item');
//                 previewElement.src = e.target.result;
//                 previewElement.title = file.name;
//                 previewElement.alt = file.name;
//                 previewElement.setAttribute('controls', fileType === 'video');

//                 fileWrapper.appendChild(previewElement);
//             }

//             if (fileType === 'image') {
//                 reader.readAsDataURL(file);
//             } else if (fileType === 'video') {
//                 reader.readAsDataURL(file);
//             }
//         }
//     }
// });

