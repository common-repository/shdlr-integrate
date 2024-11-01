jQuery(document).ready(function($){
	
	var valid_token = '';

	validate_fetch(false);

	$('#validate').on('click', function(){
		validate_fetch(true);
	});

	function validate_fetch(validate){
		if(!valid_token){
			$('#preloader').show();
			$('#conf_detail').hide();
		}
		token = $('#token').val();
		if(token == 'demo-token'){
			data = [{Conference:{name:'PHP Nagpur Conference', url:'demo'}}];
			if(validate){
				$('#message').hide();
				$('#message').addClass('updated').removeClass('error');
				$('#message').html('<p><b>Token successfully validated</b></p>');
				$('#message').fadeIn(1000);
			}	
			$.post( "admin-ajax.php", { action: "validate_conf", id:token}, function() {
						update_detail(data);
						valid_token = token;
					});
			return;
		}
		url = 'http://shdlr.com/conferences/check_token/'+token;
		$.get( url, function( data ) {
			$('#message').hide();
			if(data.success == true){
				if(validate){
					$('#message').addClass('updated').removeClass('error');
					$('#message').html('<p><b>'+data.message+'</b></p>');
					$('#message').fadeIn(1000);
					$.post( "admin-ajax.php", { action: "validate_conf", id:token}, function() {
						update_detail(data.data);
						valid_token = token;
					});
				} else {
					update_detail(data.data);
				}
			} else {
				$('#message').addClass('error').removeClass('updated');
				$('#message').html('<p><b>'+data.message+'</b></p>');
				$('#preloader').hide();
				$('#message').fadeIn(1000);
				if(valid_token){
					$('#token').val(valid_token);
				}
				
			}
		}, "jsonp" );
	}

	function update_detail(data){
		$('#conf_name').html(data[0].Conference.name);
		$('#shdlr_shortcode').html("[shdlr conf_id='"+data[0].Conference.url+"']");
		$('#chk_css, #chk_legend').prop('checked', 'checked');
		$('#preloader').hide();
		$('#conf_detail').fadeIn(500);
	}

	$('#conf_detail').on( 'change', '#conf_type', function(){
			shortcode = $('#shdlr_shortcode').html();
			shortcode = shortcode.replace(/ type=\'[a-z]+[\_]*[a-z]+\'/, "");
			switch($('#conf_type').val()){
				case '1':
					shortcode = shortcode.replace("]", " type='grid_popup']");
					break;
				case '2':
					shortcode = shortcode.replace("]", " type='list']");
					break;
				case '3':
					shortcode = shortcode.replace("]", " type='list_popup']");
					break;
				case '4':
					shortcode = shortcode.replace("]", " type='simple']");
					break;
				case '5':
					shortcode = shortcode.replace("]", " type='simple_popup']");
					break;
				case '6':
					shortcode = shortcode.replace("]", " type='talks']");
					break;
				case '7':
					shortcode = shortcode.replace("]", " type='talks_popup']");
					break;
				default :
					break;	
			}
			$('#shdlr_shortcode').html(shortcode);
		});

function SelectText(element) {
    var doc = document
        , text = doc.getElementById(element)
        , range, selection
    ;    
    if (doc.body.createTextRange) {
        range = document.body.createTextRange();
        range.moveToElementText(text);
        range.select();
    } else if (window.getSelection) {
        selection = window.getSelection();        
        range = document.createRange();
        range.selectNodeContents(text);
        selection.removeAllRanges();
        selection.addRange(range);
    }
}

$(function() {
    $('#conf_detail').on( 'click', 'code', function(){
        SelectText($(this).attr('id'));
    });
});
});

