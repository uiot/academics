<?php

$message = "";
$username = isset($_POST["user"]) ? escape_text($_POST["user"]) : "none";
$password = isset($_POST["password"]) ? escape_text($_POST["password"]) : "none";

if (($username != "none") && ($password != "none")):
    $count = UsersMapper::authenticate($username, $password);
    if ($count != 0):
        if ($count != 'username'):
            $db_real_name = $count['TE_Name'];
            $db_id = $count['PK_Id'];
            session_start();
            $_SESSION['u_name'] = $username;
            $_SESSION['u_real'] = $db_real_name;
            $_SESSION['u_id'] = $db_id;
            redirect("/home/index/");
        else:
            $message["text"] = "wrong username.";
            $message["type"] = "username";
        endif;
    else:
        $message["text"] = "wrong password.";
        $message["type"] = "password";
    endif;
endif;
?>
<div class="nope obese"></div>
<div class="nope obese"></div>
<div class="row">
    <div class="large-3 columns"><br/></div>
    <div class="large-6 columns">
        <div class="panel radius" style="background:none;border:0px solid transparent;">
            <div
                style="position: absolute;opacity: 0.9;width: 100%;background-color: white;padding: 1.25rem;border-radius: 3px;height: 100%;border: 1px solid white;top: 0;left: 0;"></div>


            <div class="nope"></div>
            <form action="<?php echo link_to("/login/login/"); ?>" method="POST"/>
            <div class="row">

                <div class="large-6 columns">
                    <h5>Login</h5>
                    <?php
                    if (is_array($message) && ($message["type"] == "username")):
                        echo "<div class=\"row\">
                                    <div class=\"large-10 columns\">
                                        <label class=\"error\">Username:
                                            <input type=\"text\" name=\"user\" class=\"error\"/>
                                        </label>
                                        <small class=\"error\">" . $message["text"] . "</small>
                                    </div>
                                </div>";
                    else:
                        echo "<div class=\"row\">
                                    <div class=\"large-10 columns\">
                                        <label>Username:
                                            <input type=\"text\" name=\"user\" />
                                        </label>
                                    </div>
                                </div>";
                    endif;
                    if (is_array($message) && ($message["type"] == "password")):
                        echo "<div class=\"row\">
                                    <div class=\"large-10 columns\">
                                        <label class=\"error\">Password:
                                            <input type=\"password\" name=\"password\" class=\"error\"/>
                                        </label>
                                        <small class=\"error\">" . $message["text"] . "</small>
                                    </div>
                                </div>";
                    else:
                        echo "<div class=\"row\">
                                    <div class=\"large-10 columns\">
                                        <label>Password:
                                            <input type=\"password\" name=\"password\" />
                                        </label>
                                    </div>
                                </div>";
                    endif;
                    ?>
                </div>
                <div class="large-6 columns">
                    <p>Welcome to UIoT administratin panel.<br/> This section is restricted to registered
                        users. </p>
                    <input class="button secondary small radius" type="submit" name="submit"
                           value="Login"/>
                </div>
                </form>
            </div>
        </div>
    </div>
    <div class="large-3 columns"><br/></div>
</div>



