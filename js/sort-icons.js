const sortLinks = document.querySelectorAll("#sort-link");
const sortList = document.getElementById('sort-list')

sortLinks.forEach((sortLink) => {
    let isImage1 = true;

    sortLink.addEventListener("click", (event) => {
        event.preventDefault();

        const imagePath1 = 'https://flyclipart.com/thumb2/chevron-up-font-awesome-619873.png';
        const imagePath2 = 'https://w7.pngwing.com/pngs/679/153/png-transparent-computer-icons-down-arrow-miscellaneous-cdr-angle.png';

        const img = document.createElement("img");
        img.src = isImage1 ? imagePath1 : imagePath2;
        img.classList.add('w-3', 'ml-2')

        const li = sortLink.querySelector("li");
        const lis = sortList.querySelectorAll("li");

        lis.forEach(li => {
            li.classList.add('border-b');
        }); 

        li.classList.remove('border-b')

        const existingImages = sortList.querySelectorAll("img");

        existingImages.forEach((existingImg) => {
            existingImg.remove();
        });

        li.insertAdjacentElement("afterend", img);

        isImage1 = !isImage1;
    });
});
