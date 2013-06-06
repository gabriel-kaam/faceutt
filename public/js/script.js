function ajaxBeforesend() {
	jQuery('*').addClass('loadingCur');
}

function ajaxAlways() {
	jQuery('*').removeClass('loadingCur');
}

function ajaxError() {
	addMessage('Ooops, I made a boo boo :(', 'error');
}

function addMessage(message, level) {
	jQuery('<div class="alert alert-'+level+'"><a class="close" data-dismiss="alert">×</a><strong>'+level+'!</strong> '+message+'</div>').hide().appendTo('#messages').fadeIn().delay(2500).fadeOut();
}

function makeMyBind() {
	jQuery('.btn-update-relation').on('click', function(e) {
		var o = this;
		jQuery.ajax({
			url: '/relation'
			,type: 'post'
			,data: jQuery(o).data()
			,dataType: 'json'
			,beforeSend: ajaxBeforesend
		}).done(function(d,t,p) {
			if(d.success) {
				var add = (jQuery(o).data('action') == 'add');
				if(add)
					jQuery(o).addClass('btn-primary');
				else
					jQuery(o).removeClass('btn-primary');
				jQuery(o).data('action', add ? 'del' : 'add');
				addMessage(d.message, 'success');
			} else addMessage(d.message, 'error');
		}).fail(ajaxError).always(ajaxAlways);

		e.preventDefault();
	});

	// bind on a. coz button. shoudlnt trigger!
	jQuery('a.btn-update-parameterV').on('click', function(e) {
		var o = this;
		if(!jQuery(o).hasClass('btn-primary'))
			jQuery.ajax({
				url: '/parameter/updateV'
				,type: 'post'
				,data: jQuery(o).data()
				,dataType: 'json'
				,beforeSend: ajaxBeforesend
			}).done(function(d,t,p) {
				if(d.success) {
					jQuery(o).parents('.btn-group-update-parameterV').find('a.btn-primary').removeClass('btn-primary');
					jQuery(o).addClass('btn-primary');
					jQuery(o).parents('.btn-group-update-parameterV').find('button.btn-update-parameterV .t').text(jQuery(o).data('value'));
					jQuery(o).parents('dt').removeClass(function (index, css) {
					    return (css.match (/\bvisibility-\S+/g) || []).join(' ');
					}).addClass('visibility-'+jQuery(o).data('value'));
					addMessage(d.message, 'success');
				} else addMessage(d.message, 'error');
			}).fail(ajaxError).always(ajaxAlways);

		e.preventDefault();
	});

	jQuery('#myProfile .btn-update-parameter').on('click', 'span', function(e) {
		var o = this;
		var p = jQuery(o).parent();
		jQuery(p).html('<form class="form-search"><div class="input-append"><input type="hidden" name="id" value="'+jQuery(o).parent().data('id')+'" /><input type="hidden" name="old_value" value="'+jQuery(o).text()+'" /><input type="text" name="value" value="'+jQuery(o).text()+'" class="btn-small search-query" /><input type="submit" value="Valider" class="btn btn-small" /></div></form>');
		jQuery(p).find('input[name=value]').focus();
		e.preventDefault();
	});

	jQuery('html').on('click', function(e) {
		jQuery('#myProfile .btn-update-parameter form').each(function (i,v) {
			var o = this;
			var p = jQuery(o).parent();
	
			jQuery(p).html('<span>'+jQuery(p).find('input[name=old_value]').val()+'</span>');
		});
	});
	
	jQuery('.btn-update-parameter').on('click', function(e) {
		e.stopPropagation();
	});

	jQuery('.btn-update-parameter').on('submit', 'form', function(e) {
		var o = this;
		jQuery.ajax({
			url: '/parameter/update'
			,type: 'post'
			,data: jQuery(o).serialize()
			,dataType: 'json'
			,beforeSend: ajaxBeforesend
		}).done(function(d,t,p) {
			if(d.success) {
				addMessage(d.message, 'success');
				jQuery(o).parent().html('<span>'+jQuery(o).find('input[name=value]').val()+'</span>');
			} else addMessage(d.message, 'error');
		}).fail(ajaxError).always(ajaxAlways);

		e.preventDefault();
	});
	
	jQuery('.update-skill,.update-photo').on('click', '.delete', function(e) {
		var o = this;
		var u = jQuery(o).parent();
		jQuery.ajax({
			url: jQuery(o).attr('href')
			,type: 'get'
			,dataType: 'json'
			,beforeSend: ajaxBeforesend
		}).done(function(d,t,p) {
			if(d.success) {
				jQuery(u).remove();
				addMessage(d.message, 'success');
			} else addMessage(d.message, 'error');
		}).fail(ajaxError).always(ajaxAlways);

		e.preventDefault();
	});
	
	jQuery('.update-photo').on('submit', 'form', function(e) {
		var o = this;
		var u = jQuery(o).parent();
		jQuery.ajax({
			url: jQuery(o).attr('action')
			,type: jQuery(o).attr('method')
			,enctype: 'multipart/form-data'
			,data: new FormData(jQuery(o).get(0))
			,dataType: 'json'
			,beforeSend: ajaxBeforesend
			,cache: false
			,contentType: false
			,processData: false
		}).done(function(d,t,p) {
			if(d.success) {
				jQuery(u).find('.empty').remove();
				jQuery(u).find('ul').append('<li><a href="/uploads/'+d.id+'" target="_blank">Image #'+d.id+'</a><a href="/parameter/photo/del/'+d.id+'" class="btn btn-danger btn-small delete">Supprimer</a></li>');
				jQuery(u).find('.file-input-name').remove();
				jQuery(o).get(0).reset();
				addMessage(d.message, 'success');
			} else addMessage(d.message, 'error');
		}).fail(ajaxError).always(ajaxAlways);

		e.preventDefault();
	});
	
	jQuery('.update-skill').on('submit', 'form', function(e) {
		var o = this;
		var u = jQuery(o).parent();
		jQuery.ajax({
			url: jQuery(o).attr('action')
			,type: jQuery(o).attr('method')
			,data: jQuery(o).serialize()
			,dataType: 'json'
			,beforeSend: ajaxBeforesend
		}).done(function(d,t,p) {
			if(d.success) {
				jQuery(u).find('.empty').remove();
				jQuery(u).find('ul').append('<li>'+jQuery(o).find('input[name=value]').val()+'<a href="/parameter/skill/del/'+d.id+'" class="btn btn-danger btn-small delete">Supprimer</a></li>');
				jQuery(o).find('input[name=value]').val('').focusout();
				addMessage(d.message, 'success');
			} else addMessage(d.message, 'error');
		}).fail(ajaxError).always(ajaxAlways);

		e.preventDefault();
	});
}

jQuery(document).ready(function() {
	makeMyBind();
});
