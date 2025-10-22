const eye = document.querySelector("#eye");
const eyeverif = document.querySelector("#eyeconfirm");
const inputpass = document.querySelector("#pass");
const inputpass_verif = document.querySelector("#pass_verif");


eye.addEventListener("click", function () {


    const type = inputpass.type === "password" ? "text" : "password";

    inputpass.type = type;

    this.classList.toggle("eye");
    this.classList.toggle("eye_hide");

});

eyeverif.addEventListener("click", function () {
    
    const type = inputpass_verif.type === "password" ? "text" : "password";

    inputpass_verif.type = type;

    this.classList.toggle("eyeconfirm");
    this.classList.toggle("eyeconfirm_hide");

});


