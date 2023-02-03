<!-- wscms/pages/list.tpl.php v.3.5.3. 04/10/2018 -->
<div class="row">
	<div class="col-md-3 new">
 		<a href="{{ URLSITEADMIN }}{{ CoreRequest.action }}/newItem" title="{{ Lang['inserisci una nuova pagina']|capitalize }}" class="btn btn-primary">{{ Lang['nuova pagina']|capitalize }}</a>
	</div>
	<div class="col-md-7 help-small-list">
		{% if App.params.help_small is defined and App.params.help_small != '' %}{{ App.params.help_small }}{% endif %}
	</div>
	<div class="col-md-2">
	</div>
</div>
<hr class="divider-top-module">
<div class="row">
	<div class="col-md-12">			
		<div class="table-responsive">									
			<table class="table table-striped table-bordered table-hover listData tree">
				<thead>
					<tr>
						<th></th>
						{% if App.userLoggedData.is_root == 1 %}
							<th class="id"></th>						
						{% endif %}		
						<th class="ordering">{{ Lang['ordine abb']|capitalize }}</th>		
						<th>{{ Lang['titolo']|capitalize }}</th>														
						<th>{{ Lang['template']|capitalize }}</th>
						<th>{{ Lang['tipo']|capitalize }}</th>
						<th>{{ Lang['alias']|capitalize }}</th>
						<th>{{ Lang['menu']|capitalize }}</th>
						<th><small>{{ Lang['contenuti']|capitalize }}</small></th>
						<th><small>{{ Lang['immagini']|capitalize }}</small></th>
						<th><small>{{ Lang['associati']|capitalize }}</small></th>
						<th>{{ Lang['SEO'] }}</th>																
						<th><small>{{ Lang['anteprima']|capitalize }}</small></th>						
						<th></th>						
					</tr>
				</thead>
				<tbody>
					{% if App.items is iterable and App.items|length > 0 %}
						{% for key,value in App.items %}
							<tr class="treegrid-{{ value.id }}{% if value.parent > 0 %} treegrid-parent-{{ value.parent }}{% endif %}" valign="top">
								<td class="tree-simbol"></td>
								{% if App.userLoggedData.is_root == 1 %}
									<td class="id">{{ value.id }}-{{ value.parent }}</td>
								{% endif %}													
								<td class="ordering">
									{% if App.userLoggedData.is_root is defined and App.userLoggedData.is_root == 1 %}
										<small>{{ value.ordering }}&nbsp;</small>
									{% endif %}							
									<a class="" href="{{ URLSITEADMIN }}{{ CoreRequest.action }}/{{ App.params.ordersType['item'] == 'ASC' ? 'less' : 'more' }}OrderingItem/{{ value.id }}" title="{{ Lang['sposta']|capitalize }} {{ App.params.ordersType['item'] == 'DESC' ? Lang['giu'] : Lang['su'] }}"><i class="fa fa-long-arrow-up"> </i></a>
									<a class="" href="{{ URLSITEADMIN }}{{ CoreRequest.action }}/{{ App.params.ordersType['item'] == 'ASC' ? 'more' : 'less' }}OrderingItem/{{ value.id }}" title="{{ Lang['sposta']|capitalize }} {{ App.params.ordersType['item'] == 'DESC' ? Lang['su'] : Lang['giu'] }}"><i class="fa fa-long-arrow-down"> </i></a>
								</td>																																			
								<td class="page-title" style="white-space: nowrap;">
									<a style="color:#000;" href="{{ URLSITEADMIN }}{{ CoreRequest.action }}/modifyItem/{{ value.id }}" title="{{ Lang['modifica']|capitalize }} {{ Lang['pagina'] }}">{{ value.levelString }}{{ value.title }}</a>								
								</td>
								<td><small>{{ value.template_name }}</small></td>	
								<td><small>{{ Lang['page-type'][value.type] }}</small></td>
								<td><small>{{ value.alias }}</small></td>
								<td>
									<a title="{{ value.menu == 1 ? Lang['visibile nel menu']|capitalize : Lang['nascosta nel menu']|capitalize }}" class="btn btn-default btn-circle" href="javascript:void(0);"><i class="fa {{ value.menu == 1 ? 'fa-eye' : 'fa-eye-slash' }}"> </i></a>
								</td>
								<td>
									{% if value.type == 'default' %}								
										<a class="btn btn-default btn-circle" href="{{ URLSITEADMIN }}{{ CoreRequest.action }}/listIblo/{{ value.id }}" title="{{ Lang['contenuti associati']|capitalize }} ({{ value.blocks }})"><i class="fa fa-file-text-o"> </i></a>									
									{% endif %}								
								</td>
								<td>	
									{% if value.filename != '' %}
									<a class="" href="{{ App.params.uploadDirs['item'] }}{{ value.filename }}" data-lightbox="image-1" data-title="{{ value.org_filename }}" title="{{ value.org_filename }}">
										<img  class="img-thumbnail"  src="{{ App.params.uploadDirs['item'] }}{{ value.filename }}" alt="{{ value.org_filename }}">
									</a>
									{% else %}
									<img  class="img-thumbnail"  src="{{ App.params.uploadDirs['item'] }}default/image.png" alt="{{ Lang['immagine di default']|capitalize }}">										
									{% endif %}
									
									
								</td>
								<td>		
									{% if value.type == 'default' %}
										<!-- 						
										<a class="btn btn-default btn-circle" href="{{ URLSITEADMIN }}{{ CoreRequest.action }}/listIimg/{{ value.id }}" title="{{ Lang['immagini associate']|capitalize }} ({{ value.images }})"><i class="fa fa-picture-o"> </i></a><small>({{ value.images }})</small>																
										-->								
										<a class="btn btn-default btn-circle" href="{{ URLSITEADMIN }}{{ CoreRequest.action }}/listIfil/{{ value.id }}" title="{{ Lang['files associati']|capitalize }} ({{ value.files }})"><i class="fa fa-file-o"> </i></a><small>({{ value.files }})</small>																
										<!-- 										
										<a class="btn btn-default btn-circle" href="{{ URLSITEADMIN }}{{ CoreRequest.action }}/listIgal/{{ value.id }}" title="{{ Lang['galleria associata']|capitalize }} ({{ value.gallery }}  {{ Lang['immagini'] }})"><i class="fa fa-film"> </i></a><small>({{ value.imagesgallery }})</small>											
										<a class="btn btn-default btn-circle" href="{{ URLSITEADMIN }}{{ CoreRequest.action }}/listIvid/{{ value.id }}" title="{{ Lang['video associati']|capitalize }} ({{ value.videos }})"><i class="fa fa-youtube-play"> </i></a><small>({{ value.videos }})</small>																													
	 									-->							
 									{% endif %}
 								</td>
 								<td>
 									{% if value.type == 'default' %}	
										<a class="btn btn-primary btn-circle" href="{{ URLSITEADMIN }}{{ CoreRequest.action }}/modifySeoItem/{{ value.id }}" title="{{ Lang['modifica']|capitalize }} {{ Lang['tag seo'] }}"><i class="fa fa-edit"> </i></a>
									{% endif %}								
								</td>									
								<td>
									{% if value.type == 'default' %}							
										<a class="btn btn-default btn-circle" href="{{ URLSITE }}pages/{{ value.id }}/{{ GlobalSettings['site code key'] }}/aprew" target="_blank"><i class="fa fa-eye"> </i></a>
									{% endif %}									
								</td>										
								<td class="actions">
									<a class="btn btn-default btn-circle" href="{{ URLSITEADMIN }}{{ CoreRequest.action }}/{{ value.active == 1 ? 'disactive' : 'active' }}Item/{{ value.id }}" title="{{ value.active == 1 ? Lang['disattiva']|capitalize : Lang['attiva']|capitalize }} {{ Lang['pagina'] }}"><i class="fa fa-{{ value.active == 1 ? 'unlock' : 'lock' }}"> </i></a>			 
									<a class="btn btn-default btn-circle" href="{{ URLSITEADMIN }}{{ CoreRequest.action }}/modifyItem/{{ value.id }}" title="{{ Lang['modifica']|capitalize }} {{ Lang['pagina'] }}"><i class="fa fa-edit"> </i> </a>
									<a class="btn btn-default btn-circle confirm" href="{{ URLSITEADMIN }}{{ CoreRequest.action }}/deleteItem/{{ value.id }}" title="{{ Lang['cancella']|capitalize }} {{ Lang['pagina'] }}"><i class="fa fa-cut"> </i></a>										
								</td>
     						</tr> 
						{% endfor %}
					{% else %}
						<tr>
							{% if App.userLoggedData.is_root == 1 %}<td></td>{% endif %}
							<td colspan="12">{{ Lang['Nessuna voce trovata!']|capitalize }}</td>
						</tr>
					{% endif %}
				</tbody>       
			</table>
				
		</div>
		<!-- /.table-responsive -->
	</div>
</div>
<!-- /.col-md-12 -->