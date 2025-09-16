<?php
session_start();
$rooms= [
    "room1" => [
        "image" =>"img/1.jpg.jpg",
        "Features"=>["Self contain","Kitchen", "Merged Bath and restroom","Tiled Floor" ,"In-flow Water" ],
        "Prices" =>"$500"
    ],
    "room2" => [
        "image" =>"img/2.jpg.jpg",
        "Features"=>["Self contain","Kitchen", "Merged Bath and restroom","Tiled Floor" ,"In-flow Water" ],
        "Prices" =>"$500"
    ],
    "room3" => [
        "image" =>"img/3.jpg.jpg",
        "Features"=>["Self contain","Kitchen", "Merged Bath and restroom","Tiled Floor" ,"In-flow Water" ],
        "Prices" =>"$500"
    ],
    "room4" => [
        "image" =>"img/4.jpg.jpg",
        "Features"=>["Self contain","Kitchen", "Merged Bath and restroom","Tiled Floor" ,"In-flow Water" ],
        "Prices" =>"$500"
    ],
    "room5" => [
        "image" =>"img/5.jpg.jpg",
        "Features"=>["Self contain","Kitchen", "Merged Bath and restroom","Tiled Floor" ,"In-flow Water" ],
        "Prices" =>"$500"
    ],
    "room6" => [
        "image" =>"img/6.jpg.jpg",
        "Features"=>["Self contain","Kitchen", "Merged Bath and restroom","Tiled Floor" ,"In-flow Water" ],
        "Prices" =>"$500"
    ],
    "room7" => [
        "image" =>"img/7.jpg.jpg",
        "Features"=>["Self contain","Kitchen", "Merged Bath and restroom","Tiled Floor" ,"In-flow Water" ],
        "Prices" =>"$500"
    ],
    "room8" => [
        "image" =>"img/8.jpg.jpg",
        "Features"=>["Self contain","Kitchen", "Merged Bath and restroom","Tiled Floor" ,"In-flow Water" ],
        "Prices" =>"$500"
    ],
    "room9" => [
        "image" =>"img/9.png",
        "Features"=>["Self contain","Kitchen", "Merged Bath and restroom","Tiled Floor" ,"In-flow Water" ],
        "Prices" =>"$500"
    ],
    "room10" => [
        "image" =>"img/10.jpg.jpg",
        "Features"=>["Self contain","Kitchen", "Merged Bath and restroom","Tiled Floor" ,"In-flow Water" ],
        "Prices" =>"$500"
    ],
    "room11" => [
        "image" =>"img/11.jpg.jpg",
        "Features"=>["Self contain","Kitchen", "Merged Bath and restroom","Tiled Floor" ,"In-flow Water" ],
        "Prices" =>"$500"
    ],
    "room12" => [
        "image" =>"img/12.jpg.jpg",
        "Features"=>["Self contain","Kitchen", "Merged Bath and restroom","Tiled Floor" ,"In-flow Water" ],
        "Prices" =>"$500"
    ],
    "room13" => [
        "image" =>"img/12.jpg.jpg",
        "Features"=>["Self contain","Kitchen", "Merged Bath and restroom","Tiled Floor" ,"In-flow Water" ],
        "Prices" =>"$500"
    ],
    "room14" => [
        "image" =>"img/12.jpg.jpg",
        "Features"=>["Self contain","Kitchen", "Merged Bath and restroom","Tiled Floor" ,"In-flow Water" ],
        "Prices" =>"$500"
    ]
    ];

// Create session array for occupied room
$_SESSION["booked_rooms"] = [];

$session_rooms =& $_SESSION["booked_rooms"];
?>
<?php
// Declare Prompt for success and error
$feedback="";
$error="";

// Check if the form request method is set to Post.
if ($_SERVER["REQUEST_METHOD"] == "POST"){

    // check if the specific submit button cicked
    if(isset($_POST["generate_otp"])){

        // Get the value of the input submitted.
        $name=trim($_POST["user"]);
        $email = trim($_POST["email"]);
        $contact = trim($_POST["contact"]);

            // Check if the input are not empty.
            if(empty($name) || empty($email) || empty($contact)){
                $error = "All Fields are requirred";
            }

            // Check if an accurate Email format is provided.
            elseif(!filter_var($email,FILTER_VALIDATE_EMAIL)){
                $error="Invalid Email Format";
            }
            // Define action.
            else{
                // Generate OTP
                $otp = str_pad(rand(0, 9999), "4", 0, STR_PAD_LEFT);
                            // Check if otp already exist in room id
                foreach($session_rooms as $keys => $rooms ){
                    if($rooms["room_id"] == $otp){
                        $otp = str_pad(rand(0, 99999), "5", 0, STR_PAD_LEFT);
                        break;
                    }
                    else{
                        continue;
                    }
                    }
                    // Assign Otp to Booked Room array
                    foreach($rooms as $keys => $values){
                        if(array_key_exists($keys,$session_rooms)){
                            continue;
                        }
                        else{

                                // Store User Data in a session variable.
                                    $_SESSION["User_Data"] = [
                                    "Username" => $name,
                                    "Email" => $email,
                                    "Contact" =>$contact,
                                    "exp_time" => time() + 120, // Valid for 2minutes
                                    "Booked room" => $keys,
                                    "Room Id" => $otp
                                ] ;

                                $feedback = "<h4>Enter Otp to Generate Room</h4><p class='feedback_otp'> Your OTP code is:  $otp</p>";
                            break; 
                        }                      
                    }               
            }
    }
    elseif(isset($_POST["confirm_otp"])){
        
        $otp_inputed =  trim($_POST["otp_code"]);

        if(empty($otp_inputed)){
            $error = "Please Enter the OTP provided";
        }
        elseif( $otp_inputed != $_SESSION["User_Data"]["Room Id"] ){
            $error = "Please Enter a Valid OTP digits";
        }
        elseif(empty($error)){
           if(time() > $_SESSION["User_Data"]["exp_time"] ){
                $error = "<h4>Oops; Try Again</h4>OTP has Expired;";
                unset($_SESSION["User_Data"]);
           } 
           else{
            // Assign room id and room key to booked room session variable.
            $session_rooms[$_SESSION["User_Data"]["Booked room"]]=$_SESSION["User_Data"]["Room Id"];
            // Format Users Information.
            $userData = implode("|", [date("Y-m-d H:i:s"),
                 $_SESSION["User_Data"]["Username"],
                 $_SESSION["User_Data"]["Email"],
                 $_SESSION["User_Data"]["Contact"],
                 $_SESSION["User_Data"]["Booked room"],
                 $_SESSION["User_Data"]["Room Id"]
            ]). PHP_EOL ;
            // Put formated Info in a text file
            file_put_contents("room_info.txt", $userData, FILE_APPEND | LOCK_EX );
            $successful ="Welcome to Our Luxury Hotel";
           }
        }
    }
    elseif(isset($_POST["back_btn"])){
        unset($_SESSION["User_Data"]);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Royal Deluxe Hotel</title>
    <link rel="icon" href="img/icon.jpg.jpg">
    <link rel="stylesheet" href="style.css">
</head>
    <body>
        <header>
            <img src="img/icon.jpg.jpg" width="100px" height="100px">
                <div class="brand">
                    <div class="name">
                        <h1>Royal Deluxe Hotel </h1>
                        <p>Our Rooms offer leisure Vibes and Entertainment.</p>
                    </div>
                    <ul>
                        <li><a href="#">About</a></li>|
                        <li><a  href="#">Contact us</a></li>
                    </ul>
            </div>
        </header>
            <section>
            <?php
         
                if(!empty($error)){
                     echo "<div class='error'>$error</div>";
                }
                    if(isset($_SESSION["User_Data"]) && isset($successful)){
            ?>
            <!-- Show Room for user. -->
                <div class="indicator">
                    <div class="step active">1</div>
                    <div class="arrow active"></div>
                    <div class="step active">2</div> 
                    <div class="arrow active"></div>
                    <div class="step active">3</div> 
                </div>



                <?php  }
                 elseif(!isset($_SESSION["User_Data"]) && !isset($successful)){ 
                ?>
            <!-- Form Registration -->
                <div class="indicator">
                    <div class="step active">1</div>
                    <div class="arrow"></div>
                    <div class="step">2</div> 
                    <div class="arrow"></div>
                    <div class="step">3</div> 
                </div>

                    <h3>Provide Your Detail to Book Room</h3>
                <form method="POST" action="<?php echo $_SERVER["PHP_SELF"]?>">
                    <label for="username"> Username:</label>
                    <input id="username" type="text" placeholder="Provide your Username" name="user">

                    <label for="email"> Email:</label>
                    <input type="text" id="email" placeholder="Provide Email" name="email">

                    <label for="number">Phone Number:</label>
                    <input type="tel" placeholder="123-45-824" id="number" name="contact" pattern="[0-9]{11}">

                    <button type="submit"  class="submit"  name="generate_otp">Generate OTP</button>
                </form>
            <?php
             }
                elseif(isset($_SESSION["User_Data"]) && !isset($successfull) ){

                    // Access otp from the  User Data Session Variable
                    $otp = $_SESSION["User_Data"]["Room Id"];

                    // Define counter for otp to expire
                        $count=floor(($_SESSION["User_Data"]["exp_time"]  - time()) / 60);
                        $_SESSION["User_Data"]["timer"] = $count;
                        $exptime = $_SESSION["User_Data"]["timer"];

                    if(!empty($feedback)){
                        echo "<div class='feedback'>$feedback</div>";
                    }
                
            ?>

                <div class="indicator">
                    <div class="step active">1</div>
                    <div class="arrow active"></div>
                    <div class="step active">2</div> 
                    <div class="arrow"></div>
                    <div class="step">3</div> 
                </div>


                <div class="instruction">
                    <h4>
                        Enter OTP to continue:
                        <p class="otp"> <?php echo $otp ; ?></p>
                        <p> OTP expires in <?php echo $exptime; ?>min</p>
                    </h4>
                </div>

                <form method="POST" action="<?php echo $_SERVER['PHP_SELF'];?>">
                    <label for="otp">
                        Enter OTP below:
                    </label>
                    <input name="otp_code" type="tel" placeholder="Enter OTP" min="4" id="otp">
                    <button type="submit" name="confirm_otp" class="submit" >Confirm OTP</button>
                </form>
                <form method="POST">
                    <button type="submit" class="back_btn" name="back_btn" > <--- back 
                    </button>
                </form>
            <?php
                }
            ?>
            </section>
        <footer>
            <p>&copy;<?php echo " "." ".date("Y"); ?> All right reserved </p>
        </footer>
    </body>
</html>