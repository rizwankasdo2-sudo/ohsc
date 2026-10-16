<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>

    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

    <title>Administrator Login</title>

    <link rel="stylesheet" href="../Resources/admin_css/admin-login.css" />

</head>

<body>

<div id="wrap_login">

    <div id="header2">

        <img src="../images/logo_new.png" height="80" />
    </div>

    <div id="outer_contain">

        <div id="login-panel">

            <div id="leftimg">
            </div>

            <div id="image_repeat">

                <div id="homeimage">
                </div>

                <div id="descrp">

                    <h3>Please enter your login details</h3>

                </div>

            </div>

            <div id="right_img">
            </div>

            <div id="curv_content" class="round">

                <div id="fields">

                    <div id="lock">

                        <img src="../Resources/admin_images/lock.jpg" alt="" />

                    </div>

                    <div id="namepas">

                        <?php if (isset($_GET['msg']))
                        {
                            echo "Enter the user name and password";
                        }

                        elseif (isset($_GET['no']))
                        {
                            echo "Invalid username or password";
                        }
                        ?>

                        <form action="login.php" method="post" >

                            <p>Login</p>
                            <input value="" name="username" type="text" class="txtx"  onclick="this.value=''" size="32" />

                            <p style="margin-top:15px;">Password</p>
                            <input value="" class="txtx"  name="password" type="password" onclick="this.value=''" size="32" />

                            <div style="clear:both; margin-top:7px;"></div>

                            <!--<a href="#">Forgot your password</a>-->

                            <div style="clear:both">&nbsp;</div>

                            <input value="Login" type="submit" class="submit" />

                        </form>

                    </div>

                </div>

            </div>

        </div>

        <div id="footer2">

            <center>

                <p><strong>Oxford Learning Home </strong>© 2011, All Rights Reserved</p>
                <p>Designed & Developed by : <a href="http://www.solutionica.com" target="_blank" title="Website design and  website development company"><strong>Solutionica</strong></a></p>

            </center>

        </div>

    </div>

</div>

</body>
</html>