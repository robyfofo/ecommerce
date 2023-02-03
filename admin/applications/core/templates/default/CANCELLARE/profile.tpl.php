<!-- wscms/core/profile.tpl.php v.3.5.4. 28/03/2019 -->

<div class="row">
	<div class="col-md-12">
		<ul class="nav nav-tabs">
			<li class="active"><a href="#datibase-tab" data-toggle="tab">{{ LocalStrings['['dati base']|capitalize }} <i class="fa"></i></a></li>
  		</ul>
		<form id="applicationForm" class="form-horizontal" role="form" action="{{ URLSITEADMIN }}{{ CoreRequest.action }}/NULL"  enctype="multipart/form-data" method="post">
			<div class="tab-content">
<!-- sezione dati base --> 	
				<div class="tab-pane active" id="datibase-tab">	
					<fieldset>
						<div class="form-group">
							<label for="nameID" class="col-md-3 control-label">{{ LocalStrings['['nome']|capitalize }}</label>
							<div class="col-md-7">
								<input type="text" name="name" class="form-control" id="nameID" placeholder="{{ LocalStrings['['inserisci un nome']|capitalize }}" value="{{ App.item.name|e('html') }}">
					    	</div>
						</div>
						<div class="form-group">
							<label for="surnameID" class="col-md-3 control-label">{{ LocalStrings['['cognome']|capitalize }}</label>
							<div class="col-md-7">
								<input type="text" name="surname" class="form-control" id="surnameID" placeholder="{{ LocalStrings['['inserisci un cognome']|capitalize }}" value="{{ App.item.surname|e('html') }}">
					    	</div>
						</div>
						<hr>
						<div class="form-group">
							<label for="streetID" class="col-md-3 control-label">{{ LocalStrings['['via']|capitalize }}</label>
							<div class="col-md-7">
								<input type="text" name="street" class="form-control" id="streetID" placeholder="{{ LocalStrings['['inserisci una via']|capitalize }}" value="{{ App.item.street|e('html') }}">
					    	</div>
						</div>		
						<div class="form-group">
							<label for="cityID" class="col-md-3 control-label">{{ LocalStrings['['città']|capitalize }}</label>
							<div class="col-md-7">
								<input type="text" name="city" class="form-control" id="cityID" placeholder="{{ LocalStrings['['inserisci una città']|capitalize }}" value="{{ App.item.city|e('html') }}">
					    	</div>
						</div>	
						<div class="form-group">
							<label for="zip_codeID" class="col-md-3 control-label">{{ LocalStrings['['c.a.p.']|capitalize }}</label>
							<div class="col-md-7">
								<input type="text" name="zip_code" class="form-control" id="zip_codeID" placeholder="{{ LocalStrings['['inserisci un c.a.p.']|capitalize }}" value="{{ App.item.zip_code|e('html') }}">
					    	</div>
						</div>
						<div class="form-group">
							<label for="provinceID" class="col-md-3 control-label">{{ LocalStrings['['provincia']|capitalize }}</label>
							<div class="col-md-7">
								<input type="text" name="province" class="form-control" id="provinceID" placeholder="{{ LocalStrings['['inserisci una provincia']|capitalize }}" value="{{ App.item.province|e('html') }}">
					    	</div>
						</div>
						<div class="form-group">
							<label for="provinceID" class="col-md-3 control-label">{{ LocalStrings['['stato']|capitalize }}</label>
							<div class="col-md-7">
								<input type="text" name="state" class="form-control" id="stateID" placeholder="{{ LocalStrings['['inserisci uno stato']|capitalize }}" value="{{ App.item.state|e('html') }}">
					    	</div>
						</div>
						<hr>
						<div class="form-group">
							<label for="emailID" class="col-md-3 control-label">{{ LocalStrings['['email']|capitalize }}</label>
							<div class="col-md-7">
								<input type="email" name="email" class="form-control" id="emailID" placeholder="{{ LocalStrings['['inserisci un indirizzo email']|capitalize }}"  value="{{ App.item.email|e('html') }}">
					    	</div>
						</div>
							<div class="form-group">
							<label for="telephoneID" class="col-md-3 control-label">{{ LocalStrings['['telefono']|capitalize }}</label>
							<div class="col-md-7">
								<input type="text" name="telephone" class="form-control" id="telephoneID" placeholder="{{ LocalStrings['['inserisci un numero di telefono']|capitalize }}" value="{{ App.item.telephone|e('html') }}">
					    	</div>
						</div>
						<div class="form-group">
							<label for="mobileID" class="col-md-3 control-label">{{ LocalStrings['['mobile']|capitalize }}</label>
							<div class="col-md-7">
								<input type="text" name="mobile" class="form-control" id="mobileID" placeholder="{{ LocalStrings['['inserisci un numero mobile']|capitalize }}" value="{{ App.item.mobile|e('html') }}">
					    	</div>
						</div>
						<div class="form-group">
							<label for="emailID" class="col-md-3 control-label">{{ LocalStrings['['fax']|capitalize }}</label>
							<div class="col-md-7">
								<input type="text" name="fax" class="form-control" id="faxID" placeholder="{{ LocalStrings['['inserisci un numero di fax']|capitalize }}" value="{{ App.item.fax|e('html') }}">
					    	</div>
						</div>
						<div class="form-group">
							<label for="skypeID" class="col-md-3 control-label">{{ LocalStrings['['skype']|capitalize }}</label>
							<div class="col-md-7">
								<input type="text" name="skype" class="form-control" id="skypeID" placeholder="{{ LocalStrings['['inserisci un nome utente skype']|capitalize }}" value="{{ App.item.skype|e('html') }}">
					    	</div>
						</div>
						<hr>
						<div class="form-group">
							<label for="avatarID" class="col-md-3 control-label">{{ LocalStrings['['avatar']|capitalize }}</label>
							<div class="col-md-3">				
								<input type="file" name="avatar">				
					    	</div>
					    	<div class="col-md-4">
					    		{% if App.item.avatar is defined and App.item.avatar != '' %}								
									<img src="{{ URLSITEADMIN }}{{ CoreRequest.action }}/renderAvatarDB/{{ App.id }}" alt="{{ App.item.name }} avatar" style="max-height: 240px;">					
				            {% endif %}				
					    	</div>
						</div>
						<div class="form-group">
							<label for="templateID" class="col-md-3 control-label">{{ LocalStrings['['template']|capitalize }}</label>
							<div class="col-md-7">
								{% if App.templatesAvaiable is iterable and App.templatesAvaiable|length > 0 %}
								<select name="template">
									{% for key,value in App.templatesAvaiable %}
										<option value="{{ value }}"{% if App.item.template is defined and App.item.template == value %} selected="selected"{% endif %}>{{ value|e('html') }}</option>
									{% endfor %}
								</select>
								{% endif %}				
					    	</div>
						</div>	
					</fieldset>
				</div>
<!-- sezione dati base -->					
			</div>
<!--/Tab panes -->			
			<hr>
			<div class="form-group">
				<div class="col-md-offset-3 col-md-9 col-xs-offset-0 col-xs-12">
				<input type="hidden" name="id" value="{{ App.id }}">
				<input type="hidden" name="method" value="update">
				<button type="submit" class="btn btn-primary">{{ LocalStrings['['invia']|capitalize }}</button>
				</div>
			</div>
		</form>
	</div>
</div>