<?php defined('C5_EXECUTE') or die("Access Denied."); ?>
<?php Loader::library('authentication/open_id');?>
<?php $form = Loader::helper('form'); ?>

<script type="text/javascript">
$(function() {
	$("input[name=uName]").focus();
});
</script>

<?php if (isset($intro_msg)) { ?>
<div class="alert-message block-message success"><p><?php echo $intro_msg?></p></div>
<?php } ?>

<div class="row">
<div class="span10 offset1">
<div class="page-header">
	<h1><?php echo t('Sign in to %s', SITE)?></h1>
</div>
</div>
</div>

<?php if( $passwordChanged ){ ?>

	<div class="block-message info alert-message"><p><?php echo t('Password changed.  Please login to continue. ') ?></p></div>

<?php } ?> 

<?php if($changePasswordForm){ ?>

	<p><?php echo t('Enter your new password below.') ?></p>

	<div class="ccm-form">	

	<form method="post" action="<?php echo $this->url( '/login', 'change_password', $uHash )?>"> 

		<div class="control-group">
		<label for="uPassword" class="control-label"><?php echo t('New Password')?></label>
		<div class="controls">
			<input type="password" name="uPassword" id="uPassword" class="ccm-input-text">
		</div>
		</div>
		<div class="control-group">
		<label for="uPasswordConfirm"  class="control-label"><?php echo t('Confirm Password')?></label>
		<div class="controls">
			<input type="password" name="uPasswordConfirm" id="uPasswordConfirm" class="ccm-input-text">
		</div>
		</div>

		<div class="actions">
		<?php echo $form->submit('submit', t('Sign In') . ' &gt;')?>
		</div>
	</form>
	
	</div>

<?php }elseif($validated) { ?>

<h3><?php echo t('Email Address Verified')?></h3>

<div class="success alert-message block-message">
<p>
<?php echo t('The email address <b>%s</b> has been verified and you are now a fully validated member of this website.', $uEmail)?>
</p>
<div class="alert-actions"><a class="btn small" href="<?php echo $this->url('/')?>"><?php echo t('Continue to Site')?></a></div>
</div>


<?php } else if (isset($_SESSION['uOpenIDError']) && isset($_SESSION['uOpenIDRequested'])) { ?>

<div class="ccm-form">

<?php switch($_SESSION['uOpenIDError']) {
	case OpenIDAuth::E_REGISTRATION_EMAIL_INCOMPLETE: ?>

		<form method="post" action="<?php echo $this->url('/login', 'complete_openid_email')?>">
			<p><?php echo t('To complete the signup process, you must provide a valid email address.')?></p>
			<label for="uEmail"><?php echo t('Email Address')?></label><br/>
			<?php echo $form->text('uEmail')?>
				
			<div class="ccm-button">
			<?php echo $form->submit('submit', t('Sign In') . ' &gt;')?>
			</div>
		</form>

	<?php break;
	case OpenIDAuth::E_REGISTRATION_EMAIL_EXISTS:
	
	$ui = UserInfo::getByID($_SESSION['uOpenIDExistingUser']);
	
	?>

		<form method="post" action="<?php echo $this->url('/login', 'do_login')?>">
			<p><?php echo t('The OpenID account returned an email address already registered on this site. To join this OpenID to the existing user account, login below:')?></p>
			<label for="uEmail"><?php echo t('Email Address')?></label><br/>
			<div><strong><?php echo $ui->getUserEmail()?></strong></div>
			<br/>
			
			<div>
			<label for="uName"><?php if (USER_REGISTRATION_WITH_EMAIL_ADDRESS == true) { ?>
				<?php echo t('Email Address')?>
			<?php } else { ?>
				<?php echo t('Username')?>
			<?php } ?></label><br/>
			<input type="text" name="uName" id="uName" <?php echo (isset($uName)?'value="'.$uName.'"':'');?> class="ccm-input-text">
			</div>			<div>

			<label for="uPassword"><?php echo t('Password')?></label><br/>
			<input type="password" name="uPassword" id="uPassword" class="ccm-input-text">
			</div>

			<div class="ccm-button">
			<?php echo $form->submit('submit', t('Sign In') . ' &gt;')?>
			</div>
		</form>

	<?php break;

	}
?>

</div>

<?php } else if ($invalidRegistrationFields == true) { ?>

<div class="ccm-form">

	<p><?php echo t('You must provide the following information before you may login.')?></p>
	
<form method="post" action="<?php echo $this->url('/login', 'do_login')?>">
	<?php 
	$attribs = UserAttributeKey::getRegistrationList();
	$af = Loader::helper('form/attribute');
	
	$i = 0;
	foreach($unfilledAttributes as $ak) { 
		if ($i > 0) { 
			print '<br/><br/>';
		}
		print $af->display($ak, $ak->isAttributeKeyRequiredOnRegister());	
		$i++;
	}
	?>
	
	<?php echo $form->hidden('uName', Loader::helper('text')->entities($_POST['uName']))?>
	<?php echo $form->hidden('uPassword', Loader::helper('text')->entities($_POST['uPassword']))?>
	<?php echo $form->hidden('uOpenID', $uOpenID)?>
	<?php echo $form->hidden('completePartialProfile', true)?>

	<div class="ccm-button">
		<?php echo $form->submit('submit', t('Sign In'))?>
		<?php echo $form->hidden('rcID', $rcID); ?>
	</div>
	
</form>
</div>	

<?php } else { ?>

<form method="post" action="<?php echo $this->url('/login', 'do_login')?>" class="form-horizontal ccm-login-form">

<div class="row">
<div class="span10 offset1">
<div class="row">
<div class="span5">

<fieldset>
	
	<legend><?php echo t('User Account')?></legend>

	<div class="control-group">
	
	<label for="uName" class="control-label"><?php if (USER_REGISTRATION_WITH_EMAIL_ADDRESS == true) { ?>
		<?php echo t('Email Address')?>
	<?php } else { ?>
		<?php echo t('Username')?>
	<?php } ?></label>
	<div class="controls">
		<input type="text" name="uName" id="uName" <?php echo (isset($uName)?'value="'.$uName.'"':'');?> class="ccm-input-text">
	</div>
	
	</div>
	<div class="control-group">

	<label for="uPassword" class="control-label"><?php echo t('Password')?></label>
	
	<div class="controls">
		<input type="password" name="uPassword" id="uPassword" class="ccm-input-text" />
	</div>
	
	</div>
</fieldset>

<?php if (OpenIDAuth::isEnabled()) { ?>
	<fieldset>

	<legend><?php echo t('OpenID')?></legend>

	<div class="control-group">
		<label for="uOpenID" class="control-label"><?php echo t('Login with OpenID')?>:</label>
		<div class="controls">
			<input type="text" name="uOpenID" id="uOpenID" <?php echo (isset($uOpenID)?'value="'.$uOpenID.'"':'');?> class="ccm-input-openid">
		</div>
	</div>
	</fieldset>
<?php } ?>

</div>
<div class="span4 offset1">

	<fieldset>

	<legend><?php echo t('Options')?></legend>

	<?php if (isset($locales) && is_array($locales) && count($locales) > 0) { ?>
		<div class="control-group">
			<label for="USER_LOCALE" class="control-label"><?php echo t('Language')?></label>
			<div class="controls"><?php echo $form->select('USER_LOCALE', $locales)?></div>
		</div>
	<?php } ?>
	
	<div class="control-group">
		<label class="checkbox"><?php echo $form->checkbox('uMaintainLogin', 1)?> <span><?php echo t('Remain logged in to website.')?></span></label>
	</div>
	<?php $rcID = isset($_REQUEST['rcID']) ? Loader::helper('text')->entities($_REQUEST['rcID']) : $rcID; ?>
	<input type="hidden" name="rcID" value="<?php echo $rcID?>" />
	
	</fieldset>
</div>
<div class="span10">
	<div class="actions">
	<?php echo $form->submit('submit', t('Sign In') . ' &gt;', array('class' => 'primary'))?>
	</div>
</div>
</div>
</div>
</div>
</form>

<a name="forgot_password"></a>

<form method="post" action="<?php echo $this->url('/login', 'forgot_password')?>" class="form-horizontal ccm-forgot-password-form">
<div class="row">
<div class="span10 offset1">

<h3><?php echo t('Forgot Your Password?')?></h3>

<p><?php echo t("Enter your email address below. We will send you instructions to reset your password.")?></p>

<input type="hidden" name="rcID" value="<?php echo $rcID?>" />
	
	<div class="control-group">
		<label for="uEmail" class="control-label"><?php echo t('Email Address')?></label>
		<div class="controls">
			<input type="text" name="uEmail" value="" class="ccm-input-text" >
		</div>
	</div>
	
	<div class="actions">
		<?php echo $form->submit('submit', t('Reset and Email Password') . ' &gt;')?>
	</div>

</div>
</div>	
</form>


<?php if (ENABLE_REGISTRATION == 1) { ?>
<div class="row">
<div class="span10 offset1">
<div class="control-group">
<h3><?php echo t('Not a Member')?></h3>
<p><?php echo t('Create a user account for use on this website.')?></p>
<div class="actions">
<a class="btn" href="<?php echo $this->url('/register')?>"><?php echo t('Register here!')?></a>
</div>
</div>
</div>
</div>
<?php } ?>

<?php } ?>
