<!DOCTYPE HTML>
<!--
	TODO:
	   1) send success email
-->
<html>
	<head>
		<title>Sefirat HaOmer Reminders</title>
		<meta http-equiv="content-type" content="text/html; charset=utf-8" />
		<meta name="description" content="" />
		<meta name="keywords" content="" />
		<!--[if lte IE 8]><script src="css/ie/html5shiv.js"></script><![endif]-->
		<script src="js/jquery.min.js"></script>
		<script src="js/jquery.scrolly.min.js"></script>
		<script src="js/skel.min.js"></script>
		<script src="js/init.js"></script>
		<noscript>
			<link rel="stylesheet" href="css/skel.css" />
			<link rel="stylesheet" href="css/style.css" />
			<link rel="stylesheet" href="css/style-xlarge.css" />
		</noscript>
		<!--[if lte IE 9]><link rel="stylesheet" href="css/ie/v9.css" /><![endif]-->
		<!--[if lte IE 8]><link rel="stylesheet" href="css/ie/v8.css" /><![endif]-->
		
		<style type="text/css">
		  .center-text{
		       text-align: center;
		  }
		  
		  .error-msg{
		      color: red; 
		      display: none;  
		  }
		</style>
	</head>
	<body>

		<!-- Header -->
			<section id="header">
				<div class="inner">
					<span class="icon major fa-cloud"></span>
					<h1><strong>Sefirat HaOmer</strong> Reminders</h1>
					<p>Get nightly texts or emails so you never forget to count sefirah</p>
					<ul class="actions">
						<li><a href="#signup" class="button scrolly">Sign Up</a></li>
					</ul>
				</div>
			</section>		
		
			<section id="signup" class="main style1">
				<div class="container">
					<header class="major special">
						<h2>Sign Up For Reminders</h2>
					</header>										

                    <p class="center-text">
                    <?php                                     			
            			if(!empty($_POST['email']) || !empty($_POST['phoneEmail'])){ 
            			    $email = $_POST['email'];
            			    $phoneEmail = $_POST['phoneEmail'];
                            $timestamp = date("m/d/y H:i:s", time());            			 
            				            		
            				$signupFile = dirname(__FILE__) . '/../data/omer-signup.csv';            				
        					$omerSignupContents = file_get_contents($signupFile);
        					$file = fopen($signupFile,"a") or exit("Error!");
        					
        					$emailSuccess = false;
        					$phoneSuccess = false;
        					
        					if (!empty($email) && strlen($email) >= 8) // 8 = strlen("ab@12.il")
        					{
              					if(strpos($omerSignupContents, $email) === false){
              						$emailSuccess = fwrite($file, "\n" . $email . "," . $timestamp ) > 0;
              						
              						if($emailSuccess){
              						    require_once(dirname(__FILE__) . '/../app/EmailController.php');
              						    EmailController::sendWelcomeMessage($email);
              						}else{
                                          echo '<span style="color:red;">There was an error saving your email.</span>';
                                    }              						              						
              					}else{
              						echo '<span style="color:green;">We already have your email. You will continue to be notified about the Omer. Thank you!</span>';
              					}
        					}
        					
        					if (!empty($phoneEmail) && strlen($phoneEmail) >= 20) // 20 = strlen("1112223333@vmobl.com")
        					{
              					if(strpos($omerSignupContents, $phoneEmail) === false){
              					    $phoneSuccess = fwrite($file, "\n" . $phoneEmail . "," . $timestamp ) > 0;
      
              						if($phoneSuccess){
              						    require_once(dirname(__FILE__) . '/../app/EmailController.php');
              						    EmailController::sendWelcomeMessage($phoneEmail);
              						}else{
                                        echo '<span style="color:red;">There was an error saving your phone number.</span>';
                                    }              						              						
              					}else{
              						echo '<span style="color:green;">We already have your phone number. You will continue to be notified about the Omer. Thank you!</span>';
              					}
        					}
        					
        					if ($emailSuccess && $phoneSuccess){
        					     echo '<span style="color:green;">Thanks!</span><br>';
        						 echo '<span style="color:green;"> You have successfully signed up to receive Sefirah reminders.</span>';
        					}else if ($emailSuccess || $phoneSuccess){
        					     $methodOfNotification = $emailSuccess ? "email" : "phone";
        					     echo '<span style="color:green;">Thanks!</span><br>';
        						 echo '<span style="color:green;"> You have successfully signed up to receive Sefirah reminders to your '.$methodOfNotification.'.</span>';
        					}else{
        						 echo '<br><span style="color:red;"> Please try again!</span>';
        					}
        					        					
        					fclose($file);
        			 	}
            		?>
                    </p>

					<section>
						<form id="signupForm" method="post" action="#signup">
							<div class="row uniform 50% ">								
								<div class="-3u 4u">    								
									<input type="text" name="phone" id="phone" placeholder="Phone Number" maxlength="14" class="center-text" />
									<input type="hidden" name="phoneEmail" id="phoneEmail" />
								</div>
								<div class="2u$">
								    <div class="select-wrapper">
										<select id="carrier" name="carrier">
											<option value="0">Phone Carrier</option>
											<option value="message.alltel.com">Alltel</option>
											<option value="mms.att.net">AT&T</option>
											<option value="myboostmobile.com">Boost Mobile</option>
											<option value="messaging.nextel.com">Nextel</option>
											<option value="pm.sprint.com">Sprint PCS</option>
											<option value="tmomail.net">T-Mobile</option>
											<option value="mms.uscc.net">US Cellular</option>
											<option value="vzwpix.com">Verizon</option>
											<option value="vmobl.com">Virgin Mobile USA</option>
										</select>
									</div>
							    </div>
                            </div>
                            <div class="row uniform 50% ">
								<div class="-3u 6u$">
								    <div id="phone-error" class="center-text error-msg"></div>
									<p class="small center-text">OR</p>
								</div>
						    </div>
                            <div class="row uniform 50% ">
								<div class="-3u 6u$">									
									<input type="email" name="email" id="email" placeholder="Email" class="center-text" />
								</div>
							</div>
                            <div class="row uniform 50% ">
								<div class="-3u 6u$">
								    <div id="email-error" class="center-text error-msg"></div>
								    <input type="submit" name="submit" value="Sign Up" class="special fit" />
								</div>
							</div>
                            <div class="row uniform 50% ">
								<div class="-3u 6u$">
								    <p class="small center-text">You will be alerted at 9:00PM EST every night of Sefirah</p>
								</div>
							</div>
						</form>
					</section>

				</div>
			</section>
		

		<!-- Footer -->
			<section id="footer">
				<ul class="icons">
					<li>
					   <a href="https://twitter.com/hashtag/sefirathaomer" class="icon alt fa-twitter">
					       <span class="label">Twitter</span>
					   </a>
					</li>
					<li>
					   <a href="https://www.facebook.com/search/str/sefirat%20haomer/keywords_top" class="icon alt fa-facebook">
					       <span class="label">Facebook</span>
					   </a>
					</li>
					<li>
					   <a href="https://instagram.com/p/nwIe0kS-Sd/" class="icon alt fa-instagram">
					       <span class="label">Instagram</span>
					   </a>
					</li>
					<li>
					   <a href="https://github.com/bfeldman24/Sefirat-HaOmer-Reminders" class="icon alt fa-github">
					       <span class="label">GitHub</span>
					   </a>
					</li>
					<li>
					   <a href="mailto:ben@bprowd.com" class="icon alt fa-envelope">
					       <span class="label">Email</span>
					   </a>
				    </li>
				</ul>
				<ul class="copyright">
					<li>&copy; Sefirat HaOmer Reminders</li>
					<li><a href="/unsubscribe">Unsubscribe</a></li>
					<li><a href="mailto:ben@bprowd.com">Contact</a></li>
					<li>Design: <a href="http://html5up.net">HTML5 UP</a></li>
				</ul>
			</section>
			
    <script type="text/javascript">
        var Omer = {
            init: function(){
                 $("#signupForm").on("submit", Omer.handleSignupSubmit);
            },
            
            handleSignupSubmit: function(e){
                                                
                var phone = $("#phone").val();
                var carrier = $("#carrier").val();
                var email = $("#email").val();
                
                // Validate Email
                var emailInvalid = (email == null || email.length < 10 || email.indexOf("@") <= 0);
                if (emailInvalid && email){                                                       
                    $("#email-error").text("The email address you entered is invalid!").show();
                }else{
                    $("#email-error").text("").hide();
                }
                
                // Validate Phone
                var phoneInvalid = (phone == null || phone.length < 10 || phone.length > 14);
                var carrierInvalid = carrier == 0;
                
                if (phoneInvalid && phone){         
                    $("#phone-error").text("The phone number you entered is invalid!").show();                    
                    $("#phoneEmail").val("");
                }else if (carrierInvalid && phone){
                    $("#phone-error").text("Please choose a phone carrier!").show();
                    $("#phoneEmail").val("");
                }else{
                    phone = phone.replace(/\D/g, ''); // strip non numeric characters
                    $("#phone-error").text("").hide();
                    $("#phoneEmail").val(phone + "@" + carrier);
                }
                
                if (emailInvalid && phoneInvalid && !email && !phone){
                    $("#email-error").text("Please enter either a phone number or email address!").show();
                }
                
                return !emailInvalid || (!phoneInvalid && !carrierInvalid);   
            }
        };
        
        Omer.init();
    
    </script>
    

	</body>
</html>