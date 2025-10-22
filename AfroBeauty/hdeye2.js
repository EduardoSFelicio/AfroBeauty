const eye = document.querySelector("#eye");
const eyeverif = document.querySelector("#eyeconfirm");
const inputpass = document.querySelector("#pass");
const inputpass_verif = document.querySelector("#pass_verif");
const cpf = document.querySelector("#cpf");
const eyecpf = document.querySelector("#eyecpf");

eye.addEventListener("click", function () {


    const type = inputpass.type === "password" ? "text" : "password";

    inputpass.type = type;

    this.classList.toggle("eye2");
    this.classList.toggle("eye_hide2");

});

eyeverif.addEventListener("click", function () {
    
    const type = inputpass_verif.type === "password" ? "text" : "password";

    inputpass_verif.type = type;

    this.classList.toggle("eyeconfirm2");
    this.classList.toggle("eyeconfirm_hide2");

});

eyecpf.addEventListener("click", function () {
    
    const type = cpf.type === "password" ? "text" : "password";

    cpf.type = type;

    this.classList.toggle("eye_cpf");
    this.classList.toggle("eye_cpf_hide");

});