<!-- core/login.tpl.php v.3.5.3. 20/09/2018  -->
	<div class="row">
		<div class="col-md-4 col-md-offset-4">
			<div class="login-panel panel panel-default">
				<div class="panel-heading">
					<h3 class="panel-title">{{ LocalStrings['['inserisci un nome utente e una password']|capitalize }}</h3>
				</div>
				<div class="panel-body">
					<form class="form-signin" role="form" action="{{ URLSITEADMIN }}login" method="post">
						<fieldset>
							<div class="form-group">
							<input required class="form-control" placeholder="{{ LocalStrings['['nome utente']|capitalize }}" name="username" type="text" oninvalid="this.setCustomValidity('Devi inserire un nome utente!')" oninput="setCustomValidity('')" autofocus>
							</div>
							<div class="form-group">
								<input required class="form-control" placeholder="{{ LocalStrings['['password']|capitalize }}" name="password" type="password" value="" oninvalid="this.setCustomValidity('Devi inserire una password!')" oninput="setCustomValidity('')">
							</div>
							
							<!-- Change this to a button or input when using this as a form -->
							<input type="hidden" name="method" value="check" />
							<input type="submit" name="submit" value="{{ LocalStrings['['login']|capitalize }}" class="btn btn-lg btn-success btn-block">
						</fieldset>
					</form>					
				</div>
				<div class="panel-footer">
						<a href="{{ URLSITEADMIN }}nousername" title="{{ LocalStrings['['clicca per recuperare il nome utente']|capitalize }}">{{ LocalStrings['['nome utente']|capitalize }}</a> o <a href="{{ URLSITEADMIN }}nopassword" title="{{ LocalStrings['['clicca per recuperare la password']|capitalize }}">{{ LocalStrings['['password']|capitalize }}</a> {{ LocalStrings['['dimenticati'] }}
				</div>
			</div>
		</div>
	</div>