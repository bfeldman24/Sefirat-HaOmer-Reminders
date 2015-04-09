<!DOCTYPE HTML>
<!--
	TODO:
	   1) send success email
-->
<html>
	<head>
		<title>Unsubscribe From Sefirat HaOmer Reminders</title>
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
					<h1>Unsubscribe from <strong>Sefirat HaOmer</strong> Reminders</h1>
					<p>Get nightly texts or emails so you never forget to count sefirah</p>
					<ul class="actions">
						<li><a href="#form" class="button scrolly">Unsubscribe</a></li>
					</ul>
				</div>
			</section>		
		
			<section id="form" class="main style1">
				<div class="container">
					<header class="major special">
						<h2>Unsubscribe from Reminders</h2>
					</header>										

                    <p class="center-text">
                    <?php                                     			
            			if(!empty($_POST['email'])){ 
            			    $email = $_POST['email'];
            				            		
            				$signupFile = dirname(__FILE__) . '/../data/omer-signup.csv';            				
        					$omerSignupContents = file_get_contents($signupFile);        					        					
        					        					
        					if(strpos($omerSignupContents, $email) !== false){        						        						
    						    require_once(dirname(__FILE__) . '/../app/EmailController.php');
    						    EmailController::unsubscribeEmail($email);
    						    echo '<span style="color:green;">Thanks!</span><br>';
        						echo '<span style="color:green;">You have successfully been unsubscribed and will no longer receive Sefirah reminders.</span>';    	
        					}else{
        			            echo '<span style="color:red;">It looks like we do not have your email or phone number in our system. Check your spelling.</span><br>';
        			            echo '<span style="color:red;">Are you sure you meant to unsubscribe? If you want to sign up click <a href="/#signup">here</a>.</span>';			
        					}         					        					
        			 	}
            		?>
                    </p>

					<section>
						<form id="unsubscribeForm" method="post" action="#form">							
                            <div class="row uniform 50% ">
								<div class="-3u 6u$">									
									<input type="text" name="email" id="email" placeholder="Email or Phone Number" class="center-text" />
								</div>
							</div>
                            <div class="row uniform 50% ">
								<div class="-3u 6u$">
								    <div id="email-error" class="center-text error-msg"></div>
								    <input type="submit" name="submit" value="Submit" class="special fit" />
								</div>
							</div>
                            <div class="row uniform 50% ">
								<div class="-3u 6u$">
								    <p class="small center-text">You will not be notified anymore from Sefirah Reminders</p>
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
					   <a href="mailto:ben@bprowd.com" class="icon alt fa-envelope">
					       <span class="label">Email</span>
					   </a>
				    </li>
				</ul>
				<ul class="copyright">
					<li>&copy; Sefirat HaOmer Reminders</li>
					<li><a href="/">Signup</a></li>
					<li><a href="mailto:ben@bprowd.com">Contact</a></li>
					<li>Design: <a href="http://html5up.net">HTML5 UP</a></li>
				</ul>
			</section>
			
    <script type="text/javascript">
        var Omer = {
            init: function(){
                 $("#unsubscribeForm").on("submit", Omer.handleUnsubscribeSubmit);
            },
            
            handleUnsubscribeSubmit: function(e){                                                
                var email = $("#email").val();
                
                // Validate Email
                var emailInvalid = (email == null || email.length == 0 || email.length > 200);
                if (emailInvalid){                                                       
                    $("#email-error").text("The email address you entered is invalid!").show();
                }else{
                    $("#email-error").text("").hide();
                }                                                
                
                return !emailInvalid;   
            }
        };
        
        Omer.init();
    
    </script>
    

	</body>
</html>