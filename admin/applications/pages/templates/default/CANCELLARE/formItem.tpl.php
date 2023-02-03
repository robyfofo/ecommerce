<!-- wscms/pages/form.tpl.php v.3.5.4. 28/03/2019 -->
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

		<!-- Nav tabs -->
		<ul class="nav nav-tabs">
			<li class="active"><a href="#template-tab" data-toggle="tab">{{ Lang['template']|capitalize }} <i class="fa"></i></a></li>
			<li><a href="#options-tab" data-toggle="tab">{{ Lang['opzioni']|capitalize }} <i class="fa"></i></a></li>
			{% for lang in GlobalSettings['languages'] %}		
				<li><a href="#datibase{{ lang }}-tab" data-toggle="tab">{{ Lang['dati base']|capitalize }} {{ lang }} <i class="fa"></i></a></li>
			{% endfor %}
			<li><a href="#image-tab" data-toggle="tab">{{ Lang['immagini']|capitalize }} <i class="fa"></i></a></li>
			<li><a href="#advanced-tab" data-toggle="tab">{{ Lang['avanzate']|capitalize }} <i class="fa"></i></a></li>			
		</ul>
		<!--/Nav tabs -->		
		<form id="applicationForm" method="post" class="form-horizontal" role="form" action="{{ URLSITEADMIN }}{{ CoreRequest.action }}/{{ App.methodForm }}"  enctype="multipart/form-data" method="post">
			<!-- Tab panes -->
			<div class="tab-content">				
<!--  template --> 
				<div class="tab-pane active" id="template-tab">
					<fieldset>
						{% if App.userLoggedData.is_root == 1 %}				
							<div class="form-group">
								<label class="col-md-2 control-label">ID {{ Lang['pagina'] }}</label>
								<div class="col-md-1">
									{{ App.item.id }}
						    	</div>
							</div>
							<hr>
						{% endif %}
						<div class="form-group">
							<label for="id_templateID" class="col-md-2 control-label">{{ Lang['template']|capitalize }}</label>
							<div class="col-md-7">							
								<select name="id_template" id="id_templateID" class="form-control input-md">								
									{% if App.templatesItem is iterable and App.templatesItem|length > 0 %}
										{% for key,value in App.templatesItem %}		
											<option value="{{ value.id }}"{% if App.templateItem.id is defined and App.templateItem.id == value.id %} selected="selected"{% endif %}>&nbsp;{{ value.title|e('html') }}&nbsp;</option>													
										{% endfor %}
									{% endif %}		
								</select>										
					    	</div>
						</div>
					</fieldset>						
					<fieldset id="templateDataID">	
						<div class="form-group">
							<div class="col-md-6">
								{{ App.templateItem.content }}
							</div>
							<div class="col-md-6">
								{% if App.templateItem.filename != '' %}
								<a href="{{ App.params.template['defuploaddir'] }}{{ App.templateItem.filename }}" data-lightbox="image-1" data-title="{{ value.org_filename }}" title="{{ App.templateItem.filename }}">
									<img src="{{ App.params.template['defuploaddir'] }}{{ App.templateItem.filename }}" class="img-thumbnail" alt="{{ App.templateItem.filename }}">
								</a>
								{% else %}
									<img src="{{ App.params.uploadDirs['item'] }}default/image.png" class="img-thumbnail" alt="{{ Lang['immagine di default']|capitalize }}">
								{% endif %}
							</div>
						</div>
					</fieldset>						
				</div>
<!--/template -->	
	
<!-- sezione opzioni --> 
				<div class="tab-pane" id="options-tab">
				
					<fieldset>
						<div class="form-group">
							<label for="parentID" class="col-md-2 control-label">{{ Lang['genitore']|capitalize }}</label>
							<div class="col-md-7">							
								<select name="parent" class="form-control input-md">
									<option value="0"></option>
									{% if App.subCategories is iterable and App.subCategories|length > 0 %}
										{% for value in App.subCategories %}		
											<option value="{{ value.id }}"{% if App.item.parent is defined and App.item.parent == value.id %} selected="selected"{% endif %}>{{ value.levelString }} {{ value.title|e('html') }}</option>														
										{% endfor %}
									{% endif %}		
								</select>									
					    	</div>
						</div>
						<hr>
						<div class="form-group">
							<label for="aliasID" class="col-md-2 control-label">{{ Lang['alias']|capitalize }}</label>
							<div class="col-md-7">
								<input type="text" class="form-control" name="alias" placeholder="{{ Lang['inserisci un alias']|capitalize }}" id="aliasID" value="{{ App.item.alias|e('html') }}">
							</div>
						</div>
						<hr>
						<div class="form-group">
							<label for="type" class="col-md-2 control-label">{{ Lang['tipo']|capitalize }}</label>
							<div class="col-md-7">							
								<select name="type" class="form-control input-sm">								
									{% if GlobalSettings['page-type'] is iterable and GlobalSettings['page-type']|length > 0 %}
										{% for key,value in GlobalSettings['page-type'] %}	
											<option value="{{ key }}"{% if App.item.type is defined and App.item.type == key %} selected="selected"{% endif %}>{{ Lang['page-type'][key]|capitalize|e('html') }}</option>													
										{% endfor %}
									{% endif %}		
								</select>									
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
						<div class="form-group">
						
							<label for="moduleID" class="col-md-2 control-label">{{ Lang['link a modulo']|capitalize }}</label>
							<div class="col-md-7">							
								<select class="form-control input-md col-md-3" name="module">	
								<option></option>			
									{% if App.modules is iterable and App.modules|length > 0 %}
										{% for section in App.modules %}		
											{% if section is iterable and section|length > 0 %}
												{% for value in section %}														
												<option value="{{ value.alias }}"{% if App.item.url is defined and App.item.url == value.alias %} selected="selected"{% endif %}>{{ value.alias|e('html') }}</option>														
												{% endfor %}
											{% endif %}	
										{% endfor %}
									{% endif %}	
								</select>							
					    	</div>
						</div>
						<hr>			
						<div class="form-group">
							<label for="menuID" class="col-md-2 control-label">{{ Lang['in menu']|capitalize }}</label>
							<div class="col-md-7">
								<div class="form-check">
									<label class="form-check-label">
										<input type="checkbox" name="menu" id="menuID"{% if App.item.menu == 1 %} checked="checked"{% endif %} value="1">
									</label>
	     						</div>
	   					</div>
				  		</div>
						<hr>
					<!-- se e un utente root visualizza l'input altrimenti lo genera o mantiene automaticamente -->	
					{% if App.userLoggedData.is_root == 1 %}						
						<div class="form-group">
							<label for="orderingID" class="col-md-2 control-label">{{ Lang['ordine']|capitalize }}</label>
							<div class="col-md-1">
								<input type="text" name="ordering" placeholder="{{ Lang['inserisci un ordine']| capitalize }}" class="form-control" id="orderingID" value="{{ App.item.ordering }}">
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
<!--/sezione opzioni -->
<!--sezione generazione automatica tab dati base e contenuti in base alla lingua -->
{% for lang in GlobalSettings['languages'] %}
	{% set titleField = "title_#{lang}" %}
	{% set titleAltField = "title_alt_#{lang}" %}
	{% set titleAlt1Field = "title_alt1_#{lang}" %}			
	<div class="tab-pane" id="datibase{{ lang }}-tab">	
		<fieldset>
			<div class="form-group">
				<label for="title_{{ lang }}ID" class="col-md-2 control-label">{{ Lang['titolo']|capitalize }} {{ lang }}</label>
				<div class="col-md-7">
					<input type="text"{% if lang == Lang['user'] %} required{% endif %} class="form-control" name="title_{{ lang }}" placeholder="{{ Lang['inserisci un titolo']|capitalize }} {{ lang }}" id="title_{{ lang }}ID" value="{{ attribute(App.item, titleField)|e('html') }}" oninvalid="this.setCustomValidity('{{ Lang['Devi inserire un %ITEM%!']|replace({'%ITEM%': Lang['titolo']}) }}')" oninput="setCustomValidity('')">
				</div>								
			</div>
		</fieldset>
	</div>
{% endfor %}
<!-- sezione image -->	
				<div class="tab-pane" id="image-tab">		
					<fieldset>
						<div class="form-group">
							<label for="filenameID" class="col-md-2 control-label">{{ Lang['immagine top']|capitalize }}</label>
							<div class="col-md-4">
								<input{% if App.item.filenameRequired == true %} required{% endif %} type="file" name="filename" id="filenameID"  placeholder="{{ Lang['indica un file da caricare']|capitalize }}">							
							</div>
						</div>
						<div class="form-group">
							<label class="col-md-2 control-label">{{ Lang['anteprima']|capitalize }}</label>
							<div class="col-md-7">
								{% if App.item.filename is defined and App.item.filename != '' %}
								<a class="" href="{{ App.params.uploadDirs['item'] }}{{ App.item.filename }}" data-lightbox="image-1" data-title="{{ value.org_filename }}" title="{{ App.item.org_filename }}">
									<img  class="img-thumbnail"  src="{{ App.params.uploadDirs['item'] }}{{ App.item.filename }}" alt="{{ App.item.org_filename }}">
								</a>							
								{% else %}
									<img  class="img-thumbnail"  src="{{ App.params.uploadDirs['item'] }}default/image.png" alt="{{ Lang['immagine di default']|capitalize }}">											
								{% endif %}
							</div>			
						</div>
						{% if App.item.filename is defined and App.item.filename != '' %}
						<div class="form-group">
							<label for="deleteFilenameID" class="col-md-2 control-label">{{ Lang['cancella immagine']|capitalize }}</label>						
							<div class="col-md-5">							
								<input type="checkbox" name="deleteFilename" id="deleteFilenameID" value="1">						
							</div>					
						</div>
						{% endif %}
					</fieldset>
				

				</div>
<!-- /sezione image -->

<!-- sezione avanzate -->	
				<div class="tab-pane" id="advanced-tab">	
					<fieldset>
						<div class="form-group">
							<label for="Jscript_init_codeID" class="col-md-2 control-label">{{ Lang['Codice Javascript inizio BODY'] }}</label>
							<div class="col-md-7">
								<textarea name="jscript_init_code" class="form-control" id="jscript_init_codeID" rows="4">{{ App.item.jscript_init_code }}</textarea>
							</div>
				  		</div>
					</fieldset>				
				</div>
<!--/sezione avanzate -->	

			</div>
			<!--/Tab panes -->	
				
			<hr>	
			
			<div class="form-group">
				<div class="col-md-offset-2 col-md-7 col-xs-offset-0 col-xs-6">
					{% for lang in GlobalSettings['languages'] %}				
						{% set metaTitleField = "meta_title_#{lang}" %}
						{% set metaDescriptionField = "meta_description_#{lang}" %}
						{% set metaKeywordField = "meta_keyword_#{lang}" %}
						{% set titleSeoField = "title_seo_#{lang}" %}
							<input type="hidden" class="form-control" name="meta_title_{{ lang }}" id="title_seo_{{ lang }}ID" value="{{ attribute(App.item, metaTitleField)|e('html') }}">
							<input type="hidden" class="form-control" name="meta_description_{{ lang }}" id="title_seo_{{ lang }}ID" value="{{ attribute(App.item, metaDescriptionField)|e('html') }}">
							<input type="hidden" class="form-control" name="meta_keyword_{{ lang }}" id="title_seo_{{ lang }}ID" value="{{ attribute(App.item, metaKeywordField)|e('html') }}">
							<input type="hidden" class="form-control" name="title_seo_{{ lang }}" id="title_seo_{{ lang }}ID" value="{{ attribute(App.item, titleSeoField)|e('html') }}">
					{% endfor %}
				
					<input type="hidden" name="created" id="createdID" value="{{ App.item.created }}">
			    	<input type="hidden" name="id" value="{{ App.id }}">
			    	<input type="hidden" name="method" value="{{ App.methodForm }}">		    	
			    	<input type="hidden" name="bk_parent" value="{{ App.item.parent }}">
			      <button type="submit" name="submitForm" value="submit" class="btn btn-primary submittheform">{{ Lang['invia']|capitalize }}</button>
			      {% if App.id > 0 %}
			      	<button type="submit" name="applyForm" value="apply" class="btn btn-primary submittheform">{{ Lang['applica']|capitalize }}</button>
			      {% endif %}
				</div>
	 			<div class="col-md-2 col-xs-6">				
					<a href="{{ URLSITEADMIN }}{{ CoreRequest.action }}/listItem" title="{{ Lang['torna alla lista']|capitalize }}" class="btn btn-success">{{ Lang['indietro']|capitalize }}</a>
				</div>
			</div>

		</form>
	</div>
</div>
