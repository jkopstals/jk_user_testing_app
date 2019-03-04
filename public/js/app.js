$(document).ready(function() {
    $('#option_submit').click(function(e) {
        e.preventDefault();
        var option_uuid = $('form input[name=option_uuid]:checked').val();

        if(option_uuid === undefined && !$('#option_error').length) {
            $( "#option_select" ).before( '<div class="error" id="option_error">Lai turpinātu, jāizvēlas kāda no atbildēm</div>' );
        }
        
        if (typeof(option_uuid) != "undefined" && option_uuid !== null) {
            $.ajax({
                url: "/api/answers/",
                method: "POST",
                data: $('#option_form').serialize(),
                beforeSend:function() {
                    $('#response').html('<span class="text-info">Loading</span>');
                },
                success: function(response) {
                    console.log(response);
                    if (response.success == true && response.next_question == null) {
                        //last question
                        $('#option_form').submit();
                    }
                    $('form').trigger('reset');
                    if (typeof(response.next_question) != "undefined" && response.next_question !== null) {
                        $('#title').text(response.next_question.title);
                        $('#respondent_uuid').val(response.next_question.respondent_uuid);
                        $('#test_uuid').val(response.next_question.test_uuid);
                        $('#question_uuid').val(response.next_question.question_uuid);
                        $('#option_select').empty();
                        $.each(response.next_question.options, function(key, value) {
                            option_html = '<div class="col"><div class="box radiobox"><input type="radio" name="option_uuid" id="option_' +
                            value.uuid + '" value="' + value.uuid + '">'+value.label+'</div></div>';
                            $('#option_select').append(option_html);
                        });
                        $('#progressbar_percent').css('width', response.progress_percent + '%');
                        $('#option_error').remove();
                    }
                }
            })
        }
    });
});