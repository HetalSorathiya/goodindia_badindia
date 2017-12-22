// Create our Firebase reference
var cueListRef = new Firebase(FIRE_BASE_APP_LINK + 'cuelist/');

var questionPostRef = new Firebase(FIRE_BASE_APP_LINK + 'questionpost/');

$('#cuetablelist').dataTable({
    "sPaginationType": "full_numbers",
    aaSorting: [[0, 'desc']],
    "aoColumnDefs": [
        {'bSortable': false, 'aTargets': [2, 3, 4]}
    ]
});

$( document.body ).on( "click",'.saveanswer', function() {
    var q_id = $(this).attr('q_id');
    var q_u_id = $(this).attr('q_u_id');
    var q_url_id = $(this).attr('q_url_id');
    var answer = $(this).parents('.question-cnt').find('.answer').val();

    var postdata = {};
    postdata['q_id'] = q_id;
    postdata['q_answer'] = answer;
    postdata['q_u_id'] = q_u_id;
    postdata['q_url_id'] = q_url_id;


    var obj = $(this);

    if (answer === '') {
        obj.parents('.question-cnt').find('.field-box').addClass('error');
        obj.parents('.question-cnt').find('.error-cnt').removeClass('display-none');
        setTimeout(function() {
            // Do something after 2 seconds
            obj.parents('.question-cnt').find('.field-box').removeClass('error');
            obj.parents('.question-cnt').find('.error-cnt').addClass('display-none');
        }, 3000);
        return false;
    }

    jQuery.post(ADMIN_BASE_PATH + '/cue/updatequestion', postdata, function(result) {
        obj.parents('.question-cnt').find('.field-box').addClass('success');
        obj.parents('.question-cnt').find('.success-cnt').removeClass('display-none');
        setTimeout(function() {
            // Do something after 2 seconds
            obj.parents('.question-cnt').find('.field-box').removeClass('success');
            obj.parents('.question-cnt').find('.success-cnt').addClass('display-none');
        }, 3000);
    });
    return false;
});

$( document.body ).on( "click",'.present', function() {
    var hashtagname = $('#hashtagname').val();
    if (hashtagname === '') {
        return false;
    }

    var urlRef = cueListRef.child(hashtagname);

    var q_id = $(this).attr('q_id');
    var question = $(this).parents('.question-cnt').find('.question').html();
    var answer = $(this).parents('.question-cnt').find('.answer').val();
    urlRef.set({question: question, answer: answer, q_id: q_id});

    return false;
});

//Logic for updating the question directly

var hashtagname = $('#hashtagname').val();

if (hashtagname === '') {

} else {
    var questionRef = questionPostRef.child(hashtagname);
    questionRef.on('value', function(snapshot) {
        if (snapshot.val() === null) {
            console.log("No such question till now yet");
        } else {
            var q_url_id = $('#q_url_id').val();

            var message = snapshot.val();
            var q_id = message.q_id;
            var q_u_id = message.q_u_id;
            var question = message.question;
            var question_ids = eval(question_id_array);
            if (in_array(q_id, question_ids)) {
            } else {
                question_ids.push(parseInt(q_id));
                
                //Code to Insert table over here
                var classType = '';
                var hasOddClass = $("#cuetablelist tbody tr").first().hasClass("odd");
                if (hasOddClass) {
                    classType = 'even';
                } else {
                    classType = 'odd';
                }


                var tr_row =
                        '<tr class="question-cnt '+classType+'">\n\
                            <td class="question_id display-none  sorting_1" q_id="' + q_id + '">' + q_id + '</td>\n\
                            <td class="question " q_id="' + q_id + '">' + question + '</td>\n\
                            <td class="center ">\n\
                                <div class="field-box">\n\
                                    <input type="text" value="" class="answer span8" q_id="' + q_id + '">\n\
                                    <span class="alert-msg error-cnt display-none">\n\
                                        <i class="icon-remove-sign"></i> Answer cannot be blank\n\
                                    </span>\n\
                                    <span class="alert-msg success-cnt display-none">\n\
                                        <i class="icon-ok-sign"></i> Answer updated successfully\n\
                                    </span>\n\
                                </div>\n\
                            </td>\n\
                            <td class="center ">\n\
                                <a q_id="' + q_id + '" q_u_id="'+q_u_id+'" q_url_id="' + q_url_id + '" class="btn-flat success saveanswer">Save</a>\n\
                            </td>\n\
                            <td class="center ">\n\
                                <a class="btn-flat success present" q_id="' + q_id + '">Present</a>\n\
                            </td>\n\
                        </tr>';
                $("#cuetablelist tbody").prepend(tr_row);
                $('.dataTables_empty').parents('tr').remove();
            }
        }
    });

}

function in_array(item, arr) {
    if (!arr) {
        return false;
    } else {
        for (var p = 0; p < arr.length; p++) {
            if (item == arr[p]) {
                return true;
            }
        }
        return false;
    }
}


