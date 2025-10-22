<!DOCTYPE html>
<html lang="en">
<?php require("head.html")?>
<body>
<?php require("navbar.html")?>    

<div class="cConta">

    <div class="c"><img src="easybraids/assets/icones/icone-cliente-4x.png" alt=""></div>
    <div class="div5">
        <div>
            <h1>Seja bem-vindo,</h1>
            <p>cliente, crie sua conta.</p>
        </div>

        <form action="api/insert_cliente.php" method="post">

            <div>

                <label for="">Nome</label>
                <input type="text" name="name" id="name" placeholder="Seu nome">

                <label for="">E-mail</label>
                <input type="email" name="email" id="email" placeholder="Seu e-mail">

                <div class="div_out_input">
                    <label for="">Senha</label>
                    <input type="password" name="password" id="pass" placeholder="Sua senha">
                    <div class="eye_hide" id="eye" onclick="troca()"></div>
                </div>
                
                <div class="div_out_input">
                    <label for="">Confirmar Senha</label>
                    <input type="password" name="" id="pass_verif" placeholder="Confirme sua senha">
                    <div class="eyeconfirm_hide" id="eyeconfirm" onclick="troca()"></div>
                </div>
                
                <label for="">Telefone</label>
                <input type="text" name="phone" id="tel" placeholder="Seu telefone">

            </div>
            <div class="slaa">
                <input type="date" name="birth" id="date" placeholder="dia/mês/ano">
            
                <select class="select" name="gender" id="genero" >
                    <option value="">Gênero</option>
                    <option value="masc">Masculino</option>
                    <option value="fem">Feminino</option>
                    <option value="Outros">Outros</option>
                </select>

            </div>

            <button>Próximo</button>
        </form>

    </div>

</div>

    <script type="text/javascript">
        $("#tel").mask("(00) 00000-0000");
    </script>

<script src="hide_eye.js"></script>
<?php require("footer.html")?>
</body>
</html>