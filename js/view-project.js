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

		var noteText = $('#note_text').val();
		var uid = $('#submit_note').attr('data-user');
		var assigned = $('#submit_note').attr('data-assigned');
		var pid = $('#submit_note').attr('data-project-id');

		$('#note_text').val('');
		$('#add_note_div').slideToggle();

		$.ajax({ url: 'inc/add-note.php',
		         type: "POST",
		         data: ({note: noteText,userId: uid, assignedTo: assigned, projectId:pid}),
		         success: function(response) {
		         	// console.log(response);
					var newNote = JSON.parse(response);
					var date = newNote[0];
					var tempNote = newNote[1];
					var html = '<p style="padding:20px;" class="col-lg-10 card-text my_note bg-success new_note"><strong>Me</strong> - Today at ';
					html += date;
					html += '<br>';
					html += tempNote;
					html += '</p>';

					$('#notes').prepend(html);
					$('.new_note').slideToggle();
					$('.new_note').removeClass('new_note');


		        }
		});
	});
//====================-- end adding a note --==================================//

});