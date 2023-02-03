<!-- wscms/slides-home-rev/formItem.tpl.php v.3.5.3. 29/05/2018 -->
<div class="row">
	<div class="col-md-3 new">
 	</div>
	<div class="col-md-7 help-small-form">
		{% if App.params.help_small is defined and App.params.help_small != '' %}{{ App.params.help_small }}{% endif %}
	</div>
	<div class="col-md-2 help">
	</div>
</div>

<div class="row">
	<div class="col-md-12">

		<ul class="nav nav-tabs">
			<li class="active"><a href="#datibase-tab" data-toggle="tab">{{ Lang['dati base']|capitalize }} <i class="fa"></i></a></li>	
			{% for lang in GlobalSettings['languages'] %}
			<li><a href="#contents-{{ lang }}-tab" data-toggle="tab">{{ Lang['contenuti']|capitalize }} {{ lang }} <i class="fa"></i></a></li>	
			{% endfor %}	
  		</ul>

		<form id="applicationForm" method="post" class="form-horizontal" role="form" action="{{ URLSITEADMIN }}{{ CoreRequest.action }}/{{ App.methodForm }}"  enctype="multipart/form-data" method="post">

			<div class="tab-content">
				<div class="tab-pane active" id="datibase-tab">

					<fieldset class="form-group">
						<div class="form-group">
							<label for="filenameID" class="col-md-2 control-label">{{ Lang['immagine']|capitalize }}</label>
							<div class="col-md-4">
								<input{% if App.item.filenameRequired == true %} required{% endif %} type="file" name="filename" id="filenameID"  placeholder="Indica un file da caricare">							
							</div>
						</div>
						<div class="form-group">
							<label for="filenameID" class="col-md-2 control-label">{{ Lang['anteprima']|capitalize }}</label>
							<div class="col-md-5">
								{% if App.item.filename is defined and App.item.filename != '' %}
								<a class="" href="{{ App.params.uploadDirs['item'] }}{{ App.item.filename }}" data-lightbox="image-1" data-title="{{ App.item.org_filename }}" title="{{ App.item.org_filename }}">
									<img  class="img-thumbnail"  src="{{ App.params.uploadDirs['item'] }}{{ App.item.filename }}" alt="{{ App.item.org_filename }}">
								</a>
								{% else %}
								<img  class="img-thumbnail"  src="{{ App.params.uploadDirs['item'] }}default/image.png" alt="{{ Lang['immagine di default']|capitalize }}">										
								{% endif %}
							</div>	
						</div>
						<hr>
						<div class="form-group">
							<label for="urlID" class="col-md-2 control-label">{{ Lang['url']|capitalize }}<br>
							{{ Lang['label url dinamico'] }}</label>
							<div class="col-md-6">
								<input type="text" class="form-control" name="url" placeholder="{{ Lang['inserisci un url']|capitalize }}" id="urlID" value="{{ App.item.url|e('html') }}">
							</div>								
					
							<label for="targetID" class="col-md-1 control-label">{{ Lang['target']|capitalize }}</label>
							<div class="col-md-2">							
								<select class="form-control input-md" name="target">	
								<option></option>			
									{% if GlobalSettings['url-targets'] is iterable and GlobalSettings['url-targets']|length > 0 %}
										{% for value in GlobalSettings['url-targets'] %}											
											<option value="{{ value }}"{% if App.item.target is defined and App.item.target == value %} selected="selected"{% endif %}>{{ value|e('html') }}</option>														
										{% endfor %}
									{% endif %}	
								</select>							
					    	</div>
						</div>
						<hr>
					<!-- se e un utente root visualizza l'input altrimenti lo genera o mantiene automaticamente -->	
					{% if App.userLoggedData.is_root == 1 %}

						<div class="form-group">
							<label for="orderingID" class="col-md-2 control-label">{{ Lang['ordine']|capitalize }}</label>
							<div class="col-md-1">
								<input type="text" name="ordering" placeholder="{{ Lang['inserisci un ordine']|capitalize }}" class="form-control" id="orderingID" value="{{ App.item.ordering }}">
					    	</div>
						</div>
						<hr>

					{% else %}
						<input type="hidden" name="ordering" value="{{ App.item.ordering }}">		
					{% endif %}
					<!-- fine se root -->
					
						<div class="form-group">
							<label for="activeID" class="col-md-2 control-label">{{ Lang['attiva']|capitalize }}</label>
							<div class="col-md-7">
								<div class="form-check">
									<label class="form-check-label">
										<input type="checkbox" name="active" id="activeID"{% if App.item.active == 1 %} checked="checked"{% endif %} value="1">
									</label>
	     						</div>
	   					</div>
	   				</div>
					</fieldset>					
				</div>			
				{% for lang in GlobalSettings['languages'] %} 
					{% set titleField = "title_#{lang}" %}
					<div class="tab-pane" id="contents-{{ lang }}-tab">
						<fieldset class="form-group">
							<div class="form-group">
								<label for="title_{{ lang }}ID" class="col-md-2 control-label">{{ Lang['titolo']|capitalize }} {{ lang }} </label>
								<div class="col-md-7">
									<input{% if lang == Lang['user'] %} required{% endif %} type="text" class="form-control" name="title_{{ lang }}" placeholder="{{ Lang['inserisci un titolo']|capitalize }} {{ lang }}" id="title_{{ lang }}ID" value="{{ attribute(App.item, titleField)|e('html') }}">
								</div>
							</div>
							<hr>
							{% for value in 1..App.params.item_contents %}
							{% set contentField = "content#{loop.index}_#{lang}" %}
							<div class="form-group">
								<label for="content{{ loop.index }}_{{ lang }}ID" class="col-md-2 control-label">{{ Lang['contenuto']|capitalize }}{{ loop.index }} {{ lang }} </label>
								<div class="col-md-8">
									<textarea name="content{{ loop.index }}_{{ lang }}" class="form-control editorHTML" id="content{{ loop.index }}_{{ lang }}ID" rows="5">{{ attribute(App.item, contentField) }}</textarea>
								</div>
							</div>
							{% endfor %}							
						</fieldset>			
					</div>
				{% endfor %}
			</div>
<!--/Tab panes -->			
			<hr>
			<div class="form-group">
				<div class="col-md-offset-2 col-md-7">		
					<input type="hidden" name="created" id="createdID" value="{{ App.item.created }}">
					<input type="hidden" name="id" id="idID" value="{{ App.id }}">
					<input type="hidden" name="method" value="{{ App.methodForm }}">
					<button type="submit" name="submitForm" value="submit" class="btn btn-primary">{{ Lang['invia']|capitalize }}</button>
					{% if App.id > 0 %}
						<button type="submit" name="applyForm" value="apply" class="btn btn-primary">{{ Lang['applica']|capitalize }}</button>
					{% endif %}
				</div>
				<div class="col-md-2">				
					<a href="{{ URLSITEADMIN }}{{ CoreRequest.action }}/listItem" title="{{ Lang['torna alla lista']|capitalize }}" class="btn btn-success">{{ Lang['indietro']|capitalize }}</a>
				</div>
			</div>
		</form>
	</div>
</div>