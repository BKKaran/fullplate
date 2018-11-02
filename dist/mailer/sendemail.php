s<?php 
	require_once('phpmailer/class.phpmailer.php');
	if( isset($_POST['submit']) && "Send Message" == $_POST['submit'] ) {

		if( '' != $_POST['author'] && '' != $_POST['comment'] && '' != $_POST['email'] ) {

			$toemail = 'support@sthemes.co'; //Your Email Address
			$toname  = 'SThemes'; // Your Name
			$author  = $_POST['author'];
			$email   = $_POST['email'];
			$comment = $_POST['comment'];
			
			$mailer = new PHPMailer();
			$mailer->SetFrom( $email , $author );
			$mailer->AddReplyTo( $email , $author );
			$mailer->AddAddress( $toemail , $toname );
			$mailer->Subject = 'New comment posted from Contact Form'; // Your Mail Subject

			$author = isset( $author ) ? "Name : $author <br><br>" : "";
			$email = isset( $email ) ? "Email : $email <br><br>" : "";
			$comment = isset( $comment ) ? "Comment : $comment <br><br>" : "";
			$referrer = $_SERVER['HTTP_REFERER'] ? '<br><br><br>This Form was submitted from: ' . $_SERVER['HTTP_REFERER'] : '';

			$body = "$author $email $comment $referrer";

			$mailer->MsgHTML( $body );
			$sendEmail = $mailer->Send();

			if( true == $sendEmail ) {

				$msg = 'We have <strong>successfully</strong> received your comment. Thanks';
				
				echo '<div class="artigo-success-msg">';
				echo $msg;
				echo '</div>';

			} else {

				$msg = 'Email <strong>could not</strong> be sent due to some Unexpected Error. Please Try Again later.';

				echo '<div class="artigo-warning-msg">';
				echo $msg;
				echo '</div>';
			}
		} else {

			$msg = '<strong>Error</strong> : Please fill the required fileds and Try Again.';

			echo '<div class="artigo-error-msg">';
			echo $msg;
			echo '</div>';
		}
	} else {

		$msg = 'An <strong>unexpected error</strong> occured. Please Try Again later.';

		echo '<div class="artigo-info-msg">';
		echo $msg;
		echo '</div>';
	}
?>