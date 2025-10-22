<!DOCTYPE html>
<html lang="en">
<?php  require("head.html");?>
<body>
<?php  require("navbar.html"); include __DIR__ . "api/get_tranca.php"; ?>

<div class="cSobre_new">

    <div class="pesq_bar">
        <input type="text" name="" id="">
    </div>

    <div class="sobre_imgs_out">

        <div class="sobre_imgs_in">

            <div class="first_line">

                <?php
                //  @session_start();
                //   foreach ($_SESSION['ser'] as $foto) {
                    ?> <!-- <div><img src=" <?php // echo "api/".$_SESSION['ser'];?>" alt=""></div> -->
                <?php
                //   }
                ?>

                <div><img src="easybraids/assets/fotos/afro-braid-1.png" alt=""></div> 
                <div><img src="easybraids/assets/fotos/afro-braid-3.jpg" alt=""></div>
                <div><img src="easybraids/assets/fotos/afro-braid-11.png" alt=""></div>

            </div>

            <div class="second_rows">

                <div class="Left">

                    <div class="l1">
                        <div><img src="easybraids/assets/fotos/afro-braid-5.png" alt=""></div>
                        <div><img src="easybraids/assets/fotos/afro-braid-6.jpg" alt=""></div>
                        <div><img src="easybraids/assets/fotos/afro-braid-4.png" alt=""></div>
                    </div>

                    <div class="l2">
                        <div><img src="easybraids/assets/fotos/afro-braid-7.png" alt=""></div>
                        <div><img src="easybraids/assets/fotos/afro-braid-8.png" alt=""></div>
                    </div>

                    <div class="l3">
                        <div><img src="easybraids/assets/fotos/afro-braid-26.png" alt=""></div>
                        <div><img src="easybraids/assets/fotos/afro-braid-24.jpg" alt=""></div>
                    </div>

                </div>

                <div class="right">

                    <div class="r1">
                        <div class="r1_2"><img src="easybraids/assets/fotos/afro-braid-18.png" alt=""></div>
                        <div class="r1_3"><img src="easybraids/assets/fotos/afro-braid-9.png" alt=""></div>
                        <div class="r1_4"><img src="easybraids/assets/fotos/afro-braid-19.png" alt=""></div>
                        <div class="r1_5"><img src="easybraids/assets/fotos/afro-braid-10.png" alt=""></div>
                    </div>
                    <div class="r2">
                        <div class="r2_1"><img src="easybraids/assets/fotos/afro-braid-17.png" alt=""></div>
                        <div class="r2_2"><img src="easybraids/assets/fotos/afro-braid-16.png" alt=""></div>
                        <div class="r2_3"><img src="easybraids/assets/fotos/afro-braid-13.png" alt=""></div>
                    </div>  
                    <div class="r3">
                        <div class="r3_1"><img src="easybraids/assets/fotos/afro-braid-21.png" alt=""></div>
                        <div class="r3_2"><img src="easybraids/assets/fotos/afro-braid-22.png" alt=""></div>
                    </div> 

                </div>

            </div>

        </div>

    </div>




</div>


<?php  require("footer.html");?>
</body>
</html>