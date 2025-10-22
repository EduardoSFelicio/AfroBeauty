let count = 1;
document.getElementById("radio1").checked = true;

setInterval( function() {
    nextImage();
}, 4000)


function nextImage(){
    count++;
    if (count>4) {
        count = 1;
    }

    document.getElementById("radio"+count).checked = true;

}

const myObserver = new IntersectionObserver ((entries) =>{
    entries.forEach((entry) => {
        if (entry.isIntersecting) {
            entry.target.classList.add('txtSlideMod')
        }else{
            entry.target.classList.remove('txtSlideMod')
        }
    })
})

const elements = document.querySelectorAll('.txtSlide')

elements.forEach((element) => myObserver.observe(element));





