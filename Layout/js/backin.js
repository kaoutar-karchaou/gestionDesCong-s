$(function(){
'use strict';
// 
// $("select").selectBoxIt({
//   autoWidth:false
// });
$(function () {
  $('[data-toggle="tooltip"]').tooltip()
})
//hide placeholder on form focus
$("[placeholder]").focus(function(){
  $(this).attr('data-text',$(this).attr("placeholder"));
  $(this).attr('placeholder','');

}).blur(function(){
    $(this).attr('placeholder',$(this).attr('data-text'));
});

// add the star
$('input').each(function(){
  if($(this).attr('required')=='required'){
  	$(this).after('<span class="star-input"> * </span>');
  }
});
//show  password  
var pass = $('.password');
$('.fa-pass').hover(function(){
   pass.attr('type','text');
},function(){
	pass.attr('type','password');
});
//add confirm message
$('.confirmm').click(function () {
   return  confirm('Voulez-vous vraiment Annuler Le Demande?');
 });

$('.confirm').click(function () {
   return  confirm('Voulez-vous vraiment supprimer cet élément?');
 });



// classic  view   and  Full  view 
$('.cats .classic-view').click(function(){
   $(this).next('.full-view').fadeToggle(500);
});
$('.option  span').click(function(){
	
    $(this).addClass('active').siblings('span').removeClass('active');
    if($(this).data('view') === 'Full'){
       $(".full-view").fadeIn();
    }

    else{
        $(".full-view").fadeOut();
    }
});

// start uplaod  image 





});



function readURL(input) {
  if (input.files && input.files[0]) {

    var reader = new FileReader();

    reader.onload = function(e) {
      $('.image-upload-wrap').hide();

      $('.file-upload-image').attr('src', e.target.result);
      $('.file-upload-content').show();

      $('.image-title').html(input.files[0].name);
    };

    reader.readAsDataURL(input.files[0]);

  } else {
    removeUpload();
  }
}

function removeUpload() {
  $('.file-upload-input').replaceWith($('.file-upload-input').clone());
  $('.file-upload-content').hide();
  $('.image-upload-wrap').show();
}
$('.image-upload-wrap').bind('dragover', function () {
    $('.image-upload-wrap').addClass('image-dropping');
  });
  $('.image-upload-wrap').bind('dragleave', function () {
    $('.image-upload-wrap').removeClass('image-dropping');
});



   $(document).ready(function() {
    $('#form-edit').formValidation({
        framework: 'bootstrap',
        icon: {
            // valid: 'glyphicon glyphicon-ok',
            // invalid: 'glyphicon glyphicon-remove',
            validating: 'glyphicon glyphicon-refresh'
        },
        fields: {
            title: {
               
                validators: {
                    notEmpty: {
                        message: 'Ce champs est obligtoire'
                    }
                }
            },
            auteurs: {
               
                validators: {
                    notEmpty: {
                        message: 'Ce champs est obligtoire'
                    }
                }
            },
          
          
            Description: {
            
                validators: {
                    notEmpty: {
                        message: 'Ce champs est obligtoire'
                    }
                }
            },
          
           
            
            
        }
    });
});


  $(document).ready(function() {
    $('#form-cours').formValidation({
        framework: 'bootstrap',
        icon: {
            // valid: 'glyphicon glyphicon-ok',
            // invalid: 'glyphicon glyphicon-remove',
            validating: 'glyphicon glyphicon-refresh'
        },
        fields: {
            title: {
               
                validators: {
                    notEmpty: {
                        message: 'Ce champs est obligtoire'
                    }
                }
            },
            professeur: {
               
                validators: {
                    notEmpty: {
                        message: 'Ce champs est obligtoire'
                    }
                }
            },
          
          
            filiere: {
            
                validators: {
                    notEmpty: {
                        message: 'Ce champs est obligtoire'
                    }
                }
            },
            semestre: {
            
                validators: {
                    notEmpty: {
                        message: 'Ce champs est obligtoire'
                    }
                }
            },
          

          file_s: {
            
                validators: {
                    notEmpty: {
                        message: 'Ce champs est obligtoire'
                    }
                }
            },
          
          
           
            
            
        }
    });
});
