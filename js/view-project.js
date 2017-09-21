$(function() {

//====================-- set notes height --==============================//
	var notesTop = $('#notes_content').offset().top;
	var noteHeight = $(window).height() - notesTop - 15;
	$('#notes_content').outerHeight(noteHeight);
//====================-- end set notes height --==============================//
	

//====================-- adding a note --=====================================//

	$('#add_note').click(function(){
		$('#add_note_div').slideToggle();
	});

	$('#cancel_note').click(function(){
		$('#note_text').val('');
		$('#add_note_div').slideToggle();
	});

	$('#submit_note').click(function(){

		noteText = $('#note_text').val();

		$.ajax({ url: '/inc/add-note.php',
		         type: "POST",
		         dataType:'json',
		         data: ({note: noteText}),
		         success: function(data) {
		                      alert(data);
		        }
		});
	});
//====================-- end adding a note --==================================//

});