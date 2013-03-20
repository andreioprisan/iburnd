
<div class="row" style="align:center">
	<div class="span5" style="padding-left: 100px">
		<br>
		<form>
		<fieldset>
			<legend>sign in to your account</legend>
			<br>
			<div class="clearfix">
			  <div class="input">
			    <div class="input-prepend">
			      <span class="add-on">&nbsp;<font color="black">email</font>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </span>
			      <input class="large" id="prependedInput" name="prependedInput" size="16" type="text">
			    </div>
			  </div></form>
			</div><!-- /clearfix -->
			<div class="clearfix">
			  <label for="prependedInput2"></label>
			  <div class="input">
			    <div class="input-prepend">
			      <label class="add-on">&nbsp;<font color="black">password</font> &nbsp;</label>
			      <input class="large" id="prependedInput2" name="prependedInput2" size="16" type="password"> 
			    </div>
			  </div>
			</div><!-- /clearfix -->
			<div class="clearfix">
			  <label for="signin"></label>
			  <div class="input">
			    <div class="input-prepend">
			&nbsp;&nbsp;<button class="medium btn primary" id="signin" name="signin" size="4">sign in</button> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="">Forgot your password?</a><br/><br/><br/>
			    </div>
			  </div>
			</div><!-- /clearfix -->

			<div class="clearfix">
			  <div class="input">
			    <div class="input-append">
					<form action="<?php echo 'https://'.$_SERVER['HTTP_HOST'].'/facebook_auth/login';?>" method="post">
					      <img src="/assets/img/facebook_32.png"><input class="medium btn" id="submit" name="submit" size="16" type="submit" value="sign in with Facebook">
					</form>
			    </div>
			  </div>
			</div><!-- /clearfix -->
		</fieldset>
	</div>
	<div class="span6" align="left">
		<br>
		<form>
		<fieldset>
			<legend>sign up today!</legend>
			<br>
			<div class="clearfix">
			  <label for="prependedInput"></label>
			  <div class="input">
			    <div class="input-prepend">
			      <span class="add-on">&nbsp;<font color="black">name</font>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </span>
			      <input class="large" id="prependedInput" name="prependedInput" size="16" type="text">
			    </div>
			  </div></form>
			</div><!-- /clearfix -->

			<div class="clearfix">
			  <label for="prependedInput"></label>
			  <div class="input">
			    <div class="input-prepend">
			      <span class="add-on">&nbsp;<font color="black">email</font>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </span>
			      <input class="large" id="prependedInput" name="prependedInput" size="16" type="text">
			    </div>
			  </div></form>
			</div><!-- /clearfix -->


			<div class="clearfix">
			  <label for="prependedInput2"></label>
			  <div class="input">
			    <div class="input-prepend">
			      <label class="add-on">&nbsp;<font color="black">password</font> &nbsp;</label>
			      <input class="large" id="prependedInput2" name="prependedInput2" size="16" type="password"> 
			    </div>
			  </div>
			</div><!-- /clearfix -->

			<div class="clearfix">
			  <label for="signin"></label>
			  <div class="input">
			    <div class="input-prepend">
			&nbsp;<button class="medium btn primary" id="signin" name="signin" size="4">sign up</button> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="">Privacy Policy</a><br/><br/><br/>
			    </div>
			  </div>
			</div><!-- /clearfix -->
		</fieldset>
	</div>
</div>
