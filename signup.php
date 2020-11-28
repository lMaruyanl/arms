
<main>
    <div>
        <form action="source/signup_s.php" method="post">
            <div>
            <?php 
                if(isset($_GET['user'])){
                    $user = $_GET['user'];
                   echo '<input class="ns" type="text" name="uname" value="'.$user.'">';
                }
                else{
                    echo'<input class="ns" type="text" name="uname" placeholder="Username">';
                }
            ?>
            </div>
            <div>
            <?php 
                if(isset($_GET['email'])){
                    $email = $_GET['email'];
                   echo '<input class="ns" type="text" name="email" value="'.$email.'">';
                }
                else{
                    echo'<input class="ns" type="text" name="email" placeholder="Email_Address">';
                }
            ?>
            </div>
            <div>
            <input type="text" name="tenant">
            </div>
            <div>
            <input type="text" name="username">
            </div>
            <div>
            <input class="ns" type="password" name="pwd" placeholder="Password">
            </div>
            <div>
                <input class="ns" type="text" name="answer" placeholder="usertype">
            </div>
            <div>
            <button class="botton" type="submit" name="submit_signup">Submit</button>
            </div>
        </form>
    </div>
</main>