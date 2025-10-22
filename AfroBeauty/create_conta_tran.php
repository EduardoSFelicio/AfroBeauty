<!DOCTYPE html>
<html lang="en">
<?php require("head.html")?>
<body>
<?php require("navbar.html")?>    

<div class="cConta">

    <div class="c"><img src="easybraids/assets/icones/icone-trancista-4x.png" alt=""></div>
    <div class="div6">
        <div>
            <h1>Seja bem-vindo,</h1>
            <p>trancista, crie sua conta.</p>
        </div>

        <form action="api/insert_trancista.php" method="post">

            <div class="form">

                <label for="">Nome</label>
                <input type="text" name="name" id="name" placeholder="Seu nome">

                <label for="">E-mail</label>
                <input type="email" name="email" id="email" placeholder="Seu e-mail">


                <div class="div_out_input">
                    <label for="">Senha</label>
                    <input type="password" name="password" id="pass" placeholder="Sua senha">
                    <div class="eye_hide2" id="eye"></div>
                </div> 

                <div class="div_out_input">
                    <label for="">Confirmar Senha</label>
                    <input type="password" name="" id="pass_verif" placeholder="Confirme sua senha">
                    <div class="eyeconfirm_hide2" id="eyeconfirm"></div>
                </div>      

                <div class="div_out_input">
                    <label for="">CPF/CNPJ</label>
                    <input type="text" name="cpfcnpj" id="cpf" placeholder="CPF/CNPJ">
                    <div class="eye_cpf" id="eyecpf"></div>
                </div> 
                
                <label for="">Telefone</label>
                <input type="text" name="phone" id="tel" placeholder="Seu telefone">

            </div>

            <div class="slaa">
                
                <input type="date" name="birth" id="date" placeholder="Data de nasc">
            
                <select class="select" name="gender" id="genero" >
                    <option value="none">Gênero</option>
                    <option value="masc">Masculino</option>
                    <option value="fem">Feminino</option>
                    <option value="Outros">Outros</option>
                </select>

            </div>

            <button class="btn">Próximo</button>
        </form>

    </div>

</div>

    <script type="text/javascript">
        $("#tel").mask("(00) 00000-0000");
    </script>
<script src="cpf/cpf_validate.js"></script>
<script src="hdeye2.js"></script>
<?php require("footer.html")?>
</body>
</html>