$(function() {

	// set user info on edit users modal //
	$('.edit_user').click(function(){
		id = $(this).attr("data-uid");
		fName = $(this).attr("data-fname");
		lName = $(this).attr("data-lname");
		uName = $(this).attr("data-uname");
		email = $(this).attr("data-email");
		access = $(this).attr("data-uacces");
		SetEditForm(id,fName,lName,uName,email,access);
	});
	function SetEditForm(id,fName,lName,uName,email,access){
		$('#uId').val(id);
		$('#inputfName').val(fName);
		$('#inputlName').val(lName);
		$('#inputUName').val(uName);
		$('#inputEmail').val(email);
		$('#inputaccess').val(access).prop('selected', true);
		$('#inputPassword').val('');

	}
	$(document).on('click', '#del_user', function () {

	    id = $(this).attr("data-uid");
		fName = $(this).attr("data-fname");
		lName = $(this).attr("data-lname");

		SetDeleteForm(id,fName,lName);

	});
	function SetDeleteForm(id,fName,lName){
		$('#duId').val(id);
		$('#del_f_name').text(fName);
		$('#del_l_name').text(lName);
	}


	 // UPLOAD CLASS DEFINITION
    // ======================
    if($('#js-upload-form').length > 0){
    var dropZone = document.getElementById('drop-zone');
    var uploadForm = document.getElementById('js-upload-form');

    var UploadFiles = function(files) {
        console.log(files[0]);
        var extensions = ["image/vnd.adobe.photoshop","application/x-photoshop","application/photoshop","application/psd","image/psd", "image/gif", "image/jpg", "image/jpeg", "text/plain", "image/png", "application/pdf", "application/octet-stream"];
        var mxfilesize = 3000000;
        var msg = [];
        var legitFiles = 0;

        for(i=0;i<files.length;i++){
        	tFile = files[i];
        	if(extensions.includes(files[i].type)){
        		if(files[i].size <= mxfilesize){
        			
        			legitFiles ++;
        			
        		}else{
        			$('#uploadmsg').append('<div class="alert alert-danger"><strong>'+files[i].name+'\'s</strong> file size is too large</div>');
        		}
				
        	}else{
        		$('#uploadmsg').append('<div class="alert alert-danger"><strong>'+files[i].name+'</strong> has an unsupported file extension.</div>');
        		
        		
        	}
        }
        	
        	if(legitFiles == files.length){
        		var formData = new FormData();
		        var xhr = new XMLHttpRequest();
		        var x;
				var progressBar  = $('.progress-bar');
		        for(x=0;x<files.length;x++){
		        	formData.append('files[]', files[x]);
		        }

		        xhr.onload = function(){
		        	
		        	if (this.readyState == 4 && this.status == 200) {
		        		var data = this.responseText;
		        		var upfiles = JSON.parse(data);
		        		var furl = [];
		        		for (var i in upfiles) {
		        			
		        			$('#uploadmsg').append('<div class="alert alert-success"><strong>'+upfiles[i].name+'</strong> has been uploaded.</div>');
		        				hfval = $('#dd_ufiles').val();
		        				if(hfval != ''){
		        					$('#dd_ufiles').val(document.location.origin + '/' + upfiles[i].file + ',' + hfval);	
		        				}else{
		        					$('#dd_ufiles').val(document.location.origin + '/' + upfiles[i].file);
		        				}
		        		}
		        		
		        	}else{

		        	}
		        }
		        xhr.upload.onprogress = function (e) {
				    if (e.lengthComputable) {
				        progressBar.max = e.total;
				        $(progressBar).css('width', e.loaded+'%');
				    }
				}

				xhr.upload.onloadstart = function (e) {
				    $(progressBar).css('width', '0%');
				}
				xhr.upload.onloadend = function (e) {
				    $(progressBar).css('width', '100%');
				}

		        xhr.open('post', 'upload-dd.php');
		        xhr.send(formData);
        	}


    }

    dropZone.ondrop = function(e) {
        e.preventDefault();
        this.className = 'upload-drop-zone';

        UploadFiles(e.dataTransfer.files);
    }

    dropZone.ondragover = function() {
        this.className = 'upload-drop-zone drop';
        return false;
    }

    dropZone.ondragleave = function() {
        this.className = 'upload-drop-zone';
        return false;
    }
	}
});